<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT

 *  Session class
 */
class Session {

	// Check to see if a session exists
	// Params: $name (string) - contains the session variable name to check for
	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}

	// Create a new session variable
	// Params: $name (string)  - contains the session variable name that will be created
	//         $value (string) - contains the variable value to store
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
	
	// Get a session variable
	// Params: $name (string) - contains the session variable name to retrieve
	public static function get($name){
		return $_SESSION[$name];
	}	
	
	// Delete a session variable
	// Params: $name (string) - contains the session variable name to delete
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
	
	// Flash a session variable on the screen
	// Params: $name (string)   - contains the session variable name to flash on screen
	//         $string (string) - contains the message to flash on the screen (optional)
	public static function flash($name, $string = ''){
		// If the session exists, display it on screen ("flash" it)
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			// The session doesn't exist, set it as a variable now so it can be "flashed" in the future
			self::put($name, $string);
		}
	}
}