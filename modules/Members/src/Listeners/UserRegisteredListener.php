<?php

namespace NamelessMC\Members\Listeners;

use NamelessMC\Framework\Events\Listener;

class UserRegisteredListener extends Listener
{
    private \Cache $cache;

    public function __construct(
        \Cache $cache
    ) {
        $this->cache = $cache;
    }

    public function handle(\UserRegisteredEvent $event): void {
        if (\Settings::get('member_list_hide_banned', false, 'Members')) {
            $cacheKey = 'new_members_banned';
            $query = \DB::getInstance()->query('SELECT id FROM nl2_users WHERE isbanned = 0 ORDER BY joined DESC LIMIT 12');
        } else {
            $cacheKey = 'new_members';
            $query = \DB::getInstance()->query('SELECT id FROM nl2_users ORDER BY joined DESC LIMIT 12');
        }

        $new_member_ids = array_map(static fn ($row) => $row->id, $query->results());

        $this->cache->setCache('member_lists');
        $this->cache->store($cacheKey, $new_member_ids);
    }
}