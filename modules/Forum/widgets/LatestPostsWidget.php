<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Latest Posts Widget
 */
class LatestPostsWidget extends WidgetBase {

    private $_smarty, 
            $_language, 
            $_cache, 
            $_user;

    public function __construct($pages = array(), $latest_posts_language, $by_language, $smarty, $cache, $user, $language){
    	$this->_smarty = $smarty;
    	$this->_cache = $cache;
    	$this->_user = $user;
    	$this->_language = $language;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->query('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', array('Latest Posts'))->first();

        // Set widget variables
        $this->_module = 'Forum';
        $this->_name = 'Latest Posts';
        $this->_location = isset($widget_query->location) ? $widget_query->location : null;
        $this->_description = 'Display latest posts from your forum.';
        $this->_order = isset($widget_query->order) ? $widget_query->order : null;

        $this->_smarty->assign(array(
        	'LATEST_POSTS' => $latest_posts_language,
	        'BY' => $by_language
        ));
    }

    public function initialise() {
	    require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
	    $forum = new Forum();
	    $queries = new Queries();
	    $timeago = new Timeago(TIMEZONE);

		// Get user group IDs
		$user_groups = $this->_user->getAllGroupIds();

	    $this->_cache->setCache('forum_discussions_' . rtrim(implode('-', $user_groups), '-'));
	    if($this->_cache->isCached('discussions')){
		    $template_array = $this->_cache->retrieve('discussions');

	    } else {
		    // Generate latest posts
		    $discussions = $forum->getLatestDiscussions($user_groups, ($this->_user->isLoggedIn() ? $this->_user->data()->id : 0));

		    $n = 0;
		    // Calculate the number of discussions to display (5 max)
		    if(count($discussions) <= 5){
			    $limit = count($discussions);
		    } else {
			    $limit = 5;
		    }

		    $template_array = array();

		    // Generate an array to pass to template
		    while($n < $limit){
			    // Get the name of the forum from the ID
			    $forum_name = $queries->getWhere('forums', array('id', '=', $discussions[$n]['forum_id']));
			    $forum_name = Output::getPurified(htmlspecialchars_decode($forum_name[0]->forum_title));

			    // Get the number of replies
			    $posts = $queries->getWhere('posts', array('topic_id', '=', $discussions[$n]['id']));
			    $posts = count($posts);

			    // Is there a label?
			    if($discussions[$n]['label'] != 0){ // yes
				    // Get label
				    $label = $queries->getWhere('forums_topic_labels', array('id', '=', $discussions[$n]['label']));
				    if(count($label)){
					    $label = $label[0];

					    $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					    if(count($label_html)){
						    $label_html = $label_html[0]->html;
						    $label = str_replace('{x}', Output::getClean($label->name), $label_html);
					    } else $label = '';
				    } else $label = '';
			    } else { // no
				    $label = '';
			    }

			    // Add to array
				$topic_creator = new User($discussions[$n]['topic_creator']);
				$last_reply_user = new User($discussions[$n]['topic_last_user']);
			    $template_array[] = array(
				    'topic_title' => Output::getClean($discussions[$n]['topic_title']),
				    'topic_id' => $discussions[$n]['id'],
				    'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_date']), $this->_language->getTimeLanguage()),
				    'topic_created' => date('d M Y, H:i', $discussions[$n]['topic_date']),
				    'topic_created_username' => $topic_creator->getDisplayname(),
				    'topic_created_mcname' => $topic_creator->getDisplayname(true),
				    'topic_created_style' => $topic_creator->getGroupClass(),
				    'topic_created_user_id' => Output::getClean($discussions[$n]['topic_creator']),
				    'locked' => $discussions[$n]['locked'],
				    'forum_name' => $forum_name,
				    'forum_id' => $discussions[$n]['forum_id'],
				    'views' => $discussions[$n]['topic_views'],
				    'posts' => $posts,
				    'last_reply_avatar' => $last_reply_user->getAvatar(64),
				    'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_reply_date']), $this->_language->getTimeLanguage()),
				    'last_reply' => date('d M Y, H:i', $discussions[$n]['topic_reply_date']),
				    'last_reply_username' => $last_reply_user->getDisplayname(),
				    'last_reply_mcname' => $last_reply_user->getDisplayname(true),
				    'last_reply_style' => $last_reply_user->getGroupClass(),
				    'last_reply_user_id' => Output::getClean($discussions[$n]['topic_last_user']),
				    'label' => $label,
				    'link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title'])),
				    'forum_link' => URL::build('/forum/forum/' . $discussions[$n]['forum_id']),
				    'author_link' => $topic_creator->getProfileURL(),
				    'last_reply_profile_link' => $last_reply_user->getProfileURL(),
				    'last_reply_link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title']), 'pid=' . $discussions[$n]['last_post_id'])
			    );

			    $n++;
		    }

		    $this->_cache->store('discussions', $template_array, 60);
	    }

	    // Generate HTML code for widget
	    $this->_smarty->assign('LATEST_POSTS_ARRAY', $template_array);

	    $this->_content = $this->_smarty->fetch('widgets/forum/latest_posts.tpl');
    }
}