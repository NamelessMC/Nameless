<?php

if (!$user->handlePanelPageLoad('admincp.core.members')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'member_list';

$page_title = $language->get('admin', 'member_list');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        $list = MemberList::getInstance()->getList($_POST['list']);
        $enabled = DB::getInstance()->get('member_lists', ['name', $list->getName()])->first()->enabled;
        DB::getInstance()->update('member_lists', ['name', $list->getName()], [
            'enabled' => !$enabled
        ]);

        Session::flash('admin_member_list_success', $language->get('admin', 'member_list_toggled', [
            'list' => $list->getFriendlyName(),
            'value' => strtolower($language->get('admin', $enabled ? 'disabled' : 'enabled')),
        ]));
        Redirect::to(URL::build('/panel/core/members'));
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

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'MEMBER_LIST' => $language->get('admin', 'member_list'),
    'PAGE' => PANEL_PAGE,
    'MEMBER_LISTS' => MemberList::getInstance()->allLists(),
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
$template->displayTemplate('core/members.tpl', $smarty);
