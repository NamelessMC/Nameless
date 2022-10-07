<?php
/**
 * Cravatar avatar source class
 *
 * @package Modules\Core\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr12
 * @license MIT
 */
class CravatarAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Cravatar';
        $this->_base_url = 'https://cravatar.eu/';
        $this->_perspectives_map = [
            'face' => 'helmavatar',
            'head' => 'helmhead',
        ];
    }

    public function getUrlToFormat(string $perspective): string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{identifier}/{size}.png';
    }
}
