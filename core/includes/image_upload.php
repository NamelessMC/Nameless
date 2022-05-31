<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Image uploads
 */

// Initialisation
$page = 'image_uploads';
const ROOT_PATH = '../..';

// Get the directory the user is trying to access
$directory = $_SERVER['REQUEST_URI'];
$directories = explode('/', $directory);

require(ROOT_PATH . '/core/init.php');

if (!$user->isLoggedIn()) {
    die();
}

$image_extensions = ['jpg', 'png', 'jpeg'];
$delete_extensions = array_merge($image_extensions, ['gif']);

if ($user->hasPermission('usercp.gif_avatar')) {
    $image_extensions[] = 'gif';
}

if ($_POST['type'] == 'favicon') {
    $image_extensions[] = 'ico';
}

// Deal with input
if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Token valid
        $image = new \Bulletproof\Image($_FILES);
        $image->setSize(1, 2097152); // between 1b and 2mb
        $image->setDimension(2000, 2000); // 2k x 2k pixel maximum
        $image->setMime($image_extensions);

        switch ($_POST['type']) {
            case 'background':
                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'backgrounds']));
                break;

            case 'template_banner':
                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'template_banners']));
                break;

            case 'logo':
                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'logos']));
                break;

            case 'favicon':
                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'favicons']));
                break;

            case 'default_avatar':
                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'avatars', 'defaults']));
                break;

            case 'profile_banner':
                if (!$user->hasPermission('usercp.profile_banner')) {
                    Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
                }

                if (
                    !is_dir(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]))
                    && !mkdir(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]))
                ) {
                    die('uploads/profile_images folder not writable! <a href="' . URL::build('/profile/' . urlencode($user->data()->username)) . '">Back</a>');
                }

                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]));
                break;

            default:
                // Default to normal avatar upload
                if (!defined('CUSTOM_AVATARS')) {
                    die('Custom avatar uploading is disabled');
                }

                $image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'avatars']));
                $image->setName($user->data()->id);
                break;
        }

        if ($image['file']) {
            try {
                $upload = $image->upload();

                if (!$upload) {
                    if (Input::get('type') == 'profile_banner') {
                        Session::flash('profile_banner_error', $image->getError());
                        Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
                    }

                    http_response_code(400);
                    die($image->getError());
                }

                // OK
                // Avatar?
                if (Input::get('type') == 'avatar') {
                    // Need to delete any other avatars
                    $diff = array_diff($delete_extensions, [strtolower($upload->getMime())]);

                    $to_remove = [];
                    foreach ($diff as $extension) {
                        $to_remove += glob(ROOT_PATH . '/uploads/avatars/' . $user->data()->id . '.' . $extension);
                    }

                    foreach ($to_remove as $item) {
                        unlink($item);
                    }

                    $user->update([
                        'has_avatar' => true,
                        'avatar_updated' => date('U')
                    ]);

                    Redirect::to(URL::build('/user/settings'));
                }

                if (Input::get('type') == 'profile_banner') {
                    $user->update([
                        'banner' => Output::getClean($user->data()->id . '/' . $upload->getName() . '.' . $upload->getMime())
                    ]);

                    Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
                }

                die('OK');
            } catch (Exception $e) {
                // Error
                http_response_code(400);
                die($e->getMessage());
            }
        } else {
            if (Input::get('type') == 'avatar') {
                Redirect::to(URL::build('/user/settings'));
            }

            die('No image selected');
        }
    } else if (Input::get('type') == 'background') {
        Session::flash('admin_images', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
    }
}

die('Invalid input');
