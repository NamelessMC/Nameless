<?php
/*
 *  Made by Samerton
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

                NamelessOAuth::getInstance()->unlinkProviderForUser($_POST['user_id'], $_POST['provider_name']);

                Session::flash('oauth_success', $language->get('admin', 'unlink_account_success', ['provider' => ucfirst($_POST['provider_name'])]));
            }
        }
        die();
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
}

$view_user = new User($_GET['id']);
if (!$view_user->exists()) {
    Redirect::to(URL::build('/panel/users'));
}
$user_query = $view_user->data();

$oauth_providers = NamelessOAuth::getInstance()->getProvidersAvailable();
$user_oauth_providers = NamelessOAuth::getInstance()->getAllProvidersForUser($user_query->id);

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
    'EDITING_USER' => $language->get('admin', 'editing_user_x', [
        'user' => Text::bold(Output::getClean($user_query->nickname))
    ]),
    'USER_ID' => $user_query->id,
    'BACK_LINK' => URL::build('/panel/user/' . urlencode($user_query->id)),
    'BACK' => $language->get('general', 'back'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'ARE_YOU_SURE_MESSAGE' => $language->get('admin', 'unlink_account_confirm'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'DELETE_LINK' => URL::build('/panel/users/oauth/', 'action=delete'),
    'OAUTH_PROVIDERS' => $oauth_providers,
    'NO_OAUTH_PROVIDERS' => $language->get('user', 'no_providers'),
    'USER_OAUTH_PROVIDERS' => $user_providers_template,
    'UNLINK' => $language->get('admin', 'unlink'),
    'NAME' => $language->get('admin', 'name'),
    'IDENTIFIER' => $language->get('admin', 'identifier'),
    'NOT_LINKED' => $language->get('admin', 'not_linked'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_oauth.tpl', $smarty);
