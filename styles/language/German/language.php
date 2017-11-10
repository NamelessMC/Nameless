<?php
/*
 *                              Translation created by manuelgu, LPkkjHD, BukkitTNT, philipp0002 and McLive
 *                             	https://www.spigotmc.org/members/bukkittnt.71804/
 *                              https://www.spigotmc.org/members/lpkkjhd.31564/
 *								https://www.spigotmc.org/members/manuelgu.32316/
 *							  	https://www.spigotmc.org/members/philipp0002.26678/
 * 								https://www.spigotmc.org/members/mclive.1332/
 *
 *                                              License: MIT
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
	'infractions' => 'Straftaten',
	'invalid_token' => 'Unbekannte Sitzung, bitte versuche es erneut',
	'invalid_action' => 'Unmögliche Aktion',
	'successfully_updated' => 'Update erfolgreich',
	'settings' => 'Einstellungen',
	'confirm_action' => 'Aktion Bestätigen',
	'edit' => 'Bearbeiten',
	'actions' => 'Aktionen',
	'task_successful' => 'Aufgabe wurde erfolgreich ausgeführt',

	// Admin login
	're-authenticate' => 'Neuanmeldung erforderlich',

	// Admin sidebar
	'index' => 'Übersicht',
	'announcements' => 'Hinweise',
	'core' => 'Kernmodule ',
	'custom_pages' => 'Eigene Seiten',
	'general' => 'Allgemein',
	'forums' => 'Foren',
	'users_and_groups' => 'Benutzer und Gruppen',
	'minecraft' => 'Minecraft',
	'style' => 'Theme',
	'addons' => 'Erweiterungen',
	'update' => 'Updates',
	'misc' => 'Verschiedenes',
	'help' => 'Hilfe',

	// Admin index page
	'statistics' => 'Statistiken',
	'registrations_per_day' => 'Registrationen pro Tag (letzten 7 Tage)',

	// Admin announcements page
	'current_announcements' => 'Aktuelle Ankündigungen',
	'create_announcement' => 'Ankündigung erstellen',
	'announcement_content' => 'Ankündigungsinhalt',
	'announcement_location' => 'Ankündigungsposition',
	'announcement_can_close' => 'Kann die Ankündigung schließen?',
	'announcement_permissions' => 'Hinweisrechte',
	'no_announcements' => 'Noch keine Ankündigungen vorhanden.',
	'confirm_cancel_announcement' => 'Willst du diese Ankündigung wirklich nicht senden?',
	'announcement_location_help' => 'Strg-Klick um mehrere Seiten zu wählen',
	'select_all' => 'Alle auswählen',
	'deselect_all' => 'Alle abwählen',
	'announcement_created' => 'Ankündigung erfolgreich erstellt',
	'please_input_announcement_content' => 'Bitte füge noch den Inhalt und die Art hinzu',
	'confirm_delete_announcement' => 'Willst du diese Ankündigung wirklich löschen?',
	'announcement_actions' => 'Ankündigungsaktionen',
	'announcement_deleted' => 'Ankündigung erfolgreich gelöscht',
	'announcement_type' => 'Ankündigungsart',
	'can_view_announcement' => 'Kann die Ankündigung sehen?',

	// Admin core page
	'general_settings' => 'Allgemeine Einstellungen',
	'modules' => 'Module',
	'module_not_exist' => 'Dieses Modul existiert nicht!',
	'module_enabled' => 'Modul aktiviert.',
	'module_disabled' => 'Modul deaktiviert.',
	'site_name' => 'Seitenname',
	'language' => 'Sprache',
	'voice_server_not_writable' => 'core/voice_server.php ist nicht beschreibbar. Bitte überprüfe die Berechtigungen',
	'email' => 'E-Mail',
	'incoming_email' => 'Eingehende E-Mail Adresse',
	'outgoing_email' => 'Ausgehende E-Mail Adresse',
	'outgoing_email_help' => 'Nur benötigt, wenn die PHP Mail Funktion angeschaltet ist',
	'use_php_mail' => 'Verwende PHP mail() Funktion?',
	'use_php_mail_help' => 'Empfohlen: Eingeschaltet. Wenn diese Website keine E-Mails versendet, deaktiviere es und ändere in core/email.php deine E-Mail Einstellungen.',
	'use_gmail' => 'Verwende GoogleMail (Gmail) um E-Mails zu versenden?',
	'use_gmail_help' => 'Diese Funktion ist nur möglich, wenn die PHP mail() Funktion deaktiviert ist. Wenn du nicht GoogleMail verwendest, wird SMTP verwendet. Beide Wege müssen manuell in core/mail.php gesetzt werden.',
	'enable_mail_verification' => 'Aktiviere Email verifikation?',
	'enable_email_verification_help' => 'Wenn dies aktiviert ist, müssen sich neue Nutzer per Email freischalten.',
	'explain_email_settings' => 'Die folgende Einstellung wird benötigt, wenn die "Use PHP mail()" Option <strong>deaktiviert</strong> ist. Die Dokumentation dafür findest du <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">im Wiki</a>.',
	'email_config_not_writable' => 'Bitte gebe <strong>core/email.php</strong> Schreibrechte. Wir können auf die Datei nicht zugreifen.',
	'pages' => 'Pages',
	'enable_or_disable_pages' => 'Aktiviere oder deaktiviere hier Seiten.',
	'enable' => 'Aktivieren',
	'disable' => 'Deaktivieren',
	'maintenance_mode' => 'Wartungsmodus',
	'forum_in_maintenance' => 'Das Forum befindet sich im Wartungsmodus.',
	'unable_to_update_settings' => 'Fehler beim aktualisieren der Einstellungen. Stelle sicher, dass alle Felder ausgefüllt sind.',
	'editing_google_analytics_module' => 'Editiere Google Analytics Einstellungen',
	'tracking_code' => 'Tracking Code',
	'tracking_code_help' => 'Füge hier den Tracking Code für Google Analytics ein. Die script-Tags werden ebenfalls benötigt.',
	'google_analytics_help' => 'Siehe <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">hier</a> für mehr Informationen (Schritt 1-3).',
	'social_media_links' => 'Social Media Links',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Dunkles Twitter-Layout verwenden?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registration',
	'registration_warning' => 'Wenn dieses Option aktiviert ist, können sich keine neuen Nutzer registrieren.',
	'google_recaptcha' => 'Aktiviere Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Geschäftsbedingungen',
	'voice_server_module' => 'Voice-Server Modul',
	'only_works_with_teamspeak' => 'Dieses Modul funktioniert nur mit Teamspeak & Discord',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Bitte gib die ServerQuerry Daten ein.',
	'ip_without_port' => 'IP (ohne Port)',
	'voice_server_port' => 'Port (standart 10011)',
	'virtual_port' => 'Virtual Port (standart 9987)',
	'permissions' => 'Berechtigungen:',
	'view_applications' => 'Bewerbungen ansehen?',
	'accept_reject_applications' => 'Bewerbungen akzeptieren/ablehnen?',
	'questions' => 'Fragen:',
	'question' => 'Frage',
	'type' => 'Typ',
	'options' => 'Optionen',
	'options_help' => 'Jede Option in einer neuen Zeile; (Nur bei Dropdown)',
	'no_questions' => 'Bisher keine Fragen hinzugefügt.',
	'new_question' => 'Neue Frage',
	'editing_question' => 'Frage editieren',
	'delete_question' => 'Frage löschen',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'textarea' => 'Textfeld',
	'question_deleted' => 'Frage gelöscht',
	'name_required' => 'Name wird benötigt.',
	'question_required' => 'Frage wird benötigt.',
	'name_minimum' => 'Name muss mindestens aus 2 Zeichen bestehen.',
	'question_minimum' => 'Frage muss mindestens aus 2 Zeichen bestehen.',
	'name_maximum' => 'Name darf nur maximal aus 16 Zeichen bestehen.',
	'question_maximum' => 'Frage darf nur maximal aus 16 Zeichen bestehen.',
	'use_followers' => 'Follower System nutzen?',
	'use_followers_help' => 'Wenn dies nicht aktiv ist, wird das Follower System genutzt.',

	// Admin custom pages page
	'click_on_page_to_edit' => 'Klicke auf eine Seite, um sie zu bearbeiten.',
	'page' => 'Seite:',
	'url' => 'URL:',
	'page_url' => 'Seiten URL',
	'page_url_example' => '(Mit vorstehendem "/". Beispiel: /help/)',
	'page_title' => 'Seitentitel',
	'page_content' => 'Seiteninhalt',
	'new_page' => 'Neue Seite',
	'page_successfully_created' => 'Seite erfolgreich erstellt',
	'page_successfully_edited' => 'Seite erfolgreich bearbeitet',
	'unable_to_create_page' => 'Fehler bei der Erstellung der Seite.',
	'unable_to_edit_page' => 'Fehler bei der Bearbeitung der Seite.',
	'create_page_error' => 'Stelle sicher, dass die eingegebene URL zwischen 1 und 20 Zeichen, der Seitentitel zwischen 1 und 30 Zeichen und der Seiteninhalt zwischen 5 und 20480 Zeichen lang ist.',
	'delete_page' => 'Seite löschen',
	'confirm_delete_page' => 'Bist du sicher, dass du diese Seite löschen willst?',
	'page_deleted_successfully' => 'Seite wurde gelöscht',
	'page_link_location' => 'Zeige diese Seite:',
	'page_link_navbar' => 'Navigationsleiste',
	'page_link_more' => 'Navigationsleiste -> "Mehr" Menü',
	'page_link_footer' => 'Fußleiste',
	'page_link_none' => 'Kein Seitenlink',
	'page_permissions' => 'Seiten Berechtigungen',
	'can_view_page' => 'Kann Seite sehen:',
	'redirect_page' => 'Seite weiterleiten?',
	'redirect_link' => 'Weiterleitungslink',
	'page_icon' => 'Page Icon',

	// Admin forum page
	'labels' => 'Themen Label',
	'new_label' => 'Neues Label',
	'no_labels_defined' => 'Keine Labels definiert',
	'label_name' => 'Label Name',
	'label_type' => 'Label Typ',
	'label_forums' => 'Label Foren',
	'label_creation_error' => 'Fehler beim Erstellen des Labels. Bitte stelle sicher, dass der Name nicht länger als 32 Zeichen ist und ein Typ zugewiesen ist.',
	'confirm_label_deletion' => 'Bist du sicher, dass du dieses Label entfernen willst?',
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
	'forum_layout' => 'Forenlayout',
	'table_view' => 'Tabellenansicht',
	'latest_discussions_view' => '"Neuste Diskussion" Ansicht',
	'create_forum' => 'Forum erstellen',
	'forum_name' => 'Forenname',
	'forum_description' => 'Forenbeschreibung',
	'delete_forum' => 'Forum löschen',
	'move_topics_and_posts_to' => 'Verschiebe Themen und Beiträge nach',
	'delete_topics_and_posts' => 'Lösche Themen und Beiträge',
	'parent_forum' => 'Übergeordnetes Forum',
	'has_no_parent' => 'Hat kein übergeordnetes Forum',
	'forum_permissions' => 'Forenberechtigungen',
	'can_view_forum' => 'Dürfen Forum sehen',
	'can_create_topic' => 'Dürfen Themen erstellen',
	'can_post_reply' => 'Dürfen in Themen antworten',
	'display_threads_as_news' => 'Stelle Themen als News auf der Frontseite dar?',
	'input_forum_title' => 'Gib einen Foren-Titel an.',
	'input_forum_description' => 'Gib eine Foren-Beschreibung an.',
	'forum_name_minimum' => 'Der Foren-Name muss mindestens 2 Zeichen lang sein.',
	'forum_description_minimum' => 'Die Foren-Beschreibung muss mindestens 2 Zeichen lang sein.',
	'forum_name_maximum' => 'Der Foren-Name darf maximal 150 Zeichen lang sein.',
	'forum_description_maximum' => 'Die Foren-Beschreibung darf maximal 150 Zeichen lang sein.',
	'forum_type_forum' => 'Diskussions Forum',
	'forum_type_category' => 'Kategorie',

	// Admin Users and Groups page
	'users' => 'Benutzer',
	'new_user' => 'Neuer Benutzer',
	'created' => 'Erstellt',
	'user_deleted' => 'Benutzer gelöscht',
	'validate_user' => 'Benutzer bestätigen',
	'update_uuid' => 'Aktualisiere die UUID',
	'unable_to_update_uuid' => 'Unmöglich die UUID zu aktualisieren.',
	'update_mc_name' => 'Aktualisiere Minecraft Name',
	'reset_password' => 'Passwort zurücksetzen',
	'punish_user' => 'Benutzer bestrafen',
	'delete_user' => 'Benutzer löschen',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Adresse',
	'ip' => 'IP:',
	'other_actions' => 'Andere Aktionen:',
	'disable_avatar' => 'Avatar ausschalten',
	'enable_avatar' => 'Avatar aktivieren',
	'confirm_user_deletion' => 'Bist du sicher, dass du den Benutzer {x} löschen willst?', // Don't replace "{x}"
	'groups' => 'Gruppen',
	'group' => 'Gruppe',
	'group2' => 'Gruppe 2',
	'new_group' => 'Neue Gruppe',
	'id' => 'ID',
	'name' => 'Name',
	'create_group' => 'Gruppe erstellen',
	'group_name' => 'Gruppenname',
	'group_html' => 'Gruppen-HTML',
	'group_html_lg' => 'Gruppen HTML lang',
	'donor_group_id' => 'Spender Paket-ID',
	'donor_group_id_help' => '<p>Die Paket-ID ist die ID des Gruppenpacketes von Buycraft, MinecraftMarket oder MCStock.</p><p>Dieses Feld darf freigelassen werden.</p>',
	'donor_group_instructions' => '<p>Spendergruppen müssen in der Reihenfolge von dem <strong>kleinsten zum größten Wert</strong> erstellt werden.</p>
									<p>Beispiel: ein Paket für 10€ muss vor einem Paket das 20€ kostet erstellt werden.</p>',
	'delete_group' => 'Gruppe löschen',
	'confirm_group_deletion' => 'Bist du sicher, dass du die Gruppe {x} löschen willst?', // Don't replace "{x}"
	'group_staff' => 'Ist diese Gruppe eine Staff Gruppe?',
	'group_modcp' => 'Können Benutzer der Gruppe das Moderatoren Panel sehen?',
	'group_admincp' => 'Können Benutzer der Gruppe das Admin Panel sehen?',
	'group_name_required' => 'Du musst einen Gruppennamen angeben.',
	'group_name_minimum' => 'Der Name muss mindestens 2 Zeichen lang sein.',
	'group_name_maximum' => 'Der Name darf maximal 20 Zeichen lang sein.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'Der User muss in einer Gruppe sein.',
	'uuid_max_32' => 'Die UUID kann maximal 32 Zeichen lang sein.',
	'cant_delete_root_user' => 'Du kannst den Root-Benutzer nicht löschen!',
	'cant_modify_root_user' => 'Du kannst die Gruppe des Root-Benutzers nicht ändern.',

	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft Einstellungen',
	'use_plugin' => 'Verwende das Nameless Minecraft Plugin?',
	'force_avatars' => 'Erzwinge die Verwendung von Minecraft Avataren?',
	'uuid_linking' => 'Erlaube UUID Kopplung?',
	'use_plugin_help' => 'Das verwenden des Plugins erlaubt die Ränge-Synchronisierung, InGame-Registrierungen und Einreichung von Tickets.',
	'uuid_linking_help' => 'Wenn diese Funktion aktiviert ist, werden die Benutzeraccounts nicht mit den Minecraft UUIDs verknüpft. Es wird sehr empfohlen diese Funktion aktiviert zu lassen.',
	'plugin_settings' => 'Plugineinstellungen',
	'confirm_api_regen' => 'Bist du sicher, dass du einen neuen API-Schlüssel generieren willst?',
	'servers' => 'Server',
	'new_server' => 'Neuer Server',
	'confirm_server_deletion' => 'Bist du sicher, dass du diesen Server löschen willst?',
	'main_server' => 'Hauptserver',
	'main_server_help' => 'Der Server durch den die Spieler connecten. Normalerweise ist dies die BungeeCord Instanz.',
	'choose_a_main_server' => 'Wähle einen Hauptserver..',
	'external_query' => 'Verwende external query?',
	'external_query_help' => 'Verwendet eine externe API, um den Minecraft Server abzufragen. Verwende dies nur, wenn die eingebaute Abfrage nicht funktioniert. Es wird sehr empfohlen diese Funktion deaktiviert zu lassen.',
	'editing_server' => 'Bearbeite den Server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (mit Port)',
	'server_ip_with_port_help' => 'Dies ist die IP-Adresse, die den Nutzern angezeigt wird..',
	'server_ip_numeric' => 'Server IP (mit Port) (nur numerisch)',
	'server_ip_numeric_help' => 'Dies ist die IP-Adresse, die abgefragt wird. Sie wird den Nutzern nicht angezeigt',
	'show_on_play_page' => 'Sichtbar auf der Spieleseite?',
	'pre_17' => 'Vor 1.7 Minecraft Version?',
	'server_name' => 'Servername',
	'invalid_server_id' => 'Unbekannte Server ID',
	'show_players' => 'Zeige Spielerliste auf der Spieleseite?',
	'server_edited' => 'Server wurde erfolgreich bearbeitet',
	'server_created' => 'Server wurde erfolgreich erstellt',
	'query_errors' => 'Abfragefehler',
	'query_errors_info' => 'Die folgenden Fehlermeldungen lassen dich Fehlfunktionen an dem internen Serverabfrager diagnostizieren.',
	'no_query_errors' => 'Keine Abfrage aufgezeichnet',
	'date' => 'Datum:',
	'port' => 'Port:',
	'viewing_error' => 'Anzeigefehler',
	'confirm_error_deletion' => 'Bist du sicher, dass du diese Fehlermeldung löschen willst?',
	'display_server_status' => 'Zeige Serverstatus Modul?',
	'server_name_required' => 'Du musst einen Servernamen angeben.',
	'server_ip_required' => 'Du musst eine ServerIP angeben.',
	'server_name_minimum' => 'Der Servername muss mindestens 2 Zeichen lang sein.',
	'server_ip_minimum' => 'Die ServerIP muss mindestens 2 Zeichen lang sein.',
	'server_name_maximum' => 'Der Servername darf maximal 20 Zeichen lang sein.',
	'server_ip_maximum' => 'Die Server IP darf maximal 64 Zeichen lang sein.',
	'purge_errors' => 'Fehler Löschen',
	'confirm_purge_errors' => 'Bist du sicher, dass du alle Fehler löschen willst?',
	'avatar_type' => 'Avatar Typ',
	'custom_usernames' => 'Nur Minecraftnamen?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'mcassoc nutzen?',
	'use_mcassoc_help' => 'mcassoc sorgt dafür, dass Minecraftaccounts verifiziert werden können',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Falscher mcassoc key.',
	'mcassoc_instance' => 'mcassoc Instanz',
	'mcassoc_instance_help' => 'Generiere einen Instanzcode <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">hier</a>',
	'mcassoc_key_help' => 'Hole dir deinen Key <a href="https://mcassoc.lukegb.com/" target="_blank">hier</a>',
	'enable_name_history' => 'Aktiviere die Profilnamen Historie?',

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
	'addon_install_warning' => 'Die Installation von Addons ist auf eigene Gefahr. Bitte lege ein Backup von deinen Daten und der Datenbank an, bevor du fortfährst',
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
	'unable_to_enable_addon' => 'Konnte die Erweiterung nicht aktivieren. Bitte versichere dich das es sich um eine valide NamelessMC Erweiterung handelt.',
	
	// Admin Misc page
	'other_settings' => 'Andere Einstellungen',
	'enable_error_reporting' => 'Aktiviere Fehlerrückmeldungen?',
	'error_reporting_description' => 'Fehlerrückmeldungen sollten nur für debugging Zwecke verwendet werden. Es wird dringend empfohlen, diese Option deaktiviert zu lassen!',
	'display_page_load_time' => 'Zeige Seitenladezeit?',
	'page_load_time_description' => 'Wenn diese Aktion aktiviert ist, wird in der Fußzeile ein Speedometer angezeigt, welches die Ladezeit anzeigt.',
	'reset_website' => 'Website zurücksetzen',
	'reset_website_info' => 'Mit dieser Funktion wird die Website zurückgesetzt. <strong>Addons werden deaktiviert, aber nicht gelöscht, und Einstellungen, die am Addon vorgenommen wurden, sind immer noch present.</strong> Die definierten Minecraft Server werden auch bleiben.',
	'confirm_reset_website' => 'Bist du sicher, dass du die Website zurücksetzen willst?',

	// Admin Update page
	'installation_up_to_date' => 'Du nutzt die neueste Version.',
	'update_check_error' => 'Fehler beim abfragen der neuesten Version.',
	'new_update_available' => 'Ein neues Update ist verfügbar.',
	'your_version' => 'Deine Version:',
	'new_version' => 'Neue Version:',
	'download' => 'Download',
	'update_warning' => 'Warnung: Lade zuerst das Update hoch!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Start',
	'play' => 'Server',
	'forum' => 'Forum',
	'more' => 'Mehr',
	'staff_apps' => 'Bewerbungen',
	'view_messages' => 'Nachrichten anzeigen',
	'view_alerts' => 'Benachrichtigungen anzeigen',

	// Icons - will display before the text
	'home_icon' => '',
	'play_icon' => '',
	'forum_icon' => '',
	'staff_apps_icon' => ''
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
	'user_title' => 'Etikette',
	'email_address' => 'E-Mail Adresse',
	'date_of_birth' => 'Geburtsdatum',
	'location' => 'Standort',
	'password' => 'Passwort',
	'confirm_password' => 'Passwort bestätigen',
	'i_agree' => 'Ich stimme zu',
	'agree_t_and_c' => 'Mit dem Klicken auf <strong class="label label-primary">Registrieren</strong>, stimmst du unseren <a href="#" data-toggle="modal" data-target="#t_and_c_m">Bedingungen und Konditionen</a> zu.',
	'register' => 'Registrieren',
	'sign_in' => 'Anmelden',
	'sign_out' => 'Abmelden',
	'terms_and_conditions' => 'Allgemeine Geschäftsbedingungen',
	'successful_signin' => 'Du wurdest erfolgreich angemeldet',
	'incorrect_details' => 'Falscher Benutzername/Falsches Passwort',
	'remember_me' => 'Angemeldet bleiben',
	'forgot_password' => 'Passwort vergessen',
	'must_input_username' => 'Du musst einen Benutzername angeben.',
	'must_input_password' => 'Du musst ein Passwort angeben.',
	'inactive_account' => 'Dein Account ist im Moment deaktiviert. Hast du dein Passwort zurücksetzen lassen?',
	'account_banned' => 'Dein Account wurde gebannt.',
	'successfully_logged_out' => 'Du wurdest erfolgreich ausgeloggt.',
	'signature' => 'Signatur',
	'registration_check_email' => 'Bitte überprüfe deine E-Mails auf einen Bestätigungslink. Du wirst dich nicht anmelden können, bis dieser geklickt wurde.',
	'unknown_login_error' => 'Entschuldigung, während du dich einloggen wollstest ist ein unbekannter Fehler aufgetreten. Bitte versuche es später erneut.',
	'validation_complete' => 'Danke für\'s registrieren! Du kannst dich nun anmelden',
	'validation_error' => 'Fehler beim bearbeiten deiner Anfrage. Versuche den Link später noch einmal zu klicken.',
	'registration_error' => 'Bitte stelle sicher, dass du alle Felder ausgefüllt hast, wobei der Benutzername zwischen 3 und 20 Zeichen und das Passwort zwischen 6 und 30 Zeichen lang sein muss.', // todo
	'username_required' => 'Bitte gebe einen Nutzernamen an.',
	'password_required' => 'Bitte gebe ein Passwort an.',
	'email_required' => 'Bitte gebe eine gültige E-Mail-Adresse an.',
	'mcname_required' => 'Bitte gebe einen Minecraft Namen an.',
	'accept_terms' => 'Du musst unsere AGBs akzeptieren, um dich zu registrieren.',
	'invalid_recaptcha' => 'Ungültige reCAPTCHA Antwort.',
	'username_minimum_3' => 'Dein Nutzername muss mindestens 3 Zeichen lang sein.',
	'username_maximum_20' => 'Dein Nutzername darf nicht länger als 20 Zeichen sein.',
	'mcname_minimum_3' => 'Dein Minecraftname muss mindestens 3 Zeichen lang sein.',
	'mcname_maximum_20' => 'Dein Minecraftname darf nicht länger als 20 Zeichen sein..',
	'password_minimum_6' => 'Dein Passwort muss mindestens 6 Zeichen haben..',
	'password_maximum_30' => 'Dein Passwort darf nicht länger als 30 Zeichen sein.',
	'passwords_dont_match' => 'Deine Passwörter stimmen nicht überein.',
	'username_mcname_email_exists' => 'Dein Nutzername, Minecraftname oder deine E-Mail-Adresse sind bereits registriert. Hast du schon ein Konto eingerichtet?',
	'invalid_mcname' => 'Dein Minecraftname ist ungültig.',
	'mcname_lookup_error' => 'Es gab einen Fehler bei der Anfrage zu Mojang\'s Servern. Bitte versuche es später noch einmal.',
	'signature_maximum_900' => 'Deine Signatur darf maximal 900 Zeichen enthalten.',
	'invalid_date_of_birth' => 'Dein Geburtsdatum ist ungültig.',
	'location_required' => 'Gib bitte deinen Standort an.',
	'location_minimum_2' => 'Dein Standort muss mindestens 2 Zeichen enthalten.',
	'location_maximum_128' => 'Dein Standort darf maximal 128 Zeichen enthalten.',
	'verify_account' => 'Account verifizieren',
	'verify_account_help' => 'Bitte führe die angegeben Schritte durch um deinen Account zu verifizieren.',
	'verification_failed' => 'Verifikation fehlgeschlagen.',
	'verification_success' => 'Erfolgreich freigeschaltet.',
	'complete_signup' => 'Complete Signup',
	'registration_disabled' => 'Webseite Registrierung ist aktuell deaktiviert.',

	// UserCP
	'user_cp' => 'Benutzer Panel',
	'no_file_chosen' => 'Keine Datei ausgewählt',
	'private_messages' => 'Private Nachrichten',
	'profile_settings' => 'Profileinstellungen',
	'your_profile' => 'Dein Profil',
	'topics' => 'Themen',
	'posts' => 'Beiträge',
	'reputation' => 'Ansehen',
	'friends' => 'Freunde',
	'alerts' => 'Benachrichtigungen',

	// Messaging
	'new_message' => 'Neue Nachricht',
	'no_messages' => 'Keine Nachrichten',
	'and_x_more' => 'und {x} mehr', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Nachrichtentitel',
	'message' => 'Nachricht',
	'to' => 'An:',
	'separate_users_with_comma' => 'Trenne verschiedene Namen mit Komma (",")',
	'viewing_message' => 'Lese Nachricht',
	'delete_message' => 'Lösche Nachricht',
	'confirm_message_deletion' => 'Bist du sicher, dass du diese Nachricht löschen willst?',

	// Profile settings
	'display_name' => 'Anzeigename',
	'upload_an_avatar' => 'Lade einen Avatar hoch (.jpg, .png oder .gif):',
	'use_gravatar' => 'Nutze Gravatar?',
	'change_password' => 'Ändere Passwort',
	'current_password' => 'Aktuelles Passwort',
	'new_password' => 'Neues Passwort',
	'repeat_new_password' => 'Wiederhole neues Passwort',
	'password_changed_successfully' => 'Passwort wurde erfolgreich geändert',
	'incorrect_password' => 'Dein aktuelles Passwort ist ungültig',
	'update_minecraft_name_help' => 'Dies wird deinen Namen auf deinen Aktuellen Minecraftnamen ändern. Diese Aktion lässt dich nur alle 30 Tage ausführen.',
	'unable_to_update_mcname' => 'Fehler beim updaten den MC-Namen.',
	'display_age_on_profile' => 'Zeige das Alter im Profil?',
	'two_factor_authentication' => 'Zwei-Faktor-Authentifizierung',
	'enable_tfa' => 'Aktiviere die Zwei-Faktor-Authentifizierung',
	'tfa_type' => 'Zwei-Faktor-Authentifizierungs Typ:',
	'authenticator_app' => 'App',
	'tfa_scan_code' => 'Bitte Scanne folgenden Code:',
	'tfa_code' => 'Wenn dein Gerät keine Kamera besitzt, gib folgenden Code ein:',
	'tfa_enter_code' => 'Gib bitte den Code ein, welcher dir angezeigt wird:',
	'invalid_tfa' => 'Ungültiger Code, versuche es erneut.',
	'tfa_successful' => 'Zwei-Faktor-Authentifizierung erfolgreich eingerichtet.',
	'confirm_tfa_disable' => 'Willst du die Zwei-Faktor-Authentifizierung deaktivieren?',
	'tfa_disabled' => 'Du hast die Zwei-Faktor-Authentifizierung deaktiviert.',
	'tfa_enter_email_code' => 'Gib den Code ein, der dir per E-Mail zugeschickt wurde',
	'tfa_email_contents' => 'Jemand hat sich versucht mit deinem Konto anzumelden. Wenn du das warst, gib folgenden Code ein:',

	// Alerts
	'viewing_unread_alerts' => 'Zeigt ungelesene Nachrichten. Wechsele zu <a href="/user/alerts/?view=read"><span class="label label-success">gelesen</span></a>.',
	'viewing_read_alerts' => 'Zeigt gelesene Nachrichten. Wechsele zu<a href="/user/alerts/"><span class="label label-warning">ungelesenen</span></a>.',
	'no_unread_alerts' => 'Du hast keine ungelesenen Benachrichtigungen.',
	'no_alerts' => 'Keine Benachrichtigungen',
	'no_read_alerts' => 'Du hast keine gelesenen Benachrichtigungen.',
	'view' => 'Anzeigen',
	'alert' => 'Benachrichtigungen',
	'when' => 'Wann',
	'delete' => 'Löschen',
	'tag' => 'Benutzer Tag',
	'tagged_in_post' => 'Du wurdest in einem Post erwähnt',
	'report' => 'Melden',
	'deleted_alert' => 'Benachrichtigung erfolgreich gelöscht',

	// Warnings
	'you_have_received_a_warning' => 'Du hast eine Warnung von {x} am {y} erhalten.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Zur Kenntnis genommen',

	// Forgot password
	'password_reset' => 'Passwort zurücksetzen',
	'email_body' => 'Du bekommst diese E-Mail, da du dein Passwort zurücksetzen lassen möchtest. Um dein Passwort zurückzusetzen, klicke bitte auf den Link unten:', // Body for the password reset email
	'email_body_2' => 'Wenn du dein Passwort nicht zurücksetzen willst, dann kannst du diese E-Mail einfach ignorieren.',
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
	'user_hasnt_registered' => 'Dieser Benutzer ist noch nicht auf unserer Website registriert',
	'user_no_friends' => 'Dieser Benutzer hat noch keine Freunde hinzugefügt',
	'send_message' => 'Nachricht senden',
	'remove_friend' => 'Freund entfernen',
	'add_friend' => 'Freund hinzufügen',
	'last_online' => 'Zuletzt Online:',
	'find_a_user' => 'Finde eine Nutzer',
	'user_not_following' => 'Dieser Benutzer folgt niemandem.',
	'user_no_followers' => 'Dieser Benutzer hat keine Follower.',
	'following' => 'Folgt',
	'followers' => 'Follower',
	'display_location' => 'Aus {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, aus {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Schreibe etwas unter {x}\'s Profil...', // Don't replace {x}
	'write_on_own_profile' => 'Schreibe etwas unter dein Profil...',
	'profile_posts' => 'Profil Nachrichten',
	'no_profile_posts' => 'Es existieren keine Profilnachrichten.',
	'invalid_wall_post' => 'Die Nachricht muss zwichen 2 und 2048 Zeichen lang sein.',
	'about' => 'Über',
	'reply' => 'Antworten',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'Keine likes.',
	'post_liked' => 'Post geliked.',
	'post_unliked' => 'Post gedisliked.',
	'no_posts' => 'Keine Posts.',
	'last_5_posts' => 'Letzten 5 Posts',
	'follow' => 'Folgen',
	'unfollow' => 'Nicht mehr Folgen',
	'name_history' => 'Name History',
	'changed_name_to' => 'Hat am {y} seinen Namen zu {x} geändert.', // Don't replace {x} or {y}
	'original_name' => 'Voriger Name:',
	'name_history_error' => 'Fehler beim abrufen der Name-History.',

	// Staff applications
	'staff_application' => 'Team Bewerbung',
	'application_submitted' => 'Die Bewerbung wurde erfolgreich abgeschickt.',
	'application_already_submitted' => 'Du hast bereits eine Bewerbung eingesendet. Bitte warte bis diese bearbeitet wurde, bevor du eine neue Bewerbung einsendest.',
	'not_logged_in' => 'Bitte melde dich an, um Zugriff auf diese Seite zu bekommen.',
	'application_accepted' => 'Deine Bewerbung wurde akzeptiert.',
	'application_rejected' => 'Deine Bewerbung wurd abgelehnt.'
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
	'cant_ban_root_user' => 'Der Administrator darf nicht verwarnt oder gebannt werden.',
	'invalid_reason' => 'Gib bitte einen gültigen Grund an.',
	'punished_successfully' => 'Der Nutzer wurde erfolgreich bestraft.',

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
	'ingame_report' => 'Ingame Report',

	// Staff applications
	'comment_error' => 'Bitte stelle sicher, dass dein Kommentar zwischen 2 und 2048 Zeichen lang ist.',
	'viewing_open_applications' => 'Zeigt <span class="label label-info">offene</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/?view=accepted"><span class="label label-success">angenommene</span></a> oder <a href="/mod/applications/?view=declined"><span class="label label-danger">abgelehnte</span></a> Bewerbungen.',
	'viewing_accepted_applications' => 'Zeigt <span class="label label-success">angenommene</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/"><span class="label label-info">offene</span></a> oder <a href="/mod/applications/?view=declined"><span class="label label-danger">abgelehnte</span></a>.',
	'viewing_declined_applications' => 'Zeigt <span class="label label-danger">abgelehnte</span> Bewerbungen. Zeige stattdessen <a href="/mod/applications/"><span class="label label-info">offene</span></a> oder <a href="/mod/applications/?view=accepted"><span class="label label-success">angenommene</span></a>.',
	'time_applied' => 'Uhrzeit der Bewerbung',
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
	'social' => 'Soziale Netzwerke',
	'join' => 'Beitreten',

	// General terms
	'submit' => 'Absenden',
	'close' => 'Schließen',
	'cookie_message' => '<strong>Diese Website verwendet Cookies um deine Erfahrung zu verbessern.</strong><p>Mit dem weiteren Verwenden dieser Website stimmst du der Verwendung zu.</p>',
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
	'info' => 'Info',
	'next' => 'Nächste',

	// Play page
	'connect_with' => 'Verbinde zu dem Server mit der IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Spieler Online:',
	'queried_in' => 'Abgefragt in:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Es sind aktuell keine Spieler online!',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'Es sind {x} Spieler online.', // Don't replace {x}

	// Other
	'page_loaded_in' => 'Seite wurde in {x} Sekunden geladen.', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Nichts',
	'404' => 'Wir konnten diese Seite nicht finden.'
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
	'ago' => 'her',
	'by' => 'von',
	'in' => 'in',
	'views' => 'Aufrufe',
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
	'home' => 'Start',
	'topic_locked' => 'Thema geschossen',
	'new_reply' => 'neue Antwort',
	'mod_actions' => 'Moderationsaktionen',
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
	'no_reputation' => 'Dieser Beitrag wurde noch nicht bewertet',
	're' => 'RE:',

	// Create post view
	'create_post' => 'Beitrag erstellen',
	'post_submitted' => 'Beitrag eingereicht',
	'creating_post_in' => 'Beitrag wird erstellt in: ',
	'topic_locked_permission_post' => 'Dieses Thema ist geschlossen, deine Berechtigungen erlauben es dir aber hier zu posten',

	// Edit post view
	'editing_post' => 'Beitrag bearbeiten',

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
	'merge_instructions' => 'Die Themen, die Zusammengeführt werden sollen <strong>müssen</strong> sich im selben Forum befinden. Verschiebe das Thema falls nötig.',
	'merge_with' => 'Zusammenführen mit:',

	// Other
	'forum_error' => 'Entschuldigung, es wurde kein Forum oder Beitrag gefunden.',
	'are_you_logged_in' => 'Bist du eingeloggt?',
	'online_users' => 'Benutzer online',
	'no_users_online' => 'Es sind keine Benutzer online.',

	// Search
	'search_error' => 'Die Suchanfrage muss zwischen 1 und 32 Zeichen lang sein.',
	'no_search_results' => 'No search results have been found.',

	//Share on a social-media.
	'sm-share' => 'Aktie',
	'sm-share-facebook' => 'Auf Facebook teilen',
	'sm-share-twitter' => 'Auf Twitter teilen',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hallo,',
	'message' => 'Danke für deine Registrierung! Um deine Registration abzuschließen, klicke bitte auf den folgenden Link:',
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
	'page_x_of_y' => 'Zeige Seite _PAGE_ von _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Keine Einträge',
	'filtered' => '(gefiltert aus _MAX_ Einträgen)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Registration abschließen.'
);

?>
