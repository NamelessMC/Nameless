<?php

class RegisteredMembersListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'registered_members';
        $this->_friendly_name = $language->get('general', 'registered_members');
        $this->_module = 'Core';
        $this->_icon = 'user icon';
        $this->_display_on_overview = false;
    }

    protected function generateMembers(): array {
        return [
            'SELECT id FROM nl2_users ORDER BY username',
            'id',
        ];
    }
}
