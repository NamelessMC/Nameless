<?php

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
class Util
{
    private static array $_enabled_modules = [];

    /**
     * Convert Cyrillic to Latin letters.
     * https://en.wikipedia.org/wiki/ISO_9.
     *
     * @param string $string String to convert.
     *
     * @return string Converted string.
     */
    public static function cyrillicToLatin(string $string): string
    {
        $cyrillic = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
        ];
        $latin = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya',
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
    public static function recursiveRemoveDirectory(string $directory): bool
    {
        // safety precaution, only allow deleting files in "custom", "modules" or "uploads" directory
        if (
            str_contains($directory, 'Core') ||
            (
                !str_contains(realpath($directory), 'custom') &&
                !str_contains(realpath($directory), 'modules') &&
                !str_contains(realpath($directory), 'uploads')
            )
        ) {
            return false;
        }

        foreach (glob($directory . '/*') as $file) {
            if (is_dir($file)) {
                if (!self::recursiveRemoveDirectory($file)) {
                    return false;
                }
            } elseif (!unlink($file)) {
                return false;
            }
        }

        rmdir($directory);

        return true;
    }

    /**
     * Get an array containing all timezone lists.
     *
     * @throws Exception
     * @return array     All timezones.
     */
    public static function listTimezones(): array
    {
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
            $offset = 'GMT ' . (int) ($current->getOffset() / 3600) . ':' . str_pad(abs((int) ($current->getOffset() % 3600 / 60)), 2, 0);

            // Prettify timezone name
            $name = Output::getClean(str_replace(['/', '_'], [', ', ' '], $timezone));

            // Add to timezones array
            $timezones[$timezone] = ['offset' => $offset, 'name' => $name, 'time' => $current->format('H:i')];
        }

        array_multisort($offsets, $timezones);

        return $timezones;
    }

    /**
     * Check for Nameless updates.
     *
     * @return string|UpdateCheck Object with information about any updates, or error message.
     */
    public static function updateCheck()
    {
        $uid = Settings::get('unique_id');

        $update_check_response = HttpClient::get(
            'https://namelessmc.com/api/v2/updateCheck&uid=' . $uid .
            '&version=' . NAMELESS_VERSION .
            '&php_version=' . urlencode(PHP_VERSION) .
            '&language=' . LANGUAGE .
            '&docker=' . (getenv('NAMELESSMC_METRICS_DOCKER') === false ? 'false' : 'true') .
            '&mysql_server=' . DB::getInstance()->getPDO()->getAttribute(PDO::ATTR_SERVER_VERSION)
        );

        if ($update_check_response->hasError()) {
            return $update_check_response->getError();
        }

        $update_check = new UpdateCheck($update_check_response);
        if ($update_check->hasError()) {
            return $update_check->getErrorMessage();
        }

        Settings::set('version_checked', date('U'));

        if ($update_check->updateAvailable()) {
            Settings::set(
                'version_update',
                $update_check->isUrgent()
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
    public static function getLatestNews(): string
    {
        $news = HttpClient::get('https://namelessmc.com/news');

        if ($news->hasError()) {
            return json_encode([
                'error' => $news->getError(),
            ]);
        }

        return $news->contents();
    }

    /**
     * Determine if a specific module is enabled.
     *
     * @param  string $name Name of module to check for.
     * @return bool   Whether this module is enabled or not.
     */
    public static function isModuleEnabled(string $name): bool
    {
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
     * Read the last part of a file, removing a leading partial line if necessary.
     * @param  string $file_path Path to file to read
     * @param  int    $max_bytes Max number of bytes to read at end of file
     * @return string Read string
     */
    public static function readFileEnd(string $file_path, int $max_bytes = 100_000): string
    {
        $fp = fopen($file_path, 'rb');
        $size = filesize($file_path);
        $start = max([$size - $max_bytes, 0]);
        fseek($fp, $start);
        $read_length = $size - $start;
        if ($read_length) {
            $content = fread($fp, $read_length);
            if ($start > 0) {
                // Read content may contain partial line, remove it
                $first_lf = strpos($content, PHP_EOL);
                $content = substr($content, $first_lf + 1);
            }

            return $content;
        }

        return '';
    }

    /**
     * Determine the order of array items with dependencies (denoted by the "after" or "before" field)
     * This is a more generic version of the module sort order determination.
     * @param  array $items Items (array of items consisting of after, before and name)
     * @return array Ordered items
     */
    public static function determineOrder(array $items): array
    {
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
     * @param  array  $items   Names of items already processed
     * @param  string $current Name of current item
     * @return array  Items before and after the current item
     */
    public static function findBeforeAfter(array $items, string $current): array
    {
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

    /**
     * Determine whether a module/template version is compatible with the current NamelessMC version.
     * This ignores patch versions, and only checks major and minor versions.
     * For example, 2.0.0 and 2.0.1 are compatible, but 2.0.0 and 2.1.0 are not.
     * @param  string $version          Version of module/template to check
     * @param  string $nameless_version Current NamelessMC version
     * @return bool   Whether they are compatible or not
     */
    public static function isCompatible(string $version, string $nameless_version): bool
    {
        [$major, $minor] = explode('.', $version);
        [$nameless_major, $nameless_minor] = explode('.', $nameless_version);

        return $major == $nameless_major && $minor == $nameless_minor;
    }

    /**
     * Format bytes into a human-readable string.
     *
     * @param  int    $bytes Number of bytes to format.
     * @return string Formatted string.
     */
    public static function formatBytes(int $bytes): string
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
    }
}
