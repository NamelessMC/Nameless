<?php

class InitialsAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Initials';
        $this->_module = 'Core';
        $this->_can_be_disabled = false;
    }

    public function get(User $user): string {
        return "https://api.dicebear.com/5.x/initials/png?seed={$user->data()->username}&size={$this->size}";
    }
}
