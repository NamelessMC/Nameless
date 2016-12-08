<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Installer
 */
 
// Definitions 
define('PATH', '/'); 
define('ROOT_PATH', dirname(__FILE__));
$page = 'install';

// Start initialising the page
require('core/init.php');

?>

<html lang="en">
  <head>
	<!-- Page Title -->
	<title>Install &bull; NamelessMC</title>

	<!-- Global CSS -->
	<link rel="stylesheet" href="core/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="core/assets/css/custom.css">
	<link rel="stylesheet" href="core/assets/css/font-awesome.min.css">

	<style>
	html {
		overflow-y: scroll;
	}
	</style> 
  </head>
  
  <body>
	<div class="jumbotron jumbotron-fluid" style="height:100vh; margin-bottom:0rem !important;">
	  <center>
	    <h1>NamelessMC v2 <sup><span style="font-size: small;">pre-release</span></sup></h1>
		
		<hr />
		
		<?php
		if(!isset($_GET['do']) && !isset($_GET['step'])){
		?>
		<p>Welcome to NamelessMC version 2.0 pre-release.</p>
		
		<div class="row">
		  <div class="col-md-6 offset-md-3">
			<div class="alert alert-danger">
			  Please note that this pre-release is not intended for use on a public site.
			</div>
		  </div>
		</div>
		
		<p>The installer will guide you through the installation process.</p>
		
		<p>Firstly, is this a new installation?</p>
		
		<a href="?do=install" class="btn btn-primary btn-lg">
		  New Installation &raquo;
		</a>
		
		<a href="?do=upgrade" class="btn btn-warning btn-lg disabled">
		  Upgrading from v1 &raquo;
		</a>
		
		<?php
		} else {
			if(isset($_GET['do'])){
				if($_GET['do'] == 'install'){
					// Fresh install
					$_SESSION['action'] = 'install';
				} else {
					// Upgrade
					$_SESSION['action'] = 'upgrade';
				}
				
				Redirect::to('?step=requirements');
				die();
			} else {
				switch($_GET['step']){
					case 'requirements':
						// Requirements
						$error = '<p style="display: inline;" class="text-danger"><i class="fa fa-times-circle"></i></p><br />';
						$success = '<p style="display: inline;" class="text-success"><i class="fa fa-check-circle"></i></p><br />';
						
						?>
						<h4>Requirements:</h4>
						
						<?php
						if(version_compare(phpversion(), '5.4', '<')){
							echo 'PHP > 5.4 - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP > 5.4 - ' . $success;
						}
						
						if(!extension_loaded('gd')){
							echo 'PHP GD Extension - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP GD Extension - ' . $success;
						}
						if(!extension_loaded('PDO')){
							echo 'PHP PDO Extension - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP PDO Extension - ' . $success;
						}
						if(!function_exists('mcrypt_encrypt')) {
							echo 'PHP mcrypt Extension - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP mcrypt Extension - ' . $success;
						}
						if(!function_exists('curl_version')){
							echo 'PHP cURL Extension - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP cURL Extension - ' . $success;
						}
						if(!extension_loaded('xml')){
							echo 'PHP XML Extension - ' . $error;
							$php_error = true;
						} else {
							echo 'PHP XML Extension - ' . $success;
						}
						
						// Permissions
						if(is_writable('core/config.php')){
							echo 'core/config.php Writable - ' . $success;
						} else {
							echo 'core/config.php Writable - ' . $error;
							$php_error = true;
						}
						
						if(is_writable('cache')){
							echo 'Cache Writable - ' . $success;
						} else {
							echo 'Cache Writable - ' . $error;
							$php_error = true;
						}
						
						if(is_writable('cache/templates_c')){
							echo 'Template Cache Writable - ' . $success;
						} else {
							echo 'Template Cache Writable - ' . $error;
							$php_error = true;
						}
						
						if(isset($php_error)){
						?>
		<br />
		<div class="row">
		  <div class="col-md-6 offset-md-3">
			<div class="alert alert-danger">
			  You must have all of the required extensions installed, and have correct permissions set, in order to proceed with installation.
			</div>
		  </div>
		</div>
						<?php
						} else {
							echo '<br /><a class="btn btn-primary btn-lg" href="?step=database">Proceed &raquo;</a>';
						}
					break;
					
					case 'database':
						if(Input::exists()){
							// Ensure all fields are filled
							$validate = new Validate();
							
							$validation = $validate->check($_POST, array(
								'db_address' => array(
									'required' => true
								),
								'db_port' => array(
									'required' => true
								),
								'db_username' => array(
									'required' => true
								),
								'db_name' => array(
									'required' => true
								)
							));
							
							if($validation->passed()){
								// Check database connection
								if(isset($_POST['db_password']) && !empty($_POST['db_password'])){
									$password = $_POST['db_password'];
								} else {
									$password = '';
								}
								
								// Get installation path
								$path = str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));
								
								$mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $password, Input::get('db_name'), Input::get('db_port'));
								if($mysqli->connect_errno) {
									$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
								} else {
									// Valid, check if config is writable
									if(is_writable('core/config.php')){
										// Writable, attempt to write configuration
										$insert = 	'<?php' . PHP_EOL . 
													'$GLOBALS[\'config\'] = array(' . PHP_EOL . 
													'	"mysql" => array(' . PHP_EOL . 
													'		"host" => "' . Input::get('db_address') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL . 
													'		"username" => "' . Input::get('db_username') . '", // Web server database username' . PHP_EOL . 
													'		"password" => \'' . $password . '\', // Web server database password' . PHP_EOL . 
													'		"db" => "' . Input::get('db_name') . '", // Web server database name' . PHP_EOL .
													'		"port" => "' . Input::get('db_port') . '", // Web server database port' . PHP_EOL .
													'		"prefix" => "nl2_" // Web server table prefix' . PHP_EOL .
													'	),' . PHP_EOL . 
													'	"remember" => array(' . PHP_EOL . 
													'		"cookie_name" => "nlmc", // Name for website cookies' . PHP_EOL . 
													'		"cookie_expiry" => 604800' . PHP_EOL . 
													'	),' . PHP_EOL . 
													'	"session" => array(' . PHP_EOL . 
													'		"session_name" => "user",' . PHP_EOL . 
													'		"admin_name" => "admin",' . PHP_EOL .
													'		"token_name" => "token"' . PHP_EOL . 
													'	),' . PHP_EOL . 
													'	"core" => array(' . PHP_EOL . 
													'		"path" => "' . $path . '",' . PHP_EOL . 
													'		"friendly" => false' . PHP_EOL . 
													'	)' . PHP_EOL . 
													');';

										try {
											$file = fopen('core/config.php','w');
											fwrite($file, $insert);
											fclose($file);

											Redirect::to('install.php?step=database_initialise');
											die();
											
										} catch(Exception $e){
											$error = $e->getMessage();
										}
										
									} else {
										// Not writable
										$error = 'Your <strong>core/config.php</strong> is not writable. Please check file permissions.';
									}
								}
								
							} else {
								$error = 'Please ensure all fields have been filled out.';
							}
						}
						?>
	  </center>
	  <div class="row">
		<div class="col-md-6 offset-md-3">
		  <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
		  <form action="" method="post">
			<div class="form-group">
			  <label for="inputDBAddress">Database Address</label>
			  <input type="text" class="form-control" name="db_address" id="inputDBAddress" placeholder="Database Address">
			</div>
			
			<div class="form-group">
			  <label for="inputDBPort">Database Port</label>
			  <input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="Database Port" value="3306">
			</div>
			
			<div class="form-group">
			  <label for="inputDBUsername">Database Username</label>
			  <input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="Database Username">
			</div>
			  
			<div class="form-group">
			  <label for="inputDBPassword">Database Password</label>
			  <input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="Database Password">
			</div>
			  
			<div class="form-group">
			  <label for="inputDBName">Database Name</label>
			  <input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="Database Name">
		    </div>
			
			<div class="form-group">
			  <input type="submit" class="btn btn-primary" value="Submit">
			</div>
		  </form>
		</div>
	  </div>
	  <center>
						<?php
					break;
					
					case 'database_initialise':
						// Initialise database tables
						?>
	  <p>The installer is now initialising the database.</p>
	  <p>This may take a while...</p>
						<?php
						try {
							$queries = new Queries();
							$queries->dbInitialise();
						} catch(Exception $e){
							die($e->getMessage());
						}
						
						Redirect::to('install.php?step=configuration');
						die();
						
					break;
					
					case 'configuration':
						// Configure site
						if(Input::exists()){
							// Validate input
							$validate = new Validate();
							
							try {
								$validation = $validate->check($_POST, array(
									'sitename' => array(
										'required' => true,
										'min' => 1,
										'max' => 32
									),
									'incoming' => array(
										'required' => true,
										'min' => 4,
										'max' => 64
									),
									'outgoing' => array(
										'required' => true,
										'min' => 4,
										'max' => 64
									)
								));
							} catch(Exception $e) { }
							
							if($validation->passed()){
								$queries = new Queries();
								
								$queries->create('settings', array(
									'name' => 'sitename',
									'value' => Output::getClean(Input::get('sitename'))
								));
								
								// Cache
								$cache = new Cache();
								$cache->setCache('sitenamecache');
								$cache->store('sitename', Output::getClean(Input::get('sitename')));
								
								$queries->create('settings', array(
									'name' => 'incoming_email',
									'value' => Output::getClean(Input::get('incoming'))
								));
								
								$queries->create('settings', array(
									'name' => 'outgoing_email',
									'value' => Output::getClean(Input::get('outgoing'))
								));
								
								Redirect::to('install.php?step=initialise');
								die();
							} else {
								$error = 'Please input a valid site name between 1 and 32 characters long, and valid email addresses between 4 and 64 characters long.';
							}
							
						}
						
						?>
	</center>
	<div class="row">
	  <div class="col-md-6 offset-md-3">
	    <form action="" method="post">
		  <h3>Configuration</h3>
		  <p>Please input basic information about your site. These values can be changed later on through the admin panel.</p>
		  
		  <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
		  
	      <div class="form-group">
		    <label for="inputSitename">Site Name</label>
			<input type="text" class="form-control" name="sitename" id="inputSitename" placeholder="Site Name">
		  </div>
		  
	      <div class="form-group">
		    <label for="inputContactEmail">Contact Email</label>
			<input type="email" class="form-control" name="incoming" id="inputContactEmail" placeholder="Contact Email">
		  </div>
		  
	      <div class="form-group">
		    <label for="inputOutgoingEmail">Outgoing Email</label>
			<input type="email" class="form-control" name="outgoing" id="inputOutgoingEmail" placeholder="Outgoing Email">
		  </div>
		  
		  <div class="form-group">
		    <input type="submit" class="btn btn-primary" value="Submit">
		  </div>
	    </form>
	  </div>
	</div>
	<center>
						<?php
					break;
					
					case 'initialise':
						// Initialise database and cache
						echo 'Initialising database and cache, please wait...';
						
						$queries = new Queries();
						$cache = new Cache();
						
						// Create first category + forum
						$queries->create('forums', array(
							'forum_title' => 'Category',
							'forum_description' => 'The first forum category!',
							'forum_order' => 1,
							'forum_type' => 'category'
						));
						
						$queries->create('forums', array(
							'forum_title' => 'Forum',
							'forum_description' => 'The first discussion forum!',
							'forum_order' => 2,
							'parent' => 1,
							'forum_type' => 'forum'
						));
						
						// Permissions
						for($i = 0; $i < 4; $i++){
							for($n = 1; $n < 3; $n++){
								$queries->create('forums_permissions', array(
									'group_id' => $i,
									'forum_id' => $n,
									'view' => 1,
									'create_topic' => (($i == 0) ? 0 : 1),
									'create_post' => (($i == 0) ? 0 : 1),
									'view_other_topics' => 1,
									'moderate' => (($i == 2 || $i == 3) ? 1 : 0),
								));
							}
						}
						
						// Groups
						$queries->create('groups', array(
							'name' => 'Member',
							'group_html' => '<span class="tag tag-success">Member</span>',
							'group_html_lg' => '<span class="tag tag-success">Member</span>'
						));
						
						$queries->create('groups', array(
							'name' => 'Moderator',
							'group_html' => '<span class="tag tag-primary">Moderator</span>',
							'group_html_lg' => '<span class="tag tag-primary">Moderator</span>',
							'mod_cp' => 1
						));
						
						$queries->create('groups', array(
							'name' => 'Admin',
							'group_html' => '<span class="tag tag-danger">Admin</span>',
							'group_html_lg' => '<span class="tag tag-danger">Admin</span>',
							'group_username_css' => '#ff0000',
							'mod_cp' => 1,
							'admin_cp' => 1
						));
						
						// Languages
						$queries->create('languages', array(
							'name' => 'EnglishUK',
							'is_default' => 1
						));
						
						$cache->setCache('languagecache');
						$cache->store('language', 'EnglishUK');
						
						// Modules
						$queries->create('modules', array(
							'name' => 'Core',
							'enabled' => 1
						));
						
						$queries->create('modules', array(
							'name' => 'Forum',
							'enabled' => 1
						));
						
						$cache->setCache('modulescache');
						$cache->store('enabled_modules', array(
							array('name' => 'Core', 'priority' => 1),
							array('name' => 'Forum', 'priority' => 4)
						));
						
						// Reactions
						$queries->create('reactions', array(
							'name' => 'Like',
							'html' => '<i class="fa fa-thumbs-up text-success"></i>',
							'enabled' => 1,
							'type' => 2
						));
						
						$queries->create('reactions', array(
							'name' => 'Dislike',
							'html' => '<i class="fa fa-thumbs-down text-danger"></i>',
							'enabled' => 1,
							'type' => 0
						));
						
						$queries->create('reactions', array(
							'name' => 'Meh',
							'html' => '<i class="fa fa-meh-o text-warning"></i>',
							'enabled' => 1,
							'type' => 1
						));
						
						// Settings
						$queries->create('settings', array(
							'name' => 'registration_enabled',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'displaynames',
							'value' => 'false'
						));
						
						$queries->create('settings', array(
							'name' => 'uuid_linking',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'recaptcha',
							'value' => 'false'
						));
						
						$queries->create('settings', array(
							'name' => 'recaptcha_key',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'recaptcha_secret',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'email_verification',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 't_and_c',
							'value' => 'By registering on our website, you agree to the following:<p>This website uses "Nameless" website software. The "Nameless" software creators will not be held responsible for any content that may be experienced whilst browsing this site, nor are they responsible for any loss of data which may come about, for example a hacking attempt. The website is run independently from the software creators, and any content is the responsibility of the website administration.</p>'
						));
						
						$queries->create('settings', array(
							'name' => 't_and_c_site',
							'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
						));
						
						$queries->create('settings', array(
							'name' => 'nameless_version',
							'value' => '2.0.0-dev'
						));
						
						$queries->create('settings', array(
							'name' => 'version_checked',
							'value' => date('U')
						));
						
						$queries->create('settings', array(
							'name' => 'version_update',
							'value' => 'false'
						));
						
						$queries->create('settings', array(
							'name' => 'phpmailer',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'phpmailer_type',
							'value' => 'smtp'
						));
						
						$queries->create('settings', array(
							'name' => 'verify_accounts',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'mcassoc_key',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'mcassoc_instance',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'user_avatars',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'forum_layout',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'maintenance',
							'value' => 'false'
						));
						
						$queries->create('settings', array(
							'name' => 'avatar_site',
							'value' => 'cravatar'
						));
						
						$queries->create('settings', array(
							'name' => 'mc_integration',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'avatar_type',
							'value' => 'helmavatar'
						));
						
						$queries->create('settings', array(
							'name' => 'portal',
							'value' => 0
						));
					    $cache->setCache('portal_cache');
						$cache->store('portal', 0);
						
						$queries->create('settings', array(
							'name' => 'forum_reactions',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'formatting_type',
							'value' => 'html'
						));
						$cache->setCache('post_formatting');
						$cache->store('formatting', 'html');
						
						$queries->create('settings', array(
							'name' => 'youtube_url',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'twitter_url',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'twitter_style',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'gplus_url',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'fb_url',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'ga_script',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'error_reporting',
							'value' => 0
						));
						$cache->setCache('error_cache');
						$cache->store('error_reporting', 1);
						
						$queries->create('settings', array(
							'name' => 'page_loading',
							'value' => 0
						));
						$cache->setCache('page_load_cache');
						$cache->store('page_load', 0);
						
						$queries->create('settings', array(
							'name' => 'unique_id',
							'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 62)
						));
						
						$queries->create('settings', array(
							'name' => 'use_api',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'mc_api_key',
							'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32)
						));
						
						$queries->create('settings', array(
							'name' => 'external_query',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'followers',
							'value' => 0
						));
						
						$queries->create('settings', array(
							'name' => 'discord',
							'value' => null
						));
						
						$queries->create('settings', array(
							'name' => 'language',
							'value' => 1
						));
						
						// Templates
						$queries->create('templates', array(
							'name' => 'Default',
							'enabled' => 1,
							'is_default' => 1
						));
						$cache->setCache('templatecache');
						$cache->store('default', 'default');
						
						// Success
						Redirect::to('install.php?step=user');
						die();
						
					break;
					
					case 'user':
						// Admin user creation
					break;
					
					default:
						die('Unknown step');
					break;
				}
			}
		}
		?>
		<hr />
		
		<div class="container">
		  <span class="pull-right">
		    <a class="btn btn-primary" href="https://github.com/NamelessMC/Nameless" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a>
		  </span>
		</div>
	  </center>
	</div>
  </body>
</html>