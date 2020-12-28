<?php
/*
 *  Translated by ManiaNetwork (Marck200 & Osiris)
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Spanish/Spain Language - Language version
 */

$language = array(
    /*
     *  Change this for the account validation message
     */
    'validate_account_command' => 'Para completar la validación, ejecute el comando <strong>/validate {x}</strong> en el servidor.', // Don't replace {x}

    /*
     *  User Related
     */
    'guest' => 'Invitado',
    'guests' => 'Invitados',

    // UserCP
    'user_cp' => 'Panel de usuario',
    'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i> <span class="mobile_only">Cuenta</span>',
    'overview' => 'Visión de conjunto',
    'user_details' => 'Detalles de usuario',
    'profile_settings' => 'Ajustes del perfil',
    'successfully_logged_out' => 'Ha salido con éxito.',
    'messaging' => 'Mensajes',
    'click_here_to_view' => 'Click aquí para ver.',
    'moderation' => 'Moderación',
    'administration' => 'Administración',
    'alerts' => 'Notificaciones',
    'delete_all' => 'Eliminar todos',
    'private_profile' => 'Perfil privado',
    'gif_avatar' => 'Upload .gif as custom avatar',

    // Profile settings
    'field_is_required' => '{x} es requerido.', // Don't replace {x}
    'settings_updated_successfully' => 'Configuración actualizada correctamente.',
    'password_changed_successfully' => 'Contraseña cambiada con éxito.',
    'change_password' => 'Cambiar la contraseña',
    'current_password' => 'Contraseña actual',
    'new_password' => 'Nueva contraseña',
    'confirm_new_password' => 'Confirmar nueva contraseña',
    'incorrect_password' => 'La contraseña es incorrecta.',
    'two_factor_auth' => 'Autenticación de dos factores',
    'enabled' => 'Activado',
    'disabled' => 'Desactivado',
    'enable' => 'Activar',
    'disable' => 'Desactivar',
    'tfa_scan_code' => 'Compruebe el siguiente código en su aplicación de autenticación:',
    'tfa_code' => 'Si su dispositivo no tiene una cámara o no puede escanear el código QR, introduzca el siguiente código:',
    'tfa_enter_code' => 'Ingrese el código que aparece en su aplicación de autenticación:',
    'invalid_tfa' => 'Código no válido. Por favor, vuelva a intentarlo.',
    'tfa_successful' => 'La autenticación de doble factor se ha configurado correctamente. Tendrá que autenticarse cada vez que inicie sesión de ahora en adelante.',
    'active_language' => 'Idioma',
    'active_template' => 'Plantilla activa',
    'timezone' => 'Zona Horaria',
    'upload_new_avatar' => 'Subir una nueva foto de perfil',
    'nickname_already_exists' => 'El nombre de usuario ya está en uso.',
    'change_email_address' => 'Cambiar dirección de correo electronico',
    'email_already_exists' => 'El correo electronico que ha introducido está en uso.',
    'email_changed_successfully' => 'La dirección de correo electrónico fue actualizada con éxito.',
    'avatar' => 'Foto de perfil',
    'profile_banner' => 'Banner del perfil',
    'upload_profile_banner' => 'Subir banner del perfil',
    'upload' => 'Subir',
    'topic_updates' => 'Get emails for topics you follow',
    'gravatar' => 'Use Gravatar as avatar',

    // Alerts
    'user_tag_info' => 'Has sido etiquetado en un post por {x}.', // Don't replace {x}
    'no_alerts' => 'No hay nuevas notificaciones',
    'view_alerts' => 'Ver notificaciones',
    '1_new_alert' => 'Tienes una nueva notifiación.',
    'x_new_alerts' => 'Tienes {x} nuevas notificaciones', // Don't replace {x}
    'no_alerts_usercp' => 'No tienes notificaciones.',

    // Registraton
    'registration_check_email' => '¡Gracias por registrarte! Revise su correo electrónico para obtener un enlace de validación para completar su registro. Si no encuentra el correo electrónico, compruebe su carpeta de spam.',
    'username' => 'Usuario',
    'nickname' => 'Nombre de Usuario',
    'minecraft_username' => 'Usuario de Minecraft',
    'email_address' => 'Correo electrónico',
    'email' => 'Correo',
    'password' => 'Contraseña',
    'confirm_password' => 'Confirmar contraseña',
    'i_agree' => 'Estoy de acuerdo',
    'agree_t_and_c' => 'Haciendo click en <strong class="label label-primary">Registro</strong>, acepta nuestros <a href="{x}" target="_blank">Terminos y Condiciones</a>.',
    'create_an_account' => 'Crear una cuenta',
    'terms_and_conditions' => 'Términos y Condiciones',
    'validation_complete' => 'Su cuenta ha sido validada, ahora puede iniciar sesión.',
    'validation_error' => 'Se ha producido un error desconocido al validar su cuenta, póngase en contacto con un administrador.',
    'signature' => 'Firma',
    'signature_max_900' => 'La firma debe tener como máximo 900 carácteres.',

    // Registration - Authme
    'connect_with_authme' => 'Conecte su cuenta con AuthMe',
    'authme_help' => 'Ingrese los detalles de su cuenta de AuthMe. Si no tiene una cuenta en el juego, conéctese al servidor ahora y siga las instrucciones proporcionadas.',
    'unable_to_connect_to_authme_db' => 'No se puede conectar a la base de datos AuthMe. Si este error persiste, póngase en contacto con un administrador.',
    'authme_account_linked' => 'Cuenta vinculada correctamente.',
    'authme_email_help_1' => 'Por último, introduzca su dirección de correo electrónico.',
    'authme_email_help_2' => 'Por último, ingrese su dirección de correo electrónico y elija un nombre para mostrar en su cuenta.',

    // Registration errors
    'username_required' => 'Se requiere un nombre de usuario.',
    'email_required' => 'Se requiere una dirección de correo electrónico.',
    'password_required' => 'Se requiere una contraseña.',
    'mcname_required' => 'Se requiere un nombre de usuario de Minecraft.',
    'accept_terms' => 'Debe aceptar los términos y condiciones antes de registrarse.',
    'username_minimum_3' => 'El nombre de usuario debe tener un mínimo de 3 caracteres.',
    'mcname_minimum_3' => 'El nombre de usuario de Minecraft debe tener un mínimo de 3 caracteres.',
    'password_minimum_6' => 'La contraseña debe tener un mínimo de 6 caracteres.',
    'username_maximum_20' => 'El nombre de usuario debe tener un máximo de 20 caracteres.',
    'mcname_maximum_20' => 'El nombre de usuario de Minecraft debe tener un máximo de 20 caracteres.',
    'password_maximum_30' => 'La contraseña debe tener un máximo de 30 caracteres.',
    'passwords_dont_match' => 'Las contraseñas no coinciden.',
    'username_mcname_email_exists' => 'Su nombre de usuario o dirección de correo electrónico ya existe.',
    'invalid_mcname' => 'Su nombre de usuario de Minecraft no es válido.',
    'invalid_email' => 'Su correo electrónico es inválido.',
    'mcname_lookup_error' => 'Se ha producido un error al comunicarse con los servidores de Mojang para verificar su nombre de usuario. Por favor, inténtelo de nuevo más tarde.',
    'invalid_recaptcha' => 'La respuesta de reCAPTCHA no es válida.',
    'verify_account' => 'Verificar Cuenta',
    'verify_account_help' => 'Siga las instrucciones a continuación para verificar que posee la cuenta de Minecraft en cuestión.',
    'validate_account' => 'Validar cuenta',
    'verification_failed' => 'Verificación fallida. Inténtelo de nuevo.',
    'verification_success' => 'Verificación exitora. Ahora puede iniciar sesión.',
    'authme_username_exists' => 'Su cuenta de AuthMe ya está conectada con nuestra web.',
    'uuid_already_exists' => 'Esa UUID ya está en uso. (Esa cuenta de Minecraft ya está registrada). Contacte con un administrador para solucionar el problema.',

    // Login
    'successful_login' => 'Ha iniciado sesión correctamente.',
    'incorrect_details' => 'Los datos son incorrectos.',
    'inactive_account' => 'Su cuenta está inactiva. Comprueba tu correo electrónicos para ver un enlace de validación. Si no lo encuentra, mire en su carpeta de spam.',
    'account_banned' => 'Esa cuenta está baneada.',
    'forgot_password' => '¿Ha olvidado su contraseña?',
    'remember_me' => 'No cerrar sesión',
    'must_input_email' => 'Debe introducir una dirección de correo electrónico.',
    'must_input_username' => 'Debe introducir un nombre de usuario.',
    'must_input_password' => 'Debe introducir una contraseña.',

    // Forgot password
    'forgot_password_instructions' => 'Por favor, introduzca su dirección de correo electrónico para que podamos enviarle las instrucciones para restablecer su contraseña.',
    'forgot_password_email_sent' => 'Si existe una cuenta con esa dirección de correo electrónico, se enviará correo electrónico con instrucciones. Si no puede encontrarlo, pruebe a comprobar su carpeta de spam.',
    'unable_to_send_forgot_password_email' => 'No se ha podido enviar el correo para restablecer la contraseña. Póngase en contacto con un administrador.',
    'enter_new_password' => 'Confirme su dirección de correo electrónico e ingrese una nueva contraseña a continuación.',
    'incorrect_email' => 'La dirección de correo electrónico que ha introducido no coincide con la solicitud.',
    'forgot_password_change_successful' => 'Su contraseña ha sido cambiada exitosamente. Ahora puede iniciar sesión.',

    // Profile pages
    'profile' => 'Perfil',
    'follow' => 'Seguir',
    'no_wall_posts' => 'No hay publicaciones en el muro todavía.',
    'change_banner' => 'Cambiar banner',
    'post_on_wall' => 'Publicar en el muro de {x}', // Don't replace {x}
    'invalid_wall_post' => 'Asegúrese de que su publicación tenga entre 1 y 10000 caracteres.',
    '1_reaction' => '1 reacción',
    'x_reactions' => '{x} reaccciones', // Don't replace {x}
    '1_like' => '1 me gusta',
    'x_likes' => '{x} me gustas', // Don't replace {x}
    '1_reply' => '1 respuesta',
    'x_replies' => '{x} respuestas', // Don't replace {x}
    'no_replies_yet' => 'No hay respuestas',
    'feed' => 'Muro',
    'about' => 'Acerca de',
    'reactions' => 'Reacciones',
    'replies' => 'Respuestas',
    'new_reply' => 'Nueva respuesta',
    'registered' => 'Registrado:',
    'registered_x' => 'Registrado: {x}',
    'last_seen' => 'Ultima vez visto:',
    'last_seen_x' => 'Ultima vez visto: {x}', // Don't replace {x}
    'new_wall_post' => '{x} ha publicado en tu perfil.',
    'couldnt_find_that_user' => 'No se ha podido encontrar a ese usuario.',
    'block_user' => 'Bloquear usuario',
    'unblock_user' => 'Desbloquear usuario',
    'confirm_block_user' => '¿Está seguro de que quiere bloquear a este usuario? No podrá enviarte mensajes privados ni etiquetarte en publicaciones.',
    'confirm_unblock_user' => '¿Está seguro de que quiere desbloquear a este usuario? Podrá enviarte mensajes privados y/o etiquetarte en publicaciones.',
    'user_blocked' => 'Usuario bloqueado.',
    'user_unblocked' => 'Usuario desbloqueado.',
    'views' => 'Vistas del perfil:',
    'private_profile_page' => 'Este perfil es privado.',
    'new_wall_post_reply' => '{x} ha respondido a su publicación en el perfil de {y}.', // Don't replace {x} or {y}
    'new_wall_post_reply_your_profile' => '{x} ha respondido a su publicación en su perfil.', // Don't replace {x}
    'no_about_fields' => 'Este usuario no ha aádido ningún campo de información todavía.',
    'reply' => 'Responder',
    'discord_username' => 'Discord Username',
    
    // Reports
    'invalid_report_content' => 'No se puede enviar el informe. Asegúrese de que la razón posee entre 2 y 1024 caracteres.',
    'report_post_content' => 'Introduzca una razón para su reporte',
    'report_created' => 'Reporte creado correctamente',

    // Messaging
    'no_messages' => 'No hay mensajes nuevos',
    'no_messages_full' => 'No tiene ningún mensaje.',
    'view_messages' => 'Ver mensajes',
    '1_new_message' => 'Tiene 1 nuevo mensaje',
    'x_new_messages' => 'Tiene {x} nuevos mensajes', // Don't replace {x}
    'new_message' => 'Nuevo mensaje',
    'message_title' => 'Titulo del Mensaje',
    'to' => 'a',
    'separate_users_with_commas' => 'Debe separar el nombre de los destinatarios mediante comas',
    'title_required' => 'Debe ingresar un título',
    'content_required' => 'Debe ingresar algún contenido',
    'users_to_required' => 'Debe ingresar algún destinatario del mensaje',
    'cant_send_to_self' => 'No puede enviarse un mensaje a si mismo',
    'title_min_2' => 'El título debe tener un mínimo de 2 caracteres',
    'content_min_2' => 'El contenido debe tener un mínimo de 2 caracteres',
    'title_max_64' => 'El título debe tener un máximo de 64 caracteres',
    'content_max_20480' => 'El contenido del mensaje debe tener como máximo 20480 caracteres',
    'max_pm_10_users' => 'Solo puede enviar un mensaje a un máximo de 10 usuarios',
    'message_sent_successfully' => 'Mensaje enviado con éxito',
    'participants' => 'Participantes',
    'last_message' => 'Último mensaje',
    'by' => 'por',
    'leave_conversation' => 'Dejar la conversación',
    'confirm_leave' => '¿Seguro que quiere dejar esta conversación?',
    'one_or_more_users_blocked' => 'No puede enviar mensajes privados a por lo menos un miembro de la conversación.',
    'messages' => 'Mensajes',
    'latest_profile_posts' => 'Latest Profile Posts',
    'no_profile_posts' => 'No profile posts.',

    /*
     *  Infractions area
     */
    'you_have_been_banned' => 'Ha sido baneado.',
    'you_have_received_a_warning' => 'Ha recibido una advertencia.',
    'acknowledge' => 'Reconocer',

    /*
     *  Hooks
     */
    'user_x_has_registered' => '{x} se ha registado ' . SITE_NAME . '!',
    'user_x_has_validated' => '{x} has validated their account!',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Linked',
    'not_linked' => 'Not Linked',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Successfully unlinked your Discord User ID.',
    'discord_id_confirm' => 'Please send this message: "!verify {guild_id}:{token}" to {bot_username} to confirm your Discord User ID.',
    'pending_link' => 'Pending',
    'discord_id_taken' => 'That Discord ID has already been taken.',
    'discord_invalid_id' => 'That Discord User ID is invalid.',
    'discord_already_pending' => 'You already have a pending verification.',
    'discord_database_error' => 'The Nameless Link database is currently down. Please try again later.',
    'discord_communication_error' => 'There was an error while communicating with the Discord Bot. Please ensure the bot is running and your Bot URL is correct.',
    'discord_unknown_error' => 'There was an unknown error while syncing Discord roles. Please contact an administrator.',
    'discord_id_help' => 'For information on where to find Discord ID\'s, please read <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>'
);
