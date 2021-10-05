<?php

// Polish

$language = [

    // Misc
    'discord_id' => 'ID Serwera Discord',
    'discord_widget_theme' => 'Styl widgetu Discord',
    'discord_id_length' => 'Sprawdź czy twoje ID Serwera Discord ma 18 znaków.',
    'discord_id_numeric' => 'Sprawdź czy twoje ID Discord ma odpowiednią ilość znaków (Tylko numery).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Nie można włączyć integracji Discord dopóki dobrze jej nie skonfigurujesz, zobacz <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">ten artykuł</a>.',
    'discord_bot_setup' => 'Konfiguracja Bota',
    'discord_integration_not_setup' => 'Integracja Discord nie jest włączona',
    'discord' => 'Discord',
    'enable_discord_integration' => 'Włączyć Integrację Discord?',
    'discord_role_id' => 'ID Roli Discord',
    'discord_role_id_numeric' => 'ID Roli musi być liczbą.',
    'discord_role_id_length' => 'ID Roli musi mieć 18 znaków.',
    'discord_settings_updated' => 'Twoje ustawienia Discord zostały zapisane',
    'discord_guild_id_required' => 'Wpisz URL Serwera, aby integracja działała.',
    'discord_bot_url' => 'URL Bota',
    'discord_bot_url_info' => 'Położenie własnego bota. Zmień, jeśli wiesz co robisz!',
    'discord_bot_url_required' => 'Wpisz URL Bota, aby integracja działała.',
    'discord_invalid_api_url' => 'Wygląda na to, że twój API URL wygasł. Powiadom administratora, aby zaaktualizował URL API bota.',
    'test_bot_url' => 'Przetestuj URL Bota',
    'discord_bot_url_valid' => 'Adres URL Bota jest nieprawidłowy.',
    'discord_cannot_interact' => 'Bot nie może integrować z rolami użytkowników. Sprawdź czy rola bota jest na pewno nad rolami użytkowników.',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'An internal bot error occured.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API key is invalid',
    'discord_bot_error_invrole' => 'Provided Role ID is invalid.',
    'discord_bot_error_hierarchy' => 'The bot cannot edit this user\'s roles.',

    // API Errors
    'discord_integration_disabled' => 'Integracja Discord jest wyłączona.',
    'unable_to_set_discord_id' => 'Nie można ustawić ID Discord.',
    'unable_to_set_discord_bot_url' => 'Unable to set Discord bot URL',
    'provide_one_discord_settings' => 'Please provide at least one of the following: "url", "guild_id"',
    'no_pending_verification_for_token' => 'There are no verifications pending under the supplied token.',
    'unable_to_update_discord_username' => 'Unable to update Discord username.',
    'unable_to_update_discord_roles' => 'Unable to update Discord roles list.',
    'unable_to_update_discord_bot_username' => 'Unable to update Discord bot username.',

    // API Success
    'discord_id_set' => 'ID Discord ustawione pomyślnie',
    'discord_settings_updated' => 'Discord settings updated successfully',
    'discord_usernames_updated' => 'Discord usernames updated successfully',

    // User Settings
    'discord_username' => 'Discord Username',
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
