<?php

/** @var PDO $conn */
/** @var DB $nameless_conn */

$forums = $conn->query('SELECT * FROM Forums')->fetchAll(PDO::FETCH_ASSOC);
$forum_threads = $conn->query('SELECT * FROM ForumThreads')->fetchAll(PDO::FETCH_ASSOC);
$forum_posts = $conn->query('SELECT * FROM Posts')->fetchAll(PDO::FETCH_ASSOC);
$users = $conn->query('SELECT * FROM Users')->fetchAll(PDO::FETCH_ASSOC);
$user_ids = [];

$order = 4;

$group_permissions = [
    0 => [
        'view' => true,
        'create_topic' => false,
        'edit_topic' => false,
        'create_post' => false,
        'view_other_topics' => true,
        'moderate' => false,
    ],
    1 => [
        'view' => true,
        'create_topic' => true,
        'edit_topic' => true,
        'create_post' => true,
        'view_other_topics' => true,
        'moderate' => false,
    ],
    2 => [
        'view' => true,
        'create_topic' => true,
        'edit_topic' => true,
        'create_post' => true,
        'view_other_topics' => true,
        'moderate' => true,
    ],
    3 => [
        'view' => true,
        'create_topic' => true,
        'edit_topic' => true,
        'create_post' => true,
        'view_other_topics' => true,
        'moderate' => true,
    ],
];

try {
    $nameless_conn->getPDO()->beginTransaction();

    $nameless_conn->insert('forums', [
        'forum_title' => 'Enjin Import',
        'forum_description' => 'Imported from Enjin',
        'parent' => 0,
        'forum_order' => 2,
        'forum_type' => 'category',
    ]);

    $category_id = $nameless_conn->lastId();
    foreach ($group_permissions as $group => $permissions) {
        $nameless_conn->insert('forums_permissions', [
            'forum_id' => $category_id,
            'group_id' => $group,
            'view' => $permissions['view'],
            'create_topic' => $permissions['create_topic'],
            'edit_topic' => $permissions['edit_topic'],
            'create_post' => $permissions['create_post'],
            'view_other_topics' => $permissions['view_other_topics'],
            'moderate' => $permissions['moderate'],
        ]);
    }

    foreach ($forums as $forum) {
        $nameless_conn->insert('forums', [
            'forum_title' => $forum['title'],
            'forum_description' => 'Imported from Enjin',
            'parent' => $category_id,
            'forum_order' => $order++,
        ]);

        $forum_id = $nameless_conn->lastId();
        foreach ($group_permissions as $group => $permissions) {
            $nameless_conn->insert('forums_permissions', [
                'forum_id' => $forum_id,
                'group_id' => $group,
                'view' => $permissions['view'],
                'create_topic' => $permissions['create_topic'],
                'edit_topic' => $permissions['edit_topic'],
                'create_post' => $permissions['create_post'],
                'view_other_topics' => $permissions['view_other_topics'],
                'moderate' => $permissions['moderate'],
            ]);
        }
    }

    foreach ($users as $user) {
        if ($user['uuid'] !== '00000000-0000-0000-0000-000000000000') {
            $profile = ProfileUtils::getProfile($user['uuid']);
            if ($profile) {
                $username = $profile->getUsername();
            }
        } else {
            $username = $user['username'];
        }

        $nameless_conn->insert('users', [
            'username' => $user['username'],
            'nickname' => $username,
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'pass_method' => 'enjin-import',
            'joined' => date('U'),
            'email' => str_replace(' ', '-', $user['username']) . '@enjin-import.com',
            'active' => 0,
            'user_title' => 'Imported from Enjin :)',
            'lastip' => '127.0.0.1',
        ]);

        $user_id = $nameless_conn->lastId();

        if ($user['uuid'] !== '00000000-0000-0000-0000-000000000000' && $profile !== null) {
            DB::getInstance()->insert('users_integrations', [
                'integration_id' => 1,
                'user_id' => $user_id,
                'identifier' => str_replace('-', '', $user['uuid']),
                'username' => $username,
                'verified' => true,
                'date' => date('U'),
            ]);
        }

        $user_ids[$user['id']] = $user_id;
    }

    foreach ($forum_threads as $thread) {
        $topic_creator_id = $nameless_conn->get('users', ['id', $user_ids[$thread['poster_id']]])->first()->id;
        $forum_name = array_filter($forums, static function ($forum) use ($thread) {
            return $forum['id'] == $thread['forum'];
        });
        $forum_name = array_shift($forum_name)['title'];
        $forum = $nameless_conn->get('forums', ['forum_title', $forum_name])->first();

        $nameless_conn->insert('topics', [
            'forum_id' => $forum->id,
            'topic_title' => $thread['title'],
            'topic_creator' => $topic_creator_id,
            'topic_last_user' => $topic_creator_id,
            'topic_date' => date('U'),
            'topic_reply_date' => date('U'),
        ]);

        $nameless_conn->update('forums', $forum->id, [
            'last_post_date' => date('U'),
            'last_user_posted' => $topic_creator_id,
            'last_topic_posted' => $nameless_conn->lastId(),
        ]);
    }

    $touched_topics = [];
    foreach ($forum_posts as $post) {
        [$created_epoch, $edited_epoch] = convertDate($post['posted']);
        $thread_name = array_filter($forum_threads, static function ($thread) use ($post) {
            return $thread['id'] == $post['forumThread'];
        });
        $thread_name = array_shift($thread_name)['title'];

        $topic = $nameless_conn->get('topics', ['topic_title', $thread_name])->first();
        $post_creator = $nameless_conn->get('users', ['id', $user_ids[$post['poster_id']]])->first();
        $nameless_conn->insert('posts', [
            'forum_id' => $topic->forum_id,
            'topic_id' => $topic->id,
            'post_creator' => $post_creator->id,
            'post_content' => $post['bbcode'],
            'post_date' => date('Y-m-d H:i:s', $created_epoch),
            'last_edited' => $edited_epoch,
            'created' => $created_epoch,
        ]);

        // Set the topic date to the first post date in that topic
        $update = [
            'topic_last_user' => $post_creator->id,
            'topic_reply_date' => $created_epoch,
        ];
        if (!in_array($topic->id, $touched_topics)) {
            $update['topic_date'] = $created_epoch;
            $touched_topics[] = $topic->id;
        }
        $nameless_conn->update('topics', $topic->id, $update);
    }

    $nameless_conn->getPDO()->commit();

    Util::setSetting('enjin_imported', 1);
} catch (PDOException $exception) {
    $error = $exception->getMessage() . ' ' . $exception->getTraceAsString();
    $nameless_conn->getPDO()->rollBack();
}

