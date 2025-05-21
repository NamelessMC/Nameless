<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        // Convert social_media to use settings table
        $this->_cache->setCache('social_media');
        $discord_widget_theme = $this->_cache->retrieve('discord_widget_theme') ?: 'dark';
        Settings::set('discord_widget_theme', $discord_widget_theme, 'Discord Integration');
        $this->_cache->eraseAll();

        $this->setVersion('2.3.0');
    }
};
