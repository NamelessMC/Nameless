<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  UserCP settings
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_settings');

require('core/templates/cc_navbar.php');

require('core/includes/password.php'); // For password hashing
require('core/includes/phpass.php'); // phpass for Wordpress auth
require('core/includes/emojione/autoload.php'); // Emojione
require('core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

// Two factor auth?
if(isset($_GET['do'])){
	if($_GET['do'] == 'enable_tfa'){
		// Enable TFA
		require('core/includes/tfa/autoload.php');
		
		// Ensure TFA is currently disabled
		if($user->data()->tfa_enabled == 1){
			Redirect::to(URL::build('/user/settings'));
			die();
		}
		
        $tfa = new \RobThree\Auth\TwoFactorAuth(SITE_NAME);
		
		if(!isset($_GET['s'])){
			// Generate secret
			$secret = $tfa->createSecret();

			$queries->update('users', $user->data()->id, array(
				'tfa_secret' => $secret
			));
		?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('user', 'user_cp');
	require('core/templates/header.php'); 
	?>
  </head>
  <body>
    <?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');
	
	// Assign Smarty variables
	$smarty->assign(array(
		'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
		'TFA_SCAN_CODE_TEXT' => $language->get('user', 'tfa_scan_code'),
		'IMG_SRC' => $tfa->getQRCodeImageAsDataUri(SITE_NAME . ':' . Output::getClean($user->data()->username), $secret),
		'TFA_CODE_TEXT' => $language->get('user', 'tfa_code'),
		'TFA_CODE' => chunk_split($secret, 4, ' '),
		'NEXT' => $language->get('general', 'next'),
		'LINK' => URL::build('/user/settings/', 'do=enable_tfa&amp;s=2'),
		'CANCEL' => $language->get('general', 'cancel'),
		'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa')
	));
	
	$smarty->display('custom/templates/' . TEMPLATE . '/user/tfa.tpl');

    require('core/templates/scripts.php');
	?>
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
								'tfa_complete' => 1,
								'tfa_enabled' => 1,
								'tfa_type' => 1
							));
							
							Session::flash('tfa_success', $language->get('user', 'tfa_successful'));
							Redirect::to(URL::build('/user/settings'));
							die();
						} else {
							$error = $language->get('user', 'invalid_tfa');
						}
					} else {
						$error = $language->get('user', 'invalid_tfa');
					}
				} else {
					$error = $language->get('general', 'invalid_token');
				}
			}
			?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('user', 'user_cp');
	require('core/templates/header.php'); 
	?>
  
  </head>
  <body>
	<?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');
	if(isset($error)) $smarty->assign('ERROR', $error);
	
	$smarty->assign(array(
		'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
		'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
		'SUBMIT' => $language->get('general', 'submit'),
		'TOKEN' => Token::get(),
		'CANCEL' => $language->get('general', 'cancel'),
		'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa')
	));
	
	$smarty->display('custom/templates/' . TEMPLATE . '/user/tfa.tpl');

	require('core/templates/scripts.php');
	?>
  </body>
