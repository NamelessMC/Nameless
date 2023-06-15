<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Authme connector
 */

$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');

// Use recaptcha?
$captcha = CaptchaBase::isCaptchaEnabled();

// Deal with any input
$errors = [];
if (Input::exists()) {
    if (Token::check()) {
        // Valid token
        if (isset($_GET['step']) && $_GET['step'] == 2) {
            // Step 2
            if (!isset($_SESSION['authme'])) {
                Redirect::to(URL::build('/register'));
            }

            $to_validation = [
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 4,
                    Validate::MAX => 64,
                    Validate::UNIQUE => 'users',
                ],
                'username' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20,
                    Validate::UNIQUE => 'users',
                ],
            ];

            // Are custom usernames enabled?
            if (Util::getSetting('displaynames') === '1') {
                $to_validation['nickname'] = [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20,
                    Validate::UNIQUE => 'users',
                ];
            }

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

            $_POST['username'] = $_SESSION['authme']['username'];
            $validation = Validate::check($_POST, $to_validation);

            $validation->messages([
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20'),
                    Validate::UNIQUE => $language->get('user', 'username_already_exists'),
                ],
                'nickname' => [
                    Validate::REQUIRED => $language->get('user', 'nickname_required'),
                    Validate::MIN => $language->get('user', 'nickname_minimum_3'),
                    Validate::MAX => $language->get('user', 'nickname_maximum_20'),
                    Validate::UNIQUE => $language->get('user', 'nickname_already_exists'),
                ],
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::MIN => $language->get('user', 'invalid_email'),
                    Validate::MAX => $language->get('user', 'invalid_email'),
                    Validate::UNIQUE => $language->get('user', 'email_already_exists'),
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

            if ($validation->passed()) {
                // Get default language ID before creating user
                $language_id = DB::getInstance()->get('languages', ['short_code', LANGUAGE]);

                if ($language_id->count()) {
                    $language_id = $language_id->first()->id;
                } else {
                    // fallback to EnglishUK
                    $language_id = DB::getInstance()->get('languages', ['short_code', 'en_UK'])->first()->id;
                }

                if (!filter_var($ip = HttpUtils::getRemoteAddress(), FILTER_VALIDATE_IP)) {
                    $ip = $_SESSION['authme']['ip'];
                }

                $mcname = Output::getClean($_SESSION['authme']['username']);
                if (Util::getSetting('displaynames') === '1') {
                    $nickname = Input::get('nickname');
                } else {
                    $nickname = $mcname;
                }

                // Add username back to post for integration handling
                $_POST['username'] = $mcname;

                $integration = Integrations::getInstance()->getIntegration('Minecraft');
                $integration->afterRegistrationValidation();

                if (count($integration->getErrors())) {
                    $errors = $integration->getErrors();
                } else {
                    // Generate validation code
                    $code = SecureRandom::alphanumeric();
                    $email = Output::getClean(Input::get('email'));

                    $user->create([
                        'username' => $mcname,
                        'nickname' => $nickname,
                        'password' => $_SESSION['authme']['pass'],
                        'pass_method' => $_SESSION['authme']['hash'],
                        'joined' => date('U'),
                        'email' => $email,
                        'reset_code' => $code,
                        'lastip' => $ip,
                        'last_online' => date('U'),
                        'language_id' => $language_id,
                        'register_method' => 'authme',
                        'authme_sync_password' => Input::get('authme_sync_password') === 'on',
                    ]);

                    // Get user ID
                    $user_id = DB::getInstance()->lastId();

                    // Get default group ID
                    $cache->setCache('default_group');
                    if ($cache->isCached('default_group')) {
                        $default_group = $cache->retrieve('default_group');
                    } else {
                        $default_group = Group::find(1, 'default_group')->id;

                        $cache->store('default_group', $default_group);
                    }

                    $user = new User($user_id);
                    $user->addGroup($default_group);

                    EventHandler::executeEvent(new UserRegisteredEvent(
                        $user,
                    ));

                    // Link the minecraft integration
                    $integration->successfulRegistration($user);

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

                    unset($_SESSION['authme']);

                    if (Util::getSetting('email_verification') === '1') {
                        // Send registration email
                        sendRegisterEmail($language, $email, $mcname, $user_id, $code);

                        Session::flash('home', $language->get('user', 'registration_check_email'));
                        Redirect::to(URL::build('/'));
                    } else {
                        // Redirect straight to verification link
                        Redirect::to(URL::build('/validate/', 'c=' . urlencode($code)));
                    }
                }
            } else {
                // Validation errors
                $errors = $validation->errors();
            }
        } else {
            // Step 1
            if ($captcha) {
                $captcha_passed = CaptchaBase::getActiveProvider()->validateToken($_POST);
            } else {
                $captcha_passed = true;
            }

            if ($captcha_passed) {
                // Valid recaptcha
                $validation = Validate::check($_POST, [
                    'username' => [
                        Validate::REQUIRED => true,
                        Validate::UNIQUE => 'users',
                    ],
                    'password' => [
                        Validate::REQUIRED => true,
                    ],
                    't_and_c' => [
                        Validate::REQUIRED => true,
                        Validate::AGREE => true,
                    ],
                ])->messages([
                    'username' => [
                        Validate::REQUIRED => $language->get('user', 'username_required'),
                        Validate::UNIQUE => $language->get('user', 'authme_username_exists'),
                    ],
                    'password' => [
                        Validate::REQUIRED => $language->get('user', 'password_required'),
                    ],
                    't_and_c' => $language->get('user', 'accept_terms'),
                ]);

                if ($validation->passed()) {
                    // Try connecting to AuthMe
                    $authme_db = Config::get('authme', []);

                    // Try to connect to the database
                    try {
                        $authme_conn = DB::getCustomInstance($authme_db['address'], $authme_db['db'], $authme_db['user'], $authme_db['pass'], $authme_db['port']);

                        // Success, check user exists in database and validate password
                        $result = $authme_conn->query("SELECT password, ip FROM {$authme_db['table']} WHERE realname = ?", [Input::get('username')]);
                        if ($result->count() > 0) {
                            $result = $result->first();
                            if (is_null($result->password)) {
                                $errors[] = $language->get('user', 'authme_no_password');
                            } else {
                                // Validate inputted password against actual password
                                $valid = false;

                                switch ($authme_db['hash']) {
                                    case 'bcrypt':
                                        if (password_verify($_POST['password'], $result->password)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'username' => Input::get('username'),
                                                'pass' => $result->password,
                                                'ip' => $result->ip,
                                                'hash' => 'bcrypt',
                                            ];
                                        }

                                        break;

                                    case 'sha1':
                                        if (sha1($_POST['password']) == $result->password) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'username' => Input::get('username'),
                                                'pass' => $result->password,
                                                'ip' => $result->ip,
                                                'hash' => 'sha1',
                                            ];
                                        }

                                        break;

                                    // Strip prefixes from password that authme adds
                                    case 'sha256':
                                        // $SHA$<salt>$<password hash>
                                        [, , $salt, $password_hash] = explode('$', $result->password);

                                        if ($salt . hash('sha256', hash('sha256', $_POST['password']) . $salt) == $salt . $password_hash) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'username' => Input::get('username'),
                                                'pass' => $salt . '$' . $password_hash,
                                                'ip' => $result->ip,
                                                'hash' => 'sha256',
                                            ];
                                        }
                                        break;

                                    case 'pbkdf2':
                                        // pbkdf2_sha256$<iterations>$<salt>$<password hash>
                                        [, $iterations, $salt, $pass] = explode('$', $result->password);
                                        $hashed = hash_pbkdf2('sha256', $_POST['password'], $salt, $iterations, 64, true);

                                        if ($hashed == hex2bin($pass)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'username' => Input::get('username'),
                                                'pass' => $iterations . '$' . $salt . '$' . $pass,
                                                'ip' => $result->ip,
                                                'hash' => 'pbkdf2',
                                            ];
                                        }
                                        break;
                                }

                                if ($valid === true) {
                                    // Passwords match
                                    // Continue to step 2
                                    Redirect::to(URL::build('/register', 'step=2'));
                                }

                                // Passwords don't match
                                $errors[] = $language->get('user', 'incorrect_details');
                            }
                        } else {
                            $errors[] =  $language->get('user', 'authme_account_not_found');
                        }
                    } catch (PDOException $exception) {
                        // Connection error
                        ErrorHandler::logWarning('Failure connecting to AuthMe DB during registration process: ' . $exception->getCode() . ' - ' . $exception->getMessage());
                        $errors[] = $language->get('user', 'unable_to_connect_to_authme_db');
                    }
                } else {
                    // Validation errors
                    $errors = $validation->errors();
                }
            } else {
                // Invalid recaotcha
                $errors[] = $language->get('user', 'invalid_recaptcha');
            }
        }
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

