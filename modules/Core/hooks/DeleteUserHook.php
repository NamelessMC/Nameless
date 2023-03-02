<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Delete user event listener for Core module
 */

class DeleteUserHook {

    public static function execute(UserDeletedEvent $event): void {
        $user_id = $event->user->data()->id;
        $db = DB::getInstance();

        // Delete the user
        $db->delete('users', ['id', $user_id]);

        // All the below tables have foreign key constrains which
        // should be deleted after deleting the user from nl2_users, but since these
        // keys were added in a later update, we cannot be sure they exist, hence needing to
        // delete them manually still. Maybe in the future we can rely more on the foreign keys.

        // Groups
        $db->delete('users_groups', ['user_id', $user_id]);

        // IP logs
        $db->delete('users_ips', ['user_id', $user_id]);

        // Logs
        $db->delete('logs', ['user_id', $user_id]);

        // Alerts
        $db->delete('alerts', ['user_id', $user_id]);

        // Blocked users
        $db->delete('blocked_users', ['user_id', $user_id]);
        $db->delete('blocked_users', ['user_blocked_id', $user_id]);

        // Email errors
        $db->delete('email_errors', ['user_id', $user_id]);

        // Infractions
        $db->delete('infractions', ['punished', $user_id]);
        $db->delete('infractions', ['staff', $user_id]);

        // Private messages
        $db->delete('private_messages', ['author_id', $user_id]);
        $db->delete('private_messages_replies', ['author_id', $user_id]);
        $db->delete('private_messages_users', ['user_id', $user_id]);

        // Reports
        $db->delete('reports', ['reporter_id', $user_id]);
        $db->delete('reports', ['reported_id', $user_id]);
        $db->delete('reports_comments', ['commenter_id', $user_id]);

        // User sessions
        $db->delete('users_session', ['user_id', $user_id]);

        // Profile fields
        $db->delete('users_profile_fields', ['user_id', $user_id]);

        // Username history
        $db->delete('users_username_history', ['user_id', $user_id]);

        // Profile wall posts
        $db->delete('user_profile_wall_posts', ['user_id', $user_id]);
        $db->delete('user_profile_wall_posts', ['author_id', $user_id]);
        $db->delete('user_profile_wall_posts_reactions', ['user_id', $user_id]);
        $db->delete('user_profile_wall_posts_replies', ['author_id', $user_id]);

        // Oauth
        $db->delete('oauth_users', ['user_id', $user_id]);

        // User Integrations
        $db->delete('users_integrations', ['user_id', $user_id]);
    }
}
