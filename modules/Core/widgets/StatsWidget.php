<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Statistics Widget // By Xemah // https://xemah.me
 */
 
class StatsWidget extends WidgetBase {
	private $_cache, $_smarty, $_language;

    public function __construct($pages = array(), $smarty, $language, $cache){
    	$this->_cache = $cache;
    	$this->_smarty = $smarty;
    	$this->_language = $language;

        parent::__construct($pages);

        // Get order
        $order = DB::getInstance()->query('SELECT `order` FROM nl2_widgets WHERE `name` = ?', array('Statistics'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Statistics';
        $this->_location = 'right';
        $this->_description = 'Displays the basic statistics of your website.';
        $this->_order = $order->order;
    }

    public function initialise(){
        $queries = new Queries();
        $user = new User();
        
        $this->_cache->setCache('statistics');
        
    	if($this->_cache->isCached('statistics')){
    	    
    		$users_query = $this->_cache->retrieve('statistics');
    		$users_registered = $users_query['users_registered'];
    		$latest_member = $users_query['latest_member'];
    		
    	} else {

    		$users_query = $queries->orderAll('users', 'joined', 'DESC');
    		$users_registered = count($users_query);
    		$latest_member = array(
    			'style' => $user->getGroupClass($users_query[0]->id),
    			'profile' => URL::build('/profile/' . Output::getClean($users_query[0]->username)),
    			'avatar' => $user->getAvatar($users_query[0]->id),
    			'username' => Output::getClean($users_query[0]->username),
    			'nickname' => Output::getClean($users_query[0]->nickname),
    			'id' => Output::getClean($users_query[0]->id)
    		);
    		
        	$users_query = null;
    
            $this->_cache->store('statistics', array(
    			'users_registered' => $users_registered,
    			'latest_member' => $latest_member
    		), 120);
    		
    	};
    	
        $forum_module = $queries->getWhere('modules', array('name', '=', 'Forum'));
        $forum_module = $forum_module[0];
        
    	if ($forum_module->enabled) {
    	    $this->_cache->setCache('forum_statistics');
        
    	    if($this->_cache->isCached('forum_statistics')){
    	        
    	        $forum_statistics = $this->_cache->retrieve('forum_statistics');
        		$total_threads = $forum_statistics['total_threads'];
        		$total_posts = $forum_statistics['total_posts'];
        		
    	    } else {
    	        
        		$threads_query = $queries->orderAll('topics', 'topic_date', 'DESC');
        		$posts_query = $queries->orderAll('posts', 'post_date', 'DESC');
        		$total_threads = count($threads_query);
        		$total_posts = count($posts_query);
        		$posts_query = null;
                $threads_query = null;
                
                $this->_cache->store('forum_statistics', array(
        			'total_threads' => $total_threads,
        			'total_posts' => $total_posts,
        		), 120);
        	};
        	
        	$this->_smarty->assign(array(
        	    'FORUM_STATISTICS' => $this->_language['forum_stats'],
                'TOTAL_THREADS' =>  $this->_language['total_threads'],
                'TOTAL_THREADS_VALUE' => $total_threads,
                'TOTAL_POSTS' =>  $this->_language['total_posts'],
                'TOTAL_POSTS_VALUE' => $total_posts,
        	));
    	};
    	
        $this->_smarty->assign(array(
        	'STATISTICS' =>  $this->_language['statistics'],
            'USERS_REGISTERED' =>  $this->_language['users_registered'],
            'USERS_REGISTERED_VALUE' => $users_registered,
            'LATEST_MEMBER' =>  $this->_language['latest_member'],
            'LATEST_MEMBER_VALUE' => $latest_member,
        ));
        
	    $this->_content = $this->_smarty->fetch('widgets/statistics.tpl');
    }
}
