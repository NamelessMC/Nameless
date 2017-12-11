<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  External Minecraft query class
 */

class ExternalMCQuery {
    private static  $_count = 0,
                    $_servers = array();

    // Add a server to the query
    // Params: $server - IP address/domain for server, with port
    public static function addServer($server){
        self::$_servers[] = $server;
        self::$_count++;
    }

    // Query all servers
    // Returns array containing server query data
    // Params: $type - Query type - basic, playerlist or extensive
    public static function queryServers($type = 'basic'){
        if(self::$_count == 0)
            return false;

        switch($type){
            case 'playerlist':
                return self::playerListQuery();
                break;

            case 'extensive':
            case 'basic':
                return self::query($type);
                break;
        }
    }

    // Basic server query
    // Returns array containing query result
    // Params: $type = Query type - basic or extensive
    private static function query($type){
        if($type == 'basic'){
            $action = '/info/';

        } else if($type == 'extensive'){
            $action = '/extensive/';

        } else return false;

        // Single or batch?
        if(self::$_count > 1){
            // Batch
            $queryUrl = 'https://use.gameapis.net/mc/query' . $action;
            foreach(self::$_servers as $server){
                $queryUrl .= $server . ',';
            }

            $queryUrl = rtrim($queryUrl, ',');
        } else
            // Single
            $queryUrl = 'https://use.gameapis.net/mc/query' . $action . self::$_servers[0];

        try {
            // cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, $queryUrl);

            $result = curl_exec($ch);
            $result = json_decode($result);

            curl_close($ch);

            return $result;

        } catch(Exception $e){
            return array(
                'error' => true,
                'value' => $e->getMessage()
            );
        }
    }

    // Query servers for playerlist
    // Returns array containing query result
    private static function playerListQuery(){
        // No batch method available in API
        $results = array();

        foreach(self::$_servers as $server){
            try {
                // cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_URL, 'https://use.gameapis.net/mc/query/players/' . $server);

                $result = curl_exec($ch);
                $result = json_decode($result);

                curl_close($ch);

                // Add result to return array
                $results[$server] = $result;

            } catch(Exception $e){
                // Exception
                $results[$server] = array(
                    'error' => true,
                    'value' => $e->getMessage()
                );
            }
        }

        return $results;
    }

    // Check Minecraft service status
    // Returns array containing query response
    public static function queryMinecraftServices(){
        try {
            // cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, 'https://use.gameapis.net/mc/extra/status');

            $result = curl_exec($ch);
            $result = json_decode($result);

            curl_close($ch);

        } catch(Exception $e){
            $result = array(
                'error' => true,
                'value' => $e->getMessage()
            );
        }

        return $result;
    }

    // Get a server's favicon
    // Params: $ip - server IP address
    public static function getFavicon($ip = null){
        if($ip){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, 'https://use.gameapis.net/mc/query/icon/' . $ip);

            $result = curl_exec($ch);

            curl_close($ch);

            return $result;
        }
        return false;
    }
}