<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Navigation class
 */
class Navigation {
	
	// Variables
	private $_topNavbar = array(),
			$_footerNav = array();
	
	// Construct Navigation class
	public function __construct(){
		
	}

	// Add a simple item to the navigation
	// Params: 	$name (string)		- unique name for the navbar item, if the page name equals this the item will display as active (required)
	//			$title (string)		- item title (required)
	//			$link (string)		- HTML href attribute, can be link built with URL class or hyperlink (required)
	//			$location (string) 	- location to add item to, either 'top' or 'footer' (defaults to 'top')
	//			$target (string)	- HTML target attribute (eg '_blank') (optional)
	//          $order (int)        - nav item order (default 10)
    //          $icon (string)      - icon to prepend to nav item (default '')
	public function add($name, $title, $link, $location = 'top', $target = null, $order = 10, $icon = ''){
		// Add the link to the navigation
		if($location == 'top'){
			// Add to top navbar
			$this->_topNavbar[$name] = array(
				'title' => $title,
				'link' => $link,
				'target' => $target,
                'order' => $order,
                'icon' => $icon
			);
			
		} else {
			// Add to footer navigation
			$this->_footerNav[$name] = array(
				'title' => $title,
				'link' => $link,
				'target' => $target,
                'order' => $order,
                'icon' => $icon
			);
		}
	}
	
	// Add a dropdown menu to the navigation
	// Params:	$name (string)		- unique name for the navbar (required)
	//			$title (string)		- dropdown title (required)
	//			$location (string)	- location to add item to, either 'top' or 'footer' (defaults to 'top'),
    //          $order (int)        - nav item order (default 10)
    //          $icon (string)      - icon to prepend to nav item (default '')
	public function addDropdown($name, $title, $location = 'top', $order = 10, $icon = ''){
		// Add the dropdown
		if($location == 'top'){
			// Navbar
			$this->_topNavbar[$name] = array(
				'type' => 'dropdown',
				'title' => $title,
				'items' => array(),
                'order' => $order,
                'icon' => $icon
			);
			
		} else {
			// Footer
			$this->_footerNav[$name] = array(
				'type' => 'dropdown',
				'title' => $title,
				'items' => array(),
                'order' => $order,
                'icon' => $icon
			);
			
		}
	}
	
	// Add an item to a menu dropdown
	// Params:	$dropdown (string) 	- name of dropdown to add item to (required)
	//			$name (string)		- unique name for the item, if the page name equals this the item will display as active (required)
	//			$title (string)		- item title (required)
	//			$link (string)		- HTML href attribute, can be link built with URL class or hyperlink (required)
	//			$location (string)	- location to add item to, either 'top' or 'footer' (defaults to 'top')
	//			$target (string)	- HTML target attribute (eg '_blank') (optional)
    //          $icon (string)      - icon to prepend to nav item (default '')
	//          $order (int)        - nav item order (default 10)
	public function addItemToDropdown($dropdown, $name, $title, $link, $location = 'top', $target = null, $icon = '', $order = 10){
		// Add the item
		if($location == 'top' && isset($this->_topNavbar[$dropdown])){
			// Navbar
			$this->_topNavbar[$dropdown]['items'][$name] = array(
				'title' => $title,
				'link' => $link,
				'target' => $target,
                'icon' => $icon,
				'order' => $order
			);
			
		} else if(isset($this->_footerNav[$dropdown])){
			// Footer
			$this->_footerNav[$dropdown]['items'][$name] = array(
				'title' => $title,
				'link' => $link,
				'target' => $target,
                'icon' => $icon,
				'order' => $order
			);
			
		}
		
		// Unable to add item to dropdown, might not have been initialised
		return false;
	}
	
	// Return top navigation - returns array to pass to template
	// Params: $location (string) - either 'top' or 'footer' (defaults to 'top')
	public function returnNav($location = 'top'){
		$return = array(); // String to return
		if($location == 'top'){
			if(count($this->_topNavbar)){
				foreach($this->_topNavbar as $key => $item){
					$return[$key] = $item;
					if(defined('PAGE') && PAGE == $key){
						$return[$key]['active'] = true;
					}

					// Sort dropdown
					if(isset($return[$key]['items'])){
						if(count($return[$key]['items'])){
							uasort($return[$key]['items'], function($a, $b){
								$result = 0;
								if($a['order'] > $b['order']){
									$result = 1;
								} else if($a['order'] < $b['order']){
									$result = -1;
								}
								return $result;
							});
						} else {
							unset($return[$key]);
						}
					}
				}
			}
		} else {
			if(count($this->_footerNav)){
				foreach($this->_footerNav as $key => $item){
					$return[$key] = $item;
					if(defined('PAGE') && PAGE == $key){
						$return[$key]['active'] = true;
					}

					// Sort dropdown
					if(isset($return[$key]['items']) && count($return[$key]['items'])){
						uasort($return[$key]['items'], function($a, $b){
							$result = 0;
							if($a['order'] > $b['order']){
								$result = 1;
							} else if($a['order'] < $b['order']){
								$result = -1;
							}
							return $result;
						});
					}
				}
			}
		}

        uasort($return, function($a, $b){
	        $result = 0;
	        if($a['order'] > $b['order']){
		        $result = 1;
	        } else if($a['order'] < $b['order']){
		        $result = -1;
	        }
	        return $result;
        });

		return $return;
	}
}
