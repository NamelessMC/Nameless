<?php
/**
 * Webhook handler class
 *
 * @package NamelessMC\Events
 * @author Partydragen
 * @version 2.3.0
 * @license MIT
 */
class WebHook implements WebhookDispatcher {

    public static function execute(AbstractEvent $event, string $webhook_url): void {
        if (!$event instanceof HasWebhookParams) {
            return;
        }

        $params = $event->webhookParams();
        if (!isset($params['event'])) {
            $params['event'] = $event::name();
        }

        $json = json_encode($params, JSON_UNESCAPED_SLASHES);

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
