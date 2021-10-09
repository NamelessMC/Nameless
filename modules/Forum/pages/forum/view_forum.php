<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  View forum page
 */

// Always define page name
define('PAGE', 'forum');

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();
$timeago = new Timeago(TIMEZONE);

// Get forum ID
$fid = explode('/', $route);
$fid = $fid[count($fid) - 1];

if (!strlen($fid)) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

$fid = explode('-', $fid);
if (!is_numeric($fid[0])) {
    require_once(ROOT_PATH . '/404.php');
    die();
}
$fid = Output::getClean($fid[0]);

// Get user group ID
$user_groups = $user->getAllGroupIds();

// Does the forum exist, and can the user view it?
$list = $forum->canViewForum($fid, $user_groups);
if (!$list) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

// Get data from the database
$forum_query = $queries->getWhere('forums', array('id', '=', $fid));
$forum_query = $forum_query[0];

// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/forum'));
        die();
    } else {
        if ($_GET['p'] == 1) {
            // Avoid bug in pagination class
            Redirect::to(URL::build('/forum/view/' . $fid . '-' .  $forum->titleToURL($forum_query->forum_title)));
            die();
        }
        $p = $_GET['p'];
    }
} else {
    $p = 1;
}

$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', '/forum/view'));
if (count($page_metadata)) {
    define('PAGE_DESCRIPTION', str_replace(array('{site}', '{forum_title}', '{page}', '{description}'), array(SITE_NAME, Output::getClean($forum_query->forum_title), Output::getClean($p), Output::getClean(strip_tags(Output::getDecoded($forum_query->forum_description)))), $page_metadata[0]->description));
    define('PAGE_KEYWORDS', $page_metadata[0]->tags);
}

