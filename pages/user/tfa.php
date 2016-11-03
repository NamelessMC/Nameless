<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
$page = 'tfa';

// Two factor auth
// Ensure user is logged in
if(!$user->isLoggedIn()){
	Redirect::to('/');
	die();
}

use \RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('NamelessMC');

if(!isset($_GET['s'])){
	// Generate secret
	$secret = $tfa->createSecret();

	if($user->data()->tfa_secret){
		Redirect::to('/user/settings');
		die();
	}

	$queries->update('users', $user->data()->id, array(
		'tfa_secret' => $secret
	));

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Two Factor Authentication">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = 'Two Factor Authentication';
	
	require('core/includes/template/generate.php');
	?>
  </head>
  <body>
    <div class="container">
	  <div class="well">
	    <h2><?php echo $user_language['two_factor_authentication']; ?></h2>
	    <p><?php echo $user_language['tfa_scan_code']; ?></p>
	    <img src="<?php echo $tfa->getQRCodeImageAsDataUri($sitename . ':' . htmlspecialchars($user->data()->mcname), $secret); ?>">
	    <hr />
	    <p><?php echo $user_language['tfa_code']; ?></p>
	    <br />
	    <strong><?php echo chunk_split($secret, 4, ' '); ?></strong>
	  
	    <hr />
	  
	    <a href="/user/tfa/?s=2" class="btn btn-primary"><?php echo $general_language['next']; ?></a>
	  </div>
	</div>
  </body>
</html>
<?php 
} else { 
	// Validate code to see if it matches the secret
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			if(isset($_POST['tfa_code'])){
				if($tfa->verifyCode($user->data()->tfa_secret, $_POST['tfa_code']) === true){
					$queries->update('users', $user->data()->id, array(
						'tfa_complete' => 1
					));
					
					Session::flash('usercp_settings', '<div class="alert alert-success">' . $user_language['tfa_successful'] . '</div>');
					Redirect::to('/user/settings');
					die();
				} else {
					Session::flash('tfa', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
				}
			} else {
				Session::flash('tfa', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
			}
		} else {
			Session::flash('tfa', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Two Factor Authentication">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = 'Two Factor Authentication';
	
	require('core/includes/template/generate.php');
	?>
  </head>
  <body>
    <div class="container">
	  <div class="well">
	    <h2><?php echo $user_language['two_factor_authentication']; ?></h2>
		<?php if(Session::exists('tfa')) echo Session::flash('tfa'); ?>
	    <p><?php echo $user_language['tfa_enter_code']; ?></p>
	    <form action="" method="post">
	      <input type="text" class="form-control" name="tfa_code">
		  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		  <br />
		  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
	    </form>
	  </div>
	</div>
  </body>
</html>
<?php } ?>
