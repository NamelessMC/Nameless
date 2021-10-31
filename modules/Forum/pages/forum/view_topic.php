<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  View topic page
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');

// Set the page name for the active link in navbar
define('PAGE', 'forum');

$forum = new Forum();
$timeago = new TimeAgo(TIMEZONE);
$mentionsParser = new MentionsParser();

require(ROOT_PATH . '/core/includes/emojione/autoload.php'); // Emojione
require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

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
$topic = $queries->getWhere('topics', ['id', '=', $tid]);
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

if ($user->isLoggedIn())
    $user_id = $user->data()->id;
else
    $user_id = 0;

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
        die();
    } else {
        if ($_GET['p'] <= 1) {
            // Avoid bug in pagination class
            Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)));
            die();
        }
        $p = $_GET['p'];
    }
} else {
    $p = 1;
}

// Is the URL pointing to a specific post?
if (isset($_GET['pid'])) {
    $posts = DB::getInstance()->selectQuery('SELECT * FROM nl2_posts WHERE topic_id = ? AND deleted = 0', [$tid])->results();
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
            Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'p=' . ceil($output / 10)) . '#post-' . $_GET['pid']);
            die();
        } else {
            Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)) . '#post-' . $_GET['pid']);
            die();
        }
    } else {
        require_once(ROOT_PATH . '/404.php');
        die();
    }
}

// Follow/unfollow
if (isset($_GET['action'])) {
    if ($user->isLoggedIn()) {
        if (Token::check($_POST['token'])) {
            switch ($_GET['action']) {
                case 'follow':
                    $already_following = DB::getInstance()->selectQuery('SELECT id FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);
                    if (!$already_following->count()) {
                        $queries->create('topics_following', [
                            'topic_id' => $tid,
                            'user_id' => $user->data()->id,
                            'existing_alerts' => 0
                        ]);
                        Session::flash('success_post', $forum_language->get('forum', 'now_following_topic'));
                    }
                    break;
                case 'unfollow':
                    $delete = DB::getInstance()->createQuery('DELETE FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);
                    Session::flash('success_post', $forum_language->get('forum', 'no_longer_following_topic'));
                    if (isset($_GET['return']) && $_GET['return'] == 'list') {
                        Redirect::to(URL::build('/user/following_topics'));
                        die();
                    }
                    break;
            }
        } else Session::flash('failure_post', $language->get('general', 'invalid_token'));
    }

    Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)));
    die();
}

$forum_parent = $queries->getWhere('forums', ['id', '=', $topic->forum_id]);

$page_metadata = $queries->getWhere('page_descriptions', ['page', '=', '/forum/topic']);
if (count($page_metadata)) {
    $first_post = $queries->orderWhere('posts', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1');
    $first_post = strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], Output::getDecoded($first_post[0]->post_content)));

    define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{forum_title}', '{page}', '{post}'], [SITE_NAME, Output::getClean($topic->topic_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($forum_parent[0]->forum_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
    define('PAGE_KEYWORDS', $page_metadata[0]->tags);
} else {
    $page_metadata = $queries->getWhere('page_descriptions', ['page', '=', '/forum/view_topic']);

    if (count($page_metadata)) {
        $first_post = $queries->orderWhere('posts', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1');
        $first_post = strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], Output::getDecoded($first_post[0]->post_content)));

        define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{forum_title}', '{page}', '{post}'], [SITE_NAME, Output::getClean($topic->topic_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($forum_parent[0]->forum_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
        define('PAGE_KEYWORDS', $page_metadata[0]->tags);
    }
}

$page_title = ((strlen(Output::getClean($topic->topic_title)) > 20) ? Output::getClean(mb_substr($topic->topic_title, 0, 20)) . '...' : Output::getClean($topic->topic_title)) . ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Assign author + title to Smarty variables
// Get first post
$first_post = DB::getInstance()->selectQuery('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY id ASC LIMIT 1', [$tid])->first();

$topic_user = new User($topic->topic_creator);

