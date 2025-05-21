<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        // Convert templatecache to use settings table
        $this->_cache->setCache('templatecache');
        $default_template = $this->_cache->retrieve('default') ?: 'DefaultRevamp';
        $default_panel_template = $this->_cache->retrieve('panel_default') ?: 'Default';
        Settings::set('default_template', $default_template);
        Settings::set('default_panel_template', $default_panel_template);
        $this->_cache->eraseAll();

        // Convert template_settings to use settings table
        $this->_cache->setCache('template_settings');
        $darkMode = $this->_cache->retrieve('darkMode') ?: '0';
        $navbarColour = $this->_cache->retrieve('navbarColour') ?: 'white';
        Settings::set('dark_mode', $darkMode);
        Settings::set('default_revamp_navbar_color', $navbarColour);
        $this->_cache->eraseAll();

        $this->setVersion('2.3.0');
    }
};
