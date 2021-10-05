<?php

// French

$language = [

    // Misc
    'discord_id' => 'ID du serveur discord',
    'discord_widget_theme' => 'Thème Discord Widget',
    'discord_bot_must_be_setup' => 'Vous ne pouvez pas activer l\'intégration Discord avant d\'avoir configuré le bot. Pour obtenir des informations, veuillez <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">cliquez ici</a>.',
    'discord_bot_setup' => 'Configuration du bot',
    'discord_integration_not_setup' => 'L\'intégration de Discord n\'est pas configurée',
    'discord_hooks' => 'Discord Hooks',
    'no_hooks_yet' => 'Il n\'y a pas encore de webhooks.',
    'discord_hooks_info' => 'Envoyez un message à un canal Discord lorsque quelque chose se produit sur votre site. Créer un webhook Discord dans votre onglet Configuration du serveur Discord -> Webhooks.',
    'discord_hook_url' => 'URL Discord webhook',
    'discord_hook_events' => 'Activation des événements webhook Discord (Ctrl+clic pour sélectionner plusieurs événements)',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Contenu de la demande non valide.',
    'discord_bot_error_error' => 'Une erreur interne du bot s\'est produite.',
    'discord_bot_error_invguild' => 'L\'ID de la guilde fournie n\'est pas valide, ou le bot n\'en fait pas partie..',
    'discord_bot_error_invuser' => 'L\'ID utilisateur fourni n\'est pas valide, ou n\'est pas dans la Guilde spécifiée..',
    'discord_bot_error_notlinked' => 'Le bot n\'est pas lié à ce site pour l\'identifiant Guild fourni..',
    'discord_bot_error_unauthorized' => 'La clé API du site web n\'est pas valide',
    'discord_bot_error_invrole' => 'L\'ID de rôle fourni n\'est pas valide.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'provide_one_discord_settings' => 'Veuillez fournir au moins un des éléments suivants : "url", "guild_id".',
    'no_pending_verification_for_token' => 'Il n\'y a pas de vérifications en cours sous le jeton fourni.',
    'unable_to_update_discord_username' => 'Impossible de mettre à jour le nom d\'utilisateur Discord.',
    'unable_to_update_discord_roles' => 'Impossible de mettre à jour la liste des rôles Discord.',
    'unable_to_update_discord_bot_username' => 'Impossible de mettre à jour le nom d\'utilisateur du robot Discord.',

    // API Success
    'discord_settings_updated' => 'Les paramètres de Discord ont été mis à jour avec succès',
    'discord_usernames_updated' => 'Les noms d\'utilisateur Discord ont été mis à jour avec succès',

    // User Settings
    'discord_username' => 'Nom d\'utilisateur Discord',
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
