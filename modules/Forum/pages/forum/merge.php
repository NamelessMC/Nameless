<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Merge two topics together
 */

define('PAGE', 'forum');
$page_title = $forum_language->get('forum', 'merge_topics');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to('/forum');
    die();
}

if (!isset($_GET["tid"]) || !is_numeric($_GET["tid"])) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
} else {
    $topic_id = $_GET["tid"];
    $forum_id = DB::getInstance()->query('SELECT forum_id FROM nl2_topics WHERE id = ?', array($topic_id))->first();
    $forum_id = $forum_id->forum_id;
}

if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'merge' => [
                    Validate::REQUIRED => true
                ]
            ]);

            $posts_to_move = $queries->getWhere('posts', array('topic_id', '=', $topic_id));
            if ($validation->passed()) {

                foreach ($posts_to_move as $post_to_move) {
                    $queries->update('posts', $post_to_move->id, array(
                        'topic_id' => Input::get('merge')
                    ));
                }
                $queries->delete('topics', array('id', '=', $topic_id));
                Log::getInstance()->log(Log::Action('forums/merge'));
                // Update latest posts in categories
                $forum->updateForumLatestPosts();
                $forum->updateTopicLatestPosts();

                Redirect::to(URL::build('/forum/topic/' . Input::get('merge')));
                die();

            } else {
                echo 'Error processing that action. <a href="' . URL::build('/forum') . '">Forum index</a>';
                die();
            }
        }
    }
} else {
    Redirect::to(URL::build("/forum"));
    die();
}

$token = Token::get();

// Get topics
$topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND deleted = 0 AND id <> ? ORDER BY id ASC', array($forum_id, $topic_id))->results();

// Smarty
$smarty->assign(array(
    'MERGE_TOPICS' => $forum_language->get('forum', 'merge_topics'),
    'MERGE_INSTRUCTIONS' => $forum_language->get('forum', 'merge_instructions'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CANCEL_LINK' => URL::build('/forum/topic/' . Output::getClean($topic_id)),
    'TOPICS' => $topics
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/merge.tpl', $smarty);
