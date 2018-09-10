<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Forum module file
 */

class Forum_Module extends Module {
	private $_language, $_forum_language;

	public function __construct($language, $forum_language, $pages){
		$this->_language = $language;
		$this->_forum_language = $forum_language;

		$name = 'Forum';
		$author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
		$module_version = '2.0.0-pr5';
		$nameless_version = '2.0.0-pr5';

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('Forum', '/admin/forums', 'pages/admin/forums.php');
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

		// Redirects
		$pages->add('Forum', '/forum/view_topic', 'pages/forum/redirect.php');
		$pages->add('Forum', '/forum/view_forum', 'pages/forum/redirect.php');
	}

	public function onInstall(){
		// Not necessary for Forum
	}

	public function onUninstall(){

	}

	public function onEnable(){
		// No actions necessary
	}

	public function onDisable(){
		// No actions necessary
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets){
		// AdminCP
		PermissionHandler::registerPermissions('Forum', array(
			'admincp.forums' => $this->_language->get('admin', 'admin_cp') . ' &raquo; ' . $this->_forum_language->get('forum', 'forum')
		));

		// Hooks
		HookHandler::registerEvent('newTopic', $this->_forum_language->get('forum', 'new_topic_hook_info'), array('uuid' => $this->_language->get('admin', 'uuid'), 'username' => $this->_language->get('user', 'username'), 'nickname' => $this->_language->get('user', 'nickname'), 'content' => $this->_language->get('general', 'content'), 'content_full' => $this->_language->get('general', 'full_content'), 'avatar_url' => $this->_language->get('user', 'avatar'), 'title' => $this->_forum_language->get('forum', 'topic_title'), 'url' => $this->_language->get('general', 'url')));

		// Sitemap
		$pages->registerSitemapMethod(ROOT_PATH . '/modules/Forum/classes/Forum_Sitemap.php', 'Forum_Sitemap::generateSitemap');

		// Add link to navbar
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

		$navs[0]->add('forum', $this->_forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $forum_order, $icon);

		// Widgets
		// Latest posts
		require_once(ROOT_PATH . '/modules/Forum/widgets/LatestPostsWidget.php');
		$module_pages = $widgets->getPages('Latest Posts');

		$widgets->add(new LatestPostsWidget($module_pages, $this->_forum_language->get('forum', 'latest_posts'), $this->_forum_language->get('forum', 'by'), $smarty, $cache, $user, $this->_language));

		// Front end or back end?
		if(defined('FRONT_END')){
			// Global variables if user is logged in
			if($user->isLoggedIn()){
				$queries = new Queries();

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
		} else if(defined('BACK_END')){

		}
	}
}