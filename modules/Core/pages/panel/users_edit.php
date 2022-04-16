<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel users page
 */

if (!$user->handlePanelPageLoad('admincp.users.edit')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
}

$view_user = new User($_GET['id']);
if (!$view_user->data()) {
    Redirect::to(URL::build('/panel/users'));
}
$user_query = $view_user->data();

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'users';
const EDITING_USER = true;
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'validate') {
        if (Token::check()) {
            // Validate the user
            if ($user_query->active == 0) {
                $view_user->update([
                    'active' => 1,
                    'reset_code' => ''
                ]);

                EventHandler::executeEvent('validateUser', [
                    'user_id' => $user_query->id,
                    'username' => $user_query->username,
                    'language' => $language
                ]);

                Session::flash('edit_user_success', $language->get('admin', 'user_validated_successfully'));
            }
        }
    } else if ($_GET['action'] == 'resend_email' && $user_query->active == 0) {
        require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');
        if (sendRegisterEmail($queries, $language, $user_query->email, $user_query->username, $user_query->id, $user_query->reset_code)) {
            Session::flash('edit_user_success', $language->get('admin', 'email_resent_successfully'));
        } else {
            Session::flash('edit_user_error', $language->get('admin', 'email_resend_failed'));
        }
    } else {
        throw new InvalidArgumentException('Invalid action: ' . $_GET['action']);
    }

    Redirect::to(URL::build('/panel/users/edit/', 'id=' . urlencode($user_query->id)));
}

