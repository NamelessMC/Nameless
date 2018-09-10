<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Navbar generation
 */

// User area
$user_area = array();
$user_area_left = array();

if($user->isLoggedIn()){
	$user_area['usercp'] = array(
		'target' => '',
		'link' => URL::build('/user'),
		'title' => $language->get('user', 'user_cp_icon')
	);
	if(defined('PAGE') && PAGE == 'usercp'){
		$user_area['usercp']['active'] = true;
	}
	
	$user_area_left['account'] = array(
		'target' => '',
		'link' => '',
		'title' => Output::getClean($user->data()->nickname),
		'items' => array(
			'profile' => array(
				'link' => URL::build('/profile/' . Output::getClean($user->data()->username)),
				'target' => '',
				'title' => $language->get('user', 'profile')
			),
			'separator1' => array(
				'separator' => true
			),
			'user' => array(
				'link' => URL::build('/user'),
				'target' => '',
				'title' => $language->get('user', 'user_cp')
			)
		)
	);
	
	if($user->canViewMCP()){
		$user_area_left['account']['items']['mod'] = array(
			'link' => URL::build('/mod'),
			'target' => '',
			'title' => $language->get('moderator', 'mod_cp')
		);
	}
	
	if($user->canViewACP()){
		$user_area_left['account']['items']['admin'] = array(
			'link' => URL::build('/admin'),
			'target' => '',
			'title' => $language->get('admin', 'admin_cp')
		);
	}
	
	$user_area_left['account']['items']['separator2'] = array(
		'separator' => true
	);
	
	$user_area_left['account']['items']['logout'] = array(
		'link' => URL::build('/logout'),
		'target' => '',
		'title' => $language->get('general', 'log_out')
	);
} else {
	$user_area_left['account'] = array(
		'target' => '',
		'link' => '',
		'title' => $language->get('user', 'guest'),
		'items' => array(
			'login' => array(
				'link' => URL::build('/login'),
				'target' => '',
				'title' => $language->get('general', 'sign_in')
			),
			'register' => array(
				'link' => URL::build('/register'),
				'target' => '',
				'title' => $language->get('general', 'register')
			)
		)
	);
}

// Assign to Smarty variables
$smarty->assign(array(
	'NAVBAR_INVERSE' => '', 
	'SITE_NAME' => SITE_NAME,
	'NAV_LINKS' => $navigation->returnNav('top'),
	'USER_AREA' => $user_area,
	'USER_DROPDOWN' => $user_area_left,
	'GLOBAL_MESSAGES' => ''
));

if($user->isLoggedIn()){
	// Get unread alerts and messages
	$smarty->assign(array(
		'ALERTS_LINK' => URL::build('/user/alerts'),
		'VIEW_ALERTS' => $language->get('user', 'view_alerts'),
		'MESSAGING_LINK' => URL::build('/user/messaging'),
		'VIEW_MESSAGES' => $language->get('user', 'view_messages'),
		'LOADING' => $language->get('general', 'loading'),
		'MESSAGING' => $language->get('user', 'messaging'),
		'ALERTS' => $language->get('user', 'alerts')
	));
}
