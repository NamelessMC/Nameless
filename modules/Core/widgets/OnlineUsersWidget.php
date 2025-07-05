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
        $this->_cache->setCache('online_members_widget');

        $online_users = $this->_cache->fetch('users', function () {
            if (Settings::get('online_users_widget_include_staff', 0)) {
                $online = DB::getInstance()->query(
                    <<<SQL
                        SELECT
                            u.id
                        FROM nl2_users AS u
                        JOIN nl2_users_groups AS ug ON u.id = ug.user_id
                        JOIN nl2_groups AS g ON ug.group_id = g.id
                        WHERE g.order = (
                            SELECT MIN(ig.order)
                            FROM nl2_users_groups AS iug
                            JOIN nl2_groups AS ig ON iug.group_id = ig.id
                            WHERE iug.user_id = u.id
                            GROUP BY iug.user_id
                            ORDER BY NULL
                        )
                        AND u.last_online > ?
                        ORDER BY g.order ASC
                        LIMIT 10
                    SQL,
                [strtotime('-5 minutes')]
                )->results();
            } else {
                $online = DB::getInstance()->query(
                    <<<SQL
                        SELECT
                            u.id
                        FROM nl2_users AS u
                        JOIN nl2_users_groups AS ug ON u.id = ug.user_id
                        JOIN nl2_groups AS g ON ug.group_id = g.id
                        WHERE g.order = (
                            SELECT MIN(ig.order)
                            FROM nl2_users_groups AS iug
                            JOIN nl2_groups AS ig ON iug.group_id = ig.id
                            WHERE iug.user_id = u.id
                            GROUP BY iug.user_id
                            ORDER BY NULL
                        )
                        AND u.last_online > ?
                        AND g.staff = 0
                        ORDER BY g.order ASC
                        LIMIT 10
                    SQL,
                    [strtotime('-5 minutes')]
                )->results();
            }

            foreach ($online as $item) {
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

            return $users;
        }, 120);

        // Count total online users
        $total_online_users = $this->_cache->fetch('total', function () {
            if (Settings::get('online_users_widget_include_staff', 0)) {
                return DB::getInstance()->query('SELECT COUNT(id) AS count FROM nl2_users WHERE last_online > ?', [strtotime('-5 minutes')])->first()->count;
            } else {
                return DB::getInstance()->query(
                    <<<SQL
                        SELECT
                            COUNT(u.id) as count
                        FROM nl2_users AS u
                        JOIN nl2_users_groups AS ug ON u.id = ug.user_id
                        JOIN nl2_groups AS g ON ug.group_id = g.id
                        WHERE g.order = (
                            SELECT MIN(ig.order)
                            FROM nl2_users_groups AS iug
                            JOIN nl2_groups AS ig ON iug.group_id = ig.id
                            WHERE iug.user_id = u.id
                            GROUP BY iug.user_id
                            ORDER BY NULL
                        )
                        AND u.last_online > ?
                        AND g.staff = 0
                    SQL,
                    [strtotime('-5 minutes')]
                )->first()->count;
            }
        }, 120);

        // Generate HTML code for widget
        if (count($online_users)) {
            $this->_engine->addVariables([
                'SHOW_NICKNAME_INSTEAD' => Settings::get('online_users_widget_use_nicknames', 0),
                'ONLINE_USERS' => $this->_language->get('general', 'online_users'),
                'ONLINE_USERS_LIST' => $online_users,
                'TOTAL_ONLINE_USERS' => $this->_language->get('general', 'total_online_users', ['count' => $total_online_users])
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
