<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  Spanish (ES-es)
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP', 
	'invalid_token' => 'Token no válido, inténtelo de nuevo.',
	'invalid_action' => 'Acción no válida',
	'successfully_updated' => 'Actualizado correctamente',
	'settings' => 'Configuración',
	'confirm_action' => 'Confirme acción',
	'edit' => 'Editar',
	'actions' => 'Acciones',
	'task_successful' => 'Tarea ejecutada correctamante',
	
	// Admin login
	're-authenticate' => 'Por favor, identifícate',
	
	// Admin sidebar
	'index' => 'Información general',
	'core' => 'Principal',
	'custom_pages' => 'Páginas Personalizadas',
	'general' => 'General',
	'forums' => 'Foros',
	'users_and_groups' => 'Usuarios y Grupos',
	'minecraft' => 'Minecraft',
	'style' => 'Stilo',
	'addons' => 'Complementos',
	'update' => 'Actualizar',
	'misc' => 'Misceláneo',
	
	// Admin index page
	'statistics' => 'Estadísticas',
	
	// Admin core page
	'general_settings' => 'Configuración General',
	'modules' => 'Módulos',
	'module_not_exist' => '¡El módulo no existe!',
	'module_enabled' => 'Módulo activado.',
	'module_disabled' => 'Módulo desactivado.',
	'site_name' => 'Nombre del sitio',
	'language' => 'Idioma',
	'voice_server_not_writable' => 'core/voice_server.php no es modificable. Verifique permisos',
	'email' => 'Correo',
	'incoming_email' => 'Dirección de entrada',
	'outgoing_email' => 'Dirección de salida',
	'outgoing_email_help' => 'Sólo necesario si la función de correo de PHP está activada',
	'use_php_mail' => '¿Usar la función mail() de PHP?',
	'use_php_mail_help' => 'Recomendado: activar. Si su sitio envia correos electrónicos, por favor, desactívelo y edite core/email.php con la configuración del corero.',
	'use_gmail' => '¿Usar Gmail para enviar correo?',
	'use_gmail_help' => 'Sólo disponible si la funcion de correo de PHP está desactivada. Si elije no usar Gmail, se usuará el protocolo SMTP. De todas formas, edite la configuración en core/email.php.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Seleccione una página para editarla.',
	'page' => 'Página:',
	'url' => 'URL:',
	'page_url' => 'URL de la página',
	'page_url_example' => '(Precedido de "/", por ejemplo /help/)',
	'page_title' => 'Título de la página',
	'page_content' => 'Contenido de la página',
	'new_page' => 'Nueva página',
	'page_successfully_created' => 'Página creada correctamente',
	'page_successfully_edited' => 'Página editada correctamente',
	'unable_to_create_page' => 'No se ha podido crear la página.',
	'unable_to_edit_page' => 'No se ha podido editar la página.',
	'create_page_error' => 'Por favor, asegúrese que ha introducido una URL entre 1 y 20 caracteres, un títlo entre 1 y 30, y el contenido de la página tener entre 5 y 20480 caracteres.',
	'delete_page' => 'Borrar página',
	'confirm_delete_page' => '¿Seguro que quiere borrar la página?',
	'page_deleted_successfully' => 'Página borrada.',
	'page_link_location' => 'Mostar página en:',
	'page_link_navbar' => 'Barra de navegación',
	'page_link_more' => 'Barra de navegación con menú "Mas"',
	'page_link_footer' => 'Pie de página',
	
	// Admin forum page
	'labels' => 'Etiquetas de tema',
	'new_label' => 'Nueva etiqueta',
	'no_labels_defined' => 'No se han definido etiquetas',
	'label_name' => 'Nombre de etiqueta',
	'label_type' => 'Tipo de etiqueta',
	'label_forums' => 'Etiqueta de Foros',
	'label_creation_error' => 'Error al crear la etiqueta. Por favor, asegúrese que no tiene más de 32 caracteres y ha especificado el tipo.',
	'confirm_label_deletion' => '¿Seguro que quiere borrar esta etiqueta?',
	'editing_label' => 'Editar etiqueta',
	'label_creation_success' => 'Etiqueta creada correctamente',
	'label_edit_success' => 'Etiqueta editada correctamente',
	'label_default' => 'Defecto',
	'label_primary' => 'Primario',
	'label_success' => 'Correcto',
	'label_info' => 'Información',
	'label_warning' => 'Aviso',
	'label_danger' => 'Peligo',
	'new_forum' => 'Nuevo foro',
	'forum_layout' => 'Características del Foro',
	'table_view' => 'Vista de tabla',
	'latest_discussions_view' => 'Ver último mentasaje',
	'create_forum' => 'Crear foro',
	'forum_name' => 'Nombre del foro',
	'forum_description' => 'Descripción del foro',
	'delete_forum' => 'Borrar foro',
	'move_topics_and_posts_to' => 'Move mensaje y respuestas a',
	'delete_topics_and_posts' => 'Borra mensaje y respuestas',
	'parent_forum' => 'Foro padre',
	'has_no_parent' => 'No tiene padre',
	'forum_permissions' => 'Permisos del foro',
	'can_view_forum' => 'No puede ver el foro:',
	'can_create_topic' => 'No crear mensaje en:',
	'can_post_reply' => 'No puede responder:',
	'display_threads_as_news' => '¿Mostrar hilos y noticias en la página principal?',
	
	// Admin Users and Groups page
	'users' => 'Usuarios',
	'new_user' => 'Nuevo usuario',
	'created' => 'Creado',
	'user_deleted' => 'usuario borrado',
	'validate_user' => 'Usuario validado',
	'update_uuid' => 'Actulaizar UUID',
	'unable_to_update_uuid' => 'No se puede actualizar UUID.',
	'update_mc_name' => 'Actualizar nombre de Minecraft',
	'reset_password' => 'Borrar Clave',
	'punish_user' => 'Castgar Usuario',
	'delete_user' => 'Borrar Usuario',
	'minecraft_uuid' => 'UUID de Minecraft',
	'ip_address' => 'Dirección IP',
	'ip' => 'IP:',
	'other_actions' => 'Otras acciones:',
	'disable_avatar' => 'Desactivar avatar',
	'confirm_user_deletion' => '¿Seguro que quiere borrar el usuario {x}?', // Don't replace "{x}"
	'groups' => 'Grupos',
	'group' => 'Grupo',
	'new_group' => 'Nuevo Grupo',
	'id' => 'ID',
	'name' => 'Nombre',
	'create_group' => 'Crear Grupo',
	'group_name' => 'Nombre del grupo',
	'group_html' => 'HTML del Grupo',
	'group_html_lg' => 'HTML extendido del Grupo',
	'donor_group_id' => 'ID Paquete de compra',
	'donor_group_id_help' => '<p>Este es el ID del paquete en Buycraft, MinecraftMarket or MCStock.</p><p>Puede estar vacio.</p>',
	'donor_group_instructions' => 	'<p>Los grupos de paquetes deben ser creados en orden inverso, <strong>de menor vaolr a mayor valor</strong>.</p>
									<p>Por ejemplo. Un paquete de 10€ debe ser creado antes que uno de 20€.</p>',
	'delete_group' => 'Borrar grupo',
	'confirm_group_deletion' => 'Seguro que desea borar el grupo {x}?', // Don't replace "{x}"
	'group_staff' => '¿Es un grupo de administración?',
	'group_modcp' => '¿Puede el grupo ver ModCP?',
	'group_admincp' => '¿Puede el grupo ver AdminCP?',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Configuración de Minecraft',
	'use_plugin' => '¿Usar el plugin Nameless de Minecraft?',
	'force_avatars' => '¿Forzar las imagenes Minecraft?',
	'uuid_linking' => 'Enable UUID linking?',
	'use_plugin_help' => 'Using the plugin allows for rank synchronisation and also ingame registration and ticket submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Plugin Settings',
	'confirm_api_regen' => 'Are you sure you want to generate a new API key?',
	'servers' => 'Servers',
	'new_server' => 'New Server',
	'confirm_server_deletion' => 'Are you sure you want to delete this server?',
	'main_server' => 'Main Server',
	'main_server_help' => 'The server players connect through. Normally this will be the Bungee instance.',
	'choose_a_main_server' => 'Choose a main server..',
	'external_query' => 'Use external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Editing server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (with port)',
	'show_on_play_page' => 'Show on Play page?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Server Name',
	'invalid_server_id' => 'Invalid server ID',
	'show_players' => 'Show player list on Play page?',
	'server_edited' => 'Server edited successfully',
	'server_created' => 'Server created successfully',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'No query errors logged',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => 'Are you sure you want to delete this error?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Themes',
	'templates' => 'Templates',
	'installed_themes' => 'Installed themes',
	'installed_templates' => 'Installed templates',
	'installed_addons' => 'Installed addons',
	'install_theme' => 'Install Theme',
	'install_template' => 'Install Template',
	'install_addon' => 'Install Addon',
	'install_a_theme' => 'Install a theme',
	'install_a_template' => 'Install a template',
	'install_an_addon' => 'Install an addon',
	'active' => 'Active',
	'activate' => 'Activate',
	'deactivate' => 'Deactivate',
	'theme_install_instructions' => 'Please upload themes to the <strong>styles/themes</strong> directory. Then, click the "scan" button below.',
	'template_install_instructions' => 'Please upload templates to the <strong>styles/templates</strong> directory. Then, click the "scan" button below.',
	'addon_install_instructions' => 'Please upload addons to the <strong>addons</strong> directory. Then, click the "scan" button below.',
	'addon_install_warning' => 'Addons are installed at your own risk. Please back up your files and the database before proceeding',
	'scan' => 'Scan',
	'theme_not_exist' => 'That theme doesn\'t exist!',
	'template_not_exist' => 'That template doesn\'t exist!',
	'addon_not_exist' => 'That addon doesn\'t exist!',
	'style_scan_complete' => 'Completed, any new styles have been installed.',
	'addon_scan_complete' => 'Completed, any new addons have been installed.',
	'theme_enabled' => 'Theme enabled.',
	'template_enabled' => 'Template enabled.',
	'addon_enabled' => 'Addon enabled.',
	'theme_deleted' => 'Theme deleted.',
	'template_deleted' => 'Template deleted.',
	'addon_disabled' => 'Addon disabled.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Are you sure you wish to delete the theme <strong>{x}</strong>?<br /><br />The theme will be deleted from your <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />The template will be deleted from your <strong>styles/templates</strong> directory.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Other Settings',
	'enable_error_reporting' => 'Enable error reporting?',
	'error_reporting_description' => 'This should only be used for debugging purposes, it\'s highly recommended this is left as disabled.',
	'display_page_load_time' => 'Display page loading time?',
	'page_load_time_description' => 'Having this enabled will display a speedometer in the footer which will display the page loading time.',
	'reset_website' => 'Reset Website',
	'reset_website_info' => 'This will reset your website settings. <strong>Addons will be disabled but not removed, and their settings will not change.</strong> Your defined Minecraft servers will also remain.',
	'confirm_reset_website' => 'Are you sure you want to reset your website settings?'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Home',
	'play' => 'Play',
	'forum' => 'Forum',
	'vote' => 'Vote',
	'donate' => 'Donate',
	'more' => 'More',
	'staff_apps' => 'Staff Applications'
);

