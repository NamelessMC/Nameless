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

if (count(MemberList::getInstance()->allEnabledLists()) == 0) {
    Redirect::to(URL::build('/'));
}

if (Input::exists()) {
    if (Token::check()) {
        $validation = Validate::check($_POST, [
            'search' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::RATE_LIMIT => [5, 30] // 5 attempts every 30 seconds
            ],
        ]);

        if ($validation->passed()) {
            $search = Input::get('search');
            $result = DB::getInstance()->query('SELECT username FROM nl2_users WHERE username = ? OR nickname = ?', [$search, $search]);
            if ($result->count()) {
                $username = $result->first()->username;
                Redirect::to(URL::build('/profile/' . urlencode($username)));
            } else {
                $error = $member_language->get('members', 'member_list_search_no_results', [
                    'search' => Output::getClean($search),
                ]);
            }
        } else {
            $error = $validation->errors()[0];
        }
    }
}

const PAGE = 'members';
$page_title = $member_language->get('members', 'members');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (isset($_GET['group'])) {
    $viewing_list = 'group';
    $smarty->assign([
        'VIEWING_GROUP' => Group::find($_GET['group']),
    ]);

    $lists = [];
} else {
    $viewing_list = $_GET['list'] ?? 'overview';

    $lists = $viewing_list === 'overview'
        ? MemberList::getInstance()->allEnabledLists()
        : [MemberList::getInstance()->getList($viewing_list)];
}

$new_members = [];
foreach (DB::getInstance()->query('SELECT id FROM nl2_users ORDER BY joined DESC LIMIT 12')->results() as $new_member) {
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
    $groups[] = Group::find($group_id);
}

if ($viewing_list !== 'overview') {
    $member_count = isset($_GET['group'])
        ? MemberList::getInstance()->getList($_GET['group'], true)->getMemberCount()
        : MemberList::getInstance()->getList($viewing_list)->getMemberCount();
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

$smarty->assign([
    'MEMBERS' => $member_language->get('members', 'members'),
    'MEMBER_LISTS' => MemberList::getInstance()->allEnabledLists(),
    'MEMBER_LISTS_VIEWING' => $lists,
    'VIEWING_LIST' => $viewing_list,
    'MEMBER_LIST_URL' => URL::build('/members'),
    'QUERIES_URL' => URL::build('/queries/member_list', 'list={{list}}&page={{page}}&overview=' . ($viewing_list === 'overview' ? 'true' : 'false')),
    'OVERVIEW' => $language->get('user', 'overview'),
    'VIEW_ALL' => $member_language->get('members', 'view_all'),
    'GROUPS' => $groups,
    'VIEW_GROUP_URL' => URL::build('/members', 'group='),
    'NEW_MEMBERS' => $member_language->get('members', 'new_members'),
    'NEW_MEMBERS_VALUE' => $new_members,
    'TOKEN' => Token::get(),
    'FIND_MEMBER' => $member_language->get('members', 'find_member'),
    'NAME' => $member_language->get('members', 'name'),
    'VIEW_GROUP' => $member_language->get('members', 'view_group'),
    'GROUP' => $member_language->get('members', 'group'),
    'NO_MEMBERS_FOUND' => $member_language->get('members', 'no_members'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('members/members.tpl', $smarty);
