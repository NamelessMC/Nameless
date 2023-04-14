<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Clone group event listener handler class
 */

class CloneGroupHook {

    public static function execute(GroupClonedEvent $event): void {

        // Clone group permissions for custom pages
        $new_group_id = $event->group->id;
        $permissions = DB::getInstance()->query('SELECT * FROM nl2_custom_pages_permissions WHERE group_id = ?', [$event->cloned_group->id]);
        if ($permissions->count()) {
            $permissions = $permissions->results();

            $inserts = [];
            foreach ($permissions as $permission) {
                $inserts[] = '('.$permission->page_id.',' . $new_group_id . ',' . $permission->view . ')';
            }

            $query = 'INSERT INTO nl2_custom_pages_permissions (page_id, group_id, view) VALUES ';
            $query .= implode(',', $inserts);

            DB::getInstance()->query($query);
        }
    }
}
