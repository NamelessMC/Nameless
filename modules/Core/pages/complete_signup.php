<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  API signup completion
 */

$page = 'complete_signup';
const PAGE = 'complete_signup';
$page_title = $language->get('general', 'register');

require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

// Validate code
if (!isset($_GET['c'])) {
    Redirect::to(URL::build('/'));
}

// Ensure API is enabled
if (Util::getSetting('use_api') !== '1') {
    die('API is disabled');
}

if (!$user->isLoggedIn()) {
    $target_user = new User($_GET['c'], 'reset_code');
    if ($target_user->exists()) {
        if (Input::exists()) {
            if (Token::check()) {
                // Validate input
                $validation = Validate::check($_POST, [
                    'password' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 6
                    ],
                    'password_again' => [
                        Validate::MATCHES => 'password'
                    ],
                    't_and_c' => [
                        Validate::REQUIRED => true,
                        Validate::AGREE => true
                    ]
                ])->messages([
                    'password' => [
                        Validate::REQUIRED => $language->get('user', 'password_required'),
                        Validate::MIN => $language->get('user', 'password_minimum_6')
                    ],
                    'password_again' => $language->get('user', 'passwords_dont_match'),
                    't_and_c' => $language->get('user', 'accept_terms')
                ]);

                if ($validation->passed()) {
                    // Complete registration
                    // Hash password
                    $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);

                    $target_user->update([
                        'password' => $password,
                        'reset_code' => null,
                        'last_online' => date('U'),
                        'active' => true,
                    ]);

                    EventHandler::executeEvent(new UserValidatedEvent(
                        $target_user,
                    ));

                    Session::flash('home', $language->get('user', 'validation_complete'));
                    Redirect::to(URL::build('/'));
                }

                // Errors
                $errors = $validation->errors();

            } else {
                $errors[] = $language->get('general', 'invalid_token');
            }
        }
    } else {
        Session::flash('home', $language->get('user', 'validation_error'));
        Redirect::to(URL::build('/'));
    }
} else {
    Redirect::to(URL::build('/'));
}

// Smarty variables
if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ]);
}

$smarty->assign([
    'REGISTER' => $language->get('general', 'register'),
    'PASSWORD' => $language->get('user', 'password'),
    'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
    'SUBMIT' => $language->get('general', 'submit'),
    'I_AGREE' => $language->get('user', 'i_agree'),
    'AGREE_TO_TERMS' => $language->get('user', 'agree_t_and_c', [
        'linkStart' => '<a href="' . URL::build('/terms') . '">',
        'linkEnd' => '</a>',
    ]),
    'TOKEN' => Token::get()
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate('complete_signup.tpl', $smarty);
