<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum class
 */

class Forum {

    /** @var DB */
    private $_db;

    // Constructor, connect to database
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    // Returns an array of forums a user can access, including topic information
    // Params: $groups (array) - user groups
    public function listAllForums($groups = array(0), $user_id = null) {
        if (in_array(0, $groups)) {
            $user_id = 0;
        }

        if (!$user_id) {
            $user_id = 0;
        }

        // Get a list of parent forums
        $parent_forums = $this->_db->orderWhere('forums', 'parent = 0', 'forum_order', 'ASC')->results();

        $return = array();

        if (count($parent_forums)) {
            foreach ($parent_forums as $forum) {
                if ($this->forumExist($forum->id, $groups)) {
                    $return[$forum->id]['description'] = Output::getClean($forum->forum_description);
                    $return[$forum->id]['title'] = Output::getClean($forum->forum_title);
                    $return[$forum->id]['icon'] = Output::getPurified(Output::getDecoded($forum->icon));

                    // Get subforums
                    $forums = $this->_db->orderWhere('forums', 'parent = ' . $forum->id, 'forum_order', 'ASC')->results();
                    if (count($forums)) {
                        foreach ($forums as $item) {
                            if ($this->forumExist($item->id, $groups)) {
                                $return[$forum->id]['subforums'][$item->id] = $item;
                                $return[$forum->id]['subforums'][$item->id]->forum_title = Output::getClean($item->forum_title);
                                $return[$forum->id]['subforums'][$item->id]->forum_description = Output::getClean($item->forum_description);
                                $return[$forum->id]['subforums'][$item->id]->icon = Output::getPurified(Output::getDecoded($item->icon));
                                $return[$forum->id]['subforums'][$item->id]->link = URL::build('/forum/view/' . $item->id . '-' . $this->titleToURL($item->forum_title));
                                $return[$forum->id]['subforums'][$item->id]->redirect_to = Output::getClean(htmlspecialchars_decode($item->redirect_url));

                                // Get topic/post count
                                $topics = $this->_db->orderWhere('topics', 'forum_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $topics = count($topics);
                                $return[$forum->id]['subforums'][$item->id]->topics = $topics;

                                $posts = $this->_db->orderWhere('posts', 'forum_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $posts = count($posts);
                                $return[$forum->id]['subforums'][$item->id]->posts = $posts;

                                // Can the user view other topics
                                if ($this->canViewOtherTopics($item->id, $groups) || $item->last_user_posted == $user_id) {
                                    if ($item->last_topic_posted) {
                                        // Last reply
                                        $last_reply = $this->_db->orderWhere('posts', 'topic_id = ' . $item->last_topic_posted, 'created', 'DESC')->results();
                                    } else {
                                        $last_reply = null;
                                    }
                                } else {
                                    $last_topic = $this->_db->orderWhere('topics', 'forum_id = ' . $item->id . ' AND deleted = 0 AND topic_creator = ' . $user_id, 'topic_reply_date', 'DESC')->results();
                                    if (count($last_topic)) {
                                        $last_reply = $this->_db->orderWhere('posts', 'topic_id = ' . $last_topic[0]->id, 'created', 'DESC')->results();
                                    } else {
                                        $last_reply = null;
                                    }
                                }

                                if (isset($last_reply) && !is_null($last_reply) && count($last_reply)) {
                                    $n = 0;
                                    while (isset($last_reply[$n]) && $last_reply[$n]->deleted == 1) {
                                        $n++;
                                    }

                                    if (!isset($last_reply[$n])) continue;

                                    // Title
                                    $last_topic = $this->_db->get('topics', array('id', '=', $last_reply[$n]->topic_id))->results();

                                    $return[$forum->id]['subforums'][$item->id]->last_post = $last_reply[$n];
                                    $return[$forum->id]['subforums'][$item->id]->last_post->title = Output::getClean($last_topic[0]->topic_title);
                                    $return[$forum->id]['subforums'][$item->id]->last_post->link = URL::build('/forum/topic/' . $last_reply[$n]->topic_id . '-' . $this->titleToURL($last_topic[0]->topic_title), 'pid=' . $last_reply[0]->id);
                                }

                                // Get list of subforums (names + links)
                                $subforums = $this->_db->orderWhere('forums', 'parent = ' . $item->id, 'forum_order', 'ASC')->results();
                                if (count($subforums)) {
                                    foreach ($subforums as $subforum) {
                                        if ($this->forumExist($subforum->id, $groups)) {
                                            if (!isset($return[$forum->id]['subforums'][$item->id]->subforums))
                                                $return[$forum->id]['subforums'][$item->id]->subforums = array();
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id] = new stdClass();
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->title = Output::getClean($subforum->forum_title);
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->link = URL::build('/forum/view/' . $subforum->id . '-' . $this->titleToURL($subforum->forum_title));
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->icon = Output::getPurified(Output::getDecoded($subforum->icon));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    // Returns an array of the latest 50 discussions a user can access
    // Params: $groups (array) - user groups
    public function getLatestDiscussions($groups = array(0), $user_id = null) {
        if (!$user_id) {
            $user_id = 0;
        }

        $all_topics_forums = DB::getInstance()->query("SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (" . rtrim(implode(',', $groups), ',') . ") AND `view` = 1 AND view_other_topics = 1")->results();

        if ($user_id > 0) {
            $own_topics_forums = DB::getInstance()->query("SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (" . rtrim(implode(',', $groups), ',') . ") AND `view` = 1 AND view_other_topics = 0")->results();
        } else {
            $own_topics_forums = array();
        }

        if (!count($all_topics_forums) && !count($own_topics_forums))
            return array();

        $all_topics_forums_string = '(';
        foreach ($all_topics_forums as $forum) {
            $all_topics_forums_string .= $forum->forum_id . ',';
        }
        $all_topics_forums_string = rtrim($all_topics_forums_string, ',');
        $all_topics_forums_string .= ')';

        try {
            if (count($own_topics_forums)) {

                $own_topics_forums_string = '(';
                foreach ($own_topics_forums as $forum) {
                    $own_topics_forums_string .= $forum->forum_id . ',';
                }
                $own_topics_forums_string = rtrim($own_topics_forums_string, ',');
                $own_topics_forums_string .= ')';

                $query = DB::getInstance()->query("(
		        SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.forum_id IN " . $all_topics_forums_string . " ORDER BY topics.topic_reply_date DESC LIMIT 50
		        ) UNION (
		        SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND ((topics.forum_id IN " . $own_topics_forums_string . " AND topics.topic_creator = ?) OR topics.sticky = 1) ORDER BY topics.topic_reply_date DESC LIMIT 50
		        ) ORDER BY topic_reply_date DESC LIMIT 50", array($user_id), PDO::FETCH_ASSOC)->results();
            } else {
                $query = DB::getInstance()->query("SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.forum_id IN " . $all_topics_forums_string . " ORDER BY topics.topic_reply_date DESC LIMIT 50", array(), PDO::FETCH_ASSOC)->results();
            }
        } catch (Exception $e) {
            // Likely no permissions to view any forums
            $query = [];
        }

        return $query;
    }

    // Returns true/false, depending on whether the specified forum exists and whether the user can view it
    // Params: $forum_id (integer) - forum id to check, $groups (array) - user groups
    public function forumExist($forum_id, $groups = array(0)) {
        // Does the forum exist?
        $exists = $this->_db->get("forums", array("id", "=", $forum_id))->results();
        if (count($exists)) {
            return $this->hasPermission($forum_id, 'view', $groups);
        }

        return false;
    }

    // Returns true/false, depending on whether the specified topic exists
    // Params: $topic_id (integer) - topic id to check
    public function topicExist($topic_id) {
        // Does the topic exist?
        $exists = $this->_db->get("topics", array("id", "=", $topic_id))->results();
        return count($exists) > 0;
    }

    // Returns true/false depending on whether the current user can view a forum
    // Params: $forum_id (integer) - forum id to check, $groups (array) - user groups
    public function canViewForum($forum_id, $groups = array(0)) {
        return $this->hasPermission($forum_id, 'view', $groups);

    }

    // Returns true/false, depending on whether the user's group can create a topic in a specified forum
    // Params: $forum_id (integer) - forum id to check, $groups (array) - user groups
    public function canPostTopic($forum_id, $groups = array(0)) {
        return $this->hasPermission($forum_id, 'create_topic', $groups);
    }

    // Returns true/false, depending on whether the user's group can create a reply to a topic in a specified forum
    // Params: $forum_id (integer) - forum id to check, $groups (array) - user groups
    public function canPostReply($forum_id, $groups = array(0)) {
        return $this->hasPermission($forum_id, 'create_post', $groups);
    }

    public function canEditTopic($forum_id, $groups = array(0)) {
        return $this->hasPermission($forum_id, 'edit_topic', $groups);
    }

    private function hasPermission($forum_id, $required_permission, $groups) {
        $permissions = $this->_db->get('forums_permissions', array('forum_id', '=', $forum_id))->results();
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->$required_permission == 1)
                    return true;
            }
        }
        return false;
    }

    // Updates the latest post column in all forums. Used when a reply/topic is deleted
    public function updateForumLatestPosts() {
        $forums = $this->_db->get('forums', array('id', '<>', 0))->results();
        $latest_posts = array();
        $n = 0;

        foreach ($forums as $item) {
            if ($item->parent != 0) {
                $latest_post_query = $this->_db->orderWhere('posts', 'forum_id = ' . $item->id, 'post_date', 'DESC')->results();

                if (!empty($latest_post_query)) {
                    foreach ($latest_post_query as $latest_post) {
                        if ($latest_post->deleted != 1) {
                            // Ensure topic isn't deleted
                            $topic_query = $this->_db->get('topics', array('id', '=', $latest_post->topic_id))->results();

                            if (empty($topic_query)) continue;

                            $latest_posts[$n]["forum_id"] = $item->id;
                            if ($latest_post->created)
                                $latest_posts[$n]["date"] = $latest_post->created;
                            else
                                $latest_posts[$n]["date"] = strtotime($latest_post->post_date);
                            $latest_posts[$n]["author"] = $latest_post->post_creator;
                            $latest_posts[$n]["topic_id"] = $latest_post->topic_id;

                            break;
                        }
                    }
                }

                if (!isset($latest_posts[$n])) {
                    $latest_posts[$n]["forum_id"] = $item->id;
                    $latest_posts[$n]["date"] = null;
                    $latest_posts[$n]["author"] = null;
                    $latest_posts[$n]["topic_id"] = null;
                }

                $n++;
            }
        }

        $forums = null;

        if (count($latest_posts)) {
            foreach ($latest_posts as $latest_post) {
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
    public function updateTopicLatestPosts() {
        $topics = $this->_db->get('topics', array('id', '<>', 0))->results();
        $latest_posts = array();
        $n = 0;

        foreach ($topics as $topic) {
            $latest_post_query = $this->_db->orderWhere('posts', 'topic_id = ' . $topic->id, 'post_date', 'DESC')->results();

            if (count($latest_post_query)) {
                foreach ($latest_post_query as $latest_post) {
                    if ($latest_post->deleted != 1) {
                        $latest_posts[$n]["topic_id"] = $topic->id;

                        if ($latest_post->created != null)
                            $latest_posts[$n]["date"] = $latest_post->created;
                        else
                            $latest_posts[$n]["date"] = strtotime($latest_post->post_date);

                        $latest_posts[$n]["author"] = $latest_post->post_creator;

                        break;
                    }
                }
            }

            $n++;
        }

        foreach ($latest_posts as $latest_post) {
            if (!empty($latest_post["date"])) {
                $this->_db->update('topics', $latest_post["topic_id"], array(
                    'topic_reply_date' => $latest_post["date"],
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

    // Returns an array containing information about a specified post
    // Params: $post_id (integer) - post id to check
    public function getIndividualPost($post_id) {
        $data = $this->_db->get('posts', array('id', '=', $post_id))->results();
        if (count($data)) {
            return (array(
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
    // Params: $number (integer) - number to return (max 10)
    public function getLatestNews($number = 5) {
        $return = array(); // Array to return containing news
        $labels_cache = array(); // Array to contain labels

        $news_items = $this->_db->query("SELECT * FROM nl2_topics WHERE forum_id IN (SELECT id FROM nl2_forums WHERE news = 1) AND deleted = 0 ORDER BY topic_date DESC LIMIT 10")->results();

        foreach ($news_items as $item) {
            $news_post = $this->_db->get("posts", array("topic_id", "=", $item->id))->results();
            $posts = count($news_post);

            if (is_null($news_post[0]->created)) {
                $post_date = date('d M Y, H:i', strtotime($news_post[0]->post_date));
            } else {
                $post_date = date('d M Y, H:i', $news_post[0]->created);
            }

            $labels = array();

            if ($item->labels) {
                // Get label
                $label_ids = explode(',', $item->labels);

                if (count($label_ids)) {
                    foreach ($label_ids as $label_id) {
                        if (isset($labels_cache[$label_id])) {
                            $labels[] = $labels_cache[$label_id];
                        } else {
                            $label = $this->_db->get('forums_topic_labels', array('id', '=', $label_id));
                            if ($label->count()) {
                                $label = $label->first();

                                $label_html = $this->_db->get('forums_labels', array('id', '=', $label->label));

                                if ($label_html->count()) {
                                    $label_html = $label_html->first()->html;
                                    $label = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html));
                                } else $label = '';
                            } else $label = '';

                            $labels_cache[$label_id] = $label;
                            $labels[] = $label;
                        }
                    }
                }
            }

            $post = $news_post[0]->post_content;
            $return[] = array(
                "topic_id" => $item->id,
                "topic_date" => $post_date,
                "topic_title" => $item->topic_title,
                "topic_views" => $item->topic_views,
                "author" => $item->topic_creator,
                "content" => Util::truncate(Output::getDecoded($post)),
                "replies" => $posts,
                'label' => $labels[0],
                'labels' => $labels
            );
        }

        // Order the discussions by date - most recent first
        usort($return, function ($a, $b) {
            return strtotime($b['topic_date']) - strtotime($a['topic_date']);
        });

        return array_slice($return, 0, $number, true);
    }

    // Can the user moderate the specified forum?
    // Params:  $forum_id (integer) - forum ID to check
    //			$groups (array) - user groups
    public function canModerateForum($forum_id = null, $groups = array(0)) {
        if (in_array(0, $groups) || !$forum_id) return false;

        $permissions = $this->_db->get('forums_permissions', array('forum_id', '=', $forum_id))->results();

        // Check the forum
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->moderate == 1) return true;
            }
        }

        return false;
    }

    // Returns all posts in topic
    // Params: $tid (integer) - topic ID to retrieve post from
    public function getPosts($tid = null) {
        if ($tid) {
            // Get posts from database
            $posts = $this->_db->get('posts', array('topic_id', '=', $tid));

            if ($posts->count()) {
                $posts = $posts->results();

                // Remove deleted posts
                foreach ($posts as $key => $post) {
                    if ($post->deleted == 1) unset($posts[$key]);
                }

                return array_values($posts);
            }
        }
        return false;
    }

    // Transform a topic title to URL-ify it
    public function titleToURL($topic = null) {
        if ($topic) {
            $topic = Util::cyrillicToLatin($topic);
            return Output::getClean(strtolower(urlencode(str_replace(' ', '-', htmlspecialchars_decode($topic)))));
        }

        return '';
    }

    // Can the user view other topics in a forum?
    // Params: $forum_id - forum ID (int), $groups (array) - user groups
    public function canViewOtherTopics($forum_id, $groups = array(0)) {
        // Does the forum exist?
        $exists = $this->_db->get("forums", array("id", "=", $forum_id))->results();
        if (count($exists)) {
            // Can the user view other topics?
            $access = $this->_db->get("forums_permissions", array("forum_id", "=", $forum_id))->results();

            foreach ($access as $item) {
                if (in_array($item->group_id, $groups)) {
                    if ($item->view_other_topics == 1) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    // Get any subforums at any level for a forum
    // Params: $forum_id - forum ID (int), $groups (array) - user groups
    public function getAnySubforums($forum_id, $groups = array(0), $depth = 0) {
        if ($depth == 10) {
            return array();
        }

        $ret = array();

        $subforums_query = $this->_db->query('SELECT * FROM nl2_forums WHERE parent = ? ORDER BY forum_order ASC', array($forum_id));

        if (!$subforums_query->count()) {
            return $ret;
        }

        foreach ($subforums_query->results() as $result) {
            if ($this->forumExist($result->id, $groups)) {
                $to_add = new stdClass();
                $to_add->id = Output::getClean($result->id);
                $to_add->forum_title = Output::getClean($result->forum_title);
                $to_add->category = false;
                $ret[] = $to_add;

                $subforums = $this->getAnySubforums($result->id, $groups, ++$depth);

                if (count($subforums)) {
                    foreach ($subforums as $subforum) {
                        $ret[] = $subforum;
                    }
                }
            }
        }

        return $ret;
    }
}
