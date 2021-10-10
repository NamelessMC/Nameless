<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Forum module file
 */

class Forum_Module extends Module {

    private $_language, 
            $_forum_language;

	public function __construct($language, $forum_language, $pages) {
		$this->_language = $language;
		$this->_forum_language = $forum_language;

		$name = 'Forum';
		$author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
		$module_version = '2.0.0-pr12';
		$nameless_version = '2.0.0-pr12';

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('Forum', '/panel/forums', 'pages/panel/forums.php');
		$pages->add('Forum', '/panel/forums/labels', 'pages/panel/labels.php');
        $pages->add('Forum', '/panel/forums/settings', 'pages/panel/settings.php');

		$pages->add('Forum', '/forum', 'pages/forum/index.php', 'forum', true);
		$pages->add('Forum', '/forum/error', 'pages/forum/error.php');
		$pages->add('Forum', '/forum/view', 'pages/forum/view_forum.php');
		$pages->add('Forum', '/forum/topic', 'pages/forum/view_topic.php');
		$pages->add('Forum', '/forum/new', 'pages/forum/new_topic.php');
		$pages->add('Forum', '/forum/spam', 'pages/forum/spam.php');
		$pages->add('Forum', '/forum/report', 'pages/forum/report.php');
		$pages->add('Forum', '/forum/get_quotes', 'pages/forum/get_quotes.php');
		$pages->add('Forum', '/forum/delete_post', 'pages/forum/delete_post.php');
		$pages->add('Forum', '/forum/delete', 'pages/forum/delete.php');
		$pages->add('Forum', '/forum/move', 'pages/forum/move.php');
		$pages->add('Forum', '/forum/merge', 'pages/forum/merge.php');
		$pages->add('Forum', '/forum/edit', 'pages/forum/edit.php');
		$pages->add('Forum', '/forum/lock', 'pages/forum/lock.php');
		$pages->add('Forum', '/forum/stick', 'pages/forum/stick.php');
		$pages->add('Forum', '/forum/reactions', 'pages/forum/reactions.php');
		$pages->add('Forum', '/forum/search', 'pages/forum/search.php');

		// UserCP
		$pages->add('Forum', '/user/following_topics', 'pages/user/following_topics.php');

		// Redirects
		$pages->add('Forum', '/forum/view_topic', 'pages/forum/redirect.php');
		$pages->add('Forum', '/forum/view_forum', 'pages/forum/redirect.php');

		// Hooks
		HookHandler::registerEvent('newTopic', $this->_forum_language->get('forum', 'new_topic_hook_info'), array('uuid' => $this->_language->get('admin', 'uuid'), 'username' => $this->_language->get('user', 'username'), 'nickname' => $this->_language->get('user', 'nickname'), 'content' => $this->_language->get('general', 'content'), 'content_full' => $this->_language->get('general', 'full_content'), 'avatar_url' => $this->_language->get('user', 'avatar'), 'title' => $this->_forum_language->get('forum', 'topic_title'), 'url' => $this->_language->get('general', 'url'), 'available_hooks' => $this->_forum_language->get('forum', 'available_hooks')));
	}

	public function onInstall() {
		// Not necessary for Forum
	}

	public function onUninstall() {

	}

	public function onEnable() {
		// No actions necessary
	}

