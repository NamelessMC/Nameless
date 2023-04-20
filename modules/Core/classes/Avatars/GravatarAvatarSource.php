<?php

class GravatarAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Gravatar';
        $this->_module = 'Core';
    }

    public function get(User $user): ?string {
        if (!$user->data()->gravatar) {
            return null;
        }

        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->data()->email))) . "?s={$this->_size}";
    }
}
