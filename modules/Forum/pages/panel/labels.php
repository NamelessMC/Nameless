<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel forum labels page
 */

// Can the user view the panel?
if(!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'forum');
define('PANEL_PAGE', 'forum_labels');
$page_title = $forum_language->get('forum', 'labels');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['action'])){
	// Topic labels
	$topic_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
	$template_array = array();

	if(count($topic_labels)){
		foreach($topic_labels as $topic_label){
			$label_type = $queries->getWhere('forums_labels', array('id', '=', $topic_label->label));
			if(!count($label_type)) $label_type = 0;
			else $label_type = $label_type[0];

			// List of forums label is enabled in
			$enabled_forums = explode(',', $topic_label->fids);
			$forums_string = '';
			foreach($enabled_forums as $item){
				$forum_name = $queries->getWhere('forums', array('id', '=', $item));
				if(count($forum_name)) $forums_string .= Output::getClean($forum_name[0]->forum_title) . ', '; else $forums_string .= $forum_language->get('forum', 'no_forums');
			}
			$forums_string = rtrim($forums_string, ', ');

			$template_array[] = array(
				'name' => str_replace('{x}', Output::getClean(Output::getDecoded($topic_label->name)), Output::getPurified(Output::getDecoded($label_type->html))),
				'edit_link' => URL::build('/panel/forums/labels/', 'action=edit&lid=' . Output::getClean($topic_label->id)),
				'delete_link' => URL::build('/panel/forums/labels/', 'action=delete&lid=' . Output::getClean($topic_label->id)),
				'enabled_forums' => $forums_string
			);
		}
	}

	$smarty->assign(array(
		'LABEL_TYPES' => $forum_language->get('forum', 'label_types'),
		'LABEL_TYPES_LINK' => URL::build('/panel/forums/labels/', 'action=types'),
		'NEW_LABEL' => $forum_language->get('forum', 'new_label'),
		'NEW_LABEL_LINK' => URL::build('/panel/forums/labels/', 'action=new'),
		'ALL_LABELS' => $template_array,
		'EDIT' => $language->get('general', 'edit'),
		'DELETE' => $language->get('general', 'delete'),
		'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'NO_LABELS' => $forum_language->get('forum', 'no_labels_defined')
	));

	$template_file = 'forum/labels.tpl';

} else {
	switch($_GET['action']){
		case 'new':
			// Deal with input
			if(Input::exists()){
				// Check token
				if(Token::check()){
					// Valid token
					// Validate input
					$validate = new Validate();

					$validation = $validate->check($_POST, [
						'label_name' => [
							Validate::REQUIRED => true,
							Validate::MIN => 1,
							Validate::MAX => 32
                        ],
						'label_id' => [
							Validate::REQUIRED => true
                        ]
                    ])->message($forum_language->get('forum', 'label_creation_error'));

					if($validation->passed()){
						// Create string containing selected forum IDs
						$forum_string = '';
						if(isset($_POST['label_forums']) && count($_POST['label_forums'])){
							// Turn array of inputted forums into string of forums
							foreach($_POST['label_forums'] as $item){
								$forum_string .= $item . ',';
							}
						}

						$forum_string = rtrim($forum_string, ',');

						$group_string = '';
						if(isset($_POST['label_groups']) && count($_POST['label_groups'])){
							foreach($_POST['label_groups'] as $item){
								$group_string .= $item . ',';
							}
						}

						$group_string = rtrim($group_string, ',');

						try {
							$queries->create('forums_topic_labels', array(
								'fids' => $forum_string,
								'name' => Output::getClean(Input::get('label_name')),
								'label' => Input::get('label_id'),
								'gids' => $group_string
							));

							Session::flash('forum_labels', $forum_language->get('forum', 'label_creation_success'));
							Redirect::to(URL::build('/panel/forums/labels'));
							die();
						} catch (Exception $e) {
							$errors = array($e->getMessage());
						}

					} else {
						// Validation errors
						$errors = $validation->errors();
					}

				} else {
					// Invalid token
					$errors = array($language->get('general', 'invalid_token'));
				}
			}

			// Get a list of labels
			$labels = $queries->getWhere('forums_labels', array('id', '<>', 0));
			$template_array = array();

			if(count($labels)){
				foreach($labels as $label){
					$template_array[] = array(
						'id' => Output::getClean($label->id),
						'name' => str_replace('{x}', Output::getClean($label->name), Output::getPurified(Output::getDecoded($label->html)))
					);
				}
			}

			// Get a list of forums
			$forum_list = $queries->orderWhere('forums', 'parent <> 0', 'forum_order', 'ASC');
			$template_forums = array();

			if(count($forum_list)){
				foreach($forum_list as $item){
					$template_forums[] = array(
						'id' => Output::getClean($item->id),
						'name' => Output::getClean(Output::getDecoded($item->forum_title))
					);
				}
			}

			// Get a list of all groups
			$group_list = $queries->getWhere('groups', array('id', '<>', 0));
			$template_groups = array();

			if(count($group_list)){
				foreach($group_list as $item){
					$template_groups[] = array(
						'id' => Output::getClean($item->id),
						'name' => Output::getClean(Output::getDecoded($item->name))
					);
				}
			}

			$smarty->assign(array(
				'CREATING_LABEL' => $forum_language->get('forum', 'creating_label'),
				'CANCEL' => $language->get('general', 'cancel'),
				'CANCEL_LINK' => URL::build('/panel/forums/labels'),
				'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
				'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
				'YES' => $language->get('general', 'yes'),
				'NO' => $language->get('general', 'no'),
				'LABEL_NAME' => $forum_language->get('forum', 'label_name'),
				'LABEL_NAME_VALUE' => Output::getClean(Input::get('label_name')),
				'LABEL_TYPE' => $forum_language->get('forum', 'label_type'),
				'LABEL_TYPES' => $template_array,
				'LABEL_FORUMS' => $forum_language->get('forum', 'label_forums'),
				'ALL_FORUMS' => $template_forums,
				'LABEL_GROUPS' => $forum_language->get('forum', 'label_groups'),
				'ALL_GROUPS' => $template_groups
			));

			$template_file = 'forum/labels_new.tpl';

			break;

		case 'edit':
			// Editing a label
			if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
				// Check the label ID is valid
				Redirect::to(URL::build('/panel/forums/labels'));
				die();
			}

			// Does the label exist?
			$label = $queries->getWhere('forums_topic_labels', array('id', '=', $_GET['lid']));
			if(!count($label)){
				// No, it doesn't exist
				Redirect::to(URL::build('/panel/forums/labels'));
				die();
			} else {
				$label = $label[0];
			}

			// Deal with input
			if(Input::exists()){
				// Check token
				if(Token::check()){
					// Valid token
					// Validate input
					$validate = new Validate();

                    $validation = $validate->check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_id' => [
                            Validate::REQUIRED => true
                        ]
                    ])->message($forum_language->get('forum', 'label_creation_error'));

					if($validation->passed()){
						// Create string containing selected forum IDs
						$forum_string = '';
						if(isset($_POST['label_forums']) && count($_POST['label_forums'])){
							foreach($_POST['label_forums'] as $item){
								// Turn array of inputted forums into string of forums
								$forum_string .= $item . ',';
							}
						}

						$forum_string = rtrim($forum_string, ',');

						$group_string = '';
						if(isset($_POST['label_groups']) && count($_POST['label_groups'])){
							foreach($_POST['label_groups'] as $item){
								$group_string .= $item . ',';
							}
						}

						$group_string = rtrim($group_string, ',');

						try {
							$queries->update('forums_topic_labels', $label->id, array(
								'fids' => $forum_string,
								'name' => Output::getClean(Input::get('label_name')),
								'label' => Input::get('label_id'),
								'gids' => $group_string
							));

							Session::flash('forum_labels', $forum_language->get('forum', 'label_edit_success'));
							Redirect::to(URL::build('/panel/forums/labels', 'action=edit&lid=' . Output::getClean($label->id)));
							die();
						} catch (Exception $e) {
							$errors = array($e->getMessage());
						}

					} else {
						// Validation errors
						$errors = $validation->errors();
					}

				} else {
					// Invalid token
					$errors = array($language->get('general', 'invalid_token'));
				}
			}

			// Get a list of labels
			$labels = $queries->getWhere('forums_labels', array('id', '<>', 0));
			$template_array = array();

			if(count($labels)){
				foreach($labels as $item){
					$template_array[] = array(
						'id' => Output::getClean($item->id),
						'name' => str_replace('{x}', Output::getClean($item->name), Output::getPurified(Output::getDecoded($item->html))),
						'selected' => ($label->label == $item->id)
					);
				}
			}

			// Get a list of forums
			$forum_list = $queries->orderWhere('forums', 'parent <> 0', 'forum_order', 'ASC');
			$template_forums = array();

			// Get a list of forums in which the label is enabled
			$enabled_forums = explode(',', $label->fids);

			if(count($forum_list)){
				foreach($forum_list as $item){
					$template_forums[] = array(
						'id' => Output::getClean($item->id),
						'name' => Output::getClean(Output::getDecoded($item->forum_title)),
						'selected' => (in_array($item->id, $enabled_forums))
					);
				}
			}

			// Get a list of all groups
			$group_list = $queries->getWhere('groups', array('id', '<>', 0));
			$template_groups = array();

			// Get a list of groups which have access to the label
			$groups = explode(',', $label->gids);

			if(count($group_list)){
				foreach($group_list as $item){
					$template_groups[] = array(
						'id' => Output::getClean($item->id),
						'name' => Output::getClean(Output::getDecoded($item->name)),
						'selected' => (in_array($item->id, $groups))
					);
				}
			}

			$smarty->assign(array(
				'EDITING_LABEL' => $forum_language->get('forum', 'editing_label'),
				'CANCEL' => $language->get('general', 'cancel'),
				'CANCEL_LINK' => URL::build('/panel/forums/labels'),
				'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
				'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
				'YES' => $language->get('general', 'yes'),
				'NO' => $language->get('general', 'no'),
				'LABEL_NAME' => $forum_language->get('forum', 'label_name'),
				'LABEL_NAME_VALUE' => Output::getClean($label->name),
				'LABEL_TYPE' => $forum_language->get('forum', 'label_type'),
				'LABEL_TYPES' => $template_array,
				'LABEL_FORUMS' => $forum_language->get('forum', 'label_forums'),
				'ALL_FORUMS' => $template_forums,
				'LABEL_GROUPS' => $forum_language->get('forum', 'label_groups'),
				'ALL_GROUPS' => $template_groups
			));

			$template_file = 'forum/labels_edit.tpl';

			break;

		case 'delete':
			// Label deletion
			if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
				// Check the label ID is valid
				Redirect::to(URL::build('/panel/forums/labels'));
				die();
			}

			if (Token::check($_POST['token'])) {
                // Delete the label
                $queries->delete('forums_topic_labels', array('id', '=', $_GET['lid']));
                Session::flash('forum_labels', $forum_language->get('forum', 'label_deleted_successfully'));

            } else Session::flash('forum_labels_error', $language->get('general', 'invalid_token'));

			Redirect::to(URL::build('/panel/forums/labels'));
			die();

			break;

		case 'types':
			// List label types
			$labels = $queries->getWhere('forums_labels', array('id', '<>', 0));
			$template_array = array();

			if(count($labels)){
				foreach($labels as $label){
					$template_array[] = array(
						'name' => str_replace('{x}', Output::getClean(Output::getDecoded($label->name)), Output::getPurified(Output::getDecoded($label->html))),
						'edit_link' => URL::build('/panel/forums/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)),
						'delete_link' => URL::build('/panel/forums/labels/', 'action=delete_type&lid=' . Output::getClean($label->id)),
					);
				}
			}

			$smarty->assign(array(
				'LABEL_TYPES' => $forum_language->get('forum', 'label_types'),
				'LABELS_LINK' => URL::build('/panel/forums/labels'),
				'NEW_LABEL_TYPE' => $forum_language->get('forum', 'new_label_type'),
				'NEW_LABEL_TYPE_LINK' => URL::build('/panel/forums/labels/', 'action=new_type'),
				'ALL_LABEL_TYPES' => $template_array,
				'EDIT' => $language->get('general', 'edit'),
				'DELETE' => $language->get('general', 'delete'),
				'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
				'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
				'YES' => $language->get('general', 'yes'),
				'NO' => $language->get('general', 'no'),
				'NO_LABEL_TYPES' => $forum_language->get('forum', 'no_label_types_defined')
			));

			$template_file = 'forum/labels_types.tpl';

			break;

		case 'new_type':
			// Creating a label type
			// Deal with input
			if(Input::exists()){
				// Check token
				if(Token::check()){
					// Valid token
					// Validate input
					$validate = new Validate();

					$validation = $validate->check($_POST, [
						'label_name' => [
							Validate::REQUIRED => true,
							Validate::MIN => 1,
							Validate::MAX => 32
                        ],
						'label_html' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
							Validate::MAX => 1024
                        ]
                    ])->message($forum_language->get('forum', 'label_type_creation_error'));

					if($validation->passed()){
						try {
							$queries->create('forums_labels', array(
								'name' => Output::getClean(Input::get('label_name')),
								'html' => Input::get('label_html')
							));

							Session::flash('forum_labels', $forum_language->get('forum', 'label_type_creation_success'));
							Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
							die();

						} catch (Exception $e) {
							$errors = array($e->getMessage());
						}

					} else {
						// Validation errors
						$errors = $validation->errors();
					}

				} else {
					// Invalid token
					$errors = array($language->get('general', 'invalid_token'));
				}
			}


			$smarty->assign(array(
				'LABEL_TYPES' => $forum_language->get('forum', 'label_types'),
				'CREATING_LABEL_TYPE' => $forum_language->get('forum', 'creating_label_type'),
				'CANCEL' => $language->get('general', 'cancel'),
				'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
				'CANCEL_LINK' => URL::build('/panel/forums/labels/', 'action=types'),
				'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
				'YES' => $language->get('general', 'yes'),
				'NO' => $language->get('general', 'no'),
				'LABEL_TYPE_NAME' => $forum_language->get('forum', 'label_type_name'),
				'LABEL_TYPE_NAME_VALUE' => Output::getClean(Input::get('label_type_name')),
				'LABEL_TYPE_HTML' => $forum_language->get('forum', 'label_type_html'),
				'INFO' => $language->get('general', 'info'),
				'LABEL_TYPE_HTML_INFO' => $forum_language->get('forum', 'label_type_html_help'),
				'LABEL_TYPE_HTML_VALUE' => Output::getClean(Input::get('label_type_html'))
			));

			$template_file = 'forum/labels_types_new.tpl';

			break;

		case 'edit_type':
			// Editing a label type
			if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
				Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
				die();
			}

			// Does the label exist?
			$label = $queries->getWhere('forums_labels', array('id', '=', $_GET['lid']));
			if(!count($label)){
				// No, it doesn't exist
				Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
				die();
			} else {
				$label = $label[0];
			}

			// Deal with input
			if(Input::exists()){
				// Check token
				if(Token::check()){
					// Valid token
					// Validate input
					$validate = new Validate();

                    $validation = $validate->check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_html' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 1024
                        ]
                    ])->message($forum_language->get('forum', 'label_type_creation_error'));

					if($validation->passed()){
						try {
							$queries->update('forums_labels', $label->id, array(
								'name' => Output::getClean(Input::get('label_name')),
								'html' => Output::getDecoded(Input::get('label_html'))
							));

							Session::flash('forum_labels', $forum_language->get('forum', 'label_type_edit_success'));
							Redirect::to(URL::build('/panel/forums/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)));
							die();
						} catch (Exception $e) {
							$errors = array($e->getMessage());
						}

					} else {
						// Validation errors
						$errors = $validation->errors();
					}

				} else {
					// Invalid token
					$errors = array($language->get('general', 'invalid_token'));
				}
			}

			$smarty->assign(array(
				'LABEL_TYPES' => $forum_language->get('forum', 'label_types'),
				'EDITING_LABEL_TYPE' => $forum_language->get('forum', 'editing_label_type'),
				'CANCEL' => $language->get('general', 'cancel'),
				'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
				'CANCEL_LINK' => URL::build('/panel/forums/labels/', 'action=types'),
				'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
				'YES' => $language->get('general', 'yes'),
				'NO' => $language->get('general', 'no'),
				'LABEL_TYPE_NAME' => $forum_language->get('forum', 'label_type_name'),
				'LABEL_TYPE_NAME_VALUE' => Output::getClean($label->name),
				'LABEL_TYPE_HTML' => $forum_language->get('forum', 'label_type_html'),
				'INFO' => $language->get('general', 'info'),
				'LABEL_TYPE_HTML_INFO' => $forum_language->get('forum', 'label_type_html_help'),
				'LABEL_TYPE_HTML_VALUE' => Output::getClean($label->html)
			));

			$template_file = 'forum/labels_types_edit.tpl';

			break;

		case 'delete_type':
			// Label deletion
			if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
				// Check the label ID is valid
				Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
				die();
			}

			if (Token::check($_POST['token'])) {
                // Delete the label
                $queries->delete('forums_labels', array('id', '=', $_GET['lid']));
                Session::flash('forum_labels', $forum_language->get('forum', 'label_type_deleted_successfully'));

            } else Session::flash('forum_labels_error', $language->get('general', 'invalid_token'));

			Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
			die();

			break;

		default:
			Redirect::to(URL::build('/panel/forums/labels'));
			die();
			break;
	}

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('forum_labels'))
	$success = Session::flash('forum_labels');

if(Session::exists('forum_labels_error'))
	$errors = [Session::flash('forum_labels_error')];

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
	'FORUM' => $forum_language->get('forum', 'forum'),
	'LABELS' => $forum_language->get('forum', 'labels'),
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
