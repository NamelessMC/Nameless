<?php
return new class extends UpgradeScript {
    public function run(): void {
        $this->runMigrations();

        // Move query interval from cache to settings table
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('server_query_cache');
        if ($cache->isCached('query_interval')) {
            $query_interval = $cache->retrieve('query_interval');
            if (is_numeric($query_interval) && $query_interval <= 60 && $query_interval >= 5) {
                // Interval ok
            } else {
                // Default to 10
                $query_interval = 10;
            }
            Util::setSetting('minecraft_query_interval', $query_interval);
        }

        // Replace `external_query` with `query_type`
        Util::setSetting('query_type', Util::getSetting('external_query') == 1 ? 'external' : 'internal');
        Util::setSetting('external_query', null);

        // Forum post conversion
        ConvertForumPostTask::schedule();

        // Add all groups to member list selectable groups
        Util::setSetting('member_list_viewable_groups', json_encode(array_map(static fn (Group $group) => $group->id, Group::all())), 'Members');

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

            Util::setSetting('minecraft_avatar_source', $default_source);
            $cache->erase('avatar_source');
        }

        // Rename `avatar_type` to `minecraft_avatar_perspective` and move to DB
        $cache->setCache('avatar_settings_cache');
        if ($cache->isCached('avatar_perspective')) {
            $avatar_type = $cache->retrieve('avatar_perspective');
            Util::setSetting('avatar_perspective', $avatar_type);
            $cache->erase('avatar_type');
        }

        // Move `custom_avatars` to DB
        // TODO: make sure this is correct
        $cache->setCache('avatar_settings_cache');
        if ($cache->isCached('custom_avatars')) {
            $custom_avatars = $cache->retrieve('custom_avatars');
            Util::setSetting('custom_user_avatars', $custom_avatars);
            $cache->erase('custom_avatars');
        }

        $this->setVersion('2.1.0');
    }
};
