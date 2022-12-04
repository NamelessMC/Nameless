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
    private const URL_EXCLUDE_CHARS = [
        '?',
        '&',
        '/',
        '#',
        '.',
    ];

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

        $groups_in = implode(',', $groups);

        // Get a list of parent forums
        $parent_forums = $this->_db->query(
            <<<SQL
                SELECT *
                FROM `nl2_forums`
                WHERE `parent` = 0
                  AND `id` IN 
                      (SELECT `forum_id`
                       FROM nl2_forums_permissions
                       WHERE `group_id` IN ($groups_in)
                       AND `view` = 1
                       )
                ORDER BY `forum_order` ASC
            SQL
        );

        $return = [];

        if ($parent_forums->count()) {
            foreach ($parent_forums->results() as $forum) {
                $return[$forum->id]['description'] = Output::getClean($forum->forum_description);
                $return[$forum->id]['title'] = Output::getClean($forum->forum_title);
                $return[$forum->id]['icon'] = Output::getPurified($forum->icon);

                // Get discussion forums
                $forums = $this->_db->query(
                    <<<SQL
                        SELECT f.*
                        FROM nl2_forums AS f
                        WHERE f.parent = ?
                          AND f.id IN 
                              (SELECT fp.forum_id
                               FROM nl2_forums_permissions AS fp
                               WHERE fp.group_id IN ($groups_in)
                               AND fp.view = 1
                               )
                        ORDER BY f.forum_order ASC
                    SQL,
                    [$forum->id]
                );

                if ($forums->count()) {
                    foreach ($forums->results() as $item) {
                        $return[$forum->id]['subforums'][$item->id] = $item;
                        $return[$forum->id]['subforums'][$item->id]->forum_title = Output::getClean($item->forum_title);
                        $return[$forum->id]['subforums'][$item->id]->forum_description = Output::getClean($item->forum_description);
                        $return[$forum->id]['subforums'][$item->id]->icon = Output::getPurified($item->icon);
                        $return[$forum->id]['subforums'][$item->id]->link = URL::build('/forum/view/' . urlencode($item->id) . '-' . $this->titleToURL($item->forum_title));
                        $return[$forum->id]['subforums'][$item->id]->redirect_to = Output::getClean($item->redirect_url);

                        // Get latest post from any sub-subforums
                        $subforums = $this->getAnySubforums($item->id, $groups, 0, true, $user_id);

                        $latest_post = [$item->last_post_date, $item->last_user_posted, $item->last_topic_posted];

                        if (count($subforums)) {
                            $return[$forum->id]['subforums'][$item->id]->subforums = [];

                            foreach ($subforums as $subforum) {
                                if (isset($subforum->last_post_date) && $subforum->last_post_date > $latest_post[0]) {
                                    $latest_post = [$subforum->last_post_date, $subforum->last_user_posted, $subforum->last_topic_posted];
                                }

                                $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id] = new stdClass();
                                $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->title = $subforum->forum_title;
                                $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->link = URL::build('/forum/view/' . urlencode($subforum->id) . '-' . $this->titleToURL($subforum->forum_title));
                                $return[$forum->id]['subforums'][$item->id]->subforums[$subforum->id]->icon = $subforum->icon;
                            }
                        }

                        // Can the user view other topics?
                        if ($this->canViewOtherTopics($item->id, $groups)) {
                            $topics = $this->_db->query(
                                <<<SQL
                                    SELECT COUNT(*) AS `count`
                                    FROM nl2_topics
                                    WHERE
                                        `forum_id` = ?
                                      AND `deleted` = 0
                                SQL,
                                [$item->id]
                            )->first()->count;

                            $posts = $this->_db->query(
                                <<<SQL
                                    SELECT COUNT(*) AS `count`
                                    FROM nl2_posts
                                    WHERE `forum_id` = ?
                                      AND `deleted` = 0
                                SQL,
                                [$item->id]
                            )->first()->count;

                        } else {
                            $topics = $this->_db->query(
                                <<<SQL
                                    SELECT COUNT(*) AS `count`
                                    FROM nl2_topics
                                    WHERE `forum_id` = ?
                                      AND (`topic_creator` = ? OR `sticky` = 1)
                                      AND `deleted` = 0
                                SQL,
                                [$item->id, $user_id],
                            )->first()->count;

                            $posts = $this->_db->query(
                                <<<SQL
                                    SELECT COUNT(*) AS `count`
                                    FROM nl2_posts
                                    WHERE `topic_id` IN
                                          (SELECT `id`
                                           FROM nl2_topics
                                           WHERE `forum_id` = ?
                                             AND (`topic_creator` = ? OR `sticky` = 1)
                                           )
                                      AND `deleted` = 0
                                SQL,
                                [$item->id, $user_id]
                            )->first()->count;
                        }

                        $return[$forum->id]['subforums'][$item->id]->topics = $topics;
                        $return[$forum->id]['subforums'][$item->id]->posts = $posts;

                        // Get latest topic info
                        if ($latest_post[0]) {
                            $latest_topic = $this->_db->query(
                                <<<SQL
                                    SELECT t.*,
                                           p.id as pid,
                                           p.post_creator,
                                           p.created
                                    FROM nl2_topics t
                                        RIGHT JOIN nl2_posts p
                                            ON p.id =
                                               (SELECT ps.id
                                                FROM nl2_posts ps
                                                WHERE ps.topic_id = ?
                                                  AND ps.deleted = 0
                                                ORDER BY ps.created DESC LIMIT 1
                                                )
                                    WHERE t.id = ?
                                SQL,
                                [$latest_post[2], $latest_post[2]]
                            );

                            if ($latest_topic->count() && $latest_topic = $latest_topic->first()) {
                                $return[$forum->id]['subforums'][$item->id]->last_post = $latest_topic;
                                $return[$forum->id]['subforums'][$item->id]->last_post->title = Output::getClean($latest_topic->topic_title);
                                $return[$forum->id]['subforums'][$item->id]->last_post->link = URL::build('/forum/topic/' . urlencode($latest_post[2]) . '-' . $this->titleToURL($latest_topic->topic_title), 'pid=' . $latest_topic->pid);
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
            $topic = str_replace(self::URL_EXCLUDE_CHARS, '', Util::cyrillicToLatin($topic));
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
     * Get the newest x topics this user/group can view
     *
     * @param array $groups Array of groups the user is in
     * @param int $user_id User ID
     * @param int $limit Limit of topics to return, default 50
     * @return array Latest topics
     */
    public function getLatestDiscussions(array $groups = [0], int $user_id = 0, int $limit = 50): array {
        if (!$user_id) {
            $user_id = 0;
        }

        $all_topics_forums = DB::getInstance()->query('SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 1')->results();

        $own_topics_forums = [];
        if ($user_id > 0) {
            $own_topics_forums = DB::getInstance()->query('SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 0')->results();
        }

        if (!count($all_topics_forums) && !count($own_topics_forums)) {
            return [];
        }

        $all_topics_forums_string = '';
        foreach ($all_topics_forums as $forum) {
            $all_topics_forums_string .= $forum->forum_id . ',';
        }
        $all_topics_forums_string = rtrim($all_topics_forums_string, ',');

        if (count($own_topics_forums)) {
            $own_topics_forums_string = '';
            foreach ($own_topics_forums as $forum) {
                $own_topics_forums_string .= $forum->forum_id . ',';
            }
            $own_topics_forums_string = rtrim($own_topics_forums_string, ',');

            return DB::getInstance()->query(
                "(SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.forum_id IN (' . $all_topics_forums_string . ') ORDER BY topics.topic_reply_date DESC LIMIT $limit)
                UNION
                (SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND ((topics.forum_id IN (' . $own_topics_forums_string . ') AND topics.topic_creator = ?) OR topics.sticky = 1) ORDER BY topics.topic_reply_date DESC LIMIT $limit)
                ORDER BY topic_reply_date DESC LIMIT $limit",
                [$user_id],
                true
            )->results();
        }

        return DB::getInstance()->query(
            "SELECT topics.id as id, topics.forum_id as forum_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id
            FROM nl2_topics topics 
            LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) 
            WHERE topics.deleted = 0 AND topics.forum_id IN (' . $all_topics_forums_string . ') ORDER BY topics.topic_reply_date DESC LIMIT $limit",
        )->results();
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
     *
     * @param int $forum_id The forum ID to update
     */
    public function updateForumLatestPosts(int $forum_id): void {
        $latest_post = $this->_db->query(
            <<<SQL
                SELECT `created`, 
                       `post_date`,
                       `post_creator`,
                       `topic_id`
                FROM nl2_posts
                WHERE `forum_id` = ?
                  AND `deleted` = 0
                ORDER BY `created` DESC LIMIT 1
            SQL,
            [$forum_id]
        );

        if ($latest_post->count() && $latest_post = $latest_post->first()) {
            $this->_db->update('forums', $forum_id, [
                'last_post_date' => $latest_post->created ?? strtotime($latest_post->post_date),
                'last_user_posted' => $latest_post->post_creator,
                'last_topic_posted' => $latest_post->topic_id,
            ]);
        } else {
            $this->_db->update('forums', $forum_id, [
                'last_post_date' => null,
                'last_user_posted' => null,
                'last_topic_posted' => null,
            ]);
        }
    }

    /**
     * Update the database with the new latest forum topic posts.
     *
     * @param int $topic_id The topic ID to update
     */
    public function updateTopicLatestPosts(int $topic_id): void {
        $latest_post = $this->_db->query(
            <<<SQL
                SELECT `created`, 
                       `post_date`,
                       `post_creator`
                FROM nl2_posts
                WHERE `topic_id` = ?
                  AND `deleted` = 0
                ORDER BY `created` DESC LIMIT 1
            SQL,
            [$topic_id]
        );

        if ($latest_post->count() && $latest_post = $latest_post->first()) {
            $this->_db->update('topics', $topic_id, [
                'topic_reply_date' => $latest_post->created ?? strtotime($latest_post->post_date),
                'topic_last_user' => $latest_post->post_creator,
            ]);
        } else {
            $this->_db->update('forums', $forum_id, [
                'last_post_date' => null,
                'last_user_posted' => null,
                'last_topic_posted' => null,
            ]);
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
                'content' => Text::truncate($post),
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
     * @param ?bool $onlyOwnTopics Whether to only get forums in which the user can only view their own topics (default false)
     * @param ?int $user_id Current user ID (default 0) - only used if $onlyOwnTopics is true
     * @return array Subforums at any level for a forum
     */
    public function getAnySubforums(
        int $forum_id,
        array $groups = [0],
        int $depth = 0,
        ?bool $onlyOwnTopics = false,
        ?int $user_id = 0
    ): array {
        if ($depth == 10) {
            return [];
        }

        $ret = [];
        $groups_in = implode(',', $groups);

        $subforums_query = $this->_db->query(
            <<<SQL
                SELECT
                    f.*,
                    EXISTS (
                        SELECT p.ID
                        FROM nl2_forums_permissions p
                        WHERE p.group_id IN ($groups_in)
                          AND p.forum_id = f.id
                          AND p.view_other_topics = 1
                    ) view_other_topics
                FROM nl2_forums f
                WHERE f.parent = ?
                  AND f.id IN
                      (SELECT sp.forum_id
                       FROM nl2_forums_permissions sp
                       WHERE sp.group_id IN ($groups_in)
                       AND sp.view = 1
                       )
            SQL,
            [$forum_id]
        );

        if (!$subforums_query->count()) {
            return $ret;
        }

        foreach ($subforums_query->results() as $result) {
            $to_add = new stdClass();
            $to_add->id = Output::getClean($result->id);
            $to_add->forum_title = Output::getClean($result->forum_title);
            $to_add->icon = Output::getPurified($result->icon);
            $to_add->category = false;

            // Latest post
            if ($onlyOwnTopics && $result->view_other_topics != '1') {
                // Get the latest topic that the user can view
                $latest_post = $this->_db->query(
                    <<<SQL
                        SELECT
                            p.topic_id,
                            p.created,
                            p.post_date,
                            p.post_creator
                        FROM nl2_topics t
                            LEFT JOIN nl2_posts p
                                ON p.id = (
                                    SELECT id
                                    FROM nl2_posts sp
                                    WHERE sp.topic_id = t.id
                                    AND sp.deleted = 0
                                    ORDER BY sp.created DESC LIMIT 1
                                )
                        WHERE t.forum_id = ?
                          AND (t.topic_creator = ? OR t.sticky = 1)
                    SQL,
                    [$result->id, $user_id]
                );

                if ($latest_post->count() && $latest_post = $latest_post->first()) {
                    $to_add->last_post_date = $latest_post->created ?? strtotime($latest_post->post_date);
                    $to_add->last_user_posted = $latest_post->post_creator;
                    $to_add->last_topic_posted = $latest_post->topic_id;
                }
            } else {
                $to_add->last_post_date = $result->last_post_date;
                $to_add->last_user_posted = $result->last_user_posted;
                $to_add->last_topic_posted = $result->last_topic_posted;
            }

            $ret[] = $to_add;

            $subforums = $this->getAnySubforums($result->id, $groups, ++$depth);

            if (count($subforums)) {
                foreach ($subforums as $subforum) {
                    $ret[] = $subforum;
                }
            }
        }

        return $ret;
    }

    /**
     * Get an array returned of accessible labels when providing group ids
     *
     * @param array $labels  of label ids
     * @param array $user_groups array of a user their group ids
     * @return array An array of the ids of the labels the user has access to
     */
    public static function getAccessibleLabels(array $labels, array $user_groups): array {
        return array_reduce($labels, function(&$prev, $topic_label) use ($user_groups) {
            $label = DB::getInstance()->get('forums_topic_labels', ['id', $topic_label])->first();
            if ($label) {
                $label_group_ids = explode(',', $label->gids);
                $hasPerm = array_reduce($user_groups, fn($prev, $group_id) => $prev || in_array($group_id, $label_group_ids));
                if ($hasPerm) {
                    $prev[] = $label->id;
                }
            }
            return is_array($prev) ? $prev : [];
        });
    }
}
