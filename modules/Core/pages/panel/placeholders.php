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

$template_file = 'core/placeholders.tpl';

if (isset($_GET['leaderboard'])) {
    
    $placeholder_name = $_GET['leaderboard'];
    $placeholder = Placeholders::getInstance()->getPlaceholderByName($placeholder_name);

    if ($placeholder != null) {

        $template_file = 'core/placeholders_leaderboard.tpl';

        if (Input::exists()) {

            if (Token::check()) {

                $enabled = Input::get('leaderboard_enabled') == 'on' ? 1 : 0;
                $title_input = Input::get('leaderboard_title');
                $title = $title_input == '' ? null : $title_input;
                $sort = Input::get('leaderboard_sort');

                DB::getInstance()->query("UPDATE nl2_placeholders_settings SET leaderboard = ?, leaderboard_title = ?, leaderboard_sort = ? WHERE name = ?", [
                    $enabled,
                    $title,
                    $sort,
                    $placeholder->name
                ]);

                Session::flash('placeholders_success', 'Updated leaderboard settings successfully');

                Redirect::to(URL::build('/panel/core/placeholders'));

            } else {
                $errors[] = $language->get('general', 'invalid_token');
            }
        }

        $smarty->assign(array(
            'PAGE' => PANEL_PAGE,
            'PARENT_PAGE' => PARENT_PAGE,
            'DASHBOARD' => $language->get('admin', 'dashboard'),
            'CONFIGURATION' => $language->get('admin', 'configuration'),
            'TOKEN' => Token::get(),
            'INFO' => $language->get('general', 'info'),
            'ENABLED_INFO' => 'Leaderboards work best with numeric placeholders (such as coins, kills, blocks mined, etc). If you enable a leaderboard on a text-based placeholder - you cannot be sure it will order it as you want.',
            'SUBMIT' => $language->get('general', 'submit'),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/placeholders'),
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDER_LEADERBOARD_SETTINGS' => 'Placeholder Leaderboard Settings',
            'PLACEHOLDER_LEADERBOARD_INFO' => 'Placeholder Leaderboards let you create leaderboards to display ranked players on your server according to any placeholder.',
            'PLACEHOLDER' => $placeholder
        ));

    } else {
        Redirect::to(URL::build('/panel/core/placeholders'));
        die();
    }

} else {

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
        'LEADERBOARD_ENABLED' => 'Leaderboard Enabled',
        'LEADERBOARD_SETTINGS' => 'Leaderboard Settings',
    ));
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

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
