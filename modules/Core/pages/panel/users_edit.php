<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel users page
 */

if(!$user->handlePanelPageLoad('admincp.users.edit')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
    die();
}

$view_user = new User($_GET['id']);
if (!$view_user->data()) {
    Redirect::to('/panel/users');
    die();
}
$user_query = $view_user->data();

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'users');
define('EDITING_USER', true);
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');
require_once(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'validate') {
        if (Token::check()) {
            // Validate the user
            if ($user_query->active == 0) {
                $queries->update('users', $user_query->id, array(
                    'active' => 1,
                    'reset_code' => ''
                ));

                HookHandler::executeEvent('validateUser', array(
                    'event' => 'validateUser',
                    'user_id' => $user_query->id,
                    'username' => Output::getClean($user_query->username),
                    'uuid' => Output::getClean($user_query->uuid),
                    'language' => $language
                ));

                Session::flash('edit_user_success', $language->get('admin', 'user_validated_successfully'));
            }
        }

    } else if ($_GET['action'] == 'update_mcname') {
        require_once(ROOT_PATH . '/core/integration/uuid.php');
        $uuid = $user_query->uuid;

        $profile = ProfileUtils::getProfile($uuid);

        if ($profile) {
            $result = $profile->getUsername();

            if (!empty($result)) {
                if ($user_query->username == $user_query->nickname) {
                    $queries->update('users', $user_query->id, array(
                        'username' => Output::getClean($result),
                        'nickname' => Output::getClean($result)
                    ));
                } else {
                    $queries->update('users', $user_query->id, array(
                        'username' => Output::getClean($result)
                    ));
                }

                Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));
            }
        }
    } else if ($_GET['action'] == 'update_uuid') {
        require_once(ROOT_PATH . '/core/integration/uuid.php');

        $profile = ProfileUtils::getProfile($user_query->username);

        if (!empty($profile)) {
            $result = $profile->getProfileAsArray();

            if (isset($result['uuid']) && !empty($result['uuid'])) {
                $queries->update('users', $user_query->id, array(
                    'uuid' => Output::getClean($result['uuid'])
                ));

                Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));
            }
        }
    } else if ($_GET['action'] == 'resend_email' && $user_query->active == 0) {
        require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');
        if (sendRegisterEmail($queries, $language, $user_query->email, $user_query->username, $user_query->id, $user_query->reset_code)) {
            Session::flash('edit_user_success', $language->get('admin', 'email_resent_successfully'));
        } else {
            Session::flash('edit_user_errors', $language->get('admin', 'email_resend_failed'));
        }
    }

    Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
    die();
}

if (Input::exists()) {
    $errors = array();

    if (Token::check()) {
        if (Input::get('action') === 'update') {
            // Update a user's settings
            $signature = Input::get('signature');
            $_POST['signature'] = strip_tags(Input::get('signature'));

            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'email' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 4,
                    Validate::MAX => 50
                ],
                'UUID' => [
                    Validate::MAX => 32
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
                'UUID' => $language->get('admin', 'uuid_max_32'),
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
                    // Signature from Markdown -> HTML if needed
                    $cache->setCache('post_formatting');
                    $formatting = $cache->retrieve('formatting');

                    if ($formatting == 'markdown') {
                        $signature = Michelf\Markdown::defaultTransform($signature);
                        $signature = Output::getClean($signature);
                    } else {
                        $signature = Output::getClean($signature);
                    }

                    $private_profile_active = $queries->getWhere('settings', array('name', '=', 'private_profile'));
                    $private_profile_active = $private_profile_active[0]->value == 1;
                    $private_profile = 0;

                    if ($private_profile_active) {
                        $private_profile = Input::get('privateProfile');
                    }

                    // Template
                    $new_template = $queries->getWhere('templates', array('id', '=', Input::get('template')));

                    if (count($new_template)) $new_template = $new_template[0]->id;
                    else $new_template = $user_query->theme_id;

                    // Nicknames?
                    $displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
                    $displaynames = $displaynames[0]->value;

                    if ($displaynames == 'true') {
                        $username = Input::get('username');
                        $nickname = Input::get('nickname');
                    } else {
                        $username = Input::get('username');
                        $nickname = Input::get('username');
                    }

                    $queries->update('users', $user_query->id, array(
                        'nickname' => Output::getClean($nickname),
                        'email' => Output::getClean(Input::get('email')),
                        'username' => Output::getClean($username),
                        'user_title' => Output::getClean(Input::get('title')),
                        'uuid' => Output::getClean(Input::get('UUID')),
                        'signature' => $signature,
                        'private_profile' => $private_profile,
                        'theme_id' => $new_template
                    ));

                    // Get current group ids
                    $user_groups = array();
                    foreach ($view_user->getGroups() as $group) {
                        $user_groups[$group->id] = $group->id;
                    }

                    // Get groups
                    if ($view_user->data()->id != $user->data()->id || $user->hasPermission('admincp.groups.self')) {
                        if ($view_user->data()->id == 1 || (isset($_POST['groups']) && count($_POST['groups']))) {
                            $added = array();
                            // Any new groups?
                            foreach ($_POST['groups'] as $group) {
                                if (!in_array($group, $user_groups)) {
                                    $view_user->addGroup($group, 0, array(true));
                                    $added[] = $group;
                                }
                            }

                            $removed = array();
                            // Any groups to remove?
                            foreach ($view_user->getGroups() as $group) {
                                $form_groups = isset($_POST['groups']) ? $_POST['groups'] : [];
                                if (!in_array($group->id, $form_groups)) {
                                    // be sure root user keep the root group
                                    if ($group->id == 2 && $view_user->data()->id == 1) {
                                        continue;
                                    }

                                    $view_user->removeGroup($group->id);
                                    $removed[] = $group->id;
                                }
                            }

                            Discord::updateDiscordRoles($view_user, $added, $removed, $language);
                        }
                    }

                    Session::flash('edit_user_success', $language->get('admin', 'user_updated_successfully'));
                    Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
                    die();
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors = $validation->errors();
                if (!$passed) {
                    $errors[] = $language->get('admin', 'select_user_group');
                }
            }
        } else if (Input::get('action') == 'delete') {
            if ($user_query->id > 1) {
                HookHandler::executeEvent('deleteUser', array(
                    'user_id' => $user_query->id,
                    'username' => Output::getClean($user_query->username),
                    'uuid' => Output::getClean($user_query->uuid),
                    'email_address' => Output::getClean($user_query->email)
                ));

                Session::flash('users_session', $language->get('admin', 'user_deleted'));
            }

            Redirect::to(URL::build('/panel/users'));
            die();
        }
    } else
        $errors[] = $language->get('general', 'invalid_token');
}

