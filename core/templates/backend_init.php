<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Backend template initialisation
 */

const BACK_END = true;

$template_path = ROOT_PATH . '/custom/panel_templates/Default';
$smarty->setTemplateDir($template_path);
$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

require(ROOT_PATH . '/custom/panel_templates/Default/template.php');

$cache->setCache('backgroundcache');
$logo_image = $cache->retrieve('logo_image');

if (!empty($logo_image)) {
    $smarty->assign('PANEL_LOGO_IMAGE', Output::getClean($logo_image));
}

$favicon_image = $cache->retrieve('favicon_image');

if (!empty($favicon_image)) {
    $smarty->assign('FAVICON', Output::getClean($favicon_image));
}

$smarty->assign('TITLE', $page_title);
