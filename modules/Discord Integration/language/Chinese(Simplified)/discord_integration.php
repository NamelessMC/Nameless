<?php

// Chinese Simplified

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => '启用 Discord 集成?',
    'discord_role_id' => 'Discord 身份组 ID',
    'discord_role_id_numeric' => 'Discord 身份组 ID 必须为数字。',
    'discord_role_id_length' => 'Discord 身份组 ID 必须为 18 位长。',
    'discord_settings_updated' => '已成功更新您的 Discord 设置。',
    'discord_guild_id_required' => '请输入您的 Discord 服务器 ID:',
    'discord_bot_url' => 'Discord 机器人链接',
    'discord_bot_url_info' => '您的自定义 Nameless Link 机器人的链接。请您在理解此项的用途后更改。',
    'discord_bot_url_required' => '请输入您 Nameless Link 机器人的链接。',
    'discord_invalid_api_url' => '好像您的 API 链接变化了。请您联系服务器拥有者修改链接。',
    'test_bot_url' => '测试机器人 URL',
    'discord_bot_url_valid' => '您的机器人 URL 有效。',
    'discord_cannot_interact' => 'Nameless Link 机器人无法对此用户进行操作。它的权限组可能比 Nameless Link 权限组更高。',
    'discord_id' => 'Discord 服务器 ID',
    'discord_widget_theme' => 'Discord Widget 主题',
    'discord_id_length' => '请确保您的 Discord ID 长 18 位。',
    'discord_id_numeric' => '请确保您的 Discord ID 只包含数字。',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => '您必须配置 Discord 机器人后才能启用 Discord 集成。您可<a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">点击此处</a>了解详情。',
    'discord_bot_setup' => '机器人已配置?',
    'discord_integration_not_setup' => 'Discord 集成尚未配置',

    // Discord bot Errors
    'discord_bot_error_badparameter' => '非法请求体。',
    'discord_bot_error_error' => '发生了一个内部机器人错误。',
    'discord_bot_error_invguild' => '提供的服务器 ID 错误，或此机器人不在提供的服务器中。',
    'discord_bot_error_invuser' => '提供的用户 ID 错误，或并不在指定的服务器中。',
    'discord_bot_error_notlinked' => '提供的服务器中的机器人没有链接到此网站。',
    'discord_bot_error_unauthorized' => '网站 API 密钥错误。',
    'discord_bot_error_invrole' => '提供的权限组 ID 错误。',
    'discord_bot_error_hierarchy' => '此机器人无法修改此用户的权限组。',

    // API Errors
    'discord_integration_disabled' => '已关闭 Discord 集成。',
    'unable_to_set_discord_id' => '无法设置 Discord ID。',
    'unable_to_set_discord_bot_url' => '无法设置 Discord 机器人 URL',
    'provide_one_discord_settings' => '请提供至少一个: "url", "guild_id"',
    'no_pending_verification_for_token' => '此 token 下没有待进行的验证。',
    'unable_to_update_discord_username' => '无法更新 Discord 用户名。',
    'unable_to_update_discord_roles' => '无法更新 Discord 权限组列表。',
    'unable_to_update_discord_bot_username' => '无法更新 Discord 机器人用户名。',

    // API Success
    'discord_id_set' => 'Discord ID 更新成功',
    'discord_settings_updated' => 'Discord 设置更新成功',
    'discord_usernames_updated' => 'Discord 用户名更新成功',

    // User Settings
    'discord_username' => 'Discord 用户名',

    // Discord
    'discord_link' => 'Discord 链接',
    'linked' => '已链接',
    'not_linked' => '未链接',
    'discord_id' => 'Discord 用户 ID',
    'discord_id_unlinked' => '成功取消了您的 Discord 链接。',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => '待定',
    'discord_id_taken' => '此 Discord 用户 ID 已被使用。',
    'discord_invalid_id' => '此 Discord 用户 ID 不符合要求。',
    'discord_already_pending' => '您已经有待定验证了。',
    'discord_database_error' => 'Nameless Link 数据库目前掉线了。请您稍后再试。',
    'discord_communication_error' => '无法与 Discord 机器人交流。请您确保机器人正在运行并且您的机器人链接是正确的。',
    'discord_unknown_error' => '在同步 Discord 身份组时出现了错误。请联系管理员。',
    'discord_id_help' => '您可阅读 <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">此文章</a> 来了解如何获得您的用户 ID。'

];