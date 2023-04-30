<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Email management page
 */

if (!$user->handlePanelPageLoad('admincp.core.emails')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'emails';
$page_title = $language->get('admin', 'emails');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Since emails are sent in the user's language, they need to be able to pick which language's messages to edit
if (Session::exists('editing_language')) {
    $lang_short_code = Session::get('editing_language');
} else {
    $default_lang = DB::getInstance()->get('languages', ['is_default', true])->results();
    $lang_short_code = $default_lang[0]->short_code;
}
$editing_language = new Language('core', $lang_short_code);
$emails = [
    ['register', $language->get('admin', 'registration'), ['subject' => $editing_language->get('emails', 'register_subject'), 'message' => $editing_language->get('emails', 'register_message')]],
    ['change_password', $language->get('user', 'change_password'), ['subject' => str_replace('?', '', $editing_language->get('emails', 'change_password_subject')), 'message' => $editing_language->get('emails', 'change_password_message')]],
    ['forum_topic_reply', $language->get('admin', 'forum_topic_reply_email'), ['subject' => $editing_language->get('emails', 'forum_topic_reply_subject'), 'message' => $editing_language->get('emails', 'forum_topic_reply_message')]]
];

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'test') {
        $smarty->assign([
            'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/emails')
        ]);

        if (isset($_GET['do']) && $_GET['do'] == 'send') {
            $errors = [];

            $sent = Email::send(
                ['email' => $user->data()->email, 'name' => $user->data()->nickname],
                Output::getClean(SITE_NAME) . ' - Test Email',
                Output::getClean(SITE_NAME) . ' - Test email successful!',
            );

            if (isset($sent['error'])) {
                $errors[] = $sent['error'];
            }

            if (!count($errors)) {
                $success = $language->get('admin', 'test_email_success');
            }

            $smarty->assign([
                'TEST_EMAIL_QUERY' => $language->get('admin', 'test_email_query'),
                'TEST_EMAIL_SUGGEST_1' => $language->get('admin', 'test_email_suggest_1'),
                'TEST_EMAIL_SUGGEST_2' => $language->get('admin', 'test_email_suggest_2'),
                'TEST_EMAIL_SUGGEST_3' => $language->get('admin', 'test_email_suggest_3', [
                    'docLinkStart' => '<a href=\'https://docs.namelessmc.com/smtp\' target=\'_blank\'>',
                    'docLinkEnd' => '</a>'
                ])
            ]);
        } else {
            $smarty->assign([
                'SEND_TEST_EMAIL_INFO' => $language->get('admin', 'send_test_email_info', [
                    'email' => Text::bold(Output::getClean($user->data()->email))
                ]),
                'INFO' => $language->get('general', 'info'),
                'SEND' => $language->get('admin', 'send'),
                'SEND_LINK' => URL::build('/panel/core/emails/', 'action=test&do=send')
            ]);
        }

        $template_file = 'core/emails_test.tpl';
    } else {
        if ($_GET['action'] == 'edit_messages') {

            $available_languages = [];

            $languages = DB::getInstance()->get('languages', ['id', '<>', 0])->results();
            foreach ($languages as $language_db) {
                $lang = new Language('core', $language_db->short_code);
                $lang_file = $lang->getActiveLanguageFile();
                if (file_exists($lang_file) && is_writable($lang_file)) {
                    $available_languages[] = $language_db;
                }
            }

            $smarty->assign([
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
            ]);

            $template_file = 'core/emails_edit_messages.tpl';
        } else {
            if ($_GET['action'] == 'preview') {
                $viewing_language = new Language('core', Session::get('editing_language'));

                $smarty->assign([
                    'MESSAGE' => Email::formatEmail($_GET['email'], $viewing_language)
                ]);

                $template_file = 'core/emails_edit_messages_preview.tpl';
            }
        }
    }
} else {
    // Handle input
    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {

            // Handle email message updating
            if (isset($_POST['greeting'])) {
                $editing_lang = new Language('core', $lang_short_code);

                Session::put('editing_language', Input::get('editing_language'));

                $editing_lang->set('emails', 'greeting', Output::getClean(Input::get('greeting')));
                $editing_lang->set('emails', 'thanks', Output::getClean(Input::get('thanks')));

                foreach ($emails as $email) {
                    $editing_lang->set('emails', $email[0] . '_subject', Output::getClean(Input::get($email[0] . '_subject')));
                    $editing_lang->set('emails', $email[0] . '_message', Output::getClean(Input::get($email[0] . '_message')));
                }
                Session::flash('emails_success', $language->get('admin', 'email_settings_updated_successfully'));
                Redirect::to(URL::build('/panel/core/emails', 'action=edit_messages'));
            } else {
                Util::setSetting('phpmailer', (isset($_POST['enable_mailer']) && $_POST['enable_mailer']) ? '1' : '0');

                if (!empty($_POST['email'])) {
                    Util::setSetting('outgoing_email', $_POST['email']);
                }

                if ($_POST['port'] && !is_numeric($_POST['port'])) {
                    $errors[] = $language->get('admin', 'email_port_invalid');
                }

                if (!count($errors)) {
                    // Update config

                    Config::set('email.email', !empty($_POST['email']) ? $_POST['email'] : Config::get('email.email', ''));
                    Config::set('email.username', !empty($_POST['username']) ? $_POST['username'] : Config::get('email.username', ''));
                    Config::set('email.password', !empty($_POST['password']) ? $_POST['password'] : Config::get('email.password', ''));
                    Config::set('email.name', !empty($_POST['name']) ? $_POST['name'] : Config::get('email.name', ''));
                    Config::set('email.host', !empty($_POST['host']) ? $_POST['host'] : Config::get('email.host', ''));
                    Config::set('email.port', !empty($_POST['port']) ? (int) $_POST['port'] : Config::get('email.port', ''));

                    // Redirect to refresh config values
                    Session::flash('emails_success', $language->get('admin', 'email_settings_updated_successfully'));
                    Redirect::to(URL::build('/panel/core/emails'));
                }
            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    if ($user->hasPermission('admincp.core.emails_mass_message')) {
        $smarty->assign([
            'MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
            'MASS_MESSAGE_LINK' => URL::build('/panel/core/emails/mass_message'),
        ]);
    }

    $smarty->assign([
        'MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
        'MASS_MESSAGE_LINK' => URL::build('/panel/core/emails/mass_message'),
        'EDIT_EMAIL_MESSAGES' => $language->get('admin', 'edit_email_messages'),
        'EDIT_EMAIL_MESSAGES_LINK' => URL::build('/panel/core/emails/', 'action=edit_messages'),
        'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
        'SEND_TEST_EMAIL_LINK' => URL::build('/panel/core/emails/', 'action=test'),
        'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
        'EMAIL_ERRORS_LINK' => URL::build('/panel/core/emails/errors'),
        'ENABLE_MAILER' => $language->get('admin', 'use_external_mail_server'),
        'ENABLE_MAILER_VALUE' => Util::getSetting('phpmailer'),
        'INFO' => $language->get('general', 'info'),
        'ENABLE_MAILER_HELP' => $language->get('admin', 'enable_mailer_help', [
            'docLinkStart' => "<a href='https://docs.namelessmc.com/smtp' target='_blank'>",
            'docLinkEnd' => '</a>'
        ]),
        'OUTGOING_EMAIL' => $language->get('admin', 'outgoing_email'),
        'OUTGOING_EMAIL_INFO' => $language->get('admin', 'outgoing_email_info'),
        'OUTGOING_EMAIL_VALUE' => Output::getClean(Util::getSetting('outgoing_email')),
        'USERNAME' => $language->get('user', 'username'),
        'USERNAME_VALUE' => Output::getClean(Config::get('email.username', '')),
        'PASSWORD' => $language->get('user', 'password'),
        'PASSWORD_HIDDEN' => $language->get('admin', 'email_password_hidden'),
        'NAME' => $language->get('admin', 'name'),
        'NAME_VALUE' => Output::getClean(Config::get('email.name', '')),
        'HOST' => $language->get('admin', 'host'),
        'HOST_VALUE' => Output::getClean(Config::get('email.host', '')),
        'PORT' => $language->get('admin', 'email_port'),
        'PORT_VALUE' => Output::getClean(Config::get('email.port', 587)),
        'SUBMIT' => $language->get('general', 'submit'),
        'TOKEN' => Token::get()
    ]);

    $template_file = 'core/emails.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('emails_success')) {
    $success = Session::flash('emails_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'EMAILS' => $language->get('admin', 'emails'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
