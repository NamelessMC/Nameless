<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel users page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.users')){
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'users');
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('users_session'))
	$success = Session::flash('users_session');

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

// Get all users
$users = $queries->getWhere('users', array('id', '<>', 0));
$output = array();
foreach($users as $item){
	$output[] = array(
		'id' => Output::getClean($item->id),
		'username' => Output::getClean($item->username),
		'nickname' => Output::getClean($item->nickname),
		'avatar' => $user->getAvatar($item->id, '', 128),
		'style' => $user->getGroupClass($item->id),
		'profile' => URL::build('/profile/' . Output::getClean($item->username)),
		'panel_profile' => URL::build('/panel/user/' . Output::getClean($item->id . '-' . $item->username)),
		'primary_group' => Output::getClean($user->getGroupName($item->group_id)),
		'all_groups' => $user->getAllGroups($item->id, true),
		'registered' => date('d M Y', $item->joined),
		'registered_unix' => Output::getClean($item->joined)
	);
}

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
	'USERS' => $language->get('admin', 'users'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'USER' => $language->get('admin', 'user'),
	'GROUPS' => $language->get('admin', 'groups'),
	'REGISTERED' => $language->get('admin', 'registered'),
	'ACTIONS' => $language->get('general', 'actions'),
	'ACTIONS_LIST' => Core_Module::getUserActions(),
	'ALL_USERS' => $output
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users.tpl', $smarty);