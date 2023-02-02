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

$template_path = Constants::ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE;
$smarty->setCompileDir(Constants::ROOT_PATH . '/cache/templates_c');

if (file_exists(Constants::ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php')) {
    $smarty->setTemplateDir(Constants::ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE);

    require(Constants::ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php');
} else {
    $smarty->setTemplateDir(Constants::ROOT_PATH . '/custom/panel_templates/Default');

    require(Constants::ROOT_PATH . '/custom/panel_templates/Default/template.php');
}

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
