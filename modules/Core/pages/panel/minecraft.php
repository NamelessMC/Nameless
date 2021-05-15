<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft page
 */

if(!$user->handlePanelPageLoad('admincp.minecraft')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'minecraft');
$page_title = $language->get('admin', 'minecraft');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(Input::exists()){
    // Check token
    if(Token::check()){
        // Valid token
        // Process input
        if(isset($_POST['enable_minecraft'])){
            // Either enable or disable Minecraft integration
            $enable_minecraft_id = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
            $enable_minecraft_id = $enable_minecraft_id[0]->id;

            $queries->update('settings', $enable_minecraft_id, array(
                'value' => Input::get('enable_minecraft')
            ));
        }

    } else {
        // Invalid token
        $errors = array($language->get('general', 'invalid_token'));

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

// Check if Minecraft integration is enabled
$minecraft_enabled = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
$minecraft_enabled = $minecraft_enabled[0]->value;

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_MINECRAFT_INTEGRATION' => $language->get('admin', 'enable_minecraft_integration'),
    'MINECRAFT_ENABLED' => $minecraft_enabled
));

if($minecraft_enabled == 1){
    if($user->hasPermission('admincp.minecraft.authme')){
        $smarty->assign(array(
            'AUTHME' => $language->get('admin', 'authme_integration'),
            'AUTHME_LINK' => URL::build('/panel/minecraft/authme')
        ));
    }

    if($user->hasPermission('admincp.minecraft.verification')){
        $smarty->assign(array(
            'ACCOUNT_VERIFICATION' => $language->get('admin', 'account_verification'),
            'ACCOUNT_VERIFICATION_LINK' => URL::build('/panel/minecraft/account_verification')
        ));
    }

    if($user->hasPermission('admincp.minecraft.servers')){
        $smarty->assign(array(
            'SERVERS' => $language->get('admin', 'minecraft_servers'),
            'SERVERS_LINK' => URL::build('/panel/minecraft/servers')
        ));
    }

    if($user->hasPermission('admincp.minecraft.query_errors')){
        $smarty->assign(array(
            'QUERY_ERRORS' => $language->get('admin', 'query_errors'),
            'QUERY_ERRORS_LINK' => URL::build('/panel/minecraft/query_errors')
        ));
    }

    if($user->hasPermission('admincp.minecraft.banners') && function_exists('exif_imagetype')){
        $smarty->assign(array(
            'BANNERS' => $language->get('admin', 'server_banners'),
            'BANNERS_LINK' => URL::build('/panel/minecraft/banners')
        ));
    }

    if ($user->hasPermission('admincp.core.placeholders')) {
        $smarty->assign(array(
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDERS_LINK' => URL::build('/panel/minecraft/placeholders')
        ));
    }
}

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft.tpl', $smarty);
