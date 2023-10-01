<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Panel auth page
 */

if ($user->isLoggedIn()) {
    if (!$user->canViewStaffCP()) {
        // No
        Redirect::to(URL::build('/'));
    }
    if ($user->isAdmLoggedIn()) {
        // Already authenticated
        Redirect::to(URL::build('/panel'));
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
}

const PAGE = 'panel';
const PANEL_PAGE = 'auth';
$page_title = $language->get('admin', 're-authenticate');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with any input
if (Input::exists()) {
    if (Token::check()) {
        // Validate input
        $validation = Validate::check($_POST, [
            'password' => [
                    Validate::REQUIRED => true
                ]
            ]
        );

        if ($validation->passed()) {
            $user = new User();

            if ($user->checkCredentials($user->data()->email, Input::get('password'))) {
                $success = true;

                if (
                    Settings::get('require_staffcp_tfa') &&
                    $user->data()->tfa_type === 1 &&
                    $user->data()->tfa_complete == 1
                ) {
                    $success = false;
                    $tfa = new \RobThree\Auth\TwoFactorAuth('NamelessMC');

                    if ($tfa->verifyCode($user->data()->tfa_secret, str_replace(' ', '', $_POST['tfa_code'])) !== true) {
                        Session::flash('adm_auth_error', $language->get('user', 'invalid_tfa'));
                    } else {
                        $success = true;
                    }
                }

                if ($success) {
                    // Get IP
                    $ip = HttpUtils::getRemoteAddress();

                    // Create log
                    Log::getInstance()->log(Log::Action('admin/login'));

                    $user->adminLogin($user->data()->email, Input::get('password'));

                    // Redirect to a certain page?
                    if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) != '=') {
                        Redirect::back();
                    } else {
                        Redirect::to(URL::build('/panel'));
                    }
                }
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

$smarty->assign([
    'PLEASE_REAUTHENTICATE' => $language->get('admin', 're-authenticate'),
    'PASSWORD' => $language->get('user', 'password'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel')
]);

if (
    Settings::get('require_staffcp_tfa') &&
    $user->data()->tfa_type === 1 &&
    $user->data()->tfa_complete == 1
) {
    $smarty->assign([
        'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
        'TFA_ENTER_CODE' => $language->get('user', 'two_factor_auth_code'),
    ]);
}

if (Session::exists('adm_auth_error')) {
    $smarty->assign('ERROR', Session::flash('adm_auth_error'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('auth.tpl', $smarty);
