<?php
if (!$user->isLoggedIn()) {
    die(json_encode(['value' => 0]));
}

$alerts = Alert::getAlerts($user->data()->id);

echo json_encode(['value' => count($alerts), 'alerts' => $alerts]);
