<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Delete user event listener for Core module
 */

class DeleteUserHook {

    public static function execute(array $params = []): void {
        if (isset($params['user_id']) && $params['user_id'] > 1) {
            $queries = new Queries();

            // All the below tables have foreign key constrains which
            // should be deleted after deleting the user from nl2_users, but since these
            // keys were added in a later update, we cannot be sure they exist, hence needing to
            // delete them manually still. Maybe in the future we can rely more on the foreign keys.

            // Delete the user
            $queries->delete('users', ['id', $params['user_id']]);

            // Groups
            $queries->delete('users_groups', ['user_id', $params['user_id']]);

            // IP logs
            $queries->delete('users_ips', ['user_id', $params['user_id']]);

            // Logs
            $queries->delete('logs', ['user_id', $params['user_id']]);

            // Alerts
            $queries->delete('alerts', ['user_id', $params['user_id']]);

            // Blocked users
            $queries->delete('blocked_users', ['user_id', $params['user_id']]);
            $queries->delete('blocked_users', ['user_blocked_id', $params['user_id']]);

            // Email errors
            $queries->delete('email_errors', ['user_id', $params['user_id']]);

            // Infractions
            $queries->delete('infractions', ['punished', $params['user_id']]);
            $queries->delete('infractions', ['staff', $params['user_id']]);

            // Private messages
            $queries->delete('private_messages', ['author_id', $params['user_id']]);
            $queries->delete('private_messages_replies', ['author_id', $params['user_id']]);
            $queries->delete('private_messages_users', ['user_id', $params['user_id']]);

            // Reports
            $queries->delete('reports', ['reporter_id', $params['user_id']]);
            $queries->delete('reports_comments', ['commenter_id', $params['user_id']]);

            // User sessions
            $queries->delete('users_admin_session', ['user_id', $params['user_id']]);
            $queries->delete('users_session', ['user_id', $params['user_id']]);

            // Profile fields
            $queries->delete('users_profile_fields', ['user_id', $params['user_id']]);

            // Username history
            $queries->delete('users_username_history', ['user_id', $params['user_id']]);

            // Profile wall posts
            $queries->delete('user_profile_wall_posts', ['user_id', $params['user_id']]);
            $queries->delete('user_profile_wall_posts', ['author_id', $params['user_id']]);
            $queries->delete('user_profile_wall_posts_reactions', ['user_id', $params['user_id']]);
            $queries->delete('user_profile_wall_posts_replies', ['author_id', $params['user_id']]);

            // Oauth
            $queries->delete('oauth_users', ['user_id', $params['user_id']]);

            // User Integrations
            $queries->delete('users_integrations', ['user_id', $params['user_id']]);
        }
    }
}
