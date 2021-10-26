<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Stick/unstick a topic
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

// Ensure a topic is set via URL parameters
if (isset($_GET["tid"])) {
    if (is_numeric($_GET["tid"])) {
        $topic_id = $_GET["tid"];
    } else {
        Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
        die();
    }
} else {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

// Check topic exists and get forum ID
$topic = $queries->getWhere('topics', array('id', '=', $topic_id));

if (!count($topic)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/forum/topic/' . $topic_id));
    die();
}

$forum_id = $topic[0]->forum_id;

if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
    // Get current status
    if ($topic[0]->sticky == 0) {
        $sticky = 1;
        $status = $forum_language->get('forum', 'topic_stuck');
    } else {
        $sticky = 0;
        $status = $forum_language->get('forum', 'topic_unstuck');
    }

    $queries->update("topics", $topic_id, array(
        "sticky" => $sticky
    ));

    Log::getInstance()->log(($sticky == 1) ? Log::Action('forums/topic/stick') : Log::Action('forums/topic/unstick'), $topic[0]->topic_title);

    Session::flash('success_post', $status);
}

Redirect::to(URL::build('/forum/topic/' . $topic_id));
die();
