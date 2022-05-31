<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel Minecraft Authme page
 */

if (!$user->handlePanelPageLoad('admincp.minecraft.authme')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
const MINECRAFT_PAGE = 'authme';
$page_title = $language->get('admin', 'authme_integration');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        if (isset($_POST['enable_authme'])) {
            // Either enable or disable Authme integration
            DB::getInstance()->update('settings', ['name', 'authme'], [
                'value' => Input::get('enable_authme')
            ]);

        } else {
            // AuthMe config settings
            $validation = Validate::check($_POST, [
                'hashing_algorithm' => [
                    Validate::REQUIRED => true
                ],
                'db_address' => [
                    Validate::REQUIRED => true
                ],
                'db_name' => [
                    Validate::REQUIRED => true
                ],
                'db_username' => [
                    Validate::REQUIRED => true
                ],
                'db_table' => [
                    Validate::REQUIRED => true
                ]
            ])->message($language->get('admin', 'enter_authme_db_details'));

            if ($validation->passed()) {
                $authme_db = DB::getInstance()->get('settings', ['name', 'authme_db'])->results();
                $authme_db_id = $authme_db[0]->id;
                $authme_db = json_decode($authme_db[0]->value);

                if (isset($_POST['db_password'])) {
                    $password = $_POST['db_password'];
                } else {
                    if (isset($authme_db->password) && !empty($authme_db->password)) {
                        $password = $authme_db->password;
                    } else {
                        $password = '';
                    }
                }

                $result = [
                    'address' => Output::getClean(Input::get('db_address')),
                    'port' => (isset($_POST['db_port']) && !empty($_POST['db_port']) && is_numeric($_POST['db_port'])) ? $_POST['db_port'] : 3306,
                    'db' => Output::getClean(Input::get('db_name')),
                    'user' => Output::getClean(Input::get('db_username')),
                    'pass' => $password,
                    'table' => Output::getClean(Input::get('db_table')),
                    'hash' => Output::getClean(Input::get('hashing_algorithm')),
                    'sync' => Input::get('authme_sync')
                ];

                $cache->setCache('authme_cache');
                $cache->store('authme', $result);

                DB::getInstance()->update('settings', $authme_db_id, [
                    'value' => json_encode($result)
                ]);

            } else {
                $errors = $validation->errors();
            }
        }

    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
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

// Is Authme enabled?
$authme_enabled = DB::getInstance()->get('settings', ['name', 'authme'])->results();
$authme_enabled = $authme_enabled[0]->value;

if ($authme_enabled == '1') {
    // Retrieve Authme database details
    $authme_db = DB::getInstance()->get('settings', ['name', 'authme_db'])->results();
    $authme_db = json_decode($authme_db[0]->value);

    $smarty->assign([
        'AUTHME_DB_DETAILS' => ($authme_db ?: []),
        'AUTHME_HASH_ALGORITHM' => $language->get('admin', 'authme_hash_algorithm'),
        'AUTHME_DB_ADDRESS' => $language->get('admin', 'authme_db_address'),
        'AUTHME_DB_PORT' => $language->get('admin', 'authme_db_port'),
        'AUTHME_DB_NAME' => $language->get('admin', 'authme_db_name'),
        'AUTHME_DB_USER' => $language->get('admin', 'authme_db_user'),
        'AUTHME_DB_PASSWORD' => $language->get('admin', 'authme_db_password'),
        'AUTHME_DB_PASSWORD_HIDDEN' => $language->get('admin', 'authme_db_password_hidden'),
        'AUTHME_DB_TABLE' => $language->get('admin', 'authme_db_table'),
        'AUTHME_PASSWORD_SYNC' => $language->get('admin', 'authme_password_sync'),
        'AUTHME_PASSWORD_SYNC_HELP' => $language->get('admin', 'authme_password_sync_help')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'AUTHME_INFO' => $language->get('admin', 'authme_integration_info'),
    'INFO' => $language->get('general', 'info'),
    'ENABLE_AUTHME' => $language->get('admin', 'enable_authme'),
    'ENABLE_AUTHME_VALUE' => ($authme_enabled == '1'),
    'AUTHME' => $language->get('admin', 'authme_integration'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft_authme.tpl', $smarty);
