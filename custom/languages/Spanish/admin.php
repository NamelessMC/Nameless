﻿<?php
/*
 *	Made by TheSuperSkills (Edited by Ariuw and iMaykolRD_)
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3/pr4
 *
 *  License: MIT
 *
 *  Spanish Language - Admin
 */

$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'Inicie sesión para ingresar',

	// Sidebar
<<<<<<< HEAD
=======
	'dashboard' => 'Dashboard',
	'configuration' => 'Configuration',
	'layout' => 'Layout',
	'user_management' => 'User Management',
>>>>>>> upstream/v2
	'admin_cp' => 'Panel de Administración',
	'administration' => 'Administración',
	'overview' => 'Visión de conjunto',
	'core' => 'Configuraciones',
	'minecraft' => 'Minecraft',
	'modules' => 'Módulos',
	'security' => 'Seguridad',
	'sitemap' => 'Sitemap',
	'styles' => 'Estilos',
	'users_and_groups' => 'Usuarios y grupos',

	// Overview
	'running_nameless_version' => 'Ejecutando NamelessMC. Versión <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Ejecutando la versión de PHP <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => 'Estadísticas',
	'registrations' => 'Registraciones',
	'topics' => 'Temas',
	'posts' => 'Publicaciones',
    'notices' => 'Noticias',
    'no_notices' => 'No hay noticias.',
<<<<<<< HEAD
    'email_errors_logged' => 'Se han registrado los errores de correo electrónico. Click <a href="{x}">aquí</a> para ver.', // Don't replace "{x}"
=======
    'email_errors_logged' => 'Se han registrado los errores de correo electrónico',
>>>>>>> upstream/v2
	// Core
	'settings' => 'Ajustes',
	'general_settings' => 'Ajustes generales',
	'sitename' => 'Nombre del sitio',
	'default_language' => 'Lenguaje por defecto',
	'default_language_help' => 'Los usuarios podrán elegir entre los idiomas instalados.',
<<<<<<< HEAD
=======
	'install_language' => 'Install Language',
	'update_user_languages' => 'Update User Languages',
	'update_user_languages_warning' => 'This will update the language for all users on your site, even if they have already selected one!',
	'updated_user_languages' => 'User languages have been updated.',
>>>>>>> upstream/v2
	'installed_languages' => 'Todos los nuevos idiomas se han instalado correctamente.',
	'default_timezone' => 'Zona horaria por defecto',
	'registration' => 'Registro',
	'enable_registration' => '¿Activar registro?',
	'verify_with_mcassoc' => '¿Verificar cuentas de Usuarios con MCAssoc?',
	'email_verification' => '¿Habilitar la verificación de correo electrónico?',
<<<<<<< HEAD
	'homepage_type' => 'Tipo de pagina de inicio',
	'post_formatting_type' => 'Tipo de formato posterior',
	'portal' => 'Portal',
	'missing_sitename' => 'Introduzca un nombre del sitio entre 2 y 64 caracteres.',
	'use_friendly_urls' => 'Usar URLs de Amigos',
	'use_friendly_urls_help' => 'IMPORTANTE: Tu servidor debe estar configurado para permitir el uso de los archivos mod_rewrite y .htaccess para que esto funcione.',
	'config_not_writable' => 'Tu <strong>core/config.php</strong> archivo no es escribible. Compruebe los permisos de archivo.',
=======
	'registration_settings_updated' => 'Registration settings updated successfully.',
	'homepage_type' => 'Tipo de pagina de inicio',
	'post_formatting_type' => 'Tipo de formato posterior',
	'portal' => 'Portal',
	'private_profiles' => 'Private Profiles',
	'missing_sitename' => 'Introduzca un nombre del sitio entre 2 y 64 caracteres.',
	'missing_contact_address' => 'Please insert a contact email address between 3 and 255 characters long.',
	'use_friendly_urls' => 'Usar URLs de Amigos',
	'use_friendly_urls_help' => 'IMPORTANTE: Tu servidor debe estar configurado para permitir el uso de los archivos mod_rewrite y .htaccess para que esto funcione.',
	'config_not_writable' => 'Tu <strong>core/config.php</strong> archivo no es escribible. Compruebe los permisos de archivo.',
	'settings_updated_successfully' => 'General settings updated successfully.',
>>>>>>> upstream/v2
	'social_media' => 'Redes sociales',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => '¿Usar tema oscuro de Twitter?',
	'discord_id' => 'Discord Server ID',
	'discord_widget_theme' => 'Discord Widget Theme',
	'dark' => 'Dark',
	'light' => 'Light',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
<<<<<<< HEAD
	'successfully_updated' => 'Actualizado exitosamente',
    'debugging_and_maintenance' => 'Depuración y mantenimiento',