if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        if (Input::get('action') === 'update') {
            // Update a user's settings
            $signature = Input::get('signature');
            $_POST['signature'] = strip_tags(Input::get('signature'));

            $validation = Validate::check($_POST, [
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 4,
                    Validate::MAX => 50
                ],
                'signature' => [
                    Validate::MAX => 900
                ],
                'ip' => [
                    Validate::MAX => 256
                ],
                'title' => [
                    Validate::MAX => 64
                ],
                'username' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20
                ],
                'nickname' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20
                ]
            ])->messages([
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required')
                ],
                'title' => $language->get('admin', 'title_max_64'),
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'mcname_required'),
                    Validate::MIN => $language->get('user', 'mcname_minimum_3'),
                    Validate::MAX => $language->get('user', 'mcname_maximum_20')
                ],
                'nickname' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20')
                ]
            ]);

            // Does user have any groups selected
            $passed = false;
            if ($view_user->data()->id == 1 || (isset($_POST['groups']) && count($_POST['groups']))) {
                $passed = true;
            }

            if ($validation->passed() && $passed) {
                try {
                    $signature = Output::getClean($signature);

                    $private_profile_active = $queries->getWhere('settings', ['name', '=', 'private_profile']);
                    $private_profile_active = $private_profile_active[0]->value == 1;
                    $private_profile = 0;

                    if ($private_profile_active) {
                        $private_profile = Input::get('privateProfile');
                    }

                    // Template
                    $new_template = $queries->getWhere('templates', ['id', '=', Input::get('template')]);

                    if (count($new_template)) {
                        $new_template = $new_template[0]->id;
                    } else {
                        $new_template = $user_query->theme_id;
                    }

                    // Nicknames?
                    $displaynames = $queries->getWhere('settings', ['name', '=', 'displaynames']);
                    $displaynames = $displaynames[0]->value;

                    $username = Input::get('username');
                    if ($displaynames == 'true') {
                        $nickname = Input::get('nickname');
                    } else {
                        $nickname = Input::get('username');
                    }

                    $view_user->update([
                        'nickname' => Output::getClean($nickname),
                        'email' => Output::getClean(Input::get('email')),
                        'username' => Output::getClean($username),
                        'user_title' => Output::getClean(Input::get('title')),
                        'signature' => $signature,
                        'private_profile' => $private_profile,
                        'theme_id' => $new_template
                    ]);

                    if ($view_user->data()->id != $user->data()->id || $user->hasPermission('admincp.groups.self')) {
                        if ($view_user->data()->id == 1 || (isset($_POST['groups']) && count($_POST['groups']))) {
                            $modified = [];

                            // Check for new groups to give them which they dont already have
                            foreach ($_POST['groups'] as $group) {
                                if (!in_array($group, $view_user->getAllGroupIds())) {
                                    $view_user->addGroup($group, 0, [true]);
                                    $modified[] = $group;
                                }
                            }

                            // Check for groups they had, but werent in the $_POST groups
                            foreach ($view_user->getAllGroupIds() as $group_id) {
                                $form_groups = $_POST['groups'] ?? [];
                                if (!in_array($group_id, $form_groups)) {
                                    $view_user->removeGroup($group_id);
                                    $modified[] = $group_id;
                                }
                            }

                            // Dispatch the modified groups
                            GroupSyncManager::getInstance()->broadcastChange(
                                $view_user,
                                NamelessMCGroupSyncInjector::class,
                                $modified
                            );
                        }
                    }

                    Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));
                    Redirect::to(URL::build('/panel/users/edit/', 'id=' . urlencode($user_query->id)));
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors = $validation->errors();
                if (!$passed) {
                    $errors[] = $language->get('admin', 'select_user_group');
                }
            }
        } else {
            if (Input::get('action') == 'delete') {
                if ($user_query->id > 1) {
                    EventHandler::executeEvent('deleteUser', [
                        'user_id' => $user_query->id,
                        'username' => $user_query->username,
                        'email_address' => $user_query->email
                    ]);

                    Session::flash('users_session', $language->get('admin', 'user_deleted'));
                }

                Redirect::to(URL::build('/panel/users'));
            }
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

if (Session::exists('edit_user_success')) {
    $success = Session::flash('edit_user_success');
}

if (Session::exists('edit_user_error')) {
    $errors[] = Session::flash('edit_user_error');
}

if (Session::exists('edit_user_warnings')) {
    $warnings = Session::flash('edit_user_warnings');
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

if (isset($warnings) && count($warnings)) {
    $smarty->assign([
        'WARNINGS' => $warnings,
        'WARNINGS_TITLE' => $language->get('admin', 'warning')
    ]);
}

if ($user_query->active == 0) {
    $smarty->assign([
        'VALIDATE_USER' => $language->get('admin', 'validate_user'),
        'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . urlencode($user_query->id) . '&action=validate'),
        'RESEND_ACTIVATION_EMAIL' => $language->get('admin', 'resend_activation_email'),
        'RESEND_ACTIVATION_EMAIL_LINK' => URL::build('/panel/users/edit/', 'id=' . urlencode($user_query->id) . '&action=resend_email')
    ]);
}

if ($user_query->id != 1 && !$view_user->canViewStaffCP()) {
    $smarty->assign([
        'DELETE_USER' => $language->get('admin', 'delete_user'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'CONFIRM_DELETE_USER' => str_replace('{x}', Output::getClean($user_query->username), $language->get('admin', 'confirm_user_deletion')),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no')
    ]);
}

$limit_groups = false;
if ($user_query->id == 1 || ($user_query->id == $user->data()->id && !$user->hasPermission('admincp.groups.self'))) {
    $smarty->assign([
        'CANT_EDIT_GROUP' => $language->get('admin', 'cant_modify_root_user')
    ]);
    $limit_groups = true;
}

$displaynames = $queries->getWhere('settings', ['name', '=', 'displaynames']);
$displaynames = $displaynames[0]->value;

$private_profile = $queries->getWhere('settings', ['name', '=', 'private_profile']);
$private_profile = $private_profile[0]->value;

$templates = [];
$templates_query = $queries->getWhere('templates', ['id', '<>', 0]);

foreach ($templates_query as $item) {
    $templates[] = [
        'id' => Output::getClean($item->id),
        'name' => Output::getClean($item->name),
        'active' => $item->id === $user_query->theme_id
    ];
}

$groups = $queries->orderAll('groups', '`order`', 'ASC');
$filtered_groups = [];
foreach ($groups as $group) {
    // Only show groups whose ID is not their main group and whose order is HIGHER than their main group. A main group simply means this $user's group with LOWEST order
    // TODO: Probably can make this into the mysql query
    if ($limit_groups) {
        if ((!($group->id == $view_user->getMainGroup()->id)) && ($view_user->getMainGroup()->order < $group->order)) {
            $filtered_groups[] = $group;
        }
    } else {
        $filtered_groups[] = $group;
    }
}

$signature = Output::getPurified($user_query->signature);

$user_groups = [];
foreach ($view_user->getAllGroupIds() as $group_id) {
    $user_groups[$group_id] = $group_id;
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'EDITING_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'editing_user_x')),
    'BACK_LINK' => URL::build('/panel/user/' . $user_query->id),
    'BACK' => $language->get('general', 'back'),
    'ACTIONS' => $language->get('general', 'actions'),
    'USER_ID' => Output::getClean($user_query->id),
    'DISPLAYNAMES' => ($displaynames == 'true'),
    'USERNAME' => $language->get('user', 'username'),
    'USERNAME_VALUE' => Output::getClean($user_query->username),
    'NICKNAME' => $language->get('user', 'nickname'),
    'NICKNAME_VALUE' => Output::getClean($user_query->nickname),
    'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
    'EMAIL_ADDRESS_VALUE' => Output::getClean($user_query->email),
    'USER_TITLE' => $language->get('admin', 'title'),
    'USER_TITLE_VALUE' => Output::getClean($user_query->user_title),
    'PRIVATE_PROFILE' => $language->get('user', 'private_profile'),
    'PRIVATE_PROFILE_VALUE' => $user_query->private_profile,
    'PRIVATE_PROFILE_ENABLED' => ($private_profile == 1),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'SIGNATURE' => $language->get('user', 'signature'),
    'SIGNATURE_VALUE' => $signature,
    'ALL_GROUPS' => $filtered_groups,
    'GROUPS' => $language->get('admin', 'groups'),
    'GROUPS_INFO' => $language->get('admin', 'secondary_groups_info'),
    'GROUPS_VALUE' => $user_groups,
    'MAIN_GROUP' => $view_user->getMainGroup(),
    'MAIN_GROUP_INFO' => $language->get('admin', 'main_group'),
    'INFO' => $language->get('general', 'info'),
    'ACTIVE_TEMPLATE' => $language->get('user', 'active_template'),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
    'TEMPLATES' => $templates
]);

$template->addCSSFiles([
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => [],
]);

$template->addJSFiles([
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => []
]);
$template->addJSScript(Input::createTinyEditor($language, 'InputSignature'));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_edit.tpl', $smarty);
