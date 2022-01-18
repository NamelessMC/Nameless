<?php

class CraftheadAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Crafthead';
        $this->_base_url = 'https://crafthead.net/';
        $this->_perspectives_map = [
            'face' => 'helm',
            'head' => 'cube'
        ];
    }

    public function getUrlToFormat(string $perspective): string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{x}/{y}';
    }
}
