<?php
// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Unauthenticated']));
}

if (!Token::check()) {
    die(json_encode(['error' => 'Invalid token']));
}

$user->data()->night_mode = !$user->data()->night_mode;

die(json_encode(['success' => true]));
