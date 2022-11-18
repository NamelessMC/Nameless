<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Delete topic
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
}

// Always define page name
const PAGE = 'forum';

$forum = new Forum();

// Check params are set
if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
    Redirect::to(URL::build('/forum'));
}

$topic_id = $_GET['tid'];

// Check topic exists
$topic = DB::getInstance()->get('topics', ['id', $topic_id])->results();

if (!count($topic)) {
    Redirect::to(URL::build('/forum'));
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/forum/topic/' . urlencode($topic_id)));
}

$topic = $topic[0];

if ($forum->canModerateForum($topic->forum_id, $user->getAllGroupIds())) {

    DB::getInstance()->update('topics', $topic_id, [
        'deleted' => true,
    ]);
    //TODO: TOPIC
    Log::getInstance()->log(Log::Action('forums/topic/delete'), $topic_id);

    $posts = DB::getInstance()->get('posts', ['topic_id', $topic_id])->results();

    if (count($posts)) {
        foreach ($posts as $post) {
            DB::getInstance()->update('posts', $post->id, [
                'deleted' => true,
            ]);
        }
    }

    // Update latest posts in forums
    $forum->updateForumLatestPosts($topic->forum_id);

}
Redirect::to(URL::build('/forum'));
