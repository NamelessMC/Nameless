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
        }

        return $params;
    }
}