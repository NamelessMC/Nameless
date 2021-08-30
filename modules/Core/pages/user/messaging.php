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
    die();
}

// Always define page name for navbar
define('PAGE', 'cc_messaging');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles(
    array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array()
    )
);

// Display either Markdown or HTML editor
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    $template->addJSFiles(
        array(
            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array(),
        )
    );

    $template->addJSScript(
        '$(document).ready(function() {
            var el = $("#markdown").emojioneArea({
                pickerPosition: "bottom"
            });
        });'
    );
} else {
    $template->addJSFiles(
        array(
            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
        )
    );

    $template->addJSScript(Input::createTinyEditor($language, 'reply'));
}

$timeago = new Timeago(TIMEZONE);

require(ROOT_PATH . '/core/includes/emojione/autoload.php'); // Emojione
require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

$smarty->assign(
    array(
        'ERROR_TITLE' => $language->get('general', 'error')
    )
);

// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/user/messaging'));
        die();
    } else {
        if ($_GET['p'] == 1) {
            // Avoid bug in pagination class
            if(isset($_GET['message']))
                Redirect::to(URL::build('/user/messaging/', 'action=view&message=' . Output::getClean($_GET['message'])));
            else
                Redirect::to(URL::build('/user/messaging'));
            die();
        }
        $p = $_GET['p'];
    }
} else {
    $p = 1;
}

