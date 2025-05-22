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

        $this->setVersion('2.3.0');
    }
};
