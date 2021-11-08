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
    'discord_widget_disabled' => 'Поддержка виджетов отключена на данном Discord сервере. Перейдите в раздел \'Виджет\' в настройках вашего сервера и активируйте его.',
    'discord_id_length' => 'Убедитесь что ваш ID длинее 18 символов.',
    'discord_id_numeric' => 'Убедитесь что ваш ID состоит из цифр.',
    'discord_invite_info' => 'Чтобы пригласить бота Nameless Link на ваш Discord сервер, нажмите <a target="_blank" href="https://namelessmc.com/discord-bot-invite">сюда</a>. Затем, следуйте инструкции которую вы получите после подключения бота. Как вариант, вы можете разместить <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">свою версию бота</a>.',
    'discord_bot_must_be_setup' => 'Нельзя интегрировать сайт, пока вы не подключите бота. Информация <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">здесь</a>.',
    'discord_bot_setup' => 'Бот настроен?',
    'discord_integration_not_setup' => 'Discord не настроен',
    'discord_username' => 'Имя в Discord',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Некорректный запрос.',
    'discord_bot_error_error' => 'У бота произошла ошибка.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'API ключ сайта некорректный',
    'discord_bot_error_invrole' => 'ID роли некорректный.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
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
    'discord_link' => 'Привязка к Discord',
    'linked' => 'Привязан',
    'not_linked' => 'Не привязан',
    'discord_user_id' => 'Discord ID',
    'discord_id_unlinked' => 'Вы отвязали свой профиль в Discord.',
    'discord_id_confirm' => 'Введите команду "/verify {token}" в Discord для завершения привязки.',
    'pending_link' => 'В процессе привязки',
    'discord_id_taken' => 'Этот User ID в Discord уже занят.',
    'discord_invalid_id' => 'Этот User ID не был найден в Discord.',
    'discord_already_pending' => 'Вы уже в процессе привязки аккаунта.',
    'discord_database_error' => 'База бота Nameless Link не работает в данный момент. Попробуйте в другой раз.',
    'discord_communication_error' => 'Произошла ошибка во время связи с Discord. Убедитесь что бот активен и URL страница бота корректна.',
    'discord_unknown_error' => 'Произошла ошибка во время сихронизации привилегий на сервере. Свяжитесь с администрацией.',
    'discord_id_help' => 'Чтобы найти ваш ID сервера в Discord, прочитайте это <a href="https://support.discord.com/hc/ru/articles/206346498-%D0%93%D0%B4%D0%B5-%D0%BC%D0%BD%D0%B5-%D0%BD%D0%B0%D0%B9%D1%82%D0%B8-ID-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F-%D1%81%D0%B5%D1%80%D0%B2%D0%B5%D1%80%D0%B0-%D1%81%D0%BE%D0%BE%D0%B1%D1%89%D0%B5%D0%BD%D0%B8%D1%8F-" target="_blank">руководство.</a>'
];
