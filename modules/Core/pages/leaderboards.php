<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Leaderboards page
 */

// MC integration and Placeholders enabled?
if (!Util::getSetting('mc_integration') || Util::getSetting('placeholders') !== '1') {
    require_once(ROOT_PATH . '/404.php');
    die();
}

$leaderboard_placeholders = Placeholders::getInstance()->getLeaderboardPlaceholders();

if (!count($leaderboard_placeholders)) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'leaderboards';
$page_title = $language->get('general', 'leaderboards');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$leaderboard_placeholders_data = [];
$leaderboard_users = [];

$timeago = new TimeAgo(TIMEZONE);

foreach ($leaderboard_placeholders as $leaderboard_placeholder) {
    // Get all rows from user placeholder table with this placeholders server id + name
    $data = Placeholders::getInstance()->getLeaderboardData($leaderboard_placeholder->server_id, $leaderboard_placeholder->name);

    if (!count($data)) {
        continue;
    }

    // TODO: move this to placeholders class
    $integration = Integrations::getInstance()->getIntegration('Minecraft');
    foreach ($data as $row) {
        $row_data = new stdClass();

        $uuid = bin2hex($row->uuid);
        if (!array_key_exists($uuid, $leaderboard_users)) {
            $integration_user = new IntegrationUser($integration, $uuid, 'identifier');
            if (!$integration_user->exists()) {
                continue;
            }
            $leaderboard_users[$uuid] = $integration_user;
        }

        $row_data->server_id = $leaderboard_placeholder->server_id;
        $row_data->name = $leaderboard_placeholder->name;
        $row_data->username = Output::getClean($leaderboard_users[$uuid]->data()->username);
        $row_data->avatar = AvatarSource::getAvatarFromUUID($uuid, 24);
        $row_data->value = $row->value;
        $row_data->last_updated = ucfirst($timeago->inWords($row->last_updated, $language));

        $leaderboard_placeholders_data[] = $row_data;
    }
}

$smarty->assign([
    'PLAYER' => $language->get('admin', 'placeholders_player'),
    'SCORE' => $language->get('admin', 'placeholders_score'),
    'LAST_UPDATED' => $language->get('admin', 'placeholders_last_updated'),
    'LEADERBOARDS' => $language->get('general', 'leaderboards'),
    'LEADERBOARD_PLACEHOLDERS' => $leaderboard_placeholders,
    'LEADERBOARD_PLACEHOLDERS_DATA' => $leaderboard_placeholders_data
]);

$template->addJSScript('
    window.onLoad = showTable(null, null, true);

    function showTable(name, server_id, first = false) {

        if (name === null) {
            name = $(".leaderboard_tab").first().attr("name");
            server_id = $(".leaderboard_tab").first().attr("server_id");
        }

        if (!first) {
            disableTabs();
            hideTables();
        }

        $("#tab-" + name + "-server-" + server_id).addClass("active");
        $("#table-" + name + "-server-" + server_id).show();
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
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('leaderboards.tpl', $smarty);
