<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  External Minecraft query class
 */

class ExternalMCQuery {

    /**
     * Basic server query.
     *
     * @param string $ip IP to query
     * @param int|null $port Port to query, `25565` by default.
     * @return object Query result.
     */
    public static function query(string $ip, int $port = 25565) {
        $queryUrl = 'https://api.namelessmc.com/api/server/' . $ip . '/' . $port;

        try {

            return json_decode(HttpClient::get($queryUrl, [
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 5
            ])->data());

        } catch (Exception $e) {
            return [
                'error' => true,
                'value' => $e->getMessage()
            ];
        }
    }

    /**
     * Get a server's favicon.
     *
     * @param string|null $ip Server's IP.
     * @return bool
     */
    public static function getFavicon(string $ip = null): bool{
        if($ip){
            $query_ip = explode(':', $ip);

            if(count($query_ip) == 2){
                $ip = $query_ip[0];
                $port = $query_ip[1];
            } else if(count($query_ip) == 1) {
                $ip = $query_ip[0];
                $port = $query_ip[1];
            } else
                return false;

            $queryUrl = 'https://api.namelessmc.com/api/server/' . $ip . '/' . $port;

            try {
                $result = json_decode(HttpClient::get($queryUrl, [
                    CURLOPT_CONNECTTIMEOUT => 0,
                    CURLOPT_TIMEOUT => 5
                ])->data());

                if (!$result->error && $result->response->description->favicon) {
                    return $result->response->description->favicon;
                }

            } catch (Exception $e) {
            }
        }
        return false;
    }
}
