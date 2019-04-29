<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Authme connector
 */

$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Ensure AuthMe is enabled
$authme_enabled = $queries->getWhere('settings', array('name', '=', 'authme'));
$authme_enabled = $authme_enabled[0]->value;

// Use recaptcha?
$recaptcha = $queries->getWhere("settings", array("name", "=", "recaptcha"));
$recaptcha = $recaptcha[0]->value;

$recaptcha_key = $queries->getWhere("settings", array("name", "=", "recaptcha_key"));
$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));

// Deal with any input
$errors = array();
if(Input::exists()){
    if(Token::check(Input::get('token'))){
        // Valid token
        if(isset($_GET['step']) && $_GET['step'] == 2){
            // Step 2
            if(!isset($_SESSION['authme'])){
                Redirect::to(URL::build('/register'));
                die();
            }

            $validate = new Validate();

            // Are custom usernames enabled?
            $custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
            $custom_usernames = $custom_usernames[0]->value;

            if($custom_usernames == 'true'){
                $validation = $validate->check($_POST, array(
                    'nickname' => array(
                        'required' => true,
                        'min' => 3,
                        'max' => 20,
                        'unique' => 'users'
                    ),
                    'email' => array(
                        'required' => true,
                        'min' => 4,
                        'max' => 64,
                        'unique' => 'users'
                    )
                ));
            } else {
                $validation = $validate->check($_POST, array(
                    'email' => array(
                        'required' => true,
                        'min' => 4,
                        'max' => 64,
                        'unique' => 'users'
                    )
                ));
            }

            if($validation->passed()){
                // Get Authme hashing method
                $cache->setCache('authme_cache');
                $authme_hash = $cache->retrieve('authme');

                // UUID linking
                $uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
                $uuid_linking = $uuid_linking[0]->value;

                // Get default language ID before creating user
                $language_id = $queries->getWhere('languages', array('name', '=', LANGUAGE));

                if(count($language_id)) $language_id = $language_id[0]->id;
                else $language_id = 1; // fallback to EnglishUK

                $ip = $user->getIP();
                if(filter_var($ip, FILTER_VALIDATE_IP)){
                    // Valid IP
                } else
                    $ip = $_SESSION['authme']['ip'];

                if($custom_usernames == 'true')
                    $nickname = Output::getClean(Input::get('nickname'));
                else
                    $nickname = $_SESSION['authme']['user'];

                $mcname = $_SESSION['authme']['user'];

                // UUID
                if($uuid_linking == '1'){
                    require(ROOT_PATH . '/core/integration/uuid.php'); // For UUID stuff
                    if(!isset($mcname_result)){
                        $profile = ProfileUtils::getProfile(str_replace(' ', '%20', $mcname));
                        if($profile && method_exists($profile, 'getProfileAsArray'))
                            $mcname_result = $profile->getProfileAsArray();
                    }
                    if(isset($mcname_result["uuid"]) && !empty($mcname_result['uuid'])){
                        $uuid = $mcname_result['uuid'];
                    } else {
                        $errors[] = $language->get('user', 'mcname_lookup_error');
                        $uuid = 'none';
                    }
                } else {
                    $uuid = 'none';
                }

                try {
                    $user->create(array(
                        'username' => $_SESSION['authme']['user'],
                        'nickname' => $nickname,
                        'password' => $_SESSION['authme']['pass'],
                        'pass_method' => $authme_hash['hash'],
                        'uuid' => $uuid,
                        'joined' => date('U'),
                        'group_id' => 1,
                        'email' => Output::getClean(Input::get('email')),
                        'lastip' => $ip,
                        'active' => 1,
                        'last_online' => date('U')
                    ));

                    unset($_SESSION['authme']);

                    Session::flash('home', $language->get('user', 'validation_complete'));
                    Redirect::to(URL::build('/'));
                    die();

                } catch(Exception $e){
                    $errors[] = $e->getMessage();
                }

            } else {
                // Validation errors
                foreach($validation->errors() as $validation_error){
                    if(strpos($validation_error, 'is required') !== false){
                        // x is required
                        switch($validation_error){
                            case (strpos($validation_error, 'nickname') !== false):
                                $errors[] = $language->get('user', 'username_required');
                                break;
                            case (strpos($validation_error, 'email') !== false):
                                $errors[] = $language->get('user', 'email_required');
                                break;
                        }

                    } else if(strpos($validation_error, 'minimum') !== false){
                        // x must be a minimum of y characters long
                        switch($validation_error){
                            case (strpos($validation_error, 'nickname') !== false):
                                $errors[] = $language->get('user', 'username_minimum_3');
                                break;
                            case (strpos($validation_error, 'email') !== false):
                                $errors[] = $language->get('user', 'invalid_email');
                                break;
                        }

                    } else if(strpos($validation_error, 'maximum') !== false){
                        // x must be a maximum of y characters long
                        switch($validation_error){
                            case (strpos($validation_error, 'username') !== false):
                                $errors[] = $language->get('user', 'username_maximum_20');
                                break;
                            case (strpos($validation_error, 'email') !== false):
                                $errors[] = $language->get('user', 'invalid_email');
                                break;
                        }

                    } else if(strpos($validation_error, 'already exists') !== false){
                        // already exists
                        if(!in_array($language->get('user', 'username_mcname_email_exists'), $errors))
                            $errors[] = $language->get('user', 'username_mcname_email_exists');

                    }
                }
            }

        } else {
            // Step 1
            if($recaptcha == 'true'){
                // Check reCAPCTHA
                $url = 'https://www.google.com/recaptcha/api/siteverify';

                $post_data = 'secret=' . $recaptcha_secret[0]->value . '&response=' . Input::get('g-recaptcha-response');

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $result = curl_exec($ch);

                $result = json_decode($result, true);
            } else {
                // reCAPTCHA is disabled
                $result = array(
                    'success' => 'true'
                );
            }

            if(isset($result['success']) && $result['success'] == 'true'){
                // Valid recaptcha
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'username' => array(
                        'required' => true,
                        'unique' => 'users'
                    ),
                    'password' => array(
                        'required' => true
                    ),
                    't_and_c' => array(
                        'required' => true,
                        'agree' => true
                    )
                ));

                if($validation->passed()){
                    // Try connecting to AuthMe
                    $cache->setCache('authme_cache');
                    $authme_db = $cache->retrieve('authme');

                    // Try to connect to the database
                    $authme_conn = new mysqli($authme_db['address'], $authme_db['user'], $authme_db['pass'], $authme_db['db'], $authme_db['port']);

                    if($authme_conn->connect_errno){
                        // Connection error
                        $errors[] = $authme_conn->connect_errno . ' - ' . $authme_conn->connect_error;
                        $errors[] = $language->get('user', 'unable_to_connect_to_authme_db');
                    } else {
                        // Success, check user exists in database and validate password
                        $stmt = $authme_conn->prepare("SELECT password, ip FROM " . $authme_db['table'] . " WHERE realname = ?");
                        if($stmt){
                            $stmt->bind_param('s', Input::get('username'));
                            $stmt->execute();
                            $stmt->bind_result($password, $ip);

                            while($stmt->fetch()){
                                // Retrieve result
                            }

                            $stmt->free_result();
                            $stmt->close();

                            if(is_null($password)){
                                $errors[] = $language->get('user', 'incorrect_details');
                            } else {
                                // Validate inputted password against actual password
                                $valid = false;

                                switch($authme_db['hash']){
                                    case 'bcrypt':
                                        require(ROOT_PATH . '/core/includes/password.php');

                                        if(password_verify($_POST['password'], $password)){
                                            $valid = true;
                                            $_SESSION['authme'] = array(
                                                'user' => Output::getClean(Input::get('username')),
                                                'pass' => $password,
                                                'ip' => $ip
                                            );
                                        }

                                        break;

                                    case 'sha1':
                                        if(sha1($_POST['password']) == $password){
                                            $valid = true;
                                            $_SESSION['authme'] = array(
                                                'user' => Output::getClean(Input::get('username')),
                                                'pass' => $password,
                                                'ip' => $ip
                                            );
                                        }

                                        break;

                                    case 'sha256':
                                        $exploded = explode('$', $password);
                                        $salt = $exploded[2];

                                        if($salt . hash('sha256', hash('sha256', $_POST['password']) . $salt) == $salt . $exploded[3]){
                                            $valid = true;
                                            $_SESSION['authme'] = array(
                                                'user' => Output::getClean(Input::get('username')),
                                                'pass' => ($salt . '$' . $exploded[3]),
                                                'ip' => $ip
                                            );
                                        }

                                        break;

                                    case 'pbkdf2':
                                        $exploded = explode('$', $password);

                                        $iterations = $exploded[1];
                                        $salt = $exploded[2];
                                        $pass = $exploded[3];

                                        $hashed = hash_pbkdf2('sha256', $_POST['password'], $salt, $iterations, 64, true);

                                        if($hashed == hex2bin($pass)){
                                            $valid = true;
                                            $_SESSION['authme'] = array(
                                                'user' => Output::getClean(Input::get('username')),
                                                'pass' => ($iterations . '$' . $salt . '$' . $pass),
                                                'ip' => $ip
                                            );
                                        }

                                        break;
                                }

                                if($valid === true){
                                    // Passwords match
                                    // Continue to step 2
                                    Redirect::to(URL::build('/register', 'step=2'));
                                    die();

                                } else {
                                    // Passwords don't match
                                    $errors[] = $language->get('user', 'incorrect_details');
                                }

                            }

                        } else {
                            $errors[] = $language->get('user', 'unable_to_connect_to_authme_db');
                        }

                        $authme_conn->close();
                    }
                } else {
                    // Validation errors
                    foreach ($validation->errors() as $validation_error) {
                        if (strpos($validation_error, 'is required') !== false) {
                            switch ($validation_error) {
                                case (strpos($validation_error, 'username') !== false):
                                    $errors[] = $language->get('user', 'username_required');
                                    break;
                                case (strpos($validation_error, 'password') !== false):
                                    $errors[] = $language->get('user', 'password_required');
                                    break;
                                case (strpos($validation_error, 't_and_c') !== false):
                                    $errors[] = $language->get('user', 'accept_terms');
                                    break;
                            }
                        } else if(strpos($validation_error, 'exists') !== false){
                            $errors[] = $language->get('user', 'authme_username_exists');
                        }
                    }
                }

            } else {
                // Invalid recaotcha

            }
        }
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

