<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr0
 *
 *  License: MIT
 *
 *  Panel Minecraft server banners page
 */

if(!$user->handlePanelPageLoad('admincp.minecraft.banners')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (!function_exists('exif_imagetype')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'minecraft');
define('MINECRAFT_PAGE', 'server_banners');
$page_title = $language->get('admin', 'server_banners');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['server']) && !isset($_GET['edit'])){
    $servers = $queries->getWhere('mc_servers', array('id', '<>', 0));
    if(count($servers)){
        $template_array = array();

        foreach($servers as $server){
            $template_array[] = array(
                'name' => Output::getClean($server->name),
                'edit_link' => URL::build('/panel/minecraft/banners/', 'edit=' . Output::getClean($server->id)),
                'view_link' => URL::build('/panel/minecraft/banners/', 'server=' . Output::getClean($server->id))
            );
        }

        $smarty->assign(array(
            'SERVERS' => $template_array,
            'EDIT' => $language->get('general', 'edit'),
            'VIEW' => $language->get('general', 'view')
        ));

    } else {
        $smarty->assign('NO_SERVERS', $language->get('admin', 'no_servers_defined'));
    }

    $template_file = 'integrations/minecraft/minecraft_server_banners.tpl';

} else {
    if(isset($_GET['server'])){
        // View
        // Get server
        $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['server']));
        if(!count($server)){
            Redirect::to(URL::build('/panel/minecraft/banners'));
            die();
        }
        $server = $server[0];

        $smarty->assign(array(
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/minecraft/banners'),
            'SERVER_NAME' => Output::getClean($server->name),
            'BANNER_URL' => Util::getSelfURL() . ltrim(rtrim(URL::build('/banner/'. urlencode($server->name) . '.png'), '/'), '/'),
            'BANNER_PATH' => rtrim(URL::build('/banner/'. urlencode($server->name) . '.png'), '/')
        ));

        $template_file = 'integrations/minecraft/minecraft_server_banners_view.tpl';

    } else {
        // Edit
        // Get server
        $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['edit']));
        if(!count($server)){
            Redirect::to(URL::build('/panel/minecraft/banners'));
            die();
        }

        if(Input::exists()){
            // Check token
            if(Token::check()){
                // Valid token
                try {
                    if(file_exists(ROOT_PATH . '/uploads/banners/' . Input::get('banner'))){
                        $queries->update('mc_servers', $_GET['edit'], array(
                            'banner_background' => Output::getClean(Input::get('banner'))
                        ));

                        $success = $language->get('admin', 'banner_updated_successfully');
                    }
                } catch (Exception $e) {
                    $errors = array($e->getMessage());
                }


            } else {
                // Invalid token
                $errors = array($language->get('general', 'invalid_token'));
            }

            // Re-query
            $server = $queries->getWhere('mc_servers', array('id', '=', $_GET['edit']));
        }

        $server = $server[0];

        $image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'banners'));
        $images = scandir($image_path);
        $template_images = array();

        // Only display jpeg, png, jpg, gif
        $allowed_exts = array('gif', 'png', 'jpg', 'jpeg');
        $n = 1;

        foreach($images as $image){
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_exts)){
                continue;
            }
            $template_images[] = array(
                'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/banners/' . $image,
                'value' => $image,
                'selected' => ($server->banner_background == $image),
                'n' => $n
            );
            $n++;
        }

        $smarty->assign(array(
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/minecraft/banners'),
            'SERVER_NAME' => Output::getClean($server->name),
            'BANNER_BACKGROUND' => $language->get('admin', 'banner_background'),
            'BANNER_BACKGROUND_VALUE' => Output::getClean($server->banner_background),
            'IMAGES' => $template_images
        ));

        $template_file = 'integrations/minecraft/minecraft_server_banners_edit.tpl';
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if(isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'SERVER_BANNERS' => $language->get('admin', 'server_banners')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
