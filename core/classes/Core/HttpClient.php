<?php

class HttpClient {

    private $ch;
    private $data;

    private function __construct($ch, $data) {
        $this->ch = $ch;
        $this->data = $data;
    }

    /**
     * Get the response body
     *
     * @return string The response body
     */
    public function data(): string {
        return $this->data;
    }

    /**
     * Get the response HTTP status code
     *
     * @return int The response code
     */
    public function getStatus(): int {
        return curl_getinfo($this->ch, CURLINFO_RESPONSE_CODE);
    }

    /**
     * Check if the response has an error
     *
     * @return bool Whether the response has an error or not
     */
    public function hasError() : bool {
        return $this->data === false && $this->getError() !== '';
    }

    /**
     * Get the error message
     *
     * @return string The error message
     */
    public function getError(): string {
        return curl_error($this->ch);
    }

    public function __destruct() {
        curl_close($this->ch);
    }

    /**
     * Make a GET request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @return HttpClient New HttpClient instance.
     */
    public static function get(string $url, array $options = []): HttpClient {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            ...$options
        ]);

        $contents = curl_exec($ch);

        // Make an error log if a curl error occurred
        if ($contents === false) {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
        }

        return new HttpClient($ch, $contents);
    }

    /**
     * Make a POST request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @param string $data JSON request body to attach to request.
     * @return HttpClient New HttpClient instance.
     */
    public static function post(string $url, string $data): HttpClient {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type:application/json'
            ],
        ]);

        $contents = curl_exec($ch);

        // Make an error log if a curl error occurred
        if ($contents === false) {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
        }

        return new HttpClient($ch, $contents);
    }

}