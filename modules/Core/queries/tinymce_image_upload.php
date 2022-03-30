<?php

if (!$user->isLoggedIn()) {
    die();
}

if (!Token::check()) {
    die();
}

$image = new \Bulletproof\Image($_FILES);
$image->setLocation(implode(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'post_images')));
$image->setDimension(10000, 10000);
$image->setSize(10, 10000000 /* 10MB */);
$image->setName($user->data()->id . '-' . time());

if ($image['file']) {
    $upload = $image->upload();
    if (!$upload) {
        http_response_code(400);
        die($image->getError());
    }

    die(json_encode([
        'location' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/post_images/' . $image->getName().'.'.$image->getMime(),
    ]));
}

http_response_code(400);
die('No file uploaded');