=======
	'social_media_settings_updated' => 'Social media settings updated successfully.',
	'successfully_updated' => 'Actualizado exitosamente',
    'debugging_and_maintenance' => 'Depuración y mantenimiento',
    'debugging_settings_updated_successfully' => 'Debugging settings updated successfully.',
>>>>>>> upstream/v2
    'enable_debug_mode' => '¿Habilitar modo de depuracion?',
    'force_https' => '¿Forzar HTTPs?',
    'force_https_help' => 'Si habilita esto, todas las solicitudes a su sitio web seran redirigidas a https. Debe tener un certificado SSL valido activo para que funcione correctamente.',
    'force_www' => 'Force www?',
    'contact_email_address' => 'Correo electrónico de contacto',
    'emails' => 'Correos electrónicos',
    'email_errors' => 'Errores de correo electrónico',
    'registration_email' => 'Correo electrónico de registro',
    'contact_email' => 'Correo de contacto',
    'forgot_password_email' => '¿Olvidaste tu contraseña?',
    'unknown' => 'Desconocido',
    'delete_email_error' => 'Eliminar error',
    'confirm_email_error_deletion' => '¿Seguro que quieres eliminar este error?',
    'viewing_email_error' => 'Error de visualización',
    'unable_to_write_email_config' => 'No se puede escribir en el archivo <strong>core/email.php</core>. Compruebe los permisos de archivo.',
    'enable_mailer' => '¿Activar PHPMailer?',
    'enable_mailer_help' => 'Habilite esto si los correos electrónico no se envian por defecto. El uso de PHPMailer requiere que usted tenga un servicio capaz de enviar correos electrónico, como Gmail o un proveedor de SMTP.',
    'outgoing_email' => 'Dirección de correo saliente',
    'outgoing_email_info' => 'Esta es la dirección de correo electrónico que NamelessMC utilizará para enviar correos electronicos.',
<<<<<<< HEAD
    'mailer_settings_info' => 'Los campos siguientes son obligatorios si ha habilitado PHPMailer. Para obtener más información sobre como rellenar estos campos, consulta en <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">the wiki</a>.',
=======
    'mailer_settings_info' => 'Los campos siguientes son obligatorios si ha habilitado PHPMailer. Para obtener más información sobre como rellenar estos campos, consulta en <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-SMTP-with-Nameless-(e.g.-Gmail-or-Outlook)" target="_blank">the wiki</a>.',
>>>>>>> upstream/v2
    'host' => 'Host',
	'email_port' => 'Port',
    'email_password_hidden' => 'La contraseña no se muestra por razones de seguridad.',
    'send_test_email' => 'Enviar prueba de correo electrónico',
    'send_test_email_info' => 'El siguiente botón intentará enviar un correo electrónico a su dirección de correo electrónico, <strong>{x}</strong>. Se mostrarán todos los errores lanzados mientras se envía el correo.', // Don't replace {x}
    'send' => 'Enviar',
    'test_email_error' => 'Error de prueba de correo electrónico:',
    'test_email_success' => '¡Correo electrónico de prueba enviado con éxito!',
<<<<<<< HEAD
    'terms_error' => 'Asegúrese de que sus términos y condiciones no superen los 2048 caracteres...',
=======
    'terms_error' => 'Asegúrese de que sus términos y condiciones no superen los 100000 caracteres...',
    'privacy_policy_error' => 'Please enter a privacy policy no longer than 100000 characters.',
>>>>>>> upstream/v2
    'terms_updated' => 'Términos actualizados con éxito.',
    'avatars' => 'Avatares',
    'allow_custom_avatars' => 'Permitir avatares de usuario personalizados?',
    'default_avatar' => 'Avatar por defecto',
    'custom_avatar' => 'Avatar personalizado',
    'minecraft_avatar' => 'Avatar de Minecraft',
    'minecraft_avatar_source' => 'Fuente para avatar de Minecraft',
    'built_in_avatars' => 'Servicio de avatar integrado',
    'minecraft_avatar_perspective' => 'Minecraft perspectiva de avatar',
    'face' => 'Cara',
    'head' => 'Cabeza',
<<<<<<< HEAD
=======
    'bust' => 'Bust',
>>>>>>> upstream/v2
    'select_default_avatar' => 'Seleccione un nuevo avatar predeterminado:',
    'no_avatars_available' => 'No hay avatares disponibles. Sube una nueva imagen por encima de la primera.',
    'avatar_settings_updated_successfully' => 'Configuración de avatar actualizada correctamente.',
    'navigation' => 'Navegación',
    'navbar_order' => 'Orden de la barra de Navegación',
    'navbar_order_instructions' => 'Puede dar a cada elemento un número superior a 0 para pedir artículos en la barra de navegación, siendo 1 el primer artículo y los números más altos que vendrán después.',
<<<<<<< HEAD
=======
    'navbar_icon' => 'Navbar Icon',
    'navbar_icon_instructions' => 'You can also add an icon to each navbar item here, for example using <a href="https://fontawesome.com/v4.7.0/" target="_blank" rel="noopener nofollow">Font Awesome</a>.',
    'navigation_settings_updated_successfully' => 'Navigation settings updated successfully.',
