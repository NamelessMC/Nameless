<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum module - forum profile tab
 */

if (!isset($forum) || (!$forum instanceof Forum)) {
    $forum = new Forum();
}

// Get latest posts
$latest_posts = DB::getInstance()->orderWhere('posts', 'post_creator = ' . $query->id . ' AND deleted = 0', 'post_date', 'DESC LIMIT 15')->results();
if (!count($latest_posts)) {
    $smarty->assign('NO_POSTS', $forum_language->get('forum', 'user_no_posts'));
} else {
    // Check permissions
    $n = 0;

    if (!$user->isLoggedIn()) {
        $groups = [0];
    } else {
        $groups = $user->getAllGroupIds();
    }

    // Array to assign posts to
    $posts = [];

    $permissions = [];
    $topic_titles = [];
    foreach ($latest_posts as $latest_post) {
        if ($n == 5) {
            break;
        }

        // Is the post somewhere the user can view?
        if (!isset($permissions[$latest_post->forum_id])) {
            $permission = false;
            $forum_permissions = DB::getInstance()->get('forums_permissions', ['forum_id', $latest_post->forum_id])->results();
            foreach ($forum_permissions as $forum_permission) {
                if (in_array($forum_permission->group_id, $groups)) {
                    if ($forum_permission->view == 1 && $forum_permission->view_other_topics == 1) {
                        $permission = true;
                        break;
                    }
                }
            }
            $permissions[$latest_post->forum_id] = $permission;
        } else {
            $permission = $permissions[$latest_post->forum_id];
        }

        if ($permission != true) {
            continue;
        }

        // Check the post isn't deleted
        if ($latest_post->deleted == 1) {
            continue;
        }

        // Get topic title
        if (!isset($topic_titles[$latest_post->topic_id])) {
            $topic_title = DB::getInstance()->get('topics', ['id', $latest_post->topic_id])->results();
            if (!count($topic_title)) {
                continue;
            }
            $topic_title = Output::getClean($topic_title[0]->topic_title);
            $topic_titles[$latest_post->topic_id] = $topic_title;
        } else {
            $topic_title = $topic_titles[$latest_post->topic_id];
        }

        if (is_null($latest_post->created)) {
            $date_friendly = $timeago->inWords($latest_post->post_date, $language);
            $date_full = date(DATE_FORMAT, strtotime($latest_post->post_date));
        } else {
            $date_friendly = $timeago->inWords($latest_post->created, $language);
            $date_full = date(DATE_FORMAT, $latest_post->created);
        }

        $posts[] = [
            'link' => URL::build('/forum/topic/' . $latest_post->topic_id . '-' . $forum->titleToURL($topic_title), 'pid=' . $latest_post->id),
            'title' => $topic_title,
            'content' => EventHandler::executeEvent('renderPost', ['content' => $latest_post->post_content])['content'],
            'date_friendly' => $date_friendly,
            'date_full' => $date_full
        ];

        $n++;
    }
}

// Smarty
$smarty->assign([
    'PF_LATEST_POSTS' => (isset($posts)) ? $posts : [],
    'PF_LATEST_POSTS_TITLE' => $forum_language->get('forum', 'latest_posts'),
    'FORUM_TAB_TITLE' => $forum_language->get('forum', 'forum')
]);
