<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Profile Posts Widget
 */

class ProfilePostsWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;
    private User $_user;
    private TimeAgo $_timeago;

    public function __construct(Smarty $smarty, Language $language, Cache $cache, User $user, TimeAgo $timeago) {
        $this->_language = $language;
        $this->_smarty = $smarty;
        $this->_cache = $cache;
        $this->_user = $user;
        $this->_timeago = $timeago;

        // Get widget
        $widget_query = self::getData('Latest Profile Posts');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Latest Profile Posts';
        $this->_location = $widget_query->location ?? null;
        $this->_description = 'Display the latest profile posts on your site.';
        $this->_order = $widget_query->order ?? null;
    }

    public function initialise(): void {
        // Generate HTML code for widget
        if ($this->_user->isLoggedIn()) {
            $user_id = $this->_user->data()->id;
        } else {
            $user_id = 0;
        }

        $this->_cache->setCache('profile_posts_widget');

        $posts_array = [];
        if ($this->_cache->isCached('profile_posts_' . $user_id)) {
            $posts_array = $this->_cache->retrieve('profile_posts_' . $user_id);
        } else {
            if ($this->_user->isLoggedIn()) {
                if ($this->_user->hasPermission('profile.private.bypass')) {
                    $posts = DB::getInstance()->query('SELECT * FROM nl2_user_profile_wall_posts ORDER BY `time` DESC LIMIT 5')->results();
                } else {
                    $posts = DB::getInstance()->query(
                        <<<SQL
                        SELECT *
                        FROM nl2_user_profile_wall_posts
                        WHERE `user_id` NOT IN (
                            SELECT `id` FROM nl2_users WHERE `private_profile` = 1
                        )
                        AND EXISTS (SELECT `id` FROM nl2_blocked_users WHERE `user_blocked_id` = `user_id`) = 0
                        ORDER BY `time` DESC LIMIT 5
                        SQL,
                    )->results();
                }
            } else {
                $posts = DB::getInstance()->query(
                    <<<SQL
                        SELECT *
                        FROM nl2_user_profile_wall_posts
                        WHERE `user_id` NOT IN (
                            SELECT `id` FROM nl2_users WHERE `private_profile` = 1
                        )
                        ORDER BY `time` DESC LIMIT 5
                        SQL,
                )->results();
            }

            foreach ($posts as $post) {
                $post_author = new User($post->author_id);
                $post_user = new User($post->user_id);
                $link = rtrim($post_user->getProfileURL(), '/');

                $posts_array[] = [
                    'avatar' => $post_author->getAvatar(),
                    'username' => $post_author->getDisplayname(),
                    'username_style' => $post_author->getGroupStyle(),
                    'content' => Text::truncate(strip_tags($post->content), 20),
                    'link' => $link . '/#post-' . $post->id,
                    'date_ago' => date(DATE_FORMAT, $post->time),
                    'user_id' => $post->author_id,
                    'user_profile_link' => $post_author->getProfileURL(),
                    'ago' => $this->_timeago->inWords($post->time, $this->_language)
                ];
            }
            $this->_cache->store('profile_posts_' . $user_id, $posts_array, 120);
        }
        if (count($posts_array) >= 1) {
            $this->_smarty->assign([
                'PROFILE_POSTS_ARRAY' => $posts_array
            ]);
        }
        $this->_smarty->assign([
            'LATEST_PROFILE_POSTS' => $this->_language->get('user', 'latest_profile_posts'),
            'NO_PROFILE_POSTS' => $this->_language->get('user', 'no_profile_posts')
        ]);
        $this->_content = $this->_smarty->fetch('widgets/profile_posts.tpl');
    }
}
