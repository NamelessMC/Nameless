<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Display either homepage (with news or custom content) or portal
 */

// Home page or portal?
$cache->setCache('home_type');
$home_type = $cache->retrieve('type');

if ($home_type === 'portal') {
    require('portal.php');
} else {
    require('home.php');
}
