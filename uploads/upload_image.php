<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Image upload handler
 */

define('ROOT_PATH', dirname(__DIR__) . '');
$page = 'image_upload';

require('../core/init.php');

if ($user->isLoggedIn()) {

    if (!is_dir(ROOT_PATH . '/uploads/images')) {
        mkdir(ROOT_PATH . '/uploads/images');
    }

    $image = new \Bulletproof\Image($_FILES);

    $image->setSize(1000, 2 * 1048576)
        ->setMime(['jpeg', 'png', 'gif'])
        ->setDimension(2000, 2000)
        ->setLocation(ROOT_PATH . '/uploads/images/' . $user->data()->id, 0777);

    if ($image['upload']) {
        $upload = $image->upload();

        if ($upload) {
            $url = ((defined('CONFIG_PATH')) ? CONFIG_PATH : '/uploads/images/' . $user->data()->id . '/' . $image->getName() . '.' . $image->getMime());

            echo json_encode([
                'uploaded' => '1',
                'fileName' => $image->getName() . $image->getMime(),
                'url' => $url
            ]);
        } else {
            echo json_encode([
                'uploaded' => '0',
                'error' => ['message' => $image->getError() . ' ' . $image->getMime()]
            ]);
        }
    }
} else {
    echo json_encode([
        'uploaded' => '0',
        'error' => ['You are not logged in']
    ]);
}
