<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
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
if (!$view_user->exists()) {
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
                    'active' => true,
                    'reset_code' => ''
                ]);

                EventHandler::executeEvent(new UserValidatedEvent(
                    $view_user,
                ));

                Session::flash('edit_user_success', $language->get('admin', 'user_validated_successfully'));
            }
        }
    } else if ($_GET['action'] == 'resend_email' && $user_query->active == 0) {
        require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');
        if (sendRegisterEmail($language, $user_query->email, $user_query->username, $user_query->id, $user_query->reset_code)) {
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
                    Validate::UNIQUE => [
                        'users',
                        'id:' . $view_user->data()->id // ignore current user
                    ],
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
                    Validate::UNIQUE => [
                        'users',
                        'id:' . $view_user->data()->id // ignore current user
                    ],
                    Validate::MIN => 3,
                    Validate::MAX => 20
                ],
                'nickname' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 20
                ],
                'timezone' => [
                    Validate::REQUIRED => true,
                    Validate::TIMEZONE => true
                ],
            ])->messages([
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'email_required'),
                    Validate::UNIQUE => $language->get('user', 'email_already_exists')
                ],
                'title' => $language->get('admin', 'title_max_64'),
                'username' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::UNIQUE => $language->get('user', 'username_already_exists'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20')
                ],
                'nickname' => [
                    Validate::REQUIRED => $language->get('user', 'username_required'),
                    Validate::MIN => $language->get('user', 'username_minimum_3'),
                    Validate::MAX => $language->get('user', 'username_maximum_20')
                ],
                'timezone' => $language->get('general', 'invalid_timezone'),
            ]);

            // Does user have any groups selected
            $passed = false;
            if ($view_user->data()->id == 1 || (isset($_POST['groups']) && count($_POST['groups']))) {
                $passed = true;
            }

            if ($validation->passed() && $passed) {
                try {
                    $private_profile_active = Util::getSetting('private_profile');
                    $private_profile = 0;

                    if ($private_profile_active) {
                        $private_profile = Input::get('privateProfile');
                    }

                    // Template
                    if (Input::get('template') != 0) {
                        $new_template = DB::getInstance()->get('templates', ['id', Input::get('template')])->results();

                        if (count($new_template)) {
                            $new_template = $new_template[0]->id;
                        } else {
                            $new_template = $user_query->theme_id;
                        }
                    } else {
                        $new_template = null;
                    }

                    // Nicknames?
                    $username = Input::get('username');
                    if (Util::getSetting('displaynames') === '1') {
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
                        'language_id' => Output::getClean(Input::get('language')),
                        'timezone' => Output::getClean(Input::get('timezone')),
                        'theme_id' => $new_template
                    ]);

                    $group_sync_log = [];
                    if ($view_user->data()->id != $user->data()->id || $user->hasPermission('admincp.groups.self')) {
                        if ($view_user->data()->id == 1 || (isset($_POST['groups']) && count($_POST['groups']))) {
                            $user_group_ids = $view_user->getAllGroupIds();
                            $form_groups = $_POST['groups'] ?? [];

                            // Check for new groups to give them which they don't already have
                            foreach ($form_groups as $group_id) {
                                if (!in_array($group_id, $user_group_ids)) {
                                    $view_user->addGroup($group_id);
                                }
                            }

                            // Check for groups they had, but weren't in the $_POST groups
                            foreach ($user_group_ids as $group_id) {
                                if (!in_array($group_id, $form_groups)) {
                                    $view_user->removeGroup($group_id);
                                }
                            }

                            // Dispatch groupsync with all of their groups
                            GroupSyncManager::getInstance()->broadcastChange(
                                $view_user,
                                NamelessMCGroupSyncInjector::class,
                                $view_user->getAllGroupIds(),
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

        } else if ((Input::get('action') == 'delete') && $user_query->id > 1) {
                EventHandler::executeEvent(new UserDeletedEvent($view_user));

                Session::flash('users_session', $language->get('admin', 'user_deleted'));
                Redirect::to(URL::build('/panel/users'));
        } else if ((Input::get('action') == 'change_password') && $user_query->id > 1 && !$view_user->canViewStaffCP()) {
            $validation = Validate::check($_POST, [
                'password' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 6
                ],
                'password_again' => [
                    Validate::MATCHES => 'password'
                ]
            ])->messages([
                'password' => [
                    Validate::REQUIRED => $language->get('user', 'password_required'),
                    Validate::MIN => $language->get('user', 'password_minimum_6')
                ],
                'password_again' => $language->get('user', 'passwords_dont_match')
            ]);

            if ($validation->passed()) {
                $password = Input::get('password');
                $encrypted_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
                $view_user->update([
                    'password' => $encrypted_password
                ]);
                Session::flash('edit_user_success', $language->get('admin', 'user_password_changed_successfully'));
            } else {
                Session::flash('edit_user_error', implode('\n', $validation->errors()));
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
    $warnings = [Session::flash('edit_user_warnings')];
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
        'CONFIRM_DELETE_USER' => $language->get('admin', 'confirm_user_deletion', ['user' => Output::getClean($user_query->username)]),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),

        'NEW_PASSWORD' => $language->get('user', 'new_password'),
        'CONFIRM_NEW_PASSWORD' => $language->get('user', 'confirm_new_password'),
        'CHANGE_PASSWORD' => $language->get('user', 'change_password'),
    ]);
}

