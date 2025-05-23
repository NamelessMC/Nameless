<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Online users widget
 */

class OnlineUsersWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Cache $cache, TemplateEngine $engine, Language $language) {
        $this->_module = 'Core';
        $this->_name = 'Online Users';
        $this->_description = 'Displays a list of online users on your website.';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_widgets/online_users.php';

        $this->_engine = $engine;
        $this->_cache = $cache;
        $this->_language = $language;
    }

    public function initialise(): void {
        $this->_cache->setCache('online_members');

        $online = $this->_cache->fetch('users', function () {
            if (Settings::get('online_users_widget_include_staff', 0)) {
                $online = DB::getInstance()->query('SELECT id FROM nl2_users WHERE last_online > ?', [strtotime('-5 minutes')])->results();
            } else {
                $online = DB::getInstance()->query('SELECT U.id FROM nl2_users AS U JOIN nl2_users_groups AS UG ON (U.id = UG.user_id) JOIN nl2_groups AS G ON (UG.group_id = G.id) WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id ORDER BY NULL) AND U.last_online > ' . strtotime('-5 minutes') . ' AND G.staff = 0')->results();
            }

            return $online;
        }, 120);

        // Generate HTML code for widget
        if (count($online)) {
            $users = [];

            foreach ($online as $item) {
                if (count($users) === 10) {
                    break;
                }

                $online_user = new User($item->id);
                if ($online_user->exists()) {
                    $users[] = [
                        'profile' => $online_user->getProfileURL(),
                        'style' => $online_user->getGroupStyle(),
                        'username' => $online_user->getDisplayname(true),
                        'nickname' => $online_user->getDisplayname(),
                        'avatar' => $online_user->getAvatar(),
                        'id' => Output::getClean($online_user->data()->id),
                        'title' => Output::getClean($online_user->data()->user_title),
                        'group' => $online_user->getMainGroup()->group_html
                    ];
                }
            }

            $this->_engine->addVariables([
                'SHOW_NICKNAME_INSTEAD' => Settings::get('online_users_widget_use_nicknames', 0),
                'ONLINE_USERS' => $this->_language->get('general', 'online_users'),
                'ONLINE_USERS_LIST' => $users,
                'TOTAL_ONLINE_USERS' => $this->_language->get('general', 'total_online_users', ['count' => count($online)])
            ]);

        } else {
            $this->_engine->addVariables([
                'ONLINE_USERS' => $this->_language->get('general', 'online_users'),
                'NO_USERS_ONLINE' => $this->_language->get('general', 'no_online_users'),
                'TOTAL_ONLINE_USERS' => $this->_language->get('general', 'total_online_users', ['count' => 0])
            ]);
        }

        $this->_content = $this->_engine->fetch('widgets/online_users');
    }
}
