<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Mark a post as spam
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
}

// Always define page name
const PAGE = 'forum';

// Initialise
$forum = new Forum();

// Get the post
if (!isset($_POST['post']) || !is_numeric($_POST['post'])) {
    Redirect::to(URL::build('/forum'));
}

$post = DB::getInstance()->get('posts', ['id', $_POST['post']])->results();
if (!count($post)) {
    // Doesn't exist
    Redirect::to(URL::build('/forum'));
}
$post = $post[0];

// Check the user can moderate the forum
if ($forum->canModerateForum($post->forum_id, $user->getAllGroupIds())) {
    // Check token
    if (Token::check()) {
        // Valid token, go ahead and mark the user as spam

        // Get user
        $banned_user = new User($post->post_creator);

        $is_admin = $banned_user->canViewStaffCP();

        // Ensure user is not admin
        if ($is_admin) {
            Session::flash('failure_post', $language->get('moderator', 'cant_ban_admin'));
            Redirect::to(URL::build('/forum/topic/' . urlencode($post->topic_id), 'pid=' . urlencode($post->id)));
        }

        // First get any forums where this user is the last user who posted
        $latest_forums = [];
        $latest_forums_query = DB::getInstance()->query('SELECT `id` FROM nl2_forums WHERE `last_user_posted` = ?', [$banned_user->data()->id]);
        if ($latest_forums_query->count()) {
            $latest_forums = array_map(fn($latest_forum) => $latest_forum->id, $latest_forums_query->results());
        }

        // Now get any topics where this user is the last user who posted
        $latest_topics = [];
        $latest_topics_query = DB::getInstance()->query(
            <<<SQL
            SELECT `id`
            FROM nl2_topics
            WHERE `topic_creator` <> ?
              AND `topic_last_user` = ?
            SQL,
            [
                $banned_user->data()->id,
                $banned_user->data()->id,
            ]
        );
        if ($latest_topics_query->count()) {
            $latest_topics = array_map(fn($latest_topic) => $latest_topic->id, $latest_topics_query->results());
        }

        // Delete all posts from the user
        DB::getInstance()->query('UPDATE nl2_posts SET `deleted` = 1 WHERE `post_creator` = ?', [$post->post_creator]);

        // Delete all topics from the user
        DB::getInstance()->query(
            'UPDATE nl2_posts SET `deleted` = 1 WHERE `topic_id` IN (SELECT `id` FROM nl2_topics WHERE `topic_creator` = ?)',
            [$post->post_creator]
        );
        DB::getInstance()->query('UPDATE nl2_topics SET `deleted` = 1 WHERE `topic_creator` = ?', [$post->post_creator]);

        // Log user out
        $banned_user_ip = $banned_user->data()->lastip;

        // Ban IP
        DB::getInstance()->insert('ip_bans', [
            'ip' => $banned_user_ip,
            'banned_by' => $user->data()->id,
            'banned_at' => date('U'),
            'reason' => 'Spam'
        ]);

        // Ban user
        DB::getInstance()->update('users', $post->post_creator, [
            'isbanned' => true,
        ]);

        if (count($latest_forums)) {
            foreach ($latest_forums as $latest_forum) {
                $forum->updateForumLatestPosts($latest_forum);
            }
        }

        if (count($latest_topics)) {
            foreach ($latest_topics as $latest_topic) {
                $forum->updateTopicLatestPosts($latest_topic, null);
            }
        }

        // Redirect
        Session::flash('spam_info', $language->get('moderator', 'user_marked_as_spam'));
        Redirect::to(URL::build('/forum'));
    } else {
        // Invalid token
        Redirect::to(URL::build('/forum/topic/' . urlencode($post->topic_id), 'pid=' . urlencode($post->id)));
    }
} else {
    // Can't moderate forum
    Redirect::to(URL::build('/forum'));
}
