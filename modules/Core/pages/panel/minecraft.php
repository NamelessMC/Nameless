<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft page
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

if (!$user->handlePanelPageLoad('admincp.minecraft')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
$page_title = $language->get('admin', 'minecraft');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    // Check token
    try {
        if (Token::check()) {
            // Valid token
            // Process input
            if (isset($_POST['enable_minecraft'])) {
                // Either enable or disable Minecraft integration
                DB::getInstance()->update('settings', ['name', 'mc_integration'], [
                    'value' => Input::get('enable_minecraft')
                ]);
            }

        } else {
            // Invalid token
            $errors = [$language->get('general', 'invalid_token')];

        }
    } catch (Exception $ignored) {
    }
}

// Load modules + template
Module::loadPageWithMessages($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template, $language, $success ?? null, $errors ?? null);

// Check if Minecraft integration is enabled
$minecraft_enabled = MINECRAFT;

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_MINECRAFT_INTEGRATION' => $language->get('admin', 'enable_minecraft_integration'),
    'MINECRAFT_ENABLED' => $minecraft_enabled
]);

if ($minecraft_enabled === true) {
    if ($user->hasPermission('admincp.minecraft.authme')) {
        $smarty->assign([
            'AUTHME' => $language->get('admin', 'authme_integration'),
            'AUTHME_LINK' => URL::build('/panel/minecraft/authme')
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.verification')) {
        $smarty->assign([
            'ACCOUNT_VERIFICATION' => $language->get('admin', 'account_verification'),
            'ACCOUNT_VERIFICATION_LINK' => URL::build('/panel/minecraft/account_verification')
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.servers')) {
        $smarty->assign([
            'SERVERS' => $language->get('admin', 'minecraft_servers'),
            'SERVERS_LINK' => URL::build('/panel/minecraft/servers')
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.query_errors')) {
        $smarty->assign([
            'QUERY_ERRORS' => $language->get('admin', 'query_errors'),
            'QUERY_ERRORS_LINK' => URL::build('/panel/minecraft/query_errors')
        ]);
    }

    if (function_exists('exif_imagetype') && $user->hasPermission('admincp.minecraft.banners')) {
        $smarty->assign([
            'BANNERS' => $language->get('admin', 'server_banners'),
            'BANNERS_LINK' => URL::build('/panel/minecraft/banners')
        ]);
    }

    if ($user->hasPermission('admincp.core.placeholders')) {
        $smarty->assign([
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDERS_LINK' => URL::build('/panel/minecraft/placeholders')
        ]);
    }
}

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
try {
    $template->displayTemplate('integrations/minecraft/minecraft.tpl', $smarty);
} catch (SmartyException $ignored) {
}
