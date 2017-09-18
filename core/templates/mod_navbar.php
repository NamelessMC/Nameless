<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  ModCP navbar generation
 */

$mod_nav->add('mod_overview', $language->get('moderator', 'overview'), URL::build('/mod'));
$mod_nav->add('mod_ip_lookup', $language->get('moderator', 'ip_lookup'), URL::build('/mod/ip_lookup'));
$mod_nav->add('mod_punishments', $language->get('moderator', 'punishments'), URL::build('/mod/punishments'));
$mod_nav->add('mod_reports', $language->get('moderator', 'reports'), URL::build('/mod/reports'));

$smarty->assign(array(
	'MOD_LINKS' => $mod_nav->returnNav('top')
));