>>>>>>> upstream/v2
    'enable_page_load_timer' => '¿Habilitar el tiempo de carga de la página?',
    'google_recaptcha' => '¿Habilitar Google reCAPTCHA?',
    'recaptcha_site_key' => 'Clave de sitio de reCAPTCHA (Site key)',
    'recaptcha_secret_key' => 'Clave secreta de reCAPTCHA (Secret Key)',
    'registration_disabled_message' => 'Mensaje de registración desactivada',
    'enable_nicknames_on_registration' => '¿Habilitar nicks para registrar usuarios?',
    'validation_promote_group' => 'Grupo de validación posterior',
    'validation_promote_group_info' => 'Este es el grupo al que se promocionará a un usuario una vez que haya validado su cuenta.',
    'login_method' => 'Método de logueo',
    'privacy_and_terms' => 'Política de privacidad. Términos & Condiciones',

	// Reactions
	'icon' => 'Icono',
	'type' => 'Tipo',
	'positive' => 'Me gusta',
	'neutral' => 'Neutral',
	'negative' => 'No me gusta',
	'editing_reaction' => 'Edición de reacciones',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> Nueva Reacción',
	'creating_reaction' => 'Crear reacción',
<<<<<<< HEAD
=======
	'no_reactions' => 'There are no reactions yet.',
	'reaction_created_successfully' => 'Reaction created successfully.',
	'reaction_edited_successfully' => 'Reaction edited successfully.',
	'reaction_deleted_successfully' => 'Reaction deleted successfully.',
	'name_required' => 'A name is required',
	'html_required' => 'HTML is required',
	'type_required' => 'A type is required',
	'name_maximum_16' => 'The name must be no more than 16 characters',
	'html_maximum_255' => 'The HTML must be no more than 255 characters',
	'confirm_delete_reaction' => 'Are you sure you want to delete this reaction?',
>>>>>>> upstream/v2

	// Custom profile fields
	'custom_fields' => 'Campos del perfil personalizado',
	'new_field' => '<i class="fa fa-plus-circle"></i> Nuevo campo',
	'required' => 'Requiere',
	'public' => 'Publico',
	'text' => 'Texto',
	'textarea' => 'Área de texto',
	'date' => 'Fecha',
	'creating_profile_field' => 'Creación de un campo de perfil',
	'editing_profile_field' => 'Edición del campo de perfil',
	'field_name' => 'Nombre del campo',
	'profile_field_required_help' => 'Los campos obligatorios deben ser llenados por el usuario y aparecerán durante el registro.',
	'profile_field_public_help' => 'Los campos públicos se mostrarán a todos los usuarios. Si está desactivado sólo los moderadores pueden ver los valores.',
	'profile_field_error' => 'Introduzca un nombre de campo entre 2 y 16 caracteres.',
	'description' => 'Descripción',
	'display_field_on_forum' => '¿Mostrar campo en el foro?',
	'profile_field_forum_help' => 'Si está activado, el campo se mostrará por el usuario junto a los mensajes del foro.',
	'profile_field_editable_help' => 'If enabled, users will have permission to edit the field in their profile settings.',
	'no_custom_fields' => 'There are no custom fields yet.',
<<<<<<< HEAD
=======
	'profile_field_updated_successfully' => 'The profile field was updated successfully.',
	'profile_field_created_successfully' => 'The profile field was created successfully.',
	'profile_field_deleted_successfully' => 'The profile field was deleted successfully.',
