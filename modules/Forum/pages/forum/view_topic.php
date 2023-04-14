<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  View topic page
 */

// Set the page name for the active link in navbar
const PAGE = 'forum';

$forum = new Forum();
$timeago = new TimeAgo(TIMEZONE);

// Get topic ID
$tid = explode('/', $route);
$tid = $tid[count($tid) - 1];

if (!strlen($tid)) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

$tid = explode('-', $tid);
if (!is_numeric($tid[0])) {
    require_once(ROOT_PATH . '/404.php');
    die();
}
$tid = $tid[0];

// Does the topic exist, and can the user view it?
$user_groups = $user->getAllGroupIds();

$list = $forum->topicExist($tid);
if (!$list) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

// Get the topic information
$topic = DB::getInstance()->get('topics', ['id', $tid])->results();
$topic = $topic[0];

if ($topic->deleted == 1) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

$list = $forum->canViewForum($topic->forum_id, $user_groups);
if (!$list) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if ($user->isLoggedIn()) {
    $user_id = $user->data()->id;
} else {
    $user_id = 0;
}

if ($topic->topic_creator != $user_id && !$forum->canViewOtherTopics($topic->forum_id, $user_groups)) {
    // Only allow viewing stickied topics
    if ($topic->sticky == 0) {
        require_once(ROOT_PATH . '/403.php');
        die();
    }
}

// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/forum'));
    }

    if ($_GET['p'] <= 1) {
        // Avoid bug in pagination class
        Redirect::to(URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title)));
    }
    $p = $_GET['p'];
} else {
    $p = 1;
}

// Is the URL pointing to a specific post?
if (isset($_GET['pid'])) {
    $posts = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? AND deleted = 0', [$tid])->results();
    if (count($posts)) {
        $i = 0;
        while ($i < count($posts)) {
            if ($posts[$i]->id == $_GET['pid']) {
                $output = $i + 1;
                break;
            }
            $i++;
        }
        if (ceil($output / 10) != $p) {
            Redirect::to(URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title), 'p=' . ceil($output / 10)) . '#post-' . $_GET['pid']);
        } else {
            Redirect::to(URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title)) . '#post-' . $_GET['pid']);
        }
    } else {
        require_once(ROOT_PATH . '/404.php');
    }
    die();
}

// Follow/unfollow
if (isset($_GET['action'])) {
    if ($user->isLoggedIn()) {
        if (Token::check($_POST['token'])) {
            switch ($_GET['action']) {
                case 'follow':
                    $already_following = DB::getInstance()->query('SELECT id FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);
                    if (!$already_following->count()) {
                        DB::getInstance()->insert('topics_following', [
                            'topic_id' => $tid,
                            'user_id' => $user->data()->id,
                            'existing_alerts' => 0
                        ]);
                        Session::flash('success_post', $forum_language->get('forum', 'now_following_topic'));
                    }
                    break;
                case 'unfollow':
                    $delete = DB::getInstance()->query('DELETE FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);
                    Session::flash('success_post', $forum_language->get('forum', 'no_longer_following_topic'));
                    if (isset($_GET['return']) && $_GET['return'] == 'list') {
                        Redirect::to(URL::build('/user/following_topics'));
                    }
                    break;
            }
        } else {
            Session::flash('failure_post', $language->get('general', 'invalid_token'));
        }
    }

    Redirect::to(URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title)));
}

$forum_parent = DB::getInstance()->get('forums', ['id', $topic->forum_id])->results();

