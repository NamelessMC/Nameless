<?php 
/*
 *  Made by Sander Jochems
 *  http://sanderjochems.r4u.nl
 */
 
/*
 *  Dutch Language
 */
  
/*
 *  Admin Panel
 */
$admin_language = array(
    // General terms
    'admin_cp' => 'AdminCP', 
    'invalid_token' => 'Ongeldige token, probeer het opnieuw',
    'invalid_action' => 'Ongeldige Actie',
    'successfully_updated' => 'Succesvol geupdate',
    'settings' => 'Instellingen',
    'confirm_action' => 'Bevestig Actie',
    'edit' => 'Bewerk',
    'actions' => 'Acties',
    'task_successful' => 'Opdracht succesvol',
     
    // Admin login
    're-authenticate' => 'Log opnieuw in',
     
    // Admin sidebar
    'index' => 'Overview',
    'core' => 'Core',
    'custom_pages' => 'Aangepatse pagina\'s',
    'general' => 'Algemeen',
    'forums' => 'Forums',
    'users_and_groups' => 'Gebruikers en Groepen',
    'minecraft' => 'Minecraft',
    'style' => 'Stijl',
    'addons' => 'Addons',
    'update' => 'Update',
    'misc' => 'Misc',
     
    // Admin index page
    'statistics' => 'Statistieken',
    'registrations_per_day' => 'Registraties per dag (7 dagen geleden)',
     
    // Admin core page
    'general_settings' => 'Algemene Instellingen',
    'modules' => 'Modules',
    'module_not_exist' => 'Die module bestaat niet',
    'module_enabled' => 'Module ingeschakeld.',
    'module_disabled' => 'Module uitgeschakeld.',
    'site_name' => 'Site Naam',
    'language' => 'Taal',
    'voice_server_not_writable' => 'core/voice_server.php is niet beschrijfbaar. Controleer de bestandsrechten.',
    'email' => 'Email',
    'incoming_email' => 'Inkomend email adres',
    'outgoing_email' => 'Uitgaand email adres',
    'outgoing_email_help' => 'Alleen nodig als de PHP mail functie is ingeschakeld',
    'use_php_mail' => 'Gebruik PHP mail() functie?',
    'use_php_mail_help' => 'Aanbevolen: ingeschakeld. Als je website geen mails stuurt, dan kunt u de core/email.php uitschakelen in de email instellingen.',
    'use_gmail' => 'Gebruik Gmail voor het verzenden van e-mail?',
    'use_gmail_help' => 'Alleen beschikbaar als de PHP mail() functie is uitgeschakeld. Als u ervoor kiest om Gmail te gebruiken, zal SMTP worden gebruikt, dit zal de configuratie in core/email.php nodig hebben.',
    'enable_mail_verification' => 'Schakel e-mail account verificatie in?',
    'enable_email_verification_help' => 'Nadat deze geactiveerd is zullen nieuwe geregistreerde gebruikers gevraagt worden om hun account via de e-mail te verifiëren voordat de registratie voltooid wordt.',
    'pages' => 'Pagina\'s',
    'enable_or_disable_pages' => 'Schakel pagina\'s hier in en uit .',
    'enable' => 'Ingeschakeld',
    'disable' => 'Uitgeschakeld',
    'maintenance_mode' => 'Forum onderhoud modus',
    'forum_in_maintenance' => 'Forum is in onderhoud mode.',
	'unable_to_update_settings' => 'Unable to update settings. Please ensure no fields are left empty.',
	'editing_google_analytics_module' => 'Editing Google Analytics module',
	'tracking_code' => 'Tracking Code',
	'tracking_code_help' => 'Insert the tracking code for Google Analytics here, including the surrounding script tags.',
	'google_analytics_help' => 'See <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">this guide</a> for more information, following steps 1 to 3.',
	'social_media_links' => 'Social Media Links',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registration',
	'registration_warning' => 'Having this module disabled will also disable new members registering on your site.',
	'google_recaptcha' => 'Enable Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Registration Terms and Conditions',
	'voice_server_module' => 'Voice Server Module',
	'only_works_with_teamspeak' => 'This module currently only works with TeamSpeak',
	'voice_server_help' => 'Please enter the details for the ServerQuery user',
	'ip_without_port' => 'IP (without port)',
	'voice_server_port' => 'Port (usually 10011)',
	'virtual_port' => 'Virtual Port (usually 9987)',
	'permissions' => 'Permissions:',
	'view_applications' => 'View Applications?',
	'accept_reject_applications' => 'Accept/Reject Applications?',
	'questions' => 'Questions:',
	'question' => 'Question',
	'type' => 'Type',
	'options' => 'Options',
	'options_help' => 'Each option on a new line; can be left empty (dropdowns only)',
	'no_questions' => 'No questions added yet.',
	'new_question' => 'New Question',
	'editing_question' => 'Editing Question',
	'delete_question' => 'Delete Question',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'textarea' => 'Text Area',
	'question_deleted' => 'Question Deleted',
	'use_followers' => 'Use followers?',
	'use_followers_help' => 'If disabled, the friends system will be used.',
     
    // Admin custom pages page
    'click_on_page_to_edit' => 'Klik op een pagina om deze te bewerken.',
    'page' => 'Pagina:',
    'url' => 'URL:',
    'page_url' => 'Pagina URL',
    'page_url_example' => '(Met voorafgaande "/", bijvoorbeeld /help/)',
    'page_title' => 'Pagina Titel',
    'page_content' => 'Pagina Inhoud',
    'new_page' => 'Nieuwe Pagina',
    'page_successfully_created' => 'Pagina succesvol gemaakt',
    'page_successfully_edited' => 'Pagina succesvol bewerkt',
    'unable_to_create_page' => 'Kan de pagina niet maken.',
    'unable_to_edit_page' => 'Kan de pagina niet bewerken.',
    'create_page_error' => 'Zorg ervoor dat u URL tussen 1 en 20 tekens lang is, de pagina titel tussen 1 en 30 tekens lang is en de inhoud van de pagina tussen 5 en 20.480 tekens lang is.',
    'delete_page' => 'Verwijder Pagina',
    'confirm_delete_page' => 'Weet u zeker dat u deze pagina wilt verwijderen?',
    'page_deleted_successfully' => 'Pagina succesvol verwijderd',
    'page_link_location' => 'Toon pagina link in:',
    'page_link_navbar' => 'Navbar',
    'page_link_more' => 'Navbar "More" dropdown',
    'page_link_footer' => 'Pagina Onderkant',
    'page_link_none' => 'Geen pagina link',
     
    // Admin forum page
    'labels' => 'Topic Labels',
    'new_label' => 'Nieuw Label',
    'no_labels_defined' => 'Geen labels gedefinieerd',
    'label_name' => 'Label Naam',
    'label_type' => 'Label Type',
    'label_forums' => 'Label Forums',
    'label_creation_error' => 'Fout bij het maken van een label. Zorg ervoor dat de naam niet langer is dan 32 tekens en dat u een soort heeft opgegeven.',
    'confirm_label_deletion' => 'Weet u zeker dat u dit label verwijderen?',
    'editing_label' => 'Label bewerken',
    'label_creation_success' => 'Label succesvol gemaakt',
    'label_edit_success' => 'Label succesvol bewerkt',
    'label_default' => 'Default',
    'label_primary' => 'Primary',
    'label_success' => 'Success',
    'label_info' => 'Info',
    'label_warning' => 'Warning',
    'label_danger' => 'Danger',
    'new_forum' => 'Nieuw Forum',
    'forum_layout' => 'Forum Layout',
    'table_view' => 'Table view',
    'latest_discussions_view' => 'Laatste Discussions view',
    'create_forum' => 'Maak Forum',
    'forum_name' => 'Forum Naam',
    'forum_description' => 'Forum Beschrijving',
    'delete_forum' => 'Verwijder Forum',
    'move_topics_and_posts_to' => 'Verplaats topics en posts naar',
    'delete_topics_and_posts' => 'Verwijder topics en posts',
    'parent_forum' => 'Ouderlijk Forum',
    'has_no_parent' => 'Heeft geen ouderlijk Forum',
    'forum_permissions' => 'Forum Rechten',
    'can_view_forum' => 'Kan forum bekijken:',
    'can_create_topic' => 'Kan een topic maken:',
    'can_post_reply' => 'Kan een antwoord plaatsen:',
    'display_threads_as_news' => 'Vertoon threads als nieuws op de voorpagina?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description.',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
     
    // Admin Users and Groups page
    'users' => 'Gebruikers',
    'new_user' => 'Nieuwe Gebruiker',
    'created' => 'Gemaakt',
    'user_deleted' => 'Gebruiker Verwijderd',
    'validate_user' => 'Bevestigde Gebruiker',
    'update_uuid' => 'Update UUID',
    'unable_to_update_uuid' => 'Kan de UUID niet bewerken.',
    'update_mc_name' => 'Update Minecraft Naam',
    'reset_password' => 'Reset Wachtwoord',
    'punish_user' => 'Straf Gebruiker',
    'delete_user' => 'Verwijder Gebruiker',
    'minecraft_uuid' => 'Minecraft UUID',
    'ip_address' => 'IP Adres',
    'ip' => 'IP:',
    'other_actions' => 'Andere Actie\'s:',
    'disable_avatar' => 'Schakel avatar uit',
    'confirm_user_deletion' => 'Weet u zeker dat u de gebruiker <b>{x}</b> wilt verwijderen?', // Don't replace "{x}"
    'groups' => 'Groepen',
    'group' => 'Groep',
    'new_group' => 'Nieuwe Groep',
    'id' => 'ID',
    'name' => 'Naam',
    'create_group' => 'Maak Groep',
    'group_name' => 'Groep Naam',
    'group_html' => 'Groep HTML',
    'group_html_lg' => 'Groep HTML Groote',
    'donor_group_id' => 'Donor Pakket ID',
    'donor_group_id_help' => '<p>Dit is de ID van het pakket van de groep van Buycraft, Minecraft Market of MCStock.</p><p>Dit kan leeg gelaten worden.</p>',
    'donor_group_instructions' =>    '<p>Donor groepen moeten worden gecreëerd in de volgorde van de <strong>laagste naar hoogste prijs</ strong>.</p>
                                    <p>Zo zal een &euro 10 pakket worden gemaakt boven een &euro 20 pakket.</p>',
    'delete_group' => 'Verwijder Groep',
    'confirm_group_deletion' => 'Weet u zeker dat u de groep <b>{x}<b> verwijderen?', // Don't replace "{x}"
    'group_staff' => 'Is de groep een staff groep?',
    'group_modcp' => 'Kan de groep de ModCP bekijken?',
    'group_admincp' => 'Kan de groep de AdminCP bekijken?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'The group name must be a minimum of 2 characters.',
	'group_name_maximum' => 'The group name must be a maximum of 20 characters.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'The user must be in a group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
     
    // Admin Minecraft page
    'minecraft_settings' => 'Minecraft Instellingen',
    'use_plugin' => 'Gebruik Nameless Minecraft plugin?',
    'force_avatars' => 'Force Minecraft avatars?',
    'uuid_linking' => 'Schakel UUID koppelen in?',
    'use_plugin_help' => 'Gebruik de plugin voor rank synchronisatie en ook in het spel registreren en ticket indienen.',
    'uuid_linking_help' => 'Indien uitgeschakeld, zullen gebruikersaccounts niet worden gekoppeld aan UUID. Het wordt aanbevolen dat u dit als ingeschakeld laat.',
    'plugin_settings' => 'Plugin Instellingen',
    'confirm_api_regen' => 'Weet u zeker dat u een nieuwe API Key wilt genereren?',
    'servers' => 'Servers',
    'new_server' => 'Nieuwe Server',
    'confirm_server_deletion' => 'Weet u zeker dat u deze server wilt verwijderen?',
    'main_server' => 'Belangrijkste Server',
    'main_server_help' => 'Hierheen verbinden de spelers van de server. Normaal gesproken zal dit de BungeeCord zijn.',
    'choose_a_main_server' => 'Kies de belangrijkste server.',
    'external_query' => 'Gebruik externe query?',
    'external_query_help' => 'Gebruik een externe API om de Minecraft-server te Queryen? Gebruik dit alleen als de ingebouwde in de zoekopdracht niet werkt; Het aanbovelen dat dit aangevinkt is.',
    'editing_server' => 'Server {x} aan het bewerken', // Don't replace "{x}"
    'server_ip_with_port' => 'Server IP (met port) (numerieke of domein)',
    'server_ip_with_port_help' => 'Dit is het IP dat zal worden getoond aan gebruikers. Het zal niet worden gequeryd.',
    'server_ip_numeric' => 'Server IP (met port) (alleen numerieke)',
    'server_ip_numeric_help' => 'Dit is het IP dat zal worden gequeryd, zorg ervoor dat het alleen numeriek is. Het zal niet worden getoond aan gebruikers.',
    'show_on_play_page' => 'Laat op de Play pagina zien?',
    'pre_17' => 'Pre 1.7 Minecraft versie?',
    'server_name' => 'Server Naam',
    'invalid_server_id' => 'Ongeldige server ID',
    'show_players' => 'Laat de spelers op de Play pagina zien.',
    'server_edited' => 'Server succesvol bewerkt',
    'server_created' => 'Server succesvol gemaakt',
    'query_errors' => 'Query Errors',
    'query_errors_info' => 'De volgende fouten kunt u problemen diagnosticeren met uw interne server query.',
    'no_query_errors' => 'Geen query fouten gevonden',
    'date' => 'Datum:',
    'port' => 'Port:',
    'viewing_error' => 'Bekijk Error',
    'confirm_error_deletion' => 'Bent u zeker dat u deze error wilt verwijderen?',
    'display_server_status' => 'Laat Server Status module zien',
	'server_name_required' => 'You must insert a server name.',
	'server_ip_required' => 'You must insert the server\'s IP.',
	'server_name_minimum' => 'The server name must be a minimum of 2 characters.',
	'server_ip_minimum' => 'The server IP must be a minimum of 2 characters.',
	'server_name_maximum' => 'The server name must be a maximum of 20 characters.',
	'server_ip_maximum' => 'The server IP must be a maximum of 64 characters.',
     
    // Admin Themes, Templates and Addons
    'themes' => 'Thema\'s',
    'templates' => 'Templates',
    'installed_themes' => 'Geinstaleerde Thema\'s',
    'installed_templates' => 'Geinstaleerde templates',
    'installed_addons' => 'Geinstaleerde addons',
    'install_theme' => 'Installeer Thema',
    'install_template' => 'Installeer Template',
    'install_addon' => 'Installeer Addon',
    'install_a_theme' => 'Installeer een thema',
    'install_a_template' => 'Installeer een template',
    'install_an_addon' => 'Installeer een addon',
    'active' => 'Actief',
    'activate' => 'Activeer',
    'deactivate' => 'Deactiveer',
    'theme_install_instructions' => 'Upload thema\'s naar de <b>styles/themes</b> map, Klik daarna op de "Scan" knop.',
    'template_install_instructions' => 'Upload templates naar de <b>styles/templates</b> map, Klik daarna op de "Scan" knop.',
    'addon_install_instructions' => 'Upload addons naar de <b>addons</b> map, Klik daarna op de "Scan" knop.',
    'addon_install_warning' => 'Addons worden geïnstalleerd op uw eigen risico. Maak een back-up van uw bestanden en de database voordat u verder gaat',
    'scan' => 'Scan',
    'theme_not_exist' => 'Dat thema bestaat niet',
    'template_not_exist' => 'Dat template bestaat niet',
    'addon_not_exist' => 'Die addon bestaat niet',
    'style_scan_complete' => 'Klaar, alle nieuwe stijlen zijn geïnstalleerd.',
    'addon_scan_complete' => 'Klaar, alle nieuwe addons zijn geïnstalleerd.',
    'theme_enabled' => 'Thema Ingeschakeld.',
    'template_enabled' => 'Template Uitgeschakeld.',
    'addon_enabled' => 'Addon Ingeschakeld.',
    'theme_deleted' => 'Thema Verwijderd.',
    'template_deleted' => 'Template Verwijderd.',
    'addon_disabled' => 'Addon Uitgeschakeld.',
    'inverse_navbar' => 'Inverse Navbar',
    'confirm_theme_deletion' => 'Weet u zeker dat u het thema <b>{x}</b> wilt verwijderen?<br /><br />Het thema wordt verwijderd uit uw <b>styles/themes</b> map.', // Don't replace {x}
    'confirm_template_deletion' => 'Weet u zeker dat u het thema <b>{x}</b> wilt verwijderen?<br /><br />Het template wordt verwijderd uit uw <b>styles/templates</b> map.', // Don't replace {x}
     
    // Admin Misc page
    'other_settings' => 'Andere Instellingen',
    'enable_error_reporting' => 'Inschakelen error rapportage?',
    'error_reporting_description' => 'Dit mag alleen worden gebruikt voor het opsporen van fouten, het is aanbevolen dit als uitgeschakeld te laten staan.',
    'display_page_load_time' => 'Toon pagina laadtijd?',
    'page_load_time_description' => 'Nadat deze is ingeschakeld zal een snelheidsmeter weergegeven in de voettekst die de laadtijd van de pagina weergeeft.',
    'reset_website' => 'Reset Website',
    'reset_website_info' => 'Dit zal uw website resetten. <b>Addons worden uitgeschakeld, maar niet verwijderd, en de instellingen zullen niet veranderen.</b> Uw ingestelde Minecraft servers zullen ook blijven.',
    'confirm_reset_website' => 'Weet u zeker dat u uw website wilt resetten?',
	
	// Admin Update page
	'installation_up_to_date' => 'Your installation is up to date.',
	'update_check_error' => 'Unable to check for updates. Please try again later.',
	'new_update_available' => 'A new update is available.',
	'your_version' => 'Your version:',
	'new_version' => 'New version:',
	'download' => 'Download',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
);
 
