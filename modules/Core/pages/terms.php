<?php
/*
 *  Made by Samerton
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
$site_terms = DB::getInstance()->get('privacy_terms', ['name', 'terms'])->results();
if (!count($site_terms)) {
    $site_terms = Util::getSetting('t_and_c_site');
} else {
    $site_terms = $site_terms[0]->value;
}
$site_terms = Output::getPurified($site_terms);

$nameless_terms = Output::getPurified(Util::getSetting('t_and_c'));

$smarty->assign([
    'TERMS' => $language->get('user', 'terms_and_conditions'),
    'SITE_TERMS' => $site_terms,
    'NAMELESS_TERMS' => $nameless_terms
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('terms.tpl', $smarty);
