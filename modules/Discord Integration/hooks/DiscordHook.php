<?php
declare(strict_types=1);

use DebugBar\DebugBarException;

/**
 * @package Modules\Core\Integrations
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class DiscordHook {

    /**
     * @param array $params
     * @return void
     *
     * @throws DebugBarException
     */
    public static function execute(array $params = []): void {
        $return = EventHandler::executeEvent('discordWebhookFormatter', ['format' => [], 'data' => $params])['format'];

        if (!is_array($return) || !count($return)) {
            $return = [];

            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['content_full']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $return['username'] = $params['username'] . ' | ' . SITE_NAME;
            $return['avatar_url'] = $params['avatar_url'];
            $return['embeds'] = [[
                'title' => $params['title'],
                'description' => $content,
                'url' => $params['url'],
                'footer' => ['text' => $params['content']]
            ]];

            if (isset($params['color'])) {
                $return['embeds'][0]['color'] = hexdec($params['color']);
            }
        }

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
