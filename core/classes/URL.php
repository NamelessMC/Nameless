<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  URL class
 */ 
class URL {
	
	// Returns a URL in the correct format (friendly or not)
	// Params:  $url (string) - contains the URL which will be formatted
	// 			$params (string) - contains string with URL parameters (optional)
	public static function build($url, $params = ''){	
		if((defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) || (!defined('FRIENDLY_URLS') && Config::get('core/friendly') == true)){
			// Friendly URLs are enabled, we can just use the URL passed through
			// Check for params
			if($params != ''){
				$params = '?' . $params;
			}
			return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . $url . $params;
		} else {
			// Friendly URLs are disabled, we need to change it
			// Check for params
			if($params != ''){
				return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . 'index.php?route=' . $url . '&' . $params;
			} else {
				return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . 'index.php?route=' . $url;
			}
		}
	}

}