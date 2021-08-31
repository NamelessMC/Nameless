<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel forums page
 */

// Can the user view the panel?
if(!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'forum');
define('PANEL_PAGE', 'forums');
$page_title = $forum_language->get('forum', 'forums');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action']) && !isset($_GET['forum'])) {
    $forums = $queries->orderAll('forums', 'forum_order', 'ASC');
    $template_array = array();

    if (count($forums)) {
        $i = 1;
        $count = count($forums);
        foreach ($forums as $item) {
            if ($item->parent > 0) {
                $parent_forum_query = $queries->getWhere('forums', array('id', '=', $item->parent));
                if (count($parent_forum_query)) {
                    $parent_forum_count = 1;
                    $parent_forum = str_replace('{x}', Output::getClean(Output::getDecoded($parent_forum_query[0]->forum_title)), $forum_language->get('forum', 'parent_forum_x'));
                    $id = $parent_forum_query[0]->parent;

                    while ($parent_forum_count < 100 && $id > 0) {
                        $parent_forum_query = $queries->getWhere('forums', array('id', '=', $parent_forum_query[0]->parent));
                        $id = $parent_forum_query[0]->parent;
                        $parent_forum_count++;
                    }
                } else {
                    $parent_forum = null;
                    $parent_forum_count = 0;
                }
            } else
                $parent_forum_count = 0;

            $template_array[] = array(
                'edit_link' => URL::build('/panel/forums/', 'forum=' . Output::getClean($item->id)),
                'delete_link' => URL::build('/panel/forums/', 'action=delete&fid=' . Output::getClean($item->id)),
                'up_link' => ($i > 1 ? URL::build('/panel/forums/', 'action=order&dir=up&fid=' . Output::getClean($item->id)) : null),
                'down_link' => ($i < $count ? URL::build('/panel/forums/', 'action=order&dir=down&fid=' . Output::getClean($item->id)) : null),
                'title' => Output::getClean(Output::getDecoded($item->forum_title)),
                'description' => Output::getPurified(Output::getDecoded($item->forum_description)),
                'id' => Output::getClean($item->id),
                'parent_forum' => (($item->parent > 0) ? $parent_forum : null),
                'parent_forum_count' => $parent_forum_count
            );
            $i++;
        }
    }

    $forum_reactions = $queries->getWhere('settings', array('name', '=', 'forum_reactions'));
    $forum_reactions = $forum_reactions[0]->value;

    $smarty->assign(array(
        'NEW_FORUM' => $forum_language->get('forum', 'new_forum'),
        'NEW_FORUM_LINK' => URL::build('/panel/forums/', 'action=new'),
        'FORUMS_ARRAY' => $template_array,
        'NO_FORUMS' => $forum_language->get('forum', 'no_forums'),
        'REORDER_DRAG_URL' => URL::build('/panel/forums')
    ));

    $template_file = 'forum/forums.tpl';
} else if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'new':
            if (!isset($_GET['step'])) {
                // Step 1
                if (Input::exists()) {
                    $errors = array();

                    if (Token::check()) {
                        // Validate input
                        $validate = new Validate();
                        $validation = $validate->check($_POST, [
                            'forumname' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 150
                            ],
                            'forumdesc' => [
                                Validate::MAX => 255
                            ],
                            'forum_icon' => [
                                Validate::MAX => 256
                            ]
                        ])->messages([
                            'forumname' => [
                                Validate::REQUIRED => $forum_language->get('forum', 'input_forum_title'),
                                Validate::MIN => $forum_language->get('forum', 'forum_name_minimum'),
                                Validate::MAX => $forum_language->get('forum', 'forum_name_maximum')
                            ],
                            'forumdesc' => $forum_language->get('forum', 'forum_description_maximum'),
                            'forum_icon' => $forum_language->get('forum', 'forum_icon_maximum')
                        ]);

                        if ($validation->passed()) {
                            // Create the forum
                            try {
                                $description = Input::get('forumdesc');

                                $last_forum_order = $queries->orderAll('forums', 'forum_order', 'DESC');
                                if (count($last_forum_order)) $last_forum_order = $last_forum_order[0]->forum_order;
                                else $last_forum_order = 0;

                                $queries->create('forums', array(
                                    'forum_title' => Output::getClean(Input::get('forumname')),
                                    'forum_description' => Output::getClean($description),
                                    'forum_order' => $last_forum_order + 1,
                                    'forum_type' => Output::getClean(Input::get('forum_type')),
                                    'icon' => Output::getClean(Input::get('forum_icon'))
                                ));

                                $forum_id = $queries->getLastId();

                                Redirect::to(URL::build('/panel/forums/', 'action=new&step=2&forum=' . $forum_id));
                                die();
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors = $validation->errors();
                        }
                    } else {
                        // Invalid token
                        $errors[] = $language->get('general', 'invalid_token');
                    }
                }

                $smarty->assign(array(
                    'FORUM_TYPE' => $forum_language->get('forum', 'forum_type'),
                    'FORUM_TYPE_FORUM' => $forum_language->get('forum', 'forum_type_forum'),
                    'FORUM_TYPE_CATEGORY' => $forum_language->get('forum', 'forum_type_category'),
                    'FORUM_NAME' => $forum_language->get('forum', 'forum_name'),
                    'FORUM_NAME_VALUE' => Output::getClean(Input::get('forumname')),
                    'FORUM_DESCRIPTION' => $forum_language->get('forum', 'forum_description'),
                    'FORUM_DESCRIPTION_VALUE' => Output::getClean(Input::get('forumdesc')),
                    'FORUM_ICON' => $forum_language->get('forum', 'forum_icon'),
                    'FORUM_ICON_VALUE' => Output::getClean(Input::get('forum_icon'))
                ));

                $template_file = 'forum/forums_new_step_1.tpl';
            } else {
                // Parent category, for type forum only
                if (!isset($_GET['forum']) || !is_numeric($_GET['forum'])) {
                    Redirect::to(URL::build('/panel/forums'));
                    die();
                }

                // Get forum from database
                $forum = $queries->getWhere('forums', array('id', '=', $_GET['forum']));
                if (!count($forum)) {
                    Redirect::to(URL::build('/panel/forums'));
                    die();
                } else $forum = $forum[0];

                // Forums only
                if ($forum->forum_type == 'category') {
                    Redirect::to(URL::build('/panel/forums/', 'forum=' . $forum->id));
                    die();
                }

                // Deal with input
                if (Input::exists()) {
                    $errors = array();

                    if (Token::check()) {
                        try {
                            if (isset($_POST['redirect']) && $_POST['redirect'] == 1) {
                                $redirect = 1;
                                if (isset($_POST['redirect_url']) && strlen($_POST['redirect_url']) > 0 && strlen($_POST['redirect_url']) <= 512) {
                                    $redirect_url = Output::getClean($_POST['redirect_url']);
                                } else {
                                    $redirect_error = true;
                                }
                            } else {
                                $redirect = 0;
                                $redirect_url = null;
                            }

                            if (isset($_POST['hooks']) && count($_POST['hooks'])) $hooks = json_encode($_POST['hooks']);
                            else $hooks = null;

                            if (!isset($redirect_error)) {
                                if (isset($_POST['parent']))
                                    $parent = $_POST['parent'];
                                else
                                    $parent = 0;

                                $queries->update('forums', $forum->id, array(
                                    'parent' => $parent,
                                    'news' => Input::get('news_forum'),
                                    'redirect_forum' => $redirect,
                                    'redirect_url' => $redirect_url,
                                    'hooks' => $hooks
                                ));

                                Redirect::to(URL::build('/panel/forums/', 'forum=' . $forum->id));
                                die();
                            } else {
                                $errors[] = $forum_language->get('forum', 'invalid_redirect_url');
                            }
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    } else {
                        $errors[] = $language->get('general', 'invalid_token');
                    }
                }

                // Get a list of forums
                $forums = $queries->getWhere('forums', array('id', '<>', $forum->id));
                $template_array = array();

                if (count($forums)) {
                    foreach ($forums as $item) {
                        $template_array[] = array(
                            'id' => Output::getClean($item->id),
                            'name' => Output::getClean(Output::getDecoded($item->forum_title))
                        );
                    }
                }
                $hooks_query = $queries->orderAll('hooks', 'id', 'ASC');
                $hooks_array = array();
                if (count($hooks_query)) {
                    foreach ($hooks_query as $hook) {
                        if (in_array('newTopic', json_decode($hook->events))) {
                            $hooks_array[] = array(
                                'id' => $hook->id,
                                'name' => Output::getClean($hook->name),
                            );
                        }
                    }
                }
                $smarty->assign(array(
                    'SELECT_PARENT_FORUM' => $forum_language->get('forum', 'select_a_parent_forum'),
                    'PARENT_FORUMS' => $template_array,
                    'DISPLAY_TOPICS_AS_NEWS' => $forum_language->get('forum', 'display_topics_as_news'),
                    'REDIRECT_FORUM' => $forum_language->get('forum', 'redirect_forum'),
                    'REDIRECT_URL' => $forum_language->get('forum', 'redirect_url'),
                    'REDIRECT_URL_VALUE' => Output::getClean(Input::get('redirect_url')),
                    'INCLUDE_IN_HOOK' => $forum_language->get('forum', 'include_in_hook'),
                    'HOOKS_ARRAY' => $hooks_array,
                    'INFO' => $language->get('general', 'info'),
                    'HOOK_SELECT_INFO' => $language->get('admin', 'hook_select_info')
                ));

                $template_file = 'forum/forums_new_step_2.tpl';
            }

            $smarty->assign(array(
                'CREATING_FORUM' => $forum_language->get('forum', 'creating_forum'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/forums'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel')
            ));

            break;

        case 'order':
            if (!isset($_GET['dir'])) {
                echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/panel/forums') . '">' . $language->get('general', 'back') . '</a>';
                die();
            }
            if ($_GET['dir'] == 'up' || $_GET['dir'] == 'down') {
                if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
                    echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/panel/forums') . '">' . $language->get('general', 'back') . '</a>';
                    die();
                }

                if (!Token::check($_POST['token'])) {
                    Session::flash('admin_forums_error', $language->get('general', 'invalid_token'));
                    Redirect::to('/panel/forums');
                    die();
                }

                $dir = $_GET['dir'];

                $forum_id = $queries->getWhere('forums', array('id', '=', $_GET['fid']));
                $forum_id = $forum_id[0]->id;

                $forum_order = $queries->getWhere('forums', array('id', '=', $_GET['fid']));
                $forum_order = $forum_order[0]->forum_order;

                $previous_forums = $queries->orderAll('forums', 'forum_order', 'ASC');

                if ($dir == 'up') {
                    $n = 0;
                    foreach ($previous_forums as $previous_forum) {
                        if ($previous_forum->id == $_GET['fid']) {
                            $previous_fid = $previous_forums[$n - 1]->id;
                            $previous_f_order = $previous_forums[$n - 1]->forum_order;
                            break;
                        }
                        $n++;
                    }

                    try {
                        if (isset($previous_fid) && isset($previous_f_order)) {
                            $queries->update('forums', $forum_id, array(
                                'forum_order' => $previous_f_order
                            ));
                            $queries->update('forums', $previous_fid, array(
                                'forum_order' => $previous_f_order + 1
                            ));
                        }
                    } catch (Exception $e) {
                        $errors = array($e->getMessage());
                    }

                    Redirect::to(URL::build('/panel/forums'));
                    die();
                } else if ($dir == 'down') {
                    $n = 0;
                    foreach ($previous_forums as $previous_forum) {
                        if ($previous_forum->id == $_GET['fid']) {
                            $previous_fid = $previous_forums[$n + 1]->id;
                            $previous_f_order = $previous_forums[$n + 1]->forum_order;
                            break;
                        }
                        $n++;
                    }
                    try {
                        if (isset($previous_fid) && isset($previous_f_order)) {
                            $queries->update('forums', $forum_id, array(
                                'forum_order' => $previous_f_order
                            ));
                            $queries->update('forums', $previous_fid, array(
                                'forum_order' => $previous_f_order - 1
                            ));
                        }
                    } catch (Exception $e) {
                        $errors = array($e->getMessage());
                    }

                    Redirect::to(URL::build('/panel/forums'));
                    die();
                }
            } else if ($_GET['dir'] == 'drag') {
                // Get forums
                if (isset($_GET['forums'])) {
                    $forums = json_decode($_GET['forums'])->forums;

                    $i = 0;
                    foreach ($forums as $item) {
                        $queries->update('forums', $item, array(
                            'forum_order' => $i
                        ));

                        $i++;
                    }
                }

                die('Complete');
            } else {
                echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/panel/forums') . '">' . $language->get('general', 'back') . '</a>';
                die();
            }
            break;

        case 'delete':
            if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
                Redirect::to(URL::build('/panel/forums'));
                die();
            }

            // Ensure forum exists
            $forum = $queries->getWhere('forums', array('id', '=', $_GET['fid']));
            if (!count($forum)) {
                Redirect::to(URL::build('/panel/forums'));
                die();
            }
            $forum = $forum[0];

            if (Input::exists()) {
                if (Token::check()) {
                    if (Input::get('confirm') === 'true') {
                        $forum_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET['fid'])); // Get permissions to be deleted
                        if (Input::get('move_forum') === 'none') {
                            $posts = $queries->getWhere('posts', array('forum_id', '=', $_GET['fid']));
                            $topics = $queries->getWhere('topics', array('forum_id', '=', $_GET['fid']));

                            foreach ($posts as $post) {
                                $queries->delete('posts', array('id', '=', $post->id));
                            }
                            foreach ($topics as $topic) {
                                $queries->delete('topics', array('id', '=', $topic->id));
                            }

                            $queries->delete('forums', array('id', '=', $_GET["fid"]));

                            // Forum perm deletion
                            foreach ($forum_perms as $perm) {
                                $queries->delete('forums_permissions', array('id', '=', $perm->id));
                            }

                            Session::flash('admin_forums', $forum_language->get('forum', 'forum_deleted_successfully'));
                            Redirect::to(URL::build('/panel/forums'));
                            die();

                        } else {
                            $new_forum = Input::get('move_forum');
                            $posts = $queries->getWhere('posts', array('forum_id', '=', $_GET['fid']));
                            $topics = $queries->getWhere('topics', array('forum_id', '=', $_GET['fid']));

                            foreach ($posts as $post) {
                                $queries->update('posts', $post->id, array(
                                    'forum_id' => $new_forum
                                ));
                            }
                            foreach ($topics as $topic) {
                                $queries->update('topics', $topic->id, array(
                                    'forum_id' => $new_forum
                                ));
                            }

                            $queries->delete('forums', array('id', '=', $_GET["fid"]));

                            // Forum perm deletion
                            foreach ($forum_perms as $perm) {
                                $queries->delete('forums_permissions', array('id', '=', $perm->id));
                            }

                            Session::flash('admin_forums', $forum_language->get('forum', 'forum_deleted_successfully'));
                            Redirect::to(URL::build('/panel/forums'));
                            die();
                        }
                    }
                } else {
                    $errors = array($language->get('general', 'invalid_token'));
                }
            }

            $other_forums = $queries->orderWhere('forums', 'parent > 0', 'forum_order', 'ASC');
            $template_array = array();
            foreach ($other_forums as $item) {
                if ($item->id == $forum->id)
                    continue;

                $template_array[] = array(
                    'id' => Output::getClean($item->id),
                    'name' => Output::getClean(Output::getDecoded($item->forum_title))
                );
            }

            $smarty->assign(array(
                'DELETE_FORUM' => $forum_language->get('forum', 'delete_forum'),
                'MOVE_TOPICS_AND_POSTS_TO' => $forum_language->get('forum', 'move_topics_and_posts_to'),
                'DELETE_TOPICS_AND_POSTS' => $forum_language->get('forum', 'delete_topics_and_posts'),
                'OTHER_FORUMS' => $template_array
            ));

            $template_file = 'forum/forums_delete.tpl';

            break;

        default:
            Redirect::to(URL::build('/panel/forums'));
            die();
            break;
    }
} else if (isset($_GET['forum'])) {
    // Editing forum
    if (!is_numeric($_GET['forum'])) {
        die();
    } else {
        $forum = $queries->getWhere('forums', array('id', '=', $_GET['forum']));
    }

    if (!count($forum)) {
        Redirect::to(URL::build('/panel/forums'));
        die();
    }

    $available_forums = $queries->orderWhere('forums', 'id > 0', 'forum_order', 'ASC'); // Get a list of all forums which can be chosen as a parent
    $groups = $queries->getWhere('groups', array('id', '<>', '0')); // Get a list of all groups

    if (Input::exists()) {
        $errors = array();

        if (Token::check()) {
            if (Input::get('action') == 'update') {
                $validate = new Validate();
                $validation = $validate->check($_POST, [
                    'title' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 150
                    ],
                    'description' => [
                        Validate::MAX => 255
                    ],
                    'icon' => [
                        Validate::MAX => 256
                    ]
                ])->messages([
                    'title' => [
                        Validate::REQUIRED => $forum_language->get('forum', 'input_forum_title'),
                        Validate::MIN => $forum_language->get('forum', 'forum_name_minimum'),
                        Validate::MAX => $forum_language->get('forum', 'forum_name_maximum')
                    ],
                    'description' => $forum_language->get('forum', 'forum_description_maximum'),
                    'icon' => $forum_language->get('forum', 'forum_icon_maximum')
                ]);

                if ($validation->passed()) {
                    try {
                        if (isset($_POST['redirect']) && $_POST['redirect'] == 1) {
                            $redirect = 1;
                            if (isset($_POST['redirect_url']) && strlen($_POST['redirect_url']) > 0 && strlen($_POST['redirect_url']) <= 512) {
                                $redirect_url = Output::getClean($_POST['redirect_url']);
                            } else {
                                $redirect = 0;
                                $redirect_url = null;
                                $redirect_error = true;
                            }
                        } else {
                            $redirect = 0;
                            $redirect_url = null;
                        }

                        if (isset($_POST['parent_forum']))
                            $parent = $_POST['parent_forum'];
                        else
                            $parent = 0;

                        if (isset($_POST['hooks']) && count($_POST['hooks'])) $hooks = json_encode($_POST['hooks']);
                        else $hooks = null;

                        if (isset($_POST['default_labels']) && count($_POST['default_labels'])) $default_labels = implode(',', $_POST['default_labels']);
                        else $default_labels = null;

                        // Update the forum
                        $to_update = array(
                            'forum_title' => Output::getClean(Input::get('title')),
                            'forum_description' => Output::getClean(Input::get('description')),
                            'news' => Input::get('display'),
                            'parent' => $parent,
                            'redirect_forum' => $redirect,
                            'icon' => Output::getClean(Input::get('icon')),
                            'forum_type' => Output::getClean(Input::get('forum_type')),
                            'topic_placeholder' => Input::get('topic_placeholder'),
                            'hooks' => $hooks,
                            'default_labels' => $default_labels
                        );

                        if (!isset($redirect_error))
                            $to_update['redirect_url'] = $redirect_url;

                        $queries->update('forums', $_GET['forum'], $to_update);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    // Guest forum permissions
                    $view = Input::get('perm-view-0');
                    $create = 0;
                    $edit = 0;
                    $post = 0;
                    $view_others = Input::get('perm-view_others-0');
                    $moderate = 0;

                    if (!($view)) $view = 0;

                    $forum_perm_exists = 0;

                    $forum_perm_query = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET['forum']));
                    if (count($forum_perm_query)) {
                        foreach ($forum_perm_query as $query) {
                            if ($query->group_id == 0) {
                                $forum_perm_exists = 1;
                                $update_id = $query->id;
                                break;
                            }
                        }
                    }

                    try {
                        if ($forum_perm_exists != 0) { // Permission already exists, update
                            // Update the forum
                            $queries->update('forums_permissions', $update_id, array(
                                'view' => $view,
                                'create_topic' => $create,
                                'edit_topic' => $edit,
                                'create_post' => $post,
                                'view_other_topics' => $view_others,
                                'moderate' => $moderate
                            ));
                        } else { // Permission doesn't exist, create
                            $queries->create('forums_permissions', array(
                                'group_id' => 0,
                                'forum_id' => $_GET['forum'],
                                'view' => $view,
                                'create_topic' => $create,
                                'edit_topic' => $edit,
                                'create_post' => $post,
                                'view_other_topics' => $view_others,
                                'moderate' => $moderate
                            ));
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    // Group forum permissions
                    foreach ($groups as $group) {
                        $view = Input::get('perm-view-' . $group->id);
                        $create = Input::get('perm-topic-' . $group->id);
                        $edit = Input::get('perm-edit_topic-' . $group->id);
                        $post = Input::get('perm-post-' . $group->id);
                        $view_others = Input::get('perm-view_others-' . $group->id);
                        $moderate = Input::get('perm-moderate-' . $group->id);

                        if (!($view)) $view = 0;
                        if (!($create)) $create = 0;
                        if (!($edit)) $edit = 0;
                        if (!($post)) $post = 0;
                        if (!($view_others)) $view_others = 0;
                        if (!($moderate)) $moderate = 0;

                        $forum_perm_exists = 0;

                        if (count($forum_perm_query)) {
                            foreach ($forum_perm_query as $query) {
                                if ($query->group_id == $group->id) {
                                    $forum_perm_exists = 1;
                                    $update_id = $query->id;
                                    break;
                                }
                            }
                        }

                        try {
                            if ($forum_perm_exists != 0) { // Permission already exists, update
                                // Update the forum
                                $queries->update('forums_permissions', $update_id, array(
                                    'view' => $view,
                                    'create_topic' => $create,
                                    'edit_topic' => $edit,
                                    'create_post' => $post,
                                    'view_other_topics' => $view_others,
                                    'moderate' => $moderate
                                ));
                            } else { // Permission doesn't exist, create
                                $queries->create('forums_permissions', array(
                                    'group_id' => $group->id,
                                    'forum_id' => $_GET['forum'],
                                    'view' => $view,
                                    'create_topic' => $create,
                                    'edit_topic' => $edit,
                                    'create_post' => $post,
                                    'view_other_topics' => $view_others,
                                    'moderate' => $moderate
                                ));
                            }
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    }

                    Session::flash('admin_forums', $forum_language->get('forum', 'forum_updated_successfully'));
                    Redirect::to(URL::build('/panel/forums'));
                    die();
                } else {
                    $errors = $validation->errors();
                }
            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    $hooks_query = $queries->orderAll('hooks', 'id', 'ASC');
    $hooks_array = array();
    if (count($hooks_query)) {
        foreach ($hooks_query as $hook) {
            if (in_array('newTopic', json_decode($hook->events))) {
                $hooks_array[] = array(
                    'id' => $hook->id,
                    'name' => Output::getClean($hook->name),
                );
            }
        }
    }

    $forum_hooks = $forum[0]->hooks ?: '[]';

    $template_forums_array = array();
    if (count($available_forums)) {
        foreach ($available_forums as $item) {
            if ($item->id == $forum[0]->id) continue;
            $template_forums_array[] = array(
                'id' => $item->id,
                'title' => Output::getClean($item->forum_title)
            );
        }
    }

    // Get all forum permissions
    $guest_query = DB::getInstance()->query('SELECT 0 AS id, `view`, view_other_topics FROM nl2_forums_permissions WHERE group_id = 0 AND forum_id = ?', array($forum[0]->id))->results();
    $group_query = DB::getInstance()->query('SELECT id, name, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM nl2_groups A LEFT JOIN (SELECT group_id, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM nl2_forums_permissions WHERE forum_id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', array($forum[0]->id))->results();

    // Get default labels
    $enabled_labels = $forum[0]->default_labels ? explode(',', $forum[0]->default_labels) : array();
    $forum_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
    $available_labels = array();
    if (count($forum_labels)) {
        foreach ($forum_labels as $label) {
            $forum_ids = explode(',', $label->fids);

            if (in_array($forum[0]->id, $forum_ids)) {
                $available_labels[] = array(
                    'id' => Output::getClean($label->id),
                    'name' => Output::getClean($label->name),
                    'is_enabled' => in_array($label->id, $enabled_labels)
                );
            }
        }
    }

    $smarty->assign(array(
        'CANCEL' => $language->get('general', 'cancel'),
        'CANCEL_LINK' => URL::build('/panel/forums'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
        'FORUM_TYPE' => $forum_language->get('forum', 'forum_type'),
        'FORUM_TYPE_FORUM' => $forum_language->get('forum', 'forum_type_forum'),
        'FORUM_TYPE_CATEGORY' => $forum_language->get('forum', 'forum_type_category'),
        'FORUM_TYPE_VALUE' => ($forum[0]->forum_type == 'category') ? 'category' : 'forum',
        'FORUM_TITLE' => $forum_language->get('forum', 'forum_name'),
        'FORUM_TITLE_VALUE' => Output::getClean(Output::getDecoded($forum[0]->forum_title)),
        'FORUM_DESCRIPTION' => $forum_language->get('forum', 'forum_description'),
        'FORUM_DESCRIPTION_VALUE' => Output::getClean(Output::getDecoded($forum[0]->forum_description)),
        'FORUM_ICON' => $forum_language->get('forum', 'forum_icon'),
        'FORUM_ICON_VALUE' => Output::getClean(Output::getDecoded($forum[0]->icon)),
        'PARENT_FORUM' => $forum_language->get('forum', 'parent_forum'),
        'PARENT_FORUM_VALUE' => $forum[0]->parent,
        'NO_PARENT' => $forum_language->get('forum', 'has_no_parent'),
        'PARENT_FORUM_LIST' => $template_forums_array,
        'DISPLAY_TOPICS_AS_NEWS' => $forum_language->get('forum', 'display_topics_as_news'),
        'DISPLAY_TOPICS_AS_NEWS_VALUE' => ($forum[0]->news == 1),
        'REDIRECT_FORUM' => $forum_language->get('forum', 'redirect_forum'),
        'REDIRECT_FORUM_VALUE' => ($forum[0]->redirect_forum == 1),
        'REDIRECT_URL' => $forum_language->get('forum', 'redirect_url'),
        'REDIRECT_URL_VALUE' => Output::getClean(Output::getDecoded($forum[0]->redirect_url)),
        'INCLUDE_IN_HOOK' => $forum_language->get('forum', 'include_in_hook'),
        'HOOKS_ARRAY' => $hooks_array,
        'FORUM_HOOKS' => json_decode($forum_hooks),
        'INFO' => $language->get('general', 'info'),
        'HOOK_SELECT_INFO' => $language->get('admin', 'hook_select_info'),
        'FORUM_PERMISSIONS' => $forum_language->get('forum', 'forum_permissions'),
        'GUESTS' => $language->get('user', 'guests'),
        'GUEST_PERMISSIONS' => (count($guest_query) ? $guest_query[0] : array()),
        'GROUP_PERMISSIONS' => $group_query,
        'GROUP' => $forum_language->get('forum', 'group'),
        'CAN_VIEW_FORUM' => $forum_language->get('forum', 'can_view_forum'),
        'CAN_CREATE_TOPIC' => $forum_language->get('forum', 'can_create_topic'),
        'CAN_EDIT_TOPIC' => $forum_language->get('forum', 'can_edit_topic'),
        'CAN_POST_REPLY' => $forum_language->get('forum', 'can_post_reply'),
        'CAN_VIEW_OTHER_TOPICS' => $forum_language->get('forum', 'can_view_other_topics'),
        'CAN_MODERATE_FORUM' => $forum_language->get('forum', 'can_moderate_forum'),
        'TOPIC_PLACEHOLDER' => $forum_language->get('forum', 'topic_placeholder'),
        'TOPIC_PLACEHOLDER_VALUE' => Output::getPurified($forum[0]->topic_placeholder),
        'DEFAULT_LABELS' => $forum_language->get('forum', 'default_labels'),
        'DEFAULT_LABELS_INFO' => $forum_language->get('forum', 'default_labels_info'),
        'AVAILABLE_DEFAULT_LABELS' => $available_labels
    ));

    $template_file = 'forum/forums_edit.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('admin_forums'))
    $success = Session::flash('admin_forums');

if (Session::exists('admin_forums_error'))
    $errors = [Session::flash('admin_forums_error')];

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'FORUM' => $forum_language->get('forum', 'forum'),
    'FORUMS' => $forum_language->get('forum', 'forums'),
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
