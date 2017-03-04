<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!$user->isLoggedIn()){
	Redirect::to('/');
	die();
}

// page for UserCP sidebar
$user_page = 'settings';

// Disable TFA
if(isset($_GET['action']) && $_GET['action'] == 'disable_tfa'){
	$queries->update('users', $user->data()->id, array(
		'tfa_secret' => null,
		'tfa_enabled' => 0,
		'tfa_complete' => 0
	));
	
	Session::flash('usercp_settings', '<div class="alert alert-success">' . $user_language['tfa_disabled'] . '</div>');
	
	// Redirect
	echo '<script data-cfasync="false">window.location.replace("/user/settings");</script>';
	die();
}

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier
require('core/includes/password.php'); // For password hashing
require('core/includes/validate_date.php'); // For date validation

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

if(isset($_GET['action']) && $_GET['action'] == 'update_mcname'){
	// Update Minecraft username
	if($uuid_linking == '1'){
		if(strtotime("-30 days") > $user->data()->last_username_update){
			require('core/integration/uuid.php');
			
			$uuid = $user->data()->uuid;
			
			$profile = ProfileUtils::getProfile($uuid);
			
			$result = $profile->getUsername();
			
			$result = htmlspecialchars($result);
			
			if(!empty($result)){
				$queries->update("users", $user->data()->id, array(
					"mcname" => $result,
					"last_username_update" => date('U')
				));
				
				if($custom_usernames == "false"){
					$queries->update("users", $user->data()->id, array(
						"username" => $result
					));
				}
				
				Session::flash('usercp_settings', '<div class="alert alert-info">' . $admin_language['task_successful'] . '</div>');
			} else {
				// Error
				Session::flash('usercp_settings', '<div class="alert alert-warning">' . $user_language['unable_to_update_mcname'] . '</div>');
			}
		} else {
			Session::flash('usercp_settings', '<div class="alert alert-warning">' . $user_language['unable_to_update_mcname'] . '</div>');
		}
	}
	
	// Finished, redirect
	echo '<script data-cfasync="false">window.location.replace("/user/settings");</script>';
	die();
}

