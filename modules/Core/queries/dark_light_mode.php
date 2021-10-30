<?php
// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Unauthenticated']));
}

if (!Token::check()) {
    die(json_encode(['error' => 'Invalid token']));
}

$user->update([
    'night_mode' => $user->data()->night_mode == '1' ? '0' : '1'
]);

die(json_encode(['success' => true]));
