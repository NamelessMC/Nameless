<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel user page
 */

if (!$user->handlePanelPageLoad()) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

$uid = explode('/', $route);
$uid = $uid[count($uid) - 1];

if (!strlen($uid)) {
    Redirect::to(URL::build('/panel'));
}

$uid = explode('-', $uid);
if (!is_numeric($uid[0])) {
    Redirect::to(URL::build('/panel'));
}
$uid = $uid[0];

$view_user = new User($uid);
if (!$view_user->exists()) {
    Redirect::to(URL::build('/panel'));
}
$user_query = $view_user->data();

$timeago = new TimeAgo(TIMEZONE);

const PAGE = 'panel';
const PANEL_PAGE = 'users';
const PARENT_PAGE = 'users';
$page_title = Output::getClean($user_query->username);
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Input::exists()) {
    if (Input::get('action') === 'linkEnjin') {
        $user_id = Input::get('user');
        $link_user = new User($user_id);

        // Ensure user exists, and it is valid (not the current user and enjin imported)
        if ($link_user->data()->id == $user_query->id || $link_user->data()->pass_method == 'enjin-import') {
            $errors = [$language->get('admin', 'invalid_user')];
        } else {
            try {
                DB::getInstance()->getPDO()->beginTransaction();

                DB::getInstance()->query('UPDATE nl2_forums SET last_user_posted = ? WHERE last_user_posted = ?', [$link_user->data()->id, $user_query->id]);
                DB::getInstance()->query('UPDATE nl2_topics SET topic_creator = ? WHERE topic_creator = ?', [$link_user->data()->id, $user_query->id]);
                DB::getInstance()->query('UPDATE nl2_topics SET topic_last_user = ? WHERE topic_last_user = ?', [$link_user->data()->id, $user_query->id]);
                DB::getInstance()->query('UPDATE nl2_posts SET post_creator = ? WHERE post_creator = ?', [$link_user->data()->id, $user_query->id]);
                if (Input::get('overwriteIntegrations') == '1' || !$link_user->getIntegration('Minecraft')) {
                    DB::getInstance()->query('DELETE FROM nl2_users_integrations WHERE user_id = ? AND integration_id = 1', [$link_user->data()->id]);
                    DB::getInstance()->query('UPDATE nl2_users_integrations SET user_id = ? WHERE user_id = ? AND integration_id = 1', [$link_user->data()->id, $user_query->id]);
                }

                EventHandler::executeEvent('deleteUser', [
                    'user_id' => $user_query->id,
                ]);

                DB::getInstance()->getPDO()->commit();

                Session::flash('user_link_enjin_success', $language->get('admin', 'user_link_enjin_success', [
                    'oldUsername' => Text::bold(Output::getClean($user_query->username)),
                ]));
                Redirect::to(URL::build('/panel/user/' . Output::getClean($link_user->data()->id)));
            } catch (Exception $exception) {
                DB::getInstance()->getPDO()->rollBack();
                $errors = [$exception->getMessage()];
            }
        }
    }
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
} else if (Session::exists('user_link_enjin_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('user_link_enjin_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$user_language = DB::getInstance()->get('languages', ['id', $user_query->language_id])->results();
$user_language = $user_language[0]->name;

if ($user->hasPermission('admincp.users.edit')) {
    // Email address
    $smarty->assign([
        'EMAIL_ADDRESS' => Output::getClean($user_query->email),
        'EMAIL_ADDRESS_LABEL' => $language->get('user', 'email_address')
    ]);
}

if ($user->hasPermission('modcp.ip_lookup')) {
    // Last IP
    $smarty->assign([
        'LAST_IP' => Output::getClean($user_query->lastip),
        'LAST_IP_LABEL' => $language->get('admin', 'ip_address')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'AVATAR' => $view_user->getAvatar(256),
    'NICKNAME' => $view_user->getDisplayname(),
    'USERNAME' => $view_user->getDisplayname(true),
    'USER_STYLE' => $view_user->getGroupStyle(),
    'USER_GROUP' => Output::getClean($view_user->getMainGroup()->name),
    'USER_GROUPS' => $view_user->getAllGroupHtml(),
    'USER_TITLE' => Output::getClean($user_query->user_title),
    'LANGUAGE' => Output::getClean($user_language),
    'TIMEZONE' => Output::getClean($user_query->timezone),
    'REGISTERED' => $language->get('user', 'registered'),
    'REGISTERED_VALUE' => date('d M Y', $user_query->joined),
    'LAST_SEEN' => $language->get('user', 'last_seen'),
    'LAST_SEEN_SHORT_VALUE' => $timeago->inWords($user_query->last_online, $language),
    'LAST_SEEN_FULL_VALUE' => date(DATE_FORMAT, $user_query->last_online),
    'DETAILS' => $language->get('admin', 'details'),
    'LINKS' => Core_Module::getUserActions(),
    'USER_ID' => $user_query->id,
    'USERNAME_LABEL' => $language->get('user', 'username'),
    'NICKNAME_LABEL' => $language->get('user', 'nickname'),
    'USER_TITLE_LABEL' => $language->get('admin', 'title'),
    'LANGUAGE_LABEL' => $language->get('user', 'active_language'),
    'TIMEZONE_LABEL' => $language->get('user', 'timezone'),
    'NAME' => $language->get('admin', 'name'),
    'CONTENT' => $language->get('admin', 'content'),
    'UPDATED' => $language->get('admin', 'updated'),
    'NOT_SET' => $language->get('admin', 'not_set'),
    'PROFILE_FIELDS_LABEL' => 'Profile Fields',
    'ALL_PROFILE_FIELDS' => ProfileField::all(),
    'USER_PROFILE_FIELDS' => $view_user->getProfileFields(true),
    'NO_PROFILE_FIELDS' => $language->get('admin', 'no_custom_fields'),
]);

if ($view_user->data()->pass_method === 'enjin-import' && Util::getSetting('enjin_imported')) {
    $smarty->assign([
        'USER_IS_ENJIN_IMPORTED' => true,
        'USER_IS_ENJIN_IMPORTED_MESSAGE' => $language->get('admin', 'user_imported_from_enjin', [
            'buttonStart' => '<a onclick="$(\'#linkModal\').modal().show();" style="text-decoration: underline; cursor: pointer;">',
            'buttonEnd' => '</a>',
        ]),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'NO' => $language->get('general', 'no'),
        'YES' => $language->get('general', 'yes'),
        'ALL_USERS' => DB::getInstance()->query("SELECT id, username FROM nl2_users WHERE pass_method <> 'enjin-import'")->results(),
        'HAS_MC_INTEGRATION' => $view_user->getIntegration('Minecraft') !== null,
        'CHOOSE_USER' => $language->get('admin', 'choose_user_to_link'),
        'WARNING' => $language->get('admin', 'cant_undo_enjin_link'),
        'OVERWRITE_EXISTING_INTEGRATIONS' => $language->get('admin', 'overwrite_existing_integrations'),
    ]);
}

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/user.tpl', $smarty);
