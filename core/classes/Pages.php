<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Pages class
 */ 
class Pages {
	
	// Variables
	private $_pages = array();
	
	// Construct Pages class
	public function __construct(){
		
	}
	
	// Defines a page and assigns it to a module
	// Params:  $module (string)	- module which the page belongs to
	// 			$url (string) 		- contains URL string
	//			$file (string)		- contains path (from module folder) to page file
    //          $name (string)      - contains name of page (optional)
    //          $widgets (boolean)  - can widgets be used on the page? Default false
	public function add($module, $url, $file, $name = '', $widgets = false){
		$this->_pages[$url] = array(
			'module' => $module, 
			'file' => $file,
            'name' => $name,
            'widgets' => $widgets
		);
	}

	// Defines a custom page
    // Params:  $url (string)       - contains URL string
    //          $name (string)      - contains name of page
    //          $widgets (boolean)  - can widgets be used on the page? Default false
    public function addCustom($url, $name, $widgets = false){
	    $this->_pages[$url] = array(
            'module' => 'Core',
            'file' => 'custom.php',
            'name' => $name,
            'widgets' => $widgets,
            'custom' => true
        );
    }

	// Returns the array of all pages
	// No params
	public function returnPages(){
		return $this->_pages;
	}

	// Return pages which allow widgets
    // No params
    public function returnWidgetPages(){
	    $ret = array();
	    foreach($this->_pages as $page)
	        if(!empty($page['name']) && $page['widgets'] === true)
	            $ret[$page['module']][$page['name']] = true;

        return $ret;
    }
	
}