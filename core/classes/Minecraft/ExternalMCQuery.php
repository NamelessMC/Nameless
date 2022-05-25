<?php
/**
 * Queries Minecraft servers using the external querying API.
 *
 * @package NamelessMC\Minecraft
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class ExternalMCQuery {

    /**
     * Basic server query.
     *
     * @param string $ip IP to query
     * @param int $port Port to query, `25565` by default.
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @return object|false Query result, false on failure.
     */
    public static function query(string $ip, int $port = 25565, bool $bedrock = false) {
        $client = HttpClient::get('https://api.namelessmc.com/api/' . ($bedrock ? 'bedrock' : 'server') . '/' . $ip . '/' . $port);

        if (!$client->hasError()) {
            return $client->json();
        }

        return false;
    }

    /**
     * Get a server's favicon.
     *
     * @param string $ip Server's IP.
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @return string|false Server's favicon, false on failure.
     */
    public static function getFavicon(string $ip, bool $bedrock = false) {
        $query_ip = explode(':', $ip);

        if (count($query_ip) !== 2 && count($query_ip) !== 1) {
            return false;
        }

        $ip = $query_ip[0];
        $port = $query_ip[1] ?? ($bedrock ? 19132 : 25565);

        $client = HttpClient::get('https://api.namelessmc.com/api/' . ($bedrock ? 'bedrock' : 'server') . '/' . $ip . '/' . $port);
        if ($client->hasError()) {
            return false;
        }

        $result = $client->json();

        if (!$result->error && $result->response->description->favicon) {
            return $result->response->description->favicon;
        }

        return false;
    }
}