>>>>>>> upstream/v2

    // Minecraft
    'enable_minecraft_integration' => '¿Habilitar la integración de Minecraft?',
    'mc_service_status' => 'Estado del servicio Minecraft',
    'service_query_error' => 'No se puede recuperar el estado del servicio.',
    'authme_integration' => 'Integración del AuthMe',
    'authme_integration_info' => 'Cuando la integración de AuthMe está habilitada, los usuarios sólo pueden registrarse en el juego.',
    'enable_authme' => '¿Habilitar la integración de AuthMe?',
    'authme_db_address' => 'Dirección de la nase de datos del AuthMe',
    'authme_db_port' => 'Puerto de la base de datos del AuthMe',
    'authme_db_name' => 'Nombre de la base de datos del AuthMe',
    'authme_db_user' => 'Nombre de usuario de la base de datos del AuthMe',
    'authme_db_password' => 'Contraseña de la base de datos del AuthMe',
    'authme_hash_algorithm' => 'Algoritmo del hash (AuthMe)',
    'authme_db_table' => 'Tabla de usuarios de AuthMe',
    'enter_authme_db_details' => 'Introduce datos válidos de la base de datos.',
    'authme_password_sync' => '¿Sincronizar contraseña con la del AuthMe?',
    'authme_password_sync_help' => 'Si está activada, siempre que la contraseña de un usuario se actualice en el juego, la contraseña también se actualizará en el sitio web.',
    'minecraft_servers' => 'Servidores de Minecraft',
    'account_verification' => 'Verificación de la cuenta de Minecraft',
    'server_banners' => 'Banner del servidor',
    'query_errors' => 'Errores de consulta',
    'add_server' => '<i class="fa fa-plus-circle"></i> Agregar servidor',
    'no_servers_defined' => 'No se han definido servidores aún',
    'query_settings' => 'Configuración de consultas',
    'default_server' => 'Servidor predeterminado',
    'no_default_server' => 'No hay servidor predeterminado',
    'external_query' => '¿Usar consulta externa?',
    'external_query_help' => 'Si la consulta predeterminada del servidor no funciona, habilite esta opción.',
    'adding_server' => 'Añadir un servidor',
    'server_name' => 'Nombre del Servidor',
    'server_address' => 'IP del servidor',
    'server_address_help' => 'Esta es la dirección IP o dominio utilizado para conectarse a su servidor, sin el puerto.',
    'server_port' => 'Puerto del servidor',
    'parent_server' => 'Servidor padre',
    'parent_server_help' => 'Un servidor padre suele ser la instancia de BungeeCord a la que está conectado el servidor, si es que existe.',
    'no_parent_server' => 'No hay servidor padre',
    'bungee_instance' => '¿Esta BungeeCord instalado?',
    'bungee_instance_help' => 'Seleccione esta opción si el servidor es una instancia de BungeeCord.',
    'server_query_information' => 'Con el fin de mostrar una lista de jugadores en línea en su sitio web, su servidor <strong>must</strong> debe tener el \'enable-query\' habilitado en el archivo <strong>server.properties</strong>.',
    'enable_status_query' => '¿Habilitar estado de consulta?',
    'status_query_help' => 'Si esta opción está activada, la página de estado mostrará este servidor en línea o fuera de línea.',
    'enable_player_list' => '¿Activar la lista de jugadores?',
    'pre_1.7' => '¿La versión del servidor de Minecraft es anterior a la 1.7?',
    'player_list_help' => 'Si está activado, la página de estado mostrará una lista de jugadores en línea.',
    'server_query_port' => 'Puerto de consulta del servidor',
    'server_query_port_help' => 'Esta es la opción query.port en el archivo server.properties de su servidor, siempre que la opción enable-query del mismo archivo esté establecida en true.',
    'server_name_required' => 'Introduzca el nombre del servidor',
    'server_name_minimum' => 'Asegúrese de que su nombre de servidor sea un mínimo de 1 carácter',
    'server_name_maximum' => 'Asegúrese de que su nombre de servidor sea un máximo de 20 carácteres',
    'server_address_required' => 'Introduzca la dirección del servidor',
    'server_address_minimum' => 'Asegúrese de que la dirección de su servidor es de un mínimo de 1 carácter',
    'server_address_maximum' => 'Asegúrese de que la dirección de su servidor sea un máximo de 64 carácteres',
    'server_port_required' => 'Introduzca el puerto del servidor',
    'server_port_minimum' => 'Asegúrese de que su puerto de servidor sea un mínimo de 2 carácteres',
    'server_port_maximum' => 'Asegúrese de que el puerto del servidor tenga un máximo de 5 carácteres',
    'server_parent_required' => 'Seleccione un servidor principal',
    'query_port_maximum' => 'Asegúrese de que su puerto de consulta tenga un máximo de 5 carácteres',
    'server_created' => 'Servidor se ha creado correctamente.',
    'confirm_delete_server' => '¿Está seguro de que desea eliminar este servidor?',
    'server_updated' => 'Servidor actualizado con éxito.',
    'editing_server' => 'Edición del servidor',
    'server_deleted' => 'Servidor eliminado correctamente',
    'unable_to_delete_server' => 'No se puede eliminar el servidor.',
    'leave_port_empty_for_srv' => 'Puede dejar el puerto vacío si es 25565, o si su dominio utiliza un registro SRV',
    'viewing_query_error' => 'Visualización del error de consulta',
    'confirm_query_error_deletion' => '¿Está seguro de que desea eliminar este error de consulta?',
    'no_query_errors' => 'No se han registrado errores de consulta.',
    'new_banner' => '<i class="fa fa-plus-circle"></i> Nuevo banner',
    'purge_errors' => 'Errores de purga',
    'confirm_purge_errors' => '¿Está seguro de que desea purgar todos los errores??',
<<<<<<< HEAD
=======
	'email_errors_purged_successfully' => 'Email errors have been purged successfully.',
	'error_deleted_successfully' => 'The error has been deleted successfully.',
	'no_email_errors' => 'No email errors logged.',
	'email_settings_updated_successfully' => 'Email settings have been updated successfully.',
	'content' => 'Content',
