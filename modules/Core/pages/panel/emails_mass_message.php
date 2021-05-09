<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Email Mass Message page
 */

if(!$user->handlePanelPageLoad('admincp.core.emails_mass_message')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'emails');
$page_title = $language->get('admin', 'emails_mass_message');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (Input::exists()) {
    $errors = array();

    if (Token::check()) {

        $validate = new Validate();

        $validate->check($_POST, [
            'subject' => [
                Validate::REQUIRED => true,
                Validate::MIN => 1,
            ],
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 1,
                Validate::MAX => 75000
            ]
        ]);

        if ($validate->passed()) {

            $users = $queries->getWhere('users', array('id', '<>', 0));

            $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
            $siteemail = $siteemail[0]->value;
            $contactemail = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
            $contactemail = $contactemail[0]->value;

            try {
                $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                $php_mailer = $php_mailer[0]->value;
                if ($php_mailer == '1') {
                    foreach ($users as $email_user) {
                        // PHP Mailer
                        $email = array(
                            'replyto' => array('email' => $contactemail, 'name' => Output::getClean(SITE_NAME)),
                            'to' => array('email' => Output::getClean($email_user->email), 'name' => Output::getClean($email_user->username)),
                            'subject' => Output::getClean(Input::get('subject')),
                            'message' => str_replace(['{username}', '{sitename}'], [$email_user->username, SITE_NAME], Input::get('content')),
                        );
                        $sent = Email::send($email, 'mailer');

                        if (isset($sent['error'])) {
                            // Error, log it
                            $queries->create('email_errors', array(
                                'type' => 6, // 6 = mass message
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => $user->data()->id
                            ));
                        }
                    }
                } else {
                    foreach ($users as $email_user) {
                        // PHP mail function
                        $headers = 'From: ' . $siteemail . "\r\n" .
                            'Reply-To: ' . $contactemail . "\r\n" .
                            'X-Mailer: PHP/' . phpversion() . "\r\n" .
                            'MIME-Version: 1.0' . "\r\n" .
                            'Content-type: text/html; charset=UTF-8' . "\r\n";

                        $email = array(
                            'to' => $email_user->email,
                            'subject' => Output::getClean(Input::get('subject')),
                            'message' => str_replace(['{username}', '{sitename}'], [$email_user->username, SITE_NAME], Input::get('content')),
                            'headers' => $headers
                        );

                        $sent = Email::send($email, 'php');

                        if (isset($sent['error'])) {
                            // Error, log it
                            $queries->create('email_errors', array(
                                'type' => 6, // 6 = mass message
                                'content' => $sent['error'],
                                'at' => date('U'),
                                'user_id' => $user->data()->id
                            ));
                        }
                    }
                }
            } catch (Exception $e) {
                // Error
                $errors[] = $e->getMessage();
            }

            Log::getInstance()->log(Log::Action('admin/core/email/mass_message'));

            if (!count($errors)) {
                Session::flash('emails_success', $language->get('admin', 'emails_mass_message_sent_successfully'));
                Redirect::to(URL::build('/panel/core/emails'));
                die();
            }
        } else {
            $errors = $validate->errors();
        }
    } else
        $errors[] = $language->get('general', 'invalid_token');
}

$php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
$php_mailer = $php_mailer[0]->value;

$outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
$outgoing_email = $outgoing_email[0]->value;

require(ROOT_PATH . '/core/email.php');

$smarty->assign(array(
    'SENDING_MASS_MESSAGE' => $language->get('admin', 'sending_mass_message'),
    'EMAILS_MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
    'SUBJECT' => $language->get('admin', 'email_message_subject'),
    'CONTENT' => $language->get('general', 'content'),
    'INFO' => $language->get('general', 'info'),
    'REPLACEMENT_INFO' => $language->get('admin', 'emails_mass_message_replacements'),
    'LOADING' => $language->get('admin', 'emails_mass_message_loading'),
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/core/emails')
));

$template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
));

// Get post formatting type (HTML or Markdown)
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    // Markdown
    $smarty->assign('MARKDOWN', true);
    $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));

    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
    ));

    $template->addJSScript('
      $(document).ready(function() {
        var el = $("#markdown").emojioneArea({
            pickerPosition: "bottom"
        });
      });
    ');
} else {
    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
    ));

    $template->addJSScript(Input::createTinyEditor($language, 'reply'));
}

$template_file = 'core/emails_mass_message.tpl';

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
