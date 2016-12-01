<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin core settings page
 */

if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
$page = 'admin';
$admin_page = 'core';

// Query database for settings
$current_default_language = $queries->getWhere('settings', array('name', '=', 'language'));
$current_default_language = $current_default_language[0]->value;

?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php 
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php'); 
	?>
  
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
  
  </head>
  <body>
    <?php require('modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <h3><?php echo $language->get('admin', 'core'); ?></h3>
			  <?php if(!isset($_GET['view'])){ ?>
			  <div class="table-responsive">
			    <table class="table table-striped">
				  <tr>
				    <td><a href="<?php echo URL::build('/admin/core/', 'view=general'); ?>"><?php echo $language->get('admin', 'general_settings'); ?></a></td>
				  </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>"><?php echo $language->get('admin', 'custom_fields'); ?></a></td>
				  </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>"><?php echo $language->get('user', 'reactions'); ?></a></td>
				  </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/registration'); ?>"><?php echo $language->get('admin', 'registration'); ?></a></td>
				  </tr>
				</table>
			  </div>
			  <?php 
			  } else {
				  switch($_GET['view']){
					  case 'general':
						// Deal with input
						if(Input::exists()){
							if(Token::check(Input::get('token'))){
								// Validate input
								$validate = new Validate();
								
								$validation = $validate->check($_POST, array(
									'sitename' => array(
										'required' => true,
										'min' => 2,
										'max' => 64
									)
								));
								
								if($validation->passed()){
									// Update settings
									// Sitename
									$sitename_id = $queries->getWhere('settings', array('name', '=', 'sitename'));
									$sitename_id = $sitename_id[0]->id;
									
									$queries->update('settings', $sitename_id, array(
										'value' => Output::getClean(Input::get('sitename'))
									));
									
									// Update cache
									$cache->setCache('sitenamecache');
									$cache->store('sitename', Output::getClean(Input::get('sitename')));
									
									// Language
									// Get current default language
									$default_language = $queries->getWhere('languages', array('is_default', '=', 1));
									$default_language = $default_language[0];
									
									if($default_language->name != Input::get('language')){
										// The default language has been changed
										$queries->update('languages', $default_language->id, array(
											'is_default' => 0
										));
										
										$language_id = $queries->getWhere('languages', array('id', '=', Input::get('language')));
										$language_name = Output::getClean($language_id[0]->name);
										$language_id = $language_id[0]->id;
										
										$queries->update('languages', $language_id, array(
											'is_default' => 1
										));
										
										// Update cache
										$cache->setCache('languagecache');
										$cache->store('language', $language_name);
									}
									
									// Portal
									$portal_id = $queries->getWhere('settings', array('name', '=', 'portal'));
									$portal_id = $portal_id[0]->id;
									
									if($_POST['homepage'] == 'portal'){
										$use_portal = 1;
									} else $use_portal = 0;
									
									$queries->update('settings', $portal_id, array(
										'value' => $use_portal
									));
									
									// Update cache
									$cache->setCache('portal_cache');
									$cache->store('portal', $use_portal);
									
									// Post formatting
									$formatting_id = $queries->getWhere('settings', array('name', '=', 'formatting_type'));
									$formatting_id = $formatting_id[0]->id;
									
									$queries->update('settings', $formatting_id, array(
										'value' => Output::getClean(Input::get('formatting'))
									));
									
									// Update cache
									$cache->setCache('post_formatting');
									$cache->store('formatting', Output::getClean(Input::get('formatting')));
									
									// Friendly URLs
									if(Input::get('friendlyURL') == 'true') $friendly = 'true';
									else $friendly = 'false';
									
									if(is_writable(join(DIRECTORY_SEPARATOR, array('core', 'config.php')))){
										// Writable
										require(join(DIRECTORY_SEPARATOR, array('core', 'config.php')));
										
										// Make string to input
										$input_string = '<?php' . PHP_EOL .
														'$GLOBALS[\'config\'] = array(' . PHP_EOL .
														'    "mysql" => array(' . PHP_EOL .
														'        "host" => "' . Config::get('mysql/host') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL .
														'        "username" => "' . Config::get('mysql/username') . '", // Web server database username' . PHP_EOL .
														'        "password" => \'' . Config::get('mysql/password') . '\', // Web server database password' . PHP_EOL .
														'        "db" => "' . Config::get('mysql/db') . '", // Web server database name' . PHP_EOL .
														'        "prefix" => "nl2_" // Web server table prefix' . PHP_EOL .
														'    ),' . PHP_EOL .
														'    "remember" => array(' . PHP_EOL .
														'        "cookie_name" => "nl2", // Name for website cookies' . PHP_EOL .
														'        "cookie_expiry" => 604800' . PHP_EOL .
														'    ),' . PHP_EOL .
														'    "session" => array(' . PHP_EOL .
														'        "session_name" => "2user",' . PHP_EOL .
														'        "admin_name" => "2admin",' . PHP_EOL .
														'        "token_name" => "2token"' . PHP_EOL .
														'    ),' . PHP_EOL .
														'    "core" => array(' . PHP_EOL .
														'        "path" => "' . Config::get('core/path') . '",' . PHP_EOL .
														'        "friendly" => ' . $friendly . PHP_EOL .
														'    )' . PHP_EOL .
														');';
										
										$file = fopen(join(DIRECTORY_SEPARATOR, array('core', 'config.php')), 'w');
										fwrite($file, $input_string);
										fclose($file);
										
									} else $errors = array($language->get('admin', 'config_not_writable'));
									
									// Redirect in case URL type has changed
									if(!isset($errors)){
										if($friendly == 'true'){
											$redirect = URL::build('/admin/core', 'view=general', 'friendly');
										} else {
											$redirect = URL::build('/admin/core', 'view=general', 'non-friendly');
										}
										Redirect::to($redirect);
										die();
									}
									
								} else $errors = array($language->get('admin', 'missing_sitename'));
							} else {
								// Invalid token
								$errors = array($language->get('general', 'invalid_token'));
							}
						}
						?>
			  <form action="" method="post">
			    <?php if(isset($errors)){ ?><div class="alert alert-danger"><?php foreach($errors as $error) echo $error; ?></div><?php } ?>
			    <div class="form-group">
				  <?php
				  // Get site name
				  $sitename = $queries->getWhere('settings', array('name', '=', 'sitename'));
				  $sitename = $sitename[0];
				  ?>
			      <label for="inputSitename"><?php echo $language->get('admin', 'sitename'); ?></label>
			      <input type="text" class="form-control" name="sitename" id="inputSitename" value="<?php echo Output::getClean($sitename->value); ?>" />
				</div>
				<div class="form-group">
				  <label for="inputLanguage"><?php echo $language->get('admin', 'default_language'); ?></label> <span class="tag tag-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'default_language_help'); ?>"></i></span>
			      <div class="input-group">
				    <?php
				    // Get languages
				    $languages = $queries->getWhere('languages', array('id', '<>', 0));
				    ?>
				    <select name="language" class="form-control" id="inputLanguage">
				      <?php
					  foreach($languages as $item){
					  ?>
				      <option value="<?php echo $item->id; ?>"<?php if($item->is_default == 1){ ?> selected<?php } ?>><?php echo Output::getClean($item->name); ?></option>
				      <?php
					  }
					  ?>
				    </select>
				    <div class="input-group-btn">
				      <a class="btn btn-secondary" href="#"><i class="fa fa-plus-circle"></i></a>
				    </div>
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputHomepage"><?php echo $language->get('admin', 'homepage_type'); ?></label>
				  <?php
				  // Get portal setting
				  $portal = $queries->getWhere('settings', array('name', '=', 'portal'));
				  $portal = $portal[0];
				  ?>
				  <select name="homepage" class="form-control" id="inputHomepage">
				    <option value="default"<?php if($portal->value == 0){ ?> selected<?php } ?>><?php echo $language->get('admin', 'default'); ?></option>
				    <option value="portal"<?php if($portal->value == 1){ ?> selected<?php } ?>><?php echo $language->get('admin', 'portal'); ?></option>
				  </select>
				</div>
				<div class="form-group">
				  <?php
				  // Get post formatting setting
				  $cache->setCache('post_formatting');
				  $formatting = $cache->retrieve('formatting');
				  ?>
				  <label for="inputFormatting"><?php echo $language->get('admin', 'post_formatting_type'); ?></label>
				  <select name="formatting" class="form-control" id="inputFormatting">
				    <option value="html"<?php if($formatting == 'html'){ ?> selected<?php } ?>>HTML</option>
					<option value="markdown"<?php if($formatting == 'markdown'){ ?> selected<?php } ?>>Markdown</option>
				  </select>
				</div>
				<div class="form-group">
				  <?php
				  // Get friendly URL setting
				  $friendly_url = Config::get('core/friendly');
				  ?>
				  <label for="inputFormatting"><?php echo $language->get('admin', 'use_friendly_urls'); ?></label> <span class="tag tag-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'use_friendly_urls_help'); ?>"></i></span>
				  <select name="friendlyURL" class="form-control" id="inputFriendlyURL">
				    <option value="true"<?php if($friendly_url == true){ ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
					<option value="false"<?php if($friendly_url == false){ ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
				  </select>
				</div>
				<br />
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
			  </form>
						<?php
					  break;
					  
					  case 'profile':
						if(!isset($_GET['id']) && !isset($_GET['action'])){
							
							// Custom profile fields
							$profile_fields = $queries->getWhere('profile_fields', array('id', '<>', 0));
						?>
			  <h4 style="display:inline;"><?php echo $language->get('admin', 'custom_fields'); ?></h4>
			  <span class="pull-right">
			    <a class="btn btn-primary" href="<?php echo URL::build('/admin/core/', 'view=profile&amp;action=new'); ?>"><?php echo $language->get('admin', 'new_field'); ?></a>
			  </span>
			  <br /><br />
			  <table class="table">
			    <thead>
				  <tr>
				    <th><?php echo $language->get('admin', 'name'); ?></th>
					<th><?php echo $language->get('admin', 'type'); ?></th>
					<th><?php echo $language->get('admin', 'required'); ?></th>
					<th><?php echo $language->get('admin', 'public'); ?></th>
				  </tr>
				</thead>
				<tbody>
				  <?php 
				  if(count($profile_fields)){
					  foreach($profile_fields as $field){
				  ?>
				  <tr>
				    <td><a href="<?php echo URL::build('/admin/core/', 'view=profile&amp;id=' . $field->id); ?>"><?php echo Output::getClean($field->name); ?></a></td>
					<td><?php 
					switch($field->type){
						case 1:
							// Text field
							echo $language->get('admin', 'text');
						break;
						case 2:
							// Textarea
							echo $language->get('admin', 'textarea');
						break;
						case 3:
							// Date
							echo $language->get('admin', 'date');
						break;
					}?></td>
					<td><?php
					if($field->required == 1) echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
					else echo '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
					?></td>
					<td><?php
					if($field->public == 1) echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
					else echo '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
					?></td>
				  </tr>
				  <?php 
					  }
				  }
				  ?>
				</tbody>
			  </table>
						<?php
						} else {
							if(isset($_GET['action'])){
								
							} else if(isset($_GET['id'])) {
								// Editing field
								
							}
						}
					  break;
					  
					  case 'reactions':
						if(!isset($_GET['id']) && (!isset($_GET['action']))){
							// Get all reactions
							$reactions = $queries->getWhere('reactions', array('id', '<>', 0));
						?>
			  <h4 style="display:inline;"><?php echo $language->get('user', 'reactions'); ?></h4>
			  <span class="pull-right"><a class="btn btn-primary" href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;action=new'); ?>"><?php echo $language->get('admin', 'new_reaction'); ?></a></span>
			  <br /><br />
			  <table class="table">
				<thead>
				  <tr>
				    <th><?php echo $language->get('admin', 'name'); ?></th>
				    <th><?php echo $language->get('admin', 'icon'); ?></th>
					<th><?php echo $language->get('admin', 'type'); ?></th>
					<th><?php echo $language->get('admin', 'enabled'); ?></th>
				  </tr>
				</thead>
				<tbody>
				  <?php
				  if(count($reactions)){
					  foreach($reactions as $reaction){
				  ?>
				  <tr>
				    <td><a href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;id=' . $reaction->id); ?>"><?php echo Output::getClean($reaction->name); ?></a></td>
				    <td><?php echo $reaction->html; ?></td>
				    <td><?php if($reaction->type == 2) echo $language->get('admin', 'positive'); else if($reaction->type == 1) echo $language->get('admin', 'neutral'); else echo $language->get('admin', 'negative'); ?></td>
				    <td><?php if($reaction->enabled == 1){ ?><i class="fa fa-check-circle text-success" aria-hidden="true"></i><?php } else { ?><i class="fa fa-times-circle text-danger" aria-hidden="true"></i><?php } ?></td>
				  <?php
					  }
				  }
				  ?>
				  </tr>
				</tbody>
			  </table>
					<?php 
						} else { 
							if(isset($_GET['id']) && !isset($_GET['action'])){
								// Get reaction
								$reaction = $queries->getWhere('reactions', array('id', '=', $_GET['id']));
								if(!count($reaction)){
									// Reaction doesn't exist
									Redirect::to(URL::build('/admin/core/', 'view=reactions'));
									die();
									
								} else $reaction = $reaction[0];
								
								// Deal with input
								if(Input::exists()){
									if(Token::check(Input::get('token'))){
										// Validate input
										$validate = new Validate();
										$validation = $validate->check($_POST, array(
											'name' => array(
												'required' => true,
												'min' => 1,
												'max' => 16
											),
											'html' => array(
												'required' => true,
												'min' => 1,
												'max' => 255
											),
											'type' => array(
												'required' => true
											)
										));
										
										if($validation->passed()){
											// Check enabled status
											if(isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
											else $enabled = 0;
											
											// Update database
											$queries->update('reactions', $_GET['id'], array(
												'name' => Output::getClean(Input::get('name')),
												'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
												'type' => Input::get('type'),
												'enabled' => $enabled
											));
											
											$reaction = $queries->getWhere('reactions', array('id', '=', $_GET['id']));
											$reaction = $reaction[0];
										} else {
											// Validation error
										}
									} else {
										// Invalid token
									}
								}
					?>
			  <h4 style="display:inline;"><?php echo $language->get('admin', 'editing_reaction'); ?></h4>
			  <span class="pull-right">
			    <a href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;action=delete&amp;reaction=' . $reaction->id); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_deletion'); ?>');" class="btn btn-danger"><?php echo $language->get('general', 'delete'); ?></a>
			    <a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>" class="btn btn-warning"><?php echo $language->get('general', 'cancel'); ?></a>
			  </span>
			  <hr />
			  <form action="" method="post">
			    <div class="form-group">
			      <label for="InputReactionName"><?php echo $language->get('admin', 'name'); ?></label>
			      <input type="text" class="form-control" name="name" id="InputReactionName" placeholder="<?php echo $language->get('admin', 'name'); ?>" value="<?php echo Output::getClean($reaction->name); ?>">
				</div>
				
				<div class="form-group">
				  <label for="InputReactionHTML"><?php echo $language->get('admin', 'html'); ?></label>
				  <input type="text" class="form-control" name="html" id="InputReactionHTML" placeholder="<?php echo $language->get('admin', 'html'); ?>" value="<?php echo Output::getClean($reaction->html); ?>">
				</div>
				
				<div class="form-group">
				  <label for="InputReactionType"><?php echo $language->get('admin', 'type'); ?></label>
				  <select name="type" class="form-control" id="InputReactionType">
					<option value="2"<?php if($reaction->type == 2) echo ' selected'; ?>><?php echo $language->get('admin', 'positive'); ?></option>
					<option value="1"<?php if($reaction->type == 1) echo ' selected'; ?>><?php echo $language->get('admin', 'neutral'); ?></option>
					<option value="0"<?php if($reaction->type == 0) echo ' selected'; ?>><?php echo $language->get('admin', 'negative'); ?></option>
				  </select>
				</div>
				
				<div class="form-group">
				  <label for="InputEnabled"><?php echo $language->get('admin', 'enabled'); ?></label>
				  <input type="checkbox" name="enabled" class="js-switch"<?php if($reaction->enabled == 1) echo ' checked'; ?>/>
				</div>
				
				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
				</div>
			 </form>
						<?php
							} else if(isset($_GET['action'])){
								if($_GET['action'] == 'new'){
									// Deal with input
									if(Input::exists()){
										if(Token::check(Input::get('token'))){
											// Validate input
											$validate = new Validate();
											$validation = $validate->check($_POST, array(
												'name' => array(
													'required' => true,
													'min' => 1,
													'max' => 16
												),
												'html' => array(
													'required' => true,
													'min' => 1,
													'max' => 255
												),
												'type' => array(
													'required' => true
												)
											));
											
											if($validation->passed()){
												// Check enabled status
												if(isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
												else $enabled = 0;
												
												// Update database
												$queries->create('reactions', array(
													'name' => Output::getClean(Input::get('name')),
													'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
													'type' => Input::get('type'),
													'enabled' => $enabled
												));
												
												Redirect::to(URL::build('/admin/core/', 'view=reactions'));
												die();
											} else {
												// Validation error
											}
										} else {
											// Invalid token
										}
									}
									?>
			  <h4 style="display:inline;"><?php echo $language->get('admin', 'creating_reaction'); ?></h4>
			  <span class="pull-right">
			    <a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>" class="btn btn-warning"><?php echo $language->get('general', 'cancel'); ?></a>
			  </span>
			  <hr />
			  <form action="" method="post">
			    <div class="form-group">
			      <label for="InputReactionName"><?php echo $language->get('admin', 'name'); ?></label>
			      <input type="text" class="form-control" name="name" id="InputReactionName" placeholder="<?php echo $language->get('admin', 'name'); ?>" value="<?php echo Output::getClean(Input::get('name')); ?>">
				</div>
				
				<div class="form-group">
				  <label for="InputReactionHTML"><?php echo $language->get('admin', 'html'); ?></label>
				  <input type="text" class="form-control" name="html" id="InputReactionHTML" placeholder="<?php echo $language->get('admin', 'html'); ?>" value="<?php echo Output::getClean(Input::get('html')); ?>">
				</div>
				
				<div class="form-group">
				  <label for="InputReactionType"><?php echo $language->get('admin', 'type'); ?></label>
				  <select name="type" class="form-control" id="InputReactionType">
					<option value="2"><?php echo $language->get('admin', 'positive'); ?></option>
					<option value="1"><?php echo $language->get('admin', 'neutral'); ?></option>
					<option value="0"><?php echo $language->get('admin', 'negative'); ?></option>
				  </select>
				</div>
				
				<div class="form-group">
				  <label for="InputEnabled"><?php echo $language->get('admin', 'enabled'); ?></label>
				  <input type="checkbox" name="enabled" class="js-switch" />
				</div>
				
				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
				</div>
			 </form>
									<?php
								} else if($_GET['action'] == 'delete'){
									// Check specified reaction exists
									if(!isset($_GET['reaction']) || !is_numeric($_GET['reaction'])){
										Redirect::to(URL::build('/admin/core/', 'view=reactions'));
										die();
									}

									// Delete reaction
									$queries->delete('reactions', array('id', '=', $_GET['reaction']));
									
									// Redirect
									Redirect::to(URL::build('/admin/core/', 'view=reactions'));
									die();
								}
							}
						}
					  break;
					  
					  default:
						Redirect::to(URL::build('/admin/core'));
						die();
					  break;
				  }
			  }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
	
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html);
	});
	</script>
	
  </body>
</html>