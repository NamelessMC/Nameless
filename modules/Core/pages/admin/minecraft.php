<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
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
                  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                </form>

                  <?php
                  if ($minecraft_enabled == '1') {
                  ?>
                    <hr />
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=authme'); ?>"><?php echo $language->get('admin', 'authme_integration'); ?></a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=account_verification'); ?>"><?php echo $language->get('admin', 'account_verification'); ?></a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=servers'); ?>"><?php echo $language->get('admin', 'minecraft_servers'); ?></a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=query_errors'); ?>"><?php echo $language->get('admin', 'query_errors'); ?></a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <a href="<?php echo URL::build('/admin/minecraft/', 'view=banners'); ?>"><?php echo $language->get('admin', 'server_banners'); ?></a>
                          </td>
                        </tr>
                      </table>
                    </div>
                  <?php
                  }
              } else {
                switch($_GET['view']){
                    case 'account_verification':
                      echo '<h4>' . $language->get('admin', 'account_verification') . '</h4>';
                      break;

                    case 'authme':
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

                                    } else {
                                        $error = $language->get('admin', 'enter_authme_db_details');
                                    }
                                }
                            } else {
                                // Invalid token
                                $error = $language->get('general', 'invalid_token');
                            }
                        }

                        $token = Token::generate();

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
                      if(isset($_GET['action'])){
                        switch($_GET['action']){
                            case 'new':
                              // Handle input
                              if(Input::exists()){
                                  if(Token::check(Input::get('token'))){
                                      // Validate input
                                      $validate = new Validate();


                                  } else
                                      // Invalid token
                                      $error = $language->get('general', 'invalid_token');
                              }

                              echo '<h4 style="display:inline">' . $language->get('admin', 'adding_server') . '</h4>';
                              echo '<span class="pull-right"><a class="btn btn-danger" href="' . URL::build('/admin/minecraft', 'view=servers') . '">' . $language->get('general', 'cancel') . '</a></span><hr />';
                              ?>
                                <form action="" method="post">
                                  <div class="form-group">
                                    <label for="InputName"><?php echo $language->get('admin', 'server_name'); ?></label>
                                    <input name="server_name" placeholder="<?php echo $language->get('admin', 'server_name'); ?>" id="InputName" value="<?php echo Output::getClean(Input::get('server_name')); ?>" class="form-control">
                                  </div>
                                  <div class="form-group">
                                    <label for="InputAddress"><?php echo $language->get('admin', 'server_address'); ?></label> <?php echo ' <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('general', 'info') . '" data-content="' . $language->get('admin', 'server_address_help') . '"></i></span>'; ?>
                                    <input name="server_address" placeholder="<?php echo $language->get('admin', 'server_address'); ?>" id="InputAddress" value="<?php echo Output::getClean(Input::get('server_address')); ?>" class="form-control">
                                  </div>
                                </form>
                              <?php
                              break;
                        }
                      } else {
                          echo '<h4 style="display:inline">' . $language->get('admin', 'minecraft_servers') . '</h4>';
                          echo '<span class="pull-right"><a class="btn btn-primary" href="' . URL::build('/admin/minecraft', 'view=servers&amp;action=new') . '">' . $language->get('admin', 'add_server') . '</a></span><br />';

                          $servers = $queries->getWhere('mc_servers', array('id', '<>', 0));

                          if(count($servers)){
                              // Servers exist
                              foreach($servers as $server){

                              }
                          } else {
                              // No servers exist

                          }
                      }

                      break;

                    case 'banners':
                      echo '<h4>' . $language->get('admin', 'server_banners') . '</h4>';
                      break;

                    case 'query_errors':
                      echo '<h4>' . $language->get('admin', 'query_errors') . '</h4>';
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
	require('modules/Core/pages/admin/footer.php');
	require('modules/Core/pages/admin/scripts.php'); 
	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
	
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html);
	});
	
	/*
	 *  Submit form on clicking enable/disable Minecraft/AuthMe
	 */
	var changeCheckbox = document.querySelector('.js-check-change');

	changeCheckbox.onchange = function() {
	  if($("#enableMinecraft").length == 0)
          $('#enableAuthMe').submit();
	  else
	      $('#enableMinecraft').submit();
	};
	
	</script>
	
  </body>
</html>