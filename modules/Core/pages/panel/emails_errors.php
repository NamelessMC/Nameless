<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel API page
 */

if(!$user->handlePanelPageLoad('admincp.core.emails')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'emails');
$page_title = $language->get('admin', 'email_errors');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['do'])) {
    if ($_GET['do'] == 'purge') {
        // Purge all errors

         $queries->delete('email_errors', array('id', '<>', 0));

        Session::flash('emails_errors_success', $language->get('admin', 'email_errors_purged_successfully'));
        Redirect::to(URL::build('/panel/core/emails/errors'));
        die();
    } else if ($_GET['do'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {

        $queries->delete('email_errors', array('id', '=', $_GET['id']));

        Session::flash('emails_errors_success', $language->get('admin', 'error_deleted_successfully'));
        Redirect::to(URL::build('/panel/core/emails/errors'));
        die();
    } else if ($_GET['do'] == 'view' && isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Check the error exists
        $error = $queries->getWhere('email_errors', array('id', '=', $_GET['id']));
        if (!count($error)) {
            Redirect::to(URL::build('/panel/core/emails/errors'));
            die();
        }
        $error = $error[0];

        switch ($error->type) {
            case 1:
                $type = $language->get('admin', 'registration_email');
                break;
            case 2:
                $type = $language->get('admin', 'contact_email');
                break;
            case 3:
                $type = $language->get('admin', 'forgot_password_email');
                break;
            case 4:
                $type = $language->get('admin', 'api_registration_email');
                break;
            case 5:
                $type = $language->get('admin', 'forum_topic_reply_email');
                break;
            case 6:
                $type = $language->get('admin', 'emails_mass_message');
                break;
            default:
                $type = $language->get('admin', 'unknown');
                break;
        }

        $smarty->assign(array(
            'BACK_LINK' => URL::build('/panel/core/emails/errors'),
            'VIEWING_ERROR' => $language->get('admin', 'viewing_email_error'),
            'USERNAME' => $language->get('user', 'username'),
            'USERNAME_VALUE' => Output::getClean($user->idToName($error->user_id)),
            'DATE' => $language->get('general', 'date'),
            'DATE_VALUE' => date('d M Y, H:i', $error->at),
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
        ));

        if ($error->type == 1) {
            $user_validated = $queries->getWhere('users', array('id', '=', $error->user_id));
            if (count($user_validated)) {
                $user_validated = $user_validated[0];
                if ($user_validated->active == 0) {
                    $smarty->assign(array(
                        'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . $error->user_id . '&amp;action=validate'),
                        'VALIDATE_USER_TEXT' => $language->get('admin', 'validate_user')
                    ));
                }
            }
        } else if ($error->type == 4) {
            $user_error = $queries->getWhere('users', array('id', '=', $error->user_id));
            if (count($user_error)) {
                $user_error = $user_error[0];
                if ($user_error->active == 0 && !is_null($user_error->reset_code)) {
                    $smarty->assign(array(
                        'REGISTRATION_LINK' => $language->get('admin', 'registration_link'),
                        'SHOW_REGISTRATION_LINK' => $language->get('admin', 'show_registration_link'),
                        'REGISTRATION_LINK_VALUE' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . Output::getClean($user_error->reset_code))
                    ));
                }
            }
        }

        $template_file = 'core/emails_errors_view.tpl';
    } else {
        Redirect::to(URL::build('/panel/core/emails/errors'));
        die();
    }
} else {
    // Display all errors
    $email_errors = $queries->orderWhere('email_errors', 'id <> 0', 'at', 'DESC');

    // Get page
    if (isset($_GET['p'])) {
        if (!is_numeric($_GET['p'])) {
            Redirect::to(URL::build('/panel/core/emails/errors'));
            die();
        } else {
            if ($_GET['p'] == 1) {
                // Avoid bug in pagination class
                Redirect::to(URL::build('/panel/core/emails/errors'));
                die();
            }
            $p = $_GET['p'];
        }
    } else {
        $p = 1;
    }

    // Pagination
    $paginator = new Paginator();

    $results = $paginator->getLimited($email_errors, 10, $p, count($email_errors));
    $pagination = $paginator->generate(7, URL::build('/panel/core/emails/errors', true));

    $smarty->assign(array(
        'BACK_LINK' => URL::build('/panel/core/emails'),
        'TYPE' => $language->get('admin', 'type'),
        'DATE' => $language->get('general', 'date'),
        'USERNAME' => $language->get('user', 'username'),
        'ACTIONS' => $language->get('general', 'actions')
    ));

    if (count($email_errors)) {
        $template_errors = array();

        for ($n = 0; $n < count($results->data); $n++) {
            switch ($results->data[$n]->type) {
                case 1:
                    $type = $language->get('admin', 'registration_email');
                    break;
                case 2:
                    $type = $language->get('admin', 'contact_email');
                    break;
                case 3:
                    $type = $language->get('admin', 'forgot_password_email');
                    break;
                case 4:
                    $type = $language->get('admin', 'api_registration_email');
                    break;
                case 5:
                    $type = $language->get('admin', 'forum_topic_reply_email');
                    break;
                case 6:
                    $type = $language->get('admin', 'emails_mass_message');
                    break;
                default:
                    $type = $language->get('admin', 'unknown');
                    break;
            }

            $template_errors[] = array(
                'type' => $type,
                'date' => date('d M Y, H:i', $results->data[$n]->at),
                'user' => Output::getClean($user->idToName($results->data[$n]->user_id)),
                'view_link' => URL::build('/panel/core/emails/errors/', 'do=view&id=' . $results->data[$n]->id),
                'id' => $results->data[$n]->id
            );
        }

        $smarty->assign(array(
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
        ));
    } else {
        $smarty->assign(array(
            'NO_ERRORS' => $language->get('admin', 'no_email_errors')
        ));
    }

    $template_file = 'core/emails_errors.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('emails_errors_success'))
    $smarty->assign(array(
        'SUCCESS' => Session::flash('emails_errors_success'),
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
    'EMAILS_LINK' => URL::build('/panel/core/emails'),
    'EMAIL_ERRORS' => $language->get('admin', 'email_errors'),
    'PAGE' => PANEL_PAGE,
    'BACK' => $language->get('general', 'back')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
