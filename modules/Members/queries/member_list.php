<?php

header('Content-type: application/json;charset=utf-8');

$list = $_GET['list'];

if (str_starts_with($list, 'group_')) {
    die(json_encode(
        MemberList::getInstance()->getMembersInGroup((int) substr($list, 6))
    ));
}

$provider = MemberList::getInstance()->getList($list);

die(json_encode($provider->getMembers(
    isset($_GET['overview']) && $_GET['overview'] === 'true'
)));
