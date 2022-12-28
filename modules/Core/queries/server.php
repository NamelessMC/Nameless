<?php
declare(strict_types=1);
/**
 *  Made by Unknown
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  TODO: Add description
 *
 * @var Cache $cache
 * @var Language $language
 */

// Check server ID is specified
use GuzzleHttp\Exception\GuzzleException;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die();
}

$server = DB::getInstance()->get('mc_servers', ['id', $_GET['id']])->results();
if (!count($server)) {
    die();
}

$server = $server[0];

$cache->setCacheName('server_' . $server->id);
if ($cache->hasCashedData('result')) {
    echo $cache->retrieve('result');
} else {
    // Get query type
    $query_type = Util::getSetting('external_query', '0');
    if ($query_type === '1') {
        $query_type = 'external';
    } else {
        $query_type = 'internal';
    }

    $full_ip = [
        'ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port),
        'pre' => $server->pre,
        'name' => $server->name
    ];

    try {
        $result = json_encode(MCQuery::singleQuery($full_ip, $query_type, $server->bedrock, $language), JSON_PRETTY_PRINT);
    } catch (GuzzleException $ignored) {
    }

    $cache->store('result', $result, 30);
    echo $result;
}

die();
