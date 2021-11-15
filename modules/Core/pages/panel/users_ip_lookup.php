<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel IP lookup page
 */

if(!$user->handlePanelPageLoad('modcp.ip_lookup')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'ip_lookup';
$page_title = $language->get('moderator', 'ip_lookup');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['uid'])) {
    $user_id = intval($_GET['uid']);

    $user_query = $queries->getWhere('users', ['id', '=', $user_id]);
    if (!count($user_query)) {
        Redirect::to(URL::build('/panel/users'));
        die();
    }
    $user_query = $user_query[0];

    // Search by user ID
    $user_ips = $queries->getWhere('users_ips', ['user_id', '=', $user_id]);

    if (count($user_ips)) {
        $accounts = [];

        foreach ($user_ips as $account) {
            $accounts[] = [
                'ip' => Output::getClean($account->ip),
                'link' => URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($account->ip))
            ];
        }

        if (count($user_ips) == 1)
            $count_accounts = str_replace('{y}', Output::getClean($user_query->username), $language->get('moderator', '1_ip_with_name'));
        else
            $count_accounts = str_replace(['{x}', '{y}'], [count($user_ips), Output::getClean($user_query->username)], $language->get('moderator', 'count_ips_with_name'));

        $smarty->assign([
            'ACCOUNTS' => $accounts,
            'COUNT_ACCOUNTS' => $count_accounts,
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/users/ip_lookup')
        ]);

        $template_file = 'core/users_ip_lookup_results.tpl';
    } else {
        $errors = [$language->get('moderator', 'no_ips_with_username')];

        $template_file = 'core/users_ip_lookup.tpl';
    }
} else if (isset($_GET['ip'])) {
    // IP has been specified
    // Get accounts with this IP
    $ip_accounts = $queries->getWhere('users_ips', ['ip', '=', Output::getClean($_GET['ip'])]);

    if (!count($ip_accounts)) {
        $errors = [$language->get('moderator', 'no_accounts_with_that_ip')];

        $template_file = 'core/users_ip_lookup.tpl';
    } else {
        $accounts = [];

        foreach ($ip_accounts as $account) {
            $username = $queries->getWhere('users', ['id', '=', $account->user_id]);

            if (count($username))
                $accounts[] = [
                    'username' => Output::getClean($username[0]->username),
                    'nickname' => Output::getClean($username[0]->nickname),
                    'profile' => URL::build('/panel/user/' . Output::getClean($username[0]->id . '-' . $username[0]->username)),
                    'account_ips' => URL::build('/panel/users/ip_lookup/', 'uid=' . $account->user_id),
                    'style' => $user->getGroupClass()
                ];
        }

        if (count($ip_accounts) == 1)
            $count_accounts = str_replace('{y}', Output::getClean($_GET['ip']), $language->get('moderator', '1_account_with_ip'));
        else
            $count_accounts = str_replace(['{x}', '{y}'], [count($ip_accounts), Output::getClean($_GET['ip'])], $language->get('moderator', 'count_accounts_with_ip'));

        $smarty->assign([
            'IP_SEARCH' => true,
            'ACCOUNTS' => $accounts,
            'COUNT_ACCOUNTS' => $count_accounts,
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/users/ip_lookup')
        ]);

        $template_file = 'core/users_ip_lookup_results.tpl';
    }
} else {
    if (Input::exists()) {
        // Check token
        if (Token::check()) {
            // Search
            $query = $queries->getWhere('users', ['username', '=', Output::getClean(Input::get('search'))]);

            if (!count($query)) {
                // Try nickname
                $query = $queries->getWhere('users', ['nickname', '=', Output::getClean(Input::get('search'))]);
            }

            if (count($query)) {
                Redirect::to(URL::build('/panel/users/ip_lookup/', 'uid=' . Output::getClean($query[0]->id)));
                die();
            }

            // Try searching IPs
            $query = $queries->getWhere('users_ips', ['ip', '=', Output::getClean(Input::get('search'))]);

            if (count($query)) {
                Redirect::to(URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean(Input::get('search'))));
                die();
            }

            $errors = [$language->get('moderator', 'no_users_or_ips_found')];
        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }
    }

    $template_file = 'core/users_ip_lookup.tpl';
}

if (isset($success))
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);

if (isset($errors) && count($errors))
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'SEARCH_FOR_IP_OR_USER' => $language->get('moderator', 'search_for_ip'),
    'IP_LOOKUP' => $language->get('moderator', 'ip_lookup'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
