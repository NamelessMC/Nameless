<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel groups page
 */

if (!$user->handlePanelPageLoad('admincp.groups')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'groups';
const PANEL_PAGE = 'groups';
$page_title = $language->get('admin', 'groups');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_groups')) {
    $success = Session::flash('admin_groups');
}

if (Session::exists('admin_groups_error')) {
    $errors = [Session::flash('admin_groups_error')];
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'new':
            if (Input::exists()) {

                $errors = [];

                if (Token::check()) {
                    $validation = Validate::check($_POST, [
                        'groupname' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 2,
                            Validate::MAX => 20
                        ],
                        'html' => [
                            Validate::MAX => 1024
                        ]
                    ])->messages([
                        'groupname' => [
                            Validate::REQUIRED => $language->get('admin', 'group_name_required'),
                            Validate::MIN => $language->get('admin', 'group_name_minimum'),
                            Validate::MAX => $language->get('admin', 'group_name_maximum')
                        ],
                        'html' => $language->get('admin', 'html_maximum')
                    ]);

                    if ($validation->passed()) {
                        try {
                            if (isset($_POST['default']) && $_POST['default'] == 1) {
                                $default = 1;
                            } else {
                                $default = 0;
                            }

                            // If this is the new default group, update old default group
                            $default_group = Group::find(1, 'default_group');
                            if (!$default_group && $default == 0) {
                                $default = 1;
                            }

                            $last_group_order = DB::getInstance()->query('SELECT `order` FROM nl2_groups ORDER BY `order` DESC LIMIT 1')->results();
                            if (count($last_group_order)) {
                                $last_group_order = $last_group_order[0]->order;
                            } else {
                                $last_group_order = 0;
                            }

                            DB::getInstance()->insert('groups', [
                                'name' => Input::get('groupname'),
                                'group_html' => Input::get('html'),
                                'group_username_color' => ($_POST['username_style'] ? Input::get('username_style') : null),
                                'group_username_css' => ($_POST['username_css'] ? Input::get('username_css') : null),
                                'admin_cp' => Input::get('staffcp'),
                                'staff' => Input::get('staff'),
                                'default_group' => $default,
                                'order' => (Input::get('order') == 5 ? $last_group_order + 1 : Input::get('order')),
                                'force_tfa' => Input::get('tfa'),
                                'permissions' => '{}',
                            ]);

                            $group_id = DB::getInstance()->lastId();

                            if ($default == 1) {
                                if ($default_group && $default_group->id != $group_id) {
                                    DB::getInstance()->update('groups', $default_group->id, [
                                        'default_group' => false
                                    ]);
                                }

                                $cache->setCache('default_group');
                                $cache->store('default_group', $group_id);
                            }

                            $cache->setCache('groups_tfa_' . $group_id);
                            $cache->store('enabled', Input::get('tfa'));

                            Session::flash('admin_groups', $language->get('admin', 'group_created_successfully'));
                            Redirect::to(URL::build('/panel/core/groups'));
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    } else {
                        $errors = $validation->errors();
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'CREATING_NEW_GROUP' => $language->get('admin', 'creating_group'),
                'CANCEL' => $language->get('general', 'cancel'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CANCEL_LINK' => URL::build('/panel/core/groups'),
                'NAME' => $language->get('admin', 'name'),
                'GROUP_HTML' => $language->get('admin', 'group_html'),
                'GROUP_USERNAME_COLOUR' => $language->get('admin', 'group_username_colour'),
                'GROUP_USERNAME_CSS' => $language->get('admin', 'group_username_css'),
                'GROUP_ORDER' => $language->get('admin', 'group_order'),
                'STAFF_GROUP' => $language->get('admin', 'group_staff'),
                'STAFF_CP' => $language->get('admin', 'can_view_staffcp'),
                'DEFAULT_GROUP' => $language->get('admin', 'default_group'),
                'FORCE_TFA' => $language->get('admin', 'force_tfa')
            ]);

            $template_file = 'core/groups_new.tpl';

            break;

        case 'edit':
            if (!isset($_GET['group']) || !is_numeric($_GET['group'])) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            $group = Group::find($_GET['group']);
            if (!$group) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            if ($group->id == 2 || ((in_array($group->id, $user->getAllGroupIds())) && !$user->hasPermission('admincp.groups.self'))) {
                $smarty->assign([
                    'OWN_GROUP' => $language->get('admin', 'cant_edit_this_group'),
                    'INFO' => $language->get('general', 'info')
                ]);
            } else {
                $smarty->assign([
                    'PERMISSIONS' => $language->get('admin', 'permissions'),
                    'PERMISSIONS_LINK' => URL::build('/panel/core/groups/', 'action=permissions&group=' . urlencode($group->id)),
                    'DELETE' => $language->get('general', 'delete'),
                    'DELETE_GROUP' => $language->get('admin', 'delete_group'),
                    'CONFIRM_DELETE' => $language->get('admin', 'confirm_group_deletion', ['group' => Output::getClean($group->name)]),
                ]);
            }

            if (Input::exists()) {
                $errors = [];
                if (Token::check()) {
                    if (Input::get('action') == 'update') {
                        $validation = Validate::check($_POST, [
                            'groupname' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 20
                            ],
                            'html' => [
                                Validate::MAX => 1024
                            ]
                        ])->messages([
                            'groupname' => [
                                Validate::REQUIRED => $language->get('admin', 'group_name_required'),
                                Validate::MIN => $language->get('admin', 'group_name_minimum'),
                                Validate::MAX => $language->get('admin', 'group_name_maximum')
                            ],
                            'html' => $language->get('admin', 'html_maximum')
                        ]);

                        if ($validation->passed()) {
                            try {
                                if (isset($_POST['default']) && $_POST['default'] == 1) {
                                    $default = 1;
                                    $cache->setCache('default_group');
                                    $cache->store('default_group', $_GET['group']);
                                } else {
                                    $default = 0;
                                }

                                // If this is the new default group, update old default group
                                $default_group = Group::find(1, 'default_group');
                                if ($default_group && $default == 1 && $default_group->id != $_GET['group']) {
                                    DB::getInstance()->update('groups', $default_group->id, [
                                        'default_group' => false
                                    ]);
                                } else {
                                    if (!$default_group && $default == 0) {
                                        $default = 1;
                                    }
                                }

                                if ($group->id == 2) {
                                    $staff_cp = 1;
                                } else {
                                    $staff_cp = Input::get('staffcp');
                                }

                                DB::getInstance()->update('groups', $_GET['group'], [
                                    'name' => Input::get('groupname'),
                                    'group_html' => Input::get('html'),
                                    'group_username_color' => ($_POST['username_style'] ? Input::get('username_style') : null),
                                    'group_username_css' => ($_POST['username_css'] ? Input::get('username_css') : null),
                                    'admin_cp' => $staff_cp,
                                    'staff' => Input::get('staff'),
                                    'default_group' => $default,
                                    'order' => Input::get('order'),
                                    'force_tfa' => Input::get('tfa')
                                ]);

                                $cache->setCache('groups_tfa_' . $_GET['group']);
                                $cache->store('enabled', Input::get('tfa'));

                                Session::flash('admin_groups', $language->get('admin', 'group_updated_successfully'));
                                Redirect::to(URL::build('/panel/core/groups/', 'action=edit&group=' . urlencode($_GET['group'])));
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors = $validation->errors();
                        }
                    } else {
                        if (Input::get('action') == 'delete') {
                            try {
                                $default_group = Group::find(1, 'default_group');

                                if ($default_group) {
                                    if ($group->id == 2 || $default_group->id == Input::get('id') || $group->admin_cp == 1) {
                                        // Can't delete default group/admin group
                                        Session::flash('admin_groups_error', $language->get('admin', 'unable_to_delete_group'));
                                    } else {
                                        DB::getInstance()->delete('groups', ['id', Input::get('id')]);
                                        DB::getInstance()->delete('users_groups', ['group_id', Input::get('id')]);
                                        Session::flash('admin_groups', $language->get('admin', 'group_deleted_successfully'));
                                    }
                                }

                                Redirect::to(URL::build('/panel/core/groups'));
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        }
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'GROUP_TITLE' => Output::getClean($group->name),
                'GROUP_ID' => Output::getClean($group->id),
                'NAME' => $language->get('admin', 'name'),
                'GROUP_HTML' => $language->get('admin', 'group_html'),
                'GROUP_HTML_VALUE' => Output::getClean($group->group_html),
                'GROUP_USERNAME_COLOUR' => $language->get('admin', 'group_username_colour'),
                'GROUP_USERNAME_COLOUR_VALUE' => Output::getClean($group->group_username_color),
                'GROUP_USERNAME_CSS' => $language->get('admin', 'group_username_css'),
                'GROUP_USERNAME_CSS_VALUE' => Output::getClean($group->group_username_css),
                'STAFF_GROUP' => $language->get('admin', 'group_staff'),
                'STAFF_GROUP_VALUE' => $group->staff,
                'STAFF_CP' => $language->get('admin', 'can_view_staffcp'),
                'STAFF_CP_VALUE' => $group->admin_cp,
                'DEFAULT_GROUP' => $language->get('admin', 'default_group'),
                'DEFAULT_GROUP_VALUE' => $group->default_group,
                'CANCEL' => $language->get('general', 'cancel'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CANCEL_LINK' => URL::build('/panel/core/groups'),
                'GROUP_NAME' => Output::getClean($group->name),
                'GROUP_ORDER' => $language->get('admin', 'group_order'),
                'GROUP_ORDER_VALUE' => $group->order,
                'FORCE_TFA' => $language->get('admin', 'force_tfa'),
                'FORCE_TFA_VALUE' => $group->force_tfa
            ]);

            $template_file = 'core/groups_form.tpl';

            break;

        case 'clone':
            if (!isset($_GET['group']) || !is_numeric($_GET['group'])) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            $group = Group::find($_GET['group']);
            if (!$group) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            if (Input::exists()) {
                $errors = [];
                if (Token::check()) {
                    if (Input::get('action') == 'update') {
                        $validation = Validate::check($_POST, [
                            'groupname' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 20
                            ],
                            'html' => [
                                Validate::MAX => 1024
                            ]
                        ])->messages([
                            'groupname' => [
                                Validate::REQUIRED => $language->get('admin', 'group_name_required'),
                                Validate::MIN => $language->get('admin', 'group_name_minimum'),
                                Validate::MAX => $language->get('admin', 'group_name_maximum')
                            ],
                            'html' => $language->get('admin', 'html_maximum')
                        ]);

                        if ($validation->passed()) {
                            try {
                                if (isset($_POST['default']) && $_POST['default'] == 1) {
                                    $default = 1;
                                } else {
                                    $default = 0;
                                }

                                // If this is the new default group, update old default group
                                $default_group = Group::find(1, 'default_group');
                                if (!$default_group && $default == 0) {
                                    $default = 1;
                                }

                                DB::getInstance()->insert('groups', [
                                    'name' => Input::get('groupname'),
                                    'group_html' => Input::get('html'),
                                    'group_username_color' => ($_POST['username_style'] ? Input::get('username_style') : null),
                                    'group_username_css' => ($_POST['username_css'] ? Input::get('username_css') : null),
                                    'admin_cp' => Input::get('staff'),
                                    'staff' => Input::get('staff'),
                                    'permissions' => $group->permissions,
                                    'default_group' => $default,
                                    'order' => Input::get('order'),
                                    'force_tfa' => Input::get('tfa')
                                ]);

                                $group_id = DB::getInstance()->lastId();

                                EventHandler::executeEvent(new GroupClonedEvent($group_id, $group->id));

                                if ($default == 1) {
                                    if ($default_group && $default_group->id != $group_id) {
                                        DB::getInstance()->update('groups', $default_group->id, [
                                            'default_group' => false
                                        ]);
                                    }

                                    $cache->setCache('default_group');
                                    $cache->store('default_group', $group_id);
                                }

                                $cache->setCache('groups_tfa_' . $group_id);
                                $cache->store('enabled', Input::get('tfa'));

                                Session::flash('admin_groups', $language->get('admin', 'group_cloned_successfully'));
                                Redirect::to(URL::build('/panel/core/groups/', 'action=edit&group=' . urlencode($group_id)));
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors = $validation->errors();
                        }
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'GROUP_TITLE' => $language->get('admin', 'cloning_group', [
                    'group' => Output::getClean($group->name)
                ]),
                'GROUP_ID' => Output::getClean($group->id),
                'NAME' => $language->get('admin', 'name'),
                'GROUP_HTML' => $language->get('admin', 'group_html'),
                'GROUP_HTML_VALUE' => Output::getClean($group->group_html),
                'GROUP_USERNAME_COLOUR' => $language->get('admin', 'group_username_colour'),
                'GROUP_USERNAME_COLOUR_VALUE' => Output::getClean($group->group_username_color),
                'GROUP_USERNAME_CSS' => $language->get('admin', 'group_username_css'),
                'GROUP_USERNAME_CSS_VALUE' => Output::getClean($group->group_username_css),
                'STAFF_GROUP' => $language->get('admin', 'group_staff'),
                'STAFF_GROUP_VALUE' => $group->staff,
                'STAFF_CP' => $language->get('admin', 'can_view_staffcp'),
                'STAFF_CP_VALUE' => $group->admin_cp,
                'DEFAULT_GROUP' => $language->get('admin', 'default_group'),
                'DEFAULT_GROUP_VALUE' => $group->default_group,
                'CANCEL' => $language->get('general', 'cancel'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CANCEL_LINK' => URL::build('/panel/core/groups'),
                'GROUP_NAME' => Output::getClean($group->name),
                'GROUP_ORDER' => $language->get('admin', 'group_order'),
                'GROUP_ORDER_VALUE' => $group->order,
                'FORCE_TFA' => $language->get('admin', 'force_tfa'),
                'FORCE_TFA_VALUE' => $group->force_tfa
            ]);

            $template_file = 'core/groups_form.tpl';

            break;

        case 'permissions':
            if (!isset($_GET['group']) || !is_numeric($_GET['group'])) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            $group = Group::find($_GET['group']);
            if (!$group) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            if ($group->id == 2 || ((in_array($group->id, $user->getAllGroupIds())) && !$user->hasPermission('admincp.groups.self'))) {
                Redirect::to(URL::build('/panel/core/groups'));
            }

            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    // Token valid
                    // Build new JSON object for permissions
                    $perms = [];
                    if (isset($_POST['permissions']) && count($_POST['permissions'])) {
                        foreach ($_POST['permissions'] as $permission => $value) {
                            $perms[$permission] = 1;
                        }
                    }
                    $perms_json = json_encode($perms);

                    try {
                        DB::getInstance()->update('groups', $group->id, ['permissions' => $perms_json]);

                        Session::flash('admin_groups', $language->get('admin', 'permissions_updated_successfully'));
                        Redirect::to(URL::build('/panel/core/groups/', 'action=edit&group=' . urlencode($group->id)));
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'PERMISSIONS' => $language->get('admin', 'permissions'),
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/groups/', 'action=edit&group=' . urlencode($group->id)),
                'PERMISSIONS_VALUES' => json_decode($group->permissions, true),
                'ALL_PERMISSIONS' => PermissionHandler::getPermissions(),
                'SELECT_ALL' => $language->get('admin', 'select_all'),
                'DESELECT_ALL' => $language->get('admin', 'deselect_all')
            ]);

            $template_file = 'core/groups_permissions.tpl';

            break;

        case 'order':
            // Get groups
            if (isset($_POST['groups']) && Token::check($_POST['token'])) {
                $groups = json_decode($_POST['groups'])->groups;

                $i = 1;
                foreach ($groups as $item) {
                    DB::getInstance()->update('groups', $item, [
                        'order' => $i
                    ]);
                    $i++;
                }
            }
            die('Complete');
        default:
            Redirect::to(URL::build('/panel/core/groups'));
    }
} else {
    $groups_template = [];
    foreach (Group::all() as $group) {
        $groups_template[] = [
            'id' => Output::getClean($group->id),
            'order' => $group->order,
            'name' => Output::getClean($group->name),
            'edit_link' => URL::build('/panel/core/groups/', 'action=edit&group=' . urlencode($group->id)),
            'clone_link' => URL::build('/panel/core/groups/', 'action=clone&group=' . urlencode($group->id)),
            'users' => DB::getInstance()->query('SELECT COUNT(*) AS c FROM nl2_users_groups WHERE group_id = ?', [$group->id])->first()->c,
            'staff' => $group->staff
        ];
    }

    $smarty->assign([
        'GROUP_ID' => $language->get('admin', 'group_id'),
        'NAME' => $language->get('admin', 'name'),
        'USERS' => $language->get('admin', 'users'),
        'NEW_GROUP' => $language->get('admin', 'new_group'),
        'NEW_GROUP_LINK' => URL::build('/panel/core/groups/', 'action=new'),
        'GROUP_LIST' => $groups_template,
        'ORDER' => $language->get('admin', 'group_order'),
        'STAFF' => $language->get('moderator', 'staff'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'EDIT' => $language->get('general', 'edit'),
        'REORDER_DRAG_URL' => URL::build('/panel/core/groups', 'action=order')
    ]);

    $template_file = 'core/groups.tpl';
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'GROUPS' => $language->get('admin', 'groups'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'INFO' => $language->get('general', 'info'),
    'WARNING' => $language->get('general', 'warning'),
    'FORCE_TFA_WARNING' => $language->get('admin', 'force_tfa_warning'),
    'GROUP_SYNC' => $language->get('admin', 'group_sync'),
    'GROUP_SYNC_LINK' => URL::build('/panel/core/api/', 'view=group_sync')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
