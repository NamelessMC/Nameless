<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Portal page
 */

// Always define page name
const PAGE = 'portal';
$page_title = $language->get('general', 'home');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$smarty->assign([
    'GENERAL_SETTINGS_URL' => URL::build('/panel/core/general_settings'),
]);

// Display template
$template->displayTemplate('portal.tpl', $smarty);
