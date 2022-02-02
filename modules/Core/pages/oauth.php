<?php

if (!isset($_GET['provider'], $_GET['code'])) {
    if ($_GET['provider'] !== OAuth::DISCORD && $_GET['provider'] !== OAuth::GOOGLE) {
        throw new RuntimeException("Invalid provider {$_GET['provider']}");
    }
}

if (!Session::exists('oauth_method')) {
    throw new RuntimeException('No OAuth method set');
}

$provider_name = $_GET['provider'];
$provider = OAuth::getInstance()->getProviderInstance($provider_name);
$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code']
]);
$oauth_user = $provider->getResourceOwner($token)->toArray();
$provider_id = $oauth_user[OAuth::getInstance()->getIdName($provider_name)];

// register
if (Session::get('oauth_method') === 'register') {
    if (OAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', str_replace('{x}', ucfirst($provider_name), $language->get('user', 'oauth_already_linked')));
        Redirect::to(URL::build('/register'));
        die();
    }

    Session::put('oauth_register_data', json_encode([
        'provider' => $provider_name,
        'id' => $provider_id,
        'email' => $oauth_user['email'],
    ]));

    Redirect::to(URL::build('/register'));
    die();
}

// login
if (Session::get('oauth_method') === 'login') {
    if (!OAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', str_replace('{x}', ucfirst($provider_name), $language->get('user', 'no_user_found_with_provider')));
        Redirect::to(URL::build('/login'));
        die();
    }

    if ((new User())->login(
        OAuth::getInstance()->getUserIdFromProviderId($provider_name, $provider_id),
        '', true, 'oauth'
    )) {
        Log::getInstance()->log(Log::Action('user/login'));
        Session::flash('home', str_replace('{x}', ucfirst($provider_name), $language->get('user', 'oauth_login_success')));
        Session::delete('oauth_method');

        if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) != '=') {
            Redirect::to($_SESSION['last_page']);
            die();
        }

        Redirect::to(URL::build('/'));
        die();
    }

    throw new RuntimeException('Failed to login user with OAuth');
}

// link
if (Session::get('oauth_method') === 'link') {
    if (OAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', str_replace('{x}', ucfirst($provider_name), $language->get('user', 'oauth_already_linked')));
        Redirect::to(URL::build('/user/oauth'));
        die();
    }

    OAuth::getInstance()->saveUserProvider(
        $user->data()->id,
        $provider_name,
        $provider_id,
    );

    Session::flash('oauth_success', str_replace('{x}', ucfirst($provider_name), $language->get('user', 'oauth_link_success')));
    Session::delete('oauth_method');

    Redirect::to(URL::build('/user/oauth'));
    die();
}
