<?php

// French

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Enable Discord integration?',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'Discord Role ID must be numeric.',
    'discord_role_id_length' => 'Discord Role ID must be 18 digits long.',
    'discord_guild_id' => 'ID du serveur discord',
    'discord_widget_theme' => 'Thème Discord Widget',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Please ensure your Discord ID is 18 characters long.',
    'discord_id_numeric' => 'Please ensure your Discord ID is numeric (Numbers only).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Vous ne pouvez pas activer l\'intégration Discord avant d\'avoir configuré le bot. Pour obtenir des informations, veuillez <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">cliquez ici</a>.',
    'discord_bot_setup' => 'Configuration du bot',
    'discord_integration_not_setup' => 'L\'intégration de Discord n\'est pas configurée',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Contenu de la demande non valide.',
    'discord_bot_error_error' => 'Une erreur interne du bot s\'est produite.',
    'discord_bot_error_invguild' => 'L\'ID de la guilde fournie n\'est pas valide, ou le bot n\'en fait pas partie..',
    'discord_bot_error_invuser' => 'L\'ID utilisateur fourni n\'est pas valide, ou n\'est pas dans la Guilde spécifiée..',
    'discord_bot_error_notlinked' => 'Le bot n\'est pas lié à ce site pour l\'identifiant Guild fourni..',
    'discord_bot_error_unauthorized' => 'La clé API du site web n\'est pas valide',
    'discord_bot_error_invrole' => 'L\'ID de rôle fourni n\'est pas valide.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
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
    'discord_settings_updated' => 'Les paramètres de Discord ont été mis à jour avec succès',
    'discord_usernames_updated' => 'Les noms d\'utilisateur Discord ont été mis à jour avec succès',

    // User Settings
    'discord_link' => 'Lien Discord',
    'linked' => 'Lié',
    'not_linked' => 'Non lié',
    'discord_id' => 'ID de l\'utilisateur Discord',
    'discord_id_unlinked' => 'Vous avez réussi à dissocier votre ID utilisateur Discord.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'En attente',
    'discord_id_taken' => 'Cet identifiant Discord a déjà été pris.',
    'discord_invalid_id' => 'Cet ID utilisateur Discord n\'est pas valide.',
    'discord_already_pending' => 'Vous avez déjà une vérification en cours.',
    'discord_database_error' => 'La base de données du lien Nameless est actuellement en panne. Veuillez réessayer plus tard.',
    'discord_communication_error' => 'Une erreur s\'est produite lors de la communication avec le robot Discord. Veuillez vous assurer que le robot est en cours d\'exécution et que votre URL de robot est correcte.',
    'discord_unknown_error' => 'Une erreur inconnue s\'est produite lors de la synchronisation des rôles Discord. Veuillez contacter un administrateur.',
    'discord_id_help' => 'Pour savoir où trouver les identifiants Discord, veuillez lire le <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">document</a>suivant.'
];
