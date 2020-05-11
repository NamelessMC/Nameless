<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr7
 *
 *  License: MIT
 *
 *  Panel users page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.users.edit')){
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	Redirect::to(URL::build('/panel/users'));
	die();
}
$user_id = $_GET['id'];

$user_query = $queries->getWhere('users', array('id', '=', $user_id));
if(!count($user_query)){
	Redirect::to(URL::build('/panel/users'));
	die();
}
$user_query = $user_query[0];

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'users');
define('EDITING_USER', true);
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');
require_once(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(isset($_GET['action'])){
	if($_GET['action'] == 'validate'){
		// Validate the user
		if($user_query->active == 0){
			$queries->update('users', $user_query->id, array(
				'active' => 1,
				'reset_code' => ''
			));

			HookHandler::executeEvent('validateUser', array(
				'event' => 'validateUser',
				'user_id' => $user_query->id,
				'username' => Output::getClean($user_query->username),
				'uuid' => Output::getClean($user_query->uuid),
				'language' => $language
			));

			Session::flash('edit_user_success', $language->get('admin', 'user_validated_successfully'));
		}

	} else if($_GET['action'] == 'update_mcname'){
		require_once(ROOT_PATH . '/core/integration/uuid.php');
		$uuid = $user_query->uuid;

		$profile = ProfileUtils::getProfile($uuid);

		if($profile){
			$result = $profile->getUsername();

			if(!empty($result)){
				if($user_query->username == $user_query->nickname){
					$queries->update('users', $user_query->id, array(
						'username' => Output::getClean($result),
						'nickname' => Output::getClean($result)
					));
				} else {
					$queries->update('users', $user_query->id, array(
						'username' => Output::getClean($result)
					));
				}

				Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));

			}
		}

	} else if($_GET['action'] == 'update_uuid'){
		require_once(ROOT_PATH . '/core/integration/uuid.php');

		$profile = ProfileUtils::getProfile($user_query->username);

		if(!empty($profile)){
			$result = $profile->getProfileAsArray();

			if(isset($result['uuid']) && !empty($result['uuid'])){
				$queries->update('users', $user_query->id, array(
					'uuid' => Output::getClean($result['uuid'])
				));

				Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));

			}
		}
	}

	Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
	die();
}

