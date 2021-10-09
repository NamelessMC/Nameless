<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum search page
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
if (!isset($forum) || (isset($forum) && !$forum instanceof Forum))
    $forum = new Forum();

require_once(ROOT_PATH . '/core/includes/emojione/autoload.php'); // Emojione

define('PAGE', 'forum');

// Initialise
$timeago = new Timeago(TIMEZONE);
$emojione = new Emojione\Client(new Emojione\Ruleset());

// Get user group ID
$user_groups = $user->getAllGroupIds();

if (!isset($_GET['s'])) {
    if (Input::exists()) {
        if (Token::check()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'forum_search' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 128
                ]
            ]);

            if ($validation->passed()) {
                $search = str_replace(' ', '+', Output::getClean(Input::get('forum_search')));
                $search = preg_replace("/[^a-zA-Z0-9 +]+/", "", $search); // alphanumeric only

                Redirect::to(URL::build('/forum/search/', 's=' . $search . '&p=1'));
                die();
            } else {
                $error = $forum_language->get('forum', 'invalid_search_query');
            }
        } else {
            $error = $language->get('general', 'invalid_token');
        }
    }
} else {
    $search = Output::getClean(str_replace('+', ' ', $_GET['s']));
    $search = preg_replace("/[^a-zA-Z0-9 +]+/", "", $search); // alphanumeric only

    if (isset($_GET['p']) && is_numeric($_GET['p']))
        $p = $_GET['p'];
    else
        $p = 1;

    if (isset($_SESSION['last_forum_search']) && $_SESSION['last_forum_search_query'] != $_GET['s'] && $_SESSION['last_forum_search'] > strtotime('-1 minute')) {
        Session::flash('search_error', str_replace('{x}', (60 - (date('U') - $_SESSION['last_forum_search'])), $forum_language->get('forum', 'search_again_in_x_seconds')));
        Redirect::to(URL::build('/forum/search'));
        die();
    }

    $cache->setCache($search . '-' . rtrim(implode('-', $user_groups), '-'));
    if (!$cache->isCached('result')) {
        // Execute search
        $search_topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE topic_title LIKE ?', array('%' . $search . '%'))->results();
        $search_posts = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE post_content LIKE ?', array('%' . $search . '%'))->results();

        $search_results = array_merge((array)$search_topics, (array)$search_posts);

        $results = array();
        foreach ($search_results as $result) {
            // Check permissions
            $perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $result->forum_id));
            foreach ($perms as $perm) {
                if (in_array($perm->group_id, $user_groups) && $perm->view == 1 && $perm->view_other_topics == 1) {
                    if (isset($result->topic_id)) {
                        // Post
                        if (!isset($results[$result->id]) && $result->deleted == 0) {
                            // Get associated topic
                            $topic = $queries->getWhere('topics', array('id', '=', $result->topic_id));
                            if (count($topic) && $topic[0]->deleted === 0) {
                                $topic = $topic[0];
                                $results[$result->id] = array(
                                    'post_id' => $result->id,
                                    'topic_id' => $topic->id,
                                    'topic_title' => $topic->topic_title,
                                    'post_author' => $result->post_creator,
                                    'post_date' => $result->post_date,
                                    'post_content' => $result->post_content
                                );

                                break;
                            } else
                                break;
                        } else
                            break;
                    } else {
                        // Topic, get associated post
                        $post = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY post_date ASC LIMIT 1', array($result->id));
                        if ($post->count()) {
                            $post = $post->first();
                            if (!isset($results[$post->id]) && $post->deleted == 0) {
                                $results[$post->id] = array(
                                    'post_id' => $post->id,
                                    'topic_id' => $result->id,
                                    'topic_title' => $result->topic_title,
                                    'post_author' => $post->post_creator,
                                    'post_date' => $post->post_date,
                                    'post_content' => $post->post_content
                                );

                                break;
                            } else
                                break;
                        } else
                            break;
                    }

                    break;
                }
            }
        }

        $results = array_values($results);
        $cache->store('result', $results, 60);

        if (!isset($_SESSION['last_forum_search_query']) || $_SESSION['last_forum_search_query'] != $_GET['s']) {
            $_SESSION['last_forum_search'] = date('U');
            $_SESSION['last_forum_search_query'] = $_GET['s'];
        }
    } else
        $results = $cache->retrieve('result');

    $input = true;
}

if (!isset($_GET['s']))
    $page_title = $forum_language->get('forum', 'forum_search');
else {
    $page_title = $forum_language->get('forum', 'forum_search') . ' - ' . Output::getClean(substr($search, 0, 20)) . ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
}
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array()
));

$template->addJSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
));

if (isset($_GET['s'])) {
    // Show results
    if (count($results)) {
        $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
        $results = $paginator->getLimited($results, 10, $p, count($results));
        $pagination = $paginator->generate(7, URL::build('/forum/search/', 's=' . $search . '&'));

        $smarty->assign('PAGINATION', $pagination);

        // Posts to display on the page
        $posts = array();
        // Display the correct number of posts
        $n = 0;
        while (($n < count($results->data)) && isset($results->data[$n])) {
            $content = htmlspecialchars_decode($results->data[$n]['post_content']);
            $content = $emojione->unicodeToImage($content);
            $content = Output::getPurified($content);

            $post_user = new User($results->data[$n]['post_author']);
            $posts[$n] = array(
                'post_author' => $post_user->getDisplayname(),
                'post_author_id' => Output::getClean($results->data[$n]['post_author']),
                'post_author_avatar' => $post_user->getAvatar(25),
                'post_author_profile' => $post_user->getProfileURL(),
                'post_author_style' => $post_user->getGroupClass(),
                'post_date_full' => date('d M Y, H:i', strtotime($results->data[$n]['post_date'])),
                'post_date_friendly' => $timeago->inWords($results->data[$n]['post_date'], $language->getTimeLanguage()),
                'content' => $content,
                'topic_title' => Output::getClean($results->data[$n]['topic_title']),
                'post_url' => URL::build('/forum/topic/' . $results->data[$n]['topic_id'] . '-' . $forum->titleToURL($results->data[$n]['topic_title']), 'pid=' . $results->data[$n]['post_id'])
            );
            $n++;
        }

        $results = null;

        $smarty->assign(array(
            'RESULTS' => $posts,
            'READ_FULL_POST' => $forum_language->get('forum', 'read_full_post')
        ));
    } else
        $smarty->assign('NO_RESULTS', $forum_language->get('forum', 'no_results_found'));

    $smarty->assign(array(
        'SEARCH_RESULTS' => $forum_language->get('forum', 'search_results'),
        'NEW_SEARCH' => $forum_language->get('forum', 'new_search'),
        'NEW_SEARCH_URL' => URL::build('/forum/search'),
        'SEARCH_TERM' => (isset($_GET['s']) ? Output::getClean($_GET['s']) : '')
    ));

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forum/search_results.tpl', $smarty);
} else {
    // Search bar
    if (isset($error))
        $smarty->assign('ERROR', $error);
    else if (Session::exists('search_error'))
        $smarty->assign('ERROR', Session::flash('search_error'));

    $smarty->assign(array(
        'FORUM_SEARCH' => $forum_language->get('forum', 'forum_search'),
        'FORM_ACTION' => URL::build('/forum/search'),
        'SEARCH' => $language->get('general', 'search'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'ERROR_TITLE' => $language->get('general', 'error')
    ));

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

    $page_load = microtime(true) - $start;
    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forum/search.tpl', $smarty);
}
