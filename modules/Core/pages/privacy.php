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
const PAGE = 'privacy';
$page_title = $language->get('general', 'privacy_policy');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Retrieve privacy policy from database
$policy = DB::getInstance()->get('privacy_terms', ['name', 'privacy'])->results();
if (!count($policy)) {
    $policy = Output::getPurified(Util::getSetting('privacy_policy'));
} else {
    $policy = Output::getPurified($policy[0]->value);
}

$smarty->assign([
    'PRIVACY_POLICY' => $language->get('general', 'privacy_policy'),
    'POLICY' => $policy
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('privacy.tpl', $smarty);
