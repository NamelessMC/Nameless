<?php

header('Content-type: application/json;charset=utf-8');

$provider = $_GET['list'];
$only = isset($_GET['only']) && $_GET['only'] === 'true';

$provider = MemberList::getInstance()->getList($provider);

die(json_encode($provider->getMembers($only)));
