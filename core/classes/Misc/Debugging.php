<?php
/**
 * Class to help manage global state of various debugging variables.
 * TODO: Make `Debugging::enabled()` instead of needing to do `defined('DEBUGGING') && DEBUGGING`
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0
 * @license MIT
 */
class Debugging {

    private static bool $_can_view_detailed_error = false;
    private static bool $_can_generate_debug_link = false;

    public static function canViewDetailedError(): bool {
        return self::$_can_view_detailed_error;
    }

    public static function setCanViewDetailedError(bool $detailed_error): void {
        self::$_can_view_detailed_error = $detailed_error;
    }

    public static function canGenerateDebugLink(): bool {
        return self::$_can_generate_debug_link;
    }

    public static function setCanGenerateDebugLink(bool $can_generate_debug_link): void {
        self::$_can_generate_debug_link = $can_generate_debug_link;
    }

}
