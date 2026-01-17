<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.3.0
 *
 *  License: MIT
 *
 *  User validation
 */

$page = 'validate';
const PAGE = 'validate';
$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['c'])) {
    $user = new User($_GET['c'], 'reset_code');
    if ($user->exists()) {
        $user->update([
            'reset_code' => null,
            'active' => true,
        ]);

        EventHandler::executeEvent(new UserValidatedEvent(
            $user,
        ));

        if (Session::exists('validate_email')) {
            Session::delete('validate_email');
        }

        Session::flash('login_success', $language->get('user', 'validation_complete'));
        Redirect::to(URL::build('/login'));
    } else {
        Session::flash('home_error', $language->get('user', 'validation_error'));
    }

    Redirect::to(URL::build('/'));
} else if (Session::exists('validate_email')) {
    $email_address = Session::get('validate_email');

    $target_user = new User($email_address, 'email');
    if (!$target_user->exists() || $target_user->data()->active) {
        Redirect::to(URL::build('/'));
    }

    // Handle input
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::EMAIL => true,
                    Validate::UNIQUE => ['users', 'id:' . $target_user->data()->id],
                    Validate::RATE_LIMIT => [1, 3600]
                ]
            ])->messages([
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::EMAIL => $language->get('user', 'invalid_email'),
                    Validate::UNIQUE => $language->get('user', 'email_already_exists'),
                    Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta)
                ]
            ]);

            if ($validation->passed()) {
                if (Input::get('email') != $email_address) {
                    // Generate new validation code for new email
                    $code = SecureRandom::alphanumeric();
                    $email = Input::get('email');

                    $target_user->update([
                        'email' => Input::get('email'),
                        'reset_code' => $code
                    ]);
                } else {
                    // Resend validation email
                    $code = $target_user->data()->reset_code;
                    $email = $target_user->data()->email;

                    if (empty($code)) {
                        $code = SecureRandom::alphanumeric();

                        $target_user->update([
                            'reset_code' => $code
                        ]);
                    }
                }

                if (Core_Emails::sendRegisterEmail($language, $email, $target_user->data()->username, $target_user->data()->id, $code)) {
                    Session::flash('validate_success', $language->get('user', 'validate_email_resent', [
                        'email' => Output::getClean($email)
                    ]));
                } else {
                    Session::flash('validate_error', $language->get('user', 'validate_email_failure'));
                }

                Session::put('validate_email', Output::getClean($email));
                Redirect::to(URL::build('/validate'));
            } else {
                $errors = $validation->errors();
            }
        } else {
            // Invalid form token
            Session::flash('settings_error', $language->get('general', 'invalid_token'));
        }
    }

    $success = $language->get('user', 'validate_email_info', [
        'email' => Output::getClean(Session::get('validate_email'))
    ]);

    $template->getEngine()->addVariables([
        'TOKEN' => Token::get(),
        'VALIDATE_EMAIL' => $language->get('user', 'validate_email'),
        'CHANGE_OR_RESEND_EMAIL' => $language->get('user', 'change_or_resend_email'),
        'CANCEL' => $language->get('general', 'cancel'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'EMAIL_ADDRESS_VALUE' => Output::getClean($email_address),
    ]);

    if (Session::exists('validate_success')) {
        $success = Session::flash('validate_success');
    }

    if (Session::exists('validate_error')) {
        $errors = [Session::flash('validate_error')];
    }

    if (isset($success)) {
        $template->getEngine()->addVariables([
            'SUCCESS' => $success,
            'SUCCESS_TITLE' => $language->get('general', 'success')
        ]);
    }

    if (isset($errors) && count($errors)) {
        $template->getEngine()->addVariables([
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        ]);
    }
} else {
    Redirect::to(URL::build('/'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('validate');
