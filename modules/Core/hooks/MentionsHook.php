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
     * - Replaces @mentions with [user] tags.
     * - Sends notifications to mentioned users.
     */
    public static function preCreate(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parseAndNotify(
                $params['user']->data()->id,
                $params['content'],
                $params['alert_url'],
                $params['mention_notification_type'],
                $params['mention_notification_title'],
            );
        }

        return $params;
    }

    /**
     * Called before content is edited in the database.
     * - Replaces @mentions with [user] tags.
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
            $params['content'] = self::processUserTags(
                $params['content'],
                static function (array $userData) {
                    [$userId, $userStyle, $userNickname, $userProfileUrl] = $userData;
                    return '<a href="' . $userProfileUrl . '" data-poload="' . URL::build('/queries/user/', 'id=' . $userId) . '" class="user-mention" style="' . $userStyle . '">@' . Output::getClean($userNickname) . '</a>';
                }
            );
        }

        return $params;
    }

    /**
     * Parses the [user] tags in a post and replaces them with plain text, no links.
     * e.g. [user]1[/user] would instead become @username
     *
     * @param array $params
     * @return array
     */
    public static function stripPost(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = self::processUserTags(
                $params['content'],
                static function (array $userData) {
                    return '@' . Output::getClean($userData[2]); // userData[2] is userNickname
                }
            );
        }

        return $params;
    }

    /**
     * Shared helper method to process user BBCode tags with a custom formatter
     *
     * @param string $content The content to process
     * @param callable $formatter Function to format the user data
     * @return string
     */
    private static function processUserTags(string $content, callable $formatter): string {
        return preg_replace_callback(
            self::USER_BBCODE_REGEX,
            static function (array $match) use ($formatter) {
                $userId = $match[1];
                $userData = self::getUserData($userId);

                if ($userData === null) {
                    return '@' . (new Language('core', LANGUAGE))->get('general', 'deleted_user');
                }

                return $formatter($userData);
            },
            $content
        );
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
