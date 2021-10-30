<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Installer
 */

// Definitions
if (!defined('PATH')) {
    define('PATH', '/');
    define('ROOT_PATH', dirname(__FILE__));
}
$page = 'install';

$install_path = str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));

// Start initialising the page
require(ROOT_PATH . '/core/init.php');

// Disable error reporting
error_reporting(0);
ini_set('display_errors', 0);

// Set default timezone to prevent potential issues
date_default_timezone_set('Europe/London');

// Select language
if (isset($_SESSION['installer_language'])
    && is_file('custom/languages/' . $_SESSION['installer_language'] . '/installer.php')
) {
    require(ROOT_PATH . '/custom/languages/' . $_SESSION['installer_language'] . '/version.php');
    require(ROOT_PATH . '/custom/languages/' . $_SESSION['installer_language'] . '/installer.php');
} else {
    // Require default language (EnglishUK)
    require(ROOT_PATH . '/custom/languages/EnglishUK/version.php');
    require(ROOT_PATH . '/custom/languages/EnglishUK/installer.php');
}

// Get installation path
$install_path = substr(str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))), 1);

if (!isset($CONFIG['installed'])) {
    if (isset($_GET['language'])) {
        // Set language
        if (is_file('custom/languages/' . $_GET['language'] . '/installer.php')) {
            $_SESSION['installer_language'] = $_GET['language'];
            die('OK');
        }
    }
    require(ROOT_PATH . '/core/installation/views/installer.view.php');
} else {
    require(ROOT_PATH . '/core/installation/views/already_installed.view.php');
}
