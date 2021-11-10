<?php

class HttpClient {

    /**
     * Make a GET request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @return string|bool Response from remote server, false on failure.
     */
    public static function get(string $url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $contents = curl_exec($ch);

        // Make an error log if a curl error occurred
        if ($contents === false) {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
            curl_close($ch);

            return false;
        }

        curl_close($ch);

        return $contents;
    }

    /**
     * Make a POST request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @param string $data JSON request body to attach to request.
     * @return string|bool Response from remote server, false on failure.
     */
    public static function post(string $url, string $data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $contents = curl_exec($ch);

        // Make an error log if a curl error occurred
        if ($contents === false) {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
            curl_close($ch);

            return false;
        }

        curl_close($ch);

        return $contents;
    }

}