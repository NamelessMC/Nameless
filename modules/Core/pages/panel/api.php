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
        $errors = array();

        if (Token::check()) {
            if (isset($_POST['action']) && $_POST['action'] == 'regen') {
                // Regenerate new API key
                // Generate new key
                $new_api_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);

                $plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
                $plugin_api = $plugin_api[0]->id;

                // Update key
                $queries->update(
                    'settings',
                    $plugin_api,
                    array(
                        'value' => $new_api_key
                    )
                );

                // Cache
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

                // Redirect
                Session::flash('api_success', $language->get('admin', 'api_key_regenerated'));
                Redirect::to(URL::build('/panel/core/api'));
                die();
            }

            $plugin_id = $queries->getWhere('settings', array('name', '=', 'use_api'));
            $plugin_id = $plugin_id[0]->id;
            $queries->update(
                'settings',
                $plugin_id,
                array(
                    'value' => Input::get('enable_api')
                )
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
                $validate = new Validate();
                $validation = $validate->check($_POST, [
                        'ingame_rank_name' => [
                            Validate::MIN => 2,
                            Validate::MAX => 64
                        ],
                        'discord_role_id' => [
                            Validate::MIN => 18,
                            Validate::MAX => 18,
                            Validate::NUMERIC => true
                        ],
                        'website_group' => [
                            Validate::REQUIRED => true
                        ]
                    ]
                )->messages([
                    'ingame_rank_name' => [
                        Validate::MIN => $language->get('admin', 'group_name_minimum'),
                        Validate::MAX => $language->get('admin', 'ingame_group_maximum')
                    ],
                    'discord_role_id' => [
                        Validate::MIN => $language->get('admin', 'discord_role_id_length'),
                        Validate::MAX => $language->get('admin', 'discord_role_id_length'),
                        Validate::NUMERIC => $language->get('admin', 'discord_role_id_numeric')
                    ]
                ]);

                $errors = array();

                if (empty($_POST['ingame_rank_name']) && empty($_POST['discord_role_id'])) {
                    $errors[] = $language->get('admin', 'at_least_one_external');
                } else if ($validation->passed()) {

                    $discord_role_id = intval(Input::get('discord_role_id'));
                    if ($discord_role_id == 0) {
                        $discord_role_id = null;
                    }
                    $fields = array();
                    $fields['website_group_id']  = intval(Input::get('website_group'));
                    $fields['discord_role_id'] = $discord_role_id;

                    $ingame_rank_name = $_POST['ingame_rank_name'];

                    if (!empty($ingame_rank_name)) {
                        if (strlen(str_replace(' ', '', $ingame_rank_name)) > 1 && strlen(str_replace(' ', '', $ingame_rank_name)) < 65) {
                            $fields['ingame_rank_name'] = $ingame_rank_name;
                        } else {
                            $errors[] = $language->get('admin', 'group_name_minimum');
                            $errors[] = $language->get('admin', 'ingame_group_maximum');
                        }
                    } else {
                        $fields['ingame_rank_name'] = null;
                    }

                    if ($discord_role_id == null && empty($ingame_rank_name)) {
                        $errors[] = $language->get('admin', 'at_least_one_external');
                    }

                    if (!count($errors)) {
                        $queries->create('group_sync', $fields);
                        Session::flash('api_success', $language->get('admin', 'group_sync_rule_created_successfully'));
                    }
                } else {
                    $errors = $validation->errors();
                }
            } else if ($_POST['action'] == 'update') {
                $errors = array();

                if (isset($_POST['ingame_group']) && isset($_POST['discord_role']) && isset($_POST['website_group'])) {

                    foreach ($_POST['website_group'] as $key => $website_group) {
                        if (!empty($_POST['ingame_group'][$key]) || !empty($_POST['discord_role'][$key])) {

                            $ingame_group = $_POST['ingame_group'][$key];
                            $discord_role_id = intval($_POST['discord_role'][$key]);
                            if ($discord_role_id == 0) $discord_role_id = null;

                            $fields = array();
                            $fields['website_group_id']  = intval($website_group);

                            if (!empty($ingame_group)) {
                                if (strlen(str_replace(' ', '', $ingame_group)) > 1 && strlen(str_replace(' ', '', $ingame_group)) < 65) {
                                    $fields['ingame_rank_name'] = $ingame_group;
                                } else {
                                    $errors[] = $language->get('admin', 'group_name_minimum');
                                    $errors[] = $language->get('admin', 'ingame_group_maximum');
                                }
                            } else $fields['ingame_rank_name'] = null;
                            if (strlen($discord_role_id) == 0 || strlen($discord_role_id) == 18) {
                                $fields['discord_role_id'] = $discord_role_id;
                            } else {
                                $errors[] = $language->get('admin', 'discord_role_id_length');
                            }

                            if (!count($errors)) {
                                try {
                                    $queries->update('group_sync', $key, $fields);
                                } catch (Exception $e) {
                                    $errors[] = $e->getMessage();
                                }
                            }
                        } else {
                            $errors[] = $language->get('admin', 'at_least_one_external');
                        }
                    }
                }

                if (!count($errors)) {
                    Session::flash('api_success', $language->get('admin', 'group_sync_rules_updated_successfully'));
                }
            } else if ($_POST['action'] == 'delete') {
                if (isset($_POST['id'])) {
                    try {
                        $queries->delete('group_sync', array('id', '=', $_POST['id']));
                        Session::flash('api_success', $language->get('admin', 'group_sync_rule_deleted_successfully'));
                    } catch (Exception $e) {
                        // Redirect anyway
                    }
                }
                die();
            }
        } else {
            $errors[] = array($language->get('general', 'invalid_token'));
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('api_success')) {
    $smarty->assign(
        array(
            'SUCCESS' => Session::flash('api_success'),
            'SUCCESS_TITLE' => $language->get('general', 'success')
        )
    );
}

