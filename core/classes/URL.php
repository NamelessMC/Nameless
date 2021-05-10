<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  URL class
 */

class URL {

    /**
     * Returns a URL in the correct format (friendly or not).
     *
     * @param string $url Contains the URL which will be formatted.
     * @param string $params Contains string with URL parameters.
     * @param string $force Determines whether or not to force a URL type (optional, can be either "friendly" or "non-friendly").
     * @return string|bool Assembled URL, false on failure.
     */
    public static function build($url, $params = '', $force = null) {
        if (is_null($force)) {
            if ((defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) || (!defined('FRIENDLY_URLS') && Config::get('core/friendly') == true)) {
                // Friendly URLs are enabled
                return self::buildFriendly($url, $params);
            } else {
                // Friendly URLs are disabled, we need to change it
                return self::buildNonFriendly($url, $params);
            }
        }

        if ($force == 'friendly') {
            return self::buildFriendly($url, $params);
        } else if ($force == 'non-friendly') {
            return self::buildNonFriendly($url, $params);
        } else {
            return false;
        }
    }

    /**
     * Returns a friendly URL.
     * Internal class use only. All external calls should use build().
     *
     * @param string $url Contains the URL which will be formatted
     * @param string $params URL paramaters to append to end.
     * @return string Assembled URL.
     */
    private static function buildFriendly($url, $params) {
        // Check for params
        if ($params != '' || $params === true) {
            if ($params === true) {
                $params = '';
            }

            $params = '?' . $params;
        }

        return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . $url . ((substr($url, -1) == '/') ? '' : '/') . $params;
    }

    /**
     * Returns a non-friendly URL.
     * Internal class use only. All external calls should use build().
     *
     * @param string $url Contains the URL which will be formatted
     * @param string $params URL paramaters to append to end.
     * @return string Assembled URL.
     */
    private static function buildNonFriendly($url, $params) {
        if ($params != '' || $params === true) {
            if ($params === true) {
                $params = '';
            }

            return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/index.php?route=' . $url . ((substr($url, -1) == '/') ? '' : '/') . '&' . $params;
        } else {
            return (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/index.php?route=' . $url . ((substr($url, -1) == '/') ? '' : '/');
        }
    }
}
