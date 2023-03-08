<?php

/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  User sessions page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_sessions';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

if (Input::exists()) {
    if (Token::check()) {
        if (Input::get('action') === 'logout_other_sessions') {
            $user->logoutAllOtherSessions();
            Session::flash('user_sessions_success', $language->get('user', 'sessions_logged_out'));
        }
    } else {
        // Invalid token
        Session::flash('user_sessions_error', $language->get('general', 'invalid_token'));
    }
}

if (Session::exists('user_sessions_success')) {
    $smarty->assign([
        'SUCCESS' => $language->get('general', 'success'),
        'SUCCESS_MESSAGE' => Session::flash('user_sessions_success'),
    ]);
}

if (Session::exists('user_sessions_error')) {
    $smarty->assign([
        'ERROR' => $language->get('general', 'error'),
        'ERROR_MESSAGE' => Session::flash('user_sessions_error'),
    ]);
}

$sessions = $user->getActiveSessions();
$sessions_list = [];
// TODO: Should we display all sessions, or just active ones? Over time, the list could get very long if we display all sessions.
// Not really any reason to show inactive ones, since they can't action on them.
foreach ($sessions as $session) {
    if ($session->login_method === 'admin') {
        continue;
    }

    $dd = new \DeviceDetector\DeviceDetector($session->user_agent, \DeviceDetector\ClientHints::factory($_SESSION));
    $dd->skipBotDetection();
    $dd->parse();
    $device_type = $dd->getDeviceName() ?: 'desktop';

    $sessions_list[] = [
        'id' => $session->id,
        'is_current' => $session->hash === Session::get(Config::get('session.session_name')),
        'device_type' => $device_type,
        'last_seen_timeago' => $timeago->inWords($session->last_seen, $language),
        'last_seen' => date(DATE_FORMAT, $session->last_seen),
        'device_description' => $dd->getOs('name') . ' - ' . $dd->getClient('name'),
        'device_os' => $dd->getOs('name'),
        'device_browser' => $dd->getClient('name'),
        'location' => $session->ip . ' (' . HttpUtils::getIpCountry($session->ip) . ')',
    ];
}

$smarty->assign([
    'TOKEN' => Token::get(),
    'NO' => $language->get('general', 'no'),
    'YES' => $language->get('general', 'yes'),
    'USER_CP' => $language->get('user', 'user_cp'),
    'SESSIONS' => $language->get('general', 'sessions'),
    'SESSIONS_LIST' => $sessions_list,
    'LOGOUT' => $language->get('general', 'log_out'),
    'LOGOUT_OTHER_SESSIONS' => $language->get('user', 'logout_other_sessions'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/sessions.tpl', $smarty);
