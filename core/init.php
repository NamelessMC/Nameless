<?php 
session_start();
 
if(!isset($page)){
	die();
}

// Require config
if(!isset($path)){
	require('core/config.php');
} else {
	require($path . 'core/config.php');
}

/*
 *  Autoload classes
 */

if(!isset($path)){
	require_once 'core/includes/smarty/Smarty.class.php'; // Smarty
	require_once 'core/includes/sanitize.php'; // Sanitisation
	
	// Normal autoloader
	spl_autoload_register(function($class) {
		if(strpos($class, 'TeamSpeak3') === false){
			require_once 'core/classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
		}
	});
	
} else if($path === "../../../"){
	// For banner
	require_once '../../includes/smarty/Smarty.class.php';
	require_once '../../includes/sanitize.php';
	spl_autoload_register(function($class) {
		require_once '../../classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	});
} else if($path === "../../"){
	// For alerts/PMs
	require_once '../includes/smarty/Smarty.class.php';
	require_once '../includes/sanitize.php';
	spl_autoload_register(function($class) {
		require_once '../classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	});
}

/* 
 *  Initialise
 */
 
if($page !== 'install'){
	$queries 	= new Queries();
	$user	 	= new User();
	$smarty 	= new Smarty();
	$c	 		= new Cache();
}

// Error reporting?
if($page !== 'install'){
	$error_reporting = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
	$error_reporting = $error_reporting[0]->value;
	if($error_reporting !== '0'){
		// Enabled
		ini_set('display_startup_errors',1);
		ini_set('display_errors',1);
		error_reporting(-1);
	} else {
		// Disabled
		error_reporting(0);
		ini_set('display_errors', 0);
	}
}

// Process if user has checked "remember me"
if($page !== 'install'){
	if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
		
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}
}

if($page !== 'query_alerts' && $page !== 'query_pms' && $page !== 'install' && $page !== 'api' && $page !== 'query_apps' && $page !== 'banner'){
	// Set path for Smarty
	$smarty->setCompileDir('cache/templates_c');

	// Language
	$c->setCache('languagecache');
	$language = $c->retrieve('language');
	if(file_exists('styles/language/' . $language . '/language.php')){
		require('styles/language/' . $language . '/language.php');
	} else {
		require('styles/language/EnglishUK/language.php');
	}

	// Theme
	$c->setCache('themecache');
	$theme_result = $c->retrieve('theme');
	if(!($theme_result)) $theme_result = 'Bootstrap';
	
	$inverse_navbar = $c->retrieve('inverse_navbar');

	// Template
	$c->setCache('templatecache');
	$template = $c->retrieve('template');
	if(!($template) || !is_dir('styles/templates/' . $template)){
		$template = 'Default';
	}
	
	
	// Display page load time?
	$c->setCache('page_load_cache');
	$page_loading = $c->retrieve('page_load');
	 
	// Initialise array for navbar items and footer navigation items, and also custom scripts/css
	$navbar_array = array();
	$footer_nav_array = array();
	$admin_sidebar = array();
	$custom_js = array();
	$custom_css = array();
	 
	// Get enabled addons
	$enabled_addon_pages = array();
	$addons = $queries->getWhere('addons', array('enabled', '=', 1));
	foreach($addons as $addon){
		// Require its initialisation file
		if(file_exists('addons/' . htmlspecialchars($addon->name) . '/initialisation.php')){
			require('addons/' . htmlspecialchars($addon->name) . '/initialisation.php');
			$enabled_addon_pages[] = $addon->name;
		} else {
			// Disable addon
			Session::flash('addon_error', '<div class="alert alert-danger">' . $admin_language['unable_to_enable_addon'] . '</div>');
			$queries->update('addons', $addon->id, array(
				'enabled' => 0
			));
		}
	}
	
	/*
	 *  Todo: cache whether the status module is enabled 
	 */
	$status_enabled = $queries->getWhere('settings', array('name', '=', 'mc_status_module'));
	$status_enabled = $status_enabled[0];
	
	/*
	 *  Todo: cache whether the Play page is enabled
	 */ 
	$play_enabled = $queries->getWhere('settings', array('name', '=', 'play_page_enabled'));
	$play_enabled = $play_enabled[0]->value;

	
	// Get enabled modules
	$modules = $queries->getWhere('core_modules', array('enabled', '=', 1));
	foreach($modules as $module){
		// Require its initialisation file
		require('core/modules/' . htmlspecialchars($module->name) . '/initialisation.php');
	}

	// Get site name from cache
	$c->setCache('sitenamecache');
	$sitename = htmlspecialchars($c->retrieve('sitename'));
	$smarty->assign('SITE_NAME', $sitename);

	// Perform tasks for signed in users
	if($user->isLoggedIn()){
		
		// Update a user's IP
		$ip = $user->getIP();
		if(filter_var($ip, FILTER_VALIDATE_IP)){
			$user->update(array(
				'lastip' => $ip
			));
		}
		
		// Update user last online
		$queries->update('users', $user->data()->id, array(
			'last_online' => date('U')
		));
		
		// Is user banned?
		if($user->data()->isbanned == 1){
			// Yes, log them out
			$user->logout();
		}
		
		// Perform moderator actions
		if($user->canViewMCP($user->data()->id)){
			// Are there any open reports for moderators?
			$reports = $queries->getWhere('reports', array('status' , '<>', '1'));
			if(count($reports)){
				$reports = true; // Open reports
			} else {
				$reports = false; // No open reports
			}
		}

		// Initial private message and alert check
		$pms = $queries->getWhere('private_messages_users', array('user_id', '=', $user->data()->id));

		$unread_pms = array();
		foreach($pms as $pm){
			if($pm->read != 1){
				$pm_query = $queries->getWhere('private_messages', array('id', '=', $pm->pm_id));
				$unread_pms[] = $pm_query[0];
			}
		}
		
		$alerts = $queries->getWhere('alerts', array('user_id', '=', $user->data()->id));

		$unread_alerts = array();
		foreach($alerts as $alert){
			if($alert->read != 1){
				$unread_alerts[] = $alert;
			}
		}
		
	} else {
		// User not logged in
		// Display cookie and log in/register notice
		$cookie_message = '
		<div class="alert alert-cookie alert-info alert-dismissible" role="alert">
          <button type="button" class="close close-cookie" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">' . $general_language['close'] . '</span></button>
	      ' . $general_language['cookie_message'] . '
	    </div>';
		
		Session::flash('global', $cookie_message);
	}
}