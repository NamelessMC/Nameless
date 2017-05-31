<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Cookie class
 */
class Cookie {
	
	// Check the specified cookie exists (returns true or false)
	// Params: $name (string) - name of cookie to check
	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true : false;
	}
	
	// Return the value of the specified cookie
	// Params: $name (string) - name of cookie to return value of
	public static function get($name){
		return $_COOKIE[$name];
	}
	
	// Create a new cookie
	// Params: $name (string) - name of cookie to create
	//         $value (string) - value to store in cookie
	//         $expiry (integer) - when does the cookie expire?
	public static function put($name, $value, $expiry){
		if(setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}
		return false;
	}
	
	// Delete a cookie
	// Params: $name (string) - name of cookie to delete
	public static function delete($name){
		if(setcookie($name, '', time() - 1, '/')){
			return true;
		}
		return false;
	}
	
}