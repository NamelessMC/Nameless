<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr6
 *
 *  User class
 */
class User {
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
			$_admSessionName,
			$_isAdmLoggedIn,
			$_permissions;

	// Construct User class
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		$this->_admSessionName = Config::get('session/admin_name');

		if(!$user){
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				if($this->find($user)){
					$this->_isLoggedIn = true;
				} else {
					// process logout
				}
			}
			if(Session::exists($this->_admSessionName)) {
				$user = Session::get($this->_admSessionName);
				if($user == $this->data()->id && $this->find($user)){
					$this->_isAdmLoggedIn = true;
				} else {
					// process logout
				}
			}
		} else {
			$this->find($user);
		}

	}

	// Get name of group from an ID
	public function getGroupName($group_id) {
		$data = $this->_db->get('groups', array('id', '=', $group_id));
		if($data->count()) {
			$results = $data->results();
			return htmlspecialchars($results[0]->name);
		} else {
			return false;
		}
	}

	// Get a group's CSS class from an ID
	public function getGroupClass($user_id) {
		$user = $this->_db->get('users', array('id', '=', $user_id));

		// Check the user exists..
		if($user->count()){
			// Get the user's group
			$group_id = $user->results();
			$group_id = $group_id[0]->group_id;

			$data = $this->_db->get('groups', array('id', '=', $group_id));
			if($data->count()) {
				$results = $data->results();
				return 'color:' . htmlspecialchars($results[0]->group_username_css) . ';';
			}
		}

		return false;
	}



    // Get a user's IP address
    /*
	public function getIP() {
        $usingproxy = false;
        $proxiedIp = null;
        $ip = $_SERVER['REMOTE_ADDR'];

		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $proxiedIp = $_SERVER['HTTP_CLIENT_IP'];
		    $usingproxy = true;
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxiedIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $usingproxy = true;
		}
		if($usingproxy) {
		    $proxyTextList = Config::get("allowedProxies");
		    $hasRanges = false;
            if ( strpos( $proxyTextList, '/' ) == true ) {
                $hasRanges = true;
            }
            $proxyList = explode(',', $proxyTextList);

            // Check if it's in the array at first for mixed lists
            if (in_array($_SERVER['REMOTE_ADDR'], $proxyList)) {
                // Return proxied ip if it's in the list and there's no ranges
                return $proxiedIp;
            }

            if($hasRanges) { // Check if we're using ranges (needed for cloudflare)
                foreach($proxyList as $proxy) {
                    if ( strpos( $proxy, '/' ) == true ) { // check if it's an actual range (we're lazy so we just check for an / in it)
                        if($this->ip_in_range($ip, $proxy)) {
                            return $proxiedIp;
                        }
                    }
                }
            }
            // Return normal ip if it's not in the list
            return $ip;
        }
        // Return normal ip if not using proxy
		return $ip;
	}
    */
    public function getIP() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Check if a given ip is in a network
     * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
     * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
     * @return boolean true if the ip is in this range / false if not.
     * https://gist.github.com/tott/7684443
     */
    function ip_in_range( $ip, $range ) {
        if ( strpos( $range, '/' ) == false ) {
            $range .= '/32';
        }
        // $range is in IP/CIDR format eg 127.0.0.1/24
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }

	// Update a user's data
	public function update($fields = array(), $id = null) {

		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating your details.');
		}
	}

	// Create a new user
	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	// Find a specified user by username
	// Params: $user (mixed) - either username or user ID to search for
	//         $field (string) - database field to use, eg email, username, id
	public function find($user = null, $field = 'id') {
		if ($user) {
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	// Get username from ID
	public function IdToName($id = null){
		if($id){
			$data = $this->_db->get('users', array('id', '=', $id));

			if($data->count()){
				$results = $data->results();
				return $results[0]->username;
			}
		}
		return false;
	}

	// Get nickname from ID
	public function IdToNickname($id = null) {
		if($id){
			$data = $this->_db->get('users', array('id', '=', $id));

			if($data->count()) {
				$results = $data->results();
				return $results[0]->nickname;
			}
		}
		return false;
	}

	// Log the user in
	public function login($username = null, $password = null, $remember = false, $method = 'email') {
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
			$this->_isLoggedIn = true;
		} else {
            if($this->checkCredentials($username, $password, $method) === true){
                // Valid credentials
                Session::put($this->_sessionName, $this->data()->id);
                if($remember) {
                    $hash = Hash::unique();
                    $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                    if(!$hashCheck->count()) {
                        $this->_db->insert('users_session', array(
                            'user_id' => $this->data()->id,
                            'hash' => $hash
                        ));
                    } else
                        $hash = $hashCheck->first()->hash;

                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                }
                return true;
            }
		}
		return false;
	}

	// Handle AdminCP logins
	public function adminLogin($username = null, $password = null, $method = 'email') {
		if(!$username && !$password && $this->exists()){
			Session::put($this->_admSessionName, $this->data()->id);
		} else {
            if($this->checkCredentials($username, $password, $method) === true){
                Session::put($this->_admSessionName, $this->data()->id);

                $hash = Hash::unique();
                $hashCheck = $this->_db->get('users_admin_session', array('user_id', '=', $this->data()->id));

                if(!$hashCheck->count()) {
                    $this->_db->insert('users_admin_session', array(
                        'user_id' => $this->data()->id,
                        'hash' => $hash
                    ));
                } else {
                    $hash = $hashCheck->first()->hash;
                }

                Cookie::put($this->_cookieName . "_adm", $hash, 3600);

                return true;
			}
		}
		return false;
	}

	// Check whether given credentials are valid
    public function checkCredentials($username, $password, $method = 'email'){
        $user = $this->find($username, $method);
        if($user){
            switch($this->data()->pass_method) {
                case 'wordpress':
                    // phpass
                    $phpass = new PasswordHash(8, FALSE);

                    if($phpass->CheckPassword($password, $this->data()->password))
                        return true;

                    break;

                case 'sha256':
                    $exploded = explode('$', $this->data()->password);

                    $salt = $exploded[0];
                    $pass = $exploded[1];

                    if($salt . hash('sha256', hash('sha256', $password) . $salt) == $salt . $pass)
                        return true;

                    break;

                case 'pbkdf2':
                    $exploded = explode('$', $this->data()->password);

                    $iterations = $exploded[0];
                    $salt = $exploded[1];
                    $pass = $exploded[2];

                    $hashed = hash_pbkdf2('sha256', $password, $salt, $iterations, 64, true);

                    if($hashed == hex2bin($pass))
                        return true;

                    break;

                case 'modernbb':
                case 'sha1':
                    if(sha1($password) == $this->data()->password)
                        return true;

                    break;

                default:
                    // Default to bcrypt
                    if(password_verify($password, $this->data()->password))
                        return true;
                    break;
            }
        }

        return false;
    }

	// Get a user's group from their ID. We can either return their ID only, their normal HTML display code, or their large HTML display code
	public function getGroup($id, $html = null, $large = null) {
		$data = $this->_db->get('users', array('id', '=', $id));
		if($html === null){
			if($large === null){
				$results = $data->results();
				return $results[0]->group_id;
			} else {
				$results = $data->results();
				$data = $this->_db->get('groups', array('id', '=', $results[0]->group_id));
				$results = $data->results();
				return $results[0]->group_html_lg;
			}
		} else {
			$results = $data->results();
			$data = $this->_db->get('groups', array('id', '=', $results[0]->group_id));
			$results = $data->results();
			return $results[0]->group_html;
		}
	}

	// Get all of a user's groups from their ID. We can return their ID only or their HTML display code
    public function getAllGroups($id, $html = null) {
        $groups = array();

        $data = $this->_db->get('users', array('id', '=', $id));
        if($data->count()){
            $results = $data->results();

            if(is_null($html)){
                $groups[] = $results[0]->group_id;
            } else {
                $group = $this->_db->get('groups', array('id', '=', $results[0]->group_id));
                if($group->count()){
                    $group = $group->results();
                    $groups[] = $group[0]->group_html;
                }
            }

            $secondary = $results[0]->secondary_groups;
            if($secondary){
                $secondary = json_decode($secondary, true);
                if(count($secondary)){
                    foreach($secondary as $item){
                        if(is_null($html)){
                            $groups[] = $item;
                        } else {
                            $group = $this->_db->get('groups', array('id', '=', $item));
                            if($group->count()){
                                $group = $group->results();
                                $groups[] = $group[0]->group_html;
                            }
                        }
                    }
                }
            }
        }

        return $groups;
    }

	// Get a user's signature, by user ID
	public function getSignature($id) {
		$data = $this->_db->get('users', array('id', '=', $id));
		$results = $data->results();
		if(!empty($results[0]->signature)){
			return $results[0]->signature;
		} else {
			return "";
		}
	}

	// Get a user's avatar, based on user ID
	public function getAvatar($id, $path = null, $size = 128, $full = false) {
        $data = $this->_db->get('users', array('id', '=', $id))->results();
        if(empty($data)){
            // User doesn't exist
            return false;
        }

        if($data[0]->uuid != null && $data[0]->uuid != 'none')
            $uuid = Output::getClean($data[0]->uuid);
        else {
            $uuid = Output::getClean($data[0]->username);
            //fix accounts with special characters in name having no avatar                                             
            if(preg_match("#[^][_A-Za-z0-9]#", $uuid)) 
                $uuid = 'Steve';
	}
        // Get avatar type
        if(defined('CUSTOM_AVATARS')){
            // Custom avatars
            if($data[0]->gravatar == 1){
                // Gravatar
                return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $data[0]->email ) ) ) . "?d=" . urlencode( 'https://cravatar.eu/helmavatar/' . $uuid . '/200.png' ) . "&s=200";
            } else if($data[0]->has_avatar == 1){
                // Custom avatar
                $exts = array('gif','png','jpg','jpeg');
                foreach($exts as $ext) {
                    if(file_exists(ROOT_PATH . "/uploads/avatars/" . $id . "." . $ext)){
                        $avatar_path = ($full ? rtrim(Util::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . "uploads/avatars/" . $id . "." . $ext . '?v=' . Output::getClean($data[0]->avatar_updated);
                        break;
                    }
                }
                if(isset($avatar_path)){
                    return $avatar_path;
                }
            }
        }

        // Default avatar
        if(defined('DEFAULT_AVATAR_TYPE') && DEFAULT_AVATAR_TYPE == 'custom'){
            // Custom default avatar
            return(($full ? rtrim(Util::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/defaults/' . DEFAULT_AVATAR_IMAGE);
        } else {
            // Minecraft avatar
	        if(defined('DEFAULT_AVATAR_SOURCE')){
		        if(defined('DEFAULT_AVATAR_PERSPECTIVE'))
			        $perspective = DEFAULT_AVATAR_PERSPECTIVE;
		        else
			        $perspective = 'face';

		        switch(DEFAULT_AVATAR_SOURCE){
			        case 'crafatar':
				        if($perspective == 'face')
					        return 'https://crafatar.com/avatars/' . Output::getClean($uuid) . '?size=' . $size . '&amp;overlay';
				        else
					        return 'https://crafatar.com/renders/head/' . Output::getClean($uuid) . '?overlay';

				        break;

			        case 'nameless':
				        // Only supports face currently
				        if(defined('FRIENDLY_URLS') && FRIENDLY_URLS == true)
					        return URL::build('/avatar/' . Output::getClean($uuid));
				        else
					        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u=' . Output::getClean($uuid);

				        break;

			        case 'mc-heads':
				        if($perspective == 'face')
					        return 'https://mc-heads.net/avatar/' . Output::getClean($uuid) . '/' . $size;
				        else
					        return 'https://mc-heads.net/head/' . Output::getClean($uuid) . '/' . $size;

				        break;

			        case 'minotar':
				        if($perspective == 'face')
					        return 'https://minotar.net/helm/'.  Output::getClean($uuid) . '/' . $size . '.png';
				        else
					        return 'https://minotar.net/cube/'.  Output::getClean($uuid) . '/' . $size . '.png';

				        break;

			        case 'visage':
				        if($perspective == 'face')
					        return 'https://visage.surgeplay.com/face/' . $size . '/' . Output::getClean($uuid);
				        else if($perspective == 'bust')
					        return 'https://visage.surgeplay.com/bust/' . $size . '/' . Output::getClean($uuid);
				        else
					        return 'https://visage.surgeplay.com/head/' . $size . '/' . Output::getClean($uuid);

				        break;

			        case 'cravatar':
			        default:
				        if($perspective == 'face')
					        return 'https://cravatar.eu/helmavatar/' . Output::getClean($uuid) . '/' . $size . '.png';
				        else
					        return 'https://cravatar.eu/helmhead/' . Output::getClean($uuid) . '/' . $size . '.png';
				        break;
		        }
	        } else {
		        // Fall back to cravatar
		        return 'https://cravatar.eu/helmavatar/' . Output::getClean($uuid) . '/' . $size . '.png';
	        }
        }
	}

	// Does the user have any infractions?
	public function hasInfraction($user_id){
		$data = $this->_db->get('infractions', array('punished', '=', $user_id))->results();
		if(empty($data)){
			return false;
		} else {
			$return = array();
			$n = 0;
			foreach($data as $infraction){
				if($infraction->acknowledged == '0'){
					$return[$n]["id"] = $infraction->id;
					$return[$n]["staff"] = $infraction->staff;
					$return[$n]["reason"] = $infraction->reason;
					$return[$n]["date"] = $infraction->infraction_date;
					$n++;
				}
			}
			return $return;
		}
	}

	// Does the user exist?
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	// Log the user out
	public function logout() {

		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	// Process logout if user is admin
	public function admLogout() {

		$this->_db->delete('users_admin_session', array('user_id', '=', $this->data()->id));

		Session::delete($this->_admSessionName);
		Cookie::delete($this->_cookieName . "_adm");
	}

	// Returns the currently logged in user's data
	public function data() {
		return $this->_data;
	}

	// Returns true if the current user is logged in
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	// Returns true if the current user is authenticated as an administrator
	public function isAdmLoggedIn() {
		return $this->_isAdmLoggedIn;
	}

	// Return a comma separated string of all users - this is for the new private message dropdown
	public function listAllUsers() {
		$data = $this->_db->get('users', array('id', '<>', '0'))->results();
		$return = "";
		$i = 1;

		foreach($data as $item){
			if($i != count($data)){
				$return .= '"' . $item->username . '",';
			} else {
				$return .= '"' . $item->username . '"';
			}
			$i++;
		}
		return $return;
	}

	// Return an ID from a username
	public function NameToId($name = null){
		if($name){
			$data = $this->_db->get('users', array('username', '=', $name));

			if($data->count()){
				$results = $data->results();
				return $results[0]->id;
			}
		}
		return false;
	}

    // Return an ID from an email
    public function emailToId($email = null){
        if($email){
            $data = $this->_db->get('users', array('email', '=', $email));

            if($data->count()){
                $results = $data->results();
                return $results[0]->id;
            }
        }
        return false;
    }

	// Get a list of PMs a user has access to
	public function listPMs($user_id = null){
		if($user_id){
			$return = array(); // Array to return containing info of PMs

			// Get a list of PMs which the user is in
			$data = $this->_db->get('private_messages_users', array('user_id', '=', $user_id));

			if($data->count()){
				$data = $data->results();
				foreach($data as $result){
					// Get a list of users who are in this conversation and return them as an array
					$pms = $this->_db->get('private_messages_users', array('pm_id', '=', $result->pm_id))->results();
					$users = array(); // Array containing users with permission
					foreach($pms as $pm){
						$users[] = $pm->user_id;
					}

					// Get the PM data
					$pm = $this->_db->get('private_messages', array('id', '=', $result->pm_id))->results();
					$pm = $pm[0];

					$return[$pm->id]['id'] = $pm->id;
					$return[$pm->id]['title'] = Output::getClean($pm->title);
					$return[$pm->id]['created'] = $pm->created;
					$return[$pm->id]['updated'] = $pm->last_reply_date;
					$return[$pm->id]['user_updated'] = $pm->last_reply_user;
					$return[$pm->id]['users'] = $users;
				}
			}
			// Order the PMs by date updated - most recent first
			usort($return, function($a, $b) {
				return $b['updated'] - $a['updated'];
			});

			return $return;
		}
		return false;
	}

	// Get a specific private message, and see if the user actually has permission to view it
	public function getPM($pm_id = null, $user_id = null){
		if($user_id && $pm_id){
			// Get the PM - is the user the author?
			$data = $this->_db->get('private_messages', array('id', '=', $pm_id));
			if($data->count()){
				$data = $data->results();
				$data = $data[0];

				// Does the user have permission to view the PM?
				$pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
				foreach($pms as $pm){
					if($pm->user_id == $user_id){
						$has_permission = true;
						$pm_user_id = $pm->id;
						break;
					}
				}

				if(!isset($has_permission)){
					return false; // User doesn't have permission
				}

				// Set message to "read"
				if($pm->read == 0){
					$this->_db->update('private_messages_users', $pm_user_id, array(
						'`read`' => 1
					));
				}

				// User has permission, return the PM information

				// Get a list of users in the conversation
				if(!isset($pms)){
					$pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
				}

				$users = array(); // Array to store users
				foreach($pms as $pm){
					$users[] = $pm->user_id;
				}

				return array($data, $users);
			}
		}
		return false;
	}

	// Delete a user's access to view the PM, or if they're the author, the PM itself
	public function deletePM($pm_id = null, $user_id = null){
		if($user_id && $pm_id){
			// Is the user the author?
			$data = $this->_db->get('private_messages', array('id', '=', $pm_id));
			if($data->count()){
				$data = $data->results();
				$data = $data[0];
				if($data->author_id != $user_id){
					// User is not the author, only delete
					$pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
					foreach($pms as $pm){
						if($pm->user_id == $user_id){
							// get the ID and delete
							$id = $pm->id;
							$this->_db->delete('private_messages_users', array('id', '=', $id));
							return true;
						}
					}
				} else {
					// User is the author, delete the PM altogether
					$this->_db->delete('private_messages_users', array('pm_id', '=', $pm_id));
					$this->_db->delete('private_messages', array('id', '=', $pm_id));
					return true;
				}
			}
		}
		return false;
	}

	// Get the number of unread PMs for the specified user
	public function getUnreadPMs($user_id = null){
		if($user_id){
			$pms = $this->_db->get('private_messages_users', array('user_id', '=', $user_id));
			if($pms->count()){
				$pms = $pms->results();
				$count = 0;
				foreach($pms as $pm){
					if($pm->read == 0){
						$count++;
					}
				}
				return $count;
			} else {
				return 0;
			}
		}
		return false;
	}

	// Can the specified user view the AdminCP?
	public function canViewACP($user_id = null){
        if(is_null($user_id)){
            $group_id = $this->data()->group_id;
            if(is_null($this->data()->secondary_groups))
                $secondary = array();
            else
                $secondary = json_decode($this->data()->secondary_groups, true);
        } else {
            // Get user
            $user_query = $this->_db->get('users', array('id', '=', $user_id));
            if($user_query->count()){
                $user_query = $user_query->first();
                $group_id = $user_query->group_id;
                if(is_null($user_query->secondary_groups))
                    $secondary = array();
                else
                    $secondary = json_decode($user_query->secondary_groups, true);

            } else {
                return false;
            }
        }

        // Get whether the user can view the AdminCP from the groups table
        $data = $this->_db->get('groups', array('id', '=', $group_id));
        if($data->count()){
            $data = $data->results();
            if($data[0]->admin_cp == 1){
                // Can view
                return true;
            } else {
                // Check secondary groups
                if(count($secondary)){
                    foreach($secondary as $group) {
                        $data = $this->_db->get('groups', array('id', '=', $group));
                        if($data->count()){
                            $data = $data->results();

                            if($data[0]->admin_cp == 1){
                                return true;
                            }
                        }
                    }
                }
            }
        }
		return false;
	}

	// Can the specified user view the ModCP?
	public function canViewMCP($user_id = null){
	    if(is_null($user_id)){
	        $group_id = $this->data()->group_id;
	        if(is_null($this->data()->secondary_groups))
	            $secondary = array();
	        else
	            $secondary = json_decode($this->data()->secondary_groups, true);
        } else {
	        // Get user
            $user_query = $this->_db->get('users', array('id', '=', $user_id));
            if($user_query->count()){
                $user_query = $user_query->first();
                $group_id = $user_query->group_id;
                if(is_null($user_query->secondary_groups))
                    $secondary = array();
                else
                    $secondary = json_decode($user_query->secondary_groups, true);

            } else {
                return false;
            }
        }

        // Get whether the user can view the ModCP from the groups table
        $data = $this->_db->get('groups', array('id', '=', $group_id));
        if($data->count()) {
            $data = $data->results();
            if ($data[0]->mod_cp == 1) {
                // Can view
                return true;
            } else {
                // Check secondary groups
                if(count($secondary)){
                    foreach ($secondary as $group) {
                        $data = $this->_db->get('groups', array('id', '=', $group));
                        if ($data->count()) {
                            $data = $data->results();

                            if($data[0]->mod_cp == 1){
                                return true;
                            }
                        }
                    }
                }
            }
        }
		return false;
	}

	// Return profile fields for specified user
	// Params:  $user_id (integer) - user id of user to retrieve fields from
	//			$public (boolean)  - whether to only return public fields or not (default true)
	//			$forum (boolean)   - whether to only return fields which display on forum posts, only if $public is true (default false)
	public function getProfileFields($user_id = null, $public = true, $forum = false){
		if($user_id){
			$data = $this->_db->get('users_profile_fields', array('user_id', '=', $user_id));

			if($data->count()){
				if($public == true){
					// Return public fields only
					$return = array();
					foreach($data->results() as $result){
						$is_public = $this->_db->get('profile_fields', array('id', '=', $result->field_id));
						if(!$is_public->count()) continue;
						else $is_public = $is_public->results();

						if($is_public[0]->public == 1){
							if($forum == true){
								if($is_public[0]->forum_posts == 1){
									$return[] = array(
										'name' => Output::getClean($is_public[0]->name),
										'value' => Output::getClean($result->value)
									);
								}
							} else {
								$return[] = array(
									'name' => Output::getClean($is_public[0]->name),
									'value' => Output::getClean($result->value)
								);
							}
						}
					}

					return $return;
				} else {
					// Return all fields
					$return = array();
					foreach($data->results() as $result){
						$name = $this->_db->get('profile_fields', array('id', '=', $result->field_id));
						if(!$name->count()) continue;
						else $name = $name->results();

						$return[] = array(
							'name' => Output::getClean($name[0]->name),
							'value' => Output::getClean($result->value)
						);
					}

					return $return;
				}
			} else return false;
		}
		return false;
	}

	/*
	 *  Is a user blocked?
	 *  Params: $user - ID of first user
	 *          $blocked - ID of user who may or may not be blocked
	 */
	public function isBlocked($user, $blocked){
	    if($user && $blocked){
	        $possible_users = $this->_db->get('blocked_users', array('user_id', '=', $user));
	        if($possible_users->count()){
	            $possible_users = $possible_users->results();

	            foreach($possible_users as $possible_user){
	                if($possible_user->user_blocked_id == $blocked)
	                    return true;
                }
            }
        }
        return false;
    }

    /*
     *  Does the user have a given permission?
     *  Params: $permission (string) - name of permission
     *          $user_id (int) - user ID of user to check - optional, default to logged in user
     */
    public function hasPermission($permission, $user_id = null){
        if($this->_permissions != null) {
            if(isset($this->_permissions[$permission]) && $this->_permissions[$permission] == 1){
                return true;
            }
        }

        if(!$user_id){
            if($this->isLoggedIn())
                $group = $this->_db->get('groups', array('id', '=', $this->data()->group_id));
            else
                return false;
        } else {
            $user = $this->_db->get('users', array('id', '=', $user_id));
            if(!$user->count())
                return false;

            $user = $user->first();
            $group = $this->_db->get('groups', array('id', '=', $user->group_id));
        }

        if($group->count()){
            $group = $group->first();
            $this->_permissions = json_decode($group->permissions, true);
			
            if(isset($this->_permissions[$permission]) && $this->_permissions[$permission] == 1){
                return true;
            }
        }
        return false;
    }
	
	// Get a user's profile views, by user ID
	public function getProfileViews($user_id = null) {
		$data = $this->_db->get('users', array('id', '=', $user_id));
		$results = $data->results();
		if(!empty($results[0]->profile_views)){
			return $results[0]->profile_views;
		} else {
			return 0;
		}
	}

    // Is private profile enabled and does he have the permission to use it?
    public function canPrivateProfile($user_id = null){
        $settings_data = $this->_db->get('settings', array('name', '=', 'private_profile'));
        $settings_results = $settings_data->results();
        if(($settings_results[0]->value == 1) && ($this->hasPermission('usercp.private_profile', $user_id)))
            return true;
        else
            return false;
    }

    // Is the profile page set to private?
    public function isPrivateProfile($user_id = null) {
        $cp_data = $this->_db->get('users', array('id', '=', $user_id));
        $cp_results = $cp_data->results();
        if($cp_results[0]->private_profile == 1){
            // It's private
            return true;
        } else {
            // It's not private
            return false;
        }
    }

    // Get templates a user's group has access to
	public function getUserTemplates($group_id = null, $secondary_groups = null) {
    	if(!$group_id){
    		if(!$this->isLoggedIn()){
    			return [];
		    }

    		$group_id = $this->_data->group_id;
	    }

    	if(!$secondary_groups){
    		if(!$this->isLoggedIn()){
    			return [];
		    }

    		$secondary_groups = $this->_data->secondary_groups;
	    }

    	$decoded = json_decode($secondary_groups);
    	$decoded[] = $group_id;

    	$groups = '(';
    	foreach($decoded as $item){
    		if(is_numeric($item)){
    			$groups .= ((int) $item) . ',';
		    }
	    }

    	$groups = rtrim($groups, ',') . ')';

    	return $this->_db->query('SELECT template.id, template.name FROM nl2_templates AS template WHERE template.enabled = 1 AND template.id IN (SELECT template_id FROM nl2_groups_templates WHERE can_use_template = 1 AND group_id IN ' . $groups . ')')->results();
	}

}
