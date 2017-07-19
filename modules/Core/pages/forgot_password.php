<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Forgot password page
 */

define('PAGE', 'forgot_password');

// Ensure user is not logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
    die();
}

require('core/includes/password.php'); // For password hashing
?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - forgot password form">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <?php if (isset($custom_meta)) {
        echo $custom_meta;
    } ?>

    <?php
    $title = str_replace('?', '', $language->get('user', 'forgot_password'));
    require('core/templates/header.php');
    ?>

    <!-- Custom style -->
    <style>
        html {
            overflow-y: scroll;
        }
    </style>

</head>
<body>
<?php
// Generate navbar and footer
require('core/templates/navbar.php');
require('core/templates/footer.php');

if (!isset($_GET['c'])) {
    // Enter email address form
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (!isset($_POST['email']) || empty($_POST['email']))
                $error = $language->get('user', 'email_required');
            else {
                // Check to see if the email exists
                $exists = $queries->getWhere('users', array('email', '=', Input::get('email')));
                if (count($exists)) {
                    // Generate a code
                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

                    // Send an email
                    $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                    $php_mailer = $php_mailer[0]->value;

                    if ($php_mailer == '1') {
                        // PHP Mailer
                        // HTML to display in message
                        $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'change_password.html'));
                        $html = file_get_contents($path);

                        $link = 'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/forgot_password/', 'c=' . $code);

                        $html = str_replace(array('[Sitename]', '[ChangePassword]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array(SITE_NAME, str_replace('?', '', $language->get('user', 'forgot_password')), $language->get('user', 'email_greeting'), $language->get('user', 'forgot_password_email_message'), $link, $language->get('user', 'email_thanks')), $html);

                        $email = array(
                            'to' => array('email' => Output::getClean($exists[0]->email), 'name' => Output::getClean($exists[0]->nickname)),
                            'subject' => SITE_NAME . ' - ' . str_replace('?', '', $language->get('user', 'forgot_password')),
                            'message' => $html
                        );

                        $sent = Email::send($email, 'mailer');

                        if (isset($sent['error'])) {
                            // Error, log it
                            $queries->create('email_errors', array(
                                'type' => 3, // 3 = forgot password
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => $exists[0]->id
                            ));

                            $error = $language->get('user', 'unable_to_send_forgot_password_email');
                        }

                    } else {
                        // PHP mail function
                        $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                        $siteemail = $siteemail[0]->value;

                        $to = $exists[0]->email;
                        $subject = SITE_NAME . ' - ' . str_replace('?', '', $language->get('user', 'forgot_password'));

                        $message = $language->get('user', 'email_greeting') . PHP_EOL .
                            $language->get('user', 'forgot_password_email_message') . PHP_EOL . PHP_EOL .
                            'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/forgot_password/', 'c=' . $code) . PHP_EOL . PHP_EOL .
                            $language->get('user', 'email_thanks') . PHP_EOL .
                            SITE_NAME;

                        $headers = 'From: ' . $siteemail . "\r\n" .
                            'Reply-To: ' . $siteemail . "\r\n" .
                            'X-Mailer: PHP/' . phpversion() . "\r\n" .
                            'MIME-Version: 1.0' . "\r\n" . 
                            'Content-type: text/plain; charset=UTF-8' . "\r\n";

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
                                'type' => 3, // 3 = forgot password
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => $exists[0]->user_id
                            ));

                            $error = $language->get('user', 'unable_to_send_forgot_password_email');
                        }

                    }

                    if (!isset($error)) {
                        $queries->update('users', $exists[0]->id, array(
                            'reset_code' => $code
                        ));
                    }

                }

                $success = $language->get('user', 'forgot_password_email_sent');
            }
        } else
            $error = $language->get('general', 'invalid_token');
    }

    if (isset($error))
        $smarty->assign('ERROR', $error);
    else if (isset($success))
        $smarty->assign('SUCCESS', $success);

    $smarty->assign(array(
        'FORGOT_PASSWORD' => str_replace('?', '', $language->get('user', 'forgot_password')),
        'FORGOT_PASSWORD_INSTRUCTIONS' => $language->get('user', 'forgot_password_instructions'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ));

    $smarty->display('custom/templates/' . TEMPLATE . '/forgot_password.tpl');
} else {
    // Check code exists
    $code = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
    if (!count($code)) {
        Redirect::to(URL::build('/forgot_password'));
        die();
    }

    $code = $code[0];

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array(
                    'required' => true
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6,
                    'max' => 30
                ),
                'password_again' => array(
                    'matches' => 'password'
                )
            ));

            if ($validation->passed()) {
                if ($code->email == $_POST['email']) {
                    $new_password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));
                    try {
                        $queries->update('users', $code->id, array(
                            'password' => $new_password,
                            'reset_code' => null
                        ));

                        Session::flash('login_success', $language->get('user', 'forgot_password_change_successful'));
                        Redirect::to(URL::build('/login'));
                        die();
                    } catch (Exception $e) {
                        $errors = array($e->getMessage());
                    }
                } else
                    $errors = array($language->get('user', 'incorrect_email'));

            } else {
                $errors = array();
                foreach ($validation->errors() as $validation_error) {
                    if (strpos($validation_error, 'is required') !== false) {
                        // x is required
                        switch ($validation_error) {
                            case (strpos($validation_error, 'email') !== false):
                                $errors[] = $language->get('user', 'email_required');
                                break;
                            case (strpos($validation_error, 'password') !== false):
                                $errors[] = $language->get('user', 'password_required');
                                break;
                        }

                    } else if (strpos($validation_error, 'minimum') !== false) {
                        // x must be a minimum of y characters long
                        $errors[] = $language->get('user', 'password_minimum_6');

                    } else if (strpos($validation_error, 'maximum') !== false) {
                        // x must be a maximum of y characters long
                        $errors[] = $language->get('user', 'password_maximum_30');

                    } else if (strpos($validation_error, 'must match') !== false) {
                        // password must match password again
                        $errors[] = $language->get('user', 'passwords_dont_match');

                    }
                }
            }
        } else
            $errors = array($language->get('general', 'invalid_token'));
    }

    if (isset($errors) && count($errors))
        $smarty->assign('ERROR', $errors);

    $smarty->assign(array(
        'FORGOT_PASSWORD' => str_replace('?', '', $language->get('user', 'forgot_password')),
        'ENTER_NEW_PASSWORD' => $language->get('user', 'enter_new_password'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'PASSWORD' => $language->get('user', 'password'),
        'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ));

    $smarty->display('custom/templates/' . TEMPLATE . '/change_password.tpl');

}

// Scripts
require('core/templates/scripts.php');
?>
</body>
</html>