$smarty->assign([
    'TOPIC_TITLE' => Output::getClean($topic->topic_title),
    'TOPIC_AUTHOR_USERNAME' => $topic_user->getDisplayname(),
    'TOPIC_AUTHOR_MCNAME' => $topic_user->getDisplayname(true),
    'TOPIC_AUTHOR_PROFILE' => $topic_user->getProfileURL(),
    'TOPIC_AUTHOR_STYLE' => $topic_user->getGroupClass(),
    'TOPIC_ID' => $topic->id,
    'FORUM_ID' => $topic->forum_id,
    'TOPIC_LAST_EDITED' => ($first_post->last_edited ? $timeago->inWords(date('d M Y, H:i', $first_post->last_edited), $language->getTimeLanguage()) : null),
    'TOPIC_LAST_EDITED_FULL' => ($first_post->last_edited ? date('d M Y, H:i', $first_post->last_edited) : null)
]);

// Is there a label?
if ($topic->label != 0) { // yes
    // Get label
    $label = $queries->getWhere('forums_topic_labels', ['id', '=', $topic->label]);
    if (count($label)) {
        $label = $label[0];

        $label_html = $queries->getWhere('forums_labels', ['id', '=', $label->label]);
        if (count($label_html)) {
            $label_html = Output::getPurified($label_html[0]->html);
            $label = str_replace('{x}', Output::getClean($label->name), $label_html);
        } else $label = '';
    } else $label = '';
} else { // no
    $label = '';
}

