<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Merge two topics together
 */

const PAGE = 'forum';
$page_title = $forum_language->get('forum', 'merge_topics');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to('/forum');
}

if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
}

$topic_id = $_GET['tid'];
$forum_id = DB::getInstance()->query('SELECT forum_id FROM nl2_topics WHERE id = ?', [$topic_id])->first();
$forum_id = $forum_id->forum_id;

if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'merge' => [
                    Validate::REQUIRED => true
                ]
            ]);

            $posts_to_move = DB::getInstance()->get('posts', ['topic_id', $topic_id])->results();
            if ($validation->passed()) {
                $posts = implode(',', array_column($posts_to_move, 'id'));
                DB::getInstance()->query(
                    "UPDATE nl2_posts SET `topic_id` = ? WHERE `id` IN ($posts)",
                    [Input::get('merge')]
                );

                $newTopic = DB::getInstance()->get('topics', ['id', Input::get('merge')])->first();

                DB::getInstance()->delete('topics', ['id', $topic_id]);
                Log::getInstance()->log(Log::Action('forums/merge'));
                // Update latest posts in categories
                $forum->updateForumLatestPosts($forum_id);
                if ($newTopic->forum_id != $forum_id) {
                    $forum->updateForumLatestPosts($newTopic->forum_id);
                }
                $forum->updateTopicLatestPosts((int) Input::get('merge'), $forum_id);

                Redirect::to(URL::build('/forum/topic/' . urlencode(Input::get('merge'))));

            } else {
                echo 'Error processing that action. <a href="' . URL::build('/forum') . '">Forum index</a>';
            }
            die();
        }
    }
} else {
    Redirect::to(URL::build('/forum'));
}

$token = Token::get();

// Get topics
$topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND deleted = 0 AND id <> ? ORDER BY id ASC', [$forum_id, $topic_id])->results();

// Smarty
$smarty->assign([
    'MERGE_TOPICS' => $forum_language->get('forum', 'merge_topics'),
    'MERGE_INSTRUCTIONS' => $forum_language->get('forum', 'merge_instructions'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CANCEL_LINK' => URL::build('/forum/topic/' . urlencode($topic_id)),
    'TOPICS' => $topics
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/merge.tpl', $smarty);
