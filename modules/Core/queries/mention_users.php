<?php

header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die();
}

$users = DB::getInstance()->query(
    'SELECT u.id, u.username, u.nickname FROM nl2_users u WHERE u.nickname LIKE ? OR u.username LIKE ?',
    ["{$_GET['nickname']}%", "{$_GET['nickname']}%"]
)->results();

$users_json = [];
foreach ($users as $user) {
    $users_json[] = [
        'nickname' => $user->nickname,
        'avatar_url' => AvatarSource::getInstance()->getAvatarForUser($user->id, 20),
    ];
}

die(json_encode($users_json));
