<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Initialisation file
 */

session_start();

// Page variable must be set
if(!isset($page)){
	die();
}

if(!file_exists('core/config.php')){
	if(is_writable('core/config.php')) {
		fopen('core/config.php', 'w');
	} else {
		die('Your <strong>core</strong> directory is not writable, please check your file permissions.');
	}
}

if(!file_exists('cache/templates_c')){
	try {
		mkdir('cache/templates_c', 0777, true);
	} catch(Exception $e){
		die('Unable to create cache directories, please check your file permissions.');
	}
}

// Require config
if(isset($path))
	require($path . 'core/config.php');
else
	require(ROOT_PATH . '/core/config.php');

if(isset($conf) && is_array($conf))
	$GLOBALS['config'] = $conf;
else $page = 'install';

/*
 *  Autoload classes
 */
require_once ROOT_PATH . '/core/includes/smarty/Smarty.class.php'; // Smarty

// Normal autoloader
spl_autoload_register(function($class) {
	if(strpos($class, 'TeamSpeak3') === false){
		$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'core', 'classes', $class . '.php'));
		if(file_exists($path)) require_once($path);
	}
});

if($page != 'install'){
	/*
	 *  Initialise
	 */

	// Friendly URLs?
	define('FRIENDLY_URLS', Config::get('core/friendly'));

	// Queries
	$queries = new Queries();

	// Set up cache
	$cache = new Cache();

	// Page load timer?
	$cache->setCache('page_load_cache');
	$page_loading = $cache->retrieve('page_load');

	// Set timezone
	try {
		$cache->setCache('timezone_cache');
		if($cache->isCached('timezone')){
			define('TIMEZONE', $cache->retrieve('timezone'));
		} else define('TIMEZONE', 'Europe/London');

		date_default_timezone_set(TIMEZONE);
	} catch(Exception $e){
		die('Unable to set timezone: ' . $e->getMessage());
	}

	// Error reporting
	$cache->setCache('error_cache');
	if($cache->isCached('error_reporting')){
		if($cache->retrieve('error_reporting') == 1){
			// Enabled
			ini_set('display_startup_errors',1);
			ini_set('display_errors',1);
			error_reporting(-1);
		} else {
			// Disabled
			error_reporting(0);
			ini_set('display_errors', 0);
		}
	} else {
		// Disable by default
		error_reporting(0);
		ini_set('display_errors', 0);
	}

	// Get the Nameless version
	$nameless_version = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
	$nameless_version = $nameless_version[0]->value;

	define('NAMELESS_VERSION', $nameless_version);

	// User initialisation
	$user = new User();
	// Do they need logging in (checked remember me)?
	if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	// Check if we're in a subdirectory
	if(empty($directories[0])) unset($directories[0]);
	$directories = array_values($directories);

	$config_path = Config::get('core/path');

	if(!empty($config_path)){
		$config_path = explode('/', Config::get('core/path'));

		for($i = 0; $i < count($config_path); $i++){
			unset($directories[$i]);
		}

		define('CONFIG_PATH', '/' . Config::get('core/path'));

		$directories = array_values($directories);

	}
	$directory = implode('/', $directories);

	$directory = '/' . $directory;

	// Are we loading a profile page?
	if(FRIENDLY_URLS == true){
		if($directories[0] != 'profile'){
			$directory = strtok($directory, '?');
		} else {
			$directory = '/profile';
		}
	} else {
		if(isset($_GET['route']) && strpos($_GET['route'], 'profile') !== false){
			$profile = explode('/', $_GET['route']);
			$directories[1] = $profile[2];
			$route = '/profile';
		}
	}

	// Remove the trailing /
	if(strlen($directory) > 1) $directory = rtrim($directory, '/');

	// Language
	if(!$user->isLoggedIn() || !($user->data()->language_id)){
		// Default language for guests
		$cache->setCache('languagecache');
		$language = $cache->retrieve('language');

		if(!$language){
			define('LANGUAGE', 'EnglishUK');
			$language = new Language();
		} else {
			define('LANGUAGE', $language);
			$language = new Language('core', $language);
		}
	} else {
		// User selected language
		$language = $queries->getWhere('languages', array('id', '=', $user->data()->language_id));
		if(!count($language)){
			// Get default language
			$cache->setCache('languagecache');
			$language = $cache->retrieve('language');

			if(!$language){
				define('LANGUAGE', 'EnglishUK');
				$language = new Language();
			} else {
				define('LANGUAGE', $language);
				$language = new Language('core', $language);
			}
		} else {
			define('LANGUAGE', $language[0]->name);
			$language = new Language('core', $language[0]->name);
		}
	}

	// Site name
	$cache->setCache('sitenamecache');
	$sitename = $cache->retrieve('sitename');

	if(!$sitename){
		define('SITE_NAME', 'NamelessMC');
	} else {
		define('SITE_NAME', $sitename);
	}

	// Template
	$cache->setCache('templatecache');
	$template = $cache->retrieve('default');

	if(!$template){
		define('TEMPLATE', 'Default');
	} else {
		define('TEMPLATE', $template);
	}

	// Smarty
	$smarty = new Smarty();

	$template_path = 'custom/templates/' . TEMPLATE;
	$smarty->setTemplateDir($template_path);
	$smarty->setCompileDir('cache/templates_c');
	$smarty->assign('SITE_NAME', SITE_NAME);

	// Maintenance mode?
    $cache->setCache('maintenance_cache');
    $maintenance = $cache->retrieve('maintenance');
    if(isset($maintenance['maintenance']) && $maintenance['maintenance'] != 'false'){
        // Enabled
        // Admins only beyond this point
        if(!$user->isLoggedIn() || !$user->canViewACP()){
            // Maintenance mode
            if($directory != '/login') {
                require('maintenance.php');
                die();
            }
        } else {
            // Display notice to admin stating maintenance mode is enabled
            $smarty->assign('MAINTENANCE_ENABLED', $language->get('admin', 'maintenance_enabled'));
        }
    }

	// Navbar links
	$navigation = new Navigation();
	$cc_nav 	= new Navigation();
	$mod_nav	= new Navigation();

	// Add homepage to navbar
	$navigation->add('index', $language->get('general', 'home'), URL::build('/'));

	// Modules
	$cache->setCache('modulescache');
	$enabled_modules = $cache->retrieve('enabled_modules');

	$pages = new Pages();

	// Sort by priority
	usort($enabled_modules, function($a, $b) {
		return $a['priority'] - $b['priority'];
	});

	foreach($enabled_modules as $module){
		require('modules/' . $module['name'] . '/init.php');
	}

	// Perform tasks if the user is logged in
	if($user->isLoggedIn()){
		// Update a user's IP
		$ip = $user->getIP();
		if(filter_var($ip, FILTER_VALIDATE_IP)){
			$user->update(array(
				'lastip' => $ip
			));
		}

		// Insert it into the logs
		$user_ip_logged = $queries->getWhere('users_ips', array('ip', '=', $ip));
		if(!count($user_ip_logged)){
			// Create the entry now
			$queries->create('users_ips', array(
				'user_id' => $user->data()->id,
				'ip' => $ip
			));
		} else {
			if(count($user_ip_logged) > 1){
				foreach($user_ip_logged as $user_ip){
					// Check to see if it's been logged by the current user
					if($user_ip->user_id == $user->data()->id){
						// Already logged for this user
						$already_logged = true;
						break;
					}
				}

				if(!isset($already_logged)){
					// Not yet logged, do so now
					$queries->create('users_ips', array(
						'user_id' => $user->data()->id,
						'ip' => $ip
					));
				}

			} else {
				// Does the entry already belong to the current user?
				if($user_ip_logged[0]->user_id != $user->data()->id){
					$queries->create('users_ips', array(
						'user_id' => $user->data()->id,
						'ip' => $ip
					));
				}
			}
		}

		// Update last online
		// Update user last online
		$queries->update('users', $user->data()->id, array(
			'last_online' => date('U')
		));

	}
}
