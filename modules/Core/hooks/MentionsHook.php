<?php

use GuzzleHttp\Exception\GuzzleException;

/**
 * Mentions hook for pre-create/edit event for Core module
 *
 * @package Modules\Core\Hooks
 * @author Samerton
 * @version 2.0.0 pre-13
 * @license MIT
 */
class MentionsHook extends HookBase {

    private static array $_cache = [];

    /**
     * @param array{user: ?User, content: ?string, alert_url: ?string, alert_short: ?string, alert_full: ?string} $params
     *
     * @return array{user: ?User, content: ?string, alert_url: ?string, alert_short: ?string, alert_full: ?string}
     * @throws GuzzleException
     */
    public static function preCreate(array $params = ["user" => null, "content" => null, "alert_url" => null, "alert_short" => null, "alert_full" => null]): array {
        if (!parent::validateParams($params, ["user", "content"])) {
            return $params;
        }

        $params['content'] = MentionsParser::parse(
            $params['user']->data()->id,
            $params['content'],
            $params['alert_url'] ?: null,
            $params['alert_short'] ?: null,
            $params['alert_full'] ?: null,
        );

        return $params;
    }

    /**
     * @param array{user: ?User, content: ?string, topic_id: ?string, post_id: ?string} $params
     *
     * @return array{user: ?User, content: ?string, topic_id: ?string, post_id: ?string}
     * @throws GuzzleException
     */
    public static function preEdit(array $params = ["user" => null, "content" => null, "topic_id" => null, "post_id" => null]): array {
        if (!parent::validateParams($params, ["user", "content", "topic_id", "post_id"])) {
            return $params;
        }

        $params['content'] = MentionsParser::parse(
            $params['user']->data()->id,
            $params['content'],
            URL::build('/forum/topic/' . urlencode($params['topic_id']), 'pid=' . urlencode($params['post_id']))
        );

        return $params;
    }

    /**
     * @param array{content: ?string} $params
     *
     * @return array{content: ?string}
     * @throws GuzzleException
     */
    public static function parsePost(array $params = ["content" => null]): array {
        if (!parent::validateParams($params, ["content"])) {
            return $params;
        }

        $params['content'] = preg_replace_callback(
            '/\[user](.*?)\[\/user]/ism',
            static function (array $match) {
                if (isset(MentionsHook::$_cache[$match[1]])) {
                    [$userId, $userStyle, $userNickname, $userProfileUrl] = MentionsHook::$_cache[$match[1]];
                } else {
                    $user = new User($match[1]);

                    if (!$user->exists()) {
                        return '@' . (new Language('core', LANGUAGE))->get('general', 'deleted_user');
                    }

                    $userId = $user->data()->id;
                    $userStyle = $user->getGroupStyle();
                    $userNickname = $user->data()->nickname;
                    $userProfileUrl = $user->getProfileURL();

                    MentionsHook::$_cache[$match[1]] = [$userId, $userStyle, $userNickname, $userProfileUrl];
                }

                return '<a href="' . $userProfileUrl . '" data-poload="' . URL::build('/queries/user/', 'id=' . $userId) . '" class="user-mention" style="' . $userStyle . '">@' . Output::getClean($userNickname) . '</a>';
            },
            $params['content']
        );

        return $params;
    }
}
