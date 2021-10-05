<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  API signup completion
 */

$page = 'complete_signup';
define('PAGE', 'complete_signup');
$page_title = $language->get('general', 'register');

require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

// Validate code
if(!isset($_GET['c'])){
    Redirect::to(URL::build('/'));
    die();
} else {
    require(ROOT_PATH . '/core/includes/password.php');

    // Ensure API is enabled
    $is_api_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
    if($is_api_enabled[0]->value != '1'){
        $is_legacy_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
        if($is_legacy_enabled[0]->value != '1'){
            die('Legacy API is disabled');
        }
    }

    if(!$user->isLoggedIn()){
		$target_user = new User($_GET['c'], 'reset_code');
        if ($target_user->data()) {
            if(Input::exists()){
                if(Token::check()){
                    // Validate input
                    $validate = new Validate();
                    $validation = $validate->check($_POST, [
                        'password' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 6
                        ],
                        'password_again' => [
                            Validate::MATCHES => 'password'
                        ],
                        't_and_c' => [
                            Validate::REQUIRED => true,
                            Validate::AGREE => true
                        ]
                    ])->messages([
                        'password' => [
                            Validate::REQUIRED => $language->get('user', 'password_required'),
                            Validate::MIN => $language->get('user', 'password_minimum_6')
                        ],
                        'password_again' => $language->get('user', 'passwords_dont_match'),
                        't_and_c' => $language->get('user', 'accept_terms')
                    ]);

                    if($validation->passed()){
                        // Complete registration
                        // Hash password
                        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));

                        $target_user->update(array(
                            'password' => $password,
                            'reset_code' => null,
                            'last_online' => date('U'),
                            'active' => 1
                        ));

                        HookHandler::executeEvent('validateUser', array(
                            'event' => 'validateUser',
                            'user_id' => $target_user->data()->id,
                            'username' => $target_user->getDisplayname(),
                            'uuid' => Output::getClean($target_user->data()->uuid),
                            'content' => str_replace('{x}', $target_user->getDisplayname(), $language->get('user', 'user_x_has_validated')),
                            'avatar_url' => $target_user->getAvatar(128, true),
                            'url' => Util::getSelfURL() . ltrim($target_user->getProfileURL(), '/'),
                            'language' => $language
                        ));

                        Session::flash('home', $language->get('user', 'validation_complete'));
                        Redirect::to(URL::build('/'));
                        die();

                    } else {
                        // Errors
                        $errors = $validation->errors();
                    }

                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }
        } else {
            Session::flash('home', $language->get('user', 'validation_error'));
            Redirect::to(URL::build('/'));
            die();
        }
    } else {
        Redirect::to(URL::build('/'));
        die();
    }
}

// Smarty variables
if(isset($errors) && count($errors)){
	$smarty->assign('ERRORS', $errors);
}

$smarty->assign(array(
	'REGISTER' => $language->get('general', 'register'),
	'PASSWORD' => $language->get('user', 'password'),
	'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
	'SUBMIT' => $language->get('general', 'submit'),
	'I_AGREE' => $language->get('user', 'i_agree'),
	'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c')),
	'TOKEN' => Token::get()
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate('complete_signup.tpl', $smarty);
