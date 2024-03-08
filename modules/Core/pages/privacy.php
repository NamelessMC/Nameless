<?php
/**
 * Privacy policy page
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
const PAGE = 'privacy';
$page_title = $language->get('general', 'privacy_policy');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Retrieve privacy policy from database
$policy = DB::getInstance()->get('privacy_terms', ['name', 'privacy']);
if (!$policy->count()) {
    $policy = Output::getPurified(Settings::get('privacy_policy'));
} else {
    $policy = Output::getPurified($policy->first()->value);
}

$template->getEngine()->addVariables([
    'PRIVACY_POLICY' => $language->get('general', 'privacy_policy'),
    'POLICY' => $policy
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('privacy');
