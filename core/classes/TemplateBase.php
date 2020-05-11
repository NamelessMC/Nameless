<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr7
 *
 *  License: MIT
 *
 *  Base template class
 */
abstract class TemplateBase {
	protected $_name = '', $_version = '', $_nameless_version = '', $_author = '', $_settings = '', $_css = array(), $_js = array();

	public function __construct($name, $version, $nameless_version, $author){
		$this->_name = $name;
		$this->_version = $version;
		$this->_nameless_version = $nameless_version;
		$this->_author = $author;
	}

	public abstract function onPageLoad();

	public function addCSSFiles($files){
		if(is_array($files) && count($files)){
			foreach($files as $href => $file)
				$this->_css[] = '<link rel="stylesheet" href="' . $href . '"' . (isset($file['integrity']) ? ' integrity="' . $file['integrity'] . '"' : '') . (isset($file['crossorigin']) ? ' crossorigin="' . $file['crossorigin'] . '"' : '') . '>';
		}
	}

	public function addCSSStyle($style = null){
		if($style)
			$this->_css[] = '<style>' . $style . '</style>';
	}

	public function addJSFiles($files){
		if(is_array($files) && count($files)){
			foreach($files as $href => $file)
				$this->_js[] = '<script type="text/javascript" src="' . $href . '"' . (isset($file['integrity']) ? ' integrity="' . $file['integrity'] . '"' : '') . (isset($file['crossorigin']) ? ' crossorigin="' . $file['crossorigin'] . '"' : '') . '></script>';
		}
	}

	public function addJSScript($script = null){
		if($script)
			$this->_js[] = '<script type="text/javascript">' . $script . '</script>';
	}

	public function getCSS(){
		return $this->_css;
	}

	public function getJS(){
		return $this->_js;
	}

	public function getName(){
		return $this->_name;
	}

	public function getVersion(){
		return $this->_version;
	}

	public function getNamelessVersion(){
		return $this->_nameless_version;
	}

	public function getAuthor(){
		return $this->_author;
	}

	public function getSettings(){
		return $this->_settings;
	}

	public function displayTemplate($template, $smarty){
		// Template Smarty variables
		$smarty->assign(array(
			'TEMPLATE_CSS' => $this->getCSS(),
			'TEMPLATE_JS' => $this->getJS()
		));
		$smarty->display($template);
	}

	public function getTemplate($template, $smarty){
		// Template Smarty variables
		$smarty->assign(array(
			'TEMPLATE_CSS' => $this->getCSS(),
			'TEMPLATE_JS' => $this->getJS()
		));
		return $smarty->fetch($template);
	}

}