$page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/forum/topic'])->results();
if (count($page_metadata)) {
    $first_post = DB::getInstance()->orderWhere('posts', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1')->results();
    $first_post = htmlentities(strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], $first_post[0]->post_content)), ENT_QUOTES, 'UTF-8', false);

    define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{forum_title}', '{page}', '{post}'], [Output::getClean(SITE_NAME), Output::getClean($topic->topic_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($forum_parent[0]->forum_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
    define('PAGE_KEYWORDS', $page_metadata[0]->tags);
} else {
    $page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/forum/view_topic'])->results();

    if (count($page_metadata)) {
        $first_post = DB::getInstance()->orderWhere('posts', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1')->results();
        $first_post = htmlentities(strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], $first_post[0]->post_content)), ENT_QUOTES, 'UTF-8', false);

        define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{forum_title}', '{page}', '{post}'], [Output::getClean(SITE_NAME), Output::getClean($topic->topic_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($forum_parent[0]->forum_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
        define('PAGE_KEYWORDS', $page_metadata[0]->tags);
    }
}

$page_title = ((strlen(Output::getClean($topic->topic_title)) > 20) ? Output::getClean(mb_substr($topic->topic_title, 0, 20)) . '...' : Output::getClean($topic->topic_title)) . ' - ' . $language->get('general', 'page_x', ['page' => $p]);
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Assign author + title to Smarty variables
// Get first post
$first_post = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY id ASC LIMIT 1', [$tid])->first();

$topic_user = new User($topic->topic_creator);

$smarty->assign([
    'TOPIC_TITLE' => Output::getClean($topic->topic_title),
    'TOPIC_AUTHOR_USERNAME' => $topic_user->getDisplayname(),
    'TOPIC_AUTHOR_MCNAME' => $topic_user->getDisplayname(true),
    'TOPIC_AUTHOR_PROFILE' => $topic_user->getProfileURL(),
    'TOPIC_AUTHOR_STYLE' => $topic_user->getGroupStyle(),
    'TOPIC_ID' => $topic->id,
    'FORUM_ID' => $topic->forum_id,
    'TOPIC_LAST_EDITED' => ($first_post->last_edited ? $timeago->inWords($first_post->last_edited, $language) : null),
    'TOPIC_LAST_EDITED_FULL' => ($first_post->last_edited ? date(DATE_FORMAT, $first_post->last_edited) : null)
]);

// Is there a label?
if ($topic->label != 0) { // yes
    // Get label
    $label = DB::getInstance()->get('forums_topic_labels', ['id', $topic->label])->results();
    if (count($label)) {
        $label = $label[0];

        $label_html = DB::getInstance()->get('forums_labels', ['id', $label->label])->results();
        if (count($label_html)) {
            $label_html = Output::getPurified($label_html[0]->html);
            $label = str_replace('{x}', Output::getClean($label->name), $label_html);
        } else {
            $label = '';
        }
    } else {
        $label = '';
    }
} else { // no
    $label = '';
}

$labels = [];
if ($topic->labels) {
    // Get labels
    $topic_labels = explode(',', $topic->labels);

    foreach ($topic_labels as $topic_label) {
        $label_query = DB::getInstance()->get('forums_topic_labels', ['id', $topic_label])->results();
        if (count($label_query)) {
            $label_query = $label_query[0];

            $label_html = DB::getInstance()->get('forums_labels', ['id', $label_query->label])->results();
            if (count($label_html)) {
                $label_html = Output::getPurified($label_html[0]->html);
                $labels[] = str_replace('{x}', Output::getClean($label_query->name), $label_html);
            }
        }
    }
}

$smarty->assign(['TOPIC_LABEL' => $label, 'TOPIC_LABELS' => $labels]);

// Get all posts in the topic
$posts = $forum->getPosts($tid);

// Can the user post a reply in this topic?
if ($user->isLoggedIn()) {
    // Topic locked?
    if ($topic->locked == 0 || $forum->canModerateForum($topic->forum_id, $user_groups)) {
        $can_reply = $forum->canPostReply($topic->forum_id, $user_groups);
    } else {
        $can_reply = false;
    }
} else {
    $can_reply = false;
}

