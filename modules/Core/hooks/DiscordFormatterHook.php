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
        $data = $params['data'];
        $format = $params['format'];

        if ($data['event'] == 'registerUser') {
            $format['username'] = SITE_NAME;
            $format['content'] = '';
            $format['embeds'] = [[
                'author' => [
                    'name' => Output::getClean($data['username']),
                    'url' => $data['url'],
                    'icon_url' => $data['avatar_url']
                ],
                'description' => $data['language']->get('user', 'user_x_has_registered', ['user' => Output::getClean($data['username']), 'siteName' => SITE_NAME])
            ]];

            $params['format'] = $format;
        } else if ($data['event'] == 'createAnnouncement') {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $data['message']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'title' => 'New Announcement: ' . $data['header'],
                'description' => $content,
            ]];

            $params['format'] = $format;
        }

        return $params;
    }
}