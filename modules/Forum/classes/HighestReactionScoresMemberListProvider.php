<?php

class HighestReactionScoresMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'highest_reaction_scores';
        $this->_friendly_name = $language->get('forum', 'highest_reaction_scores');
        $this->_module = 'Forum';
        $this->_icon = 'star icon';
    }

    protected function generateMembers(): array {
        $members = [];

        $query = DB::getInstance()->query('SELECT COUNT(user_received) AS `count`, user_received FROM nl2_forums_reactions WHERE reaction_id = 1 GROUP BY user_received ORDER BY `count` DESC');
        foreach ($query->results() as $result) {
            $members[] = [new User($result->user_received), $result->count];
        }

        return $members;
    }
}
