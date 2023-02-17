<?php

header('Content-type: application/json;charset=utf-8');

$provider = $_GET['list'];

$provider = MemberList::getInstance()->getList($provider);

die(json_encode($provider->getMembers(
    isset($_GET['overview']) && $_GET['overview'] === 'true'
)));
