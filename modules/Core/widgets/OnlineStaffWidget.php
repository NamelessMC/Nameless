<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Online staff widget
 */

class OnlineStaffWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(TemplateEngine $engine, Language $language, Cache $cache) {
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_description = 'Displays a list of online staff members on your website.';
        $this->_engine = $engine;

        $this->_cache = $cache;
        $this->_language = $language;
    }

    public function initialise(): void {
        $this->_cache->setCache('online_staff_widget');

        $online_staff = $this->_cache->fetch('staff', function () {
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
                    AND g.staff = 1
                    ORDER BY g.order ASC
                    LIMIT 10
                SQL,
                [strtotime('-5 minutes')]
            )->results();

            $staff_members = [];
            foreach ($online as $staff) {
                $staff_user = new User($staff->id);
                if ($staff_user->exists()) {
                    $staff_members[] = [
                        'profile' => $staff_user->getProfileURL(),
                        'style' => $staff_user->getGroupStyle(),
                        'username' => $staff_user->getDisplayname(true),
                        'nickname' => $staff_user->getDisplayname(),
                        'avatar' => $staff_user->getAvatar(),
                        'id' => Output::getClean($staff_user->data()->id),
                        'group' => $staff_user->getMainGroup()->group_html,
                    ];
                }
            }

            return $staff_members;
        }, 120);

        // Count total online staff
        $total_online_staff = $this->_cache->fetch('total_staff', function () {
            return DB::getInstance()->query(
                <<<SQL
                    SELECT
                        COUNT(u.id) AS count
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
                    AND g.staff = 1
                SQL,
                [strtotime('-5 minutes')]
            )->first()->count;
        }, 120);

        // Generate HTML code for widget
        if (count($online_staff)) {
            $this->_engine->addVariables([
                'ONLINE_STAFF' => $this->_language->get('general', 'online_staff'),
                'ONLINE_STAFF_LIST' => $online_staff,
                'TOTAL_ONLINE_STAFF' => $this->_language->get('general', 'total_online_staff', ['count' => $total_online_staff]),
            ]);

        } else {
            $this->_engine->addVariables([
                'ONLINE_STAFF' => $this->_language->get('general', 'online_staff'),
                'NO_STAFF_ONLINE' => $this->_language->get('general', 'no_online_staff'),
                'TOTAL_ONLINE_STAFF' => $this->_language->get('general', 'total_online_staff', ['count' => 0]),
            ]);
        }

        $this->_content = $this->_engine->fetch('widgets/online_staff');
    }
}
