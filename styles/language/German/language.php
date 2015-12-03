<?php
/*
 *	Translation created by LPkkjHD
 *  https://www.spigotmc.org/members/lpkkjhd.31564/
 *
 *  License: MIT
 */

/*
 *  German language (Du)
 */

/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'invalid_token' => 'Unbekannter Token, bitte versuche es erneut',
	'invalid_action' => 'Unmögliche Aktion',
	'successfully_updated' => 'Update erfolgreich',
	'settings' => 'Einstellungen',
	'confirm_action' => 'Aktion Bestätigen',
	'edit' => 'Bearbeiten',
	'actions' => 'Aktionen',
	'task_successful' => 'Aufgabe wurde erfolgreich ausgeführt',

	// Admin login
	're-authenticate' => 'Neuanmeldung Erforderlich',

	// Admin sidebar
	'index' => 'Übersicht',
	'core' => 'Core',
	'custom_pages' => 'Eigene Seiten',
	'general' => 'Allgemein',
	'forums' => 'Foren',
	'users_and_groups' => 'Benutzer und Gruppen',
	'minecraft' => 'Minecraft',
	'style' => 'Theme',
	'addons' => 'Erweiterungen',
	'update' => 'Updates',
	'misc' => 'Verschiedenes',

	// Admin index page
	'statistics' => 'Statistiken',
	'registrations_per_day' => 'Registrations per day (last 7 days)',

	// Admin core page
	'general_settings' => 'Allgemeine Einstellungen',
	'modules' => 'Module',
	'module_not_exist' => 'Dieses Modul existiert nicht!',
	'module_enabled' => 'Modul angeschaltet.',
	'module_disabled' => 'Modul ausgeschaltet.',
	'site_name' => 'Seiten Name',
	'language' => 'Sprache',
	'voice_server_not_writable' => 'core/voice_server.php ist nicht beschreibbar. Bitte überprüfe die Berechtigungen',
	'email' => 'E-Mail',
	'incoming_email' => 'Eingehende E-Mail Adresse',
	'outgoing_email' => 'Ausgehende E-Mail Adresse',
	'outgoing_email_help' => 'Nur benötigt wenn die PHP Mail funktion angeschaltet ist',
	'use_php_mail' => 'Verwende PHP mail() funktion?',
	'use_php_mail_help' => 'Empfohlen: eingeschalted. Wenn diese Website keine E-Mails versendet, deaktiviere es und ändere in core/email.php deine E-Mail Einstellungen.',
	'use_gmail' => 'Verwende GoogleMail (Gmail) um E-Mails zu versenden?',
	'use_gmail_help' => 'Diese Funktion ist nur möglich wenn die PHP mail Funktion deaktiviert ist. Wenn du nicht GoogleMail verwendest wird SMTP verwendet. Beide wege müssen manuell in core/mail.php gesetzt werden.',

	// Admin custom pages page
	'click_on_page_to_edit' => 'Klicke auf eine Seite um sie zu bearbeiten.',
	'page' => 'Seite:',
	'url' => 'URL:',
	'page_url' => 'Seiten URL',
	'page_url_example' => '(Mit vorstehendem "/". Beispiel: /help/)',
	'page_title' => 'Seiten Titel',
	'page_content' => 'Seiten Inhalt',
	'new_page' => 'Neue Seite',
	'page_successfully_created' => 'Seite erfolgreich erstellt',
	'page_successfully_edited' => 'Seite erfolgreich bearbeitet',
	'unable_to_create_page' => 'Auserstande die Seite zu erstellen.',
	'unable_to_edit_page' => 'Auserstande die Seite zu bearbeiten.',
	'create_page_error' => 'Stelle sicher, dass die eingegebene URL zwischen 1 und 20 Zeichen, der Seiten Titel zwischen 1 und 30 Zeichen und der Seiteninhalt zwischen 5 und 20480 Zeichen lang ist.',
	'delete_page' => 'Seite löschen',
	'confirm_delete_page' => 'Bist du sicher, dass du diese Seite löschen willst?',
	'page_deleted_successfully' => 'Seite wurde gelöscht',
	'page_link_location' => 'Zeige diese Seite:',
	'page_link_navbar' => 'Navigationsleiste',
	'page_link_more' => 'Navigationsleiste -> "Mehr" Menü',
	'page_link_footer' => 'Fußleiste',
	'page_link_none' => 'No page link',

	// Admin forum page
	'labels' => 'Themen Label',
	'new_label' => 'Neues Label',
	'no_labels_defined' => 'Keine Labels definiert',
	'label_name' => 'Label Name',
	'label_type' => 'Label Typ',
	'label_forums' => 'Label Foren',
	'label_creation_error' => 'Fehler beim erstellen des Labels. Bitte stelle sicher, dass der Name nicht länger als 32 Zeichen lang ist und ein Typ zugewiesen ist.',
	'confirm_label_deletion' => 'Bist du dir sicher, dass du dieses Label entfernen willst?',
	'editing_label' => 'Label bearbeiten',
	'label_creation_success' => 'Label wurde erstellt',
	'label_edit_success' => 'Label wurde bearbeitet',
	'label_default' => 'Normal',
	'label_primary' => 'Primär',
	'label_success' => 'Erfolgreich',
	'label_info' => 'Info',
	'label_warning' => 'Warnung',
	'label_danger' => 'Achtung',
	'new_forum' => 'Neues Forum',
	'forum_layout' => 'Foren Layout',
	'table_view' => 'Tabellen Ansicht',
	'latest_discussions_view' => '"Neuste Diskussion" Ansicht',
	'create_forum' => 'Forum erstellen',
	'forum_name' => 'Foren Name',
	'forum_description' => 'Foren Beschreibung',
	'delete_forum' => 'Forum löschen',
	'move_topics_and_posts_to' => 'Verschiebe Themen und Beiträge nach',
	'delete_topics_and_posts' => 'Lösche Themen und Beiträge',
	'parent_forum' => 'Übergeordnetes Forum',
	'has_no_parent' => 'Hat kein Übergeordnetes Forum',
	'forum_permissions' => 'Foren Berechtigungen',
	'can_view_forum' => 'Dürfen Forum sehen:',
	'can_create_topic' => 'Dürfen Themen erstellen:',
	'can_post_reply' => 'Dürfen in Themen antworten:',
	'display_threads_as_news' => 'Stelle Themen als News auf der Front Seite dar?',

	// Admin Users and Groups page
	'users' => 'Benutzer',
	'new_user' => 'Neuer Benutzer',
	'created' => 'Erstellt',
	'user_deleted' => 'Benutzer gelöscht',
	'validate_user' => 'Benutzer bestätigen',
	'update_uuid' => 'Aktualisiere die UUID',
	'unable_to_update_uuid' => 'Unmöglich die UUID zu Aktualisieren.',
	'update_mc_name' => 'Aktualisiere Minecraft Name',
	'reset_password' => 'Passwort zurücksetzen',
	'punish_user' => 'Benutzer bestrafen',
	'delete_user' => 'Benutzer löschen',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Adresse',
	'ip' => 'IP:',
	'other_actions' => 'Andere Aktionen:',
	'disable_avatar' => 'Avatar ausschalten',
	'confirm_user_deletion' => 'Bist du sicher, dass du den Benutzer {x} löschen willst?', // Don't replace "{x}"
	'groups' => 'Gruppen',
	'group' => 'Gruppe',
	'new_group' => 'Neue Gruppe',
	'id' => 'ID',
	'name' => 'Name',
	'create_group' => 'Gruppe erstellen',
	'group_name' => 'Gruppen Name',
	'group_html' => 'Gruppen HTML',
	'group_html_lg' => 'Gruppen HTML lang',
	'donor_group_id' => 'Spender Paket-ID',
	'donor_group_id_help' => '<p>Die Paket-ID ist die ID des Gruppen Packetes von Buycraft, MinecraftMarket oder MCStock.</p><p>Dieses Feld darf Freigelassen bleiben.</p>',
	'donor_group_instructions' => 	'<p>Spender Gruppen müssen in der Reihenfolge von dem <strong>kleinsten zum größten Wert</strong> erstellt werden.</p>
									<p>Beispiel: ein Paket für 10€ muss vor einem Paket das 20€ kostet erstellt werden.</p>',
	'delete_group' => 'Gruppe löschen',
	'confirm_group_deletion' => 'Bist du dir sicher, dass du die Gruppe {x} löschen willst?', // Don't replace "{x}"
	'group_staff' => 'Ist diese Gruppe eine Staff Gruppe?',
	'group_modcp' => 'Können Benutzer der Gruppe das Moderatoren Panel sehen?',
	'group_admincp' => 'Können Benutzer der Gruppe das Admin Panel sehen?',

	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft Einstellungen',
	'use_plugin' => 'Verwende das Nameless Minecraft plugin?',
	'force_avatars' => 'Erzwige die Venwendung von Minecraft Avataren?',
	'uuid_linking' => 'Erlaube UUID kopplung?',
	'use_plugin_help' => 'Das verwenden des Plugins erlaubt die Ränge Synchronisierung, ingame Registrierungen und Ticket einreichung.',
	'uuid_linking_help' => 'Wenn diese Funktion aktiviert ist werden die Benutzer Accounts nicht mit den Minecraft UUIDs verknüpft. Es wird sehr Empfohlen diese Funktion aktiviert zu lassen.',
	'plugin_settings' => 'Plugin Einstellungen',
	'confirm_api_regen' => 'Bist du sicher, dass du einen neuen API-Schlüssel generieren willst?',
	'servers' => 'Server',
	'new_server' => 'Neuer Server',
	'confirm_server_deletion' => 'Bist du sicher, dass du diesen Server löschen willst?',
	'main_server' => 'Haupt Server',
	'main_server_help' => 'Der Server durch den die Spieler connecten. Normalerweise ist dies die BungeeCord instanz.',
	'choose_a_main_server' => 'Wähle einen Hauptserver..',
	'external_query' => 'Verwende external query?',
	'external_query_help' => 'Verwendet eine externe API um den Minecraft Server abzufragen. Verwende dies nur wenn die eingebaute Abfrage nicht funktioniert. Es wird sehr Empfohlen diese Funktion deaktiviert zu lassen.',
	'editing_server' => 'Bearbeite den Server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (mit port) (numeric or domain)',
	'server_ip_with_port_help' => 'This is the IP which will be displayed to users. It will not be queried.',
	'server_ip_numeric' => 'Server IP (with port) (numeric only)',
	'server_ip_numeric_help' => 'This is the IP which will be queried, please ensure it is numeric only. It will not be displayed to users.',
	'show_on_play_page' => 'Zeite auf der Spielen Seite?',
	'pre_17' => 'Vor 1.7 Minecraft Version?',
	'server_name' => 'Server Name',
	'invalid_server_id' => 'Unbekannte Server ID',
	'show_players' => 'Zeige Player Liste auf der Spielen Seite?',
	'server_edited' => 'Server wurde erfolgreich bearbeitet',
	'server_created' => 'Server wurde erfolgreich erstellt',
	'query_errors' => 'Abfrage Fehler',
	'query_errors_info' => 'Die Folgenden Fehlermeldungen lassen dich Fehlfunktionen an dem Internen Serverabfrager diagnostizieren.',
	'no_query_errors' => 'Keine Abfrage aufgezeichnet',
	'date' => 'Datum:',
	'port' => 'Port:',
	'viewing_error' => 'Anzeige Fehler',
	'confirm_error_deletion' => 'Bist du sicher, dass du diese Fehlermeldung löschen willst?',

	// Admin Themes, Templates and Addons
	'themes' => 'Themen',
	'templates' => 'Vorlagen',
	'installed_themes' => 'Installierte Themen',
	'installed_templates' => 'Installierte Vorlagen',
	'installed_addons' => 'Installierte Addons',
	'install_theme' => 'Installiere Theme',
	'install_template' => 'Installiere Vorlage',
	'install_addon' => 'Installiere Addon',
	'install_a_theme' => 'Installiere ein Theme',
	'install_a_template' => 'Installiere eine Vorlage',
	'install_an_addon' => 'Installiere ein Addon',
	'active' => 'Aktiv',
	'activate' => 'Aktivieren',
	'deactivate' => 'Deaktivieren',
	'theme_install_instructions' => 'Bitte lade die Themen nach <strong>styles/themes</strong> hoch. Wenn dies geschehen ist klicke auf "Scannen".',
	'template_install_instructions' => 'Bitte lade Vorlagen nach <strong>styles/templates</strong> hoch. Wenn dies geschehen ist klicke auf "Scannen".',
	'addon_install_instructions' => 'Bitte lade Addons nach <strong>addons</strong> hoch. Wenn dies geschehen ist klicke auf "Scannen".',
	'addon_install_warning' => 'Addons zu installieren erfolgt auf eigene Gefahr. Bitte lege ein Backup von deinen Daten und der Datenbank an bevor du fortfährst',
	'scan' => 'Scannen',
	'theme_not_exist' => 'Dieses Theme existiert nicht!',
	'template_not_exist' => 'Dieses Template existiert nicht!',
	'addon_not_exist' => 'Dieses Addon existiert nicht!',
	'style_scan_complete' => 'Fertig, neue Styles wurden installiert.',
	'addon_scan_complete' => 'Fertig, neue Addons wurden installiert.',
	'theme_enabled' => 'Theme aktiviert.',
	'template_enabled' => 'Vorlage aktiviert.',
	'addon_enabled' => 'Addon aktiviert.',
	'theme_deleted' => 'Theme gelöscht.',
	'template_deleted' => 'Vorlage gelöscht.',
	'addon_disabled' => 'Addon deaktiviert.',
	'inverse_navbar' => 'Umgekehrte Navigationsleiste',
	'confirm_theme_deletion' => 'Bist du sicher, dass du das <strong>{x}</strong> Theme löschen willst?<br /><br />Das Theme wird aus dem <strong>styles/themes</strong> Ordner entfernt.', // Don't replace {x}
	'confirm_template_deletion' => 'Bist du sicher, dass du die Vorlage <strong>{x}</strong> löschen willst?<br /><br />Das Theme wird aus dem <strong>styles/templates</strong> Ordner entfernt.', // Don't replace {x}

	// Admin Misc page
	'other_settings' => 'Andere Einstellungen',
	'enable_error_reporting' => 'Aktiviere Fehler Rückmeldungen?',
	'error_reporting_description' => 'Fehler Rückmeldungen sollten nur für debugging Zwecke verwendet werden, es wird dringend Empfohlen diese Option deaktiviert zu lassen!',
	'display_page_load_time' => 'Zeige Seitenladezeit?',
	'page_load_time_description' => 'Wenn diese Aktion aktiviert ist wird in der Fußzeile ein Speedometer angezeigt welches die Ladezeit anzeigt.',
	'reset_website' => 'Website zurücksetzen',
	'reset_website_info' => 'Mit dieser Funktion wird die Website zurückgesetzt. <strong>Addons werden deaktiviert aber nicht gelöscht und Einstellungen, die am Addon vorgenommen wurden sind immer noch present.</strong> Die definierten Minecraft Server werden auch bleiben.',
	'confirm_reset_website' => 'Bist du sicher, dass du die Website zurücksetzen willst?'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Home',
	'play' => 'Spielen',
	'forum' => 'Forum',
	'vote' => 'Voten',
	'donate' => 'Spenden',
	'more' => 'Mehr',
	'staff_apps' => 'Bewerbungen'
);