if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		if(Input::get('action') === 'update'){
			// Update a user's settings
			$signature = Input::get('signature');
			$_POST['signature'] = strip_tags(Input::get('signature'));

			$validate = new Validate();

			$to_validation = array(
				'email' => array(
					'required' => true,
					'min' => 4,
					'max' => 50
				),
				'UUID' => array(
					'max' => 32
				),
				'signature' => array(
					'max' => 900
				),
				'ip' => array(
					'max' => 256
				),
				'title' => array(
					'max' => 64
				),
				'username' => array(
					'required' => true,
					'min' => 3,
					'max' => 20
				),
				'nickname' => array(
					'required' => true,
					'min' => 3,
					'max' => 20
				)
			);

			if($user_query->id != 1 && ($user_query->id != $user->data()->id || $user->hasPermission('admincp.groups.self'))){
				$to_validation['group'] = array(
					'required' => true
				);
				$group = Input::get('group');

			} else {
				$group = $user_query->group_id;
			}

			// Get secondary groups
			if(isset($_POST['secondary_groups']) && count($_POST['secondary_groups'])){
				$secondary_groups = json_encode($_POST['secondary_groups']);
			} else {
				$secondary_groups = '';
			}

			$validation = $validate->check($_POST, $to_validation);

			if($validation->passed()){
				try {
					// Signature from Markdown -> HTML if needed
					$cache->setCache('post_formatting');
					$formatting = $cache->retrieve('formatting');

					if($formatting == 'markdown'){
						$signature = Michelf\Markdown::defaultTransform($signature);
						$signature = Output::getClean($signature);
					} else {
						$signature = Output::getClean($signature);
					}

					$private_profile_active = $queries->getWhere('settings', array('name', '=', 'private_profile'));
					$private_profile_active = $private_profile_active[0]->value == 1;
					$private_profile = 0;

					if($private_profile_active){
						$private_profile = Input::get('privateProfile');
					}

					// Template
					$new_template = $queries->getWhere('templates', array('id', '=', Input::get('template')));

					if (count($new_template)) $new_template = $new_template[0]->id;
					else $new_template = $user_query->template_id;

					// Nicknames?
					$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
					$displaynames = $displaynames[0]->value;

					if($displaynames == 'true'){
						$username = Input::get('username');
						$nickname = Input::get('nickname');
					} else {
						$username = Input::get('username');
						$nickname = Input::get('username');
					}

					$queries->update('users', $user_query->id, array(
						'nickname' => Output::getClean($nickname),
						'email' => Output::getClean(Input::get('email')),
						'group_id' => $group,
						'username' => Output::getClean($username),
						'user_title' => Output::getClean(Input::get('title')),
						'uuid' => Output::getClean(Input::get('UUID')),
						'signature' => $signature,
						'secondary_groups' => $secondary_groups,
						'private_profile' => $private_profile,
						'theme_id' => $new_template
					));

					Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));
					Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
					die();

				} catch(Exception $e){
					$errors[] = $e->getMessage();
				}

			} else {
				foreach($validation->errors() as $error){
					if(strpos($error, 'is required') !== false){
						// x is required
						switch($error){
							case (strpos($error, 'nickname') !== false):
								$errors[] = $language->get('user', 'username_required');
								break;
							case (strpos($error, 'email') !== false):
								$errors[] = $language->get('user', 'email_required');
								break;
							case (strpos($error, 'username') !== false):
								$errors[] = $language->get('user', 'mcname_required');
								break;
							case (strpos($error, 'group') !== false):
								$errors[] = $language->get('admin', 'select_user_group');
								break;
						}

					} else if(strpos($error, 'minimum') !== false){
						// x must be a minimum of y characters long
						switch($error){
							case (strpos($error, 'nickname') !== false):
								$errors[] = $language->get('user', 'username_minimum_3');
								break;
							case (strpos($error, 'username') !== false):
								$errors[] = $language->get('user', 'mcname_minimum_3');
								break;
						}

					} else if(strpos($error, 'maximum') !== false){
						// x must be a maximum of y characters long
						switch($error){
							case (strpos($error, 'nickname') !== false):
								$errors[] = $language->get('user', 'username_maximum_20');
								break;
							case (strpos($error, 'username') !== false):
								$errors[] = $language->get('user', 'mcname_maximum_20');
								break;
							case (strpos($error, 'UUID') !== false):
								$errors[] = $language->get('admin', 'uuid_max_32');
								break;
							case (strpos($error, 'title') !== false):
								$errors[] = $language->get('admin', 'title_max_64');
								break;
						}

					}
				}
			}
		} else if(Input::get('action') == 'delete'){
			if($user_query->id > 1){
				HookHandler::executeEvent('deleteUser', array(
					'user_id' => $user_query->id,
					'username' => Output::getClean($user_query->username),
					'uuid' => Output::getClean($user_query->uuid),
					'email_address' => Output::getClean($user_query->email)
				));

				Session::flash('users_session', $language->get('admin', 'user_deleted'));
			}

			Redirect::to(URL::build('/panel/users'));
			die();
		}
	} else
		$errors[] = $language->get('general', 'invalid_token');
}

if(Session::exists('edit_user_success'))
	$success = Session::flash('edit_user_success');

if(isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

if($user_query->active == 0){
	$smarty->assign(array(
		'VALIDATE_USER' => $language->get('admin', 'validate_user'),
		'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=validate')
	));
}

if(defined('MINECRAFT') && MINECRAFT === true){
	$smarty->assign(array(
		'UPDATE_MINECRAFT_USERNAME' => $language->get('admin', 'update_mc_name'),
		'UPDATE_MINECRAFT_USERNAME_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=update_mcname'),
		'UPDATE_UUID' => $language->get('admin', 'update_uuid'),
		'UPDATE_UUID_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=update_uuid')
	));
}

if($user_query->id != 1 && !$user->canViewACP($user_query->id)){
	$smarty->assign(array(
		'DELETE_USER' => $language->get('admin', 'delete_user'),
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'CONFIRM_DELETE_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'confirm_user_deletion')),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no')
	));
}