/*
 *  Navbar
 */
$navbar_language = array(
    'home' => 'Home',
    'play' => 'Play',
    'forum' => 'Forum',
    'more' => 'Meer',
    'staff_apps' => 'Staff Applications',
    'view_messages' => 'Bekijk Berichten',
    'view_alerts' => 'Bekijk Alerts',
	
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
    'create_an_account' => 'Maak een Account',
    'authme_password' => 'AuthMe Wachtwoord',
    'username' => 'Username',
    'minecraft_username' => 'Minecraft Username',
    'email' => 'Email',
    'email_address' => 'Email Adres',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
    'password' => 'Wachtwoord',
    'confirm_password' => 'Bevestig Wachtwoord',
    'i_agree' => 'Ik Accepteer',
    'agree_t_and_c' => 'Als u op <strong class="label label-primary">Registreer</strong> klikt, dan bent u het eens met de <a href="#" data-toggle="modal" data-target="#t_and_c_m">Algemene Voorwaarden</a>.',
    'register' => 'Registreer',
    'sign_in' => 'Inloggen',
    'sign_out' => 'Uitloggen',
    'terms_and_conditions' => 'Algemene Voorwaarden',
    'successful_signin' => 'U bent succesvol ingelogd',
    'incorrect_details' => 'Gegevens Onjuist',
    'remember_me' => 'Onthoud mij',
    'forgot_password' => 'Wachtwoord Vergeten',
    'must_input_username' => 'U moet een username invullen.',
    'must_input_password' => 'U moet een wachtwoord invullen.',
    'inactive_account' => 'Uw account is momenteel inactief. Heeft u uw wachtwoord opnieuw aangevraagd?',
    'account_banned' => 'Uw account is geband.',
    'successfully_logged_out' => 'U bent succesvol uitgelogd.',
    'signature' => 'Ondertekening',
    'registration_check_email' => 'Controleer uw e-mails voor een validatie link. U zult niet in staat om in te loggen, totdat er deze wordt geklikt.',
    'unknown_login_error' => 'Sorry, er was een onbekende fout tijdens het inloggen. Probeer het later opnieuw.',
    'validation_complete' => 'Bedankt voor het registreren! U kunt nu inloggen.',
    'validation_error' => 'Fout bij het verwerken van uw aanvraag. Probeer het opnieuw door op de link te klikken.',
    'registration_error' => 'Zorg ervoor dat u alle velden heeft ingevuld en dat uw gebruikersnaam tussen de 3 en 20 tekens lang is en uw wachtwoord tussen de 6 en 30 tekens lang is.',
    'username_required' => 'Vul een gebruikersnaam in.',
    'password_required' => 'Vul een wachtwoord in.',
    'email_required' => 'Vul een email adres in.',
    'mcname_required' => 'Vul een Minecraft Username in.',
    'accept_terms' => 'U moet de Algemene Voorwaarden accepteren voordat u zich kunt registreren.',
    'invalid_recaptcha' => 'Ongeldige reCAPTCHA.',
    'username_minimum_3' => 'Uw gebruikersnaam moet minimaal 3 tekens lang zijn.',
    'username_maximum_20' => 'Uw gebruikersnaam mag maximaal 20 tekens lang zijn.',
    'mcname_minimum_3' => 'Uw Minecraft Username moet minimaal 3 tekens lang zijn.',
    'mcname_maximum_20' => 'Uw Minecraft Username mag maximaal 20 tekens lang zijn.',
    'password_minimum_6' => 'Uw wachtwoord moet minimaal 6 tekens lang zijn.',
    'password_maximum_30' => 'Uw wachtwoord mag maximaal 30 tekens lang zijn.',
    'passwords_dont_match' => 'Uw wachtwoorden komen niet overeen.',
    'username_mcname_email_exists' => 'Uw gebruikersnaam, Minecraft gebruikersnaam of e-mailadres bestaat al. Heeft u al een account aangemaakt?',
    'invalid_mcname' => 'Uw Minecraft gebruikersnaam is geen geldige account',
    'mcname_lookup_error' => 'Er is een fout opgetreden bij het verbinden met de Mojang servers. Probeer het later opnieuw.',
	'signature_maximum_900' => 'Your signature must be a maximum of 900 characters.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
     
    // UserCP
    'user_cp' => 'UserCP',
    'no_file_chosen' => 'Geen bestand gekozen',
    'private_messages' => 'Prive berichten',
    'profile_settings' => 'Profiel Instellingen',
    'your_profile' => 'Mijn Profiel',
    'topics' => 'Topics',
    'posts' => 'Posts',
    'reputation' => 'Reputatie',
    'friends' => 'Vrienden',
    'alerts' => 'Alerts',
     
    // Messaging
    'new_message' => 'Nieuwe Berichten',
    'no_messages' => 'Geen Berichten',
    'and_x_more' => 'en {x} meer', // Don't replace "{x}"
    'system' => 'Systeem',
    'message_title' => 'Bercith Titel',
    'message' => 'Bericht',
    'to' => 'Aan:',
    'separate_users_with_comma' => 'Meerde gebruikers tegelijk een bericht sturen? Plaats er een komma ertussen (",")',
    'viewing_message' => 'Bekijk Bericht',
    'delete_message' => 'Verwijder Bericht',
    'confirm_message_deletion' => 'Weet u zeker dat u dit bericht wilt verwijderen?',
     
    // Profile settings
    'display_name' => 'Weergavenaam',
    'upload_an_avatar' => 'Upload een avatar (.jpg, .png or .gif only):',
    'use_gravatar' => 'Gebruik Gravatar?',
    'change_password' => 'Verander wachtwoord',
    'current_password' => 'Huidig wachtwoord',
    'new_password' => 'Nieuw wachtwoord',
    'repeat_new_password' => 'Herhaal nieuw wachtwoord',
    'password_changed_successfully' => 'Wachtwoord succesvol veranderd',
    'incorrect_password' => 'Uw huidige wachtwoord is onjuist',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Display age on profile?',
     
    // Alerts
    'viewing_unread_alerts' => 'Bekijk ongelezen alerts. Verander naar <a href="/user/alerts/?view=read"><span class="label label-success">gelezen</span></a>.',
    'viewing_read_alerts' => 'Bekijk gelezen alerts. Verander naar <a href="/user/alerts/"><span class="label label-warning">ongelezen</span></a>.',
    'no_unread_alerts' => 'U heeft geen ongelezen alerts.',
    'no_alerts' => 'Geen alerts',
    'no_read_alerts' => 'U heeft geen gelezen alerts.',
    'view' => 'Bekijk',
    'alert' => 'Alert',
    'when' => 'Wanneer',
    'delete' => 'Verwijder',
    'tag' => 'Gebruiker Tag',
    'tagged_in_post' => 'U bent getagd in een post',
    'report' => 'Rapporteer',
    'deleted_alert' => 'Alert succesvol verwijderd',
     
    // Warnings
    'you_have_received_a_warning' => 'U heeft een waarschuwing van {x} op {y} ontvangen.', // Don't replace "{x}" or "{y}"
    'acknowledge' => 'Dit Klopt',
     
    // Forgot password
    'password_reset' => 'Wachtwoord Reset',
    'email_body' => 'U ontvangt deze e-mail omdat u een wachtwoord reset heeft gevraagd. Om uw wachtwoord te resetten, gebruik dan de volgende link:', // Body for the password reset email
    'email_body_2' => 'Als u het wachtwoord reset niet heeft aangevraagd, kunt u deze e-mail negeren.',
    'password_email_set' => 'Controleer uw e-mail voor verdere instructies.',
    'username_not_found' => 'Die gebruikersnaam bestaat niet.',
    'change_password' => 'Verander Wachtwoord',
    'your_password_has_been_changed' => 'Uw wachtwoord is succesvol gewijzigd.',
     
    // Profile page
    'profile' => 'Profiel',
    'player' => 'Spelers',
    'offline' => 'Offline',
    'online' => 'Online',
    'pf_registered' => 'Geregistreerd:',
    'pf_posts' => 'Posts:',
    'pf_reputation' => 'Reputatie:',
    'user_hasnt_registered' => 'Deze gebruiker heeft nog niet geregistreerd op onze website',
    'user_no_friends' => 'Deze gebruiker heeft geen vrienden toegevoegd',
    'send_message' => 'Verstuur Bericht',
    'remove_friend' => 'Verwijder Vriend',
    'add_friend' => 'Vriend Toevoegen',
    'last_online' => 'Laatst Online:',
	'find_a_user' => 'Find a user',
	'user_not_following' => 'This user does not follow anyone.',
	'user_no_followers' => 'This user has no followers.',
	'following' => 'FOLLOWING',
	'followers' => 'FOLLOWERS',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Write something on your profile...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'About',
	'reply' => 'Reply',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'No likes.',
	'post_liked' => 'Post liked.',
	'post_unliked' => 'Post unliked.',
	'no_posts' => 'No posts.',
	'last_5_posts' => 'Last 5 posts',
     
    // Staff applications
    'staff_application' => 'Staff Application',
    'application_submitted' => 'Application succesvol ingediend.',
    'application_already_submitted' => 'U heeft al een aanvraag ingediend. U moet wachten totdat hij beantwoord is voordat u een andere kan maken.',
    'not_logged_in' => 'U moet inloggen om deze pagina te bekijken.',
    'application_accepted' => 'Uw staff aanvraag is geaccepteerd.',
    'application_rejected' => 'Uw staff aanvraag is afgewezen.'
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
    'search_for_a_user' => 'Zoek voor een speler',
    'user' => 'Gebruiker:',
    'ip_lookup' => 'IP Lookup:',
    'registered' => 'Geregistreerd',
    'reason' => 'Reden:',
     
    // Reports
    'report_closed' => 'Report Gesloten.',
    'new_comment' => 'Nieuw Comment',
    'comments' => 'Comments',
    'only_viewed_by_staff' => 'Kan alleen worden bekeken door staff',
    'reported_by' => 'Gerapporteerd door',
    'close_issue' => 'Gesloten Uitslag',
    'report' => 'Report:',
    'view_reported_content' => 'Bekijk gemelde inhoud',
    'no_open_reports' => 'Geen open reports',
    'user_reported' => 'Speler Gerapporteerd',
    'type' => 'Type',
    'updated_by' => 'Bijgewerkt Door',
    'forum_post' => 'Forum Post',
    'user_profile' => 'Speler Profiel',
    'comment_added' => 'Comment added.',
    'new_report_submitted_alert' => 'Nieuw report ingediend door {x} over {y}', // Don't replace "{x}" or "{y}"
     
    // Staff applications
    'comment_error' => 'Zorg ervoor dat uw comment tussen de 2 en 2048 tekens lang is.',
    'viewing_open_applications' => 'Bekijk <span class="label label-info">open</span> applications. Verander naar <a href="/mod/applications/?view=accepted"><span class="label label-success">Geaccepted</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">Afgewezen</span></a>.',
    'viewing_accepted_applications' => 'Bekijk <span class="label label-success">geaccepteerd</span> applications. Verander naar <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">Afgewezen</span></a>.',
    'viewing_declined_applications' => 'Bekijk <span class="label label-danger">afgewezen</span> applications. Verander naar <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">Geaccepted</span></a>.',
    'time_applied' => 'Tijd Toegepast',
    'no_applications' => 'Geen applications in deze categorie',
    'viewing_app_from' => 'Bekijk application van {x}', // Don't replace "{x}"
    'open' => 'Open',
    'accepted' => 'Geaccepted',
    'declined' => 'Afgewezen',
    'accept' => 'Accepteer',
    'decline' => 'Afwijzen',
    'new_app_submitted_alert' => 'Nieuwe application ingediend door {x}' // Don't replace "{x}"
);
 
