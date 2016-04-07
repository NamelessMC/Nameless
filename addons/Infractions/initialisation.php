<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Initialise the infractions addon
// We've already checked to see if it's enabled

// Require language
require('addons/Infractions/language.php');

// Enabled, add links to navbar
if(!isset($footer_nav_array)) $footer_nav_array = array();

$footer_nav_array['infractions'] = $infractions_language['infractions_icon'] . $infractions_language['infractions'];
