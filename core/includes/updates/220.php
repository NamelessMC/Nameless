<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);

        // Update icon definitions to just be class names instead of full HTML
        $announcements = DB::getInstance()->get('announcements', ['icon', '<>', ''])->results();
        foreach ($announcements as $announcement) {
            DB::getInstance()->update('announcements', $announcement->id, [
                'icon' => $this->extractIconClasses($announcement->icon)
            ]);
        }
        (new Announcements($cache))->resetCache();

        $custom_pages = DB::getInstance()->get('custom_pages', ['icon', '<>', ''])->results();
        foreach ($custom_pages as $custom_page) {
            DB::getInstance()->update('custom_pages', $custom_page->id, [
                'icon' => $this->extractIconClasses($custom_page->icon)
            ]);
        }

        $forums = DB::getInstance()->get('forums', ['icon', '<>', ''])->results();
        foreach ($forums as $forum) {
            DB::getInstance()->update('forums', $forum->id, [
                'icon' => $this->extractIconClasses($forum->icon)
            ]);
        }

        $cache->setCache('navbar_icons');
        $icons = $cache->retrieveAll();
        foreach ($icons as $key => $icon) {
            $cache->store($key, $this->extractIconClasses($icon));
        }

        $this->setVersion('2.3.0');
    }

    /**
     * Transform "<i class="fas fa-home"></i>" to "fas fa-home"
     */
    private function extractIconClasses(string $icon_html): string {
        return preg_replace('/<i class="([^"]+)"><\/i>/', '$1', $icon_html);
    }

};
