<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.3.0
 *
 *  Mentions hook for pre-create/edit event for Core module
 */

class MentionsHook extends HookBase {

    private static array $_cache = [];

    public static function preCreate(AbstractEvent $event): void {
        if (!empty($event->content) && isset($event->user)) {
            $event->content = MentionsParser::parse(
                $event->user->data()->id,
                $event->content,
                empty($event->alert_url) ? null : $event->alert_url,
                empty($event->alert_short) ? null : $event->alert_short,
                empty($event->alert_full) ? null : $event->alert_full
            );
        }
    }

    public static function preEdit(AbstractEvent $event): void {
        if (!empty($event->content) && isset($event->user)) {
            $event->content = MentionsParser::parse(
                $event->user->data()->id,
                $event->content
            );
        }
    }

    public static function parsePost(AbstractEvent $event): void {
        if (!empty($event->content)) {
            $event->content = preg_replace_callback(
                '/\[user\](.*?)\[\/user\]/ism',
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
                $event->content
            );
        }
    }

    public static function stripPost(AbstractEvent $event): void {
        if (!empty($event->content)) {
            self::stripContent($event->content);
        }
    }

    /**
     * Strips [user] tags and parses the ID to username
     * e.g. [user]1[/user] would instead become (at)Username
     *
     * @param string $content
     * @return string
     */
    public static function stripContent(string $content): string {
        return preg_replace_callback(
            '/\[user\](.*?)\[\/user\]/ism',
            static function (array $match) {
                if (isset(MentionsHook::$_cache[$match[1]])) {
                    $userNickname = MentionsHook::$_cache[$match[1]][2];
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

                return '@' . Output::getClean($userNickname);
            },
            $content
        );
    }
}
