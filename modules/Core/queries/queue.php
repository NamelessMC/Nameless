<?php
/**
 * Queue query runs any pending tasks
 *
 * @var $cache
 */

header('Content-type: application/json;charset=utf-8');

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

// Process queue
$tasks = Queue::process();

return_json($tasks);
