<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel Minecraft Authme page
 */

if(!$user->handlePanelPageLoad('admincp.minecraft.authme')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'minecraft');
define('MINECRAFT_PAGE', 'authme');
$page_title = $language->get('admin', 'authme_integration');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if(Input::exists()){
    $errors = array();

    if(Token::check()){
        if(isset($_POST['enable_authme'])){
            // Either enable or disable Authme integration
            $enable_authme_id = $queries->getWhere('settings', array('name', '=', 'authme'));
            $enable_authme_id = $enable_authme_id[0]->id;

            $queries->update('settings', $enable_authme_id, array(
                'value' => Input::get('enable_authme')
            ));

        } else {
            // AuthMe config settings
            $validate = new Validate();
            $validation = $validate->check($_POST, [
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

            if($validation->passed()){
                $authme_db = $queries->getWhere('settings', array('name', '=', 'authme_db'));
                $authme_db_id = $authme_db[0]->id;
                $authme_db = json_decode($authme_db[0]->value);

                if(isset($_POST['db_password'])){
                    $password = $_POST['db_password'];
                } else {
                    if(isset($authme_db->password) && !empty($authme_db->password))
                        $password = $authme_db->password;
                    else
                        $password = '';
                }

                $result = array(
                    'address' => Output::getClean(Input::get('db_address')),
                    'port' => (isset($_POST['db_port']) && !empty($_POST['db_port']) && is_numeric($_POST['db_port'])) ? $_POST['db_port'] : 3306,
                    'db' => Output::getClean(Input::get('db_name')),
                    'user' => Output::getClean(Input::get('db_username')),
                    'pass' => $password,
                    'table' => Output::getClean(Input::get('db_table')),
                    'hash' => Output::getClean(Input::get('hashing_algorithm')),
                    'sync' => Input::get('authme_sync')
                );

                $cache->setCache('authme_cache');
                $cache->store('authme', $result);

                $queries->update('settings', $authme_db_id, array(
                    'value' => json_encode($result)
                ));

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

// Is Authme enabled?
$authme_enabled = $queries->getWhere('settings', array('name', '=', 'authme'));
$authme_enabled = $authme_enabled[0]->value;

if($authme_enabled == '1'){
    // Retrieve Authme database details
    $authme_db = $queries->getWhere('settings', array('name', '=', 'authme_db'));
    $authme_db = json_decode($authme_db[0]->value);

    $smarty->assign(array(
        'AUTHME_DB_DETAILS' => ($authme_db ? $authme_db : array()),
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
    ));
}

$smarty->assign(array(
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
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft_authme.tpl', $smarty);
