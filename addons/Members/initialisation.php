<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  Modified by Samerton
 *  https://worldscapemc.co.uk
 *
 *  License: MIT
 * Copyright (c) 2016 Samerton
 */

// Initialise the members addon
// We've already checked to see if it's enabled

require('addons/Members/language.php');

// Enabled, add links to navbar
$navbar_array[] = array('members' => $members_language['members_icon'] . $members_language['members']);
