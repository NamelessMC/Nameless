<?php

class MCHeadsAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'MC-Heads';
        $this->_base_url = 'https://mc-heads.net/';
        $this->_perspectives_map = [
            'face' => 'avatar',
            'head' => 'head'
        ];
    }

    public function getUrlToFormat($perspective) {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{x}/{y}';
    }
}