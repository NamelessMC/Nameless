<?php

use Astrotomic\Twemoji\Twemoji;

/**
 * Contains misc utility methods.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author Aberdeener
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class Util {

    private static array $_enabled_modules = [];
    private static ?array $_cached_settings = null;

    /**
     * Convert Cyrillic to Latin letters.
     * https://en.wikipedia.org/wiki/ISO_9.
     *
     * @param string $string String to convert.
     *
     * @return string Converted string.
     */
    public static function cyrillicToLatin(string $string): string {
        $cyrillic = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $latin = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];

        return str_replace($cyrillic, $latin, $string);
    }

    /**
     * Recursively remove a directory.
     *
     * @param string $directory Path to directory to remove.
     *
     * @return bool Whether the action succeeded or not.
     */
    public static function recursiveRemoveDirectory(string $directory): bool {
        // safety precaution, only allow deleting files in "custom" directory
        if (!str_contains($directory, 'custom')) {
            return false;
        }

        foreach (glob($directory . '/*') as $file) {
            if (is_dir($file)) {
                if (!self::recursiveRemoveDirectory($file)) {
                    return false;
                }
            } else {
                if (!unlink($file)) {
                    return false;
                }
            }
        }

        rmdir($directory);

        return true;
    }

    /**
     * Get an array containing all timezone lists.
     *
     * @return array All timezones.
     */
    public static function listTimezones(): array {
        // Array to contain timezones
        $timezones = [];

        // Array to contain offsets
        $offsets = [];

        // Get all PHP timezones
        $all_timezones = DateTimeZone::listIdentifiers();

        // Get current UTC time to calculate offset
        $current = new DateTime('now', new DateTimeZone('UTC'));

        foreach ($all_timezones as $timezone) {
            // Get timezone offset
            $current->setTimezone(new DateTimeZone($timezone));

            // Add offset to offset array
            $offsets[] = $current->getOffset();

            // Format timezone offset
            $offset = 'GMT ' . (int)($current->getOffset() / 3600) . ':' . str_pad(abs((int)($current->getOffset() % 3600 / 60)), 2, 0);

            // Prettify timezone name
            $name = Output::getClean(str_replace(['/', '_'], [', ', ' '], $timezone));

            // Add to timezones array
            $timezones[$timezone] = ['offset' => $offset, 'name' => $name, 'time' => $current->format('H:i')];
        }

        array_multisort($offsets, $timezones);

        return $timezones;
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
     * Determine whether the trusted proxies config option is set to a valid value or not.
     *
     * @deprecated Use `HttpUtils::isTrustedProxiesConfigured`. Will be removed in 2.1.0
     * @return bool Whether the trusted proxies option is configured or not
     */
    public static function isTrustedProxiesConfigured(): bool {
        return HttpUtils::isTrustedProxiesConfigured();
    }

    /**
     * @deprecated Use `HttpUtils::getTrustedProxies`. Will be removed in 2.1.0
     * @return array List of trusted proxy networks according to config file and environment
     */
    public static function getTrustedProxies(): array {
        return HttpUtils::getTrustedProxies();
    }

    /**
     * Get the client's true IP address, using proxy headers if necessary.
     *
     * @deprecated Use `HttpUtils::getRemoteAddress`. Will be removed in 2.1.0
     * @return ?string Client IP address, or null if there is no remote address, for example in CLI environment
     */
    public static function getRemoteAddress(): ?string {
        return HttpUtils::getRemoteAddress();
    }

    /**
     * Get the protocol used by client's HTTP request, using proxy headers if necessary.
     *
     * @deprecated Use `HttpUtils::getProtocol`. Will be removed in 2.1.0
     * @return string 'http' if HTTP or 'https' if HTTPS. If the protocol is not known, for example when using the CLI, 'http' is always returned.
     */
    public static function getProtocol(): string {
        return HttpUtils::getProtocol();
    }

    /**
     * Get port used by client's HTTP request, using proxy headers if necessary.
     *
     * @deprecated Use `HttpUtils::getPort`. Will be removed in 2.1.0
     * @return ?int Port number, or null when using the CLI
     */
    public static function getPort(): ?int {
        return HttpUtils::getPort();
    }

    /**
     * Get the server name.
     *
     * @param bool $show_protocol Whether to show http(s) at front or not.
     *
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
     * URL-ify a string
     *
     * @param string|null $string $string String to URLify
     *
     * @return string Url-ified string. (I dont know what this means)
     */
    public static function stringToURL(string $string = null): string {
        if ($string) {
            $string = preg_replace('/[^A-Za-z0-9 ]/', '', $string);
            return Output::getClean(strtolower(urlencode(str_replace(' ', '-', $string))));
        }

        return '';
    }

    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * ### Options:
     *
     * - `ending` Will be used as Ending and appended to the trimmed string
     * - `exact` If false, $text will not be cut mid-word
     * - `html` If true, HTML tags would be handled correctly
     * @link http://book.cakephp.org/view/1469/Text#truncate-1625
     * @link https://github.com/cakephp/cakephp/blob/master/LICENSE
     *
     * @param string $text String to truncate.
     * @param int $length Length of returned string, including ellipsis.
     * @param array $options An array of html attributes and options.
     * @return string Trimmed string.
     */
    public static function truncate(string $text, int $length = 750, array $options = []): string {
        $default = [
            'ending' => '...', 'exact' => true, 'html' => false
        ];
        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen(strip_tags($ending));
            $openTags = [];
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else {
                        if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                            $pos = array_search($closeTag[1], $openTags);
                            if ($pos !== false) {
                                array_splice($openTags, $pos, 1);
                            }
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                    break;
                }

                $truncate .= $tag[3];
                $totalLength += $contentLength;
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }

            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
        $truncate .= $ending;

        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * Check for Nameless updates.
     *
     * @return string|UpdateCheck Object with information about any updates, or error message.
     */
    public static function updateCheck() {
        $uid = self::getSetting('unique_id');

        $update_check_response = HttpClient::get('https://namelessmc.com/api/v2/updateCheck&uid=' . $uid .
            '&version=' . NAMELESS_VERSION .
            '&php_version=' . urlencode(PHP_VERSION) .
            '&language=' . LANGUAGE .
            '&docker=' . (getenv('NAMELESSMC_METRICS_DOCKER') === false ? 'false' : 'true') .
            '&mysql_server=' . DB::getInstance()->getPDO()->getAttribute(PDO::ATTR_SERVER_VERSION)
        );

        if ($update_check_response->hasError()) {
            return $update_check_response->getError();
        }

        $update_check = new UpdateCheck($update_check_response->json(true));
        if ($update_check->hasError()) {
            return $update_check->getErrorMessage();
        }

        self::setSetting("version_checked", date('U'));

        if ($update_check->updateAvailable()) {
            self::setSetting('version_update', $update_check->isUrgent()
                ? 'urgent'
                : 'true'
            );
        }

        return $update_check;
    }

    /**
     * Get the latest Nameless news.
     *
     * @return string NamelessMC news in JSON.
     */
    public static function getLatestNews(): string {
        $news = HttpClient::get('https://namelessmc.com/news');

        if ($news->hasError()) {
            return json_encode([
                'error' => $news->getError()
            ]);
        }

        return $news->contents();
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
                return '<a href="' . $m[1] . '" rel="nofollow noopener" target="_blank">' . $m[2] . '</a>';
            }

            return '<a href="' . $m[1] . '" target="_blank">' . $m[2] . '</a>';
        }, $data);
    }

    /**
     * Get a setting from the database table `nl2_settings`.
     *
     * @param string $setting Setting to check.
     * @param ?string $fallback Fallback to return if $setting is not set in DB.
     * @param ?string $module Alphanumeric (no spaces!) module name to use as a settings table prefix. For example,
     *                        specify 'store' to use the 'nl2_store_settings' table. Null to use the standard
     *                        nl2_settings table.
     * @return ?string Setting from DB or $fallback.
     */
    public static function getSetting(string $setting, ?string $fallback = null, ?string $module = null): ?string {
        $table_name = $module == null ? 'nl2_settings' : "nl2_${module}_settings";

        if (self::$_cached_settings == null) {
            $result = DB::getInstance()->query('SELECT `name`, `value` FROM `' . $table_name. '`')->results();
            // Store settings in dictionary format
            self::$_cached_settings = [];
            foreach ($result as $row) {
                self::$_cached_settings[$row->name] = $row->value;
            }
        }

        return self::$_cached_settings[$setting] ?? null;
    }

    /**
     * Modify a setting in the database table `nl2_settings`.
     *
     * @param string $setting Setting name.
     * @param string|null $new_value New setting value, or null to delete
     * @param ?string $module Alphanumeric (no spaces!) module name to use as a settings table prefix. For example,
     *                        specify 'store' to use the 'nl2_store_settings' table. Null to use the standard
     *                        nl2_settings table.
     */
    public static function setSetting(string $setting, ?string $new_value, ?string $module = null): void {
        $table_name = $module == null ? 'nl2_settings' : "nl2_${module}_settings";

        if ($new_value == null) {
            DB::getInstance()->query('DELETE FROM `' . $table_name . '` WHERE `name` = ?', [$setting]);
        } else {
            $query = 'INSERT INTO `' . $table_name . '` (`name`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?';
            DB::getInstance()->query($query, [$setting, $new_value, $new_value]);
        }

        if (self::$_cached_settings != null) {
            self::$_cached_settings[$setting] = $new_value;
        }
    }

    /**
     * Get in-game rank name from a website group ID, uses Group Sync rules.
     *
     * @param int $website_group_id ID of website group to search for.
     * @return string|null Name of in-game rank or null if rule is not set up.
     */
    public static function getIngameRankName(int $website_group_id): ?string {
        $nameless_injector = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class);
        $data = DB::getInstance()->get('group_sync', [$nameless_injector->getColumnName(), $website_group_id]);

        if ($data->count()) {
            return $data->first()->ingame_rank_name;
        }

        return null;
    }

    /**
     * Determine if a specific module is enabled
     *
     * @param string $name Name of module to check for.
     * @return bool Whether this module is enabled or not.
     */
    public static function isModuleEnabled(string $name): bool {
        if (in_array($name, self::$_enabled_modules)) {
            return true;
        }

        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('modulescache');

        $enabled_modules = $cache->retrieve('enabled_modules');

        if (in_array($name, array_column($enabled_modules, 'name'))) {
            self::$_enabled_modules[] = $name;
            return true;
        }

        return false;
    }

    /**
     * Replace native emojis with their Twemoji equivalent.
     *
     * @param string $text Text to parse
     * @return string Text with emojis replaced with URLs to their Twemoji equivalent.
     */
    public static function renderEmojis(string $text): string {
        return Twemoji::text($text)->toHtml(null, [
            'width' => 20,
            'height' => 20,
            'style' => 'vertical-align: middle;'
        ]);
    }

    /**
     * Wrap text in HTML `<strong>` tags. Used for when variables in translations are bolded,
     * since we want as little HTML in the translation strings as possible.
     *
     * @param string $text Text to wrap
     * @return string Text wrapped in `<strong>` tags
     */
    public static function bold(string $text): string {
        return '<strong>' . $text . '</strong>';
    }

    /**
     * Read the last part of a file, removing a leading partial line if necessary.
     * @param string $file_path Path to file to read
     * @param int $max_bytes Max number of bytes to read at end of file
     * @return string Read string
     */
    public static function readFileEnd(string $file_path, int $max_bytes = 100_000): string {
        $fp = fopen($file_path, 'rb');
        $size = filesize($file_path);
        $start = max([$size - $max_bytes, 0]);
        fseek($fp, $start);
        $read_length = $size - $start;
        $content = fread($fp, $read_length);
        if ($start > 0) {
            // Read content may contain partial line, remove it
            $first_lf = strpos($content, PHP_EOL);
            $content = substr($content, $first_lf + 1);
        }
        return $content;
    }

    /**
     * Determine the order of array items with dependencies (denoted by the "after" or "before" field)
     * This is a more generic version of the module sort order determination
     * @param array $items Items (array of items consisting of after, before and name)
     * @return array Ordered items
     */
    public static function determineOrder(array $items): array {
        if (empty($items)) {
            return $items;
        }

        $order = [array_shift($items)['name']];

        foreach ($items as $item) {
            foreach ($order as $n => $nValue) {
                $before_after = self::findBeforeAfter($order, $nValue);

                if (!array_diff($item['after'], $before_after[0]) && !array_diff($item['before'], $before_after[1])) {
                    array_splice($order, $n + 1, 0, $item['name']);
                    continue 2;
                }
            }

            $order[] = $item['name'];
        }

        return $order;
    }

    /**
     * Used by order determination to get items before or after a specified item
     * Typically not called on its own - use Util::determineOrder in most cases!
     * @param array $items Names of items already processed
     * @param string $current Name of current item
     * @return array Items before and after the current item
     */
    public static function findBeforeAfter(array $items, string $current): array {
        $before = [$current];
        $after = [];
        $found = false;

        foreach ($items as $item) {
            if ($found) {
                $after[] = $item;
            } else {
                if ($item == $current) {
                    $found = true;
                } else {
                    $before[] = $item;
                }
            }
        }

        return [$before, $after];
    }

}