/*
 * User Related
 */
$user_language = array(
	// Registration
	'authme_password' => 'AuthMe Passwort',
	'create_an_account' => 'Erstelle einen Account',
	'username' => 'Benutzername',
	'minecraft_username' => 'Minecraft Benutzername',
	'email' => 'E-Mail',
	'email_address' => 'E-Mail Adresse',
	'password' => 'Passwort',
	'confirm_password' => 'Passwort bestätigen',
	'i_agree' => 'Ich Stimme zu',
	'agree_t_and_c' => 'Mit dem Klicken auf <strong class="label label-primary">Registrieren</strong>, Stimmst du unseren <a href="#" data-toggle="modal" data-target="#t_and_c_m">Bedingungen und Konditionen</a> zu.',
	'register' => 'Registrieren',
	'sign_in' => 'Anmelden',
	'sign_out' => 'Abmelden',
	'terms_and_conditions' => 'Bedingungen und Konditionen',
	'successful_signin' => 'Du wurdest erfolgreich angemeldet',
	'incorrect_details' => 'Falsche Details',
	'remember_me' => 'Login Merken',
	'forgot_password' => 'Passwort vergessen',
	'must_input_username' => 'Du musst einen Benutzername angeben.',
	'must_input_password' => 'Du musst ein Passwort angeben.',
	'inactive_account' => 'Dein Account ist im Moment deaktiviert. Hast du dein Passwort zurücksetzen lassen?',
	'account_banned' => 'Dein Account wurde gebannt.',
	'successfully_logged_out' => 'Du wurdest erfolgreich ausgeloggt.',
	'signature' => 'Signatur',
	'registration_check_email' => 'Bitte überprüfe deine E-Mails auf einen Bestätigungslink. Du wirst dich nicht Anmelden können, bist dieser geklickt wurde.',
	'unknown_login_error' => 'Entschuldigung, während du dich einloggen wollstest ist ein unbekannter Fehler aufgetreten. Bitte Versuche es später erneut.',
	'validation_complete' => 'Danke für\'s Registrieren! Du kannst dich nun Anmelden',
	'validation_error' => 'Fehler beim Bearbeiten deiner Anfrage. Versuche den link später noch einmal zu klicken.',
	'registration_error' => 'Bitte Stelle sicher, dass du alle Felder ausgefüllt hast, wobei der Benutzername zwischen 3 und 20 Zeichen und das Passwort zwischen 6 und 30 Zeichen lang sein muss.', // todo

	// UserCP
	'user_cp' => 'User Panel',
	'no_file_chosen' => 'Keine Datei ausgewählt',
	'private_messages' => 'Private Nachichten',
	'profile_settings' => 'Profil Einstellungen',
	'your_profile' => 'Dein Profil',
	'topics' => 'Themen',
	'posts' => 'Beiträge',
	'reputation' => 'Ansehen',
	'friends' => 'Freunde',
	'alerts' => 'Alarme',

	// Messaging
	'new_message' => 'Neue Nachicht',
	'no_messages' => 'Keine Nachichten',
	'and_x_more' => 'und {x} mehr', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Nachichten Titel',
	'message' => 'Nachicht',
	'to' => 'An:',
	'separate_users_with_comma' => 'Trenne verschiedene Namen mit Komma (",")',
	'viewing_message' => 'Lese Nachicht',
	'delete_message' => 'Lösche Nachicht',
	'confirm_message_deletion' => 'Bist du dir sicher, dass du diese Nachicht löschen willst?',

	// Profile settings
	'display_name' => 'Anzeige Name',
	'upload_an_avatar' => 'Lade einen Avatar hoch (.jpg, .png or .gif only):',
	'change_password' => 'Change password',
	'current_password' => 'Current password',
	'new_password' => 'New password',
	'repeat_new_password' => 'Repeat new password',
	'password_changed_successfully' => 'Password changed successfully',
	'incorrect_password' => 'Your current password is incorrect',

	// Alerts
	'viewing_unread_alerts' => 'Zeigt ungelesene Nachichten. Wechsele zu <a href="/user/alerts/?view=read"><span class="label label-success">gelesen</span></a>.',
	'viewing_read_alerts' => 'Zeigt gelesene Nachichten. Wechsele zu<a href="/user/alerts/"><span class="label label-warning">ungelesenen</span></a>.',
	'no_unread_alerts' => 'Du hast keine ungelesenen Benachichtungen.',
	'no_read_alerts' => 'Du hast keine "read" Benachichtungen.',  //unsure if "read" means "lesen"
	'view' => 'Anzeigen',
	'alert' => 'Benachichtigen',
	'when' => 'Wann',
	'delete' => 'Löschen',
	'tag' => 'Benutzer tag',
	'report' => 'Melden',
	'deleted_alert' => 'Benachichtig erfolgreich gelöscht',

	// Warnings
	'you_have_received_a_warning' => 'Du hast eine Warnung von {x} vom {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Zur Kenntnis genommen',

	// Forgot password
	'password_reset' => 'Passwort zurücksetzen',
	'email_body' => 'Du bekommst diese Mail, da du dein Passwort zurücksetzen lassen willst. Um dein Passwort zurückzusetzen klicke Bitte auf den Link unten:', // Body for the password reset email
	'email_body_2' => 'Wenn du dein Passwort nicht zurücksetzen willst dann kannst du diese Mail ignorieren.',
	'password_email_set' => 'Erfolgreich. Bitte prüfe dein E-Mail Postfach für weitere Instruktionen.',
	'username_not_found' => 'Dieser Benutzername existiert nicht.',
	'change_password' => 'Passwort ändern',
	'your_password_has_been_changed' => 'Dein Passwort wurde geändert.',

	// Profile page
	'profile' => 'Profil',
	'player' => 'Spieler',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registriert seit:',
	'pf_posts' => 'Beiträge:',
	'pf_reputation' => 'Ansehen:',
	'user_hasnt_registered' => 'Dieser Benutzer ist noch nicht auf unserer Website Registriert',
	'user_no_friends' => 'Dieser Benutzer hat noch keine Freunde hinzugefügt',
	'send_message' => 'Nachicht senden',
	'remove_friend' => 'Freund entfernen',
	'add_friend' => 'Freund hinzufügen',
	
	// Staff applications
	'staff_application' => 'Staff Application',
	'application_submitted' => 'Die Bewerbung wurde erfolgreich abgeschickt.',
	'application_already_submitted' => 'Du hast bereits eine Bewerbung eingesendet. Bitte warte bis diese bearbeitet wurde bevor du eine neue Bewerbung einsendest.',
	'not_logged_in' => 'Bitte melde dich an um Zugriff auf diese Seite zu bekommen.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'Moderations Panel',
	'overview' => 'Übersicht',
	'reports' => 'Meldungen',
	'punishments' => 'Bestrafungen',
	'staff_applications' => 'Bewerbungen',

	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warnen',
	'search_for_a_user' => 'Suche nach Benutzer',
	'user' => 'Benutzer:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registriert',
	'reason' => 'Grund:',

	// Reports
	'report_closed' => 'Meldung geschlossen.',
	'new_comment' => 'Neuer Kommentar',
	'comments' => 'Kommentare',
	'only_viewed_by_staff' => 'Kann nur von Staff Mitgliedern eingesehen werden',
	'reported_by' => 'Gemeldet von',
	'close_issue' => 'Schließe Angelegenheit',
	'report' => 'Meldung:',
	'view_reported_content' => 'Zeige gemeldeten Inhalt',
	'no_open_reports' => 'Keine offenen Meldungen',
	'user_reported' => 'Benutzer gemeldet',
	'type' => 'Typ',
	'updated_by' => 'Aktualisiert von',
	'forum_post' => 'Foren Beitrag',
	'user_profile' => 'Benutzer Profil',
	'comment_added' => 'Kommentar hinzugefügt.',
	'new_report_submitted_alert' => 'Neue Meldung von {x} betreffend {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Bitte stelle sicher, dass dein Kommentar aus zwischen 2 und 2048 Zeichen lang ist.',
	'viewing_open_applications' => 'Zeigt <span class="label label-info">offene</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/?view=accepted"><span class="label label-success">angenommene</span></a> oder <a href="/mod/applications/?view=declined"><span class="label label-danger">abgelehnte</span></a> Bewerbungen.',
	'viewing_accepted_applications' => 'Zeigt <span class="label label-success">angenommene</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/"><span class="label label-info">offene</span></a> oder <a href="/mod/applications/?view=declined"><span class="label label-danger">abgelehnte</span></a>.',
	'viewing_declined_applications' => 'Zeigt <span class="label label-danger">abgelehnte</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/"><span class="label label-info">offene</span></a> oder <a href="/mod/applications/?view=accepted"><span class="label label-success">angenommene</span></a>.',
	'time_applied' => 'Time Applied',
	'no_applications' => 'Keine Bewerbungen in dieser Kategorie',
	'viewing_app_from' => 'Zeigt die Bewerbung von {x}', // Don't replace "{x}"
	'open' => 'Offen',
	'accepted' => 'Angenommen',
	'declined' => 'Abgelehnt',
	'accept' => 'Annehmen',
	'decline' => 'Ablehnen',
	'new_app_submitted_alert' => 'Neue Bewerbung von {x}' // Don't replace "{x}"
);

