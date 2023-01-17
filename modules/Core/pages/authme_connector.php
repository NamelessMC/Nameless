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

            // Are custom usernames enabled?
            if (Util::getSetting('displaynames') === '1') {
                $to_validation['username'] = [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20,
                    Validate::UNIQUE => 'users',
                ];
            }

            $to_validation['email'] = [
                Validate::REQUIRED => true,
                Validate::MIN => 4,
                Validate::MAX => 64,
                Validate::UNIQUE => 'users',
            ];

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

            $validation = Validate::check($_POST, $to_validation);

            $validation->messages([
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists'),
                ],
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::MIN => $language->get('user', 'invalid_email'),
                    Validate::MAX => $language->get('user', 'invalid_email'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists'),
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
                // Get Authme hashing method
                $cache->setCache('authme_cache');
                $authme_hash = $cache->retrieve('authme');

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

                $mcname = Output::getClean($_SESSION['authme']['user']);
                if ($custom_usernames == 'true') {
                    $nickname = Input::get('nickname');
                } else {
                    $nickname = $mcname;
                }

                // Add username back to post for integration handling
                $_POST['username'] = $_SESSION['authme']['user'];

                $integration = Integrations::getInstance()->getIntegration('Minecraft');
                $integration->afterRegistrationValidation();

                if (count($integration->getErrors())) {
                    $errors = $integration->getErrors();
                } else {
                    // Get default group ID
                    $cache->setCache('default_group');
                    if ($cache->isCached('default_group')) {
                        $default_group = $cache->retrieve('default_group');
                    } else {
                        $default_group = DB::getInstance()->get('groups', ['default_group', true])->results();
                        if (!count($default_group)) {
                            $default_group = 1;
                        } else {
                            $default_group = $default_group[0]->id;
                        }

                        $cache->store('default_group', $default_group);
                    }

                    $user->create([
                        'username' => $_SESSION['authme']['user'],
                        'nickname' => $nickname,
                        'password' => $_SESSION['authme']['pass'],
                        'pass_method' => $authme_hash['hash'],
                        'joined' => date('U'),
                        'email' => Output::getClean(Input::get('email')),
                        'lastip' => $ip,
                        'active' => true,
                        'last_online' => date('U'),
                        'language_id' => $language_id,
                    ]);

                    // Get user ID
                    $user_id = DB::getInstance()->lastId();

                    $user = new User($user_id);
                    $user->addGroup($default_group);

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

                    Session::flash('home', $language->get('user', 'validation_complete'));
                    Redirect::to(URL::build('/'));
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
                    $cache->setCache('authme_cache');
                    $authme_db = $cache->retrieve('authme');

                    // Try to connect to the database
                    try {
                        $authme_conn = DB::getCustomInstance($authme_db['address'], $authme_db['db'], $authme_db['user'], $authme_db['pass'], $authme_db['port']);

                        // Success, check user exists in database and validate password
                        $result = $authme_conn->query('SELECT password, ip FROM ' . $authme_db['table'] . ' WHERE realname = ?', [Input::get('username')]);
                        if ($result->count() > 0) {
                            $result = $result->first();
                            if (is_null($result->password)) {
                                $errors[] = $language->get('user', 'incorrect_details');
                            } else {
                                // Validate inputted password against actual password
                                $valid = false;

                                switch ($authme_db['hash']) {
                                    case 'bcrypt':
                                        if (password_verify($_POST['password'], $result->password)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => $result->password,
                                                'ip' => $result->ip,
                                            ];
                                        }

                                        break;

                                    case 'sha1':
                                        if (sha1($_POST['password']) == $result->password) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => $result->password,
                                                'ip' => $result->ip,
                                            ];
                                        }

                                        break;

                                    case 'sha256':
                                        $exploded = explode('$', $result->password);
                                        $salt = $exploded[2];

                                        if ($salt . hash('sha256', hash('sha256', $_POST['password']) . $salt) == $salt . $exploded[3]) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => ($salt . '$' . $exploded[3]),
                                                'ip' => $result->ip,
                                            ];
                                        }

                                        break;

                                    case 'pbkdf2':
                                        [, $iterations, $salt, $pass] = explode('$', $result->password);

                                        $hashed = hash_pbkdf2('sha256', $_POST['password'], $salt, $iterations, 64, true);

                                        if ($hashed == hex2bin($pass)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => ($iterations . '$' . $salt . '$' . $pass),
                                                'ip' => $result->ip,
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
                        $errors[] = $exception->getCode() . ' - ' . $exception->getMessage();
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
    // Check if authme has been setup
    $cache->setCache('authme_cache');
    $authme_hash = $cache->retrieve('authme');

    // Smarty
    $smarty->assign([
        'AUTHME_SETUP' => $authme_hash !== null,
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
