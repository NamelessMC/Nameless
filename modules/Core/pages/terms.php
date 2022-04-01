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
const PAGE = 'terms';
$page_title = $language->get('user', 'terms_and_conditions');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Retrieve terms from database
$site_terms = $queries->getWhere('privacy_terms', ['name', '=', 'terms']);
if (!count($site_terms)) {
    $site_terms = $queries->getWhere('settings', ['name', '=', 't_and_c_site']);
}
$site_terms = Output::getPurified($site_terms[0]->value);

$nameless_terms = $queries->getWhere('settings', ['name', '=', 't_and_c']);
$nameless_terms = Output::getPurified($nameless_terms[0]->value);

$smarty->assign([
    'TERMS' => $language->get('user', 'terms_and_conditions'),
    'SITE_TERMS' => $site_terms,
    'NAMELESS_TERMS' => $nameless_terms
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{{time}}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('terms.tpl', $smarty);
