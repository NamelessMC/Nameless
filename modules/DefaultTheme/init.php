<?php 
/*
 *	Made by Samerton
 *  https://github.com/samerton
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Default themes for NamelessMC
 */

// Custom language
$default_theme_language = new Language(ROOT_PATH . '/modules/DefaultTheme/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/DefaultTheme/module.php');
$module = new DefaultTheme_Module($pages, $default_theme_language);