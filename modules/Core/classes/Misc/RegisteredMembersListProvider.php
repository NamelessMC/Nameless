<?php
/**
 * Registered members member list provider
 *
 * @package Modules\Core\Misc
 * @author Aberdener
 * @version 2.1.0
 * @license MIT
 */
class RegisteredMembersListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'registered_members';
        $this->_friendly_name = $language->get('general', 'registered_members');
        $this->_module = 'Core';
        $this->_icon = 'user icon';
        $this->_display_on_overview = false;
    }

    protected function generator(): array {
        return [
            'SELECT id FROM nl2_users ORDER BY username',
            'id',
        ];
    }
}
