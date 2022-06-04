<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  User validation
 */

$page = 'validate';
const PAGE = 'validate';
$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['c'])) {
    $user = new User($_GET['c'], 'reset_code');
    if ($user->exists()) {
        $user->update([
            'reset_code' => null,
            'active' => true,
        ]);

        $default_language = new Language('core', DEFAULT_LANGUAGE);
        EventHandler::executeEvent('validateUser', [
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayname(),
            'content' => $default_language->get('user', 'user_x_has_validated', ['user' => $user->getDisplayname()]),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'language' => $default_language
        ]);

        GroupSyncManager::getInstance()->broadcastChange(
            $user,
            NamelessMCGroupSyncInjector::class,
            [$user->getMainGroup()->id]
        );

        Session::flash('home', $language->get('user', 'validation_complete'));
    } else {
        Session::flash('home_error', $language->get('user', 'validation_error'));
    }
}
Redirect::to(URL::build('/'));
