<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel page metadata page
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
		if(!$user->hasPermission('admincp.pages.metadata')){
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'pages');
define('PANEL_PAGE', 'page_metadata');
$page_title = $language->get('admin', 'page_metadata');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(!isset($_GET['id'])){
	$smarty->assign(array(
		'PAGE_TITLE' => $language->get('admin', 'page'),
		'PAGE_LIST' => $pages->returnPages(),
		'EDIT_LINK' => URL::build('/panel/core/metadata/', 'id={x}')
	));

	$template_file = 'core/metadata.tpl';

} else {
	$page = $pages->getPageById($_GET['id']);
	if(is_null($page)){
		Redirect::to(URL::build('/panel/core/metadata'));
		die();
	}

	$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));

	if(Input::exists()){
		$errors = array();

		if(Token::check(Input::get('token'))){
			if(isset($_POST['description'])){
				if(strlen($_POST['description']) > 500){
					$errors[] = $language->get('admin', 'description_max_500');
				} else {
					$description = $_POST['description'];
				}
			} else
				$description = null;

			if(isset($_POST['keywords']))
				$keywords = $_POST['keywords'];
			else
				$keywords = null;

			if(!count($errors)){
				if(count($page_metadata)){
					$page_id = $page_metadata[0]->id;

					$queries->update('page_descriptions', $page_id, array(
						'description' => $description,
						'tags' => $keywords
					));

				} else {
					$queries->create('page_descriptions', array(
						'page' => $page['key'],
						'description' => $description,
						'tags' => $keywords
					));
				}

				$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));

				$success = $language->get('admin', 'metadata_updated_successfully');

			}
		} else
			$errors[] = $language->get('general', 'invalid_token');
	}

	if(count($page_metadata)){
		$description = Output::getClean($page_metadata[0]->description);
		$tags = Output::getClean($page_metadata[0]->tags);
	} else {
		$description = '';
		$tags = '';
	}

	$smarty->assign(array(
		'BACK' => $language->get('general', 'back'),
		'BACK_LINK' => URL::build('/panel/core/metadata'),
		'EDITING_PAGE' => str_replace('{x}', Output::getClean($page['key']), $language->get('admin', 'editing_page_x')),
		'DESCRIPTION' => $language->get('admin', 'description'),
		'DESCRIPTION_VALUE' => $description,
		'KEYWORDS' => $language->get('admin', 'keywords'),
		'KEYWORDS_VALUE' => $tags
	));

	$template_file = 'core/metadata_edit.tpl';

}

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
	'PAGES' => $language->get('admin', 'pages'),
	'PAGE_METADATA' => $language->get('admin', 'page_metadata'),
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