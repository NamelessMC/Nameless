<?php

if (Input::exists()) {
    if (Token::check()) {
        Util::setSetting('minecraft_avatar_source', Input::get('minecraft_avatar_source'));
        Util::setSetting('minecraft_avatar_perspective', Input::get('minecraft_avatar_perspective'));

        AvatarSource::getInstance()->clearSourceAvatarCache(MinecraftAvatarSource::class);

        Redirect::to(AvatarSource::getInstance()->getSourceBySafeName(MinecraftAvatarSource::class)->getSettingsUrl());
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$smarty->assign([
    'SUBMIT' => $language->get('general', 'submit'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/panel_templates/Default/core/avatar_sources/minecraft.tpl',
    'MINECRAFT_AVATAR_SOURCE' => $language->get('admin', 'minecraft_avatar_source'),
    'MINECRAFT_AVATAR_VALUES' => MinecraftAvatarSource::getAllSourceNames(),
    'MINECRAFT_AVATAR_VALUE' => Util::getSetting('minecraft_avatar_source'),
    'MINECRAFT_AVATAR_PERSPECTIVE' => $language->get('admin', 'minecraft_avatar_perspective'),
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUE' => Util::getSetting('minecraft_avatar_perspective'),
    'MINECRAFT_AVATAR_PERSPECTIVE_VALUES' => MinecraftAvatarSource::getAllPerspectives(),
    'HEAD' => $language->get('admin', 'head'),
    'FACE' => $language->get('admin', 'face'),
    'BUST' => $language->get('admin', 'bust'),
]);
