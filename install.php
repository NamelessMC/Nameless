<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Installer
 */

// Definitions
if (!defined('PATH')) {
    define('PATH', '/');
    define('ROOT_PATH', __DIR__);
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
    && is_file('custom/languages/' . $_SESSION['installer_language'] . '.json')
) {
    $language_short_code = $_SESSION['installer_language'];
} else {
    // Require default language (English UK)
    $language_short_code = 'en_UK';
}

$language = new Language('core', $language_short_code);

// Get installation path
$install_path = substr(str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))), 1);

if (!isset($CONFIG['installed'])) {
    if (isset($_GET['language'])) {
        // Set language
        if (is_file('custom/languages/' . $_GET['language'] . '.json')) {
            $_SESSION['installer_language'] = $_GET['language'];
            die('OK');
        }
        die($_GET['language'] . ' is not a valid language');
    }
    require(ROOT_PATH . '/core/installation/installer.php');
} else {
    require(ROOT_PATH . '/core/installation/already_installed.php');
}
