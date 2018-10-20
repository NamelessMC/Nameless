<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel auth page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if($user->isAdmLoggedIn()){
		// Already authenticated
		Redirect::to(URL::build('/panel'));
		die();
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PANEL_PAGE', 'auth');
$page_title = $language->get('admin', 're-authenticate');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

require(ROOT_PATH . '/core/includes/password.php'); // Require password compat library

// Get login method
$method = $queries->getWhere('settings', array('name', '=', 'login_method'));
$method = $method[0]->value;

// Deal with any input
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Validate input
		$validate = new Validate();

		if($method == 'email')
			$to_validate = array(
				'email' => array('required' => true, 'isbanned' => true, 'isactive' => true),
				'password' => array('required' => true)
			);
		else
			$to_validate = array(
				'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
				'password' => array('required' => true)
			);

		$validation = $validate->check($_POST, $to_validate);

		if($validation->passed()) {
			if($method == 'email')
				$username = Input::get('email');
			else
				$username = Input::get('username');

			$user = new User();
			$login = $user->adminLogin($username, Input::get('password'), $method);

			if($login){
				// Get IP
				$ip = $user->getIP();

				// Create log
				Log::getInstance()->log(Log::Action('admin/login'));

				Redirect::to(URL::build('/panel'));
				die();
			} else {
				Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
			}
		} else {
			Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
		}
	} else {
		// Invalid token
		Session::flash('adm_auth_error', $language->get('general', 'invalid_token'));
	}
}

if($method == 'email'){
	$smarty->assign(array(
		'EMAIL' => $language->get('user', 'email')
	));
} else {
	$smarty->assign(array(
		'USERNAME' => $language->get('user', 'username')
	));
}

$smarty->assign(array(
	'PLEASE_REAUTHENTICATE' => $language->get('admin', 're-authenticate'),
	'PASSWORD' => $language->get('user', 'password'),
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'CANCEL' => $language->get('general', 'cancel')
));

if(Session::exists('adm_auth_error'))
	$smarty->assign('ERROR', Session::flash('adm_auth_error'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('auth.tpl', $smarty);