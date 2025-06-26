<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ConvertCacheUsesToSettings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $cache = new Cache([
            'name' => 'nameless',
            'extension' => '.cache',
            'path' => ROOT_PATH . '/cache/'
        ]);

        // Convert templatecache to use settings table
        $cache->setCache('templatecache');
        $default_template = $cache->fetch('default', 'DefaultRevamp');
        $default_panel_template = $cache->fetch('panel_default', 'Default');
        Settings::set('default_template', $default_template);
        Settings::set('default_panel_template', $default_panel_template);

        // Convert template_settings to use settings table
        $cache->setCache('template_settings');
        $darkMode = $cache->fetch('darkMode', '0');
        $navbarColour = $cache->fetch('navbarColour', 'white');
        Settings::set('dark_mode', $darkMode);
        Settings::set('default_revamp_navbar_color', $navbarColour);

        // Convert backgroundcache to use settings table
        $cache->setCache('backgroundcache');
        $logo_image = $cache->fetch('logo_image', '');
        $banner_image = $cache->fetch('banner_image', '');
        $og_image = $cache->fetch('og_image', '');
        $favicon_image = $cache->fetch('favicon_image', '');
        Settings::set('logo_image_path', $logo_image);
        Settings::set('banner_image_path', $banner_image);
        Settings::set('og_image_path', $og_image);
        Settings::set('favicon_image_path', $favicon_image);

        // Convert avatar_settings_cache to use settings table
        $cache->setCache('avatar_settings_cache');
        $custom_avatars = $cache->fetch('custom_avatars', '0');
        $default_avatar_type = $cache->fetch('default_avatar_type', 'minecraft');
        $default_avatar_image = $cache->fetch('default_avatar_image', '');
        $default_avatar_source = $cache->fetch('avatar_source', 'cravatar');
        if ($default_avatar_source === 'Nameless') {
            $default_avatar_source = 'cravatar';
        }
        $default_avatar_perspective = $cache->fetch('avatar_perspective', 'face');
        Settings::set('custom_avatars', $custom_avatars);
        Settings::set('default_avatar_type', $default_avatar_type);
        Settings::set('default_avatar_image', $default_avatar_image);
        Settings::set('default_avatar_source', $default_avatar_source);
        Settings::set('default_avatar_perspective', $default_avatar_perspective);

        // Convert OnlineUsersWidget to use settings table
        $cache->setCache('online_members');
        $use_nickname_show = $cache->fetch('show_nickname_instead', '0');
        $include_staff = $cache->fetch('include_staff_in_users', '0');
        Settings::set('online_users_widget_use_nicknames', $use_nickname_show);
        Settings::set('online_users_widget_include_staff', $include_staff);

        // Convert social_media to use settings table
        $cache->setCache('social_media');
        $discord_widget_theme = $cache->fetch('discord_widget_theme', 'dark');
        Settings::set('discord_widget_theme', $discord_widget_theme, 'Discord Integration');
    }
}
