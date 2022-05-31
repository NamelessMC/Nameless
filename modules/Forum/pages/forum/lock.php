<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Lock/unlock a topic
 */

$forum = new Forum();

if ($user->isLoggedIn()) {
    if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
        Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    }

    $topic_id = $_GET['tid'];

    // Check topic exists and get forum ID
    $topic = DB::getInstance()->get('topics', ['id', $topic_id])->results();

    if (!count($topic)) {
        Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    }

    if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
        Session::flash('failure_post', $language->get('general', 'invalid_token'));
        Redirect::to(URL::build('/forum/topic/' . urlencode($topic_id)));
    }

    $forum_id = $topic[0]->forum_id;

    if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
        $locked_status = $topic[0]->locked;

        if ($locked_status == 1) {
            $locked_status = 0;
        } else {
            $locked_status = 1;
        }

        DB::getInstance()->update('topics', $topic_id, [
            'locked' => $locked_status
        ]);
        Log::getInstance()->log(Log::Action('forums/topic/lock'), ($locked_status == 1) ? $language->get('log', 'info_forums_lock') : $language->get('log', 'info_forums_unlock'));

        Redirect::to(URL::build('/forum/topic/' . urlencode($topic_id)));

    } else {
        Redirect::to(URL::build('/forum'));
    }
} else {
    Redirect::to(URL::build('/forum'));
}
