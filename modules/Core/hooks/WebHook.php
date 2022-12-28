<?php
declare(strict_types=1);

use DebugBar\DebugBarException;

/**
 * Webhook handler class
 *
 * @package Core\Hooks
 * @author Partydragen
 * @version 2.0.0
 * @license MIT
 */
class WebHook {

    /**
     * @param array $params
     *
     * @return void
     * @throws DebugBarException
     */
    public static function execute(array $params = []): void {
        $return = $params;
        unset($return['webhook']);
        $json = json_encode($return, JSON_UNESCAPED_SLASHES);

        $httpClient = HttpClient::post($params['webhook'], $json, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($httpClient->hasError()) {
            trigger_error($httpClient->getError());
        }
    }
}
