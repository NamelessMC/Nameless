<?php

if (!$user->isLoggedIn()) {
    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
    die('Not logged in');
}

if (!Token::check()) {
    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
    die('Invalid token');
}

$image = (new \Bulletproof\Image($_FILES))
    ->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'post_images']))
    ->setSize(10, 10000000 /* 10MB */)
    ->setDimension(10000, 10000)
    ->setName($user->data()->id . '-' . time());

if ($image['file']) {
    if (!$image->upload()) {
        http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        $error = $image->getError() ?: 'Unknown error, check logs for more details';
        ErrorHandler::logWarning('TinyMCE image upload error: ' . $error);
        die($error);
    }

    die(json_encode([
        'location' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/post_images/' . $image->getName() . '.' . $image->getMime(),
    ]));
}

http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
die('No file uploaded');