/* 
 *  General
 */
$general_language = array(
    // Homepage
    'news' => 'Nieuws',
    'social' => 'Sociaal',
     
    // General terms
    'submit' => 'Verstuur',
    'close' => 'Sluiten',
    'cookie_message' => '<strong>Deze website maakt gebruik van cookies om uw ervaring te verbeteren.</strong><p>Door op deze website te blijven accepteerd u deze.</p>',
    'theme_not_exist' => 'Het geselecteerde thema bestaat niet.',
    'confirm' => 'Bevestigen',
    'cancel' => 'Annuleer',
    'guest' => 'Gast',
    'guests' => 'Gasten',
    'back' => 'Terug',
    'search' => 'Zoeken',
    'help' => 'Help',
    'success' => 'Succes',
    'error' => 'Error',
    'view' => 'Bekijk',
	'info' => 'Info',
     
    // Play page
    'connect_with' => 'Server IP: <b>{x}<b>', // Don't replace {x}
    'online' => 'Online',
    'offline' => 'Offline',
    'status' => 'Status:',
    'players_online' => 'Spelers Online:',
    'queried_in' => 'Queried In:',
    'server_status' => 'Server Status',
    'no_players_online' => 'Er zijn geen spelers online!',
    'x_players_online' => 'Er zijn {x} spelers online.', // Don't replace {x}
     
    // Other
    'page_loaded_in' => 'Pagina geladen in {x} seconden', // Don't replace {x}; 's' stands for 'seconds'
    'none' => 'Geen',
	'404' => 'Sorry, we couldn\'t find that page.'
);
 
