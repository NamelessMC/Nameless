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
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['core'];
	
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
	// "Core" page
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
			<li<?php if(!isset($_GET['view'])){ ?> class="active"<?php } ?>><a href="/admin/core"><?php echo $admin_language['general_settings']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'modules'){ ?> class="active"<?php } ?>><a href="/admin/core/?view=modules"><?php echo $admin_language['modules']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'email'){ ?> class="active"<?php } ?>><a href="/admin/core/?view=email"><?php echo $admin_language['email']; ?></a></li>
		    <li<?php if(isset($_GET['view']) && $_GET['view'] == 'pages'){ ?> class="active"<?php } ?>><a href="/admin/core/?view=pages"><?php echo $admin_language['pages']; ?></a></li>
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
							
							if(Input::get('followers') == 'on'){
								$followers = 1;
							} else {
								$followers = 0;
							}
							
							$followers_id = $queries->getWhere('settings', array('name', '=', 'followers'));
							$followers_id = $followers_id[0]->id;
							
							$queries->update('settings', $followers_id, array(
								'value' => $followers
							));
							
							Session::flash('general_settings', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
							echo '<script data-cfasync="false">window.location.replace("/admin/core");</script>';
							die();
							
						} else {
							Session::flash('general_settings', '<div class="alert alert-danger">' . $admin_language['unable_to_update_settings'] . '</div>');
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
			  <?php
			  $followers = $queries->getWhere('settings', array('name', '=', 'followers'));
			  $followers = $followers[0]->value;
			  ?>
			  <div class="form-group">
				<label for="followers"><?php echo $admin_language['use_followers']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['use_followers_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				<input id="followers" name="followers" type="checkbox" class="js-switch" <?php if($followers == 1){ ?>checked <?php } ?>/>
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
				<span class="pull-right"><a href="/admin/core/?view=modules&amp;deactivate=<?php echo htmlspecialchars($module->name); ?>" style="width: 100px;" class="btn btn-danger"><?php echo $admin_language['deactivate']; ?></a> <a href="/admin/core/?view=modules&action=edit&module=<?php echo htmlspecialchars($module->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a></span>
						<?php } else { ?>
				<span class="pull-right"><a href="/admin/core/?view=modules&amp;activate=<?php echo htmlspecialchars($module->name); ?>" style="width: 100px;" class="btn btn-success"><?php echo $admin_language['activate']; ?></a> <a href="/admin/core/?view=modules&action=edit&module=<?php echo htmlspecialchars($module->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a></span>
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
							echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules\');</script>';
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
						echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules\');</script>';
						die();
					} else if(isset($_GET['deactivate'])){
						// Disable a module
						// First, check the module actually exists
						$module = $queries->getWhere('core_modules', array('name', '=', htmlspecialchars($_GET['deactivate'])));
						if(!count($module)){
							Session::flash('core_modules', '<div class="alert alert-danger">' . $admin_language['module_not_exist'] . '</div>');
							echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules\');</script>';
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
						echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules\');</script>';
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
									Session::flash('google_analytics_settings', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
								} else {
									// Invalid token
									Session::flash('google_analytics_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}
							
							// Get the current tracking code
							$settings = $queries->getWhere('settings', array('name', '=', 'ga_script'));
							
							// Show settings for Google Analytics
						?>
						<h4><?php echo $admin_language['editing_google_analytics_module']; ?></h4>
						<?php
						if(Session::exists('google_analytics_settings')){
							echo Session::flash('google_analytics_settings');
						}
						?>
						<form action="" method="post">
						  <label for="tracking"><?php echo $admin_language['tracking_code']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['tracking_code_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
						  <textarea id="tracking" name="tracking" class="form-control" rows="5"><?php echo $settings[0]->value; ?></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<br />
						<em><?php echo $admin_language['google_analytics_help']; ?></em>
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
									
									// Twitter dark theme
									$twitter_dark_theme = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
									$twitter_dark_theme = $twitter_dark_theme[0]->id;
									
									if(isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) $theme = 'dark';
									else $theme = 'light';
									
									$queries->update('settings', $twitter_dark_theme, array(
										'value' => $theme
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
									Session::flash('social_media_links', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
								} else {
									// Invalid token
									Session::flash('social_media_links', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}

							// Show settings for social media links
							// Get values from database
							$youtube_url = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
							$twitter_url = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
							$twitter_style = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
							$gplus_url = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
							$fb_url = $queries->getWhere('settings', array('name', '=', 'fb_url'));
						?>
						<h4><?php echo $admin_language['social_media_links']; ?></h4>
						<?php
						if(Session::exists('social_media_links')){
							echo Session::flash('social_media_links');
						}
						?>
						<form action="" method="post">
							<div class="form-group">
								<label for="InputYoutube"><?php echo $admin_language['youtube_url']; ?></label>
								<input type="text" name="youtubeurl" class="form-control" id="InputYoutube" placeholder="<?php echo $admin_language['youtube_url']; ?>" value="<?php echo htmlspecialchars($youtube_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputTwitter"><?php echo $admin_language['twitter_url']; ?></label>
								<input type="text" name="twitterurl" class="form-control" id="InputTwitter" placeholder="<?php echo $admin_language['twitter_url']; ?>" value="<?php echo htmlspecialchars($twitter_url[0]->value); ?>">
							</div>
							<div class="form-group">
							  <label for="InputTwitterStyle"><?php echo $admin_language['twitter_dark_theme']; ?></label>
							  <input id="InputTwitterStyle" name="twitter_dark_theme" type="checkbox" class="js-switch" value="1" <?php if($twitter_style[0]->value == 'dark') echo 'checked'; ?>/>
							</div>
							<div class="form-group">
								<label for="InputGPlus"><?php echo $admin_language['google_plus_url']; ?></label>
								<input type="text" name="gplusurl" class="form-control" id="InputGPlus" placeholder="<?php echo $admin_language['google_plus_url']; ?>" value="<?php echo htmlspecialchars($gplus_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputFacebook"><?php echo $admin_language['facebook_url']; ?></label>
								<input type="text" name="fburl" class="form-control" id="InputFacebook" placeholder="<?php echo $admin_language['facebook_url']; ?>" value="<?php echo htmlspecialchars($fb_url[0]->value); ?>">
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
									Session::flash('registration_settings', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
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
						<h4><?php echo $admin_language['registration']; ?></h4>
						<?php
						if(Session::exists('registration_settings')){
							echo Session::flash('registration_settings');
						}
						?>
						<em><?php echo $admin_language['registration_warning']; ?></em><hr>
						<form action="" method="post">
							<div class="form-group">
							    <input type="hidden" name="enable_recaptcha" value="0" />
								<label for="InputEnableRecaptcha"><?php echo $admin_language['google_recaptcha']; ?></label>
								<input id="InputEnableRecaptcha" name="enable_recaptcha" type="checkbox" class="js-switch" value="1"<?php if($recaptcha_id[0]->value == 'true'){ ?> checked<?php } ?> />
							</div>
							<div class="form-group">
								<label for="InputRecaptcha"><?php echo $admin_language['recaptcha_site_key']; ?></label>
								<input type="text" name="recaptcha" class="form-control" id="InputRecaptcha" placeholder="<?php echo $admin_language['recaptcha_site_key']; ?>" value="<?php echo htmlspecialchars($recaptcha_key[0]->value); ?>">
							</div>
							<div class="form-group">
							    <label for="InputRecaptchaSecret"><?php echo $admin_language['recaptcha_secret_key']; ?></label>
								<input type="text" name="recaptcha_secret" class="form-control" id="InputRecaptchaSecret" placeholder="<?php echo $admin_language['recaptcha_secret_key']; ?>" value="<?php echo htmlspecialchars($recaptcha_secret[0]->value); ?>">
							</div>
							<div class="form-group">
							    <label for="InputTandC"><?php echo $admin_language['registration_terms_and_conditions']; ?></label>
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
									if(Input::get('action') == 'discord'){
										// Update Discord ID
										$discord_id = Input::get('discord_id');
										if(isset($discord_id)){
											$discord_update_id = $queries->getWhere('settings', array('name', '=', 'discord'));
											$discord_update_id = $discord_update_id[0]->id;
											$queries->update('settings', $discord_update_id, array(
												'value' => $discord_id
											));
										}
									} else {
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
									}
								} else {
									// Invalid token
									Session::flash('voice_server_settings', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
								}
							}
							
							// XSS token
							$token = Token::generate();
							
							require_once('core/voice_server.php'); // for credentials
						?>
						<h4><?php echo $admin_language['voice_server_module']; ?></h4>
						<?php
						if(Session::exists('voice_server_settings')){
							echo Session::flash('voice_server_settings');
						}
						// Get Discord ID
						$discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
						$discord_id = htmlspecialchars($discord_id[0]->value);
						?>
						<div class="alert alert-info"><?php echo $admin_language['only_works_with_teamspeak']; ?></div>
						<h3>Discord</h3>
						<form action="" method="post">
						  <div class="form-group">
						    <label for="discord_id"><?php echo $admin_language['discord_id']; ?></label>
							<input type="text" class="form-control" name="discord_id" id="discord_id" value="<?php echo $discord_id; ?>" placeholder="<?php echo $admin_language['discord_id']; ?>" />
						  </div>
						  <div class="form-group">
						    <input type="hidden" name="action" value="discord">
							<input type="hidden" name="token" value="<?php echo $token; ?>">
							<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						  </div>
						</form>
						<hr />
						<h3>TeamSpeak</h3>
						<strong><?php echo $admin_language['voice_server_help']; ?></strong>
						<br /><br />
						<form action="" method="post">
						  <div class="form-group">
						    <label for="username"><?php echo $user_language['username']; ?></label>
							<input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($voice_server_username); ?>" placeholder="<?php echo $user_language['username']; ?>">
						  </div>
						  <div class="form-group">
						    <label for="password"><?php echo $user_language['password']; ?></label>
							<input type="password" class="form-control" name="password" id="password" value="<?php echo htmlspecialchars($voice_server_password); ?>" placeholder="<?php echo $user_language['password']; ?>">
						  </div>
						  <div class="form-group">
						    <label for="ip"><?php echo $admin_language['ip_without_port']; ?></label>
							<input type="text" class="form-control" name="ip" id="ip" value="<?php echo htmlspecialchars($voice_server_ip); ?>" placeholder="<?php echo $admin_language['ip_without_port']; ?>">
						  </div>
						  <div class="form-group">
						    <label for="port"><?php echo $admin_language['voice_server_port']; ?></label>
							<input type="text" class="form-control" name="port" id="port" value="<?php echo htmlspecialchars($voice_server_port); ?>" placeholder="<?php echo $admin_language['voice_server_port']; ?>">
						  </div>
						  <div class="form-group">
						    <label for="virtual_port"><?php echo $admin_language['virtual_port']; ?></label>
							<input type="text" class="form-control" name="virtual_port" id="virtual_port" value="<?php echo htmlspecialchars($voice_virtual_server_port); ?>" placeholder="<?php echo $admin_language['virtual_port']; ?>">
						  </div>
						  <input type="hidden" name="action" value="teamspeak">
						  <input type="hidden" name="token" value="<?php echo $token; ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<?php
						} else if($_GET['module'] == 'Staff_Applications'){
						?>
						<h4><?php echo $navbar_language['staff_apps']; ?></h4>
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
											
											// Link location
											$c->setCache('staffapps');
											$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
											
										}
									} else {
										Session::flash('apps_post_success', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
										echo '<script data-cfasync="false">window.location.replace("/admin/core/?view=modules&action=edit&module=Staff_Applications")</script>';
										die();
									}
								}
								
								// Query groups again to get updated values
								$groups = $queries->getWhere('groups', array('id', '<>', '0'));
						?>
						<form role="form" action="" method="post">
						  <div class="form-group">
						    <strong><?php echo $admin_language['permissions']; ?></strong><br /><br />
						    <div class="row">
						      <div class="col-md-8">
							    <div class="col-md-6">
							      <?php echo $admin_language['group']; ?>
							    </div>
							    <div class="col-md-3">
							      <?php echo $admin_language['view_applications']; ?>
							    </div>
							    <div class="col-md-3">
							      <?php echo $admin_language['accept_reject_applications']; ?>
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
						  </div>
						  
						  <div class="form-group">
						    <label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
							<?php
							// Get position of link
							$c->setCache('staffapps');
							if($c->isCached('linklocation')){
								$link_location = $c->retrieve('linklocation');
							} else {
								$c->store('linklocation', 'navbar');
								$link_location = 'navbar';
							}
							?>
						    <select name="linkposition" id="InputLinkPosition" class="form-control">
							  <option value="navbar" <?php if($link_location == 'navbar'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
							  <option value="more" <?php if($link_location == 'more'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_more']; ?></option>
							  <option value="footer" <?php if($link_location == 'footer'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
							  <option value="none" <?php if($link_location == 'none'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
							</select>
						  </div>
						  
						  <div class="form-group">
						    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						    <input type="submit" class="btn btn-default" value="<?php echo $general_language['submit']; ?>">
						  </div>
						</form>
						
						<br /><br />
						<strong><?php echo $admin_language['questions']; ?></strong> <span class="pull-right"><a href="/admin/core/?view=modules&amp;action=edit&amp;module=Staff_Applications&amp;module_action=new" class="btn btn-primary"><?php echo $admin_language['new_question']; ?></a></span><br />
						<?php 
						// Get a list of questions
						$questions = $queries->getWhere('staff_apps_questions', array('id', '<>', 0));
						if(count($questions)){
						?>
						<table class="table table-striped">
						  <tr>
							<th><?php echo $admin_language['name']; ?></th>
							<th><?php echo $admin_language['question']; ?></th>
							<th><?php echo $admin_language['type']; ?></th>
							<th><?php echo $admin_language['options']; ?></th>
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
							echo $admin_language['no_questions'];
						}
						?>
						</table>
						<?php 
							} else if(isset($_GET['question']) && !isset($_GET['module_action'])) { 
								// Get the question
								if(!is_numeric($_GET['question'])){
									echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								$question_id = $_GET['question'];
								$question = $queries->getWhere('staff_apps_questions', array('id', '=', $question_id));
								
								// Does the question exist?
								if(!count($question)){
									echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
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

											Session::flash('apps_post_success', '<div class="alert alert-info">' . $admin_language['successfully_updated'] . '</div>');
											echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
											die();
										}  else {
											// errors
											$error = array();
											foreach($validation->errors() as $item){
												if(strpos($item, 'is required') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_required'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_required'];
														break;
													}
												} else if(strpos($item, 'minimum') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_minimum'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_minimum'];
														break;
													}
												} else if(strpos($item, 'maximum') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_maximum'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_maximum'];
														break;
													}
												}
											}
										}
								
									} else {
										// Invalid token
										$error[] = $admin_language['invalid_token'];
									}
								}
						
								$question = $question[0];
						?>
						<!-- Errors? Display here -->
						<?php
						if(isset($error)){
						?>
						<div class="alert alert-danger">
						<?php
							foreach($error as $item){
								echo $item . '<br />';
							}
						?>
						</div>
						<?php
						}
						?>
						<strong><?php echo $admin_language['editing_question']; ?></strong>
						<span class="pull-right"><a href="/admin/core/?view=modules&amp;action=edit&amp;module=Staff_Applications&amp;question=<?php echo $question->id; ?>&amp;module_action=delete" onclick="return confirm('<?php echo $forum_language['confirm_cancellation']; ?>');" class="btn btn-danger"><?php echo $admin_language['delete_question']; ?></a></span>
						<br /><br />
						
						<form method="post" action="">
						  <label for="name"><?php echo $admin_language['name']; ?></label>
						  <input class="form-control" type="text" name="name" id="name" placeholder="<?php echo $admin_language['name']; ?>" value="<?php echo htmlspecialchars($question->name); ?>">
						  <br />
						  <label for="question"><?php echo $admin_language['question']; ?></label>
						  <input class="form-control" type="text" name="question" id="question" placeholder="<?php echo $admin_language['question']; ?>" value="<?php echo htmlspecialchars($question->question); ?>">
						  <br />
						  <label for="type"><?php echo $admin_language['type']; ?></label>
						  <select name="type" id="type" class="form-control">
							<option value="1"<?php if($question->type == 1){ ?> selected<?php } ?>><?php echo $admin_language['dropdown']; ?></option>
							<option value="2"<?php if($question->type == 2){ ?> selected<?php } ?>><?php echo $admin_language['text']; ?></option>
							<option value="3"<?php if($question->type == 3){ ?> selected<?php } ?>><?php echo $admin_language['textarea']; ?></option>
						  </select>
						  <br />
						  <label for="options"><?php echo $admin_language['options']; ?> - <em><?php echo $admin_language['options_help']; ?></em></label>
						  <?php
						  // Get already inputted options
						  if($question->options == null){
							  $options = '';
						  } else {
							  $options = str_replace(',', "\n", htmlspecialchars($question->options));
						  }
						  ?>
						  <textarea rows="5" class="form-control" name="options" id="options" placeholder="<?php echo $admin_language['options']; ?>"><?php echo $options; ?></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
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

											Session::flash('apps_post_success', '<div class="alert alert-info">' . $admin_language['successfully_updated'] . '</div>');
											echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
											die();
										} else {
											// errors
											$error = array();
											foreach($validation->errors() as $item){
												if(strpos($item, 'is required') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_required'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_required'];
														break;
													}
												} else if(strpos($item, 'minimum') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_minimum'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_minimum'];
														break;
													}
												} else if(strpos($item, 'maximum') !== false){
													switch($item){
														case (strpos($item, 'name') !== false):
															$error[] = $admin_language['name_maximum'];
														break;
														case (strpos($item, 'question') !== false):
															$error[] = $admin_language['question_maximum'];
														break;
													}
												}
											}
										}
										
									} else {
										// Invalid token
										$error[] = $admin_language['invalid_token'];
									}
								}

						?>
						<!-- Errors? Display here -->
						<?php
						if(isset($error)){
						?>
						<div class="alert alert-danger">
						<?php
							foreach($error as $item){
								echo $item . '<br />';
							}
						?>
						</div>
						<?php
						}
						?>
						<strong><?php echo $admin_language['new_question']; ?></strong><br /><br />
						
						<form method="post" action="">
						  <label for="name"><?php echo $admin_language['name']; ?></label>
						  <input class="form-control" type="text" name="name" id="name" placeholder="<?php echo $admin_language['name']; ?>">
						  <br />
						  <label for="question"><?php echo $admin_language['question']; ?></label>
						  <input class="form-control" type="text" name="question" id="question" placeholder="<?php echo $admin_language['question']; ?>">
						  <br />
						  <label for="type"><?php echo $admin_language['type']; ?></label>
						  <select name="type" id="type" class="form-control">
							<option value="1"><?php echo $admin_language['dropdown']; ?></option>
							<option value="2"><?php echo $admin_language['text']; ?></option>
							<option value="3"><?php echo $admin_language['textarea']; ?></option>
						  </select>
						  <br />
						  <label for="options"><?php echo $admin_language['options']; ?> - <em><?php echo $admin_language['options_help']; ?></em></label>
						  <textarea rows="5" class="form-control" name="options" id="options" placeholder="<?php echo $admin_language['options']; ?>"></textarea>
						  <br />
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
						</form>
						<?php 
							} else if(isset($_GET['module_action']) && $_GET['module_action'] == 'delete' && isset($_GET['question'])) {
								// Get the question
								if(!is_numeric($_GET['question'])){
									echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								$question_id = $_GET['question'];
								$question = $queries->getWhere('staff_apps_questions', array('id', '=', $question_id));
								
								// Does the question exist?
								if(!count($question)){
									echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
									die();
								}
								
								// Exists, we can delete it
								$queries->delete('staff_apps_questions', array('id', '=', $question_id));
								
								Session::flash('apps_post_success', '<div class="alert alert-info">' . $admin_language['question_deleted'] . '</div>');
								echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=modules&action=edit&module=Staff_Applications\');</script>';
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
								
								$verification_id = $queries->getWhere('settings', array('name', '=', 'email_verification'));
								$verification_id = $verification_id[0]->id;
								$queries->update('settings', $verification_id, array(
									'value' => Input::get('enable_verification')
								));
								
								// Email setings
								if($_POST['username'] || $_POST['password'] || $_POST['name'] || $_POST['host']){
									// Update config
									// Check config is writable
									// Generate config path
									$config_path = join(DIRECTORY_SEPARATOR, array('core', 'email.php'));
									
									if(is_writable($config_path)){
										// Writable
										require('core/email.php');
										
										// Make string to input
										$input_string = '<?php' . PHP_EOL . 
														'$GLOBALS[\'email\'] = array(' . PHP_EOL .
														'    \'username\' => \'' . str_replace('\'', '\\\'', (isset($_POST['username']) ? $_POST['username'] : $GLOBALS['email']['username'])) . '\',' . PHP_EOL .
														'    \'password\' => \'' . str_replace('\'', '\\\'', ((isset($_POST['password']) && !empty($_POST['password'])) ? $_POST['password'] : $GLOBALS['email']['password'])) . '\',' . PHP_EOL .
														'    \'name\' => \'' . str_replace('\'', '\\\'', (isset($_POST['name']) ? $_POST['name'] : $GLOBALS['email']['name'])) . '\',' . PHP_EOL .
														'    \'host\' => \'' . str_replace('\'', '\\\'', (isset($_POST['host']) ? $_POST['host'] : $GLOBALS['email']['host'])) . '\',' . PHP_EOL .
														'    \'port\' => ' . str_replace('\'', '\\\'', $GLOBALS['email']['port']) . ',' . PHP_EOL .
														'    \'secure\' => \'' . str_replace('\'', '\\\'', $GLOBALS['email']['secure']) . '\',' . PHP_EOL .
														'    \'smtp_auth\' => ' . (($GLOBALS['email']['smtp_auth']) ? 'true' : 'false') . '' . PHP_EOL .
														');';
										
										$file = fopen($config_path, 'w');
										fwrite($file, $input_string);
										fclose($file);
										
									} else {
										// Not writable
										echo '<div class="alert alert-danger">' . $admin_language['email_config_not_writable'] . '</div>';
									}
								}
								
								
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
					$verification = $queries->getWhere('settings', array('name', '=', 'email_verification'));
					
					// Require email settings
					require('core/email.php');
				?>
			  <h3><?php echo $admin_language['email']; ?></h3>
			  <form action="" method="post">
				<div class="form-group">
				  <input type="hidden" name="enable_verification" value="0" />
				  <label for="InputEnableVerification"><?php echo $admin_language['enable_mail_verification']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['enable_email_verification_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				  <input id="InputEnableVerification" name="enable_verification" type="checkbox" class="js-switch" value="1"<?php if($verification[0]->value == '1'){ ?> checked<?php } ?> />
				</div>
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
				<hr />
				<p><?php echo $admin_language['explain_email_settings']; ?></p>
				<div class="form-group">
				  <label for="inputUsername">Username</label>
				  <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($GLOBALS['email']['username']); ?>" id="inputUsername">
				</div>
				<div class="form-group">
				  <label for="inputPassword">Password</label>
				  <input class="form-control" type="password" name="password" id="inputPassword">
				</div>
				<div class="form-group">
				  <label for="inputName">Name</label>
				  <input class="form-control" type="text" name="name" value="<?php echo htmlspecialchars($GLOBALS['email']['name']); ?>" id="inputName">
				</div>
				<div class="form-group">
				  <label for="inputHost">Host</label>
				  <input class="form-control" type="text" name="host" value="<?php echo htmlspecialchars($GLOBALS['email']['host']); ?>" id="inputHost">
				</div>
				<hr />
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			  </form>
				<?php
				} else if($_GET['view'] == 'pages'){
					// Pages
					if(Input::exists()){
						if(Token::check(Input::get('token'))){
							if(Input::get('action') == 'play'){
								$play_page_enabled = $queries->getWhere('settings', array('name', '=', 'play_page_enabled'));
								$play_page_enabled_id = $play_page_enabled[0]->id;
								$play_page_enabled = $play_page_enabled[0]->value;
								
								if($play_page_enabled == '1'){
									$play_page_enabled = '0';
								} else {
									$play_page_enabled = '1';
								}
								
								$queries->update('settings', $play_page_enabled_id, array(
									'value' => $play_page_enabled
								));
								
								echo '<script data-cfasync="false">window.location.replace(\'/admin/core/?view=pages\');</script>';
								die();
								
							} else if(Input::get('action') == 'maintenance'){
								$maintenance_enabled = $queries->getWhere('settings', array('name', '=', 'maintenance'));
								$maintenance_enabled_id = $maintenance_enabled[0]->id;
								$maintenance_enabled = $maintenance_enabled[0]->value;
								
								if($maintenance_enabled == 'true'){
									$maintenance_enabled = 'false';
								} else {
									$maintenance_enabled = 'true';
								}
								
								$queries->update('settings', $maintenance_enabled_id, array(
									'value' => $maintenance_enabled
								));
							}
						}
					}
					
					$play_page_enabled = $queries->getWhere('settings', array('name', '=', 'play_page_enabled'));
					$play_page_enabled = $play_page_enabled[0]->value;
				
					$maintenance_enabled = $queries->getWhere('settings', array('name', '=', 'maintenance'));
					$maintenance_enabled = $maintenance_enabled[0]->value;
					
					// Generate token
					$token = Token::generate();
				?>
			  <h3><?php echo $admin_language['pages']; ?></h3>
			  <p><?php echo $admin_language['enable_or_disable_pages']; ?></p>
			  <strong><?php echo $navbar_language['play']; ?>:</strong>
			  <form name="play" style="display: inline;" action="" method="post">
			    <input type="hidden" name="action" value="play" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<a href="#" onclick="document.forms['play'].submit();"><?php if($play_page_enabled != '1') echo $admin_language['enable']; else echo $admin_language['disable']; ?></a>
			  </form>
			  <br />
			  <strong><?php echo $admin_language['maintenance_mode']; ?>:</strong>
			  <form name="maintenance" style="display: inline;" action="" method="post">
			    <input type="hidden" name="action" value="maintenance" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<a href="#" onclick="document.forms['maintenance'].submit();"><?php if($maintenance_enabled != 'true') echo $admin_language['enable']; else echo $admin_language['disable']; ?></a>
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