/*
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Neues',
	'social' => 'Soziales',

	// General terms
	'submit' => 'Submit',
	'close' => 'Schließen',
	'cookie_message' => '<strong>Diese Website verwendet cookies um deine Erfahrung zu Verbessern.</strong><p>Mit dem weiteren Verwenden dieser Website stimmst du der Verwendung zu.</p>',
	'theme_not_exist' => 'Das ausgewählte Theme existiert nicht.',
	'confirm' => 'Bestätigen',
	'cancel' => 'Abbrechen',
	'guest' => 'Gast',
	'guests' => 'Gäste',
	'back' => 'Zurück',
	'search' => 'Suchen',
	'help' => 'Hilfe',
	'success' => 'Erfolgreich',
	'error' => 'Fehler',
	'view' => 'Zeige',

	// Play page
	'connect_with' => 'Verbinde zu dem Server mit der IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Spieler Online:',
	'queried_in' => 'Abgefragt in:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Es sind keine Spieler online!',
	'x_players_online' => 'Es sind {x} Spieler online.', // Don't replace {x}

	// Other
	'page_loaded_in' => 'Seite geladen in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Nichts'
);

/*
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Foren',
	'discussion' => 'Diskussion',
	'stats' => 'Statistiken',
	'last_reply' => 'Letzte Antwort',
	'ago' => 'ago',
	'by' => 'von',
	'in' => 'in',
	'views' => 'views',
	'posts' => 'Beiträge',
	'topics' => 'Themen',
	'topic' => 'Thema',
	'statistics' => 'Statistiken',
	'overview' => 'Übersicht',
	'latest_discussions' => 'Neuste Diskussionen',
	'latest_posts' => 'Neuste Beiträge',
	'users_registered' => 'Benutzer Registriert:',
	'latest_member' => 'Neustes Mitglied:',
	'forum' => 'Forum',
	'last_post' => 'Neuster Beitrag',
	'no_topics' => 'Hier gibt es noch keine Themen',
	'new_topic' => 'Neues Thema',
	'subforums' => 'Unterforen:',

	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Thema geschossen',
	'new_reply' => 'neue Antwort',
	'mod_actions' => 'Moderations Aktionen',
	'lock_thread' => 'Thema schließen',
	'unlock_thread' => 'Thema öffnen',
	'merge_thread' => 'Themen zusammenführen',
	'delete_thread' => 'Thema löschen',
	'confirm_thread_deletion' => 'Bist du sicher, dass du dieses Thema löschen willst?',
	'move_thread' => 'Thema verschieben',
	'sticky_thread' => 'Thema anheften',
	'report_post' => 'Beitrag melden',
	'quote_post' => 'Beitrag zitieren',
	'delete_post' => 'Beitrag löschen',
	'edit_post' => 'Beitrag bearbeiten',
	'reputation' => 'Bewertung',
	'confirm_post_deletion' => 'Bist du sicher, dass du diesen Beitrag löschen willst?',
	'give_reputation' => 'Bewerten',
	'remove_reputation' => 'Bewertung entfernen',
	'post_reputation' => 'Beitragsbewertung',
	'no_reputation' => 'dieser Beitrag wurde noch nicht bewertet',
	're' => 'RE:',

	// Create post view
	'create_post' => 'Beitrag erstellen',
	'post_submitted' => 'Beitrag eingereicht',
	'creating_post_in' => 'Beitrag wird erstellt in: ',
	'topic_locked_permission_post' => 'Dieses Thema ist geschlossen, Deine Berechtigungen erlauben es dir aber hier zu Posten',

	// Edit post view
	'editing_post' => 'Beitrag Bearbeiten',

	// Sticky threads
	'thread_is_' => 'Thema ist ',
	'now_sticky' => 'ab jetzt angeheftet',
	'no_longer_sticky' => 'nicht länger angeheftet',

	// Create topic
	'topic_created' => 'Thema erstellt.',
	'creating_topic_in_' => 'Erstelle ein Thena im Forum ',
	'thread_title' => 'Thema Titel',
	'confirm_cancellation' => 'Bist du sicher?',
	'label' => 'Label',

	// Reports
	'report_submitted' => 'Meldung eingereicht.',
	'view_post_content' => 'Zeige Beitragsinhalt',
	'report_reason' => 'Grund der Meldung',

	// Move thread
	'move_to' => 'Verschieben nach:',

	// Merge threads
	'merge_instructions' => 'Die Themen, die Zusammengeführt werden sollen <strong>müssen</strong> im sich im selben Forum befinden. Verschiebe das Thema falls nötig.',
	'merge_with' => 'Zusammenführen mit:',

	// Other
	'forum_error' => 'Sorry, es wurde kein Forum oder Beitrag gefunden.',
	'are_you_logged_in' => 'Bist du eingeloggt?',
	
	// Search
	'search_error' => 'Please input a search query between 1 and 32 characters long.'
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hallo,',
	'message' => 'Danke für deine Registrierung! Um deine Registration abzuschliesen klicke bitte auf den folgenden Link:',
	'thanks' => 'Danke,'
);

/*
 *  Time language, eg "1 minute ago"
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'weniger als eine Minute',
	'1_minute' => '1 Minute',
	'_minutes' => ' Minuten',
	'about_1_hour' => 'ungefähr eine Stunde',
	'_hours' => ' Stunden',
	'1_day' => '1 Tag',
	'_days' => ' Tage',
	'about_1_month' => 'ungefähr einen Monat',
	'_months' => ' Monate',
	'about_1_year' => 'ungefähr ein Jahr',
	'over_x_years' => 'mehr als {x} Jahre' // Don't replace "{x}"
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'weniger als eine Minute',
	'1_minute' => 'vor einer Minute',
	'_minutes' => 'vor {x} Minuten',
	'about_1_hour' => 'vor einer Stunde',
	'_hours' => 'vor {x} Stunden',
	'1_day' => 'vor einem Tag',
	'_days' => 'vor {x} Tagen',
	'about_1_month' => 'vor einem Monat',
	'_months' => 'vor {x} Monaten',
	'about_1_year' => 'vor einem Jahr',
	'over_x_years' => 'vor {x} Jahren'
);

/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Zeige _MENU_ einträge pro Seite', // Don't replace "_MENU_"
	'nothing_found' => 'Keine Ergebnisse gefunden',
	'page_x_of_y' => 'Zeige seite _PAGE_ von _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Keine Einträge',
	'filtered' => '(gefiltert aus _MAX_ Einträgen)' // Don't replace "_MAX_"
);

?>