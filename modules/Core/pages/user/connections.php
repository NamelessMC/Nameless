<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  UserCP connections
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_connections';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (Input::exists()) {
    if (Token::check()) {

        // Get Integration
        $integration = Integrations::getInstance()->getIntegration(Input::get('integration'));
        if ($integration == null) {
            Redirect::to(URL::build('/user/connections'));
        }

        if (Input::get('action') === 'link') {
            // Link Integration
            $integration->onLinkRequest($user);

        } else if (Input::get('action') === 'unlink') {
            // Unlink Integration
            $integration->onUnlinkRequest($user);

        } else if (Input::get('action') === 'verify') {
            // Verify Integration
            $integration->onVerifyRequest($user);

        }

        // Reload page if there is no errors, Else show errors
        if (!$integration->getErrors()) {
            Redirect::to(URL::build('/user/connections'));
        } else {
            $errors = $integration->getErrors();
        }
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

$integrations_list = [];
foreach (Integrations::getInstance()->getAll() as $integration) {
    $connected = false;
    $username = null;
    $verified = null;

    // Check if user is linked to this integration
    $integrationUser = $user->getIntegration($integration->getName());
    if ($integrationUser != null) {
        $connected = true;
        $username = Output::getClean($integrationUser->data()->username);
        $verified = Output::getClean($integrationUser->isVerified());
    }

    $integrations_list[] = [
        'name' => Output::getClean($integration->getName()),
        'icon' => Output::getClean($integration->geticon()),
        'connected' => $connected,
        'username' => $username,
        'verified' => $verified
    ];
}

// Language values
$smarty->assign([
    'TOKEN' => Token::get(),
    'USER_CP' => $language->get('user', 'user_cp'),
    'CONNECTIONS' => $language->get('user', 'connections'),
    'INTEGRATIONS' => $integrations_list
]);

if (Session::exists('connections_success')) {
    $success = Session::flash('connections_success');
}

if (Session::exists('connections_error')) {
    $errors = [Session::flash('connections_error')];
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/connections.tpl', $smarty);