/* 
 *  Forum
 */
$forum_language = array(
    // Latest discussions view
    'forums' => 'Forums',
    'discussion' => 'Discussie',
    'stats' => 'Stats',
    'last_reply' => 'Laatste Reply',
    'ago' => 'geleden',
    'by' => 'door',
    'in' => 'in',
    'views' => 'views',
    'posts' => 'posts',
    'topics' => 'topics',
    'topic' => 'topic',
    'statistics' => 'Statistieken',
    'overview' => 'Overzicht',
    'latest_discussions' => 'Laatste Discussions',
    'latest_posts' => 'Laatste Posts',
    'users_registered' => 'Gebruikers Geregistreerd:',
    'latest_member' => 'Nieuwste Gebruiker:',
    'forum' => 'Forum',
    'last_post' => 'Laatste Post',
    'no_topics' => 'Nog geen topics hier',
    'new_topic' => 'Nieuw Topic',
    'subforums' => 'Subforums:',
     
    // View topic view
    'home' => 'Home',
    'topic_locked' => 'Topic Gesloten',
    'new_reply' => 'Nieuw Reply',
    'mod_actions' => 'Mod Actions',
    'lock_thread' => 'Sluit Thread',
    'unlock_thread' => 'Ontsluiten Thread',
    'merge_thread' => 'Smelt Thread Samen',
    'delete_thread' => 'Verwijder Thread',
    'confirm_thread_deletion' => 'Weet u zeker dat u deze thread wilt verwijderen?',
    'move_thread' => 'Verplaats Thread',
    'sticky_thread' => 'Sticky Thread',
    'report_post' => 'Report Post',
    'quote_post' => 'Quote Post',
    'delete_post' => 'Verwijder Post',
    'edit_post' => 'Bewerk Post',
    'reputation' => 'reputatie',
    'confirm_post_deletion' => 'Weet u zeker dat u deze post wilt verwijderen?',
    'give_reputation' => 'Geef Reputatie',
    'remove_reputation' => 'Verwijder Reputatie',
    'post_reputation' => 'Post Reputatie',
    'no_reputation' => 'Er zijn nog geen reputaties voor deze post',
    're' => 'RE:',
     
    // Create post view
    'create_post' => 'Maak Post',
    'post_submitted' => 'Post Geplaatst',
    'creating_post_in' => 'Maak post in: ',
    'topic_locked_permission_post' => 'Dit onderwerp is gesloten, maar u kunt nog posten',
     
    // Edit post view
    'editing_post' => 'Post Bewerken',
     
    // Sticky threads
    'thread_is_' => 'Thread is ',
    'now_sticky' => 'nu een sticky thread',
    'no_longer_sticky' => 'niet langer een sticky thread',
     
    // Create topic
    'topic_created' => 'Topic Gemaakt.',
    'creating_topic_in_' => 'Maak topic in forum ',
    'thread_title' => 'Thread Titel',
    'confirm_cancellation' => 'Weet u het zeker?',
    'label' => 'Label',
     
    // Reports
    'report_submitted' => 'Report ingediend.',
    'view_post_content' => 'Bekijk post inhoud',
    'report_reason' => 'Report Reden',
     
    // Move thread
    'move_to' => 'Verplaats naar:',
     
    // Merge threads
    'merge_instructions' => 'De thread <strong>moet</strong> binnen hetzelfde forum zijn. Het verplaats van de thread is nodig.',
    'merge_with' => 'Samensmelten met:',
     
    // Other
    'forum_error' => 'Sorry, we konden dat forum of topic niet vinden.',
    'are_you_logged_in' => 'Bent u ingelogd?',
    'online_users' => 'Gebruikers Online',
    'no_users_online' => 'Er zijn geen gebruikers online.',
     
    // Search
    'search_error' => 'Voer een zoekopdracht in die tussen 1 en 32 tekens lang is.'
);
 
