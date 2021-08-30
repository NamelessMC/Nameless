<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Email management page
 */

if(!$user->handlePanelPageLoad('admincp.core.emails')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'emails');
$page_title = $language->get('admin', 'emails');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Since emails are sent in the user's language, they need to be able to pick which language's messages to edit
if (Session::exists('editing_language')) $lang_name = Session::get('editing_language');
else {
    $default_lang = $queries->getWhere('languages', array('is_default', '=', 1));
    $default_lang = $default_lang[0]->name;
    $lang_name = $default_lang;
}
$editing_language = new Language(null, $lang_name);
$emails = array(
    ['register', $language->get('admin', 'registration'), ['subject' => $editing_language->get('emails', 'register_subject'), 'message' => $editing_language->get('emails', 'register_message')]],
    ['change_password', $language->get('user', 'change_password'), ['subject' => str_replace('?', '', $editing_language->get('emails', 'change_password_subject')), 'message' => $editing_language->get('emails', 'change_password_message')]],
    ['forum_topic_reply', $language->get('admin', 'forum_topic_reply_email'), ['subject' => $editing_language->get('emails', 'forum_topic_reply_subject'), 'message' => $editing_language->get('emails', 'forum_topic_reply_message')]]
);

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'test') {
        $smarty->assign(array(
            'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/emails')
        ));

        if (isset($_GET['do']) && $_GET['do'] == 'send') {
            $errors = array();

            $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
            $php_mailer = $php_mailer[0]->value;

            if ($php_mailer == '1') {

                // PHP Mailer
                $email = array(
                    'to' => array('email' => Output::getClean($user->data()->email), 'name' => Output::getClean($user->data()->nickname)),
                    'subject' => SITE_NAME . ' - Test Email',
                    'message' => SITE_NAME . ' - Test email successful!',
                );

                $sent = Email::send($email, 'mailer');

                if (isset($sent['error']))
                    // Error
                    $errors[] = $sent['error'];
            } else {
                // PHP mail function
                $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                $siteemail = $siteemail[0]->value;

                $to = $user->data()->email;
                $subject = SITE_NAME . ' - Test Email';

                $message = SITE_NAME . ' - Test email successful!';

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

                if (isset($sent['error']))
                    // Error
                    $errors[] = $sent['error'];
            }

            if (!count($errors))
                $success = $language->get('admin', 'test_email_success');
        } else {
            $smarty->assign(array(
                'SEND_TEST_EMAIL_INFO' => str_replace('{x}', Output::getClean($user->data()->email), $language->get('admin', 'send_test_email_info')),
                'INFO' => $language->get('general', 'info'),
                'SEND' => $language->get('admin', 'send'),
                'SEND_LINK' => URL::build('/panel/core/emails/', 'action=test&do=send')
            ));
        }

        $template_file = 'core/emails_test.tpl';
    } else if ($_GET['action'] == 'edit_messages') {

        $available_languages = array();

        $languages = $queries->getWhere('languages', array('id', '<>', 0));
        foreach ($languages as $language_db) {
            $lang = new Language(null, $language_db->name);
            $lang_file = ($lang->getActiveLanguageDirectory() . DIRECTORY_SEPARATOR . 'emails.php');
            if (file_exists($lang_file) && is_writable($lang_file)) array_push($available_languages, $language_db);
        }

        $smarty->assign(array(
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/emails'),
            'EMAILS_MESSAGES' => $language->get('admin', 'edit_email_messages'),
            'EDITING_MESSAGES' => $language->get('admin', 'editing_messages'),
            'OPTIONS' => $language->get('admin', 'email_message_options'),
            'SELECT_LANGUAGE' => $language->get('admin', 'editing_language'),
            'EDITING_LANGUAGE' => $editing_language->getActiveLanguage(),
            'LANGUAGES' => $available_languages,
            'INFO' => $language->get('general', 'info'),
            'LANGUAGE_INFO' => $language->get('admin', 'email_language_info'),
            'GREETING' => $language->get('admin', 'email_message_greeting'),
            'GREETING_VALUE' => $editing_language->get('emails', 'greeting'),
            'THANKS' => $language->get('admin', 'email_message_thanks'),
            'THANKS_VALUE' => $editing_language->get('emails', 'thanks'),
            'EMAILS_LIST' => $emails,
            'SUBJECT' => $language->get('admin', 'email_message_subject'),
            'MESSAGE' => $language->get('admin', 'email_message_message'),
            'PREVIEW' => $language->get('admin', 'email_preview_popup'),
            'PREVIEW_INFO' => $language->get('admin', 'email_preview_popup_message'),
            'SUBMIT' => $language->get('general', 'submit'),
            'TOKEN' => Token::get()
        ));

        $template_file = 'core/emails_edit_messages.tpl';
    } else if ($_GET['action'] == 'preview') {

        $viewing_language =  new Language(null, Session::get('editing_language'));

        $smarty->assign(array(
            'USER_NAME' => $user->data()->username,
            'SUBJECT' => $viewing_language->get('emails', $_GET['email'] . '_subject'),
            'MESSAGE' => Email::formatEmail($_GET['email'], $viewing_language)
        ));

        $template_file = 'core/emails_edit_messages_preview.tpl';
    }
} else {
    // Handle input
    if (Input::exists()) {
        $errors = array();

        if (Token::check()) {

            // Handle email message updating
            if (isset($_POST['greeting'])) {
                $editing_lang = new Language(null, $lang_name);

                Session::put('editing_language', Input::get('editing_language'));

                $editing_lang->set('emails', 'greeting', Output::getClean(Input::get('greeting')));
                $editing_lang->set('emails', 'thanks', Output::getClean(Input::get('thanks')));

                foreach ($emails as $email) {
                    $editing_lang->set('emails', $email[0] . '_subject', Output::getClean(Input::get($email[0] . '_subject')));
                    $editing_lang->set('emails', $email[0] . '_message', Output::getClean(Input::get($email[0] . '_message')));
                }

                if (!count($errors)) {
                    Session::flash('emails_success', $language->get('admin', 'email_settings_updated_successfully'));
                    Redirect::to(URL::build('/panel/core/emails', 'action=edit_messages'));
                    die();
                }
            } else {

                if (isset($_POST['enable_mailer']) && $_POST['enable_mailer'] == 1)
                    $mailer = '1';
                else
                    $mailer = '0';

                $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                $php_mailer = $php_mailer[0]->id;

                $queries->update('settings', $php_mailer, array(
                    'value' => $mailer
                ));

                if (!empty($_POST['email'])) {
                    $outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                    $outgoing_email = $outgoing_email[0]->id;

                    $queries->update('settings', $outgoing_email, array(
                        'value' => Output::getClean($_POST['email'])
                    ));
                }

                if ($_POST['port'] && !is_numeric($_POST['port'])) {
                    $errors[] = $language->get('admin', 'email_port_invalid');
                }

                if (!count($errors)) {
                    // Update config
                    $config_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'email.php';
                    if (file_exists($config_path)) {
                        if (is_writable($config_path)) {
                            require(ROOT_PATH . '/core/email.php');

                            // Build new email config
                            $config = '<?php' . PHP_EOL .
                                '$GLOBALS[\'email\'] = array(' . PHP_EOL .
                                '    \'email\' => \'' . (!empty($_POST['email']) ? addslashes($_POST['email']) : $GLOBALS['email']['email']) . '\',' . PHP_EOL .
                                '    \'username\' => \'' . (!empty($_POST['username']) ? addslashes($_POST['username']) : $GLOBALS['email']['username']) . '\',' . PHP_EOL .
                                '    \'password\' => \'' . (!empty($_POST['password']) ? addslashes($_POST['password']) : $GLOBALS['email']['password']) . '\',' . PHP_EOL .
                                '    \'name\' => \'' . (!empty($_POST['name']) ? addslashes($_POST['name']) : $GLOBALS['email']['name']) . '\',' . PHP_EOL .
                                '    \'host\' => \'' . (!empty($_POST['host']) ? addslashes($_POST['host']) : $GLOBALS['email']['host']) . '\',' . PHP_EOL .
                                '    \'port\' => ' . (!empty($_POST['port']) ? $_POST['port'] : $GLOBALS['email']['port']) . ',' . PHP_EOL .
                                '    \'secure\' => \'' . $GLOBALS['email']['secure'] . '\',' . PHP_EOL .
                                '    \'smtp_auth\' => ' . (($GLOBALS['email']['smtp_auth']) ? 'true' : 'false') . PHP_EOL .
                                ');';

                            $file = fopen($config_path, 'w');
                            fwrite($file, $config);
                            fclose($file);
                        } else {
                            // Permissions incorrect
                            $errors[] = $language->get('admin', 'unable_to_write_email_config');
                        }
                    } else {
                        // Create one now
                        if (is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'core')) {
                            // Build new email config
                            $config = '<?php' . PHP_EOL .
                                '$GLOBALS[\'email\'] = array(' . PHP_EOL .
                                '    \'email\' => \'' . (!empty($_POST['email']) ? addslashes($_POST['email']) : '') . '\',' . PHP_EOL .
                                '    \'username\' => \'' . (!empty($_POST['username']) ? addslashes($_POST['username']) : '') . '\',' . PHP_EOL .
                                '    \'password\' => \'' . (!empty($_POST['password']) ? addslashes($_POST['password']) : '') . '\',' . PHP_EOL .
                                '    \'name\' => \'' . (!empty($_POST['name']) ? addslashes($_POST['name']) : '') . '\',' . PHP_EOL .
                                '    \'host\' => \'' .  (!empty($_POST['host']) ? addslashes($_POST['host']) : '') . '\',' . PHP_EOL .
                                '    \'port\' => \'' . (!empty($_POST['port']) ? $_POST['port'] : 587) . ',' . PHP_EOL .
                                '    \'secure\' => \'tls\',' . PHP_EOL .
                                '    \'smtp_auth\' => true' . PHP_EOL .
                                ');';
                            $file = fopen($config_path, 'w');
                            fwrite($file, $config);
                            fclose($file);
                        } else {
                            $errors[] = $language->get('admin', 'unable_to_write_email_config');
                        }
                    }

                    if (!count($errors)) {
                        // Redirect to refresh config values
                        Session::flash('emails_success', $language->get('admin', 'email_settings_updated_successfully'));
                        Redirect::to(URL::build('/panel/core/emails'));
                        die();
                    }
                }
            }
        } else
            $errors[] = $language->get('general', 'invalid_token');
    }

    $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
    $php_mailer = $php_mailer[0]->value;

    $outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
    $outgoing_email = $outgoing_email[0]->value;

    require(ROOT_PATH . '/core/email.php');

    if ($user->hasPermission('admincp.core.emails_mass_message')) {
        $smarty->assign(array(
            'MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
            'MASS_MESSAGE_LINK' => URL::build('/panel/core/emails/mass_message'),
        ));
    }

    $smarty->assign(array(
        'MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
        'MASS_MESSAGE_LINK' => URL::build('/panel/core/emails/mass_message'),
        'EDIT_EMAIL_MESSAGES' => $language->get('admin', 'edit_email_messages'),
        'EDIT_EMAIL_MESSAGES_LINK' => URL::build('/panel/core/emails/', 'action=edit_messages'),
        'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
        'SEND_TEST_EMAIL_LINK' => URL::build('/panel/core/emails/', 'action=test'),
        'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
        'EMAIL_ERRORS_LINK' => URL::build('/panel/core/emails/errors'),
        'ENABLE_MAILER' => $language->get('admin', 'enable_mailer'),
        'ENABLE_MAILER_VALUE' => $php_mailer,
        'INFO' => $language->get('general', 'info'),
        'ENABLE_MAILER_HELP' => $language->get('admin', 'enable_mailer_help'),
        'OUTGOING_EMAIL' => $language->get('admin', 'outgoing_email'),
        'OUTGOING_EMAIL_INFO' => $language->get('admin', 'outgoing_email_info'),
        'OUTGOING_EMAIL_VALUE' => Output::getClean($outgoing_email),
        'MAILER_SETTINGS_INFO' => $language->get('admin', 'mailer_settings_info'),
        'USERNAME' => $language->get('user', 'username'),
        'USERNAME_VALUE' => (!empty($GLOBALS['email']['username']) ? Output::getClean($GLOBALS['email']['username']) : ''),
        'PASSWORD' => $language->get('user', 'password'),
        'PASSWORD_HIDDEN' => $language->get('admin', 'email_password_hidden'),
        'NAME' => $language->get('admin', 'name'),
        'NAME_VALUE' => (!empty($GLOBALS['email']['name']) ? Output::getClean($GLOBALS['email']['name']) : ''),
        'HOST' => $language->get('admin', 'host'),
        'HOST_VALUE' => (!empty($GLOBALS['email']['host']) ? Output::getClean($GLOBALS['email']['host']) : ''),
        'PORT' => $language->get('admin', 'email_port'),
        'PORT_VALUE' => (!empty($GLOBALS['email']['port']) ? Output::getClean(isset($GLOBALS['email']['port']) ? $GLOBALS['email']['port'] : 587) : 587),
        'SUBMIT' => $language->get('general', 'submit'),
        'TOKEN' => Token::get()
    ));

    $template_file = 'core/emails.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('emails_success'))
    $success = Session::flash('emails_success');

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'EMAILS' => $language->get('admin', 'emails'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
