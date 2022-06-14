<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr0
 *
 *  License: MIT
 *
 *  Panel Minecraft server banners page
 */

if (!$user->handlePanelPageLoad('admincp.minecraft.banners')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (!function_exists('exif_imagetype')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
const MINECRAFT_PAGE = 'server_banners';
$page_title = $language->get('admin', 'server_banners');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['server']) && !isset($_GET['edit'])) {
    $servers = DB::getInstance()->get('mc_servers', ['id', '<>', 0])->results();
    if (count($servers)) {
        $template_array = [];

        foreach ($servers as $server) {
            $template_array[] = [
                'name' => Output::getClean($server->name),
                'edit_link' => URL::build('/panel/minecraft/banners/', 'edit=' . urlencode($server->id)),
                'view_link' => URL::build('/panel/minecraft/banners/', 'server=' . urlencode($server->id))
            ];
        }

        $smarty->assign([
            'SERVERS' => $template_array,
            'EDIT' => $language->get('general', 'edit'),
            'VIEW' => $language->get('general', 'view')
        ]);

    } else {
        $smarty->assign('NO_SERVERS', $language->get('admin', 'no_servers_defined'));
    }

    $template_file = 'integrations/minecraft/minecraft_server_banners.tpl';

} else {
    if (isset($_GET['server'])) {
        // View
        // Get server
        $server = DB::getInstance()->get('mc_servers', ['id', $_GET['server']])->results();
        if (!count($server)) {
            Redirect::to(URL::build('/panel/minecraft/banners'));
        }
        $server = $server[0];

        $smarty->assign([
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/minecraft/banners'),
            'SERVER_NAME' => Output::getClean($server->name),
            'BANNER_URL' => URL::getSelfURL() . ltrim(rtrim(URL::build('/banner/' . urlencode($server->name) . '.png'), '/'), '/'),
            'BANNER_PATH' => rtrim(URL::build('/banner/' . urlencode($server->name) . '.png'), '/')
        ]);

        $template_file = 'integrations/minecraft/minecraft_server_banners_view.tpl';

    } else {
        // Edit
        // Get server
        $server = DB::getInstance()->get('mc_servers', ['id', $_GET['edit']])->results();
        if (!count($server)) {
            Redirect::to(URL::build('/panel/minecraft/banners'));
        }

        if (Input::exists()) {
            // Check token
            if (Token::check()) {
                // Valid token
                try {
                    if (file_exists(ROOT_PATH . '/uploads/banners/' . Input::get('banner'))) {
                        DB::getInstance()->update('mc_servers', $_GET['edit'], [
                            'banner_background' => Output::getClean(Input::get('banner'))
                        ]);

                        $success = $language->get('admin', 'banner_updated_successfully');
                    }
                } catch (Exception $e) {
                    $errors = [$e->getMessage()];
                }


            } else {
                // Invalid token
                $errors = [$language->get('general', 'invalid_token')];
            }

            // Re-query
            $server = DB::getInstance()->get('mc_servers', ['id', $_GET['edit']])->results();
        }

        $server = $server[0];

        $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'banners']);
        $images = scandir($image_path);
        $template_images = [];

        // Only display jpeg, png, jpg, gif
        $allowed_exts = ['gif', 'png', 'jpg', 'jpeg'];
        $n = 1;

        foreach ($images as $image) {
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed_exts)) {
                continue;
            }
            $template_images[] = [
                'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/banners/' . $image,
                'value' => $image,
                'selected' => ($server->banner_background == $image),
                'n' => $n
            ];
            $n++;
        }

        $smarty->assign([
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/minecraft/banners'),
            'SERVER_NAME' => Output::getClean($server->name),
            'BANNER_BACKGROUND' => $language->get('admin', 'banner_background'),
            'BANNER_BACKGROUND_VALUE' => Output::getClean($server->banner_background),
            'IMAGES' => $template_images
        ]);

        $template_file = 'integrations/minecraft/minecraft_server_banners_edit.tpl';
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

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'SERVER_BANNERS' => $language->get('admin', 'server_banners')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
