<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel index page
 */

if (!$user->handlePanelPageLoad()) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PANEL_PAGE = 'dashboard';
$page_title = $language->get('admin', 'dashboard');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$dashboard_graphs = Core_Module::getDashboardGraphs();
$graphs = [];

if (count($dashboard_graphs)) {
    $i = 0;
    foreach ($dashboard_graphs as $key => $dashboard_graph) {
        $graph = [
            'id' => $i++,
            'title' => $key,
            'datasets' => [],
            'axes' => [],
            'keys' => []
        ];

        foreach ($dashboard_graph['datasets'] as $dskey => $dataset) {
            $label = explode('/', $dataset['label']);
            $varname = $label[0];
            $axis = 'y' . ($dataset['axis'] ?? 1);
            $axis_side = ($dataset['axis_side'] ?? 'left');

            $graph['datasets'][$dskey] = [
                'label' => ${$varname}->get($label[1], $label[2]),
                'axis' => $axis,
                'colour' => ($dataset['colour'] ?? '#000')
            ];

            $graph['axes'][$axis] = $axis_side;
        }

        unset($dashboard_graph['datasets']);

        foreach ($dashboard_graph as $date => $values) {
            $date = ltrim($date, '_');

            if (!array_key_exists($date, $graph['keys'])) {
                $graph['keys'][$date] = $date;
            }

            foreach ($values as $valuekey => $value) {
                $graph['datasets'][$valuekey]['data'][$date] = $value;
            }
        }

        $graphs[$key] = $graph;
    }
}

$dashboard_graphs = null;

$cache->setCache('nameless_news');
if ($cache->isCached('news')) {
    $news = $cache->retrieve('news');

} else {
    $news_query = Util::getLatestNews();
    $news_query = json_decode($news_query);

    $news = [];

    if (!is_null($news_query) && !isset($news_query->error) && count($news_query)) {
        $timeago = new TimeAgo(TIMEZONE);

        $i = 0;

        foreach ($news_query as $item) {
            $news[] = [
                'title' => Output::getClean($item->title),
                'date' => Output::getClean($item->date),
                'date_friendly' => $timeago->inWords($item->date, $language),
                'author' => Output::getClean($item->author),
                'url' => Output::getClean($item->url)
            ];

            if (++$i == 5) {
                break;
            }
        }
    }

    $cache->store('news', $news, 3600);
}

if (!count($news)) {
    $smarty->assign('NO_NEWS', $language->get('admin', 'unable_to_retrieve_nameless_news'));
} else {
    $smarty->assign('NEWS', $news);
}