if(!isset($_GET['action'])) {
    // Get private messages
    $messages = $user->listPMs($user->data()->id);

    // Pagination
    $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
    $results = $paginator->getLimited($messages, 10, $p, count($messages));
    $pagination = $paginator->generate(7, URL::build('/user/messaging/', true));

    $smarty->assign('PAGINATION', $pagination);

    // Array to pass to template
    $template_array = array();

    // Display the correct number of messages
    for ($n = 0; $n < count($results->data); $n++) {
        // Get participants list
        $participants = '';

        foreach ($results->data[$n]['users'] as $item) {
            $participants .= '<a href="' . URL::build('/profile/' . Output::getClean($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
        }
        $participants = rtrim($participants, ', ');

        $target_user = new User($results->data[$n]['user_updated']);
        $template_array[] = array(
            'id' => $results->data[$n]['id'],
            'title' => Output::getClean($results->data[$n]['title']),
            'participants' => $participants,
            'link' => URL::build('/user/messaging/', 'action=view&amp;message=' . $results->data[$n]['id']),
            'last_message_user_id' => Output::getClean($results->data[$n]['user_updated']),
            'last_message_user' => $target_user->getDisplayname(),
            'last_message_user_profile' => $target_user->getProfileURL(),
            'last_message_user_avatar' => $target_user->getAvatar(30),
            'last_message_user_style' => $target_user->getGroupClass(),
            'last_message_date' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]['updated']), $language->getTimeLanguage()),
            'last_message_date_full' => date('d M Y, H:i', $results->data[$n]['updated'])
        );
    }

    // Assign Smarty variables
    $smarty->assign(
        array(
            'USER_CP' => $language->get('user', 'user_cp'),
            'MESSAGING' => $language->get('user', 'messaging'),
            'MESSAGES' => $template_array,
            'NO_MESSAGES' => $language->get('user', 'no_messages_full'),
            'MESSAGE_TITLE' => $language->get('user', 'message_title'),
            'PARTICIPANTS' => $language->get('user', 'participants'),
            'LAST_MESSAGE' => $language->get('user', 'last_message'),
            'BY' => $language->get('user', 'by')
        )
    );

    if ($user->hasPermission('usercp.messaging')) {
        // Can send messages
        $smarty->assign(
            array(
                'NEW_MESSAGE' => $language->get('user', 'new_message'),
                'NEW_MESSAGE_LINK' => URL::build('/user/messaging/', 'action=new')
            )
        );
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

    require(ROOT_PATH . '/core/templates/cc_navbar.php');

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('user/messaging.tpl', $smarty);

} else {
    if ($_GET['action'] == 'new') {
        if (!$user->hasPermission('usercp.messaging')) {
          Redirect::to(URL::build('/user/messaging'));
          die();
        }
        // New PM
        if (Input::exists()) {
            if (Token::check()) {
                // Validate input
                $validate = new Validate();
                $validation = $validate->check($_POST, [
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

                    } else if (!count($users)) {
                        $error = $language->get('user', 'cant_send_to_self');

                    } else {
                        // Ensure people haven't been added twice
                        $users = array_unique($users);

                        if (!isset($max_users)) {

                            // Input the content
                            $queries->create(
                                'private_messages',
                                array(
                                    'author_id' => $user->data()->id,
                                    'title' => Output::getClean(Input::get('title')),
                                    'created' => date('U'),
                                    'last_reply_user' => $user->data()->id,
                                    'last_reply_date' => date('U')
                                )
                            );

                            // Get the PM ID
                            $last_id = $queries->getLastId();

                            // Parse markdown
                            $cache->setCache('post_formatting');
                            $formatting = $cache->retrieve('formatting');

                            if ($formatting == 'markdown'){
                                $content = Michelf\Markdown::defaultTransform(Input::get('content'));
                                $content = Output::getClean($content);
                            } else {
                                $content = Output::getClean(Input::get('content'));
                            }

                            // Insert post content into database
                            $queries->create(
                                'private_messages_replies',
                                array(
                                    'pm_id' => $last_id,
                                    'author_id' => $user->data()->id,
                                    'created' => date('U'),
                                    'content' => $content
                                )
                            );

                            // Add users to conversation
                            foreach ($users as $item) {
                                // Get ID
                                $user_id = $user->nameToId($item);

                                if ($user_id) {
                                    // Not the author
                                    $queries->create(
                                        'private_messages_users',
                                        array(
                                            'pm_id' => $last_id,
                                            'user_id' => $user_id
                                        )
                                    );
                                }
                            }

                            // Add the author to the list of users
                            $queries->create(
                                'private_messages_users',
                                array(
                                    'pm_id' => $last_id,
                                    'user_id' => $user->data()->id,
                                    'read' => 1
                                )
                            );

                            // Sent successfully
                            Session::flash('user_messaging_success', $language->get('user', 'message_sent_successfully'));
                            Redirect::to(URL::build('/user/messaging'));
                            die();

                        } else {
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

        // Markdown or HTML?
        $cache->setCache('post_formatting');
        $formatting = $cache->retrieve('formatting');

        if ($formatting == 'markdown') {
            // Markdown
            $smarty->assign('MARKDOWN', true);
            $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
        }

        if (isset($_GET['uid'])) {
            // Messaging a specific user
            $user_messaging = $queries->getWhere('users', array('id', '=', $_GET['uid']));

            if (count($user_messaging)) {
                $smarty->assign('TO_USER', Output::getClean($user_messaging[0]->nickname));
            }
        }

        // Assign Smarty variables
        $smarty->assign(
            array(
                'NEW_MESSAGE' => $language->get('user', 'new_message'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'CANCEL_LINK' => URL::build('/user/messaging'),
                'SUBMIT' => $language->get('general', 'submit'),
                'TOKEN' => Token::get(),
                'MESSAGE_TITLE' => $language->get('user', 'message_title'),
                'MESSAGE_TITLE_VALUE' => (isset($_POST['title']) ? Output::getPurified($_POST['title']) : ''),
                'CONTENT' => (isset($_POST['content']) ? Output::getPurified($_POST['content']) : ''),
                'TO' => $language->get('user', 'to'),
                'SEPARATE_USERS_WITH_COMMAS' => $language->get('user', 'separate_users_with_commas'),
                'ALL_USERS' => $user->listAllUsers()
            )
        );

        // Load modules + template
        Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

        require(ROOT_PATH . '/core/templates/cc_navbar.php');

        $page_load = microtime(true) - $start;
        define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

        $template->onPageLoad();

        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');

        // Display template
        $template->displayTemplate('user/new_message.tpl', $smarty);

    } else if ($_GET['action'] == 'view') {
        // Ensure message is specified
        if (!isset($_GET['message']) || !is_numeric($_GET['message'])) {
            Redirect::to(URL::build('/user/messaging'));
            die();
        }

        // Ensure message exists
        $pm = $user->getPM($_GET['message'], $user->data()->id); // Get the PM - this also handles setting it as "read"

        if (!$pm) { // Either PM doesn't exist, or the user doesn't have permission to view it
            Redirect::to(URL::build('/user/messaging'));
            die();
        }

        // Deal with input
        if (Input::exists()) {
            // Check token
            if (Token::check()) {
                // Valid token
                // Validate input
                $validate = new Validate();
                $validation = $validate->check($_POST, [
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
                    // Parse markdown
                    $cache->setCache('post_formatting');
                    $formatting = $cache->retrieve('formatting');

                    if ($formatting == 'markdown') {
                        $content = Michelf\Markdown::defaultTransform(Input::get('content'));
                        $content = Output::getClean($content);
                    } else {
                        $content = Output::getClean(Input::get('content'));
                    }

                    // Insert post content into database
                    $queries->create(
                        'private_messages_replies',
                        array(
                            'pm_id' => $pm[0]->id,
                            'author_id' => $user->data()->id,
                            'created' => date('U'),
                            'content' => $content
                        )
                    );

                    // Update last reply PM information
                    $queries->update(
                        'private_messages',
                        $pm[0]->id,
                        array(
                            'last_reply_user' => $user->data()->id,
                            'last_reply_date' => date('U')
                        )
                    );

                    // Update PM as unread for all users
                    $users = $queries->getWhere('private_messages_users', array('pm_id', '=', $pm[0]->id));

                    foreach ($users as $item) {
                        if ($item->user_id != $user->data()->id) {
                            $queries->update(
                                'private_messages_users',
                                $item->id,
                                array(
                                    '`read`' => 0
                                )
                            );
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
        $pm_replies = $queries->getWhere('private_messages_replies', array('pm_id', '=', $_GET['message']));

        // Pagination
        $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
        $results = $paginator->getLimited($pm_replies, 10, $p, count($pm_replies));
        $pagination = $paginator->generate(7, URL::build('/user/messaging/', 'action=view&amp;message=' . $pm[0]->id . '&amp;'));

        $smarty->assign('PAGINATION', $pagination);

        // Array to pass to template
        $template_array = array();

        // Display the correct number of messages
        for ($n = 0; $n < count($results->data); $n++) {
            $target_user = new User($results->data[$n]->author_id);

            $template_array[] = array(
                'id' => $results->data[$n]->id,
                'author_id' => $results->data[$n]->author_id,
                'author_username' => $target_user->getDisplayname(),
                'author_profile' => $target_user->getProfileURL(),
                'author_avatar' => $target_user->getAvatar(100),
                'author_style' => $target_user->getGroupClass(),
                'author_groups' => $target_user->getAllGroups('true'),
                'message_date' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->created), $language->getTimeLanguage()),
                'message_date_full' => date('d M Y, H:i', $results->data[$n]->created),
                'content' => Output::getPurified($emojione->unicodeToImage(Output::getDecoded($results->data[$n]->content)))
            );
        }

        // Get participants list
        $participants = '';

        foreach ($pm[1] as $item){
            $participants .= '<a href="' . URL::build('/profile/' . Output::getClean($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
        }
        $participants = rtrim($participants, ', ');

        // Smarty variables
        $smarty->assign(array(
            'MESSAGE_TITLE' => Output::getClean($pm[0]->title),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/user/messaging'),
            'LEAVE_CONVERSATION' => $language->get('user', 'leave_conversation'),
            'CONFIRM_LEAVE' => $language->get('user', 'confirm_leave'),
            'LEAVE_CONVERSATION_LINK' => URL::build('/user/messaging/', 'action=leave&amp;message=' . $pm[0]->id),
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
        ));

        // Markdown or HTML?
        $cache->setCache('post_formatting');
        $formatting = $cache->retrieve('formatting');

        if($formatting == 'markdown'){
            // Markdown
            $smarty->assign('MARKDOWN', true);
            $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
        }

        if(isset($_POST['content']))
            $smarty->assign('CONTENT', Output::getClean($_POST['content']));
        else $smarty->assign('CONTENT', '');

        Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

        require(ROOT_PATH . '/core/templates/cc_navbar.php');

        $page_load = microtime(true) - $start;
        define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

        $template->onPageLoad();

        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');

        // Display template
        $template->displayTemplate('user/view_message.tpl', $smarty);

    } else if ($_GET['action'] == 'leave') {
        // Try to remove the user from the conversation
        if (!isset($_GET['message']) || !is_numeric($_GET['message']) || !Token::check($_POST['token'])) {
            Redirect::to(URL::build('/user/messaging'));
            die();
        }

        $message = $queries->getWhere('private_messages_users', array('pm_id', '=', $_GET['message']));

        if (count($message)) {
            foreach ($message as $item) {
                if ($item->user_id == $user->data()->id) {
                    $queries->delete('private_messages_users', array('id', '=', $item->id));
                    break;
                }
            }
        }

        // Done, redirect
        Redirect::to(URL::build('/user/messaging'));
        die();
    }
}
