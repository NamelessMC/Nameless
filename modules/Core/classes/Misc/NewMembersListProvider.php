<?php

class NewMembersListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'new_members';
        $this->_friendly_name = $language->get('general', 'new_members');
        $this->_module = 'Core';
        $this->_icon = 'user plus icon';
    }

    protected function generateMembers(): array {
        $query = DB::getInstance()->query('SELECT id FROM nl2_users ORDER BY joined DESC')->results();

        $members = [];
        foreach ($query as $result) {
            $members[] = new User($result->id);
        }

        return $members;
    }
}
