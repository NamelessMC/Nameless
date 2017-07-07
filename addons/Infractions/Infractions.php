<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 */
 
// Infractions class
class Infractions {
	private $_db,
			$_data,
			$_language,
			$_prefix;
	
	// Connect to database
	public function __construct($inf_db, $language) {
		$this->_db = DB_Custom::getInstance($inf_db['address'], $inf_db['name'], $inf_db['username'], $inf_db['password']);
		$this->_prefix = $inf_db['prefix'];
		$this->_language = $language;
	}
	
	// Receive a list of all infractions for BungeeAdminTools, either for a single user or for all users
	// Params: $uuid (string), UUID of a user. If null, will list all infractions
	public function bat_getAllInfractions($uuid = null) {
		if($uuid !== null){
			$field = "uuid";
			$symbol = "=";
			$equals = str_replace('-', '', $uuid);
		} else {
			$field = "uuid";
			$symbol = "<>";
			$equals = "0";
		}
		$bans = $this->_db->get($this->_prefix . 'ban', array($field, $symbol, $equals))->results();
		$kicks = $this->_db->get($this->_prefix . 'kick', array($field, $symbol, $equals))->results();
		$mutes = $this->_db->get($this->_prefix . 'mute', array($field, $symbol, $equals))->results();
		
		$results = array();
		$i = 0;
		
		foreach($bans as $ban){
			$results[$i]["id"] = $ban->ban_id;
			$results[$i]["uuid"] = $ban->UUID;
			$results[$i]["staff"] = htmlspecialchars($ban->ban_staff);
			$results[$i]["issued"] = strtotime($ban->ban_begin);
			$results[$i]["issued_human"] = date("jS M Y, H:i", strtotime($ban->ban_begin));
			if($ban->ban_reason !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->ban_reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			if($ban->ban_unbandate !== null){
				$results[$i]["unbanned"] = "true";
				$results[$i]["unbanned_by"] = htmlspecialchars($ban->ban_unbanstaff);
				$results[$i]["unbanned_date"] = htmlspecialchars($ban->ban_unbandate);
				if($ban->ban_unbanreason !== "noreason"){
					$results[$i]["unbanned_reason"] = htmlspecialchars($ban->ban_unbanreason);
				}
			}
			if($ban->ban_end !== null){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['temp_ban'] . '</span>';
				if($ban->ban_state == 0){
					$results[$i]["expires"] = strtotime($ban->ban_end);
					$results[$i]["expires_human"] = '<span class="label label-success" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($ban->ban_end)), $this->_language['expired_x']) . '">' . $this->_language['expired'] . '</span>';
				} else {
					$results[$i]["expires"] = strtotime($ban->ban_end);
					$results[$i]["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($ban->ban_end)), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
				}
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['ban'] . '</span>';
				if($ban->ban_unbandate !== null){
					$results[$i]["expires_human"] = '<span class="label label-success">' . $this->_language['revoked'] .'</span>';
				} else {
					$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
				}
			}
			$i++;
		}
		
		foreach($kicks as $kick){
			$results[$i]["id"] = $kick->kick_id;
			$results[$i]["uuid"] = $kick->UUID;
			$results[$i]["staff"] = htmlspecialchars($kick->kick_staff);
			$results[$i]["issued"] = strtotime($kick->kick_date);
			$results[$i]["issued_human"] = date("jS M Y, H:i", strtotime($kick->kick_date));
			$results[$i]["reason"] = htmlspecialchars($kick->kick_reason);
			$results[$i]["type"] = "kick";
			$results[$i]["type_human"] = '<span class="label label-primary">' . $this->_language['kick'] . '</span>';
			$results[$i]["expires_human"] = '';
			$i++;
		}
		
		foreach($mutes as $mute){
			$results[$i]["id"] = $mute->mute_id;
			$results[$i]["uuid"] = $mute->UUID;
			$results[$i]["staff"] = htmlspecialchars($mute->mute_staff);
			$results[$i]["issued"] = strtotime($mute->mute_begin);
			$results[$i]["issued_human"] = date("jS M Y, H:i", strtotime($mute->mute_begin));
			if($mute->mute_reason !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->mute_reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			$results[$i]["type"] = "mute";
			$results[$i]["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';
			if($mute->mute_unmutedate !== null){
				$results[$i]["unmuted"] = "true";
				$results[$i]["unmuted_by"] = htmlspecialchars($mute->mute_unmutestaff);
				$results[$i]["unmuted_date"] = htmlspecialchars($mute->mute_unmutedate);
				if($mute->mute_unmutereason !== "noreason"){
					$results[$i]["unmuted_reason"] = htmlspecialchars($mute->mute_unmutereason);
				}
			}
			if($mute->mute_end !== null){
				if($mute->mute_state == 0){
					$results[$i]["expires"] = strtotime($mute->mute_end);
					$results[$i]["expires_human"] = '<span class="label label-success" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($mute->mute_end)), $this->_language['expired_x']) . '">' . $this->_language['expired'] . '</span>';
				} else {
					$results[$i]["expires"] = strtotime($mute->mute_end);
					$results[$i]["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($mute->mute_end)), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
				}
			} else {
				if($mute->mute_unmutedate !== null){
					$results[$i]["expires_human"] = '<span class="label label-success">' . $this->_language['revoked'] .'</span>';
				} else {
					$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
				}
			}
			$i++;
		}
		
		// Sort by date
		function date_compare($a, $b)
		{
			$t1 = $a['issued'];
			$t2 = $b['issued'];
			return $t2 - $t1;
		}    
		usort($results, 'date_compare');
		return $results;
	}
	
	// Receive an object containing infraction information for a specified infraction ID and type (BungeeAdminTools)
	// Params: $type (string), either ban, kick or mute; $id (int), ID of infraction
	public function bat_getInfraction($type, $id) {
		if($type === "ban" || $type === "temp_ban"){
			$result = $this->_db->get($this->_prefix . 'ban', array("ban_id", "=", $id))->results();
			return $result;
		} else if($type === "kick"){
			$result = $this->_db->get($this->_prefix . 'kick', array("kick_id", "=", $id))->results();
			return $result;
		} else if($type === "mute"){
			$result = $this->_db->get($this->_prefix . 'mute', array("mute_id", "=", $id))->results();
			return $result;
		}
		return false;
	}
	
	// Get a username from a UUID
	// Params: $uuid (string) - UUID of user
	public function bat_getUsernameFromUUID($uuid){
		// Query database
		$results = $this->_db->get($this->_prefix . 'players', array('uuid', '=', $uuid))->results();
		return $results;
	}

	// Receive a list of all infractions for Ban Management, either for a single user or for all users
	// Params: $uuid (string), username (not UUID) of a user. If null, will list all infractions
	public function bm_getAllInfractions($uuid = null) {
		// First, we need to get the player ID (if specified)
		if($uuid !== null){
			// Get BM player ID
			$id = $this->_db->get($this->_prefix . 'players', array('name', '=', $uuid))->results();
			if(count($id)){
				$uuid = $id[0]->id;
			} else
				return array();
			
			$field = "player_id";
			$symbol = "=";
			$equals = $uuid;
		} else {
			$field = "player_id";
			$symbol = "<>";
			$equals = "0";
		}
		$bans = $this->_db->get($this->_prefix . 'player_bans', array($field, $symbol, $equals))->results();
		$kicks = $this->_db->get($this->_prefix . 'player_kicks', array($field, $symbol, $equals))->results();
		$mutes = $this->_db->get($this->_prefix . 'player_mutes', array($field, $symbol, $equals))->results();
		$warnings = $this->_db->get($this->_prefix . 'player_warnings', array($field, $symbol, $equals))->results();
		
		$results = array();
		$i = 0;
		
		// Bans - first, current bans
		foreach($bans as $ban){
			$results[$i]["id"] = $ban->id;
			$results[$i]["uuid"] = bin2hex($ban->player_id);
			
			// Console or a player?
			if(bin2hex($ban->actor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get($this->_prefix . 'players', array('id', '=', $ban->actor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $ban->created;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $ban->created);
			
			// Is a reason set?
			if($ban->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			// Is it a temp-ban?
			if($ban->expires != 0){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['temp_ban'] . "</span>";
				$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $ban->expires), $this->_language['expires_x']) . "\">" . $this->_language['active'] . "</span>";
				$results[$i]["expires"] = $ban->expires;
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['ban'] . "</span>";
				$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] . "</span>";
			}
			$i++;
		}
		// Bans - next, previous bans
		$bans = $this->_db->get($this->_prefix . 'player_ban_records', array($field, $symbol, $equals))->results();
		foreach($bans as $ban){
			$results[$i]["id"] = $ban->id;
			$results[$i]["uuid"] = bin2hex($ban->player_id);
			$results[$i]['past'] = true;
			
			// Console or a player?
			if(bin2hex($ban->pastActor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get($this->_prefix . 'players', array('id', '=', $ban->pastActor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $ban->pastCreated;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $ban->pastCreated);
			
			// Is a reason set?
			if($ban->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			// Was it a temp-ban?
			if($ban->expired != 0){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['temp_ban'] . "</span>";
				$results[$i]["expires"] = strtotime($ban->expired);
				$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $ban->expired), $this->_language['expired_x']) . "\">" . $this->_language['expired'] . "</span>";
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['ban'] . "</span>";
				$results[$i]["expires_human"] = "<span class=\"label label-success\">" . $this->_language['revoked'] . "</span>";
			}
			$i++;
		}
		
		// Kicks
		foreach($kicks as $kick){
			$results[$i]["id"] = $kick->id;
			$results[$i]["uuid"] = bin2hex($kick->player_id);
			
			// Console or a player?
			if(bin2hex($kick->actor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get($this->_prefix . 'players', array('id', '=', $kick->actor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $kick->created;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $kick->created);
			$results[$i]["reason"] = htmlspecialchars($kick->reason);
			$results[$i]["type"] = "kick";
			$results[$i]["type_human"] = "<span class=\"label label-primary\">" . $this->_language['kick'] . "</span>";
			$results[$i]["expires_human"] = '';
			$i++;
		}
		
		// Mutes - first, current mutes
		foreach($mutes as $mute){
			$results[$i]["id"] = $mute->id;
			$results[$i]["uuid"] = bin2hex($mute->player_id);
			
			// Console or a player?
			if(bin2hex($mute->actor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get($this->_prefix . 'players', array('id', '=', $mute->actor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $mute->created;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $mute->created);
			
			// Is a reason set?
			if($mute->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			$results[$i]["type"] = "mute";
			$results[$i]["type_human"] = "<span class=\"label label-warning\">" . $this->_language['mute'] . "</span>";
			
			// Is it a temp mute?
			if($mute->expires != 0){
				$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $mute->expires), $this->_language['expires_x']) . "\">" . $this->_language['active'] . "</span>";
				$results[$i]["expires"] = $mute->expires;
			} else {
				$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] . "</span>";
			}

			$i++;
		}
		
		// Mutes - next, previous mutes
		$mutes = $this->_db->get($this->_prefix . 'player_mute_records', array($field, $symbol, $equals))->results();
		foreach($mutes as $mute){
			$results[$i]["id"] = $mute->id;
			$results[$i]["uuid"] = bin2hex($mute->player_id);
			$results[$i]['past'] = true;
			
			// Console or a player?
			if(bin2hex($mute->pastActor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get($this->_prefix . 'players', array('id', '=', $mute->pastActor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $mute->pastCreated;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $mute->pastCreated);
			
			// Is a reason set?
			if($mute->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			$results[$i]["type"] = "mute";
			$results[$i]["type_human"] = "<span class=\"label label-warning\">" . $this->_language['mute'] . "</span>";
			
			// Was it a temp-ban?
			if($mute->expired != 0){
				$results[$i]["expires"] = strtotime($mute->expired);
				$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $mute->expired), $this->_language['expired_x']) . "\">" . $this->_language['expired'] . "</span>";
			} else {
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['mute'] . "</span>";
				$results[$i]["expires_human"] = "<span class=\"label label-success\">" . $this->_language['revoked'] . "</span>";
			}
			$i++;
		}
		
		// Warnings
		foreach($warnings as $warning){
			$results[$i]["id"] = $warning->id;
			$results[$i]["uuid"] = bin2hex($warning->player_id);
			
			// Console or a player?
			if(bin2hex($warning->actor_id) == '2b28d0bed7484b93968e8f4ab16999b3'){
				$results[$i]["staff"] = 'Console';
			} else {
				// We need to get the player's username first
				$username = $this->_db->get('bm_players', array('id', '=', $warning->actor_id))->results();
				$username = htmlspecialchars($username[0]->name);
				$results[$i]["staff"] = $username;
			}
			
			$results[$i]["issued"] = $warning->created;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $warning->created);
			$results[$i]["reason"] = htmlspecialchars($warning->reason);
			$results[$i]["type"] = "warning";
			$results[$i]["type_human"] = "<span class=\"label label-info\">" . $this->_language['warning'] . "</span>";
			$results[$i]["expires_human"] = '';
			$i++;
		}

		// Order by date, most recent first
		function date_compare($a, $b){
			$t1 = $a['issued'];
			$t2 = $b['issued'];
			return $t2 - $t1;
		}    
		usort($results, 'date_compare');
		return $results;
	}
	
	// Receive an object containing infraction information for a specified infraction ID and type (Ban Management)
	// Params: $type (string), either ban, kick or mute; $id (int), ID of infraction
	public function bm_getInfraction($type, $id, $past = false) {
		if($type === "ban" || $type === "temp_ban"){
			if(!$past) $result = $this->_db->get($this->_prefix . 'player_bans', array("id", "=", $id))->results();
			else $result = $this->_db->get($this->_prefix . 'player_ban_records', array("id", "=", $id))->results();
			return $result;
		} else if($type === "kick"){
			$result = $this->_db->get($this->_prefix . 'player_kicks', array("id", "=", $id))->results();
			return $result;
		} else if($type === "mute"){
			if(!$past) $result = $this->_db->get($this->_prefix . 'player_mutes', array("id", "=", $id))->results();
			else $result = $this->_db->get($this->_prefix . 'player_mute_records', array("id", "=", $id))->results();
			return $result;
		} else if($type === "warning"){
			$result = $this->_db->get($this->_prefix . 'player_warnings', array("id", "=", $id))->results();
			return $result;
		}
		return false;
	}
	
	// Receive the username from an ID (Ban Management)
	// Params: $id (string (binary)), player_id of user to lookup
	public function bm_getUsernameFromID($id) {
		$result = $this->_db->get($this->_prefix . 'players', array('id', '=', $id))->results();
		if(count($result)){
			return htmlspecialchars($result[0]->name);
		}
		return 'Unknown';
	}
	
	// Receive a list of all infractions for LiteBans, either for a single user or for all users
	// Params: $uuid (string), UUID of a user. If null, will list all infractions
	public function lb_getAllInfractions($uuid = null) {
		if($uuid !== null){
			$field = "uuid";
			$symbol = "=";
			$equals = $uuid;
		} else {
			$field = "uuid";
			$symbol = "<>";
			$equals = "0";
		}

		$bans = $this->_db->get($this->_prefix . 'bans', array($field, $symbol, $equals))->results();
		$kicks = $this->_db->get($this->_prefix . 'kicks', array($field, $symbol, $equals))->results();
		$mutes = $this->_db->get($this->_prefix . 'mutes', array($field, $symbol, $equals))->results();
		$warnings = $this->_db->get($this->_prefix . 'warnings', array($field, $symbol, $equals))->results();
		
		$results = array();
		$i = 0;

		// Bans
		foreach($bans as $ban){
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($ban->uuid)))->results();
			
			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = $username[0]->name; else $username = 'Unknown';

			$results[$i]["username"] = htmlspecialchars($username);
			$results[$i]["id"] = $ban->id;
			$results[$i]["staff"] = htmlspecialchars($ban->banned_by_name);
			
			$results[$i]["issued"] = $ban->time / 1000;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $ban->time / 1000);
			
			// Is a reason set?
			if($ban->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			// Is it a temp-ban?
			if($ban->until != -1){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['temp_ban'] . "</span>";
				if($ban->active == 0x01){
					$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $ban->until / 1000), $this->_language['expires_x']) . "\">" . $this->_language['active'] . "</span>";
					$results[$i]["expires"] = $ban->until / 1000;
				} else {
					$results[$i]["expires_human"] = "<span class=\"label label-info\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $ban->until / 1000), $this->_language['expired_x']) . "\">" . $this->_language['expired'] . "</span>";
					$results[$i]["expires"] = $ban->until / 1000;
				}
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = "<span class=\"label label-danger\">" . $this->_language['ban'] . "</span>";

				if($ban->active == null){
					// Active
					$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] ."</span>";
				} else {
					if($ban->active == 0x01){
						$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] ."</span>";
					} else {
						$results[$i]["expires_human"] = "<span class=\"label label-success\">" . $this->_language['revoked'] ."</span>";	
					}
				}
			}
			$i++;
		}
		
		// Mutes
		foreach($mutes as $mute){
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($mute->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = $username[0]->name; else $username = 'Unknown';
			
			$results[$i]["username"] = htmlspecialchars($username);
			$results[$i]["id"] = $mute->id;
			$results[$i]["staff"] = htmlspecialchars($mute->banned_by_name);
			
			$results[$i]["issued"] = $mute->time / 1000;
			$results[$i]["issued_human"] = date("jS M Y, H:i", $mute->time / 1000);
			
			// Is a reason set?
			if($mute->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			$results[$i]["type"] = "mute";
			$results[$i]["type_human"] = "<span class=\"label label-warning\">" . $this->_language['mute'] . "</span>";
			
			// Is it a temp-mute?
			if($mute->until != -1){
				if($mute->active == 0x01){
					$results[$i]["expires_human"] = "<span class=\"label label-success\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $mute->until / 1000), $this->_language['expires_x']) . "\">" . $this->_language['active'] . "</span>";
					$results[$i]["expires"] = $mute->until / 1000;
				} else {
					$results[$i]["expires_human"] = "<span class=\"label label-info\" rel=\"tooltip\" data-trigger=\"hover\" data-original-title=\"" . str_replace('{x}', date("jS M Y", $mute->until / 1000), $this->_language['expired_x']) . "\">" . $this->_language['expired'] . "</span>";
					$results[$i]["expires"] = $mute->until / 1000;
				}
			} else {
				if($mute->active == null){
					// Active
					$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] ."</span>";
				} else {
					if($mute->active == 0x01){
						$results[$i]["expires_human"] = "<span class=\"label label-danger\">" . $this->_language['permanent'] ."</span>";
					} else {
						$results[$i]["expires_human"] = "<span class=\"label label-success\">" . $this->_language['revoked'] ."</span>";	
					}
				}
			}
			$i++;
		}
		
		// Warnings
		foreach($warnings as $warning){
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($warning->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = $username[0]->name; else $username = 'Unknown';
			
			$results[$i]["username"] = htmlspecialchars($username);
			$results[$i]["id"] = $warning->id;
			$results[$i]["staff"] = htmlspecialchars($warning->banned_by_name);
			
			$results[$i]["issued"] = $warning->time / 1000;
			$results[$i]["issued_human"] = date("jS M Y, H:i:s", $warning->time / 1000);
			
			// Is a reason set?
			if($warning->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($warning->reason);
			} else {
				$results[$i]["reason"] = "-";
			}


			$results[$i]["type"] = "warning";
			$results[$i]["type_human"] = "<span class=\"label label-info\">" . $this->_language['warning'] . "</span>";
			$results[$i]["expires_human"] = '';

			$i++;
		}
		
		// Kicks
		foreach($kicks as $kick){
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($kick->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = $username[0]->name; else $username = 'Unknown';
			
			$results[$i]["username"] = htmlspecialchars($username);
			$results[$i]["id"] = $kick->id;
			$results[$i]["staff"] = htmlspecialchars($kick->banned_by_name);
			
			$results[$i]["issued"] = $kick->time / 1000;
			$results[$i]["issued_human"] = date("jS M Y, H:i:s", $kick->time / 1000);
			
			// Is a reason set?
			if($kick->reason !== null){
				$results[$i]["reason"] = htmlspecialchars($kick->reason);
			} else {
				$results[$i]["reason"] = "-";
			}

			$results[$i]["type"] = "kick";
			$results[$i]["type_human"] = "<span class=\"label label-default\">" . $this->_language['kick'] . "</span>";
			$results[$i]["expires_human"] = '';

			$i++;
		}

		// Order by date, most recent first
		function date_compare($a, $b){
			$t1 = $a['issued'];
			$t2 = $b['issued'];
			return $t2 - $t1;
		}    
		usort($results, 'date_compare');

		return $results;
	}
	
	// Receive an object containing infraction information for a specified infraction ID and type (LiteBans)
	// Params: $type (string), either ban, kick or mute; $id (int), ID of infraction
	public function lb_getInfraction($type, $id) {
		if($type === "ban" || $type === "temp_ban"){
			$results = $this->_db->get($this->_prefix . 'bans', array("id", "=", $id))->results();
			
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($results[0]->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = htmlspecialchars($username[0]->name); else $username = 'Unknown';
			
			return array($results[0], $username);
		} else if($type === "mute"){
			$results = $this->_db->get($this->_prefix . 'mutes', array("id", "=", $id))->results();
			
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($results[0]->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = htmlspecialchars($username[0]->name); else $username = 'Unknown';
			
			return array($results[0], $username);
		} else if($type === "warning"){
			$results = $this->_db->get($this->_prefix . 'warnings', array("id", "=", $id))->results();
			
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($results[0]->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = htmlspecialchars($username[0]->name); else $username = 'Unknown';
			
			return array($results[0], $username);
		} else if($type === "kick"){
			$results = $this->_db->get($this->_prefix . 'kicks', array("id", "=", $id))->results();
			
			$username = $this->_db->get($this->_prefix . 'history', array('uuid', '=', htmlspecialchars($results[0]->uuid)))->results();

			if(count($username) > 1){
				// get most recent name
				usort($username, function($a, $b) {
					return strtotime($b->date) - strtotime($a->date);
				});
			}
			if(count($username)) $username = htmlspecialchars($username[0]->name); else $username = 'Unknown';
			
			return array($results[0], $username);
		}
		return false;
	}
	
	// Receive a list of all infractions for Ban and Mute Plugin, either for a single user or for all users
	// Params: $uuid (string), UUID of a user. If null, will list all infractions
	public function bam_getAllInfractions($uuid = null) {
		if($uuid !== null){
			$field = "UUID";
			$symbol = "=";
			$equals = $uuid;
		} else {
			$field = "UUID";
			$symbol = "<>";
			$equals = "0";
		}
		$bans = $this->_db->get('history', array($field, $symbol, $equals))->results();
		$mutes = $this->_db->get('mutes', array($field, $symbol, $equals))->results();
		
		$results = array();
		$i = 0;
		
		foreach($bans as $ban){
			$results[$i]["id"] = htmlspecialchars($ban->name) . ';' . strtotime($ban->time);
			$results[$i]["uuid"] = str_replace('-', '', $ban->UUID);
			$results[$i]["staff"] = htmlspecialchars($ban->banner);
			$results[$i]["issued"] = strtotime($ban->time);
			$results[$i]["issued_human"] = date("jS M Y, H:i", strtotime($ban->time));
			if($ban->cause !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->cause);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			if($ban->dauer !== 'Permanent'){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['temp_ban'] . '</span>';
				
				// Todo: determine expire time
				$results[$i]["expires"] = '';
				$results[$i]["expires_human"] = '';
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['ban'] . '</span>';
				$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
				
				// Todo: determine if revoked or not
			}

			$i++;
		}
		
		foreach($mutes as $mute){
			$results[$i]["id"] = htmlspecialchars($mute->name) . ';' . strtotime($mute->time);
			$results[$i]["uuid"] = str_replace('-', '', $mute->UUID);
			$results[$i]["staff"] = htmlspecialchars($mute->banner);
			if($mute->cause !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->cause);
			} else {
				$results[$i]["reason"] = "-";
			}
			$results[$i]["type"] = "mute";
			$results[$i]["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';

			if($mute->time !== null){
				$results[$i]["expires"] = strtotime($mute->time);
				$results[$i]["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($mute->time)), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
			} else {
				$results[$i]['expires'] = 0;
				$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
			}
			$i++;
		}
		
		// Sort by date
		function date_compare($a, $b)
		{
			if(isset($a['issued'])){
				$t1 = $a['issued'];
			} else {
				$t1 = $a['expires'];
			}
			if(isset($b['issued'])){
				$t2 = $b['issued'];
			} else {
				$t2 = $b['expires'];
			}
			return $t2 - $t1;
		}    
		usort($results, 'date_compare');
		return $results;
	}
	
	// Receive an object containing infraction information for a specified infraction ID and type (Ban and Mute plugin)
	// Params: $type (string), either ban, kick or mute; $id (int), ID of infraction
	public function bam_getInfraction($type, $id) {
		$ids = explode(';', $id);
		$name = $ids[0];
		if($ids[1]) $time = date('Y-m-d H:i:s', $ids[1]);
		else $time = null;
		
		if($type === "ban" || $type === "temp_ban"){
			$results = $this->_db->get('history', array("time", "=", $time))->results();
			foreach($results as $result){
				if($result->name == $name) return $result;
			}
		} else if($type === "mute"){
			if($time){
				$results = $this->_db->get('mutes', array("time", "=", $time))->results();
				foreach($results as $result){
					if($result->name == $name) return $result;
				}
			} else {
				$results = $this->_db->get('mutes', array('name', '=', htmlspecialchars($name)))->results();
				foreach($results as $result){
					if($result->time == null) return $result;
				}
			}
		}

		return false;
	}
	
	// Receive a list of all infractions for BungeeUtilisals, either for a single user or for all users
	// Params: $uuid (string), UUID of a user. If null, will list all infractions
	public function bu_getAllInfractions($uuid = null) {
		if($uuid !== null){
			$ban_field = "Banned";
			$mute_field = "Muted";
			$symbol = "=";
			$equals = $uuid;
		} else {
			$ban_field = "Banned";
			$mute_field = "Muted";
			$symbol = "<>";
			$equals = "0";
		}
		$bans = $this->_db->get('bans', array($ban_field, $symbol, $equals))->results();
		$mutes = $this->_db->get('mutes', array($mute_field, $symbol, $equals))->results();
		
		$results = array();
		$i = 0;
		
		foreach($bans as $ban){
			$results[$i]["id"] = htmlspecialchars($ban->Banned);
			$results[$i]["uuid"] = htmlspecialchars($ban->Banned);
			$results[$i]["staff"] = htmlspecialchars($ban->BannedBy);

			if($ban->Reason !== null){
				$results[$i]["reason"] = htmlspecialchars($ban->Reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			if($ban->BanTime != '-1'){
				$results[$i]["type"] = "temp_ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['temp_ban'] . '</span>';
				
				// Convert expiry date
				$date = $ban->BanTime / 1000;
				
				$results[$i]["expires"] = strtotime($ban->BanTime);
				$results[$i]["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
			} else {
				$results[$i]["type"] = "ban";
				$results[$i]["type_human"] = '<span class="label label-danger">' . $this->_language['ban'] . '</span>';
				$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
			}

			$i++;
		}
		
		foreach($mutes as $mute){
			$results[$i]["id"] = htmlspecialchars($mute->Muted);
			$results[$i]["uuid"] = htmlspecialchars($mute->Muted);
			$results[$i]["staff"] = htmlspecialchars($mute->MutedBy);

			if($mute->Reason !== null){
				$results[$i]["reason"] = htmlspecialchars($mute->Reason);
			} else {
				$results[$i]["reason"] = "-";
			}
			
			if($mute->MuteTime != '-1'){
				$results[$i]["type"] = "temp_mute";
				$results[$i]["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';
				
				// Convert expiry date
				$date = $mute->MuteTime / 1000;
				
				$results[$i]["expires"] = strtotime($mute->MuteTime);
				$results[$i]["expires_human"] = '<span class="label label-warning" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
			} else {
				$results[$i]["type"] = "mute";
				$results[$i]["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';
				$results[$i]["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
			}

			$i++;
		}
		return $results;
	}
	
	// Receive an object containing infraction information for a specified infraction ID and type (BungeeUtilisals plugin)
	// Params: $type (string), either ban or mute; $id (string), UUID of infraction
	public function bu_getInfraction($type, $id) {
		if($type === "ban" || $type === "temp_ban"){
			$results = $this->_db->get('bans', array("Banned", "=", $id))->results();

			if(count($results)) return $results[0];
		} else if($type === "mute" || $type === "temp_mute"){
			$results = $this->_db->get('mutes', array("Muted", "=", $id))->results();

			if(count($results)) return $results[0];
		}

		return false;
	}
	
	
	// Receive a list of all infractions for AdvancedBan, either for a single user or for all users
	// Params: $uuid (string) - UUID of a user. If null, return all infractions
	public function ab_getAllInfractions($uuid = null) {
		if($uuid !== null){
			$symbol = "=";
			$equals = str_replace('-', '', $uuid);
		} else {
			$symbol = "<>";
			$equals = "0";
		}
		$punishments = $this->_db->get('punishmenthistory', array('uuid', $symbol, $equals))->results();
		
		$results = array();
		
		if(count($punishments)){
			foreach($punishments as $punishment){
				$ret = array();
				$ret["id"] = $punishment->id;
				$ret["uuid"] = str_replace('-', '', $punishment->uuid);
				$ret["staff"] = htmlspecialchars($punishment->operator);
				$ret["issued"] = ($punishment->start / 1000);
				$ret["issued_human"] = date("jS M Y, H:i", ($punishment->start / 1000));

				if($punishment->reason !== null){
					$ret["reason"] = htmlspecialchars($punishment->reason);
				} else {
					$ret["reason"] = "-";
				}
				
				switch($punishment->punishmentType){
					case 'BAN':
						// Ban
						$ret["type"] = "ban";
						$ret["type_human"] = '<span class="label label-danger">' . $this->_language['ban'] . '</span>';
						$ret["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
					break;
					
					case 'TEMP_BAN':
						// Temp ban
						$ret["type"] = "temp_ban";
						$ret["type_human"] = '<span class="label label-danger">' . $this->_language['temp_ban'] . '</span>';

						// Convert expiry date
						$date = $punishment->end / 1000;
				
						$ret["expires"] = $date;
						if(strtotime('now') < $date)
							$ret["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
						else
							$ret["expires_human"] = '<span class="label label-success" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expired_x']) . '">' . $this->_language['expired'] . '</span>';
					break;
					
					case 'MUTE':
						// Mute
						$ret["type"] = "mute";
						$ret["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';
						$ret["expires_human"] = '<span class="label label-danger">' . $this->_language['permanent'] . '</span>';
					break;
					
					case 'TEMP_MUTE':
						// Temp mute
						$ret["type"] = "temp_mute";
						$ret["type_human"] = '<span class="label label-warning">' . $this->_language['mute'] . '</span>';

						// Convert expiry date
						$date = $punishment->end / 1000;
				
						$ret["expires"] = $date;
						if(strtotime('now') < $date)
							$ret["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
						else
							$ret["expires_human"] = '<span class="label label-success" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expired_x']) . '">' . $this->_language['expired'] . '</span>';
					break;
					
					case 'WARNING':
						// Warning
						$ret["type"] = "warning";
						$ret["type_human"] = '<span class="label label-info">' . $this->_language['warning'] . '</span>';
						$ret["expires_human"] = '';
					break;
					
					case 'TEMP_WARNING':
						$ret["type"] = "warning";
						$ret["type_human"] = '<span class="label label-info">' . $this->_language['warning'] . '</span>';
						
						// Convert expiry date
						$date = $punishment->end / 1000;
						
						$ret["expires"] = $date;
						if(strtotime('now') < $date)
							$ret["expires_human"] = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expires_x']) . '">' . $this->_language['active'] . '</span>';
						else
							$ret["expires_human"] = '<span class="label label-success" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", $date), $this->_language['expired_x']) . '">' . $this->_language['expired'] . '</span>';
					break;
					
					case 'KICK':
						// Kick
						$ret["type"] = "kick";
						$ret["type_human"] = '<span class="label label-default">' . $this->_language['kick'] . '</span>';
						$ret["expires_human"] = '';
					break;
				}
				
				$results[] = $ret;
			}
		}
		
		// Sort by date
		function date_compare($a, $b){
			if(isset($a['issued'])){
				$t1 = $a['issued'];
			} else {
				$t1 = $a['expires'];
			}
			if(isset($b['issued'])){
				$t2 = $b['issued'];
			} else {
				$t2 = $b['expires'];
			}
			return $t2 - $t1;
		}
		
		usort($results, 'date_compare');

		return $results;
	}
	
	// Retrieve a specific infraction
	// Params: $id - ID of ban to retrieve
	public function ab_getInfraction($id){
		$results = $this->_db->get('punishmenthistory', array('id', '=', $id))->results();
		
		if(count($results)) return $results[0];

		return false;
	}
}
