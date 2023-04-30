<?php
/**
 * Queue query runs any pending tasks
 *
 * @var Cache $cache
 * @var \DI\Container $container
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var Smarty $smarty
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

header('Content-type: application/json;charset=utf-8');

define('PAGE', 'queue_query');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// TODO: mixed type for $output when PHP 8.0+
function return_json($output, ?bool $error = false) {
    echo json_encode(['error' => $error, 'output' => $output], JSON_PRETTY_PRINT);
    die();
}

$cache->setCache('queue');
$last_run = intval($cache->retrieve('last_run') ?? 0);

$interval = floatval(Util::getSetting('queue_interval') ?? 1);
$runner = Util::getSetting('queue_runner');

if ($runner == 'cron') {
    if (!isset($_GET['cron'])) {
        return_json('Invalid cron URL', true);
    }

    if (Input::get('key') != Util::getSetting('cron_key')) {
        return_json('Invalid cron key', true);
    }
}

$date = date('U');
$next_run = $last_run + ($interval * 60);
$diff = $next_run - $date;

if ($last_run && $diff > 0) {
    return_json("Please wait $diff seconds before executing the queue", true);
}

// Update last run immediately
$cache->store('last_run', $date);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

// Process queue
$tasks = Queue::process($container);

return_json($tasks);
