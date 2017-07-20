<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Contact page
 */

// Always define page name
define('PAGE', 'contact');

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

                        $headers = 'From: ' . $siteemail . "\r\n" .
                            'Reply-To: ' . $siteemail . "\r\n" .
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
                $error = $language->get('general', 'contact_message_failed');
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
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <?php
    $title = $language->get('general', 'contact');
    require('core/templates/header.php');
    ?>
</head>
<body>
<?php
require('core/templates/navbar.php');
require('core/templates/footer.php');

// Smarty variables
if($recaptcha == 'true'){
    $smarty->assign('RECAPTCHA', Output::getClean($recaptcha_key[0]->value));
}

if(isset($error))
  $smarty->assign('ERROR', $error);

if(isset($success))
    $smarty->assign('SUCCESS', $success);

$smarty->assign(array(
    'CONTACT' => $language->get('general', 'contact'),
    'MESSAGE' => $language->get('general', 'message'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

// Display template
$smarty->display('custom/templates/' . TEMPLATE . '/contact.tpl');

require('core/templates/scripts.php');
if($recaptcha === "true"){
    ?>
  <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php
}
?>
</body>
</html>
