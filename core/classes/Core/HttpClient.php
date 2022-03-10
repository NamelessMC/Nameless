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
        $guzzleClient = new Client(array_merge([
            'timeout' => 5.0,
            'handler' => self::createHandler(),
        ], $options));

        $error = '';

        try {
            $response = $guzzleClient->get($url);
        } catch (GuzzleException $exception) {
            $error = $exception->getMessage();
            Log::getInstance()->log(Log::Action('misc/curl_error'), $exception->getMessage());
        }

        return new HttpClient(
            $response,
            $error
        );
    }

    /**
     * Make a POST request to a URL.
     * Failures will automatically be logged along with the error.
     *
     * @param string $url URL to send request to.
     * @param string $data JSON request body to attach to request.
     * @param array $options Options to set with the GuzzleClient.
     * @return HttpClient New HttpClient instance.
     */
    public static function post(string $url, string $data, array $options = []): HttpClient {
        $guzzleClient = new Client(array_merge([
            'timeout' => 5.0,
            'handler' => self::createHandler(),
        ], $options));

        $error = '';

        try {
            $response = $guzzleClient->post($url, [
                'body' => $data,
            ]);
        } catch (GuzzleException $exception) {
            $error = $exception->getMessage();
            Log::getInstance()->log(Log::Action('misc/curl_error'), $exception->getMessage());
        }

        return new HttpClient(
            $response,
            $error
        );
    }

    /**
     * Create a Guzzle handler stack with profiling middleware for the PHPDebugBar.
     *
     * @return HandlerStack The handler stack to use with Guzzle.
     */
    private static function createHandler(): HandlerStack {
        $debugBar = DebugBarHelper::getInstance()->getDebugBar();
        $stack = HandlerStack::create();

        if ($debugBar !== null) {
            $stack->unshift(new ProfilingMiddleware(
                new Profiler($debugBar->getCollector('time'))
            ));
        }

        return $stack;
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
            return $this->_error;
        }

        if ($this->_response === null) {
            return '$this->_response is null';
        }

        return '';
    }

}
