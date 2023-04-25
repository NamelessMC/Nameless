<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Latest Posts Widget
 */

class LatestPostsWidget extends WidgetBase {

    private Language $_language;
    private Cache $_cache;
    private User $_user;

    public function __construct(Language $forum_language, Smarty $smarty, Cache $cache, User $user, Language $language) {
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
        $this->_settings = ROOT_PATH . '/modules/Forum/widgets/admin/latest_posts.php';
        $this->_order = $widget_query->order ?? null;

        $this->_smarty->assign([
            'LATEST_POSTS' => $forum_language->get('forum', 'latest_posts'),
            'NO_POSTS_FOUND' => $forum_language->get('forum', 'no_posts_found'),
            'BY' => $forum_language->get('forum', 'by'),
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
            $limit = (int) Util::getSetting('latest_posts_limit', 5, 'Forum');
            // Generate latest posts
            $discussions = $forum->getLatestDiscussions($user_groups, ($this->_user->isLoggedIn() ? $this->_user->data()->id : 0), $limit);

            $template_array = [];

            // Generate an array to pass to template
            foreach ($discussions as $discussion) {
                // Get the name of the forum from the ID
                $forum_name = $db->get('forums', ['id', $discussion->forum_id])->results();
                $forum_name = Output::getPurified($forum_name[0]->forum_title);

                // Get the number of replies
                $posts = $db->query('SELECT COUNT(*) as c FROM nl2_posts WHERE `topic_id` = ? AND `deleted` = 0', [$discussion->id])->first()->c;

                // Is there a label?
                if ($discussion->label != 0) {
                    // Get label
                    $label = $db->get('forums_topic_labels', ['id', $discussion->label])->results();
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
                $topic_creator = new User($discussion->topic_creator);
                $last_reply_user = new User($discussion->topic_last_user);
                $template_array[] = [
                    'topic_title' => Output::getClean($discussion->topic_title),
                    'topic_id' => $discussion->id,
                    'topic_created_rough' => $timeago->inWords($discussion->topic_date, $this->_language),
                    'topic_created' => date(DATE_FORMAT, $discussion->topic_date),
                    'topic_created_username' => $topic_creator->getDisplayname(),
                    'topic_created_mcname' => $topic_creator->getDisplayname(true),
                    'topic_created_style' => $topic_creator->getGroupStyle(),
                    'topic_created_user_id' => Output::getClean($discussion->topic_creator),
                    'locked' => $discussion->locked,
                    'forum_name' => $forum_name,
                    'forum_id' => $discussion->forum_id,
                    'views' => $discussion->topic_views,
                    'posts' => $posts,
                    'last_reply_avatar' => $last_reply_user->getAvatar(64),
                    'last_reply_rough' => $timeago->inWords($discussion->topic_reply_date, $this->_language),
                    'last_reply' => date(DATE_FORMAT, $discussion->topic_reply_date),
                    'last_reply_username' => $last_reply_user->getDisplayname(),
                    'last_reply_mcname' => $last_reply_user->getDisplayname(true),
                    'last_reply_style' => $last_reply_user->getGroupStyle(),
                    'last_reply_user_id' => Output::getClean($discussion->topic_last_user),
                    'label' => $label,
                    'link' => URL::build('/forum/topic/' . urlencode($discussion->id) . '-' . $forum->titleToURL($discussion->topic_title)),
                    'forum_link' => URL::build('/forum/forum/' . $discussion->forum_id),
                    'author_link' => $topic_creator->getProfileURL(),
                    'last_reply_profile_link' => $last_reply_user->getProfileURL(),
                    'last_reply_link' => URL::build('/forum/topic/' . $discussion->id . '-' . $forum->titleToURL($discussion->topic_title), 'pid=' . $discussion->last_post_id)
                ];
            }

            $this->_cache->store('discussions', $template_array, 60);
        }

        // Generate HTML code for widget
        $this->_smarty->assign('LATEST_POSTS_ARRAY', $template_array);

        $this->_content = $this->_smarty->fetch('widgets/forum/latest_posts.tpl');
    }
}
