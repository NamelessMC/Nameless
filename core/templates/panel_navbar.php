<?php
/**
 * Staff panel navbar generation.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Language     $language
 * @var Navigation   $staffcp_nav
 * @var TemplateBase $template
 * @var User         $user
 */

// Assign to template variables
$template->getEngine()->addVariables([
    'SITE_NAME' => Output::getClean(SITE_NAME),
    'SITE_HOME' => URL::build('/'),
    'CONFIG_PATH' => defined('CONFIG_PATH') ? CONFIG_PATH . '/' : '/',
    'OG_URL' => Output::getClean(rtrim(URL::getSelfURL(), '/') . $_SERVER['REQUEST_URI']),
    'PANEL_INDEX' => URL::build('/panel'),
    'NAV_LINKS' => $staffcp_nav->returnNav('top'),
    'VIEW_SITE' => $language->get('admin', 'view_site'),
    'PAGE_LOAD_TIME' => PAGE_LOAD_TIME,
    'SUPPORT' => $language->get('admin', 'support'),
    'SOURCE' => $language->get('admin', 'source'),
    'NOTICES' => Core_Module::getNotices(),
    'NO_NOTICES' => $language->get('admin', 'no_notices'),
    'MODE_TOGGLE' => $language->get('admin', 'mode_toggle'),
    'LOGGED_IN_USER' => [
        'username' => $user->getDisplayname(true),
        'nickname' => $user->getDisplayname(),
        'profile' => $user->getProfileURL(),
        'panel_profile' => URL::build('/panel/user/' . urlencode($user->data()->id) . '-' . urlencode($user->data()->username)),
        'username_style' => $user->getGroupStyle(),
        'user_title' => Output::getClean($user->data()->user_title),
        'avatar' => $user->getAvatar(),
        'integrations' => $user_integrations ?? [],
    ],
]);
