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
$cache->setCache('online_members');

if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['staff']) && $_POST['staff'] == 1) {
            $cache->store('include_staff_in_users', 1);
        } else {
            $cache->store('include_staff_in_users', 0);
        }
        if (isset($_POST['nickname']) && $_POST['nickname'] == 1) {
            $cache->store('show_nickname_instead', 1);
        } else {
            $cache->store('show_nickname_instead', 0);
        }

        $success = $language->get('admin', 'widget_updated');
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$include_staff = $cache->retrieve('include_staff_in_users');
$use_nickname_show = $cache->retrieve('show_nickname_instead');

$smarty->assign([
    'INCLUDE_STAFF' => $language->get('admin', 'include_staff_in_user_widget'),
    'INCLUDE_STAFF_VALUE' => $include_staff,
    'SHOW_NICKNAME_INSTEAD' => $language->get('admin', 'show_nickname_instead_of_username'),
    'SHOW_NICKNAME_INSTEAD_VALUE' => $use_nickname_show,
    'SETTINGS_TEMPLATE' => 'core/widgets/online_users.tpl'
]);
