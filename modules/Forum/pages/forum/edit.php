<?php
/*
 *  Made by Samerton
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

$post_editing = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY id ASC LIMIT 1', [$topic_id])->results();

// Check topic exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

if ($post_editing[0]->id == $post_id) {
    $edit_title = true;

    /*
     *  Get the title of the topic
     */

    $post_title = DB::getInstance()->get('topics', ['id', $topic_id])->results();
    $post_labels = $post_title[0]->labels ? explode(',', $post_title[0]->labels) : [];
    $post_title = Output::getClean($post_title[0]->topic_title);
}

/*
 *  Get the post we're editing
 */

$post_editing = DB::getInstance()->get('posts', ['id', $post_id])->results();

// Check post exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

$forum_id = $post_editing[0]->forum_id;

// Get user group IDs
$user_groups = $user->getAllGroupIds();
// Check permissions before proceeding
if ($user->data()->id == $post_editing[0]->post_creator && !$forum->canEditTopic($forum_id, $user_groups) && !$forum->canModerateForum($forum_id, $user_groups)) {
    Redirect::to(URL::build('/forum/topic/' . urlencode($post_id)));
}

if ($user->data()->id != $post_editing[0]->post_creator && !($forum->canModerateForum($forum_id, $user_groups))) {
    Redirect::to(URL::build('/forum/topic/' . urlencode($post_id)));
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
            $content = EventHandler::executeEvent(isset($edit_title) ? 'preTopicEdit' : 'prePostEdit', [
                'content' => Input::get('content'),
                'post_id' => $post_id,
                'topic_id' => $topic_id,
                'user' => $user,
            ])['content'];

            // Update post content
            DB::getInstance()->update('posts', $post_id, [
                'post_content' => $content,
                'last_edited' => date('U')
            ]);

            Log::getInstance()->log(Log::Action('forums/post/edit'), $post_id);

            if (isset($edit_title)) {
                // Update title and labels
                $existing_labels = $post_labels;
                $post_labels = [];

                //
                //  This is quite a mess but let me try to explain.
                //
                //  1. We get all the topic labels for this topic
                //  2. We filter all the labels the user has access to
                //  3. Check which labels already exist on the forum that the user DOESN'T have access to
                //  4. Get all the newly posted labels and add the labels that already existed and the user doesn't have access to, to the labels array
                //  5. Save the labels
                //

                $all_forum_labels = DB::getInstance()->get('forums_topic_labels', ['id', '<>', 0])->results();
                $forum_labels = array_reduce($all_forum_labels, function (&$prev, $lbl) use ($forum_id) {
                    $forum_ids = explode(',', $lbl->fids);
                    if (in_array($forum_id, $forum_ids)) {
                        $prev[] = $lbl->id;
                    }
                    return $prev;
                }, []);
                $accessible_labels = Forum::getAccessibleLabels($forum_labels, $user_groups);
                $existing_inaccessible_labels = array_diff($existing_labels, $accessible_labels);

                // Get all the posted labels and see which ones the user can actually edit
                if (isset($_POST['topic_label']) && !empty($_POST['topic_label']) && is_array($_POST['topic_label'])) {
                    $post_labels = Forum::getAccessibleLabels($_POST['topic_label'], $user_groups);
                }

                $post_labels = array_merge($existing_inaccessible_labels, $post_labels);
                DB::getInstance()->update('topics', $topic_id, [
                    'topic_title' => Input::get('title'),
                    'labels' => implode(',', $post_labels)
                ]);

                Log::getInstance()->log(Log::Action('forums/topic/edit'), Input::get('title'));
            }

            // Display success message and redirect
            Session::flash('success_post', $forum_language->get('forum', 'post_edited_successfully'));
            Redirect::to(URL::build('/forum/topic/' . urlencode($topic_id), 'pid=' . ($post_id)));
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

    $smarty->assign('TOPIC_TITLE_VALUE', $post_title);

    // Topic labels
    $smarty->assign('LABELS_TEXT', $forum_language->get('forum', 'label'));
    $labels = [];

    $forum_labels = DB::getInstance()->get('forums_topic_labels', ['id', '<>', 0])->results();
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
                $label_html = DB::getInstance()->get('forums_labels', ['id', $label->label])->results();
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

// Purify post content
$content = EventHandler::executeEvent('renderPostEdit', [
    'content' => $post_editing[0]->post_content,
    'user' => $user
])['content'];

$smarty->assign([
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CANCEL_LINK' => URL::build('/forum/topic/' . urlencode($topic_id), 'pid=' . urlencode($post_id)),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CONTENT_LABEL' => $language->get('general', 'content'),
    'TOPIC_TITLE' => $forum_language->get('forum', 'topic_title')
]);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'editor', $content, true));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/forum_edit_post.tpl', $smarty);
