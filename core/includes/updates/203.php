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

        // Add all groups to member list selectable groups
        Util::setSetting('member_list_viewable_groups', json_encode(array_map(static fn (Group $group) => $group->id, Group::all())), 'Members');

        $this->setVersion('2.1.0');
    }
    
    /**
     * Transform "<i class="fas fa-home"></i>" to "fas fa-home"
     */
    private function extractIconClasses(string $icon_html): string {
        return preg_replace('/<i class="([^"]+)"><\/i>/', '$1', $icon_html);
    }
};
