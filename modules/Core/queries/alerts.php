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
 */

if (!$user->isLoggedIn()) {
    die(json_encode(['value' => 0]));
}

$alerts = Alert::getAlerts($user->data()->id ?? null);

echo json_encode(['value' => count($alerts), 'alerts' => $alerts]);
