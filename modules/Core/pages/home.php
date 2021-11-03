<?php
/*
 *	Made by Samerton
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

$template->addCSSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => [],
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => []
]);

$template->addJSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => [],
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => []
]);

if (Session::exists('home')) {
    $smarty->assign('HOME_SESSION_FLASH', Session::flash('home'));
    $smarty->assign('SUCCESS_TITLE', $language->get('general', 'success'));
}

if (Session::exists('home_error')) {
    $smarty->assign('HOME_SESSION_ERROR_FLASH', Session::flash('home_error'));
    $smarty->assign('ERROR_TITLE', $language->get('general', 'error'));
}

if (isset($front_page_modules)) {
	foreach ($front_page_modules as $module) {
		require(ROOT_PATH . '/' . $module);
	}
}

// Assign to Smarty variables
$smarty->assign('SOCIAL', $language->get('general', 'social'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('index.tpl', $smarty);
