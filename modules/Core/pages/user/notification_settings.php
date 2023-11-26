<?php
/**
 * User notification settings page
 *
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */

/**
 * @var Cache $cache
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var Smarty $smarty
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_notification_settings';
$page_title = $language->get('user', 'notification_settings');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

$preferences = DB::getInstance()->query(
    'SELECT `type`, `alert`, `email` FROM nl2_users_notification_preferences WHERE `user_id` = ?',
    [$user->data()->id],
)->results();

$mappedPreferences = [];

foreach (Notification::getTypes() as $type) {
    $userTypePreference = array_search($type['key'], array_column($preferences, 'type'));

    $alert = $email = false;
    if ($userTypePreference !== false) {
        $alert = (bool) $preferences[$userTypePreference]['alert'];
        $email = (bool) $preferences[$userTypePreference]['email'];
    }

    $mappedPreferences[] = [
        'type' => $type['key'],
        'value' => $type['value'],
        'alert' => $alert,
        'email' => $email,
    ];
}

$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'NOTIFICATION_SETTINGS_TITLE' => $language->get('user', 'notification_settings'),
    'NOTIFICATION_SETTINGS' => $mappedPreferences,
    'OVERVIEW' => $language->get('user', 'overview'),
    'SUBMIT' => $language->get('general', 'submit'),
    'ALERT' => $language->get('admin', 'mass_message_type_alert'),
    'EMAIL' => $language->get('admin', 'mass_message_type_email'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require ROOT_PATH . '/core/templates/cc_navbar.php';

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('user/notification_settings.tpl', $smarty);
