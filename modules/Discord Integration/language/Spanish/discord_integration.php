<?php

// Spanish

$language = [

    // Misc
    'discord_id' => 'ID del Servidor de Discord',
    'discord_widget_theme' => 'Tema del Discord Widget',
    'discord_id_length' => 'Asegúrate de que tu ID de Discord tenga 18 caracteres.',
    'discord_id_numeric' => 'Asegúrese de que su ID de Discord sea numérica (No debe contener letras).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Cannot enable Discord Integration until you have setup the bot. For information, please <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click here</a>.',
    'discord_bot_setup' => 'Bot Setup',
    'discord_integration_not_setup' => 'Discord Integration is not setup',
    'discord' => 'Discord',
    'enable_discord_integration' => '¿Habilitar la integración de Discord?',
    'discord_role_id' => 'ID de rol de discord',
    'discord_role_id_numeric' => 'El ID de rol de Discord debe ser numérico.',
    'discord_role_id_length' => 'La ID del rol de discordia debe tener 18 dígitos.',
    'discord_settings_updated' => 'La configuración de Discord se actualizó correctamente.',
    'discord_guild_id_required' => 'Ingrese su ID de servidor Discord para que la integración de Discord funcione.',
    'discord_bot_url' => 'URL de el bot de Discord',
    'discord_bot_url_info' => 'La ubicación en la que se encuentra su instancia personalizada del bot Nameless Link. ¡Solo cambie este valor si sabe lo que está haciendo!',
    'discord_bot_url_required' => 'Ingrese su URL de Bot para que la integración de Discord funcione.',
    'discord_invalid_api_url' => 'Parece que su URL de API ha cambiado. Solicite al propietario del gremio que actualice la URL con el bot de Enlace sin nombre.',
    'test_bot_url' => 'Probar URL del bot',
    'discord_bot_url_valid' => 'Tu URL de bot es válida.',
    'discord_cannot_interact' => 'El bot Nameless Link no puede interactuar con este usuario en Discord. ¿Su rol es más alto que el rol de Nameless Link?',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Cuerpo de solicitud no válido.',
    'discord_bot_error_error' => 'Ocurrió un error interno del bot.',
    'discord_bot_error_invguild' => 'El ID del servidor proporcionado no es válido o el bot no está en él.',
    'discord_bot_error_invuser' => 'El ID de usuario proporcionado no es válido o no pertenece al servidor especificado.',
    'discord_bot_error_notlinked' => 'El bot no está vinculado a este sitio web para la identificación del servidor proporcionada.',
    'discord_bot_error_unauthorized' => 'La clave de API del sitio web no es válida',
    'discord_bot_error_invrole' => 'El ID del role proporcionado no es válido.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'La integración de discord está deshabilitada.',
    'unable_to_set_discord_id' => 'No se puede establecer la identificación de Discord.',
    'unable_to_set_discord_bot_url' => 'Unable to set Discord bot URL',
    'provide_one_discord_settings' => 'Please provide at least one of the following: "url", "guild_id"',
    'no_pending_verification_for_token' => 'There are no verifications pending under the supplied token.',
    'unable_to_update_discord_username' => 'Unable to update Discord username.',
    'unable_to_update_discord_roles' => 'Unable to update Discord roles list.',
    'unable_to_update_discord_bot_username' => 'Unable to update Discord bot username.',

    // API Success
    'discord_id_set' => 'ID de discord establecida correctamente.',
    'discord_settings_updated' => 'Discord settings updated successfully',
    'discord_usernames_updated' => 'Discord usernames updated successfully',

    // User Settings
    'discord_username' => 'Discord Username',
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
