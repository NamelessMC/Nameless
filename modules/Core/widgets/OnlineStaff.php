<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Online staff widget
 */
class OnlineStaffWidget extends WidgetBase {

    private $_cache,
            $_smarty,
            $_language;

    public function __construct($pages = array(), $smarty, $language, $cache) {
        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->query('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', array('Online Staff'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays a list of online staff members on your website.';
        $this->_order = $widget_query->order;
    }

    public function initialise() {
        $this->_cache->setCache('online_members');

        if($this->_cache->isCached('staff'))
            $online = $this->_cache->retrieve('staff');
        else {
            $online = DB::getInstance()->query('SELECT U.id FROM nl2_users AS U JOIN nl2_users_groups AS UG ON (U.id = UG.user_id) JOIN nl2_groups AS G ON (UG.group_id = G.id) WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id ORDER BY NULL) AND U.last_online > ' . strtotime('-5 minutes') . ' AND G.staff = 1', array())->results();
            $this->_cache->store('staff', $online, 120);
        }

        // Generate HTML code for widget
        if(count($online)){
            $staff_members = array();

            foreach ($online as $staff) {
                $staff_user = new User($staff->id);
                $staff_members[] = array(
                    'profile' => $staff_user->getProfileURL(),
                    'style' => $staff_user->getGroupClass(),
                    'username' => $staff_user->getDisplayname(true),
                    'nickname' => $staff_user->getDisplayname(),
                    'avatar' => $staff_user->getAvatar(),
                    'id' => Output::getClean($staff_user->data()->id),
                    'title' => Output::getClean($staff_user->data()->user_title),
                    'group' => $staff_user->getMainGroup()->group_html,
                    'group_order' => $staff_user->getMainGroup()->order
                );
            }

            $this->_smarty->assign(array(
                'ONLINE_STAFF' => $this->_language['title'],
                'ONLINE_STAFF_LIST' => $staff_members,
                'TOTAL_ONLINE_STAFF' => str_replace('{x}', count($staff_members), $this->_language['total_online_staff'])
            ));

        } else
            $this->_smarty->assign(array(
                'ONLINE_STAFF' => $this->_language['title'],
                'NO_STAFF_ONLINE' => $this->_language['no_online_staff'],
                'TOTAL_ONLINE_STAFF' => str_replace('{x}', '0', $this->_language['total_online_staff'])
            ));

        $this->_content = $this->_smarty->fetch('widgets/online_staff.tpl');
    }
}
