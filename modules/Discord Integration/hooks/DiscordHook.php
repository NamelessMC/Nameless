<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Discord webhook handler class
 */

// TODO: Let events define a function to build a discord embed for the webhook
class DiscordHook {

    public static function execute(array $params = []): void {
        // Ensure hook is compatible
        $return = [];
        if ($params['event'] == 'registerUser') {
            $return['username'] = SITE_NAME;
            $return['content'] = '';
            $return['embeds'] = [[
                'author' => [
                    'name' => Output::getClean($params['username']),
                    'url' => $params['url'],
                    'icon_url' => $params['avatar_url']
                ],
                'description' => $params['language']->get('user', 'user_x_has_registered', ['user' => Output::getClean($params['username']), 'siteName' => SITE_NAME])
            ]];
        } else if ($params['event'] == 'createAnnouncement') {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['message']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $return['username'] = $params['username'] . ' | ' . SITE_NAME;
            $return['avatar_url'] = $params['avatar_url'];
            $return['embeds'] = [[
                'title' => 'New Announcement: ' . $params['header'],
                'description' => $content,
            ]];
        } else {
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
        }

        if (isset($params['color']) && $params['color'] != null) {
            $return['embeds'][0]['color'] = hexdec($params['color']);
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
