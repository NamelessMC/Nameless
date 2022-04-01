<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Edit post page
 */

// Always define page name
const PAGE = 'forum';
$page_title = $forum_language->get('forum', 'edit_post');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles([
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => [],
]);

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
}

// Initialise
$forum = new Forum();

if (isset($_GET['pid'], $_GET['tid']) && is_numeric($_GET['pid']) && is_numeric($_GET['tid'])) {
    $post_id = $_GET['pid'];
    $topic_id = $_GET['tid'];
} else {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

/*
 *  Is the post the first in the topic? If so, allow the title to be edited.
 */

$post_editing = DB::getInstance()->selectQuery('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY id ASC LIMIT 1', [$topic_id])->results();

// Check topic exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

if ($post_editing[0]->id == $post_id) {
    $edit_title = true;

    /*
	 *  Get the title of the topic
	 */

    $post_title = $queries->getWhere('topics', ['id', '=', $topic_id]);
    $post_labels = $post_title[0]->labels ? explode(',', $post_title[0]->labels) : [];
    $post_title = Output::getClean($post_title[0]->topic_title);
}

/*
 *  Get the post we're editing
 */

$post_editing = $queries->getWhere('posts', ['id', '=', $post_id]);

// Check post exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

$forum_id = $post_editing[0]->forum_id;

// Get user group IDs
$user_groups = $user->getAllGroupIds();

// Check permissions before proceeding
if ($user->data()->id === $post_editing[0]->post_creator && !$forum->canEditTopic($forum_id, $user_groups) && !$forum->canModerateForum($forum_id, $user_groups)) {
    Redirect::to(URL::build('/forum/topic/' . $post_id));
}

if ($user->data()->id !== $post_editing[0]->post_creator && !($forum->canModerateForum($forum_id, $user_groups))) {
    Redirect::to(URL::build('/forum/topic/' . $post_id));
}

// Deal with input
if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token, check input
        $to_validate = [
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 50000
            ]
        ];
        // Add title to validation if we need to
        if (isset($edit_title)) {
            $to_validate['title'] = [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 64
            ];
        }

        $validation = Validate::check($_POST, $to_validate)->messages([
            'content' => [
                Validate::REQUIRED => $forum_language->get('forum', 'content_required'),
                Validate::MIN => $forum_language->get('forum', 'content_min_2'),
                Validate::MAX => $forum_language->get('forum', 'content_max_50000')
            ],
            'title' => [
                Validate::REQUIRED => $forum_language->get('forum', 'title_required'),
                Validate::MIN => $forum_language->get('forum', 'title_min_2'),
                Validate::MAX => $forum_language->get('forum', 'title_max_64')
            ]
        ]);

        if ($validation->passed()) {
            // Valid post content
            $content = Output::getClean(Input::get('content'));
            $content = EventHandler::executeEvent(isset($edit_title) ? 'preTopicEdit' : 'prePostEdit', [
                'content' => $content,
                'post_id' => $post_id,
                'topic_id' => $topic_id,
                'user' => $user,
            ])['content'];

            // Update post content
            $queries->update('posts', $post_id, [
                'post_content' => $content,
                'last_edited' => date('U')
            ]);

            Log::getInstance()->log(Log::Action('forums/post/edit'), $post_id);

            if (isset($edit_title)) {
                // Update title and labels
                $post_labels = [];

                if (isset($_POST['topic_label']) && !empty($_POST['topic_label']) && is_array($_POST['topic_label'])) {
                    foreach ($_POST['topic_label'] as $topic_label) {
                        $label = $queries->getWhere('forums_topic_labels', ['id', '=', $topic_label]);
                        if (count($label)) {
                            $lgroups = explode(',', $label[0]->gids);

                            $hasperm = false;
                            foreach ($user_groups as $group_id) {
                                if (in_array($group_id, $lgroups)) {
                                    $hasperm = true;
                                    break;
                                }
                            }

                            if ($hasperm) {
                                $post_labels[] = $label[0]->id;
                            }
                        }
                    }
                }

                $queries->update('topics', $topic_id, [
                    'topic_title' => Output::getDecoded(Input::get('title')),
                    'labels' => implode(',', $post_labels)
                ]);

                Log::getInstance()->log(Log::Action('forums/topic/edit'), Output::getDecoded(Input::get('title')));
            }

            // Display success message and redirect
            Session::flash('success_post', $forum_language->get('forum', 'post_edited_successfully'));
            Redirect::to(URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id));
        }

        // Error handling
        $errors = $validation->errors();
    } else {
        // Bad token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

if (isset($errors)) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ]);
}

$smarty->assign('EDITING_POST', $forum_language->get('forum', 'edit_post'));

if (isset($edit_title, $post_labels)) {
    $smarty->assign('EDITING_TOPIC', true);

    $smarty->assign('TOPIC_TITLE', $post_title);

    // Topic labels
    $smarty->assign('LABELS_TEXT', $forum_language->get('forum', 'label'));
    $labels = [];

    $forum_labels = $queries->getWhere('forums_topic_labels', ['id', '<>', 0]);
    if (count($forum_labels)) {
        foreach ($forum_labels as $label) {
            $forum_ids = explode(',', $label->fids);

            if (in_array($forum_id, $forum_ids)) {
                // Check permissions
                $lgroups = explode(',', $label->gids);
                $perms = false;

                foreach ($user_groups as $group) {
                    if (in_array($group, $lgroups)) {
                        $perms = true;
                    }
                }

                if ($perms == false) {
                    continue;
                }

                // Get label HTML
                $label_html = $queries->getWhere('forums_labels', ['id', '=', $label->label]);
                if (!count($label_html)) {
                    continue;
                }

                $label_html = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html[0]->html));

                $labels[] = [
                    'id' => $label->id,
                    'active' => in_array($label->id, $post_labels),
                    'html' => $label_html
                ];
            }
        }
    }

    $smarty->assign('LABELS', $labels);
}

$smarty->assign([
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CANCEL_LINK' => URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CONTENT' => Output::getPurified(Output::getDecoded($post_editing[0]->post_content))
]);

$clean = Output::getDecoded($post_editing[0]->post_content);
$clean = Output::getPurified($clean);

$template->addJSFiles([
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => []
]);

$template->addJSScript(Input::createTinyEditor($language, 'editor', true));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{{time}}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/forum_edit_post.tpl', $smarty);
