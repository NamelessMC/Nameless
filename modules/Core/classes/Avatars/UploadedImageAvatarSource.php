<?php

class UploadedImageAvatarSource extends AvatarSourceBase {

    public function __construct() {
        $this->_name = 'Uploaded Image';
        $this->_module = 'Core';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_avatar_settings/uploaded_image.php';
    }

    public function get(User $user): ?string {
        $base_url = ($this->full_url ? rtrim(URL::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars';

        if (Util::getSetting('custom_user_avatars') && $user->data()->has_avatar) {
            $exts = ['png', 'jpg', 'jpeg'];

            if ($user->hasPermission('usercp.gif_avatar')) {
                $exts[] = 'gif';
            }

            foreach ($exts as $ext) {
                if (file_exists(ROOT_PATH . "/uploads/avatars/{$user->data()->id}.{$ext}")) {
                    return "{$base_url}/{$user->data()->id}.{$ext}?v={$user->data()->avatar_updated}";
                }
            }
        }

        // Fallback to default avatar image if it is set
        $custom_default_avatar = Util::getSetting('custom_default_avatar');
        if ($custom_default_avatar) {
            if (file_exists(ROOT_PATH . '/uploads/avatars/defaults/' . $custom_default_avatar)) {
                return "{$base_url}/defaults/{$custom_default_avatar}";
            }
        }

        return null;
    }
}
