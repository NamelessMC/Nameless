<?php

// Norwegian

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Aktiver Discord-integrering?',
    'discord_role_id' => 'Discord rolle-ID',
    'discord_role_id_numeric' => 'Discord rolle-ID må være numerisk',
    'discord_role_id_length' => 'Discord rolle-ID må inneholde 18 tegn.',
    'discord_guild_id' => 'Discord Server-ID',
    'discord_widget_theme' => 'Discord Widget-Tema',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Pass på at Discord-ID-en inneholder 18 tegn.',
    'discord_id_numeric' => 'Pass på at Discord-ID-en er numerisk (kun tall).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Kan ikke aktivere Discord-integrering før du har satt opp boten. For informasjon, vennligst <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">klikk her</a>.',
    'discord_bot_setup' => 'Bot-oppsett',
    'discord_integration_not_setup' => 'Discord-integrering er ikke satt opp',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Ugyldig forespørseltekst',
    'discord_bot_error_error' => 'En intern botfeil oppstod.',
    'discord_bot_error_invguild' => 'Oppgitte guild-ID  er ugyldig, eller så er ikke boten i den.',
    'discord_bot_error_invuser' => 'Oppgitte bruker-ID er ugyldig, eller så er ikke brukeren i den spesifiserte guilden.',
    'discord_bot_error_notlinked' => 'Boten er ikke koblet til nettsiden for den oppgitte guild-id.',
    'discord_bot_error_unauthorized' => 'Nettside-API-nøkkel er ugyldig.',
    'discord_bot_error_invrole' => 'Oppgitte rolle-id er ugyldig.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'Discord-integrering er deaktivert.',
    'unable_to_set_discord_id' => 'Kunne ikke oppdatere Discord-IDen.',
    'unable_to_set_discord_bot_url' => 'Kunne ikke sette Discord-bot-URLen',
    'provide_one_discord_settings' => 'Vennligst oppgi minst ett av følgende: "url, "guild_id"',
    'no_pending_verification_for_token' => 'Ingen verifiseringer venter for den medfølgende koden.',
    'unable_to_update_discord_username' => 'Kunne ikke oppdatere Discord-brukernavn.',
    'unable_to_update_discord_roles' => 'Kunne ikke oppdatere Discord-rolleliste.',
    'unable_to_update_discord_bot_username' => 'Kunne ikke oppdatere Discord-botbrukernavn.',

    // API Success
    'discord_id_set' => 'Discord-ID-en har blitt endret.',
    'discord_settings_updated' => 'Discord-innstillinger har blitt oppdatert',
    'discord_usernames_updated' => 'Discord-brukernavn har blitt oppdatert',

    // User Settings
    'discord_link' => 'Discord-lenke',
    'linked' => 'Tilkoblet',
    'not_linked' => 'Ikke tilkoblet',
    'discord_id' => 'Discord-bruker-ID',
    'discord_id_unlinked' => 'Discord-bruker-ID-en din har blitt frakoblet.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Venter',
    'discord_id_taken' => 'Den oppgitte Discord-bruker-ID-en er allerede koblet til en annen bruker.',
    'discord_invalid_id' => 'Den oppgitte Discord-bruker-ID-en er ikke gyldig.',
    'discord_already_pending' => 'Du har allerede en verifiseringsforespørsel som venter.',
    'discord_database_error' => 'Nameless Link-databasen er nede for øyeblikket. Prøv igjen senere.',
    'discord_communication_error' => 'Det oppstod en feil under kommuniseringen med Discord-boten. Sjekk om boten er oppe, og at bot-URL-en er korrekt.',
    'discord_unknown_error' => 'Det oppstod en ukjent feil under synkroniseringen av Discord-rollene. Vennligst kontakt en administrator.',
    'discord_id_help' => 'For mer informasjon om hvordan man finner Discord-bruker-ID, vennligst les <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">denne artikkelen.</a>'
];
