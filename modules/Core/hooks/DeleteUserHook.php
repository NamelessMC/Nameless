<?php

/**
 * Delete user event listener for Core module
 *
 * @package Modules\Core\Hooks
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class DeleteUserHook extends HookBase {

    /**
     * @param array{user_id: ?string} $params
     *
     * @return void
     */
    public static function execute(array $params = ["user_id" => null]): void {
        if (!parent::validateParams($params, ["user_id"])) {
            return;
        }

        $db = DB::getInstance();

        // Get the list of tables to delete the user from
        $tables = [
            'users',
            'users_groups',
            'users_ips',
            'logs',
            'alerts',
            'blocked_users',
            'email_errors',
            'infractions',
            'private_messages',
            'private_messages_replies',
            'private_messages_users',
            'reports',
            'reports_comments',
            'users_session',
            'users_profile_fields',
            'users_username_history',
            'user_profile_wall_posts',
            'user_profile_wall_posts_reactions',
            'user_profile_wall_posts_replies',
            'oauth_users',
            'users_integrations',
        ];

        // Delete the user from all the tables
        foreach ($tables as $table) {
            $db->delete($table, ['id', $params['user_id']]);
        }
    }
}
