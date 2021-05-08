<?php
/*
 *	Made by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  AvatarSourceBase class
 */

abstract class AvatarSourceBase {

    /**
     * Name for this avatar service to be shown to user in StaffCP.
     */
    public $_name;

    /**
     * Base URL all avatars from this source will add on to.
     */
    protected $_base_url = '';

    /**
     * A map of `NamelessMC perspective name` => `Avatar source route`, 
     * as not all avatar sources will have the same name, and subsequentally route, for each perspective.
     */
    protected array $_perspectives_map = array();

    /**
     * Get the URL for this users avatar.
     * 
     * @param string $uuid UUID of avatar to get.
     * @param string $perspective Perspective to render avatar with.
     * @param int|null $size Size in pixels to render avatar at. Default 128
     * @return string Compiled URL of avatar image.
     */
    public function getAvatar($uuid, $perspective, $size = 128) {
        return $this->formatUrl($this->getUrlToFormat($perspective), $uuid, $size);
    }

    /**
     * Get raw URL with placeholders to format.
     * {x} = UUID / username
     * 
     * {y} = size in pixels
     * 
     * @param string $perspective Perspective to use in url.
     * @return string URL with placeholders to format.
     */
    abstract function getUrlToFormat($perspective);

    /**
     * Translate NamelessMC perspective name to the relative name for this avatar source.
     * 
     * @param string $perspective NamelessMC perspective name to translate.
     * @return string Translated perspective name.
     */
    public function getRelativePerspective($perspective) {
        return $this->_perspectives_map[$perspective];
    }

    public function formatUrl($url_to_format, $uuid, $size) {
        return str_replace(['{x}', '{y}'], [$uuid, $size], $url_to_format);
    }
}