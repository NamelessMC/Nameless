<?php

const PAGE = 'oauth';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (isset($_GET['action']) && $_GET['action'] == 'cancel_registration') {
    Session::delete('oauth_register_data');
    Redirect::to(URL::build('/register'));
}

if (!isset($_GET['provider'], $_GET['code'])) {
    ErrorHandler::logWarning('No provider or code set when accessing OAuth');
    Session::flash('home_error', $language->get('general', 'oauth_no_data'));
    Redirect::to(URL::build('/'));
}

if (!array_key_exists($_GET['provider'], NamelessOAuth::getInstance()->getProvidersAvailable())) {
    ErrorHandler::logWarning("Invalid provider {$_GET['provider']}");
    Session::flash('home_error', $language->get('general', 'oauth_failed'));
    Redirect::to(URL::build('/'));
}

if (!Session::exists('oauth_method')) {
    ErrorHandler::logWarning("No OAuth method set");
    Session::flash('home_error', $language->get('general', 'oauth_failed'));
    Redirect::to(URL::build('/'));
}

// If they are filling in 2FA. We've already retrieved their user. We can skip the other steps in this case
if (isset($_SESSION['user_id']) && isset($_POST['tfa_code'])) {
    $user = new User($_SESSION['user_id']);

    // Continue the 2FA process
    $tfa = new \RobThree\Auth\TwoFactorAuth('NamelessMC');
    if ($tfa->verifyCode($user->data()->tfa_secret, str_replace(' ', '', $_POST['tfa_code'])) !== true) {
        Session::flash('tfa_signin', $language->get('user', 'invalid_tfa'));
        require(ROOT_PATH . '/core/includes/tfa_signin.php');
        die();
    }
    unset($_SESSION['user_id']);

    // Log the user in if 2FA passed
    if ($user->login(
        $user->data()->id,
        '', true, 'oauth'
    )) {
        Log::getInstance()->log(Log::Action('user/login'));
        Session::flash('home', $language->get('user', 'oauth_login_success', ['provider' => ucfirst($provider_name)]));
        Session::delete('oauth_method');
        Redirect::to(URL::build('/'));
    }
}

$provider_name = $_GET['provider'];
$provider = NamelessOAuth::getInstance()->getProviderInstance($provider_name);
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    Session::flash('oauth_error', $language->get('general', 'oauth_failed_setup'));
    ErrorHandler::logWarning('An error occurred while handling an OAuth ' . Session::get('oauth_method') . ' request: ' . $e->getMessage());

    $method = Session::get('oauth_method');
    switch($method) {
        case 'register':
            Redirect::to(URL::build('/register'));

        case 'login':
            Redirect::to(URL::build('/login'));

        case 'link':
            Redirect::to(URL::build('/user/oauth/'));

        case 'link_integration':
            Redirect::to(URL::build('/user/connections/'));
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

    $user_id = NamelessOAuth::getInstance()->getUserIdFromProviderId($provider_name, $provider_id);
    $user = new User($user_id);

    // Make sure user is validated
    if (!$user->isValidated()) {
        Session::flash('oauth_error', $language->get('user', 'inactive_account'));
        Redirect::to(URL::build('/login'));
    }

    // Make sure user is not banned
    if ($user->data()->isbanned == 1) {
        Session::flash('oauth_error', $language->get('user', 'account_banned'));
        Redirect::to(URL::build('/login'));
    }

    // If the user has 2FA enabled, ask for those credentials
    if ($user->data()->tfa_enabled == 1 && $user->data()->tfa_complete == 1) {
        $_SESSION['user_id'] = $user_id;
        if (!isset($_POST['tfa_code'])) {
            require(ROOT_PATH . '/core/includes/tfa_signin.php');
            die();
        }
    }

    // Log the user in
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

// link user integration
if (Session::get('oauth_method') === 'link_integration') {
    $integration = Integrations::getInstance()->getIntegration($provider_name);
    if ($integration == null) {
        Session::flash('connections_error', $language->get('general', 'oauth_failed_setup'));
    }

    // Allow the user integration to access the data from the oauth response
    Session::put('oauth_register_data', json_encode([
        'provider' => $provider_name,
        'id' => $provider_id,
        'email' => $oauth_user['email'],
        'data' => $oauth_user
    ]));

    // Link the user integration
    $integration->successfulRegistration($user);

    // Link their oauth details if its not linked already
    if (!NamelessOAuth::getInstance()->userExistsByProviderId($provider_name, $provider_id)) {
        NamelessOAuth::getInstance()->saveUserProvider(
            $user->data()->id,
            $provider_name,
            $provider_id,
        );
    }

    Session::delete('oauth_register_data');
    Session::delete('oauth_method');
    Redirect::to(URL::build('/user/connections'));
}
