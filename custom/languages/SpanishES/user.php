<?php
/*
 *  Translated by ManiaNetwork
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Spanish/Spain Language - Users
 */

$language = array(
    /*
     *  Change this for the account validation message
     */
    'validate_account_command' => 'Para completar el registro, ejecuta el comando <strong>/verify {x}</strong> dentro del juego.', // Don't replace {x}

    /*
     *  User Related
     */
    'guest' => 'Invitado',
    'guests' => 'Invitados',

    // UserCP
    'user_cp' => 'Cuenta',
    'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i> <span class="mobile_only">Cuenta</span>',
    'overview' => 'Resumen',
    'user_details' => 'Detalles del usuario',
    'profile_settings' => 'Configuración del perfil',
    'successfully_logged_out' => 'Se ha cerrado la sesión con éxito.',
    'messaging' => 'Mensajería',
    'click_here_to_view' => 'Haga clic aquí para ver.',
    'alerts' => 'Avisos',
    'delete_all' => 'Borrar todo',
    'private_profile' => 'Perfil privado',
    'gif_avatar' => 'Subir un .gif como avatar personalizado',
    'placeholders' => 'Marcadores de posición',
    'no_placeholders' => 'No hay marcadores de posición',

    // Profile settings
    'field_is_required' => '{x} es necesario.', // Don't replace {x}
    'settings_updated_successfully' => 'La configuración se ha actualizado correctamente.',
    'password_changed_successfully' => 'La contraseña ha sido cambiada con éxito.',
    'change_password' => 'Cambiar la contraseña',
    'current_password' => 'Contraseña actual',
    'new_password' => 'Nueva contraseña',
    'confirm_new_password' => 'Confirme la nueva contraseña',
    'incorrect_password' => 'Su contraseña es incorrecta.',
    'two_factor_auth' => 'Autenticación de dos factores',
    'enabled' => 'Activado',
    'disabled' => 'Desactivado',
    'enable' => 'Activar',
    'disable' => 'Desactivar',
    'tfa_scan_code' => 'Escanee el siguiente código dentro de su aplicación de autenticación:',
    'tfa_code' => 'Si su dispositivo no tiene cámara o no puede escanear el código QR, introduzca el siguiente código:',
    'tfa_enter_code' => 'Introduzca el código que aparece en su aplicación de autenticación:',
    'invalid_tfa' => 'Código inválido, por favor inténtelo de nuevo.',
    'tfa_successful' => 'La autenticación de dos factores se ha configurado correctamente. A partir de ahora tendrás que autenticarte cada vez que te conectes.',
    'active_language' => 'Idioma activo',
    'active_template' => 'Plantilla activa',
    'timezone' => 'Zona horaria',
    'upload_new_avatar' => 'Subir un nuevo avatar',
    'nickname_already_exists' => 'El apodo que has elegido ya existe.',
    'change_email_address' => 'Cambiar la dirección de correo electrónico',
    'email_already_exists' => 'La dirección de correo electrónico que ha introducido ya existe.',
    'email_changed_successfully' => 'La dirección de correo electrónico se ha cambiado con éxito.',
    'avatar' => 'Avatar',
    'profile_banner' => 'Banner del perfil',
    'upload_profile_banner' => 'Subir banner del perfil',
    'upload' => 'Subir',
    'topic_updates' => 'Recibe correos electrónicos de los temas que sigues',
    'gravatar' => 'Utilizar Gravatar como avatar',

    // Alerts
    'user_tag_info' => 'Has sido etiquetado en un mensaje de {x}.', // Don't replace {x}
    'no_alerts' => 'No hay nuevos avisos',
    'view_alerts' => 'Ver avisos',
    '1_new_alert' => 'Tienes un nuevo aviso',
    'x_new_alerts' => 'Tienes {x} nuevos avisos', // Don't replace {x}
    'no_alerts_usercp' => 'No tienes ningún aviso.',

    // Registration
    'registration_check_email' => 'Gracias por registrarse. Por favor, compruebe sus correos electrónicos para un enlace de validación con el fin de completar su registro. Si no encuentra el correo electrónico, compruebe su carpeta de correo no deseado.',
    'username' => 'Nombre de usuario',
    'nickname' => 'Apodo',
    'minecraft_username' => 'Nombre de usuario en Minecraft',
    'email_address' => 'Dirección de correo electrónico',
    'email' => 'Correo electrónico',
    'password' => 'Contraseña',
    'confirm_password' => 'Confirme la contraseña',
    'i_agree' => 'Estoy de acuerdo',
    'agree_t_and_c' => 'He leído y acepto los <a href="{x}" target="_blank">términos y condiciones</a>..',
    'create_an_account' => 'Crear una cuenta',
    'terms_and_conditions' => 'Términos y condiciones',
    'validation_complete' => 'Su cuenta ha sido validada, ahora puede conectarse.',
    'validation_error' => 'Se ha producido un error desconocido al validar su cuenta, por favor, póngase en contacto con un administrador del sitio web.',
    'signature' => 'Firma',
    'signature_max_900' => 'Su firma debe tener un máximo de 900 caracteres.',

    // Registration - Authme
    'connect_with_authme' => 'Conecte su cuenta con AuthMe',
    'authme_help' => 'Por favor, introduce los datos de tu cuenta AuthMe dentro del juego. Si aún no tienes una cuenta dentro del juego, únete al servidor ahora y sigue las instrucciones que se te dan.',
    'unable_to_connect_to_authme_db' => 'No se puede conectar a la base de datos de AuthMe. Si este error persiste, póngase en contacto con un administrador.',
    'authme_account_linked' => 'Cuenta vinculada con éxito.',
    'authme_email_help_1' => 'Por último, introduzca su dirección de correo electrónico.',
    'authme_email_help_2' => 'Por último, introduzca su dirección de correo electrónico y elija un nombre para su cuenta.',

    // Registration errors
    'username_required' => 'Se requiere un nombre de usuario.',
    'email_required' => 'Se requiere una dirección de correo electrónico.',
    'password_required' => 'Se requiere una contraseña.',
    'mcname_required' => 'Se requiere un nombre de usuario de Minecraft.',
    'accept_terms' => 'Debe aceptar los términos y condiciones antes de registrarse.',
    'username_minimum_3' => 'Su nombre de usuario debe tener un mínimo de 3 caracteres.',
    'mcname_minimum_3' => 'Tu nombre de usuario de Minecraft debe tener un mínimo de 3 caracteres.',
    'password_minimum_6' => 'Su contraseña debe tener un mínimo de 6 caracteres.',
    'username_maximum_20' => 'Su nombre de usuario debe tener un máximo de 20 caracteres.',
    'mcname_maximum_20' => 'Tu nombre de usuario de Minecraft debe tener un máximo de 20 caracteres.',
    'passwords_dont_match' => 'Sus contraseñas no coinciden.',
    'username_mcname_email_exists' => 'Su nombre de usuario o dirección de correo electrónico ya existe.',
    'invalid_mcname' => 'Tu nombre de usuario de Minecraft no es válido.',
    'invalid_email' => 'Su correo electrónico no es válido.',
    'mcname_lookup_error' => 'Se ha producido un error de comunicación con los servidores de Mojang para verificar tu nombre de usuario. Por favor, inténtalo de nuevo más tarde.',
    'invalid_recaptcha' => 'Respuesta reCAPTCHA inválida.',
    'verify_account' => 'Verificar la cuenta',
    'verify_account_help' => 'Por favor, sigue las siguientes instrucciones para que podamos verificar que eres el propietario de la cuenta de Minecraft en cuestión.',
    'validate_account' => 'Validar la cuenta',
    'verification_failed' => 'Verificación fallida, por favor, inténtelo de nuevo.',
    'verification_success' => '¡Validado con éxito! Ya puede iniciar la sesión.',
    'authme_username_exists' => 'Su cuenta de Authme ya ha sido conectada al sitio web.',
    'uuid_already_exists' => 'Tu UUID ya existe, lo que significa que esta cuenta de Minecraft ya está registrada.',

    // Login
    'successful_login' => 'Ha iniciado sesión con éxito.',
    'incorrect_details' => 'Ha introducido datos incorrectos.',
    'inactive_account' => 'Su cuenta está inactiva. Por favor, compruebe sus correos electrónicos para un enlace de validación, incluso dentro de su carpeta de correo no deseado.',
    'account_banned' => 'La cuenta ha sido expulsada.',
    'forgot_password' => '¿Ha olvidado su contraseña?',
    'remember_me' => 'Recuérdame',
    'must_input_email' => 'Debe introducir una dirección de correo electrónico.',
    'must_input_username' => 'Debe introducir un nombre de usuario.',
    'must_input_password' => 'Debe introducir una contraseña.',
    'must_input_email_or_username' => 'You must input an email or username.',
    'email_or_username' => 'Email or Username',

    // Forgot password
    'forgot_password_instructions' => 'Por favor, introduzca su dirección de correo electrónico para que podamos enviarle más instrucciones para restablecer su contraseña.',
    'forgot_password_email_sent' => 'Si existe una cuenta con la dirección de correo electrónico, se ha enviado un correo electrónico con más instrucciones. Si no lo encuentras, prueba a comprobar la carpeta de correo no deseado.',
    'unable_to_send_forgot_password_email' => 'No se ha podido enviar el correo electrónico de olvido de contraseña. Por favor, póngase en contacto con un administrador.',
    'enter_new_password' => 'Confirme su dirección de correo electrónico e introduzca una nueva contraseña a continuación.',
    'incorrect_email' => 'La dirección de correo electrónico que ha introducido no coincide con la solicitud.',
    'forgot_password_change_successful' => 'Su contraseña ha sido cambiada con éxito. Ahora puede iniciar sesión.',

    // Profile pages
    'profile' => 'Perfil',
    'follow' => 'Seguir',
    'no_wall_posts' => 'Todavía no hay mensajes en el muro.',
    'change_banner' => 'Cambiar el banner',
    'post_on_wall' => 'Publicar en el muro de {x}', // Don't replace {x}
    'invalid_wall_post' => 'Por favor, asegúrese de que su mensaje tiene entre 1 y 10000 caracteres.',
    '1_reaction' => 'Una reacción',
    'x_reactions' => '{x} reacciones', // Don't replace {x}
    '1_like' => 'Un me gusta',
    'x_likes' => '{x} me gusta', // Don't replace {x}
    '1_reply' => 'Una respuesta',
    'x_replies' => '{x} respuestas', // Don't replace {x}
    'no_replies_yet' => 'Todavía no hay respuestas',
    'feed' => 'Muro',
    'about' => 'Acerca de',
    'reactions' => 'Reacciones',
    'replies' => 'Respuestas',
    'new_reply' => 'Nueva respuesta',
    'registered' => 'Registrado:',
    'registered_x' => 'Registrado: {x}',
    'last_seen' => 'Visto por última vez:',
    'last_seen_x' => 'Visto por última vez: {x}', // Don't replace {x}
    'new_wall_post' => '{x} ha publicado un mensaje en tu perfil.',
    'couldnt_find_that_user' => 'No se pudo encontrar a ese usuario.',
    'block_user' => 'Bloquear usuario',
    'unblock_user' => 'Desbloquear usuario',
    'confirm_block_user' => '¿Estás seguro de que quieres bloquear a este usuario? No podrá enviarte mensajes privados ni etiquetarte en las publicaciones.',
    'confirm_unblock_user' => '¿Estás seguro de que quieres desbloquear a este usuario? Podrá enviarte mensajes privados y etiquetarte en las publicaciones.',
    'user_blocked' => 'Usuario bloqueado.',
    'user_unblocked' => 'Usuario desbloqueado.',
    'views' => 'Visitas del perfil:',
    'private_profile_page' => '¡Este es un perfil privado!',
    'new_wall_post_reply' => '{x} ha respondido a tu mensaje en el perfil de {y}.', // Don't replace {x} or {y}
    'new_wall_post_reply_your_profile' => '{x} ha respondido a tu mensaje en tu perfil.', // Don't replace {x}
    'no_about_fields' => 'Este usuario aún no ha añadido ningún campo para "Acerca de".',
    'reply' => 'Responder',
    'discord_username' => 'Nombre de usuario en Discord',

    // Reports
    'invalid_report_content' => 'No se ha podido crear el informe. Por favor, asegúrese de que el motivo del informe tiene entre 2 y 1024 caracteres.',
    'report_post_content' => 'Por favor, introduzca un motivo para su informe',
    'report_created' => 'Informe creado con éxito',

    // Messaging
    'no_messages' => 'No hay mensajes nuevos',
    'no_messages_full' => 'No tienes ningún mensaje.',
    'view_messages' => 'Ver mensajes',
    '1_new_message' => 'Tienes un mensaje nuevo',
    'x_new_messages' => 'Tienes {x} mensajes nuevos', // Don't replace {x}
    'new_message' => 'Nuevo mensaje',
    'message_title' => 'Título del mensaje',
    'to' => 'Para',
    'separate_users_with_commas' => 'Separe los usuarios con comas',
    'title_required' => 'Introduzca un título',
    'content_required' => 'Por favor, introduzca algún contenido',
    'users_to_required' => 'Por favor, introduzca algunos destinatarios del mensaje',
    'cant_send_to_self' => '¡No puedes enviarte un mensaje a ti mismo!',
    'title_min_2' => 'El título debe tener un mínimo de 2 caracteres',
    'content_min_2' => 'El título debe tener un mínimo de 2 caracteres',
    'title_max_64' => 'El título debe tener un máximo de 64 caracteres',
    'content_max_20480' => 'El contenido debe tener un máximo de 20480 caracteres',
    'max_pm_10_users' => 'Sólo puedes enviar un mensaje a un máximo de 10 usuarios',
    'message_sent_successfully' => 'Mensaje enviado con éxito',
    'participants' => 'Participantes',
    'last_message' => 'Último mensaje',
    'by' => 'por',
    'leave_conversation' => 'Dejar la conversación',
    'confirm_leave' => '¿Estás seguro de que quieres dejar esta conversación?',
    'one_or_more_users_blocked' => 'No se pueden enviar mensajes privados al menos a un miembro de la conversación.',
    'messages' => 'Mensajes',
    'latest_profile_posts' => 'Últimos mensajes del perfil',
    'no_profile_posts' => 'No hay mensajes en el perfil.',

    /*
     *  Infractions area
     */
    'you_have_been_banned' => '¡Has sido expulsado!',
    'you_have_received_a_warning' => '¡Has recibido una advertencia!',
    'acknowledge' => 'Condenado',

    /*
     *  Hooks
     */
    'user_x_has_registered' => '¡{x} se ha unido a ' . SITE_NAME . '!',
    'user_x_has_validated' => '¡{x} ha validado su cuenta!',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Vinculado',
    'not_linked' => 'No vinculado',
    'discord_id' => 'ID de usuario de Discord',
    'discord_id_unlinked' => 'Se ha desvinculado con éxito su ID de usuario de Discord.',
    'discord_id_confirm' => 'Please run the command "/verify token:{token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pendiente',
    'discord_id_taken' => 'Esa ID de Discord ya ha sido tomada.',
    'discord_invalid_id' => 'Esa ID de usuario de Discord no es válida.',
    'discord_already_pending' => 'Ya tienes una verificación pendiente.',
    'discord_database_error' => 'La base de datos de Nameless Link no funciona actualmente. Por favor, inténtelo más tarde.',
    'discord_communication_error' => 'Se ha producido un error al comunicarse con el Bot de Discord. Por favor, asegúrate de que el bot se está ejecutando y de que la URL de tu bot es correcta.',
    'discord_unknown_error' => 'Se ha producido un error desconocido al sincronizar los roles de Discord. Por favor, póngase en contacto con un administrador.',
    'discord_id_help' => 'Para obtener información sobre dónde encontrar ID\ de Discordia, por favor lea <a href="https://support.discord.com/hc/es-es/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">esto.</a>'
);
