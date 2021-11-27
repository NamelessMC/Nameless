<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Delete user event listener for Forum module
 */

class DeleteUserForumHook {

	public static function execute(array $params = []): void {
		if (isset($params['user_id']) && $params['user_id'] > 1) {
			$queries = new Queries();

			// Delete the user's posts
			$queries->delete('posts', ['post_creator', '=', $params['user_id']]);

			// Delete the user's topics
			$queries->delete('topics', ['topic_creator', '=', $params['user_id']]);

			// Forum reactions
			$queries->delete('forums_reactions', ['user_received', '=', $params['user_id']]);
			$queries->delete('forums_reactions', ['user_given', '=', $params['user_id']]);

			// Topics following
			$queries->delete('topics_following', ['user_id', '=', $params['user_id']]);
		}
	}
}