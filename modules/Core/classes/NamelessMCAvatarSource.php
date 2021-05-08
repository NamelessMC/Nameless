<?php

class NamelessMCAvatarSource extends AvatarSourceBase {

    public function __construct(Language $language) {
        $this->_name = 'Nameless';
        $this->_base_url = $language->get('admin', 'built_in_avatars');
        $this->_perspectives_map = [
            'face' => '' // Dont need to provide any mapping here, just using this map for the dropdown in staffcp.
        ];
    }

    public function getUrlToFormat($perspective) {
        if (defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) {
            return URL::build('/avatar/{x}');
        }

        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u={x}';
    }
}