<?php

header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die();
}

$users = DB::getInstance()->query(<<<SQL
        SELECT DISTINCT
            u.id,
            u.username,
            u.nickname,
            u.gravatar,
            u.email,
            u.has_avatar,
            u.avatar_updated,
            IFNULL(ui.identifier, 'none') as uuid
        FROM nl2_users u
            LEFT JOIN nl2_users_integrations ui ON ui.user_id = u.id AND ui.integration_id = 1
            LEFT JOIN nl2_blocked_users bu ON bu.user_id = u.id AND bu.user_blocked_id = ?
        WHERE
            (u.nickname LIKE ? OR u.username LIKE ?)
            AND bu.user_id IS NULL
    SQL,
    [$user->data()->id, "{$_GET['nickname']}%", "{$_GET['nickname']}%"]
)->results();

$users_json = [];
foreach ($users as $user) {
    $users_json[] = [
        'nickname' => $user->nickname,
        'avatar_url' => AvatarSource::getAvatarFromUserData($user, false, 20, true)
    ];
}

die(json_encode($users_json));