if (isset($errors) && count($errors)) {
    $smarty->assign(
        array(
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        )
    );
}

if (!isset($_GET['view'])) {
    // Is the API enabled?
    $api_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
    if (count($api_enabled)) {
        $api_enabled = $api_enabled[0]->value;
    } else {
        $queries->create(
            'settings',
            array(
                'name' => 'use_api',
                'value' => 0
            )
        );
        $api_enabled = '0';
    }

    // Get API key
    $plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
    $plugin_api = $plugin_api[0]->value;

    // Is email verification enabled
    $emails = $configuration->get('Core', 'email_verification');

    // Is API verification enabled?
    $api_verification = $configuration->get('Core', 'api_verification');

    // Is the username sync enabled?
    $username_sync = $configuration->get('Core', 'username_sync');

    $smarty->assign(
        array(
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
        )
    );

    $template_file = 'core/api.tpl';
} else {

    if ($_GET['view'] == 'group_sync') {
        // Get all groups
        $groups = $queries->getWhere('groups', array('id', '<>', 0));
        $website_groups = array();
        foreach ($groups as $group) {
            $website_groups[] = array(
                'id' => Output::getClean($group->id),
                'name' => Output::getClean($group->name)
            );
        }

        // Get ingame groups
        $ingame_groups = DB::getInstance()->query("SELECT `groups` FROM `nl2_query_results` ORDER BY `id` DESC LIMIT 1")->first();
        $ingame_groups = json_decode($ingame_groups->groups, true);

        // Get Discord groups
        $discord_groups = array();
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            $discord_groups = Discord::getRoles();
        }

        // Get existing group sync configuration
        $group_sync = $queries->getWhere('group_sync', array('id', '<>', 0));
        $template_groups = array();
        foreach ($group_sync as $group) {
            $template_groups[] = array(
                'id' => Output::getClean($group->id),
                'ingame' => Output::getClean($group->ingame_rank_name),
                'discord' => $group->discord_role_id,
                'website' => $group->website_group_id
            );
        }

        $smarty->assign(
            array(
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
                'INGAME_GROUPS' => is_array($ingame_groups) ? $ingame_groups : array(),
                'INGAME_GROUP_NAME' => $language->get('admin', 'ingame_group'),
                'DISCORD_GROUPS' => is_array($discord_groups) ? $discord_groups : array(),
                'DISCORD_ROLE_ID' => $language->get('admin', 'discord_role_id'),
                'WEBSITE_GROUP' => $language->get('admin', 'website_group'),
                'GROUPS' => $website_groups,
                'GROUP_SYNC_VALUES' => $template_groups,
                'DELETE' => $language->get('general', 'delete'),
                'NEW_RULE' => $language->get('admin', 'new_rule'),
                'EXISTING_RULES' => $language->get('admin', 'existing_rules'),
                'DISCORD_INTEGRATION_NOT_SETUP' => $language->get('admin', 'discord_integration_not_setup'),
                'GROUP_SYNC_PLUGIN_NOT_SET_UP' => $language->get('admin', 'group_sync_plugin_not_set_up'),
                'DELETE_LINK' => URL::build('/panel/core/api/', 'view=group_sync'),
                'NONE' => $language->get('general', 'none'),
                'DISABLED' => $language->get('admin', 'disabled')
            )
        );

        $template_file = 'core/api_group_sync.tpl';
    } else if ($_GET['view'] == 'api_endpoints') {

        $endpoints_array = array();
        foreach ($endpoints->getAll() as $endpoint) {
            $endpoints_array[] = array(
                'route' => $endpoint->getRoute(),
                'module' => $endpoint->getModule(),
                'description' => $endpoint->getDescription(),
                'method' => $endpoint->getMethod()
            );
        };

        $smarty->assign(
            array(
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
            )
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
