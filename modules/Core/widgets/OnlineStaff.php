<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Online staff widget
 */
class OnlineStaffWidget extends WidgetBase {
    public function __construct($pages = array(), $online_staff = array(), $smarty, $language){
        parent::__construct($pages);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_location = 'right';
        $this->_description = 'Displays a list of online staff members on your website.';

        // Generate HTML code for widget
        if(count($online_staff)){
            $user = new User();

            $staff_members = array();

            foreach($online_staff as $staff)
                $staff_members[] = array(
                    'profile' => URL::build('/profile/' . Output::getClean($staff->username)),
                    'style' => $user->getGroupClass($staff->id),
                    'username' => Output::getClean($staff->username),
                    'nickname' => Output::getClean($staff->nickname),
                    'avatar' => $user->getAvatar($staff->id)
                );

            $smarty->assign(array(
                'ONLINE_STAFF' => $language['title'],
                'ONLINE_STAFF_LIST' => $staff_members
            ));

        } else
           $smarty->assign(array(
               'ONLINE_STAFF' => $language['title'],
               'NO_STAFF_ONLINE' => $language['no_online_staff']
           ));

        $this->_content = $smarty->fetch('widgets/online_staff.tpl');

    }
}