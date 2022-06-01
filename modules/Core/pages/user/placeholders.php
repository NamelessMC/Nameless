<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  User placeholders page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Placeholders enabled?
if (Util::getSetting('placeholders') !== '1') {
    require_once(ROOT_PATH . '/404.php');
    die();
}

// Always define page name for navbar
const PAGE = 'cc_placeholders';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

$placeholders_list = [];

foreach ($user->getPlaceholders() as $placeholder) {
    $placeholders_list[] = [
        'name' => $placeholder->name,
        'friendly_name' => $placeholder->friendly_name,
        'value' => $placeholder->value,
        'last_updated' => ucfirst($timeago->inWords($placeholder->last_updated, $language)),
        'show_on_profile' => $placeholder->show_on_profile,
        'show_on_forum' => $placeholder->show_on_forum
    ];
}

$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'NO_PLACEHOLDERS' => $language->get('user', 'no_placeholders'),
    'PLACEHOLDERS' => $language->get('user', 'placeholders'),
    'PLACEHOLDERS_LIST' => $placeholders_list,
    'SERVER_ID' => $language->get('admin', 'placeholders_server_id'),
    'NAME' => $language->get('admin', 'placeholders_name'),
    'VALUE' => $language->get('admin', 'placeholders_value'),
    'LAST_UPDATED' => $language->get('admin', 'placeholders_last_updated'),
    'SHOW_ON_PROFILE' => $language->get('admin', 'placeholders_show_on_profile'),
    'SHOW_ON_FORUM' => $language->get('admin', 'placeholders_show_on_forum')
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/placeholders.tpl', $smarty);