// Compatibility
if ($user->hasPermission('admincp.core.debugging')) {
    $compat_success = [];
    $compat_warnings = [];
    $compat_errors = [];

    if (PHP_VERSION_ID < 80200) {
        $compat_warnings[] = 'PHP ' . PHP_VERSION;
    } else {
        $compat_success[] = 'PHP ' . PHP_VERSION;
    }
    if (!extension_loaded('gd')) {
        $compat_errors[] = 'PHP GD';
    } else {
        $compat_success[] = 'PHP GD ' . phpversion('gd');
    }
    if (!extension_loaded('mbstring')) {
        $compat_errors[] = 'PHP mbstring';
    } else {
        $compat_success[] = 'PHP mbstring ' . phpversion('mbstring');
    }
    if (!extension_loaded('PDO')) {
        $compat_errors[] = 'PHP PDO';
    } else {
        $compat_success[] = 'PHP PDO ' . phpversion('PDO');
    }
    if (!function_exists('curl_version')) {
        $compat_errors[] = 'PHP cURL';
    } else {
        $compat_success[] = 'PHP cURL ' . phpversion('curl');
    }
    if (!extension_loaded('xml')) {
        $compat_errors[] = 'PHP XML';
    } else {
        $compat_success[] = 'PHP XML ' . phpversion('xml');
    }
    if (!function_exists('exif_imagetype')) {
        $compat_errors[] = 'PHP EXIF';
    } else {
        $compat_success[] = 'PHP EXIF ' . phpversion('exif');
    }
    if (!extension_loaded('PDO')) {
        $compat_errors[] = 'PHP PDO';
    } else {
        $compat_success[] = 'PHP PDO ' . phpversion('PDO');
    }
    if (!extension_loaded('pdo_mysql')) {
        $compat_errors[] = 'PHP PDO MySQL';
    } else {
        $compat_success[] = 'PHP PDO MySQL ' . phpversion('pdo_mysql');
    }
    $pdo_driver = DB::getInstance()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);
    $pdo_server_version = DB::getInstance()->getPDO()->getAttribute(PDO::ATTR_SERVER_VERSION);
    // Even if it is a MariaDB server, it still reports "mysql" as the driver name.
    if ($pdo_driver === 'mysql') {
        if (!str_contains($pdo_server_version, 'MariaDB')) {
            $pdo_driver = 'MySQL';
        } else {
            $pdo_driver = 'MariaDB';
            // MariaDB version strings are displayed as: "<major>.<minor>.<patch>-MariaDB" OR sometimes its "<replication version hack>-<major>.<minor>.<patch>-MariaDB",
            // and we only want the version number.
            preg_match('/(?:.+-)?(.+)?-MariaDB/', $pdo_server_version, $pdo_server_version);
            $pdo_server_version = $pdo_server_version[1];
        }
    }

    if (($pdo_driver === 'MySQL' && version_compare($pdo_server_version, '8.0', '>=')) ||
        ($pdo_driver === 'MariaDB' && version_compare($pdo_server_version, '10.5', '>='))) {
        $compat_success[] = $pdo_driver . ' Server ' . $pdo_server_version;
    } else if (($pdo_driver === 'MySQL' && version_compare($pdo_server_version, '5.7', '>=')) ||
        ($pdo_driver === 'MariaDB' && version_compare($pdo_server_version, '10.3', '>='))) {
        $compat_warnings[] = $pdo_driver . ' Server ' . $pdo_server_version;
    } else {
        $compat_errors[] = $pdo_driver . ' Server ' . $pdo_server_version;
    }

    if (HttpUtils::isTrustedProxiesConfigured()) {
        $compat_success[] = $language->get('admin', 'trusted_proxies_configured');
    } else {
        $compat_errors[] = $language->get('admin', 'trusted_proxies_not_configured', [
            'linkStart' => '<a href="https://docs.namelessmc.com/trusted-proxies" target="_blank">',
            'linkEnd' => '</a>',
        ]);
    }

    if (HttpUtils::getPort() === 80 && HttpUtils::getProtocol() === 'https') {
        $compat_errors[] = $language->get('admin', 'https_port_80');
    }

    if (defined('DEBUGGING') && DEBUGGING) {
        $compat_errors[] = $language->get('admin', 'debugging_enabled');
    }

    if ($template->getName() !== 'Default') {
        $compat_warnings[] = $language->get('admin', 'panel_template_third_party', [
            'name' => Text::bold($template->getName()),
        ]);
    }

    $smarty->assign([
        'SERVER_COMPATIBILITY' => $language->get('admin', 'server_compatibility'),
        'COMPAT_SUCCESS' => $compat_success,
        'COMPAT_WARNINGS' => $compat_warnings,
        'COMPAT_ERRORS' => $compat_errors,
    ]);
}

if (is_dir(ROOT_PATH . '/modules/Core/pages/admin')) {
    $smarty->assign([
        'DIRECTORY_WARNING' => $language->get('admin', 'admin_dir_still_exists')
    ]);
} else {
    if (is_dir(ROOT_PATH . '/modules/Core/pages/mod')) {
        $smarty->assign([
            'DIRECTORY_WARNING' => $language->get('admin', 'mod_dir_still_exists')
        ]);
    }
}

$smarty->assign([
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'DASHBOARD_STATS' => CollectionManager::getEnabledCollection('dashboard_stats'),
    'PAGE' => PANEL_PAGE,
    'PARENT_PAGE' => PANEL_PAGE,
    'GRAPHS' => $graphs,
    'STATISTICS' => $language->get('admin', 'statistics'),
    'NAMELESS_NEWS' => $language->get('admin', 'nameless_news'),
    'CONFIRM_LEAVE_SITE' => $language->get('admin', 'confirm_leave_site', [
        'link' => '<strong id="leaveSiteURL">{x}</strong>',
    ]),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'MAIN_ITEMS' => CollectionManager::getEnabledCollection('dashboard_main_items'),
    'SIDE_ITEMS' => CollectionManager::getEnabledCollection('dashboard_side_items'),
    // TODO: show latest git commit hash?
    'NAMELESS_VERSION' => $language->get('admin', 'running_nameless_version', [
        'version' => Text::bold(NAMELESS_VERSION)
    ]),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('index.tpl', $smarty);
