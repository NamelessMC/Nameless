<?php
/**
 * MCHeads avatar source class
 *
 * @package Modules\Core\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr12
 * @license MIT
 */
class MCHeadsAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'MC-Heads';
        $this->_base_url = 'https://mc-heads.net/';
        $this->_perspectives_map = [
            'face' => 'avatar',
            'head' => 'head'
        ];
    }

    public function getUrlToFormat(string $perspective): string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{identifier}/{size}';
    }
}
