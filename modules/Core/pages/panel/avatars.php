<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel avatars page
 */

if (!$user->handlePanelPageLoad('admincp.core.avatars')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'avatars';
$page_title = $language->get('admin', 'avatars');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Check if settings
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'settings') {
        $source = AvatarSource::getInstance()->getSourceBySafeName($_GET['source']);
        if (!$source) {
            Redirect::to(URL::build('/panel/core/avatars'));
        }

        require_once($source->getSettings());

        $smarty->assign([
            'EDITING_AVATAR_SOURCE' => $language->get('admin', 'editing_avatar_source_x', [
                'avatarSource' => Text::bold(Output::getClean($source->getName()))
            ]),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/avatars'),
        ]);

        $template_file = 'core/avatar_source_settings.tpl';
    } else if ($_GET['action'] === 'order') {
        if (Token::check()) {
            $new_settings = [];
            $order = 1;
            foreach (json_decode($_GET['sources']) as $source) {
                $source = AvatarSource::getInstance()->getSourceBySafeName($source);
                if ($source) {
                    $new_settings[$source->getSafeName()] = [
                        'enabled' => $source->isEnabled(),
                        'order' => $order,
                    ];
                    $order++;
                }
            }
            Util::setSetting('avatar_source_settings', json_encode($new_settings));

            die('Ok');
        }

        die('Invalid token');
    }
} else {
    $template_file = 'core/avatars.tpl';

    $template->assets()->include([
        AssetTree::JQUERY_UI,
    ]);

    // Input
    if (Input::exists()) {
        if (Token::check()) {
            $new_settings = [];
            foreach (Input::get('toggle') as $source => $enabled) {
                $source = AvatarSource::getInstance()->getSourceBySafeName($source);
                if ($source) {
                    $new_settings[$source->getSafeName()] = [
                        'enabled' => $enabled,
                        'order' => $source->getOrder(),
                    ];
                }
            }
            Util::setSetting('avatar_source_settings', json_encode($new_settings));

            Session::flash('avatar_success', $language->get('admin', 'avatar_settings_updated_successfully'));
            Redirect::to(URL::build('/panel/core/avatars'));
        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }
    }

    $smarty->assign([
        'AVATAR_SOURCES' => AvatarSource::getInstance()->getAllSources(),
        'INFO' => $language->get('general', 'info'),
        'AVATARS_INFO' => $language->get('admin', 'avatars_info'),
    ]);
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('avatar_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('avatar_success'),
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
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'AVATARS' => $language->get('admin', 'avatars'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
