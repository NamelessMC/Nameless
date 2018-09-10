<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Moderator IP Lookup page
 */

// Can the user view the ModCP?
if($user->isLoggedIn()){
	if(!$user->canViewMCP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else if(!$user->hasPermission('modcp.ip_lookup')){
        // Can't view this page
        require(ROOT_PATH . '/404.php');
        die();
    }
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
define('PAGE', 'mod_ip_lookup');
$page_title = $language->get('moderator', 'mod_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require(ROOT_PATH . '/core/templates/mod_navbar.php');

// Generate content to pass to template
if(!isset($_GET['ip'])){
	// No IP specified yet
	if(isset($_GET['user']) && is_numeric($_GET['user'])){
		// Search by user ID
		$user_ips = $queries->getWhere('users_ips', array('user_id', '=', $_GET['user']));

		if(count($user_ips)){
			$accounts = array();

			foreach($user_ips as $account){
				$accounts[] = array(
					'ip' => Output::getClean($account->ip),
					'link' => URL::build('/mod/ip_lookup/', 'ip=' . Output::getClean($account->ip))
				);
			}

			if(count($user_ips) == 1)
				$count_accounts = str_replace('{y}', Output::getClean($user->idToName($_GET['user'])), $language->get('moderator', '1_ip_with_name'));
			else
				$count_accounts = str_replace(array('{x}', '{y}'), array(count($user_ips), Output::getClean($user->idToName($_GET['user']))), $language->get('moderator', 'count_ips_with_name'));

			$smarty->assign(array(
				'ACCOUNTS' => $accounts,
				'COUNT_ACCOUNTS' => $count_accounts
			));
		} else {
			$smarty->assign('NO_ACCOUNTS', $language->get('moderator', 'no_ips_with_username'));
		}

	} else {
		// Search box
		if(Input::exists()){
			// Check token
			if(Token::check(Input::get('token'))){
				// Search
				$query = $queries->getWhere('users', array('username', '=', Output::getClean(Input::get('search'))));

				Log::getInstance()->log(Log::Action('mod/iplookup'), Output::getClean(Input::get('search')));

				if(!count($query)){
					// Try nickname
					$query = $queries->getWhere('users', array('nickname', '=', Output::getClean(Input::get('search'))));
				}

				if(count($query)){
					Redirect::to(URL::build('/mod/ip_lookup/', 'user=' . $query[0]->id));
					die();
				}

				// Try searching IPs
				$query = $queries->getWhere('users_ips', array('ip', '=', Output::getClean(Input::get('search'))));

				if(count($query)){
					Redirect::to(URL::build('/mod/ip_lookup/', 'ip=' . Output::getClean(Input::get('search'))));
					die();
				}

				$smarty->assign('ERROR', $language->get('moderator', 'no_users_or_ips_found'));
			} else {
				$smarty->assign('ERROR', $language->get('general', 'invalid_token'));
			}
		}
		$smarty->assign('SEARCH', $language->get('moderator', 'search_for_ip'));
	}

} else {
	// IP has been specified
	// Get accounts with this IP
	$ip_accounts = $queries->getWhere('users_ips', array('ip', '=', Output::getClean($_GET['ip'])));

	if(!count($ip_accounts)){
		$smarty->assign(array(
			'NO_ACCOUNTS' => $language->get('moderator', 'no_accounts_with_that_ip')
		));
	} else {
		$accounts = array();

		foreach($ip_accounts as $account){
			$username = $queries->getWhere('users', array('id', '=', $account->user_id));

			if(count($username))
			$accounts[] = array(
				'username' => Output::getClean($username[0]->username),
				'nickname' => Output::getClean($username[0]->nickname),
				'profile' => URL::build('/profile/' . Output::getClean($username[0]->username)),
				'account_ips' => URL::build('/mod/ip_lookup/', 'user='. $account->user_id)
			);
		}

		if(count($ip_accounts) == 1)
			$count_accounts = str_replace('{y}', Output::getClean($_GET['ip']), $language->get('moderator', '1_account_with_ip'));
		else
			$count_accounts = str_replace(array('{x}', '{y}'), array(count($ip_accounts), Output::getClean($_GET['ip'])), $language->get('moderator', 'count_accounts_with_ip'));

		$smarty->assign(array(
			'IP_SEARCH' => true,
			'ACCOUNTS' => $accounts,
			'COUNT_ACCOUNTS' => $count_accounts
		));
	}

}

$smarty->assign(array(
	'MOD_CP' => $language->get('moderator', 'mod_cp'),
	'IP_LOOKUP' => $language->get('moderator', 'ip_lookup'),
	'SUBMIT' => $language->get('general', 'submit'),
	'TOKEN' => Token::get()
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('mod/ip_lookup.tpl', $smarty);
