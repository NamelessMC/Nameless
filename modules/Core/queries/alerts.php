<?php
if (!$user->isLoggedIn()) {
    die(json_encode(array("value" => 0)));
}

$alerts = Alert::getAlerts($user->data()->id);

echo json_encode(array("value" => count($alerts), "alerts" => $alerts));
