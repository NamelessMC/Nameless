<?php
/**
 * Made by UNKNOWN
 * https://github.com/NamelessMC/Nameless/
 * NamelessMC version UNKNOWN
 *
 * License: MIT
 *
 * TODO: Add description
 *
 * @var User $user
 * @var Cache $cache
 */

// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

// Get website dark mode setting value
$cache->setCache('template_settings');
$darkMode = $cache->isCached('darkMode') ? $cache->retrieve('darkMode') : '0';

// TODO: Why are the values reversed?
if (!$user->isLoggedIn()) {
    if (Cookie::exists('night_mode')) {
        $darkMode = Cookie::get('night_mode') === '1' ? '0' : '1';
    } else {
        $darkMode = $darkMode === '1' ? '0' : '1';
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
        $darkMode = $darkMode === '1' ? '0' : '1';
    } else {
        $darkMode = $user->data()->night_mode === true ? '0' : '1';
    }

    $user->update([
        'night_mode' => $darkMode
    ]);
}

die(json_encode(['success' => true]));
