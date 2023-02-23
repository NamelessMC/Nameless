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
        $search = Input::get('search');
        if (strlen($search) < 2) {
            $error = $member_language->get('members', 'member_list_search_min_2_chars');
        } else {
            $result = DB::getInstance()->query('SELECT username FROM nl2_users WHERE username = ? OR nickname = ?', [$search, $search]);
            if ($result->count()) {
                $username = $result->first()->username;
                Redirect::to(URL::build('/profile/' . urlencode($username)));
            } else {
                $error = $member_language->get('members', 'member_list_search_no_results', [
                    'search' => Output::getClean($search),
                ]);
            }
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

$smarty->assign([
    'MEMBERS' => $member_language->get('members', 'members'),
    'MEMBER_LISTS' => MemberList::getInstance()->allEnabledLists(),
    'MEMBER_LISTS_VIEWING' => $lists,
    'VIEWING_LIST' => $viewing_list,
    'MEMBER_LIST_URL' => URL::build('/members'),
    'QUERIES_URL' => URL::build('/queries/member_list', 'list={{list}}&overview=' . ($viewing_list === 'overview' ? 'true' : 'false')),
    'OVERVIEW' => $language->get('user', 'overview'),
    'VIEW_ALL' => $member_language->get('members', 'view_all'),
    'GROUPS' => Group::all(),
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