/*
 * User Related
 */
$user_language = array(
	// Registration
	'create_an_account' => 'Create an Account',
	'username' => 'Username',
	'minecraft_username' => 'Minecraft Username',
	'email' => 'Email',
	'email_address' => 'Email Address',
	'password' => 'Password',
	'confirm_password' => 'Confirm Password',
	'i_agree' => 'I Agree',
	'agree_t_and_c' => 'By clicking <strong class="label label-primary">Register</strong>, you agree to our <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a>.',
	'register' => 'Register',
	'sign_in' => 'Sign In',
	'sign_out' => 'Sign Out',
	'terms_and_conditions' => 'Terms and Conditions',
	'successful_signin' => 'You have been signed in successfully',
	'incorrect_details' => 'Incorrect details',
	'remember_me' => 'Remember me',
	'forgot_password' => 'Forgot Password',
	'must_input_username' => 'You must insert a username.',
	'must_input_password' => 'You must insert a password.',
	'inactive_account' => 'Your account is currently inactive. Did you request a password reset?',
	'account_banned' => 'Your account has been banned.',
	'successfully_logged_out' => 'You have been successfully logged out.',
	'signature' => 'Signature',
	'registration_check_email' => 'Please check your emails for a validation link. You won\'t be able to log in until this is clicked.',
	'unknown_login_error' => 'Sorry, there was an unknown error whilst logging you in. Please try again later.',
	'validation_complete' => 'Thanks for registering! You can now log in.',
	'validation_error' => 'Error processing your request. Please try clicking the link again.',
	'registration_error' => 'Please ensure you have filled out all fields, and that your username is between 3 and 20 characters long and your password is between 6 and 30 characters long.',
	
	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => 'No file chosen',
	'private_messages' => 'Private Messages',
	'profile_settings' => 'Profile Settings',
	'your_profile' => 'Your Profile',
	'topics' => 'Topics',
	'posts' => 'Posts',
	'reputation' => 'Reputation',
	'friends' => 'Friends',
	'alerts' => 'Alerts',
	
	// Messaging
	'new_message' => 'New Message',
	'no_messages' => 'No messages',
	'and_x_more' => 'and {x} more', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Message Title',
	'message' => 'Message',
	'to' => 'To:',
	'separate_users_with_comma' => 'Separate users with a comma (",")',
	'viewing_message' => 'Viewing Message',
	'delete_message' => 'Delete Message',
	'confirm_message_deletion' => 'Are you sure you want to delete this message?',
	
	// Profile settings
	'display_name' => 'Display name',
	'upload_an_avatar' => 'Upload an avatar (.jpg, .png or .gif only):',
	
	// Alerts
	'viewing_unread_alerts' => 'Viewing unread alerts. Change to <a href="/user/alerts/?view=read"><span class="label label-success">read</span></a>.',
	'viewing_read_alerts' => 'Viewing read alerts. Change to <a href="/user/alerts/"><span class="label label-warning">unread</span></a>.',
	'no_unread_alerts' => 'You have no unread alerts.',
	'no_read_alerts' => 'You have no read alerts.',
	'view' => 'View',
	'alert' => 'Alert',
	'when' => 'When',
	'delete' => 'Delete',
	'tag' => 'User Tag',
	'report' => 'Report',
	'deleted_alert' => 'Alert successfully deleted',
	
	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Password Reset',
	'email_body' => 'You are receiving this email because you requested a password reset. In order to reset your password, please use the following link:', // Body for the password reset email
	'email_body_2' => 'If you did not request the password reset, you can ignore this email.',
	'password_email_set' => 'Success. Please check your emails for further instructions.',
	'username_not_found' => 'That username does not exist.',
	'change_password' => 'Change Password',
	'your_password_has_been_changed' => 'Your password has been changed.',
	
	// Profile page
	'profile' => 'Profile',
	'player' => 'Player',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registered:',
	'pf_posts' => 'Posts:',
	'pf_reputation' => 'Reputation:',
	'user_hasnt_registered' => 'This user hasn\'t registered on our website yet',
	'user_no_friends' => 'This user has not added any friends',
	'send_message' => 'Send Message',
	'remove_friend' => 'Remove Friend',
	'add_friend' => 'Add Friend'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Overview',
	'reports' => 'Reports',
	'punishments' => 'Punishments',
	'staff_applications' => 'Staff Applications',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warn',
	'search_for_a_user' => 'Search for a user',
	'user' => 'User:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registered',
	'reason' => 'Reason:',
	
	// Reports
	'report_closed' => 'Report closed.',
	'new_comment' => 'New comment',
	'comments' => 'Comments',
	'only_viewed_by_staff' => 'Can only be viewed by staff',
	'reported_by' => 'Reported by',
	'close_issue' => 'Close issue',
	'report' => 'Report:',
	'view_reported_content' => 'View reported content',
	'no_open_reports' => 'No open reports',
	'user_reported' => 'User Reported',
	'type' => 'Type',
	'updated_by' => 'Updated By',
	'forum_post' => 'Forum Post',
	'user_profile' => 'User Profile',
	'comment_added' => 'Comment added.',
	'new_report_submitted_alert' => 'New report submitted by {x} regarding user {y}' // Don't replace "{x}" or "{y}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'News',
	'social' => 'Social',
	
	// General terms
	'submit' => 'Submit',
	'close' => 'Close',
	'cookie_message' => '<strong>This site uses cookies to enhance your experience.</strong><p>By continuing to browse and interact with this website, you agree with their use.</p>',
	'theme_not_exist' => 'The selected theme does not exist.',
	'confirm' => 'Confirm',
	'cancel' => 'Cancel',
	'guest' => 'Guest',
	'guests' => 'Guests',
	'back' => 'Back',
	'search' => 'Search',
	'help' => 'Help',
	'success' => 'Success',
	'error' => 'Error',
	'view' => 'View',
	
	// Play page
	'connect_with' => 'Connect to the server with the IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Players Online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'There are no players online!',
	'x_players_online' => 'There are {x} players online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Page loaded in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forums',
	'discussion' => 'Discussion',
	'stats' => 'Stats',
	'last_reply' => 'Last Reply',
	'ago' => 'ago',
	'by' => 'by',
	'in' => 'in',
	'views' => 'views',
	'posts' => 'posts',
	'topics' => 'topics',
	'topic' => 'Topic',
	'statistics' => 'Statistics',
	'overview' => 'Overview',
	'latest_discussions' => 'Latest Discussions',
	'latest_posts' => 'Latest Posts',
	'users_registered' => 'Users registered:',
	'latest_member' => 'Latest member:',
	'forum' => 'Forum',
	'last_post' => 'Last Post',
	'no_topics' => 'No topics here yet',
	'new_topic' => 'New Topic',
	'subforums' => 'Subforums:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Topic Locked',
	'new_reply' => 'New Reply',
	'mod_actions' => 'Mod Actions',
	'lock_thread' => 'Lock Thread',
	'unlock_thread' => 'Unlock Thread',
	'merge_thread' => 'Merge Thread',
	'delete_thread' => 'Delete Thread',
	'confirm_thread_deletion' => 'Are you sure you want to delete this thread?',
	'move_thread' => 'Move Thread',
	'sticky_thread' => 'Sticky Thread',
	'report_post' => 'Report Post',
	'quote_post' => 'Quote Post',
	'delete_post' => 'Delete Post',
	'edit_post' => 'Edit Post',
	'reputation' => 'reputation',
	'confirm_post_deletion' => 'Are you sure you want to delete this post?',
	'give_reputation' => 'Give reputation',
	'remove_reputation' => 'Remove reputation',
	'post_reputation' => 'Post Reputation',
	'no_reputation' => 'No reputation for this post yet',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Create post',
	'post_submitted' => 'Post submitted',
	'creating_post_in' => 'Creating post in: ',
	'topic_locked_permission_post' => 'This topic is locked, however your permissions allow you to post',
	
	// Edit post view
	'editing_post' => 'Editing post',
	
	// Sticky threads
	'thread_is_' => 'Thread is ',
	'now_sticky' => 'now a sticky thread',
	'no_longer_sticky' => 'no longer a sticky thread',
	
	// Create topic
	'topic_created' => 'Topic created.',
	'creating_topic_in_' => 'Creating topic in forum ',
	'thread_title' => 'Thread Title',
	'confirm_cancellation' => 'Are you sure?',
	'label' => 'Label',
	
	// Reports
	'report_submitted' => 'Report submitted.',
	'view_post_content' => 'View post content',
	'report_reason' => 'Report Reason',
	
	// Move thread
	'move_to' => 'Move to:',
	
	// Merge threads
	'merge_instructions' => 'The thread to merge with <strong>must</strong> be within the same forum. Move a thread if necessary.',
	'merge_with' => 'Merge with:',
	
	// Other
	'forum_error' => 'Sorry, we couldn\'t find that forum or topic.',
	'are_you_logged_in' => 'Are you logged in?'
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hi',
	'message' => 'Thanks for registering! In order to complete your registration, please click the following link:',
	'thanks' => 'Thanks,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'less than a minute ago',
	'1_minute' => '1 minute ago',
	'_minutes' => '{x} minutes ago',
	'about_1_hour' => 'about 1 hour ago',
	'_hours' => '{x} hours ago',
	'1_day' => '1 day ago',
	'_days' => '{x} days ago',
	'about_1_month' => 'about 1 month ago',
	'_months' => '{x} months ago',
	'about_1_year' => 'about 1 year ago',
	'over_x_years' => 'over {x} years ago'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Display _MENU_ records per page', // Don't replace "_MENU_"
	'nothing_found' => 'No results found',
	'page_x_of_y' => 'Showing page _PAGE_ of _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'No records available',
	'filtered' => '(filtered from _MAX_ total records)' // Don't replace "_MAX_"
);
 
?>
