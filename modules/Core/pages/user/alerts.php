<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  User alerts page
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_alerts');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new Timeago(TIMEZONE);

if(!isset($_GET['view'])){
	if(!isset($_GET['action'])){
		// Get alerts
		$alerts = $queries->orderWhere('alerts', 'user_id = ' . $user->data()->id, 'created', 'DESC');

		$alerts_limited = array();
		$n = 0;

		if(count($alerts) > 30) $limit = 30;
		else $limit = count($alerts);

		while($n < $limit){
			// Only display 30 alerts
			// Get date
			$alerts[$n]->date = date('d M Y, H:i', $alerts[$n]->created);
			$alerts[$n]->date_nice = $timeago->inWords(date('d M Y, H:i', $alerts[$n]->created), $language->getTimeLanguage());
			$alerts[$n]->view_link = URL::build('/user/alerts/', 'view=' . $alerts[$n]->id);

			$alerts_limited[] = $alerts[$n];

			$n++;
		}

		// Language values
		$smarty->assign(array(
			'USER_CP' => $language->get('user', 'user_cp'),
			'ALERTS' => $language->get('user', 'alerts'),
			'ALERTS_LIST' => $alerts_limited,
			'DELETE_ALL' => $language->get('user', 'delete_all'),
			'DELETE_ALL_LINK' => URL::build('/user/alerts/', 'action=purge'),
			'CLICK_TO_VIEW' => $language->get('user', 'click_here_to_view'),
			'NO_ALERTS' => $language->get('user', 'no_alerts_usercp')
		));

		// Load modules + template
		Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

		require(ROOT_PATH . '/core/templates/cc_navbar.php');

		$page_load = microtime(true) - $start;
		define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

		$template->onPageLoad();

		require(ROOT_PATH . '/core/templates/navbar.php');
		require(ROOT_PATH . '/core/templates/footer.php');

		// Display template
		$template->displayTemplate('user/alerts.tpl', $smarty);

	} else {
		if($_GET['action'] == 'purge'){
			$queries->delete('alerts', array('user_id', '=', $user->data()->id));
			Redirect::to(URL::build('/user/alerts'));
			die();
		}
	}

} else {
	// Redirect to alert, mark as read
	if(!is_numeric($_GET['view'])) Redirect::to(URL::build('/user/alerts'));

	// Check the alert belongs to the user..
	$alert = $queries->getWhere('alerts', array('id', '=', $_GET['view']));

	if(!count($alert) || $alert[0]->user_id != $user->data()->id) Redirect::to(URL::build('/user/alerts'));

	if($alert[0]->read == 0){
		$queries->update('alerts', $alert[0]->id, array(
			'`read`' => 1
		));
	}

	Redirect::to($alert[0]->url);
	die();
}