if(count($errors))
    $smarty->assign('ERRORS', $errors);

if(!isset($_GET['step'])){
    // Smarty
    $smarty->assign(array(
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_INFO' => $language->get('user', 'authme_help'),
        'USERNAME' => $language->get('user', 'username'),
        'PASSWORD' => $language->get('user', 'password'),
        'TOKEN' => Token::generate(),
        'SUBMIT' => $language->get('general', 'submit'),
        'I_AGREE' => $language->get('user', 'i_agree'),
        'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c'))
    ));

    // Recaptcha
    if($recaptcha == 'true'){
        $smarty->assign('RECAPTCHA', Output::getClean($recaptcha_key[0]->value));
    }

    $template_file = ROOT_PATH . '/custom/templates/' . TEMPLATE . '/authme.tpl';
} else {
    // Step 2
    // Are custom usernames enabled?
    $custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
    $custom_usernames = $custom_usernames[0]->value;

    if($custom_usernames == 'true') {
        $info = $language->get('user', 'authme_email_help_2');
        $smarty->assign('NICKNAME', $language->get('user', 'username'));
    } else
        $info = $language->get('user', 'authme_email_help_1');

    $smarty->assign(array(
        'CONNECT_WITH_AUTHME' => $language->get('user', 'connect_with_authme'),
        'AUTHME_SUCCESS' => $language->get('user', 'authme_account_linked'),
        'AUTHME_INFO' => $info,
        'EMAIL' => $language->get('user', 'email'),
        'TOKEN' => Token::generate(),
        'SUBMIT' => $language->get('general', 'submit')
    ));

    $template_file = ROOT_PATH . '/custom/templates/' . TEMPLATE . '/authme_email.tpl';
}

if($recaptcha === "true"){
	$template->addJSFiles(array(
		'https://www.google.com/recaptcha/api.js' => array()
	));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate($template_file, $smarty);