<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Two Factor Auth signin
$_SESSION['username'] = Input::get('username');
$_SESSION['password'] = Input::get('password');
$_SESSION['remember'] = Input::get('remember');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> sign in page">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['sign_in'];

	require('core/includes/template/generate.php');
	?>

	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>

  </head>
  <body>
    <div class="container">
	  <div class="well">
		<form action="" method="post">
		  <h2><?php echo $user_language['two_factor_authentication']; ?></h2>
		  <?php if(Session::exists('tfa_signin')) echo Session::flash('tfa_signin'); ?>
		  <p><?php if($user_query[0]->tfa_type == 1) echo $user_language['tfa_enter_code']; else echo $user_language['tfa_enter_email_code']; ?></p>
		  <input type="text" class="form-control" name="tfa_code">
		  <input type="hidden" name="tfa" value="true">
		  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		  <br />
		  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
		</form>
	  </div>
	</div>
  </body>
</html>
