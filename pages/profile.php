<?php
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
require_once('core/integration/uuid.php'); // For UUID stuff
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

$profile_user = $queries->getWhere("users", array("username", "=", $profile)); // Is it their username?
if(!count($profile_user)){ // No..
	$profile_user = $queries->getWhere("users", array("mcname", "=", $profile)); // Is it their Minecraft username?
	if(!count($profile_user)){ // No..
		$exists = false;
		$uuid = $queries->getWhere("uuid_cache", array("mcname", "=", $profile)); // Get the UUID, maybe they haven't registered yet
		if(!count($uuid)){
			if($uuid_linking == '1'){ // is UUID linking enabled?
				$profile_utils = ProfileUtils::getProfile($profile);
				
				if($profile_utils == null){ // Not a Minecraft user, end the page
					Redirect::to('/404');
					die();
				}
				
				// Get results as array
				$result = $profile_utils->getProfileAsArray(); 
				
				if(empty($result['uuid'])){ // Not a Minecraft user, end the page
					Redirect::to('/404');
					die();
					
				}
				
				$uuid = $result["uuid"];
				$mcname = htmlspecialchars($profile);
				// Cache the UUID so we don't have to keep looking it up via Mojang's servers
				try {
					$queries->create("uuid_cache", array(
						'mcname' => $mcname,
						'uuid' => $uuid
					));
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else {
				$mcname = htmlspecialchars($profile);
			}
		} else {
			$uuid = $uuid[0]->uuid;
			$mcname = htmlspecialchars($profile);
		}
	} else {
		$exists = true;
		$uuid = htmlspecialchars($profile_user[0]->uuid);
		$mcname = htmlspecialchars($profile_user[0]->mcname);
	}
} else {
	$exists = true;
	$uuid = htmlspecialchars($profile_user[0]->uuid);
	$mcname = htmlspecialchars($profile_user[0]->mcname);
}

if($user->isLoggedIn()){
	if(isset($_POST['AddFriend'])) {
		if(Token::check(Input::get('token'))){
			$user->addfriend($user->data()->id, $profile_user[0]->id);
		}
	}
	if(isset($_POST['RemoveFriend'])){
		if(Token::check(Input::get('token'))){
			$user->removefriend($user->data()->id, $profile_user[0]->id);
		}
	}
	$token = Token::generate();
}

$servers = $queries->getWhere("mc_servers", array("display", "=", "1"));

// Are we using the built-in query or an external API?
$query_to_use = $queries->getWhere('settings', array('name', '=', 'external_query'));
$query_to_use = $query_to_use[0]->value;

if($query_to_use == 'false'){
	define( 'MQ_TIMEOUT', 1 );
	require('core/integration/status/MinecraftServerPing.php');
	require('core/integration/status/server.php');
	$serverStatus = new ServerStatus();
	foreach($servers as $server){
		$parts = explode(':', $server->ip);
		if(count($parts) == 1){
			$server_ip = htmlspecialchars($parts[0]);
			$server_port = 25565;
		} else if(count($parts) == 2){
			$server_ip = htmlspecialchars($parts[0]);
			$server_port = htmlspecialchars($parts[1]);
		} else {
			echo 'Invalid IP</div>';
			die();
		}
		if($serverStatus->isOnline($server_ip, $server_port, $mcname) === true){
			$is_online = $server->name;
			break;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="User profile page &bull; <?php echo $sitename; ?>">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $user_language['profile']; ?> - <?php echo $profile; ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	.jumbotron {
		margin-bottom: 0px;
		background-image: url(/core/assets/img/profile.jpg);
		background-position: 0% 25%;
		background-size: cover;
		background-repeat: no-repeat;
		color: white;
	}
	</style>
	
  </head>
  <body>
    <?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
	<br />
	<div class="container">
	  <?php if(isset($profile)){ ?>
	  <div class="row">
		<div class="col-md-9">
			<div class="jumbotron">
			  <h2>
			    <img class="img-rounded" src="https://cravatar.eu/avatar/<?php echo $mcname; ?>/60.png" />
				<strong><?php echo $mcname; ?></strong> 
				<?php 
				if($exists == true){ 
					echo $user->getGroup($profile_user[0]->id, null, "true"); 
				} else { 
					echo '<span class="label label-default">' . $user_language['player'] . '</span>';
				}
				if($query_to_use == 'false'){ 
				?>
				<span class="label label-<?php 
					if(!isset($is_online)){ 
						echo 'danger">' . $user_language['offline']; 
					} else { 
						echo 'success" rel="tooltip" data-trigger="hover" data-original-title="' . htmlspecialchars($is_online) . '">' . $user_language['online']; 
					}
				?>
				</span>
				<?php
				}
				?>
			  </h2>
			</div>
		    <br />
		    <div role="tabpanel">
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#forum" role="tab" data-toggle="tab"><?php echo $navbar_language['forum']; ?></a></li>
			  </ul>
			  <!-- Tab panes -->
			  <div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="forum">
					<br />
					<?php 
					// Check if the user has registered on the website
					if($exists == true){
					?>
					<strong><?php echo $user_language['pf_registered']; ?></strong> <?php echo date("d M Y, G:i", $profile_user[0]->joined); ?><br />
					<strong><?php echo $user_language['pf_posts']; ?></strong> <?php echo count($queries->getWhere("posts", array("post_creator", "=", $profile_user[0]->id))); ?><br />
					<strong><?php echo $user_language['pf_reputation']; ?></strong> <?php echo count($queries->getWhere("reputation", array("user_received", "=", $profile_user[0]->id))); ?><br />
					<?php 
					} else {
						echo $user_language['user_hasnt_registered'];
					} 
					?>
				</div>
			  </div>
		    </div>
		</div>
		<div class="col-md-3">
			<div class="well well-sm">
				<h3><?php echo $user_language['friends']; ?></h3>
				<?php
				if($exists == true){
					$friends = $user->listFriends($profile_user[0]->id);
					if($friends !== false){
						foreach($friends as $friend){
							echo '<span rel="tooltip" title="' . $user->IdToName($friend->friend_id) . '"><a href="/profile/' . $user->IdToMCName($friend->friend_id) . '"><img class="img-rounded" src="https://cravatar.eu/avatar/' . $user->IdToMCName($friend->friend_id) . '/40.png" /></a></span>&nbsp;';
						}
					} else {
						echo $user_language['user_no_friends'];
					}
					echo '<br /><br />';
					if($user->isLoggedIn()){
						if($user->isfriend($user->data()->id, $profile_user[0]->id) === 0){
							if($user->data()->id === $profile_user[0]->id){
								// echo "Can't add yourself as a friend!";
							} else {
								echo '<center>
								<form style="display: inline"; method="post">
								<input type="hidden" name="token" value="' . $token . '">
								<input type="submit" class="btn btn-success" name="AddFriend" value="' . $user_language['add_friend'] . '">
								</form>
								<a href="/user/messaging/?action=new&uid=' . $profile_user[0]->id . '" class="btn btn-primary">' . $user_language['send_message'] . '</a>
								</center>';
							}
						} else {
							if($user->data()->id === $profile_user[0]->id){
								// echo "Can't remove yourself as a friend!";
							} else {
								echo '<center>
								<form style="display: inline"; method="post">
								<input type="hidden" name="token" value="' . $token . '">
								<input type="submit" class="btn btn-danger" name="RemoveFriend" value="' . $user_language['remove_friend'] . '">
								</form>
								<a href="/user/messaging/?action=new&uid=' . $profile_user[0]->id . '" class="btn btn-primary">' . $user_language['send_message'] . '</a>
								</center>';
							}
						}
					}
				} else {
					echo $user_language['user_no_friends'] . '<br /><br />';
				}
				?>
			</div>
		</div>
	  </div>
	  <?php } else { 
		if(Input::exists()){
			Redirect::to('/profile/' . htmlspecialchars(Input::get('username')));
			die();
		}
	  
	  
	  ?>
	    <h2>Find a user</h2>
		<?php if(Input::exists() && isset($error)){ ?>
		<div class="alert alert-danger">Can't find that user</div>
		<?php } ?>
		<form role="form" action="" method="post">
		  <input type="text" name="username" id="username" autocomplete="off" value="<?php echo escape(Input::get('username')); ?>" class="form-control input-lg" placeholder="Username" tabindex="1">
		  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		  <br />
		  <input type="submit" value="Search" class="btn btn-primary btn-lg" tabindex="2">
		</form>
	  <?php } ?>
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