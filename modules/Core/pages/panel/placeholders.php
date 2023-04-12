<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel placeholders page
 */

if (!$user->handlePanelPageLoad('admincp.core.placeholders')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
const MINECRAFT_PAGE = 'placeholders';
$page_title = $language->get('admin', 'placeholders');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$all_placeholders = Placeholders::getInstance()->getAllPlaceholders();

$template_file = 'integrations/minecraft/placeholders.tpl';

if (isset($_GET['leaderboard'])) {

    $server_id = $_GET['server_id'];
    $placeholder_safe_name = $_GET['leaderboard'];
    $placeholder = Placeholders::getInstance()->getPlaceholder($server_id, $placeholder_safe_name);

    if ($placeholder != null) {

        $template_file = 'integrations/minecraft/placeholders_leaderboard.tpl';

        if (Input::exists()) {

            if (Token::check()) {

                $enabled = Input::get('leaderboard_enabled') == 'on' ? 1 : 0;
                $title_input = Input::get('leaderboard_title');
                $title = $title_input == '' ? null : $title_input;
                $sort = Input::get('leaderboard_sort');

                DB::getInstance()->query('UPDATE nl2_placeholders_settings SET leaderboard = ?, leaderboard_title = ?, leaderboard_sort = ? WHERE `name` = ? AND server_id = ?', [
                    $enabled,
                    $title,
                    $sort,
                    $placeholder->name,
                    $placeholder->server_id
                ]);

                Session::flash('placeholders_success', $language->get('admin', 'placeholder_leaderboard_updated'));
                Redirect::to(URL::build('/panel/minecraft/placeholders'));
            } else {
                $errors[] = $language->get('general', 'invalid_token');
            }
        }

        $smarty->assign([
            'PAGE' => PANEL_PAGE,
            'PARENT_PAGE' => PARENT_PAGE,
            'DASHBOARD' => $language->get('admin', 'dashboard'),
            'CONFIGURATION' => $language->get('admin', 'configuration'),
            'TOKEN' => Token::get(),
            'INFO' => $language->get('general', 'info'),
            'ENABLED_INFO' => $language->get('admin', 'placeholder_leaderboard_enable_info'),
            'SUBMIT' => $language->get('general', 'submit'),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/minecraft/placeholders'),
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDER_LEADERBOARD_SETTINGS' => $language->get('admin', 'placeholder_leaderboard_settings'),
            'PLACEHOLDER_LEADERBOARD_INFO' => $language->get('admin', 'placeholder_leaderboard_info'),
            'PLACEHOLDER' => $placeholder,
            'LEADERBOARD_ENABLED' => $language->get('admin', 'placeholder_leaderboard_enabled'),
            'LEADERBOARD_TITLE' => $language->get('admin', 'placeholder_leaderboard_title'),
            'LEADERBOARD_SORT' => $language->get('admin', 'placeholder_leaderboard_sort'),
            'INTEGRATIONS' => $language->get('admin', 'integrations'),
            'MINECRAFT' => $language->get('admin', 'minecraft'),
            'MINECRAFT_LINK' => URL::build('/panel/minecraft')
        ]);
    } else {
        Redirect::to(URL::build('/panel/minecraft/placeholders'));
    }

} else {

    if (Input::exists()) {

        if (Token::check()) {

            if (Input::get('action') === 'settings') {
                // Update placeholders value
                Util::setSetting('placeholders', (isset($_POST['placeholders_enabled']) && $_POST['placeholders_enabled'] == 'on') ? '1' : '0');

                foreach ($all_placeholders as $placeholder) {
                    $friendly_name_input = Input::get('friendly_name-' . $placeholder->name . '-server-' . $placeholder->server_id);
                    $friendly_name = $friendly_name_input == '' ? null : $friendly_name_input;
                    $show_on_profile = Input::get('show_on_profile-' . $placeholder->name . '-server-' . $placeholder->server_id) == 'on' ? 1 : 0;
                    $show_on_forum = Input::get('show_on_forum-' . $placeholder->name . '-server-' . $placeholder->server_id) == 'on' ? 1 : 0;

                    DB::getInstance()->query('UPDATE nl2_placeholders_settings SET friendly_name = ?, show_on_profile = ?, show_on_forum = ? WHERE name = ? AND server_id = ?', [
                        $friendly_name,
                        $show_on_profile,
                        $show_on_forum,
                        $placeholder->name,
                        $placeholder->server_id
                    ]);
                }

                Session::flash('placeholders_success', $language->get('admin', 'updated_placeholder_settings'));
                Redirect::to(URL::build('/panel/minecraft/placeholders'));
            } else {
                // Deleting placeholder
                $placeholder_safe_name = Input::get('placeholder_safe_name');
                $server_id = Input::get('server_id');

                $placeholder = Placeholders::getInstance()->getPlaceholder($server_id, $placeholder_safe_name);
                if ($placeholder) {
                    DB::getInstance()->query('DELETE FROM nl2_placeholders_settings WHERE name = ? AND server_id = ?', [
                        $placeholder->name,
                        $placeholder->server_id,
                    ]);

                    die('Ok');
                }

                die('No placeholder found');
            }

        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    $smarty->assign([
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
        'SERVER_ID' => $language->get('admin', 'placeholders_server_id'),
        'NAME' => $language->get('admin', 'placeholders_name'),
        'FRIENDLY_NAME' => $language->get('admin', 'placeholders_friendly_name'),
        'SHOW_ON_PROFILE' => $language->get('admin', 'placeholders_show_on_profile'),
        'SHOW_ON_FORUM' => $language->get('admin', 'placeholders_show_on_forum'),
        'FRIENDLY_NAME_INFO' => $language->get('admin', 'placeholders_friendly_name_info'),
        'SHOW_ON_PROFILE_INFO' => $language->get('admin', 'placeholders_show_on_profile_info'),
        'SHOW_ON_FORUM_INFO' => $language->get('admin', 'placeholders_show_on_forum_info'),
        'LEADERBOARD_ENABLED' => $language->get('admin', 'placeholder_leaderboard_enabled'),
        'LEADERBOARD_SETTINGS' => $language->get('admin', 'leaderboard_settings'),
        'INTEGRATIONS' => $language->get('admin', 'integrations'),
        'MINECRAFT' => $language->get('admin', 'minecraft'),
        'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
        'ENABLE_PLACEHOLDERS' => $language->get('admin', 'enable_placeholders'),
        'ENABLE_PLACEHOLDERS_VALUE' => Util::getSetting('placeholders') === '1',
        'DELETE' => $language->get('admin', 'delete'),
        'CONFIRM_DELETE_PLACEHOLDER' => $language->get('admin', 'confirm_delete_placeholder'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
    ]);
}


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('placeholders_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('placeholders_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
