<?php

class CrafatarAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Crafatar';
        $this->_base_url = 'https://crafatar.com/';
        $this->_perspectives_map = [
            'face' => 'avatars',
            'head' => 'renders/head'
        ];
    }

    public function getUrlToFormat($perspective) {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{x}?size={y}&overlay';
    }
}