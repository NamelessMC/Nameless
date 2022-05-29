<?php

header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die();
}

$users = DB::getInstance()->query(
    'SELECT u.id, u.username, u.nickname, u.gravatar, u.email, u.has_avatar, u.avatar_updated, IFNULL(nl2_users_integrations.identifier, \'none\') as uuid FROM nl2_users u LEFT JOIN nl2_users_integrations ON user_id=u.id AND integration_id=1 WHERE u.nickname LIKE ? OR u.username LIKE ?',
    ["{$_GET['nickname']}%", "{$_GET['nickname']}%"]
)->results();

$users_json = [];
foreach ($users as $user) {
    $users_json[] = [
        'nickname' => $user->nickname,
        'avatar_url' => AvatarSource::getAvatarFromUserData($user, false, 20, true)
    ];
}

die(json_encode($users_json));
