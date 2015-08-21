<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Initialise the Google Analytics addon
// We've already checked to see if it's enabled

// Add the script to the top of every page
$ga_script = $queries->getWhere('settings', array('name', '=', 'ga_script'));
$ga_script = $ga_script[0]->value;