<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel avatars page
 */

if (!$user->handlePanelPageLoad('admincp.core.avatars')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'avatars';
$page_title = $language->get('admin', 'avatars');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Input
if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['avatar_source'])) {
            try {
                Util::setSetting('user_avatars', $custom_avatars = (isset($_POST['custom_avatars']) && $_POST['custom_avatars']) ? '1' : '0');
                Util::setSetting('default_avatar_type', Input::get('default_avatar'));
                Util::setSetting('avatar_site', Input::get('avatar_source'));
                Util::setSetting('avatar_type', Input::get('avatar_perspective'));

                $cache->setCache('avatar_settings_cache');
                $cache->store('custom_avatars', $custom_avatars);
                $cache->store('default_avatar_type', Input::get('default_avatar'));
                $cache->store('avatar_source', Input::get('avatar_source'));
                $cache->store('avatar_perspective', Input::get('avatar_perspective'));
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
            }
        } else {
            if (isset($_POST['avatar'])) {
                // Selecting a new default avatar
                try {
                    Util::setSetting('custom_default_avatar', Input::get('avatar'));
                    $cache->setCache('avatar_settings_cache');
                    $cache->store('default_avatar_image', Input::get('avatar'));
                } catch (Exception $e) {
                    $errors = [$e->getMessage()];
                }
            }
        }

        //Log::getInstance()->log(Log::Action('admin/core/avatar'));

        Session::flash('avatar_success', $language->get('admin', 'avatar_settings_updated_successfully'));
        Redirect::to(URL::build('/panel/core/avatars'));

    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('avatar_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('avatar_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'avatars', 'defaults']);
$images = scandir($image_path);
$template_images = [];

// Only display jpeg, png, jpg, gif
$allowed_exts = ['gif', 'png', 'jpg', 'jpeg'];

if (count($images)) {
    foreach ($images as $image) {
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed_exts)) {
            continue;
        }

        $template_images[(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/avatars/defaults/' . Output::getClean($image)] = Output::getClean($image);
    }
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'AVATARS' => $language->get('admin', 'avatars'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CUSTOM_AVATARS' => $language->get('admin', 'allow_custom_avatars'),
    'CUSTOM_AVATARS_VALUE' => defined('CUSTOM_AVATARS'),
    'DEFAULT_AVATAR' => $language->get('admin', 'default_avatar'),
    'DEFAULT_AVATAR_VALUE' => Util::getSetting('default_avatar_type'),
    'MINECRAFT_AVATAR' => $language->get('admin', 'minecraft_avatar'),
    'CUSTOM_AVATAR' => $language->get('admin', 'custom_avatar'),
    'MINECRAFT_AVATAR_SOURCE' => $language->get('admin', 'minecraft_avatar_source'),
    'MINECRAFT_AVATAR_VALUES' => AvatarSource::getAllSourceNames(),
    'MINECRAFT_AVATAR_VALUE' => Util::getSetting('avatar_site'),
    'MINECRAFT_AVATAR_PERSPECTIVE' => $language->get('admin', 'minecraft_avatar_perspective'),
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUE' => Util::getSetting('avatar_type'),
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUES' => AvatarSource::getAllPerspectives(),
    'HEAD' => $language->get('admin', 'head'),
    'FACE' => $language->get('admin', 'face'),
    'BUST' => $language->get('admin', 'bust'),
    'SELECT_DEFAULT_AVATAR' => $language->get('admin', 'select_default_avatar'),
    'IMAGES' => $template_images,
    'NO_AVATARS' => $language->get('admin', 'no_avatars_available'),
    'DEFAULT_AVATAR_IMAGE' => Util::getSetting('custom_default_avatar'),
    'UPLOAD_NEW_IMAGE' => $language->get('admin', 'upload_new_image'),
    'UPLOAD_FORM_ACTION' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/includes/image_upload.php',
    'DRAG_FILES_HERE' => $language->get('admin', 'drag_files_here'),
    'CLOSE' => $language->get('general', 'close')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/avatars.tpl', $smarty);
