<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *
 *  Complete API registration by asking user for their password
 *
 */
 
if(!isset($_GET['c'])){
	echo '<script data-cfasync="false">window.location.replace("/");</script>';
	die();
} else {
	require('core/includes/password.php'); // For password hashing
	
	// Ensure API is actually enabled
	$is_enabled = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
	if($is_enabled[0]->value != '1'){
		die('API is disabled');
	}
	
	if(!$user->isLoggedIn()){
		$check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
		if(count($check)){
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					// Validate input
					$to_validation = array(
						'password' => array(
							'required' => true,
							'min' => 6,
							'max' => 30
						),
						'password_again' => array(
							'matches' => 'password'
						),
						't_and_c' => array(
							'required' => true,
							'agree' => true
						)
					);
					
					$validate = new Validate();
					$validation = $validate->check($_POST, $to_validation);
					
					if($validation->passed()){
						// Complete registration
						$check = $check[0];
						
						// Hash password
						$password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));
						
						try {
							$queries->update('users', $check->id, array(
								'password' => $password,
								'reset_code' => null,
								'active' => 1
							));
						} catch(Exception $e){
							die($e->getMessage());
						}
						
						Session::flash('home', '<div class="alert alert-info">' . $user_language['validation_complete'] . '</div>');
						echo '<script data-cfasync="false">window.location.replace("/");</script>';
						die();
						
					} else {
						// Errors
						foreach($validation->errors() as $validation_error){
							
							if(strpos($validation_error, 'is required') !== false){
								// x is required
								switch($validation_error){
									case (strpos($validation_error, 'password') !== false):
										$error .= $user_language['password_required'] . '<br />';
									break;
									case (strpos($validation_error, 't_and_c') !== false):
										$error .= $user_language['accept_terms'] . '<br />';
									break;
								}
								
							} else if(strpos($validation_error, 'minimum') !== false){
								$error .= $user_language['password_minimum_6'] . '<br />';
								
							} else if(strpos($validation_error, 'maximum') !== false){
								$error .= $user_language['password_maximum_30'] . '<br />';
								
								
							} else if(strpos($validation_error, 'must match') !== false){
								// password must match password again
								$error .= $user_language['passwords_dont_match'] . '<br />';
							}
						}
						
						$error = rtrim($error, '<br />');
					}
			
				} else {
					$error = $admin_language['invalid_token'];
				}
			}
		} else {
			Session::flash('home', '<div class="alert alert-danger">' . $user_language['validation_error'] . '</div>');
			echo '<script data-cfasync="false">window.location.replace("/");</script>';
			die();
		}
	} else {
		echo '<script data-cfasync="false">window.location.replace("/");</script>';
		die();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> signup completion page">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<meta name="robots" content="noindex">
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['complete_signup'];
	
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
	<?php
	// Navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>

	<div class="container">
	  <div class="row">
		<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		  <form role="form" action="" method="post">
			<h2><?php echo $user_language['complete_signup']; ?></h2>
			
			<?php if(isset($error)){ ?>
			<div class="alert alert-danger">
			  <?php echo $error; ?>
			</div>
			<?php } ?>

			<hr class="colorgraph">

			<div class="form-group">
			  <label for="inputPassword"><?php echo $user_language['password']; ?></label>
			  <input type="password" class="form-control" name="password" id="inputPassword" placeholder="<?php echo $user_language['password']; ?>">
			</div>
			
			<div class="form-group">
			  <label for="inputPasswordAgain"><?php echo $user_language['confirm_password']; ?></label>
			  <input type="password" class="form-control" name="password_again" id="inputPasswordAgain" placeholder="<?php echo $user_language['confirm_password']; ?>">
			</div>
			
			<div class="row">
			  <div class="col-xs-4 col-sm-3 col-md-3">
				<span class="button-checkbox">
				  <button type="button" class="btn" data-color="info"> <?php echo $user_language['i_agree']; ?></button>
				  <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
				</span>
			  </div>
			  <div class="col-xs-8 col-sm-9 col-md-9">
				<?php echo $user_language['agree_t_and_c']; ?>
			  </div>
			</div>
			
			<hr class="colorgraph">
			
			<div class="form-group">
			  <center>
			    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			    <input type="submit" class="btn btn-primary btn-lg" value="<?php echo $user_language['register']; ?>">
			  </center>
			</div>

		  </form>
		</div>
	  </div>
	</div>

	<?php
	// HTML Purifier
	require_once('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	$config->set('URI.DisableExternalResources', false);
	$config->set('URI.DisableResources', false);
	$config->set('HTML.Allowed', 'u,p,b,i,a,s');
	$config->set('HTML.AllowedAttributes', 'target, href');
	$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	$purifier = new HTMLPurifier($config);
	?>
	<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="t_and_c_m_Label" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="t_and_c_m_Label"><?php echo $user_language['terms_and_conditions']; ?></h4>
		  </div>
		  <div class="modal-body">
			<?php 
			$t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c"));
			echo $purifier->purify(htmlspecialchars_decode($t_and_c[0]->value));
			$t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c_site"));
			echo $purifier->purify(htmlspecialchars_decode($t_and_c[0]->value));
			?>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $general_language['close']; ?></button>
		  </div>
		</div>
	  </div>
	</div>
	
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');

	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	

	<script>
	$(function () {
		$('.button-checkbox').each(function () {
			// Settings
			var $widget = $(this),
				$button = $widget.find('button'),
				$checkbox = $widget.find('input:checkbox'),
				color = $button.data('color'),
				settings = {
					on: {
						icon: 'glyphicon glyphicon-check'
					},
					off: {
						icon: 'glyphicon glyphicon-unchecked'
					}
				};
			// Event Handlers
			$button.on('click', function () {
				$checkbox.prop('checked', !$checkbox.is(':checked'));
				$checkbox.triggerHandler('change');
				updateDisplay();
			});
			$checkbox.on('change', function () {
				updateDisplay();
			});
			// Actions
			function updateDisplay() {
				var isChecked = $checkbox.is(':checked');
				// Set the button's state
				$button.data('state', (isChecked) ? "on" : "off");
				// Set the button's icon
				$button.find('.state-icon')
					.removeClass()
					.addClass('state-icon ' + settings[$button.data('state')].icon);
				// Update the button's color
				if (isChecked) {
					$button
						.removeClass('btn-default')
						.addClass('btn-' + color + ' active');
				}
				else {
					$button
						.removeClass('btn-' + color + ' active')
						.addClass('btn-default');
				}
			}
			// Initialisation
			function init() {
				updateDisplay();
				// Inject the icon if applicable
				if ($button.find('.state-icon').length == 0) {
					$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
				}
			}
			init();
		});
	});
	</script>
  </body>
</html>
