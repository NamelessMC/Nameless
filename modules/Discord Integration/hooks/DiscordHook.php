<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  Discord webhook handler class
 */

class DiscordHook {

    public static function execute(AbstractEvent $event, string $webhook_url = ''): void {
        $params = $event->params();
        if ($event instanceof DiscordDispatchable) {
            $return = $event->toDiscordWebook()->toArray();
        } else {
            $return = EventHandler::executeEvent('discordWebhookFormatter', ['format' => [], 'data' => $params])['format'];
        }

        if (!is_array($return) || !count($return)) {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['content_full']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            // Create generic fallback embed if no embeds are provided
            $return = DiscordWebhookBuilder::make()
                ->username($params['username'] . ' | ' . SITE_NAME)
                ->avatarUrl($params['avatar_url'])
                ->embed(function (DiscordEmbed $embed) use ($params, $content) {
                    return $embed
                        ->title($params['title'])
                        ->description($content)
                        ->url($params['url'])
                        ->footer($params['content']);
                })
                ->toArray();
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
