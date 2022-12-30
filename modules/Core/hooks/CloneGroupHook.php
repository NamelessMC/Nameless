<?php

/**
 * Clone group event listener handler class
 *
 * @package Modules\Core\Hooks
 * @author Partydragen
 * @version 2.0.0
 * @license MIT
 */
class CloneGroupHook extends HookBase {

    /**
     * @param array{group_id: ?string, cloned_group_id: ?string} $params
     *
     * @return void
     */
    public static function execute(array $params = ["group_id" => null, "cloned_group_id" => null]): void {
        $new_group_id = $params['group_id'];
        $old_group_id = $params['cloned_group_id'];

        if (!parent::validateParams($params, ["group_id", "cloned_group_id"])) {
            return;
        }

        // Clone group permissions for custom pages
        $permissions = DB::getInstance()->query('SELECT * FROM nl2_custom_pages_permissions WHERE group_id = ?', [$old_group_id]);
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
