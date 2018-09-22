<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel API page
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
		if(!$user->hasPermission('admincp.core.api')){
			require_once(ROOT_PATH . '/404.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'api');
$page_title = $language->get('admin', 'api');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(isset($_GET['action']) && $_GET['action'] == 'api_regen'){
	// Regenerate new API key
	// Generate new key
	$new_api_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);

	$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
	$plugin_api = $plugin_api[0]->id;

	// Update key
	$queries->update('settings', $plugin_api, array(
		'value' => $new_api_key
	));

	// Cache
	file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

	// Redirect
	Session::flash('api_success', $language->get('admin', 'api_key_regenerated'));
	Redirect::to(URL::build('/panel/core/api'));
	die();
}

if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		$plugin_id = $queries->getWhere('settings', array('name', '=', 'use_api'));
		$plugin_id = $plugin_id[0]->id;
		$queries->update('settings', $plugin_id, array(
			'value' => Input::get('enable_api')
		));

		$legacy_plugin_id = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
		$legacy_plugin_id = $legacy_plugin_id[0]->id;
		$queries->update('settings', $legacy_plugin_id, array(
			'value' => Input::get('enable_legacy_api')
		));

		if(isset($_POST['verification']) && $_POST['verification'] == 'on')
			$verification = 1;
		else
			$verification = 0;

		$verification_id = $queries->getWhere('settings', array('name', '=', 'email_verification'));
		$verification_id = $verification_id[0]->id;
		try {
			$queries->update('settings', $verification_id, array(
				'value' => $verification
			));
		} catch(Exception $e){
			$errors[] = $e->getMessage();
		}

		if(isset($_POST['api_verification']) && $_POST['api_verification'] == 'on')
			$api_verification = 1;
		else
			$api_verification = 0;

		$api_verification_id = $queries->getWhere('settings', array('name', '=', 'api_verification'));
		$api_verification_id = $api_verification_id[0]->id;
		try {
			$queries->update('settings', $api_verification_id, array(
				'value' => $api_verification
			));
		} catch(Exception $e){
			$errors[] = $e->getMessage();
		}

		if(isset($_POST['username_sync']) && $_POST['username_sync'] == 'on')
			$username_sync = 1;
		else
			$username_sync = 0;

		$username_sync_id = $queries->getWhere('settings', array('name', '=', 'username_sync'));
		$username_sync_id = $username_sync_id[0]->id;
		try {
			$queries->update('settings', $username_sync_id, array(
				'value' => $username_sync
			));
		} catch(Exception $e){
			$errors[] = $e->getMessage();
		}

		Session::flash('api_success', $language->get('admin', 'api_settings_updated_successfully'));

		//Log::getInstance()->log(Log::Action('admin/api/change'));
	} else {
		$errors[] = $language->get('general', 'invalid_token');
	}
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('api_success'))
	$smarty->assign(array(
		'SUCCESS' => Session::flash('api_success'),
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

// Is the API enabled?
$api_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
if(count($api_enabled)){
	$api_enabled = $api_enabled[0]->value;
} else {
	$queries->create('settings', array(
		'name' => 'use_api',
		'value' => 0
	));
	$api_enabled = '0';
}

// Is the legacy API enabled?
$legacy_api_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
if(count($legacy_api_enabled)){
	$legacy_api_enabled = $legacy_api_enabled[0]->value;
} else {
	$queries->create('settings', array(
		'name' => 'use_legacy_api',
		'value' => 0
	));
	$legacy_api_enabled = '0';
}

// Get API key
$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
$plugin_api = $plugin_api[0]->value;

// Is email verification enabled
$emails = $queries->getWhere('settings', array('name', '=', 'email_verification'));
$emails = $emails[0]->value;

// Is API verification enabled?
$api_verification = $queries->getWhere('settings', array('name', '=', 'api_verification'));
$api_verification = $api_verification[0]->value;

// Is the username sync enabled?
$username_sync = $queries->getWhere('settings', array('name', '=', 'username_sync'));
$username_sync = $username_sync[0]->value;

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'CONFIGURATION' => $language->get('admin', 'configuration'),
	'API' => $language->get('admin', 'api'),
	'PAGE' => PANEL_PAGE,
	'API_INFO' => $language->get('admin', 'api_info'),
	'INFO' => $language->get('general', 'info'),
	'ENABLE_API' => $language->get('admin', 'enable_api'),
	'API_ENABLED' => $api_enabled,
	'API_KEY' => $language->get('admin', 'api_key'),
	'API_KEY_VALUE' => Output::getClean($plugin_api),
	'API_KEY_REGEN_URL' => URL::build('/panel/core/api/', 'action=api_regen'),
	'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
	'CONFIRM_API_REGEN' => $language->get('admin', 'confirm_api_regen'),
	'YES' => $language->get('general', 'yes'),
	'NO' => $language->get('general', 'no'),
	'CHANGE' => $language->get('general', 'change'),
	'API_URL' => $language->get('admin', 'api_url'),
	'API_URL_VALUE' => rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($plugin_api)), '/'),
	'COPY' => $language->get('admin', 'copy'),
	'ENABLE_LEGACY_API' => $language->get('admin', 'enable_legacy_api'),
	'LEGACY_API_ENABLED' => $legacy_api_enabled,
	'LEGACY_API_INFO' => $language->get('admin', 'legacy_api_info'),
	'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
	'EMAIL_VERIFICATION_VALUE' => $emails,
	'API_VERIFICATION' => $language->get('admin', 'api_verification'),
	'API_VERIFICATION_VALUE' => $api_verification,
	'API_VERIFICATION_INFO' => $language->get('admin', 'api_verification_info'),
	'USERNAME_SYNC' => $language->get('admin', 'enable_username_sync'),
	'USERNAME_SYNC_INFO' => $language->get('admin', 'enable_username_sync_info'),
	'USERNAME_SYNC_VALUE' => $username_sync,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'COPIED' => $language->get('general', 'copied')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/api.tpl', $smarty);