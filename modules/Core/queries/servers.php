<?php
// Check cache to see when servers were last queried
$cache->setCache('server_query_cache');
if ($cache->isCached('query_interval')) {
    $query_interval = $cache->retrieve('query_interval');
    if (is_numeric($query_interval) && $query_interval <= 60 && $query_interval >= 5) {
        // Interval ok
    } else {
        // Default to 10
        $query_interval = 10;

        $cache->store('query_interval', $query_interval);
    }
} else {
    // Default to 10
    $query_interval = 10;

    $cache->store('query_interval', $query_interval);
}

if (isset($_GET['key'])) {
    // Get key from database - check it matches
    $key = Util::getSetting('unique_id');
    if ($key == null || $_GET['key'] != $key) {
        die();
    }
} else {
    if ($cache->isCached('last_query')) {
        $last_query = $cache->retrieve('last_query');
        if ($last_query > strtotime($query_interval . ' minutes ago')) {
            // No need to re-query
            die('1');
        }
    }
}

// Get query type
$query_type = Util::getSetting('external_query', 'use else statement if seting is not present in database');
if ($query_type === '1') {
    $query_type = 'external';
} else {
    $query_type = 'internal';
}

// Query
$servers = DB::getInstance()->get('mc_servers', ['id', '<>', 0])->results();
if (count($servers)) {
    $results = [];

    foreach ($servers as $server) {
        // Get query address for server
        $full_ip = [
            'ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port),
            'pre' => $server->pre,
            'name' => $server->name
        ];
        $result = MCQuery::singleQuery($full_ip, $query_type, $server->bedrock, $language);

        if ($server->parent_server > 0) {
            $result['parent_server'] = $server->parent_server;
        }

        $results[$server->id] = $result;
    }

    // Parent servers
    foreach ($results as $id => $result) {
        if (isset($result['parent_server'], $results[$result['parent_server']])) {
            $results[$result['parent_server']]['player_count'] += $result['player_count'];
        }
    }

    // Insert into db
    foreach ($results as $id => $result) {
        // Insert into db
        DB::getInstance()->insert('query_results', [
            'server_id' => $id,
            'queried_at' => date('U'),
            'players_online' => ($result['player_count'] ?? 0)
        ]);
    }

    $cache->store('last_query', date('U'));
}

die('2');
