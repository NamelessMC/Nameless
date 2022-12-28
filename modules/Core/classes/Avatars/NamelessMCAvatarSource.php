<?php
declare(strict_types=1);

/**
 * Built-in NamelessMC Avatar class
 *
 * @package Modules\Core\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr12
 * @license MIT
 */
class NamelessMCAvatarSource extends AvatarSourceBase {

    /**
     * @param Language $language
     */
    public function __construct(Language $language) {
        $this->_name = 'Nameless';
        $this->_base_url = $language->get('admin', 'built_in_avatars');
        $this->_perspectives_map = [
            'face' => '' // Don't need to provide any mapping here, just using this map for the dropdown in staffcp.
        ];
    }

    /**
     * Get raw URL with placeholders to format.
     * - `{identifier} = UUID / username`
     * - `{size} = size in pixels`
     *
     * @param string $perspective Perspective to use in url.
     *
     * @return string URL with placeholders to format.
     */
    public function getUrlToFormat(string $perspective): string {
        if (defined('FRIENDLY_URLS') && FRIENDLY_URLS === true) {
            return URL::build('/avatar/{identifier}');
        }

        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u={identifier}&s={size}';
    }
}
