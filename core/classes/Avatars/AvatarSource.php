<?php
/**
 * Manages avatar sources and provides static methods for fetching avatars.
 *
 * @package NamelessMC\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr10
 * @license MIT
 */
class AvatarSource {

    protected static array $_sources = [];

    protected static AvatarSourceBase $_active_source;

    /**
     * Main usage of this class.
     * Uses active avatar source to get the URL of their Minecraft avatar.
     *
     * @param string $uuid UUID of avatar to get.
     * @param int $size Size in pixels to render avatar at. Default 128
     *
     * @return string Compiled URL of avatar image.
     */
    public static function getAvatarFromUUID(string $uuid, int $size = 128): string {
        return self::getActiveSource()->getAvatar($uuid, self::getDefaultPerspective(), $size);
    }

    /**
     * Get a user's avatar from their raw data object.
     * Used by the API for TinyMCE mention avatars to avoid reloading the user from the database.
     *
     * @param object $data User data to use
     * @param bool $allow_gifs Whether to allow GIFs or not ()
     * @param int $size Size in pixels to render avatar at. Default 128
     * @param bool $full Whether to return the full URL or just the path
     *
     * @return string Full URL of avatar image.
     */
    public static function getAvatarFromUserData(object $data, bool $allow_gifs = false, int $size = 128, bool $full = false): string {
        // If custom avatars are enabled, first check if they have gravatar enabled, and then fallback to normal image
        if (defined('CUSTOM_AVATARS')) {
            if ($data->gravatar) {
                return 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($data->email))) . '?s=' . $size;
            }

            if ($data->has_avatar) {
                $exts = ['png', 'jpg', 'jpeg'];

                if ($allow_gifs) {
                    $exts[] = 'gif';
                }

                foreach ($exts as $ext) {
                    if (file_exists(ROOT_PATH . '/uploads/avatars/' . $data->id . '.' . $ext)) {
                        // We don't check the validity here since we know the file exists for sure
                        return ($full ? rtrim(URL::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/' . $data->id . '.' . $ext . '?v=' . urlencode($data->avatar_updated);
                    }
                }
            }
        }

        // Fallback to default avatar image if it is set and the avatar type is custom
        if (defined('DEFAULT_AVATAR_TYPE') && DEFAULT_AVATAR_TYPE == 'custom' && DEFAULT_AVATAR_IMAGE !== '') {
            if (file_exists(ROOT_PATH . '/uploads/avatars/defaults/' . DEFAULT_AVATAR_IMAGE)) {
                // We don't check the validity here since we know the file exists for sure
                return ($full ? rtrim(URL::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/defaults/' . DEFAULT_AVATAR_IMAGE;
            }
        }

        // Attempt to get their MC avatar if Minecraft integration is enabled
        if (Util::getSetting('mc_integration')) {
            if ($data->uuid != null && $data->uuid != 'none') {
                $uuid = $data->uuid;
            } else {
                $uuid = $data->username;
                // Fallback to steve avatar if they have an invalid username
                if (preg_match('#[^][_A-Za-z0-9]#', $uuid)) {
                    $uuid = 'Steve';
                }
            }

            $url = self::getAvatarFromUUID($uuid, $size);
            // The avatar might be invalid if they are using
            // an MC avatar service that uses only UUIDs
            // and this user doesn't have one
            if (self::validImageUrl($url)) {
                return $url;
            }
        }

        return "https://api.dicebear.com/5.x/initials/png?seed={$data->username}&size={$size}";
    }

    /**
     * Determine if a URL is a valid image URL for avatars.
     *
     * @param string $url URL to check
     * @return bool Whether the URL is a valid image URL
     */
    private static function validImageUrl(string $url): bool {
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('avatar_validity');

        if ($cache->isCached($url)) {
            return $cache->retrieve($url);
        }

        $is_valid = false;
        try {
            $response = HttpClient::createClient()->head($url);
            $headers = $response->getHeaders();
            if (isset($headers['Content-Type']) && $headers['Content-Type'][0] === 'image/png') {
                $is_valid = true;
            }
        } catch (Exception $ignored) {
        }

        $cache->store($url, $is_valid, 3600);
        return $is_valid;
    }

    /**
     * Get the currently active avatar source.
     *
     * @return AvatarSourceBase The active source.
     */
    public static function getActiveSource(): AvatarSourceBase {
        return self::$_active_source;
    }

    /**
     * Set the active source to the source by name.
     * Fallsback to Cravatar if name was not found.
     *
     * @param string $name Name of source to set as active.
     */
    public static function setActiveSource(string $name): void {
        $source = self::getSourceByName($name);
        if ($source === null) {
            $source = self::getSourceByName('cravatar');
        }

        self::$_active_source = $source;
    }

    /**
     * Get default perspective to pass to the active avatar source.
     *
     * @return string Perspective.
     */
    private static function getDefaultPerspective(): string {
        if (defined('DEFAULT_AVATAR_PERSPECTIVE')) {
            return DEFAULT_AVATAR_PERSPECTIVE;
        }

        return 'face';
    }

    /**
     * Find an avatar source instance by it's name.
     *
     * @return AvatarSourceBase|null Instance if found, null if not found.
     */
    public static function getSourceByName(string $name): ?AvatarSourceBase {
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
    public static function getAllSources(): iterable {
        return self::$_sources;
    }

    /**
     * Get raw url of active avatar source with placeholders.
     *
     * @return string URL with placeholders.
     */
    public static function getUrlToFormat(): string {
        // Default to Cravatar
        if (!isset(self::$_active_source)) {
            require_once(ROOT_PATH . '/modules/Core/classes/Avatars/CravatarAvatarSource.php');
            return (new CravatarAvatarSource())->getUrlToFormat(self::getDefaultPerspective());
        }

        return self::getActiveSource()->getUrlToFormat(self::getDefaultPerspective());
    }

    /**
     * Register avatar source.
     *
     * @param AvatarSourceBase $source Instance of avatar source to register.
     */
    public static function registerSource(AvatarSourceBase $source): void {
        self::$_sources[] = $source;
    }

    /**
     * Get the names and base urls of all the registered avatar sources for displaying.
     * Used for showing list of sources in staffcp.
     *
     * @return array<string, string> List of names.
     */
    public static function getAllSourceNames(): array {
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
     * @return array<string, array<string>> Array of source => [] perspectives.
     */
    public static function getAllPerspectives(): array {
        $perspectives = [];

        foreach (self::getAllSources() as $source) {
            foreach ($source->getPerspectives() as $perspective) {
                $perspectives[$source->getName()][] = $perspective;
            }
        }

        return $perspectives;
    }
}
