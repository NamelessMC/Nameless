<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  UserCP overview
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_overview';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$user_details = [
    $language->get('user', 'username') => $user->getDisplayname(true),
    $language->get('admin', 'group') => Output::getClean($user->getMainGroup()->name),
    $language->get('admin', 'registered') => date(DATE_FORMAT, $user->data()->joined)
];

// Language values
$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'USER_DETAILS' => $language->get('user', 'user_details'),
    'USER_DETAILS_VALUES' => $user_details,
    'OVERVIEW' => $language->get('user', 'overview')
]);

// Get graph data
$forum_enabled = Util::isModuleEnabled('Forum');

if ($forum_enabled) {
    $forum_query_user = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM nl2_posts WHERE post_creator = ? AND created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [$user->data()->id, strtotime('-7 days')])->results();
    $forum_query_average = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), (COUNT(*) / COUNT(Distinct post_creator)) FROM nl2_posts WHERE created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [strtotime('-7 days')])->results();
    $forum_query_total = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM nl2_posts WHERE created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [strtotime('-7 days')])->results();

    $output = [];
    foreach ($forum_query_user as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['user'] = $item->{'COUNT(*)'};
    }
    foreach ($forum_query_average as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['average'] = $item->{'(COUNT(*) / COUNT(Distinct post_creator))'};
    }
    foreach ($forum_query_total as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['total'] = $item->{'COUNT(*)'};
    }

    // Fill in missing dates
    $graph_start = strtotime('-7 days');
    $graph_start = date('d M Y', $graph_start);
    $graph_start = strtotime($graph_start);
    $end = strtotime(date('d M Y'));
    while ($graph_start <= $end) {
        if (!isset($output[$graph_start]['user'])) {
            $output[$graph_start]['user'] = 0;
        }

        if (!isset($output[$graph_start]['average'])) {
            $output[$graph_start]['average'] = 0;
        }

        if (!isset($output[$graph_start]['total'])) {
            $output[$graph_start]['total'] = 0;
        }

        $graph_start += 86400;
    }

    ksort($output);

    // Turn into string for graph
    $labels = '';
    $user_data = '';
    $average_data = '';
    $total_data = '';
    foreach ($output as $date => $item) {
        $labels .= '"' . date('Y-m-d', $date) . '", ';
        $user_data .= $item['user'] . ', ';
        $average_data .= $item['average'] . ', ';
        $total_data .= $item['total'] . ', ';
    }
    $labels = '[' . rtrim($labels, ', ') . ']';
    $user_data = '[' . rtrim($user_data, ', ') . ']';
    $average_data = '[' . rtrim($average_data, ', ') . ']';
    $total_data = '[' . rtrim($total_data, ', ') . ']';

    $smarty->assign('FORUM_GRAPH', $forum_language->get('forum', 'last_7_days_posts'));
}

if ($forum_enabled) {
    $template->assets()->include([
        AssetTree::MOMENT,
        AssetTree::CHART_JS,
    ]);

    $template->addJSScript(
        '
        $(document).ready(function() {
            var ctx = $("#dataChart").get(0).getContext("2d");

            moment.locale(\'' . (defined('HTML_LANG') ? strtolower(HTML_LANG) : 'en') . '\');

            var data = {
                labels: ' . $labels . ',
                datasets: [
                    {
                        label: "' . $forum_language->get('forum', 'your_posts') . '",
                        fill: false,
                        borderColor: "rgba(255,12,0,0.5)",
                        pointBorderColor: "rgba(255,0,5,0.5)",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $user_data . '
                    },
                    {
                        label: "' . $forum_language->get('forum', 'average_posts') . '",
                        fill: false,
                        borderColor: "#0004FF",
                        pointBorderColor: "#0004FF",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $average_data . '
                    },
                    {
                        label: "' . $forum_language->get('forum', 'total_posts') . '",
                        fill: false,
                        borderColor: "#00931D",
                        pointBorderColor: "#00931D",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $total_data . '
                    },
                ]
            }

            var dataLineChart = new Chart(ctx, {
                type: \'line\',
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                userCallback: function(label, index, labels) {
                                    // when the floored value is the same as the value we have a whole number
                                    if (Math.floor(label) === label) {
                                        return label;
                                    }

                                }
                            }
                        }],
                        xAxes: [{
                            type: \'time\',
                            time: {
                                unit: \'day\'
                            }
                        }]
                    }
                }
            });
        });
        '
    );
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/index.tpl', $smarty);