if (count($errors)) {
    $smarty->assign('ERRORS', $errors);
}

$smarty->assign('ERROR', $language->get('general', 'error'));

if (!isset($_GET['step'])) {
    $smarty->assign([
        'AUTHME_SETUP' => Config::get('authme'),
        'AUTHME_NOT_SETUP' => $language->get('user', 'authme_not_setup'),
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_INFO' => $language->get('user', 'authme_help'),
        'USERNAME' => $language->get('user', 'username'),
        'USERNAME_INPUT' => Output::getClean(Input::get('username')),
        'PASSWORD' => $language->get('user', 'password'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'I_AGREE' => $language->get('user', 'i_agree'),
        'AGREE_TO_TERMS' => $language->get('user', 'agree_t_and_c', [
            'linkStart' => '<a href="' . URL::build('/terms') . '">',
            'linkEnd' => '</a>',
        ]),
    ]);

    // Recaptcha
    if ($captcha) {
        $smarty->assign('CAPTCHA', CaptchaBase::getActiveProvider()->getHtml());
        $template->addJSFiles([CaptchaBase::getActiveProvider()->getJavascriptSource() => []]);

        $submitScript = CaptchaBase::getActiveProvider()->getJavascriptSubmit('form-contact');
        if ($submitScript) {
            $template->addJSScript('
            $("#form-contact").submit(function(e) {
                e.preventDefault();
                ' . $submitScript . '
            });
        ');
        }
    }

    $template_file = ROOT_PATH . '/custom/templates/' . TEMPLATE . '/authme.tpl';
} else {
    $fields = new Fields();
    // Step 2
    // Are custom usernames enabled?
    if (Util::getSetting('displaynames') === '1') {
        $info = $language->get('user', 'authme_email_help_2');
        $fields->add('nickname', Fields::TEXT, $language->get('user', 'nickname'), true, Output::getClean(Input::get('nickname')));
    } else {
        $info = $language->get('user', 'authme_email_help_1');
    }

    $fields->add('email', Fields::EMAIL, $language->get('user', 'email_address'), true, Output::getClean(Input::get('email')));

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

    $smarty->assign([
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_SYNC_PASSWORD' => $language->get('user', 'authme_sync_password'),
        'AUTHME_SYNC_PASSWORD_HELP' => $language->get('user', 'authme_sync_password_help'),
        'AUTHME_SYNC_PASSWORD_CHECKED' => Input::get('authme_sync_password') === 'on',
        'AUTHME_SUCCESS' => $language->get('user', 'authme_account_linked'),
        'AUTHME_INFO' => $info,
        'FIELDS' => $fields->getAll(),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
    ]);

    $template_file = ROOT_PATH . '/custom/templates/' . TEMPLATE . '/authme_email.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate($template_file, $smarty);
