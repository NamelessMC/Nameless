<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel forums page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'forum';
const PANEL_PAGE = 'forums';
$page_title = $forum_language->get('forum', 'forums');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action']) && !isset($_GET['forum'])) {
    $forums = DB::getInstance()->orderAll('forums', 'forum_order', 'ASC')->results();
    $template_array = [];

    if (count($forums)) {
        $i = 1;
        $count = count($forums);
        foreach ($forums as $item) {
            if ($item->parent > 0) {
                $parent_forum_query = DB::getInstance()->get('forums', ['id', $item->parent])->results();
                if (count($parent_forum_query)) {
                    $parent_forum_count = 1;
                    $parent_forum = $forum_language->get('forum', 'parent_forum_x', ['forum' => Output::getClean($parent_forum_query[0]->forum_title)]);
                    $id = $parent_forum_query[0]->parent;

                    while ($parent_forum_count < 100 && $id > 0) {
                        $parent_forum_query = DB::getInstance()->get('forums', ['id', $parent_forum_query[0]->parent])->results();
                        $id = $parent_forum_query[0]->parent;
                        $parent_forum_count++;
                    }
                } else {
                    $parent_forum = null;
                    $parent_forum_count = 0;
                }
            } else {
                $parent_forum_count = 0;
            }

            $template_array[] = [
                'edit_link' => URL::build('/panel/forums/', 'forum=' . Output::getClean($item->id)),
                'delete_link' => URL::build('/panel/forums/', 'action=delete&fid=' . Output::getClean($item->id)),
                'up_link' => ($i > 1 ? URL::build('/panel/forums/', 'action=order&dir=up&fid=' . Output::getClean($item->id)) : null),
                'down_link' => ($i < $count ? URL::build('/panel/forums/', 'action=order&dir=down&fid=' . Output::getClean($item->id)) : null),
                'title' => Output::getClean($item->forum_title),
                'description' => Output::getPurified($item->forum_description),
                'id' => Output::getClean($item->id),
                'parent_forum' => (($item->parent > 0) ? $parent_forum : null),
                'parent_forum_count' => $parent_forum_count
            ];
            $i++;
        }
    }

    $forum_reactions = Util::getSetting('forum_reactions');

    $smarty->assign([
        'NEW_FORUM' => $forum_language->get('forum', 'new_forum'),
        'NEW_FORUM_LINK' => URL::build('/panel/forums/', 'action=new'),
        'FORUMS_ARRAY' => $template_array,
        'NO_FORUMS' => $forum_language->get('forum', 'no_forums'),
        'REORDER_DRAG_URL' => URL::build('/panel/forums')
    ]);

    $template_file = 'forum/forums.tpl';
} else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'new':
                if (!isset($_GET['step'])) {
                    // Step 1
                    if (Input::exists()) {
                        $errors = [];

                        if (Token::check()) {
                            // Validate input
                            $validation = Validate::check($_POST, [
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

                                    $last_forum_order = DB::getInstance()->orderAll('forums', 'forum_order', 'DESC')->results();
                                    if (count($last_forum_order)) {
                                        $last_forum_order = $last_forum_order[0]->forum_order;
                                    } else {
                                        $last_forum_order = 0;
                                    }

                                    DB::getInstance()->insert('forums', [
                                        'forum_title' => Input::get('forumname'),
                                        'forum_description' => $description,
                                        'forum_order' => $last_forum_order + 1,
                                        'forum_type' => Input::get('forum_type'),
                                        'icon' => Input::get('forum_icon')
                                    ]);

                                    $forum_id = DB::getInstance()->lastId();

                                    Redirect::to(URL::build('/panel/forums/', 'action=new&step=2&forum=' . $forum_id));
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

                    $smarty->assign([
                        'FORUM_TYPE' => $forum_language->get('forum', 'forum_type'),
                        'FORUM_TYPE_FORUM' => $forum_language->get('forum', 'forum_type_forum'),
                        'FORUM_TYPE_CATEGORY' => $forum_language->get('forum', 'forum_type_category'),
                        'FORUM_NAME' => $forum_language->get('forum', 'forum_name'),
                        'FORUM_NAME_VALUE' => Output::getClean(Input::get('forumname')),
                        'FORUM_DESCRIPTION' => $forum_language->get('forum', 'forum_description'),
                        'FORUM_DESCRIPTION_VALUE' => Output::getClean(Input::get('forumdesc')),
                        'FORUM_ICON' => $forum_language->get('forum', 'forum_icon'),
                        'FORUM_ICON_VALUE' => Output::getClean(Input::get('forum_icon'))
                    ]);

                    $template_file = 'forum/forums_new_step_1.tpl';
                } else {
                    // Parent category, for type forum only
                    if (!isset($_GET['forum']) || !is_numeric($_GET['forum'])) {
                        Redirect::to(URL::build('/panel/forums'));
                    }

                    // Get forum from database
                    $forum = DB::getInstance()->get('forums', ['id', $_GET['forum']])->results();
                    if (!count($forum)) {
                        Redirect::to(URL::build('/panel/forums'));
                    }

                    $forum = $forum[0];

                    // Forums only
                    if ($forum->forum_type == 'category') {
                        Redirect::to(URL::build('/panel/forums/', 'forum=' . $forum->id));
                    }

                    // Deal with input
                    if (Input::exists()) {
                        $errors = [];

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

                                if (isset($_POST['hooks']) && count($_POST['hooks'])) {
                                    $hooks = json_encode($_POST['hooks']);
                                } else {
                                    $hooks = null;
                                }

                                if (!isset($redirect_error)) {
                                    $parent = $_POST['parent'] ?? 0;

                                    DB::getInstance()->update('forums', $forum->id, [
                                        'parent' => $parent,
                                        'news' => Input::get('news_forum'),
                                        'redirect_forum' => $redirect,
                                        'redirect_url' => $redirect_url,
                                        'hooks' => $hooks
                                    ]);

                                    Redirect::to(URL::build('/panel/forums/', 'forum=' . $forum->id));
                                }

                                $errors[] = $forum_language->get('forum', 'invalid_redirect_url');
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors[] = $language->get('general', 'invalid_token');
                        }
                    }

                    // Get a list of forums
                    $forums = DB::getInstance()->get('forums', ['id', '<>', $forum->id])->results();
                    $template_array = [];

                    if (count($forums)) {
                        foreach ($forums as $item) {
                            $template_array[] = [
                                'id' => Output::getClean($item->id),
                                'name' => Output::getClean($item->forum_title)
                            ];
                        }
                    }
                    $hooks_query = DB::getInstance()->orderAll('hooks', 'id', 'ASC')->results();
                    $hooks_array = [];
                    if (count($hooks_query)) {
                        foreach ($hooks_query as $hook) {
                            if (in_array('newTopic', json_decode($hook->events))) {
                                $hooks_array[] = [
                                    'id' => $hook->id,
                                    'name' => Output::getClean($hook->name),
                                ];
                            }
                        }
                    }
                    $smarty->assign([
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
                    ]);

                    $template_file = 'forum/forums_new_step_2.tpl';
                }

                $smarty->assign([
                    'CREATING_FORUM' => $forum_language->get('forum', 'creating_forum'),
                    'CANCEL' => $language->get('general', 'cancel'),
                    'CANCEL_LINK' => URL::build('/panel/forums'),
                    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                    'YES' => $language->get('general', 'yes'),
                    'NO' => $language->get('general', 'no'),
                    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel')
                ]);

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
                    }

                    $dir = $_GET['dir'];

                    $forum_id = DB::getInstance()->get('forums', ['id', $_GET['fid']])->results();
                    $forum_id = $forum_id[0]->id;

                    $forum_order = DB::getInstance()->get('forums', ['id', $_GET['fid']])->results();
                    $forum_order = $forum_order[0]->forum_order;

                    $previous_forums = DB::getInstance()->orderAll('forums', 'forum_order', 'ASC')->results();

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
                            if (isset($previous_fid, $previous_f_order)) {
                                DB::getInstance()->update('forums', $forum_id, [
                                    'forum_order' => $previous_f_order
                                ]);
                                DB::getInstance()->update('forums', $previous_fid, [
                                    'forum_order' => $previous_f_order + 1
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                        Redirect::to(URL::build('/panel/forums'));
                    }

                    if ($dir == 'down') {
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
                            if (isset($previous_fid, $previous_f_order)) {
                                DB::getInstance()->update('forums', $forum_id, [
                                    'forum_order' => $previous_f_order
                                ]);
                                DB::getInstance()->update('forums', $previous_fid, [
                                    'forum_order' => $previous_f_order - 1
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                        Redirect::to(URL::build('/panel/forums'));
                    }
                } else {
                    if ($_GET['dir'] == 'drag') {
                        // Get forums
                        if (isset($_GET['forums'])) {
                            $forums = json_decode($_GET['forums'])->forums;

                            $i = 0;
                            foreach ($forums as $item) {
                                DB::getInstance()->update('forums', $item, [
                                    'forum_order' => $i
                                ]);

                                $i++;
                            }
                        }

                        die('Complete');
                    }

                    echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/panel/forums') . '">' . $language->get('general', 'back') . '</a>';
                    die();
                }
                break;

            case 'delete':
                if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
                    Redirect::to(URL::build('/panel/forums'));
                }

                // Ensure forum exists
                $forum = DB::getInstance()->get('forums', ['id', $_GET['fid']])->results();
                if (!count($forum)) {
                    Redirect::to(URL::build('/panel/forums'));
                }
                $forum = $forum[0];

                if (Input::exists()) {
                    if (Token::check()) {
                        if (Input::get('confirm') === 'true') {
                            $forum_perms = DB::getInstance()->get('forums_permissions', ['forum_id', $_GET['fid']])->results(); // Get permissions to be deleted
                            if (Input::get('move_forum') === 'none') {
                                $posts = DB::getInstance()->get('posts', ['forum_id', $_GET['fid']])->results();
                                $topics = DB::getInstance()->get('topics', ['forum_id', $_GET['fid']])->results();

                                foreach ($posts as $post) {
                                    DB::getInstance()->delete('posts', ['id', $post->id]);
                                }
                                foreach ($topics as $topic) {
                                    DB::getInstance()->delete('topics', ['id', $topic->id]);
                                }

                                // Forum perm deletion

                            } else {
                                $new_forum = Input::get('move_forum');
                                $posts = DB::getInstance()->get('posts', ['forum_id', $_GET['fid']])->results();
                                $topics = DB::getInstance()->get('topics', ['forum_id', $_GET['fid']])->results();

                                foreach ($posts as $post) {
                                    DB::getInstance()->update('posts', $post->id, [
                                        'forum_id' => $new_forum
                                    ]);
                                }
                                foreach ($topics as $topic) {
                                    DB::getInstance()->update('topics', $topic->id, [
                                        'forum_id' => $new_forum
                                    ]);
                                }

                                // Forum perm deletion

                            }
                            DB::getInstance()->delete('forums', ['id', $_GET['fid']]);
                            foreach ($forum_perms as $perm) {
                                DB::getInstance()->delete('forums_permissions', ['id', $perm->id]);
                            }
                            Session::flash('admin_forums', $forum_language->get('forum', 'forum_deleted_successfully'));
                            Redirect::to(URL::build('/panel/forums'));
                        }
                    } else {
                        $errors = [$language->get('general', 'invalid_token')];
                    }
                }

                $other_forums = DB::getInstance()->orderWhere('forums', 'parent > 0', 'forum_order', 'ASC')->results();
                $template_array = [];
                foreach ($other_forums as $item) {
                    if ($item->id == $forum->id) {
                        continue;
                    }

                    $template_array[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->forum_title)
                    ];
                }

                $smarty->assign([
                    'DELETE_FORUM' => $forum_language->get('forum', 'delete_forum'),
                    'MOVE_TOPICS_AND_POSTS_TO' => $forum_language->get('forum', 'move_topics_and_posts_to'),
                    'DELETE_TOPICS_AND_POSTS' => $forum_language->get('forum', 'delete_topics_and_posts'),
                    'OTHER_FORUMS' => $template_array
                ]);

                $template_file = 'forum/forums_delete.tpl';

                break;

            default:
                Redirect::to(URL::build('/panel/forums'));
        }
    } else {
        if (isset($_GET['forum'])) {
            // Editing forum
            if (!is_numeric($_GET['forum'])) {
                die();
            }

            $forum = DB::getInstance()->get('forums', ['id', $_GET['forum']])->results();

            if (!count($forum)) {
                Redirect::to(URL::build('/panel/forums'));
            }

            $available_forums = DB::getInstance()->orderWhere('forums', 'id > 0', 'forum_order', 'ASC')->results(); // Get a list of all forums which can be chosen as a parent

            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    if (Input::get('action') == 'update') {
                        $validation = Validate::check($_POST, [
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

                                $parent = $_POST['parent_forum'] ?? 0;

                                if (isset($_POST['hooks']) && count($_POST['hooks'])) {
                                    $hooks = json_encode($_POST['hooks']);
                                } else {
                                    $hooks = null;
                                }

                                if (isset($_POST['default_labels']) && count($_POST['default_labels'])) {
                                    $default_labels = implode(',', $_POST['default_labels']);
                                } else {
                                    $default_labels = null;
                                }

                                // Update the forum
                                $to_update = [
                                    'forum_title' => Input::get('title'),
                                    'forum_description' => Input::get('description'),
                                    'news' => Input::get('display'),
                                    'parent' => $parent,
                                    'redirect_forum' => $redirect,
                                    'icon' => Input::get('icon'),
                                    'forum_type' => Input::get('forum_type'),
                                    'topic_placeholder' => Input::get('topic_placeholder'),
                                    'hooks' => $hooks,
                                    'default_labels' => $default_labels
                                ];

                                if (!isset($redirect_error)) {
                                    $to_update['redirect_url'] = $redirect_url;
                                }

                                DB::getInstance()->update('forums', $_GET['forum'], $to_update);
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

                            if (!($view)) {
                                $view = 0;
                            }

                            $forum_perm_exists = 0;

                            $forum_perm_query = DB::getInstance()->get('forums_permissions', ['forum_id', $_GET['forum']])->results();
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
                                    DB::getInstance()->update('forums_permissions', $update_id, [
                                        'view' => $view,
                                        'create_topic' => $create,
                                        'edit_topic' => $edit,
                                        'create_post' => $post,
                                        'view_other_topics' => $view_others,
                                        'moderate' => $moderate
                                    ]);
                                } else { // Permission doesn't exist, create
                                    DB::getInstance()->insert('forums_permissions', [
                                        'group_id' => 0,
                                        'forum_id' => $_GET['forum'],
                                        'view' => $view,
                                        'create_topic' => $create,
                                        'edit_topic' => $edit,
                                        'create_post' => $post,
                                        'view_other_topics' => $view_others,
                                        'moderate' => $moderate
                                    ]);
                                }
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }

                            // Group forum permissions
                            foreach (Group::all() as $group) {
                                $view = Input::get('perm-view-' . $group->id);
                                $create = Input::get('perm-topic-' . $group->id);
                                $edit = Input::get('perm-edit_topic-' . $group->id);
                                $post = Input::get('perm-post-' . $group->id);
                                $view_others = Input::get('perm-view_others-' . $group->id);
                                $moderate = Input::get('perm-moderate-' . $group->id);

                                if (!($view)) {
                                    $view = 0;
                                }
                                if (!($create)) {
                                    $create = 0;
                                }
                                if (!($edit)) {
                                    $edit = 0;
                                }
                                if (!($post)) {
                                    $post = 0;
                                }
                                if (!($view_others)) {
                                    $view_others = 0;
                                }
                                if (!($moderate)) {
                                    $moderate = 0;
                                }

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
                                        DB::getInstance()->update('forums_permissions', $update_id, [
                                            'view' => $view,
                                            'create_topic' => $create,
                                            'edit_topic' => $edit,
                                            'create_post' => $post,
                                            'view_other_topics' => $view_others,
                                            'moderate' => $moderate
                                        ]);
                                    } else { // Permission doesn't exist, create
                                        DB::getInstance()->insert('forums_permissions', [
                                            'group_id' => $group->id,
                                            'forum_id' => $_GET['forum'],
                                            'view' => $view,
                                            'create_topic' => $create,
                                            'edit_topic' => $edit,
                                            'create_post' => $post,
                                            'view_other_topics' => $view_others,
                                            'moderate' => $moderate
                                        ]);
                                    }
                                } catch (Exception $e) {
                                    $errors[] = $e->getMessage();
                                }
                            }

                            Session::flash('admin_forums', $forum_language->get('forum', 'forum_updated_successfully'));
                            Redirect::to(URL::build('/panel/forums'));
                        }

                        $errors = $validation->errors();
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $hooks_query = DB::getInstance()->orderAll('hooks', 'id', 'ASC')->results();
            $hooks_array = [];
            if (count($hooks_query)) {
                foreach ($hooks_query as $hook) {
                    if (in_array('newTopic', json_decode($hook->events))) {
                        $hooks_array[] = [
                            'id' => $hook->id,
                            'name' => Output::getClean($hook->name),
                        ];
                    }
                }
            }

            $forum_hooks = $forum[0]->hooks ?: '[]';

            $template_forums_array = [];
            if (count($available_forums)) {
                foreach ($available_forums as $item) {
                    if ($item->id == $forum[0]->id) {
                        continue;
                    }
                    $template_forums_array[] = [
                        'id' => $item->id,
                        'title' => Output::getClean($item->forum_title)
                    ];
                }
            }

            // Get all forum permissions
            $guest_query = DB::getInstance()->query('SELECT 0 AS id, `view`, view_other_topics FROM nl2_forums_permissions WHERE group_id = 0 AND forum_id = ?', [$forum[0]->id])->results();
            $group_query = DB::getInstance()->query('SELECT id, name, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM nl2_groups A LEFT JOIN (SELECT group_id, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM nl2_forums_permissions WHERE forum_id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', [$forum[0]->id])->results();

            // Get default labels
            $enabled_labels = $forum[0]->default_labels ? explode(',', $forum[0]->default_labels) : [];
            $forum_labels = DB::getInstance()->get('forums_topic_labels', ['id', '<>', 0])->results();
            $available_labels = [];
            if (count($forum_labels)) {
                foreach ($forum_labels as $label) {
                    $forum_ids = explode(',', $label->fids);

                    if (in_array($forum[0]->id, $forum_ids)) {
                        $available_labels[] = [
                            'id' => Output::getClean($label->id),
                            'name' => Output::getClean($label->name),
                            'is_enabled' => in_array($label->id, $enabled_labels)
                        ];
                    }
                }
            }

            $smarty->assign([
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
                'FORUM_TITLE_VALUE' => Output::getClean($forum[0]->forum_title),
                'FORUM_DESCRIPTION' => $forum_language->get('forum', 'forum_description'),
                'FORUM_DESCRIPTION_VALUE' => Output::getClean($forum[0]->forum_description),
                'FORUM_ICON' => $forum_language->get('forum', 'forum_icon'),
                'FORUM_ICON_VALUE' => Output::getClean($forum[0]->icon),
                'PARENT_FORUM' => $forum_language->get('forum', 'parent_forum'),
                'PARENT_FORUM_VALUE' => $forum[0]->parent,
                'NO_PARENT' => $forum_language->get('forum', 'has_no_parent'),
                'PARENT_FORUM_LIST' => $template_forums_array,
                'DISPLAY_TOPICS_AS_NEWS' => $forum_language->get('forum', 'display_topics_as_news'),
                'DISPLAY_TOPICS_AS_NEWS_VALUE' => ($forum[0]->news == 1),
                'REDIRECT_FORUM' => $forum_language->get('forum', 'redirect_forum'),
                'REDIRECT_FORUM_VALUE' => ($forum[0]->redirect_forum == 1),
                'REDIRECT_URL' => $forum_language->get('forum', 'redirect_url'),
                'REDIRECT_URL_VALUE' => Output::getClean($forum[0]->redirect_url),
                'INCLUDE_IN_HOOK' => $forum_language->get('forum', 'include_in_hook'),
                'HOOKS_ARRAY' => $hooks_array,
                'FORUM_HOOKS' => json_decode($forum_hooks),
                'INFO' => $language->get('general', 'info'),
                'HOOK_SELECT_INFO' => $language->get('admin', 'hook_select_info'),
                'FORUM_PERMISSIONS' => $forum_language->get('forum', 'forum_permissions'),
                'GUESTS' => $language->get('user', 'guests'),
                'GUEST_PERMISSIONS' => (count($guest_query) ? $guest_query[0] : []),
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
            ]);

            $template_file = 'forum/forums_edit.tpl';
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_forums')) {
    $success = Session::flash('admin_forums');
}

if (Session::exists('admin_forums_error')) {
    $errors = [Session::flash('admin_forums_error')];
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
    'FORUMS' => $forum_language->get('forum', 'forums'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
