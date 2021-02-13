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
        if (count($target_user->data())) {
            if(Input::exists()){
                if(Token::check()){
                    // Validate input
                    $to_validation = array(
                        'password' => array(
                            'required' => true,
                            'min' => 6,
                            'max' => 30
                        ),
                        'password_again' => array(
                            'matches' => 'password'
                        ),
                        't_and_c' => array(
                            'required' => true,
                            'agree' => true
                        )
                    );

                    $validate = new Validate();
                    $validation = $validate->check($_POST, $to_validation);

                    if($validation->passed()){
                        // Complete registration
                        // Hash password
                        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));

                        try {
                            $target_user->update(array(
                                'password' => $password,
                                'reset_code' => null,
                                'last_online' => date('U'),
                                'active' => 1
                            ));
                        } catch(Exception $e){
                            die($e->getMessage());
                        }

                        HookHandler::executeEvent('validateUser', array(
                            'event' => 'validateUser',
                            'user_id' => $target_user->data()->id,
                            'username' => $target_user->getDisplayname(),
                            'uuid' => Output::getClean($target_user->data()->uuid),
                            'content' => str_replace('{x}', $target_user->getDisplayname(), $language->get('user', 'user_x_has_validated')),
                            'avatar_url' => $target_user->getAvatar(null, 128, true),
                            'url' => Util::getSelfURL() . ltrim($target_user->getProfileURL(), '/'),
                            'language' => $language
                        ));

                        Session::flash('home', $language->get('user', 'validation_complete'));
                        Redirect::to(URL::build('/'));
                        die();

                    } else {
                        // Errors
                        $errors = array();

                        foreach($validation->errors() as $validation_error){
                            if(strpos($validation_error, 'is required') !== false){
                                // x is required
                                switch($validation_error){
                                    case (strpos($validation_error, 'password') !== false):
                                        $errors[] = $language->get('user', 'password_required');
                                        break;
                                    case (strpos($validation_error, 't_and_c') !== false):
                                        $errors[] = $language->get('user', 'accept_terms');
                                        break;
                                }

                            } else if(strpos($validation_error, 'minimum') !== false){
                                $errors[] = $language->get('user', 'password_minimum_6');

                            } else if(strpos($validation_error, 'maximum') !== false){
                                $errors[] = $language->get('user', 'password_maximum_30');

                            } else if(strpos($validation_error, 'must match') !== false){
                                // password must match password again
                                $errors[] = $language->get('user', 'passwords_dont_match');
                            }
                        }
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