if (Session::exists('edit_user_success'))
    $success = Session::flash('edit_user_success');

if (Session::exists('edit_user_errors'))
    $errors = Session::flash('edit_user_errors');

if (Session::exists('edit_user_warnings'))
    $warnings = Session::flash('edit_user_warnings');

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

if (isset($warnings) && count($warnings)) {
    $smarty->assign(array(
        'WARNINGS' => $warnings,
        'WARNINGS_TITLE' => $language->get('admin', 'warning')
    ));
}

if ($user_query->active == 0) {
    $smarty->assign(array(
        'VALIDATE_USER' => $language->get('admin', 'validate_user'),
        'VALIDATE_USER_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=validate'),
        'RESEND_ACTIVATION_EMAIL' => $language->get('admin', 'resend_activation_email'),
        'RESEND_ACTIVATION_EMAIL_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=resend_email')
    ));
}

if (defined('MINECRAFT') && MINECRAFT === true) {
    $smarty->assign(array(
        'UPDATE_MINECRAFT_USERNAME' => $language->get('admin', 'update_mc_name'),
        'UPDATE_MINECRAFT_USERNAME_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=update_mcname'),
        'UPDATE_UUID' => $language->get('admin', 'update_uuid'),
        'UPDATE_UUID_LINK' => URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id) . '&action=update_uuid')
    ));
}

if ($user_query->id != 1 && !$view_user->canViewStaffCP()) {
    $smarty->assign(array(
        'DELETE_USER' => $language->get('admin', 'delete_user'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'CONFIRM_DELETE_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'confirm_user_deletion')),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no')
    ));
}

$limit_groups = false;
if ($user_query->id == 1 || ($user_query->id == $user->data()->id && !$user->hasPermission('admincp.groups.self'))) {
    $smarty->assign(array(
        'CANT_EDIT_GROUP' => $language->get('admin', 'cant_modify_root_user')
    ));
    $limit_groups = true;
}

$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$displaynames = $displaynames[0]->value;

$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

$private_profile = $queries->getWhere('settings', array('name', '=', 'private_profile'));
$private_profile = $private_profile[0]->value;

$templates = array();
$templates_query = $queries->getWhere('templates', array('id', '<>', 0));

foreach ($templates_query as $item) {
    $templates[] = array(
        'id' => Output::getClean($item->id),
        'name' => Output::getClean($item->name),
        'active' => $item->id === $user_query->theme_id
    );
}

$groups = $queries->orderAll('groups', '`order`', 'ASC');
$filtered_groups = array();
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

// HTML -> Markdown if necessary
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    require(ROOT_PATH . '/core/includes/markdown/tomarkdown/autoload.php');
    $converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

    $signature = $converter->convert(Output::getDecoded($user_query->signature));
    $signature = Output::getPurified($signature);
} else {
    $signature = Output::getPurified(Output::getDecoded($user_query->signature));
}

$user_groups = array();
foreach ($view_user->getGroups() as $group) {
    $user_groups[$group->id] = $group->id;
}

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'EDITING_USER' => str_replace('{x}', Output::getClean($user_query->nickname), $language->get('admin', 'editing_user_x')),
    'BACK_LINK' => URL::build('/panel/users'),
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
    'UUID_LINKING' => ($uuid_linking == '1'),
    'UUID' => $language->get('admin', 'minecraft_uuid'),
    'UUID_VALUE' => Output::getClean($user_query->uuid),
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
    'TEMPLATES' => $templates
));

$discord_id = $user_query->discord_id;

if ($discord_id != null && $discord_id != 010) {
    $smarty->assign(array(
        'DISCORD_ID' => $language->get('user', 'discord_id'),
        'DISCORD_ID_VALUE' => $discord_id
    ));
}

$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');
if ($formatting == 'markdown') {
    $template->addJSFiles(array(
        (defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
        (defined('CONFIG_PATH' ? CONFIG_PATH : '')) . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
    ));

    $template->addJSScript('
            $(document).ready(function() {
                var el = $("#InputSignature").emojioneArea({
                    pickerPosition: "bottom"
                });
            });
        ');
} else {
    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/ckeditor.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/ckeditor.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json' => array()
    ));

    $template->addJSScript(Input::createEditor('InputSignature'));
}

$template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.sprites.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/users_edit.tpl', $smarty);