// Quick reply
if (Input::exists()) {
    if (!$user->isLoggedIn() || !$can_reply) {
        Redirect::to(URL::build('/forum'));
    }
    if (Token::check()) {
        $validate = Validate::check($_POST, [
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 50000
            ]
        ])->messages([
            'content' => [
                Validate::REQUIRED => $forum_language->get('forum', 'content_required'),
                Validate::MIN => $forum_language->get('forum', 'content_min_2'),
                Validate::MAX => $forum_language->get('forum', 'content_max_50000')
            ]
        ]);

        if ($validate->passed()) {
            $content = Input::get('content');

            DB::getInstance()->insert('posts', [
                'forum_id' => $topic->forum_id,
                'topic_id' => $tid,
                'post_creator' => $user->data()->id,
                'post_content' => $content,
                'post_date' => date('Y-m-d H:i:s'),
                'created' => date('U')
            ]);

            // Get last post ID
            $last_post_id = DB::getInstance()->lastId();
            $content = EventHandler::executeEvent('prePostCreate', [
                'alert_full' => ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag_info', 'replace' => '{{author}}', 'replace_with' => $user->getDisplayname()],
                'alert_short' => ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag'],
                'alert_url' => URL::build('/forum/topic/' . urlencode($tid), 'pid=' . urlencode($last_post_id)),
                'content' => $content,
                'user' => $user,
            ])['content'];

            DB::getInstance()->update('posts', $last_post_id, [
                'post_content' => $content
            ]);

            DB::getInstance()->update('forums', $topic->forum_id, [
                'last_topic_posted' => $tid,
                'last_user_posted' => $user->data()->id,
                'last_post_date' => date('U')
            ]);
            DB::getInstance()->update('topics', $tid, [
                'topic_last_user' => $user->data()->id,
                'topic_reply_date' => date('U')
            ]);

            // Execute hooks and pass $available_hooks
            // TODO: This gets hooks only for this specific forum, not any of its parents...
            $available_hooks = DB::getInstance()->get('forums', ['id', $topic->forum_id])->first();
            $available_hooks = json_decode($available_hooks->hooks) ?? [];
            EventHandler::executeEvent(new TopicReplyCreatedEvent(
                $user,
                $topic->topic_title,
                $content,
                $tid,
                $available_hooks,
            ));

            // Alerts + Emails
            $users_following = DB::getInstance()->get('topics_following', ['topic_id', $tid])->results();
            if (count($users_following)) {
                $users_following_info = [];
                foreach ($users_following as $user_following) {
                    if ($user_following->user_id != $user->data()->id) {
                        if ($user_following->existing_alerts == 0) {
                            Alert::create(
                                $user_following->user_id,
                                'new_reply',
                                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'new_reply_in_topic', 'replace' => ['{{author}}', '{{topic}}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->topic_title)]],
                                ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'new_reply_in_topic', 'replace' => ['{{author}}', '{{topic}}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->topic_title)]],
                                URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id)
                            );
                            DB::getInstance()->update('topics_following', $user_following->id, [
                                'existing_alerts' => 1
                            ]);
                        }
                        $user_info = DB::getInstance()->get('users', ['id', $user_following->user_id])->results();
                        if ($user_info[0]->topic_updates) {
                            $users_following_info[] = ['email' => $user_info[0]->email, 'username' => $user_info[0]->username];
                        }
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'forum_topic_reply.html']);
                $html = file_get_contents($path);

                $message = str_replace(
                    ['[Sitename]', '[TopicReply]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'],
                    [
                        Output::getClean(SITE_NAME),
                        $language->get('emails', 'forum_topic_reply_subject', ['author' => $user->data()->username, 'topic' => $topic->topic_title]),
                        $language->get('emails', 'greeting'),
                        $language->get('emails', 'forum_topic_reply_message', ['author' => $user->data()->username, 'content' => html_entity_decode($content)]),
                        rtrim(URL::getSelfURL(), '/') . URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id),
                        $language->get('emails', 'thanks')
                    ],
                    $html
                );
                $subject = Output::getClean(SITE_NAME) . ' - ' . $language->get('emails', 'forum_topic_reply_subject', ['author' => $user->data()->username, 'topic' => $topic->topic_title]);

                foreach ($users_following_info as $user_info) {
                    $sent = Email::send(
                        ['email' => $user_info['email'], 'name' => $user_info['username']],
                        $subject,
                        $message,
                    );

                    if (isset($sent['error'])) {
                        DB::getInstance()->insert('email_errors', [
                            'type' => Email::FORUM_TOPIC_REPLY,
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => ($user->data()->id)
                        ]);
                    }
                }
            }
            Session::flash('success_post', $forum_language->get('forum', 'post_successful'));
            Redirect::to(URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id));
        } else {
            $error = $validate->errors();
        }
    } else {
        $error = [$language->get('general', 'invalid_token')];
    }
}

