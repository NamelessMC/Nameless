<?php
/*
 *	Made by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  AvatarSource class
 */

abstract class AvatarSource {

    protected static $_sources = array();

    /** 
     * Currently active avatar source.
     * @var AvatarSourceBase
     */
    protected static $_active_source;

    /**
     * Main usage of this class.
     * Uses active avatar source to get the URL of their avatar.
     * 
     * @param string $uuid UUID of avatar to get.
     * @param int|null $size Size in pixels to render avatar at. Default 128
     * @return string Compiled URL of avatar image.
     */
    public static function getAvatarFromUUID($uuid, $size = 128) {
        if (defined('DEFAULT_AVATAR_PERSPECTIVE')) {
            $perspective = DEFAULT_AVATAR_PERSPECTIVE;
        } else {
            $perspective = 'face';
        }

        return self::getActiveSource()->getAvatar($uuid, $perspective, $size);
    }
    
    /**
     * Register avatar source.
     *
     * @param AvatarSourceBase $source Instance of avatar source to register.
     */
    public static function registerSource(AvatarSourceBase $source) {
        self::$_sources[$source->_name] = $source;
    }

    /** 
     * Get the currently active avatar source.
     * @return AvatarSourceBase 
     */
    public static function getActiveSource() {
        return self::$_active_source;
    }

    /**
     * Set the active source to the source by name. 
     * Fallsback to Crafatar if name was not found.
     * 
     * @param string $name Name of source to set as active.
     */
    public static function setActiveSource($name) {
        $source = self::$_sources[strtolower($name)];
        if ($source == null) {
            $source = self::$_sources['cravatar'];
        }
    
        self::$_active_source = $source;
    }

    /** 
     * Get all of the registered avatar sources.
     * @return AvatarSourceBase[]
     */
    public static function getAllSources() {
        return self::$_sources;
    }
}