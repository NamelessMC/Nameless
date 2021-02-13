<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel debugging + errors page
 */

$user->handlePanelPageLoad('admincp.errors');

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'debugging_and_maintenance');
$page_title = $language->get('admin', 'error_logs');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['log']) && isset($_GET['do']) && $_GET['do'] == 'purge') {
    file_put_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $_GET['log'] . '-log.log')), '');
    Session::flash('error_log_success', $language->get('admin', 'log_purged_successfully'));
    Redirect::to(URL::build('/panel/core/errors'));
    die();
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('error_log_success'))
    $smarty->assign(array(
        'SUCCESS' => Session::flash('error_log_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($_GET['log'])) {
    switch ($_GET['log']) {
        case 'fatal':
            $type = 'fatal';
            $title = $language->get('admin', 'fatal_log');

            break;

        case 'notice':
            $type = 'notice';
            $title = $language->get('admin', 'notice_log');

            break;

        case 'warning':
            $type = 'warning';
            $title = $language->get('admin', 'warning');

            break;

        default:
            $type = 'other';
            $title = $language->get('admin', 'other_log');

            break;
    }

    if (file_exists(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $_GET['log'] . '-log.log')))) {
        $smarty->assign('LOG', nl2br(Output::getClean(file_get_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $type . '-log.log'))))));
    } else {
        $smarty->assign('NO_LOG_FOUND', $language->get('admin', 'log_file_not_found'));
    }

    $smarty->assign(array(
        'BACK_LINK' => URL::build('/panel/core/errors'),
        'LOG_NAME' => $title,
        'ACTIONS' => $language->get('general', 'actions'),
        'PURGE_LOG' => $language->get('admin', 'purge_errors'),
        'CONFIRM_PURGE_ERRORS' => $language->get('admin', 'confirm_purge_errors'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'PURGE_LOG_LINK' => URL::build('/panel/core/errors/', 'log=' . $type . '&do=purge')
    ));
} else {
    $smarty->assign(array(
        'BACK_LINK' => URL::build('/panel/core/debugging_and_maintenance'),
        'FATAL_LOG' => $language->get('admin', 'fatal_log'),
        'FATAL_LOG_LINK' => URL::build('/panel/core/errors/', 'log=fatal'),
        'NOTICE_LOG' => $language->get('admin', 'notice_log'),
        'NOTICE_LOG_LINK' => URL::build('/panel/core/errors/', 'log=notice'),
        'WARNING_LOG' => $language->get('admin', 'warning_log'),
        'WARNING_LOG_LINK' => URL::build('/panel/core/errors/', 'log=warning'),
        'OTHER_LOG' => $language->get('admin', 'other_log'),
        'OTHER_LOG_LINK' => URL::build('/panel/core/errors/', 'log=other'),
    ));
}

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'DEBUGGING_AND_MAINTENANCE' => $language->get('admin', 'debugging_and_maintenance'),
    'PAGE' => PANEL_PAGE,
    'ERROR_LOGS' => $language->get('admin', 'error_logs'),
    'BACK' => $language->get('general', 'back')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
if (!isset($_GET['log']))
    $template->displayTemplate('core/errors.tpl', $smarty);
else
    $template->displayTemplate('core/errors_view.tpl', $smarty);
