<?php

// lang

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Aktivera Discord-integration?',
    'discord_role_id' => 'Discord Roll ID',
    'discord_role_id_numeric' => 'Discord-roll-ID måste vara numeriskt.',
    'discord_role_id_length' => 'Discord-roll-ID måste vara 18 siffror långt.',
    'discord_guild_id' => 'Discord Server ID',
    'discord_widget_theme' => 'Discord Widget Theme',
    'discord_id_length' => 'Vänligen se till att ditt Discord ID is 18 bokstäver långt.',
    'discord_id_numeric' => 'Vängligen se till att ditt Discord ID är numeriskt. (Endast Nummer).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Kan inte aktivera Discord Integrationen tills att du har ställt in botten. För mer information, vänligen <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">klicka här</a>.',
    'discord_bot_setup' => 'Bot Setup',
    'discord_integration_not_setup' => 'Discord Integrationen är inte inställd.',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Ogiltig begäran.',
    'discord_bot_error_error' => 'Ett internt botfel uppstod.',
    'discord_bot_error_invguild' => 'Förutsatt att Guild-ID är ogiltigt, eller så finns inte botten i det.',
    'discord_bot_error_invuser' => 'Förutsatt att användar-ID är ogiltigt eller inte finns i specificerad guild.',
    'discord_bot_error_notlinked' => 'Bot är inte länkad till den här webbplatsen för angivet guild-ID.',
    'discord_bot_error_unauthorized' => 'Webbplatsens API-nyckel är ogiltig',
    'discord_bot_error_invrole' => 'Angivet roll-ID är ogiltigt.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Discord-integrering är inaktiverad.',
    'unable_to_set_discord_id' => 'Det går inte att ställa in Discord-ID.',
    'unable_to_set_discord_bot_url' => 'Det gick inte att ställa in Discord bot URL',
    'provide_one_discord_settings' => 'Ange minst ett av följande: "url", "guild_id"',
    'no_pending_verification_for_token' => 'Det finns inga bekräftelser under den medföljande token.',
    'unable_to_update_discord_username' => 'Det går inte att uppdatera Discord-användarnamnet.',
    'unable_to_update_discord_roles' => 'Det går inte att uppdatera Discord-rollistan.',
    'unable_to_update_discord_bot_username' => 'Det gick inte att uppdatera Discord bot-användarnamnet.',

    // API Success
    'discord_id_set' => 'Discord ID har lyckats',
    'discord_settings_updated' => 'Discord-inställningar uppdaterades framgångsrikt',
    'discord_usernames_updated' => 'Discord-användarnamn uppdaterades framgångsrikt',

    // User Settings
    'discord_link' => 'Discord Länk',
    'linked' => 'Länkad',
    'not_linked' => 'Inte länkad',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Du har tagit bort länken till ditt Discord-användar-ID.',
    'discord_id_confirm' => 'Vänligen kör "/verify {token}" kommandot på Discord för att slutföra länkningen till ditt Discord-konto.',
    'pending_link' => 'Väntar',
    'discord_id_taken' => 'Det Discord-ID har redan tagits.',
    'discord_invalid_id' => 'Discord-användar-ID är ogiltigt.',
    'discord_already_pending' => 'Du har redan en väntande verifiering.',
    'discord_database_error' => 'Nameless Link-databasen är för närvarande nere. Vänligen försök igen senare.',
    'discord_communication_error' => 'Det uppstod ett fel när du kommunicerade med Discord Botten. Se till att botten körs och att din webbadress är korrekt. ',
    'discord_unknown_error' => 'Det uppstod ett okänt fel vid synkronisering av Discord-roller. Vänligen kontakta en administratör. ',
    'discord_id_help' => 'För information om var du hittar Discord ID\'s, vänligen läs <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">detta.</a>'
];
