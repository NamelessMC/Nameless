<?php

if (!isset($_GET['provider'], $_GET['code'])) {
    if (!array_key_exists($_GET['provider'], NamelessOAuth::getInstance()->getProvidersAvailable())) {
        ErrorHandler::logWarning("Invalid provider {$_GET['provider']}");
        Session::flash('home_error', $language->get('general', 'oauth_failed'));
        Redirect::to(URL::build('/'));
    }
}

if (!Session::exists('oauth_method')) {
    ErrorHandler::logWarning("No OAuth Zmethod set");
    Session::flash('home_error', $language->get('general', 'oauth_failed'));
    Redirect::to(URL::build('/'));
}

$provider_name = $_GET['provider'];
$provider = NamelessOAuth::getInstance()->getProviderInstance($provider_name);
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    Session::flash('oauth_error', $language->get('general', 'oauth_failed_setup'));
    ErrorHandler::logWarning('An error occurred while handling an oauth ' . Session::get('oauth_method') . ' request: ' . $e->getMessage());

    $method = Session::get('oauth_method');
    switch($method) {
        case 'register':
            Redirect::to(URL::build('/register'));

        case 'login':
            Redirect::to(URL::build('/login'));

        case 'link':
            Redirect::to(URL::build('/user/oauth/'));
    }
}

$oauth_user = $provider->getResourceOwner($token)->toArray();
$provider_id = $oauth_user[NamelessOAuth::getInstance()->getUserIdName($provider_name)];

// register
if (Session::get('oauth_method') === 'register') {
    if (NamelessOAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', $language->get('user', 'oauth_already_linked', ['provider' => ucfirst($provider_name)]));
        Redirect::to(URL::build('/register'));
    }

    Session::put('oauth_register_data', json_encode([
        'provider' => $provider_name,
        'id' => $provider_id,
        'email' => $oauth_user['email'],
        'data' => $oauth_user
    ]));

    Redirect::to(URL::build('/register'));
}

// login
if (Session::get('oauth_method') === 'login') {
    if (!NamelessOAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', $language->get('user', 'no_user_found_with_provider', ['provider' => ucfirst($provider_name)]));
        Redirect::to(URL::build('/login'));
    }

    if ((new User())->login(
        NamelessOAuth::getInstance()->getUserIdFromProviderId($provider_name, $provider_id),
        '', true, 'oauth'
    )) {
        Log::getInstance()->log(Log::Action('user/login'));
        Session::flash('home', $language->get('user', 'oauth_login_success', ['provider' => ucfirst($provider_name)]));
        Session::delete('oauth_method');

        if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) != '=') {
            Redirect::back();
        }

        Redirect::to(URL::build('/'));
    }

    throw new RuntimeException('Failed to login user with OAuth');
}

// link
if (Session::get('oauth_method') === 'link') {
    if (NamelessOAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        Session::flash('oauth_error', $language->get('user', 'oauth_already_linked', ['provider' => ucfirst($provider_name)]));
        Redirect::to(URL::build('/user/oauth'));
    }

    NamelessOAuth::getInstance()->saveUserProvider(
        $user->data()->id,
        $provider_name,
        $provider_id,
    );

    Session::flash('oauth_success', $language->get('user', 'oauth_link_success', ['provider' => ucfirst($provider_name)]));
    Session::delete('oauth_method');

    Redirect::to(URL::build('/user/oauth'));
}
