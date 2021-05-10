<?php

class VisageAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Visage';
        $this->_base_url = 'https://visage.surgeplay.com/';
        $this->_perspectives_map = [
            'face' => 'face',
            'head' => 'head',
            'bust' => 'bust'
        ];
    }

    public function getUrlToFormat($perspective) {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{y}/{x}';
    }
}