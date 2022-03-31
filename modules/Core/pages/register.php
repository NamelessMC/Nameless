<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Registration page
 */

// Ensure user isn't already logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Set page name for custom scripts
$page = 'register';
const PAGE = 'register';
$page_title = $language->get('general', 'register');

require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');

// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', ['name', '=', 'registration_enabled']);
$registration_enabled = $registration_enabled[0]->value;

if ($registration_enabled == 0) {
    // Registration is disabled, display a message
    // Get registration disabled message and assign to Smarty variable
    $registration_disabled_message = $queries->getWhere('settings', ['name', '=', 'registration_disabled_message']);
    if (count($registration_disabled_message)) {
        $message = Output::getPurified(htmlspecialchars_decode($registration_disabled_message[0]->value));
    } else {
        $message = 'Registration is currently disabled.';
    }

    $smarty->assign([
        'REGISTRATION_DISABLED' => $message,
        'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('registration_disabled.tpl', $smarty);

    die();
}

// Check if Minecraft is enabled
$minecraft = $queries->getWhere('settings', ['name', '=', 'mc_integration']);
$minecraft = $minecraft[0]->value;

if ($minecraft == '1') {
    // Check if AuthMe is enabled
    $authme_enabled = $queries->getWhere('settings', ['name', '=', 'authme']);
    $authme_enabled = $authme_enabled[0]->value;

    if ($authme_enabled == '1') {
        // Authme connector
        require(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Core', 'pages', 'authme_connector.php']));
        die();
    }
}

// Registration page
// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', ['name', '=', 'displaynames']);
$custom_usernames = $custom_usernames[0]->value;

if (isset($_GET['step'], $_SESSION['mcassoc'])) {
    // Get site details for MCAssoc
    $mcassoc_site_id = SITE_NAME;

    $mcassoc_shared_secret = $queries->getWhere('settings', ['name', '=', 'mcassoc_key']);
    $mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;

    $mcassoc_instance_secret = $queries->getWhere('settings', ['name', '=', 'mcassoc_instance']);
    $mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;

    define('MCASSOC', true);

    // Initialise
    $mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
    $mcassoc->enableInsecureMode();

    require(ROOT_PATH . '/core/integration/run_mcassoc.php');
    die();
}

// Is UUID linking enabled?
if ($minecraft == '1') {
    $uuid_linking = $queries->getWhere('settings', ['name', '=', 'uuid_linking']);
    $uuid_linking = $uuid_linking[0]->value;

    if ($uuid_linking == '1') {
        // Do we want to verify the user owns the account?
        $account_verification = $queries->getWhere('settings', ['name', '=', 'verify_accounts']);
        $account_verification = $account_verification[0]->value;
    }
} else {
    $uuid_linking = '0';
}

$captcha = CaptchaBase::isCaptchaEnabled();

// Is email verification enabled?
$email_verification = $queries->getWhere('settings', ['name', '=', 'email_verification']);
$email_verification = $email_verification[0]->value;

// API verification
$api_verification = $queries->getWhere('settings', ['name', '=', 'api_verification']);
$api_verification = $api_verification[0]->value;

Session::put('oauth_method', 'register');

// Deal with any input
if (Input::exists()) {
    if (Token::check()) {
        // Valid token
        if ($captcha) {
            $captcha_passed = CaptchaBase::getActiveProvider()->validateToken($_POST);
        } else {
            $captcha_passed = true;
        }

        if ($captcha_passed) {
            $to_validation = [
                'username' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20,
                    Validate::UNIQUE => 'users'
                ],
                'password' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 6,
                ],
                'password_again' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 6,
                    Validate::MATCHES => 'password'
                ],
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::EMAIL => true,
                    Validate::UNIQUE => 'users'
                ],
                't_and_c' => [
                    Validate::REQUIRED => true,
                    Validate::AGREE => true
                ],
                // TODO: re-enable this (#2355)
                // 'timezone' => [
                //     Validate::TIMEZONE => true
                // ]
            ];

            // Minecraft username?
            if (MINECRAFT) {
                if ($custom_usernames == 'true') {
                    // Nickname enabled
                    $to_validation['nickname'] = [
                        Validate::REQUIRED => true,
                        Validate::MIN => 3,
                        Validate::MAX => 20,
                        Validate::UNIQUE => 'users'
                    ];
                    $nickname = Output::getClean(Input::get('nickname'));
                } else {
                    $nickname = Output::getClean(Input::get('username'));
                }

            } else {
                // Just check username
                $nickname = Output::getClean(Input::get('username'));
            }
            $username = Output::getClean(Input::get('username'));

            // Validate custom fields
            $profile_fields = $queries->getWhere('profile_fields', ['id', '<>', 0]);
            if (count($profile_fields)) {
                foreach ($profile_fields as $field) {
                    if ($field->required == true) {
                        $to_validation[$field->id] = [
                            Validate::REQUIRED => true,
                            Validate::MAX => (is_null($field->length) ? 1024 : $field->length)
                        ];
                    }
                }
            }

            // Valid, continue with validation
            $validation = Validate::check(
                $_POST, $to_validation
            )->messages([
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists')
                ],
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::EMAIL => $language->get('general', 'contact_message_email'),
                ],
                'password' => [
                    Validate::REQUIRED => $language->get('user', 'password_required'),
                    Validate::MIN => $language->get('user', 'password_minimum_6'),
                ],
                'password_again' => [
                    Validate::MATCHES => $language->get('user', 'passwords_dont_match'),
                ],
                'mcname' => [
                    Validate::REQUIRED => $language->get('user', 'mcname_required'),
                    Validate::MIN => $language->get('user', 'mcname_minimum_3'),
                    Validate::MAX => $language->get('user', 'mcname_maximum_20'),
                ],
                't_and_c' => [
                    Validate::REQUIRED => $language->get('user', 'accept_terms'),
                ],
                // fallback message for profile fields
                '*' => static function ($field) use ($language, $queries) {
                    $profile_field = $queries->getWhere('profile_fields', ['id', '=', $field]);
                    if (!count($profile_field)) {
                        return null;
                    }

                    return str_replace('{x}', Output::getClean($profile_field[0]->name), $language->get('user', 'field_is_required'));
                },
            ]);

            if ($validation->passed()) {
                if (MINECRAFT) {
                    $integration = Integrations::getInstance()->getIntegration('Minecraft');

                    // Ensure username doesn't already exist
                    $integrationUser = new IntegrationUser($integration, $username, 'username');
                    if ($integrationUser->exists()) {
                        $integration->addError(str_replace('{x}', $integration->getName(), $language->get('user', 'integration_username_already_linked')));
                    }

                    $result = $integration->getUuidByUsername($username);
                    if (!count($integration->getErrors())) {
                        $uuid = $result['uuid'];

                        // Ensure identifier doesn't already exist
                        $integrationUser = new IntegrationUser($integration, $uuid, 'identifier');
                        if ($integrationUser->exists()) {
                            $integration->addError(str_replace('{x}', $integration->getName(), $language->get('user', 'integration_identifier_already_linked')));
                        }

                    } else {
                        $integration_errors = $integration->getErrors();
                    }
                } else {
                    $uuid = '';
                }

                // Check if there was any integration errors
                if (!isset($integration_errors)) {
                    // Minecraft user account association
                    if (isset($account_verification) && $account_verification == '1') {
                        // MCAssoc enabled
                        // Get data from database
                        $mcassoc_site_id = SITE_NAME;

                        $mcassoc_shared_secret = $queries->getWhere('settings', ['name', '=', 'mcassoc_key']);
                        $mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;

                        $mcassoc_instance_secret = $queries->getWhere('settings', ['name', '=', 'mcassoc_instance']);
                        $mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;

                        define('MCASSOC', true);

                        // Hash password first
                        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 13]);
                        $_SESSION['password'] = $password;
                        unset($_POST['password']);

                        // Initialise
                        $mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
                        $mcassoc->enableInsecureMode();

                        require(ROOT_PATH . '/core/integration/run_mcassoc.php');

                    } else {
                        // Disabled
                        $user = new User();

                        $ip = $user->getIP();
                        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                            // TODO: Invalid IP, do something
                        }

                        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);
                        // Get current unix time
                        $date = new DateTime();
                        $date = $date->getTimestamp();

                        if ($api_verification == '1') {
                            // Generate shorter code for API validation
                            $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
                            $active = 1;
                        } else {
                            // Generate random code for email
                            $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 60);
                            $active = 0;
                        }

                        // Get default language ID before creating user
                        $language_id = $queries->getWhere('languages', ['name', '=', LANGUAGE]);

                        if (count($language_id)) {
                            $language_id = $language_id[0]->id;
                        } else {
                            $language_id = 1; // fallback to EnglishUK
                        }

                        // Get default group ID
                        $cache->setCache('default_group');
                        if ($cache->isCached('default_group')) {
                            $default_group = $cache->retrieve('default_group');
                        } else {
                            $default_group = $queries->getWhere('groups', ['default_group', '=', 1]);
                            $default_group = $default_group[0]->id;

                            $cache->store('default_group', $default_group);
                        }

                        // Create user
                        $user->create(
                            [
                                'username' => $username,
                                'nickname' => $nickname,
                                'password' => $password,
                                'pass_method' => 'default',
                                'joined' => $date,
                                'email' => Output::getClean(Input::get('email')),
                                'reset_code' => $code,
                                'lastip' => Output::getClean($ip),
                                'last_online' => $date,
                                'language_id' => $language_id,
                                'active' => $active,
                                // TODO: re-enable this (#2355)
                                // 'timezone' => ((isset($_POST['timezone']) && $_POST['timezone']) ? Output::getClean(Input::get('timezone')) : Output::getClean(TIMEZONE))
                                'timezone' => Output::getClean(TIMEZONE)
                            ]
                        );

                        // Get user ID
                        $user_id = $queries->getLastId();

                        $user = new User($user_id);
                        $user->addGroup($default_group, 0, [true]);

                        // Minecraft Integration
                        if ($minecraft == 1) {
                            $integration = Integrations::getInstance()->getIntegration('Minecraft');
                            if ($integration != null) {
                                $integrationUser = new IntegrationUser($integration);
                                $integrationUser->linkIntegration($user, $uuid, $username, false, $code);
                            }
                        }

                        if (Session::exists('oauth_register_data')) {
                            $data = json_decode(Session::get('oauth_register_data'), true);
                            OAuth::getInstance()->saveUserProvider(
                                $user_id,
                                $data['provider'],
                                $data['id'],
                            );
                            Session::delete('oauth_register_data');
                        }

                        // Custom Fields
                        if (count($profile_fields)) {
                            foreach ($profile_fields as $field) {
                                if ($field->required == false) {
                                    continue;
                                }
                                $value = Input::get($field->name);
                                if (!empty($value)) {
                                    // Insert custom field
                                    $queries->create(
                                        'users_profile_fields',
                                        [
                                            'user_id' => $user_id,
                                            'field_id' => $field->id,
                                            'value' => Output::getClean(Input::get($field->name))
                                        ]
                                    );
                                }
                            }
                        }

                        Log::getInstance()->log(Log::Action('user/register'));

                        EventHandler::executeEvent('registerUser', [
                            'user_id' => $user_id,
                            'username' => Output::getClean(Input::get('username')),
                            'uuid' => $uuid,
                            'content' => str_replace('{x}', Output::getClean(Input::get('username')), $language->get('user', 'user_x_has_registered')),
                            'avatar_url' => $user->getAvatar(128, true),
                            'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean(Input::get('username'))), '/'),
                            'language' => $language
                        ]);

                        if ($api_verification != '1' && $email_verification == '1') {
                            // Send registration email
                            sendRegisterEmail($queries, $language, Output::getClean(Input::get('email')), $username, $user_id, $code);
                        } else if ($api_verification != '1') {
                            // Redirect straight to verification link
                            $url = URL::build('/validate/', 'c=' . $code);
                            Redirect::to($url);
                        }

                        if ($api_verification != '1') {
                            Session::flash('home', $language->get('user', 'registration_check_email'));
                        } else {
                            Session::flash('home', $language->get('user', 'validation_complete'));
                        }

                        Redirect::to(URL::build('/'));
                    }
                    die();
                } else {
                    $errors = $integration_errors;
                }

            } else {
                // Errors
                // TODO: Update to new validation system
                $errors = [];
                foreach ($validation->errors() as $validation_error) {

                    if (strpos($validation_error, 'is required') !== false) {
                        // x is required
                        if (strpos($validation_error, 'username') !== false) {
                            $errors[] = $language->get('user', 'username_required');
                        } else if (strpos($validation_error, 'email') !== false) {
                            $errors[] = $language->get('user', 'email_required');
                        } else if (strpos($validation_error, 'password') !== false) {
                            $errors[] = $language->get('user', 'password_required');
                        } else if (strpos($validation_error, 'mcname') !== false) {
                            $errors[] = $language->get('user', 'mcname_required');
                        } else if (strpos($validation_error, 't_and_c') !== false) {
                            $errors[] = $language->get('user', 'accept_terms');
                        } else {
                            $errors[] = $validation_error . '.';
                        }
                    } else if (strpos($validation_error, 'minimum') !== false) {
                        // x must be a minimum of y characters long
                        if (strpos($validation_error, 'username') !== false) {
                            $errors[] = $language->get('user', 'username_minimum_3');
                        } else if (strpos($validation_error, 'mcname') !== false) {
                            $errors[] = $language->get('user', 'mcname_minimum_3');
                        } else if (strpos($validation_error, 'password') !== false) {
                            $errors[] = $language->get('user', 'password_minimum_6');
                        }
                    } else if (strpos($validation_error, 'maximum') !== false) {
                        // x must be a maximum of y characters long
                        if (strpos($validation_error, 'username') !== false) {
                            $errors[] = $language->get('user', 'username_maximum_20');
                        } else if (strpos($validation_error, 'mcname') !== false) {
                            $errors[] = $language->get('user', 'mcname_maximum_20');
                        }
                    } else if (strpos($validation_error, 'must match') !== false) {
                        // password must match password again
                        $errors[] = $language->get('user', 'passwords_dont_match');
                    } else if (strpos($validation_error, 'already exists') !== false) {
                        // already exists
                        if (!in_array($language->get('user', 'username_mcname_email_exists'), $errors)) {
                            $errors[] = $language->get('user', 'username_mcname_email_exists');
                        }
                    } else if (strpos($validation_error, 'valid email') !== false) {
                        // Validate email
                        $errors[] = $language->get('general', 'contact_message_email');
                    }
                }
            }
        } else {
            // reCAPTCHA failed
            $errors = [$language->get('user', 'invalid_recaptcha')];
        }

    } else {
        // Invalid token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

if (isset($errors)) {
    $smarty->assign('REGISTRATION_ERROR', $errors);
} else if (Session::exists('oauth_error')) {
    $smarty->assign('REGISTRATION_ERROR', Session::flash('oauth_error'));
}

// Are custom usernames enabled?
if ($custom_usernames !== 'false') {
    $smarty->assign('NICKNAMES', true);
}

if ($minecraft == 1) {
    $smarty->assign('MINECRAFT', true);
}

$custom_fields = [];
$profile_fields = $queries->getWhere('profile_fields', ['id', '<>', 0]);
if (count($profile_fields)) {
    foreach ($profile_fields as $field) {
        if ($field->required == false) {
            continue;
        }

        $custom_fields[] = [
            'id' => $field->id,
            'name' => Output::getClean($field->name),
            'description' => Output::getClean($field->description) ?: Output::getClean($field->name),
            'type' => $field->type,
            'required' => $field->required,
            'value' => $_POST[$field->id] ?? ''
        ];
    }
}

$email_value = ((isset($_POST['email']) && $_POST['email']) ? Output::getClean(Input::get('email')) : '');
if (Session::exists('oauth_register_data')) {
    $email_value = json_decode(Session::get('oauth_register_data'), true)['email'];
}

$oauth_flow = Session::exists('oauth_register_data');
if ($oauth_flow) {
    $data = json_decode(Session::get('oauth_register_data'), true);
    $smarty->assign('OAUTH_MESSAGE_CONTINUE', str_replace(
        '{x}',
        ucfirst($data['provider']),
        $language->get('general', 'oauth_message_continue')
    ));
}

// Assign Smarty variables
$smarty->assign([
    'USERNAME' => $language->get('user', 'username'),
    'NICKNAME' => ($custom_usernames == 'false' && !MINECRAFT) ? $language->get('user', 'username') : $language->get('user', 'nickname'),
    'NICKNAME_VALUE' => ((isset($_POST['nickname']) && $_POST['nickname']) ? Output::getClean(Input::get('nickname')) : ''),
    'USERNAME_VALUE' => ((isset($_POST['username']) && $_POST['username']) ? Output::getClean(Input::get('username')) : ''),
    'MINECRAFT_USERNAME' => $language->get('user', 'minecraft_username'),
    'EMAIL' => $language->get('user', 'email_address'),
    'EMAIL_VALUE' => $email_value,
    'PASSWORD' => $language->get('user', 'password'),
    'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
    'I_AGREE' => $language->get('user', 'i_agree'),
    'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c')),
    'REGISTER' => $language->get('general', 'register'),
    'LOG_IN' => $language->get('general', 'sign_in'),
    'LOGIN_URL' => URL::build('/login'),
    'TOKEN' => Token::get(),
    'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account'),
    'ALREADY_REGISTERED' => $language->get('general', 'already_registered'),
    'ERROR_TITLE' => $language->get('general', 'error'),
    'CUSTOM_FIELDS' => $custom_fields,
    'OAUTH_FLOW' => $oauth_flow,
    'OAUTH_AVAILABLE' => OAuth::getInstance()->isAvailable(),
    'OAUTH_PROVIDERS' => OAuth::getInstance()->getProvidersAvailable(),
]);

if ($captcha) {
    $smarty->assign('CAPTCHA', CaptchaBase::getActiveProvider()->getHtml());
    $template->addJSFiles([CaptchaBase::getActiveProvider()->getJavascriptSource() => []]);

    $submitScript = CaptchaBase::getActiveProvider()->getJavascriptSubmit('form-register');
    if ($submitScript) {
        $template->addJSScript('
            $("#form-register").submit(function(e) {
                e.preventDefault();
                ' . $submitScript . '
            });
        ');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('register.tpl', $smarty);
