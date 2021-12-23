<?php

// Spanish

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => '¿Habilitar la integración de Discord?',
    'discord_role_id' => 'ID de rol de discord',
    'discord_role_id_numeric' => 'El ID de rol de Discord debe ser numérico.',
    'discord_role_id_length' => 'La ID del rol de discordia debe tener 18 dígitos.',
    'discord_guild_id' => 'ID del Servidor de Discord',
    'discord_widget_theme' => 'Tema del Widget de Discord',
    'discord_id_length' => 'Asegúrate de que tu ID de Discord tenga 18 caracteres.',
    'discord_id_numeric' => 'Asegúrese de que su ID de Discord sea numérica (No debe contener letras).',
    'discord_invite_info' => 'Para invitar al bot Nameless Link a tu servidor Discord, haz clic en <a target="_blank" href="https://namelessmc.com/discord-bot-invite">aquí</a>. A continuación, ejecuta el comando <code>/apiurl</code> para enlazar el bot con tu sitio web. También puedes <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">alojar el bot tú mismo</a>.',
    'discord_bot_must_be_setup' => 'No se puede habilitar la integración de Discord hasta que no se haya configurado el bot. Para obtener información, por favor <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">haga clic aquí</a>.',
    'discord_bot_setup' => 'Configuración del bot',
    'discord_integration_not_setup' => 'La integración de Discord no está configurada',
    'discord_username' => 'Nombre de usuario de Discord',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Cuerpo de solicitud no válido.',
    'discord_bot_error_error' => 'Ocurrió un error interno del bot.',
    'discord_bot_error_invguild' => 'El ID del servidor proporcionado no es válido o el bot no está en él.',
    'discord_bot_error_invuser' => 'El ID de usuario proporcionado no es válido o no pertenece al servidor especificado.',
    'discord_bot_error_notlinked' => 'El bot no está vinculado a este sitio web para la identificación del servidor proporcionada.',
    'discord_bot_error_unauthorized' => 'La clave de API del sitio web no es válida',
    'discord_bot_error_invrole' => 'El ID del role proporcionado no es válido.',
    'discord_bot_check_logs' => 'Debería comprobar si hay un error más específico (si existe) en StaffCP -> Seguridad -> Todos los registros.',
    'discord_bot_error_partsuccess' => 'El bot no pudo editar uno o más de los roles debido a una mala configuración de la jerarquía de Discord.',

    // API Errors
    'discord_integration_disabled' => 'La integración de discord está deshabilitada.',
    'unable_to_set_discord_id' => 'No se puede establecer la identificación de Discord.',
    'unable_to_set_discord_bot_url' => 'No se puede establecer la URL del bot de Discord',
    'provide_one_discord_settings' => 'Por favor, proporcione al menos uno de los siguientes datos: "url", "guild_id"',
    'no_pending_verification_for_token' => 'No hay verificaciones pendientes bajo el token suministrado.',
    'unable_to_update_discord_username' => 'No se puede actualizar el nombre de usuario de Discord.',
    'unable_to_update_discord_roles' => 'No se puede actualizar la lista de roles de Discord.',
    'unable_to_update_discord_bot_username' => 'No se puede actualizar el nombre de usuario del bot de Discord.',

    // API Success
    'discord_id_set' => 'ID de discord establecida correctamente.',
    'discord_settings_updated' => 'La configuración de discord se ha actualizado con éxito',
    'discord_usernames_updated' => 'Los nombres de usuario de discord se han actualizado con éxito',

    // User Settings
    'discord_link' => 'Discord Link',
    'linked' => 'Vinculado',
    'not_linked' => 'No está vinculado',
    'discord_id' => 'ID de usuario de Discord',
    'discord_id_unlinked' => 'Se ha desvinculado con éxito su ID de usuario de Discord.',
    'discord_id_confirm' => 'Por favor, ejecuta el comando "/verify {token}" en Discord para terminar de vincular tu cuenta de Discord.',
    'pending_link' => 'Pendiente',
    'discord_id_taken' => 'Ese ID de Discord ya ha sido tomado.',
    'discord_invalid_id' => 'Ese ID de usuario de Discord no es válido.',
    'discord_already_pending' => 'Ya tiene una verificación pendiente.',
    'discord_database_error' => 'La base de datos de Nameless Link no funciona actualmente. Por favor, inténtelo más tarde.',
    'discord_communication_error' => 'There was an error while communicating with the Discord Bot. Please ensure the bot is running and your Bot URL is correct.',
    'discord_unknown_error' => 'Se ha producido un error desconocido al sincronizar los roles de Discord. Por favor, póngase en contacto con un administrador.',
    'discord_id_help' => 'Para obtener información sobre dónde encontrar ID\ de Discord, por favor lea <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">esto.</a>'
];