$page_title = $forum_language->get('forum', 'forum');
if (isset($p)) $page_title .= ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Redirect forum?
if ($forum_query->redirect_forum == 1) {
    if (!Util::isExternalURL($forum_query->redirect_url)) {
        Redirect::to(Output::getClean(Output::getDecoded($forum_query->redirect_url)));
        die();
    }

    $smarty->assign(array(
        'CONFIRM_REDIRECT' => str_replace('{x}', $forum_query->redirect_url, $forum_language->get('forum', 'forum_redirect_warning')),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'REDIRECT_URL' => Output::getClean(htmlspecialchars_decode($forum_query->redirect_url)),
        'FORUM_INDEX' => URL::build('/forum')
    ));

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
    $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forum/view_forum_confirm_redirect.tpl', $smarty);
} else {
    // Get all topics
    if ($user->isLoggedIn())
        $user_id = $user->data()->id;
    else
        $user_id = 0;

    if ($forum->canViewOtherTopics($fid, $user_groups))
        $topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND sticky = 0 AND deleted = 0 ORDER BY topic_reply_date DESC', array($fid))->results();
    else
        $topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND sticky = 0 AND deleted = 0 AND topic_creator = ? ORDER BY topic_reply_date DESC', array($fid, $user_id))->results();

    // Get sticky topics
    $stickies = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND sticky = 1 AND deleted = 0 ORDER BY topic_reply_date DESC', array($fid))->results();

    // Search bar
    $smarty->assign(array(
        'SEARCH_URL' => URL::build('/forum/search'),
        'SEARCH' => $language->get('general', 'search'),
        'TOKEN' => Token::get()
    ));

    // Breadcrumbs and search bar - same for latest discussions view + table view
    $parent_category = $queries->getWhere('forums', array('id', '=', $forum_query->parent));
    $breadcrumbs = array(0 => array(
        'id' => $forum_query->id,
        'forum_title' => Output::getClean($forum_query->forum_title),
        'active' => 1,
        'link' => URL::build('/forum/view/' . $forum_query->id . '-' . $forum->titleToURL($forum_query->forum_title))
    ));
    if (!empty($parent_category) && $parent_category[0]->parent == 0) {
        // Category
        $breadcrumbs[] = array(
            'id' => $parent_category[0]->id,
            'forum_title' => Output::getClean($parent_category[0]->forum_title),
            'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
        );
    } else if (!empty($parent_category)) {
        // Parent forum, get its category
        $breadcrumbs[] = array(
            'id' => $parent_category[0]->id,
            'forum_title' => Output::getClean($parent_category[0]->forum_title),
            'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
        );
        $parent = false;
        while ($parent == false) {
            $parent_category = $queries->getWhere('forums', array('id', '=', $parent_category[0]->parent));
            $breadcrumbs[] = array(
                'id' => $parent_category[0]->id,
                'forum_title' => Output::getClean($parent_category[0]->forum_title),
                'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
            );
            if ($parent_category[0]->parent == 0) {
                $parent = true;
            }
        }
    }

    $breadcrumbs[] = array(
        'id' => 'index',
        'forum_title' => $forum_language->get('forum', 'forum_index'),
        'link' => URL::build('/forum')
    );

    $smarty->assign('BREADCRUMBS', array_reverse($breadcrumbs));

    // Server status module
    if (isset($status_enabled->value) && $status_enabled->value == 'true') {
        // Todo
        $smarty->assign('SERVER_STATUS', '');
    } else {
        // Module disabled, assign empty values
        $smarty->assign('SERVER_STATUS', '');
    }

    // Assignments
    $smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));

    // Any subforums?
    $subforums = DB::getInstance()->query('SELECT * FROM nl2_forums WHERE parent = ? ORDER BY forum_order ASC', array($forum_query->id))->results();

    $subforum_array = array();

    if (count($subforums)) {
        // append subforums to string
        foreach ($subforums as $subforum) {
            // Get number of topics
            if ($forum->forumExist($subforum->id, $user_groups)) {
                if ($forum->canViewOtherTopics($subforum->id, $user_groups))
                    $latest_post = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND deleted = 0 ORDER BY topic_reply_date DESC', array($subforum->id))->results();
                else
                    $latest_post = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE forum_id = ? AND deleted = 0 AND topic_creator = ? ORDER BY topic_reply_date DESC', array($subforum->id, $user_id))->results();

                $subforum_topics = count($latest_post);
                if (count($latest_post)) {
                    foreach ($latest_post as $item) {
                        if ($item->deleted == 0) {
                            $latest_post = $item;
                            break;
                        }
                    }

                    $latest_post_user = new User($latest_post->topic_last_user);
                    $latest_post_link = URL::build('/forum/topic/' . $latest_post->id . '-' . $forum->titleToURL($latest_post->topic_title));
                    $latest_post_avatar = $latest_post_user->getAvatar(128);
                    $latest_post_title = Output::getClean($latest_post->topic_title);
                    $latest_post_user_displayname = $latest_post_user->getDisplayname();
                    $latest_post_user_link = $latest_post_user->getProfileURL();
                    $latest_post_style = $latest_post_user->getGroupClass();
                    $latest_post_date_timeago = $timeago->inWords(date('d M Y, H:i', $latest_post->topic_reply_date), $language->getTimeLanguage());
                    $latest_post_time = date('d M Y, H:i', $latest_post->topic_reply_date);
                    $latest_post_user_id = Output::getClean($latest_post->topic_last_user);

                    $latest_post = array(
                        'link' => $latest_post_link,
                        'title' => $latest_post_title,
                        'last_user_avatar' => $latest_post_avatar,
                        'last_user' => $latest_post_user_displayname,
                        'last_user_style' => $latest_post_style,
                        'last_user_link' => $latest_post_user_link,
                        'timeago' => $latest_post_date_timeago,
                        'time' => $latest_post_time,
                        'last_user_id' => $latest_post_user_id
                    );
                } else $latest_post = array();

                $subforum_array[] = array(
                    'id' => $subforum->id,
                    'title' => Output::getPurified(Output::getDecoded($subforum->forum_title)),
                    'description' => Output::getPurified(Output::getDecoded($subforum->forum_description)),
                    'topics' => $subforum_topics,
                    'link' => URL::build('/forum/view/' . $subforum->id . '-' . $forum->titleToURL($subforum->forum_title)),
                    'latest_post' => $latest_post,
                    'icon' => Output::getPurified(Output::getDecoded($subforum->icon)),
                    'redirect' => $subforum->redirect_forum
                );
            }
        }
    }

    // Assign language variables
    $smarty->assign('FORUMS', $forum_language->get('forum', 'forums'));
    $smarty->assign('DISCUSSION', $forum_language->get('forum', 'discussion'));
    $smarty->assign('TOPIC', $forum_language->get('forum', 'topic'));
    $smarty->assign('STATS', $forum_language->get('forum', 'stats'));
    $smarty->assign('LAST_REPLY', $forum_language->get('forum', 'last_reply'));
    $smarty->assign('BY', $forum_language->get('forum', 'by'));
    $smarty->assign('VIEWS', $forum_language->get('forum', 'views'));
    $smarty->assign('POSTS', $forum_language->get('forum', 'posts'));
    $smarty->assign('STATISTICS', $forum_language->get('forum', 'stats'));
    $smarty->assign('OVERVIEW', $forum_language->get('forum', 'overview'));
    $smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language->get('forum', 'latest_discussions'));
    $smarty->assign('TOPICS', $forum_language->get('forum', 'topics'));
    $smarty->assign('NO_TOPICS', $forum_language->get('forum', 'no_topics_short'));
    $smarty->assign('SUBFORUMS', $subforum_array);
    $smarty->assign('SUBFORUM_LANGUAGE', $forum_language->get('forum', 'subforums'));
    $smarty->assign('FORUM_TITLE', Output::getPurified(htmlspecialchars_decode($forum_query->forum_title)));
    $smarty->assign('FORUM_ICON', Output::getPurified(Output::getDecoded($forum_query->icon)));
    $smarty->assign('STICKY_TOPICS', $forum_language->get('forum', 'sticky_topics'));

    // Can the user post here?
    if ($user->isLoggedIn() && $forum->canPostTopic($fid, $user_groups)) {
        $smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
    } else {
        $smarty->assign('NEW_TOPIC_BUTTON', false);
    }

    $smarty->assign('NEW_TOPIC', $forum_language->get('forum', 'new_topic'));

    // Topics
    if (!count($stickies) && !count($topics)) {
        // No topics yet
        $smarty->assign('NO_TOPICS_FULL', $forum_language->get('forum', 'no_topics'));

        if ($user->isLoggedIn() && $forum->canPostTopic($fid, $user_groups)) {
            $smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
        } else {
            $smarty->assign('NEW_TOPIC_BUTTON', false);
        }

        $no_topics_exist = true;
    } else {
        // Topics/sticky topics exist
        $labels_cache = array();

        $sticky_array = array();
        // Assign sticky threads to smarty variable
        foreach ($stickies as $sticky) {
            // Get number of replies to a topic
            $replies = $queries->getWhere('posts', array('topic_id', '=', $sticky->id));
            $replies = count($replies);

            // Is there a label?
            if ($sticky->label != 0) { // yes
                // Get label
                if ($labels_cache[$sticky->label]) {
                    $label = $labels_cache[$sticky->label];
                } else {
                    $label = $queries->getWhere('forums_topic_labels', array('id', '=', $sticky->label));
                    if (count($label)) {
                        $label = $label[0];

                        $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
                        if (count($label_html)) {
                            $label_html = Output::getPurified($label_html[0]->html);
                            $label = str_replace('{x}', Output::getClean($label->name), $label_html);
                        } else $label = '';
                    } else $label = '';

                    $labels_cache[$sticky->label] = $label;
                }
            } else { // no
                $label = '';
            }

            $labels = array();
            if ($sticky->labels) {
                $topic_labels = explode(',', $sticky->labels);

                foreach ($topic_labels as $item) {
                    // Get label
                    if ($labels_cache[$item]) {
                        $labels[] = $labels_cache[$item];
                    } else {
                        $label_query = $queries->getWhere('forums_topic_labels', array('id', '=', $item));
                        if (count($label_query)) {
                            $label_query = $label_query[0];

                            $label_html = $queries->getWhere('forums_labels', array('id', '=', $label_query->label));
                            if (count($label_html)) {
                                $label_html = Output::getPurified($label_html[0]->html);
                                $label_html = str_replace('{x}', Output::getClean($label_query->name), $label_html);
                                $labels[] = $label_html;
                                $labels_cache[$item] = $label_html;
                            }
                        }
                    }
                }
            }

            $topic_user = new User($sticky->topic_creator);
            $last_reply_user = new User($sticky->topic_last_user);

            // Add to array
            $sticky_array[] = array(
                'topic_title' => Output::getClean($sticky->topic_title),
                'topic_id' => $sticky->id,
                'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_date), $language->getTimeLanguage()),
                'topic_created' => date('d M Y, H:i', $sticky->topic_date),
                'topic_created_username' => $topic_user->getDisplayname(),
                'topic_created_mcname' => $topic_user->getDisplayname(true),
                'topic_created_style' => $topic_user->getGroupClass(),
                'topic_created_user_id' => Output::getClean($sticky->topic_creator),
                'views' => $sticky->topic_views,
                'locked' => $sticky->locked,
                'posts' => $replies,
                'last_reply_avatar' => $last_reply_user->getAvatar(128),
                'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_reply_date), $language->getTimeLanguage()),
                'last_reply' => date('d M Y, H:i', $sticky->topic_reply_date),
                'last_reply_username' => $last_reply_user->getDisplayname(),
                'last_reply_mcname' => $last_reply_user->getDisplayname(true),
                'last_reply_style' => $last_reply_user->getGroupClass(),
                'last_reply_user_id' => Output::getClean($sticky->topic_last_user),
                'label' => $label,
                'labels' => $labels,
                'author_link' => $topic_user->getProfileURL(),
                'link' => URL::build('/forum/topic/' . $sticky->id . '-' . $forum->titleToURL($sticky->topic_title)),
                'last_reply_link' => $last_reply_user->getProfileURL()
            );
        }
        // Clear out variables
        $stickies = null;
        $sticky = null;

        // Latest discussions
        // Pagination
        $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
        $results = $paginator->getLimited($topics, 10, $p, count($topics));
        $pagination = $paginator->generate(7, URL::build('/forum/view/' . $fid . '-' . $forum->titleToURL($forum_query->forum_title), true));

        if (count($topics))
            $smarty->assign('PAGINATION', $pagination);
        else
            $smarty->assign('PAGINATION', '');

        $template_array = array();
        // Get a list of all topics from the forum, and paginate
        for ($n = 0; $n < count($results->data); $n++) {
            // Get number of replies to a topic
            $replies = $queries->getWhere("posts", array("topic_id", "=", $results->data[$n]->id));
            $replies = count($replies);

            // Is there a label?
            if ($results->data[$n]->label != 0) { // yes
                // Get label
                if ($labels_cache[$results->data[$n]->label]) {
                    $label = $labels_cache[$results->data[$n]->label];
                } else {
                    $label = $queries->getWhere('forums_topic_labels', array('id', '=', $results->data[$n]->label));
                    if (count($label)) {
                        $label = $label[0];

                        $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
                        if (count($label_html)) {
                            $label_html = $label_html[0]->html;
                            $label = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html));
                        } else $label = '';
                    } else $label = '';

                    $labels_cache[$results->data[$n]->label] = $label;
                }
            } else { // no
                $label = '';
            }

            $labels = array();
            if ($results->data[$n]->labels) {
                if ($labels_cache[$results->data[$n]->labels]) {
                    $labels[] = $labels_cache[$results->data[$n]->labels];
                } else {
                    $topic_labels = explode(',', $results->data[$n]->labels);

                    foreach ($topic_labels as $item) {
                        // Get label
                        $label_query = $queries->getWhere('forums_topic_labels', array('id', '=', $item));
                        if (count($label_query)) {
                            $label_query = $label_query[0];

                            $label_html = $queries->getWhere('forums_labels', array('id', '=', $label_query->label));
                            if (count($label_html)) {
                                $label_html = $label_html[0]->html;
                                $label_html = str_replace('{x}', Output::getClean($label_query->name), Output::getPurified($label_html));
                                $labels[] = $label_html;
                                $labels_cache[$item] = $label_html;
                            }
                        }
                    }
                }
            }

            $topic_user = new User($results->data[$n]->topic_creator);
            $last_reply_user = new User($results->data[$n]->topic_last_user);

            // Add to array
            $template_array[] = array(
                'topic_title' => Output::getClean($results->data[$n]->topic_title),
                'topic_id' => $results->data[$n]->id,
                'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->topic_date), $language->getTimeLanguage()),
                'topic_created' => date('d M Y, H:i', $results->data[$n]->topic_date),
                'topic_created_username' => $topic_user->getDisplayname(),
                'topic_created_mcname' => $topic_user->getDisplayname(true),
                'topic_created_style' => $topic_user->getGroupClass(),
                'topic_created_user_id' => Output::getClean($results->data[$n]->topic_creator),
                'locked' => $results->data[$n]->locked,
                'views' => $results->data[$n]->topic_views,
                'posts' => $replies,
                'last_reply_avatar' => $last_reply_user->getAvatar(),
                'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->topic_reply_date), $language->getTimeLanguage()),
                'last_reply' => date('d M Y, H:i', $results->data[$n]->topic_reply_date),
                'last_reply_username' => $last_reply_user->getDisplayname(),
                'last_reply_mcname' => $last_reply_user->getDisplayname(true),
                'last_reply_style' => $last_reply_user->getGroupClass(),
                'label' => $label,
                'labels' => $labels,
                'author_link' => $topic_user->getProfileURL(),
                'link' => URL::build('/forum/topic/' . $results->data[$n]->id . '-' . $forum->titleToURL($results->data[$n]->topic_title)),
                'last_reply_link' => $last_reply_user->getProfileURL(),
                'last_reply_user_id' => Output::getClean($results->data[$n]->topic_last_user)
            );
        }

        // Assign to Smarty variable
        $smarty->assign('STICKY_DISCUSSIONS', $sticky_array);
        $smarty->assign('LATEST_DISCUSSIONS', $template_array);
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
    $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    if (isset($no_topics_exist))
        $template->displayTemplate('forum/view_forum_no_discussions.tpl', $smarty);
    else
        $template->displayTemplate('forum/view_forum.tpl', $smarty);
}
