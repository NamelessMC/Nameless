<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin index page
 */

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
		} else if(!$user->hasPermission('admincp.core.registration')){
            require(ROOT_PATH . '/404.php');
            die();
        }
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
$page = 'admin';
$admin_page = 'core';

// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Valid token
		// Process input
		if(isset($_POST['enable_registration'])){
			// Either enable or disable registration
			$enable_registration_id = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
			$enable_registration_id = $enable_registration_id[0]->id;
			
			$queries->update('settings', $enable_registration_id, array(
				'value' => Input::get('enable_registration')
			));
		} else {
			// Registration settings
			if(isset($_POST['verification']) && $_POST['verification'] == 'on')
			  $verification = 1;
			else
			  $verification = 0;

			$verification_id = $queries->getWhere('settings', array('name', '=', 'email_verification'));
			$verification_id = $verification_id[0]->id;

            // reCAPCTHA enabled?
            if(Input::get('enable_recaptcha') == 1){
                $recaptcha = 'true';
            } else {
                $recaptcha = 'false';
            }
            $recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
            $recaptcha_id = $recaptcha_id[0]->id;
            $queries->update('settings', $recaptcha_id, array(
                'value' => $recaptcha
            ));
            // reCAPTCHA key
            $recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
            $recaptcha_id = $recaptcha_id[0]->id;
            $queries->update('settings', $recaptcha_id, array(
                'value' => htmlspecialchars(Input::get('recaptcha'))
            ));
            // reCAPTCHA secret key
            $recaptcha_secret_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
            $recaptcha_secret_id = $recaptcha_secret_id[0]->id;
            $queries->update('settings', $recaptcha_secret_id, array(
                'value' => htmlspecialchars(Input::get('recaptcha_secret'))
            ));

            // Registration disabled message
            $registration_disabled_id = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));
            $registration_disabled_id = $registration_disabled_id[0]->id;
            $queries->update('settings', $registration_disabled_id, array(
                'value' => htmlspecialchars(Input::get('message'))
            ));

			try {
			  $queries->update('settings', $verification_id, array(
			     'value' => $verification
			  ));
			} catch(Exception $e){
			  $error = $e->getMessage();
			}

			// Validation group
			$validation_group_id = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
			$validation_action = $validation_group_id[0]->value;
			$validation_action = json_decode($validation_action, true);
			if(isset($validation_action['action']))
			    $validation_action = $validation_action['action'];
			else
			    $validation_action = 'promote';
			$validation_group_id = $validation_group_id[0]->id;

			$new_value = json_encode(array('action' => $validation_action, 'group' => $_POST['promote_group']));

			try {
			    $queries->update('settings', $validation_group_id, array(
			        'value' => $new_value
			    ));
			    Log::getInstance()->log(Log::Action('admin/user/register'));
			} catch(Exception $e){
			    $error = $e->getMessage();
			}
		}
	} else {
		// Invalid token
		$error = $language->get('general', 'invalid_token');
	}
}

// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
$registration_enabled = $registration_enabled[0]->value;

// Generate form token
$token = Token::get();

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
	
	<link rel="stylesheet" href="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
  
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
			  <h3><?php echo $language->get('admin', 'registration'); ?></h3>

			  <?php if(isset($error)){ ?>
			  <div class="alert alert-danger">
			    <?php echo $error; ?>
			  </div>
			  <?php } ?>

			  <form id="enableRegistration" action="" method="post">
			    <div class="form-group">
			      <?php echo $language->get('admin', 'enable_registration'); ?>
				  <input type="hidden" name="enable_registration" value="0">
			      <input name="enable_registration" type="checkbox" class="js-switch js-check-change"<?php if($registration_enabled == '1'){ ?> checked<?php } ?> value="1" />
                </div>
				<input type="hidden" name="token" value="<?php echo $token; ?>">
			  </form>
			  
			  <?php
				  // Is email verification enabled
				  $emails = $queries->getWhere('settings', array('name', '=', 'email_verification'));
				  $emails = $emails[0]->value;

				  // Recaptcha
                  $recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
                  $recaptcha_key = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
                  $recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
                  $registration_disabled_message = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));

                  // Validation group
                  $validation_group = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
                  $validation_group = $validation_group[0]->value;
                  $validation_group = json_decode($validation_group, true);
                  if(isset($validation_group['group']))
                      $validation_group = $validation_group['group'];
                  else
                     $validation_group = 1;
			  ?>
			  <hr />
			  <form action="" method="post">
				<div class="form-group">
			      <label for="verification"><?php echo $language->get('admin', 'email_verification'); ?></label>
			      <input name="verification" id="verification" type="checkbox" class="js-switch"<?php if($emails == '1'){ ?> checked<?php } ?> />
				</div>
                <div class="form-group">
                  <label for="InputEnableRecaptcha"><?php echo $language->get('admin', 'google_recaptcha'); ?></label>
                  <input id="InputEnableRecaptcha" name="enable_recaptcha" type="checkbox" class="js-switch" value="1"<?php if($recaptcha_id[0]->value == 'true'){ ?> checked<?php } ?> />
                </div>
                <div class="form-group">
                  <label for="InputRecaptcha"><?php echo $language->get('admin', 'recaptcha_site_key'); ?></label>
                  <input type="text" name="recaptcha" class="form-control" id="InputRecaptcha" placeholder="<?php echo $language->get('admin', 'recaptcha_site_key'); ?>" value="<?php echo htmlspecialchars($recaptcha_key[0]->value); ?>">
                </div>
                <div class="form-group">
                  <label for="InputRecaptchaSecret"><?php echo $language->get('admin', 'recaptcha_secret_key'); ?></label>
                  <input type="text" name="recaptcha_secret" class="form-control" id="InputRecaptchaSecret" placeholder="<?php echo $language->get('admin', 'recaptcha_secret_key'); ?>" value="<?php echo htmlspecialchars($recaptcha_secret[0]->value); ?>">
                </div>
				<div class="form-group">
				  <label for="InputRegistrationDisabledMessage"><?php echo $language->get('admin', 'registration_disabled_message'); ?></label>
				  <textarea style="width:100%" rows="10" name="message" id="InputRegistrationDisabledMessage"><?php echo Output::getPurified(htmlspecialchars_decode($registration_disabled_message[0]->value)); ?></textarea>
				</div>
				<div class="form-group">
				  <label for="InputValidationPromoteGroup"><?php echo $language->get('admin', 'validation_promote_group'); ?></label> <span class="badge badge-info" data-toggle="popover" data-content="<?php echo $language->get('admin', 'validation_promote_group_info'); ?>"><i class="fa fa-question"></i></span>
				  <select class="form-control" id="InputValidationPromoteGroup" name="promote_group">
				    <?php
				    $groups = $queries->getWhere('groups', array('id', '<>', 0));
				    foreach($groups as $group){
				        echo '<option value="' . $group->id . '"' . (($group->id == $validation_group) ? ' selected' : '') . '>' . Output::getClean($group->name) . '</option>';
				    }
				    ?>
				  </select>
				</div>
				<div class="form-group">
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                </div>
			  </form>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>

    <?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
	
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html);
	});
	
	/*
	 *  Submit form on clicking enable/disable registration
	 */
	var changeCheckbox = document.querySelector('.js-check-change');

	changeCheckbox.onchange = function() {
	  $('#enableRegistration').submit();
	};
	
	</script>

	<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
	<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
        <?php
        echo Input::createEditor('InputRegistrationDisabledMessage');
        ?>
	</script>
	
  </body>
</html>