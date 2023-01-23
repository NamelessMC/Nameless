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
        } else if ($data['event'] == 'userGroupAdded') {
            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'description' => $data['language']->get('user', 'group_has_been_added', ['group' => "`" . $data['group_name'] . "`", 'user' => Output::getClean($data['username'])]),
            ]];

            $params['format'] = $format;
        } else if ($data['event'] == 'userGroupRemoved') {
            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'description' => $data['language']->get('user', 'group_has_been_removed', ['group' => "`" . $data['group_name'] . "`", 'user' => Output::getClean($data['username'])]),
            ]];

            $params['format'] = $format;
        }

        // TODO: createReport?

        return $params;
    }
}
