<?php

// Czech

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Povolit integraci Discordu?',
    'discord_role_id' => 'ID Discord role',
    'discord_role_id_numeric' => 'ID Discord role může obsahovat pouze čísla.',
    'discord_role_id_length' => 'ID Discord role musí být dlouhé 18 znaků.',
    'discord_guild_id' => 'ID Discord serveru',
    'discord_widget_theme' => 'Téma Discord widgetu',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Ujistěte se, že vaše Discord ID je dlouhé 18 znaků.',
    'discord_id_numeric' => 'Ujistěte se, že vaše Discord ID obsahuje pouze čísla.',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Nelze povolit integraci Discordu, dokud nenastavíte bota. Pro více informací <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">klikněte sem</a>.',
    'discord_bot_setup' => 'Bot nastaven?',
    'discord_integration_not_setup' => 'Integrace Discordu nenastavena',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Neplatné tělo žádosti.',
    'discord_bot_error_error' => 'Vyskytla se interní chyba bota.',
    'discord_bot_error_invguild' => 'Zadané ID serveru je neplatné, nebo v něm není bot.',
    'discord_bot_error_invuser' => 'Zadané ID uživatele je neplatné, nebo uživatel není v daném serveru.',
    'discord_bot_error_notlinked' => 'Bot není propojen s tímto webem u zadaného ID serveru.',
    'discord_bot_error_unauthorized' => 'API klíč webu je neplatný',
    'discord_bot_error_invrole' => 'Zadané ID role je neplatné.',
    'discord_bot_error_hierarchy' => 'The bot cannot edit this user\'s roles.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Integrace Discordu je zakázána.',
    'unable_to_set_discord_id' => 'Nepodařilo se nastavit ID Discordu.',
    'unable_to_set_discord_bot_url' => 'Nepodařilo se nastavit URL Discord bota',
    'provide_one_discord_settings' => 'Zadejte alespoň jedno z následujících: "url", "guild_id"',
    'no_pending_verification_for_token' => 'U poskytnutého tokenu nejsou žádná čekající ověření.',
    'unable_to_update_discord_username' => 'Nepodařilo se aktualizovat uživatelské jméno na Discordu.',
    'unable_to_update_discord_roles' => 'Nepodařilo se aktualizovat seznam rolí na Discordu.',
    'unable_to_update_discord_bot_username' => 'Nepodařilo se aktualizovat uživatelské jméno Discord bota.',

    // API Success
    'discord_id_set' => 'ID Discordu úspěšně nastaveno',
    'discord_settings_updated' => 'Nastavení Discordu úspěšně aktualizována',
    'discord_usernames_updated' => 'Uživatelská jména na Discordu úspěšně aktualizována',

    // User Settings
    'discord_link' => 'Propojení s Discordem',
    'linked' => 'Propojeno',
    'not_linked' => 'Nepropojeno',
    'discord_id' => 'Uživatelské Discord ID',
    'discord_id_unlinked' => 'Úspěšně jste odpojili své uživatelské ID.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Čekání',
    'discord_id_taken' => 'Toto Discord ID je již zabráno.',
    'discord_invalid_id' => 'Toto uživatelské Discord ID je neplatné.',
    'discord_already_pending' => 'Již máte čekající ověření.',
    'discord_database_error' => 'Databáze Nameless Link momentálně není dostupná. Zkuste to prosím znovu za chvíli.',
    'discord_communication_error' => 'Při komunikaci s Discord botem se vyskytla chyba. Ujistěte se, že bot běží a že URL bota je správná.',
    'discord_unknown_error' => 'Při synchronizaci Discord rolí se vyskytla neznámá chyba. Kontaktujte prosím správce.',
    'discord_id_help' => 'Pro informace, kde nalézt Discord ID, si přečtěte <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">toto.</a>'
];
