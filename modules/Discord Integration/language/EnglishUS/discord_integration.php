<?php

// EnglishUS

$language = [

    // Misc
    'discord_id' => 'Discord Server ID',
    'discord_widget_theme' => 'Discord Widget Theme',
    'discord_id_length' => 'Please ensure your Discord ID is 18 characters long.',
    'discord_id_numeric' => 'Please ensure your Discord ID is numeric (Numbers only).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Cannot enable Discord Integration until you have set up the bot. For information, please <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click here</a>.',
    'discord_bot_setup' => 'Bot set up?',
    'discord_integration_not_setup' => 'Discord integration is not set up',
    'discord' => 'Discord',
    'enable_discord_integration' => 'Enable Discord integration?',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'Discord Role ID must be numeric.',
    'discord_role_id_length' => 'Discord Role ID must be 18 digits long.',
    'discord_settings_updated' => 'Your Discord settings were updated successfully.',
    'discord_guild_id_required' => 'Please enter your Discord Server ID for Discord integration to work.',
    'discord_bot_url' => 'Discord Bot URL',
    'discord_bot_url_info' => 'The location which your custom instance of the Nameless Link bot. Only change this value if you know what you are doing!',
    'discord_bot_url_required' => 'Please enter your Bot URL for Discord integration to work.',
    'discord_invalid_api_url' => 'Looks like your API URL has changed. Please get the guild owner to update the URL with the Nameless Link bot.',
    'test_bot_url' => 'Test Bot URL',
    'discord_bot_url_valid' => 'Your Bot URL is valid.',
    'discord_cannot_interact' => 'The Nameless Link bot cannot interact with this user in Discord. Is their role higher than the Nameless Link role?',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'An internal bot error occured.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API key is invalid',
    'discord_bot_error_invrole' => 'Provided Role ID is invalid.',
    'discord_bot_error_hierarchy' => 'The bot cannot edit this user\'s roles.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Discord integration is disabled.',
    'unable_to_set_discord_id' => 'Unable to set Discord ID.',
    'unable_to_set_discord_bot_url' => 'Unable to set Discord bot URL',
    'provide_one_discord_settings' => 'Please provide at least one of the following: "url", "guild_id"',
    'no_pending_verification_for_token' => 'There are no verifications pending under the supplied token.',
    'unable_to_update_discord_username' => 'Unable to update Discord username.',
    'unable_to_update_discord_roles' => 'Unable to update Discord roles list.',
    'unable_to_update_discord_bot_username' => 'Unable to update Discord bot username.',

    // API Success
    'discord_id_set' => 'Discord ID set successfully',
    'discord_settings_updated' => 'Discord settings updated successfully',
    'discord_usernames_updated' => 'Discord usernames updated successfully',

    // User Settings
    'discord_username' => 'Discord Username',

    // Discord
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