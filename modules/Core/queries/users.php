<?php
declare(strict_types=1);
/**
 *  Made by Unknown
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  TODO: Add description
 *
 * @var User $user
 */

// Searchable user list
if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Unauthenticated']));
}

if (!isset($_GET['search']) || strlen($_GET['search']) < 3) {
    die(json_encode(['error' => 'Please enter a search query of at least 3 characters']));
}

$query = '%' . $_GET['search'] . '%';

$users = DB::getInstance()->query('SELECT id, username, nickname FROM nl2_users WHERE username LIKE ? OR nickname LIKE ?', [
    $query, $query
]);

echo json_encode(['results' => $users->results()], JSON_PRETTY_PRINT);
die();
