<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum class
 */
 
class Forum {
	private $_db,
			$_data;
	
	// Constructor, connect to database
	public function __construct() {
		$this->_db = DB::getInstance();
	}
	
	// Returns an array of forums a user can access, including topic information
	// Params: $group_id (integer) - group id of the user, optional
	public function listAllForums($group_id = null){
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Get the forums the user can view based on their group ID
		$access = $this->_db->get("forums_permissions", array("group_id", "=", $group_id))->results();
		
		$return = array(); // Array to return containing forums
		$parents = array(); // Array containing a list of parent forums
		
		// Get the forum names
		foreach($access as $forum){
			// Can they view it?
			if($forum->view == 1){
				// Get the name..
				$forum_query = $this->_db->get("forums", array("id", "=", $forum->forum_id))->results();
				if(count($forum_query)){
					$forum_id = $forum_query[0]->id;

					// First, get a list of parent forums
					if($forum_query[0]->parent == 0){
						$return[$forum_id]['description'] = Output::getClean($forum_query[0]->forum_description);
						$return[$forum_id]['title'] = Output::getClean($forum_query[0]->forum_title);
						$return[$forum_id]['order'] = $forum_query[0]->forum_order;
					}
				}
			}
		}
		
		// Loop through again and add to parent forums array
		foreach($access as $forum){
			// Can they view it?
			if($forum->view == 1){
				// Get the name..
				$forum_query = $this->_db->get("forums", array("id", "=", $forum->forum_id))->results();

				if(!count($forum_query)) continue;
				
				// Ensure it's not a parent forum
				if($forum_query[0]->parent != 0){
					// Get name of parent forum
					$parent_id = $this->_db->get("forums", array("id", "=", $forum_query[0]->parent))->results();
					$parent_id = $parent_id[0]->id;
					
					// Is the parent already in the parents array?
					// If not, the forum is a subforum which we won't display
					if(array_key_exists($parent_id, $return)){
						$return[$parent_id]['subforums'][$forum_query[0]->id] = $forum_query[0]; // add to return array
						// Purify title/description
						$return[$parent_id]['subforums'][$forum_query[0]->id]->forum_title = Output::getClean($return[$parent_id]['subforums'][$forum_query[0]->id]->forum_title);
						$return[$parent_id]['subforums'][$forum_query[0]->id]->forum_description = Output::getClean($return[$parent_id]['subforums'][$forum_query[0]->id]->forum_description);
						
						$return[$parent_id]['subforums'][$forum_query[0]->id]->link = URL::build('/forum/view_forum/', 'fid=' . $forum_query[0]->id); // build link to forum
						
						// Get topic/post count
						$topics = $this->_db->orderWhere('topics', 'forum_id = ' . $forum_query[0]->id . ' AND deleted = 0', 'id', 'ASC')->results();
						$topics = count($topics);
						$return[$parent_id]['subforums'][$forum_query[0]->id]->topics = $topics;

						$posts = $this->_db->orderWhere('posts', 'forum_id = ' . $forum_query[0]->id . ' AND deleted = 0', 'id', 'ASC')->results();
						$posts = count($posts);
						$return[$parent_id]['subforums'][$forum_query[0]->id]->posts = $posts;
						
						if($forum_query[0]->last_topic_posted){
							// Last reply
							$last_reply = $this->_db->orderWhere('posts', 'topic_id = ' . $forum_query[0]->last_topic_posted, 'post_date', 'DESC')->results();
							if(count($last_reply)){
								$n = 0;
								while(isset($last_reply[$n]) && $last_reply[$n]->deleted == 1){
									$n++;
								}
								
								if(!isset($last_reply[$n])) continue;
								
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post = $last_reply[$n];
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->link = URL::build('/forum/view_topic/', 'tid=' . $last_reply[$n]->topic_id . '&amp;pid=' . $last_reply[0]->id);
								
								// Title
								$last_topic = $this->_db->get('topics', array('id', '=', $last_reply[$n]->topic_id))->results();
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->title = Output::getClean($last_topic[0]->topic_title);
								
								// Last reply username, profile link and avatar
								$last_reply_user = $this->_db->get('users', array('id', '=', $last_reply[$n]->post_creator))->results();
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->username = Output::getClean($last_reply_user[0]->nickname);
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->mcname = Output::getClean($last_reply_user[0]->username);
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->profile = URL::build('/profile/' . Output::getClean($last_reply_user[0]->username));
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->avatar = '';
								$return[$parent_id]['subforums'][$forum_query[0]->id]->last_post->date_friendly = '';
							}
						}
					}
				}
			}
		}
		
		// Sort forums
		uasort($return, function($a, $b) {
			return $a['order'] - $b['order'];
		});
		
		return $return;
	}
	
