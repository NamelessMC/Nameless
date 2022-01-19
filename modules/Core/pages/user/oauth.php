<?php

/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  User OAuth page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
    die();
}

// Always define page name for navbar
const PAGE = 'cc_oauth';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (isset($_GET['provider'], $_GET['code'])) {

    if ($_GET['provider'] === OAuth::DISCORD || $_GET['provider'] === OAuth::GOOGLE) {
        $provider_name = $_GET['provider'];
        $provider = OAuth::getInstance()->getProviderInstance($provider_name, OAuth::PAGE_LINK);
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        $oauth_user = $provider->getResourceOwner($token)->toArray();
        $oauth_user_provider_id = $oauth_user[OAuth::getInstance()->getIdName($provider_name)];

        if (OAuth::getInstance()->userExistsByProviderId($provider_name, $oauth_user_provider_id)) {
            Session::flash('oauth_error', 'Another NamelessMC user is already linked to that ' . ucfirst($provider_name) . ' account.');
            Redirect::to(URL::build('/user/oauth'));
            die();
        }

        OAuth::getInstance()->saveUserProvider(
            $user->data()->id,
            $provider_name,
            $oauth_user_provider_id
        );

        Session::flash('oauth_success', 'Successfully linked your account with ' . ucfirst($provider_name) . '!');
        Redirect::to(URL::build('/user/oauth'));
        die();
    }
} else if (Input::exists()) {
    $provider_name = Input::get('provider');

    if (Input::get('action') === 'unlink') {
        OAuth::getInstance()->unlinkProviderForUser($user->data()->id, $provider_name);
        Session::flash('oauth_success', $language->get('user', 'oauth_unlinked'));
    }
}

$providers = OAuth::getInstance()->getProvidersAvailable(OAuth::PAGE_LINK);
$user_providers = OAuth::getInstance()->getAllProvidersForUser($user->data()->id);

$user_providers_template = [];
foreach ($user_providers as $user_provider) {
    $user_providers_template[$user_provider->provider] = $user_provider;
}

if (Session::exists('oauth_success')) {
    $smarty->assign([
        'SUCCESS' => $language->get('general', 'success'),
        'SUCCESS_MESSAGE' => Session::flash('oauth_success'),
    ]);
}

if (Session::exists('oauth_error')) {
    $smarty->assign([
        'ERROR' => $language->get('general', 'error'),
        'ERROR_MESSAGE' => Session::flash('oauth_error'),
    ]);
}

$smarty->assign([
    'TOKEN' => Token::get(),
    'NO' => $language->get('general', 'no'),
    'YES' => $language->get('general', 'yes'),
    'CONFIRM' => $language->get('general', 'confirm'),
    'USER_CP' => $language->get('user', 'user_cp'),
    'OAUTH_PROVIDERS' => $providers,
    'USER_OAUTH_PROVIDERS' => $user_providers_template,
    'OAUTH' => $language->get('admin', 'oauth'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/oauth.tpl', $smarty);
