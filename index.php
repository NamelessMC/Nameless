<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Main index file
 */

// Ensure PHP version > 5.3
if(version_compare(phpversion(), '5.4', '<')){
	die('NamelessMC is not compatible with PHP versions older than 5.4');
}
 
// Start page load timer
$start = microtime(true);
 
// Temp
date_default_timezone_set('Europe/London');
 
// Definitions 
define('PATH', '/'); 
define('ROOT_PATH', dirname(__FILE__));
$page = 'Home';

if(!ini_get('upload_tmp_dir')){
	$tmp_dir = sys_get_temp_dir();
} else {
	$tmp_dir = ini_get('upload_tmp_dir');
}

ini_set('open_basedir', ROOT_PATH . PATH_SEPARATOR  . $tmp_dir . PATH_SEPARATOR . '/proc/stat');

// Get the directory the user is trying to access
$directory = $_SERVER['REQUEST_URI'];
$directories = explode("/", $directory);
$lim = count($directories);

// Installer?
if(is_file('pages/install.php')){
	if(isset($_GET['from']) && $_GET['from'] == 'install'){
		if(is_writable('pages/install.php')){
			unlink('pages/install.php');
		} else {
			die('Unable to automatically delete <strong>pages/install.php</strong>, please do so manually.');
		}
	} else {
		$page = 'install';
		
		require('core/init.php');
		require('pages/install.php');
		die();
	}
}

// Start initialising the page
require('core/init.php');

// Is the use of the .htaccess file enabled?
define('FRIENDLY_URLS', true);

if(FRIENDLY_URLS == true){
	// Load the main page content
	
	// Check modules
	$modules = $pages->returnPages();
	
	// Custom rules
	
	// Include the page
	if(array_key_exists($directory, $modules)){
		$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', $modules[$directory]['module'], $modules[$directory]['file']));
		if(!file_exists($path)) require('404.php'); else require($path);
		die();
	} else {
		// 404
		require('404.php');
	}

} else {
	// Friendly URLs are disabled
	if(!isset($_GET['route'])){
		// Homepage
		require('pages/index.php');
	} else {
		$route = array_filter(explode('/', $_GET['route']));
		
		$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'pages', htmlspecialchars(implode('/', $route)) . '.php'));
		
		if(file_exists($path)){
			// Load the page
			require($path);
		} else {
			// Check if it should be pointing to the index page, eg /admin/index.php
			if(file_exists(rtrim($path, '.php') . DIRECTORY_SEPARATOR . 'index.php')){
				require(rtrim($path, '.php') . DIRECTORY_SEPARATOR . 'index.php');
				die();
			}
			
			// Page doesn't exist, custom page?
			
			
			// 404
			require('404.php');
		}
		
	}
}