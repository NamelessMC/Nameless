<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
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

require_once(ROOT_PATH . '/modules/Core/module.php');

// TODO: start_line is negative when issue LOC is less than line_buffer
//ErrorHandler::catchException(null, 'idk', 'modules/Core/module.php', 19);

$module = new Core_Module($language, $pages, $user, $queries, $navigation, $cache, $endpoints);
