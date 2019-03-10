<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Contact page
 */

// Always define page name
define('PAGE', 'contact');
$page_title = $language->get('general', 'contact');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Use recaptcha?
$recaptcha = $queries->getWhere("settings", array("name", "=", "recaptcha"));
$recaptcha = $recaptcha[0]->value;

$recaptcha_key = $queries->getWhere("settings", array("name", "=", "recaptcha_key"));
$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));

// Handle input
if(Input::exists()){
  if(Token::check(Input::get('token'))){
    // Check last contact message sending time
    if(!isset($_SESSION['last_contact_sent']) || (isset($_SESSION['last_contact_sent']) && $_SESSION['last_contact_sent'] < strtotime('-1 hour'))){
        // Check recaptcha
        if($recaptcha == 'true'){
            // Check reCAPCTHA
            $url = 'https://www.google.com/recaptcha/api/siteverify';

            $post_data = 'secret=' . $recaptcha_secret[0]->value . '&response=' . Input::get('g-recaptcha-response');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);

            $result = json_decode($result, true);
        } else {
            // reCAPTCHA is disabled
            $result = array(
                'success' => 'true'
            );
        }

        if(isset($result['success']) && $result['success'] == 'true'){
            // Validate input
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'content' => array(
                    'required' => true,
                    'min' => 10,
                    'max' => 5000
                ),
                'email' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 64,
                )
            ));

            if($validation->passed()){
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
                            'Reply-To: ' . $fromeemail . "\r\n" .
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
                foreach($validation->errors() as $validation_error){
                    switch($validation_error){
                        case (strpos($validation_error, 'content') !== false):
                            $errorcontent = $language->get('general', 'contact_message_failed');
                            break;
                        case (strpos($validation_error, 'email') !== false):
                            $erroremail = $language->get('general', 'contact_message_email');
                            break;
                    }
                }
            }

        } else
            // Invalid recaptcha
            $error = $language->get('user', 'invalid_recaptcha');
    } else {
      $error = str_replace('{x}', round((date('U') - strtotime('- 1 hour')) / 60), $language->get('general', 'contact_message_limit'));
    }
  } else {
    // Invalid token
    $error = $language->get('general', 'invalid_token');
  }
}

// Smarty variables
if($recaptcha == 'true'){
	$smarty->assign('RECAPTCHA', Output::getClean($recaptcha_key[0]->value));
	$template->addJSFiles(array(
		'https://www.google.com/recaptcha/api.js' => array()
	));
}

if(isset($error))
	$smarty->assign('ERROR', $error);

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
