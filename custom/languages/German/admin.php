<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  German Language - Admin
 */

/*
 *	Translation by BukkitTNT
 *
 *  http://BukkitTNT.de
 *  http://twitter.com/BukkitTNT
 *
 */

$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'Bitte melde dich erneut an!',

	// Sidebar
	'admin_cp' => 'AdminCP',
	'administration' => 'Administration',
	'overview' => 'Overview',
	'core' => 'Core',
	'minecraft' => 'Minecraft',
	'modules' => 'Module',
	'security' => 'Sicherheit',
	'styles' => 'Styles',
	'users_and_groups' => 'Benutzer und Gruppen',

	// Overview
	'running_nameless_version' => 'Forum läuft auf der Version <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Running PHP version <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => 'Statistiken',
    'notices' => 'Notices',
    'no_notices' => 'No notices.',
    'email_errors_logged' => 'Email errors have been logged. Click <a href="{x}">here</a> to view.', // Don't replace "{x}"

	// Core
	'settings' => 'Einstellungen',
	'general_settings' => 'Allgemeine Einstellungen',
	'sitename' => 'Name des Forum',
	'default_language' => 'Standart Sprache',
	'default_language_help' => 'Benutzer können aus einer Liste ihre eigene Sprache auswählen.',
	'installed_languages' => 'Es wurden alle neuen Sprachen erfolgreich installiert.',
	'default_timezone' => 'Default timezone',
	'registration' => 'Registration',
	'enable_registration' => 'Registrierung aktivieren?',
	'verify_with_mcassoc' => 'Sollen sich Nutzer mit MCAssoc verifizieren?',
	'email_verification' => 'Email Verifikation aktivieren?',
	'homepage_type' => 'Seiten Typ',
	'post_formatting_type' => 'Post Format Typ',
	'portal' => 'Portal',
	'missing_sitename' => 'Der Seiten-Name muss zwischen 2 und 64 Zeichen lang sein.',
	'use_friendly_urls' => 'Benutzerfreundliche URLs nutzen?',
	'use_friendly_urls_help' => 'Wichtig: Du musst das Apache2 Rewrite_Module aktiviert haben!',
	'config_not_writable' => 'Deine <strong>core/config.php</strong> Config ist nicht beschreibbar. Bitte überprüfe die Berechtigungen.',
	'social_media' => 'Soziale Medien',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Benutze Dunkles Twitter Theme?',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'successfully_updated' => 'Erfolgreich geupdatet.',
    'debugging_and_maintenance' => 'Debugging and Maintenance',
    'enable_debug_mode' => 'Enable debug mode?',
    'force_https' => 'Force https?',
    'force_https_help' => 'If enabled, all requests to your website will be redirected to https. You must have a valid SSL certificate active for this to work correctly.',
    'contact_email' => 'Contact Email Address',
    'emails' => 'Emails',
    'email_errors' => 'Email Errors',
    'registration_email' => 'Registration Email',
    'contact_email' => 'Contact Email',
    'unknown' => 'Unknown',
    'delete_email_error' => 'Delete error',
    'confirm_email_error_deletion' => 'Are you sure you want to delete this error?',
    'viewing_email_error' => 'Viewing error',
    'unable_to_write_email_config' => 'Unable to write to file <strong>core/email.php</core>. Please check file permissions.',
    'enable_mailer' => 'Enable PHPMailer?',
    'enable_mailer_help' => 'Enable this if emails aren\'t being sent by default. The use of PHPMailer requires you to have a service capable of sending emails, such as Gmail or an SMTP provider.',
    'outgoing_email' => 'Outgoing Email Address',
    'outgoing_email_info' => 'This is the email address which NamelessMC will use to send emails from.',
    'mailer_settings_info' => 'The following fields are required if you have enabled PHPMailer. For more information on how to fill out these fields, check out <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">the wiki</a>.',
    'host' => 'Host',
    'email_password_hidden' => 'The password is not shown for security reasons.',
    'send_test_email' => 'Send Test Email',
    'send_test_email_info' => 'The following button will attempt to send an email to your email address, <strong>{x}</strong>. Any errors thrown whilst sending the email will be displayed.', // Don't replace {x}
    'send' => 'Send',
    'test_email_error' => 'Test email error:',
    'test_email_success' => 'Test email sent successfully!',

	// Reactions
	'icon' => 'Icon',
	'type' => 'Type',
	'positive' => 'Positive',
	'neutral' => 'Neutral',
	'negative' => 'Negative',
	'editing_reaction' => 'Editiere Reaktion',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> New Reaction',
	'creating_reaction' => 'Erstelle Reaktion',

	// Custom profile fields
	'custom_fields' => 'Eigene Profilfelder',
	'new_field' => '<i class="fa fa-plus-circle"></i> Neues Feld',
	'required' => 'Benötigt',
	'public' => 'Öffentlich',
	'text' => 'Text',
	'textarea' => 'Textfeld',
	'date' => 'Datum',
	'creating_profile_field' => 'Erstelle Profilfeld',
	'editing_profile_field' => 'Editiere Profilfeld',
	'field_name' => 'Feldname',
	'profile_field_required_help' => 'Benötigte Felder müssen während der Registration ausgefüllt werden.',
	'profile_field_public_help' => 'Öffentliche Felder können von jedem eingesehen werden.',
	'profile_field_error' => 'Der Text muss zwischen 2-16 Zeichen lang sein.',
	'description' => 'Beschreibung',
	'display_field_on_forum' => 'Feld im Forum anzeigen?',
	'profile_field_forum_help' => 'Wenn dies aktiviert ist wird das Feld unter dem Namen angezeigt.',

	// Minecraft
	'enable_minecraft_integration' => 'Minecraft integration aktivieren.',
    'mc_service_status' => 'Minecraft Service Status',
    'service_query_error' => 'Unable to retrieve service status.',
    'authme_integration' => 'AuthMe Integration',
    'authme_integration_info' => 'When AuthMe integration is enabled, users can only register ingame.',
    'enable_authme' => 'Enable AuthMe integration?',
    'authme_db_address' => 'AuthMe Database Address',
    'authme_db_port' => 'AuthMe Database Port',
    'authme_db_name' => 'AuthMe Database Name',
    'authme_db_user' => 'AuthMe Database Username',
    'authme_db_password' => 'AuthMe Database Password',
    'authme_hash_algorithm' => 'AuthMe Hashing Algorithm',
    'authme_db_table' => 'AuthMe User Table',
    'enter_authme_db_details' => 'Please enter valid database details.',
    'authme_password_sync' => 'Synchronise AuthMe password?',
    'authme_password_sync_help' => 'If enabled, whenever a user\'s password is updated ingame, the password will also be updated on the website.',
    'minecraft_servers' => 'Minecraft Servers',
    'account_verification' => 'Minecraft Account Verification',
    'server_banners' => 'Server Banners',
    'query_errors' => 'Query Errors',
    'add_server' => '<i class="fa fa-plus-circle"></i> Add Server',
    'no_servers_defined' => 'No servers have been defined yet',
    'query_settings' => 'Query Settings',
    'default_server' => 'Default Server',
    'no_default_server' => 'No default server',
    'external_query' => 'Use external query?',
    'external_query_help' => 'If the default server query does not work, enable this option.',
    'adding_server' => 'Adding Server',
    'server_name' => 'Server Name',
    'server_address' => 'Server Address',
    'server_address_help' => 'This is the IP address or domain used to connect to your server, without the port.',
    'server_port' => 'Server Port',
    'parent_server' => 'Parent Server',
    'parent_server_help' => 'A parent server is typically the Bungee instance the server is connected to, if any.',
    'no_parent_server' => 'No parent server',
    'bungee_instance' => 'BungeeCord Instance?',
    'bungee_instance_help' => 'Select this option if the server is a BungeeCord instance.',
    'server_query_information' => 'In order to display a list of online players on your website, your server <strong>must</strong> have the \'enable-query\' option enabled in your server\'s <strong>server.properties</strong> file',
    'enable_status_query' => 'Enable status query?',
    'status_query_help' => 'If this is enabled, the status page will show this server as being online or offline.',
    'enable_player_list' => 'Enable player list?',
    'pre_1.7' => 'Minecraft version older than 1.7?',
    'player_list_help' => 'If this is enabled, the status page will display a list of online players.',
    'server_query_port' => 'Server Query Port',
    'server_query_port_help' => 'This is the query.port option in your server\'s server.properties file, provided the enable-query option in the same file is set to true.',
    'server_name_required' => 'Please enter the server name',
    'server_name_minimum' => 'Please ensure your server name is a minimum of 1 character',
    'server_name_maximum' => 'Please ensure your server name is a maximum of 20 characters',
    'server_address_required' => 'Please enter the server address',
    'server_address_minimum' => 'Please ensure your server address is a minimum of 1 character',
    'server_address_maximum' => 'Please ensure your server address is a maximum of 64 characters',
    'server_port_required' => 'Please enter the server port',
    'server_port_minimum' => 'Please ensure your server port is a minimum of 2 characters',
    'server_port_maximum' => 'Please ensure your server port is a maximum of 5 characters',
    'server_parent_required' => 'Please select a parent server',
    'query_port_maximum' => 'Please ensure your query port is a maximum of 5 characters',
    'server_created' => 'Server created successfully.',
    'confirm_delete_server' => 'Are you sure you want to delete this server?',
    'server_updated' => 'Server updated successfully.',
    'editing_server' => 'Editing Server',
    'server_deleted' => 'Server deleted successfully',
    'unable_to_delete_server' => 'Unable to delete server.',
    'leave_port_empty_for_srv' => 'You can the port empty if it is 25565, or if your domain uses an SRV record',
    'viewing_query_error' => 'Viewing Query Error',
    'confirm_query_error_deletion' => 'Are you sure you want to delete this query error?',
    'no_query_errors' => 'No query errors logged.',
    'new_banner' => '<i class="fa fa-plus-circle"></i> New Banner',
    'purge_errors' => 'Purge Errors',
    'confirm_purge_errors' => 'Are you sure you want to purge all errors?',
    'mcassoc_help' => 'mcassoc is an external service which can be used to verify users own the Minecraft account they have registered with. To use this feature, you will need to sign up for a shared key <a href="https://mcassoc.lukegb.com/" target="_blank">here</a>.',
    'mcassoc_key' => 'mcassoc Shared Key',
    'mcassoc_instance' => 'mcassoc Instance Key',
    'mcassoc_instance_help' => '<a href="#" onclick="generateInstance();">Click to generate an instance key</a>',
    'mcassoc_error' => 'Please ensure you that have entered your shared key correctly, and that you have generated an instance key correctly.',
    'updated_mcassoc_successfully' => 'mcassoc settings updated successfully.',
    'force_premium_accounts' => 'Force premium Minecraft accounts?',

	// Modules
	'modules_installed_successfully' => 'Alle neuen Module wurden erfolgreich installiert.',
	'enabled' => 'Aktiviert',
	'disabled' => 'Deaktiviert',
	'enable' => 'Aktivieren',
	'disable' => 'Deaktivieren',
	'module_enabled' => 'Modul aktiviert.',
	'module_disabled' => 'Modul deaktiviert.',

	// Styles
	'templates' => 'Templates',
	'template_outdated' => 'Dieses Template ist nicht für die aktuell installierte NamelessMC Version',
	'active' => 'Aktiv',
	'deactivate' => 'Deaktivieren',
	'activate' => 'Aktivieren',
	'warning_editing_default_template' => 'Warnung! Editiere nicht das Standart Template.',
	'images' => 'Bilder',
	'upload_new_image' => 'Lade ein neues Bild hoch.',
	'reset_background' => 'Hintergrund zurücksetzen.',
	'install' => '<i class="fa fa-plus-circle"></i> Installieren',
	'template_updated' => 'Template erfolgreich geupdatet.',
	'default' => 'Standart',
	'make_default' => 'Als Standart setzen.',
	'default_template_set' => 'Das Template {x} wurde als Standart gesetzt.', // Don't replace {x}
	'template_deactivated' => 'Template wurde deaktiviert.',
	'template_activated' => 'Template wurde aktiviert.',
	'permissions' => 'Permissions',

	// Users & groups
	'users' => 'Benutzer',
	'groups' => 'Gruppen',
	'group' => 'Gruppe',
	'new_user' => '<i class="fa fa-plus-circle"></i> Neuer Bentzer',
	'creating_new_user' => 'Erstelle neuen Benutzer',
	'registered' => 'Registriert',
	'user_created' => 'Benutzer wurde erstellt.',
	'cant_delete_root_user' => 'Root Nutzer darf nicht gelöscht werden!',
	'cant_modify_root_user' => 'Root Nutzer darf nicht editiert werden!',
	'user_deleted' => 'Benutzer wurde erfolgreich gelöscht.',
	'confirm_user_deletion' => 'Willst du den Benutzer <strong>{x}</strong> wirklich löschen?', // Don't replace {x}
	'validate_user' => 'Benutzer bestätigen.',
	'update_uuid' => 'UUID Updaten',
	'update_mc_name' => 'Minecraft Benutzername updaten',
	'reset_password' => 'Passwort zurücksetzen',
	'punish_user' => 'Benutzer Bannen',
	'delete_user' => 'User Löschen',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Andere Aktionen',
	'disable_avatar' => 'Avatar deaktivieren',
	'select_user_group' => 'Du musst eine Benutzergruppe auswählen.',
	'uuid_max_32' => 'UUID muss zwischen 2 und 32 Zeichen lang sein.',
	'title_max_64' => 'Der Benutzertitel muss zwischen 2 und 32 Zeichen lang sein..',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'Gruppen ID',
	'name' => 'Name',
	'title' => 'Benutzer Titel',
	'new_group' => '<i class="fa fa-plus-circle"></i> Neue Gruppe',
	'group_name_required' => 'Gib bitte einen Gruppen Namen an.',
    'group_name_minimum' => 'Der Name muss mindestens 2 Zeichen lang sein.',
    'group_name_maximum' => 'Der Name darf maximal 20 Zeichen lang sein.',
	'creating_group' => 'Neue Gruppe erstellen',
	'group_html_maximum' => 'HTML Tag darf nicht länger als 1024 Zeichen sein.',
	'group_html' => 'Gruppen HTML',
	'group_html_lg' => 'Gruppen HTML (Groß)',
	'group_username_colour' => 'Benutzernamenfarbe der Gruppe',
	'group_staff' => 'Ist diese Gruppe eine Teamgruppe?',
	'group_modcp' => 'Kann diese Gruppe auf das ModCP zugreifen?',
	'group_admincp' => 'Kann diese Gruppe auf das AdminCP zugreifen?',
	'delete_group' => 'Gruppe Löschen',
	'confirm_group_deletion' => 'Möchtest du die Gruppe {x} wirklich löschen?', // Don't replace {x}
	'group_not_exist' => 'Diese Gruppe existiert nicht.',

	// General Admin language
	'task_successful' => 'Aufgabe erfolgreich .',
	'invalid_action' => 'Ungültige Aktion.',
	'enable_night_mode' => 'Nacht Modus aktivieren',
	'disable_night_mode' => 'Nacht Modus deaktivieren',
	'view_site' => 'Seite ansehen',
	'signed_in_as_x' => 'Eingeloggt als {x}', // Don't replace {x}
    'warning' => 'Warning',

    // Maintenance
    'maintenance_mode' => 'Maintenance Mode',
    'maintenance_enabled' => 'Maintenance mode is currently enabled.',
    'enable_maintenance_mode' => 'Enable maintenance mode?',
    'maintenance_mode_message' => 'Maintenance mode message',
    'maintenance_message_max_1024' => 'Please ensure your maintenance message is a maximum of 1024 characters.',

	// Security
	'acp_logins' => 'AdminCP Logins',
	'please_select_logs' => 'Bitte wähle die Logs aus.',
	'ip_address' => 'IP Addresse',
	'template_changes' => 'Template Editierungen',
	'file_changed' => 'Datei geändert',

	// Updates
	'update' => 'Update',
	'current_version_x' => 'Aktuelle Version: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'Neue version: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'Es ist ein Update verfügbar!',
	'up_to_date' => 'Deine NamelessMC Version ist aktuell!',
	'urgent' => 'Dieses Update ist ein wichtiges Update!',
	'changelog' => 'Changelog',
	'update_check_error' => 'Fehler beim abrufen der neuesten Version:',
	'instructions' => 'Anleitung',
	'download' => 'Download',
	'install' => 'Installieren',
	'install_confirm' => 'Sei sicher dass du die Dateien zuerst hochgeladen hast!',

	// File uploads
	'drag_files_here' => 'Ziehe ein Bild hierhin.',
	'invalid_file_type' => 'Ungültiges Dateiformat!',
	'file_too_big' => 'Datei zu groß! Die Datei hat eine größe von {{filesize}}, das Limit ist {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
