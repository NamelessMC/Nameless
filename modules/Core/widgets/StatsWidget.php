<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Statistics Widget // By Xemah // https://xemah.me
 */

class StatsWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        // Get widget
        $widget_query = self::getData('Statistics');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Statistics';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays the basic statistics of your website.';
        $this->_order = $widget_query->order;
    }

    public function initialise(): void {
        $this->_cache->setCache('statistics');

        if ($this->_cache->isCached('statistics')) {
            $users_query = $this->_cache->retrieve('statistics');
            $users_registered = $users_query['users_registered'];
            $latest_member = $users_query['latest_member'];

        } else {
            $users_query = DB::getInstance()->query('SELECT `id` FROM nl2_users ORDER BY `joined` DESC LIMIT 1')->first()->id;
            $users_registered = DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_users')->first()->c;

            $latest_user = new User($users_query);
            $latest_member = [
                'style' => $latest_user->getGroupStyle(),
                'profile' => $latest_user->getProfileURL(),
                'avatar' => $latest_user->getAvatar(),
                'username' => $latest_user->getDisplayname(true),
                'nickname' => $latest_user->getDisplayname(),
                'id' => Output::getClean($users_query)
            ];

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
            $online_users = DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_users WHERE last_online > ?', [strtotime('-5 minutes')])->first()->c;
            $this->_cache->store('online_users', $online_users, 60);
        } else {
            $online_users = $this->_cache->retrieve('online_users');
        }

        if (!$this->_cache->isCached('online_guests')) {
            try {
                $online_guests = DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_online_guests WHERE last_seen > ?', [strtotime('-5 minutes')])->first()->c;
                $this->_cache->store('online_guests', $online_guests, 60);
            } catch (Exception $e) {
                // Upgrade script hasn't been run
                $online_guests = 0;
            }
        } else {
            $online_guests = $this->_cache->retrieve('online_guests');
        }

        if (Util::isModuleEnabled('Forum')) {
            $this->_cache->setCache('forum_stats');
            if (!$this->_cache->isCached('total_topics')) {
                $total_topics = DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_topics WHERE deleted = 0')->first()->c;
                $this->_cache->store('total_topics', $total_topics, 60);
            } else {
                $total_topics = $this->_cache->retrieve('total_topics');
            }

            if (!$this->_cache->isCached('total_posts')) {
                $total_posts = DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_posts WHERE deleted = 0')->first()->c;
                $this->_cache->store('total_posts', $total_posts, 60);
            } else {
                $total_posts = $this->_cache->retrieve('total_posts');
            }

            $this->_smarty->assign([
                'FORUM_STATISTICS' => $this->_language->get('general', 'forum_statistics'),
                'TOTAL_THREADS' => $this->_language->get('general', 'total_threads'),
                'TOTAL_THREADS_VALUE' => $total_topics,
                'TOTAL_POSTS' => $this->_language->get('general', 'total_posts'),
                'TOTAL_POSTS_VALUE' => $total_posts,
            ]);
        }

        $this->_smarty->assign([
            'STATISTICS' => $this->_language->get('general', 'statistics'),
            'USERS_REGISTERED' => $this->_language->get('general', 'users_registered'),
            'USERS_REGISTERED_VALUE' => $users_registered,
            'LATEST_MEMBER' => $this->_language->get('general', 'latest_member'),
            'LATEST_MEMBER_VALUE' => $latest_member,
            'USERS_ONLINE' => $this->_language->get('general', 'online_users'),
            'USERS_ONLINE_VALUE' => $online_users,
            'GUESTS_ONLINE' => $this->_language->get('general', 'online_guests'),
            'GUESTS_ONLINE_VALUE' => $online_guests,
            'TOTAL_ONLINE' => $this->_language->get('general', 'total_online'),
            'TOTAL_ONLINE_VALUE' => $online_guests + $online_users,
        ]);

        $this->_content = $this->_smarty->fetch('widgets/statistics.tpl');
    }
}
