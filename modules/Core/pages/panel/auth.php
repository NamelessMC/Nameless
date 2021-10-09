<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel auth page
 */

if ($user->isLoggedIn()) {
    if (!$user->canViewStaffCP()) {
        // No
        Redirect::to(URL::build('/'));
        die();
    }
    if ($user->isAdmLoggedIn()) {
        // Already authenticated
        Redirect::to(URL::build('/panel'));
        die();
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

define('PAGE', 'panel');
define('PANEL_PAGE', 'auth');
$page_title = $language->get('admin', 're-authenticate');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

require(ROOT_PATH . '/core/includes/password.php'); // Require password compat library

// Get login method
$login_method = $queries->getWhere('settings', array('name', '=', 'login_method'));
$login_method = $login_method[0]->value;

// Deal with any input
if (Input::exists()) {
    if (Token::check()) {
        // Validate input
        $validate = new Validate();

        if ($login_method == 'email') {
            $to_validate = [
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::IS_BANNED => true,
                    Validate::IS_ACTIVE => true
                ],
                'password' => [
                    Validate::REQUIRED => true
                ]
            ];   
        } else {
            $to_validate = [
                'username' => [
                    Validate::REQUIRED => true,
                    Validate::IS_BANNED => true,
                    Validate::IS_ACTIVE => true
                ],
                'password' => [
                    Validate::REQUIRED => true
                ]
            ];
        }

        $validation = $validate->check($_POST, $to_validate);

        if ($validation->passed()) {
            if ($login_method == 'email') {
                $username = Input::get('email');
                $method_field = 'email';
            } else if ($login_method == 'email_or_username') {
                $username = Input::get('username');
                if (strpos(Input::get('username'), '@') !== false) {
                    $method_field = 'email';
                } else {
                    $method_field = 'username';
                }
            } else {
                $username = Input::get('username');
                $method_field = 'username';
            }

            $user = new User();
            $login = $user->adminLogin($username, Input::get('password'), $method_field);

            if ($login) {
                // Get IP
                $ip = $user->getIP();

                // Create log
                Log::getInstance()->log(Log::Action('admin/login'));

                // Redirect to a certain page?
                if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) != '=') {
                    Redirect::to($_SESSION['last_page']);
                } else {
                    Redirect::to(URL::build('/panel'));
                }

                die();
            } else {
                Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
            }
        } else {
            Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
        }
    } else {
        // Invalid token
        Session::flash('adm_auth_error', $language->get('general', 'invalid_token'));
    }
}

if ($login_method == 'email') {
    $smarty->assign(array(
        'EMAIL' => $language->get('user', 'email')
    ));
} else if ($login_method == 'email_or_username') {
    $smarty->assign(array(
        'USERNAME' => $language->get('user', 'email_or_username')
    ));
} else {
    $smarty->assign(array(
        'USERNAME' => $language->get('user', 'username')
    ));
}

$smarty->assign(array(
    'PLEASE_REAUTHENTICATE' => $language->get('admin', 're-authenticate'),
    'PASSWORD' => $language->get('user', 'password'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel')
));

if (Session::exists('adm_auth_error'))
    $smarty->assign('ERROR', Session::flash('adm_auth_error'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('auth.tpl', $smarty);
