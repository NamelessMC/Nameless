<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Email Mass Message page
 */

if (!$user->handlePanelPageLoad('admincp.core.emails_mass_message')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'emails';
$page_title = $language->get('admin', 'emails_mass_message');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        $validate = Validate::check($_POST, [
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

            $users = DB::getInstance()->get('users', ['id', '<>', 0])->results();

            foreach ($users as $email_user) {
                $sent = Email::send(
                    ['email' => $email_user->email, 'name' => $email_user->username],
                    Input::get('subject'),
                    str_replace(['{username}', '{sitename}'], [$email_user->username, SITE_NAME], Output::getPurified(Input::get('content'))),
                );

                if (isset($sent['error'])) {
                    DB::getInstance()->insert('email_errors', [
                        'type' => Email::MASS_MESSAGE,
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user->data()->id
                    ]);

                    $errors[] = $language->get('admin', 'mass_email_failed_check_logs');
                } else {
                    Session::flash('emails_success', $language->get('admin', 'sent_mass_message'));
                }
            }

            Log::getInstance()->log(Log::Action('admin/core/email/mass_message'));

        } else {
            $errors = $validate->errors();
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

$php_mailer = Util::getSetting('phpmailer');
$outgoing_email = Util::getSetting('outgoing_email');

$smarty->assign([
    'SENDING_MASS_MESSAGE' => $language->get('admin', 'sending_mass_message'),
    'EMAILS_MASS_MESSAGE' => $language->get('admin', 'emails_mass_message'),
    'SUBJECT' => $language->get('admin', 'email_message_subject'),
    'CONTENT' => $language->get('general', 'content'),
    'INFO' => $language->get('general', 'info'),
    'REPLACEMENT_INFO' => $language->get('admin', 'emails_mass_message_replacements'),
    'LOADING' => $language->get('admin', 'emails_mass_message_loading'),
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/core/emails')
]);

$template_file = 'core/emails_mass_message.tpl';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'reply', null, false, true));

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
