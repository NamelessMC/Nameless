<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 */

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

ini_set('open_basedir', ROOT_PATH . PATH_SEPARATOR  . $tmp_dir);

// Get the directory the user is trying to access
$directory = $_SERVER['REQUEST_URI'];

$directories = explode("/", $directory);
$lim = count($directories);

// Installer?
if(is_file('pages/install.php')){
	if(isset($_GET['from']) && $_GET['from'] == 'install'){
		if(!unlink('pages/install.php')) die('Please delete <strong>pages/install.php</strong> before continuing.');
	} else {
		$page = 'install';

		require('core/init.php');
		require('pages/install.php');
		die();
	}
}

// Start initialising the page
require('core/init.php');

// Load the main page content
// Check if the page actually exists..
// First, check the contents of the URL
if(empty($directories[0]) && empty($directories[1])){
	// Must be the index page
	$page_path = 'pages/index.php';
} else {
	$n = 0;
	foreach($directories as $directory){
		if(strpos($directory, '?') !== false){
			$params = $directory; // Get URL parameters
			unset($directories[$n]);
		}
		$n++;
	}
	$page_path = 'pages' . implode('/', $directories);
	if(substr($page_path, -1) == "/"){
		$page_path = rtrim($page_path, '/');
	}
}

// Include the page
if(is_file($page_path)){
	require($page_path);
} else {
	if(is_file($page_path . '.php')){
		require($page_path . '.php');
	} else {
		if(is_dir($page_path)){
			if(file_exists($page_path . '/index.php')){
				require($page_path . '/index.php');
			}
		} else {
			// Profile page?
			if($directories[1] == 'profile'){
				if(isset($directories[2])) $profile = htmlspecialchars($directories[2]);
				require('pages/profile.php');
				// Kill the script
				die();
			}

			// API?
			if($directories[1] == 'api'){
				if(is_file('pages' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $directories[2] . DIRECTORY_SEPARATOR . 'index.php')){
					require('pages' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $directories[2] . DIRECTORY_SEPARATOR . 'index.php');
					die();
				}
			}

			// Custom page?
			$page_path = explode('/', $page_path);

			$custom_pages = $queries->getWhere('custom_pages', array('url', '=', '/' . $page_path[1]));

			if(count($custom_pages)){
				$page_title = $custom_pages[0]->title;
				$page_content = $custom_pages[0]->content;
				$page_id = $custom_pages[0]->id;

				if($custom_pages[0]->redirect == 1) $redirect_page = htmlspecialchars($custom_pages[0]->link);

				// For navbar
				$page = $custom_pages[0]->title;

				// Include the page
				require 'pages/extra.php';

				// Kill the page
				die();
			}

			// Doesn't exist without trailing '/', try again with trailing '/'
			$custom_pages = $queries->getWhere('custom_pages', array('url', '=', '/' . $page_path[1] . '/'));

			if(count($custom_pages)){
				$page_title = $custom_pages[0]->title;
				$page_content = $custom_pages[0]->content;
				$page_id = $custom_pages[0]->id;

				if($custom_pages[0]->redirect == 1) $redirect_page = htmlspecialchars($custom_pages[0]->link);

				// For navbar
				$page = $custom_pages[0]->title;

				// Include the page
				require 'pages/extra.php';

				// Kill the page
				die();
			}

			// 404
			require('404.php');
		}
	}
}
?>
