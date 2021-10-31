<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Site cookies page
 */

// Always define page name
define('PAGE', 'cookies');
$page_title = $cookie_language->get('cookie', 'cookie_notice');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => []
]);

$template->addJSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => []
]);

// Retrieve cookie notice from database
$cookie_notice = DB::getInstance()->selectQuery('SELECT value FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->first()->value;

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $staffcp_nav), $widgets, $template);

$smarty->assign([
    'COOKIE_NOTICE_HEADER' => $cookie_language->get('cookie', 'cookie_notice'),
    'COOKIE_NOTICE' => Output::getPurified($cookie_notice),
    'UPDATE_SETTINGS' => $cookie_language->get('cookie', 'update_settings'),
]);

$template->addJSScript(file_get_contents(ROOT_PATH . '/modules/Cookie Consent/assets/js/configure.js'));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('cookies.tpl', $smarty);
