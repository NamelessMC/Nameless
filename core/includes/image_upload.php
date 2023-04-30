<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
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

require(ROOT_PATH . '/vendor/autoload.php');
require(ROOT_PATH . '/core/init.php');

if (!$user->isLoggedIn()) {
    die('Not logged in');
}

if (!Input::exists()) {
    die('Invalid input');
}

if ($_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
    Redirect::back();
}

if (!Token::check()) {
    if (Input::get('type') === 'background') {
        Session::flash('admin_images', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
    }

    die('Invalid token');
}

$image_extensions = ['jpg', 'png', 'jpeg'];

if ($user->hasPermission('usercp.gif_avatar')) {
    $image_extensions[] = 'gif';
}

if ($_POST['type'] === 'favicon') {
    $image_extensions[] = 'ico';
}

$image = (new \Bulletproof\Image($_FILES))
        ->setSize(1, 2097152 /* 2MB */)
        ->setDimension(2000, 2000) // 2k x 2k pixel maximum
        ->setMime($image_extensions);

$folder = null;

switch ($_POST['type']) {
    case 'background':
        $folder = 'backgrounds';
        break;

    case 'template_banner':
        $folder = 'template_banners';
        break;

    case 'logo':
        $folder = 'logos';
        break;

    case 'favicon':
        $folder = 'favicons';
        break;

    case 'og_image':
        $folder = 'og_images';
        break;

    case 'default_avatar':
        $folder = implode(DIRECTORY_SEPARATOR, ['avatars', 'defaults']);
        break;

    case 'profile_banner':
        if (!$user->hasPermission('usercp.profile_banner')) {
            Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
        }

        if (
            !is_dir(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]))
            && !mkdir(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]))
        ) {
            Session::flash('profile_banner_error', $language->get('admin', 'x_directory_not_writable', ['directory' => 'uploads/profile_images']));
            Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
        }

        $folder = implode(DIRECTORY_SEPARATOR, ['profile_images', $user->data()->id]);
        break;

    default:
        // Default to normal avatar upload
        if (!defined('CUSTOM_AVATARS')) {
            die('Custom avatar uploading is disabled');
        }

        $folder = 'avatars';
        $image->setName($user->data()->id);
        break;
}

$image->setLocation(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', $folder]));

if ($image['file']) {
    try {
        if (!$image->upload()) {
            if (Input::get('type') === 'profile_banner') {
                Session::flash('profile_banner_error', $image->getError() ?: $language->get('api', 'unknown_error'));
                Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
            }

            http_response_code(500);
            $error = $image->getError() ?: 'Unknown error, check logs for more details';
            ErrorHandler::logWarning('Image upload error: ' . $error);
            die($error);
        }

        if (Input::get('type') === 'avatar') {
            // Need to delete any other avatars
            $diff = array_diff(array_merge($image_extensions, ['gif', 'ico']), [strtolower($image->getMime())]);

            foreach ($diff as $extension) {
                $to_remove = glob(ROOT_PATH . '/uploads/avatars/' . $user->data()->id . '.' . $extension);
                foreach ($to_remove as $file) {
                    unlink($file);
                }
            }

            $user->update([
                'has_avatar' => true,
                'avatar_updated' => date('U')
            ]);

            Session::flash('settings_success', $language->get('user', 'avatar_set_successfully'));
            Redirect::to(URL::build('/user/settings'));
        }

        if (Input::get('type') === 'profile_banner') {
            $user->update([
                'banner' => Output::getClean($user->data()->id . '/' . $image->getName() . '.' . $image->getMime())
            ]);

            Redirect::to(URL::build('/profile/' . urlencode($user->data()->username)));
        }

        die('OK');
    } catch (Exception $e) {
        http_response_code(500);
        $error = $e->getMessage() ?: 'Unknown error, check logs for more details';
        ErrorHandler::logWarning('Image upload exception: ' . $error);
        die($error);
    }
} else {
    if (Input::get('type') === 'avatar') {
        Redirect::to(URL::build('/user/settings'));
    }

    die('No image selected');
}
