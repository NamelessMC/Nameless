<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Panel API page
 */

if (!$user->handlePanelPageLoad('admincp.core.emails')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'emails';
$page_title = $language->get('admin', 'email_errors');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['do'])) {
    if (in_array($_GET['do'], ['delete', 'purge'])) {
        if (Token::check()) {
            if ($_GET['do'] == 'purge') {
                // Purge all errors

                DB::getInstance()->delete('email_errors', ['id', '<>', 0]);

                Session::flash('emails_errors_success', $language->get('admin', 'email_errors_purged_successfully'));
                Redirect::to(URL::build('/panel/core/emails/errors'));
            }

            if ($_GET['do'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {

                DB::getInstance()->delete('email_errors', ['id', $_GET['id']]);

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

        switch ($error->type) {
            case Email::REGISTRATION:
                $type = $language->get('admin', 'registration_email');
                break;
            case Email::FORGOT_PASSWORD:
                $type = $language->get('admin', 'forgot_password_email');
                break;
            case Email::API_REGISTRATION:
                $type = $language->get('admin', 'api_registration_email');
                break;
            case Email::FORUM_TOPIC_REPLY:
                $type = $language->get('admin', 'forum_topic_reply_email');
                break;
            case Email::MASS_MESSAGE:
                $type = $language->get('admin', 'emails_mass_message');
                break;
            default:
                $type = $language->get('admin', 'unknown');
                break;
        }

        $smarty->assign([
            'BACK_LINK' => URL::build('/panel/core/emails/errors'),
            'VIEWING_ERROR' => $language->get('admin', 'viewing_email_error'),
            'USERNAME' => $language->get('user', 'username'),
            'USERNAME_VALUE' => $error->user_id ? Output::getClean($user->idToName($error->user_id)) : $language->get('general', 'deleted_user'),
            'DATE' => $language->get('general', 'date'),
            'DATE_VALUE' => date(DATE_FORMAT, $error->at),
            'TYPE' => $language->get('admin', 'type'),
            'TYPE_ID' => $error->type,
            'TYPE_VALUE' => $type,
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

        if ($error->type == Email::REGISTRATION) {
            $user_validated = DB::getInstance()->get('users', ['id', $error->user_id])->results();
            if (count($user_validated)) {
                $user_validated = $user_validated[0];
                if ($user_validated->active == 0) {
                    $smarty->assign([
                        'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . urlencode($error->user_id) . '&amp;action=validate'),
                        'VALIDATE_USER_TEXT' => $language->get('admin', 'validate_user')
                    ]);
                }
            }
        } else if ($error->type == Email::API_REGISTRATION) {
            $user_error = DB::getInstance()->get('users', ['id', $error->user_id])->results();
            if (count($user_error)) {
                $user_error = $user_error[0];
                if ($user_error->active == 0 && !is_null($user_error->reset_code)) {
                    $smarty->assign([
                        'REGISTRATION_LINK' => $language->get('admin', 'registration_link'),
                        'SHOW_REGISTRATION_LINK' => $language->get('admin', 'show_registration_link'),
                        'REGISTRATION_LINK_VALUE' => rtrim(URL::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . urlencode($user_error->reset_code))
                    ]);
                }
            }
        }

        $template_file = 'core/emails_errors_view.tpl';
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

    $smarty->assign([
        'BACK_LINK' => URL::build('/panel/core/emails'),
        'TYPE' => $language->get('admin', 'type'),
        'DATE' => $language->get('general', 'date'),
        'USERNAME' => $language->get('user', 'username'),
        'ACTIONS' => $language->get('general', 'actions')
    ]);

    if (count($email_errors)) {
        $template_errors = [];

        foreach ($results->data as $error) {
            switch ($error->type) {
                case Email::REGISTRATION:
                    $type = $language->get('admin', 'registration_email');
                    break;
                case Email::FORGOT_PASSWORD:
                    $type = $language->get('admin', 'forgot_password_email');
                    break;
                case Email::API_REGISTRATION:
                    $type = $language->get('admin', 'api_registration_email');
                    break;
                case Email::FORUM_TOPIC_REPLY:
                    $type = $language->get('admin', 'forum_topic_reply_email');
                    break;
                case Email::MASS_MESSAGE:
                    $type = $language->get('admin', 'emails_mass_message');
                    break;
                default:
                    $type = $language->get('admin', 'unknown');
                    break;
            }

            $template_errors[] = [
                'type' => $type,
                'date' => date(DATE_FORMAT, $error->at),
                'user' => $error->user_id ? Output::getClean($user->idToName($error->user_id)) : $language->get('general', 'deleted_user'),
                'view_link' => URL::build('/panel/core/emails/errors/', 'do=view&id=' . $error->id),
                'id' => $error->id
            ];
        }

        $smarty->assign([
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
        $smarty->assign([
            'NO_ERRORS' => $language->get('admin', 'no_email_errors')
        ]);
    }

    $template_file = 'core/emails_errors.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('emails_errors_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('emails_errors_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('emails_errors_error')) {
    $errors = [Session::flash('emails_errors_error')];
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
    'EMAILS_LINK' => URL::build('/panel/core/emails'),
    'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
    'PAGE' => PANEL_PAGE,
    'BACK' => $language->get('general', 'back'),
    'TOKEN' => Token::get(),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
