<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

require('core/includes/password.php'); // Require password compatibility
require('core/integration/uuid.php'); // Require UUID integration

if(isset($_GET["step"])){
	$step = strtolower(htmlspecialchars($_GET["step"]));
} else {
	$step = "start";
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NamelessMC installer">
    <meta name="author" content="NamelessMC installer">
    <meta name="robots" content="noindex">

    <title>NamelessMC &bull; Install</title>

    <!-- Bootstrap core CSS -->
    <link href="./core/assets/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      .small-margin {
        margin-top: 10px;
        margin-bottom: 10px;
      }
      .page-header {
        margin: 12px 0px;
      }
    </style>

  </head>

  <body>
	<div class="container">
	  <br />
	  <ul class="nav nav-tabs">
	    <li <?php if($step == "start"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Start</a></li>
		<li <?php if($step == "requirements" || $step == "upgrade_requirements"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Requirements</a></li>
		<li <?php if($step == "upgrade"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Upgrade</a></li>
		<li <?php if($step == "configuration"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Configuration</a></li>
	    <li <?php if($step == "database"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Database</a></li>
		<li <?php if($step == "settings" || $step == "settings_extra"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Settings</a></li>
		<li <?php if($step == "account"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Account</a></li>
		<li <?php if($step == "convert"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Convert</a></li>
		<li <?php if($step == "finish"){ ?>class="active"<?php } else { ?>class="disabled"<?php } ?>><a>Finish</a></li>
	  </ul>

	  <?php
	  if($step === "start"){
	  ?>
          <div class="row page-header">
            <h3>Welcome to NamelessMC <small>BETA</small></h3>
            <hr class="small-margin">
            <p>This installer will guide you through the process of installing the NamelessMC website package.</p>
          </div>

          <h4>Are you upgrading from 0.4.1?</h4>

          <p>
            <button type="button" onclick="location.href='./install?step=requirements'" class="btn btn-primary">New Installation &raquo;</button>
            <button type="button" onclick="location.href='./install?step=upgrade_requirements'" class="btn btn-default">Upgrading from 0.4.1 &raquo;</button>
          </p>

		  <div class="alert alert-info">Note: if you're upgrading from a 1.x version to another 1.x version, you will need to follow the instructions from within the AdminCP's Update tab, rather than running through the installer again.</div>

          <hr>

          <div class="panel-group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#requirements">Requirements</a>
                </h4>
              </div>
              <div id="requirements" class="panel-collapse collapse">
                <div class="panel-body">
                  You will need the following:
	          <ul>
	            <li>A MySQL database on the webserver</li>
		    <li>PHP version 5.3+</li>
	          </ul>
                  The following are not required, but are recommended:
	          <ul>
	            <li>A MySQL database for a Bungee instance <a data-toggle="modal" href="#bungee_plugins">(Supported Plugins)</a></li>
		    <li>A MySQL database for your Minecraft servers <a data-toggle="modal" href="#mc_plugins">(Supported Plugins)</a></li>
	          </ul>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#conversion">Converting from other software</a>
                </h4>
              </div>
              <div id="conversion" class="panel-collapse collapse">
                <div class="panel-body">
                Currently data may be imported from:
                <ul>
                  <li>XenForo</li>
                </ul>
                Support for the following is planned:
                <ul>
                  <li>phpBB</li>
                  <li>IPBoard</li>
                  <li>MyBB</li>
                  <li>Vanilla</li>
                  <li>ModernBB</li>
                  <li>Wordpress (with optional support for bbPress)</li>
                </ul>
                </div>
              </div>
            </div>
          </div>

	  <?php
	  } else if($step === "requirements" || $step === "upgrade_requirements") {
		$error = '<p style="display: inline;" class="text-danger"><span class="glyphicon glyphicon-remove-sign"></span></p><br />';
		$success = '<p style="display: inline;" class="text-success"><span class="glyphicon glyphicon-ok-sign"></span></p><br />';
	    $warning = '<p style="display: inline;" class="text-warning"><span class="glyphicon glyphicon-remove-sign"></span></p><br />';
	  ?>
          <div class="row page-header">
            <h3>Requirements</h3>
            <hr class="small-margin">
            <p>Before you begin with the installation, the installer will verify your current setup is compatible.</p>
          </div>
	  <?php
		if(version_compare(phpversion(), '5.3', '<')){
			echo 'PHP 5.3 - ' . $error;
			$php_error = true;
		} else {
			echo 'PHP 5.3 - ' . $success;
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
		if($step == "upgrade_requirements"){
			if(!extension_loaded('mysqlnd')){
				echo 'PHP mysqlnd Extension - ' . $warning;
				$php_error = true;
			} else {
				echo 'PHP mysqlnd Extension - ' . $success;
			}
		} else {
			if(!extension_loaded('mysql') && !extension_loaded('mysqli') && !extension_loaded('mysqlnd')){
				echo 'PHP mysql, mysqli or mysqlnd Extension - ' . $warning;
				$php_error = true;
			} else {
				echo 'PHP mysql, mysqli or mysqlnd Extension - ' . $success;
			}
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
		if(!is_writable('cache') || !is_writable('cache' . DIRECTORY_SEPARATOR . 'templates_c')){
			echo 'Your <strong>cache</strong> and <strong>cache/templates_c</strong> directories must be writable. Please check your file permissions. ' . $error;
			$php_error = true;
		} else {
			echo 'Cache and cache/templates_c directories writable - ' . $success;
		}
		if(!is_writable('core' . DIRECTORY_SEPARATOR . 'config.php')){
			echo 'Your <strong>core/config.php</strong> file must be writable. Please check your file permissions. ' . $error;
			$php_error = true;
		} else {
			echo 'core/config.php writable - ' . $success;
		}
	  ?>
	  <br />
	  <?php
	    if(isset($php_error)){
	  ?>
	  <div class="alert alert-danger">You must be running at least PHP version 5.3 with the required extensions and permissions in order to proceed with installation.</div>
	  <?php
		} else {
                    if($step === "requirements") {
          ?>
          <button type="button" onclick="location.href='./install?step=configuration'" class="btn btn-primary">Proceed &raquo;</button>
          <?php
                    } else {
          ?>
          <button type="button" onclick="location.href='./install?step=upgrade'" class="btn btn-primary">Proceed &raquo;</button>
          <?php
                    }
		}
          } else if($step === "upgrade"){
		  if(Input::exists()){
			if(isset($_POST['submitted'])){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'db_address' => array(
						'required' => true
					),
					'db_username' => array(
						'required' => true
					),
					'db_name' => array(
						'required' => true
					),
					'db_port' => array(
						'required' => true
					)
				));

				if($validation->passed()) {
					$db_password = Input::get('db_password');
					if(!empty($db_password)){
						$db_password = Input::get('db_password');
					} else {
						$db_password = '';
					}

					$prefix = Input::get('prefix');
					if(empty($prefix)){
						$prefix = '';
					}

					$prefix = htmlspecialchars($prefix);

					$db_prefix = "nl1_";
					$cookie_name = "nlmc";

					/*
					 *  Test connection - use MySQLi here, as the config for PDO is not written
					 */
					$mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $db_password, Input::get('db_name'), Input::get('db_port'));
					if($mysqli->connect_errno) {
						$mysql_error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
					} else {
						/*
						 *  Write to config file
						 */
						$insert = 	'<?php' . PHP_EOL .
									'$GLOBALS[\'config\'] = array(' . PHP_EOL .
									'	"mysql" => array(' . PHP_EOL .
									'		"host" => "' . Input::get('db_address') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL .
									'		"username" => "' . Input::get('db_username') . '", // Web server database username' . PHP_EOL .
									'		"password" => \'' . $db_password . '\', // Web server database password' . PHP_EOL .
									'		"db" => "' . Input::get('db_name') . '", // Web server database name' . PHP_EOL .
									'		"port" => "' . Input::get('db_port') . '", // Web server database port' . PHP_EOL .
									'		"prefix" => "' . $db_prefix . '" // Web server table prefix' . PHP_EOL .
									'	),' . PHP_EOL .
									'	"remember" => array(' . PHP_EOL .
									'		"cookie_name" => "' . $cookie_name . '", // Name for website cookies' . PHP_EOL .
									'		"cookie_expiry" => 604800' . PHP_EOL .
									'	),' . PHP_EOL .
									'	"session" => array(' . PHP_EOL .
									'		"session_name" => "user",' . PHP_EOL .
									'		"admin_name" => "admin",' . PHP_EOL .
									'		"token_name" => "token"' . PHP_EOL .
									'	)' . PHP_EOL .
									');';

						if(is_writable('core/config.php')){
							$file = fopen('core/config.php','w');
							fwrite($file, $insert);
							fclose($file);

							$queries = new Queries();
							$queries->dbInitialise($db_prefix); // Initialise the database

							$query = $mysqli->query("SELECT * FROM {$prefix}custom_pages");
							while($row = $query->fetch_assoc()){
								$queries->create('custom_pages', array(
									'url' => $row['url'],
									'title' => $row['title'],
									'content' => $row['content'],
									'link_location' => 1
								));
							}

							$query = $mysqli->query("INSERT nl1_forums SELECT * FROM {$prefix}forums");

							$query = $mysqli->query("INSERT nl1_forums_permissions SELECT * FROM {$prefix}forums_permissions");

							$query = $mysqli->query("INSERT nl1_friends SELECT * FROM {$prefix}friends");

							$query = $mysqli->query("SELECT * FROM {$prefix}groups");
							while($row = $query->fetch_assoc()){
								$mod_cp = 0;
								$admin_cp = 0;
								$staff = 0;

								if($row['id'] == 2){
									$mod_cp = 1;
									$admin_cp = 1;
									$staff = 1;
								} else if($row['id'] == 3){
									$mod_cp = 1;
									$staff = 1;
								}

								$queries->create('groups', array(
									'id' => $row['id'],
									'name' => $row['name'],
									'buycraft_id' => $row['buycraft_id'],
									'group_html' => $row['group_html'],
									'group_html_lg' => $row['group_html_lg'],
									'mod_cp' => $mod_cp,
									'admin_cp' => $admin_cp,
									'staff' => $staff
								));
							}

							$query = $mysqli->query("INSERT nl1_infractions SELECT * FROM {$prefix}infractions");

							$query = $mysqli->query("SELECT * FROM {$prefix}mc_servers");
							while($row = $query->fetch_assoc()){
								if(isset($row['pre'])){
									$pre = $row['pre'];
								} else {
									$pre = 0;
								}

								if(isset($row['player_list'])){
									$player_list = $row['player_list'];
								} else {
									$player_list = 0;
								}

								$queries->create('mc_servers', array(
									'ip' => $row['ip'],
									'name' => $row['name'],
									'is_default' => $row['is_default'],
									'display' => $row['display'],
									'pre' => $pre,
									'player_list' => $player_list
								));
							}

							$query = $mysqli->query("INSERT nl1_posts SELECT * FROM {$prefix}posts");

							$query = $mysqli->query("INSERT nl1_private_messages SELECT * FROM {$prefix}private_messages");

							$query = $mysqli->query("INSERT nl1_private_messages_users SELECT * FROM {$prefix}private_messages_users");

							$query = $mysqli->query("INSERT nl1_reports SELECT * FROM {$prefix}reports");

							$query = $mysqli->query("INSERT nl1_reports_comments SELECT * FROM {$prefix}reports_comments");

							$query = $mysqli->query("INSERT nl1_reputation SELECT * FROM {$prefix}reputation");

							$query = $mysqli->query("INSERT nl1_settings SELECT * FROM {$prefix}settings");

							$query = $mysqli->query("SELECT * FROM {$prefix}topics");
							while($row = $query->fetch_assoc()){
								$queries->create('topics', array(
									'id' => $row['id'],
									'forum_id' => $row['forum_id'],
									'topic_title' => $row['topic_title'],
									'topic_creator' => $row['topic_creator'],
									'topic_last_user' => $row['topic_last_user'],
									'topic_date' => $row['topic_date'],
									'topic_reply_date' => $row['topic_reply_date'],
									'topic_views' => $row['topic_views'],
									'locked' => $row['locked'],
									'sticky' => $row['sticky'],
									'label' => null
								));
							}

							$query = $mysqli->query("INSERT nl1_users SELECT * FROM {$prefix}users");

							$query = $mysqli->query("INSERT nl1_users_admin_session SELECT * FROM {$prefix}users_admin_session");

							$query = $mysqli->query("INSERT nl1_users_session SELECT * FROM {$prefix}users_session");

							$query = $mysqli->query("INSERT nl1_uuid_cache SELECT * FROM {$prefix}uuid_cache");

							// Core Modules
							$modules_initialised = $queries->getWhere('core_modules', array('id', '<>', 0));

							if(!count($modules_initialised)){
								$queries->create('core_modules', array(
									'name' => 'Google_Analytics',
									'enabled' => 0
								));
								$queries->create('core_modules', array(
									'name' => 'Social_Media',
									'enabled' => 1
								));
								$queries->create('core_modules', array(
									'name' => 'Registration',
									'enabled' => 1
								));
								$queries->create('core_modules', array(
									'name' => 'Voice_Server_Module',
									'enabled' => 0
								));
								$queries->create('core_modules', array(
									'name' => 'Staff_Applications',
									'enabled' => 0
								));
							}

							// Themes
							$themes_initialised = $queries->getWhere('themes', array('id', '<>', 0));

							if(!count($themes_initialised)){
								$themes = array(
									1 => array(
										'name' => 'Bootstrap',
										'enabled' => 1
									),
									2 => array(
										'name' => 'Cerulean',
										'enabled' => 0
									),
									3 => array(
										'name' => 'Cosmo',
										'enabled' => 0
									),
									4 => array(
										'name' => 'Cyborg',
										'enabled' => 0
									),
									5 => array(
										'name' => 'Darkly',
										'enabled' => 0
									),
									6 => array(
										'name' => 'Flatly',
										'enabled' => 0
									),
									7 => array(
										'name' => 'Journal',
										'enabled' => 0
									),
									8 => array(
										'name' => 'Lumen',
										'enabled' => 0
									),
									9 => array(
										'name' => 'Paper',
										'enabled' => 0
									),
									10 => array(
										'name' => 'Readable',
										'enabled' => 0
									),
									11 => array(
										'name' => 'Sandstone',
										'enabled' => 0
									),
									12 => array(
										'name' => 'Simplex',
										'enabled' => 0
									),
									13 => array(
										'name' => 'Slate',
										'enabled' => 0
									),
									14 => array(
										'name' => 'Spacelab',
										'enabled' => 0
									),
									15 => array(
										'name' => 'Superhero',
										'enabled' => 0
									),
									16 => array(
										'name' => 'United',
										'enabled' => 0
									),
									17 => array(
										'name' => 'Yeti',
										'enabled' => 0
									)
								);

								foreach($themes as $theme){
									$queries->create('themes', array(
										'enabled' => $theme['enabled'],
										'name' => $theme['name']
									));
								}
							}

							// Templates
							$queries->create('templates', array(
								'enabled' => 1,
								'name' => 'Default'
							));

							// Cache
							$c = new Cache();
							$c->setCache('themecache');
							$c->store('theme', 'Bootstrap');
							$c->store('inverse_navbar', '0');

							// Todo: update site name
							//$c->setCache('sitenamecache');
							//$c->store('sitename', htmlspecialchars(Input::get('sitename')));

							$c->setCache('templatecache');
							$c->store('template', 'Default');

							$c->setCache('languagecache');
							$c->store('language', 'EnglishUK');

							$c->setCache('page_load_cache');
							$c->store('page_load', 0);

							$plugin_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);

							// New settings
							$queries->update('settings', 13, array(
								'value' => 'By registering on our website, you agree to the following:<p>This website uses "Nameless" website software. The "Nameless" software creators will not be held responsible for any content that may be experienced whilst browsing this site, nor are they responsible for any loss of data which may come about, for example a hacking attempt. The website is run independently from the software creators, and any content is the responsibility of the website administration.</p>'
							));

							$queries->create('settings', array(
								'name' => 'mc_api_key',
								'value' => $plugin_key
							));

							$queries->create('settings', array(
								'name' => 'phpmailer',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'phpmailer_type',
								'value' => 'smtp'
							));

							$external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
							if(!count($external_query)){
								$queries->create('settings', array(
									'name' => 'external_query',
									'value' => 'false'
								));
							}

							$queries->create('settings', array(
								'name' => 'use_plugin',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'uuid_linking',
								'value' => '1'
							));

							$queries->create('settings', array(
								'name' => 'inverse_navbar',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'error_reporting',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'ga_script',
								'value' => 'null'
							));

							$queries->create('settings', array(
								'name' => 'avatar_api',
								'value' => 'cravatar'
							));

							// Languages
							$queries->create('settings', array(
								'name' => 'language',
								'value' => 'EnglishUK'
							));

							$queries->create('settings', array(
								'name' => 't_and_c_site',
								'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
							));

							$queries->create('settings', array(
								'name' => 'incoming_email',
								'value' => ''
							));

							$queries->create('settings', array(
								'name' => 'query_update',
								'value' => 'false'
							));

							$queries->create('settings', array(
								'name' => 'mc_status_module',
								'value' => 'false'
							));

							$queries->create('settings', array(
								'name' => 'recaptcha_secret',
								'value' => 'null'
							));

							$queries->create('settings', array(
								'name' => 'email_verification',
								'value' => '1'
							));

							$queries->create('settings', array(
								'name' => 'play_page_enabled',
								'value' => '1'
							));

							$queries->create('settings', array(
								'name' => 'followers',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'discord',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'avatar_type',
								'value' => 'helmavatar'
							));

							$queries->create('settings', array(
								'name' => 'use_mcassoc',
								'value' => '0'
							));

							$queries->create('settings', array(
								'name' => 'mcassoc_key',
								'value' => ''
							));

							$queries->create('settings', array(
								'name' => 'mcassoc_instance',
								'value' => ''
							));

							$queries->create('settings', array(
								'name' => 'twitter_style',
								'value' => 'light'
							));

							$queries->create('settings', array(
								'name' => 'enable_name_history',
								'value' => 1
							));

							// Version update
							$version_id = $queries->getWhere('settings', array('name', '=', 'version'));
							$queries->update('settings', $version_id[0]->id, array(
								'value' => '1.0.21'
							));


							// Close connections
							$mysqli->close();

							echo '<script>window.location.replace("./install?step=finish&from=upgrade");</script>';
							die();

						} else {
							/*
							 *  File not writeable
							 */
							?>
		  					<div class="alert alert-danger">Your <b>core/config.php</b> file is not writeable. Please check your file permissions.</div>
							<?php
							die();
						}
					}
				} else {
					$mysql_error = 'Please input correct details';
				}
			}
		  }
	  ?>
          <div class="row page-header">
            <h3>Upgrade</h3>
            <hr class="small-margin">
            <p>The data of your old installation will now be retrieved and converted.</p>
          </div>
	  <?php if(isset($mysql_error)){ ?>
	  <div class="alert alert-danger">
	    <?php echo $mysql_error; ?>
	  </div>
	  <?php } ?>
	  <form action="" method="post">
	    <div class="form-group">
	      <label for="InputDBIP">Database Address</label>
		  <input type="text" class="form-control" name="db_address" id="InputDBIP" value="<?php echo Input::get('db_address'); ?>" placeholder="Database Address">
	    </div>
	    <div class="form-group">
	      <label for="InputDBPort">Database Port</label>
		  <input type="text" class="form-control" name="db_port" id="InputDBPort" value="3306" placeholder="Database Port">
	    </div>
	    <div class="form-group">
		  <label for="InputDBUser">Database Username</label>
		  <input type="text" class="form-control" name="db_username" id="InputDBUser" value="<?php echo Input::get('db_username'); ?>" placeholder="Database Username">
	    </div>
	    <div class="form-group">
		  <label for="InputDBPass">Database Password</label>
		  <input type="password" class="form-control" name="db_password" id="InputDBPass" placeholder="Database Password">
	    </div>
	    <div class="form-group">
		  <label for="InputDBName">Database Name</label>
		  <input type="text" class="form-control" name="db_name" id="InputDBName" value="<?php echo Input::get('db_name'); ?>" placeholder="Database Name">
	    </div>
	    <div class="form-group">
		  <label for="InputPrefix">Previous Table Prefix with following '_' <em>(can be left empty)</em></label>
		  <input id="InputPrefix" name="prefix" class="form-control" placeholder="Previous table prefix">
		</div>
		<input type="hidden" name="submitted" value="1">
                <hr>
	    <input type="submit" class="btn btn-primary" value="Submit">
	  </form>
	  <?php
	  } else if($step === "configuration"){
		if(Input::exists()){
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'db_address' => array(
					'required' => true
				),
				'db_username' => array(
					'required' => true
				),
				'db_name' => array(
					'required' => true
				),
				'db_port' => array(
					'required' => true
				)
			));

			if($validation->passed()) {
				$db_password = "";
				$db_prefix = "nl1_";
				$cookie_name = "nlmc";

				$db_password = Input::get('db_password');

				if(!empty($db_password)){
					$db_password = Input::get('db_password');
				}

				/*
				 *  Test connection - use MySQLi here, as the config for PDO is not written
				 */
				$mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $db_password, Input::get('db_name'), Input::get('db_port'));
				if($mysqli->connect_errno) {
					$mysql_error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
				} else {
					/*
					 *  Write to config file
					 */
					$insert = 	'<?php' . PHP_EOL .
								'$GLOBALS[\'config\'] = array(' . PHP_EOL .
								'	"mysql" => array(' . PHP_EOL .
								'		"host" => "' . Input::get('db_address') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL .
								'		"username" => "' . Input::get('db_username') . '", // Web server database username' . PHP_EOL .
								'		"password" => \'' . $db_password . '\', // Web server database password' . PHP_EOL .
								'		"db" => "' . Input::get('db_name') . '", // Web server database name' . PHP_EOL .
								'		"port" => "' . Input::get('db_port') . '", // Web server database port' . PHP_EOL .
								'		"prefix" => "' . $db_prefix . '" // Web server table prefix' . PHP_EOL .
								'	),' . PHP_EOL .
								'	"remember" => array(' . PHP_EOL .
								'		"cookie_name" => "' . $cookie_name . '", // Name for website cookies' . PHP_EOL .
								'		"cookie_expiry" => 604800' . PHP_EOL .
								'	),' . PHP_EOL .
								'	"session" => array(' . PHP_EOL .
								'		"session_name" => "user",' . PHP_EOL .
								'		"admin_name" => "admin",' . PHP_EOL .
								'		"token_name" => "token"' . PHP_EOL .
								'	)' . PHP_EOL .
								');';

					if(is_writable('core/config.php')){
						$file = fopen('core/config.php','w');
						fwrite($file, $insert);
						fclose($file);

						echo '<script>window.location.replace("./install?step=database");</script>';
						die();

					} else {
						/*
						 *  File not writeable
						 */
						?>
	   					<br><div class="alert alert-danger">Your <b>core/config.php</b> file is not writeable. Please check your file permissions.</div>
						<?php
						die();
					}
				}
			} else {
				$errors = "";

				foreach($validation->errors() as $error){
					if(strstr($error, 'db_address')){
						$errors .= "Please input a database address<br />";
					}
					if(strstr($error, "db_username")){
						$errors .= "Please input a database username<br />";
					}
					if(strstr($error, "db_name")){
						$errors .= "Please input a database name<br />";
					}
				}
			}
		}
	  ?>
          <div class="row page-header">
            <h3>Configuration</h3>
            <hr class="small-margin">
            <p>Please provide your database details that NamelessMC may connect to.  Please ensure the database has already been created.</p>
          </div>
          <?php
		if(isset($errors)){
	  ?>
	  <div class="alert alert-danger">
	  <?php
		echo $errors;
	  ?>
	  </div>
	  <?php
		}
		if(isset($mysql_error)){
	  ?>
	  <div class="alert alert-danger">
	  <?php
		echo $mysql_error;
	  ?>
	  </div>
	  <?php
		}
	  ?>
	  <form action="" method="post">
	    <div class="form-group">
	      <label for="InputDBIP">Database Address <strong class="text-danger">*</strong></label>
          <input type="text" class="form-control" name="db_address" id="InputDBIP" value="<?php echo Input::get('db_address'); ?>" placeholder="Database Address">
	    </div>
	    <div class="form-group">
	      <label for="InputDBPort">Database Port <strong class="text-danger">*</strong></label>
          <input type="text" class="form-control" name="db_port" id="InputDBPort" value="3306" placeholder="Database Port">
	    </div>
	    <div class="form-group">
	      <label for="InputDBUser">Database Username <strong class="text-danger">*</strong></label>
	      <input type="text" class="form-control" name="db_username" id="InputDBUser" value="<?php echo Input::get('db_username'); ?>" placeholder="Database Username">
	    </div>
	    <div class="form-group">
	      <label for="InputDBPass">Database Password</label>
	      <input type="password" class="form-control" name="db_password" id="InputDBPass" placeholder="Database Password">
	    </div>
	    <div class="form-group">
	      <label for="InputDBName">Database Name <strong class="text-danger">*</strong></label>
	      <input type="text" class="form-control" name="db_name" id="InputDBName" value="<?php echo Input::get('db_name'); ?>" placeholder="Database Name">
	    </div>
            <small><em>Fields marked <strong class="text-danger">*</strong> are required</em></small>
            <hr>
	    <input type="submit" class="btn btn-default" value="Submit">
	  </form>
	  <?php
	  } else if($step === "database"){
	  ?>
          <div class="row page-header">
            <h3>Database initialisation</h3>
            <hr class="small-margin">
            <p>The installer is now attempting to create the necessary tables in the database.</p>
          </div>
	  <?php
		if(!isset($queries)){
			$queries = new Queries(); // Initialise queries
		}
		$prefix = Config::get('mysql/prefix');

                if($queries->dbInitialise($prefix)) {
                    ?>
           <div class="alert alert-success">The database was initialised successfully, and you may now progress with the installation.</div>
           <button type='button' onclick="location.href='./install?step=settings'" class='btn btn-primary'>Continue &raquo;</button>
                    <?php
                } else {
                    echo '<div class="alert alert-danger">Database initialisation failed.</div>';
                }
	  } else if($step === "settings"){
		if(Input::exists()){
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'site_name' => array(
					'required' => true,
					'min' => 2,
					'max' => 1024
				),
				'outgoing_email' => array(
					'required' => true,
					'min' => 2,
					'max' => 1024
				),
				'incoming_email' => array(
					'required' => true,
					'min' => 2,
					'max' => 1024
				)
			));

			if($validation->passed()) {

				if(!isset($queries)){
					$queries = new Queries(); // Initialise queries
				}

				$plugin_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
				$uid = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 62);
				// Get current unix time
				$date = new DateTime();
				$date = $date->getTimestamp();

				$data = array(
					0 => array(
						'name' => 'sitename',
						'value' => htmlspecialchars(Input::get('site_name'))
					),
					1 => array(
						'name' => 'maintenance',
						'value' => 'false'
					),
					2 => array(
						'name' => 'outgoing_email',
						'value' => htmlspecialchars(Input::get('outgoing_email'))
					),
					3 => array(
						'name' => 'incoming_email',
						'value' => htmlspecialchars(Input::get('incoming_email'))
					),
					4 => array(
						'name' => 'youtube_url',
						'value' => 'null'
					),
					5 => array(
						'name' => 'twitter_url',
						'value' => 'null'
					),
					6 => array(
						'name' => 'gplus_url',
						'value' => 'null'
					),
					7 => array(
						'name' => 'fb_url',
						'value' => 'null'
					),
					8 => array(
						'name' => 'twitter_feed_id',
						'value' => 'null'
					),
					9 => array(
						'name' => 'recaptcha',
						'value' => 'false'
					),
					10 => array(
						'name' => 'recaptcha_key',
						'value' => 'null'
					),
					11 => array(
						'name' => 't_and_c',
						'value' => 'By registering on our website, you agree to the following:<p>This website uses "Nameless" website software. The "Nameless" software creators will not be held responsible for any content that may be experienced whilst browsing this site, nor are they responsible for any loss of data which may come about, for example a hacking attempt. The website is run independently from the software creators, and any content is the responsibility of the website administration.</p>'
					),
					12 => array(
						'name' => 't_and_c_site',
						'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
					),
					13 => array(
						'name' => 'displaynames',
						'value' => 'false'
					),
					14 => array(
						'name' => 'ga_script',
						'value' => 'null'
					),
					15 => array(
						'name' => 'avatar_api',
						'value' => 'cravatar'
					),
					16 => array(
						'name' => 'language',
						'value' => 'EnglishUK'
					),
					17 => array(
						'name' => 'forum_layout',
						'value' => '1'
					),
					18 => array(
						'name' => 'error_reporting',
						'value' => '0'
					),
					19 => array(
						'name' => 'inverse_navbar',
						'value' => '0'
					),
					20 => array(
						'name' => 'unique_id',
						'value' => $uid
					),
					21 => array(
						'name' => 'uuid_linking',
						'value' => '1'
					),
					22 => array(
						'name' => 'user_avatars',
						'value' => '0'
					),
					23 => array(
						'name' => 'use_plugin',
						'value' => '0'
					),
					24 => array(
						'name' => 'external_query',
						'value' => 'false'
					),
					25 => array(
						'name' => 'phpmailer',
						'value' => '0'
					),
					26 => array(
						'name' => 'phpmailer_type',
						'value' => 'smtp'
					),
					27 => array(
						'name' => 'mc_api_key',
						'value' => $plugin_key
					),
					28 => array(
						'name' => 'version',
						'value' => '1.0.21'
					),
					29 => array(
						'name' => 'version_checked',
						'value' => $date
					),
					30 => array(
						'name' => 'version_update',
						'value' => 'false'
					),
					31 => array(
						'name' => 'query_update',
						'value' => 'false'
					),
					32 => array(
						'name' => 'mc_status_module',
						'value' => 'false'
					),
					33 => array(
						'name' => 'recaptcha_secret',
						'value' => 'null'
					),
					34 => array(
						'name' => 'email_verification',
						'value' => '1'
					),
					35 => array(
						'name' => 'play_page_enabled',
						'value' => '1'
					),
					36 => array(
						'name' => 'followers',
						'value' => '0'
					),
					37 => array(
						'name' => 'discord',
						'value' => '0'
					),
					38 => array(
						'name' => 'avatar_type',
						'value' => 'helmavatar'
					),
					39 => array(
						'name' => 'use_mcassoc',
						'value' => '0'
					),
					40 => array(
						'name' => 'mcassoc_key',
						'value' => ''
					),
					41 => array(
						'name' => 'mcassoc_instance',
						'value' => ''
					),
					42 => array(
						'name' => 'twitter_style',
						'value' => 'light'
					),
					43 => array(
						'name' => 'enable_name_history',
						'value' => 1
					)
				);

				$youtube_url = Input::get('youtube_url');
				if(!empty($youtube_url)){
					$data[4]["value"] = htmlspecialchars($youtube_url);
				}
				$twitter_url = Input::get('twitter_url');
				if(!empty($twitter_url)){
					$data[5]["value"] = htmlspecialchars($twitter_url);
				}
				$twitter_feed = Input::get('twitter_feed');
				if(!empty($twitter_feed)){
					$data[8]["value"] = htmlspecialchars($twitter_feed);
				}
				$gplus_url = Input::get('gplus_url');
				if(!empty($gplus_url)){
					$data[6]["value"] = htmlspecialchars($gplus_url);
				}
				$fb_url = Input::get('fb_url');
				if(!empty($fb_url)){
					$data[7]["value"] = htmlspecialchars($fb_url);
				}
				if(Input::get('user_usernames') == 1){
					$data[13]["value"] = "true";
				}
				if(Input::get('user_avatars') == 1){
					$data[22]["value"] = "1";
				}

				try {
					foreach($data as $setting){
						$queries->create("settings", array(
							'name' => $setting["name"],
							'value' => $setting["value"]
						));
					}

					$queries->create('custom_pages', array(
						'url' => '/help/',
						'title' => 'Help',
						'content' => 'Default help page. Customise in the admin panel.',
						'link_location' => 3
					));

					// Core Modules
					$modules_initialised = $queries->getWhere('core_modules', array('id', '<>', 0));

					if(!count($modules_initialised)){
						$queries->create('core_modules', array(
							'name' => 'Google_Analytics',
							'enabled' => 0
						));
						$queries->create('core_modules', array(
							'name' => 'Social_Media',
							'enabled' => 1
						));
						$queries->create('core_modules', array(
							'name' => 'Registration',
							'enabled' => 1
						));
						$queries->create('core_modules', array(
							'name' => 'Voice_Server_Module',
							'enabled' => 0
						));
						$queries->create('core_modules', array(
							'name' => 'Staff_Applications',
							'enabled' => 0
						));
					}

					// Themes
					$themes_initialised = $queries->getWhere('themes', array('id', '<>', 0));

					if(!count($themes_initialised)){
						$themes = array(
							1 => array(
								'name' => 'Bootstrap',
								'enabled' => 1
							),
							2 => array(
								'name' => 'Cerulean',
								'enabled' => 0
							),
							3 => array(
								'name' => 'Cosmo',
								'enabled' => 0
							),
							4 => array(
								'name' => 'Cyborg',
								'enabled' => 0
							),
							5 => array(
								'name' => 'Darkly',
								'enabled' => 0
							),
							6 => array(
								'name' => 'Flatly',
								'enabled' => 0
							),
							7 => array(
								'name' => 'Journal',
								'enabled' => 0
							),
							8 => array(
								'name' => 'Lumen',
								'enabled' => 0
							),
							9 => array(
								'name' => 'Paper',
								'enabled' => 0
							),
							10 => array(
								'name' => 'Readable',
								'enabled' => 0
							),
							11 => array(
								'name' => 'Sandstone',
								'enabled' => 0
							),
							12 => array(
								'name' => 'Simplex',
								'enabled' => 0
							),
							13 => array(
								'name' => 'Slate',
								'enabled' => 0
							),
							14 => array(
								'name' => 'Spacelab',
								'enabled' => 0
							),
							15 => array(
								'name' => 'Superhero',
								'enabled' => 0
							),
							16 => array(
								'name' => 'United',
								'enabled' => 0
							),
							17 => array(
								'name' => 'Yeti',
								'enabled' => 0
							)
						);

						foreach($themes as $theme){
							$queries->create('themes', array(
								'enabled' => $theme['enabled'],
								'name' => $theme['name']
							));
						}
					}

					// Templates
					$queries->create('templates', array(
						'enabled' => 1,
						'name' => 'Default'
					));

					// Cache
					$c = new Cache();
					$c->setCache('themecache');
					$c->store('theme', 'Bootstrap');
					$c->store('inverse_navbar', '0');

					$c->setCache('sitenamecache');
					$c->store('sitename', htmlspecialchars(Input::get('site_name')));

					$c->setCache('templatecache');
					$c->store('template', 'Default');

					$c->setCache('languagecache');
					$c->store('language', 'EnglishUK');

					$c->setCache('page_load_cache');
					$c->store('page_load', 0);

					echo '<script>window.location.replace("./install?step=account");</script>';
					die();

				} catch(Exception $e){
					echo "<br><div class='alert alert-danger'>".$e->getMessage()."</div>";
                                        die();
				}

			} else {
				$errors = "";

				foreach($validation->errors() as $error){
					if(strstr($error, 'site_name')){
						$errors .= "Please input a site name<br />";
					}
					if(strstr($error, "outgoing_email")){
						$errors .= "Please input an outgoing email address<br />";
					}
					if(strstr($error, "incoming_email")){
						$errors .= "Please input an incoming email address<br />";
					}
				}
			}
		}
	  ?>
          <div class="row page-header">
            <h3>Settings</h3>
            <hr class="small-margin">
            <p>Please fill out the form below to set your basic settings, these can be changed later.</p>
          </div>
	  <?php
	    if(isset($errors)){
	  ?>
	  <div class="alert alert-danger">
	  <?php
	    echo $errors;
	  ?>
	  </div>
	  <?php
		}
	  ?>
	  <form action="?step=settings" method="post">
            <h4>General</h4>
	    <div class="form-group">
	      <label for="InputSiteName">Site Name <strong class="text-danger">*</strong></label>
		  <input type="text" class="form-control" name="site_name" id="InputSiteName" value="<?php echo Input::get('site_name'); ?>" placeholder="Site Name">
	    </div>
	    <div class="form-group">
	      <label for="InputICEmail">Incoming Email Address <strong class="text-danger">*</strong></label>
		  <input type="email" class="form-control" name="incoming_email" id="InputICEmail" value="<?php echo Input::get('incoming_email'); ?>" placeholder="Incoming Email">
	    </div>
	    <div class="form-group">
	      <label for="InputOGEmail">Outgoing Email Address <strong class="text-danger">*</strong></label>
		  <input type="email" class="form-control" name="outgoing_email" id="InputOGEmail" value="<?php echo Input::get('outgoing_email'); ?>" placeholder="Outgoing Email">
	    </div>
	    <div class="form-group">
	      <label for="InputYT">Youtube URL</label>
		  <input type="text" class="form-control" name="youtube_url" id="InputYT" value="<?php echo Input::get('youtube_url'); ?>" placeholder="Youtube URL">
	    </div>
	    <div class="form-group">
	      <label for="InputTwitter">Twitter URL</label>
		  <input type="text" class="form-control" name="twitter_url" id="InputTwitter" value="<?php echo Input::get('twitter_url'); ?>" placeholder="Twitter URL">
	    </div>
	    <div class="form-group">
	      <label for="InputTwitterFeed">Twitter Feed ID <a data-toggle="modal" href="#twitter_id"><span class="label label-info">?</span></a></label>
		  <input type="text" class="form-control" name="twitter_feed" id="InputTwitterFeed" value="<?php echo Input::get('twitter_feed'); ?>" placeholder="Twitter Feed ID">
	    </div>
	    <div class="form-group">
	      <label for="InputGPlus">Google+ URL</label>
		  <input type="text" class="form-control" name="gplus_url" id="InputGPlus" value="<?php echo Input::get('gplus_url'); ?>" placeholder="Google+ URL">
	    </div>
	    <div class="form-group">
	      <label for="InputFB">Facebook URL</label>
		  <input type="text" class="form-control" name="fb_url" id="InputFB" value="<?php echo Input::get('fb_url'); ?>" placeholder="Facebook URL">
	    </div>
            <small><em>Fields marked <strong class="text-danger">*</strong> are required</em></small>
            <hr>
            <h4>User Accounts</h4>
	    <input type="hidden" name="user_usernames" value="0" />
	    <div class="checkbox">
		  <label>
		    <input type="checkbox" name="user_usernames" value="1"> Allow registering with non-Minecraft display names
		  </label>
	    </div>
		<input type="hidden" name="user_avatars" value="0" />
	    <div class="checkbox">
		  <label>
		    <input type="checkbox" name="user_avatars" value="1"> Allow custom user avatars
		  </label>
            </div>
            <hr>
	    <input type="submit" class="btn btn-primary" value="Submit">
	  </form>
	  <?php
	  } else if($step === "account"){
		if(!isset($queries)){
			$queries = new Queries(); // Initialise queries
		}
		$allow_mcnames = $queries->getWhere("settings", array("name", "=", "displaynames"));
		$allow_mcnames = $allow_mcnames[0]->value; // Can the user register with a non-Minecraft username?

		if(Input::exists()){
			$validate = new Validate();

			$data = array(
				'email' => array(
					'required' => true,
					'min' => 2,
					'max' => 64
				),
				'password' => array(
					'required' => true,
					'min' => 6,
					'max' => 64
				),
				'password_again' => array(
					'required' => true,
					'matches' => 'password'
				)
			);

			if($allow_mcnames === "false"){ // Custom usernames are disabled
				$data['username'] = array(
					'min' => 2,
					'max' => 20
				);
			} else { // Custom usernames are enabled
				$data['username'] = array(
					'min' => 2,
					'max' => 20
				);
				$data['mcname'] = array(
					'min' => 2,
					'max' => 20
				);
			}

			$validation = $validate->check($_POST, $data); // validate

			if($validation->passed()){
				$user = new User();

				// Get Minecraft UUID of user
				if($allow_mcnames !== "false"){
					$mcname = Input::get('mcname');
					$profile = ProfileUtils::getProfile($mcname);
				} else {
					$mcname = Input::get('username');
					$profile = ProfileUtils::getProfile(Input::get('username'));
				}

				if(!empty($profile)){
					$uuid = $profile->getProfileAsArray();
					$uuid = $uuid['uuid'];
					if(empty($uuid)){
						$uuid = '';
					}
				} else {
					$uuid = '';
				}

				if($uuid == ''){
					// Error getting UUID, display an error asking user to update manually
					$uuid_error = true;
				}

				// Hash password
				$password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));

				// Get current unix time
				$date = new DateTime();
				$date = $date->getTimestamp();

				try {
					// Create groups
					// Only create if they don't already exist for some reason
					$group_exists = $queries->getWhere("groups", array("id", "=", 1));
					if(!count($group_exists)){
						$queries->create("groups", array(
							'id' => 1,
							'name' => 'Standard',
							'group_html' => '<span class="label label-success">Member</span>',
							'group_html_lg' => '<span class="label label-success">Member</span>'
						));
					}

					$group_exists = $queries->getWhere("groups", array("id", "=", 2));
					if(!count($group_exists)){
						$queries->create("groups", array(
							'id' => 2,
							'name' => 'Admin',
							'group_html' => '<span class="label label-danger">Admin</span>',
							'group_html_lg' => '<span class="label label-danger">Admin</span>',
							'mod_cp' => 1,
							'admin_cp' => 1,
							'staff' => 1
						));
					}

					$group_exists = $queries->getWhere("groups", array("id", "=", 3));
					if(!count($group_exists)){
						$queries->create("groups", array(
							'id' => 3,
							'name' => 'Moderator',
							'group_html' => '<span class="label label-info">Moderator</span>',
							'group_html_lg' => '<span class="label label-info">Moderator</span>',
							'mod_cp' => 1,
							'staff' => 1
						));
					}

					// Create admin account
					$user->create(array(
						'username' => Input::get('username'),
						'password' => $password,
						'pass_method' => 'default',
						'mcname' => $mcname,
						'uuid' => $uuid,
						'joined' => $date,
						'group_id' => 2,
						'email' => Input::get('email'),
						'lastip' => "",
						'active' => 1
					));

					$login = $user->login(Input::get('username'), Input::get('password'), true);
					if($login) {
						if(!isset($uuid_error)){
							echo '<script>window.location.replace("./install?step=convert");</script>';
						} else {
							echo '<script>window.location.replace("./install?step=convert&error=uuid");</script>';
						}
						die();
					} else {
						echo '<p>Sorry, there was an unknown error logging you in. <a href="/install/?step=account">Try again</a></p>';
						die();
					}

				} catch(Exception $e){
					echo "<br><div class='alert alert-danger'>".$e->getMessage()."</div>";
					die();
				}


			} else {
				Session::flash('admin-acc-error', '
						<div class="alert alert-danger">
							Unable to create account. Please check:<br />
							- You have entered a username between 4 and 20 characters long<br />
							- Your Minecraft username is a valid account<br />
							- Your passwords are at between 6 and 64 characters long and they match<br />
							- Your email address is between 4 and 64 characters<br />
						</div>');
			}
		}
	  ?>
          <div class="row page-header">
            <h3>Admin Account</h3>
            <hr class="small-margin">
            <p>Time to create the first account, this user will be an admin.</p>
          </div>
	  <?php
		if(Session::exists('admin-acc-error')){
			echo Session::flash('admin-acc-error');
		}
	  ?>
	  <form role="form" action="?step=account" method="post">
	    <div class="form-group">
	  	  <label for="InputUsername">Username <strong class="text-danger">*</strong></label>
		  <input type="text" class="form-control" id="InputUsername" name="username" placeholder="Username" tabindex="1">
	    </div>
		<?php
		if($allow_mcnames !== "false"){
		?>
	    <div class="form-group">
		  <label for="InputMCUsername">Minecraft Username <strong class="text-danger">*</strong></label>
		  <input type="text" class="form-control" id="InputMCUsername" name="mcname" placeholder="Minecraft Username" tabindex="2">
	    </div>
		<?php
		}
		?>
	    <div class="form-group">
		  <label for="InputEmail">Email <strong class="text-danger">*</strong></label>
		  <input type="email" name="email" id="InputEmail" class="form-control" placeholder="Email Address" tabindex="3">
	    </div>
	    <div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-6">
			  <div class="form-group">
				<label for="InputPassword">Password <strong class="text-danger">*</strong></label>
				<input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password" tabindex="4">
			  </div>
		  </div>
		  <div class="col-xs-12 col-sm-6 col-md-6">
			  <div class="form-group">
				<label for="InputConfirmPassword">Confirm Password <strong class="text-danger">*</strong></label>
				<input type="password" class="form-control" id="InputConfirmPassword" name="password_again" placeholder="Confirm Password" tabindex="5">
			  </div>
		  </div>
	    </div>
            <small><em>Fields marked <strong class="text-danger">*</strong> are required</em></small>
            <hr>
            <button type="submit" class="btn btn-default">Submit</button>
	  </form>
	  <?php
	  } else if($_GET['step'] === "convert"){
		if(!isset($user)){
			$user = new User();
		}
		if(!$user->isLoggedIn() || $user->data()->group_id != 2){
			echo '<script>window.location.replace("/install/?step=account");</script>';
			die();
		}
		if(isset($_GET["convert"]) && !isset($_GET["from"])){
	  ?>
		<div class="well">
			<h4>Which forum software are you converting from?</h4>
			<!--<a href="#" onclick="location.href='./install.php?step=convert&convert=yes&from=modernbb'">ModernBB</a><br />
			<a href="#" onclick="location.href='./install.php?step=convert&convert=yes&from=phpbb'">phpBB</a><br />
			<a href="#" onclick="location.href='./install.php?step=convert&convert=yes&from=mybb'">MyBB</a><br />
			<a href="#" onclick="location.href='./install.php?step=convert&convert=yes&from=wordpress'">WordPress</a><br />-->
			<a href="#" onclick="location.href='./install?step=convert&convert=yes&from=xenforo'">XenForo</a><br /><br />
			<button class="btn btn-danger" onclick="location.href='./install?step=convert'">Cancel</button>
		</div>
	  <?php
		} else if(isset($_GET["convert"]) && isset($_GET["from"])){
	  ?>
		<div class="well">
	  <?php
		if(strtolower($_GET["from"]) === "modernbb"){
			if(!Input::exists()){
	  ?>
			<h4>Converting from ModernBB:</h4>

	  <?php
				if(isset($_GET["error"])){
	  ?>
			<div class="alert alert-danger">
			  Error connecting to the database. Are you sure you entered the correct credentials?
			</div>
	  <?php
				}
	  ?>

			<form action="?step=convert&convert=yes&from=modernbb" method="post">
			  <div class="form-group">
			    <label for="InputDBAddress">ModernBB Database Address</label>
				<input class="form-control" type="text" id="InputDBAddress" name="db_address" placeholder="Database address">
			  </div>
			  <div class="form-group">
			    <label for="InputDBName">ModernBB Database Name</label>
				<input class="form-control" type="text" id="InputDBName" name="db_name" placeholder="Database name">
			  </div>
			  <div class="form-group">
			    <label for="InputDBUsername">ModernBB Database Username</label>
				<input class="form-control" type="text" id="InputDBUsername" name="db_username" placeholder="Database username">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPassword">ModernBB Database Password</label>
				<input class="form-control" type="password" id="InputDBPassword" name="db_password" placeholder="Database password">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPrefix">ModernBB Table Prefix (blank for none)</label>
				<input class="form-control" type="text" id="InputDBPrefix" name="db_prefix" placeholder="Table prefix">
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="hidden" name="action" value="convert">
			  <input class="btn btn-primary" type="submit" value="Convert">
			  <a href="#" class="btn btn-danger" onclick="location.href='./install?step=convert&convert=yes'">Cancel</a>
			</form>

	  <?php
			} else {
				require 'core/converters/modernbb.php';
	  ?>
			<div class="alert alert-success">
				Successfully imported ModernBB data. <strong>Important:</strong> Please redefine any private categories in the Admin panel.<br />
				<center><button class="btn btn-primary"  onclick="location.href='./install?step=finish'">Proceed</button></center>
			</div>
	  <?php
			}
		} else if(strtolower($_GET["from"]) === "phpbb"){
			if(!Input::exists()){
	  ?>
			<h4>Converting from phpBB:</h4>
			Coming soon. This won't work yet!

	  <?php
				if(isset($_GET["error"])){
	  ?>
			<div class="alert alert-danger">
			  Error connecting to the database. Are you sure you entered the correct credentials?
			</div>
	  <?php
				}
	  ?>

			<form action="?step=convert&convert=yes&from=phpbb" method="post">
			  <div class="form-group">
			    <label for="InputDBAddress">phpBB Database Address</label>
				<input class="form-control" type="text" id="InputDBAddress" name="db_address" placeholder="Database address">
			  </div>
			  <div class="form-group">
			    <label for="InputDBName">phpBB Database Name</label>
				<input class="form-control" type="text" id="InputDBName" name="db_name" placeholder="Database name">
			  </div>
			  <div class="form-group">
			    <label for="InputDBUsername">phpBB Database Username</label>
				<input class="form-control" type="text" id="InputDBUsername" name="db_username" placeholder="Database username">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPassword">phpBB Database Password</label>
				<input class="form-control" type="password" id="InputDBPassword" name="db_password" placeholder="Database password">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPrefix">phpBB Table Prefix (blank for none)</label>
				<input class="form-control" type="text" id="InputDBPrefix" name="db_prefix" placeholder="Table prefix">
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="hidden" name="action" value="convert">
			  <input class="btn btn-primary" type="submit" value="Convert">
			  <a href="#" class="btn btn-danger" onclick="location.href='./install?step=convert&convert=yes'">Cancel</a>
			</form>

	  <?php
			} else {
				require 'core/converters/phpbb.php';
	  ?>
			<div class="alert alert-success">
				Successfully imported phpBB data. <strong>Important:</strong> Please redefine any private categories in the Admin panel.<br />
				<center><button class="btn btn-primary"  onclick="location.href='./install?step=finish'">Proceed</button></center>
			</div>
	  <?php
			}
/*
 * ---- NEW, By dwilson390 -----
 */
		} else if(strtolower($_GET["from"]) === "wordpress"){
			if(!Input::exists()){
	  ?>
			<h4>Converting from WordPress:</h4>

	  <?php
				if(isset($_GET["error"])){
	  ?>
			<div class="alert alert-danger">
			  Error connecting to the database. Are you sure you entered the correct credentials?
			</div>
	  <?php
				}
	  ?>
			<div class="alert alert-success">
				WordPress conversion script created by dwilson390.<br />
			</div>
			<form action="?step=convert&convert=yes&from=wordpress" method="post">
			  <div class="form-group">
			    <label for="InputDBAddress">Wordpress Database Address</label>
				<input class="form-control" type="text" id="InputDBAddress" name="db_address" placeholder="Database address">
			  </div>
			  <div class="form-group">
			    <label for="InputDBName">Wordpress Database Name</label>
				<input class="form-control" type="text" id="InputDBName" name="db_name" placeholder="Database name">
			  </div>
			  <div class="form-group">
			    <label for="InputDBUsername">Wordpress Database Username</label>
				<input class="form-control" type="text" id="InputDBUsername" name="db_username" placeholder="Database username">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPassword">Wordpress Database Password</label>
				<input class="form-control" type="password" id="InputDBPassword" name="db_password" placeholder="Database password">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPrefix">Wordpress Table Prefix (blank for none) (<strong>Remember the '_'</strong>)</label>
				<input class="form-control" type="text" id="InputDBPrefix" name="db_prefix" placeholder="Table prefix">
			  </div>
			  <div class="form-group">
			    <label for="InputDBCheckbox">I have bbPress installed (selecting this option will also import your forums and topics)</label>
				<input class="form-control" type="checkbox" id="InputDBCheckbox" name="db_checkbox" placeholder="Table prefix">
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="hidden" name="action" value="convert">
			  <input class="btn btn-primary" type="submit" value="Convert">
			  <a href="#" class="btn btn-danger" onclick="location.href='./install?step=convert&convert=yes'">Cancel</a>
			</form>

	  <?php
			} else {
				require 'core/converters/wordpress.php';
	  ?>
			<div class="alert alert-success">
				Successfully imported Wordpress data. <strong>Important:</strong> Please redefine any private categories in the Admin panel.<br />
				<center><button class="btn btn-primary"  onclick="location.href='./install?step=finish'">Proceed</button></center>
			</div>
	  <?php
			}

/*
 * ---- END, By dwilson390 -----
 */
		} else if(strtolower($_GET["from"]) === "mybb"){
	?>
			<h4>Converting from MyBB:</h4>
			Coming Soon
	<?php
		} else if(strtolower($_GET["from"]) === "xenforo"){
			if(!Input::exists()){
	  ?>
			<h4>Converting from XenForo:</h4>

	  <?php
				if(isset($_GET["error"])){
	  ?>
			<div class="alert alert-danger">
			  Error connecting to the database. Are you sure you entered the correct credentials?
			</div>
	  <?php
				}
	  ?>

			<form action="?step=convert&convert=yes&from=xenforo" method="post">
			  <div class="form-group">
			    <label for="InputDBAddress">XenForo Database Address</label>
				<input class="form-control" type="text" id="InputDBAddress" name="db_address" placeholder="Database address">
			  </div>
			  <div class="form-group">
			    <label for="InputDBName">XenForo Database Name</label>
				<input class="form-control" type="text" id="InputDBName" name="db_name" placeholder="Database name">
			  </div>
			  <div class="form-group">
			    <label for="InputDBUsername">XenForo Database Username</label>
				<input class="form-control" type="text" id="InputDBUsername" name="db_username" placeholder="Database username">
			  </div>
			  <div class="form-group">
			    <label for="InputDBPassword">XenForo Database Password</label>
				<input class="form-control" type="password" id="InputDBPassword" name="db_password" placeholder="Database password">
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="hidden" name="action" value="convert">
			  <input class="btn btn-primary" type="submit" value="Convert">
			  <a href="#" class="btn btn-danger" onclick="location.href='./install?step=convert&convert=yes'">Cancel</a>
			</form>

	  <?php
			} else {
				require 'core/converters/xenforo.php';
	  ?>
			<div class="alert alert-success">
				Successfully imported XenForo data. <strong>Important:</strong> Please check your forum and group permissions, and update users' Minecraft usernames and UUID through the AdminCP.<br />
				<center><button class="btn btn-primary"  onclick="location.href='./install?step=finish'">Proceed</button></center>
			</div>
	  <?php
			}
		}
	?>
		</div>
	<?php
		} else if(!isset($_GET["convert"]) && !isset($_GET["from"]) && !isset($_GET["action"])){
	?>
	  <h2>Convert</h2>
	  <?php
	    if(isset($_GET['error']) && $_GET['error'] == 'uuid'){
	  ?>
	  <div class="alert alert-danger">
	    Notice: There was an error querying the Minecraft API to retrieve the admin account's UUID. Please update this manually from the AdminCP's users section.
	  </div>
	  <?php
		}
	  ?>
	  <p>Convert from another forum software?</p>
	  <div class="btn-group">
		<button class="btn btn-success" onclick="location.href='./install?step=convert&convert=yes'">Yes</button>
		<button class="btn btn-primary" onclick="location.href='./install?step=finish'">No</button>
	  </div>
	<?php
		}
	  } else if($step === "finish"){
	  ?>
          <div class="row page-header">
            <h3>Finish</h3>
            <hr class="small-margin">
            <p>Great!  NamelessMC has successfully been installed, you can now configure your site to your liking via the admin panel.</p>
          </div>
	  <?php if(isset($_GET['from']) && $_GET['from'] == 'upgrade'){ ?>
	  <div class="alert alert-info">
	    <p>Please note that tables from your old NamelessMC installation have <strong>not</strong> been deleted.  If you'd like to delete the old tables, you can manually delete the tables with your old prefix. The new tables have the prefix <strong>nl1_</strong>.</p>
	  </div>
	  <?php } ?>
          <p>Thanks for using NamelessMC for your servers website.  If you need support you can visit the support forums <a target="_blank" href="https://namelessmc.com">here</a> or you can visit also the offical repository <a target="_blank"href="https://github.com/NamelessMC/Nameless">here</a>.</p>
	  <button type="button" onclick="location.href='./admin/?from=install'" class="btn btn-primary">Proceed to the Admin Panel &raquo;</button>
	  <?php
	  }
	  ?>
      <hr>

      <footer>
        <p>&copy; NamelessMC <?php echo date("Y"); ?></p>
      </footer>
    </div> <!-- /container -->

	<?php
	if($step === "start"){
	?>

    <div class="modal fade" id="bungee_plugins" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Bungee Plugins</h4>
          </div>
          <div class="modal-body">
            NamelessMC includes support for the following BungeeCord plugins:
			<ul>
			  <li><a target="_blank" href="http://www.spigotmc.org/resources/bungee-admin-tools.444/">BungeeAdminTools</a> (for infractions)</li>
			</ul>
          </div>
          <div class="modal-footer">
		    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="mc_plugins" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Minecraft Plugins</h4>
          </div>
          <div class="modal-body">
            NamelessMC includes support for the following Bukkit/Spigot plugins:
			<ul>
			  <li><a target="_blank" href="http://dev.bukkit.org/bukkit-plugins/buycraft/">Buycraft</a></li>
			  <li><a target="_blank" href="http://dev.bukkit.org/bukkit-plugins/ban-management/">Ban Management</a></li>
			  <li><a target="_blank" href="http://dev.bukkit.org/bukkit-plugins/maxbans/">MaxBans</a></li>
			</ul>
			Coming soon:
			<ul>
			  <li><a target="_blank" href="http://www.spigotmc.org/resources/mcmmo.2445/">McMMO</a></li>
			  <li><a target="_blank" href="http://dev.bukkit.org/bukkit-plugins/lolmewnstats/">Stats</a></li>
			  <li><a target="_blank" href="http://www.spigotmc.org/resources/bukkitgames-hungergames.279/">BukkitGames</a></li>
			</ul>
          </div>
          <div class="modal-footer">
		    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

	<?php
	}
	if($step === "settings"){
	?>
    <div class="modal fade" id="twitter_id" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Twitter Feed ID</h4>
          </div>
          <div class="modal-body">
			To find your Twitter feed ID, first head into the <a target="_blank" href="https://twitter.com/settings/widgets">Twitter Widgets tab</a> in your settings. Click the "Create new" button in the top right corner of the panel, set the "Height" to "500", and then click Create Widget.<br /><br />Underneath the Preview, a new textarea will appear with some HTML code. You need to find <code>data-widget-id=</code> and copy the number between the "". <br /><br />This is your Twitter feed ID.
          </div>
          <div class="modal-footer">
		    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
	<?php
	}
	?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./core/assets/js/jquery.min.js"></script>
    <script src="./core/assets/js/bootstrap.min.js"></script>
  </body>
</html>
