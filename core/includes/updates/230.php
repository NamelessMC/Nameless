<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        // Convert avatar_settings_cache to use settings table
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('avatar_settings_cache');
        $custom_avatars = $cache->retrieve('custom_avatars') ?? false;
        $default_avatar_type = $cache->retrieve('default_avatar_type') ?: 'minecraft';
        $default_avatar_image = $cache->retrieve('default_avatar_image') ?: '';
        $default_avatar_source = $cache->retrieve('avatar_source') ?: 'cravatar';
        $default_avatar_perspective = $cache->retrieve('avatar_perspective') ?: 'face';

        Settings::set('custom_avatars', $custom_avatars);
        Settings::set('default_avatar_type', $default_avatar_type);
        Settings::set('default_avatar_image', $default_avatar_image);
        Settings::set('default_avatar_source', $default_avatar_source);
        Settings::set('default_avatar_perspective', $default_avatar_perspective);
        $cache->eraseAll();

        $this->setVersion('2.3.0');
    }
};
