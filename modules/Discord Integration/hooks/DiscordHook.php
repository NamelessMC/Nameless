<?php
/**
 * Discord webhook handler class
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */
class DiscordHook implements WebhookDispatcher {

    public static function execute($event, string $webhook_url = ''): void {
        $params = $event instanceof AbstractEvent
            ? $event->params()
            : $event;

        $webhook_url = $event instanceof AbstractEvent
            ? $webhook_url
            : $params['webhook'];

        $name = $event instanceof AbstractEvent
            ? $event::name()
            : $params['event'];

        $format = $event instanceof DiscordDispatchable
            ? $event->toDiscordWebhook()
            : [];

        $return = EventHandler::executeEvent(new DiscordWebhookFormatterEvent(
            $name,
            $format,
            $params,
        ))['format'];

        if (is_array($return) && isset($return['webhook'])) {
            unset($return['webhook']);
        }

        if ($return instanceof DiscordWebhookBuilder) {
            $return = $return->toArray();
        }

        if (!is_array($return) || !count($return)) {
            try {
                // Create generic fallback embed if no embeds are provided
                $return = DiscordWebhookBuilder::make()
                    ->setUsername($params['username'] . ' | ' . SITE_NAME)
                    ->setAvatarUrl($params['avatar_url'])
                    ->addEmbed(function (DiscordEmbed $embed) use ($params) {
                        return $embed
                            ->setTitle($params['title'])
                            ->setDescription(Text::embedSafe($params['content_full']))
                            ->setUrl($params['url'])
                            ->setFooter(Text::embedSafe($params['content']));
                    })
                    ->toArray();
            } catch (Exception $exception) {
                // Likely enabled discord webhook for event
                // that doesn't have valid fallback params
                ErrorHandler::logWarning("Error creating fallback Discord embed for {$event::name()}: {$exception->getMessage()}. Does it support embeds?");
                return;
            }
        }

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
