<?php
/**
 * Made by UNKNOWN
 * https://github.com/NamelessMC/Nameless/
 * NamelessMC version UNKNOWN
 *
 * License: MIT
 *
 * TODO: Add description
 *
 * @var Language $language
 * @var Pages $pages
 * @var Endpoints $endpoints
 */

require_once ROOT_PATH . '/modules/Discord Integration/module.php';

$module = new Discord_Module($language, $pages, $endpoints);
