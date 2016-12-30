<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  MCAssoc integration
 */

if(!defined('MCASSOC') || !(isset($_POST['username']) || isset($_SESSION['mcassoc'])) || !isset($mcassoc_site_id)) die();

// Assign post data to session variable
if(!isset($_SESSION['mcassoc'])) $_SESSION['mcassoc'] = $_POST;
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Verify Account">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
	<meta name="robots" content="noindex">
	
    <title><?php echo $language->get('user', 'verify_account'); ?> &bull; <?php echo SITE_NAME; ?></title>
	
	<?php 
	$page = 'admin'; // to load default CSS
	require('core/templates/header.php'); 
	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/client.js"></script>
	
  </head>
  
  <body>
    <?php require('core/templates/navbar.php'); ?>
    
	<div class="container">
	  <h2><?php echo $language->get('user', 'verify_account'); ?></h2>
	  <div class="alert alert-info"><?php echo $language->get('user', 'verify_account_help'); ?></div>
	  <?php
	  // MCAssoc steps
	  if(!isset($_GET['step'])){ 
		// Step 1 - MCAssoc
	    if($custom_usernames == 'true'){
			if(isset($_SESSION['mcassoc']['mcname'])){
				$username = $_SESSION['mcassoc']['mcname'];
			}
		} else {
			if(isset($_SESSION['mcassoc']['username'])){
				$username = $_SESSION['mcassoc']['username'];
			}
		}
		
		$return_link = "http://$_SERVER[HTTP_HOST]". strtok($_SERVER['REQUEST_URI'], '?') . '/?' . http_build_query(array(
			'step' => '2'
		));
		$key = $mcassoc->generateKey($username);
	  ?>
	  <center>
	    <iframe id="mcassoc" width="100%" height="400" frameBorder="0" seamless scrolling="no"></iframe>
	  </center>
	  <script>
	  MCAssoc.init("<?php echo $mcassoc_site_id; ?>", "<?php echo $key; ?>", "<?php echo $return_link; ?>");
	  </script>
	  <?php
	  } else {
		  if($_GET['step'] == 2){
			  // Final step - verify data matches form
			  if($custom_usernames == 'true'){
				if(isset($_SESSION['mcassoc']['mcname'])){
					$username = $_SESSION['mcassoc']['mcname'];
				}
			  } else {
				if(isset($_SESSION['mcassoc']['username'])){
					$username = $_SESSION['mcassoc']['username'];
				}
			  }
			  
			  if(!isset($username)) die();
			  
			  try {
				    $data = $mcassoc->unwrapData($_POST['data']);
				  
					if(!$data || $username != $data->username){
						// Does not match MCAssoc
						echo '<div class="alert alert-danger">' . $language->get('user', 'verification_failed') . '<br /><a href="' . URL::build('/register') . '" class="btn btn-primary">' . $language->get('general', 'register') . '</a></div>';
						unset($_SESSION['mcassoc']);
					} else {
						// Matches
						// Register the account
						// Password (already hashed)
						$password = $_SESSION['password'];
					  
						// Get current unix time
						$date = new DateTime();
						$date = $date->getTimestamp();
					  
						// Get IP
						$ip = $user->getIP();
					  
						$user->create(array(
							'username' => htmlspecialchars($username),
							'nickname' => htmlspecialchars($_SESSION['mcassoc']['username']),
							'uuid' => htmlspecialchars($data->uuid),
							'password' => $password,
							'pass_method' => 'default',
							'joined' => $date,
							'group_id' => 1,
							'email' => htmlspecialchars($_SESSION['mcassoc']['email']),
							'active' => 1,
							'lastip' => htmlspecialchars($ip),
							'last_online' => $date
						));
					  
						unset($_SESSION['mcassoc']);
					  
						echo '<div class="alert alert-success">' . $language->get('user', 'verification_success') . '<br /><a href="' . URL::build('/login') . '" class="btn btn-primary">' . $language->get('general', 'sign_in') . '</a></div>';
					}
			  } catch (Exception $e) {
				  echo '<div class="alert alert-danger">' . $language->get('user', 'verification_failed') . '<br /><a href="' . URL::build('/register') . '" class="btn btn-primary">' . $language->get('general', 'register') . '</a></div>';
				  unset($_SESSION['mcassoc']);
			  }
	      }
	  }
	  ?>
	</div>
  
    <?php
	// Footer
	require('core/templates/footer.php');
	?>
  
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/jquery.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/tether.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/bootstrap.min.js"></script>
	
  </body>
</html>