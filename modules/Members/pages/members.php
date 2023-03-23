<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Member list page
 */

const PAGE = 'members';
$page_title = $members_language->get('members', 'members');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (isset($_GET['group'])) {
    if (!in_array($_GET['group'], json_decode(Util::getSetting('member_list_viewable_groups', '{}', 'Members'), true))) {
        Redirect::to(URL::build('/members'));
    }

    $viewing_list = 'group';
    $viewing_group = Group::find($_GET['group']);
    $smarty->assign([
        'VIEWING_GROUP' => [
            'id' => $viewing_group->id,
            'name' => Output::getClean($viewing_group->name),
        ],
    ]);

    $lists_viewing = [];
} else {
    $viewing_list = $_GET['list'] ?? 'overview';
    if ($viewing_list !== 'overview' && !MemberListManager::getInstance()->getList($viewing_list)->isEnabled()) {
        Redirect::to(URL::build('/members'));
    }

    $lists_viewing = $viewing_list === 'overview'
        ? array_filter(MemberListManager::getInstance()->allEnabledLists(), static fn (MemberListProvider $list) => $list->displayOnOverview())
        : [MemberListManager::getInstance()->getList($viewing_list)];
}

$new_members = [];
if (Util::getSetting('member_list_hide_banned', false, 'Members')) {
    $query = DB::getInstance()->query('SELECT id FROM nl2_users WHERE isbanned = 0 ORDER BY joined DESC LIMIT 12');
} else {
    $query = DB::getInstance()->query('SELECT id FROM nl2_users ORDER BY joined DESC LIMIT 12');
}
foreach ($query->results() as $new_member) {
    $new_members[] = new User($new_member->id);
}

if (isset($error)) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERROR' => $error,
    ]);
}

$groups = [];
foreach (json_decode(Util::getSetting('member_list_viewable_groups', '{}', 'Members'), true) as $group_id) {
    $group = Group::find($group_id);
    if (!$group) {
        continue;
    }
    $groups[] = [
        'id' => $group->id,
        'name' => Output::getClean($group->name),
    ];
}

if ($viewing_list !== 'overview') {
    $member_count = isset($_GET['group'])
        ? MemberListManager::getInstance()->getList($_GET['group'], true)->getMemberCount()
        : MemberListManager::getInstance()->getList($viewing_list)->getMemberCount();
    $url_param = isset($_GET['group'])
        ? 'group=' . $_GET['group']
        : 'list=' . $viewing_list;

    $template_pagination['div'] = $template_pagination['div'] .= ' centered';
    $paginator = new Paginator(
        $template_pagination ?? null,
        $template_pagination_left ?? null,
        $template_pagination_right ?? null
    );
    $paginator->setValues($member_count, 20, $_GET['p'] ?? 1);
    $smarty->assign([
        'PAGINATION' => $paginator->generate(6, URL::build('/members/', $url_param)),
    ]);
}

// Sort sidebar lists to have displayOnOverview lists first
$sidebar_lists = MemberListManager::getInstance()->allEnabledLists();
usort($sidebar_lists, static function ($a, $b) {
    return $b->displayOnOverview() - $a->displayOnOverview();
});

$smarty->assign([
    'MEMBERS' => $members_language->get('members', 'members'),
    'SIDEBAR_MEMBER_LISTS' => $sidebar_lists,
    'MEMBER_LISTS_VIEWING' => $lists_viewing,
    'VIEWING_LIST' => $viewing_list,
    'MEMBER_LIST_URL' => URL::build('/members'),
    'QUERIES_URL' => URL::build('/queries/member_list', 'list={{list}}&page={{page}}&overview=' . ($viewing_list === 'overview' ? 'true' : 'false')),
    'OVERVIEW' => $language->get('user', 'overview'),
    'VIEW_ALL' => $members_language->get('members', 'view_all'),
    'GROUPS' => $groups,
    'VIEW_GROUP_URL' => URL::build('/members', 'group='),
    'NEW_MEMBERS' => $members_language->get('members', 'new_members'),
    'NEW_MEMBERS_VALUE' => $new_members,
    'FIND_MEMBER' => $members_language->get('members', 'find_member'),
    'NAME' => $members_language->get('members', 'name'),
    'SEARCH_URL' => URL::build('/queries/users'),
    'NO_RESULTS_HEADER' => $members_language->get('members', 'no_results_header'),
    'NO_RESULTS_TEXT' => $members_language->get('members', 'no_results_text'),
    'VIEW_GROUP' => $members_language->get('members', 'view_group'),
    'GROUP' => $members_language->get('members', 'group'),
    'NO_MEMBERS_FOUND' => $members_language->get('members', 'no_members'),
    'NO_OVERVIEW_LISTS_ENABLED' => $members_language->get('members', 'no_overview_lists_enabled'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('members/members.tpl', $smarty);