if($user_query->id == 1 || ($user_query->id == $user->data()->id && !$user->hasPermission('admincp.groups.self'))){
	$smarty->assign(array(
		'CANT_EDIT_GROUP' => $language->get('admin', 'cant_modify_root_user')
	));
}

$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$displaynames = $displaynames[0]->value;

$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

$private_profile = $queries->getWhere('settings', array('name', '=', 'private_profile'));
$private_profile = $private_profile[0]->value;

$templates = array();
$templates_query = $queries->getWhere('templates', array('id', '<>', 0));

foreach($templates_query as $item){
	$templates[] = array(
		'id' => Output::getClean($item->id),
		'name' => Output::getClean($item->name),
		'active' => $item->id === $user_query->theme_id
	);
}

$groups = $queries->orderAll('groups', '`order`', 'ASC');

// HTML -> Markdown if necessary
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if($formatting == 'markdown'){
	require(ROOT_PATH . '/core/includes/markdown/tomarkdown/autoload.php');
	$converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

	$signature = $converter->convert(Output::getDecoded($user_query->signature));
	$signature = Output::getPurified($signature);

} else {
	$signature = Output::getPurified(Output::getDecoded($user_query->signature));

}

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
	'USERS' => $language->get('admin', 'users'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'EDITING_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'editing_user_x')),
	'BACK_LINK' => URL::build('/panel/users'),
	'BACK' => $language->get('general', 'back'),
	'ACTIONS' => $language->get('general', 'actions'),
	'USER_ID' => Output::getClean($user_query->id),
	'DISPLAYNAMES' => ($displaynames == 'true'),
	'USERNAME' => $language->get('user', 'username'),
	'USERNAME_VALUE' => Output::getClean($user_query->username),
	'NICKNAME' => $language->get('user', 'nickname'),
	'NICKNAME_VALUE' => Output::getClean($user_query->nickname),
	'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
	'EMAIL_ADDRESS_VALUE' => Output::getClean($user_query->email),
	'UUID_LINKING' => ($uuid_linking == '1'),
	'UUID' => $language->get('admin', 'minecraft_uuid'),
	'UUID_VALUE' => Output::getClean($user_query->uuid),
	'USER_TITLE' => $language->get('admin', 'title'),
	'USER_TITLE_VALUE' => Output::getClean($user_query->user_title),
	'PRIVATE_PROFILE' => $language->get('user', 'private_profile'),
	'PRIVATE_PROFILE_VALUE' => $user_query->private_profile,
	'PRIVATE_PROFILE_ENABLED' => ($private_profile == 1),
	'ENABLED' => $language->get('admin', 'enabled'),
	'DISABLED' => $language->get('admin', 'disabled'),
	'SIGNATURE' => $language->get('user', 'signature'),
	'SIGNATURE_VALUE' => $signature,
	'ALL_GROUPS' => $groups,
	'GROUP' => $language->get('admin', 'group'),
	'GROUP_ID' => $user_query->group_id,
	'SECONDARY_GROUPS' => $language->get('admin', 'secondary_groups'),
	'SECONDARY_GROUPS_INFO' => $language->get('admin', 'secondary_groups_info'),
	'SECONDARY_GROUPS_VALUE' => ((($user_secondary_groups = json_decode($user_query->secondary_groups, true)) == null) ? array() : $user_secondary_groups),
	'INFO' => $language->get('general', 'info'),
	'ACTIVE_TEMPLATE' => $language->get('user', 'active_template'),
	'TEMPLATES' => $templates
));

$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');
if($formatting == 'markdown'){
	$template->addJSFiles(array(
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
	));

	$template->addJSScript('
            $(document).ready(function() {
                var el = $("#InputSignature").emojioneArea({
                    pickerPosition: "bottom"
                });
            });
		');

} else {
	$template->addJSFiles(array(
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array(),
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/ckeditor/ckeditor.js' => array(),
		(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json' => array()
	));

	$template->addJSScript(Input::createEditor('InputSignature'));
}

$template->addCSSFiles(array(
	(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array(),
	(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
	(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emoji/css/emojione.sprites.css' => array(),
	(defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_edit.tpl', $smarty);
