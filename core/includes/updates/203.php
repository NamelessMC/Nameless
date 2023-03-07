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

        // Replace`external_query` with `query_type`
        Util::setSetting('query_type', Util::getSetting('external_query') == 1 ? 'external' : 'internal');
        Util::setSetting('external_query', null);

        // Add all groups to member list selectable groups
        Util::setSetting('member_list_viewable_groups', json_encode(array_map(static fn (Group $group) => $group->id, Group::all())), 'Members');

        $this->setVersion('2.1.0');
    }
};
