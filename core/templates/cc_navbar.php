<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  UserCP navbar generation
 */

$cc_nav->add('cc_overview', $language->get('user', 'overview'), URL::build('/user'));
$cc_nav->add('cc_alerts', $language->get('user', 'alerts'), URL::build('/user/alerts'));
$cc_nav->add('cc_messaging', $language->get('user', 'messaging'), URL::build('/user/messaging'));
$cc_nav->add('cc_settings', $language->get('user', 'profile_settings'), URL::build('/user/settings'));

$smarty->assign(array(
	'CC_NAV_LINKS' => $cc_nav->returnNav('top')
));