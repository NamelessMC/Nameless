<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Delete user event listener for Forum module
 */

class DeleteUserForumHook {

    public static function execute(UserDeletedEvent $event): void {
        $user_id = $event->user->data()->id;

        $db = DB::getInstance();

        // Delete the user's posts
        $db->delete('posts', ['post_creator', $user_id]);

        // Delete the user's topics
        $db->delete('topics', ['topic_creator', $user_id]);

        // Forum reactions
        $db->delete('forums_reactions', ['user_received', $user_id]);
        $db->delete('forums_reactions', ['user_given', $user_id]);

        // Topics following
        $db->delete('topics_following', ['user_id', $user_id]);
    }
}
