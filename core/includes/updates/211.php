<?php
return new class extends UpgradeScript {
    public function run(): void {
        $this->runMigrations();

        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);

        // Update avatar settings cache to use the class name for minecraft default source and swap to DB
        $cache->setCache('avatar_settings_cache');
        if ($cache->isCached('avatar_source')) {
            $default_source = $cache->retrieve('avatar_source');
            switch($default_source) {
                case 'cravatar':
                    $default_source = CravatarMinecraftAvatarSource::class;
                    break;
                case 'crafthead':
                    $default_source = CraftheadMinecraftAvatarSource::class;
                    break;
                case 'crafatar':
                    $default_source = CrafatarMinecraftAvatarSource::class;
                    break;
                case 'mc-heads':
                    $default_source = MCHeadsMinecraftAvatarSource::class;
                    break;
                case 'minotar':
                    $default_source = MinotarMinecraftAvatarSource::class;
                    break;
                case 'nameless':
                    $default_source = NamelessMCMinecraftAvatarSource::class;
                    break;
                case 'visage':
                    $default_source = VisageMinecraftAvatarSource::class;
                    break;
            }

            Settings::set('minecraft_avatar_source', $default_source);
            $cache->erase('avatar_source');
        }

        // Rename `avatar_type` to `minecraft_avatar_perspective` and move to DB
        // Ensure admin group has administrator perm
        $cache->setCache('avatar_settings_cache');
        if ($cache->isCached('avatar_perspective')) {
            $avatar_type = $cache->retrieve('avatar_perspective');
            Settings::set('avatar_perspective', $avatar_type);
            $cache->erase('avatar_type');
        }

        // Move `custom_avatars` to DB
        // TODO: make sure this is correct
        $cache->setCache('avatar_settings_cache');
        if ($cache->isCached('custom_avatars')) {
            $custom_avatars = $cache->retrieve('custom_avatars');
            Settings::set('custom_user_avatars', $custom_avatars);
            $cache->erase('custom_avatars');
        }
    }
};
