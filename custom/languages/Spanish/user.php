<?php 
/*
 *	Made by TheSuperSkills (Edited by Ariuw and iMaykolRD_)
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Spanish Language - Users
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
	'user_cp' => 'Panel de Usuarios',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Visión de Conjunto',
	'user_details' => 'Detalles del usuario',
	'profile_settings' => 'Ajustes de Perfil',
	'successfully_logged_out' => 'Has salido con éxito.',
	'messaging' => 'Mensajería',
	'click_here_to_view' => 'Click aquí para ver.',
	'moderation' => 'Moderación',
	'administration' => 'Administración',
	'alerts' => 'Alertas',
	'delete_all' => 'Eliminar todos',
	'private_profile' => 'Perfil privado',
	
	// Profile settings
	'field_is_required' => '{x} es requerido.', // Don't replace {x}
	'settings_updated_successfully' => 'Configuración actualizada correctamente.',
	'password_changed_successfully' => 'Contraseña cambiada con éxito.',
	'change_password' => 'Cambia la contraseña',
	'current_password' => 'Contraseña actual',
	'new_password' => 'Nueva contraseña',
	'confirm_new_password' => 'Confirmar nueva contraseña',
	'incorrect_password' => 'Tu contraseña es incorrecta.',
	'two_factor_auth' => 'Autenticación de dos factores',
	'enabled' => 'Activado',
    'disabled' => 'Desactivado',
	'enable' => 'Activar',
	'disable' => 'Desactivar',
	'tfa_scan_code' => 'Compruebe el código siguiente en su aplicación de autenticación:',
	'tfa_code' => 'Si su dispositivo no tiene una cámara o no puede escanear el código QR, introduzca el código siguiente:',
	'tfa_enter_code' => 'Ingresa el código que aparece en tu aplicación de autenticación.:',
	'invalid_tfa' => 'Código no válido, por favor vuelva a intentarlo.',
	'tfa_successful' => 'La autenticación de dos factores se ha configurado correctamente. Tendrá que autenticarse cada vez que inicie sesión de ahora en adelante.',
	'active_language' => 'Lenguaje Activo',
	'active_template' => 'Active Template',
	'timezone' => 'Zona Horaria',
	'upload_new_avatar' => 'Cargar un nuevo avatar',
	'nickname_already_exists' => 'El nick que haz elegido ya está en uso.',
	'change_email_address' => 'Cambiar dirección de correo electronico',
	'email_already_exists' => 'El correo electronico que ha introducido está en uso.',
	'email_changed_successfully' => 'La dirección de correo electrónico fue actualizada con éxito.',
	'avatar' => 'Avatar',
	'profile_banner' => 'Banner del Perfil',
	'upload_profile_banner' => 'Subir Banner del Perfil',
	'upload' => 'Subir',
	
	// Alerts
	'user_tag_info' => 'Has sido etiquetado en un post por {x}.', // Don't replace {x}
	'no_alerts' => 'No hay nuevas alertas',
	'view_alerts' => 'Ver alertas',
	'1_new_alert' => 'Tienes 1 nueva alerta.',
	'x_new_alerts' => 'Tienes {x} nuevas alertas', // Don't replace {x}
	'no_alerts_usercp' => 'No tienes alertas.',
	
	// Registraton
	'registration_check_email' => '¡Gracias por registrarte! Por favor revise sus correos electrónicos para obtener un enlace de validación para completar su registro. Si no puede encontrar el correo electrónico, compruebe su carpeta correo basura.',
	'username' => 'Usuario',
	'nickname' => 'Nombre de Usuario',
	'minecraft_username' => 'Usuario de Minecraft',
	'email_address' => 'Correo electrónico',
	'email' => 'Correo',
	'password' => 'Contraseña',
	'confirm_password' => 'Confirmar contraseña',
	'i_agree' => 'Estoy de acuerdo',
	'agree_t_and_c' => 'Haciendo click <strong class="label label-primary">Registro</strong>, tu aceptas nuestro <a href="{x}" target="_blank">Terminos y Condiciones</a>.',
	'create_an_account' => 'Crea una cuenta',
	'terms_and_conditions' => 'Términos y Condiciones',
	'validation_complete' => 'Su cuenta ha sido validada, ahora puede iniciar sesión.',
	'validation_error' => 'Se ha producido un error desconocido al validar su cuenta, póngase en contacto con un administrador del sitio web.',
	'signature' => 'Firma',
	'signature_max_900' => 'Tu firma debe ser máximo de 900 carácteres.',

    // Registration - Authme
    'connect_with_authme' => 'Conecte su cuenta con AuthMe',
    'authme_help' => 'Ingrese los detalles de su cuenta de AuthMe. Si ya no tienes una cuenta en el juego, únete al servidor ahora y sigue las instrucciones proporcionadas.',
    'unable_to_connect_to_authme_db' => 'No se puede conectar a la base de datos AuthMe. Si este error persiste, póngase en contacto con un administrador.',
    'authme_account_linked' => 'Cuenta vinculada correctamente.',
    'authme_email_help_1' => 'Por último, introduzca su dirección de correo electrónico.',
    'authme_email_help_2' => 'Por último, ingrese su dirección de correo electrónico y elija un nombre para mostrar para su cuenta.',

	// Registration errors
	'username_required' => 'Se requiere un nombre de usuario.',
	'email_required' => 'Se requiere una dirección de correo electrónico.',
	'password_required' => 'Se requiere una contraseña.',
	'mcname_required' => 'Se requiere un nombre de usuario de Minecraft.',
	'accept_terms' => 'Debe aceptar los términos y condiciones antes de registrarse.',
	'username_minimum_3' => 'Tu nombre de usuario debe tener un mínimo de 3 caracteres.',
	'mcname_minimum_3' => 'Tu nombre de usuario de Minecraft debe tener un mínimo de 3 caracteres.',
	'password_minimum_6' => 'Su contraseña debe tener un mínimo de 6 caracteres.',
	'username_maximum_20' => 'Su nombre de usuario debe tener un máximo de 20 caracteres.',
	'mcname_maximum_20' => 'El nombre de usuario de Minecraft debe tener un máximo de 20 caracteres..',
	'password_maximum_30' => 'Su contraseña debe tener un máximo de 30 caracteres..',
	'passwords_dont_match' => 'Tus contraseñas no coinciden.',
	'username_mcname_email_exists' => 'Su nombre de usuario o dirección de correo electrónico ya existe.',
	'invalid_mcname' => 'Tu nombre de usuario de Minecraft no es válido.',
	'invalid_email' => 'Tu correo electrónico es inválido.',
	'mcname_lookup_error' => 'Se ha producido un error al comunicarse con los servidores de Mojang para verificar su nombre de usuario. Por favor, inténtelo de nuevo más tarde.',
	'invalid_recaptcha' => 'La respuesta de reCAPTCHA no es válida...',
	'verify_account' => 'Verificar Cuenta',
	'verify_account_help' => 'Siga las instrucciones a continuación para verificar que posee la cuenta de Minecraft en cuestión.',
	'validate_account' => 'Validar cuenta',
	'verification_failed' => 'Verificación fallida. Por favor intenta de nuevo.',
	'verification_success' => '¡Con éxito validado! Ahora puede iniciar sesión.',
	'authme_username_exists' => 'Tu cuenta de AuthMe ya está conectada con nuestra web.',
	'uuid_already_exists' => 'Tu UUID ya existe, por lo tanto esa cuenta de Minecraft ya está registrada.',
	
	// Login
	'successful_login' => 'Has iniciado sesión correctamente.',
	'incorrect_details' => 'Has introducido datos incorrectos.',
	'inactive_account' => 'Su cuenta está inactiva. Comprueba tus correos electrónicos para ver un enlace de validación, incluso dentro de tu carpeta de correo basura.',
	'account_banned' => 'Esa cuenta está prohibida.',
	'forgot_password' => '¿Se te olvidó tu contraseña?',
	'remember_me' => 'Recuérdame',
	'must_input_email' => 'Debe introducir una dirección de correo electrónico.',
	'must_input_username' => 'Debe introducir un nombre de usuario.',
	'must_input_password' => 'Debe introducir una contraseña.',

    // Forgot password
    'forgot_password_instructions' => 'Por favor, introduzca su dirección de correo electrónico para que podamos enviarle más instrucciones para restablecer su contraseña..',
    'forgot_password_email_sent' => 'Si existe una cuenta con la dirección de correo electrónico, se ha enviado un correo electrónico que contiene instrucciones adicionales. Si no puede encontrarlo, pruebe a comprobar su carpeta de correo basura.',
    'unable_to_send_forgot_password_email' => 'No se pudo enviar la contraseña olvidada. Póngase en contacto con un administrador.',
    'enter_new_password' => 'Confirme su dirección de correo electrónico e ingrese una nueva contraseña a continuación.',
    'incorrect_email' => 'La dirección de correo electrónico que ingresaste no coincide con la solicitud..',
    'forgot_password_change_successful' => 'Tu contraseña ha sido cambiada exitosamente. Ahora puede iniciar sesión.',
	
	// Profile pages
	'profile' => 'Perfil',
	'follow' => 'Seguir',
	'no_wall_posts' => 'No hay publicaciones en el muro todavía.',
	'change_banner' => 'Cambiar banner',
	'post_on_wall' => 'Publicar en el muro de {x}', // Don't replace {x}
	'invalid_wall_post' => 'Asegúrese de que su publicación tenga entre 1 y 10000 caracteres.',
	'1_reaction' => '1 reacción',
	'x_reactions' => '{x} reaccciones', // Don't replace {x}
	'1_like' => '1 Me gusta',
	'x_likes' => '{x} Me gustas', // Don't replace {x}
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
	'new_wall_post' => '{x} ha publicado en tu Perfil.',
	'couldnt_find_that_user' => 'No se ha podido encontrar a ese usuario.',
	'block_user' => 'Bloquear usuario',
	'unblock_user' => 'Desbloquear usuario',
	'confirm_block_user' => '¿Estás seguro de que quieres bloquear a este usuario? Él no podrá enviarte mensajes privados y/o etiquetarte en publicaciones.',
	'confirm_unblock_user' => '¿Estás seguro de que quieres desbloquear a este usuario? Él podrá enviarte mensajes privados y/o etiquetarte en publicaciones.',
	'user_blocked' => 'Usuario bloqueado.',
	'user_unblocked' => 'Usuario desbloqueado.',
	'views' => 'Visitas al Perfil:',
	'private_profile_page' => '¡Este es un Perfil privado!',
	'new_wall_post_reply' => '{x} ha respondido a tu publicación en el Perfil de {y}.', // Don't replace {x} or {y}
	'new_wall_post_reply_your_profile' => '{x} ha respondido a tu publicación en tu Perfil.', // Don't replace {x}
	'no_about_fields' => 'Este usuario todavía no ha agregado ningún campo sobre el.',
	'reply' => 'Responder',
	
	// Reports
	'invalid_report_content' => 'No se puede crear el informe. Asegúrese de que la razón de su informe es entre 2 y 1024 caracteres.',
	'report_post_content' => 'Introduzca una razón para su reporte',
	'report_created' => 'Reporte creado correctamente',
	
	// Messaging
	'no_messages' => 'No hay mensajes nuevos',
	'no_messages_full' => 'No tienes ningún mensaje.',
	'view_messages' => 'Ver mensajes',
	'1_new_message' => 'Tienes 1 nuevo mensaje',
	'x_new_messages' => 'Tienes {x} nuevos mensajes', // Don't replace {x}
	'new_message' => 'Nuevo mensaje',
	'message_title' => 'Titulo del Mensaje',
	'to' => 'a',
	'separate_users_with_commas' => 'Separar usuarios con comas',
	'title_required' => 'Por favor ingrese un título',
	'content_required' => 'Por favor ingrese algún contenido',
	'users_to_required' => 'Ingresa algunos destinatarios de mensajes',
	'cant_send_to_self' => 'No puedes enviarte un mensaje!',
	'title_min_2' => 'El título debe tener un mínimo de 2 caracteres',
	'content_min_2' => 'El contenido debe tener un mínimo de 2 caracteres',
	'title_max_64' => 'El título debe tener un máximo de 64 caracteres',
	'content_max_20480' => 'El contenido debe tener un máximo de 20480 caracteres',
	'max_pm_10_users' => 'Sólo puede enviar un mensaje a un máximo de 10 usuarios',
	'message_sent_successfully' => 'Mensaje enviado con éxito',
	'participants' => 'Participantes',
	'last_message' => 'Último mensaje',
	'by' => 'por',
	'leave_conversation' => 'Dejar la conversación',
	'confirm_leave' => '¿Seguro que quieres dejar esta conversación?',
	'one_or_more_users_blocked' => 'No puede enviar mensajes privados a al menos un miembro de la conversación.',
	'messages' => 'Mensajes',
	/*
	 *  Infractions area
	 */
	'you_have_been_banned' => '¡HAS SIDO BANEADO!',
	'you_have_received_a_warning' => '¡Haz recibido una advertencia!',
	'acknowledge' => 'Reconocer',
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Hola,',
	'email_message' => '¡Gracias por registrarte! Para completar su registro, haga click en el siguiente enlace:',
	'forgot_password_email_message' => 'Para restablecer su contraseña, haga click en el siguiente enlace. Si no lo ha solicitado usted mismo, puede eliminarlo de forma segura.',
	'email_thanks' => 'Gracias,',

	/*
	 *  Hooks
	 */
	'user_x_has_registered' => '{x} se ha registado ' . SITE_NAME . '!'
);
