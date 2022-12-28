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


use Bulletproof\Image;

if (!$user->isLoggedIn()) {
    http_response_code(400);
    die('Not logged in');
}

try {
    if (!Token::check()) {
        http_response_code(400);
        die('Invalid token');
    }
} catch (Exception $ignored) {
}

$image = (new Image($_FILES))
    ->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'post_images']))
    ->setSize(10, 10000000 /* 10MB */)
    ->setDimension(10000, 10000)
    ->setName($user->data()->id . '-' . time());

if ($image['file']) {
    if (!$image->upload()) {
        http_response_code(500);
        $error = $image->getError() ?: 'Unknown error, check logs for more details';
        ErrorHandler::logWarning('TinyMCE image upload error: ' . $error);
        die($error);
    }

    die(json_encode([
        'location' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/post_images/' . $image->getName() . '.' . $image->getMime(),
    ]));
}

http_response_code(400);
die('No file uploaded');
