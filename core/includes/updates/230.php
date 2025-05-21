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

        $this->setVersion('2.3.0');
    }
};
