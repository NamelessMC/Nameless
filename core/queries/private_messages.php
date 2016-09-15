<?php
$path = '../../';
$page = 'query_pms';
require_once('../init.php');

if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
	die();
}

$pms = $queries->getWhere('private_messages_users', array('user_id', '=', $_GET['uid']));

foreach($pms as $pm){
	if($pm->read != 1){
		$unread[] = $pm;
	}
}

echo json_encode(array("value" => count($unread)));