	// Returns an array of forums a user can access, in order
	// Params: $group_id (integer) - group id of the user
	public function orderAllForums($group_id = null){
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Get the forums the user can view based on their group ID
		$access = $this->_db->get("forums_permissions", array("group_id", "=", $group_id))->results();
		
		$return = array(); // Array to return containing forums
		
		// Get the forum information as an array
		foreach($access as $forum){
			// Can they view it?
			if($forum->view == 1){
				// Get the name..
				$forum_query = $this->_db->get("forums", array("id", "=", $forum->forum_id))->results();

				// Is it a parent category?
				if($forum_query[0]->parent != 0){ // No
					$return[] = (array) $forum_query[0];
				}
			}
		}
		
		usort($return, function($a, $b) {
			return $a['forum_order'] - $b['forum_order'];
		});
		
		return $return;
	}
	
	
	// Returns an array of the latest discussions a user can access (10 from each category)
	// Params: $group_id (integer) - group id of the user
	public function getLatestDiscussions($group_id = null){
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Get the forums the user can view based on their group ID
		$access = $this->_db->get("forums_permissions", array("group_id", "=", $group_id))->results();

		$return = array(); // Array to return containing discussions
		
		// Get the discussions
		foreach($access as $forum){
			// Can they view it?
			if($forum->view == 1){
				// Get a list of discussions
				$discussions_query = $this->_db->orderWhere("topics", "forum_id = " . $forum->forum_id . " AND deleted = 0", "topic_reply_date", "DESC")->results();
				foreach($discussions_query as $discussion){
					$return[] = (array) $discussion;
				}
			}
		}
		
		// Order the discussions by date - most recent first
		usort($return, function($a, $b) {
			return $b['topic_reply_date'] - $a['topic_reply_date'];
		});
		
		return $return;
	}
	
	// Returns true/false, depending on whether the specified forum exists and whether the user can view it
	// Params: $forum_id (integer) - forum id to check, $group_id (integer) - group id of the user
	public function forumExist($forum_id, $group_id = null){
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Does the forum exist?
		$exists = $this->_db->get("forums", array("id", "=", $forum_id))->results();
		if(count($exists)){
			// Can the user view it?
			$access = $this->_db->get("forums_permissions", array("forum_id", "=", $forum_id))->results();
			
			foreach($access as $item){
				if($item->group_id == $group_id){
					if($item->view == 1){
						return true;
					}
				}
			}
		}

		return false;
	}

	// Returns true/false, depending on whether the specified topic exists and whether the user can view it
	// Params: $topic_id (integer) - topic id to check, $group_id (integer) - group id of the user
	public function topicExist($topic_id, $group_id = null) {
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Does the topic exist?
		$exists = $this->_db->get("topics", array("id", "=", $topic_id))->results();
		if(count($exists)){
			// Can the user view it?
			$forum_id = $exists[0]->forum_id;
			$access = $this->_db->get("forums_permissions", array("forum_id", "=", $forum_id))->results();
			
			foreach($access as $item){
				if($item->group_id == $group_id){
					if($item->view == 1){
						return true;
					}
				}
			}
		}

		return false;
	}

	// Returns true/false, depending on whether the user's group can create a topic in a specified forum
	// Params: $forum_id (integer) - forum id to check, $group_id (integer) - group id of the user
	public function canPostTopic($forum_id, $group_id = null) {
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Get the forum's permissions
		$permissions = $this->_db->get("forums_permissions", array("forum_id", "=", $forum_id))->results();
		if(count($permissions)){
			foreach($permissions as $permission){
				if($permission->group_id == $group_id && $permission->create_topic == 1){
					return true;
				}
			}
		}

		return false;
	}

	// Returns true/false, depending on whether the user's group can create a reply to a topic in a specified forum
	// Params: $forum_id (integer) - forum id to check, $group_id (integer) - group id of the user
	public function canPostReply($forum_id, $group_id = null) {
		if($group_id == null){
			$group_id = 0; // Guest
		}
		// Get the forum's permissions
		$permissions = $this->_db->get("forums_permissions", array("forum_id", "=", $forum_id))->results();
		if(count($permissions)){
			foreach($permissions as $permission){
				if($permission->group_id == $group_id && $permission->create_post == 1){
					return true;
				}
			}
		}

		return false;
	}

	// Updates the latest post column in all forums. Used when a reply/topic is deleted
	public function updateForumLatestPosts(){
		$forums = $this->_db->get('forums', array('id', '<>', 0))->results();
		$latest_posts = array();
		$n = 0;
	
		foreach($forums as $item){
			if($item->parent != 0){
				$latest_post_query = $this->_db->orderWhere('posts', 'forum_id = ' . $item->id, 'post_date', 'DESC')->results();
				
				if(!empty($latest_post_query)){
					foreach($latest_post_query as $latest_post){
						if($latest_post->deleted != 1){		
							// Ensure topic isn't deleted
							$topic_query = $this->_db->get('topics', array('id', '=', $latest_post->topic_id))->results();
							
							if(empty($topic_query)) continue;
							
							$latest_posts[$n]["forum_id"] = $item->id;
							$latest_posts[$n]["date"] = $latest_post->post_date;
							$latest_posts[$n]["author"] = $latest_post->post_creator;
							$latest_posts[$n]["topic_id"] = $latest_post->topic_id;
							
							break;
						}
					}
				}

				if(!isset($latest_posts[$n])){
					$latest_posts[$n]["forum_id"] = $item->id;
					$latest_posts[$n]["date"] = null;
					$latest_posts[$n]["author"] = null;
					$latest_posts[$n]["topic_id"] = null;
				}
				
				$n++;
			}
		}
	
		$forums = null;
	
		if(count($latest_posts)){
			foreach($latest_posts as $latest_post){
				$this->_db->update('forums', $latest_post["forum_id"], array(
					'last_post_date' => $latest_post["date"],
					'last_user_posted' => $latest_post["author"],
					'last_topic_posted' => $latest_post["topic_id"]
				));
			}
		}
	
		$latest_posts = null;
	
		return true;
	}
	