	public function onDisable() {
		// No actions necessary
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
		// AdminCP
		PermissionHandler::registerPermissions('Forum', array(
			'admincp.forums' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_forum_language->get('forum', 'forum')
		));

		// Sitemap
		$pages->registerSitemapMethod(ROOT_PATH . '/modules/Forum/classes/Forum_Sitemap.php', 'Forum_Sitemap::generateSitemap');

		// Add link to navbar
        $cache->setCache('nav_location');
        if(!$cache->isCached('forum_location')){
            $link_location = 1;
            $cache->store('forum_location', 1);
        } else {
            $link_location = $cache->retrieve('forum_location');
        }
        
		$cache->setCache('navbar_order');
		if(!$cache->isCached('forum_order')){
			$forum_order = 2;
			$cache->store('forum_order', 2);
		} else {
			$forum_order = $cache->retrieve('forum_order');
		}

		$cache->setCache('navbar_icons');
		if(!$cache->isCached('forum_icon'))
			$icon = '';
		else
			$icon = $cache->retrieve('forum_icon');

        switch($link_location){
            case 1:
                // Navbar
                $navs[0]->add('forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $forum_order, $icon);
            break;
            case 2:
                // "More" dropdown
                $navs[0]->addItemToDropdown('more_dropdown', 'forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $icon, $forum_order);
            break;
            case 3:
                // Footer
                $navs[0]->add('forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'footer', null, $forum_order, $icon);
            break;
        }

		// Widgets
		// Latest posts
		require_once(ROOT_PATH . '/modules/Forum/widgets/LatestPostsWidget.php');
		$module_pages = $widgets->getPages('Latest Posts');

		$widgets->add(new LatestPostsWidget($module_pages, $this->_forum_language->get('forum', 'latest_posts'), $this->_forum_language->get('forum', 'by'), $smarty, $cache, $user, $this->_language));

		// Front end or back end?
		if(defined('FRONT_END')){
			$queries = new Queries();

			// Global variables if user is logged in
			if($user->isLoggedIn()){
				// Basic user variables
				$topic_count = $queries->getWhere('topics', array('topic_creator', '=', $user->data()->id));
				$topic_count = count($topic_count);
				$post_count = $queries->getWhere('posts', array('post_creator', '=', $user->data()->id));
				$post_count = count($post_count);
				$smarty->assign('LOGGED_IN_USER_FORUM', array(
					'topic_count' => $topic_count,
					'post_count' => $post_count
				));
			}

			if(defined('PAGE') && PAGE == 'user_query'){
				$user_id = $smarty->getTemplateVars('USER_ID');

				if($user_id){
					$topic_count = $queries->getWhere('topics', array('topic_creator', '=', $user_id));
					$smarty->assign('TOPICS', str_replace('{x}', count($topic_count), $this->_forum_language->get('forum', 'x_topics')));
					$post_count = $queries->getWhere('posts', array('post_creator', '=', $user_id));
					$smarty->assign('POSTS', str_replace('{x}', count($post_count), $this->_forum_language->get('forum', 'x_posts')));
				}
			}

		} else if(defined('BACK_END')){
			if($user->hasPermission('admincp.forums')){
				$cache->setCache('panel_sidebar');
				if(!$cache->isCached('forum_order')){
					$order = 12;
					$cache->store('forum_order', 12);
				} else {
					$order = $cache->retrieve('forum_order');
				}
                
				if(!$cache->isCached('forum_settings_icon')){
					$icon = '<i class="nav-icon fas fa-cogs"></i>';
					$cache->store('forum_settings_icon', $icon);
				} else
					$icon = $cache->retrieve('forum_settings_icon');

                $navs[2]->add('forum_divider', mb_strtoupper($this->_forum_language->get('forum', 'forum'), 'UTF-8'), 'divider', 'top', null, $order, '');
                $navs[2]->add('forum_settings', $this->_language->get('admin', 'settings'), URL::build('/panel/forums/settings'), 'top', null, $order + 0.1, $icon);
                
				if(!$cache->isCached('forum_icon')){
					$icon = '<i class="nav-icon fas fa-comments"></i>';
					$cache->store('forum_icon', $icon);
				} else
					$icon = $cache->retrieve('forum_icon');

				$navs[2]->add('forums', $this->_forum_language->get('forum', 'forums'), URL::build('/panel/forums'), 'top', null, $order + 0.2, $icon);

				if(!$cache->isCached('forum_label_icon')){
					$icon = '<i class="nav-icon fas fa-tags"></i>';
					$cache->store('forum_label_icon', $icon);
				} else
					$icon = $cache->retrieve('forum_label_icon');

				$navs[2]->add('forum_labels', $this->_forum_language->get('forum', 'labels'), URL::build('/panel/forums/labels'), 'top', null, $order + 0.3, $icon);
			}

			if(defined('PANEL_PAGE') && PANEL_PAGE == 'dashboard'){
				// Dashboard graph
				$queries = new Queries();

				// Get data for topics and posts
				$latest_topics = $queries->orderWhere('topics', 'topic_date > ' . strtotime("-1 week"), 'topic_date', 'ASC');
				$latest_posts = $queries->orderWhere('posts', 'post_date > "' . date('Y-m-d G:i:s', strtotime("-1 week")) . '"', 'post_date', 'ASC');

				$cache->setCache('dashboard_graph');
				if($cache->isCached('forum_data')){
					$output = $cache->retrieve('forum_data');

				} else {
					$output = array();

					$output['datasets']['topics']['label'] = 'forum_language/forum/topics_title'; // for $forum_language->get('forum', 'topics_title');
					$output['datasets']['topics']['colour'] = '#00931D';
					$output['datasets']['posts']['label'] = 'forum_language/forum/posts_title'; // for $forum_language->get('forum', 'posts_title');
					$output['datasets']['posts']['colour'] = '#ffde0a';

					foreach($latest_topics as $topic){
						$date = date('d M Y', $topic->topic_date);
						$date = '_' . strtotime($date);

						if(isset($output[$date]['topics'])){
							$output[$date]['topics'] = $output[$date]['topics'] + 1;
						} else {
							$output[$date]['topics'] = 1;
						}
					}

					foreach($latest_posts as $post){
						$date = date('d M Y', strtotime($post->post_date));
						$date = '_' . strtotime($date);

						if(isset($output[$date]['posts'])){
							$output[$date]['posts'] = $output[$date]['posts'] + 1;
						} else {
							$output[$date]['posts'] = 1;
						}
					}

					// Fill in missing dates, set topics/posts to 0
					$start = strtotime("-1 week");
					$start = date('d M Y', $start);
					$start = strtotime($start);
					$end = strtotime(date('d M Y'));
					while($start <= $end){
						if(!isset($output['_' . $start]['topics']))
							$output['_' . $start]['topics'] = 0;

						if(!isset($output['_' . $start]['posts']))
							$output['_' . $start]['posts'] = 0;

						$start = strtotime('+1 day', $start);
					}

					// Sort by date
					ksort($output);

					$cache->store('forum_data', $output, 120);

				}

				Core_Module::addDataToDashboardGraph($this->_language->get('admin', 'overview'), $output);

				// Dashboard stats
				require_once(ROOT_PATH . '/modules/Forum/collections/panel/RecentTopics.php');
				CollectionManager::addItemToCollection('dashboard_stats', new RecentTopicsItem($smarty, $this->_forum_language, $cache, count($latest_topics)));

				require_once(ROOT_PATH . '/modules/Forum/collections/panel/RecentPosts.php');
				CollectionManager::addItemToCollection('dashboard_stats', new RecentPostsItem($smarty, $this->_forum_language, $cache, count($latest_posts)));

			}
		}

		require_once(ROOT_PATH . '/modules/Forum/hooks/DeleteUserForumHook.php');
		HookHandler::registerHook('deleteUser', 'DeleteUserForumHook::deleteUser');
	}
}