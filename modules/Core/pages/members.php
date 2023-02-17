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

const PAGE = 'members';
$page_title = $language->get('general', 'members');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$viewing_list = $_GET['list'] ?? 'overview';

$lists = $viewing_list === 'overview'
    ? MemberList::getInstance()->allEnabledLists()
    : [MemberList::getInstance()->getList($viewing_list)];

$smarty->assign([
    'MEMBERS' => $language->get('general', 'members'),
    'MEMBER_LISTS' => MemberList::getInstance()->allEnabledLists(),
    'MEMBER_LISTS_VIEWING' => $lists,
    'VIEWING_LIST' => $viewing_list,
    'MEMBER_LIST_URL' => URL::build('/members'),
    'QUERIES_URL' => URL::build('/queries/member_list', 'list={{list}}&overview=' . ($viewing_list === 'overview' ? 'false' : 'true')),
    'OVERVIEW' => $language->get('user', 'overview'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('members.tpl', $smarty);
