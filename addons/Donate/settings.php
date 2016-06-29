<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Settings for the Donate addon

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
<ul class="nav nav-pills">
  <li<?php if(!isset($_GET['view'])){ ?> class="active"<?php } ?>><a href="/admin/addons/?action=edit&amp;addon=Donate">Settings</a></li>
  <!--<li<?php //if(isset($_GET['view']) && $_GET['view'] == 'mcstock'){ ?> class="active"<?php //} ?>><a href="/admin/addons/?action=edit&amp;addon=Donate&amp;view=mcstock">MCStock</a></li>-->
</ul>

<?php if(!isset($_GET['view']) && !isset($_GET['do'])){ ?>  
<h3>Addon: Donate</h3>
Author: Samerton<br />
Version: 1.0.2<br />
Description: Integrate a donation store with your website<br />

<h3>Donation Store</h3>
<?php
$donation_settings = $queries->tableExists('donation_settings');
if(empty($donation_settings)){
	// Hasn't been installed yet
	// Install now
	$data = $queries->createTable("donation_cache", " `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `uuid` varchar(32) NOT NULL, `ign` varchar(20) NOT NULL, `price` varchar(10) NOT NULL, `package` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Cache</strong> table successfully initialised<br />';
	$data = $queries->createTable("donation_categories", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `cid` varchar(64) NOT NULL, `order` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Categories</strong> table successfully initialised<br />';
	$data = $queries->createTable("donation_packages", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `description` varchar(2048) NOT NULL, `cost` varchar(10) NOT NULL, `package_id` varchar(64) NOT NULL, `active` tinyint(4) NOT NULL, `package_order` int(11) NOT NULL, `category` varchar(64) NOT NULL, `url` varchar(512) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Packages</strong> table successfully initialised<br />';
	$data = $queries->createTable("donation_settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) NOT NULL, `value` varchar(128) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Settings</strong> table successfully initialised<br />';
	
	// Insert data
	$queries->create('donation_settings', array(
		'name' => 'store_type',
		'value' => 'bc'
	));
	$queries->create('donation_settings', array(
		'name' => 'api_key',
		'value' => ''
	));
	$queries->create('donation_settings', array(
		'name' => 'allow_guests',
		'value' => '0'
	));
	$queries->create('donation_settings', array(
		'name' => 'integrated',
		'value' => '1'
	));
	$queries->create('donation_settings', array(
		'name' => 'store_url',
		'value' => ''
	));
	$queries->create('donation_settings', array(
		'name' => 'currency',
		'value' => '1'
	));
	
	echo '<script>window.location.replace(\'/admin/addons/?action=edit&addon=Donate\');</script>';
	die();
} else {
	// Get settings from database
	$donation_settings = $queries->getWhere('donation_settings', array('id', '<>', 0));
	// Check input
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			// Valid token
			// Validate input
			$validate = new Validate();
			
			$validation = $validate->check($_POST, array(
				'api_key' => array(
					'max' => 40
				)
			));
			
			if($validation->passed()){
				// Save changes
				// Update store type
				$queries->update("donation_settings", 1, array(
					"value" => Input::get('store_type')
				));
				
				// API Key
				$queries->update("donation_settings", 2, array(
					"value" => htmlspecialchars(Input::get('api_key'))
				));
				
				// Allow guests?
				$queries->update("donation_settings", 3, array(
					"value" => htmlspecialchars(Input::get('guests'))
				));
				
				// Integrated store?
				$queries->update("donation_settings", 4, array(
					"value" => htmlspecialchars(Input::get('integrated'))
				));
				
				// Store URL
				$queries->update("donation_settings", 5, array(
					"value" => htmlspecialchars(Input::get('store_url'))
				));
				
				// Store currency
				$queries->update("donation_settings", 6, array(
					'value' => Input::get('currency')
				));
				
				// Query again because settings updated
				// Get settings from database
				$donation_settings = $queries->getWhere('donation_settings', array('id', '<>', 0));
				
			} else {
				Session::flash('admin_donate', '<div class="alert alert-danger">Please enter a valid API key.</div>');
			}
		
		} else {
			// Invalid token
			Session::flash('admin_donate', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
		}
	}
	
	// Display settings
	if(Session::exists('admin_donate')){
		echo Session::flash('admin_donate');
	}
?>
<form action="" method="post">
  <strong>Donation Plugin</strong><br />
  <div class="btn-group" data-toggle="buttons">
    <label class="btn btn-primary<?php if($donation_settings[0]->value == 'bc'){ ?> active<?php } ?>">
	  <input type="radio" name="store_type" id="InputStoreType1" value="bc" autocomplete="off"<?php if($donation_settings[0]->value == 'bc'){ ?> checked<?php } ?>> Buycraft
    </label>
    <label class="btn btn-primary<?php if($donation_settings[0]->value == 'mm'){ ?> active<?php } ?>">
	  <input type="radio" name="store_type" id="InputStoreType2" value="mm" autocomplete="off"<?php if($donation_settings[0]->value == 'mm'){ ?> checked<?php } ?>> Minecraft Market
    </label>
    <!--<label class="btn btn-primary<?php //if($donation_settings[0]->value == 'mcs'){ ?> active<?php //} ?>">
	  <input type="radio" name="store_type" id="InputStoreType3" value="mcs" autocomplete="off"<?php //if($donation_settings[0]->value == 'mcs'){ ?> checked<?php //} ?>> MCStock
    </label>-->
  </div>
  <br /><br />
  <div class="form-group">
    <label for="api_key">API Key</label>
	<input type="text" class="form-control" id="api_key" name="api_key" value="<?php echo htmlspecialchars($donation_settings[1]->value); ?>">
  </div>
  <div class="row">
    <div class="col-md-3">
	  <div class="form-group">
		<label for="integrated">Integrated store?</label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="Having this disabled will send users to your external store instead of the integrated page."><i class="fa fa-question-circle"></i></a>
		<input type="hidden" name="integrated" value="0">
		<span class="pull-right">
		  <input type="checkbox" name="integrated" id="integrated" class="js-switch" value="1"<?php if($donation_settings[3]->value == '1'){ ?> checked<?php } ?> >
	    </span>
	  </div>
	  <div class="form-group">
		<label for="guests">Allow guests?</label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="Allow guests to view the store?"><i class="fa fa-question-circle"></i></a>
		<input type="hidden" name="guests" value="0">
		<span class="pull-right">
		  <input type="checkbox" name="guests" id="guests" class="js-switch" value="1"<?php if($donation_settings[2]->value == '1'){ ?> checked<?php } ?> >
	    </span>
	  </div>
	</div>
  </div>
  <div class="form-group">
    <label for="store_url">Store URL <em>(Prefix with http:// or https://)</em></label>
	<input type="text" class="form-control" id="store_url" name="store_url" value="<?php echo htmlspecialchars($donation_settings[4]->value); ?>">
  </div>
  <div class="form-group">
	<label for="InputCurrency">Donation Currency</label>
	<select class="form-control" id="InputCurrency" name="currency">
	  <option value="0" <?php if($donation_settings[5]->value == '0'){ echo ' selected="selected"'; } ?>>$</option>
	  <option value="1" <?php if($donation_settings[5]->value == '1'){ echo ' selected="selected"'; } ?>>£</option>
	  <option value="2" <?php if($donation_settings[5]->value == '2'){ echo ' selected="selected"'; } ?>>€</option>
	  <option value="3" <?php if($donation_settings[5]->value == '3'){ echo ' selected="selected"'; } ?>>R$</option>
	</select>
  </div>
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
</form>
<h3>Actions</h3>
<a href="/admin/addons/?action=edit&amp;addon=Donate&amp;do=sync" class="btn btn-primary" data-toggle="popover" data-content="This will synchronise your website's Donate page with your donor store, displaying latest donors, packages and it will assign groups to users if set up in the Groups tab of the AdminCP.">Synchronise donor store</a>&nbsp;
<a href="/admin/addons/?action=edit&amp;addon=Donate&amp;do=clear" onclick="return confirm('Are you sure you want to clear the cache?');" class="btn btn-warning" data-toggle="popover" data-content="Clearing the cache will empty your 'Latest Donors' list and also the package and category list.">Clear donation cache</a>
<?php
}
} else {
	if(isset($_GET['view']) && !isset($_GET['do'])){
		if($_GET['view'] == 'mcstock'){
			// MCStock integration
			$donation_settings = $queries->getWhere('donation_settings', array('id', '<>', 0));
			if(!count($donation_settings)){
				// Hasn't been installed yet
			} else {
	?>
	<h3>MCStock</h3>
	<?php
				// Is MCStock enabled?
				if($donation_settings[0]->value == 'mcs'){ // Yes
	?>
	Control your MCStock donor store from here.<br /><br />
	<div class="alert alert-warning">Coming soon</div>
	<?php
				} else { // No
	?>
	<div class="alert alert-info">
	  MCStock is not selected as your donation plugin.
	</div>
	<?php
				}
			}
		}
	} else if(isset($_GET['do']) && !isset($_GET['view'])){
		if($_GET['do'] == 'sync'){
			// Synchronise with web store
			// Get site's unique key
			$unique_key = $queries->getWhere('settings', array('name', '=', 'unique_id'));
			$unique_key = htmlspecialchars($unique_key[0]->value);
			
			// Get plugin in use
			$donation_plugin = $queries->getWhere('donation_settings', array('id', '=', '1'));
			$donation_plugin = htmlspecialchars($donation_plugin[0]->value);
		?>
	<h3>Synchronise</h3>
	<p>The following button will synchronise your web store with your site database. Please be patient, this process may take a while...</p>
	<center><a onclick="syncDonate();" class="btn btn-primary">Synchronise</a></center>
	<h3>Automating the synchronisation</h3>
	In order to automate the synchronisation, you will need to set up a cron job on your webserver. It will need to load the following URL:
	<br />
	<code>http://<?php echo $_SERVER['SERVER_NAME']; ?>/addons/Donate/sync.php?key=<?php echo $unique_key; ?></code>
	<br /><br />
	<strong>Please keep the above URL a secret!</strong>
	<br /><br />
	To avoid using the API too often, please leave a reasonable time period between running the cron job.
	
	<!-- Modal -->
	<div class="modal fade" data-keyboard="false" data-backdrop="static" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="loadingModalLabel">Synchronising, please wait..</h4>
		  </div>
		  <div class="modal-body">
			<div class="progress">
			  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>		

	<script type="text/javascript">
	function syncDonate()
	{
		$('#loadingModal').modal('show');
		$.ajax(
			{
				   type: "POST",
				   url: "/addons/Donate/sync.php?key=<?php echo $unique_key; ?>",
				   cache: false,

				   success: function(response)
				   {
					$('#loadingModal').modal('hide');
					location.reload();
				   }
			 });
	}
	</script>
		<?php
		} else if($_GET['do'] == 'clear'){
			// Clear cache
			$queries->delete('donation_cache', array('id', '<>', 0));
			$queries->delete('donation_categories', array('id', '<>', 0));
			$queries->delete('donation_packages', array('id', '<>', 0));
			
			Session::flash('admin_donate', '<div class="alert alert-info">Cache cleared successfully</div>');
			echo '<script>window.location.replace(\'/admin/addons/?action=edit&addon=Donate\');</script>';
			die();
		}
	}
}
