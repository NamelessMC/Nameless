<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Online users widget settings
 */

// Check input
$cache->setCache('online_members_widget');

if (Input::exists()) {
    if (Token::check()) {
        Settings::set('online_users_widget_use_nicknames', isset($_POST['nickname']) && $_POST['nickname'] == 1);
        Settings::set('online_users_widget_include_staff', isset($_POST['staff']) && $_POST['staff'] == 1);

        if ($cache->isCached('users')) {
            $cache->erase('users');
        }

        if ($cache->isCached('total')) {
            $cache->erase('total');
        }

        $success = $language->get('admin', 'widget_updated');
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$include_staff = Settings::get('online_users_widget_include_staff', 0);
$use_nickname_show = Settings::get('online_users_widget_use_nicknames', 0);

$template->getEngine()->addVariables([
    'INCLUDE_STAFF' => $language->get('admin', 'include_staff_in_user_widget'),
    'INCLUDE_STAFF_VALUE' => $include_staff,
    'SHOW_NICKNAME_INSTEAD' => $language->get('admin', 'show_nickname_instead_of_username'),
    'SHOW_NICKNAME_INSTEAD_VALUE' => $use_nickname_show,
    'SETTINGS_TEMPLATE' => 'core/widgets/online_users.tpl'
]);
