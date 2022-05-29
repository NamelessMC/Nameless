<?php
/**
 * Built-in NamelessMC Avatar class
 *
 * @package Modules\Core\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr12
 * @license MIT
 */
class NamelessMCAvatarSource extends AvatarSourceBase {

    public function __construct(Language $language) {
        $this->_name = 'Nameless';
        $this->_base_url = $language->get('admin', 'built_in_avatars');
        $this->_perspectives_map = [
            'face' => '' // Don't need to provide any mapping here, just using this map for the dropdown in staffcp.
        ];
    }

    public function getUrlToFormat(string $perspective): string {
        if (defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) {
            return URL::build('/avatar/{identifier}');
        }

        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u={identifier}&s={size}';
    }
}
