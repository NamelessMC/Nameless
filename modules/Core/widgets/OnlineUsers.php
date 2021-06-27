<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Online users widget
 */
class OnlineUsersWidget extends WidgetBase {

    private $_smarty,
            $_cache,
            $_language;

    public function __construct($pages = array(), $cache, $smarty, $language) {
        $this->_smarty = $smarty;
        $this->_cache = $cache;
        $this->_language = $language;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->query('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', array('Online Users'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Users';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays a list of online users on your website.';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_widgets/online_users.php';
        $this->_order = $widget_query->order;
    }

    public function initialise() {
        $this->_cache->setCache('online_members');

        if($this->_cache->isCached('users')){
            $online = $this->_cache->retrieve('users');
            $use_nickname_show = $this->_cache->retrieve('show_nickname_instead');
        }
        else {
            if($this->_cache->isCached('include_staff_in_users'))
                $include_staff = $this->_cache->retrieve('include_staff_in_users');
            else {
                $include_staff = 0;
                $this->_cache->store('include_staff_in_users', 0);
            }
            if($this->_cache->isCached('show_nickname_instead'))
                $use_nickname_show = $this->_cache->retrieve('show_nickname_instead');
            else {
                $use_nickname_show = 0;
                $this->_cache->store('show_nickname_instead', 0);
            }

            if($include_staff){
                $online = DB::getInstance()->query('SELECT id FROM nl2_users WHERE last_online > ?', array(strtotime('-5 minutes')))->results();
            } else {
                $online = DB::getInstance()->query('SELECT U.id FROM nl2_users AS U JOIN nl2_users_groups AS UG ON (U.id = UG.user_id) JOIN nl2_groups AS G ON (UG.group_id = G.id) WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id ORDER BY NULL) AND U.last_online > ' . strtotime('-5 minutes') . ' AND G.staff = 0', array())->results();
            }

            $this->_cache->store('users', $online, 120);
        }

        // Generate HTML code for widget
        if(count($online)){
            $users = array();

            foreach($online as $item) {
                $online_user = new User($item->id);
                $users[] = array(
                    'profile' => $online_user->getProfileURL(),
                    'style' => $online_user->getGroupClass(),
                    'username' => $online_user->getDisplayname(true),
                    'nickname' => $online_user->getDisplayname(),
                    'avatar' => $online_user->getAvatar(),
                    'id' => Output::getClean($online_user->data()->id),
                    'title' => Output::getClean($online_user->data()->user_title),
                    'group' => $online_user->getMainGroup()->group_html
                );
            }

            $this->_smarty->assign(array(
                'SHOW_NICKNAME_INSTEAD' => $use_nickname_show,
                'ONLINE_USERS' => $this->_language['title'],
                'ONLINE_USERS_LIST' => $users,
                'TOTAL_ONLINE_USERS' => str_replace('{x}', count($users), $this->_language['total_online_users'])
            ));

        } else
            $this->_smarty->assign(array(
                'ONLINE_USERS' => $this->_language['title'],
                'NO_USERS_ONLINE' => $this->_language['no_online_users'],
                'TOTAL_ONLINE_USERS' => str_replace('{x}', 0, $this->_language['total_online_users'])
            ));

        $this->_content = $this->_smarty->fetch('widgets/online_users.tpl');
    }
}
