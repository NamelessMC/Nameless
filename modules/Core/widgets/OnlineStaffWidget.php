<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Online staff widget
 */

class OnlineStaffWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        // Get widget
        $widget_query = self::getData('Online Staff');

        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays a list of online staff members on your website.';
        $this->_order = $widget_query->order;
    }

    public function initialise(): void {
        $this->_cache->setCache('online_members');

        if ($this->_cache->isCached('staff')) {
            $online = $this->_cache->retrieve('staff');
        } else {
            $online = DB::getInstance()->query('SELECT U.id FROM nl2_users AS U JOIN nl2_users_groups AS UG ON (U.id = UG.user_id) JOIN nl2_groups AS G ON (UG.group_id = G.id) WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id ORDER BY NULL) AND U.last_online > ' . strtotime('-5 minutes') . ' AND G.staff = 1')->results();
            $this->_cache->store('staff', $online, 120);
        }

        // Generate HTML code for widget
        if (count($online)) {
            $staff_members = [];

            foreach ($online as $staff) {
                if (count($staff_members) === 10) {
                    break;
                }

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
                        'group_order' => $staff_user->getMainGroup()->order
                    ];
                }
            }

            $this->_smarty->assign([
                'ONLINE_STAFF' => $this->_language->get('general', 'online_staff'),
                'ONLINE_STAFF_LIST' => $staff_members,
                'TOTAL_ONLINE_STAFF' => $this->_language->get('general', 'total_online_staff', ['count' => count($online)]),
            ]);

        } else {
            $this->_smarty->assign([
                'ONLINE_STAFF' => $this->_language->get('general', 'online_staff'),
                'NO_STAFF_ONLINE' => $this->_language->get('general', 'no_online_staff'),
                'TOTAL_ONLINE_STAFF' => $this->_language->get('general', 'total_online_staff', ['count' => 0]),
            ]);
        }

        $this->_content = $this->_smarty->fetch('widgets/online_staff.tpl');
    }
}
