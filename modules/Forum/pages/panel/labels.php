<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel forum labels page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'forum';
const PANEL_PAGE = 'forum_labels';
$page_title = $forum_language->get('forum', 'labels');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    $db = DB::getInstance();
    // Topic labels
    $topic_labels = $db->get('forums_topic_labels', ['id', '<>', 0])->results();
    $template_array = [];

    if (count($topic_labels)) {
        foreach ($topic_labels as $topic_label) {
            $label_type = $db->get('forums_labels', ['id', $topic_label->label])->results();
            if (!count($label_type)) {
                $label_type = 0;
            } else {
                $label_type = $label_type[0];
            }

            // List of forums label is enabled in
            $enabled_forums = explode(',', $topic_label->fids);
            $forums_string = '';
            foreach ($enabled_forums as $item) {
                $forum_name = $db->get('forums', ['id', $item])->results();
                if (count($forum_name)) {
                    $forums_string .= Output::getClean($forum_name[0]->forum_title) . ', ';
                } else {
                    $forums_string .= $forum_language->get('forum', 'no_forums');
                }
            }
            $forums_string = rtrim($forums_string, ', ');

            $template_array[] = [
                'name' => str_replace('{x}', Output::getClean($topic_label->name), Output::getPurified($label_type->html)),
                'edit_link' => URL::build('/panel/forums/labels/', 'action=edit&lid=' . Output::getClean($topic_label->id)),
                'delete_link' => URL::build('/panel/forums/labels/', 'action=delete&lid=' . Output::getClean($topic_label->id)),
                'enabled_forums' => $forums_string
            ];
        }
    }

    $smarty->assign([
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
    ]);

    $template_file = 'forum/labels.tpl';

} else {
    switch ($_GET['action']) {
        case 'new':
            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_id' => [
                            Validate::REQUIRED => true
                        ]
                    ])->message($forum_language->get('forum', 'label_creation_error'));

                    if ($validation->passed()) {
                        // Create string containing selected forum IDs
                        $forum_string = '';
                        if (isset($_POST['label_forums']) && count($_POST['label_forums'])) {
                            // Turn array of inputted forums into string of forums
                            foreach ($_POST['label_forums'] as $item) {
                                $forum_string .= $item . ',';
                            }
                        }

                        $forum_string = rtrim($forum_string, ',');

                        $group_string = '';
                        if (isset($_POST['label_groups']) && count($_POST['label_groups'])) {
                            foreach ($_POST['label_groups'] as $item) {
                                $group_string .= $item . ',';
                            }
                        }

                        $group_string = rtrim($group_string, ',');

                        try {
                            DB::getInstance()->insert('forums_topic_labels', [
                                'fids' => $forum_string,
                                'name' => Output::getClean(Input::get('label_name')),
                                'label' => Input::get('label_id'),
                                'gids' => $group_string
                            ]);

                            Session::flash('forum_labels', $forum_language->get('forum', 'label_creation_success'));
                            Redirect::to(URL::build('/panel/forums/labels'));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get a list of labels
            $labels = DB::getInstance()->get('forums_labels', ['id', '<>', 0])->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $label) {
                    $template_array[] = [
                        'id' => Output::getClean($label->id),
                        'name' => str_replace('{x}', Output::getClean($label->name), Output::getPurified($label->html))
                    ];
                }
            }

            // Get a list of forums
            $forum_list = DB::getInstance()->orderWhere('forums', 'parent <> 0', 'forum_order', 'ASC')->results();
            $template_forums = [];

            if (count($forum_list)) {
                foreach ($forum_list as $item) {
                    $template_forums[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->forum_title)
                    ];
                }
            }

            // Get a list of all groups
            $template_groups = [];

            foreach (Group::all() as $item) {
                $template_groups[] = [
                    'id' => Output::getClean($item->id),
                    'name' => Output::getClean($item->name)
                ];
            }

            $smarty->assign([
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
            ]);

            $template_file = 'forum/labels_new.tpl';

            break;

        case 'edit':
            // Editing a label
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/forums/labels'));
            }

            // Does the label exist?
            $label = DB::getInstance()->get('forums_topic_labels', ['id', $_GET['lid']])->results();
            if (!count($label)) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/forums/labels'));
            }

            $label = $label[0];

            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_id' => [
                            Validate::REQUIRED => true
                        ]
                    ])->message($forum_language->get('forum', 'label_creation_error'));

                    if ($validation->passed()) {
                        // Create string containing selected forum IDs
                        $forum_string = '';
                        if (isset($_POST['label_forums']) && count($_POST['label_forums'])) {
                            foreach ($_POST['label_forums'] as $item) {
                                // Turn array of inputted forums into string of forums
                                $forum_string .= $item . ',';
                            }
                        }

                        $forum_string = rtrim($forum_string, ',');

                        $group_string = '';
                        if (isset($_POST['label_groups']) && count($_POST['label_groups'])) {
                            foreach ($_POST['label_groups'] as $item) {
                                $group_string .= $item . ',';
                            }
                        }

                        $group_string = rtrim($group_string, ',');

                        try {
                            DB::getInstance()->update('forums_topic_labels', $label->id, [
                                'fids' => $forum_string,
                                'name' => Output::getClean(Input::get('label_name')),
                                'label' => Input::get('label_id'),
                                'gids' => $group_string
                            ]);

                            Session::flash('forum_labels', $forum_language->get('forum', 'label_edit_success'));
                            Redirect::to(URL::build('/panel/forums/labels', 'action=edit&lid=' . Output::getClean($label->id)));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get a list of labels
            $labels = DB::getInstance()->get('forums_labels', ['id', '<>', 0])->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $item) {
                    $template_array[] = [
                        'id' => Output::getClean($item->id),
                        'name' => str_replace('{x}', Output::getClean($item->name), Output::getPurified($item->html)),
                        'selected' => ($label->label == $item->id)
                    ];
                }
            }

            // Get a list of forums
            $forum_list = DB::getInstance()->orderWhere('forums', 'parent <> 0', 'forum_order', 'ASC')->results();
            $template_forums = [];

            // Get a list of forums in which the label is enabled
            $enabled_forums = explode(',', $label->fids);

            if (count($forum_list)) {
                foreach ($forum_list as $item) {
                    $template_forums[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->forum_title),
                        'selected' => (in_array($item->id, $enabled_forums))
                    ];
                }
            }

            // Get a list of all groups
            $template_groups = [];

            // Get a list of groups which have access to the label
            $groups = explode(',', $label->gids);

            foreach (Group::all() as $item) {
                $template_groups[] = [
                    'id' => Output::getClean($item->id),
                    'name' => Output::getClean($item->name),
                    'selected' => (in_array($item->id, $groups))
                ];
            }

            $smarty->assign([
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
            ]);

            $template_file = 'forum/labels_edit.tpl';

            break;

        case 'delete':
            // Label deletion
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/forums/labels'));
            }

            if (Token::check($_POST['token'])) {
                // Delete the label
                DB::getInstance()->delete('forums_topic_labels', ['id', $_GET['lid']]);
                Session::flash('forum_labels', $forum_language->get('forum', 'label_deleted_successfully'));

            } else {
                Session::flash('forum_labels_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/forums/labels'));

        case 'types':
            // List label types
            // $labels = DB::getInstance()->get('forums_labels', ['id', '<>', 0])->results();
            $labels = DB::getInstance()->query(
                "SELECT `nl2_forums_labels`.*, (SELECT COUNT(id) FROM nl2_forums_topic_labels WHERE nl2_forums_labels.id = nl2_forums_topic_labels.id) as count FROM `nl2_forums_labels`"
            )->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $label) {
                    $template_array[] = [
                        'name' => str_replace('{x}', Output::getClean($label->name), Output::getPurified($label->html)),
                        'edit_link' => URL::build('/panel/forums/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)),
                        'delete_link' => URL::build('/panel/forums/labels/', 'action=delete_type&lid=' . Output::getClean($label->id)),
                        'usages' => (int) $label->count,
                    ];
                }
            }

            $smarty->assign([
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
            ]);

            $template_file = 'forum/labels_types.tpl';

            break;

        case 'new_type':
            // Creating a label type
            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
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

                    if ($validation->passed()) {
                        try {
                            DB::getInstance()->insert('forums_labels', [
                                'name' => Output::getClean(Input::get('label_name')),
                                'html' => Input::get('label_html')
                            ]);

                            Session::flash('forum_labels', $forum_language->get('forum', 'label_type_creation_success'));
                            Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));

                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }


            $smarty->assign([
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
            ]);

            $template_file = 'forum/labels_types_new.tpl';

            break;

        case 'edit_type':
            // Editing a label type
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
            }

            // Does the label exist?
            $label = DB::getInstance()->get('forums_labels', ['id', $_GET['lid']])->results();
            if (!count($label)) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
            }

            $label = $label[0];

            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
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

                    if ($validation->passed()) {
                        try {
                            DB::getInstance()->update('forums_labels', $label->id, [
                                'name' => Output::getClean(Input::get('label_name')),
                                'html' => Input::get('label_html')
                            ]);

                            Session::flash('forum_labels', $forum_language->get('forum', 'label_type_edit_success'));
                            Redirect::to(URL::build('/panel/forums/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            $smarty->assign([
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
            ]);

            $template_file = 'forum/labels_types_edit.tpl';

            break;

        case 'delete_type':
            // Label deletion
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));
            }

            if (Token::check($_POST['token'])) {
                // Make sure label type is not in use
                $count = DB::getInstance()->query('SELECT COUNT(id) AS count FROM nl2_forums_topic_labels WHERE nl2_forums_topic_labels.label = ?', [$_GET['lid']])->first()->count;

                if ($count < 1) {
                    // Delete the label
                    DB::getInstance()->delete('forums_labels', ['id', $_GET['lid']]);
                    Session::flash('forum_labels', $forum_language->get('forum', 'label_type_deleted_successfully'));
                } else {
                    Session::flash('forum_labels_error', $forum_language->get('forum', 'label_type_in_use'));
                }

            } else {
                Session::flash('forum_labels_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/forums/labels/', 'action=types'));

        default:
            Redirect::to(URL::build('/panel/forums/labels'));
    }

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('forum_labels')) {
    $success = Session::flash('forum_labels');
}

if (Session::exists('forum_labels_error')) {
    $errors = [Session::flash('forum_labels_error')];
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'FORUM' => $forum_language->get('forum', 'forum'),
    'LABELS' => $forum_language->get('forum', 'labels'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
