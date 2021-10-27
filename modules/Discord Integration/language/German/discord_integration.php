<?php

// German

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Discord-Integration aktivieren?',
    'discord_role_id' => 'Discord Rollen ID',
    'discord_role_id_numeric' => 'Die ID der Discord Rolle muss numerisch sein.',
    'discord_role_id_length' => 'Die Discord Rollen ID muss 18 Stellen lang sein.',
    'discord_guild_id' => 'Discord Server ID',
    'discord_widget_theme' => 'Discord Widget Theme',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Bitte stellen Sie sicher, dass Ihre Discord ID 18 Zeichen lang ist.',
    'discord_id_numeric' => 'Bitte stellen Sie sicher, dass Ihre Discord ID numerisch ist (nur Zahlen)..',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Die Discord-Integration kann erst aktiviert werden, wenn Sie den Bot eingerichtet haben. Für Informationen klicken Sie bitte  <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click hier</a>.',
    'discord_bot_setup' => 'Bot Setup',
    'discord_integration_not_setup' => 'Die Discord-Integration ist nicht eingerichtet',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Ungültiger Anfragetext.',
    'discord_bot_error_error' => 'Ein interner Bot-Fehler ist aufgetreten.',
    'discord_bot_error_invguild' => 'Vorausgesetzt, die Gilden-ID ist ungültig oder der Bot ist nicht darin.',
    'discord_bot_error_invuser' => 'Die angegebene Benutzer-ID ist ungültig oder befindet sich nicht in der angegebenen Gilde.',
    'discord_bot_error_notlinked' => 'Der Bot ist für die angegebene Gilden-ID nicht mit dieser Website verlinkt.',
    'discord_bot_error_unauthorized' => 'Der Website-API-Schlüssel ist ungültig',
    'discord_bot_error_invrole' => 'Die angegebene Rollen-ID ist ungültig.',
    'discord_bot_error_hierarchy' => 'The bot cannot edit this user\'s roles.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Die Discord-Integration ist deaktiviert.',
    'unable_to_set_discord_id' => 'Discord ID kann nicht festgelegt werden.',
    'unable_to_set_discord_bot_url' => 'Discord-Bot-URL kann nicht festgelegt werden',
    'provide_one_discord_settings' => 'Bitte geben Sie mindestens eine der folgenden Angaben an: "url", "guild_id"',
    'no_pending_verification_for_token' => 'Unter dem bereitgestellten Token stehen keine Überprüfungen aus.',
    'unable_to_update_discord_username' => 'Discord-Benutzername kann nicht aktualisiert werden.',
    'unable_to_update_discord_roles' => 'Discord-Rollen-Liste kann nicht aktualisiert werden.',
    'unable_to_update_discord_bot_username' => 'Discord Bot-Benutzername kann nicht aktualisiert werden.',

    // API Success
    'discord_id_set' => 'Discord ID erfolgreich eingestellt',
    'discord_settings_updated' => 'Discord Einstellungen erfolgreich aktualisiert',
    'discord_usernames_updated' => 'Discord-Benutzernamen wurden erfolgreich aktualisiert',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Verbunden',
    'not_linked' => 'Nicht verbunden',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Die Verknüpfung Ihrer Discord-Benutzer-ID wurde erfolgreich aufgehoben.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'steht aus',
    'discord_id_taken' => 'Diese Discord ID wurde bereits vergeben.',
    'discord_invalid_id' => 'Diese Discord-Benutzer-ID ist ungültig.',
    'discord_already_pending' => 'Sie haben bereits eine ausstehende Überprüfung.',
    'discord_database_error' => 'Die Nameless Link-Datenbank ist derzeit nicht verfügbar. Bitte versuchen Sie es später noch einmal.',
    'discord_communication_error' => 'Bei der Kommunikation mit dem Discord Bot ist ein Fehler aufgetreten. Bitte stellen Sie sicher, dass der Bot ausgeführt wird und Ihre Bot-URL korrekt ist.',
    'discord_unknown_error' => 'Beim Synchronisieren der Discord-Rollen ist ein unbekannter Fehler aufgetreten. Bitte wenden Sie sich an einen Administrator.',
    'discord_id_help' => 'Informationen dazu, wo Discord IDs zu finden sind, finden Sie unter <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>'
];
