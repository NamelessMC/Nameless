<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
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
	
	// Generate a salt with a specified length
	// Params: $length (int) - length of salt to return
	public static function salt($length){
		return mcrypt_create_iv($length);		
	}
	
	// Generate a unique hash
	// No parameters
	public static function unique(){
		return self::make(uniqid());
	}
}