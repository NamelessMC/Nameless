<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Modules class
 */

abstract class Module {
	private static $_modules = array();
	private $_name, $_author, $_version, $_nameless_version;

	public function __construct($module, $name, $author, $version, $nameless_version){
		self::$_modules[] = $module;
		$this->_name = $name;
		$this->_author = $author;
		$this->_version = $version;
		$this->_nameless_version = $nameless_version;
	}

	protected final function setName($name){
		$this->_name = $name;
	}

	protected final function setVersion($version){
		$this->_version = $version;
	}

	protected final function setNamelessVersion($nameless_version){
		$this->_nameless_version = $nameless_version;
	}

	protected final function setAuthor($author){
		$this->_author = $author;
	}

	abstract function onInstall();
	abstract function onUninstall();
	abstract function onEnable();
	abstract function onDisable();
	abstract function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);

	public static function loadPage($user, $pages, $cache, $smarty, $navs, $widgets, $template = null){
		foreach(self::$_modules as $module){
			$module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
		}
	}

	public static function getModules(){
		return self::$_modules;
	}

	public function getName(){
		return $this->_name;
	}

	public function getAuthor(){
		return $this->_author;
	}

	public function getVersion(){
		return $this->_version;
	}

	public function getNamelessVersion(){
		return $this->_nameless_version;
	}

}