>>>>>>> upstream/v2
    'mcassoc_help' => 'MCAssoc es un servicio externo que se puede utilizar para verificar que los usuarios poseen la cuenta de Minecraft con la que se han registrado. Para utilizar esta función, deberá registrarse en una clave compartida <a href="https://mcassoc.lukegb.com/" target="_blank">here</a>.',
    'mcassoc_key' => 'Llave compartida de MCAssoc',
    'mcassoc_instance' => 'Llave de instancia de MCAssoc',
    'mcassoc_instance_help' => '<a href="#" onclick="generateInstance();">Click para generar una clave de instancia</a>',
    'mcassoc_error' => 'Asegúrese de que ha introducido correctamente su clave compartida y de que ha generado correctamente una clave de instancia.',
    'updated_mcassoc_successfully' => 'Configuración de MCAssoc actualizada correctamente.',
    'force_premium_accounts' => '¿Forzar cuentas premium de Minecraft?',
    'banner_background' => 'Background del Banner',
    'query_interval' => 'Intervalo de consultas (en minutos, debe estar entre 5 y 60)',
    'player_graphs' => 'Gráfica de jugadores',
    'player_count_cronjob_info' => 'Puede configurar un trabajo cRON para consultar sus servidores cada {x} minutos con el siguiente comando:',
    'status_page' => 'Enable status page?',

	// Modules
	'modules_installed_successfully' => 'Todos los nuevos módulos han sido instalados correctamente.',
	'enabled' => 'Activado',
	'disabled' => 'Desactivado',
	'enable' => 'Activar',
	'disable' => 'Desactivar',
	'module_enabled' => 'Módulo activado.',
	'module_disabled' => 'Módulo disactivado.',
	'author' => 'Autor:',
<<<<<<< HEAD
=======
	'author_x' => 'Autor: {x}', // Don't replace {x}
	'module_outdated' => 'We have detected that this module is intended for Nameless version {x}, but you are running Nameless version {y}', // Don't replace "{x}" or "{y}"
	'find_modules' => 'Find Modules',
	'view_all_modules' => 'View all modules',
	'unable_to_retrieve_modules' => 'Unable to retrieve modules',
	'module' => 'Module',
>>>>>>> upstream/v2

	// Styles
	'templates' => 'Temas',
	'template_outdated' => 'Hemos detectado que su plantilla está destinada a la versión Namelessmc {x}, pero estás ejecutando la versión Namelessmc {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Activo',
	'deactivate' => 'Desactivar',
	'activate' => 'Activar',
	'warning_editing_default_template' => '¡Advertencia! No se recomienda editar la plantilla predeterminada.',
	'images' => 'Imágenes',
	'upload_new_image' => 'Subir una nueva imagen',
	'reset_background' => 'Restablecer fondo',
	'install' => '<i class="fa fa-plus-circle"></i> Instalar',
	'template_updated' => 'Plantilla actualizada correctamente.',
	'default' => 'Por defecto',
	'make_default' => 'Hacer por defecto',
	'default_template_set' => 'Plantilla predeterminada establecida en {x} exitosamente.', // Don't replace {x}
	'template_deactivated' => 'Tema desactivado.',
	'template_activated' => 'Tema activado.',
	'permissions' => 'Permisos',
	'setting_perms_for_x' => 'Configurando permisos para el tema {x}', // Don't replace {x}
	'templates_installed_successfully' => 'Todos los nuevos temas han sido instalados correctamente.',
	'confirm_delete_template' => '¿Estás seguro de que quieres eliminar este tema?',
	'delete' => 'Eliminar',
	'template_deleted_successfully' => 'Tema eliminado correctamente.',
<<<<<<< HEAD
    'background_image_x' => 'Imagen de background: <strong>{x}</strong>', // Don't replace {x}
=======
	'background_image_x' => 'Imagen de background: <strong>{x}</strong>', // Don't replace {x}
	'find_templates' => 'Find Templates',
	'view_all_templates' => 'View all templates',
	'unable_to_retrieve_templates' => 'Unable to retrieve templates',
	'template' => 'Template',
	'stats' => 'Stats',
	'downloads_x' => 'Downloads: {x}',
	'views_x' => 'Views: {x}',
	'rating_x' => 'Rating: {x}',
	'editing_template_x' => 'Editing template {x}', // Don't replace {x}
	'cant_write_to_template' => 'Can\'t write to template file! Please check file permissions.',
	'unable_to_delete_template' => 'Unable to fully delete template. Please check file permissions.',
