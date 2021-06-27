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
define('PAGE', 'contact');
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
                try {
                    $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                    $php_mailer = $php_mailer[0]->value;

                    $contactemail = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
                    $contactemail = $contactemail[0]->value;

                    if ($php_mailer == '1') {
                        // PHP Mailer
                        $html = Output::getClean(Input::get('content'));

                        $email = array(
                            'replyto' => array('email' => Output::getClean(Input::get('email')), 'name' => Output::getClean(Input::get('email'))),
                            'to' => array('email' => Output::getClean($contactemail), 'name' => Output::getClean(SITE_NAME)),
                            'subject' => SITE_NAME . ' - ' . $language->get('general', 'contact_email_subject'),
                            'message' => $html
                        );

                        $sent = Email::send($email, 'mailer');

                        if (isset($sent['error'])) {
                            // Error, log it
                            $queries->create('email_errors', array(
                                'type' => 2, // 2 = contact
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => ($user->isLoggedIn() ? $user->data()->id : null)
                            ));
                        }

                    } else {
                        // PHP mail function
                        $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                        $siteemail = $siteemail[0]->value;

                        $to = $contactemail;
                        $subject = SITE_NAME . ' - ' . $language->get('general', 'contact_email_subject');

                        $message = Output::getClean(Input::get('content'));
                        $fromemail = Output::getClean(Input::get('email'));

                        $headers = 'From: ' . $siteemail . "\r\n" .
                            'Reply-To: ' . $fromemail . "\r\n" .
                            'X-Mailer: PHP/' . phpversion() . "\r\n" .
                            'MIME-Version: 1.0' . "\r\n" . 
                            'Content-type: text/html; charset=UTF-8' . "\r\n";

                        $email = array(
                            'to' => $to,
                            'subject' => $subject,
                            'message' => $message,
                            'headers' => $headers
                        );

                        $sent = Email::send($email, 'php');

                        if (isset($sent['error'])) {
                            // Error, log it
                            $queries->create('email_errors', array(
                                'type' => 2, // 2 = contact
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => ($user->isLoggedIn() ? $user->data()->id : null)
                            ));
                        }

                    }
                } catch (Exception $e) {
                    // Error
                    $error = $e->getMessage();
                }

                $_SESSION['last_contact_sent'] = date('U');
                $success = $language->get('general', 'contact_message_sent');
            } else {
                $errors = $validation->errors();
            }

        } else
            // Invalid recaptcha
        $errors = $language->get('user', 'invalid_recaptcha');
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
    $template->addJSFiles(array(CaptchaBase::getActiveProvider()->getJavascriptSource() => array()));

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

if(isset($errors))
	$smarty->assign('ERRORS', $errors);

if(isset($erroremail))
	$smarty->assign('ERROR_EMAIL', $erroremail);

if(isset($errorcontent))
	$smarty->assign('ERROR_CONTENT', $errorcontent);

if(isset($success))
	$smarty->assign('SUCCESS', $success);

$smarty->assign(array(
	'EMAIL' => $language->get('general', 'email_address'),
	'CONTACT' => $language->get('general', 'contact'),
	'MESSAGE' => $language->get('general', 'message'),
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'ERROR_TITLE' => $language->get('general', 'error'),
	'SUCCESS_TITLE' => $language->get('general', 'success')
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate('contact.tpl', $smarty);
