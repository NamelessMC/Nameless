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

class AvatarSource {

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
        return self::getActiveSource()->getAvatar($uuid, self::getDefaultPerspective(), $size);
    }

    /**
     * Get raw url of active avatar source with placeholders.
     * 
     * @return string URL with placeholders.
     */
    public static function getUrlToFormat() {
        // Default to Cravatar
        if (!self::getActiveSource()) {
            require_once(ROOT_PATH . '/modules/Core/classes/CravatarAvatarSource.php');
            return (new CravatarAvatarSource())->getUrlToFormat(self::getDefaultPerspective());
        }

        return self::getActiveSource()->getUrlToFormat(self::getDefaultPerspective());
    }

    /**
     * Get default perspective to pass to the active avatar source.
     * 
     * @return string Perspective.
     */
    private static function getDefaultPerspective() {
        if (defined('DEFAULT_AVATAR_PERSPECTIVE')) {
            return DEFAULT_AVATAR_PERSPECTIVE;
        }

        return 'face';
    }

    /**
     * Register avatar source.
     *
     * @param AvatarSourceBase $source Instance of avatar source to register.
     */
    public static function registerSource(AvatarSourceBase $source) {
        self::$_sources[] = $source;
    }

    /** 
     * Get the currently active avatar source.
     * 
     * @return AvatarSourceBase The active source.
     */
    public static function getActiveSource() {
        return self::$_active_source;
    }

    /**
     * Set the active source to the source by name. 
     * Fallsback to Cravatar if name was not found.
     * 
     * @param string $name Name of source to set as active.
     */
    public static function setActiveSource($name) {
        $source = self::getSourceByName($name);
        if ($source == null) {
            $source = self::getSourceByName('cravatar');
        }

        self::$_active_source = $source;
    }

    /**
     * Find an avatar source instance by it's name.
     * 
     * @return AvatarSourceBase|null Instance if found, null if not found.
     */
    public static function getSourceByName($name) {
        foreach (self::getAllSources() as $source) {
            if (strtolower($source->getName()) == strtolower($name)) {
                return $source;
            }
        }

        return null;
    }

    /**
     * Get all registered sources.
     * 
     * @return AvatarSourceBase[]
     */
    public static function getAllSources() {
        return self::$_sources;
    }

    /**
     * Get the names and base urls of all the registered avatar sources for displaying.
     * Used for showing list of sources in staffcp.
     * 
     * @return array List of names.
     */
    public static function getAllSourceNames() {
        $names = [];
        
        foreach (self::getAllSources() as $source) {
            $names[$source->getName()] = rtrim($source->getBaseUrl(), '/');
        }

        return $names;
    }

    /**
     * Get key value array of all registered sources and their available perspectives.
     * Used for autoupdating dropdown selector in staffcp.
     * 
     * @return array Array of source => [] perspectives.
     */
    public static function getAllPerspectives() {
        $perspectives = [];

        foreach (self::getAllSources() as $source) {
            foreach ($source->getPerspectives() as $perspective) {
                $perspectives[$source->getName()][] = $perspective;
            }
        }

        return $perspectives;
    }
}