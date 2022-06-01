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
                $validation = Validate::check($_POST, [
                    'nickname' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 3,
                        Validate::MAX => 20,
                        Validate::UNIQUE => 'users'
                    ],
                    'email' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 4,
                        Validate::MAX => 64,
                        Validate::UNIQUE => 'users'
                    ]
                ]);
            } else {
                $validation = Validate::check($_POST, [
                    'email' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 4,
                        Validate::MAX => 64,
                        Validate::UNIQUE => 'users'
                    ]
                ]);
            }

            $validation->messages([
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists')
                ],
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::MIN => $language->get('user', 'invalid_email'),
                    Validate::MAX => $language->get('user', 'invalid_email'),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists')
                ]
            ]);

            if ($validation->passed()) {
                // Get Authme hashing method
                $cache->setCache('authme_cache');
                $authme_hash = $cache->retrieve('authme');

                // Get default language ID before creating user
                $language_id = DB::getInstance()->get('languages', ['short_code', LANGUAGE])->results();

                if (count($language_id)) {
                    $language_id = $language_id[0]->id;
                } else {
                    // fallback to EnglishUK
                    $language_id = DB::getInstance()->get('languages', ['short_code', 'en_UK'])->results();
                    $language_id = $language_id[0]->id;
                }

                $ip = Util::getRemoteAddress();
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    // Valid IP
                } else {
                    $ip = $_SESSION['authme']['ip'];
                }

                if ($custom_usernames == 'true') {
                    $nickname = Input::get('nickname');
                } else {
                    $nickname = $_SESSION['authme']['user'];
                }

                $mcname = Output::getClean($_SESSION['authme']['user']);

                // Add username back to post for integration handling
                $_POST['username'] = $_SESSION['authme']['user'];

                $integration = Integrations::getInstance()->getIntegration('Minecraft');
                $integration->afterRegistrationValidation();

                if (count($integration->getErrors())) {
                    $errors = $integration->getErrors();
                }

                if (!count($errors)) {
                    try {
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
                            'language_id' => $language_id
                        ]);

                        // Get user ID
                        $user_id = DB::getInstance()->lastId();

                        $user = new User($user_id);
                        $user->addGroup($default_group);

                        // Link the minecraft integration
                        $integration->successfulRegistration($user);

                        unset($_SESSION['authme']);

                        Session::flash('home', $language->get('user', 'validation_complete'));
                        Redirect::to(URL::build('/'));
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
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
                        Validate::UNIQUE => 'users'
                    ],
                    'password' => [
                        Validate::REQUIRED => true
                    ],
                    't_and_c' => [
                        Validate::REQUIRED => true,
                        Validate::AGREE => true
                    ]
                ])->messages([
                    'username' => [
                        Validate::REQUIRED => $language->get('user', 'username_required'),
                        Validate::UNIQUE => $language->get('user', 'authme_username_exists')
                    ],
                    'password' => [
                        Validate::REQUIRED => $language->get('user', 'password_required')
                    ],
                    't_and_c' => $language->get('user', 'accept_terms')
                ]);

                if ($validation->passed()) {
                    // Try connecting to AuthMe
                    $cache->setCache('authme_cache');
                    $authme_db = $cache->retrieve('authme');

                    // Try to connect to the database
                    $authme_conn = new mysqli($authme_db['address'], $authme_db['user'], $authme_db['pass'], $authme_db['db'], $authme_db['port']);

                    if ($authme_conn->connect_errno) {
                        // Connection error
                        $errors[] = $authme_conn->connect_errno . ' - ' . $authme_conn->connect_error;
                        $errors[] = $language->get('user', 'unable_to_connect_to_authme_db');
                    } else {
                        // Success, check user exists in database and validate password
                        $stmt = $authme_conn->prepare('SELECT password, ip FROM ' . $authme_db['table'] . ' WHERE realname = ?');
                        if ($stmt) {
                            $stmt->bind_param('s', Input::get('username'));
                            $stmt->execute();
                            $stmt->bind_result($password, $ip);

                            while ($stmt->fetch()) {
                                // Retrieve result
                            }

                            $stmt->free_result();
                            $stmt->close();

                            if (is_null($password)) {
                                $errors[] = $language->get('user', 'incorrect_details');
                            } else {
                                // Validate inputted password against actual password
                                $valid = false;

                                switch ($authme_db['hash']) {
                                    case 'bcrypt':
                                        if (password_verify($_POST['password'], $password)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => $password,
                                                'ip' => $ip
                                            ];
                                        }

                                        break;

                                    case 'sha1':
                                        if (sha1($_POST['password']) == $password) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => $password,
                                                'ip' => $ip
                                            ];
                                        }

                                        break;

                                    case 'sha256':
                                        $exploded = explode('$', $password);
                                        $salt = $exploded[2];

                                        if ($salt . hash('sha256', hash('sha256', $_POST['password']) . $salt) == $salt . $exploded[3]) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => ($salt . '$' . $exploded[3]),
                                                'ip' => $ip
                                            ];
                                        }

                                        break;

                                    case 'pbkdf2':
                                        $exploded = explode('$', $password);

                                        $iterations = $exploded[1];
                                        $salt = $exploded[2];
                                        $pass = $exploded[3];

                                        $hashed = hash_pbkdf2('sha256', $_POST['password'], $salt, $iterations, 64, true);

                                        if ($hashed == hex2bin($pass)) {
                                            $valid = true;
                                            $_SESSION['authme'] = [
                                                'user' => Input::get('username'),
                                                'pass' => ($iterations . '$' . $salt . '$' . $pass),
                                                'ip' => $ip
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
                            $errors[] = $language->get('user', 'unable_to_connect_to_authme_db');
                        }

                        $authme_conn->close();
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
    // Smarty
    $smarty->assign([
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_INFO' => $language->get('user', 'authme_help'),
        'USERNAME' => $language->get('user', 'username'),
        'PASSWORD' => $language->get('user', 'password'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'I_AGREE' => $language->get('user', 'i_agree'),
        'AGREE_TO_TERMS' => $language->get('user', 'agree_t_and_c', [
            'linkStart' => '<a href="' . URL::build('/terms') . '">',
            'linkEnd' => '</a>',
        ])
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
    // Step 2
    // Are custom usernames enabled?
    if (Util::getSetting('displaynames') === '1') {
        $info = $language->get('user', 'authme_email_help_2');
        $smarty->assign('NICKNAME', $language->get('user', 'username'));
    } else {
        $info = $language->get('user', 'authme_email_help_1');
    }

    $smarty->assign([
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_SUCCESS' => $language->get('user', 'authme_account_linked'),
        'AUTHME_INFO' => $info,
        'EMAIL' => $language->get('user', 'email'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    ]);

    $template_file = ROOT_PATH . '/custom/templates/' . TEMPLATE . '/authme_email.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate($template_file, $smarty);
