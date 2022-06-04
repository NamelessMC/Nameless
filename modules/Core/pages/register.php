<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
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
$registration_enabled = DB::getInstance()->get('settings', ['name', 'registration_enabled'])->results();
$registration_enabled = $registration_enabled[0]->value;

if ($registration_enabled == 0) {
    // Registration is disabled, display a message
    // Get registration disabled message and assign to Smarty variable
    $registration_disabled_message = DB::getInstance()->get('settings', ['name', 'registration_disabled_message'])->results();
    if (count($registration_disabled_message)) {
        $message = Output::getPurified($registration_disabled_message[0]->value);
    } else {
        $message = 'Registration is currently disabled.';
    }

    $smarty->assign([
        'REGISTRATION_DISABLED' => $message,
        'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('registration_disabled.tpl', $smarty);

    die();
}

// Check if Minecraft is enabled
$minecraft = MINECRAFT;

if ($minecraft == '1') {
    // Check if AuthMe is enabled
    $authme_enabled = DB::getInstance()->get('settings', ['name', 'authme'])->results();
    $authme_enabled = $authme_enabled[0]->value;

    if ($authme_enabled == '1') {
        // Authme connector
        require(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Core', 'pages', 'authme_connector.php']));
        die();
    }
}

// Registration page

if (isset($_GET['step'], $_SESSION['mcassoc'])) {
    // Get site details for MCAssoc
    $mcassoc_site_id = SITE_NAME;

    $mcassoc_shared_secret = DB::getInstance()->get('settings', ['name', 'mcassoc_key'])->results();
    $mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;

    $mcassoc_instance_secret = DB::getInstance()->get('settings', ['name', 'mcassoc_instance'])->results();
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
    $uuid_linking = DB::getInstance()->get('settings', ['name', 'uuid_linking'])->results();
    $uuid_linking = $uuid_linking[0]->value;

    if ($uuid_linking == '1') {
        // Do we want to verify the user owns the account?
        $account_verification = DB::getInstance()->get('settings', ['name', 'verify_accounts'])->results();
        $account_verification = $account_verification[0]->value;
    }
} else {
    $uuid_linking = '0';
}

$captcha = CaptchaBase::isCaptchaEnabled();

$integrations = Integrations::getInstance();

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

            if (Util::getSetting('displaynames') === '1') {
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
            $username = Output::getClean(Input::get('username'));

            // Validate custom fields
            $profile_fields = ProfileField::all();
            foreach ($profile_fields as $field) {
                if ($field->required) {
                    $to_validation["profile_fields[{$field->id}]"] = [
                        Validate::REQUIRED => true,
                        Validate::MAX => (is_null($field->length) ? 1024 : $field->length)
                    ];
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
                    Validate::EMAIL => $language->get('user', 'invalid_email'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists')
                ],
                'password' => [
                    Validate::REQUIRED => $language->get('user', 'password_required'),
                    Validate::MIN => $language->get('user', 'password_minimum_6'),
                ],
                'password_again' => [
                    Validate::MATCHES => $language->get('user', 'passwords_dont_match'),
                ],
                't_and_c' => [
                    Validate::REQUIRED => $language->get('user', 'accept_terms'),
                ],
                // fallback message for profile fields
                '*' => static function ($field) use ($language) {
                    // get the id from between the square brackets
                    $id = substr($field, strpos($field, '[') + 1, -1);

                    $field = ProfileField::find($id);
                    if (!$field) {
                        return null;
                    }

                    return $language->get('user', 'field_is_required', [
                        'field' => Output::getClean($field->name),
                    ]);
                },
            ]);

            // Check if any integrations wanna modify the validation
            foreach ($integrations->getEnabledIntegrations() as $integration) {
                $integration->beforeRegistrationValidation($validation);
            }

            if ($validation->passed()) {
                // Check if any integrations have actions to perform
                foreach ($integrations->getEnabledIntegrations() as $integration) {
                    $integration->afterRegistrationValidation();

                    if (count($integration->getErrors())) {
                        $integration_errors = $integration->getErrors();
                        break;
                    }
                }

                // Check if there was any integrations errors
                if (!isset($integration_errors)) {
                    // Minecraft user account association
                    if (isset($account_verification) && $account_verification == '1') {
                        // MCAssoc enabled
                        // Get data from database
                        $mcassoc_site_id = SITE_NAME;

                        $mcassoc_shared_secret = DB::getInstance()->get('settings', ['name', 'mcassoc_key'])->results();
                        $mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;

                        $mcassoc_instance_secret = DB::getInstance()->get('settings', ['name', 'mcassoc_instance'])->results();
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

                        $ip = Util::getRemoteAddress();
                        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                            // TODO: Invalid IP, do something
                        }

                        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);
                        // Get current unix time
                        $date = new DateTime();
                        $date = $date->getTimestamp();

                        // Generate validation code
                        $code = SecureRandom::alphanumeric();

                        // Get default language ID before creating user
                        $language_id = DB::getInstance()->get('languages', ['short_code', LANGUAGE])->results();

                        if (count($language_id)) {
                            $language_id = $language_id[0]->id;
                        } else {
                            // fallback to EnglishUK
                            $language_id = DB::getInstance()->get('languages', ['short_code', 'en_UK'])->results();
                            $language_id = $language_id[0]->id;
                        }

                        // Get default group ID
                        $cache->setCache('default_group');
                        if ($cache->isCached('default_group')) {
                            $default_group = $cache->retrieve('default_group');
                        } else {
                            $default_group = Group::find(1, 'default_group')->id;

                            $cache->store('default_group', $default_group);
                        }

                        $timezone = TIMEZONE;
                        $auto_timezone = Input::get('timezone');
                        if ($auto_timezone && in_array($auto_timezone, DateTimeZone::listIdentifiers())) {
                            $timezone = $auto_timezone;
                        }

                        // Create user
                        $user->create([
                            'username' => $username,
                            'nickname' => $nickname,
                            'password' => $password,
                            'pass_method' => 'default',
                            'joined' => $date,
                            'email' => Input::get('email'),
                            'reset_code' => $code,
                            'lastip' => $ip,
                            'last_online' => $date,
                            'language_id' => $language_id,
                            'timezone' => $timezone,
                        ]);

                        // Get user ID
                        $user_id = DB::getInstance()->lastId();

                        $user = new User($user_id);
                        $user->addGroup($default_group);

                        foreach ($integrations->getEnabledIntegrations() as $integration) {
                            $integration->successfulRegistration($user);
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
                        foreach ($_POST['profile_fields'] as $field_id => $value) {
                            if (!empty($value)) {
                                // Insert custom field
                                DB::getInstance()->insert('users_profile_fields', [
                                    'user_id' => $user_id,
                                    'field_id' => $field_id,
                                    'value' => $value,
                                    'updated' => date('U'),
                                ]);
                            }
                        }

                        Log::getInstance()->log(Log::Action('user/register'), '', $user_id, Util::getRemoteAddress());

                        $default_language = new Language('core', DEFAULT_LANGUAGE);
                        EventHandler::executeEvent('registerUser', [
                            'user_id' => $user_id,
                            'username' => Input::get('username'),
                            'content' => $default_language->get('user', 'user_x_has_registered', [
                                'user' => Input::get('username'),
                            ]),
                            'avatar_url' => $user->getAvatar(128, true),
                            'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . urlencode(Input::get('username'))), '/'),
                            'language' => $default_language,
                        ]);

                        if (Util::getSetting('email_verification') === '1') {
                            // Send registration email
                            sendRegisterEmail($language, Output::getClean(Input::get('email')), $username, $user_id, $code);

                            Session::flash('home', $language->get('user', 'registration_check_email'));
                        } else {
                            // Redirect straight to verification link
                            Redirect::to(URL::build('/validate/', 'c=' . urlencode($code)));
                        }

                        Redirect::to(URL::build('/'));
                    }
                    die();
                } else {
                    // Integrations errors
                    $errors = $integration_errors;
                }

            } else {
                // Errors
                $errors = $validation->errors();
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

$fields = new Fields();

// Are custom usernames enabled?
if (Util::getSetting('displaynames') === '1') {
    $nickname_value = ((isset($_POST['nickname']) && $_POST['nickname']) ? Output::getClean(Input::get('nickname')) : '');

    $fields->add('nickname', Fields::TEXT, $language->get('user', 'nickname'), true, $nickname_value);
}

$username_value = ((isset($_POST['username']) && $_POST['username']) ? Output::getClean(Input::get('username')) : '');
$email_value = ((isset($_POST['email']) && $_POST['email']) ? Output::getClean(Input::get('email')) : '');

if (Session::exists('oauth_register_data')) {
    $email_value = json_decode(Session::get('oauth_register_data'), true)['email'];
}

$fields->add('username', Fields::TEXT, $language->get('user', 'username'), true, $username_value);
$fields->add('email', Fields::EMAIL, $language->get('user', 'email_address'), true, $email_value);
$fields->add('password', Fields::PASSWORD, $language->get('user', 'password'), true);
$fields->add('password_again', Fields::PASSWORD, $language->get('user', 'confirm_password'), true);

// Check if any integrations have fields to add
foreach ($integrations->getEnabledIntegrations() as $integration) {
    $integration->onRegistrationPageLoad($fields);
}

// Custom profile fields
foreach (ProfileField::all() as $field) {
    if (!$field->required) {
        continue;
    }

    $field_value = ((isset($_POST['profile_fields']) && is_array($_POST['profile_fields'])) ? Output::getClean(Input::get('profile_fields')[$field->id]) : '');
    $fields->add(
        "profile_fields[{$field->id}]",
        $field->type,
        Output::getClean($field->name),
        $field->required,
        Output::getClean($field_value),
        Output::getClean($field->description) ?: Output::getClean($field->name)
    );
}

$oauth_flow = Session::exists('oauth_register_data');
if ($oauth_flow) {
    $data = json_decode(Session::get('oauth_register_data'), true);
    $smarty->assign('OAUTH_MESSAGE_CONTINUE', $language->get('general', 'oauth_message_continue', [
        'provider' => ucfirst($data['provider'])
    ]));
}

// Assign Smarty variables
$smarty->assign([
    'FIELDS' => $fields->getAll(),
    'I_AGREE' => $language->get('user', 'i_agree'),
    'AGREE_TO_TERMS' => $language->get('user', 'agree_t_and_c', [
        'linkStart' => '<a href="' . URL::build('/terms') . '">',
        'linkEnd' => '</a>',
    ]),
    'REGISTER' => $language->get('general', 'register'),
    'LOG_IN' => $language->get('general', 'sign_in'),
    'LOGIN_URL' => URL::build('/login'),
    'TOKEN' => Token::get(),
    'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account'),
    'ALREADY_REGISTERED' => $language->get('general', 'already_registered'),
    'ERROR_TITLE' => $language->get('general', 'error'),
    'OR' => $language->get('general', 'or'),
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

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('register.tpl', $smarty);
