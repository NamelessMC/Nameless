<?php

class InitialsAvatarSource extends AvatarSourceBase {

    public function __construct(Language $language) {
        $this->_name = $language->get('admin', 'avatar_source_initials');
        $this->_module = 'Core';
        $this->_can_be_disabled = false;
    }

    public function get(User $user): string {
        // Use PNG when full URL is enabled, so it displays properly in Discord embeds,
        // otherwise use SVG for slightly better speeds
        $type = $this->_full_url ? 'png' : 'svg';

        return "https://api.dicebear.com/5.x/initials/{$type}?seed={$user->data()->username}&size={$this->_size}";
    }
}
