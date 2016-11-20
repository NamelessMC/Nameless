<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 */

// Initialise the donate addon
// We've already checked to see if it's enabled

// Require language
require('addons/Donate/language.php');

// Enabled, add links to navbar
$navbar_array[] = array('donate' => $donate_language['donate_icon'] . $donate_language['donate']);

// Custom CSS
$custom_css[] = '<link href="' . PATH . 'core/assets/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" rel="stylesheet">';
