<?php

/**
 * User sessions page.
 *
 * @author Aberdeener
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

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_sessions';
$page_title = $language->get('user', 'user_cp');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

$timeAgo = new TimeAgo(TIMEZONE);

if (Input::exists()) {
    if (Token::check()) {
        if (Input::get('action') === 'logout_other_sessions') {
            $user->logoutAllOtherSessions();
        } else {
            $user->logoutSessionById(Input::get('session_hash'));
        }

        Session::flash('user_sessions_success', $language->get('general', 'logout_session_successful'));
        Redirect::to(URL::build('/user/sessions'));
    } else {
        // Invalid token
        Session::flash('user_sessions_error', $language->get('general', 'invalid_token'));
    }
}

if (Session::exists('user_sessions_success')) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $language->get('general', 'success'),
        'SUCCESS_MESSAGE' => Session::flash('user_sessions_success'),
    ]);
}

if (Session::exists('user_sessions_error')) {
    $template->getEngine()->addVariables([
        'ERROR' => $language->get('general', 'error'),
        'ERROR_MESSAGE' => Session::flash('user_sessions_error'),
    ]);
}

$sessions = $user->getActiveSessions();
$sessions_list = [];

foreach ($sessions as $session) {
    $agent = new \Jenssegers\Agent\Agent();
    $agent->setUserAgent($session->user_agent);

    $sessions_list[] = [
        'id' => $session->id,
        'is_current' => in_array($session->hash, [
            Session::get(Config::get('session.session_name')), Session::get(Config::get('session.admin_name'))
        ]),
        'is_admin' => $session->login_method === 'admin',
        'is_remembered' => $session->remember_me,
        'last_active' => $language->get('user', 'last_active_x', ['lastActive' => $timeAgo->inWords($session->last_seen, $language)]),
        'last_seen_timeago' => $timeAgo->inWords($session->last_seen, $language),
        'last_seen' => date(DATE_FORMAT, $session->last_seen),
        'device_type' => Output::getClean($agent->deviceType()),
        'device_os' => Output::getClean($agent->platform()),
        'device_browser' => Output::getClean($agent->browser()),
        'device_browser_version' => Output::getClean($agent->version($agent->browser())),
        'location' => Output::getClean($session->ip . ' (' . HttpUtils::getIpCountry($session->ip) . ')'),
    ];
}

$can_logout_all = false;
if (count($sessions) > 1) {
    $can_logout_all = true;
}
if (count($sessions) === 2) {
    if ($sessions[0]->login_method === 'admin' || $sessions[1]->login_method === 'admin') {
        $can_logout_all = false;
    }
}

$template->getEngine()->addVariables([
    'TOKEN' => Token::get(),
    'NO' => $language->get('general', 'no'),
    'YES' => $language->get('general', 'yes'),
    'USER_CP' => $language->get('user', 'user_cp'),
    'SESSIONS' => $language->get('general', 'sessions'),
    'SESSIONS_LIST' => $sessions_list,
    'CAN_LOGOUT_ALL' => $can_logout_all,
    'LOGOUT' => $language->get('general', 'log_out'),
    'LOGOUT_OTHER_SESSIONS' => $language->get('user', 'logout_other_sessions'),
    'LOGOUT_ALL_CONFIRM' => $language->get('general', 'log_out_all_sessions_confirm'),
    'LOGOUT_CONFIRM' => $language->get('general', 'log_out_selected_session_confirm'),
    'ADMIN_LOGGED_IN' => $language->get('admin', 'admin_logged_in'),
    'REMEMBERED' => $language->get('user', 'remembered'),
    'THIS_DEVICE' => $language->get('user', 'this_device'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require ROOT_PATH . '/core/templates/cc_navbar.php';

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('user/sessions.tpl');
