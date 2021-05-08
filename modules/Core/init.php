<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Core initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_core');
if (!$module_installed) {
    // Hasn't been installed
    // Need to run the installer
    die('Run the installer first!');
}

require_once(ROOT_PATH . '/modules/Core/classes/hCaptcha.php');
require_once(ROOT_PATH . '/modules/Core/classes/Recaptcha2.php');
require_once(ROOT_PATH . '/modules/Core/classes/Recaptcha3.php');

require_once(ROOT_PATH . '/modules/Core/classes/CrafatarAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/CraftheadAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/CravatarAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/MCHeadsAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/MinotarAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/NamelessMCAvatarSource.php');
require_once(ROOT_PATH . '/modules/Core/classes/VisageAvatarSource.php');

require_once(ROOT_PATH . '/modules/Core/module.php');

$module = new Core_Module($language, $pages, $user, $queries, $navigation, $cache, $endpoints);
