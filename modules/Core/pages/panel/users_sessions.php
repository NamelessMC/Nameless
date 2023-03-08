<?php
/*
 *  Made by Supercrafter100
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.3
 *
 *  License: MIT
 *
 *  Panel user sessions page
 */
if (!$user->handlePanelPageLoad('admincp.users.sessions')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'sessions';
$page_title = $language->get('admin', 'user_sessions');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
}

$view_user = new User($_GET['id']);
if (!$view_user->exists()) {
    Redirect::to(URL::build('/panel/users'));
}

if (Input::exists()) {
    if (Token::check()) {
        if ($_POST['action'] === 'logout' && isset($_POST['sid'])) {
            DB::getInstance()->update('users_session', $_POST['sid'], [
                'active' => false
            ]);
            $success = $language->get('general', 'logout_session_successfully');
        } else if ($_POST['action'] === 'logout_other_sessions') {
            $view_user->logoutAllOtherSessions();
            $success = $language->get('admin', 'sessions_logged_out');
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}
$timeago = new TimeAgo(TIMEZONE);
$sessions = $view_user->getActiveSessions();
$user_sessions_list = [];

// TODO: Should we display all sessions, or just active ones? Over time, the list could get very long if we display all sessions.
// Not really any reason to show inactive ones, since they can't action on them.
foreach ($sessions as $session) {
    $agent = new \Jenssegers\Agent\Agent();
    $agent->setUserAgent($session->user_agent);

    $user_sessions_list[] = [
        'id' => $session->id,
        'ip' => $session->ip . ' (' . HttpUtils::getIpCountry($session->ip) . ')',
        'device_type' => $agent->deviceType(),
        'device_os' => $agent->platform(),
        'device_browser' => $agent->browser(),
        'device_browser_version' => $agent->version($agent->browser()),
        'method' => $session->login_method,
        'last_seen_short' => $session->last_seen
            ? $timeago->inWords($session->last_seen, $language)
            : $language->get('admin', 'unknown'),
        'last_seen_long' => $session->last_seen
            ? date(DATE_FORMAT, $session->last_seen)
            : $language->get('admin', 'unknown'),
        'is_current' => in_array($session->hash, [
            Session::get(Config::get('session.session_name')), Session::get(Config::get('session.admin_name'))
        ]),
    ];
}

$smarty->assign([
    'VIEWING_USER_SESSIONS' => $language->get('admin', 'viewing_sessions_for_x', [
        'user' =>  Output::getClean($view_user->data()->username),
    ]),
    'SESSIONS' => $user_sessions_list,
    'DEVICE' => $language->get('general', 'device'),
    'LOGIN_METHOD' => $language->get('admin', 'login_method'),
    'BACK_LINK' => URL::build('/panel/user/' . Output::getClean($view_user->data()->id . '-' . $view_user->data()->username)),
    'LOGOUT' => $language->get('general', 'log_out'),
    'IP_ADDRESS' => $language->get('admin', 'ip_address'),
    'LAST_SEEN' => $language->get('admin', 'last_seen'),
]);

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'USER_ID' => $view_user->data()->id,
    'BACK' => $language->get('general', 'back')
]);

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$template->onPageLoad();
require(ROOT_PATH . '/core/templates/panel_navbar.php');
$template->displayTemplate('core/users_sessions.tpl', $smarty);
