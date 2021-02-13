<?php
if (!$user->isLoggedIn()) {
    die(json_encode(array("value" => 0)));
}

$pms = Alert::getPMs($user->data()->id);

echo json_encode(array("value" => count($pms), "pms" => $pms));
