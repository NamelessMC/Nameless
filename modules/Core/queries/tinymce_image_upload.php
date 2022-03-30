<?php

if (!$user->isLoggedIn()) {
    die();
}

$image = new \Bulletproof\Image($_FILES);
$image->setLocation('uploads/post_images');
$image->setDimension(10000, 10000);
$image->setSize(10, 10000000 /* 10MB */);
$image->setName($user->data()->id . '-' . time());

if ($image['file']) {
    $upload = $image->upload();
    if ($upload === false) {
        http_response_code(500);
        header("HTTP/1.1 500 Server Error: " . $image->getError());
        return;
    }

    die(json_encode([
        'location' => $upload->getFullPath(),
    ]));
}

http_response_code(500);
header("HTTP/1.1 500 Server Error: No image uploaded");
return;
