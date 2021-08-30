<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Delete topic
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');

// Always define page name
define('PAGE', 'forum');

$forum = new Forum();

// Check params are set
if (!isset($_GET["tid"]) || !is_numeric($_GET["tid"])) {
    Redirect::to(URL::build('/forum'));
    die();
} else {
    $topic_id = $_GET["tid"];
}

// Check topic exists
$topic = $queries->getWhere('topics', array('id', '=', $topic_id));

if (!count($topic)) {
    Redirect::to(URL::build('/forum'));
    die();
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/forum/topic/' . $topic_id));
    die();
}

$topic = $topic[0];

if ($forum->canModerateForum($topic->forum_id, $user->getAllGroupIds())) {

    $queries->update('topics', $topic_id, array(
        'deleted' => 1
    ));
    //TODO: TOPIC
    Log::getInstance()->log(Log::Action('forums/topic/delete'), $topic_id);

    $posts = $queries->getWhere('posts', array('topic_id', '=', $topic_id));

    if (count($posts)) {
        foreach ($posts as $post) {
            $queries->update('posts', $post->id, array(
                'deleted' => 1
            ));
        }
    }

    // Update latest posts in forums
    $forum->updateForumLatestPosts();

    Redirect::to(URL::build('/forum'));
    die();

} else {
    Redirect::to(URL::build('/forum'));
    die();
}
