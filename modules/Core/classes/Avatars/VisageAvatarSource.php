<?php
/**
 * Visage avatar source class
 *
 * @package Modules\Core\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr12
 * @license MIT
 */
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

    public function getUrlToFormat(string $perspective): string {
        return $this->_base_url . $this->getRelativePerspective($perspective) . '/{size}/{identifier}';
    }
}
