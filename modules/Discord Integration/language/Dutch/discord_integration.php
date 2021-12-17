<?php

// Dutch

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Discordintegratie inschakelen?',
    'discord_role_id' => 'Discord Rol ID',
    'discord_role_id_numeric' => 'Discord Rol ID moet numeriek zijn.',
    'discord_role_id_length' => 'Discord Rol ID moet 18 cijfers lang zijn.',
    'discord_guild_id' => 'Discord Server ID',
    'discord_widget_theme' => 'Discord Widget Thema',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Zorg ervoor dat uw Discord ID 18 karakters lang is.',
    'discord_id_numeric' => 'Zorg ervoor dat uw Discord ID numeriek is (alleen cijfers).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Cannot enable Discord Integration until you have setup the bot. For information, please <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click here</a>.',
    'discord_bot_setup' => 'Bot Setup',
    'discord_integration_not_setup' => 'Discord Integration is not set up',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'Er is een interne fout opgetreden.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API-sleutel is ongeldig',
    'discord_bot_error_invrole' => 'Opgegeven rol-ID is ongeldig.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Discord integration is disabled.',
    'unable_to_set_discord_id' => 'Kan geen Discord ID instellen.',
    'unable_to_set_discord_bot_url' => 'Unable to set Discord bot URL',
    'provide_one_discord_settings' => 'Please provide at least one of the following: "url", "guild_id"',
    'no_pending_verification_for_token' => 'There are no verifications pending under the supplied token.',
    'unable_to_update_discord_username' => 'Unable to update Discord username.',
    'unable_to_update_discord_roles' => 'Unable to update Discord roles list.',
    'unable_to_update_discord_bot_username' => 'Unable to update Discord bot username.',

    // API Success
    'discord_id_set' => 'Discord ID met succes ingesteld',
    'discord_settings_updated' => 'Discord settings updated successfully',
    'discord_usernames_updated' => 'Discord usernames updated successfully',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Gekoppeld',
    'not_linked' => 'Niet Gekoppeld',
    'discord_id' => 'Discord Gebruikers ID',
    'discord_id_unlinked' => 'Succesvol ontkoppeld van uw Discord Gebruikers-ID.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Wachtende',
    'discord_id_taken' => 'Die Discord Gebruikers-ID is al ingenomen.',
    'discord_invalid_id' => 'Dat Discord Gebruikers-ID is ongeldig.',
    'discord_already_pending' => 'Je hebt al een lopende verificatie.',
    'discord_database_error' => 'De Nameless Link database is op dit moment offline. Probeer het later nog eens.',
    'discord_communication_error' => 'Er was een fout in de communicatie met de Discord Bot. Zorg ervoor dat de bot werkt en dat uw Bot-URL correct is.',
    'discord_unknown_error' => 'Er was een onbekende fout bij het synchroniseren van de rollen van Discord. Neem contact op met een beheerder.',
    'discord_id_help' => 'Voor informatie over waar je de Discord ID\'s kunt vinden, lees alsjeblieft <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">dit.</a>'
];