	// Updates the latest post column in all topics
	public function updateTopicLatestPosts(){
		$topics = $this->_db->get('topics', array('id', '<>', 0))->results();
		$latest_posts = array();
		$n = 0;
	
		foreach($topics as $topic){
			$latest_post_query = $this->_db->orderWhere('posts', 'topic_id = ' . $topic->id, 'post_date', 'DESC')->results();
			
			if(count($latest_post_query)){
				foreach($latest_post_query as $latest_post){
					if($latest_post->deleted != 1){
						$latest_posts[$n]["topic_id"] = $topic->id;
						$latest_posts[$n]["date"] = $latest_post->post_date;
						$latest_posts[$n]["author"] = $latest_post->post_creator;
						
						break;
					}
				}
			}
	
			$n++;
		}
	
		foreach($latest_posts as $latest_post){
			if(!empty($latest_post["date"])){
				$this->_db->update('topics', $latest_post["topic_id"], array(
					'topic_reply_date' => date('U', strtotime($latest_post["date"])),
					'topic_last_user' => $latest_post["author"]
				));
			}
		}

		return true;
	}

	// Returns a string containing the title of a specified forum
	// Params: $forum_id (integer) - forum id to check
	public function getForumTitle($forum_id) {
		$data = $this->_db->get('forums', array('id', '=', $forum_id))->results();
		return $data[0]->forum_title;
	}

	// Returns database entries containing the reputation of a specified post
	// Params: $post_id (integer) - post id to check
	public function getReputation($post_id) {
		$data = $this->_db->get('reputation', array('post_id', '=', $post_id));
		return $data->results();
	}

	// Returns an array containing information about a specified post
	// Params: $post_id (integer) - post id to check
	public function getIndividualPost($post_id) {
		$data = $this->_db->get('posts', array('id', '=', $post_id))->results();
		if(count($data)) {
			return(array(
				'creator' => $data[0]->post_creator,
				'content' => $data[0]->post_content,
				'date' => $data[0]->post_date,
				'forum_id' => $data[0]->forum_id,
				'topic_id' => $data[0]->topic_id
			));
		}
		return false;
	}

	// Returns an array of the latest news items
	// Params: $number (integer) - number to return (max recommended 10)
	public function getLatestNews($number = 5) {
		$news_forums = $this->_db->get('forums', array('news', '=', 1))->results(); // List news forums

		$return = array(); // Array to return containing news
		foreach($news_forums as $news_forum){
			$news_items = $this->_db->orderWhere("topics", "forum_id = " . $news_forum->id, "topic_date", "DESC LIMIT 10")->results();
			
			foreach($news_items as $item){
				if($item->deleted == 1) continue;
				
				$news_post = $this->_db->get("posts", array("topic_id", "=", $item->id))->results();
				$posts = count($news_post);
				$topic_date = $news_post[0]->post_date;
				$post = $news_post[0]->post_content;
				$return[] = array(
					"topic_id" => $item->id,
					"topic_date" => $topic_date,
					"topic_title"=> $item->topic_title,
					"topic_views" => $item->topic_views,
					"author" => $item->topic_creator,
					"content" => $post,
					"replies" => $posts
				);
			}
		}

		// Order the discussions by date - most recent first
		usort($return, function($a, $b) {
			return $b['topic_date'] - $a['topic_date'];
		});

		return array_slice($return, 0, $number, true);
	}
	
	// Can the user moderate the specified forum?
	// Params:  $group_id (integer) - group ID of the user
	//			$forum_id (integer) - forum ID to check
	public function canModerateForum($group_id = null, $forum_id = null){
		if(!$group_id || !$forum_id) return false;
		
		$permissions = $this->_db->get('forums_permissions', array('forum_id', '=', $forum_id))->results();
		
		// Check the forum
		foreach($permissions as $permission){
			if($permission->group_id == $group_id){
				if($permission->moderate == 1) return true;
				else return false;
			}
		}
	}
	
	// Returns all posts in topic
	// Params: $tid (integer) - topic ID to retrieve post from
	public function getPosts($tid = null){
		if($tid){
			// Get posts from database
			$posts = $this->_db->get('posts', array('topic_id', '=', $tid));
			
			if($posts->count()){
				$posts = $posts->results();
				
				// Remove deleted posts
				foreach($posts as $key => $post){
					if($post->deleted == 1) unset($posts[$key]);
				}
				
				return array_values($posts);
			}
		}
		return false;
	}
}