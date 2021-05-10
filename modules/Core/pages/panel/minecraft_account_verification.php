<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft account verification page
 */

if(!$user->handlePanelPageLoad('admincp.minecraft.verification')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'minecraft');
define('MINECRAFT_PAGE', 'verification');
$page_title = $language->get('admin', 'account_verification');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if(Input::exists()){
    $errors = array();
    if(Token::check()){
        if(!isset($_POST['premium'])){
            $use_mcassoc = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
            $use_mcassoc = $use_mcassoc[0]->id;

            if(isset($_POST['use_mcassoc']) && $_POST['use_mcassoc'] == 'on'){

                $validate = new Validate();
                $validation = $validate->check($_POST, [
                    'mcassoc_key' => [
                        Validate::REQUIRED => true,
                        Validate::MAX => 128
                    ],
                    'mcassoc_instance' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 32,
                        Validate::MAX => 32
                    ]
                ])->message($language->get('admin', 'mcassoc_error'));

                if($validation->passed()){
                    // Update settings
                    $mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
                    $mcassoc_key = $mcassoc_key[0]->id;

                    $mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
                    $mcassoc_instance = $mcassoc_instance[0]->id;

                    $queries->update('settings', $use_mcassoc, array('value' => 1));
                    $queries->update('settings', $mcassoc_key, array('value' => Input::get('mcassoc_key')));
                    $queries->update('settings', $mcassoc_instance, array('value' => Input::get('mcassoc_instance')));

                    $success = $language->get('admin', 'updated_mcassoc_successfully');

                } else {
                    $errors = $validation->errors();
                }

            } else {
                $queries->update('settings', $use_mcassoc, array('value' => 0));
                $success = $language->get('admin', 'updated_mcassoc_successfully');
            }

        } else {
            $uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
            $uuid_linking = $uuid_linking[0]->id;

            if(isset($_POST['enable_premium_accounts']) && $_POST['enable_premium_accounts'] == 1)
                $use_premium = 1;
            else
                $use_premium = 0;

            $queries->update('settings', $uuid_linking, array('value' => $use_premium));
        }

    } else
        $errors[] = $language->get('general', 'invalid_token');
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

// Get UUID linking settings
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

if($uuid_linking == '1'){
    // Get mcassoc settings
    $use_mcassoc = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
    $use_mcassoc = $use_mcassoc[0]->value;

    $mcassoc_key = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
    $mcassoc_key = Output::getClean($mcassoc_key[0]->value);

    $mcassoc_instance = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
    $mcassoc_instance = Output::getClean($mcassoc_instance[0]->value);

    $smarty->assign(array(
        'INFO' => $language->get('general', 'info'),
        'MCASSOC_INFO' => $language->get('admin', 'mcassoc_help'),
        'USE_MCASSOC' => $language->get('admin', 'verify_with_mcassoc'),
        'USE_MCASSOC_VALUE' => ($use_mcassoc == '1'),
        'MCASSOC_KEY' => $language->get('admin', 'mcassoc_key'),
        'MCASSOC_KEY_VALUE' => $mcassoc_key,
        'MCASSOC_INSTANCE' => $language->get('admin', 'mcassoc_instance'),
        'MCASSOC_INSTANCE_VALUE' => $mcassoc_instance,
        'MCASSOC_INSTANCE_HELP' => $language->get('admin', 'mcassoc_instance_help')
    ));
}

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ACCOUNT_VERIFICATION' => $language->get('admin', 'account_verification'),
    'FORCE_PREMIUM_ACCOUNTS' => $language->get('admin', 'force_premium_accounts'),
    'FORCE_PREMIUM_ACCOUNTS_VALUE' => ($uuid_linking == '1')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft_account_verification.tpl', $smarty);
