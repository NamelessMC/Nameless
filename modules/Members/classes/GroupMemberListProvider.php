<?php

class GroupMemberListProvider extends MemberListProvider {

    private int $_group_id;

    public function __construct(int $group_id) {
        $this->_group_id = $group_id;
    }

    protected function generator(): array {
        return [
            "SELECT nl2_users.id AS id, nl2_users.username FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE nl2_users_groups.group_id = {$this->_group_id} ORDER BY nl2_users.username",
            'id',
        ];
    }
}
