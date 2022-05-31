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
    $query_type = DB::getInstance()->get('settings', ['name', 'external_query'])->results();
    if (count($query_type)) {
        if ($query_type[0]->value == '1') {
            $query_type = 'external';
        } else {
            $query_type = 'internal';
        }
    } else {
        $query_type = 'internal';
    }

    $full_ip = [
        'ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port),
        'pre' => $server->pre,
        'name' => $server->name
    ];

    $result = json_encode(MCQuery::singleQuery($full_ip, $query_type, $server->bedrock, $language), JSON_PRETTY_PRINT);
    $cache->store('result', $result, 30);
    echo $result;
}

die();
