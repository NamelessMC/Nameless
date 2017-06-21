<?php 
/* 
 *	Made by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Infractions addon page
$page = 'Infractions'; // for navbar

// Ensure the addon is enabled
if(!in_array('Infractions', $enabled_addon_pages)){
	// Not enabled, redirect to homepage
	echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
	die();
}

require('core/includes/paginate.php');
require('addons/Infractions/config.php');
require('addons/Infractions/Infractions.php');
require('core/integration/uuid.php');

$infractions = new Infractions($inf_db, $infractions_language);
$pagination = new Pagination();
$timeago = new Timeago();

// Get current plugin in use
$inf_plugin = $queries->getWhere('infractions_settings', array('id', '=', 1));

if(!count($inf_plugin)){
	// Need to configure addon
	echo 'Please set up the addon in the AdminCP -> Addons tab.';
	die();
}

$inf_plugin = $inf_plugin[0]->value;

// Redirect to fix pagination if URL does not end in /
if(substr($_SERVER['REQUEST_URI'], -1) !== '/' && !strpos($_SERVER['REQUEST_URI'], '?')){
	echo '<script data-cfasync="false">window.location.replace(\'/infractions/\');</script>';
	die();
}

// Get page number
if(isset($_GET['p'])){
	if(!is_numeric($_GET['p'])){
		Redirect::to('/infractions');
		die();
	} else {
		if($_GET['p'] == 1){ 
			// Avoid bug in pagination class
			Redirect::to('/infractions/');
			die();
		}
		$p = $_GET['p'];
	}
} else {
	$p = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Infractions page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php
	// Generate header and navbar content
	// Page title
	$title = $infractions_language['infractions'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>

  <body>
    <?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
	
    <div class="container">	
	  <div class="well">
        <h2><?php echo $infractions_language['infractions']; ?></h2>
		<?php
		if(!isset($_GET['type']) && !isset($_GET['id'])){
			// Check cache
			$c->setCache('infractions_cache');
			if($c->isCached('infractions')){
				$all_infractions = $c->retrieve('infractions');
			} else {
				// Get all infractions, depending on plugin
				switch($inf_plugin){
					case 'bat':
						$all_infractions = $infractions->bat_getAllInfractions();
					break;
					case 'bm':
						$all_infractions = $infractions->bm_getAllInfractions();
					break;
					case 'lb':
						$all_infractions = $infractions->lb_getAllInfractions();
					break;
					case 'bam':
						$all_infractions = $infractions->bam_getAllInfractions();
					break;
					case 'bu':
						$all_infractions = $infractions->bu_getAllInfractions();
					break;
					case 'ab':
						$all_infractions = $infractions->ab_getAllInfractions();
					break;
				}
				
				$c->store('infractions', $all_infractions, 120);
			}

			// Pagination
			$paginate = PaginateArray($p);
			
			$n = $paginate[0];
			$f = $paginate[1];
			
			if(count($all_infractions) > $f){
				$d = $p * 10;
			} else {
				$d = count($all_infractions) - $n;
				$d = $d + $n;
			}
		?>
	    <div class="table-responsive">
		  <table class="table table-bordered">
		    <colgroup>
		      <col span="1" style="width: 15%;">
		      <col span="1" style="width: 15%;">
		      <col span="1" style="width: 15%">
			  <?php if($inf_plugin != 'bu'){ ?>
		      <col span="1" style="width: 30%">
		      <col span="1" style="width: 15%">
			  <?php } else { ?>
		      <col span="1" style="width: 45%">
			  <?php } ?>
		      <col span="1" style="width: 10%">
		    </colgroup>
		    <thead>
		      <tr>
			    <td><?php echo $user_language['username']; ?></td>
			    <td><?php echo $infractions_language['staff_member']; ?></td>
			    <td><?php echo $infractions_language['action']; ?></td>
			    <td><?php echo $infractions_language['reason']; ?></td>
				<?php if($inf_plugin != 'bu'){ ?>
			    <td><?php echo $infractions_language['created']; ?></td>
				<?php } ?>
			    <td><?php echo $infractions_language['actions']; ?></td>
		      </tr>
		    </thead>
		    <tbody>
			<?php 
			while($n < $d){
				if(isset($mcname)) unset($mcname);
				
				$infraction = $all_infractions[$n];
				if($inf_plugin == "mb"){
					$exploded = explode('.', $infraction["id"]);
					$mcname = $exploded[0];
					$time = $exploded[1];
				} else if($inf_plugin == "lb"){
					$mcname = $infraction["username"];
				} else {
					$infractions_query = $queries->getWhere('users', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));
					if(empty($infractions_query)){
						
						if($inf_plugin == 'bat') $mcname = $infractions->bat_getUsernameFromUUID($infraction['uuid']);
						
						if($inf_plugin != 'bat' || !count($mcname)){
							if($inf_plugin == 'bm') $mcname = $infractions->bm_getUsernameFromID(pack("H*", str_replace('-', '', $infraction['uuid'])));
							
							else {
								$infractions_query = $queries->getWhere('uuid_cache', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));
								
								if(empty($infractions_query)){
									// Query Minecraft API to retrieve username
									$profile = ProfileUtils::getProfile(str_replace('-', '', $infraction["uuid"]));
									if(empty($profile)){
										// Couldn't find player
										
									} else {
										$result = $profile->getProfileAsArray();
											if(isset($result['username'])){
											$mcname = htmlspecialchars($result["username"]);
											$uuid = htmlspecialchars(str_replace('-', '', $infraction["uuid"]));
											try {
												$queries->create("uuid_cache", array(
													'mcname' => $mcname,
													'uuid' => $uuid
												));
											} catch(Exception $e){
												die($e->getMessage());
											}
										}
									}
								}
								$mcname = $queries->getWhere('uuid_cache', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));
								if(count($mcname))
									$mcname = $mcname[0]->mcname;
								else
									$mcname = 'Unknown';
							}
						} else {
							$mcname = $mcname[0]->BAT_player;
						}
					} else {
						$mcname = $infractions_query[0]->mcname;
					}
				}
			?>
		      <tr>
			    <td><a href="/profile/<?php echo htmlspecialchars($mcname); ?>"><?php echo htmlspecialchars($mcname); ?></a></td>
			    <td><?php if(strtolower($infraction["staff"]) !== "console"){?><a href="/profile/<?php echo htmlspecialchars($infraction["staff"]); ?>"><?php if($inf_plugin !== "mb"){ echo htmlspecialchars($infraction["staff"]); } else { echo htmlspecialchars($infractions->mb_getUsernameFromName($infraction["staff"])); }?></a><?php } else { echo 'Console'; } ?></td>
			    <td><?php echo $infraction["type_human"]; ?> <?php echo $infraction["expires_human"]; ?></td>
			    <td><?php echo htmlspecialchars($infraction["reason"]); ?></td>
			    <?php if($inf_plugin != 'bu') { ?><td><?php if(isset($infraction['issued'])){ ?><span rel="tooltip" data-placement="top" title="<?php echo $infraction["issued_human"]; ?>"><?php echo $timeago->inWords(date('d M Y, H:i', $infraction["issued"]), $time_language); ?></span><?php } else echo '-'; ?></td><?php } ?>
			    <td><a class="btn btn-primary btn-sm" href="/infractions/?type=<?php echo $infraction["type"]; ?>&amp;id=<?php echo $infraction["id"]; if(isset($infraction['past'])){ ?>&amp;past=true<?php } ?>"><?php echo $infractions_language['view']; ?></a></td>
		      </tr>
			<?php
				$n++;
			}
			?>
		    </tbody>
		  </table>
		</div>
			<?php
			$pagination->setCurrent($p);
			$pagination->setTotal(count($all_infractions));
			$pagination->alwaysShowPagination();
			
			echo $pagination->parse();
		} else {
			// Viewing infraction
			if(isset($_GET['type']) && $_GET["type"] !== "ban" && $_GET["type"] !== "kick" && $_GET["type"] !== "mute" && $_GET["type"] !== "temp_ban" && $_GET["type"] !== "warning" && $_GET['type'] !== 'temp_mute'){
				Redirect::to('/infractions');
				die();
			}
			
			if(!isset($_GET['id'])){
				Redirect::to('/infractions');
				die();
			}
			
			// Get infraction type
			switch($_GET['type']){
				case 'ban':
					$action = '<span class="label label-danger">' . $infractions_language['ban'] . '</span>';
				break;
				case 'temp_ban':
					$action = '<span class="label label-danger">' . $infractions_language['temp_ban'] . '</span>';
				break;
				case 'mute':
				case 'temp_mute':
					$action = '<span class="label label-warning">' . $infractions_language['mute'] . '</span>';
				break;
				case 'warning':
					$action = '<span class="label label-info">' . $infractions_language['warning'] . '</span>';
				break;
				case 'kick':
					$action = '<span class="label label-primary">' . $infractions_language['kick'] . '</span>';
				break;
			}
			
			// Get infraction info, depending on plugin
			switch($inf_plugin){
				case 'bat':
					$infraction = $infractions->bat_getInfraction($_GET["type"], $_GET["id"]);
					
					// Get username from UUID
					// First check if they're registered on the site
					$username = $queries->getWhere('users', array('uuid', '=', $infraction[0]->UUID));
					if(!count($username)){
						// Couldn't find, check UUID cache
						$username = $queries->getWhere('uuid_cache', array('uuid', '=', $infraction[0]->UUID));
						
						if(!count($username)){
							// Couldn't find, check BAT database
							$username = $infractions->bat_getUsernameFromUUID($infraction[0]->UUID);
							
							if(!count($username)){
								// Couldn't find, get and put into cache
								$profile = ProfileUtils::getProfile($infraction[0]->UUID);
								if(empty($profile)){
									echo 'Could not find that player, please try again later.';
									die();
								} else {
									// Enter into database
									$result = $profile->getProfileAsArray();
									$username = htmlspecialchars($result["username"]);
									$uuid = htmlspecialchars($infraction[0]->UUID);
									try {
										$queries->create("uuid_cache", array(
											'mcname' => $username,
											'uuid' => $uuid
										));
									} catch(Exception $e){
										die($e->getMessage());
									}
								}
								
							} else { 
								$username = htmlspecialchars($username[0]->BAT_player);
							}
						} else {
							$username = htmlspecialchars($username[0]->mcname);
						}
					} else {
						$username = htmlspecialchars($username[0]->mcname);
					}
					
					// Get date of infraction
					switch($_GET['type']){
						case 'ban':
						case 'temp_ban':
							$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->ban_begin)) . '">' . $timeago->inWords(date('d M Y, H:i', strtotime($infraction[0]->ban_begin)), $time_language) . '</span>';
							$staff = htmlspecialchars($infraction[0]->ban_staff);
							if($infraction[0]->ban_reason) $reason = htmlspecialchars($infraction[0]->ban_reason); else $reason = $infractions_language['no_reason']; 
						break;
						case 'mute':
							$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->mute_begin)) . '">' . $timeago->inWords(date('d M Y, H:i', strtotime($infraction[0]->mute_begin)), $time_language) . '</span>';
							$staff = htmlspecialchars($infraction[0]->mute_staff);
							if($infraction[0]->mute_reason) $reason = htmlspecialchars($infraction[0]->mute_reason); else $reason = $infractions_language['no_reason']; 
						break;
						case 'kick':
							$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->kick_date)) . '">' . $timeago->inWords(date('d M Y, H:i', strtotime($infraction[0]->kick_date)), $time_language) . '</span>';
							$staff = htmlspecialchars($infraction[0]->kick_staff);
							if($infraction[0]->kick_reason) $reason = htmlspecialchars($infraction[0]->kick_reason); else $reason = $infractions_language['no_reason']; 
						break;
					}
					
					// Expires/expired?
					switch($_GET['type']){
						case 'ban':
							if($infraction[0]->ban_unbandate){
								$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->ban_unbandate)) . '">' . str_replace('{x}', htmlspecialchars($infraction[0]->ban_unbanstaff), $infractions_language['revoked_by']) . '</span>';
							} else {
								$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
							}
						break;
						case 'temp_ban':
							if(strtotime($infraction[0]->ban_end) < date('U')){
								$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->ban_end)) . '">' . $infractions_language['expired'] . '</span>';
							} else {
								if($infraction[0]->ban_unbandate){
									$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->ban_unbandate)) . '">' . str_replace('{x}', htmlspecialchars($infraction[0]->ban_unbanstaff), $infractions_language['revoked_by']) . '</span>';
								} else {
									$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->ban_end)) . '">' . $infractions_language['active'] . '</span>';
								}
							}
						break;
						case 'mute':
							if(($infraction[0]->mute_end) && strtotime($infraction[0]->mute_end) < date('U')){
								$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->mute_end)) . '">' . $infractions_language['expired'] . '</span>';
							} else {
								if($infraction[0]->mute_unmutedate){
									$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->mute_unmutedate)) . '">' . str_replace('{x}', htmlspecialchars($infraction[0]->mute_unmutestaff), $infractions_language['revoked_by']) . '</span>';
								} else {
									if($infraction[0]->mute_end){
										$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction[0]->mute_end)) . '">' . $infractions_language['active'] . '</span>';
									} else {
										$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
									}
								}
							}
						break;
					}
					
				break;
				case 'bm':
					$infraction = $infractions->bm_getInfraction($_GET["type"], $_GET["id"], isset($_GET['past']) ? true : false);
					
					// Get username
					$username = $infractions->bm_getUsernameFromID($infraction[0]->player_id);
					$username = htmlspecialchars($username);
					
					// Get date of infraction
					if(!isset($_GET['past'])) $created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction[0]->created) . '">' . $timeago->inWords(date('d M Y, H:i', $infraction[0]->created), $time_language) . '</span>';
					else $created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction[0]->pastCreated) . '">' . $timeago->inWords(date('d M Y, H:i', $infraction[0]->pastCreated), $time_language) . '</span>';
					
					// Reason
					if($infraction[0]->reason) $reason = htmlspecialchars($infraction[0]->reason); else $reason = $infractions_language['no_reason']; 
					
					// Expires/expired?
					switch($_GET['type']){
						case 'ban':
						case 'temp_ban':
						case 'mute':
							// End of infraction
							if(isset($infraction[0]->expires)){
								// Not expired yet, or is permanent
								if($infraction[0]->expires != 0){
									// Will expire
									$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction[0]->expires) . '">' . $infractions_language['active'] . '</span>';
								} else {
									// Permanent
									$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
								}
							} else if(isset($infraction[0]->expired)){
								// Expired or unbanned
								if($infraction[0]->expired != 0){
									// Has expired
									$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction[0]->expired) . '">' . $infractions_language['expired'] . '</span>';
								} else {
									// Unbanned
									$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction[0]->ban_unbandate) . '">' . str_replace('{x}', htmlspecialchars($infraction[0]->ban_unbanstaff), $infractions_language['revoked_by']) . '</span>';
								}
							}
						break;
					}
					
					// Staff
					if(!isset($_GET['past'])) $staff = htmlspecialchars($infractions->bm_getUsernameFromID($infraction[0]->actor_id));
					else $staff = htmlspecialchars($infractions->bm_getUsernameFromID($infraction[0]->pastActor_id));
					
				break;
				case 'lb':
					$infraction = $infractions->lb_getInfraction($_GET["type"], $_GET["id"]);
					$username = htmlspecialchars($infraction[1]);
					$infraction = $infraction[0];
					
					switch($_GET['type']){
						case 'ban':
							if($infraction->active == null){
								$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
							} else {
								if($infraction->active == 0x01){
									// active
									$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
								} else {
									// revoked
									$expires = '<span class="label label-success">' . $infractions_language['revoked'] . '</span>';
								}
							}
						break;
						
						case 'temp_ban':
						case 'mute':
							if($infraction->active == null){
								// active
								$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->until / 1000)) . '">' . $infractions_language['active'] . '</span>';
							} else {
								if($infraction->active == 0x01){
									// active
									$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->until / 1000)) . '">' . $infractions_language['active'] . '</span>';
								} else {
									// revoked
									$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->until / 1000)) . '">' . $infractions_language['expired'] . '</span>';
								}
							}
						break;
					}
					
					$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', $infraction->time / 1000) . '">' . $timeago->inWords(date('d M Y, H:i', $infraction->time / 1000), $time_language) . '</span>';
					
					// Reason
					if($infraction->reason) $reason = htmlspecialchars($infraction->reason); else $reason = $infractions_language['no_reason']; 
					
					if($infraction->banned_by_uuid != "CONSOLE"){
						// Get username of staff from UUID
						$staff_uuid = str_replace('-', '', $infraction->banned_by_uuid);
						$infractions_query = $queries->getWhere('users', array('uuid', '=', htmlspecialchars($staff_uuid)));
						if(empty($infractions_query)){
							$infractions_query = $queries->getWhere('uuid_cache', array('uuid', '=', htmlspecialchars($staff_uuid)));
							if(empty($infractions_query)){
								$profile = ProfileUtils::getProfile($staff_uuid);
								if(empty($profile)){
									echo 'Could not find that player';
									die();
								}
								$result = $profile->getProfileAsArray();
								$staff = htmlspecialchars($result["username"]);
								$uuid = htmlspecialchars($staff_uuid);
								try {
									$queries->create("uuid_cache", array(
										'mcname' => $staff,
										'uuid' => $uuid
									));
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
							$staff = $queries->getWhere('uuid_cache', array('uuid', '=', $staff_uuid));
							$staff = htmlspecialchars($staff[0]->mcname);
						} else {
							$staff = $queries->getWhere('users', array('uuid', '=', $staff_uuid));
							$staff = htmlspecialchars($staff[0]->mcname);
						}
					} else {
						$staff = 'Console';
					}
				break;
				case 'bam':
					$infraction = $infractions->bam_getInfraction($_GET["type"], $_GET["id"]);
					
					// Get username
					$username = htmlspecialchars($infraction->name);
					
					// Reason
					if($infraction->cause) $reason = htmlspecialchars($infraction->cause); else $reason = $infractions_language['no_reason']; 
					
					// Expires/expired?
					switch($_GET['type']){
						case 'ban':
						case 'temp_ban':
							// Get date of infraction
							$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', strtotime($infraction->time)) . '">' . $timeago->inWords(date('d M Y, H:i', strtotime($infraction->time)), $time_language) . '</span>';
						break;
						case 'mute':
							if($infraction->time !== null){
								$expires = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", strtotime($infraction->time)), $infractions_language['expires_x']) . '">' . $infractions_language['active'] . '</span>';
							} else {
								$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
							}
						break;
					}
					
					// Staff
					$staff = htmlspecialchars($infraction->banner);
					
				break;
				case 'bu':
					$infraction = $infractions->bu_getInfraction($_GET["type"], $_GET["id"]);
					
					// Get username
					if($_GET['type'] == 'ban' || $_GET['type'] == 'temp_ban') $uuid = str_replace('-', '', htmlspecialchars($infraction->Banned));
					else $uuid = str_replace('-', '', htmlspecialchars($infraction->Muted));
					$infractions_query = $queries->getWhere('users', array('uuid', '=', $uuid));
					if(empty($infractions_query)){
						$infractions_query = $queries->getWhere('uuid_cache', array('uuid', '=', $uuid));
						if(empty($infractions_query)){
							$profile = ProfileUtils::getProfile($uuid);
							if(empty($profile)){
								echo 'Could not find that player';
								die();
							} else {
								$result = $profile->getProfileAsArray();
								$username = htmlspecialchars($result["username"]);
								try {
									$queries->create("uuid_cache", array(
										'mcname' => $username,
										'uuid' => $uuid
									));
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
						}
						$username = $queries->getWhere('uuid_cache', array('uuid', '=', $uuid));
						$username = htmlspecialchars($username[0]->mcname);
					} else {
						$username = htmlspecialchars($infractions_query[0]->mcname);
					}
					
					// Reason
					if($infraction->Reason) $reason = htmlspecialchars($infraction->Reason); else $reason = $infractions_language['no_reason']; 
					
					// Expires/expired?
					switch($_GET['type']){
						case 'ban':
						case 'temp_ban':
							if($infraction->BanTime !== '-1'){
								$expires = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", ($infraction->BanTime / 1000)), $infractions_language['expires_x']) . '">' . $infractions_language['active'] . '</span>';
							} else {
								$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
							}
							
							$staff = htmlspecialchars($infraction->BannedBy);
						break;
						case 'mute':
						case 'temp_mute':
							if($infraction->MuteTime !== '-1'){
								$expires = '<span class="label label-danger" rel="tooltip" data-trigger="hover" data-original-title="' . str_replace('{x}', date("jS M Y", ($infraction->MuteTime / 1000)), $infractions_language['expires_x']) . '">' . $infractions_language['active'] . '</span>';
							} else {
								$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
							}
							
							$staff = htmlspecialchars($infraction->MutedBy);
						break;
					}
					
				break;
				case 'ab':
					// Advanced Ban
					$infraction = $infractions->ab_getInfraction($_GET['id']);
					
					// Username
					$uuid = htmlspecialchars($infraction->uuid);
					$infractions_query = $queries->getWhere('users', array('uuid', '=', $uuid));
					if(empty($infractions_query)){
						$infractions_query = $queries->getWhere('uuid_cache', array('uuid', '=', $uuid));
						if(empty($infractions_query)){
							$profile = ProfileUtils::getProfile($uuid);
							if(empty($profile)){
								$username = 'Unknown';
							} else {
								$result = $profile->getProfileAsArray();
								$username = htmlspecialchars($result["username"]);
								try {
									$queries->create("uuid_cache", array(
										'mcname' => $username,
										'uuid' => $uuid
									));
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
						}
						$username = $queries->getWhere('uuid_cache', array('uuid', '=', $uuid));
						if(count($username))
							$username = htmlspecialchars($username[0]->mcname);
						else
							$username = 'Unknown';
					} else {
						$username = htmlspecialchars($infractions_query[0]->mcname);
					}
					
					// Staff
					$staff = htmlspecialchars($infraction->operator);
					
					// Date
					$created = '<span rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->start / 1000)) . '">' . $timeago->inWords(date('d M Y, H:i', ($infraction->start / 1000)), $time_language) . '</span>';
					
					// Expires/expired?
					switch($_GET['type']){
						case 'ban':
						case 'mute':
							$expires = '<span class="label label-danger">' . $infractions_language['permanent'] . '</span>';
						break;
						case 'temp_ban':
						case 'temp_mute':
							if(($infraction->end / 1000) < date('U')){
								$expires = '<span class="label label-success" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->end / 1000)) . '">' . $infractions_language['expired'] . '</span>';
							} else {
								$expires = '<span class="label label-danger" rel="tooltip" data-placement="top" title="' . date('jS M Y, H:i', ($infraction->end / 1000)) . '">' . $infractions_language['active'] . '</span>';
							}
						break;
					}
					
					// Reason
					if($infraction->reason)
						$reason = htmlspecialchars($infraction->reason);
					else
						$reason = 'n/a';

				break;
			}
			
			if(!isset($infraction) || !isset($username)){
				echo '<script data-cfasync="false">window.location.replace("/infractions");</script>';
				die();
			}
			?>
		  <hr />
		  <h4 style="display:inline;"><?php echo $infractions_language['viewing_infraction']; ?></h4>
		  <span class="pull-right"><a class="btn btn-primary" href="/infractions"><?php echo $general_language['back']; ?></a></span>
		  <br /><br />
		  <?php echo $infractions_language['user'] . ' <strong><a href="/profile/' . $username . '">' . $username . '</a></strong>'; ?><br />
		  <?php echo $infractions_language['staff_member'] . ': ' . (strtolower($staff) == 'console' ? $staff : '<a href="/profile/' . $staff . '">' . $staff . '</a>'); ?><br />
		  <?php echo $infractions_language['action'] . ': <strong>' . $action . '</strong>'; ?><br />
		  <?php if(isset($created)) echo $infractions_language['created'] . ': ' . $created . '<br />'; ?>
		  <?php if(isset($expires)) echo $infractions_language['status'] . ' ' . $expires . '<br />'; ?>
		  <?php echo $infractions_language['reason'] . ': '; ?><pre><?php echo $reason; ?></pre><br />
		  <hr />
		  <?php
		}
		?>
	  </div>
    </div>
	
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>

  </body>
</html>
