<?php
/**
 * Staff panel Minecraft page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

if (!$user->handlePanelPageLoad('admincp.minecraft')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
$page_title = $language->get('admin', 'minecraft');
require_once ROOT_PATH . '/core/templates/backend_init.php';

if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token
        // Process input
        if (isset($_POST['enable_minecraft'])) {
            // Either enable or disable Minecraft integration
            Settings::set(Settings::MINECRAFT_INTEGRATION, Input::get('enable_minecraft'));
        } else if (isset($_POST['premium'])) {
            Settings::set('uuid_linking', Input::get('enable_premium_accounts'));
        }
    } else {
        // Invalid token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

// Check if Minecraft integration is enabled
$minecraft_enabled = Settings::get(Settings::MINECRAFT_INTEGRATION);
$uuid_linking = Settings::get('uuid_linking');

$template->getEngine()->addVariables([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_MINECRAFT_INTEGRATION' => $language->get('admin', 'enable_minecraft_integration'),
    'MINECRAFT_ENABLED' => $minecraft_enabled,
    'FORCE_PREMIUM_ACCOUNTS' => $language->get('admin', 'force_premium_accounts'),
    'FORCE_PREMIUM_ACCOUNTS_VALUE' => ($uuid_linking == '1'),
]);

if ($minecraft_enabled == 1) {
    if ($user->hasPermission('admincp.minecraft.authme')) {
        $template->getEngine()->addVariables([
            'AUTHME' => $language->get('admin', 'authme_integration'),
            'AUTHME_LINK' => URL::build('/panel/minecraft/authme'),
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.servers')) {
        $template->getEngine()->addVariables([
            'SERVERS' => $language->get('admin', 'minecraft_servers'),
            'SERVERS_LINK' => URL::build('/panel/minecraft/servers'),
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.query_errors')) {
        $template->getEngine()->addVariables([
            'QUERY_ERRORS' => $language->get('admin', 'query_errors'),
            'QUERY_ERRORS_LINK' => URL::build('/panel/minecraft/query_errors'),
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.banners') && function_exists('exif_imagetype')) {
        $template->getEngine()->addVariables([
            'BANNERS' => $language->get('admin', 'server_banners'),
            'BANNERS_LINK' => URL::build('/panel/minecraft/banners'),
        ]);
    }

    if ($user->hasPermission('admincp.core.placeholders')) {
        $template->getEngine()->addVariables([
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDERS_LINK' => URL::build('/panel/minecraft/placeholders'),
        ]);
    }
}

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate('integrations/minecraft/minecraft');
