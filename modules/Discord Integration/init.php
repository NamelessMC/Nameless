<?php
declare(strict_types=1);
/**
 *  Made by Unknown
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  TODO: Add description
 *
 * @var Language $language
 * @var Pages $pages
 * @var Endpoints $endpoints
 */

require_once ROOT_PATH . '/modules/Discord Integration/module.php';

$module = new Discord_Module($language, $pages, $endpoints);
