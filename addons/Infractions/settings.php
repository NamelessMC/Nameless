<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Settings for the Infractions addon

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
?>

<h3>Addon: Infractions</h3>
Author: Samerton<br />
Version: 1.0.0<br />
Description: Integrate your server infractions with your website<br />

<h3>Infractions Settings</h3>
<?php
$infractions_settings = $queries->tableExists('infractions_settings');
if(empty($infractions_settings)){
	// Hasn't been installed yet
	// Install now
	$data = $queries->createTable("infractions_settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) NOT NULL, `value` varchar(128) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Infraction Settings</strong> table successfully initialised<br />';
	
	// Insert data
	$queries->create('infractions_settings', array(
		'name' => 'plugin_type',
		'value' => 'bat'
	));
	
	echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=Infractions\');</script>';
	die();
} else {
	// Get settings from database
	$infractions_settings = $queries->getWhere('infractions_settings', array('id', '<>', 0));
	// Check input
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			// Valid token
			if(Input::get('action') == 'settings'){
				// Update settings
				$queries->update('infractions_settings', 1, array(
					'value' => htmlspecialchars(Input::get('plugin_type'))
				));
				
				echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=Infractions\');</script>';
				die();
				
			} else if(Input::get('action') == 'database'){
				// Update database details
				// Validate input
				$validate = new Validate();
				
				$validation = $validate->check($_POST, array(
					'dbaddress' => array(
						'required' => true
					),
					'dbname' => array(
						'required' => true
					),
					'dbusername' => array(
						'required' => true
					)
				));
				
				if($validation->passed()){
					// Check config is writable
					// Generate config path
					$config_path = join(DIRECTORY_SEPARATOR, array('addons', 'Infractions', 'config.php'));
					
					if(is_writable($config_path)){
						// Writable
						
						// Make string to input
						$input_string = '<?php' . PHP_EOL . 
										'$inf_db = array(' . PHP_EOL .
										'    \'address\' => \'' . str_replace('\'', '\\\'', Input::get('dbaddress')) . '\',' . PHP_EOL .
										'    \'name\' => \'' . str_replace('\'', '\\\'', Input::get('dbname')) . '\',' . PHP_EOL .
										'    \'username\' => \'' . str_replace('\'', '\\\'', Input::get('dbusername')) . '\',' . PHP_EOL .
										'    \'password\' => \'' . str_replace('\'', '\\\'', Input::get('dbpassword')) . '\'' . PHP_EOL .
										');';
						
						$file = fopen($config_path, 'w');
						fwrite($file, $input_string);
						fclose($file);
						
					} else {
						// Not writable
						Session::flash('admin_infractions', '<div class="alert alert-danger">Your <strong>addons/Infractions/settings.php</strong> file is not writable. Please check file permissions.</div>');
					}
				}
				
			}
		} else {
			// Invalid token
			Session::flash('admin_infractions', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
		}
	}
	
	// Display settings
	if(Session::exists('admin_infractions')){
		echo Session::flash('admin_infractions');
	}
	
	// Generate token
	$token = Token::generate();
?>
<form action="" method="post">
  <strong>Infractions Plugin</strong><br />
  <div class="btn-group" data-toggle="buttons">
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'bat'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType1" value="bat" autocomplete="off"<?php if($infractions_settings[0]->value == 'bat'){ ?> checked<?php } ?>> Bungee Admin Tools
    </label>
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'bm'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType2" value="bm" autocomplete="off"<?php if($infractions_settings[0]->value == 'bm'){ ?> checked<?php } ?>> Ban Management
    </label>
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'lb'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType3" value="lb" autocomplete="off"<?php if($infractions_settings[0]->value == 'lb'){ ?> checked<?php } ?>> LiteBans
    </label>
  </div>
  <br /><br />
  <input type="hidden" name="action" value="settings">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
</form>
<hr />
<form action="" method="post">
  <strong>Update Database Settings</strong><br /><br />
  <div class="form-group">
    <label for="InputDatabaseAddress">Database Address</label>
	<input type="text" id="InputDatabaseAddress" name="dbaddress" class="form-control" placeholder="Hidden">
  </div>
  <div class="form-group">
    <label for="InputDatabaseName">Database Name</label>
	<input type="text" id="InputDatabaseName" name="dbname" class="form-control" placeholder="Hidden">
  </div>
  <div class="form-group">
    <label for="InputDatabaseUsername">Database Username</label>
	<input type="text" id="InputDatabaseUsername" name="dbusername" class="form-control" placeholder="Hidden">
  </div>
  <div class="form-group">
    <label for="InputDatabasePassword">Database Password</label>
	<input type="password" id="InputDatabasePassword" name="dbpassword" class="form-control" placeholder="Hidden">
  </div>
  <input type="hidden" name="action" value="database">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
</form>
<?php
}