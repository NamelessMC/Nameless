<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Latest Posts Widget
 */

class LatestPostsWidget extends WidgetBase {

    private Language $_language;
    private Cache $_cache;
    private User $_user;

    public function __construct(string $latest_posts_language, string $by_language, Smarty $smarty, Cache $cache, User $user, Language $language) {
        $this->_smarty = $smarty;
        $this->_cache = $cache;
        $this->_user = $user;
        $this->_language = $language;

        // Get widget
        $widget_query = self::getData('Latest Posts');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Forum';
        $this->_name = 'Latest Posts';
        $this->_location = $widget_query->location ?? null;
        $this->_description = 'Display latest posts from your forum.';
        $this->_order = $widget_query->order ?? null;

        $this->_smarty->assign([
            'LATEST_POSTS' => $latest_posts_language,
            'BY' => $by_language
        ]);
    }

    public function initialise(): void {
        $forum = new Forum();
        $db = DB::getInstance();
        $timeago = new TimeAgo(TIMEZONE);

        // Get user group IDs
        $user_groups = $this->_user->getAllGroupIds();

        $this->_cache->setCache('forum_discussions_' . rtrim(implode('-', $user_groups), '-'));
        if ($this->_cache->isCached('discussions')) {
            $template_array = $this->_cache->retrieve('discussions');

        } else {
            // Generate latest posts
            $discussions = $forum->getLatestDiscussions($user_groups, ($this->_user->isLoggedIn() ? $this->_user->data()->id : 0));

            $n = 0;
            // Calculate the number of discussions to display (5 max)
            if (count($discussions) <= 5) {
                $limit = count($discussions);
            } else {
                $limit = 5;
            }

            $template_array = [];

            // Generate an array to pass to template
            while ($n < $limit) {
                // Get the name of the forum from the ID
                $forum_name = $db->get('forums', ['id', $discussions[$n]['forum_id']])->results();
                $forum_name = Output::getPurified($forum_name[0]->forum_title);

                // Get the number of replies
                $posts = $db->get('posts', ['topic_id', $discussions[$n]['id']])->results();
                $posts = count($posts);

                // Is there a label?
                if ($discussions[$n]['label'] != 0) { // yes
                    // Get label
                    $label = $db->get('forums_topic_labels', ['id', $discussions[$n]['label']])->results();
                    if (count($label)) {
                        $label = $label[0];

                        $label_html = $db->get('forums_labels', ['id', $label->label])->results();
                        if (count($label_html)) {
                            $label_html = $label_html[0]->html;
                            $label = str_replace('{x}', Output::getClean($label->name), $label_html);
                        } else {
                            $label = '';
                        }
                    } else {
                        $label = '';
                    }
                } else { // no
                    $label = '';
                }

                // Add to array
                $topic_creator = new User($discussions[$n]['topic_creator']);
                $last_reply_user = new User($discussions[$n]['topic_last_user']);
                $template_array[] = [
                    'topic_title' => Output::getClean($discussions[$n]['topic_title']),
                    'topic_id' => $discussions[$n]['id'],
                    'topic_created_rough' => $timeago->inWords($discussions[$n]['topic_date'], $this->_language),
                    'topic_created' => date(DATE_FORMAT, $discussions[$n]['topic_date']),
                    'topic_created_username' => $topic_creator->getDisplayname(),
                    'topic_created_mcname' => $topic_creator->getDisplayname(true),
                    'topic_created_style' => $topic_creator->getGroupStyle(),
                    'topic_created_user_id' => Output::getClean($discussions[$n]['topic_creator']),
                    'locked' => $discussions[$n]['locked'],
                    'forum_name' => $forum_name,
                    'forum_id' => $discussions[$n]['forum_id'],
                    'views' => $discussions[$n]['topic_views'],
                    'posts' => $posts,
                    'last_reply_avatar' => $last_reply_user->getAvatar(64),
                    'last_reply_rough' => $timeago->inWords($discussions[$n]['topic_reply_date'], $this->_language),
                    'last_reply' => date(DATE_FORMAT, $discussions[$n]['topic_reply_date']),
                    'last_reply_username' => $last_reply_user->getDisplayname(),
                    'last_reply_mcname' => $last_reply_user->getDisplayname(true),
                    'last_reply_style' => $last_reply_user->getGroupStyle(),
                    'last_reply_user_id' => Output::getClean($discussions[$n]['topic_last_user']),
                    'label' => $label,
                    'link' => URL::build('/forum/topic/' . urlencode($discussions[$n]['id']) . '-' . $forum->titleToURL($discussions[$n]['topic_title'])),
                    'forum_link' => URL::build('/forum/forum/' . $discussions[$n]['forum_id']),
                    'author_link' => $topic_creator->getProfileURL(),
                    'last_reply_profile_link' => $last_reply_user->getProfileURL(),
                    'last_reply_link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title']), 'pid=' . $discussions[$n]['last_post_id'])
                ];

                $n++;
            }

            $this->_cache->store('discussions', $template_array, 60);
        }

        // Generate HTML code for widget
        $this->_smarty->assign('LATEST_POSTS_ARRAY', $template_array);

        $this->_content = $this->_smarty->fetch('widgets/forum/latest_posts.tpl');
    }
}
