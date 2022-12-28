<?php
declare(strict_types=1);

/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Panel auth page
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

use GuzzleHttp\Exception\GuzzleException;

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
    try {
        if (Token::check()) {
            // Validate input
            try {
                $validation = Validate::check($_POST, [
                        'password' => [
                            Validate::REQUIRED => true
                        ]
                    ]
                );
            } catch (Exception $ignored) {
            }

            if ($validation->passed()) {
                $user = new User();
                try {
                    $login = $user->adminLogin($user->data()->email, Input::get('password'));
                } catch (GuzzleException $ignored) {
                }

                if ($login) {
                    // Get IP
                    $ip = HttpUtils::getRemoteAddress();

                    // Create log
                    Log::getInstance()->log(Log::Action('admin/login'));

                    // Redirect to a certain page?
                    if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) !== '=') {
                        Redirect::back();
                    } else {
                        Redirect::to(URL::build('/panel'));
                    }
                }

            }
            Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
        } else {
            // Invalid token
            Session::flash('adm_auth_error', $language->get('general', 'invalid_token'));
        }
    } catch (Exception $ignored) {
    }
}

$smarty->assign([
    'PLEASE_REAUTHENTICATE' => $language->get('admin', 're-authenticate'),
    'PASSWORD' => $language->get('user', 'password'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel')
]);

if (Session::exists('adm_auth_error')) {
    $smarty->assign('ERROR', Session::flash('adm_auth_error'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
try {
    $template->displayTemplate('auth.tpl', $smarty);
} catch (SmartyException $ignored) {
}
