<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel user page
 */

if(!$user->handlePanelPageLoad()) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

$uid = explode('/', $route);
$uid = $uid[count($uid) - 1];

if (!strlen($uid)) {
    Redirect::to(URL::build('/panel'));
    die();
}

$uid = explode('-', $uid);
if (!is_numeric($uid[0])) {
    Redirect::to(URL::build('/panel'));
    die();
}
$uid = $uid[0];

$view_user = new User($uid);
if (!$view_user->data()) {
    Redirect::to(URL::build('/panel'));
    die();
}
$user_query = $view_user->data();

$timeago = new Timeago(TIMEZONE);

define('PAGE', 'panel');
define('PANEL_PAGE', 'users');
define('PARENT_PAGE', 'users');
$page_title = Output::getClean($user_query->username);
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$user_language = $queries->getWhere('languages', array('id', '=', $user_query->language_id));
$user_language = $user_language[0]->name;

if ($user->hasPermission('admincp.users.edit')) {
    // Email address
    $smarty->assign(array(
        'EMAIL_ADDRESS' => Output::getClean($user_query->email),
        'EMAIL_ADDRESS_LABEL' => $language->get('user', 'email_address')
    ));
}

if ($user->hasPermission('modcp.ip_lookup')) {
    // Last IP
    $smarty->assign(array(
        'LAST_IP' => Output::getClean($user_query->lastip),
        'LAST_IP_LABEL' => $language->get('admin', 'ip_address')
    ));
}

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'AVATAR' => $view_user->getAvatar(256),
    'NICKNAME' => $view_user->getDisplayname(),
    'USERNAME' => $view_user->getDisplayname(true),
    'USER_STYLE' => $view_user->getGroupClass(),
    'USER_GROUP' => Output::getClean($view_user->getMainGroup()->name),
    'USER_GROUPS' => $view_user->getAllGroups(true),
    'USER_TITLE' => Output::getClean($user_query->user_title),
    'UUID' => Output::getClean($user_query->uuid),
    'LANGUAGE' => Output::getClean($user_language),
    'TIMEZONE' => Output::getClean($user_query->timezone),
    'REGISTERED' => $language->get('user', 'registered'),
    'REGISTERED_VALUE' => date('d M Y', $user_query->joined),
    'LAST_SEEN' => $language->get('user', 'last_seen'),
    'LAST_SEEN_SHORT_VALUE' => $timeago->inWords(date('d M Y, H:i', $user_query->last_online), $language->getTimeLanguage()),
    'LAST_SEEN_FULL_VALUE' => date('d M Y, H:i', $user_query->last_online),
    'DETAILS' => $language->get('admin', 'details'),
    'LINKS' => Core_Module::getUserActions(),
    'USER_ID' => $user_query->id,
    'USERNAME_LABEL' => $language->get('user', 'username'),
    'NICKNAME_LABEL' => $language->get('user', 'nickname'),
    'USER_TITLE_LABEL' => $language->get('admin', 'title'),
    'UUID_LABEL' => $language->get('admin', 'uuid'),
    'LANGUAGE_LABEL' => $language->get('user', 'active_language'),
    'TIMEZONE_LABEL' => $language->get('user', 'timezone')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/user.tpl', $smarty);
