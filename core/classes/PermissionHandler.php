<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  Permission handler class
 */
class PermissionHandler {
    private static $_permissions;

    /*
     *  Register a permission for display in the AdminCP
     *  Params: $section (string) - permission section to add permission to
     *          $permissions (string or array of strings) - module-unique name of permission(s)
     *          $title (string) - permission title - displays when managing permissions in AdminCP
     */
    public static function registerPermissions($section, $permissions){
        if(!is_array($permissions))
            return false;
        foreach($permissions as $permission => $title){
            if(!isset(self::$_permissions[$section][$permission])){
                self::$_permissions[$section][$permission] = $title;
            }
        }
        return true;
    }

    /*
     *  Get all permissions
     *  Params: none
     */
    public static function getPermissions(){
        return self::$_permissions;
    }
}