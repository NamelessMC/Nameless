<?php
declare(strict_types=1);

/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  User validation
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 * @var Language $forum_language
 */

use GuzzleHttp\Exception\GuzzleException;

$page = 'validate';
const PAGE = 'validate';
$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['c'])) {
    try {
        $user = new User($_GET['c'], 'reset_code');
    } catch (GuzzleException $ignored) {
    }

    if ($user->exists()) {
        try {
            $user->update([
                'reset_code' => null,
                'active' => true,
            ]);
        } catch (Exception $ignored) {
        }

        $default_language = new Language('core', DEFAULT_LANGUAGE);
        try {
            EventHandler::executeEvent('validateUser', [
                'user_id' => $user->data()->id,
                'username' => $user->getDisplayName(),
                'content' => $default_language->get('user', 'user_x_has_validated', ['user' => $user->getDisplayName()]),
                'avatar_url' => $user->getAvatar(128, true),
                'url' => URL::getSelfURL() . ltrim($user->getProfileURL(), '/'),
                'language' => $default_language
            ]);
        } catch (GuzzleException $ignored) {
        }

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