// Is avatar uploading enabled?
$avatar_enabled = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
$avatar_enabled = $avatar_enabled[0]->value;

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		
		if(Input::get('action') == 'settings'){
			$signature = Input::get('signature');
			$_POST['signature'] = strip_tags(Input::get('signature'));
			
			$validate_array = array(
				'signature' => array(
					'max' => 900
				),
				'location' => array(
					'required' => true,
					'min' => 2,
					'max' => 128
				)
			);
			
			if($custom_usernames == 'true'){
				$validate_array['screenname'] = array(
					'required' => true,
					'min' => 2,
					'max' => 20
				);
			}
			
			// Validate date of birth
			if(isset($_POST['birthday']) && (!validateDate(Input::get('birthday')) || strtotime(Input::get('birthday')) > strtotime('now'))){
				// Invalid
				Session::flash('usercp_settings', '<div class="alert alert-danger">' . $user_language['invalid_date_of_birth'] . '</div>');
			} else {
				// Valid
				$validation = $validate->check($_POST, $validate_array);
				
				if($validation->passed()){
					if($custom_usernames == 'true'){
						$username = Input::get('screenname');
					} else {
						$username = $user->data()->mcname;
					}
					
					if($avatar_enabled == '1'){
						if(Input::get('gravatar') == 'on'){
							$gravatar = 1;
							$has_avatar = 1;
						} else {
							$gravatar = 0;
							$has_avatar = 0;
						}
					} else {
						$gravatar = 0;
						$has_avatar = 0;
					}
					
					if(Input::get('display_age') == 'on'){
						$display_age = 1;
					} else {
						$display_age = 0;
					}
					
					// update database value
					try {
						$queries->update('users', $user->data()->id, array(
							'username' => htmlspecialchars($username),
							'signature' => htmlspecialchars($signature),
							'gravatar' => $gravatar,
							'has_avatar' => $has_avatar,
							'display_age' => $display_age,
							'location' => htmlspecialchars(Input::get('location'))
						));
						
						if(isset($_POST['birthday'])){
							$queries->update('users', $user->data()->id, array(
								'birthday' => date('Y-m-d', strtotime(str_replace('-', '/', htmlspecialchars(Input::get('birthday')))))
							));
						}
						
						Redirect::to('/user/settings');
						die();
					} catch(Exception $e) {
						die($e->getMessage());
					}
					
				} else {
				
					$error_string = '<div class="alert alert-danger">';
					foreach($validation->errors() as $error){
						if(strpos($error, 'is required') !== false){
							// Empty display name or location field
							switch($error){
								case (strpos($error, 'username') !== false):
									$error_string .= $user_language['username_required'] . '<br />';
								break;
								case (strpos($error, 'location') !== false):
									$error_string .= $user_language['location_required'] . '<br />';
								break;
							}
						} else if(strpos($error, 'minimum') !== false){
							// Username under 3 chars/location under 2 chars
							switch($error){
								case (strpos($error, 'username') !== false):
									$error_string .= $user_language['username_minimum_3'] . '<br />';
								break;
								case (strpos($error, 'location') !== false):
									$error_string .= $user_language['location_minimum_2'] . '<br />';
								break;
							}
						} else if(strpos($error, 'maximum') !== false){
							// Field passes maximum value
							switch($error){
								case (strpos($error, 'username') !== false):
									// Username is over 20 chars
									$error_string .= $user_language['username_maximum_20'] . '<br />';
								break;
								case (strpos($error, 'signature') !== false):
									// Signature is over 900 chars
									$error_string .= $user_language['signature_maximum_900'] . '<br />';
								break;
								case (strpos($error, 'location') !== false):
									// Location is over 128 chars
									$error_string .= $user_language['location_maximum_128'] . '<br />';
								break;
							}
						}
					}
					$error_string .= '</div>';
				
					Session::flash('usercp_settings', $error_string);
				}
			}
		} else if(Input::get('action') == 'password'){
			$validate_array = array(
				'old_password' => array(
					'required' => true
				),
				'new_password' => array(
					'required' => true,
					'min' => 6,
					'max' => 30
				),
				'new_password_again' => array(
					'required' => true,
					'matches' => 'new_password'
				)
			);
			
			$validation = $validate->check($_POST, $validate_array);
			
			if($validation->passed()){
				// update password
				// Check old password matches 
				$old_password = Input::get('old_password');
				if(password_verify($old_password, $user->data()->password)){
					try {
						// Hash new password
						$new_password = password_hash(Input::get('new_password'), PASSWORD_BCRYPT, array("cost" => 13));
						
						// Update password
						$queries->update('users', $user->data()->id, array(
							'password' => $new_password
						));
						
						Session::flash('usercp_settings', '<div class="alert alert-success">' . $user_language['password_changed_successfully'] . '</div>');
						Redirect::to('/user/settings');
						die();
					} catch(Exception $e) {
						die($e->getMessage());
					}
				} else {
					// Invalid current password
					Session::flash('usercp_settings', '<div class="alert alert-danger">' . $user_language['incorrect_password'] . '</div>');
				}
				
			} else {
				$error_string = '<div class="alert alert-danger">';
				foreach($validation->errors() as $error){
					if(strpos($error, 'is required') !== false){
						// Empty field
						if(strpos($error_string, $user_language['password_required']) !== false){
							// Only add error once
						} else {
							$error_string .= $user_language['password_required'] . '<br />';
						}
					} else if(strpos($error, 'minimum') !== false){
						// Field under 6 chars
						if(strpos($error_string, $user_language['password_minimum_6']) !== false){
							// Only add error once
						} else {
							$error_string .= $user_language['password_minimum_6'] . '<br />';
						}
					} else if(strpos($error, 'maximum') !== false){
						// Field under 6 chars
						if(strpos($error_string, $user_language['password_maximum_30']) !== false){
							// Only add error once
						} else {
							$error_string .= $user_language['password_maximum_30'] . '<br />';
						}
					} else if(strpos($error, 'must match') !== false){
						// Password must match password again
						$error_string .= $user_language['passwords_dont_match'] . '<br />';
							
					}
				}
				$error_string .= '</div>';
			
				Session::flash('usercp_settings', $error_string);
			}
		} else if(Input::get('action') == 'tfa'){
			// Two factor authentication
			$validation = $validate->check($_POST, array(
				'tfa_enabled' => array(
					'required' => true
				),
				'tfa_type' => array(
					'required' => true
				)
			));
			
			if($validation->passed()){
				try {
					if(Input::get('tfa_enabled') == 'on') $tfa = 1; else $tfa = 0;
					
					if(Input::get('tfa_type') == '2'){
						$tfa_type = 0; 
						$tfa_complete = 1;
					} else {
						$tfa_type = 1;
						$tfa_complete = 0;
					}
					
					// Update
					$queries->update('users', $user->data()->id, array(
						'tfa_enabled' => $tfa,
						'tfa_type' => $tfa_type,
						'tfa_secret' => null,
						'tfa_complete' => $tfa_complete
					));
					
					// Do we need to generate a secret key for the app?
					if($tfa_type == 1 && !$user->data()->tfa_secret){
						echo '<script data-cfasync="false">window.location.replace("/user/tfa");</script>';
						die();
					}
					
				} catch(Exception $e){
					die($e->getMessage());
				}
				
				// Done, redirect
				echo '<script data-cfasync="false">window.location.replace("/user/settings");</script>';
				die();
			}
		}
	}
}


