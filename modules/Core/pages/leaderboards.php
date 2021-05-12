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
$leaderboard_users = [];

require_once(ROOT_PATH . '/core/classes/Timeago.php');
$timeago = new TimeAgo(TIMEZONE);

foreach ($leaderboard_placeholders as $leaderboard_placeholder) {
    $data = Placeholders::getInstance()->getLeaderboardData($leaderboard_placeholder->name);

    if ($data == null || !count($data)) {
        continue;
    }

    $data = $data[0];

    if (!array_key_exists($data->uuid, $leaderboard_users)) {
        $user_data = DB::getInstance()->get('users', ['uuid', '=', $data->uuid])->results()[0];
        $leaderboard_users[$data->uuid] = $user_data; 
    }

    $data->username = Output::getClean($leaderboard_users[$data->uuid]->username);
    $data->avatar = Util::getAvatarFromUUID($data->uuid, 24);
    $data->last_updated = ucfirst($timeago->inWords(date('d M Y, H:i', $data->last_updated), $language->getTimeLanguage()));

    $leaderboard_placeholders_data[$leaderboard_placeholder->name] = $data;
}

$smarty->assign(array(
    'LEADERBOARDS' => $language->get('general', 'leaderboards'),
    'LEADERBOARD_PLACEHOLDERS' => $leaderboard_placeholders,
    'LEADERBOARD_PLACEHOLDERS_DATA' => $leaderboard_placeholders_data
));

$template->addJSScript('
    window.onLoad = showTable(null, true);

    function showTable(name, first = false) {

        if (name == null) {
            name = $(".leaderboard_tab").first().attr("name");
        }

        if (!first) {
            disableTabs();
            hideTables();
        }

        $("#tab-" + name).addClass("active");
        $("#table-" + name).show();
    }

    function disableTabs() {
        $(".leaderboard_tab").each(function(i, e) {
            $(e).removeClass("active");
        });
    }

    function hideTables() {
        $(".leaderboard_table").each(function(i, e) {
            $(e).hide();
        });
    }
');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('leaderboards.tpl', $smarty);
