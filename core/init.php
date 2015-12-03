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
} else if($path === "../../"){
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

	/* 
	 *  TEMPORARY - STAFF APPLICATION QUERY
	 */
	$staff_applications = $queries->getWhere('core_modules', array('name', '=', 'Staff_Applications'));
	// for upgrade purposes, can be deleted in the future
	if(!count($staff_applications)){
		$queries->create('core_modules', array(
			'name' => 'Staff_Applications',
			'enabled' => 0
		));
		$data = $queries->alterTable("groups", "staff_apps", "tinyint(1) NOT NULL DEFAULT '0'");
		$data = $queries->alterTable("groups", "accept_staff_apps", "tinyint(1) NOT NULL DEFAULT '0'");
		$data = $queries->createTable("staff_apps_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `aid` int(11) NOT NULL, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$data = $queries->createTable("staff_apps_questions", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `name` varchar(16) NOT NULL, `question` varchar(256) NOT NULL, `options` text NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$data = $queries->createTable("staff_apps_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, `status` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	}
	
	/*
	 *  TEMPORARY - MINECRAFT SERVER TABLE QUERY
	 */
	$mc_servers_col_exists = $queries->getWhere('settings', array('name', '=', 'query_update'));
	if(!count($mc_servers_col_exists)){
		// Insert column for Minecraft query IP
		$data = $queries->alterTable("mc_servers", "query_ip", "varchar(64) NOT NULL");
		
		// Insert column for Gravatar in users table
		$data = $queries->alterTable("users", "gravatar", "tinyint(1) NOT NULL DEFAULT '0'");
		
		// Also for last online column
		$data = $queries->alterTable("users", "last_online", "int(11) DEFAULT NULL");
		
		// Input data into settings so this step doesn't happen again
		$queries->create('settings', array(
			'name' => 'query_update',
			'value' => 'false'
		));
	}
	$mc_servers_col_exists = null;
	
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