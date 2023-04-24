<?php

class GravatarAvatarSource extends AvatarSourceBase {

    public function __construct(Language $language) {
        $this->_name = $language->get('admin', 'avatar_source_gravatar');
        $this->_module = 'Core';
    }

    public function get(User $user): ?string {
        if (!$user->data()->gravatar) {
            return null;
        }

        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->data()->email))) . "?s={$this->_size}";
    }
}
