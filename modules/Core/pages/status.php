<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Status page
 */

if (!Util::getSetting('mc_integration') || !Util::getSetting('status_page')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'status';
$page_title = $language->get('general', 'status');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$servers = DB::getInstance()->orderWhere('mc_servers', 'display = 1', '`order`', 'ASC')->results();

$smarty->assign(
    [
        'STATUS' => $language->get('general', 'status'),
        'IP' => $language->get('general', 'ip'),
        'TABLE_STATUS' => $language->get('general', 'table_status'),
        'DEFAULT_STATUS' => ($result ?? null),
        'SERVERS' => $servers,
        'NO_SERVERS' => $language->get('general', 'no_servers'),
        'BUNGEE' => $language->get('general', 'bungee_instance'),
        'ERROR_TITLE' => $language->get('general', 'error')
    ]
);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('status.tpl', $smarty);