function convertDate(string $enjinDate): array {
    $date = null;
    $edited_date = null;

    $parts = explode(' ', $enjinDate);
    // Posted Nov 24, 16
    // Posted Nov 24, 16 · OP
    if (!str_contains($enjinDate, 'Last edited')) {
        [, $month, $day, $year] = $parts;
    } else {
        if (!str_contains($enjinDate, 'OP')) {
            // Posted Nov 24, 16 · Last edited Nov 24, 16
            [, $month, $day, $year, , , , $edited_month, $edited_day, $edited_year] = $parts;
        } else {
            // Posted Nov 24, 16 · OP · Last edited Nov 24, 16
            [, $month, $day, $year, , , , , , $edited_month, $edited_day, $edited_year] = $parts;
        }
    }

    echo json_encode($parts) . '<br>';

    $date = strtotime(implode(' ', normalizeMonthDayYear($month, $day, $year)));

    if (isset($edited_month)) {
        $edited_date = strtotime(implode(' ', normalizeMonthDayYear($edited_month, $edited_day, $edited_year)));
    }

    return [$date, $edited_date];
}

function normalizeMonthDayYear(string $month, string $day, string $year): array {
    $month = [
        'Jan' => 'January',
        'Feb' => 'February',
        'Mar' => 'March',
        'Apr' => 'April',
        'May' => 'May',
        'Jun' => 'June',
        'Jul' => 'July',
        'Aug' => 'August',
        'Sep' => 'September',
        'Oct' => 'October',
        'Nov' => 'November',
        'Dec' => 'December',
    ][$month];
    $day = substr($day, 0, -1);
    $year = '20' . $year;

    return [$month, $day, $year];
}
