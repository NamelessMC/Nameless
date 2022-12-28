<?php
declare(strict_types=1);

/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Forgot password page
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 * @var Language $forum_language
 */

use GuzzleHttp\Exception\GuzzleException;

const PAGE = 'forgot_password';

$page_title = str_replace('?', '', $language->get('user', 'forgot_password'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Ensure user is not logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

if (!isset($_GET['c'])) {
    // Enter email address form
    if (Input::exists()) {
        try {
            if (Token::check()) {

                try {
                    $validation = Validate::check([
                        'email' => [
                            Validate::REQUIRED => true,
                            Validate::RATE_LIMIT => [2, 60] // 2 attempts every 60 seconds
                        ]
                    ])->messages([
                        'email' => [
                            Validate::REQUIRED => $language->get('user', 'email_required'),
                            Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta)
                        ]
                    ]);
                } catch (Exception $ignored) {
                }

                if ($validation->passed()) {
                    // Check to see if the email exists
                    try {
                        $target_user = new User(Input::get('email'), 'email');
                    } catch (GuzzleException $ignored) {
                    }
                    if ($target_user->exists() && $target_user->data()->active) {
                        // Generate a code
                        try {
                            $code = SecureRandom::alphanumeric();
                        } catch (Exception $ignored) {
                        }

                        // Send an email
                        $link = rtrim(URL::getSelfURL(), '/') . URL::build('/forgot_password/', 'c=' . urlencode($code));

                        $sent = Email::send(
                            ['email' => $target_user->data()->email, 'name' => $target_user->getDisplayName()],
                            SITE_NAME . ' - ' . $language->get('emails', 'change_password_subject'),
                            str_replace('[Link]', $link, Email::formatEmail('change_password', $language)),
                            Email::getReplyTo()
                        );

                        if (isset($sent['error'])) {
                            DB::getInstance()->insert('email_errors', [
                                'type' => Email::FORGOT_PASSWORD,
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => $target_user->data()->id
                            ]);

                            $error = $language->get('user', 'unable_to_send_forgot_password_email');
                        }

                        if (!isset($error)) {
                            try {
                                $target_user->update([
                                    'reset_code' => $code
                                ]);
                            } catch (Exception $ignored) {
                            }
                        }
                    }

                    $success = $language->get('user', 'forgot_password_email_sent');
                } else {
                    $error = implode('<br />', $validation->errors());
                }
            } else {
                $error = $language->get('general', 'invalid_token');
            }
        } catch (Exception $ignored) {
        }
    }

    if (isset($error)) {
        $smarty->assign([
            'ERROR_TITLE' => $language->get('general', 'error'),
            'ERROR' => $error
        ]);
    } else if (isset($success)) {
        $smarty->assign([
            'SUCCESS_TITLE' => $language->get('general', 'success'),
            'SUCCESS' => $success
        ]);
    }

    $smarty->assign([
        'FORGOT_PASSWORD' => str_replace('?', '', $language->get('user', 'forgot_password')),
        'FORGOT_PASSWORD_INSTRUCTIONS' => $language->get('user', 'forgot_password_instructions'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    try {
        $template->displayTemplate('forgot_password.tpl', $smarty);
    } catch (SmartyException $ignored) {
    }
} else {
    // Check code exists
    try {
        $target_user = new User($_GET['c'], 'reset_code');
    } catch (GuzzleException $ignored) {
    }
    if (!$target_user->exists()) {
        Redirect::to('/forgot_password');
    }

    if (Input::exists()) {
        try {
            if (Token::check()) {
                try {
                    $validation = Validate::check($_POST, [
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
                } catch (Exception $ignored) {
                }

                if ($validation->passed()) {
                    if (strcasecmp($target_user->data()->email, $_POST['email']) === 0) {
                        $new_password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);
                        try {
                            $target_user->update([
                                'password' => $new_password,
                                'reset_code' => null
                            ]);

                            Session::flash('login_success', $language->get('user', 'forgot_password_change_successful'));
                            Redirect::to(URL::build('/login'));
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
        } catch (Exception $ignored) {
        }
    }

    if (isset($errors) && count($errors)) {
        $smarty->assign([
            'ERROR_TITLE' => $language->get('general', 'error'),
            'ERROR' => $errors
        ]);
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

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    try {
        $template->displayTemplate('change_password.tpl', $smarty);
    } catch (SmartyException $ignored) {
    }
}
