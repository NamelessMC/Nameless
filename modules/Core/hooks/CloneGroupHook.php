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
        // Clone group permissions
        $new_group_id = $event->group->id;
        $permissions = DB::getInstance()->query('SELECT * FROM nl2_permissions WHERE permissible = ? AND permissible_id = ?', [get_class($event->cloned_group), $event->cloned_group->id]);
        if ($permissions->count()) {
            $permissions = $permissions->results();

            $inserts = [];
            foreach ($permissions as $permission) {
                $inserts[] = '('.$new_group_id.',"'.get_class($event->group).'",' . $permission->permissible_id . ',"' . $permission->permission . '",' . $permission->value . ')';
            }

            $query = 'INSERT INTO nl2_permissions (permissible_id, permissible, permissible_id, permission, value) VALUES ';
            $query .= implode(',', $inserts);

            DB::getInstance()->query($query);
        }

        // Clone group permissions for custom pages
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
