<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Contact page
 */

// Always define page name
const PAGE = 'contact';
$page_title = $language->get('general', 'contact');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$captcha = CaptchaBase::isCaptchaEnabled();

// Handle input
if (Input::exists()) {
    if (Token::check()) {
        // Check last contact message sending time
        if (!isset($_SESSION['last_contact_sent']) || (isset($_SESSION['last_contact_sent']) && $_SESSION['last_contact_sent'] < strtotime('-1 hour'))) {
            if ($captcha) {
                $captcha_passed = CaptchaBase::getActiveProvider()->validateToken($_POST);
            } else {
                $captcha_passed = true;
            }

            if ($captcha_passed) {
                // Validate input
                $validate = new Validate();
                $validation = $validate->check($_POST, [
                    'content' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 10,
                        Validate::MAX => 5000
                    ],
                    'email' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 4,
                        Validate::MAX => 64,
                    ]
                ])->messages([
                    'content' => $language->get('general', 'contact_message_failed'),
                    'email' => $language->get('general', 'contact_message_email')
                ]);

                if ($validation->passed()) {
                    $sent = Email::send(
                        ['email' => Output::getClean(Util::getSetting(DB::getInstance(), 'incoming_email')), 'name' => Output::getClean(SITE_NAME)],
                        SITE_NAME . ' - ' . $language->get('general', 'contact_email_subject'),
                        Output::getClean(Input::get('content')),
                        ['email' => Output::getClean(Input::get('email')), 'name' => Output::getClean(Input::get('email'))]
                    );

                    if (isset($sent['error'])) {
                        // Error, log it
                        $queries->create('email_errors', [
                            'type' => Email::CONTACT,
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => ($user->isLoggedIn() ? $user->data()->id : null)
                        ]);

                        $errors = $sent['error'];
                    } else {
                        $_SESSION['last_contact_sent'] = date('U');
                        $success = $language->get('general', 'contact_message_sent');
                    }
                } else {
                    $errors = $validation->errors();
                }

            } else {
                $errors = $language->get('user', 'invalid_recaptcha');
            }
        } else {
            // TODO: This seems to never go down
            $errors = str_replace('{x}', round((date('U') - strtotime('- 1 hour')) / 60), $language->get('general', 'contact_message_limit'));
        }
    } else {
        // Invalid token
        $errors = $language->get('general', 'invalid_token');
    }
}

// Smarty variables
if ($captcha) {
    $smarty->assign('CAPTCHA', CaptchaBase::getActiveProvider()->getHtml());
    $template->addJSFiles([CaptchaBase::getActiveProvider()->getJavascriptSource() => []]);

    $submitScript = CaptchaBase::getActiveProvider()->getJavascriptSubmit('form-contact');
    if ($submitScript) {
        $template->addJSScript('
            $("#form-contact").submit(function(e) {
                e.preventDefault();
                ' . $submitScript . '
            });
        ');
    }
}

if (isset($errors)) {
    $smarty->assign('ERRORS', $errors);
}

if (isset($erroremail)) {
    $smarty->assign('ERROR_EMAIL', $erroremail);
}

if (isset($errorcontent)) {
    $smarty->assign('ERROR_CONTENT', $errorcontent);
}

if (isset($success)) {
    $smarty->assign('SUCCESS', $success);
}

$smarty->assign([
    'EMAIL' => $language->get('general', 'email_address'),
    'CONTACT' => $language->get('general', 'contact'),
    'MESSAGE' => $language->get('general', 'message'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ERROR_TITLE' => $language->get('general', 'error'),
    'SUCCESS_TITLE' => $language->get('general', 'success')
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate('contact.tpl', $smarty);
