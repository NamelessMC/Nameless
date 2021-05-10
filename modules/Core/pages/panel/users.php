<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel users page
 */

if(!$user->handlePanelPageLoad('admincp.users')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'users');
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('users_session')) {
    $success = Session::flash('users_session');
}

if (isset($success)) {
    $smarty->assign(
        array(
            'SUCCESS' => $success,
            'SUCCESS_TITLE' => $language->get('general', 'success')
        )
    );
}

if (isset($errors) && count($errors)) {
    $smarty->assign(
        array(
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        )
    );
}

$output = array();
if (!defined('PANEL_TEMPLATE_STAFF_USERS_AJAX')) {
    // Get all users
    $users = $queries->getWhere('users', array('id', '<>', 0));
    foreach ($users as $item) {
        $target_user = new User($item->id);

        $output[] = array(
            'id' => Output::getClean($item->id),
            'username' => $target_user->getDisplayname(true),
            'nickname' => $target_user->getDisplayname(),
            'avatar' => $target_user->getAvatar(128),
            'style' => $target_user->getGroupClass(),
            'profile' => $target_user->getProfileURL(),
            'panel_profile' => URL::build('/panel/user/' . Output::getClean($item->id . '-' . $item->username)),
            'primary_group' => Output::getClean($target_user->getMainGroup()->name),
            'all_groups' => $target_user->getAllGroups(true),
            'registered' => date('d M Y', $item->joined),
            'registered_unix' => Output::getClean($item->joined)
        );
    }
}

$smarty->assign(
    array(
        'PARENT_PAGE' => PARENT_PAGE,
        'DASHBOARD' => $language->get('admin', 'dashboard'),
        'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
        'USERS' => $language->get('admin', 'users'),
        'PAGE' => PANEL_PAGE,
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'USER' => $language->get('admin', 'user'),
        'GROUP' => $language->get('admin', 'group'),
        'GROUPS' => $language->get('admin', 'groups'),
        'REGISTERED' => $language->get('admin', 'registered'),
        'ACTIONS' => $language->get('general', 'actions'),
        'ACTIONS_LIST' => Core_Module::getUserActions(),
        'ALL_USERS' => $output
    )
);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users.tpl', $smarty);
