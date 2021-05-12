<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel avatars page
 */

if(!$user->handlePanelPageLoad('admincp.core.avatars')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'avatars');
$page_title = $language->get('admin', 'avatars');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Input
if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['avatar_source'])) {
            // Custom avatars?
            if (isset($_POST['custom_avatars']) && $_POST['custom_avatars'] == 1)
                $custom_avatars = 1;
            else
                $custom_avatars = 0;

            try {
                $custom_avatars_id = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
                $custom_avatars_id = $custom_avatars_id[0]->id;
                $queries->update('settings', $custom_avatars_id, array('value' => $custom_avatars));

                $default_avatar_type = $queries->getWhere('settings', array('name', '=', 'default_avatar_type'));
                $default_avatar_type = $default_avatar_type[0]->id;
                $queries->update('settings', $default_avatar_type, array('value' => Input::get('default_avatar')));

                $mc_avatar_source = $queries->getWhere('settings', array('name', '=', 'avatar_site'));
                $mc_avatar_source = $mc_avatar_source[0]->id;
                $queries->update('settings', $mc_avatar_source, array('value' => Input::get('avatar_source')));

                $mc_avatar_perspective = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
                $mc_avatar_perspective = $mc_avatar_perspective[0]->id;
                $queries->update('settings', $mc_avatar_perspective, array('value' => Input::get('avatar_perspective')));

                $cache->setCache('avatar_settings_cache');
                $cache->store('custom_avatars', $custom_avatars);
                $cache->store('default_avatar_type', Input::get('default_avatar'));
                $cache->store('avatar_source', Input::get('avatar_source'));
                $cache->store('avatar_perspective', Input::get('avatar_perspective'));
            } catch (Exception $e) {
                $errors = array($e->getMessage());
            }
        } else if (isset($_POST['avatar'])) {
            // Selecting a new default avatar
            try {
                $default_avatar = $queries->getWhere('settings', array('name', '=', 'custom_default_avatar'));
                $default_avatar = $default_avatar[0]->id;
                $queries->update('settings', $default_avatar, array('value' => Input::get('avatar')));

                $cache->setCache('avatar_settings_cache');
                $cache->store('default_avatar_image', Input::get('avatar'));
            } catch (Exception $e) {
                $errors = array($e->getMessage());
            }
        }

        //Log::getInstance()->log(Log::Action('admin/core/avatar'));

        Session::flash('avatar_success', $language->get('admin', 'avatar_settings_updated_successfully'));
        Redirect::to(URL::build('/panel/core/avatars'));

    } else
        $errors = array($language->get('general', 'invalid_token'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (Session::exists('avatar_success')) {
    $smarty->assign(array(
        'SUCCESS' => Session::flash('avatar_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));
}

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

// Get setting values
$custom_avatars = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
$custom_avatars = $custom_avatars[0]->value;

$default_avatar_type = $queries->getWhere('settings', array('name', '=', 'default_avatar_type'));
$default_avatar_type = $default_avatar_type[0]->value;

$mc_avatar_source = $queries->getWhere('settings', array('name', '=', 'avatar_site'));
$mc_avatar_source = $mc_avatar_source[0]->value;

$mc_avatar_perspective = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
$mc_avatar_perspective = $mc_avatar_perspective[0]->value;

$default_avatar_image = $queries->getWhere('settings', array('name', '=', 'custom_default_avatar'));
$default_avatar_image = $default_avatar_image[0]->value;

$image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'avatars', 'defaults'));
$images = scandir($image_path);
$template_images = array();

// Only display jpeg, png, jpg, gif
$allowed_exts = array('gif', 'png', 'jpg', 'jpeg');

if (count($images)) {
    foreach ($images as $image) {
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed_exts)) {
            continue;
        }

        $template_images[(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/avatars/defaults/' . Output::getClean($image)] = Output::getClean($image);
    }
}

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'AVATARS' => $language->get('admin', 'avatars'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CUSTOM_AVATARS' => $language->get('admin', 'allow_custom_avatars'),
    'CUSTOM_AVATARS_VALUE' => $custom_avatars,
    'DEFAULT_AVATAR' => $language->get('admin', 'default_avatar'),
    'DEFAULT_AVATAR_VALUE' => $default_avatar_type,
    'MINECRAFT_AVATAR' => $language->get('admin', 'minecraft_avatar'),
    'CUSTOM_AVATAR' => $language->get('admin', 'custom_avatar'),
    'MINECRAFT_AVATAR_SOURCE' => $language->get('admin', 'minecraft_avatar_source'),
    'MINECRAFT_AVATAR_VALUES' => AvatarSource::getAllSourceNames(),
    'MINECRAFT_AVATAR_VALUE' => $mc_avatar_source,
    'MINECRAFT_AVATAR_PERSPECTIVE' => $language->get('admin', 'minecraft_avatar_perspective'),
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUE' => $mc_avatar_perspective,
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUES' => AvatarSource::getAllPerspectives(),
    'HEAD' => $language->get('admin', 'head'),
    'FACE' => $language->get('admin', 'face'),
    'BUST' => $language->get('admin', 'bust'),
    'SELECT_DEFAULT_AVATAR' => $language->get('admin', 'select_default_avatar'),
    'IMAGES' => $template_images,
    'NO_AVATARS' => $language->get('admin', 'no_avatars_available'),
    'DEFAULT_AVATAR_IMAGE' => $default_avatar_image,
    'UPLOAD_NEW_IMAGE' => $language->get('admin', 'upload_new_image'),
    'UPLOAD_FORM_ACTION' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/includes/image_upload.php',
    'DRAG_FILES_HERE' => $language->get('admin', 'drag_files_here'),
    'CLOSE' => $language->get('general', 'close')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/avatars.tpl', $smarty);
