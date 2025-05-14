<?php
/**
 * Staff panel email errors page
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
$page_title = $language->get('admin', 'email_errors');
require_once ROOT_PATH . '/core/templates/backend_init.php';

if (isset($_GET['do'])) {
    if (in_array($_GET['do'], ['delete', 'purge'])) {
        if (Token::check()) {
            if ($_GET['do'] == 'purge') {
                // Purge all errors

                DB::getInstance()->delete('email_errors', ['id', '<>', 0]);

                $cache->setCache('notices_cache');
                $cache->store('email_errors', 0);

                Session::flash('emails_errors_success', $language->get('admin', 'email_errors_purged_successfully'));
                Redirect::to(URL::build('/panel/core/emails/errors'));
            }

            if ($_GET['do'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {

                DB::getInstance()->delete('email_errors', ['id', $_GET['id']]);

                $cache->setCache('notices_cache');
                $cache->erase('email_errors');

                Session::flash('emails_errors_success', $language->get('admin', 'error_deleted_successfully'));
                Redirect::to(URL::build('/panel/core/emails/errors'));
            }
        } else {
            Session::flash('emails_errors_error', $language->get('general', 'invalid_token'));
            Redirect::to(URL::build('/panel/core/emails/errors'));
        }
    }

    if ($_GET['do'] == 'view' && isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Check the error exists
        $error = DB::getInstance()->get('email_errors', ['id', $_GET['id']])->results();
        if (!count($error)) {
            Redirect::to(URL::build('/panel/core/emails/errors'));
        }
        $error = $error[0];

        $template->getEngine()->addVariables([
            'BACK_LINK' => URL::build('/panel/core/emails/errors'),
            'VIEWING_ERROR' => $language->get('admin', 'viewing_email_error'),
            'USERNAME' => $language->get('user', 'username'),
            'USERNAME_VALUE' => $error->user_id ? Output::getClean($user->idToName($error->user_id)) : $language->get('general', 'deleted_user'),
            'DATE' => $language->get('general', 'date'),
            'DATE_VALUE' => date(DATE_FORMAT, $error->at),
            'MAILER' => $language->get('admin', 'mailer'),
            'MAILER_VALUE' => $error->mailer,
            'CONTENT' => $language->get('admin', 'content'),
            'CONTENT_VALUE' => Output::getPurified($error->content),
            'ACTIONS' => $language->get('general', 'actions'),
            'DELETE_ERROR' => $language->get('admin', 'delete_email_error'),
            'DELETE_ERROR_LINK' => URL::build('/panel/core/emails/errors/', 'do=delete&amp;id=' . $error->id),
            'CONFIRM_DELETE_ERROR' => $language->get('admin', 'confirm_email_error_deletion'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'CLOSE' => $language->get('general', 'close')
        ]);

        if ($error->mailer == 'Register') {
            $user_validated = DB::getInstance()->get('users', $error->user_id)->first();
            if ($user_validated && $user_validated->active == 0) {
                $template->getEngine()->addVariables([
                    'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . urlencode($error->user_id) . '&amp;action=validate'),
                    'VALIDATE_USER_TEXT' => $language->get('admin', 'validate_user')
                ]);
            }
        }

        $template_file = 'core/emails_errors_view';
    } else {
        Redirect::to(URL::build('/panel/core/emails/errors'));
    }
} else {
    // Display all errors
    $email_errors = DB::getInstance()->orderWhere('email_errors', 'id <> 0', 'at', 'DESC')->results();

    // Get page
    if (isset($_GET['p'])) {
        if (!is_numeric($_GET['p'])) {
            Redirect::to(URL::build('/panel/core/emails/errors'));
        }

        if ($_GET['p'] == 1) {
            // Avoid bug in pagination class
            Redirect::to(URL::build('/panel/core/emails/errors'));
        }
        $p = $_GET['p'];
    } else {
        $p = 1;
    }

    // Pagination
    $paginator = new Paginator();

    $results = $paginator->getLimited($email_errors, 10, $p, count($email_errors));
    $pagination = $paginator->generate(7, URL::build('/panel/core/emails/errors'));

    $template->getEngine()->addVariables([
        'BACK_LINK' => URL::build('/panel/core/emails'),
        'MAILER' => $language->get('admin', 'mailer'),
        'DATE' => $language->get('general', 'date'),
        'USERNAME' => $language->get('user', 'username'),
        'ACTIONS' => $language->get('general', 'actions')
    ]);

    if (count($email_errors)) {
        $template_errors = [];

        foreach ($results->data as $error) {
            $template_errors[] = [
                'mailer' => $error->mailer,
                'date' => date(DATE_FORMAT, $error->at),
                'user' => $error->user_id ? Output::getClean($user->idToName($error->user_id)) : $language->get('general', 'deleted_user'),
                'view_link' => URL::build('/panel/core/emails/errors/', 'do=view&id=' . $error->id),
                'id' => $error->id
            ];
        }

        $template->getEngine()->addVariables([
            'EMAIL_ERRORS_ARRAY' => $template_errors,
            'DELETE_LINK' => URL::build('/panel/core/emails/errors/', 'do=delete&id={x}'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'PURGE_BUTTON' => $language->get('admin', 'purge_errors'),
            'CONFIRM_PURGE_ERRORS' => $language->get('admin', 'confirm_purge_errors'),
            'PURGE_LINK' => URL::build('/panel/core/emails/errors/', 'do=purge'),
            'CONFIRM_DELETE_ERROR' => $language->get('admin', 'confirm_email_error_deletion'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'PAGINATION' => $pagination
        ]);
    } else {
        $template->getEngine()->addVariable('NO_ERRORS', $language->get('admin', 'no_email_errors'));
    }

    $template_file = 'core/emails_errors';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('emails_errors_success')) {
    $template->getEngine()->addVariables([
        'SUCCESS' => Session::flash('emails_errors_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (Session::exists('emails_errors_error')) {
    $errors = [Session::flash('emails_errors_error')];
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
    'EMAILS_LINK' => URL::build('/panel/core/emails'),
    'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
    'PAGE' => PANEL_PAGE,
    'BACK' => $language->get('general', 'back'),
    'TOKEN' => Token::get(),
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate($template_file);
