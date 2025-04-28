<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0 pre-13
 *
 *  Mentions hook for pre-create/edit event for Core module
 */

class MentionsHook extends HookBase {

    private const USER_BBCODE_REGEX = '/\[user\](.*?)\[\/user\]/ism';

    private static array $_cache = [];

    /**
     * Called before content is persisted to the database.
     */
    public static function preCreate(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                $params['alert_url'] ?? null,
                $params['mention_notification_type'] ?? null,
                $params['mention_notification_title'] ?? null,
            );
        }

        return $params;
    }

    /**
     * Called before content is edited in the database.
     */
    public static function preEdit(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
            );
        }

        return $params;
    }

    /**
     * Parses the [user] tags in a post and replaces them with a link to the user's profile.
     * e.g. [user]1[/user] would instead become <a href="profile/username">@username</a>
     *
     * @param array $params
     * @return array
     */
    public static function parsePost(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = preg_replace_callback(
                self::USER_BBCODE_REGEX,
                static function (array $match) {
                    $userId = $match[1];
                    $userData = self::getUserData($userId);

                    if ($userData === null) {
                        return '@' . (new Language('core', LANGUAGE))->get('general', 'deleted_user');
                    }

                    [$userId, $userStyle, $userNickname, $userProfileUrl] = $userData;
                    return '<a href="' . $userProfileUrl . '" data-poload="' . URL::build('/queries/user/', 'id=' . $userId) . '" class="user-mention" style="' . $userStyle . '">@' . Output::getClean($userNickname) . '</a>';
                },
                $params['content']
            );
        }

        return $params;
    }

    /**
     * Parses the [user] tags in a post and replaces them with plain mention text.
     * e.g. [user]1[/user] would instead become @username
     *
     * @param array $params
     * @return array
     */
    public static function stripPost(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = preg_replace_callback(
                self::USER_BBCODE_REGEX,
                static function (array $match) {
                    $userId = $match[1];
                    $userData = self::getUserData($userId);

                    if ($userData === null) {
                        return '@' . (new Language('core', LANGUAGE))->get('general', 'deleted_user');
                    }

                    $nickname = $userData[2];

                    return '@' . Output::getClean($nickname);
                },
                $params['content']
            );
        }

        return $params;
    }

    /**
     * Get cached user data or fetch and cache if not exists
     *
     * @param string $userId User ID to look up
     * @return array Array containing [userId, userStyle, userNickname, userProfileUrl] or null if user doesn't exist
     */
    private static function getUserData(string $userId): ?array {
        if (isset(self::$_cache[$userId])) {
            return self::$_cache[$userId];
        }

        $user = new User($userId);
        if (!$user->exists()) {
            return null;
        }

        $userData = [
            $user->data()->id,
            $user->getGroupStyle(),
            $user->data()->nickname,
            $user->getProfileURL()
        ];

        return self::$_cache[$userId] = $userData;
    }

    private static function validate(array $params): bool {
        return parent::validateParams($params, ['content', 'user']);
    }
}
