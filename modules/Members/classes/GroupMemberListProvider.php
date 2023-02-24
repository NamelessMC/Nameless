<?php

class GroupMemberListProvider extends MemberListProvider {

    private int $_group_id;

    public function forGroup(int $group_id): self {
        $this->_group_id = $group_id;
        return $this;
    }

    protected function generateMembers(): array {
        return [
            "SELECT DISTINCT(nl2_users.id) AS id, nl2_users.username FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE group_id = {$this->_group_id} ORDER BY nl2_users.username",
            'id',
        ];
    }
}
