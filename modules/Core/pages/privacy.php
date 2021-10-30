<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Site terms page
 */

// Always define page name
define('PAGE', 'privacy');
$page_title = $language->get('general', 'privacy_policy');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => []
]);

$template->addJSFiles([
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => []
]);

// Retrieve privacy policy from database
$policy = $queries->getWhere('privacy_terms', ['name', '=', 'privacy']);
if (!count($policy)) {
	$policy = $queries->getWhere('settings', ['name', '=', 'privacy_policy']);
}
$policy = Output::getPurified($policy[0]->value);

$smarty->assign([
	'PRIVACY_POLICY' => $language->get('general', 'privacy_policy'),
	'POLICY' => $policy
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('privacy.tpl', $smarty);
