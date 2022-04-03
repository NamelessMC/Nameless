<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel debugging + maintenance page
 */

if (!$user->handlePanelPageLoad('admincp.core.debugging')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'debugging_and_maintenance';
$page_title = $language->get('admin', 'debugging_and_maintenance');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        // Valid token
        // Validate message
        $validation = Validate::check($_POST, [
            'message' => [
                Validate::MAX => 1024
            ]
        ])->message($language->get('admin', 'maintenance_message_max_1024'));

        if ($validation->passed()) {
            // Update database and cache
            // Is debug mode enabled or not?
            if (isset($_POST['enable_debugging']) && $_POST['enable_debugging'] == 1) {
                $enabled = 1;
            } else {
                $enabled = 0;
            }

            $debug_id = $queries->getWhere('settings', ['name', '=', 'error_reporting']);
            $debug_id = $debug_id[0]->id;
            $queries->update('settings', $debug_id, [
                'value' => $enabled
            ]);

            // Cache
            $cache->setCache('error_cache');
            $cache->store('error_reporting', $enabled);

            // Is maintenance enabled or not?
            if (isset($_POST['enable_maintenance']) && $_POST['enable_maintenance'] == 1) {
                $enabled = 'true';
            } else {
                $enabled = 'false';
            }

            $maintenance_id = $queries->getWhere('settings', ['name', '=', 'maintenance']);
            $maintenance_id = $maintenance_id[0]->id;
            $queries->update('settings', $maintenance_id, [
                'value' => $enabled
            ]);

            if (isset($_POST['message']) && !empty($_POST['message'])) {
                $message = Input::get('message');
            } else {
                $message = 'Maintenance mode is enabled.';
            }

            $maintenance_id = $queries->getWhere('settings', ['name', '=', 'maintenance_message']);
            $maintenance_id = $maintenance_id[0]->id;
            $queries->update('settings', $maintenance_id, [
                'value' => $message
            ]);

            //Log::getInstance()->log(Log::Action('admin/core/maintenance/update'));

            // Cache
            $cache->setCache('maintenance_cache');
            $cache->store('maintenance', [
                'maintenance' => $enabled,
                'message' => $message
            ]);

            // Page load timer
            if (isset($_POST['enable_page_load_timer']) && $_POST['enable_page_load_timer'] == 1) {
                $enabled = 1;
            } else {
                $enabled = 0;
            }

            $load_id = $queries->getWhere('settings', ['name', '=', 'page_loading']);
            $load_id = $load_id[0]->id;
            $queries->update('settings', $load_id, [
                'value' => $enabled
            ]);

            // Cache
            $cache->setCache('page_load_cache');
            $cache->store('page_load', $enabled);

            // Reload to update debugging
            Session::flash('debugging_success', $language->get('admin', 'debugging_settings_updated_successfully'));
            Redirect::to(URL::build('/panel/core/debugging_and_maintenance'));
        }

        $errors = $validation->errors();
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('debugging_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('debugging_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$cache->setCache('maintenance_cache');
$maintenance = $cache->retrieve('maintenance');

$cache->setCache('page_load_cache');
if ($cache->isCached('page_load')) {
    $page_loading = $cache->retrieve('page_load');
} else {
    $page_loading = 0;
}

if ($user->hasPermission('admincp.errors')) {
    $smarty->assign([
        'ERROR_LOGS' => $language->get('admin', 'error_logs'),
        'ERROR_LOGS_LINK' => URL::build('/panel/core/errors')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'DEBUGGING_AND_MAINTENANCE' => $language->get('admin', 'debugging_and_maintenance'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_DEBUG_MODE' => $language->get('admin', 'enable_debug_mode'),
    'ENABLE_DEBUG_MODE_VALUE' => (defined('DEBUGGING') ? DEBUGGING : 0),
    'ENABLE_MAINTENANCE_MODE' => $language->get('admin', 'enable_maintenance_mode'),
    'ENABLE_MAINTENANCE_MODE_VALUE' => ((isset($maintenance['maintenance']) && $maintenance['maintenance'] != 'false') ? 1 : 0),
    'ENABLE_PAGE_LOAD_TIMER' => $language->get('admin', 'enable_page_load_timer'),
    'ENABLE_PAGE_LOAD_TIMER_VALUE' => $page_loading,
    'MAINTENANCE_MODE_MESSAGE' => $language->get('admin', 'maintenance_mode_message'),
    'MAINTENANCE_MODE_MESSAGE_VALUE' => Output::getPurified($maintenance['message']),
    'DEBUG_LINK' => $language->get('admin', 'debug_link'),
    'DEBUG_LINK_URL' => URL::build('/queries/debug_link'),
    'TOASTR_COPIED' => $language->get('admin', 'debug_link_toastr', [
        'linkStart' => '<u><a href="{x}" target="_blank">',
        'linkEnd' => '</a></u>',
    ]),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/debugging_and_maintenance.tpl', $smarty);
