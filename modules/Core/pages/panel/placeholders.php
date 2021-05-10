<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Panel placeholders page
 */

if(!$user->handlePanelPageLoad('admincp.core.placeholders')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'placeholders');
define('PANEL_PAGE', 'placeholders');
$page_title = $language->get('admin', 'placeholders');
require_once(ROOT_PATH . '/core/templates/backend_init.php');
$queries = new Queries();
$all_placeholders = $queries->getWhere('placeholders_settings', ['name', '<>', '']);


if (Input::exists()) {

    // TODO: Token and error/success messages
    // TODO: dropdown doesnt open auto to placeholders active
    if (Token::check()) {

        foreach ($all_placeholders as $placeholder) {

            $friendly_name_input = Input::get('friendly_name-' . $placeholder->name);
            $friendly_name = $friendly_name_input == '' ? null : $friendly_name_input;
            $public = Input::get('public-' . $placeholder->name) == 'on' ? 1 : 0;

            DB::getInstance()->query("UPDATE nl2_placeholders_settings SET friendly_name = ?, public = ? WHERE name = ?", [
                $friendly_name,
                $public,
                $placeholder->name
            ]);
        }

        Redirect::to(URL::build('/panel/core/placeholders'));
    }

}


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('placeholders_success')) {
    $smarty->assign(array(
        'SUCCESS' => Session::flash('placeholders_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));
}

if (isset($errors) && count($errors)) {
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));
}

$smarty->assign(array(
    'PAGE' => PANEL_PAGE,
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'TOKEN' => Token::get(),
    'INFO' => $language->get('general', 'info'),
    'FRIENDLY_NAME_INFO' => 'Use this to set a \'nickname\' to this placeholder. The friendly name will be used instead of the raw name.',
    'PUBLIC_PRIVATE_INFO' => 'If this is disabled, users will not be able to view this placeholder\'s settings or display it publically.',
    'SUBMIT' => $language->get('general', 'submit'),
    'PLACEHOLDERS_INFO' => 'Placeholders allow the NamelessMC Spigot plugin to send statistics about each player to your website so they can display them on their profile and forum posts.',
    'ALL_PLACEHOLDERS' => $all_placeholders,
    'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
    'NAME' => 'Name',
    'FRIENDLY_NAME' => 'Friendly Name',
    'PUBLIC' => 'Public'
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/placeholders.tpl', $smarty);
