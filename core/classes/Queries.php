<?php
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
	
	public function dbInitialise(){
		$data = $this->_db->showTables('settings');
		if(!empty($data)){
			return '<div class="alert alert-warning">Database already initialised!</div>';
		} else {
			$data = $this->_db->createTable("addons", " `id` int(11) NOT NULL AUTO_INCREMENT, `enabled` tinyint(4) NOT NULL DEFAULT '0', `name` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("alerts", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `type` varchar(64) NOT NULL, `url` varchar(255) NOT NULL, `content` varchar(255) NOT NULL, `read` tinyint(1) NOT NULL DEFAULT '0', `created` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("core_modules", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `enabled` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("custom_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(20) NOT NULL, `title` varchar(30) NOT NULL, `content` mediumtext NOT NULL, `link_location` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("forums", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_title` varchar(150) NOT NULL, `forum_description` varchar(255) NOT NULL, `forum_type` varchar(255) NOT NULL DEFAULT `forum`, `last_post_date` datetime DEFAULT NULL, `last_user_posted` int(11) DEFAULT NULL, `last_topic_posted` int(11) DEFAULT NULL, `parent` int(11) NOT NULL DEFAULT '0', `forum_order` int(11) NOT NULL, `news` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("forums_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `group_id` int(11) NOT NULL, `forum_id` int(11) NOT NULL, `view` tinyint(1) NOT NULL DEFAULT '1', `create_topic` tinyint(1) NOT NULL DEFAULT '1', `create_post` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("forums_topic_labels", " `id` int(11) NOT NULL AUTO_INCREMENT, `fids` varchar(32) NOT NULL, `name` varchar(32) NOT NULL, `label` varchar(20) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("friends", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `friend_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("groups", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `buycraft_id` varchar(64) DEFAULT NULL, `group_html` varchar(1024) NOT NULL, `group_html_lg` varchar(1024) NOT NULL, `mod_cp` tinyint(1) NOT NULL DEFAULT '0', `admin_cp` tinyint(1) NOT NULL DEFAULT '0', `staff` tinyint(1) NOT NULL DEFAULT '0', `staff_apps` tinyint(1) NOT NULL DEFAULT '0', `accept_staff_apps` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("infractions", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `punished` int(11) NOT NULL, `staff` int(11) NOT NULL, `reason` text NOT NULL, `infraction_date` datetime NOT NULL, `acknowledged` tinyint(1) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("language", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) NOT NULL, `enabled` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("mc_servers", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(64) NOT NULL, `query_ip` varchar(64) NOT NULL, `name` varchar(20) NOT NULL, `is_default` tinyint(1) NOT NULL DEFAULT '0', `display` tinyint(1) NOT NULL DEFAULT '1', `pre` tinyint(1) NOT NULL DEFAULT '0', `player_list` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("posts", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_id` int(11) NOT NULL, `topic_id` int(11) NOT NULL, `post_creator` int(11) NOT NULL, `post_content` mediumtext NOT NULL, `post_date` datetime NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("private_messages", " `id` int(11) NOT NULL AUTO_INCREMENT, `author_id` int(11) NOT NULL, `title` varchar(128) NOT NULL, `content` mediumtext NOT NULL, `sent_date` int(11) NOT NULL, `updated` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("private_messages_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `pm_id` int(11) NOT NULL, `content` mediumtext NOT NULL, `user_id` int(11) NOT NULL, `created` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("private_messages_users", " `id` int(11) NOT NULL AUTO_INCREMENT, `pm_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `read` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("query_errors", " `id` int(11) NOT NULL AUTO_INCREMENT, `date` int(11) NOT NULL, `error` varchar(2048) NOT NULL, `ip` varchar(64) NOT NULL, `port` int(6) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("reports", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` tinyint(1) NOT NULL, `reporter_id` int(11) NOT NULL, `reported_id` int(11) NOT NULL, `status` tinyint(1) NOT NULL, `date_reported` datetime NOT NULL, `date_updated` datetime NOT NULL, `report_reason` varchar(255) NOT NULL, `updated_by` int(11) NOT NULL, `reported_post` int(11) NOT NULL, `reported_post_topic` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("reports_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `report_id` int(11) NOT NULL, `commenter_id` int(11) NOT NULL, `comment_date` datetime NOT NULL, `comment_content` varchar(255) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("reputation", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_received` int(11) NOT NULL, `post_id` int(11) NOT NULL, `topic_id` int(11) NOT NULL, `user_given` int(11) NOT NULL, `time_given` datetime NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `value` varchar(2048) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("staff_apps_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `aid` int(11) NOT NULL, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("staff_apps_questions", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `name` varchar(16) NOT NULL, `question` varchar(256) NOT NULL, `options` text NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("staff_apps_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, `status` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("templates", " `id` int(11) NOT NULL AUTO_INCREMENT, `enabled` tinyint(1) NOT NULL DEFAULT '0', `name` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("themes", " `id` int(11) NOT NULL AUTO_INCREMENT, `enabled` tinyint(1) NOT NULL DEFAULT '0', `name` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("topics", " `id` int(11) NOT NULL AUTO_INCREMENT, `forum_id` int(11) NOT NULL, `topic_title` varchar(150) NOT NULL, `topic_creator` int(11) NOT NULL, `topic_last_user` int(11) NOT NULL, `topic_date` int(11) NOT NULL, `topic_reply_date` int(11) NOT NULL, `topic_views` int(11) NOT NULL DEFAULT '0', `locked` tinyint(1) NOT NULL DEFAULT '0', `sticky` tinyint(1) NOT NULL DEFAULT '0', `label` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("users", " `id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `password` varchar(255) NOT NULL, `pass_method` varchar(12) NOT NULL DEFAULT 'default', `mcname` varchar(20) NOT NULL, `uuid` varchar(32) NOT NULL, `joined` int(11) NOT NULL, `group_id` int(11) NOT NULL, `email` varchar(64) NOT NULL, `isbanned` tinyint(4) NOT NULL DEFAULT '0', `lastip` varchar(45) NOT NULL, `active` tinyint(4) NOT NULL DEFAULT '0', `signature` varchar(1024) DEFAULT NULL, `reputation` int(11) NOT NULL DEFAULT '0', `reset_code` varchar(60) DEFAULT NULL, `has_avatar` tinyint(4) NOT NULL DEFAULT '0', `gravatar` tinyint(1) NOT NULL DEFAULT '0', `last_online` int(11) DEFAULT NULL, `last_username_update` int(11) DEFAULT '0', `user_title` varchar(64) DEFAULT NULL, `birthday` date DEFAULT NULL, `location` varchar(128) DEFAULT NULL, `display_age` tinyint(1) NOT NULL DEFAULT '1', `tfa_enabled` tinyint(1) NOT NULL DEFAULT '0', `tfa_type` int(11) NOT NULL DEFAULT '0', `tfa_secret` varchar(256) DEFAULT NULL, `tfa_complete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("users_admin_session", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `hash` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("users_session", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `hash` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("user_profile_wall_posts", "  `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("user_profile_wall_posts_likes", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("user_profile_wall_posts_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$data = $this->_db->createTable("uuid_cache", " `id` int(11) NOT NULL AUTO_INCREMENT, `mcname` varchar(20) NOT NULL, `uuid` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
            return true;
		}
	}
}