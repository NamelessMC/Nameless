<?php

use DebugBar\DebugBarException;

/**
 * Webhook handler class
 *
 * @package Modules\Core\Hooks
 * @author Partydragen
 * @version 2.0.0
 * @license MIT
 */
class WebHook extends HookBase {

    /**
     * @param array{webhook: ?string} $params
     *
     * @return void
     * @throws DebugBarException
     */
    public static function execute(array $params = ["webhook" => null]): void {
        if (!parent::validateParams($params, ["webhook"])) {
            return;
        }

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
