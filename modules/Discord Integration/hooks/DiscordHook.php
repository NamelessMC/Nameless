<?php
/**
 * Discord webhook handler class
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @version 2.3.0
 * @license MIT
 */
class DiscordHook implements WebhookDispatcher {

    public static function execute(AbstractEvent $event, string $webhook_url): void {
        if (!$event instanceof DiscordDispatchable) {
            return;
        }

        $format_event = new DiscordWebhookFormatterEvent(
            $event::name(),
            $event->toDiscordWebhook(),
            $event->params(),
        );
        EventHandler::executeEvent($format_event);

        $format = $format_event->format;
        if ($format instanceof DiscordWebhookBuilder) {
            $format = $format->toArray();
        }

        $json = json_encode($format, JSON_UNESCAPED_SLASHES);

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
