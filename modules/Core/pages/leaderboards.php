<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Leaderboards page
 */

$leaderboard_placeholders = Placeholders::getInstance()->getLeaderboardPlaceholders();

if (!count($leaderboard_placeholders)) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'leaderboards');
$page_title = $language->get('general', 'leaderboards');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$leaderboard_placeholders_data = [];

foreach ($leaderboard_placeholders as $leaderboard_placeholder) {
    $data = Placeholders::getInstance()->getLeaderboardData($leaderboard_placeholder->name);

    if ($data == null || !count($data)) {
        continue;
    }

    $leaderboard_placeholders_data[$leaderboard_placeholder->name] = $data[0];
}

$smarty->assign(array(
    'LEADERBOARDS' => $language->get('general', 'leaderboards'),
    'LEADERBOARD_PLACEHOLDERS' => $leaderboard_placeholders,
    'LEADERBOARD_PLACEHOLDERS_DATA' => $leaderboard_placeholders_data
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('leaderboards.tpl', $smarty);
