<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  Discord webhook handler class
 */

class DiscordHook {

    /**
     * @param AbstractEvent|array $event Event to execute, or array of params if event is not object based
     */
    public static function execute($event, string $webhook_url = ''): void {
        $params = $event instanceof AbstractEvent
                ? $event->params()
                : $event;
        $webhook_url = $event instanceof AbstractEvent
            ? $webhook_url
            : $event['webhook'];
        $format = [];

        if ($event instanceof DiscordDispatchable) {
            $format = $event->toDiscordWebook();
        }

        $return = EventHandler::executeEvent(new DiscordWebhookFormatterEvent(
            $event::name(),
            $format,
            $params,
        ))['format'];

        if ($return instanceof DiscordWebhookBuilder) {
            $return = $return->toArray();
        }

        if (!is_array($return) || !count($return)) {
            try {
                $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['content_full']));
                if (mb_strlen($content) > 512) {
                    $content = mb_substr($content, 0, 512) . '...';
                }

                // Create generic fallback embed if no embeds are provided
                $return = DiscordWebhookBuilder::make()
                    ->setUsername($params['username'] . ' | ' . SITE_NAME)
                    ->setAvatarUrl($params['avatar_url'])
                    ->addEmbed(function (DiscordEmbed $embed) use ($params, $content) {
                        return $embed
                            ->setTitle($params['title'])
                            ->setDescription($content)
                            ->setUrl($params['url'])
                            ->setFooter($params['content']);
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
