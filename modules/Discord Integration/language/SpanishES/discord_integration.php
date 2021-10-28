<?php

// SpanishES

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => '¿Activar la integración de Discord?',
    'discord_role_id' => 'ID del rol de Discord',
    'discord_role_id_numeric' => 'La ID del rol de Discord debe ser numérica.',
    'discord_role_id_length' => 'La ID del rol de Discord debe tener 18 dígitos.',
    'discord_guild_id' => 'ID del servidor de Discord',
    'discord_widget_theme' => 'Tema del Widget de Discord',
    'discord_id_length' => 'Por favor, asegúrate de que la ID de Discord tiene 18 caracteres.',
    'discord_id_numeric' => 'Por favor, asegúrese de que su ID de Discord es numérica (sólo números).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'No se puede habilitar la integración de Discord hasta que no se haya configurado el bot. Para obtener información, por favor <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">haga clic aquí</a>.',
    'discord_bot_setup' => '¿Bot instalado?',
    'discord_integration_not_setup' => 'La integración de Discord no está configurada',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Cuerpo de la solicitud no válido.',
    'discord_bot_error_error' => 'Se ha producido un error interno del bot.',
    'discord_bot_error_invguild' => 'Siempre que la ID del servidor no sea válida, o el bot no esté en él.',
    'discord_bot_error_invuser' => 'La ID de usuario proporcionada no es válida, o no está en el servidor especificado.',
    'discord_bot_error_notlinked' => 'El bot no está vinculado a este sitio web para la ID del servidor proporcionada.',
    'discord_bot_error_unauthorized' => 'La clave API del sitio web no es válida',
    'discord_bot_error_invrole' => 'La ID del rol proporcionado no es válida.',
    'discord_bot_check_logs' => 'Debería comprobar si hay un error más específico (si existe) en "Seguridad -> Todos los registros"',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'La integración de Discord está desactivada.',
    'unable_to_set_discord_id' => 'No se puede establecer la ID de Discord.',
    'unable_to_set_discord_bot_url' => 'No se puede establecer la URL del bot de Discord',
    'provide_one_discord_settings' => 'Por favor, proporcione al menos uno de los siguientes datos: "url", "guild_id"',
    'no_pending_verification_for_token' => 'No hay verificaciones pendientes bajo el token suministrado.',
    'unable_to_update_discord_username' => 'No se puede actualizar el nombre de usuario de Discord.',
    'unable_to_update_discord_roles' => 'No se puede actualizar la lista de roles de Discord.',
    'unable_to_update_discord_bot_username' => 'No se puede actualizar el nombre de usuario del bot de Discord.',

    // API Success
    'discord_id_set' => 'ID de Discord establecida con éxito',
    'discord_settings_updated' => 'La configuración de Discord se ha actualizado con éxito',
    'discord_usernames_updated' => 'Los nombres de usuario de Discord se han actualizado con éxito',

    // User Settings
    'discord_link' => 'Discord Link',
    'linked' => 'Vinculado',
    'not_linked' => 'No vinculado',
    'discord_id' => 'ID de usuario de Discord',
    'discord_id_unlinked' => 'Se ha desvinculado con éxito su ID de usuario de Discord.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pendiente',
    'discord_id_taken' => 'Esa ID de Discord ya ha sido tomada.',
    'discord_invalid_id' => 'Esa ID de usuario de Discord no es válida.',
    'discord_already_pending' => 'Ya tienes una verificación pendiente.',
    'discord_database_error' => 'La base de datos de Nameless Link no funciona actualmente. Por favor, inténtelo más tarde.',
    'discord_communication_error' => 'Se ha producido un error al comunicarse con el Bot de Discord. Por favor, asegúrate de que el bot se está ejecutando y de que la URL de tu bot es correcta.',
    'discord_unknown_error' => 'Se ha producido un error desconocido al sincronizar los roles de Discord. Por favor, póngase en contacto con un administrador.',
    'discord_id_help' => 'Para obtener información sobre dónde encontrar ID\ de Discordia, por favor lea <a href="https://support.discord.com/hc/es-es/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">esto.</a>'
];
