<?php

/**
 * Highest reaction scores member list provider
 *
 * @package Modules\Forum
 * @author Aberdener
 * @version 2.1.0
 * @license MIT
 */
class HighestForumReactionScoresMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'highest_forum_reaction_scores';
        $this->_friendly_name = $language->get('forum', 'highest_reaction_scores');
        $this->_module = 'Forum';
        $this->_icon = 'star icon';
    }

    protected function generator(): array {
        return [
            <<<SQL
            SELECT 
                (SUM(IF(r.type = 2, 1, 0)) - SUM(IF(r.type = 0, 1, 0)) + SUM(IF(r.type = 3, r.custom_score, 0))) AS `count`,
                fr.user_received
            FROM
                nl2_forums_reactions fr
            JOIN nl2_reactions r ON r.id = fr.reaction_id
            WHERE
                r.enabled = TRUE AND r.type IN (0, 2, 3)
            GROUP BY
                fr.user_received
            ORDER BY
                `count` DESC;
            SQL,
            'user_received',
            'count'
        ];
    }
}
