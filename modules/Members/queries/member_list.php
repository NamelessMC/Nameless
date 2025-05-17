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
$key = ($list . '_page_' . $page) . ($overview ? '_overview' : '') . (Settings::get('member_list_hide_banned', false, 'Members') ? '_hide_banned' : '');

$members = $cache->fetch($key, function () use ($list, $overview, $page) {
    if (str_starts_with($list, 'group_')) {
        $members = MemberListManager::getInstance()->getList((int) substr($list, 6), true)->getMembers(false, $page);
    } else {
        $members = MemberListManager::getInstance()->getList($list)->getMembers($overview, $page);
    }

    return json_encode($members);
}, 60);

die($members);