$labels = [];
if ($topic->labels) {
    // Get labels
    $topic_labels = explode(',', $topic->labels);

    foreach ($topic_labels as $topic_label) {
        $label_query = $queries->getWhere('forums_topic_labels', ['id', '=', $topic_label]);
        if (count($label_query)) {
            $label_query = $label_query[0];

            $label_html = $queries->getWhere('forums_labels', ['id', '=', $label_query->label]);
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
        die();
    }
    if (Token::check()) {
        $validate = new Validate();

        $validate->check($_POST, [
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
            try {
                $cache->setCache('post_formatting');
                $formatting = $cache->retrieve('formatting');

                if ($formatting == 'markdown') {
                    $content = Michelf\Markdown::defaultTransform(Input::get('content'));
                    $content = Output::getClean($content);
                } else $content = Output::getClean(Input::get('content'));

                $queries->create('posts', [
                    'forum_id' => $topic->forum_id,
                    'topic_id' => $tid,
                    'post_creator' => $user->data()->id,
                    'post_content' => $content,
                    'post_date' => date('Y-m-d H:i:s'),
                    'created' => date('U')
                ]);

                // Get last post ID
                $last_post_id = $queries->getLastId();
                $content = $mentionsParser->parse($user->data()->id, $content, URL::build('/forum/topic/' . $tid, 'pid=' . $last_post_id), ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag'], ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag_info', 'replace' => '{x}', 'replace_with' => Output::getClean($user->data()->nickname)]);

                $queries->update('posts', $last_post_id, [
                    'post_content' => $content
                ]);

                $queries->update('forums', $topic->forum_id, [
                    'last_topic_posted' => $tid,
                    'last_user_posted' => $user->data()->id,
                    'last_post_date' => date('U')
                ]);
                $queries->update('topics', $tid, [
                    'topic_last_user' => $user->data()->id,
                    'topic_reply_date' => date('U')
                ]);

                // Alerts + Emails
                $users_following = $queries->getWhere('topics_following', ['topic_id', '=', $tid]);
                if (count($users_following)) {
                    $users_following_info = [];
                    foreach ($users_following as $user_following) {
                        if ($user_following->user_id != $user->data()->id) {
                            if ($user_following->existing_alerts == 0) {
                                Alert::create(
                                    $user_following->user_id,
                                    'new_reply',
                                    ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'new_reply_in_topic', 'replace' => ['{x}', '{y}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->topic_title)]],
                                    ['path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'new_reply_in_topic', 'replace' => ['{x}', '{y}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->topic_title)]],
                                    URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id)
                                );
                                $queries->update('topics_following', $user_following->id, [
                                    'existing_alerts' => 1
                                ]);
                            }
                            $user_info = $queries->getWhere('users', ['id', '=', $user_following->user_id]);
                            if ($user_info[0]->topic_updates) array_push($users_following_info, ['email' => $user_info[0]->email, 'username' => $user_info[0]->username]);
                        }
                    }
                    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'forum_topic_reply.html']);
                    $html = file_get_contents($path);

                    // TODO: Add placeholder support for Email::formatEmail()
                    $message = str_replace(
                        ['[Sitename]', '[TopicReply]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'],
                        [
                            SITE_NAME,
                            str_replace(['{x}', '{y}'], [$user->data()->username, $topic->topic_title], $language->get('emails', 'forum_topic_reply_subject')),
                            $language->get('emails', 'greeting'),
                            str_replace(['{x}', '{z}'], [$user->data()->username, html_entity_decode($content)], $language->get('emails', 'forum_topic_reply_message')),
                            rtrim(Util::getSelfURL(), '/') . URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id),
                            $language->get('emails', 'thanks')
                        ],
                        $html
                    );
                    $subject = SITE_NAME . ' - ' . str_replace(['{x}', '{y}'], [$user->data()->username, $topic->topic_title], $language->get('emails', 'forum_topic_reply_subject'));
                    $siteemail = $queries->getWhere('settings', ['name', '=', 'outgoing_email']);
                    $siteemail = $siteemail[0]->value;
                    $contactemail = $queries->getWhere('settings', ['name', '=', 'incoming_email']);
                    $contactemail = $contactemail[0]->value;
                    try {
                        $php_mailer = $queries->getWhere('settings', ['name', '=', 'phpmailer']);
                        $php_mailer = $php_mailer[0]->value;
                        if ($php_mailer == '1') {
                            foreach ($users_following_info as $user_info) {
                                // PHP Mailer
                                $email = [
                                    'replyto' => ['email' => $contactemail, 'name' => Output::getClean(SITE_NAME)],
                                    'to' => ['email' => Output::getClean($user_info['email']), 'name' => Output::getClean($user_info['username'])],
                                    'subject' => $subject,
                                    'message' => $message
                                ];
                                $sent = Email::send($email, 'mailer');

                                if (isset($sent['error'])) {
                                    // Error, log it
                                    $queries->create('email_errors', [
                                        'type' => 5, // 5 = forum topic reply
                                        'content' => $sent['error'],
                                        'at' => date('U'),
                                        'user_id' => ($user->data()->id)
                                    ]);
                                }
                            }
                        } else {
                            foreach ($users_following_info as $user_info) {
                                // PHP mail function
                                $headers = 'From: ' . $siteemail . "\r\n" .
                                    'Reply-To: ' . $contactemail . "\r\n" .
                                    'X-Mailer: PHP/' . phpversion() . "\r\n" .
                                    'MIME-Version: 1.0' . "\r\n" .
                                    'Content-type: text/html; charset=UTF-8' . "\r\n";

                                $email = [
                                    'to' => $user_info['email'],
                                    'subject' => $subject,
                                    'message' => $message,
                                    'headers' => $headers
                                ];

                                $sent = Email::send($email, 'php');

                                if (isset($sent['error'])) {
                                    // Error, log it
                                    $queries->create('email_errors', [
                                        'type' => 5, // 5 = forum topic reply
                                        'content' => $sent['error'],
                                        'at' => date('U'),
                                        'user_id' => ($user->data()->id)
                                    ]);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error
                        $error = $e->getMessage();
                    }
                }
                Session::flash('success_post', $forum_language->get('forum', 'post_successful'));
                Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id));
                die();
            } catch (Exception $e) {
                die($e->getMessage());
            }
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
        $queries->increment('topics', $tid, 'topic_views');
        Cookie::put('nl-topic-' . $tid, 'true', 3600);
    }
} else {
    if (!Session::exists('nl-topic-' . $tid)) {
        $queries->increment('topics', $tid, 'topic_views');
        Session::put('nl-topic-' . $tid, 'true');
    }
}

$template->addCSSFiles([
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => [],
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => []
]);

if ($user->isLoggedIn())
    $template->addJSScript('var quotedPosts = [];');

// Are reactions enabled?
$reactions_enabled = $configuration->get('Core', 'forum_reactions');
if ($reactions_enabled == '1')
    $reactions_enabled = true;
else
    $reactions_enabled = false;

// Assign Smarty variables to pass to template
$parent_category = $queries->getWhere('forums', ['id', '=', $forum_parent[0]->parent]);

