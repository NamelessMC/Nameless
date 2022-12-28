<?php
declare(strict_types=1);

use GuzzleHttp\Exception\GuzzleException;

/**
 * Mentions hook for pre-create/edit event for Core module
 *
 * @package Core\Hooks
 * @author Samerton
 * @version 2.0.0 pre-13
 * @license MIT
 */
class MentionsHook extends HookBase {

    private static array $_cache = [];

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, string>
     * @throws GuzzleException
     */
    public static function preCreate(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                $params['alert_url'] ?: null,
                $params['alert_short'] ?: null,
                $params['alert_full'] ?: null,
            );
        }

        return $params;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, string>
     * @throws GuzzleException
     */
    public static function preEdit(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                URL::build('/forum/topic/' . urlencode($params['topic_id']), 'pid=' . urlencode($params['post_id']))
            );
        }

        return $params;
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     *
     * @throws GuzzleException
     */
    public static function parsePost(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
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
        }

        return $params;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return bool
     */
    private static function validate(array $params): bool {
        return parent::validateParams($params, ['content', 'user']);
    }
}
