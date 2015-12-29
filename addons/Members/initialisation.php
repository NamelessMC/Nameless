<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  License: MIT
 */

// Initialise the members addon
// We've already checked to see if it's enabled

require('addons/members/language.php');

// Enabled, add links to navbar
$navbar_array[] = array('members' => $members_language['members']);
