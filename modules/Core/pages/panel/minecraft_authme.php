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
            Util::setSetting('authme', Input::get('enable_authme'));
        } else {
            // AuthMe config settings
            $validation = Validate::check($_POST, [
                'hashing_algorithm' => [
                    Validate::REQUIRED => true,
                    // TODO: add Validate::IN after enjin import is merged
                ],
                'db_address' => [
                    Validate::REQUIRED => true,
                ],
                'db_port' => [
                    Validate::REQUIRED => true,
                    Validate::NUMERIC => true,
                ],
                'db_name' => [
                    Validate::REQUIRED => true,
                ],
                'db_username' => [
                    Validate::REQUIRED => true,
                ],
                'db_table' => [
                    Validate::REQUIRED => true,
                ],
            ])->message($language->get('admin', 'enter_authme_db_details'));

            if ($validation->passed()) {
                if (isset($_POST['db_password'])) {
                    $password = $_POST['db_password'];
                } else {
                    // No password provided, re-use previous password
                    $authme_details = Config::get('authme');
                    if ($authme_details === null) {
                        $password = '';
                    } else {
                        $password = json_decode($authme_details)->password;
                    }
                }

                Config::set('authme', [
                    'address' => Output::getClean(Input::get('db_address')),
                    'port' => Output::getClean(Input::get('db_port')),
                    'db' => Output::getClean(Input::get('db_name')),
                    'user' => Output::getClean(Input::get('db_username')),
                    'pass' => $password,
                    'table' => Output::getClean(Input::get('db_table')),
                    'hash' => Output::getClean(Input::get('hashing_algorithm')),
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

// Is AuthMe enabled?
if (Util::getSetting('authme')) {
    // Retrieve AuthMe database details
    $authme_db = Config::get('authme', []);

    $smarty->assign([
        'AUTHME_DB_DETAILS' => $authme_db,
        'AUTHME_HASH_ALGORITHM' => $language->get('admin', 'authme_hash_algorithm'),
        'AUTHME_DB_ADDRESS' => $language->get('admin', 'authme_db_address'),
        'AUTHME_DB_PORT' => $language->get('admin', 'authme_db_port'),
        'AUTHME_DB_NAME' => $language->get('admin', 'authme_db_name'),
        'AUTHME_DB_USER' => $language->get('admin', 'authme_db_user'),
        'AUTHME_DB_PASSWORD' => $language->get('admin', 'authme_db_password'),
        'AUTHME_DB_PASSWORD_HIDDEN' => $language->get('admin', 'authme_db_password_hidden'),
        'AUTHME_DB_TABLE' => $language->get('admin', 'authme_db_table'),
        'AUTHME_DB_CONNECTION_TEST_URL' => URL::build('/queries/authme_test_connection'),
        'TEST_CONNECTION' => $language->get('admin', 'authme_db_test_connection'),
        'CONNECTION_SUCCESS' => $language->get('admin', 'authme_db_connection_success'),
        'CONNECTION_FAILED' => $language->get('admin', 'authme_db_connection_failed'),
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
    'ENABLE_AUTHME_VALUE' => (Util::getSetting('authme') == '1'),
    'AUTHME' => $language->get('admin', 'authme_integration'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft_authme.tpl', $smarty);
