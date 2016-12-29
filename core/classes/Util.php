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
	
	// Check to see if a given date is valid, returning true/false accordingly
	// Params: 	$date (string) 		- date to check
	//			$format (string) 	- date format to use (optional, defaults to 'm/d/Y')
	public static function validateDate($date, $format = 'm/d/Y'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	// Return an array containing all timezone lists
	// No params
	public static function listTimezones(){
		// Array to contain timezones
		$timezones = array();
		
		// Array to contain offsets
		$offsets = array();
		
		// Get all PHP timezones
		$all_timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		
		// Get current UTC time to calculate offset
		$current = new DateTime('now', new DateTimeZone('UTC'));
		
		foreach($all_timezones as $timezone){
			// Get timezone offset
			$current->setTimezone(new DateTimeZone($timezone));
			
			// Add offset to offset array
			$offsets[] = $current->getOffset();
			
			// Format timezone offset
			$offset = 'GMT ' . intval($current->getOffset() / 3600) . ':' . str_pad(abs(intval($offset % 3600 / 60)), 2, 0);
			
			// Prettify timezone name
			$name = Output::getClean(str_replace(array('/', '_'), array(', ', ' '), $timezone));
			
			// Add to timezones array
			$timezones[$timezone] = array('offset' => $offset, 'name' => $name, 'time' => $current->format('H:i'));
			
		}
		
		array_multisort($offsets, $timezones);
		
		return $timezones;
	}
	
}