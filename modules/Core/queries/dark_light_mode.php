<?php
// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    Cookie::put(
        'night_mode',
        Cookie::get('night_mode') == '1' ? '0' : '1',
        time() + (10 * 365 * 24 * 60 * 60)
    );
} else {
    if (!Token::check()) {
        die(json_encode(['error' => 'Invalid token']));
    }

    $user->update([
        'night_mode' => $user->data()->night_mode == '1' ? '0' : '1'
    ]);
}

die(json_encode(['success' => true]));
