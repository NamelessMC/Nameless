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
	private $_cache, $_smarty, $_language;

    public function __construct($pages = array(), $smarty, $language, $cache){
    	$this->_cache = $cache;
    	$this->_smarty = $smarty;
    	$this->_language = $language;

        parent::__construct($pages);

        // Get order
        $order = DB::getInstance()->query('SELECT `order` FROM nl2_widgets WHERE `name` = ?', array('Online Staff'))->first();
		// Get location
		$location = DB::getInstance()->query('SELECT `location` FROM nl2_widgets WHERE `name` = ?', array('Online Staff'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_location = $location->location;
        $this->_description = 'Displays a list of online staff members on your website.';
        $this->_order = $order->order;
    }

    public function initialise(){
	    $this->_cache->setCache('online_members');

	    if($this->_cache->isCached('staff'))
		    $online = $this->_cache->retrieve('staff');
	    else {
		    $online = DB::getInstance()->query('SELECT nl2_users.id, nl2_users.username, nl2_users.nickname, nl2_users.user_title, nl2_users.group_id, g.order, g.group_html FROM nl2_users RIGHT JOIN (SELECT nl2_groups.id, nl2_groups.order, nl2_groups.group_html FROM nl2_groups WHERE staff = 1) g ON nl2_users.group_id = g.id WHERE nl2_users.last_online > ' . strtotime('-5 minutes') . ' AND nl2_users.id IS NOT NULL ORDER BY g.order', array())->results();
		    $this->_cache->store('staff', $online, 120);
	    }

	    // Generate HTML code for widget
	    if(count($online)){
		    $user = new User();

		    $staff_members = array();

		    foreach($online as $staff)
			    $staff_members[] = array(
				    'profile' => URL::build('/profile/' . Output::getClean($staff->username)),
				    'style' => $user->getGroupClass($staff->id),
				    'username' => Output::getClean($staff->username),
				    'nickname' => Output::getClean($staff->nickname),
				    'avatar' => $user->getAvatar($staff->id),
				    'id' => Output::getClean($staff->id),
				    'title' => Output::getClean($staff->user_title),
					'group' => $staff->group_html,
					'group_order' => $staff->order
				);

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