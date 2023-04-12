<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel user page
 */

if (!$user->handlePanelPageLoad()) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

$uid = explode('/', $route);
$uid = $uid[count($uid) - 1];

if (!strlen($uid)) {
    Redirect::to(URL::build('/panel'));
}

$uid = explode('-', $uid);
if (!is_numeric($uid[0])) {
    Redirect::to(URL::build('/panel'));
}
$uid = $uid[0];

$view_user = new User($uid);
if (!$view_user->exists()) {
    Redirect::to(URL::build('/panel'));
}
$user_query = $view_user->data();

$timeago = new TimeAgo(TIMEZONE);

const PAGE = 'panel';
const PANEL_PAGE = 'users';
const PARENT_PAGE = 'users';
$page_title = Output::getClean($user_query->username);
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

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

$user_language = DB::getInstance()->get('languages', ['id', $user_query->language_id])->results();
$user_language = $user_language[0]->name;

if ($user->hasPermission('admincp.users.edit')) {
    // Email address
    $smarty->assign([
        'EMAIL_ADDRESS' => Output::getClean($user_query->email),
        'EMAIL_ADDRESS_LABEL' => $language->get('user', 'email_address')
    ]);
}

if ($user->hasPermission('modcp.ip_lookup')) {
    // Last IP
    $smarty->assign([
        'LAST_IP' => Output::getClean($user_query->lastip),
        'LAST_IP_LABEL' => $language->get('admin', 'ip_address')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'AVATAR' => $view_user->getAvatar(256),
    'NICKNAME' => $view_user->getDisplayname(),
    'USERNAME' => $view_user->getDisplayname(true),
    'USER_STYLE' => $view_user->getGroupStyle(),
    'USER_GROUP' => Output::getClean($view_user->getMainGroup()->name),
    'USER_GROUPS' => $view_user->getAllGroupHtml(),
    'USER_TITLE' => Output::getClean($user_query->user_title),
    'LANGUAGE' => Output::getClean($user_language),
    'TIMEZONE' => Output::getClean($user_query->timezone),
    'REGISTERED' => $language->get('user', 'registered'),
    'REGISTERED_VALUE' => date('d M Y', $user_query->joined),
    'LAST_SEEN' => $language->get('user', 'last_seen'),
    'LAST_SEEN_SHORT_VALUE' => $timeago->inWords($user_query->last_online, $language),
    'LAST_SEEN_FULL_VALUE' => date(DATE_FORMAT, $user_query->last_online),
    'DETAILS' => $language->get('admin', 'details'),
    'LINKS' => Core_Module::getUserActions(),
    'USER_ID' => $user_query->id,
    'USERNAME_LABEL' => $language->get('user', 'username'),
    'NICKNAME_LABEL' => $language->get('user', 'nickname'),
    'USER_TITLE_LABEL' => $language->get('admin', 'title'),
    'LANGUAGE_LABEL' => $language->get('user', 'active_language'),
    'TIMEZONE_LABEL' => $language->get('user', 'timezone'),
    'TEMPLATE_LABEL' => $language->get('admin', 'template'),
    'TEMPLATE' => DB::getInstance()->get('templates', ['id', $user_query->theme_id])->first()->name ?? TEMPLATE,
    'NAME' => $language->get('admin', 'name'),
    'CONTENT' => $language->get('admin', 'content'),
    'UPDATED' => $language->get('admin', 'updated'),
    'NOT_SET' => $language->get('admin', 'not_set'),
    'PROFILE_FIELDS_LABEL' => 'Profile Fields',
    'ALL_PROFILE_FIELDS' => ProfileField::all(),
    'USER_PROFILE_FIELDS' => $view_user->getProfileFields(true),
    'NO_PROFILE_FIELDS' => $language->get('admin', 'no_custom_fields'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/user.tpl', $smarty);
