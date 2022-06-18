<?php
/**
 * Abstract class for modules create custom avatar sources.
 *
 * @package NamelessMC\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr10
 * @license MIT
 */
abstract class AvatarSourceBase {

    protected string $_name;

    /**
     * Base URL all avatars from this source will add on to.
     */
    protected string $_base_url = '';

    /**
     * A map of `NamelessMC perspective name` => `Avatar source route`,
     * as not all avatar sources will have the same name, and subsequentally route, for each perspective.
     */
    protected array $_perspectives_map = [];

    /**
     * Get the name of this avatar source.
     *
     * @return string Name of this avatar source.
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get base url of this avatar source.
     *
     * @return string Base url of this source.
     */
    public function getBaseUrl(): string {
        return $this->_base_url;
    }

    /**
     * Get "NamelessMC names" of supported perspectives for this avatar source.
     *
     * @return array Array of perspective names.
     */
    public function getPerspectives(): array {
        return array_keys($this->_perspectives_map);
    }

    /**
     * Get the URL for this users avatar.
     *
     * @param string $uuid UUID of avatar to get.
     * @param string $perspective Perspective to render avatar with.
     * @param int $size Size in pixels to render avatar at. Default 128
     *
     * @return string Compiled URL of avatar image.
     */
    public function getAvatar(string $uuid, string $perspective, int $size = 128): string {
        return $this->formatUrl($this->getUrlToFormat($perspective), $uuid, $size);
    }

    /**
     * Replace placeholders in raw url with uuid and size of requested avatar.
     *
     * @param string $url_to_format Raw url to replace placeholders in.
     * @param string $uuid uuid (or username, yuck!) of avatar to get.
     * @param int $size Size of avatar image in pixels to get.
     *
     * @return string Formatted url.
     */
    public function formatUrl(string $url_to_format, string $uuid, int $size): string {
        return str_replace(
            ['{identifier}', '{size}'],
            [$uuid, $size],
            $url_to_format
        );
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
    abstract public function getUrlToFormat(string $perspective): string;

    /**
     * Translate NamelessMC perspective name to the relative name for this avatar source.
     *
     * @param string $perspective NamelessMC perspective name to translate.
     * @return string Translated perspective name.
     * @throws InvalidArgumentException When an invalid perspective is passed.
     */
    public function getRelativePerspective(string $perspective): string {
        $perspective = strtolower($perspective);
        if (isset($this->_perspectives_map[$perspective])) {
            return $this->_perspectives_map[$perspective];
        }

        $class = static::class;
        throw new InvalidArgumentException("Attempted to get invalid perspective of: {$perspective} on {$class}");
    }
}
