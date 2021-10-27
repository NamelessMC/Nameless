<?php

// Italian

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => "Abilitare l'integrazione con Discord?",
    'discord_role_id' => 'ID ruolo Discord',
    'discord_role_id_numeric' => "L'ID ruolo Discord deve essere numerico.",
    'discord_role_id_length' => "L'ID del ruolo Discord deve contenere 18 cifre.",
    'discord_guild_id' => 'ID Server Discord',
    'discord_widget_theme' => 'Tema Widget Discord',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Assicurati che il tuo ID Discord sia lungo 18 caratteri.',
    'discord_id_numeric' => 'Assicurati che il tuo ID Discord sia numerico (solo numeri).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Non è possibile abilitare l\'integrazione con Discord fino a che il bot non sarà configurato. Per informazioni, <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">clicca qui</a>.',
    'discord_bot_setup' => 'Configurazione del bot',
    'discord_integration_not_setup' => 'Discord Integration is not setup',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'An internal bot error occured.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API key is invalid',
    'discord_bot_error_invrole' => 'Provided Role ID is invalid.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => "L'integrazione di Discord è disabilitata.",
    'unable_to_set_discord_id' => "Impossibile impostare l'ID Discord.",
    'unable_to_set_discord_bot_url' => "Impossibile impostare l'URL del bot Discord",
    'provide_one_discord_settings' => 'Fornisci almeno uno dei seguenti: "url", "guild_id"',
    'no_pending_verification_for_token' => 'There are no verifications pending under the supplied token.',
    'unable_to_update_discord_username' => 'Unable to update Discord username.',
    'unable_to_update_discord_roles' => 'Unable to update Discord roles list.',
    'unable_to_update_discord_bot_username' => 'Unable to update Discord bot username.',

    // API Success
    'discord_id_set' => 'ID Discord impostato correttamente',
    'discord_settings_updated' => 'Impostazioni di Discord aggiornate con successo',
    'discord_usernames_updated' => 'Discord usernames updated successfully',

    // User Settings
    'discord_link' => 'Collegamento Discord',
    'linked' => 'Collegato',
    'not_linked' => 'Non collegato',
    'discord_id' => 'ID utente Discord',
    'discord_id_unlinked' => 'Scollegato con successo il tuo ID utente Discord.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'In attesa',
    'discord_id_taken' => "Quell'ID Discord è già stato preso.",
    'discord_invalid_id' => "Quell'ID utente Discord non è valido.",
    'discord_already_pending' => 'Hai già una verifica in sospeso.',
    'discord_database_error' => 'Il database dei collegamenti di Nameless è attualmente inattivo. Riprova più tardi.',
    'discord_communication_error' => "Si è verificato un errore durante la comunicazione con il Bot di Discord. Assicurati che il bot sia in esecuzione e che l'URL del tuo Bot sia corretto.",
    'discord_unknown_error' => 'Si è verificato un errore sconosciuto durante la sincronizzazione dei ruoli di Discord. Contatta un amministratore.',
    'discord_id_help' => 'Per informazioni su dove trovare gli ID Discord, leggi <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">questo.</a>'
];
