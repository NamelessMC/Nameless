<?php
/**
 * Forum class
 *
 * @package Modules\Forum
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class Forum {

    private DB $_db;
    private static array $_permission_cache = [];
    private static array $_count_cache = [];

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Get an array of forums a user can access, including topic information
     *
     * @param array $groups Users groups
     * @param int $user_id User ID
     * @return array Array of forums a user can access
     */
    public function listAllForums(array $groups = [0], int $user_id = 0): array {
        if (in_array(0, $groups)) {
            $user_id = 0;
        }

        if (!$user_id) {
            $user_id = 0;
        }

        // Get a list of parent forums
        $parent_forums = $this->_db->orderWhere('forums', 'parent = 0', 'forum_order', 'ASC')->results();

        $return = [];

        if (count($parent_forums)) {
            foreach ($parent_forums as $forum) {
                if ($this->forumExist($forum->id, $groups)) {
                    $return[$forum->id]['description'] = Output::getClean($forum->forum_description);
                    $return[$forum->id]['title'] = Output::getClean($forum->forum_title);
                    $return[$forum->id]['icon'] = Output::getPurified($forum->icon);

                    // Get subforums
                    $forums = $this->_db->orderWhere('forums', 'parent = ' . $forum->id, 'forum_order', 'ASC')->results();
                    if (count($forums)) {
                        foreach ($forums as $item) {
                            if ($this->forumExist($item->id, $groups)) {
                                $return[$forum->id]['subforums'][$item->id] = $item;
                                $return[$forum->id]['subforums'][$item->id]->forum_title = Output::getClean($item->forum_title);
                                $return[$forum->id]['subforums'][$item->id]->forum_description = Output::getClean($item->forum_description);
                                $return[$forum->id]['subforums'][$item->id]->icon = Output::getPurified($item->icon);
                                $return[$forum->id]['subforums'][$item->id]->link = URL::build('/forum/view/' . urlencode($item->id) . '-' . $this->titleToURL($item->forum_title));
                                $return[$forum->id]['subforums'][$item->id]->redirect_to = Output::getClean($item->redirect_url);

                                // Get topic/post count
                                $topics = $this->_db->orderWhere('topics', 'forum_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $topics = count($topics);
                                $return[$forum->id]['subforums'][$item->id]->topics = $topics;

                                $posts = $this->_db->orderWhere('posts', 'forum_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $posts = count($posts);
                                $return[$forum->id]['subforums'][$item->id]->posts = $posts;

                                // Can the user view other topics
                                if ($item->last_user_posted == $user_id || $this->canViewOtherTopics($item->id, $groups)) {
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

                                if (isset($last_reply) && count($last_reply)) {
                                    $n = 0;
                                    while (isset($last_reply[$n]) && $last_reply[$n]->deleted == 1) {
                                        $n++;
                                    }

                                    if (!isset($last_reply[$n])) {
                                        continue;
                                    }

                                    // Title
                                    $last_topic = $this->_db->get('topics', ['id', $last_reply[$n]->topic_id])->results();

                                    $return[$forum->id]['subforums'][$item->id]->last_post = $last_reply[$n];
                                    $return[$forum->id]['subforums'][$item->id]->last_post->title = Output::getClean($last_topic[0]->topic_title);
                                    $return[$forum->id]['subforums'][$item->id]->last_post->link = URL::build('/forum/topic/' . urlencode($last_reply[$n]->topic_id) . '-' . $this->titleToURL($last_topic[0]->topic_title), 'pid=' . $last_reply[0]->id);
                                }

                                // Get list of subforums (names + links)
                                $subforums = $this->_db->orderWhere('forums', 'parent = ' . $item->id, 'forum_order', 'ASC')->results();
                                if (count($subforums)) {
                                    foreach ($subforums as $subforum) {
                                        if ($this->forumExist($subforum->id, $groups)) {
                                            if (!isset($return[$forum->id]['subforums'][$item->id]->subforums)) {
                                                $return[$forum->id]['subforums'][$item->id]->subforums = [];
                                            }
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id] = new stdClass();
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->title = Output::getClean($subforum->forum_title);
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->link = URL::build('/forum/view/' . urlencode($subforum->id) . '-' . $this->titleToURL($subforum->forum_title));
                                            $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->icon = Output::getPurified($subforum->icon);
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

    /**
     * Determine if a forum exists (in the context of a specific user)
     *
     * @param int $forum_id ID of the forum
     * @param array $groups Array of groups the user is in
     * @return bool Whether the forum exists or not
     */
    public function forumExist(int $forum_id, array $groups = [0]): bool {
        $exists = $this->_db->get('forums', ['id', $forum_id])->results();
        if (count($exists)) {
            return $this->hasPermission($forum_id, 'view', $groups);
        }

        return false;
    }

    /**
     * Determines if any groups have permission to do a certain action on a forum
     *
     * @param int $forum_id ID of the forum
     * @param string $required_permission Required permission
     * @param array $groups Array of groups the user is in
     * @return bool Whether the groups have permission or not
     */
    private function hasPermission(int $forum_id, string $required_permission, array $groups): bool {
        $cache_key = 'forum_permissions_' . $forum_id . '_' . $required_permission . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }
        $permissions = $this->_db->get('forums_permissions', ['forum_id', $forum_id])->results();
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->{$required_permission} == 1) {
                    self::$_permission_cache[$cache_key] = true;
                    return true;
                }
            }
        }
        return false;
    }

    public function titleToURL(string $topic = null): string {
        if ($topic) {
            $topic = str_replace(URL_EXCLUDE_CHARS, '', Util::cyrillicToLatin($topic));
            return Output::getClean(strtolower(urlencode(str_replace(' ', '-', $topic))));
        }

        return '';
    }

    // Returns true/false depending on whether the current user can view a forum
    // Params: $forum_id (integer) - forum id to check, $groups (array) - user groups
    public function canViewOtherTopics(int $forum_id, array $groups = [0]): bool {
        $cache_key = 'topics_view_' . $forum_id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }
        // Does the forum exist?
        $exists = $this->_db->get('forums', ['id', $forum_id])->results();
        if (count($exists)) {
            // Can the user view other topics?
            $access = $this->_db->get('forums_permissions', ['forum_id', $forum_id])->results();

            foreach ($access as $item) {
                if (in_array($item->group_id, $groups)) {
                    if ($item->view_other_topics == 1) {
                        self::$_permission_cache[$cache_key] = true;
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get the newest 50 topics this user/group can view
     *
     * @param array $groups Array of groups the user is in
     * @param int $user_id User ID
     * @return array 50 latest topics
     */
    public function getLatestDiscussions(array $groups = [0], int $user_id = 0): array {
        if (!$user_id) {
            $user_id = 0;
        }

        $all_topics_forums = DB::getInstance()->query('SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 1')->results();

        if ($user_id > 0) {
            $own_topics_forums = DB::getInstance()->query('SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 0')->results();
        } else {
            $own_topics_forums = [];
        }

        if (!count($all_topics_forums) && !count($own_topics_forums)) {
            return [];
        }

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

                $query = DB::getInstance()->query('(
                SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.forum_id IN ' . $all_topics_forums_string . ' ORDER BY topics.topic_reply_date DESC LIMIT 50
                ) UNION (
                SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND ((topics.forum_id IN ' . $own_topics_forums_string . ' AND topics.topic_creator = ?) OR topics.sticky = 1) ORDER BY topics.topic_reply_date DESC LIMIT 50
                ) ORDER BY topic_reply_date DESC LIMIT 50', [$user_id], PDO::FETCH_ASSOC)->results();
            } else {
                $query = DB::getInstance()->query('SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.forum_id IN ' . $all_topics_forums_string . ' ORDER BY topics.topic_reply_date DESC LIMIT 50', [], PDO::FETCH_ASSOC)->results();
            }
        } catch (Exception $e) {
            // Likely no permissions to view any forums
            $query = [];
        }

        return $query;
    }

    /**
     * Determine if a topic exists or not.
     *
     * @param int $topic_id The topic ID
     * @return bool Whether the topic exists or not
     */
    public function topicExist(int $topic_id): bool {
        // Does the topic exist?
        $exists = $this->_db->get('topics', ['id', $topic_id])->results();
        return count($exists) > 0;
    }

    /**
     * Determine if the groups can view the forum or not.
     *
     * @param int $forum_id The forum ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can view the forum or not
     */
    public function canViewForum(int $forum_id, array $groups = [0]): bool {
        return $this->hasPermission($forum_id, 'view', $groups);
    }

    /**
     * Determine if the groups can post topics in the forum or not.
     *
     * @param int $forum_id The forum ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can post topics in the forum or not
     */
    public function canPostTopic(int $forum_id, array $groups = [0]): bool {
        return $this->hasPermission($forum_id, 'create_topic', $groups);
    }

    /**
     * Determine if the groups can post replies in the forum or not.
     *
     * @param int $forum_id The forum ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can post replies in the forum or not
     */
    public function canPostReply(int $forum_id, array $groups = [0]): bool {
        return $this->hasPermission($forum_id, 'create_post', $groups);
    }

    /**
     * Determine if the groups can edit [psts] in the forum or not.
     *
     * @param int $forum_id The forum ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can edit posts in the forum or not
     */
    public function canEditTopic(int $forum_id, array $groups = [0]): bool {
        return $this->hasPermission($forum_id, 'edit_topic', $groups);
    }

    /**
     * Update the database with the new latest forum posts.
     */
    public function updateForumLatestPosts(): void {
        $forums = $this->_db->get('forums', ['id', '<>', 0])->results();
        $latest_posts = [];
        $n = 0;

        foreach ($forums as $item) {
            if ($item->parent != 0) {
                $latest_post_query = $this->_db->orderWhere('posts', 'forum_id = ' . $item->id, 'post_date', 'DESC')->results();

                if (!empty($latest_post_query)) {
                    foreach ($latest_post_query as $latest_post) {
                        if ($latest_post->deleted != 1) {
                            // Ensure topic isn't deleted
                            $topic_query = $this->_db->get('topics', ['id', $latest_post->topic_id])->results();

                            if (empty($topic_query)) {
                                continue;
                            }

                            $latest_posts[$n]['forum_id'] = $item->id;
                            if ($latest_post->created) {
                                $latest_posts[$n]['date'] = $latest_post->created;
                            } else {
                                $latest_posts[$n]['date'] = strtotime($latest_post->post_date);
                            }
                            $latest_posts[$n]['author'] = $latest_post->post_creator;
                            $latest_posts[$n]['topic_id'] = $latest_post->topic_id;

                            break;
                        }
                    }
                }

                if (!isset($latest_posts[$n])) {
                    $latest_posts[$n]['forum_id'] = $item->id;
                    $latest_posts[$n]['date'] = null;
                    $latest_posts[$n]['author'] = null;
                    $latest_posts[$n]['topic_id'] = null;
                }

                $n++;
            }
        }

        $forums = null;

        if (count($latest_posts)) {
            foreach ($latest_posts as $latest_post) {
                $this->_db->update('forums', $latest_post['forum_id'], [
                    'last_post_date' => $latest_post['date'],
                    'last_user_posted' => $latest_post['author'],
                    'last_topic_posted' => $latest_post['topic_id']
                ]);
            }
        }

        $latest_posts = null;
    }

    /**
     * Update the database with the new latest forum topic posts.
     */
    public function updateTopicLatestPosts(): void {
        $topics = $this->_db->get('topics', ['id', '<>', 0])->results();
        $latest_posts = [];
        $n = 0;

        foreach ($topics as $topic) {
            $latest_post_query = $this->_db->orderWhere('posts', 'topic_id = ' . $topic->id, 'post_date', 'DESC')->results();

            if (count($latest_post_query)) {
                foreach ($latest_post_query as $latest_post) {
                    if ($latest_post->deleted != 1) {
                        $latest_posts[$n]['topic_id'] = $topic->id;

                        if ($latest_post->created != null) {
                            $latest_posts[$n]['date'] = $latest_post->created;
                        } else {
                            $latest_posts[$n]['date'] = strtotime($latest_post->post_date);
                        }

                        $latest_posts[$n]['author'] = $latest_post->post_creator;

                        break;
                    }
                }
            }

            $n++;
        }

        foreach ($latest_posts as $latest_post) {
            if (!empty($latest_post['date'])) {
                $this->_db->update('topics', $latest_post['topic_id'], [
                    'topic_reply_date' => $latest_post['date'],
                    'topic_last_user' => $latest_post['author']
                ]);
            }
        }
    }

    /**
     * Get the title of a specific forum.
     *
     * @param int $forum_id The forum ID to get the title of.
     * @return string The forum title.
     */
    public function getForumTitle(int $forum_id): string {
        $data = $this->_db->get('forums', ['id', $forum_id])->results();
        return $data[0]->forum_title;
    }

    /**
     * Get data of a specific post.
     *
     * @param int $post_id The post ID to data about.
     * @return array|false The post data or false on failure.
     */
    public function getIndividualPost(int $post_id) {
        $data = $this->_db->get('posts', ['id', $post_id])->results();
        if (count($data)) {
            return [
                'creator' => $data[0]->post_creator,
                'content' => $data[0]->post_content,
                'date' => $data[0]->post_date,
                'forum_id' => $data[0]->forum_id,
                'topic_id' => $data[0]->topic_id
            ];
        }
        return false;
    }

    /**
     * Get the latest news posts to display on homepage.
     *
     * @param int $number The number of posts to get.
     * @return array The latest news posts.
     */
    public function getLatestNews(int $number = 5): array {
        $return = []; // Array to return containing news
        $labels_cache = []; // Array to contain labels

        $news_items = $this->_db->query('SELECT * FROM nl2_topics WHERE forum_id IN (SELECT id FROM nl2_forums WHERE news = 1) AND deleted = 0 ORDER BY topic_date DESC LIMIT 10')->results();

        foreach ($news_items as $item) {
            $news_post = $this->_db->get('posts', ['topic_id', $item->id])->results();
            $posts = count($news_post);

            if (is_null($news_post[0]->created)) {
                $post_date = date(DATE_FORMAT, strtotime($news_post[0]->post_date));
            } else {
                $post_date = date(DATE_FORMAT, $news_post[0]->created);
            }

            $labels = [];

            if ($item->labels) {
                // Get label
                $label_ids = explode(',', $item->labels);

                if ($label_ids !== false) {
                    foreach ($label_ids as $label_id) {
                        if (isset($labels_cache[$label_id])) {
                            $labels[] = $labels_cache[$label_id];
                        } else {
                            $label = $this->_db->get('forums_topic_labels', ['id', $label_id]);
                            if ($label->count()) {
                                $label = $label->first();

                                $label_html = $this->_db->get('forums_labels', ['id', $label->label]);

                                if ($label_html->count()) {
                                    $label_html = $label_html->first()->html;
                                    $label = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html));
                                } else {
                                    $label = '';
                                }
                            } else {
                                $label = '';
                            }

                            $labels_cache[$label_id] = $label;
                            $labels[] = $label;
                        }
                    }
                }
            }

            $post = $news_post[0]->post_content;
            $return[] = [
                'topic_id' => $item->id,
                'topic_date' => $post_date,
                'topic_title' => $item->topic_title,
                'topic_views' => $item->topic_views,
                'author' => $item->topic_creator,
                'content' => Util::truncate($post),
                'replies' => $posts,
                'label' => count($labels) ? $labels[0] : null,
                'labels' => $labels
            ];
        }

        // Order the discussions by date - most recent first
        usort($return, static function ($a, $b) {
            return strtotime($b['topic_date']) - strtotime($a['topic_date']);
        });

        return array_slice($return, 0, $number, true);
    }

    /**
     * Determine if groups have permission to moderate a forum.
     *
     * @param int|null $forum_id The forum ID to check.
     * @param array $groups The groups to check.
     * @return bool Whether the groups can moderate the forum.
     */
    public function canModerateForum(int $forum_id = null, array $groups = [0]): bool {
        if (!$forum_id || in_array(0, $groups)) {
            return false;
        }

        $cache_key = 'moderate_' . $forum_id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }

        $permissions = $this->_db->get('forums_permissions', ['forum_id', $forum_id])->results();

        // Check the forum
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->moderate == 1) {
                    self::$_permission_cache[$cache_key] = true;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get a user's post count
     *
     * @param int|null $user_id User ID to check
     * @return int Number of posts
     */
    public function getPostCount(int $user_id = null): int {
        if ($user_id) {
            if (isset(self::$_count_cache["posts_$user_id"])) {
                return self::$_count_cache["posts_$user_id"];
            }
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM nl2_posts WHERE deleted = 0 AND post_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["posts_$user_id"] = $count;
            return $count;
        }

        return 0;
    }

    /**
     * Get a user's topic count
     *
     * @param int|null $user_id User ID to check
     * @return int Number of topics
     */
    public function getTopicCount(int $user_id = null): int {
        if ($user_id) {
            if (isset(self::$_count_cache["topics_$user_id"])) {
                return self::$_count_cache["topics_$user_id"];
            }
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM nl2_topics WHERE deleted = 0 AND topic_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["topics_$user_id"] = $count;
            return $count;
        }

        return 0;
    }

    /**
     * Get posts on a specific topic.
     *
     * @param int|null $tid The topic ID to check.
     * @return array|false Array of topics or false on failure.
     */
    public function getPosts(int $tid = null) {
        if ($tid) {
            // Get posts from database
            $posts = $this->_db->get('posts', ['topic_id', $tid]);

            if ($posts->count()) {
                $posts = $posts->results();

                // Remove deleted posts
                foreach ($posts as $key => $post) {
                    if ($post->deleted == 1) {
                        unset($posts[$key]);
                    }
                }

                return array_values($posts);
            }
        }
        return false;
    }

    /**
     * Get any subforums at any level for a forum
     *
     * @param int $forum_id The forum ID
     * @param array $groups The user groups
     * @param int $depth The depth of the subforums to get
     * @return array Subforums at any level for a forum
     */
    public function getAnySubforums(int $forum_id, array $groups = [0], int $depth = 0): array {
        if ($depth == 10) {
            return [];
        }

        $ret = [];

        $subforums_query = $this->_db->query('SELECT * FROM nl2_forums WHERE parent = ? ORDER BY forum_order ASC', [$forum_id]);

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
