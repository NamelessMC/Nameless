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

if (Input::exists()) {
    if (Token::check()) {
        $preferences = [];

        foreach (Notification::getTypes() as $type) {
            foreach (['alert', 'email'] as $option) {
                if ($_POST[$type['key'] . ':' . $option]) {
                    if (!isset($preferences[$type['key']])) {
                        $preferences[$type['key']] = [];
                    }

                    $preferences[$type['key']][$option] = true;
                }
            }
        }

        DB::getInstance()->delete('users_notification_preferences', ['user_id', $user->data()->id]);

        if (count($preferences)) {
            $inserts = implode(', ', array_map(static fn () => '(?, ?, ?, ?)', $preferences));
            $values = [];

            foreach ($preferences as $key => $options) {
                $values[] = $user->data()->id;
                $values[] = $key;
                $values[] = array_key_exists('alert', $options) ? 1 : 0;
                $values[] = array_key_exists('email', $options) ? 1 : 0;
            }

            DB::getInstance()->query(
                <<<SQL
                INSERT INTO
                    nl2_users_notification_preferences
                    (`user_id`, `type`, `alert`, `email`)
                VALUES
                    $inserts
                SQL,
                $values
            );
        }

        Session::flash('notification_settings_success', $language->get('user', 'notification_settings_updated_successfully'));
        Redirect::to(URL::build('/user/notification_settings'));

    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$preferences = DB::getInstance()->query(
    'SELECT `type`, `alert`, `email` FROM nl2_users_notification_preferences WHERE `user_id` = ?',
    [$user->data()->id],
)->results();

$mappedPreferences = [];

foreach (Notification::getTypes() as $type) {
    $userTypePreference = array_search($type['key'], array_column($preferences, 'type'));

    $alert = $email = false;
    if ($userTypePreference !== false) {
        $alert = $preferences[$userTypePreference]->alert === 1;
        $email = $preferences[$userTypePreference]->email === 1;
    }

    $mappedPreferences[] = [
        'type' => $type['key'],
        'value' => $type['value'],
        'alert' => $alert,
        'email' => $email,
    ];
}

if (Session::exists('notification_settings_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('notification_settings_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
    ]);
}

$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'NOTIFICATION_SETTINGS_TITLE' => $language->get('user', 'notification_settings'),
    'NOTIFICATION_SETTINGS' => $mappedPreferences,
    'OVERVIEW' => $language->get('user', 'overview'),
    'SUBMIT' => $language->get('general', 'submit'),
    'ALERT' => $language->get('admin', 'mass_message_type_alert'),
    'EMAIL' => $language->get('admin', 'mass_message_type_email'),
    'TOKEN' => Token::get(),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require ROOT_PATH . '/core/templates/cc_navbar.php';

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('user/notification_settings.tpl', $smarty);
