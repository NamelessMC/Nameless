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
    public static function query($ip, $port = 25565) {
        $queryUrl = 'https://api.namelessmc.com/api/server/' . $ip . '/' . $port;

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

        } catch (Exception $e) {
            return array(
                'error' => true,
                'value' => $e->getMessage()
            );
        }
    }

    /**
     * Get a server's favicon.
     * 
     * @param string $ip Server's IP.
     */
    public static function getFavicon($ip = null) {
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

                if(!$result->error && $result->response->description->favicon)
                    return $result->response->description->favicon;

            } catch (Exception $e) {
            }
        }
        return false;
    }
}
