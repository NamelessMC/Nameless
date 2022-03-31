<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0 pre-13
 *
 *  Mentions hook for pre-create/edit event for Forum module
 */

class MentionsHook {

    public static function preCreate(array $params = []): array {
        if (isset($params['content']) && isset($params['post_id'])&& isset($params['topic_id'])  && isset($params['user'])) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                URL::build('/forum/topic/' . $params['topic_id'], 'pid=' . $params['post_id']),
                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag'],
                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag_info', 'replace' => '{x}', 'replace_with' => Output::getClean($params['user']->data()->nickname)]
            );
        }

        return $params;
    }

    public static function preEdit(array $params = []): array {
        if (isset($params['content']) && isset($params['post_id']) && isset($params['topic_id']) && isset($params['user'])) {
            $params['content'] = MentionsParser::parse(
                $params['user']->data()->id,
                $params['content'],
                URL::build('/forum/topic/' . $params['topic_id'], 'pid=' . $params['post_id'])
            );
        }

        return $params;
    }
}