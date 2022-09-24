<?php

class StaffMembersListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'staff_members';
        $this->_friendly_name = $language->get('general', 'staff_members');
        $this->_module = 'Core';
        $this->_icon = 'user secret icon';
    }

    protected function generateMembers(): array {
        $query = DB::getInstance()->query('SELECT u.id, u.username FROM nl2_users u INNER JOIN nl2_users_groups ug ON u.id = ug.user_id INNER JOIN nl2_groups g ON ug.group_id = g.id WHERE g.staff = 1 GROUP BY u.username, u.id ORDER BY u.username')->results();

        $members = [];
        foreach ($query as $result) {
            $members[] = new User($result->id);
        }

        return $members;
    }
}
