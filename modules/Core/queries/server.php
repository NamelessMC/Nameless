<?php
// Check server ID is specified
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die();
}

$server = DB::getInstance()->get('mc_servers', ['id', $_GET['id']])->results();
if (!count($server)) {
    die();
}

$server = $server[0];

$cache->setCache('server_' . $server->id);
if ($cache->isCached('result')) {
    echo $cache->retrieve('result');
} else {
    // Get query type
    $query_type = Util::getSetting('external_query', '0');
    if ($query_type == QueryType::EXTERNAL) {
        $query_type = 'external';
    } else if ($query_type == QueryType::INTERNAL) {
        $query_type = 'internal';
    } else if ($query_type == QueryType::PLUGIN) {
        $query_type = 'plugin';
    }

    $full_ip = [
        'ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port),
        'pre' => $server->pre,
        'name' => $server->name
    ];

    $result = json_encode($query_type === 'plugin' ? PluginQuery::singleQuery($server->id, $language) : MCQuery::singleQuery($full_ip, $query_type, $server->bedrock, $language), JSON_PRETTY_PRINT);
    $cache->store('result', $result, 30);
    echo $result;
}

die();
