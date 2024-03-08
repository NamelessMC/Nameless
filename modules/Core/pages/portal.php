<?php
/**
 * Portal page
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
const PAGE = 'portal';
$page_title = $language->get('general', 'home');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

$template->getEngine()->addVariables([
    'GENERAL_SETTINGS_URL' => URL::build('/panel/core/general_settings'),
]);

// Display template
$template->displayTemplate('portal');