$token = Token::generate();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="User panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['user_cp'];
	
	require('core/includes/template/generate.php');
	?>
	
	<link href="/core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">	
	<link href="/core/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	
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
		  <?php require('pages/user/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="well">
		    <br />
			<h3 style="display:inline;"><?php echo $user_language['profile_settings']; ?></h3>
			<span class="pull-right">
			  <?php
			  if($uuid_linking == '1' && (strtotime("-30 days") > $user->data()->last_username_update)){
			  ?>
			  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#usernameModal"><?php echo $admin_language['update_mc_name']; ?></a>
			  <?php 
			  }
			  ?>
			</span>
			<br /><br />
			<?php 
			if(Session::exists('settings_avatar_error')){
				echo Session::flash('settings_avatar_error');
			}
			
			if(Session::exists('usercp_settings')){
				echo Session::flash('usercp_settings');
			}
			
			// HTML Purifier
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$config->set('URI.DisableExternalResources', false);
			$config->set('URI.DisableResources', false);
			$config->set('HTML.Allowed', 'u,p,b,a,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
			$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
			$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
			$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
			$purifier = new HTMLPurifier($config);
			
			$signature = $purifier->purify(htmlspecialchars_decode($user->data()->signature));
			?>
			<form action="" method="post">
			  <?php
			  if($custom_usernames == 'true'){
			  ?>
			  <div class="form-group">
				<label for="InputScreenName"><?php echo $user_language['display_name']; ?></label>
				<input type="text" name="screenname" class="form-control" id="InputScreenName" value="<?php echo htmlspecialchars($user->data()->username); ?>">
			  </div>
			  <?php
			  }
			  ?>
			  <div class="form-group">
				<label for="InputLocation"><?php echo $user_language['location']; ?></label>
				<input type="text" name="location" class="form-control" id="InputLocation" value="<?php echo htmlspecialchars($user->data()->location); ?>">
			  </div>
			  <?php
			  // Birthday - only if not already inputted
			  if(!$user->data()->birthday){
			  ?>
			  <div class="form-group">
			    <label for="birthday"><?php echo $user_language['date_of_birth']; ?></label>
				<input type="text" class="form-control datepicker" name="birthday" id="birthday" placeholder="<?php echo $user_language['date_of_birth']; ?>">
			  </div>
			  <?php
			  }
			  ?>
			  <div class="form-group">
				<label for="display_age"><?php echo $user_language['display_age_on_profile']; ?></label>
				<input id="display_age" name="display_age" type="checkbox" class="js-switch" <?php if($user->data()->display_age == 1){ ?>checked <?php } ?>/>
			  </div>
			  <div class="form-group">
				<label for="signature"><?php echo $user_language['signature']; ?></label>
				<textarea rows="10" name="signature" id="signature">
					<?php echo $signature; ?>
				</textarea>
			  </div>
			  <?php
			  // Gravatar
			  if($avatar_enabled === '1'){
			  ?>
			  <div class="form-group">
				<label for="gravatar"><?php echo $user_language['use_gravatar']; ?></label>
				<input id="gravatar" name="gravatar" type="checkbox" class="js-switch" <?php if($user->data()->gravatar == 1){ ?>checked <?php } ?>/>
			  </div>
			  <?php
			  }
			  ?>
			  <input type="hidden" name="token" value="<?php echo $token; ?>" />
			  <input type="hidden" name="action" value="settings" />
			  <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>" />
			</form>
			<br />
			<form action="" method="post">
			  <h4><?php echo $user_language['change_password']; ?></h4>
			  <div class="form-group">
				<label for="InputOldPassword"><?php echo $user_language['current_password']; ?></label>
				<input type="password" name="old_password" class="form-control" id="InputOldPassword" placeholder="<?php echo $user_language['current_password']; ?>">
			  </div>
			  <div class="form-group">
				<label for="InputNewPassword"><?php echo $user_language['new_password']; ?></label>
				<input type="password" name="new_password" class="form-control" id="InputNewPassword" placeholder="<?php echo $user_language['new_password']; ?>">
			  </div>
			  <div class="form-group">
				<label for="InputNewPasswordAgain"><?php echo $user_language['repeat_new_password']; ?></label>
				<input type="password" name="new_password_again" class="form-control" id="InputNewPasswordAgain" placeholder="<?php echo $user_language['repeat_new_password']; ?>">
			  </div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>" />
			  <input type="hidden" name="action" value="password" />
			  <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>" />
			</form>
			<br />
			<?php
			if($avatar_enabled === '1'){
			?>
			<form action="/user/avatar_upload/" method="post" enctype="multipart/form-data">
			  <strong><?php echo $user_language['upload_an_avatar']; ?></strong>
			  <input type="file" name="uploaded_avatar" />
			  <input type="hidden" name="token" value="<?php echo $token; ?>" /><br />
			  <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>" />
			</form>
			<?php
			}
			?>
			<br />
			<form action="" method="post">
			  <h4><?php echo $user_language['two_factor_authentication']; ?></h4>
			  <div class="form-group">
				<label for="enable_tfa"><?php echo $user_language['enable_tfa']; ?></label>
				<input type="hidden" name="tfa_enabled" value="0">
				<input id="enable_tfa" name="tfa_enabled" type="checkbox" class="js-switch" <?php if($user->data()->tfa_enabled == 1){ ?>checked <?php } ?>/>
			  </div>
			  <div class="form-group">
			    <label for="tfa_type"><?php echo $user_language['tfa_type']; ?></label>
			    <select class="form-control" name="tfa_type" id="tfa_type">
				  <option value="2"<?php if($user->data()->tfa_type == 0) echo ' selected'; ?>><?php echo $user_language['email']; ?></option>
				  <option value="1"<?php if($user->data()->tfa_type == 1) echo ' selected'; ?>><?php echo $user_language['authenticator_app']; ?></option>
				</select>
			  </div>
			  <div class="form-group">
			    <input type="hidden" name="action" value="tfa">
			    <input type="hidden" name="token" value="<?php echo $token; ?>">
				<input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>">
				<a class="btn btn-danger" href="/user/settings/?action=disable_tfa" onclick="return confirm('<?php echo $user_language['confirm_tfa_disable']; ?>');"><?php echo $admin_language['disable']; ?></a>
			  </div>
			</form>
		  </div>
		</div>
      </div>
    </div>
	
	<!-- Update username modal -->
	<div class="modal fade" id="usernameModal" tabindex="-1" role="dialog" aria-labelledby="usernameModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="usernameModalLabel"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $general_language['info']; ?></h4>
		  </div>
		  <div class="modal-body">
			<?php echo $user_language['update_minecraft_name_help']; ?>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['cancel']; ?></button>
			<a href="/user/settings/?action=update_mcname" class="btn btn-primary"><?php echo $general_language['confirm']; ?></a>
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
	<script src="/core/assets/js/ckeditor.js"></script>
	
	<script src="/core/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript">
		$('.datepicker').datepicker({
			orientation: 'bottom'
		});
	
		CKEDITOR.replace( 'signature', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"links","groups":["links"]},
				{"name":"insert","groups":["insert"]},
				{"name":"about","groups":["about"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
		CKEDITOR.timestamp = '2';
		CKEDITOR.config.disableNativeSpellChecker = false;
	</script>
  </body>
</html>
