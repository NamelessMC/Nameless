<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forgot password page
 */

const PAGE = 'forgot_password';

$page_title = str_replace('?', '', $language->get('user', 'forgot_password'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Ensure user is not logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
    die();
}

require(ROOT_PATH . '/core/includes/password.php'); // For password hashing

if (!isset($_GET['c'])) {
    // Enter email address form
    if (Input::exists()) {
        if (Token::check()) {
            if (!isset($_POST['email']) || empty($_POST['email'])) {
                $error = $language->get('user', 'email_required');
            } else {
                // Check to see if the email exists
                $target_user = new User(Input::get('email'), 'email');
                if ($target_user->data() && $target_user->data()->active) {
                    // Generate a code
                    $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 60);

                    // Send an email
                    $link = rtrim(Util::getSelfURL(), '/') . URL::build('/forgot_password/', 'c=' . $code);

                    $sent = Email::send(
                        ['email' => Output::getClean($target_user->data()->email), 'name' => $target_user->getDisplayname()],
                        SITE_NAME . ' - ' . $language->get('emails', 'change_password_subject'),
                        str_replace('[Link]', $link, Email::formatEmail('change_password', $language))
                    );

                    if (isset($sent['error'])) {
                        $queries->create('email_errors', [
                            'type' => Email::FORGOT_PASSWORD,
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => $target_user->data()->id
                        ]);

                        $error = $language->get('user', 'unable_to_send_forgot_password_email');
                    }

                    if (!isset($error)) {
                        $target_user->update([
                            'reset_code' => $code
                        ]);
                    }
                }

                $success = $language->get('user', 'forgot_password_email_sent');
            }
        } else {
            $error = $language->get('general', 'invalid_token');
        }
    }

    if (isset($error)) {
        $smarty->assign('ERROR', $error);
    } else {
        if (isset($success)) {
            $smarty->assign('SUCCESS', $success);
        }
    }

    $smarty->assign([
        'FORGOT_PASSWORD' => str_replace('?', '', $language->get('user', 'forgot_password')),
        'FORGOT_PASSWORD_INSTRUCTIONS' => $language->get('user', 'forgot_password_instructions'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ]);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forgot_password.tpl', $smarty);
} else {
    // Check code exists
    $target_user = new User($_GET['c'], 'reset_code');
    if (!$target_user->data()) {
        Redirect::to('/forgot_password');
        die();
    }

    if (Input::exists()) {
        if (Token::check()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'email' => [
                    Validate::REQUIRED => true
                ],
                'password' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 6
                ],
                'password_again' => [
                    Validate::MATCHES => 'password'
                ]
            ])->messages([
                'email' => $language->get('user', 'email_required'),
                'password' => [
                    Validate::REQUIRED => $language->get('user', 'password_required'),
                    Validate::MIN => $language->get('user', 'password_minimum_6')
                ],
                'password_again' => $language->get('user', 'passwords_dont_match')
            ]);

            if ($validation->passed()) {
                if (strcasecmp($target_user->data()->email, $_POST['email']) == 0) {
                    $new_password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);
                    try {
                        $target_user->update([
                            'password' => $new_password,
                            'reset_code' => null
                        ]);

                        Session::flash('login_success', $language->get('user', 'forgot_password_change_successful'));
                        Redirect::to(URL::build('/login'));
                        die();
                    } catch (Exception $e) {
                        $errors = [$e->getMessage()];
                    }
                } else {
                    $errors = [$language->get('user', 'incorrect_email')];
                }
            } else {
                $errors = $validation->errors();
            }
        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }
    }

    if (isset($errors) && count($errors)) {
        $smarty->assign('ERROR', $errors);
    }

    $smarty->assign([
        'FORGOT_PASSWORD' => str_replace('?', '', $language->get('user', 'forgot_password')),
        'ENTER_NEW_PASSWORD' => $language->get('user', 'enter_new_password'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'PASSWORD' => $language->get('user', 'password'),
        'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('change_password.tpl', $smarty);
}
