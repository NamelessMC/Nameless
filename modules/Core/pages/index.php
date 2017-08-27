<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Display either homepage or portal
 */

// Minecraft integration?
if(defined('MINECRAFT') && MINECRAFT === true){
    // Query main server
    $cache->setCache('mc_default_server');

    // Already cached?
    if($cache->isCached('default_query')) {
        $result = $cache->retrieve('default_query');
        $default = $cache->retrieve('default');
    } else {
        if($cache->isCached('default')){
            $default = $cache->retrieve('default');
            $sub_servers = $cache->retrieve('default_sub');
        } else {
            // Get default server from database
            $default = $queries->getWhere('mc_servers', array('is_default', '=', 1));
            if(count($default)){
                // Get sub-servers of default server
                $sub_servers = $queries->getWhere('mc_servers', array('parent_server', '=', $default[0]->id));
                if(count($sub_servers))
                    $cache->store('default_sub', $sub_servers);
                else
                    $cache->store('default_sub', array());

                $default = $default[0];

                $cache->store('default', $default, 60);
            } else
                $cache->store('default', null, 60);
        }

        if(!is_null($default) && isset($default->ip)){
            $full_ip = array('ip' => $default->ip . (is_null($default->port) ? '' : ':' . $default->port), 'pre' => $default->pre, 'name' => $default->name);

            // Get query type
            $query_type = $queries->getWhere('settings', array('name', '=', 'external_query'));
            if(count($query_type)){
                if($query_type[0]->value == '1')
                    $query_type = 'external';
                else
                    $query_type = 'internal';
            } else
                $query_type = 'internal';

            if(count($sub_servers)){
                $servers = array($full_ip);

                foreach($sub_servers as $server)
                    $servers[] = array('ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port), 'pre' => $server->pre, 'name' => $server->name);

                $result = MCQuery::multiQuery($servers, $query_type, $language, true, $queries);

            } else {
                $result = MCQuery::singleQuery($full_ip, $query_type, $language, $queries);
            }

            // Cache for 1 minute
            $cache->store('default_query', $result, 60);
        }
    }

    $smarty->assign('MINECRAFT', true);

    if(isset($result))
        $smarty->assign('SERVER_QUERY', $result);

    if(!is_null($default) && isset($default->ip))
        $smarty->assign('CONNECT_WITH', str_replace('{x}', $default->ip . ($default->port != 25565 ? ':' . $default->port : ''), $language->get('general', 'connect_with_ip_x')));
}

// Home page or portal?
$cache->setCache('portal_cache');
$use_portal = $cache->retrieve('portal');

if($use_portal !== 1) require('home.php');
else require('portal.php');