<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Profile Posts Widget
 */
class ProfilePostsWidget extends WidgetBase {

    private $_cache,
            $_smarty,
            $_language,
            $_user,
            $_timeago;

    public function __construct($pages = array(), $smarty, $language, $cache, $user, $timeago) {
        $this->_language = $language;
        $this->_smarty = $smarty;
        $this->_cache = $cache;
        $this->_user = $user;
        $this->_timeago = $timeago;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->query('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', array('Latest Profile Posts'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Latest Profile Posts';
        $this->_location = isset($widget_query->location) ? $widget_query->location : null;
        $this->_description = 'Display the latest profile posts on your site.';
        $this->_order = isset($widget_query->order) ? $widget_query->order : null;
    }

    public function initialise() {
        // Generate HTML code for widget
        if ($this->_user->isLoggedIn()) {
            $user_id = $this->_user->data()->id;
        } else {
            $user_id = 0;
        }

        $this->_cache->setCache('profile_posts_widget');

        $posts_array = array();
        if ($this->_cache->isCached('profile_posts_' . $user_id)) {
             $posts_array = $this->_cache->retrieve('profile_posts_' . $user_id);
         } else {
            $posts = DB::getInstance()->query('SELECT * FROM nl2_user_profile_wall_posts ORDER BY time DESC LIMIT 5')->results();
            foreach ($posts as $post) {
                $post_author = new User($post->author_id);

                if ($this->_user->isLoggedIn()) {
                    if ($this->_user->isBlocked($post->author_id, $this->_user->data()->id)) continue;
                    if ($post_author->isPrivateProfile() && !$this->_user->hasPermission('profile.private.bypass')) continue;
                } else if ($post_author->isPrivateProfile()) continue;

                $post_user = new User($post->user_id);
                $link = rtrim($post_user->getProfileURL(), '/');

                $posts_array[] = array(
                    'avatar' => $post_author->getAvatar(),
                    'username' => $post_author->getDisplayname(),
                    'username_style' => $post_author->getGroupClass(),
                    'content' => Util::truncate(strip_tags(Output::getDecoded($post->content)), 20),
                    'link' => $link . '/#post-' . $post->id,
                    'date_ago' => date('d M Y, H:i', $post->time),
                    'user_id' => $post->author_id,
                    'user_profile_link' => $post_author->getProfileURL(),
                    'ago' => $this->_timeago->inWords(date('d M Y, H:i', $post->time), $this->_language->getTimeLanguage())
                );
            }
            $this->_cache->store('profile_posts_' . $user_id, $posts_array, 120);
        }
        if (count($posts_array) >= 1) {
            $this->_smarty->assign(array(
                'PROFILE_POSTS_ARRAY' => $posts_array
            ));
        }
        $this->_smarty->assign(array(
            'LATEST_PROFILE_POSTS' => $this->_language->get('user', 'latest_profile_posts'),
            'NO_PROFILE_POSTS' => $this->_language->get('user', 'no_profile_posts')
        ));
        $this->_content = $this->_smarty->fetch('widgets/profile_posts.tpl');;
    }
}
