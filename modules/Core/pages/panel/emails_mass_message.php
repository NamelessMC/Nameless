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

            $reply_to = Email::getReplyTo();

            foreach ($users as $email_user) {
                $sent = Email::send(
                    ['email' => Output::getClean($email_user->email), 'name' => Output::getClean($email_user->username)],
                    Output::getClean(Input::get('subject')),
                    Output::getClean(str_replace(['{username}', '{sitename}'], [$email_user->username, SITE_NAME], Input::get('content'))),
                    $reply_to
                );

                if (isset($sent['error'])) {
                    DB::getInstance()->insert('email_errors', [
                        'type' => Email::MASS_MESSAGE,
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user->data()->id
                    ]);
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

$php_mailer = DB::getInstance()->get('settings', ['name', 'phpmailer'])->results();
$php_mailer = $php_mailer[0]->value;

$outgoing_email = DB::getInstance()->get('settings', ['name', 'outgoing_email'])->results();
$outgoing_email = $outgoing_email[0]->value;

require(ROOT_PATH . '/core/email.php');

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

$template->addJSScript(Input::createTinyEditor($language, 'reply'));

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
