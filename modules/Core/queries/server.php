<?php
// Check server ID is specified
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die();
}

$server = $queries->getWhere('mc_servers', array('id', '=', $_GET['id']));
if (!count($server)) {
    die();
} else {
    $server = $server[0];
}

$cache->setCache('server_' . $server->id);
if ($cache->isCached('result')) {
    echo $cache->retrieve('result');
} else {
    // Get query type
    $query_type = $queries->getWhere('settings', array('name', '=', 'external_query'));
    if (count($query_type)) {
        if ($query_type[0]->value == '1')
            $query_type = 'external';
        else
            $query_type = 'internal';
    } else
        $query_type = 'internal';

    $full_ip = array('ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port), 'pre' => $server->pre, 'name' => $server->name);

    $result = json_encode(MCQuery::singleQuery($full_ip, $query_type, $language, $queries), JSON_PRETTY_PRINT);
    $cache->store('result', $result, 30);
    echo $result;
}

die();