>>>>>>> upstream/v2

	// Users & groups
	'users' => 'Usuarios',
	'groups' => 'Grupos',
	'group' => 'Grupo',
	'new_user' => '<i class="fa fa-plus-circle"></i> Nuevo usuario',
	'creating_new_user' => 'Crear nuevo usuario',
	'registered' => 'Registrado',
	'user_created' => 'Usuario creado correctamente.',
	'cant_delete_root_user' => 'No se puede eliminar el usuario root!',
	'cant_modify_root_user' => 'No se puede modificar el grupo del usuario root!',
	'user_deleted' => 'Usuario eliminado correctamente.',
	'confirm_user_deletion' => '¿Está seguro de que desea eliminar a el usuario <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Validar usuario',
	'update_uuid' => 'Actualizar UUID',
	'update_mc_name' => 'Actualizar el nombre de usuario de Minecraft',
	'reset_password' => 'Restablecer la contraseña',
	'punish_user' => 'Castigar a este usuario',
	'delete_user' => 'Borrar a este usuario',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Otras acciones',
	'disable_avatar' => 'Desactivar avatar',
	'select_user_group' => 'Debe seleccionar un grupo de usuarios.',
	'uuid_max_32' => 'El UUID debe tener un máximo de 32 caracteres.',
	'title_max_64' => 'El título de el usuario debe tener un máximo de 64 caracteres.',
	'group_id' => 'Grupo ID',
	'name' => 'Nombre',
	'title' => 'Título del usuario',
	'new_group' => '<i class="fa fa-plus-circle"></i> Nuevo grupo',
	'group_name_required' => 'Introduzca un nombre de grupo.',
	'group_name_minimum' => 'Asegúrese de que su nombre de grupo tenga un mínimo de 2 carácteres.',
	'group_name_maximum' => 'Asegúrese de que su nombre de grupo tenga un máximo de 20 carácteres.',
	'creating_group' => 'Crear nuevo grupo',
	'group_html_maximum' => 'Asegúrese de que el HTML de su grupo no tenga más de 1024 caracteres.',
	'group_html' => 'Grupo HTML',
	'group_html_lg' => 'Grupo HTML extenso',
	'group_username_colour' => 'Nombre de usuario del grupo',
	'group_staff' => '¿Será el grupo un grupo del personal?',
	'group_modcp' => '¿Puede el grupo ver el ModCP?',
	'group_admincp' => '¿Puede el grupo ver el AdminCP?',
	'delete_group' => 'Eliminar grupo',
	'confirm_group_deletion' => '¿Está seguro de que desea eliminar el grupo {x}?', // Don't replace {x}
	'group_not_exist' => 'Ese grupo no existe.',
	'secondary_groups' => 'Grupos secundarios',
	'secondary_groups_info' => 'El usuario podrá tener los permisos secundarios de los siguientes rangos. Ctrl+click para seleccionar/deseleccionar múltiples grupos.',
	'unable_to_update_uuid' => 'No se puede actualizar UUID.',
	'default_group' => '¿Será el grupo por defecto (para nuevos Usuarios)?',
	'user_id' => 'User ID',
	'uuid' => 'UUID',
<<<<<<< HEAD
=======
	'group_order' => 'Group Order',
	'group_created_successfully' => 'Group created successfully.',
	'group_updated_successfully' => 'Group updated successfully.',
	'group_deleted_successfully' => 'Group deleted successfully.',
	'unable_to_delete_group' => 'Unable to delete a default group, or a group that can view the StaffCP. Please update the group settings first!',
	'can_view_staffcp' => 'Can the group view the StaffCP?',
>>>>>>> upstream/v2

	// Permissions
	'select_all' => 'Seleccionar todo',
	'deselect_all' => 'Deseleccionar todo',
	'background_image' => 'Imagen de background',
	'can_edit_own_group' => 'No puedes editar los permisos de tu propio grupo.',
	'permissions_updated_successfully' => 'Permisos actualizados correctamente.',
	'cant_edit_this_group' => 'No puedes editar los permisos de ese grupo.',

	// General Admin language
	'task_successful' => 'Tarea éxitosa.',
	'invalid_action' => 'Acción no válida.',
	'enable_night_mode' => 'Activar modo nocturno',
	'disable_night_mode' => 'Desactivar modo nocturno',
	'view_site' => 'Ver el sitio',
	'signed_in_as_x' => 'Logueado como: {x}', // Don't replace {x}
    'warning' => 'Advertencia',

    // Maintenance
    'maintenance_mode' => 'Modo de mantenimiento',
    'maintenance_enabled' => 'El modo de mantenimiento está activado.',
    'enable_maintenance_mode' => '¿Habilitar modo de mantenimiento?',
    'maintenance_mode_message' => 'Mensaje de modo de mantenimiento',
    'maintenance_message_max_1024' => 'Asegúrese de que su mensaje de mantenimiento tenga un máximo de 1024 caracteres.',

	// Security
	'acp_logins' => 'AdminCP: Inicios de sesión',
	'please_select_logs' => 'Por favor, seleccione registros para ver',
	'ip_address' => 'Direccion IP',
	'template_changes' => 'Cambios de temas',
	'file_changed' => 'Archivo cambiado',
	'all_logs' => 'All Logs',
	'action' => 'Action',
	'action_info' => 'Action Info',

	// Updates
	'update' => 'Actualizar',
	'current_version_x' => 'Versión actual: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'Nueva versión: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'Hay una nueva actualización disponible',
