<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Permission handler class
 */

namespace NamelessMC\Core\Support;

class PermissionHandler {

    private static array $_permissions;

    /**
     *  Register a permission for display in the StaffCP.
     * 
     * @param string $section Permission section to add permission to.
     * @param array $permissions List of unique permissions to register.
     */
    public static function registerPermissions(string $section, array $permissions): bool {
        if (!is_array($permissions)) {
            return false;
        }

        foreach ($permissions as $permission => $title) {
            if (!isset(self::$_permissions[$section][$permission])) {
                self::$_permissions[$section][$permission] = $title;
            }
        }

        return true;
    }

    /**
     *  Get all registered permissions.
     *  
     * @return array Permission array.
     */
    public static function getPermissions(): array {
        return self::$_permissions;
    }
}
