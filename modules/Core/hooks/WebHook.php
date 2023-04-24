<?php
/**
 * Webhook handler class
 *
 * @package NamelessMC\Events
 * @author Partydragen
 * @version 2.2.0
 * @license MIT
 */
class WebHook implements WebhookDispatcher {

    public static function execute($event, string $webhook_url = ''): void {
        if ($event instanceof HasWebhookParams) {
            $params = $event->webhookParams();
            if (!isset($params['event'])) {
                $params['event'] = $event::name();
            }
        } else if ($event instanceof AbstractEvent) {
            ErrorHandler::logWarning('Event ' . $event::name() . ' does not implement HasWebhookParams, using `params()` instead');
            $params = $event->params();
        } else {
            $params = $event;
        }

        $webhook_url = $event instanceof AbstractEvent
            ? $webhook_url
            : $params['webhook'];

        $return = $params;
        unset($return['webhook']);

        $json = json_encode($return, JSON_UNESCAPED_SLASHES);

        $httpClient = HttpClient::post($webhook_url, $json, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($httpClient->hasError()) {
            trigger_error($httpClient->getError());
        }
    }
}
