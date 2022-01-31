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
     * @param int $port Port to query, `25565` by default.
     * @return object Query result.
     */
    public static function query(string $ip, int $port = 25565): object {
        return HttpClient::get('https://api.namelessmc.com/api/server/' . $ip . '/' . $port)->json();
    }

    /**
     * Get a server's favicon.
     *
     * @param string|null $ip Server's IP.
     * @return bool
     */
    public static function getFavicon(string $ip = null): bool {
        if ($ip) {
            $query_ip = explode(':', $ip);

            if (count($query_ip) == 2) {
                $ip = $query_ip[0];
                $port = $query_ip[1];
            } else {
                if (count($query_ip) == 1) {
                    $ip = $query_ip[0];
                    $port = $query_ip[1];
                } else {
                    return false;
                }
            }

            $result = HttpClient::get('https://api.namelessmc.com/api/server/' . $ip . '/' . $port)->json();

            if (!$result->error && $result->response->description->favicon) {
                return $result->response->description->favicon;
            }
        }
        return false;
    }
}
