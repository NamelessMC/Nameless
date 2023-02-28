<?php

/**
 * Highest reaction scores member list provider
 *
 * @package Modules\Forum
 * @author Aberdener
 * @version 2.1.0
 * @license MIT
 */
class HighestReactionScoresMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'highest_reaction_scores';
        $this->_friendly_name = $language->get('forum', 'highest_reaction_scores');
        $this->_module = 'Forum';
        $this->_icon = 'star icon';
    }

    protected function generator(): array {
        return [
            'SELECT COUNT(fr.user_received) AS `count`, fr.user_received FROM nl2_forums_reactions fr JOIN nl2_reactions r ON r.id = fr.reaction_id WHERE r.type = 2 GROUP BY fr.user_received ORDER BY `count` DESC',
            'user_received',
            'count'
        ];
    }
}
