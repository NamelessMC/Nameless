<?php
/**
 * Maintenance mode page
 *
 * @license MIT
 * @version 2.3.0
 *
 * @var Cache        $cache
 * @var FakeSmarty   $smarty
 * @var Language     $language
 * @var Navigation   $cc_nav
 * @var Navigation   $navigation
 * @var Navigation   $staffcp_nav
 * @var Pages        $pages
 * @var TemplateBase $template
 * @var User         $user
 * @var Widgets      $widgets
 */

// Check if maintenance mode is actually enabled
if (!Settings::get('maintenance')) {
    Redirect::back();
}

// Allow admin users to bypass maintenance mode
if ($user->isLoggedIn() && $user->canViewStaffCP()) {
    define('BYPASS_MAINTENANCE', true);
    Redirect::back();
}

$pages = new Pages();

const PAGE = 'maintenance';
$page_title = $language->get('errors', 'maintenance_title');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

if (!$user->isLoggedIn()) {
    $template->getEngine()->addVariables([
        'LOGIN' => $language->get('general', 'sign_in'),
        'LOGIN_LINK' => URL::build('/login'),
    ]);
}

// Assign template variables
$template->getEngine()->addVariables([
    'MAINTENANCE_TITLE' => $language->get('errors', 'maintenance_title'),
    'MAINTENANCE_MESSAGE' => Output::getPurified(Settings::get('maintenance_message', 'Maintenance mode is enabled.')),
    'RETRY' => $language->get('errors', 'maintenance_retry'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

// Display template
$template->displayTemplate('maintenance');
