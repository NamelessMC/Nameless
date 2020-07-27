<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Display either homepage or portal
 */

// Home page or portal?
$cache->setCache('portal_cache');
$use_portal = $cache->retrieve('portal');

if($use_portal !== 1) require('home.php');
else require('portal.php');
