<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel API page
 */

if(!$user->handlePanelPageLoad('admincp.core.api')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'api');
$page_title = $language->get('admin', 'api');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['view'])) {
    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            if (isset($_POST['action']) && $_POST['action'] == 'regen') {
                // Regenerate new API key
                // Generate new key
                $new_api_key = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 32);

                $plugin_api = $queries->getWhere('settings', ['name', '=', 'mc_api_key']);
                $plugin_api = $plugin_api[0]->id;

                // Update key
                $queries->update(
                    'settings',
                    $plugin_api,
                    [
                        'value' => $new_api_key
                    ]
                );

                // Cache
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

                // Redirect
                Session::flash('api_success', $language->get('admin', 'api_key_regenerated'));
                Redirect::to(URL::build('/panel/core/api'));
                die();
            }

            $plugin_id = $queries->getWhere('settings', ['name', '=', 'use_api']);
            $plugin_id = $plugin_id[0]->id;
            $queries->update(
                'settings',
                $plugin_id,
                [
                    'value' => Input::get('enable_api')
                ]
            );

            // Update email verification
            $verification = isset($_POST['verification']) && $_POST['verification'] == 'on' ? 1 : 0;
            $configuration->set('Core', 'email_verification', $verification);

            // Update API verification
            $api_verification = isset($_POST['api_verification']) && $_POST['api_verification'] == 'on' ? 1 : 0;
            $configuration->set('Core', 'api_verification', $api_verification);

            // Update Username sync
            $username_sync = isset($_POST['username_sync']) && $_POST['username_sync'] == 'on' ? 1 : 0;
            $configuration->set('Core', 'username_sync', $username_sync);

            Session::flash('api_success', $language->get('admin', 'api_settings_updated_successfully'));

            //Log::getInstance()->log(Log::Action('admin/api/change'));
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }
} else {
    // Group sync
    if (Input::exists()) {
        if (Token::check()) {
            if ($_POST['action'] == 'create') {
                $validation = GroupSyncManager::getInstance()->makeValidator($_POST, $language);

                $errors = [];

                $external = false;
                $fields = [];

                foreach (GroupSyncManager::getInstance()->getEnabledInjectors() as $injector) {
                    if (
                        $injector->getColumnName() == GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class)->getColumnName()
                    ) {
                        $fields[$injector->getColumnName()] = $_POST[$injector->getColumnName()];
                        continue;
                    }

                    if ($_POST[$injector->getColumnName()]) {
                        if ($_POST[$injector->getColumnName()] === 0) {
                            continue;
                        }

                        $fields[$injector->getColumnName()] = $_POST[$injector->getColumnName()];
                        $external = true;
                    }
                }

                if (!$external) {
                    $errors[] = $language->get('admin', 'at_least_one_external');
                } else if ($validation->passed()) {

                    $queries->create('group_sync', $fields);
                    Session::flash('api_success', $language->get('admin', 'group_sync_rule_created_successfully'));

                } else {
                    $errors = $validation->errors();
                }
            } else if ($_POST['action'] == 'update') {

                $namelessmc_injector = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class);

                foreach ($_POST['existing'] as $group_sync_id => $values) {
                    $errors = [];

                    $validator = GroupSyncManager::getInstance()->makeValidator($values, $language);

                    if (!$validator->passed()) {
                        $errors = $validator->errors();
                    } else {
                        $external = false;
                        foreach ($values as $column => $group) {
                            if (
                                $group
                                && $group !== 0
                                && $column != $namelessmc_injector->getColumnName()
                            ) {
                                $external = true;
                            }
                        }

                        if (!$external) {
                            $errors[] = $language->get('admin', 'at_least_one_external');
                        }
                    }

                    if (!count($errors)) {
                        try {
                            $queries->update('group_sync', $group_sync_id, $values);
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    }
                }

                if (!count($errors)) {
                    Session::flash('api_success', $language->get('admin', 'group_sync_rules_updated_successfully'));
                }
            } else if ($_POST['action'] == 'delete') {
                if (isset($_POST['id'])) {
                    try {
                        $queries->delete('group_sync', ['id', '=', $_POST['id']]);
                        Session::flash('api_success', $language->get('admin', 'group_sync_rule_deleted_successfully'));
                    } catch (Exception $e) {
                        // Redirect anyway
                    }
                }
                die();
            }
        } else {
            $errors[] = [$language->get('general', 'invalid_token')];
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('api_success')) {
    $smarty->assign(
        [
            'SUCCESS' => Session::flash('api_success'),
            'SUCCESS_TITLE' => $language->get('general', 'success')
        ]
    );
}

if (isset($errors) && count($errors)) {
    $smarty->assign(
        [
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        ]
    );
}

if (!isset($_GET['view'])) {
    // Is the API enabled?
    $api_enabled = $queries->getWhere('settings', ['name', '=', 'use_api']);
    if (count($api_enabled)) {
        $api_enabled = $api_enabled[0]->value;
    } else {
        $queries->create(
            'settings',
            [
                'name' => 'use_api',
                'value' => 0
            ]
        );
        $api_enabled = '0';
    }

    // Get API key
    $plugin_api = $queries->getWhere('settings', ['name', '=', 'mc_api_key']);
    $plugin_api = $plugin_api[0]->value;

    // Is email verification enabled
    $emails = $configuration->get('Core', 'email_verification');

    // Is API verification enabled?
    $api_verification = $configuration->get('Core', 'api_verification');

    // Is the username sync enabled?
    $username_sync = $configuration->get('Core', 'username_sync');

    $smarty->assign(
        [
            'PARENT_PAGE' => PARENT_PAGE,
            'DASHBOARD' => $language->get('admin', 'dashboard'),
            'CONFIGURATION' => $language->get('admin', 'configuration'),
            'API' => $language->get('admin', 'api'),
            'PAGE' => PANEL_PAGE,
            'API_INFO' => $language->get('admin', 'api_info'),
            'INFO' => $language->get('general', 'info'),
            'ENABLE_API' => $language->get('admin', 'enable_api'),
            'API_ENABLED' => $api_enabled,
            'API_KEY' => $language->get('admin', 'api_key'),
            'API_KEY_VALUE' => Output::getClean($plugin_api),
            'API_KEY_REGEN_URL' => URL::build('/panel/core/api/', 'action=api_regen'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'CONFIRM_API_REGEN' => $language->get('admin', 'confirm_api_regen'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'CHANGE' => $language->get('general', 'change'),
            'API_URL' => $language->get('admin', 'api_url'),
            'API_URL_VALUE' => rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($plugin_api), '', 'non-friendly'), '/'),
            'ENABLE_API_FOR_URL' => $language->get('api', 'api_disabled'),
            'COPY' => $language->get('admin', 'copy'),
            'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
            'EMAIL_VERIFICATION_VALUE' => $emails,
            'API_VERIFICATION' => $language->get('admin', 'api_verification'),
            'API_VERIFICATION_VALUE' => $api_verification,
            'API_VERIFICATION_INFO' => $language->get('admin', 'api_verification_info'),
            'USERNAME_SYNC' => $language->get('admin', 'enable_username_sync'),
            'USERNAME_SYNC_INFO' => $language->get('admin', 'enable_username_sync_info'),
            'USERNAME_SYNC_VALUE' => $username_sync,
            'TOKEN' => Token::get(),
            'SUBMIT' => $language->get('general', 'submit'),
            'COPIED' => $language->get('general', 'copied'),
            'GROUP_SYNC' => $language->get('admin', 'group_sync'),
            'GROUP_SYNC_LINK' => URL::build('/panel/core/api/', 'view=group_sync'),
            'API_ENDPOINTS' => $language->get('admin', 'api_endpoints'),
            'API_ENDPOINTS_LINK' => URL::build('/panel/core/api/', 'view=api_endpoints')
        ]
    );

    $template_file = 'core/api.tpl';
} else {

    if ($_GET['view'] == 'group_sync') {

        $smarty->assign(
            [
                'PARENT_PAGE' => PARENT_PAGE,
                'DASHBOARD' => $language->get('admin', 'dashboard'),
                'CONFIGURATION' => $language->get('admin', 'configuration'),
                'API' => $language->get('admin', 'api'),
                'PAGE' => PANEL_PAGE,
                'INFO' => $language->get('general', 'info'),
                'GROUP_SYNC_INFO' => $language->get('admin', 'group_sync_info'),
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/api'),
                'TOKEN' => Token::get(),
                'SUBMIT' => $language->get('general', 'submit'),
                'GROUP_SYNC_VALUES' => $queries->getWhere('group_sync', ['id', '<>', 0]),
                'GROUP_SYNC_INJECTORS' => GroupSyncManager::getInstance()->getInjectors(),
                'ENABLED_GROUP_SYNC_INJECTORS' => GroupSyncManager::getInstance()->getEnabledInjectors(),
                'NAMELESS_INJECTOR_COLUMN' => GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class)->getColumnName(),
                'LANGUAGE' => $language,
                'DELETE' => $language->get('general', 'delete'),
                'NEW_RULE' => $language->get('admin', 'new_rule'),
                'EXISTING_RULES' => $language->get('admin', 'existing_rules'),
                'DELETE_LINK' => URL::build('/panel/core/api/', 'view=group_sync'),
                'NONE' => $language->get('general', 'none'),
                'DISABLED' => $language->get('admin', 'disabled')
            ]
        );

        $template_file = 'core/api_group_sync.tpl';
    } else if ($_GET['view'] == 'api_endpoints') {

        $endpoints_array = [];
        foreach ($endpoints->getAll() as $endpoint) {
            $endpoints_array[] = [
                'route' => $endpoint->getRoute(),
                'module' => $endpoint->getModule(),
                'description' => $endpoint->getDescription(),
                'method' => $endpoint->getMethod()
            ];
        }

        $smarty->assign(
            [
                'PARENT_PAGE' => PARENT_PAGE,
                'DASHBOARD' => $language->get('admin', 'dashboard'),
                'CONFIGURATION' => $language->get('admin', 'configuration'),
                'API_ENDPOINTS' => $language->get('admin', 'api_endpoints'),
                'PAGE' => PANEL_PAGE,
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/api'),
                'ROUTE' => $language->get('admin', 'route'),
                'DESCRIPTION' => $language->get('admin', 'description'),
                'MODULE' => $language->get('admin', 'module'),
                'ENDPOINTS_INFO' => $language->get('admin', 'api_endpoints_info'),
                'ENDPOINTS_ARRAY' => $endpoints_array
            ]
        );

        $template_file = 'core/api_endpoints.tpl';
    }
}

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
