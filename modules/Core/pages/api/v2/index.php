<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 */

// Headers
header('Content-Type: application/json; charset=UTF-8');

$page_title = 'api';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

// Ensure API is actually enabled
if (!Util::getSetting(DB::getInstance(), 'use_api')) {
    die($language->get('api', 'api_disabled'));
}

// Initialise
$api = new Nameless2API($route, $language, $endpoints);
