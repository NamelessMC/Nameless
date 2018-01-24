<?php 
/*
 *	Made by Samerton
 *  https://github.com/samerton
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Default themes for NamelessMC
 */

// Custom language
$default_theme_language = new Language(ROOT_PATH . '/modules/DefaultTheme/language');

// Define URLs which belong to this module
$pages->add('DefaultTheme', '/admin/defaulttheme', 'pages/admin.php');

// Add link to admin sidebar
if(!isset($admin_sidebar)) $admin_sidebar = array();
$admin_sidebar['defaulttheme'] = array(
	'title' => $default_theme_language->get('language', 'default_theme_title'),
	'url' => URL::build('/admin/defaulttheme')
);