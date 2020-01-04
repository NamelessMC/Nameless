<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr7
 *
 *  License: MIT
 *
 *  Queries class
 */
class Queries {
	private $_db,
			$_data;
	
	public function __construct() {
		$this->_db = DB::getInstance();
	}
	
	public function getWhere($table, $where) {
		$data = $this->_db->get($table, $where);
		return $data->results();
	}
	
	public function getAll($table, $where = array()) {
		$data = $this->_db->get($table, $where);
		return $data->results();
	}
	
	public function orderAll($table, $order, $sort = null) {
		$data = $this->_db->orderAll($table, $order, $sort);
		return $data->results();
	}
	
	public function orderWhere($table, $where, $order, $sort = null) {
		$data = $this->_db->orderWhere($table, $where, $order, $sort);
		return $data->results();
	}
	
	public function getLike($table, $where, $like){
		$data = $this->_db->like($table, $where, $like);
		return $data->results();
	}
	
	public function update($table, $id, $fields = array()) {
		if(!$this->_db->update($table, $id, $fields)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function create($table, $fields = array()) {
		if(!$this->_db->insert($table, $fields)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function delete($table, $where) {
		if(!$this->_db->delete($table, $where)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function increment($table, $id, $field) {
		if(!$this->_db->increment($table, $id, $field)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function decrement($table, $id, $field) {
		if(!$this->_db->decrement($table, $id, $field)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function createTable($table, $columns, $other) {
		if(!$this->_db->createTable($table, $columns, $other)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function convertQuestionType($type) {
		if($type == "1"){
			return 'Dropdown';
		} else if($type == "2"){
			return 'Text';
		} else if($type == "3"){
			return 'Textarea';
		}
	}
	
	public function getLastId() {
		return $this->_db->lastid();
	}
	
	public function alterTable($table, $column, $attributes){
		if(!$this->_db->alterTable($table, $column, $attributes)) {
			throw new Exception('There was a problem performing that action.');
		}
	}
	
	public function tableExists($table){
		return $this->_db->showTables($table);
	}
	
	public function dbInitialise($charset = 'latin1', $engine = 'InnoDB'){
		$data = $this->_db->showTables('settings');
		if(!empty($data)){
			return '<div class="alert alert-warning">Database already initialised!</div>';
		} else {
			$data = $this->_db->createTable("alerts", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `type` varchar(64) NOT NULL, `url` varchar(255) NOT NULL, `content_short` varchar(128) NOT NULL, `content` varchar(512) NOT NULL, `created` int(11) NOT NULL, `read` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("announcements", " `id` int(11) NOT NULL AUTO_INCREMENT, `content` mediumtext NOT NULL, `can_close` tinyint(1) NOT NULL DEFAULT '0', `type` varchar(16) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("announcements_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `announcement_id` int(11) NOT NULL, `page` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("announcements_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `announcement_id` int(11) NOT NULL, `group_id` int(11) DEFAULT NULL, `user_id` int(11) DEFAULT NULL, `view` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("blocked_users", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `user_blocked_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("custom_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(20) NOT NULL, `title` varchar(30) NOT NULL, `content` mediumtext NOT NULL, `link_location` tinyint(1) NOT NULL DEFAULT '1', `redirect` tinyint(1) NOT NULL DEFAULT '0', `link` varchar(512) DEFAULT NULL, `target` tinyint(1) NOT NULL DEFAULT '0', `icon` varchar(64) DEFAULT NULL, `all_html` tinyint(1) NOT NULL DEFAULT '0', `sitemap` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("custom_pages_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `page_id` int(11) NOT NULL, `group_id` int(11) NOT NULL, `view` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("email_errors", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `content` text NOT NULL, `at` int(11) NOT NULL, `user_id` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("forums", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_title` varchar(150) NOT NULL, `forum_description` varchar(255) DEFAULT NULL, `last_post_date` int(11) DEFAULT NULL, `last_user_posted` int(11) DEFAULT NULL, `last_topic_posted` int(11) DEFAULT NULL, `parent` int(11) NOT NULL DEFAULT '0', `forum_order` int(11) NOT NULL, `news` tinyint(1) NOT NULL DEFAULT '0', `forum_type` varchar(255) NOT NULL DEFAULT 'forum', `redirect_forum` tinyint(1) NOT NULL DEFAULT '0', `redirect_url` varchar(512) DEFAULT NULL, `icon` varchar(256) DEFAULT NULL, `topic_placeholder` mediumtext, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("forums_labels", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) NOT NULL, `html` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("forums_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `group_id` int(11) NOT NULL, `forum_id` int(11) NOT NULL, `view` tinyint(1) NOT NULL DEFAULT '0', `create_topic` tinyint(1) NOT NULL DEFAULT '0', `create_post` tinyint(1) NOT NULL DEFAULT '0', `view_other_topics` tinyint(1) NOT NULL DEFAULT '0', `moderate` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("forums_reactions", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `user_received` int(11) NOT NULL, `user_given` int(11) NOT NULL, `reaction_id` int(11) NOT NULL, `time` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("forums_topic_labels", " `id` int(11) NOT NULL AUTO_INCREMENT, `fids` varchar(128) NOT NULL, `name` varchar(32) NOT NULL, `label` varchar(20) NOT NULL, `gids` varchar(128) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("friends", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `friend_id` int(11) NOT NULL, `notify` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("groups", "`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `group_html` varchar(1024) NOT NULL, `group_html_lg` varchar(1024) NOT NULL, `group_username_css` varchar(256) DEFAULT NULL, `mod_cp` tinyint(1) NOT NULL DEFAULT '0', `admin_cp` tinyint(1) NOT NULL DEFAULT '0', `staff` tinyint(1) NOT NULL DEFAULT '0', `permissions` mediumtext, `default_group` tinyint(1) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("groups_templates", " `id` int(11) NOT NULL AUTO_INCREMENT, `group_id` int(11) NOT NULL, `template_id` int(11) NOT NULL, `can_use_template` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("group_sync", " `id` int(11) NOT NULL AUTO_INCREMENT, `ingame_rank_name` varchar(64) NOT NULL, `website_group_id` int(11) NOT NULL, `primary` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("ip_bans", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(128) NOT NULL, `banned_by` int(11) NOT NULL, `banned_at` int(11) NOT NULL, `reason` varchar(255) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("infractions", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `punished` int(11) NOT NULL, `staff` int(11) NOT NULL, `reason` text NOT NULL, `infraction_date` datetime NOT NULL, `created` int(11) DEFAULT NULL, `acknowledged` tinyint(1) NOT NULL, `revoked` tinyint(1) NOT NULL DEFAULT '0', `revoked_by` int(11) DEFAULT NULL, `revoked_at` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("languages", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `is_default` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("logs", " `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `action` mediumtext NOT NULL, `ip` varchar(64) NOT NULL, `user_id` int(11) NOT NULL, `info` mediumtext, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("mc_servers", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(64) NOT NULL, `query_ip` varchar(64) NOT NULL, `name` varchar(20) NOT NULL, `is_default` tinyint(1) NOT NULL DEFAULT '0', `display` tinyint(1) NOT NULL DEFAULT '1', `pre` tinyint(1) NOT NULL DEFAULT '0', `player_list` tinyint(1) NOT NULL DEFAULT '1', `parent_server` int(11) NOT NULL DEFAULT '0', `bungee` tinyint(1) NOT NULL DEFAULT '0', `port` int(11) DEFAULT NULL, `query_port` int(11) DEFAULT '25565', `banner_background` varchar(32) NOT NULL DEFAULT 'background.png', `show_ip` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("modules", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("online_guests", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(45) NOT NULL, `last_seen` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("page_descriptions", " `id` int(11) NOT NULL AUTO_INCREMENT, `page` varchar(64) NOT NULL, `description` varchar(500) DEFAULT NULL, `tags` text, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("panel_templates", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', `is_default` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("posts", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_id` int(11) NOT NULL, `topic_id` int(11) NOT NULL, `post_creator` int(11) NOT NULL, `post_content` mediumtext NOT NULL, `post_date` datetime DEFAULT NULL, `last_edited` int(11) DEFAULT NULL, `ip_address` varchar(32) DEFAULT NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', `created` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("privacy_terms", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(8) NOT NULL, `value` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("private_messages", " `id` int(11) NOT NULL AUTO_INCREMENT, `author_id` int(11) NOT NULL, `title` varchar(128) NOT NULL, `created` int(11) NOT NULL, `last_reply_user` int(11) NOT NULL, `last_reply_date` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("private_messages_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `pm_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `created` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("private_messages_users", " `id` int(11) NOT NULL AUTO_INCREMENT, `pm_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `read` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("profile_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) NOT NULL, `type` int(11) NOT NULL DEFAULT '1', `public` tinyint(1) NOT NULL DEFAULT '1', `required` tinyint(1) NOT NULL DEFAULT '0', `description` text, `length` int(11) DEFAULT NULL, `forum_posts` tinyint(1) NOT NULL DEFAULT '0', `editable` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("query_errors", " `id` int(11) NOT NULL AUTO_INCREMENT, `date` int(11) NOT NULL, `error` varchar(2048) NOT NULL, `ip` varchar(64) NOT NULL, `port` int(6) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("query_results", " `id` int(11) NOT NULL AUTO_INCREMENT, `server_id` int(11) NOT NULL, `queried_at` int(11) NOT NULL, `players_online` int(11) NOT NULL, `extra` text, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("reactions", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) NOT NULL, `html` varchar(255) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '1', `type` tinyint(1) NOT NULL DEFAULT '2', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("reports", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` tinyint(1) NOT NULL, `reporter_id` int(11) NOT NULL, `reported_id` int(11) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '0', `date_reported` datetime NOT NULL, `date_updated` datetime NOT NULL, `reported` int(11) DEFAULT NULL, `updated` int(11) DEFAULT NULL, `report_reason` mediumtext NOT NULL, `updated_by` int(11) NOT NULL, `reported_post` int(11) DEFAULT NULL, `link` varchar(128) DEFAULT NULL, `reported_mcname` varchar(64) DEFAULT NULL, `reported_uuid` varchar(64) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("reports_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `report_id` int(11) NOT NULL, `commenter_id` int(11) NOT NULL, `comment_date` datetime NOT NULL, `date` int(11) DEFAULT NULL, `comment_content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `value` varchar(2048) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("templates", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', `is_default` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("topics", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_id` int(11) NOT NULL, `topic_title` varchar(150) NOT NULL, `topic_creator` int(11) NOT NULL, `topic_last_user` int(11) NOT NULL, `topic_date` int(11) NOT NULL, `topic_reply_date` int(11) NOT NULL, `topic_views` int(11) NOT NULL DEFAULT '0', `locked` tinyint(1) NOT NULL DEFAULT '0', `sticky` tinyint(1) NOT NULL DEFAULT '0', `label` int(11) DEFAULT NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("topics_following", "`id` int(11) NOT NULL AUTO_INCREMENT, `topic_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `existing_alerts` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users", " `id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `nickname` varchar(20) NOT NULL, `password` varchar(255) NOT NULL, `pass_method` varchar(12) NOT NULL DEFAULT 'default', `uuid` varchar(32) NOT NULL, `joined` int(11) NOT NULL, `group_id` int(11) NOT NULL, `secondary_groups` varchar(64) DEFAULT NULL, `email` varchar(64) NOT NULL, `isbanned` tinyint(1) NOT NULL DEFAULT '0', `lastip` varchar(64) DEFAULT NULL, `active` tinyint(1) NOT NULL DEFAULT '0', `signature` mediumtext, `profile_views` int(11) NOT NULL DEFAULT '0', `reputation` int(11) NOT NULL DEFAULT '0', `reset_code` varchar(60) DEFAULT NULL, `has_avatar` tinyint(1) NOT NULL DEFAULT '0', `gravatar` tinyint(1) NOT NULL DEFAULT '0', `private_profile` tinyint(1) NOT NULL DEFAULT '0', `last_online` int(11) DEFAULT NULL, `user_title` varchar(64) DEFAULT NULL, `theme_id` int(11) DEFAULT NULL, `language_id` int(11) DEFAULT NULL, `warning_points` int(11) NOT NULL DEFAULT '0', `night_mode` tinyint(1) NOT NULL DEFAULT '0', `last_username_update` int(11) NOT NULL DEFAULT '0', `tfa_enabled` tinyint(1) NOT NULL DEFAULT '0', `tfa_type` int(11) NOT NULL DEFAULT '0', `tfa_secret` varchar(256) DEFAULT NULL, `tfa_complete` tinyint(1) NOT NULL DEFAULT '0', `banner` varchar(64) DEFAULT NULL, `timezone` varchar(32) NOT NULL DEFAULT 'Europe/London', `avatar_updated` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users_admin_session", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `hash` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users_ips", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `ip` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users_profile_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `field_id` int(11) NOT NULL, `value` mediumtext, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users_session", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `hash` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("users_username_history", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `changed_to` varchar(64) NOT NULL, `changed_at` int(11) NOT NULL, `original` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("user_profile_wall_posts", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("user_profile_wall_posts_reactions", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `post_id` int(11) NOT NULL, `reaction_id` int(11) NOT NULL, `time` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("user_profile_wall_posts_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("uuid_cache", " `id` int(11) NOT NULL AUTO_INCREMENT, `mcname` varchar(20) NOT NULL, `uuid` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$data = $this->_db->createTable("widgets", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', `pages` text, `order` int(11) NOT NULL DEFAULT '10', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");

			// Success
			return true;
		}
	}
}
