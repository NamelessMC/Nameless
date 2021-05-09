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

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'ip_lookup');
$page_title = $language->get('moderator', 'ip_lookup');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($_GET['uid'])) {
    $user_id = intval($_GET['uid']);

    $user_query = $queries->getWhere('users', array('id', '=', $user_id));
    if (!count($user_query)) {
        Redirect::to(URL::build('/panel/users'));
        die();
    }
    $user_query = $user_query[0];

    // Search by user ID
    $user_ips = $queries->getWhere('users_ips', array('user_id', '=', $user_id));

    if (count($user_ips)) {
        $accounts = array();

        foreach ($user_ips as $account) {
            $accounts[] = array(
                'ip' => Output::getClean($account->ip),
                'link' => URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($account->ip))
            );
        }

        if (count($user_ips) == 1)
            $count_accounts = str_replace('{y}', Output::getClean($user_query->username), $language->get('moderator', '1_ip_with_name'));
        else
            $count_accounts = str_replace(array('{x}', '{y}'), array(count($user_ips), Output::getClean($user_query->username)), $language->get('moderator', 'count_ips_with_name'));

        $smarty->assign(array(
            'ACCOUNTS' => $accounts,
            'COUNT_ACCOUNTS' => $count_accounts,
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/users/ip_lookup')
        ));

        $template_file = 'core/users_ip_lookup_results.tpl';
    } else {
        $errors = array($language->get('moderator', 'no_ips_with_username'));

        $template_file = 'core/users_ip_lookup.tpl';
    }
} else if (isset($_GET['ip'])) {
    // IP has been specified
    // Get accounts with this IP
    $ip_accounts = $queries->getWhere('users_ips', array('ip', '=', Output::getClean($_GET['ip'])));

    if (!count($ip_accounts)) {
        $errors = array($language->get('moderator', 'no_accounts_with_that_ip'));

        $template_file = 'core/users_ip_lookup.tpl';
    } else {
        $accounts = array();

        foreach ($ip_accounts as $account) {
            $username = $queries->getWhere('users', array('id', '=', $account->user_id));

            if (count($username))
                $accounts[] = array(
                    'username' => Output::getClean($username[0]->username),
                    'nickname' => Output::getClean($username[0]->nickname),
                    'profile' => URL::build('/panel/user/' . Output::getClean($username[0]->id . '-' . $username[0]->username)),
                    'account_ips' => URL::build('/panel/users/ip_lookup/', 'uid=' . $account->user_id),
                    'style' => $user->getGroupClass($username[0]->id)
                );
        }

        if (count($ip_accounts) == 1)
            $count_accounts = str_replace('{y}', Output::getClean($_GET['ip']), $language->get('moderator', '1_account_with_ip'));
        else
            $count_accounts = str_replace(array('{x}', '{y}'), array(count($ip_accounts), Output::getClean($_GET['ip'])), $language->get('moderator', 'count_accounts_with_ip'));

        $smarty->assign(array(
            'IP_SEARCH' => true,
            'ACCOUNTS' => $accounts,
            'COUNT_ACCOUNTS' => $count_accounts,
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/users/ip_lookup')
        ));

        $template_file = 'core/users_ip_lookup_results.tpl';
    }
} else {
    if (Input::exists()) {
        // Check token
        if (Token::check()) {
            // Search
            $query = $queries->getWhere('users', array('username', '=', Output::getClean(Input::get('search'))));

            if (!count($query)) {
                // Try nickname
                $query = $queries->getWhere('users', array('nickname', '=', Output::getClean(Input::get('search'))));
            }

            if (count($query)) {
                Redirect::to(URL::build('/panel/users/ip_lookup/', 'uid=' . Output::getClean($query[0]->id)));
                die();
            }

            // Try searching IPs
            $query = $queries->getWhere('users_ips', array('ip', '=', Output::getClean(Input::get('search'))));

            if (count($query)) {
                Redirect::to(URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean(Input::get('search'))));
                die();
            }

            $errors = array($language->get('moderator', 'no_users_or_ips_found'));
        } else {
            $errors = array($language->get('general', 'invalid_token'));
        }
    }

    $template_file = 'core/users_ip_lookup.tpl';
}

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'SEARCH_FOR_IP_OR_USER' => $language->get('moderator', 'search_for_ip'),
    'IP_LOOKUP' => $language->get('moderator', 'ip_lookup'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