// Generate a post token
if ($user->isLoggedIn()) {
    $token = Token::get();
}

// View count
if ($user->isLoggedIn() || (defined('COOKIE_CHECK') && COOKIES_ALLOWED)) {
    if (!Cookie::exists('nl-topic-' . $tid)) {
        DB::getInstance()->increment('topics', $tid, 'topic_views');
        Cookie::put('nl-topic-' . $tid, 'true', 3600);
    }
} else {
    if (!Session::exists('nl-topic-' . $tid)) {
        DB::getInstance()->increment('topics', $tid, 'topic_views');
        Session::put('nl-topic-' . $tid, 'true');
    }
}

if ($user->isLoggedIn()) {
    $template->addJSScript('var quotedPosts = [];');
}

// Are reactions enabled?
$reactions_enabled = Util::getSetting('forum_reactions') === '1';

// Assign Smarty variables to pass to template
$parent_category = DB::getInstance()->get('forums', ['id', $forum_parent[0]->parent])->results();

$breadcrumbs = [
    0 => [
        'id' => 0,
        'forum_title' => Output::getClean($topic->topic_title),
        'active' => 1,
        'link' => URL::build('/forum/topic/' . urlencode($topic->id) . '-' . $forum->titleToURL($topic->topic_title))
    ],
    1 => [
        'id' => $forum_parent[0]->id,
        'forum_title' => Output::getClean($forum_parent[0]->forum_title),
        'link' => URL::build('/forum/view/' . urlencode($forum_parent[0]->id) . '-' . $forum->titleToURL($forum_parent[0]->forum_title))
    ]
];
if (!empty($parent_category) && $parent_category[0]->parent == 0) {
    // Category
    $breadcrumbs[] = [
        'id' => $parent_category[0]->id,
        'forum_title' => Output::getClean($parent_category[0]->forum_title),
        'link' => URL::build('/forum/view/' . urlencode($parent_category[0]->id) . '-' . $forum->titleToURL($parent_category[0]->forum_title))
    ];
} else {
    if (!empty($parent_category)) {
        // Parent forum, get its category
        $breadcrumbs[] = [
            'id' => $parent_category[0]->id,
            'forum_title' => Output::getClean($parent_category[0]->forum_title),
            'link' => URL::build('/forum/view/' . urlencode($parent_category[0]->id) . '-' . $forum->titleToURL($parent_category[0]->forum_title))
        ];
        $parent = false;
        while ($parent == false) {
            $parent_category = DB::getInstance()->get('forums', ['id', $parent_category[0]->parent])->results();
            $breadcrumbs[] = [
                'id' => $parent_category[0]->id,
                'forum_title' => Output::getClean($parent_category[0]->forum_title),
                'link' => URL::build('/forum/view/' . urlencode($parent_category[0]->id) . '-' . $forum->titleToURL($parent_category[0]->forum_title))
            ];
            if ($parent_category[0]->parent == 0) {
                $parent = true;
            }
        }
    }
}

$breadcrumbs[] = [
    'id' => 'index',
    'forum_title' => $forum_language->get('forum', 'forum_index'),
    'link' => URL::build('/forum')
];

$smarty->assign('BREADCRUMBS', array_reverse($breadcrumbs));

