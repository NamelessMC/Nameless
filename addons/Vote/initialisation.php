<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 */

// Initialise the vote addon
// We've already checked to see if it's enabled

require('addons/Vote/language.php');

// Enabled, add links to navbar
$navbar_array[] = array('vote' => $vote_language['vote_icon'] . $vote_language['vote']);
