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
			require_once 'core/classes/' . $class . '.php';
		}
	});
	
} else if($path === "../../../"){
	// For banner
	require_once '../../includes/smarty/Smarty.class.php';
	require_once '../../includes/sanitize.php';
	spl_autoload_register(function($class) {
		require_once '../../classes/' . $class . '.php';
	});
} else if($path === "../"){
	// For alerts/PMs
	require_once '../includes/smarty/Smarty.class.php';
	require_once '../includes/sanitize.php';
	spl_autoload_register(function($class) {
		require_once '../classes/' . $class . '.php';
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
	$inverse_navbar = $c->retrieve('inverse_navbar');

	// Template
	$c->setCache('templatecache');
	$template = $c->retrieve('template');
	
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
		require('addons/' . htmlspecialchars($addon->name) . '/initialisation.php');
		$enabled_addon_pages[] = $addon->name;
	}

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
	}
}
?>