$breadcrumbs = [
    0 => [
        'id' => 0,
        'forum_title' => Output::getClean($topic->topic_title),
        'active' => 1,
        'link' => URL::build('/forum/topic/' . $topic->id . '-' . $forum->titleToURL($topic->topic_title))
    ],
    1 => [
        'id' => $forum_parent[0]->id,
        'forum_title' => Output::getClean($forum_parent[0]->forum_title),
        'link' => URL::build('/forum/view/' . $forum_parent[0]->id . '-' . $forum->titleToURL($forum_parent[0]->forum_title))
    ]
];
if (!empty($parent_category) && $parent_category[0]->parent == 0) {
    // Category
    $breadcrumbs[] = [
        'id' => $parent_category[0]->id,
        'forum_title' => Output::getClean($parent_category[0]->forum_title),
        'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
    ];
} else if (!empty($parent_category)) {
    // Parent forum, get its category
    $breadcrumbs[] = [
        'id' => $parent_category[0]->id,
        'forum_title' => Output::getClean($parent_category[0]->forum_title),
        'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
    ];
    $parent = false;
    while ($parent == false) {
        $parent_category = $queries->getWhere('forums', ['id', '=', $parent_category[0]->parent]);
        $breadcrumbs[] = [
            'id' => $parent_category[0]->id,
            'forum_title' => Output::getClean($parent_category[0]->forum_title),
            'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
        ];
        if ($parent_category[0]->parent == 0) {
            $parent = true;
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

if ($topic->locked == 1)
    $smarty->assign('LOCKED', true);

// Is the user a moderator?
if ($user->isLoggedIn() && $forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
    $smarty->assign([
        'CAN_MODERATE' => true,
        'MOD_ACTIONS' => $forum_language->get('forum', 'mod_actions'),
        'LOCK_URL' => URL::build('/forum/lock/', 'tid=' . $tid),
        'LOCK' => (($topic->locked == 1) ? $forum_language->get('forum', 'unlock_topic') : $forum_language->get('forum', 'lock_topic')),
        'MERGE_URL' => URL::build('/forum/merge/', 'tid=' . $tid),
        'MERGE' => $forum_language->get('forum', 'merge_topic'),
        'DELETE_URL' => URL::build('/forum/delete/', 'tid=' . $tid),
        'CONFIRM_DELETE' => $forum_language->get('forum', 'confirm_delete_topic'),
        'CONFIRM_DELETE_SHORT' => $language->get('general', 'confirm_delete'),
        'CONFIRM_DELETE_POST' => $forum_language->get('forum', 'confirm_delete_post'),
        'DELETE' => $forum_language->get('forum', 'delete_topic'),
        'MOVE_URL' => URL::build('/forum/move/', 'tid=' . $tid),
        'MOVE' => $forum_language->get('forum', 'move_topic'),
        'STICK_URL' => URL::build('/forum/stick/', 'tid=' . $tid),
        'STICK' => (($topic->sticky == 1) ? $forum_language->get('forum', 'unstick_topic') : $forum_language->get('forum', 'stick_topic')),
        'MARK_AS_SPAM' => $language->get('moderator', 'mark_as_spam'),
        'CONFIRM_SPAM_POST' => $language->get('moderator', 'confirm_spam')
    ]);
}

// Sharing
$smarty->assign([
    'SHARE' => $forum_language->get('forum', 'share'),
    'SHARE_TWITTER' => $forum_language->get('forum', 'share_twitter'),
    'SHARE_TWITTER_URL' => 'https://twitter.com/intent/tweet?text=' . Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)),
    'SHARE_FACEBOOK' => $forum_language->get('forum', 'share_facebook'),
    'SHARE_FACEBOOK_URL' => 'https://www.facebook.com/sharer/sharer.php?u=' . Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title))
]);

// Pagination
$paginator = new Paginator(($template_pagination ?? []));
$results = $paginator->getLimited($posts, 10, $p, count($posts));
$pagination = $paginator->generate(7, URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), true));

$smarty->assign('PAGINATION', $pagination);

// Is Minecraft integration enabled?
$mc_integration = $queries->getWhere('settings', ['name', '=', 'mc_integration']);