// Display session messages
if (Session::exists('success_post')) {
    $smarty->assign('SESSION_SUCCESS_POST', Session::flash('success_post'));
}
if (Session::exists('failure_post')) {
    $smarty->assign('SESSION_FAILURE_POST', Session::flash('failure_post'));
}
if (isset($error) && count($error)) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $error
    ]);
}

// Display "new reply" button and "mod actions" if the user has access to them

// Can the user post a reply?
if ($user->isLoggedIn() && $can_reply) {
    $smarty->assign('CAN_REPLY', true);

    // Is the topic locked?
    if ($topic->locked != 1) { // Not locked
        $smarty->assign('NEW_REPLY', $forum_language->get('forum', 'new_reply'));
    } else { // Locked
        if ($forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
            // Can post anyway
            $smarty->assign('NEW_REPLY', $forum_language->get('forum', 'new_reply'));
        } else {
            // Can't post
            $smarty->assign('NEW_REPLY', $forum_language->get('forum', 'topic_locked'));
        }
    }
}

if ($topic->locked == 1) {
    $smarty->assign('LOCKED', true);
}

// Is the user a moderator?
if ($user->isLoggedIn() && $forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
    $smarty->assign([
        'CAN_MODERATE' => true,
        'MOD_ACTIONS' => $forum_language->get('forum', 'mod_actions'),
        'LOCK_URL' => URL::build('/forum/lock/', 'tid=' . urlencode($tid)),
        'LOCK' => (($topic->locked == 1) ? $forum_language->get('forum', 'unlock_topic') : $forum_language->get('forum', 'lock_topic')),
        'MERGE_URL' => URL::build('/forum/merge/', 'tid=' . urlencode($tid)),
        'MERGE' => $forum_language->get('forum', 'merge_topic'),
        'DELETE_URL' => URL::build('/forum/delete/', 'tid=' . urlencode($tid)),
        'CONFIRM_DELETE' => $forum_language->get('forum', 'confirm_delete_topic'),
        'CONFIRM_DELETE_SHORT' => $language->get('general', 'confirm_delete'),
        'CONFIRM_DELETE_POST' => $forum_language->get('forum', 'confirm_delete_post'),
        'DELETE' => $forum_language->get('forum', 'delete_topic'),
        'MOVE_URL' => URL::build('/forum/move/', 'tid=' . urlencode($tid)),
        'MOVE' => $forum_language->get('forum', 'move_topic'),
        'STICK_URL' => URL::build('/forum/stick/', 'tid=' . urlencode($tid)),
        'STICK' => (($topic->sticky == 1) ? $forum_language->get('forum', 'unstick_topic') : $forum_language->get('forum', 'stick_topic')),
        'MARK_AS_SPAM' => $language->get('moderator', 'mark_as_spam'),
        'CONFIRM_SPAM_POST' => $language->get('moderator', 'confirm_spam')
    ]);
}

// Sharing
$smarty->assign([
    'SHARE' => $forum_language->get('forum', 'share'),
    'SHARE_TWITTER' => $forum_language->get('forum', 'share_twitter'),
    'SHARE_TWITTER_URL' => 'https://twitter.com/intent/tweet?text=' . urlencode(rtrim(URL::getSelfURL(), '/')) . URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title)),
    'SHARE_FACEBOOK' => $forum_language->get('forum', 'share_facebook'),
    'SHARE_FACEBOOK_URL' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(rtrim(URL::getSelfURL(), '/')) . URL::build('/forum/topic/' . urlencode($tid) . '-' . $forum->titleToURL($topic->topic_title))
]);

// Pagination
$paginator = new Paginator(
    $template_pagination ?? null,
    $template_pagination_left ?? null,
    $template_pagination_right ?? null
);
$results = $paginator->getLimited($posts, 10, $p, count($posts));
$pagination = $paginator->generate(7, URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)));

$smarty->assign('PAGINATION', $pagination);

