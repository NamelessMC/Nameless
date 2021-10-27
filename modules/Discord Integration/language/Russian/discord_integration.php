<?php

// Russian

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Включить интеграцию?',
    'discord_role_id' => 'ID роли',
    'discord_role_id_numeric' => 'ID роли имеет только цифры.',
    'discord_role_id_length' => 'ID роли имеет длину в 18 символов.',
    'discord_guild_id' => 'ID сервера в Discord',
    'discord_widget_theme' => 'Тема для виджета',
    'discord_id_length' => 'Убедитесь что ваш ID длинее 18 символов.',
    'discord_id_numeric' => 'Убедитесь что ваш ID состоит из цифр.',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Нельзя интегрировать сайт, пока вы не подключите бота. Информация <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">здесь</a>.',
    'discord_bot_setup' => 'Бот настроен?',
    'discord_integration_not_setup' => 'Discord не настроен',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Некорректный запрос.',
    'discord_bot_error_error' => 'У бота произошла ошибка.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'API ключ сайта некорректный',
    'discord_bot_error_invrole' => 'ID роли некорректный.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Интеграция с Discord отключена.',
    'unable_to_set_discord_id' => 'Не удалось установить Discord ID.',
    'unable_to_set_discord_bot_url' => 'Не удалось установить URL бота в Discord',
    'provide_one_discord_settings' => 'Please provide at least one of the following: "url", "guild_id"',
    'no_pending_verification_for_token' => 'По предоставленному токену нет ожидающих проверки регистрации.',
    'unable_to_update_discord_username' => 'Не удалось обновить никнеймы в Discord.',
    'unable_to_update_discord_roles' => 'Не удалось обновить роли в Discord.',
    'unable_to_update_discord_bot_username' => 'Не удалось обновить никнейм бота в Discord.',

    // API Success
    'discord_id_set' => 'Discord ID установлен',
    'discord_settings_updated' => 'Настройки в Discord обновлены',
    'discord_usernames_updated' => 'Никнеймы в Discord обновлены',

    // User Settings
    'discord_link' => 'Discord Link',
    'linked' => 'Linked',
    'not_linked' => 'Not Linked',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Successfully unlinked your Discord User ID.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pending',
    'discord_id_taken' => 'That Discord ID has already been taken.',
    'discord_invalid_id' => 'That Discord User ID is invalid.',
    'discord_already_pending' => 'You already have a pending verification.',
    'discord_database_error' => 'The Nameless Link database is currently down. Please try again later.',
    'discord_communication_error' => 'There was an error while communicating with the Discord Bot. Please ensure the bot is running and your Bot URL is correct.',
    'discord_unknown_error' => 'There was an unknown error while syncing Discord roles. Please contact an administrator.',
    'discord_id_help' => 'For information on where to find Discord ID\'s, please read <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>'
];
