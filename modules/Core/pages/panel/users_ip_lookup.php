<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel IP lookup page
 */

if (!$user->handlePanelPageLoad('modcp.ip_lookup')) {
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
    $user_id = (int)$_GET['uid'];

    $user_query = DB::getInstance()->get('users', ['id', $user_id])->results();
    if (!count($user_query)) {
        Redirect::to(URL::build('/panel/users'));
    }
    $user_query = $user_query[0];

    // Search by user ID
    $user_ips = DB::getInstance()->get('users_ips', ['user_id', $user_id])->results();

    if (count($user_ips)) {
        $accounts = [];

        foreach ($user_ips as $account) {
            $accounts[] = [
                'ip' => Output::getClean($account->ip),
                'link' => URL::build('/panel/users/ip_lookup/', 'ip=' . urlencode($account->ip))
            ];
        }

        if (count($user_ips) == 1) {
            $count_accounts = $language->get('moderator', '1_ip_with_name', [
                'user' => Text::bold(Output::getClean($user_query->username))
            ]);
        } else {
            $count_accounts = $language->get('moderator', 'count_ips_with_name', [
                'count' => count($user_ips),
                'user' => Text::bold(Output::getClean($user_query->username))
            ]);
        }

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
} else {
    if (isset($_GET['ip'])) {
        // IP has been specified
        // Get accounts with this IP
        $ip_accounts = DB::getInstance()->get('users_ips', ['ip', Output::getClean($_GET['ip'])])->results();

        if (!count($ip_accounts)) {
            $errors = [$language->get('moderator', 'no_accounts_with_that_ip')];

            $template_file = 'core/users_ip_lookup.tpl';
        } else {
            $accounts = [];

            foreach ($ip_accounts as $account) {
                $username = DB::getInstance()->get('users', ['id', $account->user_id])->results();

                if (count($username)) {
                    $accounts[] = [
                        'username' => Output::getClean($username[0]->username),
                        'nickname' => Output::getClean($username[0]->nickname),
                        'profile' => URL::build('/panel/user/' . urlencode($username[0]->id . '-' . $username[0]->username)),
                        'account_ips' => URL::build('/panel/users/ip_lookup/', 'uid=' . urlencode($account->user_id)),
                        'style' => $user->getGroupStyle()
                    ];
                }
            }

            if (count($ip_accounts) == 1) {
                $count_accounts = $language->get('moderator', '1_account_with_ip', ['address' => Output::getClean($_GET['ip'])]);
            } else {
                $count_accounts = $language->get('moderator', 'count_accounts_with_ip', ['count' => count($ip_accounts), 'address' => Output::getClean($_GET['ip'])]);
            }

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
                $query = DB::getInstance()->get('users', ['username', Output::getClean(Input::get('search'))])->results();

                if (!count($query)) {
                    // Try nickname
                    $query = DB::getInstance()->get('users', ['nickname', Output::getClean(Input::get('search'))])->results();
                }

                if (count($query)) {
                    Redirect::to(URL::build('/panel/users/ip_lookup/', 'uid=' . urlencode($query[0]->id)));
                }

                // Try searching IPs
                $query = DB::getInstance()->get('users_ips', ['ip', Output::getClean(Input::get('search'))])->results();

                if (count($query)) {
                    Redirect::to(URL::build('/panel/users/ip_lookup/', 'ip=' . urlencode(Input::get('search'))));
                }

                $errors = [$language->get('moderator', 'no_users_or_ips_found')];
            } else {
                $errors = [$language->get('general', 'invalid_token')];
            }
        }

        $template_file = 'core/users_ip_lookup.tpl';
    }
}

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

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
