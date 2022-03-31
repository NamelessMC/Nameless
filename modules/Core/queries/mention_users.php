<?php

header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die();
}

$users = DB::getInstance()->selectQuery(
    'SELECT u.id, u.username, u.uuid, u.gravatar, u.email, u.has_avatar, u.avatar_updated FROM nl2_users u WHERE u.username LIKE ?',
    ["{$_GET['username']}%"]
)->results();

$users_json = [];
foreach ($users as $user) {
    $users_json[] = [
        'id' => (int)$user->id,
        'username' => $user->username,
        'avatar_url' => AvatarSource::getAvatarFromUserData($user, false, 20, true)
    ];
}

die(json_encode($users_json));