</html>
		<?php
		}
	} else if($_GET['do'] == 'disable_tfa') {
		// Disable TFA
		$queries->update('users', $user->data()->id, array(
			'tfa_enabled' => 0,
			'tfa_type' => 0,
			'tfa_secret' => null,
			'tfa_complete' => 0
		));

		Redirect::to(URL::build('/user/settings'));
		die();
	}
	
} else {
	// Handle input
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			if(Input::get('action') == 'settings'){
				// Validation
				$validate = new Validate();
				
				$to_validate = array(
                    'signature' => array(
                        'max' => 900
                    )
                );
				
				// Get a list of required profile fields
				$profile_fields = $queries->getWhere('profile_fields', array('required', '=', 1));
				
				if(count($profile_fields)){
					foreach($profile_fields as $field){
						$to_validate[$field->id] = array(
							'required' => true,
							'max' => (is_null($field->length) ? 1024 : $field->length)
						);
					}
				}
				
				$validation = $validate->check($_POST, $to_validate);
				
				if($validation->passed()){
					// Update profile fields
					try {
						// Update language and timezone
						$new_language = $queries->getWhere('languages', array('name', '=', Input::get('language')));
						
						if(count($new_language)) $new_language = $new_language[0]->id;
						else $new_language = $user->data()->language_id;

						$timezone = Input::get('timezone');

						$cache->setCache('post_formatting');
						$formatting = $cache->retrieve('formatting');

						if($formatting == 'markdown'){
						    $signature = Michelf\Markdown::defaultTransform(Input::get('signature'));
						    $signature = Output::getClean($signature);
						} else $signature = Output::getClean(Input::get('signature'));
						
						$queries->update('users', $user->data()->id, array(
							'language_id' => $new_language,
							'timezone' => $timezone,
							'signature' => $signature
						));
						
						foreach($_POST as $key => $item){
							if(strpos($key, 'action') !== false || strpos($key, 'token') !== false){
								// Action/token, don't do anything
								
							} else {
								// Check field exists
								$field_exists = $queries->getWhere('profile_fields', array('id', '=', $key));
								if(!count($field_exists)) continue;
								
								// Update or create?
								$update = false;
								$exists = $queries->getWhere('users_profile_fields', array('user_id', '=', $user->data()->id));
								
								if(count($exists)){
									foreach($exists as $exist){
										if($exist->field_id == $key){
											// Exists
											$update = true;
											break;
										}
									}
								}
								
								if($update == true){
									// Update field value
									$queries->update('users_profile_fields', $exist->id, array(
										'value' => Output::getClean($item) // Todo - allow HTML
									));
								} else {
									// Create new field value
									$queries->create('users_profile_fields', array(
										'user_id' => $user->data()->id,
										'field_id' => $key,
										'value' => Output::getClean($item) // Todo - allow HTML
									));
								}
							}
						}
						
						Session::flash('settings_success', $language->get('user', 'settings_updated_successfully'));
						Redirect::to(URL::build('/user/settings'));
						die();
						
					} catch(Exception $e){
						Session::flash('settings_error', $e->getMessage());
					}
					
				} else {
					// Validation errors
					$error = '';
					foreach($validation->errors() as $item){
					    if(strpos($item, 'signature') !== false){
					        $error .= $language->get('user', 'signature_max_900') . '<br />';
                        } else {
                            // Get field name
                            $id = explode(' ', $item);
                            $id = $id[0];

                            $field = $queries->getWhere('profile_fields', array('id', '=', $id));
                            if (count($field)) {
                                $field = $field[0];
                                $error .= str_replace('{x}', Output::getClean($field->name), $language->get('user', 'field_is_required')) . '<br />';
                            }
                        }
					}
					
					Session::flash('settings_error', rtrim($error, '<br />'));
				}
			} else if(Input::get('action') == 'password'){
				// Change password
				$validate = new Validate();
				
				$validation = $validate->check($_POST, array(
					'old_password' => array(
						'required' => true
					),
					'new_password' => array(
						'required' => true,
						'min' => 6,
						'max' => 30
					),
					'new_password_again' => array(
						'required' => true,
						'matches' => 'new_password'
					)
				));
				
				if($validation->passed()){
					// Update password
					// Check old password matches 
					$old_password = Input::get('old_password');
					if($user->checkCredentials($user->data()->username, $old_password)){
						try {
							// Hash new password
							$new_password = password_hash(Input::get('new_password'), PASSWORD_BCRYPT, array("cost" => 13));
							
							// Update password
							$queries->update('users', $user->data()->id, array(
								'password' => $new_password,
								'pass_method' => 'default'
							));
							
							$success = $language->get('user', 'password_changed_successfully');

						} catch(Exception $e) {
							die($e->getMessage());
						}
					} else {
						// Invalid current password
						Session::flash('settings_error', $language->get('user', 'incorrect_password'));
					}
				} else {
					$error = '';
					foreach($validation->errors() as $item){
						if(strpos($item, 'is required') !== false){
							// Empty field
							if(strpos($error, $language->get('user', 'password_required')) !== false){
								// Only add error once
							} else {
								$error .= $language->get('user', 'password_required') . '<br />';
							}
						} else if(strpos($item, 'minimum') !== false){
							// Field under 6 chars
							if(strpos($error, $language->get('user', 'password_minimum_6')) !== false){
								// Only add error once
							} else {
								$error .= $language->get('user', 'password_minimum_6') . '<br />';
							}
						} else if(strpos($item, 'maximum') !== false){
							// Field under 6 chars
							if(strpos($error, $language->get('user', 'password_maximum_30')) !== false){
								// Only add error once
							} else {
								$error .= $language->get('user', 'password_maximum_30') . '<br />';
							}
						} else if(strpos($item, 'must match') !== false){
							// Password must match password again
							$error .= $language->get('user', 'passwords_dont_match') . '<br />';
						}
					}
					Session::flash('settings_error', $error = rtrim($error, '<br />'));
				}
			}
		} else {
			// Invalid form token
			Session::flash('settings_error', $language->get('general', 'invalid_token'));
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('user', 'user_cp');
	require('core/templates/header.php'); 
	?>
  
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.sprites.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/css/emojionearea.min.css"/>
  </head>
  <body>
    <?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');

	// Error/success message?
	if(Session::exists('settings_error')) $error = Session::flash('settings_error');
	if(Session::exists('settings_success')) $success = Session::flash('settings_success');
	
	// Get languages
	$languages = array();
	$language_query = $queries->getWhere('languages', array('id', '<>', 0));

	foreach($language_query as $item){
		$languages[] = array(
			'name' => Output::getClean($item->name),
			'active' => (($user->data()->language_id == $item->id) ? true : false)
		);
	}
	
	// Get custom fields
	$custom_fields = $queries->getWhere('profile_fields', array('id', '<>', 0));
	$user_custom_fields = $queries->getWhere('users_profile_fields', array('user_id', '=', $user->data()->id));
	
	// Custom usernames?
	$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
	$displaynames = $displaynames[0]->value;
	
	$custom_fields_template = array(
		'displayname' => array(
			'disabled' => true
		)
	);
	
	if($displaynames == 'true'){
		$custom_fields_template['displayname'] = array(
			'name' => $language->get('user', 'nickname'),
			'value' => Output::getClean($user->data()->nickname),
			'id' => 'displayname',
			'type' => 'text'
		);
	}
	
	if(count($custom_fields)){
		foreach($custom_fields as $field){
			// Get field value for user
			$value = '';
			if(count($user_custom_fields)){
				foreach($user_custom_fields as $key => $item){
					if($item->field_id == $field->id){
						// TODO: support HTML fields
						$value = Output::getClean($item->value);
						unset($user_custom_fields[$key]);
						break;
					}
				}
			}
			
			// Get custom field type
			if($field->type == 1)
				$type = 'text';
			else if($field->type == 2)
				$type = 'textarea';
			else if($field->type == 3)
				$type = 'date';
			
			$custom_fields_template[$field->name] = array(
				'name' => Output::getClean($field->name),
				'value' => $value,
				'id' => $field->id,
				'type' => $type
			);
		}
	}
	
	if(Session::exists('tfa_success')){
		$success = Session::flash('tfa_success');
	}

    // Get post formatting type (HTML or Markdown)
    $cache->setCache('post_formatting');
    $formatting = $cache->retrieve('formatting');

    if($formatting == 'markdown'){
        // Markdown
        require('core/includes/markdown/tomarkdown/autoload.php');
        $converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

        $signature = $converter->convert(htmlspecialchars_decode($user->data()->signature));
        $signature = Output::getPurified($signature);

        $smarty->assign('MARKDOWN', true);
        $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
    } else {
        $signature = Output::getPurified(htmlspecialchars_decode($user->data()->signature));
    }

	// Language values
	$smarty->assign(array(
		'SETTINGS' => $language->get('user', 'profile_settings'),
		'ACTIVE_LANGUAGE' => $language->get('user', 'active_language'),
		'LANGUAGES' => $languages,
		'PROFILE_FIELDS' => $custom_fields_template,
		'SUBMIT' => $language->get('general', 'submit'),
		'TOKEN' => Token::get(),
		'ERROR' => (isset($error) ? $error : false),
		'SUCCESS' => (isset($success) ? $success : false),
		'CHANGE_PASSWORD' => $language->get('user', 'change_password'),
		'CURRENT_PASSWORD' => $language->get('user', 'current_password'),
		'NEW_PASSWORD' => $language->get('user', 'new_password'),
		'CONFIRM_NEW_PASSWORD' => $language->get('user', 'confirm_new_password'),
		'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
		'TIMEZONE' => $language->get('user', 'timezone'),
		'TIMEZONES' => Util::listTimezones(),
		'SELECTED_TIMEZONE' => $user->data()->timezone,
		'SIGNATURE' => $language->get('user', 'signature'),
        'SIGNATURE_VALUE' => $signature
	));

	if(defined('CUSTOM_AVATARS')) {
      $smarty->assign(array(
        'CUSTOM_AVATARS' => true,
        'CUSTOM_AVATARS_SCRIPT' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/includes/image_upload.php',
        'BROWSE' => $language->get('general', 'browse'),
        'UPLOAD_NEW_PROFILE_IMAGE' => $language->get('user', 'upload_new_avatar')
      ));
	}
	
	if($user->data()->tfa_enabled == 1){
		// Disable
		$smarty->assign('DISABLE', $language->get('user', 'disable'));
		$smarty->assign('DISABLE_LINK', URL::build('/user/settings/', 'do=disable_tfa'));
	} else {
		// Enable
		$smarty->assign('ENABLE', $language->get('user', 'enable'));
		$smarty->assign('ENABLE_LINK', URL::build('/user/settings/', 'do=enable_tfa'));
	}
	
	$smarty->display('custom/templates/' . TEMPLATE . '/user/settings.tpl');

    require('core/templates/scripts.php');
	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	
	<script>
	$('.datepicker').datepicker();
	</script>

    <?php
    $cache->setCache('post_formatting');
    $formatting = $cache->retrieve('formatting');
    if($formatting == 'markdown'){
        ?>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/js/emojionearea.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                var el = $("#inputSignature").emojioneArea({
                    pickerPosition: "bottom"
                });
            });
        </script>
    <?php
    } else {
    ?>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
        <?php
        echo '<script type="text/javascript">' . Input::createEditor('signature') . '</script>';
    }
    ?>
	
  </body>
</html>
<?php
}
?>