$limit_groups = false;
if ($user_query->id == 1 || ($user_query->id == $user->data()->id && !$user->hasPermission('admincp.groups.self'))) {
    $smarty->assign([
        'CANT_EDIT_GROUP' => $language->get('admin', 'cant_modify_root_user')
    ]);
    $limit_groups = true;
}

$private_profile = Util::getSetting('private_profile');

$templates = [];
$templates_query = DB::getInstance()->get('templates', ['enabled', 1])->results();

$templates[] = [
    'id' => 0,
    'name' => $language->get('general', 'default'),
    'active' => $user_query->theme_id === null
];
foreach ($templates_query as $item) {
    $templates[] = [
        'id' => Output::getClean($item->id),
        'name' => Output::getClean($item->name),
        'active' => $item->id === $user_query->theme_id
    ];
}

$groups = DB::getInstance()->orderAll('groups', '`order`', 'ASC')->results();
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

$user_groups = $view_user->getAllGroupIds();

// Get languages
$languages = [];
$language_query = DB::getInstance()->get('languages', ['id', '<>', 0])->results();
foreach ($language_query as $item) {
    $languages[] = [
        'id' => $item->id,
        'name' => Output::getClean($item->name),
        'active' => $user->data()->language_id == $item->id
    ];
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'EDITING_USER' => $language->get('admin', 'editing_user_x', [
        'user' => Output::getClean($user_query->nickname),
    ]),
    'BACK_LINK' => URL::build('/panel/user/' . $user_query->id),
    'BACK' => $language->get('general', 'back'),
    'ACTIONS' => $language->get('general', 'actions'),
    'USER_ID' => Output::getClean($user_query->id),
    'DISPLAYNAMES' => Util::getSetting('displaynames') === '1',
    'USERNAME' => $language->get('user', 'username'),
    'USERNAME_VALUE' => Output::getClean($user_query->username),
    'NICKNAME' => $language->get('user', 'nickname'),
    'NICKNAME_VALUE' => Output::getClean($user_query->nickname),
    'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
    'EMAIL_ADDRESS_VALUE' => Output::getClean($user_query->email),
    'USER_TITLE' => $language->get('admin', 'title'),
    'USER_TITLE_VALUE' => Output::getClean($user_query->user_title),
    'LANGUAGE' => $language->get('user', 'active_language'),
    'LANGUAGE_VALUE' => $user_query->language_id,
    'LANGUAGES' => $languages,
    'TIMEZONE' => $language->get('user', 'timezone'),
    'TIMEZONE_VALUE' => $user_query->timezone,
    'TIMEZONES' => Util::listTimezones(),
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
    'TEMPLATES' => $templates,
]);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'InputSignature', null, false, true));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_edit.tpl', $smarty);
