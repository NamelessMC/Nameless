<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Status page
 */

$cache->setCache('status_page');
if ($cache->isCached('enabled')) {
    $status_enabled = $cache->retrieve('enabled');

} else {
    $status_enabled = $queries->getWhere('settings', array('name', '=', 'status_page'));
    $status_enabled = $status_enabled[0]->value == 1 ? 1 : 0;
    $cache->store('enabled', $status_enabled);
}

if (!defined('MINECRAFT') || MINECRAFT !== true || $status_enabled != 1) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'status');
$page_title = $language->get('general', 'status');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$servers = $queries->orderWhere('mc_servers', 'display = 1', '`order`', 'ASC');

$smarty->assign(
    array(
        'STATUS' => $language->get('general', 'status'),
        'IP' => $language->get('general', 'ip'),
        'TABLE_STATUS' => $language->get('general', 'table_status'),
        'DEFAULT_STATUS' => (isset($result) ? $result : null),
        'SERVERS' => $servers,
        'NO_SERVERS' => $language->get('general', 'no_servers'),
        'BUNGEE' => $language->get('general', 'bungee_instance')
    )
);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('status.tpl', $smarty);
