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
    die('Not logged in');
}

// Are reactions enabled?
if (Util::getSetting('forum_reactions') !== '1') {
    die('Reactions disabled');
}

// Validate form input
if (!isset($_POST['post'], $_POST['reaction']) || !is_numeric($_POST['post']) || !is_numeric($_POST['reaction'])) {
    die('Invalid input');
}

// Get post information
$post = DB::getInstance()->get('posts', ['id', $_POST['post']])->results();

if (!count($post)) {
    die('Invalid post');
}

$post = $post[0];
$topic_id = $post->topic_id;

// Check user can actually view the post
if (!($forum->forumExist($post->forum_id, $user->getAllGroupIds()))) {
    die('Invalid post');
}

if (!Token::check()) {
    die('Invalid token');
}

// Check if the user has already reacted to this post
$user_reacted = DB::getInstance()->get('forums_reactions', [['post_id', $post->id], ['user_given', $user->data()->id]]);
if ($user_reacted->count()) {
    $reaction = $user_reacted->first();
    if ($reaction->reaction_id == $_POST['reaction']) {
        // Undo reaction
        DB::getInstance()->delete('forums_reactions', ['id', $reaction->id]);
        die('Reaction deleted');
    } else {
        // Change reaction
        DB::getInstance()->update('forums_reactions', $reaction->id, [
            'reaction_id' => $_POST['reaction'],
            'time' => date('U')
        ]);

        die('Reaction changed');
    }
}

// Input new reaction
DB::getInstance()->insert('forums_reactions', [
    'post_id' => $post->id,
    'user_received' => $post->post_creator,
    'user_given' => $user->data()->id,
    'reaction_id' => $_POST['reaction'],
    'time' => date('U')
]);

Log::getInstance()->log(Log::Action('forums/react'), $_POST['reaction']);

die('Reaction added');
