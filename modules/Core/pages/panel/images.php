<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Panel images page
 */

if (!$user->handlePanelPageLoad('admincp.styles.images')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'layout';
const PANEL_PAGE = 'images';
$page_title = $language->get('admin', 'images');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Reset background
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'reset_banner') {
        $cache->setCache('backgroundcache');
        $cache->store('banner_image', '');

        Session::flash('panel_images_success', $language->get('admin', 'template_banner_reset_successfully'));
        Redirect::to(URL::build('/panel/core/images'));
    }

    if ($_GET['action'] === 'reset_logo') {
        $cache->setCache('backgroundcache');
        $cache->store('logo_image', '');

        Session::flash('panel_images_success', $language->get('admin', 'logo_reset_successfully'));
        Redirect::to(URL::build('/panel/core/images'));
    }

    if ($_GET['action'] === 'reset_favicon') {
        $cache->setCache('backgroundcache');
        $cache->store('favicon_image', '');

        Session::flash('panel_images_success', $language->get('admin', 'favicon_reset_successfully'));
        Redirect::to(URL::build('/panel/core/images'));
    }

    if ($_GET['action'] === 'reset_og_image') {
        $cache->setCache('backgroundcache');
        $cache->store('og_image', '');

        Session::flash('panel_images_success', $language->get('admin', 'og_image_reset_successfully'));
        Redirect::to(URL::build('/panel/core/images'));
    }
}

// Deal with input
if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token
        $cache->setCache('backgroundcache');

        if (isset($_POST['banner'])) {
            $cache->store('banner_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/template_banners/' . Input::get('banner'));

            Session::flash('panel_images_success', $language->get('admin', 'template_banner_updated_successfully'));

        } else {
            if (isset($_POST['logo'])) {
                $cache->store('logo_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/logos/' . Input::get('logo'));

                Session::flash('panel_images_success', $language->get('admin', 'logo_updated_successfully'));

            } else if (isset($_POST['favicon'])) {
                $cache->store('favicon_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/favicons/' . Input::get('favicon'));

                Session::flash('panel_images_success', $language->get('admin', 'favicon_updated_successfully'));
            } else if (isset($_POST['og_image'])) {
                $cache->store('og_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/og_images/' . Input::get('og_image'));

                Session::flash('panel_images_success', $language->get('admin', 'og_image_updated_successfully'));
            }
        }

        Redirect::to(URL::build('/panel/core/images'));
    }

    // Invalid token
    $errors = [$language->get('general', 'invalid_token')];
}


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('panel_images_success')) {
    $success = Session::flash('panel_images_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors
    ]);
}

// Get banner from cache
$cache->setCache('backgroundcache');
if (!$cache->isCached('banner_image')) {
    $cache->store('banner_image', (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/homepage_bg_trimmed.jpg');
    $banner_image = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/homepage_bg_trimmed.jpg';
} else {
    $banner_image = $cache->retrieve('banner_image');
}

if ($banner_image == '') {
    $banner_img = $language->get('general', 'none');
} else {
    $banner_img = Output::getClean($banner_image);
}

// Get logo from cache
$logo_image = $cache->retrieve('logo_image');

if ($logo_image == '') {
    $logo_img = $language->get('general', 'none');
} else {
    $logo_img = Output::getClean($logo_image);
}

// Get favicon from cache
$favicon_image = $cache->retrieve('favicon_image');

if ($favicon_image == '') {
    $favicon_img = $language->get('general', 'none');
} else {
    $favicon_img = Output::getClean($favicon_image);
}

// Get OG image from cache
$og_image = $cache->retrieve('og_image');

if ($og_image == '') {
    $og_img = $language->get('general', 'none');
} else {
    $og_img = Output::getClean($og_image);
}

// Only display jpeg, png, jpg, gif
$allowed_exts = ['gif', 'png', 'jpg', 'jpeg', 'ico'];

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'template_banners']);
$images = scandir($image_path);
$template_banner_images = [];

$n = 1;

foreach ($images as $image) {
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_exts)) {
        continue;
    }
    $template_banner_images[] = [
        'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/' . $image,
        'value' => $image,
        'selected' => ($banner_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/' . $image),
        'n' => $n
    ];
    $n++;
}

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'logos']);
$images = scandir($image_path);
$logo_images = [];

$n = 1;

foreach ($images as $image) {
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_exts)) {
        continue;
    }
    $logo_images[] = [
        'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/logos/' . $image,
        'value' => $image,
        'selected' => ($logo_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/logos/' . $image),
        'n' => $n
    ];
    $n++;
}

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'favicons']);
$images = scandir($image_path);
$favicon_images = [];

