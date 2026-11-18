<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Delete post page
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
}

// Always define page name
const PAGE = 'forum';

$forum = new Forum();

// Check params are set
if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    Redirect::to(URL::build('/forum'));
}

// Get post and forum ID
$post = DB::getInstance()->get('posts', ['id', $_GET['pid']])->results();
if (!count($post)) {
    Redirect::to(URL::build('/forum'));
}
$post = $post[0];

$forum_id = $post->forum_id;

if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            $post_id = (int) $post->id;
            $topic_id = (int) $post->topic_id;

            if (isset($_POST['tid'])) {
                // Is it the OP?
                if (isset($_POST['number']) && Input::get('number') == 10) {

                    DB::getInstance()->update('topics', $topic_id, [
                        'deleted' => true,
                    ]);

                    Log::getInstance()->log(Log::Action('forums/post/delete'), $topic_id);
                    $opening_post = 1;

                    $redirect = URL::build('/forum'); // Create a redirect string
                } else {
                    $redirect = URL::build('/forum/topic/' . $topic_id);
                }
            } else {
                $redirect = URL::build('/forum');
            }

            DB::getInstance()->update('posts', $post_id, [
                'deleted' => true,
            ]);

            if (isset($opening_post)) {
                $posts = DB::getInstance()->get('posts', ['topic_id', $topic_id])->results();

                if (count($posts)) {
                    foreach ($posts as $topic_post) {
                        DB::getInstance()->update('posts', $topic_post->id, [
                            'deleted' => true,
                        ]);
                        Log::getInstance()->log(Log::Action('forums/post/delete'), $topic_post->id);
                    }
                }
            }

            // Update latest posts in categories
            $forum->updateForumLatestPosts($forum_id);
            $forum->updateTopicLatestPosts($topic_id, $forum_id);

            Redirect::to($redirect);

        } else {
            Redirect::to(URL::build('/forum/topic/' . (int) $post->topic_id));
        }
    } else {
        echo 'No post selected';
    }
} else {
    Redirect::to(URL::build('/forum'));
}
die();
