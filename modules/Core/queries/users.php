<?php

header('Content-Type: application/json');

$validate = Validate::check($_GET, [
    'search' => [
        Validate::RATE_LIMIT => 60,
    ],
]);

if (!$validate->passed()) {
    die(json_encode(['error' => 'Please wait a moment before searching again']));
}

// Searchable user list
if (!isset($_GET['search']) || strlen($_GET['search']) < 2) {
    die(json_encode(['error' => 'Please enter a search query of at least 2 characters']));
}

$query = '%' . $_GET['search'] . '%';

$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? 'LIMIT ' . (int) $_GET['limit'] : '';
$users = DB::getInstance()->query("SELECT id, username, nickname, gravatar, email, has_avatar, avatar_updated FROM nl2_users WHERE username LIKE ? OR nickname LIKE ? $limit", [
    $query, $query
])->results();

foreach ($users as $user) {
    $user->profile_url = URL::build('/profile/' . urlencode($user->username));
    $user->avatar_url = AvatarSource::getAvatarFromUserData($user);
    unset($user->gravatar, $user->email, $user->has_avatar, $user->avatar_updated);
}

echo json_encode(['results' => $users], JSON_PRETTY_PRINT);
die();
