<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin core settings page
 */

// Can the user view the AdminCP?
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
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
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
                        <td><a href="<?php echo URL::build('/admin/core/', 'view=maintenance'); ?>"><?php echo $language->get('admin', 'debugging_and_maintenance'); ?></a></td>
                    </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>"><?php echo $language->get('user', 'reactions'); ?></a></td>
				  </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/registration'); ?>"><?php echo $language->get('admin', 'registration'); ?></a></td>
				  </tr>
				  <tr>
					<td><a href="<?php echo URL::build('/admin/core/', 'view=social'); ?>"><?php echo $language->get('admin', 'social_media'); ?></a></td>
				  </tr>
				</table>
			  </div>
			  <?php
			  } else {
				  switch($_GET['view']){
					  case 'general':
					    if(isset($_GET['do']) && $_GET['do'] == 'installLanguage'){
							// Install new language
							$languages = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*' , GLOB_ONLYDIR);
							foreach($languages as $item){
								$folders = explode(DIRECTORY_SEPARATOR, $item);

								// Is it already in the database?
								$exists = $queries->getWhere('languages', array('name', '=', Output::getClean($folders[2])));
								if(!count($exists)){
									// No, add it now
									$queries->create('languages', array(
										'name' => Output::getClean($folders[2])
									));
								}
							}

							Session::flash('general_language', $language->get('admin', 'installed_languages'));
							Redirect::to(URL::build('/admin/core/', 'view=general'));
							die();
						}

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

									// Timezone
									$timezone_id = $queries->getWhere('settings', array('name', '=', 'timezone'));
									$timezone_id = $timezone_id[0]->id;

									try {
										$queries->update('settings', $timezone_id, array(
											'value' => Output::getClean($_POST['timezone'])
										));

										// Cache
										$cache->setCache('timezone_cache');
										$cache->store('timezone', Output::getClean($_POST['timezone']));

									} catch(Exception $e){
										$errors = array($e->getMessage());
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
									if(Input::get('friendlyURL') == 'true') $friendly = true;
									else $friendly = false;

									if(is_writable(join(DIRECTORY_SEPARATOR, array('core', 'config.php')))){

									// Require config
									if(isset($path)){
										$loadedConfig = json_decode(file_get_contents($path . 'core/config.php'), true);
									} else {
										$loadedConfig = json_decode(file_get_contents(ROOT_PATH . '/core/config.php'), true);
									}

									if(is_array($loadedConfig)) {
											$GLOBALS['config'] = $loadedConfig;
									}

									// Make string to input

									Config::set('core/friendly', $friendly);

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
			    <?php if(Session::exists('general_language')){ ?><div class="alert alert-success"><?php echo Session::flash('general_language'); ?></div><?php } ?>
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
				  <label for="inputLanguage"><?php echo $language->get('admin', 'default_language'); ?></label> <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'default_language_help'); ?>"></i></span>
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
				      <a class="btn btn-secondary" href="<?php echo URL::build('/admin/core/', 'view=general&amp;do=installLanguage'); ?>"><i class="fa fa-plus-circle"></i></a>
				    </div>
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputTimezone"><?php echo $language->get('admin', 'default_timezone'); ?></label>
				  <?php
				  // Get timezone setting
				  $timezone = $queries->getWhere('settings', array('name', '=', 'timezone'));
				  $timezone = $timezone[0];
				  ?>
				  <select name="timezone" class="form-control" id="inputTimezone">
				    <?php foreach(Util::listTimezones() as $key => $item){ ?>
				    <option value="<?php echo $key; ?>"<?php if($timezone->value == $key){ ?> selected<?php } ?>>(<?php echo $item['offset']; ?>) - <?php echo $item['name']; ?> (<?php echo $item['time']; ?>)</option>
					<?php } ?>
				  </select>
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
				  <label for="inputFormatting"><?php echo $language->get('admin', 'use_friendly_urls'); ?></label> <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'use_friendly_urls_help'); ?>"></i></span>
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
				    <th><?php echo $language->get('admin', 'field_name'); ?></th>
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
								if($_GET['action'] == 'new'){
									// New field
									if(Input::exists()){
										if(Token::check(Input::get('token'))){
											// Validate input
											$validate = new Validate();

											$validation = $validate->check($_POST, array(
												'name' => array(
													'required' => true,
													'min' => 2,
													'max' => 16
												),
												'type' => array(
													'required' => true
												)
											));

											if($validation->passed()){
												// Input into database
												try {
													// Get whether required/public/forum post options are enabled or not
													if(isset($_POST['required']) && $_POST['required'] == 'on') $required = 1;
													else $required = 0;

													if(isset($_POST['public']) && $_POST['public'] == 'on') $public = 1;
													else $public = 0;

													if(isset($_POST['forum']) && $_POST['forum'] == 'on') $forum_posts = 1;
													else $forum_posts = 0;

													// Insert into database
													$queries->create('profile_fields', array(
														'name' => Output::getClean(Input::get('name')),
														'type' => Input::get('type'),
														'public' => $public,
														'required' => $required,
														'description' => Output::getClean(Input::get('description')),
														'forum_posts' => $forum_posts
													));

													// Redirect
													Redirect::to(URL::build('/admin/core/', 'view=profile'));
													die();

												} catch(Exception $e){
													$error = $e->getMessage();
												}

											} else {
												// Display errors
												$error = $language->get('admin', 'profile_field_error');
											}
										} else {
											// Invalid token
											$error = $language->get('admin', 'invalid_token');
										}
									}

									?>
			  <h4 style="display:inline;"><?php echo $language->get('admin', 'creating_profile_field'); ?></h4>
			  <span class="pull-right">
			    <a class="btn btn-danger" href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
			  </span>
			  <br /><br />
			  <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
			  <form action="" method="post">
			    <div class="form-group">
				  <label for="inputName"><?php echo $language->get('admin', 'field_name'); ?></label>
				  <input type="text" name="name" id="inputName" class="form-control" placeholder="<?php echo $language->get('admin', 'field_name'); ?>">
				</div>

			    <div class="form-group">
				  <label for="inputType"><?php echo $language->get('admin', 'type'); ?></label>
				  <select class="form-control" name="type" id="inputType">
				    <option value="1"><?php echo $language->get('admin', 'text'); ?></option>
				    <option value="2"><?php echo $language->get('admin', 'textarea'); ?></option>
				    <option value="3"><?php echo $language->get('admin', 'date'); ?></option>
				  </select>
				</div>

			    <div class="form-group">
				  <label for="inputDescription"><?php echo $language->get('admin', 'description'); ?></label>
				  <textarea id="inputDescription" name="description" class="form-control"></textarea>
				</div>

				<div class="form-group">
				  <label for="inputRequired"><?php echo $language->get('admin', 'required'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_required_help'); ?>"></i></span>
				  <input type="checkbox" id="inputRequired" name="required" class="js-switch" />
				</div>

				<div class="form-group">
				  <label for="inputPublic"><?php echo $language->get('admin', 'public'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_public_help'); ?>"></i></span>
				  <input type="checkbox" id="inputPublic" name="public" class="js-switch" />
				</div>

				<div class="form-group">
				  <label for="inputForum"><?php echo $language->get('admin', 'display_field_on_forum'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_forum_help'); ?>"></i></span>
				  <input type="checkbox" id="inputForum" name="forum" class="js-switch" />
				</div>

				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
				</div>
			  </form>
									<?php
								} else if($_GET['action'] == 'delete'){
									// Delete field
									if(isset($_GET['id']))
										$queries->delete('profile_fields', array('id', '=', $_GET['id']));

									Redirect::to(URL::build('/admin/core/', 'view=profile'));
									die();
								}
							} else if(isset($_GET['id']) && !isset($_GET['action'])) {
								// Editing field

								// Ensure field actually exists
								if(!is_numeric($_GET['id'])){
									Redirect::to(URL::build('/admin/core/', 'view=profile'));
									die();
								}

								$field = $queries->getWhere('profile_fields', array('id', '=', $_GET['id']));
								if(!count($field)){
									Redirect::to(URL::build('/admin/core/', 'view=profile'));
									die();
								}

								$field = $field[0];

								if(Input::exists()){
									if(Token::check(Input::get('token'))){
										// Validate input
										$validate = new Validate();

										$validation = $validate->check($_POST, array(
											'name' => array(
												'required' => true,
												'min' => 2,
												'max' => 16
											),
											'type' => array(
												'required' => true
											)
										));

										if($validation->passed()){
											// Update database
											try {
												// Get whether required/public/forum post options are enabled or not
												if(isset($_POST['required']) && $_POST['required'] == 'on') $required = 1;
												else $required = 0;

												if(isset($_POST['public']) && $_POST['public'] == 'on') $public = 1;
												else $public = 0;

												if(isset($_POST['forum']) && $_POST['forum'] == 'on') $forum_posts = 1;
												else $forum_posts = 0;

												// Update database
												$queries->update('profile_fields', $field->id, array(
													'name' => Output::getClean(Input::get('name')),
													'type' => Input::get('type'),
													'public' => $public,
													'required' => $required,
													'description' => Output::getClean(Input::get('description')),
													'forum_posts' => $forum_posts
												));

												// Redirect
												Redirect::to(URL::build('/admin/core/', 'view=profile'));
												die();

											} catch(Exception $e){
												$error = $e->getMessage();
											}
										} else {
											// Error
											$error = $language->get('admin', 'profile_field_error');
										}

									} else {
										$error = $language->get('admin', 'invalid_token');
									}
								}

								// Generate form token
								$token = Token::generate();

								?>
			  <h4 style="display:inline;"><?php echo $language->get('admin', 'editing_profile_field'); ?></h4>
			  <span class="pull-right">
			    <a class="btn btn-warning" href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
			    <a class="btn btn-danger" href="<?php echo URL::build('/admin/core/', 'view=profile&amp;action=delete&amp;id=' . $field->id); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_deletion'); ?>');"><?php echo $language->get('general', 'delete'); ?></a>
			  </span>
			  <br /><br />
			  <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
			  <form action="" method="post">
			    <div class="form-group">
				  <label for="inputName"><?php echo $language->get('admin', 'field_name'); ?></label>
				  <input type="text" name="name" id="inputName" class="form-control" placeholder="<?php echo $language->get('admin', 'field_name'); ?>" value="<?php echo Output::getClean($field->name); ?>">
				</div>

			    <div class="form-group">
				  <label for="inputType"><?php echo $language->get('admin', 'type'); ?></label>
				  <select class="form-control" name="type" id="inputType">
				    <option value="1"<?php if($field->type == 1) echo ' selected'; ?>><?php echo $language->get('admin', 'text'); ?></option>
				    <option value="2"<?php if($field->type == 2) echo ' selected'; ?>><?php echo $language->get('admin', 'textarea'); ?></option>
				    <option value="3"<?php if($field->type == 3) echo ' selected'; ?>><?php echo $language->get('admin', 'date'); ?></option>
				  </select>
				</div>

			    <div class="form-group">
				  <label for="inputDescription"><?php echo $language->get('admin', 'description'); ?></label>
				  <textarea id="inputDescription" name="description" class="form-control"><?php echo Output::getPurified($field->description); ?></textarea>
				</div>

				<div class="form-group">
				  <label for="inputRequired"><?php echo $language->get('admin', 'required'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_required_help'); ?>"></i></span>
				  <input type="checkbox" id="inputRequired" name="required" class="js-switch" <?php if($field->required == 1) echo ' checked';?>/>
				</div>

				<div class="form-group">
				  <label for="inputPublic"><?php echo $language->get('admin', 'public'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_public_help'); ?>"></i></span>
				  <input type="checkbox" id="inputPublic" name="public" class="js-switch" <?php if($field->public == 1) echo ' checked';?>/>
				</div>

				<div class="form-group">
				  <label for="inputForum"><?php echo $language->get('admin', 'display_field_on_forum'); ?></label>
				  <span class="badge badge-info"><i class="fa fa-question" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'profile_field_forum_help'); ?>"></i></span>
				  <input type="checkbox" id="inputForum" name="forum" class="js-switch" <?php if($field->forum_posts == 1) echo ' checked';?>/>
				</div>

				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
				</div>
			  </form>
								<?php
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

											switch(Input::get('type')){
												case 1:
													$type = 1;
												break;
												case 2:
													$type = 2;
												break;
												default:
													$type = 0;
												break;
											}

											// Update database
											$queries->update('reactions', $_GET['id'], array(
												'name' => Output::getClean(Input::get('name')),
												'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
												'type' => $type,
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
					<option value="-1"<?php if($reaction->type == 0) echo ' selected'; ?>><?php echo $language->get('admin', 'negative'); ?></option>
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

												switch(Input::get('type')){
													case 1:
														$type = 1;
													break;
													case 2:
														$type = 2;
													break;
													default:
														$type = 0;
													break;
												}

												// Update database
												$queries->create('reactions', array(
													'name' => Output::getClean(Input::get('name')),
													'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
													'type' => $type,
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
					<option value="-1"><?php echo $language->get('admin', 'negative'); ?></option>
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

					  case 'social':
						// Deal with input
						if(Input::exists()){
							if(Token::check(Input::get('token'))){
								// Update database values
								// Youtube URL
								$youtube_url_id = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
								$youtube_url_id = $youtube_url_id[0]->id;

								$queries->update('settings', $youtube_url_id, array(
									'value' => Output::getClean(Input::get('youtubeurl'))
								));

								// Update cache
								$cache->setCache('social_media');
								$cache->store('youtube', Output::getClean(Input::get('youtubeurl')));

								// Twitter URL
								$twitter_url_id = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
								$twitter_url_id = $twitter_url_id[0]->id;

								$queries->update('settings', $twitter_url_id, array(
									'value' => Output::getClean(Input::get('twitterurl'))
								));

								$cache->store('twitter', Output::getClean(Input::get('twitterurl')));

								// Twitter dark theme
								$twitter_dark_theme = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
								$twitter_dark_theme = $twitter_dark_theme[0]->id;

								if(isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) $theme = 'dark';
								else $theme = 'light';

								$queries->update('settings', $twitter_dark_theme, array(
									'value' => $theme
								));

								$cache->store('twitter_theme', $theme);

								// Google Plus URL
								$gplus_url_id = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
								$gplus_url_id = $gplus_url_id[0]->id;

								$queries->update('settings', $gplus_url_id, array(
									'value' => Output::getClean(Input::get('gplusurl'))
								));

								$cache->store('google_plus', Output::getClean(Input::get('gplusurl')));

								// Facebook URL
								$fb_url_id = $queries->getWhere('settings', array('name', '=', 'fb_url'));
								$fb_url_id = $fb_url_id[0]->id;
								$queries->update('settings', $fb_url_id, array(
									'value' => Output::getClean(Input::get('fburl'))
								));

								$cache->store('facebook', Output::getClean(Input::get('fburl')));

								Session::flash('social_media_links', '<div class="alert alert-success">' . $language->get('admin', 'successfully_updated') . '</div>');
							} else {
								// Invalid token
								Session::flash('social_media_links', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
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
						<h4><?php echo $language->get('admin', 'social_media'); ?></h4>
						<?php
						if(Session::exists('social_media_links')){
							echo Session::flash('social_media_links');
						}
						?>
						<form action="" method="post">
							<div class="form-group">
								<label for="InputYoutube"><?php echo $language->get('admin', 'youtube_url'); ?></label>
								<input type="text" name="youtubeurl" class="form-control" id="InputYoutube" placeholder="<?php echo $language->get('admin', 'youtube_url'); ?>" value="<?php echo Output::getClean($youtube_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputTwitter"><?php echo $language->get('admin', 'twitter_url'); ?></label>
								<input type="text" name="twitterurl" class="form-control" id="InputTwitter" placeholder="<?php echo $language->get('admin', 'twitter_url'); ?>" value="<?php echo Output::getClean($twitter_url[0]->value); ?>">
							</div>
							<div class="form-group">
							  <label for="InputTwitterStyle"><?php echo $language->get('admin', 'twitter_dark_theme'); ?></label>
							  <input id="InputTwitterStyle" name="twitter_dark_theme" type="checkbox" class="js-switch" value="1" <?php if($twitter_style[0]->value == 'dark') echo 'checked'; ?>/>
							</div>
							<div class="form-group">
								<label for="InputGPlus"><?php echo $language->get('admin', 'google_plus_url'); ?></label>
								<input type="text" name="gplusurl" class="form-control" id="InputGPlus" placeholder="<?php echo $language->get('admin', 'google_plus_url'); ?>" value="<?php echo Output::getClean($gplus_url[0]->value); ?>">
							</div>
							<div class="form-group">
								<label for="InputFacebook"><?php echo $language->get('admin', 'facebook_url'); ?></label>
								<input type="text" name="fburl" class="form-control" id="InputFacebook" placeholder="<?php echo $language->get('admin', 'facebook_url'); ?>" value="<?php echo Output::getClean($fb_url[0]->value); ?>">
							</div>
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
						</form>
						<?php
					  break;

					  default:
						Redirect::to(URL::build('/admin/core'));
						die();
					  break;
                      case 'maintenance':
                          // Maintenance mode settings
                          // Deal with input
                          if(Input::exists()){
                              if(Token::check(Input::get('token'))){
                                  // Valid token
                                  // Validate message
                                  $validate = new Validate();
                                  $validation = $validate->check($_POST, array(
                                     'message' => array(
                                       'max' => 1024
                                     )
                                  ));

                                  if($validation->passed()){
                                      // Update database and cache
                                      // Is debug mode enabled or not?
                                      if(isset($_POST['enable_debugging']) && $_POST['enable_debugging'] == 1) $enabled = 1;
                                      else $enabled = 0;

                                      $debug_id = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
                                      $debug_id = $debug_id[0]->id;
                                      $queries->update('settings', $debug_id, array(
                                          'value' => $enabled
                                      ));

                                      // Cache
                                      $cache->setCache('error_cache');
                                      $cache->store('error_reporting', $enabled);

                                      // Is maintenance enabled or not?
                                      if(isset($_POST['enable_maintenance']) && $_POST['enable_maintenance'] == 1) $enabled = 'true';
                                      else $enabled = 'false';

                                      $maintenance_id = $queries->getWhere('settings', array('name', '=', 'maintenance'));
                                      $maintenance_id = $maintenance_id[0]->id;
                                      $queries->update('settings', $maintenance_id, array(
                                          'value' => $enabled
                                      ));

                                      if(isset($_POST['message']) && !empty($_POST['message'])) $message = Input::get('message');
                                      else $message = 'Maintenance mode is enabled.';

                                      $maintenance_id = $queries->getWhere('settings', array('name', '=', 'maintenance_message'));
                                      $maintenance_id = $maintenance_id[0]->id;
                                      $queries->update('settings', $maintenance_id, array(
                                          'value' => Output::getClean($message)
                                      ));

                                      // Cache
                                      $cache->setCache('maintenance_cache');
                                      $cache->store('maintenance', array(
                                          'maintenance' => $enabled,
                                          'message' => Output::getClean($message)
                                      ));

                                      // Reload to update debugging
                                      Redirect::to(URL::build('/admin/core/', 'view=maintenance'));

                                  } else $error = $language->get('admin', 'maintenance_message_max_1024');
                              } else {
                                  // Invalid token
                                  $error = $language->get('general', 'invalid_token');
                              }

                              // Re-query cache for updated values
                              $cache->setCache('maintenance_cache');
                              $maintenance = $cache->retrieve('maintenance');
                          }
                          ?>
                          <h4><?php echo $language->get('admin', 'debugging_and_maintenance'); ?></h4>

                          <form action="" method="post">
                            <?php if(isset($error)){ ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php } ?>
                            <div class="form-group">
                              <label for="InputDebug"><?php echo $language->get('admin', 'enable_debug_mode'); ?></label>
                              <input id="InputDebug" name="enable_debugging" type="checkbox" class="js-switch" value="1" <?php if(defined('DEBUGGING')) echo 'checked'; ?>/>
                            </div>
                            <div class="form-group">
                              <label for="InputMaintenance"><?php echo $language->get('admin', 'enable_maintenance_mode'); ?></label>
                              <input id="InputMaintenance" name="enable_maintenance" type="checkbox" class="js-switch" value="1" <?php if(isset($maintenance['maintenance']) && $maintenance['maintenance'] != 'false') echo 'checked'; ?>/>
                            </div>
                            <div class="form-group">
                              <label for="inputMaintenanceMessage"><?php echo $language->get('admin', 'maintenance_mode_message'); ?></label>
                              <textarea style="width:100%" rows="10" name="message" id="InputMaintenanceMessage"><?php echo Output::getPurified((isset($_POST['message']) ? $_POST['message'] : $maintenance['message'])); ?></textarea>
                            </div>
                            <div class="form-group">
                              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                              <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
                            </div>
                          </form>
                          <?php
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

    <?php if(isset($_GET['view']) && $_GET['view'] == 'maintenance'){ ?>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
      <?php echo Input::createEditor('InputMaintenanceMessage'); ?>
    </script>
    <?php } ?>
  </body>
</html>