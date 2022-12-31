<?php

/**
 * Discord formatter hook
 *
 * @package Modules\Discord Integration\Hooks
 * @author Partydragen
 * @version 2.0.0 pre-13
 * @license MIT
 */
class DiscordFormatterHook extends HookBase {

    /**
     * @param array{format: array, data: array{event: ?string, username: ?string, url: ?string, avatar_url: ?string, language: ?Language, header: ?string, message: ?string, group_name: ?string}} $params
     *
     * @return array{format: array, data: array{event: ?string, username: ?string, url: ?string, avatar_url: ?string, language: ?Language, header: ?string, message: ?string, group_name: ?string}}
     */
    public static function format(array $params = ["format" => [], "data" => ["event" => null, "username" => null, "url" => null, "avatar_url" => null, "language" => null,  "header" => null, "message" => null, "group_name" => null]]): array {
        if (!parent::validateParams($params, ["data"]) && self::validateParams($params['data'], ["event", "username", "url", "avatar_url", "language", "header", "message", "group_name"])) {
            return $params;
        }

        $format = $params['format'];
        $data = $params['data'];

        if ($data['event'] === 'registerUser') {
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
        } else if ($data['event'] === 'createAnnouncement') {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $data['message']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'title' => $data['language']->get('admin', 'new_announcement') . ': ' . $data['header'],
                'description' => $content,
            ]];

            $params['format'] = $format;
        } else if ($data['event'] === 'userGroupAdded') {
            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'description' => $data['language']->get('user', 'group_has_been_added', ['group' => "`" . $data['group_name'] . "`", 'user' => Output::getClean($data['username'])]),
            ]];

            $params['format'] = $format;
        } else if ($data['event'] === 'userGroupRemoved') {
            $format['username'] = $data['username'] . ' | ' . SITE_NAME;
            $format['avatar_url'] = $data['avatar_url'];
            $format['embeds'] = [[
                'description' => $data['language']->get('user', 'group_has_been_removed', ['group' => "`" . $data['group_name'] . "`", 'user' => Output::getClean($data['username'])]),
            ]];

            $params['format'] = $format;
        }

        return $params;
    }
}