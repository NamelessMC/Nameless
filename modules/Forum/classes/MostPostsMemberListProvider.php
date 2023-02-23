<?php

class MostPostsMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'most_posts';
        $this->_friendly_name = $language->get('forum', 'most_posts');
        $this->_module = 'Forum';
        $this->_icon = 'sort numeric up icon';
    }

    protected function generateMembers(): array {
        return [
            'SELECT post_creator, COUNT(post_content) AS `count` FROM nl2_posts GROUP BY post_creator ORDER BY `count` DESC',
            'post_creator',
            'count'
        ];
    }
}
