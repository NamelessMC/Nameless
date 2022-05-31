<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  React to a post
 */

$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
}

// Are reactions enabled?
if (Util::getSetting('forum_reactions') !== '1') {
    Redirect::to(URL::build('/forum'));
}

// Deal with input
if (Input::exists()) {
    // Validate form input
    if (!isset($_POST['post'], $_POST['reaction']) || !is_numeric($_POST['post']) || !is_numeric($_POST['reaction'])) {
        Redirect::to(URL::build('/forum'));
    }

    // Get post information
    $post = DB::getInstance()->get('posts', ['id', $_POST['post']])->results();

    if (!count($post)) {
        Redirect::to(URL::build('/forum'));
    }

    $post = $post[0];
    $topic_id = $post->topic_id;

    // Check user can actually view the post
    if (!($forum->forumExist($post->forum_id, $user->getAllGroupIds()))) {
        Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    }

    if (Token::check()) {
        // Check if the user has already reacted to this post
        $user_reacted = DB::getInstance()->get('forums_reactions', ['post_id', $post->id])->results();
        if (count($user_reacted)) {
            foreach ($user_reacted as $reaction) {
                if ($reaction->user_given == $user->data()->id) {
                    if ($reaction->reaction_id == $_POST['reaction']) {
                        // Undo reaction
                        DB::getInstance()->delete('forums_reactions', ['id', $reaction->id]);
                    } else {
                        // Change reaction
                        DB::getInstance()->update('forums_reactions', $reaction->id, [
                            'reaction_id' => $_POST['reaction'],
                            'time' => date('U')
                        ]);
                    }

                    $changed = true;
                    break;
                }
            }
        }

        if (!isset($changed)) {
            // Input new reaction
            DB::getInstance()->insert('forums_reactions', [
                'post_id' => $post->id,
                'user_received' => $post->post_creator,
                'user_given' => $user->data()->id,
                'reaction_id' => $_POST['reaction'],
                'time' => date('U')
            ]);

            Log::getInstance()->log(Log::Action('forums/react'), $_POST['reaction']);
        }

        // Redirect
    }
    Redirect::to(URL::build('/forum/topic/' . urlencode($topic_id), 'pid=' . urlencode($post->id)));
} else {
    Redirect::to(URL::build('/forum'));
}
