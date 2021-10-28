<?php

// Danish

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Enable Discord integration?',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'Discord Role ID must be numeric.',
    'discord_role_id_length' => 'Discord Role ID must be 18 digits long.',
    'discord_guild_id' => 'Discord Server ID',
    'discord_widget_theme' => 'Discord Widget Theme',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Please ensure your Discord ID is 18 characters long.',
    'discord_id_numeric' => 'Please ensure your Discord ID is numeric (Numbers only).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Cannot enable Discord Integration until you have set up the bot. For information, please <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click here</a>.',
    'discord_bot_setup' => 'Bot set up?',
    'discord_integration_not_setup' => 'Discord integration is not set up',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'An internal bot error occured.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API key is invalid',
    'discord_bot_error_invrole' => 'Provided Role ID is invalid.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Discord integration er deaktiveret.',
    'unable_to_set_discord_id' => 'Discord ID kan ikke angives.',
    'unable_to_set_discord_bot_url' => 'Discord bot URL kunne ikke angives',
    'provide_one_discord_settings' => 'Angiv mindst en af følgende: "url", "guild_id"',
    'no_pending_verification_for_token' => 'Der er ingen afventende verificeringer under det leverede token.',
    'unable_to_update_discord_username' => 'Discord brugernavn kunne ikke opdateres.',
    'unable_to_update_discord_roles' => 'Kunne ikke opdatere Discord rollelisten.',
    'unable_to_update_discord_bot_username' => 'Kunne ikke opdatere Discord bot brugernavn.',

    // API Success
    'discord_id_set' => 'Discord ID sat',
    'discord_settings_updated' => 'Discord indstillinger opdateret',
    'discord_usernames_updated' => 'Discord brugernavne opdateret',

    // User Settings
    'discord_link' => 'Discord Link',
    'linked' => 'Linket',
    'not_linked' => 'Ikke Linket',
    'discord_id' => 'Discord Bruger ID',
    'discord_id_unlinked' => 'Dit Discord bruger-id blev afkoblet korrekt.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Afventer',
    'discord_id_taken' => 'Det Discord ID allerede er taget.',
    'discord_invalid_id' => 'Det Discord Bruger ID er ugyldigt.',
    'discord_already_pending' => 'Du har allerede en afventende bekræftelse.',
    'discord_database_error' => 'Nameless Link databasen er i øjeblikket nede. Prøv igen senere.',
    'discord_communication_error' => 'Der opstod en fejl under kommunikation med Discord Botten. Kontroller, at botten kører, og at din Bot URL er korrekt.',
    'discord_unknown_error' => 'Der opstod en ukendt fejl under synkronisering af Discord roller. Kontakt venligst en administrator.',
    'discord_id_help' => 'For information om, hvor du kan finde Discord ID\'s, læs venligst <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">denne.</a>'
];
