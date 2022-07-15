<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Profiling\Middleware as ProfilingMiddleware;
use GuzzleHttp\Profiling\Debugbar\Profiler;
use Psr\Http\Message\ResponseInterface;

/**
 * Provides simple methods to make GET & POST HTTP requests.
 * Wrapper around GuzzleHttp\Client.
 *
 * @see GuzzleHttp\Client
 * @package NamelessMC\Core
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class HttpClient {

    private ?ResponseInterface $_response;
    private string $_error;

    private function __construct(?ResponseInterface $response, string $error) {
        $this->_response = $response;
        $this->_error = $error;
    }

    /**
     * Make a GET request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @param array $options Options to set with the GuzzleClient.
     * @return HttpClient New HttpClient instance.
     */
    public static function get(string $url, array $options = []): HttpClient {
        $guzzleClient = self::createClient($options);

        $error = '';

        try {
            $response = $guzzleClient->get($url);
        } catch (GuzzleException $exception) {
            $error = $exception->getMessage();
            Log::getInstance()->log(Log::Action('misc/curl_error'), $exception->getMessage());
        }

        return new HttpClient(
            $response ?? null,
            $error
        );
    }

    /**
     * Make a POST request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @param string|array $data JSON request body to attach to request, or array of key value pairs if form-urlencoded.
     * @param array $options Options to set with the GuzzleClient.
     * @return HttpClient New HttpClient instance.
     */
    public static function post(string $url, $data, array $options = []): HttpClient {
        $guzzleClient = self::createClient($options);

        $error = '';

        try {
            $response = $guzzleClient->post($url, [
                // if the data is an array, we assume they want to send it as form-urlencoded, otherwise it's json
                is_array($data) ? 'form_params' : 'body' => $data,
            ]);
        } catch (GuzzleException $exception) {
            $error = $exception->getMessage();
            Log::getInstance()->log(Log::Action('misc/curl_error'), $exception->getMessage());
        }

        return new HttpClient(
            $response ?? null,
            $error
        );
    }

    /**
     * Make a new Guzzle Client instance and attach it to the debug bar to display requests.
     *
     * @param array $options Options to provide Guzzle instance.
     * @return Client New Guzzle instance.
     */
    public static function createClient(array $options = []): Client {
        $debugBar = DebugBarHelper::getInstance()->getDebugBar();
        $stack = HandlerStack::create();

        if ($debugBar !== null) {
            $stack->unshift(new ProfilingMiddleware(
                new Profiler($debugBar->getCollector('time'))
            ));
        }

        return new Client(array_merge([
            'timeout' => 5.0,
            'handler' => $stack,
        ], $options));
    }

    /**
     * Get the response body
     *
     * @return string The response body
     */
    public function contents(): string {
        return $this->_response->getBody()->getContents();
    }

    /**
     * Get the response body as a decoded JSON object
     *
     * @param bool $assoc Whether to decode the JSON as a PHP array if true or PHP object.
     * @return mixed The response body
     */
    public function json(bool $assoc = false) {
        return json_decode($this->contents(), $assoc);
    }

    /**
     * Get the response HTTP status code
     *
     * @return int The response code
     */
    public function getStatus(): int {
        return $this->_response->getStatusCode();
    }

    /**
     * Check if the response has an error
     *
     * @return bool Whether the response has an error or not
     */
    public function hasError(): bool {
        return $this->getError() !== '';
    }

    /**
     * Get the error message
     *
     * @return string The error message
     */
    public function getError(): string {
        if ($this->_error !== '') {
            return Output::getClean($this->_error);
        }

        if ($this->_response === null) {
            return '$this->_response is null';
        }

        return '';
    }

}
