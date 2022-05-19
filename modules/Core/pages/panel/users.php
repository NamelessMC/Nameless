<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel users page
 */

if (!$user->handlePanelPageLoad('admincp.users')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'users';
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('users_session')) {
    $success = Session::flash('users_session');
}

if (isset($success)) {
    $smarty->assign(
        [
            'SUCCESS' => $success,
            'SUCCESS_TITLE' => $language->get('general', 'success')
        ]
    );
}

if (isset($errors) && count($errors)) {
    $smarty->assign(
        [
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        ]
    );
}

$smarty->assign(
    [
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
        'ACTIONS_LIST' => Core_Module::getUserActions()
    ]
);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users.tpl', $smarty);
