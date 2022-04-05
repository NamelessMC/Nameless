<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0 pre-13
 *
 *  Mentions hook for pre-create/edit event for Forum module
 */

class MentionsHook extends HookBase {

    public static function preCreate(array $params = []): array {
        if (self::validate($params)) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                URL::build('/forum/topic/' . urlencode($params['topic_id']), 'pid=' . urlencode($params['post_id'])),
                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag'],
                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag_info', 'replace' => '{{author}}', 'replace_with' => Output::getClean($params['user']->data()->nickname)]
            );
        }

        return $params;
    }

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

    public static function parsePost(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = preg_replace_callback(
                '/\[user\](.*?)\[\/user\]/ism',
                function($match) {
                    $user = new User($match[1]);
                    return '<a href="' . $user->getProfileURL() . '" data-poload="' . URL::build('/queries/user/', 'id=' . $user->data()->id) . '" class="user-mention" style="' . $user->getGroupClass() . '">@' . Output::getClean($user->data()->nickname) . '</a>';
                },
                $params['content']
            );
        }

        return $params;
    }

    private static function validate(array $params): bool {
        return parent::validateParams($params, ['content', 'post_id', 'topic_id', 'user']);
    }
}
