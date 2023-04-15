<?php
$template->assets()->include([
    AssetTree::DROPZONE,
    AssetTree::IMAGE_PICKER,
]);

$template->addJSScript('
// Dropzone options
Dropzone.options.upload_avatar_dropzone = {
    maxFilesize: 2,
    dictDefaultMessage: "' . $language->get('admin', 'drag_files_here') . '",
    dictInvalidFileType: "' . $language->get('admin', 'invalid_file_type') . '",
    dictFileTooBig: "' . $language->get('admin', 'file_too_big') . '"
};

$(".image-picker").imagepicker();
');

if (Input::exists()) {
    if (Token::check()) {
        Util::setSetting('custom_default_avatar', Input::get('avatar'));
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'avatars', 'defaults']);
$images = scandir($image_path);
$template_images = [];
// Only display jpeg, png, jpg, gif
$allowed_exts = ['gif', 'png', 'jpg', 'jpeg'];
foreach ($images as $image) {
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_exts)) {
        continue;
    }

    $template_images[(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/avatars/defaults/' . Output::getClean($image)] = Output::getClean($image);
}

$smarty->assign([
    'SUBMIT' => $language->get('general', 'submit'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/panel_templates/Default/core/avatar_sources/uploaded_image.tpl',
    'CUSTOM_AVATARS' => $language->get('admin', 'allow_custom_avatars'),
    'CUSTOM_AVATARS_VALUE' => Util::getSetting('custom_user_avatars'),
    'SELECT_DEFAULT_AVATAR' => $language->get('admin', 'select_default_avatar'),
    'IMAGES' => $template_images,
    'NO_AVATARS' => $language->get('admin', 'no_avatars_available'),
    'DEFAULT_AVATAR' => $language->get('admin', 'default_avatar'),
    'DEFAULT_AVATAR_IMAGE' => Util::getSetting('custom_default_avatar'),
    'UPLOAD_NEW_IMAGE' => $language->get('admin', 'upload_new_image'),
    'UPLOAD_FORM_ACTION' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/includes/image_upload.php',
    'DRAG_FILES_HERE' => $language->get('admin', 'drag_files_here'),
    'CLOSE' => $language->get('general', 'close'),
]);
