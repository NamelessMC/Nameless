<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  React to a post or get a reaction summary modal
 */

// Validate form input
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['post']) || !is_numeric($_GET['post'])) {
        die('Invalid input');
    }
    $post_id = $_GET['post'];
    $post_type = $_GET['type'];
} else {
    // User must be logged in to proceed
    if (!$user->isLoggedIn()) {
        die('Not logged in');
    }

    if (!isset($_POST['post'], $_POST['reaction']) || !is_numeric($_POST['post']) || !is_numeric($_POST['reaction'])) {
        die('Invalid input');
    }
    $post_id = $_POST['post'];
    $post_type = $_POST['type'];
}

// Get post information
$post = DB::getInstance()->get($post_type === 'post' ? 'posts' : 'user_profile_wall_posts', ['id', $post_id]);

if (!$post->count()) {
    die('Invalid post');
}

$post = $post->first();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $post_type === 'post') {
    // Are reactions enabled?
    if (Util::getSetting('forum_reactions') !== '1') {
        die('Reactions disabled');
    }

    $topic_id = $post->topic_id;

    // Check user can actually view the post
    if (!((new Forum())->forumExist($post->forum_id, $user->getAllGroupIds()))) {
        die('Invalid post');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    define('PAGE', 'reactions_modal');
    $page_title = 'reactions_modal';
    require_once(ROOT_PATH . '/core/templates/frontend_init.php');

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    $all_reactions = Reaction::find(true, 'enabled');
    $formatted_reactions[0] = [
        'id' => 0,
        'name' => 'All',
        'html' => '',
        'order' => 0,
        'count' => 0,
        'users' => [],
    ];

    if ($post_type === 'post') {
        $reactions = DB::getInstance()->get('forums_reactions', ['post_id', $post->id]);
    } else {
        $reactions = DB::getInstance()->get('user_profile_wall_posts_reactions', ['post_id', $post->id]);
    }

    if ($reactions->count()) {
        $reactions = $reactions->results();
    } else {
        $reactions = [];
    }

    foreach ($reactions as $reaction) {
        $reaction_user = new User($reaction->{$post_type === 'post' ? 'user_given' : 'user_id'});

        if (isset($formatted_reactions[$reaction->reaction_id])) {
            $formatted_reactions[$reaction->reaction_id]['count']++;
            $formatted_reactions[$reaction->reaction_id]['users'][] = [
                'id' => $reaction_user->data()->id,
                'nickname' => Output::getClean($reaction_user->getDisplayname()),
                'avatar' => $reaction_user->getAvatar(),
                'profile' => $reaction_user->getProfileURL(),
                'group_style' => $reaction_user->getGroupStyle(),
                'group_html' => $reaction_user->getAllGroupHtml(),
                'reacted_time' => date(DATE_FORMAT, $reaction->time),
                'reaction_html' => $all_reactions[$reaction->reaction_id]->html,
            ];
            continue;
        }

        $formatted_reactions[$reaction->reaction_id] = [
            'id' => $reaction->reaction_id,
            'name' => $all_reactions[$reaction->reaction_id]->name,
            'html' => $all_reactions[$reaction->reaction_id]->html,
            'order' => $all_reactions[$reaction->reaction_id]->order,
            'count' => 1,
            'users' => [
                [
                    'id' => $reaction_user->data()->id,
                    'nickname' => Output::getClean($reaction_user->getDisplayname()),
                    'avatar' => $reaction_user->getAvatar(),
                    'profile' => $reaction_user->getProfileURL(),
                    'group_style' => $reaction_user->getGroupStyle(),
                    'group_html' => $reaction_user->getAllGroupHtml(),
                    'reacted_time' => date(DATE_FORMAT, $reaction->time),
                    'reaction_html' => $all_reactions[$reaction->reaction_id]->html,
                ],
            ],
        ];
    }

    $formatted_reactions[0]['count'] = count($reactions);
    foreach ($formatted_reactions as $reaction) {
        if ($reaction['id'] === 0) {
            continue;
        }
        foreach ($reaction['users'] as $user) {
            $formatted_reactions[0]['users'][] = $user;
        }
    }

    foreach (array_keys($formatted_reactions) as $key) {
        uasort($formatted_reactions[$key]['users'], static function ($a, $b) {
            return strtotime($a['reacted_time']) < strtotime($b['reacted_time']);
        });
    }

    usort($formatted_reactions, static function($a, $b) {
        return $a['order'] - $b['order'];
    });

    $smarty->assign([
        'ACTIVE_TAB' => $_GET['tab'],
        'REACTIONS' => $formatted_reactions,
    ]);

    // modal
    die($template->getTemplate('reactions_modal.tpl', $smarty));
} else {
    // add reaction
    if (!Token::check()) {
        die('Invalid token');
    }

    if ($post_type === 'post') {
        // Check if the user has already reacted to this post
        $user_reacted = DB::getInstance()->get('forums_reactions', [['post_id', $post->id], ['user_given', $user->data()->id]]);
        if ($user_reacted->count()) {
            $reaction = $user_reacted->first();
            if ($reaction->reaction_id == $_POST['reaction']) {
                DB::getInstance()->delete('forums_reactions', $reaction->id);
                die('Reaction deleted');
            }

            DB::getInstance()->update('forums_reactions', $reaction->id, [
                'reaction_id' => $_POST['reaction'],
                'time' => date('U'),
            ]);

            die('Reaction changed');
        }

        // Input new reaction
        DB::getInstance()->insert('forums_reactions', [
            'post_id' => $post->id,
            'user_received' => $post->post_creator,
            'user_given' => $user->data()->id,
            'reaction_id' => $_POST['reaction'],
            'time' => date('U'),
        ]);

        Log::getInstance()->log(Log::Action('forums/react'), $_POST['reaction']);
    } else {
        // Check if the user has already reacted to this post
        $user_reacted = DB::getInstance()->get('user_profile_wall_posts_reactions', [['post_id', $post->id], ['user_id', $user->data()->id]]);
        if ($user_reacted->count()) {
            $reaction = $user_reacted->first();
            if ($reaction->reaction_id == $_POST['reaction']) {
                DB::getInstance()->delete('user_profile_wall_posts_reactions', $reaction->id);
                die('Reaction deleted');
            }

            DB::getInstance()->update('user_profile_wall_posts_reactions', $reaction->id, [
                'reaction_id' => $_POST['reaction'],
                'time' => date('U'),
            ]);

            die('Reaction changed');
        }

        // Input new reaction
        DB::getInstance()->insert('user_profile_wall_posts_reactions', [
            'post_id' => $post->id,
            'user_id' => $user->data()->id,
            'reaction_id' => $_POST['reaction'],
            'time' => date('U'),
        ]);
    }

    die('Reaction added');
}
