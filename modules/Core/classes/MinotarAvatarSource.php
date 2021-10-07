<?php

class MinotarAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Minotar';
        $this->_base_url = 'https://minotar.net/';
        $this->_perspectives_map = [
            'face' => 'helm',
            'head' => 'cube',
        ];
    }

    public function getUrlToFormat(string $perspective): string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{x}/{y}.png';
    }
}