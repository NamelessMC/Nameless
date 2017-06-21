<?php 
/*
 *	Made by Samerton
 *  https://worldscapemc.com
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
Contributors: partydragen, relavis<br />
Version: 1.1.0<br />
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
				
				// Link location
				$c->setCache('infractionsaddon');
				$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
					
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
										'    \'password\' => \'' . str_replace('\'', '\\\'', Input::get('dbpassword')) . '\',' . PHP_EOL .
										'    \'prefix\' => \'' . str_replace('\'', '\\\'', Input::get('dbprefix')) . '\'' . PHP_EOL .
										');';
						
						$file = fopen($config_path, 'w');
						fwrite($file, $input_string);
						fclose($file);
						
					} else {
						// Not writable
						Session::flash('admin_infractions', '<div class="alert alert-danger">Your <strong>addons/Infractions/config.php</strong> file is not writable. Please check file permissions.</div>');
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
	
	require(join(DIRECTORY_SEPARATOR, array('addons', 'Infractions', 'config.php')));
?>
<form action="" method="post">
  <strong>Infractions Plugin</strong> <a href="#" data-toggle="modal" data-target="#helpModal"><span class="label label-info"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a><br /><br />
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
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'bam'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType3" value="bam" autocomplete="off"<?php if($infractions_settings[0]->value == 'bam'){ ?> checked<?php } ?>> Ban and Mute Plugin
    </label>
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'bu'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType4" value="bu" autocomplete="off"<?php if($infractions_settings[0]->value == 'bu'){ ?> checked<?php } ?>> BungeeUtilisals
    </label>
    <label class="btn btn-primary<?php if($infractions_settings[0]->value == 'ab'){ ?> active<?php } ?>">
	  <input type="radio" name="plugin_type" id="InputPluginType5" value="ab" autocomplete="off"<?php if($infractions_settings[0]->value == 'ab'){ ?> checked<?php } ?>> AdvancedBan
    </label>
  </div>
  <br /><br />
  <div class="form-group">
	<label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
	<?php
	// Get position of link
	$c->setCache('infractionsaddon');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'footer');
		$link_location = 'footer';
	}
	?>
	<select name="linkposition" id="InputLinkPosition" class="form-control">
	  <option value="navbar" <?php if($link_location == 'navbar'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
	  <option value="more" <?php if($link_location == 'more'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_more']; ?></option>
	  <option value="footer" <?php if($link_location == 'footer'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
	  <option value="none" <?php if($link_location == 'none'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
	</select>
  </div>
  <input type="hidden" name="action" value="settings">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
</form>
<hr />
<form action="" method="post">
  <strong>Update Database Settings</strong><br /><br />
  <div class="form-group">
    <label for="InputDatabaseAddress">Database Address</label>
	<input type="text" id="InputDatabaseAddress" name="dbaddress" class="form-control" placeholder="Address" value="<?php echo $inf_db['address']; ?>">
  </div>
  <div class="form-group">
    <label for="InputDatabaseName">Database Name</label>
	<input type="text" id="InputDatabaseName" name="dbname" class="form-control" placeholder="Name" value="<?php echo $inf_db['name']; ?>">
  </div>
  <div class="form-group">
    <label for="InputTablePrefix">Table Prefix (with trailing _)</label>
	<input type="text" id="InputTablePrefix" name="dbprefix" class="form-control" placeholder="Prefix" value="<?php echo $inf_db['prefix']; ?>">
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

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="helpModalLabel">Help</h4>
      </div>
      <div class="modal-body">
        <strong>Links:</strong><hr />
		<ul>
		  <li><a href="https://www.spigotmc.org/resources/bungee-admin-tools-basics-edition.444/" target="_blank">Bungee Admin Tools</a></li>
		  <li><a href="http://dev.bukkit.org/bukkit-plugins/ban-management/" target="_blank">Ban Management</a></li>
		  <li><a href="https://www.spigotmc.org/resources/litebans.3715/" target="_blank">LiteBans</a></li>
		  <li><a href="https://www.spigotmc.org/resources/bansystem-chatban-mute-report-system-chatfilter-bungeecord-mysql.17875/" target="_blank">Ban and Mute Plugin</a></li>
		  <li><a href="https://www.spigotmc.org/resources/bungeeutilisals.7865/" target="_blank">Bungee Utilisals</a></li>
		  <li><a href="https://www.spigotmc.org/resources/advancedban.8695/" target="_blank">Advanced Ban</a></li>
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
