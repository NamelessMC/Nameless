<?php
/*
 *	Made by Samerton
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
            $date = (int)str_replace('_', '', $date);

            if (!array_key_exists($date, $graph['keys'])) {
                $graph['keys'][$date] = date('Y-m-d', $date);
            }

            foreach ($values as $valuekey => $value) {
                $graph['datasets'][$valuekey]['data'][date('Y-m-d', $date)] = $value;
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
        $timeago = new TimeAgo();

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
    $compat_errors = [];

    if (version_compare(PHP_VERSION, '7.4', '<')) {
        $compat_errors[] = 'PHP ' . PHP_VERSION;
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
    if (!extension_loaded('mysql') && !extension_loaded('mysqlnd')) {
        $compat_errors[] = 'PHP MySQL';
    } else {
        $compat_success[] = 'PHP MySQL ' . (extension_loaded('mysql') ? phpversion('mysql') : substr(phpversion('mysqlnd'), 0, strpos(phpversion('mysqlnd'), ' - ')));
    }

    // Permissions
    if (!is_writable(ROOT_PATH . '/core/config.php')) {
        $compat_errors[] = $language->get('installer', 'config_writable');
    } else {
        $compat_success[] = $language->get('installer', 'config_writable');
    }
    if (!is_writable(ROOT_PATH . '/cache')) {
        $compat_errors[] = $language->get('installer', 'cache_writable');
    } else {
        $compat_success[] = $language->get('installer', 'cache_writable');
    }
    if (!is_writable(ROOT_PATH . '/cache/templates_c')) {
        $compat_errors[] = $language->get('installer', 'template_cache_writable');
    } else {
        $compat_success[] = $language->get('installer', 'template_cache_writable');
    }

    $smarty->assign([
        'SERVER_COMPATIBILITY' => $language->get('admin', 'server_compatibility'),
        'COMPAT_SUCCESS' => $compat_success,
        'COMPAT_ERRORS' => $compat_errors
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
    'NAMELESS_VERSION' => $language->get('admin', 'running_nameless_version', ['version' => NAMELESS_VERSION])
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('index.tpl', $smarty);
