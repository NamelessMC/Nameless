<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel users page
 */

if (!$user->handlePanelPageLoad('admincp.users.edit')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'users';
const EDITING_USER = true;
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (isset($_POST['provider_name'], $_POST['user_id'])) {

                OAuth::getInstance()->unlinkProviderForUser($_POST['user_id'], $_POST['provider_name']);

                Session::flash('oauth_success', str_replace('{x}', ucfirst($_POST['provider_name']), $language->get('admin', 'unlink_account_success')));
            }
        }
        die();
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
}

$view_user = new User($_GET['id']);
if (!$view_user->data()) {
    Redirect::to(URL::build('/panel/users'));
}
$user_query = $view_user->data();

$oauth_providers = OAuth::getInstance()->getProvidersAvailable();
$user_oauth_providers = OAuth::getInstance()->getAllProvidersForUser($user_query->id);

$user_providers_template = [];
foreach ($user_oauth_providers as $user_provider) {
    $user_providers_template[$user_provider->provider] = $user_provider;
}

if (Session::exists('oauth_success')) {
    $smarty->assign([
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'SUCCESS' => Session::flash('oauth_success'),
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'EDITING_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'editing_user_x')),
    'USER_ID' => $user_query->id,
    'BACK_LINK' => URL::build('/panel/user/' . $user_query),
    'BACK' => $language->get('general', 'back'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'ARE_YOU_SURE_MESSAGE' => $language->get('admin', 'unlink_account_confirm'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'DELETE_LINK' => URL::build('/panel/users/oauth/', 'action=delete'),
    'OAUTH_PROVIDERS' => $oauth_providers,
    'USER_OAUTH_PROVIDERS' => $user_providers_template,
    'UNLINK' => $language->get('admin', 'unlink'),
    'NAME' => $language->get('admin', 'name'),
    'IDENTIFIER' => $language->get('admin', 'identifier'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_oauth.tpl', $smarty);
