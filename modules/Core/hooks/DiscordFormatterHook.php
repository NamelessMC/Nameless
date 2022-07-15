<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0 pre-13
 *
 *  Discord formatter hook
 */

class DiscordFormatterHook extends HookBase {

    public static function format(array $params = []): array {
        if ($params['data']['event'] == 'registerUser') {
            $format = $params['format'];

            $format['username'] = SITE_NAME;
            $format['content'] = '';
            $format['embeds'] = [[
                'author' => [
                    'name' => Output::getClean($params['data']['username']),
                    'url' => $params['data']['url'],
                    'icon_url' => $params['data']['avatar_url']
                ],
                'description' => $params['data']['language']->get('user', 'user_x_has_registered', ['user' => Output::getClean($params['data']['username']), 'siteName' => SITE_NAME])
            ]];

            $params['format'] = $format;
        } else if ($params['data']['event'] == 'createAnnouncement') {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['data']['message']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $return['username'] = $params['data']['username'] . ' | ' . SITE_NAME;
            $return['avatar_url'] = $params['data']['avatar_url'];
            $return['embeds'] = [[
                'title' => 'New Announcement: ' . $params['data']['header'],
                'description' => $content,
            ]];

            $params['format'] = $format;
        }

        return $params;
    }
}