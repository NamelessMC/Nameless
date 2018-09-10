<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
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

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Online Staff';
        $this->_location = 'right';
        $this->_description = 'Displays a list of online staff members on your website.';
        $this->_order = $order->order;
    }

    public function initialise(){
	    $this->_cache->setCache('online_members');

	    if($this->_cache->isCached('staff'))
		    $online = $this->_cache->retrieve('staff');
	    else {
		    $online = DB::getInstance()->query('SELECT id, username, nickname FROM nl2_users WHERE last_online > ' . strtotime('-5 minutes') . ' AND group_id IN (SELECT id FROM nl2_groups WHERE staff = 1)', array())->results();
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
				    'avatar' => $user->getAvatar($staff->id)
			    );

		    $this->_smarty->assign(array(
			    'ONLINE_STAFF' => $this->_language['title'],
			    'ONLINE_STAFF_LIST' => $staff_members
		    ));

	    } else
		    $this->_smarty->assign(array(
			    'ONLINE_STAFF' => $this->_language['title'],
			    'NO_STAFF_ONLINE' => $this->_language['no_online_staff']
		    ));

	    $this->_content = $this->_smarty->fetch('widgets/online_staff.tpl');
    }
}