<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel templates page
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
		if(!$user->hasPermission('admincp.styles.templates')){
			require_once(ROOT_PATH . '/404.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'layout');
define('PANEL_PAGE', 'template');
$page_title = $language->get('admin', 'templates');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['action'])){
	// Get all templates
	$templates = $queries->getWhere('templates', array('id', '<>', 0));

	// Get all active templates
	$active_templates = $queries->getWhere('templates', array('enabled', '=', 1));

	$current_template = $template;

	$templates_template = array();

	foreach($templates as $item){
		$template_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($item->name), 'template.php'));

		if(file_exists($template_path))
			require($template_path);
		else {
			$queries->delete('templates', array('id', '=', $item->id));
			continue;
		}

		$templates_template[] = array(
			'name' => Output::getClean($item->name),
			'version' => Output::getClean($template->getVersion()),
			'author' => $template->getAuthor(),
			'author_x' => str_replace('{x}', $template->getAuthor(), $language->get('admin', 'author_x')),
			'version_mismatch' => (($template->getNamelessVersion() != NAMELESS_VERSION) ? str_replace(array('{x}', '{y}'), array(Output::getClean($template->getNamelessVersion()), NAMELESS_VERSION), $language->get('admin', 'template_outdated')) : false),
			'enabled' => $item->enabled,
			'activate_link' => (($item->enabled) ? null : URL::build('/panel/core/templates/', 'action=activate&template=' . Output::getClean($item->id))),
			'delete_link' => ((!$user->hasPermission('admincp.styles.templates.edit') || $item->id == 1 || $item->enabled) ? null : URL::build('/panel/core/templates/', 'action=delete&template=' . Output::getClean($item->id))),
			'default' => $item->is_default,
			'deactivate_link' => (($item->enabled && count($active_templates) > 1 && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=deactivate&template=' . Output::getClean($item->id)) : null),
			'default_link' => (($item->enabled && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=make_default&template=' . Output::getClean($item->id)) : null),
			'edit_link' => ($user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($item->id)) : null)
		);

	}

	$template = $current_template;

	// Get templates from Nameless website
	$cache->setCache('all_templates');
	if($cache->isCached('all_templates')){
		$all_templates = $cache->retrieve('all_templates');

	} else {
		$all_templates = array();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/frontend_templates');

		$all_templates_query = curl_exec($ch);

		if(curl_error($ch)){
			$all_templates_error = curl_error($ch);
		}

		curl_close($ch);

		if(isset($all_templates_error)){
			$smarty->assign('WEBSITE_TEMPLATES_ERROR', $all_templates_error);

		} else {
			$all_templates_query = json_decode($all_templates_query);
			$timeago = new Timeago(TIMEZONE);

			foreach($all_templates_query as $item){
				$all_templates[] = array(
					'name' => Output::getClean($item->name),
					'description' => Output::getPurified($item->description),
					'description_short' => Util::truncate(Output::getPurified($item->description)),
					'author' => Output::getClean($item->author),
					'author_x' => str_replace('{x}', Output::getClean($item->author), $language->get('admin', 'author_x')),
					'contributors' => Output::getClean($item->contributors),
					'created' => $timeago->inWords(date('d M Y, H:i', $item->created), $language->getTimeLanguage()),
					'created_full' => date('d M Y, H:i', $item->created),
					'updated' => $timeago->inWords(date('d M Y, H:i', $item->updated), $language->getTimeLanguage()),
					'updated_full' => date('d M Y, H:i', $item->updated),
					'url' => Output::getClean($item->url),
					'latest_version' => Output::getClean($item->latest_version),
					'rating' => Output::getClean($item->rating),
					'downloads' => Output::getClean($item->downloads),
					'views' => Output::getClean($item->views),
					'rating_full' => str_replace('{x}', Output::getClean($item->rating * 2) . '/100', $language->get('admin', 'rating_x')),
					'downloads_full' => str_replace('{x}', Output::getClean($item->downloads), $language->get('admin', 'downloads_x')),
					'views_full' => str_replace('{x}', Output::getClean($item->views), $language->get('admin', 'views_x'))
				);
			}

			$cache->store('all_templates', $all_templates, 3600);
		}

	}

	if(count($all_templates)){
		if(count($all_templates) > 3){
			$rand_keys = array_rand($all_templates, 3);
			$all_templates = array($all_templates[$rand_keys[0]], $all_templates[$rand_keys[1]], $all_templates[$rand_keys[2]]);
		}
	}

	$smarty->assign(array(
		'WARNING' => $language->get('admin', 'warning'),
		'ACTIVATE' => $language->get('admin', 'activate'),
		'DEACTIVATE' => $language->get('admin', 'deactivate'),
		'DELETE' => $language->get('admin', 'delete'),
		'CONFIRM_DELETE_TEMPLATE' => $language->get('admin', 'confirm_delete_template'),
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'ACTIVE' => $language->get('admin', 'active'),
		'DEFAULT' => $language->get('admin', 'default'),
		'MAKE_DEFAULT' => $language->get('admin', 'make_default'),
		'EDIT' => $language->get('general', 'edit'),
		'TEMPLATE_LIST' => $templates_template,
		'INSTALL_TEMPLATE' => $language->get('admin', 'install'),
		'INSTALL_TEMPLATE_LINK' => URL::build('/panel/core/templates/', 'action=install'),
		'FIND_TEMPLATES' => $language->get('admin', 'find_templates'),
		'WEBSITE_TEMPLATES' => $all_templates,
		'VIEW_ALL_TEMPLATES' => $language->get('admin', 'view_all_templates'),
		'VIEW_ALL_TEMPLATES_LINK' => 'https://namelessmc.com/resources/category/2-namelessmc-v2-templates/',
		'UNABLE_TO_RETRIEVE_TEMPLATES' => $language->get('admin', 'unable_to_retrieve_templates'),
		'VIEW' => $language->get('general', 'view'),
		'TEMPLATE' => $language->get('admin', 'template'),
		'STATS' => $language->get('admin', 'stats'),
		'ACTIONS' => $language->get('general', 'actions')
	));

	$template_file = 'core/templates.tpl';

} else {
	switch($_GET['action']){
		case 'install':
			// Install new template
			// Scan template directory for new templates
			$directories = glob(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '*' , GLOB_ONLYDIR);
			foreach($directories as $directory){
				$folders = explode(DIRECTORY_SEPARATOR, $directory);

				// Is it already in the database?
				$exists = $queries->getWhere('templates', array('name', '=', htmlspecialchars($folders[count($folders) - 1])));
				if(!count($exists)){
					// No, add it now
					$queries->create('templates', array(
						'name' => htmlspecialchars($folders[count($folders) - 1])
					));
				}
			}

			Session::flash('admin_templates', $language->get('admin', 'templates_installed_successfully'));
			Redirect::to(URL::build('/panel/core/templates'));
			die();

			break;

		case 'activate':
			// Activate a template
			// Ensure it exists
			$template = $queries->getWhere('templates', array('id', '=', $_GET['template']));
			if(!count($template)){
				// Doesn't exist
				Redirect::to(URL::build('/panel/core/templates/'));
				die();
			}

			$template = $template[0]->id;

			// Activate the template
			$queries->update('templates', $template, array(
				'enabled' => 1
			));

			// Session
			Session::flash('admin_templates', $language->get('admin', 'template_activated'));
			Redirect::to(URL::build('/panel/core/templates/'));
			die();

			break;

		case 'deactivate':
			// Deactivate a template
			// Ensure it exists
			$template = $queries->getWhere('templates', array('id', '=', $_GET['template']));
			if(!count($template)){
				// Doesn't exist
				Redirect::to(URL::build('/panel/core/templates/'));
				die();
			}

			$template = $template[0]->id;

			// Deactivate the template
			$queries->update('templates', $template, array(
				'enabled' => 0
			));

			// Session
			Session::flash('admin_templates', $language->get('admin', 'template_deactivated'));
			Redirect::to(URL::build('/panel/core/templates'));
			die();

			break;

		case 'delete':
			if(!isset($_GET['template'])){
				Redirect::to('/panel/core/templates');
				die();
			}

			$item = $_GET['template'];

			try {
				// Ensure template is not default or active
				$template = $queries->getWhere('templates', array('id', '=', $item));
				if(count($template)){
					$template = $template[0];
					if($template->name == 'Default' || $template->id == 1 || $template->enabled == 1 || $template->is_default == 1){
						Redirect::to(URL::build('/panel/core/templates'));
						die();
					}

					$item = $template->name;
				} else {
					Redirect::to(URL::build('/panel/core/templates'));
					die();
				}

				if(!Util::recursiveRemoveDirectory(ROOT_PATH . '/custom/templates/' . $item))
					Session::flash('admin_templates_error', $language->get('admin', 'unable_to_delete_template'));
				else
					Session::flash('admin_templates', $language->get('admin', 'template_deleted_successfully'));

				// Delete from database
				$queries->delete('templates', array('name', '=', $item));
				Redirect::to(URL::build('/panel/core/templates'));
				die();

			} catch(Exception $e){
				Session::flash('admin_templates_error', $e->getMessage());
				Redirect::to(URL::build('/panel/core/templates'));
				die();
			}

			break;

		case 'make_default':
			// Make a template default
			// Ensure it exists
			$new_default = $queries->getWhere('templates', array('id', '=', $_GET['template']));
			if(!count($new_default)){
				// Doesn't exist
				Redirect::to(URL::build('/panel/core/templates/'));
				die();
			} else {
				$new_default_template = $new_default[0]->name;
				$new_default = $new_default[0]->id;
			}

			// Get current default template
			$current_default = $queries->getWhere('templates', array('is_default', '=', 1));
			if(count($current_default)){
				$current_default = $current_default[0]->id;
				// No longer default
				$queries->update('templates', $current_default, array(
					'is_default' => 0
				));
			}

			// Make selected template default
			$queries->update('templates', $new_default, array(
				'is_default' => 1
			));

			// Cache
			$cache->setCache('templatecache');
			$cache->store('default', $new_default_template);

			// Session
			Session::flash('admin_templates', str_replace('{x}', Output::getClean($new_default_template), $language->get('admin', 'default_template_set')));
			Redirect::to(URL::build('/panel/core/templates/'));
			die();

			break;

		case 'edit':
			// Editing template
			if(!$user->hasPermission('admincp.styles.templates.edit')){
				Redirect::to(URL::build('/panel/core/templates'));
				die();
			}
			// Get the template
			$template_query = $queries->getWhere('templates', array('id', '=', $_GET['template']));
			if(count($template_query)){
				$template_query = $template_query[0];
			} else {
				Redirect::to(URL::build('/panel/core/templates'));
				die();
			}

			if($_GET['template'] == 1){
				$smarty->assign('DEFAULT_TEMPLATE_WARNING', $language->get('admin', 'warning_editing_default_template'));
			}

			if(!isset($_GET['file']) && !isset($_GET['dir'])){
				// Get all files
				// Build path to template folder
				$template_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name)));
				$files = scandir($template_path);

				$template_files = array();
				$template_dirs = array();

				foreach($files as $file){
					if($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js')){
						if(!is_dir($template_path . DIRECTORY_SEPARATOR . $file))
							$template_files[] = array(
								'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&file=' . htmlspecialchars($file)),
								'name' => Output::getClean($file)
							);
						else
							$template_dirs[] = array(
								'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($file)),
								'name' => Output::getClean($file)
							);
					}
				}

				$smarty->assign(array(
					'BACK' => $language->get('general', 'back'),
					'BACK_LINK' => URL::build('/panel/core/templates/'),
					'TEMPLATE_FILES' => $template_files,
					'TEMPLATE_DIRS' => $template_dirs,
					'VIEW' => $language->get('general', 'view'),
					'EDIT' => $language->get('general', 'edit')
				));

				$template_file = 'core/templates_list_files.tpl';

			} else if(isset($_GET['dir']) && !isset($_GET['file'])){
				// List files in dir
				$realdir = realpath(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['dir']))));
				$dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

				if(!isset($dir) || !is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir)))){
					Redirect::to(URL::build('/panel/core/templates'));
					die();
				}

				$template_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir));
				$files = scandir($template_path);

				$template_files = array();
				$template_dirs = array();

				foreach($files as $file){
					if($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js')){
						if(!is_dir($template_path . DIRECTORY_SEPARATOR . $file))
							$template_files[] = array(
								'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($dir) . '&file=' . htmlspecialchars($file)),
								'name' => Output::getClean($file)
							);
						else
							$template_dirs[] = array(
								'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($dir) . DIRECTORY_SEPARATOR . htmlspecialchars($file)),
								'name' => Output::getClean($file)
							);
					}
				}

				// Get back link
				$dirs = explode('/', $_GET['dir']);
				if(count($dirs) > 1){
					unset($dirs[count($dirs) - 1]);
					$new_dir = implode('/', $dirs);
					$back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . $new_dir);
				} else {
					$back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id));
				}

				$smarty->assign(array(
					'BACK' => $language->get('general', 'back'),
					'BACK_LINK' => $back_link,
					'TEMPLATE_FILES' => $template_files,
					'TEMPLATE_DIRS' => $template_dirs,
					'VIEW' => $language->get('general', 'view'),
					'EDIT' => $language->get('general', 'edit')
				));

				$template_file = 'core/templates_list_files.tpl';

			} else if(isset($_GET['file'])){
				$file = basename(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['file']))));

				if(isset($_GET['dir'])){
					$realdir = realpath(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['dir']))));
					$dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

					if(!isset($dir) || !is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir)))){
						Redirect::to(URL::build('/panel/core/templates'));
						die();
					}

					$file_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir, $file));
				} else
					$file_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $file));

				if(!file_exists($file_path) || !(pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js')){
					Redirect::to(URL::build('/panel/core/templates'));
					die();
				}

				if(pathinfo($file, PATHINFO_EXTENSION) == 'tpl')
					$file_type = 'smarty';
				else if(pathinfo($file, PATHINFO_EXTENSION) == 'css')
					$file_type = 'css';
				else if(pathinfo($file, PATHINFO_EXTENSION) == 'js')
					$file_type = 'javascript';

				// Deal with input
				if(Input::exists()){
					if(Token::check(Input::get('token'))){
						// Valid token
						if(is_writable($file_path)){
							// Can write to template file
							// Write
							$file = fopen($file_path, 'w');
							fwrite($file, Input::get('code'));
							fclose($file);

							// Display session success message
							Session::flash('admin_templates', $language->get('admin', 'template_updated'));

							// Redirect to refresh page
							if(isset($_GET['dir']))
								Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&dir=' . Output::getClean($_GET['dir']) . '&file=' . Output::getClean($_GET['file'])));
							else
								Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&file=' . Output::getClean($_GET['file'])));
							die();

						} else {
							// No write permission
							$errors = array($language->get('admin', 'cant_write_to_template'));

						}

					} else {
						// Invalid token
						$errors = array($language->get('general', 'invalid_token'));

					}
				}

				if(isset($_GET['dir']))
					$cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($_GET['template']). '&dir=' . Output::getClean($_GET['dir']));
				else
					$cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($_GET['template']));

				$smarty->assign(array(
					'CANCEL' => $language->get('general', 'cancel'),
					'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
					'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
					'YES' => $language->get('general', 'yes'),
					'NO' => $language->get('general', 'no'),
					'CANCEL_LINK' => $cancel_link,
					'FILE_CONTENTS' => Output::getClean(file_get_contents($file_path)),
					'FILE_TYPE' => $file_type
				));

				$template_file = 'core/templates_edit.tpl';

			}

			$smarty->assign(array(
				'EDITING_TEMPLATE' => str_replace('{x}', Output::getClean($template_query->name), $language->get('admin', 'editing_template_x'))
			));

			break;

		default:
			Redirect::to(URL::build('/panel/core/templates'));
			die();
			break;
	}
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('admin_templates'))
	$success = Session::flash('admin_templates');

if(Session::exists('admin_templates_error'))
	$errors = array(Session::flash('admin_templates_error'));

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

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'LAYOUT' => $language->get('admin', 'layout'),
	'TEMPLATES' => $language->get('admin', 'templates'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);