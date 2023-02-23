<?php

header('Content-type: application/json;charset=utf-8');

$list = $_GET['list'];
$overview = isset($_GET['overview']) && $_GET['overview'] === 'true';

$cache->setCache('member_list_queries');
if ($cache->isCached($key = $list . ($overview ? '_overview' : ''))) {
    die($cache->retrieve($key));
}

if (str_starts_with($list, 'group_')) {
    $members = MemberList::getInstance()->getMembersInGroup((int) substr($list, 6));
} else {
    $members = MemberList::getInstance()->getList($list)->getMembers($overview);
}

$members = json_encode($members);

$cache->store($key, $members, 60);

die($members);