<<<<<<< HEAD
=======
	'new_urgent_update_available' => 'There is a new urgent update available. Please update as soon as possible!',
>>>>>>> upstream/v2
	'up_to_date' => 'La instalación de NamelessMC está actualizada!',
	'urgent' => 'Esta actualización es una actualización urgente',
	'changelog' => 'Registro de cambios',
	'update_check_error' => 'Se ha producido un error al comprobar si hay una actualización:',
	'instructions' => 'Instrucciones',
	'download' => 'Descargar',
	'install_confirm' => 'Asegúrese de haber descargado el paquete y cargado primero los archivos contenidos.',

	// Widgets
	'widgets' => 'Widgets',
	'widget_enabled' => 'Widget activado',
	'widget_disabled' => 'Widget desactivado',
<<<<<<< HEAD
=======
	'widget_updated' => 'Widget updated.',
>>>>>>> upstream/v2
	'editing_widget_x' => 'Editando el widget {x}', // Don't replace {x}
	'module_x' => 'Módulo: {x}', // Don't replace {x}
	'widget_order' => 'Widget Order',

    // Online users widget
    'include_staff_in_user_widget' => '¿Incluir a los miembros del Staff en el widget de los Usuarios?',

    // Custom Pages
    'pages' => 'Páginas',
    'custom_pages' => 'Páginas',
    'new_page' => '<i class="fa fa-plus-circle"></i> Nueva página',
    'no_custom_pages' => 'No tienes páginas personalizadas.',
    'creating_new_page' => 'Creando nueva página personalizada',
    'page_title' => 'Título de la página',
    'page_path' => 'Ruta de la página',
    'page_icon' => 'Icono de la página',
    'page_link_location' => 'Ubicación del vínculo',
    'page_link_navbar' => 'Barra de navegación',
    'page_link_footer' => 'Pié de la página',
    'page_link_more' => '"Más": Menú desplegable',
    'page_link_none' => 'No hay link',
    'page_content' => 'Contenido de la página',
    'page_redirect' => '¿Redirigir página?',
    'page_redirect_to' => 'Redirigr a (con procedimiento http://)',
    'unsafe_html' => '¿Permitir HTML inseguro?',
    'unsafe_html_warning' => 'Al habilitar esta opción, se puede usar cualquier HTML en la página, incluido un JavaScript potencialmente peligroso. Solo habilite esto si está seguro de que su HTML es seguro.',
    'include_in_sitemap' => 'Include in sitemap?',
<<<<<<< HEAD
=======
    'sitemap_link' => 'Sitemap link:',
>>>>>>> upstream/v2
    'page_permissions' => 'Permisos de la página',
    'view_page' => '¿Ver página?',
    'editing_page_x' => 'Editando página {x}', // Don't replace {x}
    'unable_to_create_page' => 'No se puede crear la página:',
    'page_title_required' => 'Se requiere el nombre de la página',
    'page_url_required' => 'Se requiere la ruta de la página',
    'link_location_required' => 'Se requiere la ubicación del vínculo',
    'page_title_minimum_2' => 'El título de la página debe ser mínimo de 2 carácteres',
    'page_url_minimum_2' => 'La ruta de la página debe ser mínimo de 2 carácteres',
    'page_title_maximum_30' => 'El título de la página debe ser máximo de 30 carácteres',
    'page_icon_maximum_64' => 'El ícono de la página debe ser máximo de 64 carácteres',
    'page_url_maximum_20' => 'La ubicación del vínculo debe ser máximo de 20 carácteres.',
    'page_content_maximum_100000' => 'El contenido de la página debe ser máximo de 100000 carácteres.',
    'page_redirect_link_maximum_512' => 'The page redirect link must be a maximum of 512 characters.',
    'confirm_delete_page' => '¿Estás seguro de que quieres eliminar esa página?',
<<<<<<< HEAD
=======
    'page_created_successfully' => 'Page created successfully.',
    'page_updated_successfully' => 'Page updated successfully.',
    'page_deleted_successfully' => 'Page deleted successfully.',
>>>>>>> upstream/v2

    // API
    'api' => 'API',
    'enable_api' => '¿Habilitar API?',
    'api_info' => 'La API permite que los complementos y otros servicios interactúen con su sitio web, como el <a href="https://namelessmc.com/resources/resource/5-namelessplugin/" target="_blank" >plugin oficial de NamelessMC</a>.',
    'enable_legacy_api' => '¿Habilitar API heredada?',
    'legacy_api_info' => 'La API heredada permite que los complementos que usan la antigua API de la versión 1 de Nameless funcionen con su sitio web versión 2.',
    'confirm_api_regen' => '¿Estás seguro de que quieres regenerar tu llave API?',
