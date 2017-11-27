<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}
 
// Set page name for sidebar
$adm_page = "minecraft";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['minecraft'];
	
	require('core/includes/template/generate.php');
	?>
	
	<link href="/core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">	
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>
  <body>
	<?php
	// Minecraft page
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
    <div class="container">
	  <br />
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <ul class="nav nav-pills">
			<li<?php if(!isset($_GET['view'])){ ?> class="active"<?php } ?>><a href="/admin/minecraft"><?php echo $admin_language['settings']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'servers'){ ?> class="active"<?php } ?>><a href="/admin/minecraft/?view=servers"><?php echo $admin_language['servers']; ?></a></li>
		    <li<?php if(isset($_GET['view']) && $_GET['view'] == 'errors'){ ?> class="active"<?php } ?>><a href="/admin/minecraft/?view=errors"><?php echo $admin_language['query_errors']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'mcassoc'){ ?> class="active"<?php } ?>><a href="/admin/minecraft/?view=mcassoc"><?php echo $admin_language['mcassoc']; ?></a></li>
		  </ul>
		  <hr>
		  <div class="well">
		    <?php if(!isset($_GET['settings']) && !isset($_GET['view'])){ ?>
			<h3><?php echo $admin_language['minecraft_settings']; ?></h3>
			<?php
			// Deal with input
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					if(Input::get('uuids') == 'on'){
						$uuids = 1;
					} else {
						$uuids = 0;
					}
					if(Input::get('avatars') == 'on'){
						$avatars = 0;
					} else {
						$avatars = 1;
					}
					if(Input::get('plugin') == 'on'){
						$plugin = 1;
					} else {
						$plugin = 0;
					}
					if(Input::get('status_module') == 'on'){
						$status_module = 'true';
					} else {
						$status_module = 'false';
					}
					if(Input::get('usernames') == 'on'){
						$usernames = 'false';
					} else {
						$usernames = 'true';
					}
					if(Input::get('name_history') == 'on'){
						$name_history = 1;
					} else {
						$name_history = 0;
					}
					
					// Update values
					$uuids_id = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
					$uuids_id = $uuids_id[0]->id;
					$queries->update('settings', $uuids_id, array(
						'value' => $uuids
					));
					
					$avatars_id = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
					$avatars_id = $avatars_id[0]->id;
					$queries->update('settings', $avatars_id, array(
						'value' => $avatars
					));
					
					$plugin_id = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
					$plugin_id = $plugin_id[0]->id;
					$queries->update('settings', $plugin_id, array(
						'value' => $plugin
					));
					
					$status_id = $queries->getWhere('settings', array('name', '=', 'mc_status_module'));
					$status_id = $status_id[0]->id;
					$queries->update('settings', $status_id, array(
						'value' => $status_module
					));
					
					$username_id = $queries->getWhere('settings', array('name', '=', 'displaynames'));
					$username_id = $username_id[0]->id;
					$queries->update('settings', $username_id, array(
						'value' => $usernames
					));
					
					$avatar_id = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
					$avatar_id = $avatar_id[0]->id;
					$queries->update('settings', $avatar_id, array(
						'value' => htmlspecialchars(Input::get('avatar_type'))
					));
					
					$name_history_id = $queries->getWhere('settings', array('name', '=', 'enable_name_history'));
					$name_history_id = $name_history_id[0]->id;
					$queries->update('settings', $name_history_id, array(
						'value' => $name_history
					));
					
				} else {
					// Invalid token
					Session::flash('mc_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
				}
			}
			
			// Get settings
			$use_plugin = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
			$user_avatars = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
			$link_uuids = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
			$server_status = $queries->getWhere('settings', array('name', '=', 'mc_status_module'));
			$usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
			$avatar_type = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
			$name_history = $queries->getWhere('settings', array('name', '=', 'enable_name_history'));
			
			if(Session::exists('mc_settings')){
				echo Session::flash('mc_settings');
			}
			?>
			<form action="" method="post">
			  <div class="row">
			    <div class="col-md-5">
				  <div class="form-group">
					<label for="plugin"><?php echo $admin_language['use_plugin']; ?></label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="<?php echo $admin_language['use_plugin_help']; ?>"><i class="fa fa-question-circle"></i></a>
					<span class="pull-right">
					  <input id="plugin" name="plugin" type="checkbox" class="js-switch" <?php if($use_plugin[0]->value == '1'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
					<label for="avatars"><?php echo $admin_language['force_avatars']; ?></label>
					<span class="pull-right">
					  <input id="avatars" name="avatars" type="checkbox" class="js-switch" <?php if($user_avatars[0]->value == '0'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
					<label for="uuids"><?php echo $admin_language['uuid_linking']; ?></label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="<?php echo $admin_language['uuid_linking_help']; ?>"><i class="fa fa-question-circle"></i></a>
					<span class="pull-right">
					  <input id="uuids" name="uuids" type="checkbox" class="js-switch" <?php if($link_uuids[0]->value == '1'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
				    <label for="status_module"><?php echo $admin_language['display_server_status']; ?></label>
					<span class="pull-right">
					  <input id="status_module" name="status_module" type="checkbox" class="js-switch" <?php if($server_status[0]->value == 'true'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
				    <label for="usernames"><?php echo $admin_language['custom_usernames']; ?></label>
					<span class="pull-right">
					  <input id="usernames" name="usernames" type="checkbox" class="js-switch" <?php if($usernames[0]->value == 'false'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
				    <label for="name_history"><?php echo $admin_language['enable_name_history']; ?></label>
					<span class="pull-right">
					  <input id="name_history" name="name_history" type="checkbox" class="js-switch" <?php if($name_history[0]->value == '1'){ ?>checked <?php } ?>/>
					</span>
				  </div>
				  <div class="form-group">
			        <label for="avatar_type"><?php echo $admin_language['avatar_type']; ?></label>
				    <select id="avatar_type" name="avatar_type" class="form-control">
					  <option value="helmavatar"<?php if($avatar_type[0]->value == 'helmavatar') echo ' selected'; ?>>helmavatar</option>
					  <option value="avatar"<?php if($avatar_type[0]->value == 'avatar') echo ' selected'; ?>>avatar</option>
					</select>
				  </div>
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			    </div>
				<div class="col-md-7">
				  <a href="/admin/minecraft/?settings=plugin" class="btn btn-info btn-xs"><?php echo $admin_language['settings']; ?></a>
				</div>
			  </div>
			</form>
			<?php } else if(isset($_GET['settings']) && !isset($_GET['view']) && $_GET['settings'] == 'plugin' && !isset($_GET['action'])){ ?>
			<h3><?php echo $admin_language['plugin_settings']; ?></h3>
			<?php
			// Get current plugin API key
			$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
			?>
			<form action="" method="post">
			  <div class="form-group">
			    <label for="InputAPIKey">API Key</label>
			    <div class="input-group">
				  <input type="text" name="api_key" id="InputAPIKey" class="form-control" readonly value="<?php echo htmlspecialchars($plugin_api[0]->value); ?>">
				  <span class="input-group-btn"><a href="/admin/minecraft/?settings=plugin&amp;action=api_regen" onclick="return confirm('<?php echo $admin_language['confirm_api_regen']; ?>');" class="btn btn-info">Change</span></a>
				</div>
			  </div>
			  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			</form>
			<?php 
			} else if(isset($_GET['settings']) && !isset($_GET['view']) && $_GET['settings'] == 'plugin' && isset($_GET['action']) && $_GET['action'] == 'api_regen'){
				// Generate new key
				$new_api_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
				
				$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
				$plugin_api = $plugin_api[0]->id;
				
				// Update key
				$queries->update('settings', $plugin_api, array(
					'value' => $new_api_key
				));
				
				// Cache
				file_put_contents('cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);
				
				// Redirect
				echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?settings=plugin");</script>';
				die();
			} else if(!isset($_GET['settings']) && isset($_GET['view']) && $_GET['view'] == 'servers'){ 
				if(!isset($_GET["action"]) && !isset($_GET["sid"])){ 
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							$current_default = $queries->getWhere("mc_servers", array("is_default", "=", 1));
							try {
								if(count($current_default)){
									$queries->update("mc_servers", $current_default[0]->id, array(
										'is_default' => 0
									));
								}
								$queries->update("mc_servers", Input::get('main'), array(
									'is_default' => 1
								));
								if(Input::get('external') == '0'){
									$external_query = 'false';
								} else {
									$external_query = 'true';
								}
								
								$external_query_id = $queries->getWhere('settings', array('name', '=', 'external_query'));
								$external_query_id = $external_query_id[0]->id;
								
								$queries->update('settings', $external_query_id, array(
									'value' => $external_query
								));
								echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=servers");</script>';
								die();
							} catch(Exception $e){
								die($e->getMessage());
							}
						} else {
							echo $admin_language['invalid_token'] . ' - <a href="/admin/minecraft/?view=servers">' . $general_language['back'] . '</a>';
							die();
						}
					}
				?>
				<h3><?php echo $admin_language['servers']; ?></h3>
				<a href="/admin/minecraft/?view=servers&amp;action=new" class="btn btn-default"><?php echo $admin_language['new_server']; ?></a>
				<br /><br />
				<?php 
				if(Session::exists('admin_servers')){
					echo Session::flash('admin_servers');
				} 
				?>
				<div class="panel panel-info">
					<div class="panel-heading"><?php echo $admin_language['servers']; ?></div>
					<div class="panel-body">
						<?php 
						$servers = $queries->getWhere("mc_servers", array("id", "<>", 0));
						$number = count($servers);
						$i = 1;
						
						foreach($servers as $server){
						?>
						<div class="row">
							<div class="col-md-6">
								<a href="/admin/minecraft/?view=servers&amp;sid=<?php echo $server->id; ?>"><?php echo htmlspecialchars($server->name) . '</a><br />' . htmlspecialchars($server->ip); ?>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a onclick="return confirm('<?php echo $admin_language['confirm_server_deletion']; ?>');" href="/admin/minecraft/?view=servers&amp;action=delete_server&amp;sid=<?php echo $server->id; ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
								</span>
							</div>
						</div>
						<hr>
						<?php 
						}
						?>
					</div>
				</div>
				<form action="" method="post">
				  <div class="form-group">
					 <label for="InputMain"><?php echo $admin_language['main_server']; ?> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['main_server_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a></label>
					 <select class="form-control" id="InputMain" name="main">
					<?php 
					$default_server = false;
					foreach($servers as $server){
					?>
					  <option value="<?php echo $server->id; ?>" <?php if($server->is_default == 1){ echo 'selected="selected"'; $default_server = true; } ?>><?php echo htmlspecialchars($server->name); ?></option>
					<?php 
					} 
					if($default_server === false){
					?>
					  <option selected disabled><?php echo $admin_language['choose_a_main_server']; ?></option>
					<?php 
					}
					?>
					</select> 
				  </div>
				  <?php
					// value for external query
					$external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
					$external_query = $external_query[0]->value;
				  ?>
				  <label for="external_query"><?php echo $admin_language['external_query']; ?></label>
				  <input type="hidden" name="external" value="0">
				  <input name="external" value="1" id="external_query" type="checkbox"<?php if($external_query === "true"){ echo ' checked'; } ?>>
				  <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['external_query_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				  <br /><br />
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-default">
				</form>
				<?php 
				} else if(isset($_GET["sid"]) && !isset($_GET["action"])) { 
					$server = $queries->getWhere("mc_servers", array("id", "=", $_GET["sid"]));
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
						$validate = new Validate();
						$validation = $validate->check($_POST, array(
							'servername' => array(
								'required' => true,
								'min' => 2,
								'max' => 20
							),
							'serverip' => array(
								'required' => true,
								'min' => 2,
								'max' => 64
							),
							'queryip' => array(
								'required' => true,
								'min' => 2,
								'max' => 64
							)							
						));
						
						if($validation->passed()){
							try {
								$queries->update("mc_servers", $_GET["sid"], array(
									'ip' => htmlspecialchars(Input::get('serverip')),
									'name' => htmlspecialchars(Input::get('servername')),
									'display' => Input::get('display'),
									'pre' => Input::get('pre'),
									'player_list' => Input::get('show_players'),
									'query_ip' => htmlspecialchars(Input::get('queryip'))
								));
								
								Session::flash('admin_servers', '<div class="alert alert-info">' . $admin_language['server_edited'] . '</div>');
								echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=servers");</script>';
								die();
							} catch(Exception $e){
								die($e->getMessage());
							}
						}
						} else {
							echo $admin_language['invalid_token'] . ' - <a href="/admin/minecraft/?view=servers">' . $general_language['back'] . '</a>';
							die();
						}						
					}
					if(isset($validation)){
						if(!$validation->passed()){
					?>
					<div class="alert alert-danger">
						<?php
						foreach($validation->errors() as $error) {
						    if(strpos($error, 'is required') !== false){
								switch($error){
									case (strpos($error, 'servername') !== false):
										echo $admin_language['server_name_required'] . '<br />';
									break;
									case (strpos($error, 'ip') !== false):
										echo $admin_language['server_ip_required'] . '<br />';
									break;
								}
							} else if(strpos($error, 'minimum') !== false){
								switch($error){
									case (strpos($error, 'servername') !== false):
										echo $admin_language['server_name_minimum'] . '<br />';
									break;
									case (strpos($error, 'ip') !== false):
										echo $admin_language['server_ip_minimum'] . '<br />';
									break;
								}
							} else if(strpos($error, 'maximum') !== false){
								switch($error){
									case (strpos($error, 'servername') !== false):
										echo $admin_language['server_name_maximum'] . '<br />';
									break;
									case (strpos($error, 'ip') !== false):
										echo $admin_language['server_ip_maximum'] . '<br />';
									break;
								}
							}
						}
						?>
					</div>
					<?php 
						}
					}
					?>
					<h2><?php echo str_replace('{x}', htmlspecialchars($server[0]->name), $admin_language['editing_server']); ?></h2>
					<form action="" method="post">
						<div class="form-group">
							<label for="servername"><?php echo $admin_language['server_name']; ?></label>
							<input class="form-control" type="text" name="servername" id="servername" value="<?php echo htmlspecialchars($server[0]->name); ?>" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="serverip"><?php echo $admin_language['server_ip_with_port']; ?> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['server_ip_with_port_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a></label>
							<input class="form-control" type="text" name="serverip" id="serverip" value="<?php echo htmlspecialchars($server[0]->ip); ?>" placeholder="<?php echo $admin_language['server_ip_with_port']; ?>" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="queryip"><?php echo $admin_language['server_ip_numeric']; ?> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['server_ip_numeric_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a></label>
							<input class="form-control" type="text" name="queryip" id="queryip" value="<?php echo htmlspecialchars($server[0]->query_ip); ?>" placeholder="<?php echo $admin_language['server_ip_numeric']; ?>" autocomplete="off">
						</div>
						<input type="hidden" name="display" value="0" />
						<label for="InputDisplay"><?php echo $admin_language['show_on_play_page']; ?></label>
						<input name="display" id="InputDisplay" value="1" type="checkbox"<?php if($server[0]->display == 1){ echo ' checked'; } ?>>
						<br /><br />
						<input type="hidden" name="pre" value="0" />
						<label for="InputPre"><?php echo $admin_language['pre_17']; ?></label>
						<input name="pre" id="InputPre" value="1" type="checkbox"<?php if($server[0]->pre == 1){ echo ' checked'; } ?>>
						<br /><br />
						<input type="hidden" name="show_players" value="0" />
						<label for="InputShowPlayers"><?php echo $admin_language['show_players']; ?></label>
						<input name="show_players" id="InputShowPlayers" value="1" type="checkbox"<?php if($server[0]->player_list == 1){ echo ' checked'; } ?>>
						<br /><br />
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						<input class="btn btn-success" type="submit" value="<?php echo $general_language['submit']; ?>">
					</form>
				<?php 
				} else if(isset($_GET["action"])) { 
					if($_GET["action"] === "new"){
						if(Input::exists()) {
							if(Token::check(Input::get('token'))) {
							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'servername' => array(
									'required' => true,
									'min' => 2,
									'max' => 20
								),
								'serverip' => array(
									'required' => true,
									'min' => 2,
									'max' => 64
								),
								'queryip' => array(
									'required' => true,
									'min' => 2,
									'max' => 64
								)
							));
							
							if($validation->passed()){
								try {
									$queries->create("mc_servers", array(
										'ip' => htmlspecialchars(Input::get('serverip')),
										'name' => htmlspecialchars(Input::get('servername')),
										'display' => Input::get('display'),
										'pre' => Input::get('pre'),
										'player_list' => Input::get('show_players'),
										'query_ip' => htmlspecialchars(Input::get('queryip'))
									));
									Session::flash('admin_servers', '<div class="alert alert-info">' . $admin_language['server_created'] . '</div>');
									echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=servers");</script>';
									die();
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
							} else {
								echo $admin_language['invalid_token'];
								die();
							}						
						}
						if(isset($validation)){
							if(!$validation->passed()){
						?>
						<div class="alert alert-danger">
							<?php
							foreach($validation->errors() as $error) {
								if(strpos($error, 'is required') !== false){
									switch($error){
										case (strpos($error, 'servername') !== false):
											echo $admin_language['server_name_required'] . '<br />';
										break;
										case (strpos($error, 'ip') !== false):
											echo $admin_language['server_ip_required'] . '<br />';
										break;
									}
								} else if(strpos($error, 'minimum') !== false){
									switch($error){
										case (strpos($error, 'servername') !== false):
											echo $admin_language['server_name_minimum'] . '<br />';
										break;
										case (strpos($error, 'ip') !== false):
											echo $admin_language['server_ip_minimum'] . '<br />';
										break;
									}
								} else if(strpos($error, 'maximum') !== false){
									switch($error){
										case (strpos($error, 'servername') !== false):
											echo $admin_language['server_name_maximum'] . '<br />';
										break;
										case (strpos($error, 'ip') !== false):
											echo $admin_language['server_ip_maximum'] . '<br />';
										break;
									}
								}
							}
							?>
						</div>
						<?php 
							}
						}
						?>
						<form action="" method="post">
							<h2><?php echo $admin_language['new_server']; ?></h2>
							<div class="form-group">
								<label for="servername"><?php echo $admin_language['server_name']; ?></label>
								<input class="form-control" type="text" name="servername" id="servername" value="<?php echo htmlspecialchars(Input::get('servername')); ?>" placeholder="<?php echo $admin_language['server_name']; ?>" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="serverip"><?php echo $admin_language['server_ip_with_port']; ?> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['server_ip_with_port_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a></label>
								<input class="form-control" type="text" name="serverip" id="serverip" value="<?php echo htmlspecialchars(Input::get('serverip')); ?>" placeholder="<?php echo $admin_language['server_ip_with_port']; ?>" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="queryip"><?php echo $admin_language['server_ip_numeric']; ?> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['server_ip_numeric_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a></label>
								<input class="form-control" type="text" name="queryip" id="queryip" value="<?php echo htmlspecialchars(Input::get('queryip')); ?>" placeholder="<?php echo $admin_language['server_ip_numeric']; ?>" autocomplete="off">
							</div>
							<input type="hidden" name="display" value="0" />
							<label for="InputDisplay"><?php echo $admin_language['show_on_play_page']; ?></label>
							<input name="display" id="InputDisplay" value="1" type="checkbox">
							<br />
							<input type="hidden" name="pre" value="0" />
							<label for="InputPre"><?php echo $admin_language['pre_17']; ?></label>
							<input name="pre" id="InputPre" value="1" type="checkbox">
							<br />
							<input type="hidden" name="show_players" value="0" />
							<label for="InputShowPlayers"><?php echo $admin_language['show_players']; ?></label>
							<input name="show_players" id="InputShowPlayers" value="1" type="checkbox">
							<br /><br />
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input class="btn btn-success" type="submit" value="<?php echo $general_language['submit']; ?>">
						</form>
				<?php
					 } else if($_GET["action"] === "delete_server"){
						if(!isset($_GET["sid"]) || !is_numeric($_GET["sid"])){		
							echo $admin_language['invalid_server_id'] . ' - <a href="/admin/minecraft/?view=servers">' . $general_language['back'] . '</a>';
							die();
						}
						$server_id = $_GET["sid"];
						try {
							$queries->delete('mc_servers', array('id', '=', $server_id));
							echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=servers");</script>';
							die();
						} catch(Exception $e) {
							die($e->getMessage());
						}
					 }
				}
			} else if(!isset($_GET['settings']) && isset($_GET['view']) && $_GET['view'] == 'errors'){
				if(!isset($_GET['error'])){
					if(isset($_GET['action']) && $_GET['action'] == 'purge'){
						// Purge all errors
						$queries->delete('query_errors', array('id', '<>', 0));
						echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=errors");</script>';
						die();
					}
			?>
			<br />
			<h3 style="display:inline;"><?php echo $admin_language['query_errors']; ?></h3>
			<span class="pull-right"><a href="/admin/minecraft/?view=errors&amp;action=purge" class="btn btn-danger" onclick="return confirm('<?php echo $admin_language['confirm_purge_errors']; ?>');"><?php echo $admin_language['purge_errors']; ?></a></span>
			<br /><br />
			<p><?php echo $admin_language['query_errors_info']; ?></p>
				<?php
					$query_errors = $queries->orderWhere('query_errors', 'id <> 0', 'DATE', 'DESC');
					if(count($query_errors)){
				?>
			<table class="table table-striped table-bordered table-hover dataTables-errors" >
			  <thead>
				<tr>
				  <th><?php echo str_replace(':', '', $admin_language['ip']); ?></th>
				  <th><?php echo str_replace(':', '', $admin_language['port']); ?></th>
				  <th><?php echo str_replace(':', '', $admin_language['date']); ?></th>
				  <th></th>
				</tr>
			  </thead>
			  <tbody>
				<?php
						foreach($query_errors as $query_error){
				?>
				<tr>
				  <td><?php echo htmlspecialchars($query_error->ip); ?></td>
				  <td><?php echo htmlspecialchars($query_error->port); ?></td>
				  <td><?php echo date('d M Y, G:i', $query_error->date); ?></td>
				  <td><a href="/admin/minecraft/?view=errors&amp;error=<?php echo $query_error->id; ?>" class="btn btn-primary btn-sm"><?php echo $user_language['view']; ?></a></td>
				</tr>
				<?php
						}
				?>
			  </tbody>
			</table>
				<?php
					} else {
						echo $admin_language['no_query_errors'];
					}
				} else {
					if(!is_numeric($_GET['error'])){
						// Not a valid error ID
						echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=errors");</script>';
						die();
					}
					// Check the error actually exists
					$query_error = $queries->getWhere('query_errors', array('id', '=', $_GET['error']));
					if(!count($query_error)){
						echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=errors");</script>';
						die();
					}
					// Error exists
					if(isset($_GET['action']) && $_GET['action'] == 'delete'){
						// Delete
						$queries->delete('query_errors', array('id', '=', $_GET['error']));
						echo '<script data-cfasync="false">window.location.replace("/admin/minecraft/?view=errors");</script>';
						die();
						
					} else {
						// Display error
						$query_error = $query_error[0];
						echo '<h3 style="display:inline;">' . $admin_language['viewing_error'] . '</h3>';
						echo '<span class="pull-right"><a onclick="return confirm(\'' . $admin_language['confirm_error_deletion'] . '\');" href="/admin/minecraft/?view=errors&amp;action=delete&amp;error=' . $query_error->id . '" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a></span>';
						echo '<br /><br />';
						echo $admin_language['ip'] . ' ' . htmlspecialchars($query_error->ip) . '<br />';
						echo $admin_language['port'] . ' ' . htmlspecialchars($query_error->port) . '<br />';
						echo $admin_language['date'] . ' ' . date('d M Y, G:i', $query_error->date) . '<br /><br />';
						echo '<div class="panel panel-danger"><div class="panel-body"><p>' . htmlspecialchars($query_error->error) . '</p></div></div>';
					}
				}
			} else if(!isset($_GET['settings']) && isset($_GET['view']) && $_GET['view'] == 'mcassoc'){
				if(Input::exists()){
					// Check token
					if(Token::check(Input::get('token'))){
						// Validate input
						$validate = new Validate();
						$validation = $validate->check($_POST, array(
							'mcassoc_key' => array(
								'max' => 128
							),
							'mcassoc_instance' => array(
								'max' => 32
							)
						));
						
						if($validation->passed()){
							// Update database
							if(Input::get('use_mcassoc') == 'on'){
								$use_mcassoc = 1;
							} else {
								$use_mcassoc = 0;
							}
							
							$use_mcassoc_id = $queries->getWhere('settings', array('name', '=', 'use_mcassoc'));
							$queries->update('settings', $use_mcassoc_id[0]->id, array(
								'value' => $use_mcassoc
							));
							
							$mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
							$queries->update('settings', $mcassoc_key[0]->id, array(
								'value' => htmlspecialchars(Input::get('mcassoc_key'))
							));

							$mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
							$queries->update('settings', $mcassoc_instance[0]->id, array(
								'value' => htmlspecialchars(Input::get('mcassoc_instance'))
							));
							
						} else {
							// Invalid key
							$message = '<div class="alert alert-danger">' . $admin_language['invalid_mcassoc_key'] . '</div>';
						}
						
					} else {
						// Invalid token
						$message = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
					}
				}
				
				// Get mcassoc settings
				$use_mcassoc = $queries->getWhere('settings', array('name', '=', 'use_mcassoc'));
				$use_mcassoc = $use_mcassoc[0]->value;
				
				$mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
				$mcassoc_key = htmlspecialchars($mcassoc_key[0]->value);
				
				$mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
				$mcassoc_instance = htmlspecialchars($mcassoc_instance[0]->value);
				
			?>
			<h3><?php echo $admin_language['mcassoc']; ?></h3>
			<?php if(isset($message)) echo $message; ?>
			<form action="" method="post">
			  <div class="form-group">
			    <div class="row">
			      <div class="col-md-5">
				    <div class="form-group">
				  	  <label for="use_mcassoc"><?php echo $admin_language['use_mcassoc']; ?></label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="<?php echo $admin_language['use_mcassoc_help']; ?>"><i class="fa fa-question-circle"></i></a>
					  <span class="pull-right">
					    <input id="use_mcassoc" name="use_mcassoc" type="checkbox" class="js-switch" <?php if($use_mcassoc == '1'){ ?>checked <?php } ?>/>
					  </span>
				    </div>
				  </div>
			    </div>
				<div class="form-group">
				  <label for="mcassoc_key"><?php echo $admin_language['mcassoc_key']; ?></label>
				  <input type="text" class="form-control" name="mcassoc_key" id="mcassoc_key" value="<?php echo $mcassoc_key; ?>" placeholder="<?php echo $admin_language['mcassoc_key']; ?>">
				</div>
				<div class="form-group">
				  <label for="mcassoc_instance"><?php echo $admin_language['mcassoc_instance']; ?></label>
				  <input type="text" class="form-control" name="mcassoc_instance" id="mcassoc_instance" value="<?php echo $mcassoc_instance; ?>" placeholder="<?php echo $admin_language['mcassoc_instance']; ?>">
				  <p><?php echo $admin_language['mcassoc_instance_help']; ?></p>
				</div>
				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
				</div>
			  </div>
			</form>
			<?php
			}
			?>
		  </div>
		</div>
	  </div>
	</div>
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});
	</script>
	<script src="/core/assets/js/tables/jquery.dataTables.min.js"></script>
	<script src="/core/assets/js/tables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTables-errors').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $table_language['display_records_per_page']; ?>",
					"zeroRecords": "<?php echo $table_language['nothing_found']; ?>",
					"info": "<?php echo $table_language['page_x_of_y']; ?>",
					"infoEmpty": "<?php echo $table_language['no_records']; ?>",
					"infoFiltered": "<?php echo $table_language['filtered']; ?>",
					"search": "<?php echo $general_language['search']; ?> "
				},
				bFilter: false
            });
		});
	</script>
  </body>
</html>
