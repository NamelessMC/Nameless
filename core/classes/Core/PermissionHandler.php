<?php
/**
 * Allows modules to define permissions.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class PermissionHandler {

    /**
     * @var array<string, array<string, string>> All registered permissions.
     */
    private static array $_permissions;

    /**
     *  Register a permission for display in the StaffCP.
     *
     * @param string $section Permission section to add permission to.
     * @param array $permissions List of unique permissions to register.
     */
    public static function registerPermissions(string $section, array $permissions): void {
        foreach ($permissions as $permission => $title) {
            if (!isset(self::$_permissions[$section][$permission])) {
                self::$_permissions[$section][$permission] = $title;
            }
        }
    }

    /**
     * Get all registered permissions.
     *
     * @return array<string, array<string, string>> Permission array.
     */
    public static function getPermissions(): array {
        return self::$_permissions;
    }
}
