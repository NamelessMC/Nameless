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
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'placeholders');
$page_title = $language->get('admin', 'placeholders');
require_once(ROOT_PATH . '/core/templates/backend_init.php');
$queries = new Queries();

$all_placeholders = Placeholders::getInstance()->getAllPlaceholders();

if (Input::exists()) {

    if (Token::check()) {

        foreach ($all_placeholders as $placeholder) {

            $friendly_name_input = Input::get('friendly_name-' . $placeholder->name);
            $friendly_name = $friendly_name_input == '' ? null : $friendly_name_input;
            $show_on_profile = Input::get('show_on_profile-' . $placeholder->name) == 'on' ? 1 : 0;
            $show_on_forum = Input::get('show_on_forum-' . $placeholder->name) == 'on' ? 1 : 0;

            DB::getInstance()->query("UPDATE nl2_placeholders_settings SET friendly_name = ?, show_on_profile = ?, show_on_forum = ? WHERE name = ?", [
                $friendly_name,
                $show_on_profile,
                $show_on_forum,
                $placeholder->name
            ]);
        }

        Session::flash('placeholders_success', $language->get('admin', 'updated_placeholder_settings'));

        Redirect::to(URL::build('/panel/core/placeholders'));

    } else {
        $errors[] = $language->get('general', 'invalid_token');
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
    'SUBMIT' => $language->get('general', 'submit'),
    'PLACEHOLDERS_INFO' => $language->get('admin', 'placeholders_info'),
    'ALL_PLACEHOLDERS' => $all_placeholders,
    'NO_PLACEHOLDERS' => $language->get('admin', 'placeholders_none'),
    'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
    'NAME' => $language->get('admin', 'placeholders_name'),
    'FRIENDLY_NAME' => $language->get('admin', 'placeholders_friendly_name'),
    'SHOW_ON_PROFILE' => $language->get('admin', 'placeholders_show_on_profile'),
    'SHOW_ON_FORUM' => $language->get('admin', 'placeholders_show_on_forum'),
    'FRIENDLY_NAME_INFO' => $language->get('admin', 'placeholders_friendly_name_info'),
    'SHOW_ON_PROFILE_INFO' => $language->get('admin', 'placeholders_show_on_profile_info'),
    'SHOW_ON_FORUM_INFO' => $language->get('admin', 'placeholders_show_on_forum_info'),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/placeholders.tpl', $smarty);
