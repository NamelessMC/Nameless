<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft query errors page
 */

if(!$user->handlePanelPageLoad('admincp.minecraft.query_errors')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'minecraft');
define('MINECRAFT_PAGE', 'query_errors');
$page_title = $language->get('admin', 'query_errors');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['id'])){
    if(isset($_GET['action']) && $_GET['action'] == 'purge'){
        $queries->delete('query_errors', array('id', '<>', 0));
        Session::flash('panel_query_errors_success', $language->get('admin', 'query_errors_purged_successfully'));
        Redirect::to(URL::build('/panel/minecraft/query_errors'));
        die();
    }

    $query_errors = $queries->orderWhere('query_errors', 'id <> 0', 'DATE', 'DESC');
    if(count($query_errors)){
        // Get page
        if(isset($_GET['p'])){
            if(!is_numeric($_GET['p'])){
                Redirect::to(URL::build('/panel/minecraft/query_errors'));
                die();
            } else
                $p = $_GET['p'];

        } else{
            $p = 1;
        }

        // Pagination
        $paginator = new Paginator();
        $results = $paginator->getLimited($query_errors, 10, $p, count($query_errors));
        $pagination = $paginator->generate(7, URL::build('/panel/minecraft/query_errors/', true));

        $template_array = array();

        foreach($results->data as $result){
            $template_array[] = array(
                'ip' => Output::getClean($result->ip),
                'port' => Output::getClean($result->port),
                'date' => date('d M Y, H:i', $result->date),
                'view_link' => URL::build('/panel/minecraft/query_errors/', 'id=' . Output::getClean($result->id)),
                'delete_link' => URL::build('/panel/minecraft/query_errors/', 'action=delete&id=' . Output::getClean($result->id))
            );
        }

        $smarty->assign(array(
            'PAGINATION' => $pagination,
            'QUERY_ERRORS_ARRAY' => $template_array,
            'SERVER_ADDRESS' => $language->get('admin', 'server_address'),
            'SERVER_PORT' => $language->get('admin', 'server_port'),
            'DATE' => $language->get('general', 'date'),
            'ACTIONS' => $language->get('general', 'actions')
        ));
    }

    $template_file = 'integrations/minecraft/minecraft_query_errors.tpl';

} else {
    // View an error
    if(!is_numeric($_GET['id'])){
        Redirect::to(URL::build('/panel/minecraft/query_errors'));
        die();
    }

    $query_error = $queries->getWhere('query_errors', array('id', '=', $_GET['id']));
    if(!count($query_error)){
        Redirect::to(URL::build('/panel/minecraft/query_errors'));
        die();
    }
    $query_error = $query_error[0];

    if($_GET['action'] == 'delete'){
        $queries->delete('query_errors', array('id', '=', $_GET['id']));
        Session::flash('panel_query_errors_success', $language->get('admin', 'query_error_deleted_successfully'));
        Redirect::to(URL::build('/panel/minecraft/query_errors'));
        die();
    }

    $smarty->assign(array(
        'VIEWING_ERROR' => $language->get('admin', 'viewing_query_error'),
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/panel/minecraft/query_errors'),
        'DELETE' => $language->get('general', 'delete'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'CONFIRM_DELETE_ERROR' => $language->get('admin', 'confirm_query_error_deletion'),
        'DELETE_LINK' => URL::build('/panel/minecraft/query_errors/', 'action=delete&id=' . Output::getClean($query_error->id)),
        'SERVER_ADDRESS' => $language->get('admin', 'server_address'),
        'SERVER_PORT' => $language->get('admin', 'server_port'),
        'DATE' => $language->get('general', 'date'),
        'SERVER_ADDRESS_VALUE' => Output::getClean($query_error->ip),
        'SERVER_PORT_VALUE' => Output::getClean($query_error->port),
        'DATE_VALUE' => date('d M Y, H:i', $query_error->date),
        'ERROR_MESSAGE' => Output::getClean($query_error->error)
    ));

    $template_file = 'integrations/minecraft/minecraft_query_errors_view.tpl';

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('panel_query_errors_success'))
    $success = Session::flash('panel_query_errors_success');

if(isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if(isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'QUERY_ERRORS' => $language->get('admin', 'query_errors'),
    'VIEW' => $language->get('general', 'view'),
    'DELETE' => $language->get('general', 'delete'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'CONFIRM_DELETE_ERROR' => $language->get('admin', 'confirm_query_error_deletion'),
    'PURGE_QUERY_ERRORS' => $language->get('admin', 'purge_errors'),
    'PURGE_QUERY_ERRORS_LINK' => URL::build('/panel/minecraft/query_errors/', 'action=purge'),
    'CONFIRM_PURGE_ERRORS' => $language->get('admin', 'confirm_purge_errors'),
    'NO_QUERY_ERRORS' => $language->get('admin', 'no_query_errors')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
