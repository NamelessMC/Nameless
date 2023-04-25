<?php

if (!$user->handlePanelPageLoad('admincp.members')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'members';
const PANEL_PAGE = 'member_lists_settings';

$page_title = $members_language->get('members', 'member_lists');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        $list = MemberListManager::getInstance()->getList($_POST['list']);
        $enabled = DB::getInstance()->get('member_lists', ['name', $list->getName()])->first()->enabled;
        DB::getInstance()->update('member_lists', ['name', $list->getName()], [
            'enabled' => !$enabled
        ]);

        Session::flash('admin_member_lists_success', $members_language->get('members', !$enabled ? 'member_list_toggled_enabled' : 'member_list_toggled_disabled', [
            'list' => $list->getFriendlyName(),
        ]));

        Redirect::to(URL::build('/panel/members/member_lists'));
    } else {
        Session::flash('admin_member_lists_error', $language->get('general', 'invalid_token'));
    }
}

if (Session::exists('admin_member_lists_error')) {
    $smarty->assign([
        'ERRORS' => [Session::flash('admin_member_lists_error')],
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

if (Session::exists('admin_member_lists_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('admin_member_lists_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'MEMBERS' => $members_language->get('members', 'members'),
    'MEMBER_LISTS' => $members_language->get('members', 'member_lists'),
    'PAGE' => PANEL_PAGE,
    'MEMBER_LISTS_VALUES' => MemberListManager::getInstance()->allLists(),
    'NAME' => $language->get('admin', 'name'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'MODULE' => $language->get('admin', 'module'),
    'EDIT' => $language->get('general', 'edit'),
    'ENABLE' => $language->get('admin', 'enable'),
    'DISABLE' => $language->get('admin', 'disable'),
    'TOKEN' => Token::get(),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('members/member_lists.tpl', $smarty);
