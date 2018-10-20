<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Recent reports dashboard collection item
 */
class RecentReportsItem extends CollectionItemBase {
	private $_smarty, $_language, $_cache, $_user;

	public function __construct($smarty, $language, $cache, $user){
		$cache->setCache('dashboard_main_items_collection');
		if($cache->isCached('recent_reports')){
			$from_cache = $cache->retrieve('recent_reports');
			if(isset($from_cache['order']))
				$order = $from_cache['order'];
			else
				$order = 3;

			if(isset($from_cache['enabled']))
				$enabled = $from_cache['enabled'];
			else
				$enabled = 1;
		} else {
			$order = 3;
			$enabled = 1;
		}

		parent::__construct($order, $enabled);

		$this->_smarty = $smarty;
		$this->_language = $language;
		$this->_cache = $cache;
		$this->_user = $user;
	}

	public function getContent(){
		// Get recent reports
		$timeago = new Timeago(TIMEZONE);

		$this->_cache->setCache('dashboard_main_items_collection');

		if($this->_cache->isCached('recent_reports_data')){
			$data = $this->_cache->retrieve('recent_reports_data');

		} else {
			$queries = new Queries();
			$query = $queries->orderWhere('reports', 'status = 0', 'date_reported', 'DESC');
			$data = array();

			if(count($query)){
				$users = array();
				$i = 0;

				foreach($query as $item){
					if(array_key_exists($item->reporter_id, $users)){
						$reporter_user = $users[$item->reporter_id];

					} else {
						$user_query = $queries->getWhere('users', array('id', '=', $item->reporter_id));
						if(!count($user_query))
							continue;
						$reporter_user = $user_query[0];
						$users[$item->reporter_id] = $reporter_user;

					}

					if(array_key_exists($item->reported_id, $users)){
						$reported_user = $users[$item->reported_id];

					} else {
						$user_query = $queries->getWhere('users', array('id', '=', $item->reported_id));
						if(!count($user_query))
							continue;
						$reported_user = $user_query[0];
						$users[$item->reported_id] = $reported_user;

					}

					$data[] = array(
						'url' => URL::build('/panel/users/reports/', 'id=' . Output::getClean($item->id)),
						'reporter_username' => Output::getClean($reporter_user->username),
						'reporter_nickname' => Output::getClean($reporter_user->nickname),
						'reporter_style' => $this->_user->getGroupClass($reporter_user->id),
						'reporter_avatar' => $this->_user->getAvatar($reporter_user->id),
						'reporter_uuid' => Output::getClean($reporter_user->uuid),
						'reporter_profile' => URL::build('/panel/user/' . Output::getClean($reporter_user->id) . '-' . Output::getClean($reporter_user->username)),
						'reported_username' => Output::getClean($reported_user->username),
						'reported_nickname' => Output::getClean($reported_user->nickname),
						'reported_style' => $this->_user->getGroupClass($reported_user->id),
						'reported_avatar' => $this->_user->getAvatar($reported_user->id),
						'reported_uuid' => Output::getClean($reported_user->uuid),
						'reported_profile' => URL::build('/panel/user/' . Output::getClean($reported_user->id) . '-' . Output::getClean($reported_user->username)),
						'time' => $timeago->inWords($item->date_reported, $this->_language->getTimeLanguage()),
						'time_full' => date('d M Y, H:i', strtotime($item->date_reported)),
						'type' => $item->type,
						'reason' => Output::getPurified($item->report_reason),
						'link' => Output::getClean($item->link),
						'ig_reported_mcname' => ($item->reported_mcname ? Output::getClean($item->reported_mcname) : ''),
						'ig_reported_uuid' => ($item->reported_uuid ? Output::getClean($item->reported_uuid) : '')
					);

					if(++$i == 5)
						break;
				}
			}

			$this->_cache->store('recent_reports_data', $data, 60);
		}

		$this->_smarty->assign(array(
			'RECENT_REPORTS' => $this->_language->get('moderator', 'recent_reports'),
			'REPORTS' => $data,
			'NO_REPORTS' => $this->_language->get('moderator', 'no_open_reports'),
			'CREATED' => $this->_language->get('moderator', 'created'),
			'REPORTED_BY' => $this->_language->get('moderator', 'reported_by'),
			'REASON' => $this->_language->get('moderator', 'reason:'),
			'WEBSITE' => $this->_language->get('moderator', 'website'),
			'INGAME' => $this->_language->get('moderator', 'ingame'),
			'VIEW' => $this->_language->get('general', 'view')
		));

		return $this->_smarty->fetch('collections/dashboard_items/recent_reports.tpl');
	}

	public function getWidth(){
		return 0.33; // 1/3 width
	}
}