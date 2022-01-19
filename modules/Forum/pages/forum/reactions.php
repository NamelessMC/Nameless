<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  React to a post
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

// Are reactions enabled?
$reactions_enabled = $queries->getWhere('settings', ['name', '=', 'forum_reactions']);
if ($reactions_enabled[0]->value != '1') {
    Redirect::to(URL::build('/forum'));
    die();
}

// Deal with input
if (Input::exists()) {
    // Validate form input
    if (!isset($_POST['post'], $_POST['reaction']) || !is_numeric($_POST['post']) || !is_numeric($_POST['reaction'])) {
        Redirect::to(URL::build('/forum'));
        die();
    }

    // Get post information
    $post = $queries->getWhere('posts', ['id', '=', $_POST['post']]);

    if (!count($post)) {
        Redirect::to(URL::build('/forum'));
        die();
    }

    $post = $post[0];
    $topic_id = $post->topic_id;

    // Check user can actually view the post
    if (!($forum->forumExist($post->forum_id, $user->getAllGroupIds()))) {
        Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
        die();
    }

    if (Token::check()) {
        // Check if the user has already reacted to this post
        $user_reacted = $queries->getWhere('forums_reactions', ['post_id', '=', $post->id]);
        if (count($user_reacted)) {
            foreach ($user_reacted as $reaction) {
                if ($reaction->user_given == $user->data()->id) {
                    if ($reaction->reaction_id == $_POST['reaction']) {
                        // Undo reaction
                        $queries->delete('forums_reactions', ['id', '=', $reaction->id]);
                    } else {
                        // Change reaction
                        $queries->update('forums_reactions', $reaction->id, [
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
            $queries->create('forums_reactions', [
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
    Redirect::to(URL::build('/forum/topic/' . $topic_id, 'pid=' . $post->id));
} else {
    Redirect::to(URL::build('/forum'));
}
die();
