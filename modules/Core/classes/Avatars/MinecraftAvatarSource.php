<?php

class MinecraftAvatarSource extends AvatarSourceBase {

    protected static array $_sources = [];

    protected static MinecraftAvatarSourceBase $_active_source;

    public function __construct(Language $language) {
        $this->_name = $language->get('admin', 'avatar_source_minecraft');
        $this->_module = 'Core';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_avatar_settings/minecraft.php';
    }

    /**
     * Main usage of this class.
     * Uses active Minecraft avatar source to get the URL of their Minecraft avatar.
     *
     * @param string $identifier UUID or username of avatar to get.
     * @param int $size Size in pixels to render avatar at. Default 128
     *
     * @return string Compiled URL of avatar image.
     */
    public static function getAvatarFromIdentifier(string $identifier, int $size = 128): ?string {
        $is_uuid = false;
        if (strlen($identifier) === 32 || strlen($identifier) === 36) {
            $is_uuid = true;
        }
        if (!$is_uuid && !self::getActiveSource()->supportsUsernames()) {
            return null;
        }

        return self::getActiveSource()->getAvatar($identifier, self::getPerspective(), $size);
    }

    /**
     * Get the currently active avatar source.
     *
     * @return MinecraftAvatarSourceBase The active source.
     */
    private static function getActiveSource(): MinecraftAvatarSourceBase {
        if (!isset(self::$_active_source)) {
            $source = Settings::get('minecraft_avatar_source', CravatarMinecraftAvatarSource::class);
            self::$_active_source = self::$_sources[$source];
        }

        return self::$_active_source;
    }

    /**
     * Get all registered sources.
     *
     * @return MinecraftAvatarSourceBase[]
     */
    private static function getAllSources(): iterable {
        return self::$_sources;
    }

    /**
     * Get default perspective to pass to the active avatar source.
     *
     * @return string Perspective.
     */
    private static function getPerspective(): string {
        return Settings::get('minecraft_avatar_perspective', 'face');
    }

    /**
     * Get raw url of active avatar source with placeholders.
     *
     * @return string URL with placeholders.
     */
    public static function getUrlToFormat(): string {
        return self::getActiveSource()->getUrlToFormat(self::getPerspective());
    }

    /**
     * Register avatar source.
     *
     * @param MinecraftAvatarSourceBase $source Instance of avatar source to register.
     */
    public static function registerSource(MinecraftAvatarSourceBase $source): void {
        self::$_sources[$source->getSafeName()] = $source;
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
            $names[$source->getSafeName()] = rtrim($source->getBaseUrl(), '/') . ' ' . ($source->supportsUsernames() ? '(supports usernames)' : '');
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
                $perspectives[$source->getSafeName()][] = $perspective;
            }
        }

        return $perspectives;
    }

    protected function get(User $user): ?string {
        $minecraft_integration = $user->getIntegration('Minecraft');
        if ($minecraft_integration !== null) {
            $identifier = $minecraft_integration->data()->identifier;
        } else {
            $identifier = $user->data()->username;
            // Fallback to steve avatar if they have an invalid username
            // TODO: what is this regex?
            if (preg_match('#[^][_A-Za-z0-9]#', $identifier)) {
                $identifier = 'Steve';
            }
        }

        return self::getAvatarFromIdentifier($identifier, $this->_size);
    }
}