<<<<<<< HEAD
    'api_registration_email' => 'EMAIL DE REGISTRO DE LA API',
=======
	'api_key' => 'API Key',
	'api_url' => 'API URL',
	'copy' => 'Copy',
	'api_key_regenerated' => 'The API key has been regenerated successfully.',
    'api_registration_email' => 'EMAIL DE REGISTRO DE LA API',
	'show_registration_link' => 'Show registration link',
	'registration_link' => 'Registration Link',
>>>>>>> upstream/v2
    'link_to_complete_registration' => 'Link para completar registro: {x}', // Don't replace {x}
    'api_verification' => '¿Habilitar verificación API?',
    'api_verification_info' => 'Si está habilitado, las cuentas solo se pueden verificar a través de la API, por ejemplo, dentro del juego utilizando el complemento oficial Nameless. <strong>¡Esta opción anulará la verificación del correo electrónico y las cuentas se activarán automáticamente!</strong><br />Debe configurar su grupo predeterminado para que tenga permisos limitados y luego actualice el grupo de validación posterior en la pestaña AdminCP -> Núcleo -> Registro al grupo de miembros completo con permisos normales.',
    'enable_username_sync' => '¿Habilitar sincronización con el nombre de usuario?',
    'enable_username_sync_info' => 'Si está habilitado, el nombre de usuario en el sitio web será cambiado el nombre de usuario usado en el servidor.',
<<<<<<< HEAD
=======
	'api_settings_updated_successfully' => 'API settings updated successfully.',
>>>>>>> upstream/v2

	// File uploads
	'drag_files_here' => 'Arrastre los archivos aquí para cargarlos.',
	'invalid_file_type' => 'Tipo de archivo invalido!',
	'file_too_big' => '¡Archivo demasiado grande! Su archivo pesa {{filesize}} y el límite es {{maxFilesize}}', // Don't replace {{filesize}} or {{maxFilesize}}
	'allowed_proxies' => 'Proxies permitidos',
	'allowed_proxies_info' => 'Lista separada por líneas de direcciones IP proxy permitidas.',

	// Error logs
	'error_logs' => 'Error Logs',
	'notice_log' => 'Notice log',
	'warning_log' => 'Warning log',
	'custom_log' => 'Custom log',
	'other_log' => 'Other log',
	'fatal_log' => 'Fatal log',
	'log_file_not_found' => 'Log file not found.',
<<<<<<< HEAD
=======
	'log_purged_successfully' => 'The log has been purged successfully.',
>>>>>>> upstream/v2

	// Hooks
	'discord_hooks' => 'Discord Hooks',
	'discord_hooks_info' => 'Send a message to a Discord channel when something happens on your site. Create a Discord hook in your Discord Server Settings -> Webhooks tab.',
	'discord_hook_url' => 'Discord webhook URL',
	'discord_hook_events' => 'Enabled Discord hook events (Ctrl+click to select multiple events)',
	'register_hook_info' => 'User registration',
	'validate_hook_info' => 'User validation',

	// Sitemap
	'unable_to_load_sitemap_file_x' => 'Unable to load sitemap file {x}', // Don't replace {x}
	'sitemap_generated' => 'Sitemap generated successfully',
	'sitemap_not_writable' => 'The <strong>cache/sitemaps</strong> directory is not writable.',
	'cache_not_writable' => 'The <strong>cache</strong> directory is not writable.',
	'generate_sitemap' => 'Generate Sitemap',
	'download_sitemap' => 'Download Sitemap',
	'sitemap_not_generated_yet' => 'A sitemap has not been generated yet!',
	'sitemap_last_generated_x' => 'The sitemap was last generated {x}', // Don't replace {x}

	// Page metadata
	'page_metadata' => 'Page Metadata',
	'metadata_page_x' => 'Viewing metadata for page {x}', // Don't replace {x}
	'keywords' => 'Keywords',
	'description_max_500' => 'The description must be at most 500 characters.',
<<<<<<< HEAD
=======
	'page' => 'Page',
	'metadata_updated_successfully' => 'Metadata updated successfully.',

	// Dashboard
	'total_users' => 'Total Users',
	'total_users_statistic_icon' => '<i class="fas fa-users"></i>',
	'recent_users' => 'New Users',
	'recent_users_statistic_icon' => '<i class="fas fa-users"></i>',
	'average_players' => 'Average Players',
	'nameless_news' => 'NamelessMC News',
	'unable_to_retrieve_nameless_news' => 'Unable to retrieve the latest news',
	'confirm_leave_site' => 'You are about to leave this site! Are you sure you want to visit <strong id="leaveSiteURL">{x}</strong>?', // don't replace {x} and make sure it has the id leaveSiteURL
	'server_compatibility' => 'Server Compatibility',
	'issues' => 'Issues',

	// Other
	'source' => 'Source',
	'support' => 'Support'
>>>>>>> upstream/v2
);