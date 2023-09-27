<?php
/**
 * StaffCP email mass messaging page
 *
 * @author Aberdeener
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

if (!$user->handlePanelPageLoad('admincp.core.emails_mass_message')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'announcements';
const PANEL_PAGE = 'mass_message';
$page_title = $language->get('admin', 'mass_message');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        $validation = Validate::check($_POST, [
            'subject' => [
                Validate::REQUIRED => true,
                Validate::MIN => 1,
            ],
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 1,
                Validate::MAX => EMAIL_MAX_LENGTH,
            ],
        ])->messages([
            'subject' => [
                Validate::REQUIRED => $language->get('admin', 'mass_message_subject_required'),
                Validate::MIN => $language->get('admin', 'mass_message_subject_required'),
            ],
            'content' => [
                Validate::REQUIRED => $language->get('admin', 'mass_message_content_required'),
                Validate::MIN => $language->get('admin', 'mass_message_content_required'),
                Validate::MAX => $language->get('admin', 'mass_message_content_maximum', ['max' => EMAIL_MAX_LENGTH]),
            ],
        ]);

        if ($validation->passed()) {
            // TODO: validation can't handle array passed in as value
            if (!empty($_POST['type'])) {
                // Exclusions
                $excludedUsers = [];
                if (isset($_POST['exclude_groups'])) {
                    $in = implode(',', array_map(static fn ($g) => '?', $_POST['exclude_groups']));
                    $excludeUsers = DB::getInstance()->query(
                        <<<SQL
                        SELECT id FROM nl2_users WHERE id IN (SELECT user_id FROM nl2_users_groups WHERE group_id IN ($in))
                        SQL,
                        $_POST['exclude_groups'],
                    )->results();
                    $excludedUsers = array_map(static fn ($u) => $u->id, $excludeUsers);
                }
                if (isset($_POST['exclude_users'])) {
                    $excludedUsers = array_merge($excludedUsers, $_POST['exclude_users']);
                }

                // Inclusions
                $includedUsers = [];
                if (isset($_POST['include_groups'])) {
                    $in = implode(',', array_map(static fn ($g) => '?', $_POST['include_groups']));
                    $includeUsers = DB::getInstance()->query(
                        <<<SQL
                        SELECT id FROM nl2_users WHERE id IN (SELECT user_id FROM nl2_users_groups WHERE group_id IN ($in))
                        SQL,
                        $_POST['include_groups'],
                    )->results();
                    $includedUsers = array_map(static fn ($u) => $u->id, $includeUsers);
                }
                if (isset($_POST['include_users'])) {
                    $includedUsers = [...$includedUsers, ...$_POST['include_users']];
                }

                $join = '';
                $clause = '';
                if (!isset($_POST['ignore_opt_in']) || !$_POST['ignore_opt_in']) {
                    $join = 'INNER JOIN nl2_users_notification_preferences unp ON unp.user_id = u.id';
                    $clause = 'unp.`type` = \'mass_message\' AND unp.enabled = 1';
                }

                if (!empty($excludedUsers) || !empty($includedUsers)) {
                    $excludeClause = '';
                    if (!empty($excludedUsers)) {
                        $excludedIn = implode(',', array_map(static fn ($u) => '?', $excludedUsers));
                        $excludeClause = "u.ID NOT IN ($excludedIn)";
                    }

                    $includeClause = '';
                    if (!empty($includedUsers)) {
                        $includedIn = implode(',', array_map(static fn ($u) => '?', $includedUsers));
                        $includeClause = "u.ID IN ($includedIn)";
                    }

                    $glue = '';
                    if ($excludeClause && $includeClause) {
                        $glue = 'AND';
                    }

                    $clause = "AND $clause";

                    $ids = array_merge($filterGroups ?? [], $filterUsers ?? []);
                    $users = DB::getInstance()->query(
                        <<<SQL
                        SELECT u.id FROM nl2_users u
                        $join
                        WHERE $excludeClause $glue $includeClause
                        $clause
                        SQL,
                        [...$excludedUsers, ...$includedUsers]
                    );
                } else {
                    $users = DB::getInstance()->query(
                        <<<SQL
                        SELECT u.id FROM nl2_users u
                        $join
                        WHERE $clause
                        SQL
                    );
                }

                $total = $users->count();

                $task = (new MassMessage())->fromNew(
                    Module::getIdFromName('Core'),
                    $language->get('admin', 'mass_message'),
                    [
                        'callback' => 'MassMessage::parseContent',
                        'content' => Input::get('content'),
                        'title' => Input::get('subject'),
                        'type' => 'mass_message',
                        'users' => $users->results()
                    ],
                    date('U'),
                    null,
                    null,
                    true,
                    $total,
                    $user->data()->id
                );

                Queue::schedule($task);

                echo <<<SQL
                        SELECT u.id FROM nl2_users u
                        $join
                        WHERE $clause
                        SQL;
                die();

                // TODO: user settings page for notification settings
                // TODO: revamp alerts page to allow for rich HTML?

//                $users = DB::getInstance()->get('users', ['id', '<>', 0])->results();
//
//                foreach ($users as $email_user) {
//                    $sent = Email::send(
//                        ['email' => $email_user->email, 'name' => $email_user->username],
//                        Input::get('subject'),
//                        str_replace(['{username}', '{sitename}'], [$email_user->username, SITE_NAME], Output::getPurified(Input::get('content'))),
//                    );
//
//                    if (isset($sent['error'])) {
//                        DB::getInstance()->insert('email_errors', [
//                            'type' => Email::MASS_MESSAGE,
//                            'content' => $sent['error'],
//                            'at' => date('U'),
//                            'user_id' => $user->data()->id
//                        ]);
//
//                        $errors[] = $language->get('admin', 'mass_email_failed_check_logs');
//                    } else {
//                        Session::flash('emails_success', $language->get('admin', 'sent_mass_message'));
//                    }
//                }

                Log::getInstance()->log(Log::Action('admin/core/email/mass_message'));
            } else {
                $errors = [$language->get('admin', 'mass_message_type_required')];
            }
        } else {
            $errors = $validation->errors();
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

$allGroups = DB::getInstance()->query('SELECT id, name FROM nl2_groups')->results();

$smarty->assign([
    'SENDING_MASS_MESSAGE' => $language->get('admin', 'sending_mass_message'),
    'COMMUNICATIONS' => $language->get('admin', 'communications'),
    'MASS_MESSAGE' => $language->get('admin', 'mass_message'),
    'SUBJECT' => $language->get('admin', 'email_message_subject'),
    'CONTENT' => $language->get('general', 'content'),
    'INFO' => $language->get('general', 'info'),
    'REPLACEMENT_INFO' => $language->get('admin', 'mass_message_replacements'),
    'ALL_GROUPS' => $allGroups,
    'USERS_QUERY_URL' => URL::build('/queries/users'),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
    'EXCLUSION_INCLUSION_INFO' => $language->get('admin', 'mass_message_exclusions_override_inclusions'),
    'EXCLUDED_GROUPS' => $language->get('admin', 'mass_message_excluded_groups'),
    'EXCLUDED_USERS' => $language->get('admin', 'mass_message_excluded_users'),
    'IGNORE_OPT_IN' => $language->get('admin', 'mass_message_ignore_opt_in'),
    'IGNORE_OPT_IN_INFO' => $language->get('admin', 'mass_message_ignore_opt_in_info'),
    'INCLUDED_GROUPS' => $language->get('admin', 'mass_message_included_groups'),
    'INCLUDED_USERS' => $language->get('admin', 'mass_message_included_users'),
    'MESSAGE_TYPE' => $language->get('admin', 'mass_message_type'),
    'MESSAGE_TYPE_ALERT' => $language->get('admin', 'mass_message_type_alert'),
    'MESSAGE_TYPE_EMAIL' => $language->get('admin', 'mass_message_type_email'),
    'UNSAFE_HTML' => $language->get('admin', 'unsafe_html'),
    'UNSAFE_HTML_WARNING' => $language->get('admin', 'unsafe_html_warning'),
]);

$template_file = 'core/mass_message.tpl';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'message', null, false, true));

if (Session::exists('emails_success')) {
    $success = Session::flash('emails_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
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
    'EMAILS' => $language->get('admin', 'emails'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
