<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Statistics Widget // By Xemah // https://xemah.me
 */

class StatsWidget extends WidgetBase {

    private Cache $_cache;
    private Smarty $_smarty;
    private Language $_language;

    public function __construct(array $pages, Smarty $smarty, Language $language, Cache $cache) {
        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->selectQuery('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', ['Statistics'])->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Statistics';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays the basic statistics of your website.';
        $this->_order = $widget_query->order;
    }

    public function initialise(): void {
        $queries = new Queries();
        $user = new User();

        $this->_cache->setCache('statistics');

        if ($this->_cache->isCached('statistics')) {

            $users_query = $this->_cache->retrieve('statistics');
            $users_registered = $users_query['users_registered'];
            $latest_member = $users_query['latest_member'];

        } else {

            $users_query = $queries->orderAll('users', 'joined', 'DESC');
            $users_registered = count($users_query);

            $latest_user = new User($users_query[0]->id);
            $latest_member = [
                'style' => $latest_user->getGroupClass(),
                'profile' => $latest_user->getProfileURL(),
                'avatar' => $latest_user->getAvatar(),
                'username' => $latest_user->getDisplayname(true),
                'nickname' => $latest_user->getDisplayname(),
                'id' => Output::getClean($users_query[0]->id)
            ];

            $users_query = null;

            $this->_cache->store(
                'statistics',
                [
                    'users_registered' => $users_registered,
                    'latest_member' => $latest_member
                ],
                120
            );

        }

        if (!$this->_cache->isCached('online_users')) {
            $online_users = DB::getInstance()->selectQuery('SELECT count(*) FROM nl2_users WHERE last_online > ?', [strtotime('-5 minutes')])->first();
            $online_users = $online_users->{'count(*)'};
            $this->_cache->store('online_users', $online_users, 60);
        } else {
            $online_users = $this->_cache->retrieve('online_users');
        }

        if (!$this->_cache->isCached('online_guests')) {
            try {
                $online_guests = DB::getInstance()->selectQuery('SELECT count(*) FROM nl2_online_guests WHERE last_seen > ?', [strtotime('-5 minutes')])->first();
                $online_guests = $online_guests->{'count(*)'};
                $this->_cache->store('online_guests', $online_guests, 60);
            } catch (Exception $e) {
                // Upgrade script hasn't been run
                $online_guests = 0;
            }
        } else {
            $online_guests = $this->_cache->retrieve('online_guests');
        }

        $forum_module = $queries->getWhere('modules', ['name', '=', 'Forum']);
        $forum_module = $forum_module[0];

        if ($forum_module->enabled) {
            $this->_cache->setCache('forum_stats');
            if (!$this->_cache->isCached('total_topics')) {
                $total_topics = DB::getInstance()->selectQuery('SELECT count(*) FROM nl2_topics WHERE deleted = 0')->first();
                $total_topics = $total_topics->{'count(*)'};
                $this->_cache->store('total_topics', $total_topics, 60);
            } else {
                $total_topics = $this->_cache->retrieve('total_topics');
            }

            if (!$this->_cache->isCached('total_posts')) {
                $total_posts = DB::getInstance()->selectQuery('SELECT count(*) FROM nl2_posts WHERE deleted = 0')->first();
                $total_posts = $total_posts->{'count(*)'};
                $this->_cache->store('total_posts', $total_posts, 60);
            } else {
                $total_posts = $this->_cache->retrieve('total_posts');
            }

            $this->_smarty->assign(
                [
                    'FORUM_STATISTICS' => $this->_language['forum_stats'],
                    'TOTAL_THREADS' =>  $this->_language['total_threads'],
                    'TOTAL_THREADS_VALUE' => $total_topics,
                    'TOTAL_POSTS' =>  $this->_language['total_posts'],
                    'TOTAL_POSTS_VALUE' => $total_posts,
                ]
            );
        }

        $this->_smarty->assign(
            [
                'STATISTICS' =>  $this->_language['statistics'],
                'USERS_REGISTERED' =>  $this->_language['users_registered'],
                'USERS_REGISTERED_VALUE' => $users_registered,
                'LATEST_MEMBER' =>  $this->_language['latest_member'],
                'LATEST_MEMBER_VALUE' => $latest_member,
                'USERS_ONLINE' => $this->_language['users_online'],
                'USERS_ONLINE_VALUE' => $online_users,
                'GUESTS_ONLINE' => $this->_language['guests_online'],
                'GUESTS_ONLINE_VALUE' => $online_guests,
                'TOTAL_ONLINE' => $this->_language['total_online'],
                'TOTAL_ONLINE_VALUE' => $online_guests + $online_users
            ]
        );

        $this->_content = $this->_smarty->fetch('widgets/statistics.tpl');
    }
}
