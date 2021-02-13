<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Delete user hook handler class for Core module
 */

class DeleteUserHook {
    
    // Execute hook
    public static function deleteUser($params = array()) {
        if (isset($params['user_id']) && $params['user_id'] > 1) {
            $queries = new Queries();

            // Delete the user
            $queries->delete('users', array('id', '=', $params['user_id']));

            // Groups
            $queries->delete('users_groups', array('user_id', '=', $params['user_id']));

            // IP logs
            $queries->delete('users_ips', array('user_id', '=', $params['user_id']));

            // Logs
            $queries->delete('logs', array('user_id', '=', $params['user_id']));

            // Alerts
            $queries->delete('alerts', array('user_id', '=', $params['user_id']));

            // Blocked users
            $queries->delete('blocked_users', array('user_id', '=', $params['user_id']));
            $queries->delete('blocked_users', array('user_blocked_id', '=', $params['user_id']));

            // Email errors
            $queries->delete('email_errors', array('user_id', '=', $params['user_id']));

            // Friends
            $queries->delete('friends', array('user_id', '=', $params['user_id']));
            $queries->delete('friends', array('friend_id', '=', $params['user_id']));

            // Infractions
            $queries->delete('infractions', array('punished', '=', $params['user_id']));
            $queries->delete('infractions', array('staff', '=', $params['user_id']));

            // Private messages
            $queries->delete('private_messages', array('author_id', '=', $params['user_id']));
            $queries->delete('private_messages_replies', array('author_id', '=', $params['user_id']));
            $queries->delete('private_messages_users', array('user_id', '=', $params['user_id']));

            // Reports
            $queries->delete('reports', array('reporter_id', '=', $params['user_id']));
            $queries->delete('reports_comments', array('commenter_id', '=', $params['user_id']));

            // User sessions
            $queries->delete('users_admin_session', array('user_id', '=', $params['user_id']));
            $queries->delete('users_session', array('user_id', '=', $params['user_id']));

            // Profile fields
            $queries->delete('users_profile_fields', array('user_id', '=', $params['user_id']));

            // Username history
            $queries->delete('users_username_history', array('user_id', '=', $params['user_id']));

            // Profile wall posts
            $queries->delete('user_profile_wall_posts', array('user_id', '=', $params['user_id']));
            $queries->delete('user_profile_wall_posts', array('author_id', '=', $params['user_id']));
            $queries->delete('user_profile_wall_posts_reactions', array('user_id', '=', $params['user_id']));
            $queries->delete('user_profile_wall_posts_replies', array('author_id', '=', $params['user_id']));
        }

        return true;
    }
}
