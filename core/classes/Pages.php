<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
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
	public function add($module, $url, $file){	
		$this->_pages[$url] = array(
			'module' => $module, 
			'file' => $file
		);
	}

	// Returns the array of all pages
	// No params
	public function returnPages(){
		return $this->_pages;
	}
	
}