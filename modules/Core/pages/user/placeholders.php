<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  User placeholders page
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_placeholders');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

$placeholders_list = array();

foreach ($user->getPlaceholders() as $placeholder) {
    $placeholders_list[] = [
        'server_id' => $placeholder->server_id,
        'name' => $placeholder->name,
        'value' => $placeholder->value,
        'last_updated' => ucfirst($timeago->inWords(date('d M Y, H:i', $placeholder->last_updated), $language->getTimeLanguage()))
    ];
}

$smarty->assign(array(
    'USER_CP' => $language->get('user', 'user_cp'),
    'NO_PLACEHOLDERS' => $language->get('user', 'no_placeholders'),
    'PLACEHOLDERS' => $language->get('user', 'placeholders'),
    'PLACEHOLDERS_LIST' => $placeholders_list,
    'OPTIONS' => $language->get('admin', 'email_message_options')
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
$template->displayTemplate('user/placeholders.tpl', $smarty);
