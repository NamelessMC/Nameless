<?php

class CravatarAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Cravatar';
        $this->_base_url = 'https://cravatar.eu/';
        $this->_perspectives_map = [
            'face' => 'helmavatar',
            'head' => 'helmhead',
        ];
    }

    public function getUrlToFormat(string $perspective):  string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{x}/{y}.png';
    }
}