// Replies
$replies = [];
// Display the correct number of posts
foreach ($results->data as $n => $nValue) {
    $post_creator = new User($nValue->post_creator);
    if (!$post_creator->exists()) {
        continue;
    }

    // Get user's group HTML formatting and their signature
    $user_groups_html = $post_creator->getAllGroupHtml();
    $signature = $post_creator->getSignature();

    // Panel heading content
    $url = URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $nValue->id);

    if ($n != 0) {
        $heading = $forum_language->get('forum', 're') . Output::getClean($topic->topic_title);
    } else {
        $heading = Output::getClean($topic->topic_title);
    }

    // Which buttons do we need to display?
    $buttons = [];

    if ($user->isLoggedIn()) {
        // Assign token
        $smarty->assign('TOKEN', $token);

        // Edit button
        if ($forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
            $buttons['edit'] = [
                'URL' => URL::build('/forum/edit/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                'TEXT' => $forum_language->get('forum', 'edit')
            ];
        } else {
            if ($user->data()->id == $nValue->post_creator && $forum->canEditTopic($forum_parent[0]->id, $user_groups)) {
                if ($topic->locked != 1) { // Can't edit if topic is locked
                    $buttons['edit'] = [
                        'URL' => URL::build('/forum/edit/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                        'TEXT' => $forum_language->get('forum', 'edit')
                    ];
                }
            }
        }

        // Delete button
        if ($user->data()->id != $nValue->post_creator && $moderate = $forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
            $buttons['spam'] = [
                'URL' => URL::build('/forum/spam/'),
                'TEXT' => $language->get('moderator', 'spam')
            ];
        }
        if ($moderate || $user->data()->id == $nValue->post_creator) {
            $buttons['delete'] = [
                'URL' => URL::build('/forum/delete_post/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                'TEXT' => $language->get('general', 'delete'),
                'NUMBER' => $p . $n
            ];
        }

        if ($user->data()->id != $nValue->post_creator) {
            // Report button
            $buttons['report'] = [
                'URL' => URL::build('/forum/report/'),
                'REPORT_TEXT' => $language->get('user', 'report_post_content'),
                'TEXT' => $language->get('general', 'report')
            ];
        }

        // Quote button
        if ($can_reply) {
            if ($topic->locked != 1 || $forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
                $buttons['quote'] = [
                    'TEXT' => $forum_language->get('forum', 'quote')
                ];
            }
        }
    }

    // Profile fields
    $fields = $post_creator->getProfileFields(false, true);

    // User integrations
    $user_integrations = [];
    foreach ($post_creator->getIntegrations() as $key => $integrationUser) {
        if ($integrationUser->data()->username != null && $integrationUser->data()->show_publicly) {
            $fields[] = [
                'name' => Output::getClean($key),
                'value' => Output::getClean($integrationUser->data()->username)
            ];

            $user_integrations[$key] = [
                'username' => Output::getClean($integrationUser->data()->username),
                'identifier' => Output::getClean($integrationUser->data()->identifier)
            ];
        }
    }

    $forum_placeholders = $post_creator->getForumPlaceholders();
    foreach ($forum_placeholders as $forum_placeholder) {
        $fields[] = [
            'name' => $forum_placeholder->friendly_name,
            'value' => $forum_placeholder->value
        ];
    }

    // Get post reactions
    $post_reactions = [];
    $total_karma = 0;
    if ($reactions_enabled) {
        $post_reactions_query = DB::getInstance()->get('forums_reactions', ['post_id', $nValue->id])->results();

        if (count($post_reactions_query)) {
            foreach ($post_reactions_query as $item) {
                if (!isset($post_reactions[$item->reaction_id])) {
                    $post_reactions[$item->reaction_id]['count'] = 1;

                    $reaction = DB::getInstance()->get('reactions', ['id', $item->reaction_id])->results();
                    $post_reactions[$item->reaction_id]['html'] = $reaction[0]->html;
                    $post_reactions[$item->reaction_id]['name'] = $reaction[0]->name;

                    if ($reaction[0]->type == 2) {
                        $total_karma++;
                    } else {
                        if ($reaction[0]->type == 0) {
                            $total_karma--;
                        }
                    }
                } else {
                    $post_reactions[$item->reaction_id]['count']++;
                }

                $reaction_user = new User($item->user_given);
                $post_reactions[$item->reaction_id]['users'][] = [
                    'username' => $reaction_user->getDisplayname(true),
                    'nickname' => $reaction_user->getDisplayname(),
                    'style' => $reaction_user->getGroupStyle(),
                    'avatar' => $reaction_user->getAvatar(),
                    'profile' => $reaction_user->getProfileURL()
                ];
            }
        }
    }

    // Purify post content
    $content = EventHandler::executeEvent('renderPost', ['content' => $nValue->post_content])['content'];

    // Get post date
    if (is_null($nValue->created)) {
        $post_date_rough = $timeago->inWords($nValue->post_date, $language);
        $post_date = date(DATE_FORMAT, strtotime($nValue->post_date));
    } else {
        $post_date_rough = $timeago->inWords($nValue->created, $language);
        $post_date = date(DATE_FORMAT, $nValue->created);
    }

    $replies[] = [
        'url' => $url,
        'heading' => $heading,
        'id' => $nValue->id,
        'user_id' => $post_creator->data()->id,
        'avatar' => $post_creator->getAvatar(),
        'integrations' => $user_integrations,
        'username' => $post_creator->getDisplayname(),
        'mcname' => $post_creator->getDisplayname(true),
        'last_seen' => $language->get('user', 'last_seen_x', ['lastSeenAt' => $timeago->inWords($post_creator->data()->last_online, $language)]),
        'last_seen_full' => date('d M Y', $post_creator->data()->last_online),
        'online_now' => $post_creator->data()->last_online > strtotime('5 minutes ago'),
        'user_title' => Output::getClean($post_creator->data()->user_title),
        'profile' => $post_creator->getProfileURL(),
        'user_style' => $post_creator->getGroupStyle(),
        'user_groups' => $user_groups_html,
        'user_posts_count' => $forum_language->get('forum', 'x_posts', ['count' => $forum->getPostCount($nValue->post_creator)]),
        'user_topics_count' => $forum_language->get('forum', 'x_topics', ['count' => $forum->getTopicCount($nValue->post_creator)]),
        'user_registered' => $forum_language->get('forum', 'registered_x', ['registeredAt' => $timeago->inWords($post_creator->data()->joined, $language)]),
        'user_registered_full' => date('d M Y', $post_creator->data()->joined),
        'user_reputation' => $post_creator->data()->reputation,
        'post_date_rough' => $post_date_rough,
        'post_date' => $post_date,
        'buttons' => $buttons,
        'content' => $content,
        'signature' => Output::getPurified(Text::renderEmojis($signature)),
        'fields' => (empty($fields) ? [] : $fields),
        'edited' => is_null($nValue->last_edited)
            ? null
            : $forum_language->get('forum', 'last_edited', ['lastEditedAt' => $timeago->inWords($nValue->last_edited, $language)]),
        'edited_full' => (is_null($nValue->last_edited) ? null : date(DATE_FORMAT, $nValue->last_edited)),
        'post_reactions' => $post_reactions,
        'karma' => $total_karma
    ];
}

$smarty->assign('REPLIES', $replies);

if ($user->isLoggedIn()) {
    // Reactions
    if ($reactions_enabled) {
        $reactions = DB::getInstance()->get('reactions', ['enabled', true])->results();
        if (!count($reactions)) {
            $reactions = [];
        }

        $smarty->assign([
            'LIKE' => $language->get('user', 'like'),
            'REACTIONS' => $reactions,
            'REACTIONS_URL' => URL::build('/forum/reactions')
        ]);
    }

    // Following?
    $is_user_following = DB::getInstance()->query('SELECT id, existing_alerts FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);

    if ($is_user_following->count()) {
        $is_user_following = $is_user_following->first();

        if ($is_user_following->existing_alerts == 1) {
            DB::getInstance()->update('topics_following', $is_user_following->id, [
                'existing_alerts' => 0
            ]);
        }

        $smarty->assign([
            'UNFOLLOW' => $forum_language->get('forum', 'unfollow'),
            'UNFOLLOW_URL' => URL::build('/forum/topic/' . $tid . '/', 'action=unfollow')
        ]);
    } else {
        $smarty->assign([
            'FOLLOW' => $forum_language->get('forum', 'follow'),
            'FOLLOW_URL' => URL::build('/forum/topic/' . $tid . '/', 'action=follow')
        ]);
    }
}

$smarty->assign('REACTIONS_TEXT', $language->get('user', 'reactions'));

// Existing quick reply content
$content = null;

// Quick reply
if ($user->isLoggedIn() && $can_reply) {
    if ($forum->canModerateForum($forum_parent[0]->id, $user_groups) || $topic->locked != 1) {
        if ($topic->locked == 1) {
            $smarty->assign('TOPIC_LOCKED_NOTICE', $forum_language->get('forum', 'topic_locked_notice'));
        }

        if (isset($_POST['content'])) {
            // Purify post content
            $content = EventHandler::executeEvent('renderPostEdit', ['content' => $_POST['content']])['content'];
        }

        $smarty->assign([
            'SUBMIT' => $language->get('general', 'submit')
        ]);
    }
} else {
    if ($topic->locked == 1) {
        $smarty->assign('TOPIC_LOCKED', $forum_language->get('forum', 'topic_locked'));
    }
}

// Assign Smarty language variables
$smarty->assign([
    'POSTS' => $forum_language->get('forum', 'posts'),
    'BY' => ucfirst($forum_language->get('forum', 'by')),
    'CANCEL' => $language->get('general', 'cancel'),
    'USER_ID' => (($user->isLoggedIn()) ? $user->data()->id : 0),
    'INSERT_QUOTES' => $forum_language->get('forum', 'insert_quotes'),
    'FORUM_TITLE' => Output::getClean($forum_parent[0]->forum_title),
    'STARTED_BY' => $forum_language->get('forum', 'started_by_x', [
        'author' => '<a href="' . $topic_user->getProfileURL() . '" style="' . $topic_user->getGroupStyle() . '">' . $topic_user->getDisplayname() . '</a>',
    ]),
    'SUCCESS' => $language->get('general', 'success'),
    'ERROR' => $language->get('general', 'error')
]);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

if ($user->isLoggedIn()) {
    $template->addJSScript(Input::createTinyEditor($language, 'quickreply', $content, true));

    $template->addJSScript('
    function quote(post) {        
        $.ajax({
            type: "GET",
            url: "' . URL::build('/forum/get_quotes') . '",
            data: {
                "post": post,
            },
            dataType: "json",
            success: function(response) {
                tinymce.editors[0].execCommand(\'mceInsertContent\', false, \'<blockquote class="blockquote"><a href="\' + response.link + \'">\' + response.author_nickname + \':</a><br />\' + response.content + \'</blockquote><br />\');
            },
            error: function(data) {
                $(\'body\').toast({
                    showIcon: \'exclamation triangle icon\',
                    message: \'' . $forum_language->get('forum', 'error_quoting_posts') . '\',
                    class: \'danger\',
                    progressUp: true,
                    displayTime: 6000,
                    showProgress: \'bottom\',
                    pauseOnHover: false,
                    position: \'bottom left\',
                });
            }
         });

         $(\'body\').toast({
            showIcon: \'info circle icon\',
            message: \'' . $forum_language->get('forum', 'quoted_post') . '\',
            class: \'info\',
            progressUp: true,
            displayTime: 6000,
            showProgress: \'bottom\',
            pauseOnHover: false,
            position: \'bottom left\',
         });
    }
    ');
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/view_topic.tpl', $smarty);
