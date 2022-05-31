<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  UserCP messaging page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_messaging';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

$smarty->assign(
    [
        'ERROR_TITLE' => $language->get('general', 'error')
    ]
);

// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/user/messaging'));
    }

    if ($_GET['p'] == 1) {
        // Avoid bug in pagination class
        if (isset($_GET['message'])) {
            Redirect::to(URL::build('/user/messaging/', 'action=view&message=' . urlencode($_GET['message'])));
        } else {
            Redirect::to(URL::build('/user/messaging'));
        }
    }
    $p = $_GET['p'];
} else {
    $p = 1;
}

if (!isset($_GET['action'])) {
    // Get private messages
    $messages = $user->listPMs($user->data()->id);

    // Pagination
    $paginator = new Paginator(
        $template_pagination ?? null,
        $template_pagination_left ?? null,
        $template_pagination_right ?? null
    );
    $results = $paginator->getLimited($messages, 10, $p, count($messages));
    $pagination = $paginator->generate(7, URL::build('/user/messaging/'));

    $smarty->assign('PAGINATION', $pagination);

    // Array to pass to template
    $template_array = [];

    // Display the correct number of messages
    foreach ($results->data as $nValue) {
        // Get participants list
        $participants = '';

        foreach ($nValue['users'] as $item) {
            $participants .= '<a href="' . URL::build('/profile/' . urlencode($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
        }
        $participants = rtrim($participants, ', ');

        $target_user = new User($nValue['user_updated']);
        $template_array[] = [
            'id' => $nValue['id'],
            'title' => Output::getClean($nValue['title']),
            'participants' => $participants,
            'link' => URL::build('/user/messaging/', 'action=view&amp;message=' . urlencode($nValue['id'])),
            'last_message_user_id' => Output::getClean($nValue['user_updated']),
            'last_message_user' => $target_user->getDisplayname(),
            'last_message_user_profile' => $target_user->getProfileURL(),
            'last_message_user_avatar' => $target_user->getAvatar(30),
            'last_message_user_style' => $target_user->getGroupStyle(),
            'last_message_date' => $timeago->inWords($nValue['updated'], $language),
            'last_message_date_full' => date(DATE_FORMAT, $nValue['updated'])
        ];
    }

    // Assign Smarty variables
    $smarty->assign(
        [
            'USER_CP' => $language->get('user', 'user_cp'),
            'MESSAGING' => $language->get('user', 'messaging'),
            'MESSAGES' => $template_array,
            'NO_MESSAGES' => $language->get('user', 'no_messages_full'),
            'MESSAGE_TITLE' => $language->get('user', 'message_title'),
            'PARTICIPANTS' => $language->get('user', 'participants'),
            'LAST_MESSAGE' => $language->get('user', 'last_message'),
        ]
    );

    if ($user->hasPermission('usercp.messaging')) {
        // Can send messages
        $smarty->assign(
            [
                'NEW_MESSAGE' => $language->get('user', 'new_message'),
                'NEW_MESSAGE_LINK' => URL::build('/user/messaging/', 'action=new')
            ]
        );
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    require(ROOT_PATH . '/core/templates/cc_navbar.php');

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('user/messaging.tpl', $smarty);

} else {
    if ($_GET['action'] == 'new') {
        if (!$user->hasPermission('usercp.messaging')) {
            Redirect::to(URL::build('/user/messaging'));
        }
        // New PM
        if (Input::exists()) {
            if (Token::check()) {
                // Validate input
                $validation = Validate::check($_POST, [
                    'title' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 64
                    ],
                    'content' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 20480
                    ],
                    'to' => [
                        Validate::REQUIRED => true
                    ]
                ])->messages([
                    'title' => [
                        Validate::REQUIRED => $language->get('user', 'title_required'),
                        Validate::MIN => $language->get('user', 'title_min_2'),
                        Validate::MAX => $language->get('user', 'title_max_64')
                    ],
                    'content' => [
                        Validate::REQUIRED => $language->get('user', 'content_required'),
                        Validate::MIN => $language->get('user', 'content_min_2'),
                        Validate::MAX => $language->get('user', 'content_max_20480')
                    ],
                    'to' => $language->get('user', 'users_to_required')
                ]);

                if ($validation->passed()) {
                    // Validation passed, validate recipients
                    $users = Input::get('to');
                    $users = explode(',', $users);
                    $n = 0;

                    // Replace white space at start of username, also limit to 10 users
                    foreach ($users as $item) {
                        if ($item[0] === ' ') {
                            $users[$n] = substr($item, 1);
                            $username = $users[$n];
                        } else {
                            $username = $item;
                        }

                        if ($username == $user->data()->nickname || $username == $user->data()->username) {
                            unset($users[$n]);
                            continue;
                        }

                        $user_id = $user->nameToId($item);
                        if ($user_id) {
                            if ($user->isBlocked($user_id, $user->data()->id) && !$user->canViewStaffCP()) {
                                $blocked = true;
                                unset($users[$n]);
                                continue;
                            }
                        }

                        if ($n == 10) {
                            $max_users = true;
                            break;
                        }
                        $n++;
                    }

                    if (isset($blocked)) {
                        $error = $language->get('user', 'one_or_more_users_blocked');

                    } else {
                        if (!count($users)) {
                            $error = $language->get('user', 'cant_send_to_self');

                        } else {
                            // Ensure people haven't been added twice
                            $users = array_unique($users);

                            if (!isset($max_users)) {

                                // Input the content
                                DB::getInstance()->insert(
                                    'private_messages',
                                    [
                                        'author_id' => $user->data()->id,
                                        'title' => Input::get('title'),
                                        'created' => date('U'),
                                        'last_reply_user' => $user->data()->id,
                                        'last_reply_date' => date('U')
                                    ]
                                );

                                // Get the PM ID
                                $last_id = DB::getInstance()->lastId();

                                // Insert post content into database
                                DB::getInstance()->insert(
                                    'private_messages_replies',
                                    [
                                        'pm_id' => $last_id,
                                        'author_id' => $user->data()->id,
                                        'created' => date('U'),
                                        'content' => Input::get('content')
                                    ]
                                );

                                // Add users to conversation
                                foreach ($users as $item) {
                                    // Get ID
                                    $user_id = $user->nameToId($item);

                                    if ($user_id) {
                                        // Not the author
                                        DB::getInstance()->insert(
                                            'private_messages_users',
                                            [
                                                'pm_id' => $last_id,
                                                'user_id' => $user_id
                                            ]
                                        );
                                    }
                                }

                                // Add the author to the list of users
                                DB::getInstance()->insert('private_messages_users', [
                                    'pm_id' => $last_id,
                                    'user_id' => $user->data()->id,
                                    'read' => true,
                                ]);

                                // Sent successfully
                                Session::flash('user_messaging_success', $language->get('user', 'message_sent_successfully'));
                                Redirect::to(URL::build('/user/messaging'));
                            }

                            // Over 10 users added
                            $error = $language->get('user', 'max_pm_10_users');
                        }
                    }
                } else {
                    // Errors
                    $errors = $validation->errors();

                    $error = implode('<br />', $errors);
                }

            } else {
                // Invalid token
                $error = $language->get('general', 'invalid_token');
            }
        }

        if (isset($error)) {
            $smarty->assign('ERROR', $error);
        }

        if (isset($_GET['uid'])) {
            // Messaging a specific user
            $user_messaging = DB::getInstance()->get('users', ['id', $_GET['uid']])->results();

            if (count($user_messaging)) {
                $smarty->assign('TO_USER', Output::getClean($user_messaging[0]->username));
            }
        }

        $content = (isset($_POST['content'])) ? EventHandler::executeEvent('renderPrivateMessageEdit', ['content' => $_POST['content']])['content'] : null;

        // Assign Smarty variables
        $smarty->assign(
            [
                'NEW_MESSAGE' => $language->get('user', 'new_message'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'CANCEL_LINK' => URL::build('/user/messaging'),
                'SUBMIT' => $language->get('general', 'submit'),
                'TOKEN' => Token::get(),
                'MESSAGE_TITLE' => $language->get('user', 'message_title'),
                'MESSAGE_TITLE_VALUE' => (isset($_POST['title']) ? Output::getPurified($_POST['title']) : ''),
                'TO' => $language->get('user', 'to'),
                'SEPARATE_USERS_WITH_COMMAS' => $language->get('user', 'separate_users_with_commas'),
                'ALL_USERS' => $user->listAllOtherUsers()
            ]
        );

        $template->assets()->include([
            AssetTree::TINYMCE,
        ]);

        $template->addJSScript(Input::createTinyEditor($language, 'reply', $content));

        // Load modules + template
        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        require(ROOT_PATH . '/core/templates/cc_navbar.php');

        $template->onPageLoad();

        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');

        // Display template
        $template->displayTemplate('user/new_message.tpl', $smarty);

    } else if ($_GET['action'] == 'view') {
        // Ensure message is specified
        if (!isset($_GET['message']) || !is_numeric($_GET['message'])) {
            Redirect::to(URL::build('/user/messaging'));
        }

        // Ensure message exists
        $pm = $user->getPM($_GET['message'], $user->data()->id); // Get the PM - this also handles setting it as "read"

        if (!$pm) { // Either PM doesn't exist, or the user doesn't have permission to view it
            Redirect::to(URL::build('/user/messaging'));
        }

        // Deal with input
        if (Input::exists()) {
            // Check token
            if (Token::check()) {
                // Valid token
                // Validate input
                $validation = Validate::check($_POST, [
                    'content' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 20480
                    ]
                ])->messages([
                    'content' => [
                        Validate::REQUIRED => $language->get('user', 'content_required'),
                        Validate::MIN => $language->get('user', 'content_min_2'),
                        Validate::MAX => $language->get('user', 'content_max_20480')
                    ]
                ]);

                if ($validation->passed()) {
                    $content = Input::get('content');

                    // Insert post content into database
                    DB::getInstance()->insert(
                        'private_messages_replies',
                        [
                            'pm_id' => $pm[0]->id,
                            'author_id' => $user->data()->id,
                            'created' => date('U'),
                            'content' => $content
                        ]
                    );

                    // Update last reply PM information
                    DB::getInstance()->update(
                        'private_messages',
                        $pm[0]->id,
                        [
                            'last_reply_user' => $user->data()->id,
                            'last_reply_date' => date('U')
                        ]
                    );

                    // Update PM as unread for all users
                    $users = DB::getInstance()->get('private_messages_users', ['pm_id', $pm[0]->id])->results();

                    foreach ($users as $item) {
                        if ($item->user_id != $user->data()->id) {
                            DB::getInstance()->update('private_messages_users', $item->id, [
                                'read' => false
                            ]);
                        }
                    }

                    // Display success message
                    $smarty->assign('MESSAGE_SENT', $language->get('user', 'message_sent_successfully'));
                    unset($_POST['content']);

                } else {
                    // Errors
                    $errors = $validation->errors();
                    $error = implode('<br />', $errors);
                }

            } else {
                // Invalid token
                $error = $language->get('general', 'invalid_token');
            }
        }

        if (isset($error)) {
            $smarty->assign('ERROR', $error);
        }

        // Get all PM replies
        $pm_replies = DB::getInstance()->get('private_messages_replies', ['pm_id', $_GET['message']])->results();

        // Pagination
        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($pm_replies, 10, $p, count($pm_replies));
        $pagination = $paginator->generate(7, URL::build('/user/messaging/', 'action=view&amp;message=' . urlencode($pm[0]->id) . '&amp;'));

        $smarty->assign('PAGINATION', $pagination);

        // Array to pass to template
        $template_array = [];

        // Display the correct number of messages
        foreach ($results->data as $nValue) {
            $target_user = new User($nValue->author_id);

            $template_array[] = [
                'id' => $nValue->id,
                'author_id' => $nValue->author_id,
                'author_username' => $target_user->getDisplayname(),
                'author_profile' => $target_user->getProfileURL(),
                'author_avatar' => $target_user->getAvatar(100),
                'author_style' => $target_user->getGroupStyle(),
                'author_groups' => $target_user->getAllGroupHtml(),
                'message_date' => $timeago->inWords($nValue->created, $language),
                'message_date_full' => date(DATE_FORMAT, $nValue->created),
                'content' => EventHandler::executeEvent('renderPrivateMessage', ['content' => $nValue->content])['content'],
            ];
        }

        // Get participants list
        $participants = '';

        foreach ($pm[1] as $item) {
            $participants .= '<a href="' . URL::build('/profile/' . urlencode($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
        }
        $participants = rtrim($participants, ', ');

        // Smarty variables
        $smarty->assign([
            'MESSAGE_TITLE' => Output::getClean($pm[0]->title),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/user/messaging'),
            'LEAVE_CONVERSATION' => $language->get('user', 'leave_conversation'),
            'CONFIRM_LEAVE' => $language->get('user', 'confirm_leave'),
            'LEAVE_CONVERSATION_LINK' => URL::build('/user/messaging/', 'action=leave&amp;message=' . urlencode($pm[0]->id)),
            'PAGINATION' => $pagination,
            'PARTICIPANTS_TEXT' => $language->get('user', 'participants'),
            'PARTICIPANTS' => $participants,
            'MESSAGES' => $template_array,
            'NEW_REPLY' => $language->get('user', 'new_reply'),
            'TOKEN' => Token::get(),
            'SUBMIT' => $language->get('general', 'submit'),
            'SUCCESS_TITLE' => $language->get('general', 'success'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
        ]);

        $content = (isset($_POST['content'])) ? EventHandler::executeEvent('renderPrivateMessageEdit', ['content' => $_POST['content']])['content'] : null;

        $template->assets()->include([
            AssetTree::TINYMCE,
        ]);

        $template->addJSScript(Input::createTinyEditor($language, 'reply', $content));

        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        require(ROOT_PATH . '/core/templates/cc_navbar.php');

        $template->onPageLoad();

        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');

        // Display template
        $template->displayTemplate('user/view_message.tpl', $smarty);

    } else if ($_GET['action'] == 'leave') {
        // Try to remove the user from the conversation
        if (!isset($_GET['message']) || !is_numeric($_GET['message']) || !Token::check($_POST['token'])) {
            Redirect::to(URL::build('/user/messaging'));
        }

        $message = DB::getInstance()->get('private_messages_users', ['pm_id', $_GET['message']])->results();

        if (count($message)) {
            foreach ($message as $item) {
                if ($item->user_id == $user->data()->id) {
                    DB::getInstance()->delete('private_messages_users', ['id', $item->id]);
                    break;
                }
            }
        }

        // Done, redirect
        Redirect::to(URL::build('/user/messaging'));
    }
}
