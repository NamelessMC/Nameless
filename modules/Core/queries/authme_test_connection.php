<?php

if (!Token::check()) {
    die('Invalid token');
}

if (!$user->isLoggedIn()) {
    die('Not logged in');
}

if (!$user->hasPermission('admincp.minecraft.authme')) {
    die('No permission');
}

if (!isset($_POST['db_address'], $_POST['db_name'], $_POST['db_username'], $_POST['db_port'], $_POST['db_table'])) {
    die('Invalid input');
}

try {
    $connection = DB::getCustomInstance($_POST['db_address'], $_POST['db_name'], $_POST['db_username'], $_POST['db_password'], $_POST['db_port']);
    $connection->query('SELECT * FROM ' . $_POST['db_table'] . ' LIMIT 1');
    // TODO: add check for hashing algorithm, will need a nice way to check if a password hash is of a certain hash algo
} catch (Exception $e) {
    die('Invalid connection');
}

die('OK');
