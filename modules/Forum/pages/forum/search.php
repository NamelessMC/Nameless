<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Forum search page
 */

if (!isset($forum) || (!$forum instanceof Forum)) {
    $forum = new Forum();
}

const PAGE = 'forum';

// Initialise
$timeago = new TimeAgo(TIMEZONE);

// Get user group ID
$user_groups = $user->getAllGroupIds();

if (!isset($_GET['s'])) {
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'forum_search' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 128
                ]
            ]);

            if ($validation->passed()) {
                $search = str_replace(' ', '+', Output::getClean(Input::get('forum_search')));
                $search = preg_replace('/[^a-zA-Z0-9 +]+/', '', $search); // alphanumeric only

                Redirect::to(URL::build('/forum/search/', 's=' . urlencode($search) . '&p=1'));
            }

            $error = $forum_language->get('forum', 'invalid_search_query', ['min' => 3, 'max' => 128]);
        } else {
            $error = $language->get('general', 'invalid_token');
        }
    }
} else {
    $search = Output::getClean(str_replace('+', ' ', $_GET['s']));
    $search = preg_replace('/[^a-zA-Z0-9 +]+/', '', $search); // alphanumeric only

    if (isset($_GET['p']) && is_numeric($_GET['p'])) {
        $p = $_GET['p'];
    } else {
        $p = 1;
    }

    if (isset($_SESSION['last_forum_search']) && $_SESSION['last_forum_search_query'] != $_GET['s'] && $_SESSION['last_forum_search'] > strtotime('-1 minute')) {
        Session::flash('search_error', $forum_language->get('forum', 'search_again_in_x_seconds', ['count' => (60 - (date('U') - $_SESSION['last_forum_search']))]));
        Redirect::to(URL::build('/forum/search'));
    }

    $cache->setCache($search . '-' . rtrim(implode('-', $user_groups), '-'));
    if (!$cache->isCached('result')) {
        // Execute search
        $search_topics = DB::getInstance()->query('SELECT * FROM nl2_topics WHERE topic_title LIKE ?', ['%' . $search . '%'])->results();
        $search_posts = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE post_content LIKE ?', ['%' . $search . '%'])->results();

        $search_results = array_merge($search_topics, $search_posts);

        $results = [];
        foreach ($search_results as $result) {
            // Check permissions
            $perms = DB::getInstance()->get('forums_permissions', ['forum_id', $result->forum_id])->results();
            foreach ($perms as $perm) {
                if (in_array($perm->group_id, $user_groups) && $perm->view == 1 && $perm->view_other_topics == 1) {
                    if (isset($result->topic_id)) {
                        // Post
                        if (!isset($results[$result->id]) && $result->deleted == 0) {
                            // Get associated topic
                            $topic = DB::getInstance()->get('topics', ['id', $result->topic_id])->results();
                            if (count($topic) && $topic[0]->deleted === 0) {
                                $topic = $topic[0];
                                $results[$result->id] = [
                                    'post_id' => $result->id,
                                    'topic_id' => $topic->id,
                                    'topic_title' => $topic->topic_title,
                                    'post_author' => $result->post_creator,
                                    'post_date' => $result->post_date,
                                    'post_content' => $result->post_content
                                ];

                                break;
                            }

                            break;
                        } else {
                            break;
                        }
                    } else {
                        // Topic, get associated post
                        $post = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY post_date ASC LIMIT 1', [$result->id]);
                        if ($post->count()) {
                            $post = $post->first();
                            if (!isset($results[$post->id]) && $post->deleted == 0) {
                                $results[$post->id] = [
                                    'post_id' => $post->id,
                                    'topic_id' => $result->id,
                                    'topic_title' => $result->topic_title,
                                    'post_author' => $post->post_creator,
                                    'post_date' => $post->post_date,
                                    'post_content' => $post->post_content
                                ];

                                break;
                            }

                            break;
                        } else {
                            break;
                        }
                    }

                }
            }
        }

        $results = array_values($results);
        $cache->store('result', $results, 60);

        if (!isset($_SESSION['last_forum_search_query']) || $_SESSION['last_forum_search_query'] != $_GET['s']) {
            $_SESSION['last_forum_search'] = date('U');
            $_SESSION['last_forum_search_query'] = $_GET['s'];
        }
    } else {
        $results = $cache->retrieve('result');
    }

    $input = true;
}

if (!isset($_GET['s'])) {
    $page_title = $forum_language->get('forum', 'forum_search');
} else {
    $page_title = $forum_language->get('forum', 'forum_search') . ' - ' . Output::getClean(substr($search, 0, 20)) . ' - ' . $language->get('general', 'page_x', ['page' => $p]);
}
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->assets()->include([
    AssetTree::TINYMCE,
]);

if (isset($_GET['s'])) {
    // Show results
    if (count($results)) {
        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($results, 10, $p, count($results));
        $pagination = $paginator->generate(7, URL::build('/forum/search/', 's=' . urlencode($search) . '&'));

        $smarty->assign('PAGINATION', $pagination);

        // Posts to display on the page
        $posts = [];
        // Display the correct number of posts
        $n = 0;
        while (($n < count($results->data)) && isset($results->data[$n])) {
            // Purify post content
            $content = EventHandler::executeEvent('renderPost', ['content' => $results->data[$n]['post_content']])['content'];

            $post_user = new User($results->data[$n]['post_author']);
            $posts[$n] = [
                'post_author' => $post_user->getDisplayname(),
                'post_author_id' => Output::getClean($results->data[$n]['post_author']),
                'post_author_avatar' => $post_user->getAvatar(25),
                'post_author_profile' => $post_user->getProfileURL(),
                'post_author_style' => $post_user->getGroupStyle(),
                'post_date_full' => date(DATE_FORMAT, strtotime($results->data[$n]['post_date'])),
                'post_date_friendly' => $timeago->inWords($results->data[$n]['post_date'], $language),
                'content' => $content,
                'topic_title' => Output::getClean($results->data[$n]['topic_title']),
                'post_url' => URL::build('/forum/topic/' . urlencode($results->data[$n]['topic_id']) . '-' . $forum->titleToURL($results->data[$n]['topic_title']), 'pid=' . $results->data[$n]['post_id'])
            ];
            $n++;
        }

        $results = null;

        $smarty->assign([
            'RESULTS' => $posts,
            'READ_FULL_POST' => $forum_language->get('forum', 'read_full_post')
        ]);
    } else {
        $smarty->assign('NO_RESULTS', $forum_language->get('forum', 'no_results_found'));
    }

    $smarty->assign([
        'SEARCH_RESULTS' => $forum_language->get('forum', 'search_results'),
        'NEW_SEARCH' => $forum_language->get('forum', 'new_search'),
        'NEW_SEARCH_URL' => URL::build('/forum/search'),
        'SEARCH_TERM' => (isset($_GET['s']) ? Output::getClean($_GET['s']) : '')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forum/search_results.tpl', $smarty);
} else {
    // Search bar
    if (isset($error)) {
        $smarty->assign('ERROR', $error);
    } else {
        if (Session::exists('search_error')) {
            $smarty->assign('ERROR', Session::flash('search_error'));
        }
    }

    $smarty->assign([
        'FORUM_SEARCH' => $forum_language->get('forum', 'forum_search'),
        'FORM_ACTION' => URL::build('/forum/search'),
        'SEARCH' => $language->get('general', 'search'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'ERROR_TITLE' => $language->get('general', 'error')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('forum/search.tpl', $smarty);
}