// Replies
$replies = [];
// Display the correct number of posts
for ($n = 0; $n < count($results->data); $n++) {
    $post_creator = new User($results->data[$n]->post_creator);
    if(!$post_creator->data()) {
        continue;
    }

    // Get user's group HTML formatting and their signature
    $user_groups_html = $post_creator->getAllGroupHtml();
    $signature = $post_creator->getSignature();

    // Panel heading content
    $url = URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $results->data[$n]->id);

    if ($n != 0) $heading = $forum_language->get('forum', 're') . Output::getClean($topic->topic_title);
    else $heading = Output::getClean($topic->topic_title);

    // Which buttons do we need to display?
    $buttons = [];

    if ($user->isLoggedIn()) {
        // Assign token
        $smarty->assign('TOKEN', $token);

        // Edit button
        if ($forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
            $buttons['edit'] = [
                'URL' => URL::build('/forum/edit/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
                'TEXT' => $forum_language->get('forum', 'edit')
            ];
        } else if ($user->data()->id == $results->data[$n]->post_creator && $forum->canEditTopic($forum_parent[0]->id, $user_groups)) {
            if ($topic->locked != 1) { // Can't edit if topic is locked
                $buttons['edit'] = [
                    'URL' => URL::build('/forum/edit/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
                    'TEXT' => $forum_language->get('forum', 'edit')
                ];
            }
        }

        // Delete button
        if ($forum->canModerateForum($forum_parent[0]->id, $user_groups)) {
            $buttons['delete'] = [
                'URL' => URL::build('/forum/delete_post/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
                'TEXT' => $language->get('general', 'delete'),
                'NUMBER' => $p . $n
            ];
            $buttons['spam'] = [
                'URL' => URL::build('/forum/spam/'),
                'TEXT' => $language->get('moderator', 'spam')
            ];
        }

        // Report button
        $buttons['report'] = [
            'URL' => URL::build('/forum/report/'),
            'REPORT_TEXT' => $language->get('user', 'report_post_content'),
            'TEXT' => $language->get('general', 'report')
        ];

        // Quote button
        if ($can_reply) {
            if ($forum->canModerateForum($forum_parent[0]->id, $user_groups) || $topic->locked != 1) {
                $buttons['quote'] = [
                    'TEXT' => $forum_language->get('forum', 'quote')
                ];
            }
        }
    }

    // Profile fields
    $fields = $post_creator->getProfileFields($post_creator->data()->id, true, true);

    // TODO: Add setting to hide/show this
    if (Util::isModuleEnabled('Discord Integration') && Discord::isBotSetup()) {
        if ($post_creator->data()->discord_username != null) {
            $fields[] = ['name' => Discord::getLanguageTerm('discord'), 'value' => $post_creator->data()->discord_username];
        }
    }

    if ($mc_integration[0]->value == '1') $fields[] = ['name' => 'IGN', 'value' => $post_creator->getDisplayname(true)];

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
        $post_reactions_query = $queries->getWhere('forums_reactions', ['post_id', '=', $results->data[$n]->id]);

        if (count($post_reactions_query)) {
            foreach ($post_reactions_query as $item) {
                if (!isset($post_reactions[$item->reaction_id])) {
                    $post_reactions[$item->reaction_id]['count'] = 1;

                    $reaction = $queries->getWhere('reactions', ['id', '=', $item->reaction_id]);
                    $post_reactions[$item->reaction_id]['html'] = $reaction[0]->html;
                    $post_reactions[$item->reaction_id]['name'] = $reaction[0]->name;

                    if ($reaction[0]->type == 2) $total_karma++;
                    else if ($reaction[0]->type == 0) $total_karma--;
                } else {
                    $post_reactions[$item->reaction_id]['count']++;
                }

                $reaction_user = new User($item->user_given);
                $post_reactions[$item->reaction_id]['users'][] = [
                    'username' => $reaction_user->getDisplayname(true),
                    'nickname' => $reaction_user->getDisplayname(),
                    'style' => $reaction_user->getGroupClass(),
                    'avatar' => $reaction_user->getAvatar(500),
                    'profile' => $reaction_user->getProfileURL()
                ];
            }
        }
    }

    // Purify post content
    $content = Util::replaceAnchorsWithText(Output::getDecoded($results->data[$n]->post_content));
    $content = $emojione->unicodeToImage($content);
    $content = Output::getPurified($content, true);

    // Get post date
    if (is_null($results->data[$n]->created)) {
        $post_date_rough = $timeago->inWords($results->data[$n]->post_date, $language->getTimeLanguage());
        $post_date = date('d M Y, H:i', strtotime($results->data[$n]->post_date));
    } else {
        $post_date_rough = $timeago->inWords(date('d M Y, H:i', $results->data[$n]->created), $language->getTimeLanguage());
        $post_date = date('d M Y, H:i', $results->data[$n]->created);
    }

    $replies[] = [
        'url' => $url,
        'heading' => $heading,
        'id' => $results->data[$n]->id,
        'user_id' => $post_creator->data()->id,
        'avatar' => $post_creator->getAvatar(500),
        'uuid' => Output::getClean($post_creator->data()->uuid),
        'username' => $post_creator->getDisplayname(),
        'mcname' => $post_creator->getDisplayname(true),
        'last_seen' => str_replace('{x}', $timeago->inWords(date('Y-m-d H:i:s', $post_creator->data()->last_online), $language->getTimeLanguage()), $language->get('user', 'last_seen_x')),
        'last_seen_full' => date('d M Y', $post_creator->data()->last_online),
        'online_now' => $post_creator->data()->last_online > strtotime('5 minutes ago'),
        'user_title' => Output::getClean($post_creator->data()->user_title),
        'profile' => $post_creator->getProfileURL(),
        'user_style' => $post_creator->getGroupClass(),
        'user_groups' => $user_groups_html,
        'user_posts_count' => str_replace('{x}', count($queries->getWhere('posts', ['post_creator', '=', $results->data[$n]->post_creator])), $forum_language->get('forum', 'x_posts')),
        'user_topics_count' => str_replace('{x}', count($queries->getWhere('topics', ['topic_creator', '=', $results->data[$n]->post_creator])), $forum_language->get('forum', 'x_topics')),
        'user_registered' => str_replace('{x}', $timeago->inWords(date('Y-m-d H:i:s', $post_creator->data()->joined), $language->getTimeLanguage()), $forum_language->get('forum', 'registered_x')),
        'user_registered_full' => date('d M Y', $post_creator->data()->joined),
        'user_reputation' => $post_creator->data()->reputation,
        'post_date_rough' => $post_date_rough,
        'post_date' => $post_date,
        'buttons' => $buttons,
        'content' => $content,
        'signature' => Output::getPurified(htmlspecialchars_decode($signature)),
        'fields' => (empty($fields) ? [] : $fields),
        'edited' => (is_null($results->data[$n]->last_edited) ? null : str_replace('{x}', $timeago->inWords(date('Y-m-d H:i:s', $results->data[$n]->last_edited), $language->getTimeLanguage()), $forum_language->get('forum', 'last_edited'))),
        'edited_full' => (is_null($results->data[$n]->last_edited) ? null : date('d M Y, H:i', $results->data[$n]->last_edited)),
        'post_reactions' => $post_reactions,
        'karma' => $total_karma
    ];
}

$smarty->assign('REPLIES', $replies);

if ($user->isLoggedIn()) {
    // Reactions
    if ($reactions_enabled) {
        $reactions = $queries->getWhere('reactions', ['enabled', '=', 1]);
        if (!count($reactions)) $reactions = [];

        $smarty->assign('REACTIONS', $reactions);
        $smarty->assign('REACTIONS_URL', URL::build('/forum/reactions'));
    }

    // Following?
    $is_user_following = DB::getInstance()->selectQuery('SELECT id, existing_alerts FROM nl2_topics_following WHERE topic_id = ? AND user_id = ?', [$tid, $user->data()->id]);

    if ($is_user_following->count()) {
        $is_user_following = $is_user_following->first();

        if ($is_user_following->existing_alerts == 1) {
            $queries->update('topics_following', $is_user_following->id, [
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

// Quick reply
if ($user->isLoggedIn() && $can_reply) {
    if ($forum->canModerateForum($forum_parent[0]->id, $user_groups) || $topic->locked != 1) {
        if ($topic->locked == 1) {
            $smarty->assign('TOPIC_LOCKED_NOTICE', $forum_language->get('forum', 'topic_locked_notice'));
        }

        $smarty->assign([
            'CONTENT' => Output::getClean(Input::get('content')),
            'SUBMIT' => $language->get('general', 'submit')
        ]);
    }
}

// Assign Smarty language variables
$smarty->assign([
    'POSTS' => $forum_language->get('forum', 'posts'),
    'BY' => ucfirst($forum_language->get('forum', 'by')),
    'CANCEL' => $language->get('general', 'cancel'),
    'USER_ID' => (($user->isLoggedIn()) ? $user->data()->id  : 0),
    'INSERT_QUOTES' => $forum_language->get('forum', 'insert_quotes'),
    'FORUM_TITLE' => Output::getClean($forum_parent[0]->forum_title),
    'STARTED_BY' => $forum_language->get('forum', 'started_by_x'),
    'SUCCESS' => $language->get('general', 'success'),
    'ERROR' => $language->get('general', 'error')
]);

// Get post formatting type (HTML or Markdown)
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    // Markdown
    $smarty->assign('MARKDOWN', true);
    $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));

    $template->addJSFiles([
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => []
    ]);

    $template->addJSScript('
	  $(document).ready(function() {
		var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});
	  });
	');
} else {
    $template->addJSFiles([
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => []
    ]);

    if ($user->isLoggedIn())
        $template->addJSScript(Input::createTinyEditor($language, 'quickreply'));
}

if ($user->isLoggedIn()) {
    if ($formatting == 'markdown') {
        $js = '
		var el = $("#markdown").emojioneArea();
		el[0].emojioneArea.setText($(\'#markdown\').val() + "\n> [" + resultData[item].author_nickname + "](" + resultData[item].link + ")\n");
		';
    } else {
        $js = '
		tinymce.editors[0].execCommand(\'mceInsertContent\', false, \'<blockquote class="blockquote"><a href="\' + resultData[item].link + \'">\' + resultData[item].author_nickname + \':</a><br />\' + resultData[item].content + \'</blockquote><br />\');
		';
    }

    $template->addJSScript('
	$(document).ready(function() {
		if(typeof $.cookie(\'' .  $tid . '-quoted\') === \'undefined\'){
			$("#quoteButton").hide();
		}
	});

	// Add post to quoted posts array
	function quote(post){
		var index = quotedPosts.indexOf(post);

		if(index > -1){
			quotedPosts.splice(index, 1);

			toastr.options.onclick = function () {};
			toastr.options.progressBar = true;
			toastr.options.closeButton = true;
			toastr.options.positionClass = \'toast-bottom-left\';
			toastr.info(\'' .  $forum_language->get('forum', 'removed_quoted_post') . '\');
		}
		else {
			quotedPosts.push(post);

			toastr.options.onclick = function () {};
			toastr.options.progressBar = true;
			toastr.options.closeButton = true;
			toastr.options.positionClass = \'toast-bottom-left\';
			toastr.info(\'' . $forum_language->get('forum', 'quoted_post') . '\');
		}

		if(quotedPosts.length == 0){
			// Delete cookie
			$.removeCookie(\'' . $tid . '-quoted\');

			// Hide insert quote button
			$("#quoteButton").hide();
		} else {
			// Create cookie
			$.cookie(\'' . $tid . '-quoted\', JSON.stringify(quotedPosts));

			// Show insert quote button
			$("#quoteButton").show();
		}
	}

	// Insert quoted posts to editor
	function insertQuotes(){
		var postData = {
			"posts": JSON.parse($.cookie(\'' .  $tid . '-quoted\')),
			"topic": ' . $tid . '
		};

		toastr.options.onclick = function () {};
		toastr.options.progressBar = true;
		toastr.options.closeButton = true;
		toastr.options.positionClass = \'toast-bottom-left\';
		toastr.info(\'' . $forum_language->get('forum', 'quoting_posts') . '\');

		var getQuotes = $.ajax({
			  type: "POST",
			  url: "' . URL::build('/forum/get_quotes') . '",
			  data: postData,
			  dataType: "json",
			  success: function(resultData){
				  for(var item in resultData){
					  if(resultData.hasOwnProperty(item)){
					  ' . $js . '
					  }
				  }

				  // Remove cookie containing quoted posts, and hide quote button
				  $.removeCookie(\'' . $tid . '-quoted\');
				  $("#quoteButton").hide();
			  },
			  error: function(data){
				  toastr.options.onclick = function () {};
				  toastr.options.progressBar = true;
				  toastr.options.closeButton = true;
				  toastr.options.positionClass = \'toast-bottom-left\';
				  toastr.error(\'' . $forum_language->get('forum', 'error_quoting_posts') . '\');
			  }
		});
	}
	');
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/view_topic.tpl', $smarty);
