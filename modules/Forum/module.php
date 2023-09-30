<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.2
 *
 *  License: MIT
 *
 *  Forum module file
 */

class Forum_Module extends Module {

    private Language $_language;
    private Language $_forum_language;

    public function __construct(Language $language, Language $forum_language, Pages $pages) {
        $this->_language = $language;
        $this->_forum_language = $forum_language;

        $name = 'Forum';
        $author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
        $module_version = '2.1.2';
        $nameless_version = '2.1.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        // Define URLs which belong to this module
        $pages->add('Forum', '/panel/forums', 'pages/panel/forums.php');
        $pages->add('Forum', '/panel/forums/labels', 'pages/panel/labels.php');
        $pages->add('Forum', '/panel/forums/settings', 'pages/panel/settings.php');

        $pages->add('Forum', '/forum', 'pages/forum/index.php', 'forum', true);
        $pages->add('Forum', '/forum/error', 'pages/forum/error.php');
        $pages->add('Forum', '/forum/view', 'pages/forum/view_forum.php');
        $pages->add('Forum', '/forum/topic', 'pages/forum/view_topic.php');
        $pages->add('Forum', '/forum/new', 'pages/forum/new_topic.php');
        $pages->add('Forum', '/forum/spam', 'pages/forum/spam.php');
        $pages->add('Forum', '/forum/report', 'pages/forum/report.php');
        $pages->add('Forum', '/forum/get_quotes', 'pages/forum/get_quotes.php');
        $pages->add('Forum', '/forum/delete_post', 'pages/forum/delete_post.php');
        $pages->add('Forum', '/forum/delete', 'pages/forum/delete.php');
        $pages->add('Forum', '/forum/move', 'pages/forum/move.php');
        $pages->add('Forum', '/forum/merge', 'pages/forum/merge.php');
        $pages->add('Forum', '/forum/edit', 'pages/forum/edit.php');
        $pages->add('Forum', '/forum/lock', 'pages/forum/lock.php');
        $pages->add('Forum', '/forum/stick', 'pages/forum/stick.php');
        $pages->add('Forum', '/forum/reactions', 'pages/forum/reactions.php');
        $pages->add('Forum', '/forum/search', 'pages/forum/search.php');

        // UserCP
        $pages->add('Forum', '/user/following_topics', 'pages/user/following_topics.php');

        // Redirects
        $pages->add('Forum', '/forum/view_topic', 'pages/forum/redirect.php');
        $pages->add('Forum', '/forum/view_forum', 'pages/forum/redirect.php');

        EventHandler::registerListener(UserDeletedEvent::class, DeleteUserForumHook::class);
        EventHandler::registerListener(GroupClonedEvent::class, CloneGroupForumHook::class);

        // -- Events
        EventHandler::registerEvent(TopicCreatedEvent::class);
        EventHandler::registerEvent(TopicReplyCreatedEvent::class);

        // -- Pipelines

        EventHandler::registerEvent('prePostCreate',
            $this->_forum_language->get('forum', 'pre_post_create_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_forum_language->get('forum', 'post_id'),
                'topic_id' => $this->_forum_language->get('forum', 'topic_id'),
                'user' => $this->_forum_language->get('forum', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('prePostEdit',
            $this->_forum_language->get('forum', 'pre_post_edit_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_forum_language->get('forum', 'post_id'),
                'topic_id' => $this->_forum_language->get('forum', 'topic_id'),
                'user' => $this->_forum_language->get('forum', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('preTopicCreate',
            $this->_forum_language->get('forum', 'pre_topic_create_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_forum_language->get('forum', 'post_id'),
                'topic_id' => $this->_forum_language->get('forum', 'topic_id'),
                'user' => $this->_forum_language->get('forum', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('preTopicEdit',
            $this->_forum_language->get('forum', 'pre_topic_edit_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_forum_language->get('forum', 'post_id'),
                'topic_id' => $this->_forum_language->get('forum', 'topic_id'),
                'topic_title' => $this->_forum_language->get('forum', 'topic_title'),
                'user' => $this->_forum_language->get('forum', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('renderPost',
            $this->_forum_language->get('forum', 'render_post'),
            [
                'content' => $this->_language->get('general', 'content')
            ],
            true,
            true
        );

        EventHandler::registerEvent('renderPostEdit',
            $this->_forum_language->get('forum', 'render_post_edit'),
            [
                'content' => $this->_language->get('general', 'content')
            ],
            true,
            true
        );

        EventHandler::registerListener('prePostCreate', 'MentionsHook::preCreate');
        EventHandler::registerListener('prePostEdit', 'MentionsHook::preEdit');
        EventHandler::registerListener('preTopicCreate', 'MentionsHook::preCreate');
        EventHandler::registerListener('preTopicEdit', 'MentionsHook::preEdit');

        EventHandler::registerListener('renderPost', 'ContentHook::purify');
        EventHandler::registerListener('renderPost', 'ContentHook::renderEmojis', 10);
        EventHandler::registerListener('renderPost', 'ContentHook::replaceAnchors', 5);
        EventHandler::registerListener('renderPost', 'MentionsHook::parsePost', 5);

        EventHandler::registerListener('renderPostEdit', 'ContentHook::purify');
        EventHandler::registerListener('renderPostEdit', 'ContentHook::replaceAnchors', 15);

        MemberListManager::getInstance()->registerListProvider(new MostPostsMemberListProvider($forum_language));
        MemberListManager::getInstance()->registerListProvider(new HighestReactionScoresMemberListProvider($forum_language));

        MemberListManager::getInstance()->registerMemberMetadataProvider(function (User $member) use ($forum_language) {
            return [
                $forum_language->get('forum', 'posts_title') =>
                    DB::getInstance()->query(
                        'SELECT COUNT(post_content) AS `count` FROM nl2_posts WHERE post_creator = ?',
                        [$member->data()->id]
                    )->first()->count,
            ];
        });

        MemberListManager::getInstance()->registerMemberMetadataProvider(function (User $member) use ($forum_language) {
            return [
                $forum_language->get('forum', 'reaction_score') =>
                    DB::getInstance()->query(
                        'SELECT COUNT(fr.user_received) AS `count` FROM nl2_forums_reactions fr JOIN nl2_reactions r ON r.id = fr.reaction_id WHERE r.type = 2 AND fr.user_received = ?',
                        [$member->data()->id]
                    )->first()->count,
            ];
        });
    }

    public function onInstall() {
        // Not necessary for Forum
    }

    public function onUninstall() {

    }

    public function onEnable() {
        // No actions necessary
    }

    public function onDisable() {
        // No actions necessary
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
        // AdminCP
        PermissionHandler::registerPermissions('Forum', [
            'admincp.forums' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_forum_language->get('forum', 'forum')
        ]);

        // Sitemap
        $pages->registerSitemapMethod([Forum_Sitemap::class, 'generateSitemap']);

        // Add link to navbar
        $cache->setCache('nav_location');
        if (!$cache->isCached('forum_location')) {
            $link_location = 1;
            $cache->store('forum_location', 1);
        } else {
            $link_location = $cache->retrieve('forum_location');
        }

        $cache->setCache('navbar_order');
        if (!$cache->isCached('forum_order')) {
            $forum_order = 2;
            $cache->store('forum_order', 2);
        } else {
            $forum_order = $cache->retrieve('forum_order');
        }

        $cache->setCache('navbar_icons');
        if (!$cache->isCached('forum_icon')) {
            $icon = '';
        } else {
            $icon = $cache->retrieve('forum_icon');
        }

        switch ($link_location) {
            case 1:
                // Navbar
                $navs[0]->add('forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $forum_order, $icon);
                break;
            case 2:
                // "More" dropdown
                $navs[0]->addItemToDropdown('more_dropdown', 'forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $icon, $forum_order);
                break;
            case 3:
                // Footer
                $navs[0]->add('forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'footer', null, $forum_order, $icon);
                break;
        }

        // Widgets
        if ($pages->getActivePage()['widgets'] || (defined('PANEL_PAGE') && str_contains(PANEL_PAGE, 'widget'))) {
            // Latest posts
            $widgets->add(new LatestPostsWidget($this->_forum_language, $smarty, $cache, $user, $this->_language));
        }

        // Front end or back end?
        if (defined('FRONT_END')) {
            // Global variables if user is logged in
            if ($user->isLoggedIn()) {
                // Basic user variables
                $topic_count = DB::getInstance()->get('topics', ['topic_creator', $user->data()->id])->results();
                $topic_count = count($topic_count);
                $post_count = DB::getInstance()->get('posts', ['post_creator', $user->data()->id])->results();
                $post_count = count($post_count);
                $smarty->assign('LOGGED_IN_USER_FORUM', [
                    'topic_count' => $topic_count,
                    'post_count' => $post_count
                ]);
            }

            if (defined('PAGE') && PAGE == 'user_query') {
                $user_id = $smarty->getTemplateVars('USER_ID');

                if ($user_id) {
                    $forum = new Forum();

                    $smarty->assign('TOPICS', $this->_forum_language->get('forum', 'x_topics', ['count' => $forum->getTopicCount($user_id)]));
                    $smarty->assign('POSTS', $this->_forum_language->get('forum', 'x_posts', ['count' => $forum->getPostCount($user_id)]));
                }
            }

        } else {
            if (defined('BACK_END')) {
                if ($user->hasPermission('admincp.forums')) {
                    $cache->setCache('panel_sidebar');
                    if (!$cache->isCached('forum_order')) {
                        $order = 12;
                        $cache->store('forum_order', 12);
                    } else {
                        $order = $cache->retrieve('forum_order');
                    }

                    if (!$cache->isCached('forum_settings_icon')) {
                        $icon = '<i class="nav-icon fas fa-cogs"></i>';
                        $cache->store('forum_settings_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('forum_settings_icon');
                    }

                    $navs[2]->add('forum_divider', mb_strtoupper($this->_forum_language->get('forum', 'forum'), 'UTF-8'), 'divider', 'top', null, $order, '');
                    $navs[2]->add('forum_settings', $this->_language->get('admin', 'settings'), URL::build('/panel/forums/settings'), 'top', null, $order + 0.1, $icon);

                    if (!$cache->isCached('forum_icon')) {
                        $icon = '<i class="nav-icon fas fa-comments"></i>';
                        $cache->store('forum_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('forum_icon');
                    }

                    $navs[2]->add('forums', $this->_forum_language->get('forum', 'forums'), URL::build('/panel/forums'), 'top', null, $order + 0.2, $icon);

                    if (!$cache->isCached('forum_label_icon')) {
                        $icon = '<i class="nav-icon fas fa-tags"></i>';
                        $cache->store('forum_label_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('forum_label_icon');
                    }

                    $navs[2]->add('forum_labels', $this->_forum_language->get('forum', 'labels'), URL::build('/panel/forums/labels'), 'top', null, $order + 0.3, $icon);
                }

                if (defined('PANEL_PAGE') && PANEL_PAGE == 'dashboard') {
                    // Dashboard graph

                    // Get data for topics and posts
                    $start_time = strtotime('7 days ago');
                    $latest_topics = DB::getInstance()->query(
                        <<<SQL
                            SELECT DATE_FORMAT(FROM_UNIXTIME(`topic_date`), '%Y-%m-%d') d, COUNT(*) c
                            FROM nl2_topics
                            WHERE `topic_date` > ? AND `topic_date` < UNIX_TIMESTAMP()
                            AND `deleted` = 0
                            GROUP BY DATE_FORMAT(FROM_UNIXTIME(`topic_date`), '%Y-%m-%d')
                        SQL,
                        [$start_time],
                    );
                    $latest_topics_count = $latest_topics->count();
                    $latest_topics = $latest_topics->results();

                    $latest_posts = DB::getInstance()->query(
                        <<<SQL
                            SELECT DATE_FORMAT(FROM_UNIXTIME(`created`), '%Y-%m-%d') d, COUNT(*) c
                            FROM nl2_posts
                            WHERE `created` > ? AND `created` < UNIX_TIMESTAMP()
                            AND `deleted` = 0
                            GROUP BY DATE_FORMAT(FROM_UNIXTIME(`created`), '%Y-%m-%d')
                        SQL,
                        [$start_time],
                    );
                    $latest_posts_count = $latest_posts->count();
                    $latest_posts = $latest_posts->results();

                    $cache->setCache('dashboard_graph');
                    if ($cache->isCached('forum_data')) {
                        $data = $cache->retrieve('forum_data');

                    } else {
                        $data = [];

                        $data['datasets']['topics']['label'] = 'forum_language/forum/topics_title'; // for $forum_language->get('forum', 'topics_title');
                        $data['datasets']['topics']['colour'] = '#00931D';
                        $data['datasets']['posts']['label'] = 'forum_language/forum/posts_title'; // for $forum_language->get('forum', 'posts_title');
                        $data['datasets']['posts']['colour'] = '#ffde0a';

                        if (count($latest_topics)) {
                            foreach ($latest_topics as $day) {
                                $data['_' . $day->d] = ['topics' => $day->c];
                            }
                        }

                        if (count($latest_posts)) {
                            foreach ($latest_posts as $day) {
                                if (isset($data['_' . $day->d])) {
                                    $data['_' . $day->d]['posts'] = $day->c;
                                } else {
                                    $data['_' . $day->d] = ['posts' => $day->c];
                                }
                            }
                        }

                        $data = Core_Module::fillMissingGraphDays($data, 'topics');
                        $data = Core_Module::fillMissingGraphDays($data, 'posts');

                        // Sort by date
                        ksort($data);

                        $cache->store('forum_data', $data, 120);
                    }

                    Core_Module::addDataToDashboardGraph($this->_language->get('admin', 'overview'), $data);

                    // Dashboard stats
                    require_once(ROOT_PATH . '/modules/Forum/collections/panel/RecentTopics.php');
                    CollectionManager::addItemToCollection('dashboard_stats', new RecentTopicsItem($smarty, $this->_forum_language, $cache, $latest_topics_count));

                    require_once(ROOT_PATH . '/modules/Forum/collections/panel/RecentPosts.php');
                    CollectionManager::addItemToCollection('dashboard_stats', new RecentPostsItem($smarty, $this->_forum_language, $cache, $latest_posts_count));

                }
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