/*
 *  Emails
 */
$email_language = array(
    // Registration email
    'greeting' => 'Beste Gebruiker',
    'message' => 'Bedankt voor het registreren! Om uw registratie te voltooien, klikt u op de volgende link:',
    'thanks' => 'Bedankt,'
);
 
/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
    'seconds_short' => 's', // Shortened "seconds", eg "s"
    'less_than_a_minute' => 'Minden dan een minuut geleden',
    '1_minute' => '1 minuut geladen',
    '_minutes' => '{x} minuten geleden',
    'about_1_hour' => '1 uur geleden',
    '_hours' => '{x} uren geleden',
    '1_day' => '1 dag geleden',
    '_days' => '{x} dagen geleden',
    'about_1_month' => '1 month geleden',
    '_months' => '{x} maanden geleden',
    'about_1_year' => '1 jaar geleden',
    'over_x_years' => '{x} jaren geleden'
);
  
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
    'display_records_per_page' => 'Geef _MENU_ rijen weer per pagina', // Don't replace "_MENU_"
    'nothing_found' => 'Geen resultaat gevonden',
    'page_x_of_y' => 'pagina _PAGE_ van de _PAGES_ wordt weergegeven', // Don't replace "_PAGE_" or "_PAGES_"
    'no_records' => 'Geen gegevens beschikbaar',
    'filtered' => '(gefilterd van de _MAX_ gegevens)' // Don't replace "_MAX_"
);
  
?>