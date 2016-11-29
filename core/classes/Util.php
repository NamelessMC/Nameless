<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Util class
 */
class Util {
	
	// Escape a string
	// Params:	$string (string)	- string to be escaped (required)
    public static function escape($string){
    	return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
	
	// Recursively remove a directory
	// Params: $directory (string)	- path to directory to remove (required)
	public static function recursiveRemoveDirectory($directory){
		if((strpos($directory, 'custom') !== false)){ // safety precaution, only allow deleting files in "custom" directory
			// alright to proceed
		} else {
			return false;
		}
		
		foreach(glob($directory . '/*') as $file){
			if(is_dir($file)) { 
				recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
		return true;
	}

	// Returns start and finish array keys (starting from 0) based on page number
	// Params: $p (int)	- page number (required)
	public static function PaginateArray($p){
		if($p == 1){
			$s = 0;
			$f = 9;
		} else {
			$s = ($p - 1) * 10; // Eg, if page 2, start at 10; if page 3, start at 20
			$f = $s + 9; // Eg, if page 2, finish at 29; if page 3, finish at 29
		}
		return array($s, $f);
	}
	
	// Recursively check to see if an item ($needle) is in an array ($haystack)
	// Params: 	$needle (mixed)		- item to search for in array (required)
	//			$haystack (array)	- array to search through for item (required)
	//			$strict (boolean)	- use PHP equals (false) or identical (true) (defaults to false) (optional)
	public static function in_array_r($needle, $haystack, $strict = false){
		foreach($haystack as $item){
			if(($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}
	
}
