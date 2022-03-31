<?php
// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

// Get website dark mode setting value
$cache->setCache('template_settings');
$darkMode = $cache->isCached('darkMode') ? $cache->retrieve('darkMode') : '0';

if (!$user->isLoggedIn()) {
    if (Cookie::exists('night_mode')) {
        $darkMode = Cookie::get('night_mode') == '1' ? '0' : '1';
    } else {
        $darkMode = $darkMode != '1' ? '1' : '0';
    }

    Cookie::put(
        'night_mode',
        $darkMode,
        time() + (10 * 365 * 24 * 60 * 60)
    );
} else {
    if (!Token::check()) {
        die(json_encode(['error' => 'Invalid token']));
    }

    if ($user->data()->night_mode === null) {
        $darkMode = $darkMode != '1' ? '1' : '0';
    } else {
        $darkMode = $user->data()->night_mode == '1' ? '0' : '1';
    }

    $user->update([
        'night_mode' => $darkMode
    ]);
}

die(json_encode(['success' => true]));
