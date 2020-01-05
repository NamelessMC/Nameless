<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
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
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

require(ROOT_PATH . '/core/includes/password.php'); // For password hashing
require(ROOT_PATH . '/core/includes/phpass.php'); // phpass for Wordpress auth
require(ROOT_PATH . '/core/includes/emojione/autoload.php'); // Emojione
require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

// Two factor auth?
if(isset($_GET['do'])){
	if($_GET['do'] == 'enable_tfa'){
		// Enable TFA
		require(ROOT_PATH . '/core/includes/tfa/autoload.php');
		
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
				'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa'),
				'ERROR_TITLE' => $language->get('general', 'error')
			));

			// Load modules + template
			Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

			require(ROOT_PATH . '/core/templates/cc_navbar.php');

			$page_load = microtime(true) - $start;
			define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

			$template->onPageLoad();

			require(ROOT_PATH . '/core/templates/navbar.php');
			require(ROOT_PATH . '/core/templates/footer.php');

			// Display template
			$template->displayTemplate('user/tfa.tpl', $smarty);

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

			if(isset($error)) $smarty->assign('ERROR', $error);

			$smarty->assign(array(
				'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
				'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
				'SUBMIT' => $language->get('general', 'submit'),
				'TOKEN' => Token::get(),
				'CANCEL' => $language->get('general', 'cancel'),
				'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa')
			));

			// Load modules + template
			Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

			require(ROOT_PATH . '/core/templates/cc_navbar.php');

			$page_load = microtime(true) - $start;
			define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

			$template->onPageLoad();

			require(ROOT_PATH . '/core/templates/navbar.php');
			require(ROOT_PATH . '/core/templates/footer.php');

			// Display template
			$template->displayTemplate('user/tfa.tpl', $smarty);

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

				// Permission to use nickname?
                if($user->hasPermission('usercp.nickname')){
                    $to_validate['nickname'] = array(
                        'required' => true,
                        'min' => 3,
                        'max' => 20
                    );

                    $displayname = Output::getClean(Input::get('nickname'));
                } else
                    $displayname = $user->data()->username;

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
				    // Check nickname is unique
                    if($user->hasPermission('usercp.nickname')) {
                        $unique_nickname = $queries->getWhere('users', array('nickname', '=', Output::getClean(Input::get('nickname'))));
                        if(count($unique_nickname)){
                            $unique_nickname = $unique_nickname[0];
                            if($unique_nickname->id != $user->data()->id){
                                // Not unique
                                $nickname_error = true;
                                $error = $language->get('user', 'nickname_already_exists');
                            }
                        }
                    }

					// Update profile fields
                    if(!isset($nickname_error)) {
                        try {
                            // Update language, template and timezone
                            $new_language = $queries->getWhere('languages', array('name', '=', Input::get('language')));

                            if (count($new_language)) $new_language = $new_language[0]->id;
                            else $new_language = $user->data()->language_id;

	                        $new_template = $queries->getWhere('templates', array('id', '=', Input::get('template')));

	                        if (count($new_template)) $new_template = $new_template[0]->id;
	                        else $new_template = $user->data()->template_id;

	                        // Check permissions
	                        $available_templates = $user->getUserTemplates();

	                        foreach($available_templates as $available_template){
	                        	if($available_template->id == $new_template){
	                        		$can_update = true;
	                        		break;
		                        }
	                        }

	                        if(!isset($can_update)){
	                        	$new_template = $user->data()->template_id;
	                        }

                            $timezone = Input::get('timezone');

                            if ($user->hasPermission('usercp.signature')) {
                                $cache->setCache('post_formatting');
                                $formatting = $cache->retrieve('formatting');

                                if ($formatting == 'markdown') {
                                    $signature = Michelf\Markdown::defaultTransform(Input::get('signature'));
                                    $signature = Output::getClean($signature);
                                } else $signature = Output::getClean(Input::get('signature'));
                            } else
                                $signature = '';

                            // Private profiles enabled?
                            $private_profiles = $queries->getWhere('settings', array('name', '=', 'private_profile'));
                            if($private_profiles[0]->value == 1) {
                                if ($user->canPrivateProfile($user->data()->id) && $_POST['privateProfile'] == 1)
                                    $privateProfile = 1;
                                else
                                    $privateProfile = 0;
                            } else
                                $privateProfile = $user->data()->private_profile;

                            $queries->update('users', $user->data()->id, array(
                                'language_id' => $new_language,
                                'timezone' => $timezone,
                                'signature' => $signature,
                                'nickname' => $displayname,
                                'private_profile' => $privateProfile,
	                            'theme_id' => $new_template
                            ));

                            Log::getInstance()->log(Log::Action('user/ucp/update'));


                            foreach ($_POST as $key => $item) {
                                if (strpos($key, 'action') !== false || strpos($key, 'token') !== false) {
                                    // Action/token, don't do anything

                                } else {
                                    // Check field exists
                                    $field_exists = $queries->getWhere('profile_fields', array('id', '=', $key));
                                    if (!count($field_exists)) continue;

                                    // Update or create?
                                    $update = false;
                                    $exists = $queries->getWhere('users_profile_fields', array('user_id', '=', $user->data()->id));

                                    if (count($exists)) {
                                        foreach ($exists as $exist) {
                                            if ($exist->field_id == $key) {
                                                // Exists
                                                $update = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($update == true) {
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

                        } catch (Exception $e) {
                            Session::flash('settings_error', $e->getMessage());
                        }
                    }
					
				} else {
					// Validation errors
					$error = '';
					foreach($validation->errors() as $item){
					    if(strpos($item, 'signature') !== false){
					        $error .= $language->get('user', 'signature_max_900') . '<br />';
                        } else if(strpos($item, 'nickname') !== false){
					        if(strpos($item, 'required') !== false){
					            $error .= $language->get('user', 'username_required') . '<br />';
                            } else if(strpos($item, 'min')  !== false){
                                $error .= $language->get('user', 'username_minimum_3') . '<br />';
                            } else if(strpos($item, 'max') !== false){
                                $error .= $language->get('user', 'username_maximum_20') . '<br />';
                            }
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
					if($user->checkCredentials($user->data()->username, $old_password, 'username')){
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
			} else if(Input::get('action') == 'email'){
                // Change password
                $validate = new Validate();

                $validation = $validate->check($_POST, array(
                    'password' => array(
                        'required' => true
                    ),
                    'email' => array(
                        'required' => true,
                        'min' => 4,
                        'max' => 64
                    )
                ));

                if($validation->passed()){
                    // Check email doesn't exist
                    $email_query = $queries->getWhere('users', array('email', '=', $_POST['email']));
                    if(count($email_query)){
                        if($email_query[0]->id != $user->data()->id){
                            $error = $language->get('user', 'email_already_exists');
                        }
                    }

                    if(!isset($error)) {
                        // Check password matches
                        $password = Input::get('password');
                        if ($user->checkCredentials($user->data()->username, $password, 'username')) {
                            try {
                                // Update email
                                $queries->update('users', $user->data()->id, array(
                                    'email' => Output::getClean($_POST['email'])
                                ));

                                Session::flash('settings_success', $language->get('user', 'email_changed_successfully'));
                                Redirect::to(URL::build('/user/settings'));
                                die();

                            } catch (Exception $e) {
                                die($e->getMessage());
                            }
                        } else {
                            // Invalid password
                            Session::flash('settings_error', $language->get('user', 'incorrect_password'));
                        }
                    }
                } else {
                    $error = '';
                    foreach($validation->errors() as $item){
                        if(strpos($item, 'is required') !== false){
                            // Empty field
                            if(strpos($item, 'password') !== false){
                                $error .= $language->get('user', 'password_required') . '<br />';
                            } else {
                                $error .= $language->get('user', 'email_required') . '<br />';
                            }
                        } else if(strpos($item, 'minimum') !== false){
                            // Field under 4 chars
                            $error .= $language->get('user', 'invalid_email') . '<br />';

                        } else if(strpos($item, 'maximum') !== false){
                            // Field over 64 chars
                            $error .= $language->get('user', 'invalid_email') . '<br />';

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

	$template->addCSSFiles(array(
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.sprites.css' => array(),
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
	));

	$template->addJSFiles(array(
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => array()
	));

	$template->addJSScript('$(\'.datepicker\').datepicker();');

	$cache->setCache('post_formatting');
	$formatting = $cache->retrieve('formatting');
	if($formatting == 'markdown'){
		$template->addJSFiles(array(
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
		));

		$template->addJSScript('
            $(document).ready(function() {
                var el = $("#inputSignature").emojioneArea({
                    pickerPosition: "bottom"
                });
            });
		');

	} else {
		$template->addJSFiles(array(
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
		));

		$template->addJSScript(Input::createTinyEditor($language, 'inputSignature'));
	}

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

	// Get templates
	$templates = array();
	$templates_query = $user->getUserTemplates();

	foreach($templates_query as $item){
		$templates[] = array(
			'id' => Output::getClean($item->id),
			'active' => $item->id === $user->data()->theme_id,
			'name' => Output::getClean($item->name)
		);
	}

	// Get custom fields
	$custom_fields = $queries->getWhere('profile_fields', array('id', '<>', 0));
	$user_custom_fields = $queries->getWhere('users_profile_fields', array('user_id', '=', $user->data()->id));
	
	$custom_fields_template = array(
		'nickname' => array(
			'disabled' => true
		)
	);
	
	if($user->hasPermission('usercp.nickname')){
		$custom_fields_template['nickname'] = array(
			'name' => $language->get('user', 'nickname'),
			'value' => Output::getClean($user->data()->nickname),
			'id' => 'nickname',
			'type' => 'text'
		);
	}
	
	if(count($custom_fields)){
		foreach($custom_fields as $field){
			// Check if its editable if not, next
			if($field->editable == false){
				continue;
			}
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

	if($user->hasPermission('usercp.signature')){
        // Get post formatting type (HTML or Markdown)
        $cache->setCache('post_formatting');
        $formatting = $cache->retrieve('formatting');

        if($formatting == 'markdown'){
            // Markdown
            require(ROOT_PATH . '/core/includes/markdown/tomarkdown/autoload.php');
            $converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => TRUE));

            $signature = $converter->convert(htmlspecialchars_decode($user->data()->signature));
            $signature = Output::getPurified($signature);

            $smarty->assign('MARKDOWN', TRUE);
            $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
        } else {
            $signature = Output::getPurified(htmlspecialchars_decode($user->data()->signature));
        }

        $smarty->assign(array(
            'SIGNATURE' => $language->get('user', 'signature'),
            'SIGNATURE_VALUE' => $signature
        ));
    }
	
	if($user->canPrivateProfile($user->data()->id)){
        $smarty->assign(array(
            'PRIVATE_PROFILE' => $language->get('user', 'private_profile'),
            'PRIVATE_PROFILE_ENABLED' => $user->isPrivateProfile($user->data()->id),
            'ENABLED' => $language->get('user', 'enabled'),
            'DISABLED' => $language->get('user', 'disabled')

        ));
    }

	// Language values
	$smarty->assign(array(
		'SETTINGS' => $language->get('user', 'profile_settings'),
		'ACTIVE_LANGUAGE' => $language->get('user', 'active_language'),
		'LANGUAGES' => $languages,
		'ACTIVE_TEMPLATE' => $language->get('user', 'active_template'),
		'TEMPLATES' => $templates,
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
        'CURRENT_EMAIL' => Output::getClean($user->data()->email),
        'CHANGE_EMAIL_ADDRESS' => $language->get('user', 'change_email_address'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
		'SUCCESS_TITLE' => $language->get('general', 'success'),
		'ERROR_TITLE' => $language->get('general', 'error')
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

	// Load modules + template
	Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

	require(ROOT_PATH . '/core/templates/cc_navbar.php');

	$page_load = microtime(true) - $start;
	define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	$template->onPageLoad();

	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	// Display template
	$template->displayTemplate('user/settings.tpl', $smarty);
}
