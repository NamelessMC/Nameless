<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Alert class
 */ 
class Alert {
	
	// Creates an alert for the specified user
	// Params:  $user_id (int) 			- contains the ID of the user who we are creating the alert for
	// 			$type (string) 			- contains the alert type, eg 'tag' for user tagging
	//			$text_short (string)	- contains the alert text in short form for the dropdown
	//			$text (string)			- contains full information about the alert
	//			$link (string,optional) - contains link to view the alert, defaults to #
	public static function create($user_id, $type, $text_short, $text, $link = '#'){	
		$db = DB::getInstance();

		$language = $db->query('SELECT nl2_languages.name AS `name` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id = ?', array($user_id));

		if($language->count()){
			$language_name = $language->first()->name;
			$language = new Language($text_short['path'], $language_name);

			if(!$db->insert('alerts', array(
				'user_id' => $user_id,
				'type' => $type,
				'url' => $link,
				'content_short' => str_replace((isset($text_short['replace']) ? $text_short['replace'] : ''), (isset($text_short['replace_with']) ? $text_short['replace_with'] : ''), $language->get($text_short['file'], $text_short['term'])),
				'content' => str_replace((isset($text['replace']) ? $text['replace'] : ''), (isset($text['replace_with']) ? $text['replace_with'] : ''), $language->get($text['file'], $text['term'])),
				'created' => date('U')
			))){
				throw new Exception('There was a problem creating an alert.');
			}
		}
	}
	
	// Get user alerts
	// Params: 	$user_id (int)		     - contains the ID of the user who we are getting alerts for
	//			$all (boolean, optional) - do we want to get all alerts (including read), or not; defaults to false)
	public static function getAlerts($user_id, $all = false){
		$db = DB::getInstance();
		
		if($all == true){
			return $db->get('alerts', array('user_id', '=', $user_id))->results();
		} else {
			$alerts = $db->get('alerts', array('user_id', '=', $user_id))->results();
			$unread = array();
			foreach($alerts as $alert){
				if($alert->read == 0){
					$unread[] = $alert;
				}
			}
			return $unread;
		}
	}
	
	// Get user unread messages
	// Params: 	$user_id (int)		     - contains the ID of the user who we are getting messages for
	//			$all (boolean, optional) - do we want to get all alerts (including read), or not; defaults to false)
	public static function getPMs($user_id, $all = false){
		$db = DB::getInstance();
		
		if($all == true){
			$pms_access = $db->get('private_messages_users', array('user_id', '=', $user_id))->results();
			$pms = array();
			
			foreach($pms_access as $pm){
				// Get actual PM information
				$pm_full = $db->get('private_messages', array('id', '=', $pm->pm_id))->results();
				
				if(!count($pm_full)) continue;
				else $pm_full = $pm_full[0];
				
				$pms[] = array(
					'id' => $pm_full->id,
					'title' => Output::getClean($pm_full->title),
					'created' => $pm_full->created,
					'author_id' => $pm_full->author_id,
					'last_reply_user' => $pm_full->last_reply_user,
					'last_reply_date' => $pm_full->last_reply_date
				);
			}
			
			return $pms;
			
		} else {
			$pms = $db->get('private_messages_users', array('user_id', '=', $user_id))->results();
			$unread = array();
			
			foreach($pms as $pm){
				if($pm->read == 0){
					$pm_full = $db->get('private_messages', array('id', '=', $pm->pm_id))->results();
					
					if(!count($pm_full)) continue;
					else $pm_full = $pm_full[0];
					
					$unread[] = array(
						'id' => $pm_full->id,
						'title' => Output::getClean($pm_full->title),
						'created' => $pm_full->created,
						'author_id' => $pm_full->author_id,
						'last_reply_user' => $pm_full->last_reply_user,
						'last_reply_date' => $pm_full->last_reply_date
					);
				}
			}
			return $unread;
		}
	}

}