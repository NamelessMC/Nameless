<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        // Convert backgroundcache to use settings table
        $this->_cache->setCache('backgroundcache');
        $logo_image = $this->_cache->retrieve('logo_image') ?: '';
        $banner_image = $this->_cache->retrieve('banner_image') ?: '';
        $og_image = $this->_cache->retrieve('og_image') ?: '';
        $favicon_image = $this->_cache->retrieve('favicon_image') ?: '';
        Settings::set('logo_image_path', $logo_image);
        Settings::set('banner_image_path', $banner_image);
        Settings::set('og_image_path', $og_image);
        Settings::set('favicon_image_path', $favicon_image);
        $this->_cache->eraseAll();

        // Convert avatar_settings_cache to use settings table
        $this->_cache->setCache('avatar_settings_cache');
        $custom_avatars = $this->_cache->retrieve('custom_avatars') ?? false;
        $default_avatar_type = $this->_cache->retrieve('default_avatar_type') ?: 'minecraft';
        $default_avatar_image = $this->_cache->retrieve('default_avatar_image') ?: '';
        $default_avatar_source = $this->_cache->retrieve('avatar_source') ?: 'cravatar';
        if ($default_avatar_source === 'Nameless') {
            $default_avatar_source = 'cravatar';
        }
        $default_avatar_perspective = $this->_cache->retrieve('avatar_perspective') ?: 'face';
        Settings::set('custom_avatars', $custom_avatars);
        Settings::set('default_avatar_type', $default_avatar_type);
        Settings::set('default_avatar_image', $default_avatar_image);
        Settings::set('default_avatar_source', $default_avatar_source);
        Settings::set('default_avatar_perspective', $default_avatar_perspective);
        $this->_cache->eraseAll();

        // Convert OnlineUsersWidget to use settings table
        $this->_cache->setCache('online_members');
        $use_nickname_show = $this->_cache->fetch('show_nickname_instead', 0);
        $include_staff = $this->_cache->fetch('include_staff_in_users', 0);
        Settings::set('online_users_widget_use_nicknames', $use_nickname_show);
        Settings::set('online_users_widget_include_staff', $include_staff);
        $this->_cache->eraseAll();

        // Convert social_media to use settings table
        $this->_cache->setCache('social_media');
        $discord_widget_theme = $this->_cache->retrieve('discord_widget_theme') ?: 'dark';
        Settings::set('discord_widget_theme', $discord_widget_theme, 'Discord Integration');
        $this->_cache->eraseAll();

        $this->setVersion('2.3.0');
    }
};
