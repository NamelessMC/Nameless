<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Admin Minecraft page
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
		} else {
        	if(!$user->hasPermission('admincp.minecraft')){
        	    require(ROOT_PATH . '/404.php');
        	    die();
        	}
        }
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

// Check if Minecraft integration is enabled
$minecraft_enabled = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
$minecraft_enabled = $minecraft_enabled[0]->value;

$page = 'admin';
$admin_page = 'minecraft';

?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<?php 
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.css">

    <style type="text/css">
      .thumbnails li img{
          width: 200px;
      }
    </style>

  </head>
  <body>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <h3><?php echo $language->get('admin', 'minecraft'); ?></h3>
              <hr />
              <?php
              if(!isset($_GET['view'])) {
                  // Deal with input
                  if(Input::exists()){
                      // Check token
                      if(Token::check(Input::get('token'))){
                          // Valid token
                          // Process input
                          if(isset($_POST['enable_minecraft'])){
                              // Either enable or disable Minecraft integration
                              $enable_minecraft_id = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
                              $enable_minecraft_id = $enable_minecraft_id[0]->id;

                              $queries->update('settings', $enable_minecraft_id, array(
                                  'value' => Input::get('enable_minecraft')
                              ));

                              // Re-query for Minecraft integration
                              $minecraft_enabled = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
                              $minecraft_enabled = $minecraft_enabled[0]->value;
                          } else {
                              // Integration settings

                          }
                      } else {
                          // Invalid token

                      }
                  }
              ?>
                <form id="enableMinecraft" action="" method="post">
                    <?php echo $language->get('admin', 'enable_minecraft_integration'); ?>
                  <input type="hidden" name="enable_minecraft" value="0">
                  <input name="enable_minecraft" type="checkbox"
                         class="js-switch js-check-change"<?php if ($minecraft_enabled == '1') { ?> checked<?php } ?>
                         value="1"/>
                  <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                </form>

                  <?php
                  if ($minecraft_enabled == '1') {
                  ?>
                    <hr />
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <?php if($user->hasPermission('admincp.minecraft.authme')){ ?>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=authme'); ?>"><?php echo $language->get('admin', 'authme_integration'); ?></a>
                          </td>
                        </tr>
                        <?php } if($user->hasPermission('admincp.minecraft.verification')){ ?>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=account_verification'); ?>"><?php echo $language->get('admin', 'account_verification'); ?></a>
                          </td>
                        </tr>
                        <?php } if($user->hasPermission('admincp.minecraft.servers')){ ?>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=servers'); ?>"><?php echo $language->get('admin', 'minecraft_servers'); ?></a>
                          </td>
                        </tr>
                        <?php } if($user->hasPermission('admincp.minecraft.query_errors')){ ?>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=query_errors'); ?>"><?php echo $language->get('admin', 'query_errors'); ?></a>
                          </td>
                        </tr>
                        <?php } if($user->hasPermission('admincp.minecraft.banners') && function_exists('exif_imagetype')){ ?>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=banners'); ?>"><?php echo $language->get('admin', 'server_banners'); ?></a>
                          </td>
                        </tr>
                        <?php } ?>
                      </table>
                    </div>
                  <?php
                  }
              } else {
                switch($_GET['view']){
                    case 'account_verification':
                      if(!$user->hasPermission('admincp.minecraft.verification')){
                        Redirect::to(URL::build('/admin/minecraft'));
                        die();
                      }
                      echo '<h4>' . $language->get('admin', 'account_verification') . '</h4>';

                      // Handle input
                      if(Input::exists()){
                        if(Token::check(Input::get('token'))){
                          if(!isset($_POST['premium'])) {
                              $use_mcassoc = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
                              $use_mcassoc = $use_mcassoc[0]->id;

                              if (isset($_POST['use_mcassoc']) && $_POST['use_mcassoc'] == 'on') {
                                  $validate = new Validate();
                                  $validation = $validate->check($_POST, array(
                                      'mcassoc_key' => array(
                                          'required' => true,
                                          'max' => 128
                                      ),
                                      'mcassoc_instance' => array(
                                          'required' => true,
                                          'min' => 32,
                                          'max' => 32
                                      )
                                  ));

                                  if ($validation->passed()) {
                                      // Update settings
                                      $mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
                                      $mcassoc_key = $mcassoc_key[0]->id;

                                      $mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
                                      $mcassoc_instance = $mcassoc_instance[0]->id;

                                      $queries->update('settings', $use_mcassoc, array('value' => 1));
                                      $queries->update('settings', $mcassoc_key, array('value' => Input::get('mcassoc_key')));
                                      $queries->update('settings', $mcassoc_instance, array('value' => Input::get('mcassoc_instance')));

                                      $success = $language->get('admin', 'updated_mcassoc_successfully');
                                  } else {
                                      $error = $language->get('admin', 'mcassoc_error');
                                  }
                              } else {
                                  $queries->update('settings', $use_mcassoc, array('value' => 0));
                                  $success = $language->get('admin', 'updated_mcassoc_successfully');
                              }
                          } else {
                            $uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
                            $uuid_linking = $uuid_linking[0]->id;

                            if(isset($_POST['enable_premium_accounts']) && $_POST['enable_premium_accounts'] == 1)
                              $use_premium = 1;
                            else
                              $use_premium = 0;

                            $queries->update('settings', $uuid_linking, array('value' => $use_premium));
                          }

                          Log::getInstance()->log(Log::Action('admin/mc/update'), $language->get('log', 'info_mc_general'));
                        }
                      }

                      // Get UUID linking settings
                      $uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
                      $uuid_linking = $uuid_linking[0]->value;

                      // Get mcassoc settings
                      $use_mcassoc = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
                      $use_mcassoc = $use_mcassoc[0]->value;

                      $mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
                      $mcassoc_key = Output::getClean($mcassoc_key[0]->value);

                      $mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
                      $mcassoc_instance = Output::getClean($mcassoc_instance[0]->value);
                      ?>
                      <form id="enablePremium" action="" method="post">
                          <?php echo $language->get('admin', 'force_premium_accounts'); ?>
                        <input type="hidden" name="enable_premium_accounts" value="0">
                        <input name="enable_premium_accounts" type="checkbox"
                               class="js-switch js-check-change"<?php if ($uuid_linking == '1') { ?> checked<?php } ?>
                               value="1"/>
                        <input type="hidden" name="premium" value="1">
                        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                      </form>
                      <?php if($uuid_linking == '1') { ?>
                      <hr/>
                      <div class="alert alert-info">
                          <?php echo $language->get('admin', 'mcassoc_help'); ?>
                      </div>
                        <?php
                        if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>';
                        else if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>';
                        ?>
                      <form action="" method="post">
                        <div class="form-group">
                          <label for="use_mcassoc"><?php echo $language->get('admin', 'verify_with_mcassoc'); ?></label>
                          <input id="use_mcassoc" name="use_mcassoc" type="checkbox" class="js-switch"
                                 <?php if ($use_mcassoc == '1'){ ?>checked <?php } ?>/>
                          </span>
                        </div>
                        <div class="form-group">
                          <label for="mcassoc_key"><?php echo $language->get('admin', 'mcassoc_key'); ?></label>
                          <input type="text" class="form-control" name="mcassoc_key" id="mcassoc_key"
                                 value="<?php echo $mcassoc_key; ?>"
                                 placeholder="<?php echo $language->get('admin', 'mcassoc_key'); ?>">
                        </div>
                        <div class="form-group">
                          <label for="mcassoc_instance"><?php echo $language->get('admin', 'mcassoc_instance'); ?></label>
                          <input type="text" class="form-control" name="mcassoc_instance" id="mcassoc_instance"
                                 value="<?php echo $mcassoc_instance; ?>"
                                 placeholder="<?php echo $language->get('admin', 'mcassoc_instance'); ?>">
                          <p><?php echo $language->get('admin', 'mcassoc_instance_help'); ?></p>
                        </div>
                        <div class="form-group">
                          <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                          <input type="submit" class="btn btn-primary"
                                 value="<?php echo $language->get('general', 'submit'); ?>">
                        </div>
                      </form>
                      <?php
                      }
                      break;

                    case 'authme':
                        if(!$user->hasPermission('admincp.minecraft.authme')){
                            Redirect::to(URL::build('/admin/minecraft'));
                            die();
                        }
                        echo '<h4>' . $language->get('admin', 'authme_integration') . '</h4>';

                        // Handle input
                        if(Input::exists()){
                            if(Token::check(Input::get('token'))){
                                if(isset($_POST['enable_authme'])){
                                    // Either enable or disable Authme integration
                                    $enable_authme_id = $queries->getWhere('settings', array('name', '=', 'authme'));
                                    $enable_authme_id = $enable_authme_id[0]->id;

                                    $queries->update('settings', $enable_authme_id, array(
                                        'value' => Input::get('enable_authme')
                                    ));
                                } else {
                                    // AuthMe config settings
                                    $validate = new Validate();
                                    $validation = $validate->check($_POST, array(
                                        'hashing_algorithm' => array(
                                            'required' => true
                                        ),
                                        'db_address' => array(
                                            'required' => true
                                        ),
                                        'db_name' => array(
                                            'required' => true
                                        ),
                                        'db_username' => array(
                                            'required' => true
                                        ),
                                        'db_table' => array(
                                            'required' => true
                                        )
                                    ));

                                    if($validation->passed()){
                                        $authme_db = $queries->getWhere('settings', array('name', '=', 'authme_db'));
                                        $authme_db_id = $authme_db[0]->id;
                                        $authme_db = json_decode($authme_db[0]->value);

                                        if(isset($_POST['db_password'])){
                                            $password = $_POST['db_password'];
                                        } else {
                                            if(isset($authme_db->password) && !empty($authme_db->password))
                                                $password = $authme_db->password;
                                            else
                                                $password = '';
                                        }

                                        $result = array(
                                            'address' => Output::getClean(Input::get('db_address')),
                                            'port' => (isset($_POST['db_port']) && !empty($_POST['db_port']) && is_numeric($_POST['db_port'])) ? $_POST['db_port'] : 3306,
                                            'db' => Output::getClean(Input::get('db_name')),
                                            'user' => Output::getClean(Input::get('db_username')),
                                            'pass' => $password,
                                            'table' => Output::getClean(Input::get('db_table')),
                                            'hash' => Output::getClean(Input::get('hashing_algorithm')),
                                            'sync' => Input::get('authme_sync')
                                        );

                                        $cache->setCache('authme_cache');
                                        $cache->store('authme', $result);

                                        $queries->update('settings', $authme_db_id, array(
                                            'value' => json_encode($result)
                                        ));

                                        Log::getInstance()->log(Log::Action('admin/authme/update'));

                                    } else {
                                        $error = $language->get('admin', 'enter_authme_db_details');
                                    }
                                }
                            } else {
                                // Invalid token
                                $error = $language->get('general', 'invalid_token');
                            }
                        }

                        $token = Token::get();

                        // Is Authme enabled?
                        $authme_enabled = $queries->getWhere('settings', array('name', '=', 'authme'));
                        $authme_enabled = $authme_enabled[0]->value;

                        if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>';

                        echo '<div class="alert alert-info">' . $language->get('admin', 'authme_integration_info') . '</div>';
                        ?>
                        <form id="enableAuthMe" action="" method="post">
                            <?php echo $language->get('admin', 'enable_authme'); ?>
                            <input type="hidden" name="enable_authme" value="0">
                            <input name="enable_authme" type="checkbox"
                                   class="js-switch js-check-change"<?php if ($authme_enabled == '1') { ?> checked<?php } ?>
                                   value="1"/>
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                        </form>
                        <?php
                        if($authme_enabled == '1'){
                            // Retrieve Authme database details
                            $authme_db = $queries->getWhere('settings', array('name', '=', 'authme_db'));
                            $authme_db = json_decode($authme_db[0]->value);
                            ?>
                        <hr />
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputHashingAlgorithm"><?php echo $language->get('admin', 'authme_hash_algorithm'); ?></label>
                                <select id="inputHashingAlgorithm" class="form-control" name="hashing_algorithm">
                                    <option value="bcrypt"<?php echo ((isset($authme_db->hash) && $authme_db->hash == 'bcrypt') ? ' selected' : ''); ?>>bcrypt</option>
                                    <option value="sha1"<?php echo ((isset($authme_db->hash) && $authme_db->hash == 'sha1') ? ' selected' : ''); ?>>SHA1</option>
                                    <option value="sha256"<?php echo ((isset($authme_db->hash) && $authme_db->hash == 'sha256') ? ' selected' : ''); ?>>SHA256</option>
                                    <option value="pbkdf2"<?php echo ((isset($authme_db->hash) && $authme_db->hash == 'pbkdf2') ? ' selected' : ''); ?>>PBKDF2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputDBAddress"><?php echo $language->get('admin', 'authme_db_address'); ?></label>
                                <input type="text" class="form-control" name="db_address" value="<?php echo ((isset($authme_db->address)) ? Output::getClean($authme_db->address) : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDBPort"><?php echo $language->get('admin', 'authme_db_port'); ?></label>
                                <input type="text" class="form-control" name="db_port" value="<?php echo ((isset($authme_db->port)) ? Output::getClean($authme_db->port) : '3306'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDBName"><?php echo $language->get('admin', 'authme_db_name'); ?></label>
                                <input type="text" class="form-control" name="db_name" value="<?php echo ((isset($authme_db->db)) ? Output::getClean($authme_db->db) : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDBUsername"><?php echo $language->get('admin', 'authme_db_user'); ?></label>
                                <input type="text" class="form-control" name="db_username" value="<?php echo ((isset($authme_db->user)) ? Output::getClean($authme_db->user) : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDBPassword"><?php echo $language->get('admin', 'authme_db_password'); ?></label>
                                <input type="password" class="form-control" name="db_password">
                            </div>
                            <div class="form-group">
                                <label for="inputDBTable"><?php echo $language->get('admin', 'authme_db_table'); ?></label>
                                <input type="text" class="form-control" name="db_table" value="<?php echo ((isset($authme_db->table)) ? Output::getClean($authme_db->table) : 'authme'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputAuthmeSync"><?php echo $language->get('admin', 'authme_password_sync'); ?></label> <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="<?php echo $language->get('general', 'info'); ?>" data-content="<?php echo $language->get('admin', 'authme_password_sync_help'); ?>"></i></span>
                                <input type="hidden" name="authme_sync" value="0">
                                <input id=inputAuthmeSync" name="authme_sync" type="checkbox"
                                       class="js-switch"<?php if (isset($authme_db->sync) && $authme_db->sync == '1') { ?> checked<?php } ?>
                                       value="1"/>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                                <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
                            </div>
                        </form>
                            <?php
                        }
                        break;

                    case 'servers':
                        if(!$user->hasPermission('admincp.minecraft.servers')){
                            Redirect::to(URL::build('/admin/minecraft'));
                            die();
                        }
                      if(isset($_GET['action'])){
                        switch($_GET['action']){
                            case 'new':
                              // Handle input
                              if(Input::exists()){
                                  if(Token::check(Input::get('token'))){
                                      // Validate input
                                      $validate = new Validate();
                                      $validation = $validate->check($_POST, array(
                                         'server_name' => array(
                                             'required' => true,
                                             'min' => 1,
                                             'max' => 20
                                         ),
                                         'server_address' => array(
                                             'required' => true,
                                             'min' => 1,
                                             'max' => 64
                                         ),
                                         'server_port' => array(
                                             'max' => 5
                                         ),
                                         'parent_server' => array(
                                             'required' => true
                                         ),
                                         'query_port' => array(
                                             'max' => 5
                                         )
                                      ));

                                      if($validation->passed()){
                                          // Handle input
                                          try {
                                              // BungeeCord selected?
                                              if(isset($_POST['bungee_instance']) && $_POST['bungee_instance'] == 1)
                                                  $bungee = 1;
                                              else
                                                  $bungee = 0;

                                              // Pre 1.7?
                                              if(isset($_POST['pre_17']) && $_POST['pre_17'] == 1)
                                                  $pre = 1;
                                              else
                                                  $pre = 0;
                                              // Status enabled?
                                              if(isset($_POST['status_query_enabled']) && $_POST['status_query_enabled'] == 1)
                                                  $status = 1;
                                              else
                                                  $status = 0;

                                              // Player list enabled?
                                              if(isset($_POST['query_enabled']) && $_POST['query_enabled'] == 1)
                                                  $query = 1;
                                              else
                                                  $query = 0;

                                              // Parent server
                                              if($_POST['parent_server'] == 'none')
                                                $parent = 0;
                                              else
                                                $parent = $_POST['parent_server'];

                                              // Validate server port
                                              if(is_numeric(Input::get('server_port')))
                                                $port = Input::get('server_port');
                                              else {
                                                if(!isset($_POST['server_port']) || empty($_POST['server_port']))
                                                  $port = null;
                                                else
                                                  $port = 25565;
                                              }

                                              // Validate server query port
                                              if(is_numeric(Input::get('query_port')))
                                                $query_port = Input::get('query_port');
                                              else
                                                $query_port = 25565;

                                              $queries->create('mc_servers', array(
                                                  'ip' => Output::getClean(Input::get('server_address')),
                                                  'query_ip' => Output::getClean(Input::get('server_address')),
                                                  'name' => Output::getClean(Input::get('server_name')),
                                                  'display' => $status,
                                                  'pre' => $pre,
                                                  'player_list' => $query,
                                                  'parent_server' => $parent,
                                                  'bungee' => $bungee,
                                                  'port' => $port,
                                                  'query_port' => $query_port
                                              ));

                                              Log::getInstance()->log(Log::Action('admin/server/add'), Output::getClean(Input::get('server_name')));

                                              Session::flash('admin_mc_servers_success', $language->get('admin', 'server_created'));
                                              Redirect::to(URL::build('/admin/minecraft', 'view=servers'));
                                              die();

                                          } catch(Exception $e){
                                              $errors = array($e->getMessage());
                                          }
                                      } else {
                                          // Validation failed
                                          $errors = array();
                                          foreach($validation->errors() as $item){
                                              if(strpos($item, 'is required') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                        $errors[] = $language->get('admin', 'server_name_required');
                                                        break;
                                                      case (strpos($item, 'server_address') !== false):
                                                        $errors[] = $language->get('admin', 'server_address_required');
                                                        break;
                                                      case (strpos($item, 'server_port') !== false):
                                                        $errors[] = $language->get('admin', 'server_port_required');
                                                        break;
                                                      case (strpos($item, 'parent_server') !== false):
                                                        $errors[] = $language->get('admin', 'server_parent_required');
                                                        break;
                                                  }
                                              } else if(strpos($item, 'minimum') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                        $errors[] = $language->get('admin', 'server_name_minimum');
                                                        break;
                                                      case (strpos($item, 'server_address') !== false):
                                                        $errors[] = $language->get('admin', 'server_address_minimum');
                                                        break;
                                                      case (strpos($item, 'server_port') !== false):
                                                        $errors[] = $language->get('admin', 'server_port_minimum');
                                                        break;
                                                  }
                                              } else if(strpos($item, 'maximum') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                        $errors[] = $language->get('admin', 'server_name_maximum');
                                                        break;
                                                      case (strpos($item, 'server_address') !== false):
                                                        $errors[] = $language->get('admin', 'server_address_maximum');
                                                        break;
                                                      case (strpos($item, 'server_port') !== false):
                                                        $errors[] = $language->get('admin', 'server_port_maximum');
                                                        break;
                                                      case (strpos($item, 'query_port') !== false):
                                                        $errors[] = $language->get('admin', 'query_port_maximum');
                                                        break;
                                                  }
                                              }
                                          }
                                      }

                                  } else
                                      // Invalid token
                                      $error = $language->get('general', 'invalid_token');
                              }

                              echo '<h4 style="display:inline">' . $language->get('admin', 'adding_server') . '</h4>';
                              echo '<span class="pull-right"><a class="btn btn-danger" href="' . URL::build('/admin/minecraft', 'view=servers') . '">' . $language->get('general', 'cancel') . '</a></span><hr />';
                              ?>
                                <form action="" method="post">
                                  <?php
                                  if(isset($errors)){
                                    echo '<div class="alert alert-danger"><ul>';
                                    foreach($errors as $error)
                                     echo '<li>' . $error . '</li>';
                                    echo '</div></ul>';
                                  }
                                  ?>
                                  <h4>Server Information</h4>
                                  <div class="form-group">
                                    <label for="InputName"><?php echo $language->get('admin', 'server_name'); ?></label>
                                    <input name="server_name" placeholder="<?php echo $language->get('admin', 'server_name'); ?>" id="InputName" value="<?php echo Output::getClean(Input::get('server_name')); ?>" class="form-control">
                                  </div>
                                  <div class="form-group">
                                    <label for="InputAddress"><?php echo $language->get('admin', 'server_address'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'server_address_help') . '"></i></span>'; ?>
                                    <input name="server_address" placeholder="<?php echo $language->get('admin', 'server_address'); ?>" id="InputAddress" value="<?php echo Output::getClean(Input::get('server_address')); ?>" class="form-control">
                                  </div>
                                  <div class="form-group">
                                    <label for="inputPort"><?php echo $language->get('admin', 'server_port'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'leave_port_empty_for_srv') . '"></i></span>'; ?>
                                    <input name="server_port" placeholder="<?php echo $language->get('admin', 'server_port'); ?>" id="inputPort" value="25565" class="form-control">
                                  </div>
                                  <div class="form-group">
                                    <label for="InputParentServer"><?php echo $language->get('admin', 'parent_server'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'parent_server_help') . '"></i></span>'; ?>
                                    <select id="InputParentServer" class="form-control" name="parent_server">
                                      <option value="none" selected><?php echo $language->get('admin', 'no_parent_server'); ?></option>
                                        <?php
                                        $available_parent_servers = $queries->getWhere('mc_servers', array('parent_server', '=', 0));
                                        if(count($available_parent_servers))
                                            foreach($available_parent_servers as $server)
                                              echo '<option value="' . $server->id . '">' . Output::getClean($server->name) . '</option>';
                                        ?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputBungeeInstance"><?php echo $language->get('admin', 'bungee_instance'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'bungee_instance_help') . '"></i></span>'; ?>
                                    <input type="hidden" name="bungee_instance" value="0">
                                    <input id=inputBungeeInstance" name="bungee_instance" type="checkbox" class="js-switch" value="1"/>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputPre17"><?php echo $language->get('admin', 'pre_1.7'); ?></label>
                                    <input type="hidden" name="pre_17" value="0">
                                    <input id=inputPre17" name="pre_17" type="checkbox" class="js-switch" value="1"/>
                                  </div>
                                    <?php
                                    // Display query information alert only if external query is selected
                                    $external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
                                    $external_query = $external_query[0]->value;
                                    ?>
                                  <h4>Query Information</h4>
                                  <div class="form-group">
                                    <div class="form-group">
                                      <label for="inputStatusQueryEnabled"><?php echo $language->get('admin', 'enable_status_query'); ?></label><?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" data-html="true" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'status_query_help') . '"></i></span>'; ?>
                                      <input type="hidden" name="status_query_enabled" value="0">
                                      <input id=inputStatusQueryEnabled" name="status_query_enabled" type="checkbox" class="js-switch" value="1"/>
                                    </div>
                                      <?php
                                      if($external_query == '1'){
                                      ?>
                                    <div class="alert alert-info">
                                      <?php echo $language->get('admin', 'server_query_information'); ?>
                                    </div>
                                      <?php
                                      }
                                      ?>
                                    <div class="form-group">
                                      <label for="inputQueryEnabled"><?php echo $language->get('admin', 'enable_player_list'); ?></label><?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" data-html="true" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'player_list_help') . '"></i></span>'; ?>
                                      <input type="hidden" name="query_enabled" value="0">
                                      <input id=inputQueryEnabled" name="query_enabled" type="checkbox" class="js-switch" value="1"/>
                                    </div>
                                    <div class="form-group">
                                      <label for="inputQueryPort"><?php echo $language->get('admin', 'server_query_port'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" data-html="true" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'server_query_port_help') . '"></i></span>'; ?>
                                      <input name="query_port" placeholder="<?php echo $language->get('admin', 'server_query_port'); ?>" id="inputQueryPort" value="25565" class="form-control">
                                    </div>
                                  </div>
                                  <hr />
                                  <div class="form-group">
                                    <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                    <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                                  </div>
                                </form>
                              <?php
                              break;
                            case 'edit':
                              // Get server
                              if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
                                Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                die();
                              }

                              $server_editing = $queries->getWhere('mc_servers', array('id', '=', $_GET['id']));
                              if(!count($server_editing)){
                                Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                die();
                              }
                              $server_editing = $server_editing[0];

                              // Handle input
                              if(Input::exists()){
                                  if(Token::check(Input::get('token'))){
                                      // Validate input
                                      $validate = new Validate();
                                      $validation = $validate->check($_POST, array(
                                          'server_name' => array(
                                              'required' => true,
                                              'min' => 1,
                                              'max' => 20
                                          ),
                                          'server_address' => array(
                                              'required' => true,
                                              'min' => 1,
                                              'max' => 64
                                          ),
                                          'server_port' => array(
                                              'max' => 5
                                          ),
                                          'parent_server' => array(
                                              'required' => true
                                          ),
                                          'query_port' => array(
                                              'max' => 5
                                          )
                                      ));

                                      if($validation->passed()){
                                          // Handle input
                                          try {
                                              // BungeeCord selected?
                                              if(isset($_POST['bungee_instance']) && $_POST['bungee_instance'] == 1)
                                                  $bungee = 1;
                                              else
                                                  $bungee = 0;

                                              // Pre 1.7?
                                              if(isset($_POST['pre_17']) && $_POST['pre_17'] == 1)
                                                  $pre = 1;
                                              else
                                                  $pre = 0;

                                              // Status enabled?
                                              if(isset($_POST['status_query_enabled']) && $_POST['status_query_enabled'] == 1)
                                                  $status = 1;
                                              else
                                                  $status = 0;

                                              // Player list enabled?
                                              if(isset($_POST['query_enabled']) && $_POST['query_enabled'] == 1)
                                                  $query = 1;
                                              else
                                                  $query = 0;

                                              // Parent server
                                              if($_POST['parent_server'] == 'none')
                                                  $parent = 0;
                                              else
                                                  $parent = $_POST['parent_server'];

                                              // Validate server port
                                              if(is_numeric(Input::get('server_port')))
                                                  $port = Input::get('server_port');
                                              else {
                                                  if(!isset($_POST['server_port']) || empty($_POST['server_port']))
                                                      $port = null;
                                                  else
                                                      $port = 25565;
                                              }

                                              // Validate server query port
                                              if(is_numeric(Input::get('query_port')))
                                                  $query_port = Input::get('query_port');
                                              else
                                                  $query_port = 25565;

                                              $queries->update('mc_servers', $server_editing->id, array(
                                                  'ip' => Output::getClean(Input::get('server_address')),
                                                  'query_ip' => Output::getClean(Input::get('server_address')),
                                                  'name' => Output::getClean(Input::get('server_name')),
                                                  'display' => $status,
                                                  'pre' => $pre,
                                                  'player_list' => $query,
                                                  'parent_server' => $parent,
                                                  'bungee' => $bungee,
                                                  'port' => $port,
                                                  'query_port' => $query_port
                                              ));

                                              Log::getInstance()->log(Log::Action('admin/server/update'), Output::getClean(Input::get('server_address')));

                                              Session::flash('admin_mc_servers_success', $language->get('admin', 'server_updated'));
                                              Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                              die();

                                          } catch(Exception $e){
                                              $errors = array($e->getMessage());
                                          }
                                      } else {
                                          // Validation failed
                                          $errors = array();
                                          foreach($validation->errors() as $item){
                                              if(strpos($item, 'is required') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                          $errors[] = $language->get('admin', 'server_name_required');
                                                          break;
                                                      case (strpos($item, 'server_address') !== false):
                                                          $errors[] = $language->get('admin', 'server_address_required');
                                                          break;
                                                      case (strpos($item, 'server_port') !== false):
                                                          $errors[] = $language->get('admin', 'server_port_required');
                                                          break;
                                                      case (strpos($item, 'parent_server') !== false):
                                                          $errors[] = $language->get('admin', 'server_parent_required');
                                                          break;
                                                  }
                                              } else if(strpos($item, 'minimum') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                          $errors[] = $language->get('admin', 'server_name_minimum');
                                                          break;
                                                      case (strpos($item, 'server_address') !== false):
                                                          $errors[] = $language->get('admin', 'server_address_minimum');
                                                          break;
                                                      case (strpos($item, 'server_port') !== false):
                                                          $errors[] = $language->get('admin', 'server_port_minimum');
                                                          break;
                                                  }
                                              } else if(strpos($item, 'maximum') !== false){
                                                  switch($item){
                                                      case (strpos($item, 'server_name') !== false):
                                                          $errors[] = $language->get('admin', 'server_name_maximum');
                                                          break;
                                                      case (strpos($item, 'server_address') !== false):
                                                          $errors[] = $language->get('admin', 'server_address_maximum');
                                                          break;
                                                      case (strpos($item, 'server_port') !== false):
                                                          $errors[] = $language->get('admin', 'server_port_maximum');
                                                          break;
                                                      case (strpos($item, 'query_port') !== false):
                                                          $errors[] = $language->get('admin', 'query_port_maximum');
                                                          break;
                                                  }
                                              }
                                          }
                                      }

                                  } else
                                      // Invalid token
                                      $error = $language->get('general', 'invalid_token');
                              }

                              echo '<h4 style="display:inline">' . $language->get('admin', 'editing_server') . '</h4>';
                              echo '<span class="pull-right"><a class="btn btn-danger" href="' . URL::build('/admin/minecraft/', 'view=servers') . '">' . $language->get('general', 'cancel') . '</a></span><hr />';
                              ?>
                              <form action="" method="post">
                                  <?php
                                  if(isset($errors)){
                                      echo '<div class="alert alert-danger"><ul>';
                                      foreach($errors as $error)
                                          echo '<li>' . $error . '</li>';
                                      echo '</div></ul>';
                                  }
                                  ?>
                                <h4>Server Information</h4>
                                <div class="form-group">
                                  <label for="InputName"><?php echo $language->get('admin', 'server_name'); ?></label>
                                  <input name="server_name" placeholder="<?php echo $language->get('admin', 'server_name'); ?>" id="InputName" value="<?php echo Output::getClean($server_editing->name); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label for="InputAddress"><?php echo $language->get('admin', 'server_address'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'server_address_help') . '"></i></span>'; ?>
                                  <input name="server_address" placeholder="<?php echo $language->get('admin', 'server_address'); ?>" id="InputAddress" value="<?php echo Output::getClean($server_editing->ip); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label for="inputPort"><?php echo $language->get('admin', 'server_port'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'leave_port_empty_for_srv') . '"></i></span>'; ?>
                                  <input name="server_port" placeholder="<?php echo $language->get('admin', 'server_port'); ?>" id="inputPort" value="<?php echo Output::getClean($server_editing->port); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label for="InputParentServer"><?php echo $language->get('admin', 'parent_server'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'parent_server_help') . '"></i></span>'; ?>
                                  <select id="InputParentServer" class="form-control" name="parent_server">
                                    <option value="none" selected><?php echo $language->get('admin', 'no_parent_server'); ?></option>
                                      <?php
                                      $available_parent_servers = $queries->getWhere('mc_servers', array('parent_server', '=', 0));
                                      if(count($available_parent_servers))
                                        foreach($available_parent_servers as $server)
                                          if($server->id != $server_editing->id)
                                            echo '<option value="' . $server->id . '"' . (($server_editing->parent_server == $server->id) ? ' selected' : '') . '>' . Output::getClean($server->name) . '</option>';
                                      ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="inputBungeeInstance"><?php echo $language->get('admin', 'bungee_instance'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'bungee_instance_help') . '"></i></span>'; ?>
                                  <input type="hidden" name="bungee_instance" value="0">
                                  <input id=inputBungeeInstance" name="bungee_instance" type="checkbox" class="js-switch" value="1"<?php if($server_editing->bungee == 1) echo ' checked'; ?>/>
                                </div>
                                <div class="form-group">
                                  <label for="inputPre17"><?php echo $language->get('admin', 'pre_1.7'); ?></label>
                                  <input type="hidden" name="pre_17" value="0">
                                  <input id=inputPre17" name="pre_17" type="checkbox" class="js-switch" value="1"<?php if($server_editing->pre == 1) echo ' checked'; ?>/>
                                </div>
                                  <?php
                                  // Display query information alert only if external query is selected
                                  $external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
                                  $external_query = $external_query[0]->value;
                                  ?>
                                <h4>Query Information</h4>
                                <div class="form-group">
                                  <div class="form-group">
                                    <label for="inputStatusQueryEnabled"><?php echo $language->get('admin', 'enable_status_query'); ?></label><?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'status_query_help') . '"></i></span>'; ?>
                                    <input type="hidden" name="status_query_enabled" value="0">
                                    <input id=inputStatusQueryEnabled" name="status_query_enabled" type="checkbox" class="js-switch" value="1"<?php if($server_editing->display == 1) echo ' checked'; ?>/>
                                  </div>
                                    <?php
                                    if($external_query == '1'){
                                        ?>
                                      <div class="alert alert-info">
                                          <?php echo $language->get('admin', 'server_query_information'); ?>
                                      </div>
                                        <?php
                                    }
                                    ?>
                                  <div class="form-group">
                                    <label for="inputQueryEnabled"><?php echo $language->get('admin', 'enable_player_list'); ?></label><?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'player_list_help') . '"></i></span>'; ?>
                                    <input type="hidden" name="query_enabled" value="0">
                                    <input id=inputQueryEnabled" name="query_enabled" type="checkbox" class="js-switch" value="1"<?php if($server_editing->player_list == 1) echo ' checked'; ?>/>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputQueryPort"><?php echo $language->get('admin', 'server_query_port'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'server_query_port_help') . '"></i></span>'; ?>
                                    <input name="query_port" placeholder="<?php echo $language->get('admin', 'server_query_port'); ?>" id="inputQueryPort" value="<?php echo Output::getClean($server_editing->query_port); ?>" class="form-control">
                                  </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                  <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                                </div>
                              </form>
                              <?php
                              break;

                            case 'delete':
                              // Get server
                              if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
                                  Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                  die();
                              }

                              try {
                                $queries->delete('mc_servers', array('id', '=', $_GET['id']));
                                $queries->delete('query_results', array('server_id', '=', $_GET['id']));
                               //TODO: Get Server name
                               Log::getInstance()->log(Log::Action('admin/server/delete'), Output::getClean($_GET['id']));
                                Session::flash('admin_mc_servers_success', $language->get('admin', 'server_deleted'));
                                Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                die();
                              } catch(Exception $e){
                                Session::flash('admin_mc_servers_error', '<p>' . $language->get('admin', 'unable_to_delete_server') . '</p><p>' . $e->getMessage() . '</p>');
                                  Redirect::to(URL::build('/admin/minecraft/', 'view=servers'));
                                die();
                              }
                              break;

                            case 'graph':
                              echo '<h4 style="display:inline">' . $language->get('admin', 'player_graphs') . '</h4>';
                              echo '<span class="pull-right"><a class="btn btn-primary" href="' . URL::build('/admin/minecraft/', 'view=servers') . '">' . $language->get('general', 'back') . '</a></span><hr />';

                              // Get data - check cache first
                              $cache->setCache('player_count_cache');
                              if($cache->isCached('data')){
                                  $graph_data = $cache->retrieve('data');

                              } else {
                                  $data = $queries->getWhere('query_results', array('id', '<>', 0));
                                  $graph_data = array();
                                  if(count($data)){
                                      // Convert data into graph format

                                      // Get servers
                                      $server_query = $queries->getWhere('mc_servers', array('id', '<>', 0));
                                      if(count($server_query)){
                                          $servers = array();

                                          foreach($server_query as $server)
                                              $servers[$server->id] = $server->name;

                                          foreach($data as $item){
                                              if(isset($graph_data[$item->server_id])){
                                                  $graph_data[$item->server_id]['data'][date('Y-m-d H:i', $item->queried_at)] = $item->players_online;
                                              } else {
                                                  $graph_data[$item->server_id]['name'] = $servers[$item->server_id];
                                                  $graph_data[$item->server_id]['data'][date('Y-m-d H:i', $item->queried_at)] = $item->players_online;
                                              }
                                          }
                                      }
                                  }

                                  $cache->store('data', $graph_data, 300);
                              }

                              if(isset($graph_data))
                                  echo '<div id="playerChart"></div>';

                              $cache->setCache('server_query_cache');
                              if($cache->isCached('query_interval')){
                                $query_interval = $cache->retrieve('query_interval');
                                if(is_numeric($query_interval) && $query_interval <= 60 && $query_interval >= 5){
                                    // Interval ok
                                } else {
                                    // Default to 10
                                    $query_interval = 10;

                                    $cache->store('query_interval', $query_interval);
                                }
                              } else {
                                // Default to 10
                                $query_interval = 10;

                                $cache->store('query_interval', $query_interval);
                              }

                              // Get unique key for cron
                              $key = $queries->getWhere('settings', array('name', '=', 'unique_id'));
                              $key = $key[0]->value;

                              echo '<div class="alert alert-info">' . str_replace('{x}', $query_interval,$language->get('admin', 'player_count_cronjob_info')) . '<br /><code>*/' . $query_interval . ' * * * * wget -O /dev/null "' . Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/queries/servers/', 'key=' . Output::getClean($key)) . '"</code></div>';

                              break;

                            default:
                              Redirect::to(URL::build('/admin/minecraft'));
                              die();
                              break;
                        }
                      } else {
                          if(Input::exists()){
                            if(Token::check(Input::get('token'))){
                              if(isset($_POST['default_server']) && is_numeric($_POST['default_server']))
                                $new_default = $_POST['default_server'];
                              else
                                $new_default = 0;


                              if(isset($_POST['external_query']) && $_POST['external_query'] == 1)
                                $external = 1;
                              else
                                $external = 0;

	                            if(isset($_POST['status_page']) && $_POST['status_page'] == 1)
		                            $status = 1;
	                            else
		                            $status = 0;

                              // Update database and cache
                              try {
                                  // Default server
                                  if($new_default > 0) {
                                      $current_default = $queries->getWhere('mc_servers', array('is_default', '=', 1));
                                      if(count($current_default) && $current_default[0]->id != $new_default)
                                        $queries->update('mc_servers', $current_default[0]->id, array(
                                            'is_default' => 0
                                        ));

                                      if(!count($current_default) || count($current_default) && $current_default[0]->id != $new_default)
                                        $queries->update('mc_servers', $new_default, array(
                                            'is_default' => 1
                                        ));
                                  }

                                  // External query
                                  $external_query_id = $queries->getWhere('settings', array('name', '=', 'external_query'));
                                  $external_query_id = $external_query_id[0];

                                  $queries->update('settings', $external_query_id->id, array(
                                      'value' => $external
                                  ));

                                  $cache->setCache('query_cache');

                                  $cache->store('query', array(
                                      'default' => $new_default,
                                      'external' => $external
                                  ));

                                  // Status page
								  $status_page_id = $queries->getWhere('settings', array('name', '=', 'status_page'));
								  $status_page_id = $status_page_id[0]->id;

								  $queries->update('settings', $status_page_id, array(
								      'value' => $status
								  ));

								  $cache->setCache('status_page');
								  $cache->store('enabled', $status);

                                  // Query interval
                                  if(isset($_POST['interval']) && is_numeric($_POST['interval']) && $_POST['interval'] <= 60 && $_POST['interval'] >= 5){
                                      $cache->setCache('server_query_cache');
                                      $cache->store('query_interval', $_POST['interval']);
                                  }
                                  //TODO: Get Server name
                                  Log::getInstance()->log(Log::Action('admin/server/default'));

                              } catch(Exception $e){
                                  // Error
                                  $error = $e->getMessage();
                              }

                            } else
                              $error = $language->get('general', 'invalid_token');
                          }
                          echo '<h4 style="display:inline">' . $language->get('admin', 'minecraft_servers') . '</h4>';
                          echo '<span class="pull-right">';
                          echo '<a class="btn btn-info" href="' . URL::build('/admin/minecraft/', 'view=servers&amp;action=graph') . '">' . $language->get('admin', 'player_graphs') . '</a> ';
                          echo '<a class="btn btn-primary" href="' . URL::build('/admin/minecraft', 'view=servers&amp;action=new') . '">' . $language->get('admin', 'add_server') . '</a>';
                          echo '</span><br /><hr />';

                          if(Session::exists('admin_mc_servers_success'))
                            echo '<div class="alert alert-success">' . Session::flash('admin_mc_servers_success') . '</div>';

                          if(Session::exists('admin_mc_servers_error'))
                            echo '<div class="alert alert-danger">' . Sesion::flash('admin_mc_servers_error') . '</div>';

                          $servers = $queries->getWhere('mc_servers', array('id', '<>', 0));

                          if(count($servers)){
                              // Servers exist
                              $counter = 1;

                              foreach($servers as $server){
                                if($server->is_default == 1)
                                  $default = $server->id;
                                ?>
                                <strong><?php echo Output::getClean($server->name); ?></strong> (ID: <strong><?php echo Output::getClean($server->id); ?></strong>)
                                <span class="pull-right">
                                  <a class="btn btn-warning btn-sm" href="<?php echo URL::build('/admin/minecraft/', 'view=servers&amp;action=edit&amp;id=' . $server->id); ?>"><i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i></a>
                                  <a class="btn btn-danger btn-sm" href="<?php echo URL::build('/admin/minecraft/', 'view=servers&amp;action=delete&amp;id=' . $server->id); ?>" onclick="return confirm('<?php echo $language->get('admin', 'confirm_delete_server'); ?>')"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                                </span>
                                <?php
                                if($counter < count($servers))
                                  echo '<hr />';

                                $counter++;
                              }
                          } else {
                              // No servers exist
                              echo '<div class="alert alert-warning">' . $language->get('admin', 'no_servers_defined') . '</div>';
                          }

                          // Query options
                          $external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
                          $external_query = $external_query[0]->value;

                          $status_page = $queries->getWhere('settings', array('name', '=', 'status_page'));
                          $status_page = $status_page[0]->value;

                          // Query interval
                          $cache->setCache('server_query_cache');
                          if($cache->isCached('query_interval')){
                              $query_interval = $cache->retrieve('query_interval');
                              if(is_numeric($query_interval) && $query_interval <= 60 && $query_interval >= 5){
                                  // Interval ok
                              } else {
                                  // Default to 10
                                  $query_interval = 10;

                                  $cache->store('query_interval', $query_interval);
                              }
                          } else {
                              // Default to 10
                              $query_interval = 10;

                              $cache->store('query_interval', $query_interval);
                          }

                          echo '<hr /><h4>' . $language->get('admin', 'query_settings') . '</h4>';
                          ?>
                          <form action="" method="post">
                            <?php if(isset($error)) { ?>
                              <div class="alert alert-danger">
                                <?php echo $error; ?>
                              </div>
                            <?php } ?>
                            <div class="form-group">
                              <label for="inputDefaultServer"><?php echo $language->get('admin', 'default_server'); ?></label>
                              <select id="inputDefaultServer" class="form-control" name="default_server">
                                <option value="none"<?php if(!isset($default)) echo ' selected'; ?>><?php echo $language->get('admin', 'no_default_server'); ?></option>
                                <?php
                                if(count($servers)){
                                  foreach($servers as $server){
                                    echo '<option value="' . $server->id . '"' . (($server->is_default == 1) ? ' selected' : '') . '>' . Output::getClean($server->name) . '</option>';
                                  }
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="inputQueryInterval"><?php echo $language->get('admin', 'query_interval'); ?></label>
                              <input id="inputQueryInterval" name="interval" type="number" class="form-control" value="<?php echo $query_interval; ?>" min="5" max="60"/>
                            </div>
                            <div class="form-group">
                              <label for="inputExternalQuery"><?php echo $language->get('admin', 'external_query'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'external_query_help') . '"></i></span>'; ?>
                              <input type="hidden" name="external_query" value="0">
                              <input id="inputExternalQuery" name="external_query" type="checkbox" class="js-switch" value="1" <?php if($external_query == '1') echo 'checked'; ?>/>
                            </div>
							  <div class="form-group">
								  <label for="inputStatusPage"><?php echo $language->get('admin', 'status_page'); ?></label>
								  <input type="hidden" name="status_page" value="0">
								  <input id="inputStatusPage" name="status_page" type="checkbox" class="js-switch" value="1" <?php if($status_page == '1') echo 'checked'; ?>/>
							  </div>
                            <div class="form-group">
                              <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                              <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                            </div>
                          </form>
                          <?php
                      }

                      break;

                    case 'banners':
                      if(!$user->hasPermission('admincp.minecraft.banners') || !function_exists('exif_imagetype')){
                          Redirect::to(URL::build('/admin/minecraft'));
                          die();
                      }
                      echo '<h4 style="display:inline;">' . $language->get('admin', 'server_banners') . '</h4>';
                      if(isset($_GET['server'])) {
                        echo '<span class="pull-right"><a href="' . URL::build('/admin/minecraft/', 'view=banners') . '" class="btn btn-info">' . $language->get('general', 'back') . '</a></span>';
                        // Get server
                        $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['server']));
                        if(!count($server)){
                          Redirect::to(URL::build('/admin/minecraft/', 'view=banners'));
                          die();
                        }
                        $server = $server[0];
                        echo '<hr />';
                        echo '<p><code>http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . rtrim(URL::build('/banner/' . urlencode($server->name) . '.png'), '/') . '</code></p>';
                        echo '<img src="' . rtrim(URL::build('/banner/' . urlencode($server->name)), '/') . '" alt="' . Output::getClean($server->name) . '" />';

                      } else if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
                          echo '<span class="pull-right"><a href="' . URL::build('/admin/minecraft/', 'view=banners') . '" class="btn btn-info">' . $language->get('general', 'back') . '</a></span>';
                          // Get server
                          $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['edit']));
                          if(!count($server)){
                              Redirect::to(URL::build('/admin/minecraft/', 'view=banners'));
                              die();
                          }

                          if(Input::exists()){
                              // Check token
                              if(Token::check(Input::get('token'))){
                                  // Valid token
                                  try {
                                      if(file_exists(ROOT_PATH . '/uploads/banners/' . Input::get('banner'))){
                                          $queries->update('mc_servers', $_GET['edit'], array(
                                            'banner_background' => Output::getClean(Input::get('banner'))
                                          ));
                                  //TODO: get Server name
                                          Log::getInstance()->log(Log::Action('admin/server/banner'));
                                      }
                                  } catch(Exception $e){
                                      $error = $e->getMessage();
                                  }


                              } else {
                                  // Invalid token
                                  $error = $language->get('general', 'invalid_token');
                              }

                              // Re-query
                              $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['edit']));
                          }

                          $server = $server[0];
                          echo '<hr />';
                          if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>';
                          ?>
                          <form action="" method="post">
                              <label for="inputBanner"><?php echo $language->get('admin', 'banner_background'); ?></label>
                              <select name="banner" id="inputBanner" class="image-picker show-html">
                                  <?php
                                  $image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'banners'));
                                  $images = scandir($image_path);

                                  // Only display jpeg, png, jpg, gif
                                  $allowed_exts = array('gif', 'png', 'jpg', 'jpeg');
                                  $n = 1;

                                  foreach($images as $image){
                                      $ext = pathinfo($image, PATHINFO_EXTENSION);
                                      if(!in_array($ext, $allowed_exts)){
                                          continue;
                                      }
                                      ?>
                                      <option data-img-src="<?php echo ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/'); ?>uploads/banners/<?php echo $image; ?>" value="<?php echo Output::getClean($image); ?>" <?php if($server->banner_background == $image) echo 'selected'; ?>><?php echo $n; ?></option>
                                      <?php
                                      $n++;
                                  }
                                  ?>
                              </select>
                              <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                              <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                          </form>
                          <?php
                      } else {
                        $servers = $queries->getWhere('mc_servers', array('id', '<>', 0));
                        if(count($servers)){
                          echo '<br /><br />';
                          $counter = 1;

                          foreach($servers as $server){
                              ?>
                            <strong><?php echo Output::getClean($server->name); ?></strong>
                            <span class="pull-right">
                                <a class="btn btn-warning btn-sm" href="<?php echo URL::build('/admin/minecraft/', 'view=banners&amp;edit=' . $server->id); ?>"><i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i></a>
                                <a class="btn btn-info btn-sm" href="<?php echo URL::build('/admin/minecraft/', 'view=banners&amp;server=' . $server->id); ?>"><i class="fa fa-search fa-fw" aria-hidden="true"></i></a>
                              </span>
                              <?php
                              if($counter < count($servers))
                                  echo '<hr />';

                              $counter++;
                          }
                        } else {
                          echo '<br /><br /><div class="alert alert-info">' . $language->get('admin', 'no_servers_defined') . '</div>';
                        }
                      }
                      break;

                    case 'query_errors':
                      if(!$user->hasPermission('admincp.minecraft.query_errors')){
                          Redirect::to(URL::build('/admin/minecraft'));
                          die();
                      }
                      echo '<h4 style="display:inline;">' . $language->get('admin', 'query_errors') . '</h4>';
                      if(!isset($_GET['id']) && !isset($_GET['action']))
                        echo '<span class="pull-right"><a href="' . URL::build('/admin/minecraft', 'view=query_errors&amp;action=purge') . '" class="btn btn-warning" onclick="return confirm(\'' . $language->get('admin', 'confirm_purge_errors') . '\');">' . $language->get('admin', 'purge_errors') . '</a></span><br /><br />';
                      else
                        echo '<span class="pull-right"><a href="' . (!isset($_GET['id']) ? URL::build('/admin/minecraft/') : URL::build('/admin/minecraft/', 'view=query_errors')) . '" class="btn btn-warning">' . $language->get('general', 'back') . '</a></span><br /><br />';

                      if(!isset($_GET['id'])){
                          if(isset($_GET['action']) && $_GET['action'] == 'purge'){
                            $queries->delete('query_errors', array('id', '<>', 0));
                            Redirect::to(URL::build('/admin/minecraft/', 'view=query_errors'));
                            die();
                          }
                          $query_errors = $queries->orderWhere('query_errors', 'id <> 0', 'DATE', 'DESC');
                          if(count($query_errors)){
                              // Get page
                              if(isset($_GET['p'])){
                                  if(!is_numeric($_GET['p'])){
                                      Redirect::to(URL::build('/admin/minecraft/', 'view=query_errors'));
                                      die();
                                  } else
                                      $p = $_GET['p'];

                              } else {
                                  $p = 1;
                              }

                              // Pagination
                              $paginator = new Paginator();
                              $results = $paginator->getLimited($query_errors, 10, $p, count($query_errors));
                              $pagination = $paginator->generate(7, URL::build('/admin/minecraft/', 'view=query_errors&'));
                              ?>
                            <div class="table-responsive">
                              <table class="table table-striped">
                                <thead>
                                <tr>
                                  <th><?php echo str_replace(':', '', $language->get('admin', 'server_address')); ?></th>
                                  <th><?php echo str_replace(':', '', $language->get('admin', 'server_port')); ?></th>
                                  <th><?php echo str_replace(':', '', $language->get('general', 'date')); ?></th>
                                  <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                for($n = 0; $n < count($results->data); $n++){
                                    ?>
                                  <tr>
                                    <td><?php echo Output::getClean($results->data[$n]->ip); ?></td>
                                    <td><?php echo Output::getClean($results->data[$n]->port); ?></td>
                                    <td><?php echo date('d M Y, H:i', $results->data[$n]->date); ?></td>
                                    <td>
                                      <a href="<?php echo URL::build('/admin/minecraft/', 'view=query_errors&amp;id=' . $results->data[$n]->id); ?>"
                                         class="btn btn-info btn-sm"><i class="fa fa-search fa-fw"></i></a> <a
                                              href="<?php echo URL::build('/admin/minecraft/', 'view=query_errors&amp;action=delete&amp;id=' . $results->data[$n]->id); ?>"
                                              class="btn btn-warning btn-sm"
                                              onclick="return confirm('<?php echo $language->get('admin', 'confirm_query_error_deletion'); ?>')"><i
                                                class="fa fa-trash fa-fw"></i></a></td>
                                  </tr>
                                <?php } ?>
                                </tbody>
                              </table>
                            </div>
                              <?php
                              echo $pagination;
                          } else
                            echo '<div class="alert alert-info">' . $language->get('admin', 'no_query_errors') . '</div>';
                      } else if(!isset($_GET['action'])){
                        if(!is_numeric($_GET['id'])){
                          Redirect::to(URL::build('/admin/minecraft/', 'view=query_errors'));
                          die();
                        }

                        $query_error = $queries->getWhere('query_errors', array('id', '=', $_GET['id']));
                        if(!count($query_error)){
                            Redirect::to(URL::build('/admin/minecraft/', 'view=query_errors'));
                            die();
                        }
                        $query_error = $query_error[0];
                        
                        echo '<strong>' . $language->get('admin', 'viewing_query_error') . '</strong><hr />';
                        echo $language->get('admin', 'server_address') . ': ' . Output::getClean($query_error->ip) . '<br />';
                        echo $language->get('admin', 'server_port') . ': ' . Output::getClean($query_error->port) . '<br />';
                        echo $language->get('general', 'date') . ': ' . date('d M Y, G:i', $query_error->date) . '<br /><br />';
                        echo '<div class="panel panel-danger"><div class="panel-body"><p>' . Output::getClean($query_error->error) . '</p></div></div>';
                      } else {
                        if($_GET['action'] == 'delete'){
                          $queries->delete('query_errors', array('id', '=', $_GET['id']));
                          Redirect::to(URL::build('/admin/minecraft/', 'view=query_errors'));
                          die();
                        }
                      }
                      break;

                    default:
                      // Invalid
                      Redirect::to(URL::build('/admin/minecraft'));
                      break;
                }
              }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>

    </div>
	
	<?php 
	require(ROOT_PATH . '/modules/Core/pages/admin/footer.php');
	require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php');
	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.min.js"></script>
	
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html);
	});
	
	/*
	 *  Submit form on clicking enable/disable Minecraft/AuthMe
	 */
	if($('.js-check-change').length) {
        var changeCheckbox = document.querySelector('.js-check-change');

        changeCheckbox.onchange = function () {
            if ($("#enableAuthMe").length > 0)
                $('#enableAuthMe').submit();
            else if ($("#enablePremium").length > 0)
                $('#enablePremium').submit();
            else
                $('#enableMinecraft').submit();
        };
    }
	<?php if(isset($_GET['view']) && isset($_GET['action']) && $_GET['view'] == 'servers' && $_GET['action'] == 'graph' && isset($graph_data)){ ?>
	</script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/plotly/plotly.min.js"></script>
	<script>
	$(function () {
        var graphData = [
            <?php
            foreach($graph_data as $data){
            ?>
            {
                x: ['<?php echo rtrim(implode('\',\'', array_keys($data['data'])), ','); ?>'],
                y: [<?php echo rtrim(implode(',', $data['data']), ','); ?>],
                mode: 'lines+markers',
                name: '<?php echo Output::getClean($data['name']); ?>'
            },
            <?php
            }
            ?>
        ];

        var selectorOptions = {
            buttons: [{
                step: 'hour',
                stepmode: 'backward',
                count: 6,
                label: '6h'
            }, {
                step: 'hour',
                stepmode: 'backward',
                count: 12,
                label: '12h'
            }, {
                step: 'day',
                stepmode: 'backward',
                count: 1,
                label: '1d'
            }, {
                step: 'day',
                stepmode: 'backward',
                count: 15,
                label: '15d'
            }, {
                step: 'month',
                stepmode: 'backward',
                count: 1,
                label: '1mo'
            }, {
                step: 'month',
                stepmode: 'backward',
                count: 6,
                label: '6mo'
            }, {
                step: 'year',
                stepmode: 'todate',
                count: 1,
                label: 'YTD'
            }, {
                step: 'year',
                stepmode: 'backward',
                count: 1,
                label: '1y'
            }, {
                step: 'all',
            }],
        };

        var layout = {
            title: 'Players',
            xaxis: {
                rangeselector: selectorOptions,
                rangeslider: {}<?php if($user->data()->night_mode == 1){ ?>,
                tickfont: {
                    "color": '#fff'
                }<?php } ?>
            },
            yaxis: {
                fixedrange: true<?php if($user->data()->night_mode == 1){ ?>,
                tickfont: {
                    "color": '#fff'
                }<?php } ?>
            },
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)'<?php if($user->data()->night_mode == 1){ ?>,
            titlefont: {
                "color": '#fff'
            },
            legend: {
                font: {
                    color: '#fff'
                }
            }<?php } ?>
        };

        Plotly.newPlot('playerChart', graphData, layout, {displayModeBar: false});
	});

    /*
     *  Generate random colours for graph lines
     *  Credit https://stackoverflow.com/a/25709983
     */
    function getRandomColour() {
        var letters = '0123456789ABCDEF'.split('');
        var colour = '#';
        for (var i = 0; i < 6; i++ ) {
            colour += letters[Math.floor(Math.random() * 16)];
        }
        return colour;
    }
	<?php } else if(isset($_GET['view']) && $_GET['view'] == 'account_verification'){ ?>
	function generateInstance() {
      var text = "";
      var possible = "abcdef0123456789";
      // thanks SO 1349426
      for(var i = 0; i < 32; i++)
          text += (possible.charAt(Math.floor(Math.random() * possible.length)));

      document.getElementById("mcassoc_instance").setAttribute("value", text);
	}
	<?php } else if(isset($_GET['view']) && $_GET['view'] == 'banners' && isset($_GET['edit'])){ ?>
    $(".image-picker").imagepicker();
	<?php } ?>
	</script>

  </body>
</html>
