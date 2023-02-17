<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft servers page
 */

if (!$user->handlePanelPageLoad('admincp.minecraft.servers')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
const MINECRAFT_PAGE = 'servers';
$page_title = $language->get('admin', 'minecraft_servers');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'new':
            // Handle input
            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'server_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 20
                        ],
                        'server_address' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 64
                        ],
                        'server_port' => [
                            Validate::MAX => 5
                        ],
                        'parent_server' => [
                            Validate::REQUIRED => true,
                        ],
                        'query_port' => [
                            Validate::MAX => 5
                        ]
                    ])->messages([
                        'server_name' => [
                            Validate::REQUIRED => $language->get('admin', 'server_name_required'),
                            Validate::MIN => $language->get('admin', 'server_name_minimum'),
                            Validate::MAX => $language->get('admin', 'server_name_maximum')
                        ],
                        'server_address' => [
                            Validate::REQUIRED => $language->get('admin', 'server_address_required'),
                            Validate::MIN => $language->get('admin', 'server_address_minimum'),
                            Validate::MAX => $language->get('admin', 'server_address_maximum')
                        ],
                        'server_port' => [
                            Validate::REQUIRED => $language->get('admin', 'server_port_required'),
                            Validate::MIN => $language->get('admin', 'server_port_minimum'),
                            Validate::MAX => $language->get('admin', 'server_port_maximum')
                        ],
                        'parent_server' => $language->get('admin', 'server_parent_required'),
                        'query_port' => $language->get('admin', 'query_port_maximum')
                    ]);

                    if ($validation->passed()) {
                        // Handle input
                        try {
                            // Bedrock selected?
                            if (isset($_POST['bedrock']) && $_POST['bedrock'] == 1) {
                                $bedrock = 1;
                            } else {
                                $bedrock = 0;
                            }

                            // BungeeCord selected?
                            if (isset($_POST['bungee_instance']) && $_POST['bungee_instance'] == 1) {
                                $bungee = 1;
                            } else {
                                $bungee = 0;
                            }

                            // Pre 1.7?
                            if (isset($_POST['pre_17']) && $_POST['pre_17'] == 1) {
                                $pre = 1;
                            } else {
                                $pre = 0;
                            }

                            // Status enabled?
                            if (isset($_POST['status_query_enabled']) && $_POST['status_query_enabled'] == 1) {
                                $status = 1;
                            } else {
                                $status = 0;
                            }

                            // Show IP enabled?
                            if (isset($_POST['show_ip_enabled']) && $_POST['show_ip_enabled'] == 1) {
                                $show_ip = 1;
                            } else {
                                $show_ip = 0;
                            }

                            // Player list enabled?
                            if (isset($_POST['query_enabled']) && $_POST['query_enabled'] == 1) {
                                $query = 1;
                            } else {
                                $query = 0;
                            }

                            // Parent server
                            if ($_POST['parent_server'] == 'none') {
                                $parent = 0;
                            } else {
                                $parent = $_POST['parent_server'];
                            }

                            // Validate server port
                            if (is_numeric(Input::get('server_port'))) {
                                $port = Input::get('server_port');
                            } else {
                                if (!isset($_POST['server_port']) || empty($_POST['server_port'])) {
                                    $port = null;
                                } else {
                                    $port = 25565;
                                }
                            }

                            // Validate server query port
                            if (is_numeric(Input::get('query_port'))) {
                                $query_port = Input::get('query_port');
                            } else {
                                $query_port = 25565;
                            }

                            $last_server_order = DB::getInstance()->query('SELECT `order` FROM nl2_mc_servers ORDER BY `order` DESC LIMIT 1')->results();
                            if (count($last_server_order)) {
                                $last_server_order = $last_server_order[0]->order;
                            } else {
                                $last_server_order = 0;
                            }

                            DB::getInstance()->insert('mc_servers', [
                                'ip' => Input::get('server_address'),
                                'query_ip' => Input::get('server_address'),
                                'name' => Input::get('server_name'),
                                'display' => $status,
                                'pre' => $pre,
                                'player_list' => $query,
                                'parent_server' => $parent,
                                'bungee' => $bungee,
                                'bedrock' => $bedrock,
                                'port' => $port,
                                'query_port' => $query_port,
                                'show_ip' => $show_ip,
                                'order' => $last_server_order + 1
                            ]);

                            Session::flash('admin_mc_servers_success', $language->get('admin', 'server_created'));
                            Redirect::to(URL::build('/panel/minecraft/servers'));

                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }
                    } else {
                        // Validation failed
                        $errors = $validation->errors();
                    }

                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $available_parent_servers = DB::getInstance()->get('mc_servers', ['parent_server', 0])->results();

            // Display query information alert only if external query is selected
            $query_type = Util::getSetting('query_type', 'internal');
            if ($query_type === 'external') {
                $smarty->assign('SERVER_QUERY_INFORMATION', $language->get('admin', 'server_query_information'));
            }

            $smarty->assign([
                'ADDING_SERVER' => $language->get('admin', 'adding_server'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/minecraft/servers'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'SERVER_INFORMATION' => $language->get('admin', 'server_information'),
                'SERVER_NAME' => $language->get('admin', 'server_name'),
                'SERVER_NAME_VALUE' => Output::getClean(Input::get('server_name')),
                'SERVER_ADDRESS' => $language->get('admin', 'server_address'),
                'SERVER_ADDRESS_VALUE' => Output::getClean(Input::get('server_address')),
                'INFO' => $language->get('general', 'info'),
                'SERVER_ADDRESS_INFO' => $language->get('admin', 'server_address_help'),
                'SERVER_PORT' => $language->get('admin', 'server_port'),
                'SERVER_PORT_VALUE' => Output::getClean(Input::get('server_port')),
                'SERVER_PORT_INFO' => $language->get('admin', 'leave_port_empty_for_srv'),
                'PARENT_SERVER' => $language->get('admin', 'parent_server'),
                'PARENT_SERVER_INFO' => $language->get('admin', 'parent_server_help'),
                'NO_PARENT_SERVER' => $language->get('admin', 'no_parent_server'),
                'AVAILABLE_PARENT_SERVERS' => $available_parent_servers,
                'PARENT_SERVER_VALUE' => Output::getClean(Input::get('parent_server')),
                'BUNGEE_INSTANCE' => $language->get('admin', 'bungee_instance'),
                'BUNGEE_INSTANCE_INFO' => $language->get('admin', 'bungee_instance_help'),
                'BEDROCK' => $language->get('admin', 'bedrock'),
                'BEDROCK_INFO' => $language->get('admin', 'bedrock_help'),
                'PRE_17' => $language->get('admin', 'pre_1_7'),
                'QUERY_INFORMATION' => $language->get('admin', 'query_information'),
                'ENABLE_STATUS_QUERY' => $language->get('admin', 'enable_status_query'),
                'ENABLE_STATUS_QUERY_INFO' => $language->get('admin', 'status_query_help'),
                'SHOW_IP_ON_STATUS_PAGE' => $language->get('admin', 'show_ip_on_status_page'),
                'SHOW_IP_ON_STATUS_PAGE_INFO' => $language->get('admin', 'show_ip_on_status_page_info'),
                'ENABLE_PLAYER_LIST' => $language->get('admin', 'enable_player_list'),
                'ENABLE_PLAYER_LIST_INFO' => $language->get('admin', 'player_list_help'),
                'SERVER_QUERY_PORT' => $language->get('admin', 'server_query_port'),
                'SERVER_QUERY_PORT_INFO' => $language->get('admin', 'server_query_port_help'),
                'SERVER_QUERY_PORT_VALUE' => Output::getClean(Input::get('query_port'))
            ]);

            $template_file = 'integrations/minecraft/minecraft_servers_new.tpl';

            break;

        case 'edit':
            // Get server
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/minecraft/servers'));
            }

            $server_editing = DB::getInstance()->get('mc_servers', ['id', $_GET['id']])->results();
            if (!count($server_editing)) {
                Redirect::to(URL::build('/panel/minecraft/servers'));
            }
            $server_editing = $server_editing[0];

            // Handle input
            if (Input::exists()) {
                if (Token::check()) {
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'server_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 20
                        ],
                        'server_address' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 64
                        ],
                        'server_port' => [
                            Validate::MAX => 5
                        ],
                        'parent_server' => [
                            Validate::REQUIRED => true,
                        ],
                        'query_port' => [
                            Validate::MAX => 5
                        ]
                    ])->messages([
                        'server_name' => [
                            Validate::REQUIRED => $language->get('admin', 'server_name_required'),
                            Validate::MIN => $language->get('admin', 'server_name_minimum'),
                            Validate::MAX => $language->get('admin', 'server_name_maximum')
                        ],
                        'server_address' => [
                            Validate::REQUIRED => $language->get('admin', 'server_address_required'),
                            Validate::MIN => $language->get('admin', 'server_address_minimum'),
                            Validate::MAX => $language->get('admin', 'server_address_maximum')
                        ],
                        'server_port' => [
                            Validate::REQUIRED => $language->get('admin', 'server_port_required'),
                            Validate::MIN => $language->get('admin', 'server_port_minimum'),
                            Validate::MAX => $language->get('admin', 'server_port_maximum')
                        ],
                        'parent_server' => $language->get('admin', 'server_parent_required'),
                        'query_port' => $language->get('admin', 'query_port_maximum')
                    ]);

                    if ($validation->passed()) {
                        // Handle input
                        try {
                            // Bedrock selected?
                            if (isset($_POST['bedrock']) && $_POST['bedrock'] == 1) {
                                $bedrock = 1;
                            } else {
                                $bedrock = 0;
                            }

                            // BungeeCord selected?
                            if (isset($_POST['bungee_instance']) && $_POST['bungee_instance'] == 1) {
                                $bungee = 1;
                            } else {
                                $bungee = 0;
                            }

                            // Pre 1.7?
                            if (isset($_POST['pre_17']) && $_POST['pre_17'] == 1) {
                                $pre = 1;
                            } else {
                                $pre = 0;
                            }

                            // Status enabled?
                            if (isset($_POST['status_query_enabled']) && $_POST['status_query_enabled'] == 1) {
                                $status = 1;
                            } else {
                                $status = 0;
                            }

                            // Show IP enabled?
                            if (isset($_POST['show_ip_enabled']) && $_POST['show_ip_enabled'] == 1) {
                                $show_ip = 1;
                            } else {
                                $show_ip = 0;
                            }

                            // Player list enabled?
                            if (isset($_POST['query_enabled']) && $_POST['query_enabled'] == 1) {
                                $query = 1;
                            } else {
                                $query = 0;
                            }

                            // Parent server
                            if ($_POST['parent_server'] == 'none') {
                                $parent = 0;
                            } else {
                                $parent = $_POST['parent_server'];
                            }

                            // Validate server port
                            if (is_numeric(Input::get('server_port'))) {
                                $port = Input::get('server_port');
                            } else {
                                if (!isset($_POST['server_port']) || empty($_POST['server_port'])) {
                                    $port = null;
                                } else {
                                    $port = 25565;
                                }
                            }

                            // Validate server query port
                            if (is_numeric(Input::get('query_port'))) {
                                $query_port = Input::get('query_port');
                            } else {
                                $query_port = 25565;
                            }

                            DB::getInstance()->update('mc_servers', $server_editing->id, [
                                'ip' => Output::getClean(Input::get('server_address')),
                                'query_ip' => Output::getClean(Input::get('server_address')),
                                'name' => Output::getClean(Input::get('server_name')),
                                'display' => $status,
                                'pre' => $pre,
                                'player_list' => $query,
                                'parent_server' => $parent,
                                'bungee' => $bungee,
                                'bedrock' => $bedrock,
                                'port' => $port,
                                'query_port' => $query_port,
                                'show_ip' => $show_ip
                            ]);

                            Session::flash('admin_mc_servers_success', $language->get('admin', 'server_updated'));
                            Redirect::to(URL::build('/panel/minecraft/servers/', 'action=edit&id=' . urlencode($server_editing->id)));

                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }
                    } else {
                        // Validation failed
                        $errors = $validation->errors();
                    }

                } else {
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            $available_parent_servers = DB::getInstance()->get('mc_servers', ['parent_server', 0])->results();

            // Display query information alert only if external query is selected
            $query_type = Util::getSetting('query_type', 'internal');

            if ($query_type == 'external') {
                $smarty->assign('SERVER_QUERY_INFORMATION', $language->get('admin', 'server_query_information'));
            }

            $smarty->assign([
                'EDITING_SERVER' => $language->get('admin', 'editing_server'),
                'SERVER_ID' => $server_editing->id,
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/minecraft/servers'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'SERVER_INFORMATION' => $language->get('admin', 'server_information'),
                'SERVER_NAME' => $language->get('admin', 'server_name'),
                'SERVER_NAME_VALUE' => Output::getClean($server_editing->name),
                'SERVER_ADDRESS' => $language->get('admin', 'server_address'),
                'SERVER_ADDRESS_VALUE' => Output::getClean($server_editing->ip),
                'INFO' => $language->get('general', 'info'),
                'SERVER_ADDRESS_INFO' => $language->get('admin', 'server_address_help'),
                'SERVER_PORT' => $language->get('admin', 'server_port'),
                'SERVER_PORT_VALUE' => Output::getClean($server_editing->port),
                'SERVER_PORT_INFO' => $language->get('admin', 'leave_port_empty_for_srv'),
                'PARENT_SERVER' => $language->get('admin', 'parent_server'),
                'PARENT_SERVER_INFO' => $language->get('admin', 'parent_server_help'),
                'NO_PARENT_SERVER' => $language->get('admin', 'no_parent_server'),
                'AVAILABLE_PARENT_SERVERS' => $available_parent_servers,
                'PARENT_SERVER_VALUE' => $server_editing->parent_server,
                'BUNGEE_INSTANCE' => $language->get('admin', 'bungee_instance'),
                'BUNGEE_INSTANCE_INFO' => $language->get('admin', 'bungee_instance_help'),
                'BUNGEE_INSTANCE_VALUE' => ($server_editing->bungee == 1),
                'BEDROCK' => $language->get('admin', 'bedrock'),
                'BEDROCK_VALUE' => ($server_editing->bedrock == 1),
                'BEDROCK_INFO' => $language->get('admin', 'bedrock_help'),
                'PRE_17' => $language->get('admin', 'pre_1_7'),
                'PRE_17_VALUE' => ($server_editing->pre == 1),
                'QUERY_INFORMATION' => $language->get('admin', 'query_information'),
                'ENABLE_STATUS_QUERY' => $language->get('admin', 'enable_status_query'),
                'ENABLE_STATUS_QUERY_INFO' => $language->get('admin', 'status_query_help'),
                'ENABLE_STATUS_QUERY_VALUE' => ($server_editing->display == 1),
                'SHOW_IP_ON_STATUS_PAGE' => $language->get('admin', 'show_ip_on_status_page'),
                'SHOW_IP_ON_STATUS_PAGE_INFO' => $language->get('admin', 'show_ip_on_status_page_info'),
                'SHOW_IP_ON_STATUS_PAGE_VALUE' => ($server_editing->show_ip == 1),
                'ENABLE_PLAYER_LIST' => $language->get('admin', 'enable_player_list'),
                'ENABLE_PLAYER_LIST_INFO' => $language->get('admin', 'player_list_help'),
                'ENABLE_PLAYER_LIST_VALUE' => ($server_editing->player_list == 1),
                'SERVER_QUERY_PORT' => $language->get('admin', 'server_query_port'),
                'SERVER_QUERY_PORT_INFO' => $language->get('admin', 'server_query_port_help'),
                'SERVER_QUERY_PORT_VALUE' => Output::getClean($server_editing->query_port)
            ]);

            $template_file = 'integrations/minecraft/minecraft_servers_edit.tpl';

            break;

        case 'delete':
            if (Token::check($_POST['token'])) {
                if (isset($_GET['id'])) {
                    DB::getInstance()->delete('mc_servers', ['id', $_GET['id']]);
                    DB::getInstance()->delete('query_results', ['server_id', $_GET['id']]);

                    Session::flash('admin_mc_servers_success', $language->get('admin', 'server_deleted'));
                }

            } else {
                Session::flash('admin_mc_servers_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/minecraft/servers'));
        case 'order':
            // Get servers
            if (isset($_POST['servers']) && Token::check($_POST['token'])) {
                $servers = json_decode($_POST['servers'])->servers;

                $i = 1;
                foreach ($servers as $item) {
                    DB::getInstance()->update('mc_servers', $item, [
                        'order' => $i
                    ]);
                    $i++;
                }
            }
            die('Complete');

        default:
            Redirect::to(URL::build('/panel/minecraft/servers'));
    }

} else {
    // Handle input
    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            if (isset($_POST['default_server']) && is_numeric($_POST['default_server'])) {
                $new_default = $_POST['default_server'];
            } else {
                $new_default = 0;
            }

            if (isset($_POST['group_sync_server']) && is_numeric($_POST['group_sync_server'])) {
                $new_group_sync_server = $_POST['group_sync_server'];
            } else {
                $new_group_sync_server = 0;
            }

            if (isset($_POST['query_type'])) {
                $query_type = $_POST['query_type'];
            }

            if (isset($_POST['status_page']) && $_POST['status_page'] == 1) {
                $status = 1;
            } else {
                $status = 0;
            }

            if (isset($_POST['player_list_limit'])) {
                $player_list_limit = $_POST['player_list_limit'];
            }

            // Update database and cache
            try {
                // Default server
                if ($new_default > 0) {
                    $current_default = DB::getInstance()->get('mc_servers', ['is_default', true])->results();
                    if (count($current_default) && $current_default[0]->id != $new_default) {
                        DB::getInstance()->update('mc_servers', $current_default[0]->id, [
                            'is_default' => false,
                        ]);
                    }

                    if (!count($current_default) || $current_default[0]->id != $new_default) {
                        DB::getInstance()->update('mc_servers', $new_default, [
                            'is_default' => true,
                        ]);
                    }
                }

                // Group sync server
                Util::setSetting('group_sync_mc_server', $new_group_sync_server);

                // Query type
                Util::setSetting('query_type', $query_type);

                // Player list limit
                if ($player_list_limit != null) { // In case the field is hidden, we don't want to change this value
                    Util::setSetting('player_list_limit', $player_list_limit);
                }

                // Status page
                Util::setSetting('status_page', $status);
                // Query interval

                if (isset($_POST['interval']) && is_numeric($_POST['interval']) && $_POST['interval'] <= 60 && $_POST['interval'] >= 5) {
                    Util::setSetting('minecraft_query_interval', $_POST['interval']);
                }

                $success = $language->get('admin', 'minecraft_settings_updated_successfully');

            } catch (Exception $e) {
                // Error
                $errors[] = $e->getMessage();
            }

        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    // List servers
    $servers = DB::getInstance()->orderAll('mc_servers', '`order`', 'ASC')->results();
    $default = 0;
    $template_array = [];

    if (count($servers)) {

        foreach ($servers as $server) {
            if ($server->is_default == 1) {
                $default = $server->id;
            }

            $template_array[] = [
                'name' => Output::getClean($server->name),
                'id' => Output::getClean($server->id),
                'server_id' => $language->get('admin', 'server_id_x', ['serverId' => Output::getClean($server->id)]),
                'edit_link' => URL::build('/panel/minecraft/servers/', 'action=edit&id=' . urlencode($server->id)),
                'delete_link' => URL::build('/panel/minecraft/servers/', 'action=delete&id=' . urlencode($server->id)),
                'is_default' => $server->is_default
            ];
        }

    } else {
        $smarty->assign('NO_SERVERS', $language->get('admin', 'no_servers_defined'));
    }

    // Settings
    $query_type = Util::getSetting('query_type', 'internal');
    $status_page = Util::getSetting('status_page');
    $group_sync_server = Util::getSetting('group_sync_mc_server');
    $player_list_limit = Util::getSetting('player_list_limit', '20');

    $smarty->assign([
        'NEW_SERVER' => $language->get('admin', 'add_server'),
        'NEW_SERVER_LINK' => URL::build('/panel/minecraft/servers/', 'action=new'),
        'CONFIRM_DELETE_SERVER' => $language->get('admin', 'confirm_delete_server'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'EDIT' => $language->get('general', 'edit'),
        'DELETE' => $language->get('general', 'delete'),
        'QUERY_SETTINGS' => $language->get('admin', 'query_settings'),
        'DEFAULT_SERVER' => $language->get('admin', 'default_server'),
        'DEFAULT_SERVER_VALUE' => $default,
        'NO_DEFAULT_SERVER' => $language->get('admin', 'no_default_server'),
        'GROUP_SYNC_SERVER' => $language->get('admin', 'group_sync_server'),
        'GROUP_SYNC_SERVER_VALUE' => $group_sync_server,
        'NO_GROUP_SYNC_SERVER' => $language->get('admin', 'no_group_sync_server'),
        'QUERY_INTERVAL' => $language->get('admin', 'query_interval'),
        'QUERY_TYPE' => $language->get('admin', 'query_type'),
        'INTERNAL' => $language->get('admin', 'internal'),
        'EXTERNAL' => $language->get('admin', 'external'),
        'PLUGIN' => $language->get('admin', 'plugin'),
        'QUERY_INTERVAL_VALUE' => Util::getSetting('minecraft_query_interval'),
        'EXTERNAL_QUERY' => $language->get('admin', 'external_query'),
        'INFO' => $language->get('general', 'info'),
        'QUERY_TYPE_INFO' => $language->get('admin', 'query_type_help'),
        'QUERY_TYPE_VALUE' => $query_type,
        'PLAYER_LIST_LIMIT' => $language->get('admin', 'player_list_limit'),
        'PLAYER_LIST_LIMIT_VALUE' => $player_list_limit,
        'STATUS_PAGE' => $language->get('admin', 'status_page'),
        'STATUS_PAGE_VALUE' => ($status_page == '1'),
        'REORDER_DRAG_URL' => URL::build('/panel/minecraft/servers', 'action=order'),
        'SERVERS' => $template_array
    ]);

    $template_file = 'integrations/minecraft/minecraft_servers.tpl';

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_mc_servers_success')) {
    $success = Session::flash('admin_mc_servers_success');
}

if (Session::exists('admin_mc_servers_error')) {
    $errors = [Session::flash('admin_mc_servers_error')];
}

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
    'MINECRAFT_SERVERS' => $language->get('admin', 'minecraft_servers')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
