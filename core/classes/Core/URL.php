<?php
/**
 * Helps build URLs which match the site's URL configuration.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class URL {

    /**
     * Returns a URL in the correct format (friendly or not).
     *
     * @param string $url Contains the URL which will be formatted.
     * @param string $params Contains string with URL parameters.
     * @param ?string $force Determines whether to force a URL type (optional, can be either "friendly" or "non-friendly").
     *
     * @return string Assembled URL, false on failure.
     */
    public static function build(string $url, string $params = '', ?string $force = null): string {
        if (is_null($force)) {
            if ((defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) || (!defined('FRIENDLY_URLS') && Config::get('core/friendly') == true)) {
                // Friendly URLs are enabled
                return self::buildFriendly($url, $params);
            }

            // Friendly URLs are disabled, we need to change it
            return self::buildNonFriendly($url, $params);
        }

        if ($force === 'friendly') {
            return self::buildFriendly($url, $params);
        }

        if ($force === 'non-friendly') {
            return self::buildNonFriendly($url, $params);
        }

        throw new InvalidArgumentException('Invalid force string: ' . $force);
    }

    /**
     * Returns a friendly URL.
     * Internal class use only. All external calls should use `build()`.
     *
     * @param string $url Contains the URL which will be formatted
     * @param string $params URL paramaters to append to end.
     * @return string Assembled URL.
     */
    private static function buildFriendly(string $url, string $params): string {
        // Check for params
        if ($params != '') {
            $params = '?' . $params;
        }

        return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . $url . ((substr($url, -1) == '/') ? '' : '/') . $params;
    }

    /**
     * Returns a non-friendly URL.
     * Internal class use only. All external calls should use `build()`.
     *
     * @param string $url Contains the URL which will be formatted
     * @param string $params URL paramaters to append to end.
     * @return string Assembled URL.
     */
    private static function buildNonFriendly(string $url, string $params): string {
        if ($params != '') {
            return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/index.php?route=' . $url . ((substr($url, -1) == '/') ? '' : '/') . '&' . $params;
        }

        return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/index.php?route=' . $url . ((substr($url, -1) == '/') ? '' : '/');
    }
}
