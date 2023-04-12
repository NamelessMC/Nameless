<?php

if (!$user->handlePanelPageLoad('admincp.members')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'members';
const PANEL_PAGE = 'members_settings';

$page_title = $members_language->get('members', 'members');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        Util::setSetting('member_list_viewable_groups', json_encode(Input::get('groups')), 'Members');
        Util::setSetting('member_list_hide_banned', Input::get('hide_banned_users'), 'Members');

        // Update link location
        if (isset($_POST['link_location'])) {
            switch ($_POST['link_location']) {
                case 1:
                case 2:
                case 3:
                case 4:
                    $location = $_POST['link_location'];
                    break;
                default:
                    $location = 1;
            }
        } else {
            $location = 1;
        }

        // Update Link location cache
        $cache->setCache('nav_location');
        $cache->store('members_location', $location);

        Session::flash('admin_members_settings', $members_language->get('members', 'settings_updated_successfully'));
    } else {
        // Invalid token
        Session::flash('admin_members_settings_error', $language->get('general', 'invalid_token'));
    }
    Redirect::to(URL::build('/panel/members/settings'));
}

// Retrieve Link Location from cache
$cache->setCache('nav_location');
$link_location = $cache->retrieve('members_location');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_members_settings')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('admin_members_settings'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('admin_members_settings_error')) {
    $smarty->assign([
        'ERROR' => Session::flash('admin_members_settings_error'),
        'ERRORS_TITLE' => $language->get('general', 'success')
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
    'MEMBERS' => $members_language->get('members', 'members'),
    'SETTINGS' => $language->get('admin', 'settings'),
    'LINK_LOCATION' => $language->get('admin', 'page_link_location'),
    'LINK_LOCATION_VALUE' => $link_location,
    'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
    'LINK_MORE' => $language->get('admin', 'page_link_more'),
    'LINK_FOOTER' => $language->get('admin', 'page_link_footer'),
    'LINK_NONE' => $language->get('admin', 'page_link_none'),
    'HIDE_BANNED_USERS' => $members_language->get('members', 'member_list_hide_banned_users'),
    'HIDE_BANNED_USERS_VALUE' => Util::getSetting('member_list_hide_banned', false, 'Members'),
    'GROUPS' => $members_language->get('members', 'viewable_groups'),
    'GROUPS_ARRAY' => $group_array,
    'GROUPS_VALUE' => json_decode(Util::getSetting('member_list_viewable_groups', '{}', 'Members'), true) ?: [],
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('members/members_settings.tpl', $smarty);
