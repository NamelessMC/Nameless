<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Installer
 */

// Definitions
if(!defined('PATH')){
    define('PATH', '/');
    define('ROOT_PATH', dirname(__FILE__));
}
$page = 'install';

// Start initialising the page
require('core/init.php');

// Disable error reporting
error_reporting(0);
ini_set('display_errors', 0);

// Set default timezone to prevent potential issues
date_default_timezone_set('Europe/London');

// Get installation path
$install_path = substr(str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))), 1);

if(!isset($GLOBALS['config']['core'])) {
    require(ROOT_PATH . '/core/installation/views/installer.view.php');
} else {
    require(ROOT_PATH . '/core/installation/views/already_installed.view.php');
}
