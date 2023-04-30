<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel API page
 */

if (!$user->handlePanelPageLoad('admincp.core.api')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'api';
$page_title = $language->get('admin', 'api');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['view'])) {
    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            if (isset($_POST['action']) && $_POST['action'] == 'regen') {
                // Regenerate new API key
                // Generate new key
                $new_api_key = SecureRandom::alphanumeric();

                // Update key
                Util::setSetting('mc_api_key', $new_api_key);

                // Cache
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

                // Redirect
                Session::flash('api_success', $language->get('admin', 'api_key_regenerated'));
                Redirect::to(URL::build('/panel/core/api'));
            }

            Util::setSetting('use_api', Input::get('enable_api'));

            // Update Username sync
            $username_sync = isset($_POST['username_sync']) && $_POST['username_sync'] == 'on' ? '1' : '0';
            Util::setSetting('username_sync', $username_sync);

            Session::flash('api_success', $language->get('admin', 'api_settings_updated_successfully'));
            Redirect::to(URL::build('/panel/core/api'));
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
                $nameless_injector_column = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class)->getColumnName();
                foreach (GroupSyncManager::getInstance()->getEnabledInjectors() as $column_name => $injector) {
                    if (!$_POST[$column_name]) {
                        continue;
                    }

                    $fields[$column_name] = $_POST[$column_name];

                    if ($column_name !== $nameless_injector_column) {
                        $external = true;
                    }
                }

                if (!$external) {
                    $errors[] = $language->get('admin', 'at_least_one_external');
                } else if ($validation->passed()) {

                    DB::getInstance()->insert('group_sync', $fields);
                    Session::flash('api_success', $language->get('admin', 'group_sync_rule_created_successfully'));

                } else {
                    $errors = $validation->errors();
                }
            } else {
                if ($_POST['action'] == 'update') {

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
                                DB::getInstance()->update('group_sync', $group_sync_id, $values);
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        }
                    }

                    if (!count($errors)) {
                        Session::flash('api_success', $language->get('admin', 'group_sync_rules_updated_successfully'));
                    }
                } else {
                    if ($_POST['action'] == 'delete') {
                        if (isset($_POST['id'])) {
                            try {
                                DB::getInstance()->delete('group_sync', ['id', $_POST['id']]);
                                Session::flash('api_success', $language->get('admin', 'group_sync_rule_deleted_successfully'));
                            } catch (Exception $e) {
                                // Redirect anyway
                            }
                        }
                        die();
                    }
                }
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
    $api_enabled = Util::getSetting('use_api');

    $smarty->assign(
        [
            'PARENT_PAGE' => PARENT_PAGE,
            'DASHBOARD' => $language->get('admin', 'dashboard'),
            'CONFIGURATION' => $language->get('admin', 'configuration'),
            'API' => $language->get('admin', 'api'),
            'PAGE' => PANEL_PAGE,
            'API_INFO' => $language->get('admin', 'api_info', [
                'pluginLinkStart' => '<a href="https://plugin.namelessmc.com" target="_blank">',
                'pluginLinkEnd' => '</a>',
                'botLinkStart' => '<a href="https://github.com/NamelessMC/Nameless-Link" target="_blank">',
                'botLinkEnd' => '</a>',
            ]),
            'INFO' => $language->get('general', 'info'),
            'ENABLE_API' => $language->get('admin', 'enable_api'),
            'API_ENABLED' => $api_enabled,
            'API_KEY' => $language->get('admin', 'api_key'),
            'API_KEY_VALUE' => Util::getSetting('mc_api_key'),
            'API_KEY_REGEN_URL' => URL::build('/panel/core/api/', 'action=api_regen'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'CONFIRM_API_REGEN' => $language->get('admin', 'confirm_api_regen'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'CHANGE' => $language->get('general', 'change'),
            'API_URL' => $language->get('admin', 'api_url'),
            'API_URL_VALUE' => rtrim(URL::getSelfURL(), '/') . rtrim(URL::build('/api/v2/', '', 'non-friendly'), '/'),
            'ENABLE_API_FOR_URL' => $language->get('admin', 'api_disabled'),
            'COPY' => $language->get('admin', 'copy'),
            'USERNAME_SYNC' => $language->get('admin', 'enable_username_sync'),
            'USERNAME_SYNC_INFO' => $language->get('admin', 'enable_username_sync_info'),
            'USERNAME_SYNC_VALUE' => Util::getSetting('username_sync') === '1',
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

        $group_sync_values = [];
        foreach (DB::getInstance()->get('group_sync', ['id', '<>', 0])->results() as $rule) {
            $rule_values = [];
            foreach (get_object_vars($rule) as $column => $value) {
                $rule_values[$column] = $value;
            }
            $group_sync_values[] = $rule_values;
        }

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
                'GROUP_SYNC_VALUES' => $group_sync_values,
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
    } else {
        if ($_GET['view'] == 'api_endpoints') {

            $endpoints_array = [];
            // TODO: sort nicely
            foreach ($endpoints->getAll() as $endpoint) {
                $endpoints_array[] = [
                    'route' => $endpoint->getRoute(),
                    'module' => $endpoint->getModule(),
                    'description' => $endpoint->getDescription(),
                    'method' => $endpoint->getMethod(),
                    'auth_type' => $endpoint->getAuthType(),
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
                    'METHOD' => $language->get('admin', 'method'),
                    'ENDPOINTS_INFO' => $language->get('admin', 'api_endpoints_info', [
                        'docLinkStart' => '<a href="https://docs.namelessmc.com/en/api-documentation" target="_blank">',
                        'docLinkEnd' => '</a>'
                    ]),
                    'ENDPOINTS_ARRAY' => $endpoints_array,
                    'TYPE' => $language->get('admin', 'type'),
                    'TRANSFORMERS' => $language->get('admin', 'transformers'),
                    'TRANSFORMERS_ARRAY' => Endpoints::getAllTransformers(),
                ]
            );

            $template_file = 'core/api_endpoints.tpl';
        }
    }
}

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
