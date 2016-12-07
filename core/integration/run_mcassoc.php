<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 1.0.12
 *
 *  License: MIT
 *
 *  MCAssoc
 */

if(!defined('MCASSOC') || !(isset($_POST['username']) || isset($_SESSION['mcassoc'])) || !isset($mcassoc_site_id)) die();

// Assign post data to session variable
if(!isset($_SESSION['mcassoc'])) $_SESSION['mcassoc'] = $_POST;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> - Minecraft association page">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['verify_account'];
	
	require('core/includes/template/generate.php');
	?>
	
	<script src="/core/assets/js/client.js"></script>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>
  
  <body>
    <?php
	// Navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
    
	<div class="container">
	  <h2><?php echo $user_language['verify_account']; ?></h2>
	  <div class="alert alert-info"><?php echo $user_language['verify_account_help']; ?></div>
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
						echo '<div class="alert alert-danger">' . $user_language['verification_failed'] . '<br /><a href="/register" class="btn btn-primary">' . $user_language['register'] . '</a></div>';
						unset($_SESSION['mcassoc']);
					} else {
						// Matches
						// Register the account
						// Password
						$password = password_hash($_SESSION['mcassoc']['password'], PASSWORD_BCRYPT, array("cost" => 13));
					  
						// Get current unix time
						$date = new DateTime();
						$date = $date->getTimestamp();
					  
						// Get IP
						$ip = $user->getIP();
					  
						$user->create(array(
							'mcname' => htmlspecialchars($username),
							'username' => htmlspecialchars($_SESSION['mcassoc']['username']),
							'uuid' => htmlspecialchars($data->uuid),
							'password' => $password,
							'pass_method' => 'default',
							'joined' => $date,
							'group_id' => 1,
							'email' => htmlspecialchars($_SESSION['mcassoc']['email']),
							'active' => 1,
							'lastip' => htmlspecialchars($ip),
							'last_online' => $date,
							'birthday' => date('Y-m-d', strtotime(str_replace('-', '/', htmlspecialchars($_SESSION['mcassoc']['birthday'])))),
							'location' => htmlspecialchars($_SESSION['mcassoc']['location'])
						));
					  
						unset($_SESSION['mcassoc']);
					  
						echo '<div class="alert alert-success">' . $user_language['verification_success'] . '<br /><a href="/signin" class="btn btn-primary">' . $user_language['sign_in'] . '</a></div>';
					}
			  } catch (Exception $e) {
				  echo '<div class="alert alert-danger">' . $user_language['verification_failed'] . '<br /><a href="/register" class="btn btn-primary">' . $user_language['register'] . '</a></div>';
				  unset($_SESSION['mcassoc']);
			  }
	      }
	  }
	  ?>
	</div>
  
    <?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');

	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	
  </body>
</html>