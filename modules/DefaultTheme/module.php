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

class DefaultTheme_Module extends Module {
	public function __construct($pages){
		$name = 'DefaultTheme';
		$author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
		$module_version = '2.0.0-pr5';
		$nameless_version = '2.0.0-pr5';

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('DefaultTheme', '/admin/defaulttheme', 'pages/admin.php');
	}

	public function onInstall(){
		// Not necessary for Core
	}

	public function onUninstall(){
		// Not necessary for Core
	}

	public function onEnable(){
		// Not necessary for Core
	}

	public function onDisable(){
		// Not necessary for Core
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets){

	}
}