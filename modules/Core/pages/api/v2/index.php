<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  Additions by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 * @var Language $language
 * @var string $route
 * @var Endpoints $endpoints
 * @var User $user
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var array $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

// Headers
header('Content-Type: application/json; charset=UTF-8');

$page_title = 'api';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

// Initialise
$api = new Nameless2API($route, $language, $endpoints);
