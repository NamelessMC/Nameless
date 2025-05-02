<?php
/**
 * StaffCP user sessions page.
 *
 * @author Supercrafter100
 * @version 2.2.0
 * @license MIT
 *
 * @var Cache $cache
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var FakeSmarty $smarty
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */
if (!$user->handlePanelPageLoad('admincp.users.sessions')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'sessions';
$page_title = $language->get('admin', 'user_sessions');
require_once ROOT_PATH . '/core/templates/backend_init.php';

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
            $success = $language->get('general', 'logout_session_successful');
        } elseif ($_POST['action'] === 'logout_other_sessions') {
            $view_user->logoutAllOtherSessions();
            $success = $language->get('admin', 'sessions_logged_out');
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}
$timeAgo = new TimeAgo(TIMEZONE);
$sessions = $view_user->getActiveSessions();
$user_sessions_list = [];

foreach ($sessions as $session) {
    $agent = new \Jenssegers\Agent\Agent();
    $agent->setUserAgent($session->user_agent);

    $user_sessions_list[] = [
        'id' => $session->id,
        'ip' => Output::getClean($session->ip . ' (' . HttpUtils::getIpCountry($session->ip) . ')'),
        'device_type' => Output::getClean($agent->deviceType()),
        'device_os' => Output::getClean($agent->platform()),
        'device_browser' => Output::getClean($agent->browser()),
        'device_browser_version' => Output::getClean($agent->version($agent->browser())),
        'method' => $session->login_method,
        'last_seen_short' => $session->last_seen
            ? $timeAgo->inWords($session->last_seen, $language)
            : $language->get('admin', 'unknown'),
        'last_seen_long' => $session->last_seen
            ? date(DATE_FORMAT, $session->last_seen)
            : $language->get('admin', 'unknown'),
        'is_current' => in_array($session->hash, [
            Session::get(Config::get('session.session_name')), Session::get(Config::get('session.admin_name')),
        ]),
    ];
}

$template->getEngine()->addVariables([
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
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'USER_ID' => $view_user->data()->id,
    'BACK' => $language->get('general', 'back'),
]);

if (isset($success)) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

$template->onPageLoad();
require ROOT_PATH . '/core/templates/panel_navbar.php';
$template->displayTemplate('core/users_sessions.tpl');
