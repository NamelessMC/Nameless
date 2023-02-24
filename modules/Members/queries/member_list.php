<?php

header('Content-type: application/json;charset=utf-8');

$list = $_GET['list'];
$overview = isset($_GET['overview']) && $_GET['overview'] === 'true';
if (!$overview) {
    $page = $_GET['page'] ?? 1;
} else {
    $page = 1;
}

$cache->setCache('member_list_queries');
$key = ($list . '_page_' . $page) . ($overview ? '_overview' : '');
if ($cache->isCached($key)) {
    die($cache->retrieve($key));
}

if (str_starts_with($list, 'group_')) {
    $members = MemberList::getInstance()->getList((int) substr($list, 6), true)->getMembers(false, $page);
} else {
    $members = MemberList::getInstance()->getList($list)->getMembers($overview, $page);
}

$members = json_encode($members);

$cache->store($key, $members, 60);

die($members);
