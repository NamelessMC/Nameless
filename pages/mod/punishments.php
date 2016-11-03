<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Mod check
if($user->isLoggedIn()){
	if(!$user->canViewMCP($user->data()->id)){
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}

// page for ModCP sidebar
$mod_page = 'punishments';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Moderator panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $mod_language['mod_cp'];
	
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
	<br />
    <div class="container">	
	  <div class="row">
		<div class="col-md-3">
		  <?php require('pages/mod/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<div class="well well-sm">
				<h2><?php echo $mod_language['punishments']; ?></h2>
				<?php 
				if(Session::exists('punishment_error')){
					echo Session::flash('punishment_error');
				}
				
				if(isset($_GET["action"]) && isset($_GET["uid"]) && !isset($_GET["ip"])){
					if(!is_numeric($_GET["uid"])){
						echo '<script>window.location.replace("/mod/punishments");</script>';
						die();
					}
					$punished_user = $queries->getWhere("users", array("id", "=", $_GET["uid"]));
					if(!count($punished_user)){
						echo '<script>window.location.replace("/mod/punishments");</script>';
						die();
					}
					if($_GET['action'] !== 'unban'){
						if($_GET['uid'] == 1){
							// Can't ban root user
							Session::flash('punishment_error', '<div class="alert alert-danger">' . $mod_language['cant_ban_root_user'] . '</div>');
							echo '<script>window.location.replace("/mod/punishments");</script>';
							die();
						}
						if(Input::exists()){
							if(Token::check(Input::get('token'))) {
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'reason' => array(
										'required' => true,
										'min' => 2,
										'max' => 256
									)
								));
								if(!$validation->passed()){
									Session::flash('punishment_error', '<div class="alert alert-danger">' . $mod_language['invalid_reason'] . '</div>');
									echo '<script>window.location.replace("/mod/punishments/?action=warn&uid=' . $_GET['uid'] . '");</script>';
									die();
								} else {
									if(Input::get('punishment') === "ban"){
										$type = 1;
									} else if(Input::get('punishment') === "warn"){
										$type = 2;
									}
								
									try {
										$queries->create('infractions', array(
											"type" => $type,
											"punished" => $_GET["uid"],
											"staff" => $user->data()->id,
											"reason" => htmlspecialchars(Input::get('reason')),
											"infraction_date" => date('Y-m-d H:i:s'),
											"acknowledged" => 0
										));
										if(Input::get('punishment') === "ban"){
											$queries->update("users", $_GET["uid"], array(
												"isbanned" => 1,
												"active" => 0
											));
											$queries->delete("users_session", array("user_id", "=", $_GET["uid"]));
										}
										Session::flash('punishment_error', '<div class="alert alert-success">' . $mod_language['punished_successfully'] . '</div>');
										echo '<script>window.location.replace("/mod/punishments");</script>';
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
								}
							}
						}
					?>
						<h4><?php echo $mod_language['reason']; ?></h4>
						<form role="form" action="" method="post">
							<textarea name="reason" class="form-control"></textarea>
							<br />
							<input type="hidden" name="punishment" value="<?php echo htmlspecialchars($_GET["action"]); ?>">
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
						</form>
					<?php 
					} else {
						// unban user
						$queries->update("users", $_GET["uid"], array(
							"isbanned" => 0,
							"active" => 1
						));
						echo '<script>window.location.replace("/mod/punishments");</script>';
						die();
					}
				} else if(!isset($_GET["uid"]) && isset($_GET["action"]) && isset($_GET["ip"])){
				?>
					<h4><?php echo $mod_language['ip_lookup'] . ' ' . str_replace("-", ".", htmlspecialchars($_GET["ip"])); ?></h4>
					<table class="table table-bordered">
					  <thead>
					    <tr>
						  <th><?php echo $user_language['username']; ?></th>
						  <th><?php echo $user_language['minecraft_username']; ?></th>
						  <th><?php echo $mod_language['registered']; ?></th>
						</tr>
					  </thead>
					  <tbody>
					    <?php 
					    $ip_users = $queries->getWhere("users", array("lastip", "=", str_replace("-", ".", $_GET["ip"]))); 
					    foreach($ip_users as $ip_user){
					    ?>
					    <tr>
						  <td>
					        <a href="/profile/<?php echo htmlspecialchars($ip_user->mcname); ?>"><?php echo htmlspecialchars($ip_user->username); ?></a>
						  </td>
						  <td>
						    <?php echo htmlspecialchars($ip_user->mcname); ?>
						  </td>
						  <td>
						    <?php echo date("d M Y, H:i", $ip_user->joined); ?>
						  </td>
						</tr>					   
					    <?php 
					    }
					    ?>
					  </tbody>
					</table>
				<?php 
				} else {
					if(Input::exists()){
						if(Token::check(Input::get('token'))) {
							if(Input::get('action') === "search"){
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'user' => array(
										'required' => true
									)
								));

								if(!$validation->passed()){
									echo '<script>window.location.replace("/mod/punishments");</script>';
									die();
								}

								$search_result = $queries->getWhere("users", array("username", "=", Input::get('user')));
								$search_result = $search_result[0];
								if(!count($search_result)){
									echo '<script>window.location.replace("/mod/punishments");</script>';
									die();
								} else {
								?>
								<h3 style="display: inline;"><?php echo $mod_language['user'] . ' ' . htmlspecialchars($search_result->username); ?></h3> <h4 style="display: inline;">(<strong><?php echo $admin_language['ip']; ?></strong> <a target="_blank" href="/mod/punishments/?action=lookup&ip=<?php echo str_replace(".", "-", htmlspecialchars($search_result->lastip)); ?>"><?php echo htmlspecialchars($search_result->lastip); ?></a>)</h4>
								<br /><br />
								<?php if($search_result->isbanned == 0){ ?><a class="btn btn-danger" href="/mod/punishments/?action=ban&uid=<?php echo $search_result->id; ?>"><?php echo $mod_language['ban']; ?></a><?php } else { ?><a class="btn btn-default" href="/mod/punishments/?action=unban&uid=<?php echo $search_result->id; ?>"><?php echo $mod_language['unban']; ?></a><?php } ?>
								<a class="btn btn-warning" href="/mod/punishments/?action=warn&uid=<?php echo $search_result->id; ?>"><?php echo $mod_language['warn']; ?></a>
								<?php 
								}
							}
						} else {
							echo 'Invalid token - <a href="/mod/punishments">Back</a>';
							die();
						}
					} else {
				?>
				<form role="form" action="" method="post">
					<div class="row">
					   <div class="col-xs-12">
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input type="hidden" name="action" value="search">
							<div class="input-group">
								<input class="form-control" type="text" name="user" placeholder="<?php echo $mod_language['search_for_a_user']; ?>" autocomplete="off">
								<div class="input-group-btn">
								  <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
								</div>
							</div>
					   </div>
					</div>
				</form>
				<?php 
					}
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
  </body>
</html>
