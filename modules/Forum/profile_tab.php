<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum module - forum profile tab
 */

if (!isset($forum) || (isset($forum) && !$forum instanceof Forum)) {
    require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
    $forum = new Forum();
}

// Get latest posts
$latest_posts = $queries->orderWhere('posts', 'post_creator = ' . $query->id . ' AND deleted = 0', 'post_date', 'DESC LIMIT 15');
if (!count($latest_posts)) {
    $smarty->assign('NO_POSTS', $forum_language->get('forum', 'user_no_posts'));
} else {
    // Check permissions
    $n = 0;

    if (!$user->isLoggedIn()) {
        $groups = array(0);
    } else {
        $groups = $user->getAllGroups();
    }

    // Array to assign posts to
    $posts = array();

    foreach ($latest_posts as $latest_post) {
        if ($n == 5) break;

        // Is the post somewhere the user can view?
        $permission = false;
        $forum_permissions = $queries->getWhere('forums_permissions', array('forum_id', '=', $latest_post->forum_id));
        foreach ($forum_permissions as $forum_permission) {
            if (in_array($forum_permission->group_id, $groups)) {
                if ($forum_permission->view == 1 && $forum_permission->view_other_topics == 1) {
                    $permission = true;
                    break;
                }
            }
        }

        if ($permission != true) continue;

        // Check the post isn't deleted
        if ($latest_post->deleted == 1) continue;

        // Get topic title
        $topic_title = $queries->getWhere('topics', array('id', '=', $latest_post->topic_id));
        if (!count($topic_title)) continue;
        $topic_title = htmlspecialchars($topic_title[0]->topic_title);

        if (is_null($latest_post->created)) {
            $date_friendly = $timeago->inWords($latest_post->post_date, $language->getTimeLanguage());
            $date_full = date('d M Y, H:i', strtotime($latest_post->post_date));
        } else {
            $date_friendly = $timeago->inWords(date('d M Y, H:i', $latest_post->created), $language->getTimeLanguage());
            $date_full = date('d M Y, H:i', $latest_post->created);
        }

        $posts[] = array(
            'link' => URL::build('/forum/topic/' . $latest_post->topic_id . '-' . $forum->titleToURL($topic_title), 'pid=' . $latest_post->id),
            'title' => $topic_title,
            'content' => Output::getPurified($emojione->unicodeToImage(htmlspecialchars_decode($latest_post->post_content))),
            'date_friendly' => $date_friendly,
            'date_full' => $date_full
        );

        $n++;
    }
}

// Smarty
$smarty->assign(array(
    'PF_LATEST_POSTS' => (isset($posts)) ? $posts : array(),
    'PF_LATEST_POSTS_TITLE' => $forum_language->get('forum', 'latest_posts'),
    'FORUM_TAB_TITLE' => $forum_language->get('forum', 'forum')
));
