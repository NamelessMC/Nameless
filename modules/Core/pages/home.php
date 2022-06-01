<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Home page
 */

// Always define page name
const PAGE = 'index';
$page_title = $language->get('general', 'home');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->assets()->include([
    DARK_MODE
        ? AssetTree::PRISM_DARK
        : AssetTree::PRISM_LIGHT,
    AssetTree::TINYMCE_SPOILER,
]);

if (Session::exists('home')) {
    $smarty->assign('HOME_SESSION_FLASH', Session::flash('home'));
    $smarty->assign('SUCCESS_TITLE', $language->get('general', 'success'));
}

if (Session::exists('home_error')) {
    $smarty->assign('HOME_SESSION_ERROR_FLASH', Session::flash('home_error'));
    $smarty->assign('ERROR_TITLE', $language->get('general', 'error'));
}

$home_type = Util::getSetting('home_type');

$smarty->assign('HOME_TYPE', $home_type);

if ($home_type === 'news') {
    foreach ($front_page_modules as $module) {
        require(ROOT_PATH . '/' . $module);
    }
} else if ($home_type === 'custom') {
    $smarty->assign('CUSTOM_HOME_CONTENT', Util::getSetting('home_custom_content'));
}

// Assign to Smarty variables
$smarty->assign('SOCIAL', $language->get('general', 'social'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('index.tpl', $smarty);
