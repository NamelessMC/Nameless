<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Statistics Widget // By Xemah // https://xemah.me
 */

class StatsWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(TemplateEngine $engine, Language $language, Cache $cache) {
        $this->_module = 'Core';
        $this->_name = 'Statistics';
        $this->_description = 'Displays the basic statistics of your website.';
        $this->_engine = $engine;

        $this->_cache = $cache;
        $this->_language = $language;
    }

    public function initialise(): void {
        $this->_cache->setCache('statistics');

        $users_query = $this->_cache->fetch('statistics', function () {
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

            return [
                'users_registered' => $users_registered,
                'latest_member' => $latest_member
            ];
        }, 120);

        $users_registered = $users_query['users_registered'];
        $latest_member = $users_query['latest_member'];

        $online_users = $this->_cache->fetch('online_users', function () {
            return DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_users WHERE last_online > ?', [strtotime('-5 minutes')])->first()->c;
        }, 60);

        $online_guests = $this->_cache->fetch('online_guests', function () {
            return DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_online_guests WHERE last_seen > ?', [strtotime('-5 minutes')])->first()->c;
        }, 60);

        if (Util::isModuleEnabled('Forum')) {
            $this->_cache->setCache('forum_stats');
            $total_topics = $this->_cache->fetch('total_topics', function () {
                return DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_topics WHERE deleted = 0')->first()->c;
            }, 60);

            $total_posts = $this->_cache->fetch('total_posts', function () {
                return DB::getInstance()->query('SELECT COUNT(*) as c FROM nl2_posts WHERE deleted = 0')->first()->c;
            }, 60);

            $this->_engine->addVariables([
                'FORUM_STATISTICS' => $this->_language->get('general', 'forum_statistics'),
                'TOTAL_THREADS' => $this->_language->get('general', 'total_threads'),
                'TOTAL_THREADS_VALUE' => $total_topics,
                'TOTAL_POSTS' => $this->_language->get('general', 'total_posts'),
                'TOTAL_POSTS_VALUE' => $total_posts,
            ]);
        }

        $this->_engine->addVariables([
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

        $this->_content = $this->_engine->fetch('widgets/statistics');
    }
}
