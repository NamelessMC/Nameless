<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Get a list of quotes
 */

if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Not logged in']));
}

// Always define page name
const PAGE = 'forum';

// Initialise
$forum = new Forum();

// Get the post data
if (empty($_GET)) {
    die(json_encode(['error' => 'No post data']));
}

$post = $forum->getIndividualPost($_GET['post']);

$content = $post['content'];

$post_author = new User($post['creator']);

die(json_encode([
    'content' => Output::getPurified($content),
    'author_nickname' => $post_author->getDisplayname(),
    'link' => URL::build('/forum/topic/' . urlencode($post['topic_id']), 'pid=' . urlencode($item))
]));
