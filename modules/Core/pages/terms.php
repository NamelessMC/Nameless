<?php
/**
 * Site terms page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Always define page name
const PAGE = 'terms';
$page_title = $language->get('user', 'terms_and_conditions');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Retrieve terms from database
$site_terms = DB::getInstance()->get('privacy_terms', ['name', 'terms']);
if (!$site_terms->count()) {
    $site_terms = Settings::get('t_and_c_site');
} else {
    $site_terms = $site_terms->first()->value;
}
$site_terms = Output::getPurified($site_terms);

$nameless_terms = Output::getPurified(Settings::get('t_and_c'));

$template->getEngine()->addVariables([
    'TERMS' => $language->get('user', 'terms_and_conditions'),
    'SITE_TERMS' => $site_terms,
    'NAMELESS_TERMS' => $nameless_terms
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('terms');
