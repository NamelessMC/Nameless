<?php

if (!$user->handlePanelPageLoad('admincp.members')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'member_lists';

$page_title = $member_language->get('members', 'member_lists');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        if (Input::get('action') == 'update_groups') {
            Util::setSetting('member_list_viewable_groups', json_encode(Input::get('groups')), 'Members');
            die();
        }

        if (Input::get('action') === 'toggle_hide_banned_users') {
            Util::setSetting('member_list_hide_banned', Input::get('hide_banned_users'), 'Members');
        } else {
            $list = MemberListManager::getInstance()->getList($_POST['list']);
            $enabled = DB::getInstance()->get('member_lists', ['name', $list->getName()])->first()->enabled;
            DB::getInstance()->update('member_lists', ['name', $list->getName()], [
                'enabled' => !$enabled
            ]);

            Session::flash('admin_member_list_success', $member_language->get('members', !$enabled ? 'member_list_toggled_enabled' : 'member_list_toggled_disabled', [
                'list' => $list->getFriendlyName(),
            ]));
        }
        Redirect::to(URL::build('/panel/core/member_lists'));
    } else {
        Session::flash('admin_member_list_error', $language->get('general', 'invalid_token'));
    }
}

if (Session::exists('admin_member_list_error')) {
    $smarty->assign([
        'ERRORS' => Session::flash('admin_member_list_error'),
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

if (Session::exists('admin_member_list_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('admin_member_list_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

$group_array = [];
foreach (Group::all() as $group) {
    $group_array[] = [
        'id' => $group->id,
        'name' => Output::getClean($group->name),
    ];
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'MEMBER_LISTS' => $member_language->get('members', 'member_lists'),
    'PAGE' => PANEL_PAGE,
    'HIDE_BANNED_USERS' => $member_language->get('members', 'member_list_hide_banned_users'),
    'HIDE_BANNED_USERS_VALUE' => Util::getSetting('member_list_hide_banned', false, 'Members'),
    'GROUPS' => $member_language->get('members', 'viewable_groups'),
    'GROUPS_ARRAY' => $group_array,
    'GROUPS_VALUE' => json_decode(Util::getSetting('member_list_viewable_groups', '{}', 'Members'), true),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
    'SELECT_CHANGE_URL' => URL::build('/panel/core/member_lists'),
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