$n = 1;

foreach ($images as $image) {
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_exts)) {
        continue;
    }
    $favicon_images[] = [
        'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/favicons/' . $image,
        'value' => $image,
        'selected' => ($favicon_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/favicons/' . $image),
        'n' => $n
    ];
    $n++;
}

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'og_images']);
$images = scandir($image_path);
$og_images = [];

$n = 1;

foreach ($images as $image) {
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_exts)) {
        continue;
    }
    $og_images[] = [
        'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/og_images/' . $image,
        'value' => $image,
        'selected' => ($og_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/og_images/' . $image),
        'n' => $n
    ];
    $n++;
}

if (!is_writable(ROOT_PATH . '/uploads/backgrounds')) {
    $smarty->assign('BACKGROUNDS_NOT_WRITABLE', $language->get('admin', 'x_directory_not_writable', [
        'directory' => Text::bold('uploads/backgrounds')
    ]));
}

if (!is_writable(ROOT_PATH . '/uploads/template_banners')) {
    $smarty->assign('TEMPLATE_BANNERS_DIRECTORY_NOT_WRITABLE', $language->get('admin', 'x_directory_not_writable', [
        'directory' => Text::bold('uploads/template_banners')
    ]));
}

if (!is_writable(ROOT_PATH . '/uploads/logos')) {
    $smarty->assign('LOGOS_DIRECTORY_NOT_WRITABLE', $language->get('admin', 'x_directory_not_writable', [
        'directory' => Text::bold('uploads/logos')
    ]));
}

if (!is_writable(ROOT_PATH . '/uploads/favicons')) {
    $smarty->assign('FAVICONS_DIRECTORY_NOT_WRITABLE', $language->get('admin', 'x_directory_not_writable', [
        'directory' => Text::bold('uploads/favicons')
    ]));
}

if (!is_writable(ROOT_PATH . '/uploads/og_images')) {
    $smarty->assign('OG_IMAGES_DIRECTORY_NOT_WRITABLE', $language->get('admin', 'x_directory_not_writable', [
        'directory' => Text::bold('uploads/og_images')
    ]));
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'LAYOUT' => $language->get('admin', 'layout'),
    'IMAGES' => $language->get('admin', 'images'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'UPLOAD_NEW_IMAGE' => $language->get('admin', 'upload_new_image'),
    'UPLOAD_PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/includes/image_upload.php',
    'CLOSE' => $language->get('general', 'close'),
    'RESET' => $language->get('admin', 'reset_background'),
    'RESET_LINK' => URL::build('/panel/core/images/', 'action=reset_bg'),
    'RESET_BANNER' => $language->get('admin', 'reset_banner'),
    'RESET_BANNER_LINK' => URL::build('/panel/core/images/', 'action=reset_banner'),
    'RESET_LOGO' => $language->get('admin', 'reset_logo'),
    'RESET_LOGO_LINK' => URL::build('/panel/core/images/', 'action=reset_logo'),
    'RESET_FAVICON' => $language->get('admin', 'reset_favicon'),
    'RESET_FAVICON_LINK' => URL::build('/panel/core/images/', 'action=reset_favicon'),
    'RESET_OG_IMAGE' => $language->get('admin', 'reset_og_image'),
    'RESET_OG_IMAGE_LINK' => URL::build('/panel/core/images/', 'action=reset_og_image'),
    'BANNER_IMAGES_ARRAY' => $template_banner_images,
    'BANNER_IMAGE' => $language->get('admin', 'banner_image_x', [
        'imageName' => Text::bold($banner_img)
    ]),
    'LOGO_IMAGES_ARRAY' => $logo_images,
    'LOGO_IMAGE' => $language->get('admin', 'logo_image_x', [
        'imageName' => Text::bold($logo_img)
    ]),
    'FAVICON_IMAGES_ARRAY' => $favicon_images,
    'FAVICON_IMAGE' => $language->get('admin', 'favicon_image_x', [
        'imageName' => Text::bold($favicon_img)
    ]),
    'OG_IMAGES_ARRAY' => $og_images,
    'FALLBACK_OG_IMAGE' => $language->get('admin', 'fallback_og_image_x', [
        'imageName' => Text::bold($og_img)
    ]),
    'ERRORS_TITLE' => $language->get('general', 'error'),
    'INFO' => $language->get('general', 'info'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/images.tpl', $smarty);
