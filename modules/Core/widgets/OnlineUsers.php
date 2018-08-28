<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Online users widget
 */
class OnlineUsersWidget extends WidgetBase {
    public function __construct($pages = array(), $online_users = array(), $smarty, $language){
        parent::__construct($pages);

        // Get order
        $order = DB::getInstance()->query('SELECT `order` FROM nl2_widgets WHERE `name` = ?', array('Online Users'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Users';
        $this->_location = 'right';
        $this->_description = 'Displays a list of online users on your website.';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_widgets/online_users.php';
        $this->_order = $order->order;

        // Generate HTML code for widget
        if(count($online_users)){
            $user = new User();

            $users = array();

            foreach($online_users as $item)
                $users[] = array(
                    'profile' => URL::build('/profile/' . Output::getClean($item->username)),
                    'style' => $user->getGroupClass($item->id),
                    'username' => Output::getClean($item->username),
                    'nickname' => Output::getClean($item->nickname),
                    'avatar' => $user->getAvatar($item->id)
                );

            $smarty->assign(array(
                'ONLINE_USERS' => $language['title'],
                'ONLINE_USERS_LIST' => $users
            ));

        } else
            $smarty->assign(array(
                'ONLINE_USERS' => $language['title'],
                'NO_USERS_ONLINE' => $language['no_online_users']
            ));

        $this->_content = $smarty->fetch('widgets/online_users.tpl');

    }
}