<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Delete user event listener for Forum module
 */

class DeleteUserForumHook implements Listener {

	public static function execute(array $params = array()): void {
		if (isset($params['user_id']) && $params['user_id'] > 1) {
			$queries = new Queries();

			// Delete the user's posts
			$queries->delete('posts', array('post_creator', '=', $params['user_id']));

			// Delete the user's topics
			$queries->delete('topics', array('topic_creator', '=', $params['user_id']));

			// Forum reactions
			$queries->delete('forums_reactions', array('user_received', '=', $params['user_id']));
			$queries->delete('forums_reactions', array('user_given', '=', $params['user_id']));

			// Topics following
			$queries->delete('topics_following', array('user_id', '=', $params['user_id']));
		}
	}
}