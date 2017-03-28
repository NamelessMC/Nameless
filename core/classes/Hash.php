<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Hash class
 */
class Hash {
	
	// Generate a hash using sha256
	// Params: $string (string) - string to hash
	//         $salt (string)   - salt (optional)
	public static function make($string, $salt = ''){
		return hash('sha256', $string . $salt);
	}
	
	// Generate a unique hash
	// No parameters
	public static function unique(){
		return self::make(uniqid());
	}
}