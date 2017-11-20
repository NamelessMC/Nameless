<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
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
  <li<?php if(isset($_GET['view']) && $_GET['view'] == 'packages'){ ?> class="active"<?php } ?>><a href="/admin/addons/?action=edit&amp;addon=Donate&amp;view=packages">Packages</a></li>
</ul>

<?php if(!isset($_GET['view']) && !isset($_GET['do'])){ ?>  
<h3>Addon: Donate</h3>
Author: Samerton<br />
Version: 1.1.3<br />
Description: Integrate a donation store with your website<br />

<h3>Donation Store</h3>
<?php
$donation_settings = $queries->tableExists('donation_settings');
if(empty($donation_settings)){
	// Hasn't been installed yet
	// Install now
	$data = $queries->createTable("donation_cache", " `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `uuid` varchar(32) NOT NULL, `ign` varchar(20) NOT NULL, `price` varchar(10) NOT NULL, `package` varchar(64) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Cache</strong> table successfully initialised<br />';
	$data = $queries->createTable("donation_categories", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `cid` varchar(64) NOT NULL, `order` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Donation Categories</strong> table successfully initialised<br />';
	$data = $queries->createTable("donation_packages", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `description` mediumtext, `cost` varchar(10) NOT NULL, `package_id` varchar(64) NOT NULL, `active` tinyint(4) NOT NULL, `package_order` int(11) NOT NULL, `category` varchar(64) NOT NULL, `url` varchar(512) NOT NULL, `custom_description` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
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
					'max' => 60
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
				
				// Link location
				$c->setCache('donateaddon');
				$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
				
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
    <label class="btn btn-primary<?php if($donation_settings[0]->value == 'cs'){ ?> active<?php } ?>">
	  <input type="radio" name="store_type" id="InputStoreType3" value="cs" autocomplete="off"<?php if($donation_settings[0]->value == 'cs'){ ?> checked<?php } ?>> CraftingStore
    </label>
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
  <div class="form-group">
	<label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
	<?php
	// Get position of link
	$c->setCache('donateaddon');
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
		if($_GET['view'] == 'packages'){
			// Change packages
			if(!isset($_GET['package'])){
				?>
	<h3>Packages</h3>
	Click to edit package descriptions:<br /><br />
				<?php
				// Display a list of all available packages
				$packages = $queries->getWhere('donation_packages', array('id', '<>', 0));
				
				if(count($packages)){
					echo '<ul>';
					foreach($packages as $package){
						echo '<li><a href="/admin/addons/?action=edit&amp;addon=Donate&amp;view=packages&amp;package=' . $package->package_id . '">' . htmlspecialchars($package->name) . '</a></li>';
					}
					echo '</ul>';
				} else echo 'No packages available yet.';
			} else {
				if(!isset($_GET['reset'])){
					// Ensure package exists
					$package = $queries->getWhere('donation_packages', array('package_id', '=', htmlspecialchars($_GET['package'])));
					
					if(!count($package)){
						echo '<script>window.location.replace(\'/admin/addons/?action=edit&addon=Donate\');</script>';
						die();
					}
					
					$package = $package[0];
					
					if(Input::exists()){
						if(Token::check(Input::get('token'))){
							// Validate input
							$validate = new Validate();
							
							$validation = $validate->check($_POST, array(
								'editor' => array(
									'required' => true,
									'min' => 1,
									'max' => 20000
								)
							));
							
							if($validation->passed()){
								try {
									$queries->update('donation_packages', $package->id, array(
										'description' => htmlspecialchars(Input::get('editor')),
										'custom_description' => 1
									));
									
									// Requery to bring $package up to date
									$package = $queries->getWhere('donation_packages', array('package_id', '=', $package->package_id));
									$package = $package[0];
									
									$error = '<div class="alert alert-success">Updated successfully.</div>';
									
								} catch(Exception $e){
									$error = '<div class="alert alert-danger">Error: ' . $e->getMessage . '</div>';
								}
							} else {
								$error = '<div class="alert alert-danger">Please input a valid description between 1 and 20000 characters long.</div>';
							}
						} else {
							// Invalid token
							$error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
						}
					}
					
					// Generate form token
					$token = Token::generate();
					
					// HTMLPurifier
					require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
					$config = HTMLPurifier_Config::createDefault();
					$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
					$config->set('URI.DisableExternalResources', false);
					$config->set('URI.DisableResources', false);
					$config->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
					$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
					$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
					$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
					$purifier = new HTMLPurifier($config);
				?>
				<br />
				<h3 style="display:inline;">Editing package <?php echo htmlspecialchars($package->name); ?></h3>
				<span class="pull-right"><a class="btn btn-danger" onclick="return confirm('Are you sure you want to reset the package description?');"href="/admin/addons/?action=edit&amp;addon=Donate&amp;view=packages&amp;package=<?php echo $package->package_id; ?>&amp;reset=true">Reset</a></span>
				<br /><br />
				<?php if(isset($error)) echo $error; ?>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="InputDescription">Description</label>
				    <textarea class="editor" rows="10" name="editor" id="InputDescription"><?php echo $purifier->purify(htmlspecialchars_decode($package->description)); ?></textarea>
				  </div>
				  <div class="form-group">
				    <input type="hidden" name="token" value="<?php echo $token; ?>">
				    <input class="btn btn-primary" type="submit" value="<?php echo $general_language['submit']; ?>">
					<a href="/admin/addons/?action=edit&amp;addon=Donate&amp;view=packages" onclick="return confirm('Are you sure?');" class="btn btn-danger">Cancel</a>
				  </div>
				</form>
				
				<script src="/core/assets/js/ckeditor.js"></script>
				<script type="text/javascript">
					CKEDITOR.replace( 'editor', {
						// Define the toolbar groups as it is a more accessible solution.
						toolbarGroups: [
							{"name":"basicstyles","groups":["basicstyles"]},
							{"name":"paragraph","groups":["list","align"]},
							{"name":"styles","groups":["styles"]},
							{"name":"colors","groups":["colors"]},
							{"name":"links","groups":["links"]}
						],
						// Remove the redundant buttons from toolbar groups defined above.
						removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
					} );
					CKEDITOR.config.disableNativeSpellChecker = false;
					CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
				</script>
					<?php
				} else {
					// Reset
					if(is_numeric($_GET['package'])){
						$package = $queries->getWhere('donation_packages', array('package_id', '=', $_GET['package']));
						if(count($package)){
							$queries->update('donation_packages', $package[0]->id, array(
								'custom_description' => 0
							));
							
							Session::flash('admin_donate', '<div class="alert alert-success">Package description reset. The description will be updated during the next sync.</div>');
							echo '<script>window.location.replace(\'/admin/addons/?action=edit&addon=Donate\');</script>';
							die();
						}
					}
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
	<strong>for example</strong> (will run every 20 minutes):<br />
	<code>wget --spider "http://<?php echo $_SERVER['SERVER_NAME']; ?>/addons/Donate/sync.php?key=<?php echo $unique_key; ?>" >/dev/null 2>&1</code>
	<br /><br />
	<strong>Please keep the above URL a secret!</strong>
	<br /><br />
	To avoid using the API too often, please leave a reasonable time period between running the cron job, such as 20 minutes.
	
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
