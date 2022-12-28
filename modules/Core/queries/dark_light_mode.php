<?php
declare(strict_types=1);
/**
 *  Made by Unknown
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  TODO: Add description
 *
 * @var User $user
 * @var Cache $cache
 */

// Toggle dark/light mode
header('Content-type: application/json;charset=utf-8');

// Get website dark mode setting value
$cache->setCacheName('template_settings');
$darkMode = $cache->hasCashedData('darkMode') ? (string)$cache->retrieve('darkMode') : '0';

if (!$user->isLoggedIn()) {
    if (Cookie::exists('night_mode')) {
        $darkMode = (string)Cookie::get('night_mode') === '1' ? '0' : '1';
    } else {
        $darkMode = $darkMode !== '1' ? '1' : '0';
    }

    Cookie::put(
        'night_mode',
        $darkMode,
        time() + (10 * 365 * 24 * 60 * 60)
    );
} else {
    try {
        if (!Token::check()) {
            die(json_encode(['error' => 'Invalid token']));
        }
    } catch (Exception $ignored) {
    }

    if ($user->data()->night_mode === null) {
        $darkMode = $darkMode !== '1' ? '1' : '0';
    } else {
        $darkMode = $user->data()->night_mode === true ? '0' : '1';
    }

    try {
        $user->update([
            'night_mode' => $darkMode
        ]);
    } catch (Exception $ignored) {
    }
}

die(json_encode(['success' => true]));
