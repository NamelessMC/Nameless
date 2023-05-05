<?php
/**
 * Helps build URLs which match the site's URL configuration.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class URL {

    private const URL_EXCLUDE_CHARS = [
        '?',
        '&',
        '/', // Encoded slashes cause issues with Apache: https://stackoverflow.com/q/9206835/4833737
        '#',
        '.',
    ];

    /**
     * Returns a URL in the correct format (friendly or not).
     *
     * @param string $url Contains the URL which will be formatted.
     * @param string $params Contains string with URL parameters.
     * @param ?string $force Determines whether to force a URL type (optional, can be either "friendly" or "non-friendly").
     * @return string Assembled URL, false on failure.
     */
    public static function build(string $url, string $params = '', ?string $force = null): string {
        if ($force === 'friendly') {
            return self::buildFriendly($url, $params);
        }

        if ($force === 'non-friendly') {
            return self::buildNonFriendly($url, $params);
        }

        // Use non-friendly URLs if NamelessMC is not installed yet
        if (!Config::exists()) {
            return self::buildFriendly($url, $params);
        }

        if (!is_null($force)) {
            throw new InvalidArgumentException('Invalid force string: ' . $force);
        }

        if ((defined('FRIENDLY_URLS') && FRIENDLY_URLS == true) || (!defined('FRIENDLY_URLS') && Config::get('core.friendly') == true)) {
            // Friendly URLs are enabled
            return self::buildFriendly($url, $params);
        }

        // Friendly URLs are disabled, we need to change it
        return self::buildNonFriendly($url, $params);
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

    /**
     * Get the server name.
     *
     * @param bool $show_protocol Whether to show http(s) at front or not.
     * @return string Compiled URL.
     */
    public static function getSelfURL(bool $show_protocol = true): string {
        $hostname = Config::get('core.hostname');

        if (!$hostname) {
            $hostname = $_SERVER['SERVER_NAME'];
        }

        $url = $hostname;

        if (defined('FORCE_WWW') && FORCE_WWW && !str_contains($hostname, 'www')) {
            $url = 'www.' . $url;
        }

        if ($show_protocol) {
            $protocol = HttpUtils::getProtocol();
            $url = $protocol . '://' . $url;
            $port = HttpUtils::getPort();
            // Add port if it is non-standard for the current protocol
            if (!(($port === 80 && $protocol === 'http') || ($port === 443 && $protocol === 'https'))) {
                $url .= ':' . $port;
            }
        }

        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        return $url;
    }

    /**
     * Is a URL internal or external? Accepts full URL and also just a path.
     *
     * @param string $url URL/path to check.
     *
     * @return bool Whether URL is external or not.
     */
    public static function isExternalURL(string $url): bool {
        if ($url[0] == '/' && $url[1] != '/') {
            return false;
        }

        $parsed = parse_url($url);

        return !(str_replace('www.', '', rtrim(self::getSelfURL(false), '/')) == str_replace('www.', '', $parsed['host']));
    }

    /**
     * Add target and rel attributes to external links only.
     * From https://stackoverflow.com/a/53461987
     *
     * @param string $data Data to replace.
     * @return string Replaced string.
     */
    public static function replaceAnchorsWithText(string $data): string {
        return preg_replace_callback('/]*href=["|\']([^"|\']*)["|\'][^>]*>([^<]*)<\/a>/i', static function ($m): string {
            if (!str_contains($m[1], self::getSelfURL())) {
                return 'href="' . $m[1] . '" rel="nofollow noopener" target="_blank">' . $m[2] . '</a>';
            }

            return 'href="' . $m[1] . '" target="_blank">' . $m[2] . '</a>';
        }, $data);
    }

    /**
     * Urlencode, but prettier.
     * - Spaces are replaced by dashes
     * - Cyrillic characters are converted to latin
     * - Some special characters are removed (see URL::URL_EXCLUDE_CHARS)
     *
     * @param string $text String to URLify
     * @return string Encoded string
     */
    public static function urlSafe(string $text): string {
        $text = str_replace(self::URL_EXCLUDE_CHARS, '', Util::cyrillicToLatin($text));
        return urlencode(strtolower(str_replace(' ', '-', $text)));
    }
}
