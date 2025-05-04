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

        GroupSyncManager::getInstance()->broadcastChange(
            $user,
            NamelessMCGroupSyncInjector::class,
            [$user->getMainGroup()->id]
        );

        Session::flash('login_success', $language->get('user', 'validation_complete'));
        Redirect::to(URL::build('/login'));
    } else {
        Session::flash('home_error', $language->get('user', 'validation_error'));
    }

    Redirect::to(URL::build('/'));
} else if (Session::exists('validate_email')) {
    $email_address = Session::get('validate_email');

    // Handle input
    if (Input::exists()) {
        if (Token::check()) {

            $target_user = new User($email_address, 'email');
            if (Input::get('action') == 'change_email') {
                // Change email
                $validation = Validate::check($_POST, [
                    'email' => [
                        Validate::REQUIRED => true,
                        Validate::EMAIL => true,
                        Validate::UNIQUE => ['users', 'id:' . $target_user->data()->id],
                    ]
                ])->messages([
                    'email' => [
                        Validate::REQUIRED => $language->get('user', 'email_required'),
                        Validate::EMAIL => $language->get('general', 'contact_message_email'),
                        Validate::UNIQUE => $language->get('user', 'email_already_exists'),
                    ]
                ]);

                if ($validation->passed()) {
                    $target_user->update([
                        'email' => Input::get('email')
                    ]);

                    // Generate validation code
                    $code = SecureRandom::alphanumeric();

                    if (Core_Emails::sendRegisterEmail($language, Input::get('email'), $target_user->data()->username, $target_user->data()->id, $code)) {
                        Session::flash('validate_success', $language->get('admin', 'email_resent_successfully'));
                    } else {
                        Session::flash('validate_error', $language->get('admin', 'email_resend_failed'));
                    }

                    Session::put('validate_email', Output::getClean(Input::get('email')));
                    Redirect::to(URL::build('/validate'));
                } else {
                    $errors = $validation->errors();
                }

            } else if (Input::get('action') == 'resend_email') {
                // Resend email
            }
        } else {
            // Invalid form token
            Session::flash('settings_error', $language->get('general', 'invalid_token'));
        }
    }

    $template->getEngine()->addVariables([
        'VALIDATE_EMAIL' => $language->get('user', 'validate_email'),
        'VALIDATE_EMAIL_INFO' => $language->get('user', 'validate_email_info', [
            'email' => Output::getClean(Session::get('validate_email'))
        ]),

        'CHANGE_EMAIL' => $language->get('user', 'change_email_address'),
        'CANCEL' => $language->get('general', 'cancel'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'EMAIL_ADDRESS_VALUE' => Output::getClean($email_address),
    ]);
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
