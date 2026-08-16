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
        $group = (int) substr($list, 6);

        if (!in_array($group, json_decode(Settings::get('member_list_viewable_groups', '{}', 'Members'), true))) {
            throw new RuntimeException("Member list '$list' is not viewable");
        }

        $memberList = MemberListManager::getInstance()->getList($group, true);
        $useOverview = false;
    } else {
        $memberList = MemberListManager::getInstance()->getList($list);
        $useOverview = $overview;

        if (!$memberList->isEnabled()) {
            throw new RuntimeException("Member list '$list' is not enabled");
        }
    }

    $members = $memberList->getMembers($useOverview, $page);

    return json_encode($members);
}, 60);

die($members);
