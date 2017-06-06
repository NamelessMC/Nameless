<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Minecraft server query class
 */

class MCQuery {
    // Query a single server
    // Returns array containing query result
    // Params:  $ip - full server IP address with port (separated by :) to query
    //          $type - type of query to use (internal or external)
    //          $language - query language object
    public static function singleQuery($ip = null, $type = 'internal', $language){
        if($ip){
            try {
                if($type == 'internal'){
                    // Internal query
                    $query_ip = explode(':', $ip['ip']);

                    if(count($query_ip) == 2){
                        $ping = new MinecraftPing($query_ip[0], $query_ip[1], 1);

                        if($ip['pre'] == 1)
                            $query = $ping->QueryOldPre17();
                        else
                            $query = $ping->Query();

                        if(isset($query['players']))
                            $return = array(
                                'status_value' => 1,
                                'status' => $language->get('general', 'online'),
                                'player_count' => Output::getClean($query['players']['online']),
                                'player_count_max' => Output::getClean($query['players']['max']),
                                'x_players_online' => str_replace('{x}', Output::getClean($query['players']['online']), $language->get('general', 'currently_x_players_online'))
                            );
                        else
                            $return = array(
                                'status_value' => 0,
                                'status' => $language->get('general', 'offline'),
                                'server_offline' => $language->get('general', 'server_offline')
                            );

                        $ping->close();

                        return $return;
                    }

                } else {
                    // External query
                    ExternalMCQuery::addServer($ip['ip']);
                    $query = ExternalMCQuery::queryServers('basic');

                    if(isset($query->status))
                        $return = array(
                            'status_value' => 1,
                            'status' => $language->get('general', 'online'),
                            'player_count' => Output::getClean($query->players->online),
                            'player_count_max' => Output::getClean($query->players->max),
                            'x_players_online' => str_replace('{x}', Output::getClean($query->players->online), $language->get('general', 'currently_x_players_online'))
                        );
                    else
                        $return = array(
                            'status_value' => 0,
                            'status' => $language->get('general', 'offline'),
                            'server_offline' => $language->get('general', 'server_offline')
                        );

                    return $return;
                }
            } catch(Exception $e){
                return array(
                    'error' => true,
                    'value' => $e->getMessage()
                );
            }
        }
        return false;
    }
}