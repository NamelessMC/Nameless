<?php

class Settings {

    private static ?array $_cached_settings = null;

    const MINECRAFT_INTEGRATION = 'mc_integration';

    private static function hasSettingsCache(?string $module): bool {
        $cache_name = $module !== null ? $module : 'core';
        return self::$_cached_settings !== null && isset(self::$_cached_settings[$cache_name]);
    }

    private static function &getSettingsCache(?string $module): array {
        $cache_name = $module !== null ? $module : 'core';
        return self::$_cached_settings[$cache_name];
    }

    private static function setSettingsCache(?string $module, array $cache): void {
        $cache_name = $module !== null ? $module : 'core';
        self::$_cached_settings[$cache_name] = $cache;
    }

    /**
     * Get a setting from the database table `nl2_settings`.
     *
     * @param string $setting Setting to check.
     * @param ?string $fallback Fallback to return if $setting is not set in DB. Defaults to null.
     * @param string $module Module name to keep settings separate from other modules. Set module
     *                       to 'Core' for global settings.
     * @return ?string Setting from DB or $fallback.
     */
    public static function get(string $setting, ?string $fallback = null, string $module = 'core'): ?string {
        if (!self::hasSettingsCache($module)) {
            // Load all settings for this module and store it as a dictionary
            if ($module === 'core') {
                $result = DB::getInstance()->query('SELECT `name`, `value` FROM `nl2_settings` WHERE `module` IS NULL')->results();
            } else {
                $result = DB::getInstance()->query('SELECT `name`, `value` FROM `nl2_settings` WHERE `module` = ?', [$module])->results();
            }

            $cache = [];
            foreach ($result as $row) {
                $cache[$row->name] = $row->value;
            }
            self::setSettingsCache($module, $cache);
        }

        $cache = &self::getSettingsCache($module);
        return $cache[$setting] ?? $fallback;
    }

    /**
     * Modify a setting in the database table `nl2_settings`.
     *
     * @param string $setting Setting name.
     * @param string|null $new_value New setting value, or null to delete
     * @param string $module Module name to keep settings separate from other modules. Set module
     *                       to 'Core' for global settings.
     */
    public static function set(string $setting, ?string $new_value, string $module = 'core'): void {
        if ($new_value == null) {
            if ($module === 'core') {
                DB::getInstance()->query('DELETE FROM `nl2_settings` WHERE `name` = ? AND `module` IS NULL', [$setting]);
            } else {
                DB::getInstance()->query('DELETE FROM `nl2_settings` WHERE `name` = ? AND `module` = ?', [$setting, $module]);
            }
        } else {
            if ($module === 'core') {
                if (DB::getInstance()->query('SELECT * FROM nl2_settings WHERE `name` = ? and `module` IS NULL', [$setting])->count()) {
                    DB::getInstance()->query(
                        'UPDATE `nl2_settings` SET `value` = ? WHERE `name` = ? AND `module` IS NULL',
                        [$new_value, $setting]
                    );
                } else {
                    DB::getInstance()->query(
                        'INSERT INTO `nl2_settings` (`name`, `value`) VALUES (?, ?)',
                        [$setting, $new_value]
                    );
                }
            } else {
                DB::getInstance()->query(
                    'INSERT INTO `nl2_settings` (`name`, `value`, `module`)
                     VALUES (?, ?, ?)
                     ON DUPLICATE KEY UPDATE `value` = ?',
                    [$setting, $new_value, $module, $new_value]
                );
            }
        }

        if (!self::hasSettingsCache($module)) {
            return;
        }

        $cache = &self::getSettingsCache($module);

        if ($new_value !== null) {
            $cache[$setting] = $new_value;
        } else if (isset($cache[$setting])) {
            unset($cache[$setting]);
        }
    }

}
