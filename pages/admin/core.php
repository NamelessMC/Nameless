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
 
$adm_page = "core";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $admin_language['admin_cp']; ?> &bull; <?php echo $admin_language['core']; ?></title>
	
	<?php
	// Generate header and navbar content
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
    <div class="container">
	  <?php
	  // "Core" page
	  // Load navbar
	  $smarty->display('styles/templates/' . $template . '/navbar.tpl');
	  ?>
	  <br />
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <ul class="nav nav-pills">
			<li<?php if(!isset($_GET['view'])){ ?> class="active"<?php } ?>><a href="/admin/core"><?php echo $admin_language['general_settings']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'modules'){ ?> class="active"<?php } ?>><a href="/admin/core/?view=modules"><?php echo $admin_language['modules']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'email'){ ?> class="active"<?php } ?>><a href="/admin/core/?view=email"><?php echo $admin_language['email']; ?></a></li>
		 </ul>
		  <hr>
		  <div class="well">
		    <?php 
			if(!isset($_GET['view'])){
				// General settings. First, deal with input
				if(Input::exists()){
					if(Token::check(Input::get('token'))){
						// valid token
						// Validate sitename value
						$validate = new Validate();
						$validation = $validate->check($_POST, array(
							'sitename' => array(
								'required' => true,
								'min' => 2,
								'max' => 32
							),
							'language' => array(
								'required' => true
							)
						));
						
						if($validation->passed()) {
							// We can update the database settings
							// Update sitename
							$queries->update('settings', 1, array(
								'value' => htmlspecialchars(Input::get('sitename'))
							));
							// Update cache for sitename
							$c->setCache('sitenamecache');
							$c->store('sitename', htmlspecialchars(Input::get('sitename')));
							
							// Update language
							$language_id = $queries->getWhere('settings', array('name', '=', 'language'));
							$language_id = $language_id[0]->id;
							
							$queries->update('settings', $language_id, array(
								'value' => htmlspecialchars(Input::get('language'))
							));
							// Update cache for language
							$c->setCache('languagecache');
							$c->store('language', htmlspecialchars(Input::get('language')));
							
							Session::flash('general_settings', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
							Redirect::to('/admin/core');
							die();
							
						} else {
							Session::flash('general_settings', '<div class="alert alert-danger">Unable to update settings. Please ensure no fields are left empty.</div>');
						}
					} else {
						// invalid token
						Session::flash('general_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
					}
				}
			?>
			<h3><?php echo $admin_language['general_settings']; ?></h3>
			<?php
			if(Session::exists('general_settings')){
				echo Session::flash('general_settings');
			}
			?>
			<form action="" method="post">
			  <div class="form-group">
			    <label for="sitename"><?php echo $admin_language['site_name']; ?></label>
			    <input type="text" class="form-control" name="sitename" id="sitename" value="<?php echo $sitename; ?>">
			  </div>
			  <div class="form-group">
			    <label for="language"><?php echo $admin_language['language']; ?></label>
				<select id="language" name="language" class="form-control">
				  <?php
				    // Get a list of installed languages
					$directories = glob('styles/language/*' , GLOB_ONLYDIR);
					foreach($directories as $directory){
						$folders = explode('/', $directory);
						echo '<option value="' . $folders[2] . '"';
						if($folders[2] == $language){
							echo ' selected';
						}
						echo '>' . $folders[2] . '</option>';
					}
				  ?>
				</select>
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			</form>
			<?php
		    } else {
				if($_GET['view'] == 'modules'){
				?>
				<h3><?php echo $admin_language['modules']; ?></h3>
				    <?php
					if(Session::exists('core_modules')){
						echo Session::flash('core_modules');
					}
					if(!isset($_GET['action']) && !isset($_GET['activate']) && !isset($_GET['deactivate'])){
						// Get a list of modules
						$modules = $queries->getWhere('core_modules', array('id', '<>', '0'));
						foreach($modules as $module){
						?>
			  <div class="row">
				&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars(str_replace('_', ' ', $module->name)); ?></strong>
						<?php if($module->enabled == 1){ ?>
				<span class="pull-right"><a href="/admin/core/?view=modules&deactivate=<?php echo htmlspecialchars($module->name); ?>" style="width: 100px;" class="btn btn-danger"><?php echo $admin_language['deactivate']; ?></a> <a href="/admin/core/?view=modules&action=edit&module=<?php echo htmlspecialchars($module->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a></span>
						<?php } else { ?>
				<span class="pull-right"><a href="/admin/core/?view=modules&activate=<?php echo htmlspecialchars($module->name); ?>" style="width: 100px;" class="btn btn-success"><?php echo $admin_language['activate']; ?></a> <a href="/admin/core/?view=modules&action=edit&module=<?php echo htmlspecialchars($module->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a></span>
						<?php } ?>
				<hr>
			  </div>
						<?php
						}
					} else if(isset($_GET['activate'])){
						// Make a module active
						// First, check the module actually exists
						$module = $queries->getWhere('core_modules', array('name', '=', htmlspecialchars($_GET['activate'])));
						if(!count($module)){
							Session::flash('core_modules', '<div class="alert alert-danger">' . $admin_language['module_not_exist'] . '</div>');
							echo '<script>window.location.replace(\'/admin/core/?view=modules\');</script>';
							die();
						}
						$module_name = $module[0]->name;
						$module = $module[0]->id;
						
						// Module exists
						
						// Make module active
						$queries->update('core_modules', $module, array(
							'enabled' => 1
						));
						
						Session::flash('core_modules', '<div class="alert alert-success">' . $admin_language['module_enabled'] . '</div>');
						echo '<script>window.location.replace(\'/admin/core/?view=modules\');</script>';
						die();
					} else if(isset($_GET['deactivate'])){
						// Disable a module
						// First, check the module actually exists
						$module = $queries->getWhere('core_modules', array('name', '=', htmlspecialchars($_GET['deactivate'])));
						if(!count($module)){
							Session::flash('core_modules', '<div class="alert alert-danger">' . $admin_language['module_not_exist'] . '</div>');
							echo '<script>window.location.replace(\'/admin/core/?view=modules\');</script>';
							die();
						}
						$module_name = $module[0]->name;
						$module = $module[0]->id;
						
						// Module exists
						
						// Disable module
						$queries->update('core_modules', $module, array(
							'enabled' => 0
						));
						
						Session::flash('core_modules', '<div class="alert alert-success">' . $admin_language['module_disabled'] . '</div>');
						echo '<script>window.location.replace(\'/admin/core/?view=modules\');</script>';
						die();
					} else if(isset($_GET['action'])){
						if($_GET['module'] == 'Google_Analytics'){
							// Deal with input
							if(Input::exists()){
								if(Token::check(Input::get('token'))){
									// Update value
									$ga_setting_id = $queries->getWhere('settings', array('name', '=', 'ga_script'));
									$ga_setting_id = $ga_setting_id[0]->id;
									
									$queries->update('settings', $ga_setting_id, array(
										'value' => Input::get('tracking')
									));
									Session::flash('google_analytics_settings', '<div class="alert alert-success">Successfully updated.</div>');
								} else {
									// Invalid token
									Session::flash('google_analytics_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}
							
							// Get the current tracking code
							$settings = $queries->getWhere('settings', array('name', '=', 'ga_script'));
							
							// Show settings for Google Analytics
						?>
						<h4>Editing Google Analytics module</h4>
						<?php
						if(Session::exists('google_analytics_settings')){
							echo Session::flash('google_analytics_settings');
						}
						?>
						<form action="" method="post">
						  <label for="tracking">Tracking Code</label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="Insert the tracking code for Google Analytics here, including the surrounding script tags."><span class="glyphicon glyphicon-question-sign"></span></a>
						  <textarea id="tracking" name="tracking" class="form-control" rows="5"><?php echo $settings[0]->value; ?></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<br />
						<em>See <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">this guide</a> for more information, following steps 1 to 3.</em>
						<?php
						} else if($_GET['module'] == 'Social_Media'){
							// Deal with input
							if(Input::exists()){
								if(Token::check(Input::get('token'))){
									// Update database values
									// Youtube URL
									$youtube_url_id = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
									$youtube_url_id = $youtube_url_id[0]->id;
									
									$queries->update('settings', $youtube_url_id, array(
										'value' => htmlspecialchars(Input::get('youtubeurl'))
									));
									// Twitter URL
									$twitter_url_id = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
									$twitter_url_id = $twitter_url_id[0]->id;
									
									$queries->update('settings', $twitter_url_id, array(
										'value' => htmlspecialchars(Input::get('twitterurl'))
									));
									// Twitter widget
									$twitter_wid_id = $queries->getWhere('settings', array('name', '=', 'twitter_feed_id'));
									$twitter_wid_id = $twitter_wid_id[0]->id;
									
									$queries->update('settings', $twitter_wid_id, array(
										'value' => htmlspecialchars(Input::get('twitter_id'))
									));
									// Google Plus URL
									$gplus_url_id = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
									$gplus_url_id = $gplus_url_id[0]->id;
									
									$queries->update('settings', $gplus_url_id, array(
										'value' => htmlspecialchars(Input::get('gplusurl'))
									));
									// Facebook URL
									$fb_url_id = $queries->getWhere('settings', array('name', '=', 'fb_url'));
									$fb_url_id = $fb_url_id[0]->id;
									$queries->update('settings', $fb_url_id, array(
										'value' => htmlspecialchars(Input::get('fburl'))
									));
									Session::flash('social_media_links', '<div class="alert alert-success">Successfully updated.</div>');
								} else {
									// Invalid token
									Session::flash('social_media_links', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}

							// Show settings for social media links
							// Get values from database
							$youtube_url = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
							$twitter_url = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
							$twitter_wid_id = $queries->getWhere('settings', array('name', '=', 'twitter_feed_id'));
							$gplus_url = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
							$fb_url = $queries->getWhere('settings', array('name', '=', 'fb_url'));
						?>
						<h4>Social Media Links</h4>
						<?php
						if(Session::exists('social_media_links')){
							echo Session::flash('social_media_links');
						}
						?>
						<form action="" method="post">
							<div class="form-group">
								<label for="InputYoutube">YouTube URL</label>
								<input type="text" name="youtubeurl" class="form-control" id="InputYoutube" placeholder="YouTube URL (with preceding http)" value="<?php echo htmlspecialchars($youtube_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputTwitter">Twitter URL</label>
								<input type="text" name="twitterurl" class="form-control" id="InputTwitter" placeholder="Twitter URL (with preceding http)" value="<?php echo htmlspecialchars($twitter_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputTwitterID">Twitter Widget ID</label>
								<input type="text" name="twitter_id" class="form-control" id="InputTwitterID" placeholder="Twitter Widget ID" value="<?php echo htmlspecialchars($twitter_wid_id[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputGPlus">Google Plus URL</label>
								<input type="text" name="gplusurl" class="form-control" id="InputGPlus" placeholder="Google Plus URL (with preceding http)" value="<?php echo htmlspecialchars($gplus_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputFacebook">Facebook URL</label>
								<input type="text" name="fburl" class="form-control" id="InputFacebook" placeholder="Facebook URL (with preceding http)" value="<?php echo htmlspecialchars($fb_url[0]->value); ?>">
							</div>
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<?php
						} else if($_GET['module'] == 'Registration'){
							// Deal with input
							if(Input::exists()){
								if(Token::check(Input::get('token'))){
									// Update database values
									// reCAPCTHA enabled?
									if(Input::get('enable_recaptcha') == 1){
										$recaptcha = 'true';
									} else {
										$recaptcha = 'false';
									}
									$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
									$recaptcha_id = $recaptcha_id[0]->id;
									$queries->update('settings', $recaptcha_id, array(
										'value' => $recaptcha
									));
									// reCAPTCHA key
									$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
									$recaptcha_id = $recaptcha_id[0]->id;
									$queries->update('settings', $recaptcha_id, array(
										'value' => htmlspecialchars(Input::get('recaptcha'))
									));
									// reCAPTCHA secret key
									$recaptcha_secret_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
									$recaptcha_secret_id = $recaptcha_secret_id[0]->id;
									$queries->update('settings', $recaptcha_secret_id, array(
										'value' => htmlspecialchars(Input::get('recaptcha_secret'))
									));
									// Terms and Conditions
									$t_and_c_id = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
									$t_and_c_id = $t_and_c_id[0]->id;
									$queries->update('settings', $t_and_c_id, array(
										'value' => htmlspecialchars(Input::get('terms_and_conditions'))
									));
									Session::flash('registration_settings', '<div class="alert alert-success">Successfully updated.</div>');
								} else {
									// Invalid token
									Session::flash('registration_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}
							
							// Get settings
							$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
							$recaptcha_key = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
							$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
							$t_and_c_id = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
							
							// We generate the token here as it clashes with HTMLPurifier
							$token = Token::generate();
							
							// HTML Purifier
							require_once('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
							$config = HTMLPurifier_Config::createDefault();
							$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
							$config->set('URI.DisableExternalResources', false);
							$config->set('URI.DisableResources', false);
							$config->set('HTML.Allowed', 'u,p,b,i,a,s');
							$config->set('HTML.AllowedAttributes', 'target, href');
							$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
							$purifier = new HTMLPurifier($config);
						?>
						<h4>Registration</h4>
						<?php
						if(Session::exists('registration_settings')){
							echo Session::flash('registration_settings');
						}
						?>
						<em>Having this module disabled will also disable new members registering on your site.</em><hr>
						<form action="" method="post">
							<div class="form-group">
							    <input type="hidden" name="enable_recaptcha" value="0" />
								<label for="InputEnableRecaptcha">Enable Google reCAPTCHA</label>
								<input id="InputEnableRecaptcha" name="enable_recaptcha" type="checkbox" class="js-switch" value="1"<?php if($recaptcha_id[0]->value == 'true'){ ?> checked<?php } ?> />
							</div>
							<div class="form-group">
								<label for="InputRecaptcha">reCAPTCHA Site Key</label>
								<input type="text" name="recaptcha" class="form-control" id="InputRecaptcha" placeholder="Recaptcha Key" value="<?php echo htmlspecialchars($recaptcha_key[0]->value); ?>">
							</div>
							<div class="form-group">
							    <label for="InputRecaptchaSecret">reCAPTCHA Secret Key</label>
								<input type="text" name="recaptcha_secret" class="form-control" id="InputRecaptchaSecret" placeholder="Recaptcha Secret Key" value="<?php echo htmlspecialchars($recaptcha_secret[0]->value); ?>">
							</div>
							<div class="form-group">
							    <label for="InputTandC">Registration Terms and Conditions</label>
								<textarea class="form-control" rows="8" name="terms_and_conditions" id="InputTandC"><?php echo $purifier->purify(htmlspecialchars_decode($t_and_c_id[0]->value)); ?></textarea>
							</div>
							<input type="hidden" name="token" value="<?php echo $token; ?>" />
							<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<?php
						} else if($_GET['module'] == 'Voice_Server_Module'){
							// Deal with input
							if(Input::exists()){
								if(Token::check(Input::get('token'))){
									if(is_writable('core/voice_server.php')){
										// "generate" string to input into config
										$insert = 	'<?php' . PHP_EOL .
													'$voice_server_enabled = \'teamspeak\';' . PHP_EOL .
													'$voice_server_username = \'' . htmlspecialchars(Input::get('username')) . '\';' . PHP_EOL .
													'$voice_server_password = \'' . htmlspecialchars(Input::get('password')) . '\';' . PHP_EOL .
													'$voice_server_ip = \'' . htmlspecialchars(Input::get('ip')) . '\';' . PHP_EOL .
													'$voice_server_port = \'' . htmlspecialchars(Input::get('port')) . '\';' . PHP_EOL .
													'$voice_virtual_server_port = \'' . htmlspecialchars(Input::get('virtual_port')) . '\';';
										
										$file = fopen('core/voice_server.php', 'w');
										fwrite($file, $insert);
										fclose($file);
									} else {
										Session::flash('voice_server_settings', '<div class="alert alert-danger">' . $admin_language['voice_server_not_writable'] . '</div>');
									}
								} else {
									// Invalid token
									Session::flash('voice_server_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}
							
							require_once('core/voice_server.php'); // for credentials
						?>
						<h4>Voice Server Module</h4>
						<?php
						if(Session::exists('voice_server_settings')){
							echo Session::flash('voice_server_settings');
						}
						?>
						<div class="alert alert-info">This module currently only works with TeamSpeak</div>
						<form action="" method="post">
						  <div class="form-group">
						    <label for="username">Username</label>
							<input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($voice_server_username); ?>" placeholder="Voice server username">
						  </div>
						  <div class="form-group">
						    <label for="password">Password</label>
							<input type="password" class="form-control" name="password" id="password" value="<?php echo htmlspecialchars($voice_server_password); ?>" placeholder="Voice server password">
						  </div>
						  <div class="form-group">
						    <label for="ip">IP (without port)</label>
							<input type="text" class="form-control" name="ip" id="ip" value="<?php echo htmlspecialchars($voice_server_ip); ?>" placeholder="Voice server IP">
						  </div>
						  <div class="form-group">
						    <label for="port">Port (usually 10011)</label>
							<input type="text" class="form-control" name="port" id="port" value="<?php echo htmlspecialchars($voice_server_port); ?>" placeholder="Voice server port">
						  </div>
						  <div class="form-group">
						    <label for="virtual_port">Virtual Port (usually 9987)</label>
							<input type="text" class="form-control" name="virtual_port" id="virtual_port" value="<?php echo htmlspecialchars($voice_virtual_server_port); ?>" placeholder="Voice server virtual port">
						  </div>
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<?php
						} else if($_GET['module'] == 'Staff_Applications'){
						?>
						<h4>Staff Applications</h4>
						<?php 
							if(!isset($_GET['module_action']) && !isset($_GET['question'])){
								if(Session::exists('apps_post_success')){
									echo Session::flash('apps_post_success');
								}
								if(Input::exists()){
									if(Token::check(Input::get('token'))){
										// Group permissions
										// Get all groups
										$groups = $queries->getWhere('groups', array('id', '<>', '0'));
										foreach($groups as $group){ 
											if(Input::get('view-' . $group->id) == 'on'){
												$view = 1;
											} else {
												$view = 0;
											}
											if(Input::get('accept-' . $group->id)){
												$accept = 1;
											} else {
												$accept = 0;
											}
											
											try {
												$queries->update('groups', $group->id, array(
													'staff_apps' => $view,
													'accept_staff_apps' => $accept
												));
												
											} catch(Exception $e) {
												die($e->getMessage());
											}
										}
									} else {
										Session::flash('apps_post_success', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
										echo '<script>window.location.replace("/admin/core/?view=modules&action=edit&module=Staff_Applications")</script>';
										die();
									}
								}
								
								// Query groups again to get updated values
								$groups = $queries->getWhere('groups', array('id', '<>', '0'));
						?>
						<form role="form" action="" method="post">
						  <strong>Permissions:</strong><br /><br />
						  <div class="row">
						    <div class="col-md-8">
							  <div class="col-md-6">
							    Group
							  </div>
							  <div class="col-md-3">
							    View applications?
							  </div>
							  <div class="col-md-3">
							    Accept/reject applications?
							  </div>
							</div>
						  </div>

						  <?php
						  foreach($groups as $group){
						  ?>
						  <div class="row">
						    <div class="col-md-8">
							  <div class="col-md-6">
							    <?php echo htmlspecialchars($group->name); ?><br /><br />
							  </div>
							  <div class="col-md-3">
							    <div class="form-group">
								  <input id="view-<?php echo $group->id; ?>" name="view-<?php echo $group->id; ?>" type="checkbox" class="js-switch" <?php if($group->staff_apps == 1){ ?>checked <?php } ?>/>
							    </div>
							  </div>
							  <div class="col-md-3">
							    <div class="form-group">
								  <input id="accept-<?php echo $group->id; ?>" name="accept-<?php echo $group->id; ?>" type="checkbox" class="js-switch" <?php if($group->accept_staff_apps == 1){ ?>checked <?php } ?>/>
							    </div>
							  </div>
							</div>
						  </div>
						  <?php
						  }
						  ?>
						  <br /><br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-default" value="<?php echo $general_language['submit']; ?>">
						</form>
						
						<br /><br />
						<strong>Questions:</strong> <span class="pull-right"><a href="/admin/core/?view=modules&amp;action=edit&amp;module=Staff_Applications&amp;module_action=new" class="btn btn-primary">New Question</a></span><br />
						<?php 
						// Get a list of questions
						$questions = $queries->getWhere('staff_apps_questions', array('id', '<>', 0));
						if(count($questions)){
						?>
						<table class="table table-striped">
						  <tr>
							<th>Name</th>
							<th>Question</th>
							<th>Type</th>
							<th>Options</th>
						  </tr>
						<?php
							foreach($questions as $question){
						?>
						  <tr>
							<td><a href="/admin/core/?view=modules&amp;action=edit&amp;module=Staff_Applications&amp;question=<?php echo $question->id; ?>"><?php echo ucfirst(htmlspecialchars($question->name)); ?></a></td>
							<td><?php echo htmlspecialchars($question->question); ?></td>
							<td><?php echo $queries->convertQuestionType($question->type); ?></td>
							<td><?php 
							$options = explode(',', $question->options);
							foreach($options as $option){
								echo htmlspecialchars($option) . '<br />';
							}
							?></td>
						  </tr>
						<?php
								echo '<a href="/admin/core/?view=modules&action=edit&module=Staff_Applications&question=' . $question->id . '"></a><br />';
							}
						} else {
							echo 'No questions defined yet.';
						}
						?>
						</table>
						<?php 
							} else if(isset($_GET['question']) && !isset($_GET['module_action'])) { 
								// Get the question
								if(!is_numeric($_GET['question'])){
									echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								$question_id = $_GET['question'];
								$question = $queries->getWhere('staff_apps_questions', array('id', '=', $question_id));
								
								// Does the question exist?
								if(!count($question)){
									echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
						
								// Deal with the input
								if(Input::exists()){
									if(Token::check(Input::get('token'))){
										$validate = new Validate();
										$validation = $validate->check($_POST, array(
											'name' => array(
												'required' => true,
												'min' => 2,
												'max' => 16
											),
											'question' => array(
												'required' => true,
												'min' => 2,
												'max' => 255
											)
										));
										
										if($validation->passed()){
											// Get options into a string
											$options = str_replace("\n", ',', Input::get('options'));
											
											$queries->update('staff_apps_questions', $question_id, array(
												'type' => Input::get('type'),
												'name' => htmlspecialchars(Input::get('name')),
												'question' => htmlspecialchars(Input::get('question')),
												'options' => htmlspecialchars($options)
											));

											Session::flash('apps_post_success', '<div class="alert alert-info">Question successfully edited</div>');
											echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
											die();
										}
								
									} else {
										// Invalid token
									}
								}
						
								$question = $question[0];
						?>
						<strong>Editing question '<?php echo htmlspecialchars($question->name); ?>'</strong>
						<span class="pull-right"><a href="/admin/core/?view=modules&amp;action=edit&amp;module=Staff_Applications&amp;question=<?php echo $question->id; ?>&amp;module_action=delete" onclick="return confirm('Are you sure you want to delete this question?');" class="btn btn-danger">Delete question</a></span>
						<br /><br />
						
						<form method="post" action="">
						  <label for="name">Question Name</label>
						  <input class="form-control" type="text" name="name" id="name" placeholder="Name" value="<?php echo htmlspecialchars($question->name); ?>">
						  <br />
						  <label for="question">Question</label>
						  <input class="form-control" type="text" name="question" id="question" placeholder="Question" value="<?php echo htmlspecialchars($question->question); ?>">
						  <br />
						  <label for="type">Type</label>
						  <select name="type" id="type" class="form-control">
							<option value="1"<?php if($question->type == 1){ ?> selected<?php } ?>>Dropdown</option>
							<option value="2"<?php if($question->type == 2){ ?> selected<?php } ?>>Text</option>
							<option value="3"<?php if($question->type == 3){ ?> selected<?php } ?>>Text Area</option>
						  </select>
						  <br />
						  <label for="options">Options - <em>Each option on a new line; can be left empty (dropdowns only)</em></label>
						  <?php
						  // Get already inputted options
						  if($question->options == null){
							  $options = '';
						  } else {
							  $options = str_replace(',', "\n", htmlspecialchars($question->options));
						  }
						  ?>
						  <textarea rows="5" class="form-control" name="options" id="options" placeholder="Options"><?php echo $options; ?></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="Edit">
						</form>
						
						
						<?php 
							} else if(isset($_GET['module_action']) && $_GET['module_action'] == 'new') { 
								// Deal with the input
								if(Input::exists()){
									if(Token::check(Input::get('token'))){
										$validate = new Validate();
										$validation = $validate->check($_POST, array(
											'name' => array(
												'required' => true,
												'min' => 2,
												'max' => 16
											),
											'question' => array(
												'required' => true,
												'min' => 2,
												'max' => 255
											)
										));
										
										if($validation->passed()){
											// Get options into a string
											$options = str_replace("\n", ',', Input::get('options'));
											
											$queries->create('staff_apps_questions', array(
												'type' => Input::get('type'),
												'name' => htmlspecialchars(Input::get('name')),
												'question' => htmlspecialchars(Input::get('question')),
												'options' => htmlspecialchars($options)
											));

											Session::flash('apps_post_success', '<div class="alert alert-info">Question successfully created</div>');
											echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
											die();
										} else {
											// errors
										}
										
									} else {
										// Invalid token
									}
								}

						?>
						<strong>New Question</strong><br /><br />
						
						<form method="post" action="">
						  <label for="name">Question Name</label>
						  <input class="form-control" type="text" name="name" id="name" placeholder="Name">
						  <br />
						  <label for="question">Question</label>
						  <input class="form-control" type="text" name="question" id="question" placeholder="Question">
						  <br />
						  <label for="type">Type</label>
						  <select name="type" id="type" class="form-control">
							<option value="1">Dropdown</option>
							<option value="2">Text</option>
							<option value="3">Text Area</option>
						  </select>
						  <br />
						  <label for="options">Options - <em>Each option on a new line; can be left empty (dropdowns only)</em></label>
						  <textarea rows="5" class="form-control" name="options" id="options" placeholder="Options"></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="Create">
						</form>
						<?php 
							} else if(isset($_GET['module_action']) && $_GET['module_action'] == 'delete' && isset($_GET['question'])) {
								// Get the question
								if(!is_numeric($_GET['question'])){
									echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								$question_id = $_GET['question'];
								$question = $queries->getWhere('staff_apps_questions', array('id', '=', $question_id));
								
								// Does the question exist?
								if(!count($question)){
									echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								
								// Exists, we can delete it
								$queries->delete('staff_apps_questions', array('id', '=', $question_id));
								
								Session::flash('apps_post_success', '<div class="alert alert-info">Question successfully deleted</div>');
								echo '<script>window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
								die();
							}
						}						
					}
				} else if($_GET['view'] == 'email'){
					// Require email information
					//require('core/email.php');
					
					// Deal with input
					if(Input::exists()){
						if(Token::check(Input::get('token'))){
							// Validate input
							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'incoming_email' => array(
									'required' => true,
									'min' => 2,
									'max' => 64
								),
								'outgoing_email' => array(
									'max' => 64
								)
							));
							
							if($validation->passed()) {
								$outgoing_email_id = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
								$outgoing_email_id = $outgoing_email_id[0]->id;
								$queries->update('settings', $outgoing_email_id, array(
									'value' => htmlspecialchars(Input::get('outgoing_email'))
								));
								
								$incoming_email_id = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
								$incoming_email_id = $incoming_email_id[0]->id;
								$queries->update('settings', $incoming_email_id, array(
									'value' => htmlspecialchars(Input::get('incoming_email'))
								));
								
								$phpmailer_id = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
								$phpmailer_id = $phpmailer_id[0]->id;
								$queries->update('settings', $phpmailer_id, array(
									'value' => Input::get('use_external')
								));
							} else {
								// Validation errors
								
							}
						} else {
							// Invalid token
						}
					}
					
					// Get current email settings
					$outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
					$incoming_email = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
					$phpmailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
				?>
			  <h3><?php echo $admin_language['email']; ?></h3>
			  <form action="" method="post">
			    <div class="form-group">
				  <label for="InputIncomingEmail"><?php echo $admin_language['incoming_email']; ?></label>
				  <input id="InputIncomingEmail" name="incoming_email" value="<?php echo htmlspecialchars($incoming_email[0]->value); ?>" class="form-control">
				</div>
				<div class="form-group">
				  <input type="hidden" name="use_external" value="1" />
				  <label for="InputEnableExternal"><?php echo $admin_language['use_php_mail']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['use_php_mail_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				  <input id="InputEnableExternal" name="use_external" type="checkbox" class="js-switch" value="0"<?php if($phpmailer[0]->value == '0'){ ?> checked<?php } ?> />
				</div>
			    <div class="form-group">
				  <label for="InputOutgoingEmail"><?php echo $admin_language['outgoing_email']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['outgoing_email_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				  <input id="InputOutgoingEmail" name="outgoing_email" value="<?php echo htmlspecialchars($outgoing_email[0]->value); ?>" class="form-control">
				</div>
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
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
	
	<script src="/core/assets/js/ckeditor.js"></script>
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});

	CKEDITOR.replace( 'InputTandC', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Subscript,Superscript'
		} );
	</script>
  </body>
</html>