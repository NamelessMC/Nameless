<?php
/*
 *	Made by Samerton
 *  https://github.com/samerton
 *  NamelessMC version 2.0.0-pr6
 *
 *  License: MIT
 *
 *  Default themes for NamelessMC
 */

class DefaultTheme_Module extends Module {
	private $_language;

	public function __construct($pages, $language){
		$name = 'DefaultTheme';
		$author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
		$module_version = '2.0.0-pr6';
		$nameless_version = '2.0.0-pr6';

		$this->_language = $language;

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('DefaultTheme', '/panel/defaulttheme', 'pages/panel.php');
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

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template){
		if(defined('BACK_END')){
			if($user->hasPermission('admincp.styles.templates')){
				$cache->setCache('panel_sidebar');
				if(!$cache->isCached('default_themes_order')){
					$order = 30;
					$cache->store('default_themes_order', 30);
				} else {
					$order = $cache->retrieve('default_themes_order');
				}

				if(!$cache->isCached('default_themes_icon')){
					$icon = '<i class="nav-icon fas fa-paint-brush"></i>';
					$cache->store('default_themes_icon', $icon);
				} else
					$icon = $cache->retrieve('default_themes_icon');

				$navs[2]->add('default_themes_divider', mb_strtoupper($this->_language->get('language', 'default_theme_title')), 'divider', 'top', null, $order, '');
				$navs[2]->add('default_themes', $this->_language->get('language', 'default_theme_title'), URL::build('/panel/defaulttheme'), 'top', null, ($order + 0.1), $icon);
			}
		}
	}
}