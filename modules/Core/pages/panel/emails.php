<?php
/**
 * Staff panel email management page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

if (!$user->handlePanelPageLoad('admincp.core.emails')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'emails';
$page_title = $language->get('admin', 'emails');
require_once ROOT_PATH . '/core/templates/backend_init.php';

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'test') {
        $template->getEngine()->addVariables([
            'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/emails')
        ]);

        if (isset($_GET['do']) && $_GET['do'] == 'send') {
            $errors = [];

            $sent = Email::sendRaw(
                Email::TEST_EMAIL,
                $user,
                'Test Email',
                Output::getClean(SITE_NAME) . ' - Test email successful!'
            );

            if (isset($sent['error'])) {
                $errors[] = $sent['error'];
            }

            if (!count($errors)) {
                $success = $language->get('admin', 'test_email_success');
            }

            $template->getEngine()->addVariables([
                'TEST_EMAIL_QUERY' => $language->get('admin', 'test_email_query'),
                'TEST_EMAIL_SUGGEST_1' => $language->get('admin', 'test_email_suggest_1'),
                'TEST_EMAIL_SUGGEST_2' => $language->get('admin', 'test_email_suggest_2'),
                'TEST_EMAIL_SUGGEST_3' => $language->get('admin', 'test_email_suggest_3', [
                    'docLinkStart' => '<a href=\'https://docs.namelessmc.com/smtp\' target=\'_blank\'>',
                    'docLinkEnd' => '</a>'
                ])
            ]);
        } else {
            $template->getEngine()->addVariables([
                'SEND_TEST_EMAIL_INFO' => $language->get('admin', 'send_test_email_info', [
                    'email' => Text::bold(Output::getClean($user->data()->email))
                ]),
                'INFO' => $language->get('general', 'info'),
                'SEND' => $language->get('admin', 'send'),
                'SEND_LINK' => URL::build('/panel/core/emails/', 'action=test&do=send')
            ]);
        }

        $template_file = 'core/emails_test';
    }
} else {
    // Handle input
    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            Settings::set('phpmailer', (isset($_POST['enable_mailer']) && $_POST['enable_mailer']) ? '1' : '0');

            if (!empty($_POST['email'])) {
                Settings::set('outgoing_email', $_POST['email']);
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
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    if ($user->hasPermission('admincp.core.emails_mass_message')) {
        $template->getEngine()->addVariables([
            'MASS_MESSAGE' => $language->get('admin', 'mass_message'),
            'MASS_MESSAGE_LINK' => URL::build('/panel/core/mass_message'),
        ]);
    }

    $template->getEngine()->addVariables([
        'SEND_TEST_EMAIL' => $language->get('admin', 'send_test_email'),
        'SEND_TEST_EMAIL_LINK' => URL::build('/panel/core/emails/', 'action=test'),
        'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
        'EMAIL_ERRORS_LINK' => URL::build('/panel/core/emails/errors'),
        'ENABLE_MAILER' => $language->get('admin', 'use_external_mail_server'),
        'ENABLE_MAILER_VALUE' => Settings::get('phpmailer'),
        'INFO' => $language->get('general', 'info'),
        'ENABLE_MAILER_HELP' => $language->get('admin', 'enable_mailer_help', [
            'docLinkStart' => "<a href='https://docs.namelessmc.com/smtp' target='_blank'>",
            'docLinkEnd' => '</a>'
        ]),
        'OUTGOING_EMAIL' => $language->get('admin', 'outgoing_email'),
        'OUTGOING_EMAIL_INFO' => $language->get('admin', 'outgoing_email_info'),
        'OUTGOING_EMAIL_VALUE' => Output::getClean(Settings::get('outgoing_email')),
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

    $template_file = 'core/emails';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('emails_success')) {
    $success = Session::flash('emails_success');
}

if (isset($success)) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

$template->getEngine()->addVariables([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'EMAILS' => $language->get('admin', 'emails'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate($template_file);
