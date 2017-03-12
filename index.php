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

// Ensure PHP version >= 5.4
if(version_compare(phpversion(), '5.4', '<')){
	die('NamelessMC is not compatible with PHP versions older than 5.4');
}

// Start page load timer
$start = microtime(true);

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

try {
	// Start initialising the page
	require('core/init.php');
}
catch(Exception $e) {
	die($e->getMessage());
}

if(!isset($GLOBALS['config']['core']) && is_file('install.php')) {
	Redirect::to('install.php');
}

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
	if(!isset($_GET['route']) || $_GET['route'] == '/'){
		// Homepage
		require('modules/Core/pages/index.php');
	} else {
		if(!isset($route)) $route = rtrim($_GET['route'], '/');

		// Check modules
		$modules = $pages->returnPages();

		// Include the page
		if(array_key_exists($route, $modules)){
			$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', $modules[$route]['module'], $modules[$route]['file']));
			if(!file_exists($path)) require('404.php'); else require($path);
			die();
		} else {
			// 404
			require('404.php');
		}

	}
}
