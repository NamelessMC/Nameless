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
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @return object Query result.
     */
    public static function query(string $ip, int $port = 25565, bool $bedrock = false): object {
        return HttpClient::get('https://api.namelessmc.com/api/' . ($bedrock ? 'bedrock' : 'server') . '/' . $ip . '/' . $port)->json();
    }

    /**
     * Get a server's favicon.
     *
     * @param string|null $ip Server's IP.
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @return bool
     */
    public static function getFavicon(string $ip = null, bool $bedrock = false): bool {
        if ($ip) {
            $query_ip = explode(':', $ip);

            if (count($query_ip) !== 2 && count($query_ip) !== 1) {
                return false;
            }

            $ip = $query_ip[0];
            $port = $query_ip[1] ?? ($bedrock ? 19132 : 25565);

            $result = HttpClient::get('https://api.namelessmc.com/api/' . ($bedrock ? 'bedrock' : 'server') . '/' . $ip . '/' . $port)->json();

            if (!$result->error && $result->response->description->favicon) {
                return $result->response->description->favicon;
            }
        }
        return false;
    }
}
