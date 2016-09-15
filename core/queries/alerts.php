<?php
$path = '../../';
$page = 'query_alerts';
require_once('../init.php');

if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
	die();
}

$alerts = $queries->getWhere('alerts', array('user_id', '=', $_GET['uid']));

foreach($alerts as $alert){
	if($alert->read != 1){
		$unread[] = $alert;
	}
}

echo json_encode(array("value" => count($unread)));