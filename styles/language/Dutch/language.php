<?php
/*
 *  Sander Jochems <http://www.sanderjochems.nl>
 *  jesseke55/Headhunterz_/jessegeerts <http://jessegeerts.nl>
 *  melerpe
 *  DoraKlikOpDora/Besbos <http://minevibes.com>
 *  Sander Lambrechts
 *
 *  License: MIT
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
    'infractions' => 'Straffen',
    'invalid_token' => 'Ongeldige token, probeer het opnieuw',
    'invalid_action' => 'Ongeldige Actie',
    'successfully_updated' => 'Succesvol bijgewerkt',
    'settings' => 'Instellingen',
    'confirm_action' => 'Bevestig actie',
    'edit' => 'Bewerk',
    'actions' => 'Acties',
    'task_successful' => 'Opdracht uitgevoerd',

    // Admin login
    're-authenticate' => 'Log opnieuw in',

    // Admin sidebar
    'index' => 'Overzicht',
    'announcements' => 'Aankondigingen',
    'core' => 'Kern',
    'custom_pages' => 'Zelf gemaakte pagina\'s',
    'general' => 'Algemeen',
    'forums' => 'Forums',
    'users_and_groups' => 'Gebruikers en Groepen',
    'minecraft' => 'Minecraft',
    'style' => 'Stijl',
    'addons' => 'Uitbreidingen',
    'update' => 'Bijwerken',
    'misc' => 'Overig',
    'help' => 'Help',

    // Admin index page
    'statistics' => 'Statistieken',
    'registrations_per_day' => 'Registraties per dag (Tot wel 7 dagen terug)',

    // Admin announcements page
    'current_announcements' => 'Huidige Aankondigingen',
    'create_announcement' => 'Maak Aankondiging',
    'announcement_content' => 'Inhoud Aankondiging',
    'announcement_location' => 'Locatie Aankondiging',
    'announcement_can_close' => 'Aankondiging weg te klikken?',
    'announcement_permissions' => 'Aankondiging toestemmingen',
    'no_announcements' => 'Nog geen aankondigingen gemaakt.',
    'confirm_cancel_announcement' => 'Weet je zeker dat je deze aankondiging wilt annuleren?',
    'announcement_location_help' => 'Ctrl-klik om meerdere pagina\'s te selecteren.',
    'select_all' => 'Selecteer alles',
    'deselect_all' => 'Deselecteer alles',
    'announcement_created' => 'Aankondiging succesvol aangemaakt!',
    'please_input_announcement_content' => 'Voer een aankondiging in en selecteer een type.',
    'confirm_delete_announcement' => 'Weet je zeker dat je de aankondiging wilt verwijderen?',
    'announcement_actions' => 'Aankondiging acties',
    'announcement_deleted' => 'Aankondiging succesvol verwijderd!',
    'announcement_type' => 'Aankondiging type',
    'can_view_announcement' => 'Kan deze groep de aankondiging bekijken?',

    // Admin core page
    'general_settings' => 'Algemene Instellingen',
    'modules' => 'Modules',
    'module_not_exist' => 'Die module bestaat niet',
    'module_enabled' => 'Module ingeschakeld.',
    'module_disabled' => 'Module uitgeschakeld.',
    'site_name' => 'Site Naam',
    'language' => 'Taal',
    'voice_server_not_writable' => '"core/voice_server.php" is niet beschrijfbaar. Controleer de bestandsrechten (chmod 777). ',
    'email' => 'Email',
    'incoming_email' => 'Inkomend email adres',
    'outgoing_email' => 'Uitgaand email adres',
    'outgoing_email_help' => 'Alleen nodig als de PHP mail functie is ingeschakeld',
    'use_php_mail' => 'Gebruik PHP mail() functie?',
    'use_php_mail_help' => 'Aanbevolen: ingeschakeld. Als je website geen mails stuurt, dan kun je de "core/email.php" uitschakelen in de email instellingen.',
    'use_gmail' => 'Gebruik Gmail voor het verzenden van e-mails?',
    'use_gmail_help' => 'Alleen beschikbaar als de PHP mail() functie is uitgeschakeld. Als je ervoor kiest om Gmail te gebruiken, zal SMTP worden gebruikt, dit zal de configuratie in core/email.php nodig hebben.',
    'enable_mail_verification' => 'Schakel e-mail account verificatie in?',
    'enable_email_verification_help' => 'Nadat deze geactiveerd is zullen nieuwe geregistreerde gebruikers gevraagd worden om hun account via de e-mail te verifiëren voordat de registratie voltooid wordt.',
    'explain_email_settings' => 'Het volgende is benodigd als de functie: "Gebruik PHP mail()" is <strong>uitgeschakeld</strong>.Je kan de documentatie over de instellingen vinden <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">op onze wiki</a>.',
    'email_config_not_writable' => 'Je <strong>core/email.php</strong> kon niet worden aangepast. Bekijk je bestandspermissies (777).',
    'pages' => 'Pagina\'s',
    'enable_or_disable_pages' => 'Schakel pagina\'s hier in en uit.',
    'enable' => 'Klik om in te schakelen',
    'disable' => 'Klik om uit te schakelen',
    'maintenance_mode' => 'Forum onderhoud modus',
    'forum_in_maintenance' => 'Forum is in onderhoud',
    'unable_to_update_settings' => 'Kan de instellingen niet updaten. Zorg dat alle velden zijn ingevuld.',
    'editing_google_analytics_module' => 'Bewerk de Google Analytics module',
    'tracking_code' => 'Traceer Code',
    'tracking_code_help' => 'Voer het traceerscript van Google Analytics hier in, laat de script tags eromheen!',
    'google_analytics_help' => 'Bekijk <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">deze gids</a> voor meer informatie, volg de stappen 1 tot en met 3',
    'social_media_links' => 'Social Media Links',
    'youtube_url' => 'YouTube URL',
    'twitter_url' => 'Twitter URL',
    'twitter_dark_theme' => 'Gebruik donker Twitter thema?',
    'twitter_widget_id' => 'Twitter Widget ID',
    'google_plus_url' => 'Google Plus URL',
    'facebook_url' => 'Facebook URL',
    'registration' => 'Registratie',
    'registration_warning' => 'Als je deze module uitschakeld dan kunnen er geen mensen registreren op je website.',
    'google_recaptcha' => 'Schakel Google reCAPTCHA in',
    'recaptcha_site_key' => 'reCAPTCHA Site sleutel',
    'recaptcha_secret_key' => 'reCAPTCHA Secret sleutel',
    'registration_terms_and_conditions' => 'Registratie voorwaarden',
    'voice_server_module' => 'Voice Server Module',
    'only_works_with_teamspeak' => 'Deze module werkt momenteel alleen met TeamSpeak en Discord.',
    'discord_id' => 'Discord Server ID',
    'voice_server_help' => 'Voer de gebruikersgegevens in voor de ServerQuery.',
    'ip_without_port' => 'IP (Zonder poort)',
    'voice_server_port' => 'Poort (Normaal is de poort 10011)',
    'virtual_port' => 'Virtuele Poort (Normaal is de poort 9987)',
    'permissions' => 'Rechten:',
    'view_applications' => 'Bekijk Aanvragen?',
    'accept_reject_applications' => 'Accepteer/Weiger Aanvragen?',
    'questions' => 'Vragen:',
    'question' => 'Vraag',
    'type' => 'Soort',
    'options' => 'Opties',
    'options_help' => 'Elke optie op een nieuwe regel (Alleen voor lijst opties, anders negeren)',
    'no_questions' => 'Er zijn geen vragen toegevoegd.',
    'new_question' => 'Nieuwe vraag',
    'editing_question' => 'Vraag bewerken',
    'delete_question' => 'Vraag verwijderen',
    'dropdown' => 'Lijst',
    'text' => 'Tekstlijn',
    'textarea' => 'Tekstblok',
    'question_deleted' => 'Vraag verwijderd',
	'name_required' => 'Naam is verplicht.',
	'question_required' => 'Vraag is verplicht.',
	'name_minimum' => 'Naam moet uit miniamaal 2 karakters bestaan.',
	'question_minimum' => 'Vraag moet uit miniamaal 2 karakters bestaan.',
	'name_maximum' => 'Naam moet uit maximaal 16 karakters bestaan.',
	'question_maximum' => 'Vraag moet uit maximaal 16 karakters bestaan.',
    'use_followers' => 'Gebruik volgers?',
    'use_followers_help' => 'Als dit is uitgeschakeld dan wordt het vrienden systeem ingeschakeld',

    // Admin custom pages page
    'click_on_page_to_edit' => 'Klik op een pagina om deze te bewerken.',
    'page' => 'Pagina:',
    'url' => 'URL:',
    'page_url' => 'Pagina URL',
    'page_url_example' => '(Met voorafgaande "/", bijvoorbeeld /help/)',
    'page_title' => 'Pagina Titel',
    'page_content' => 'Pagina Inhoud',
    'new_page' => 'Nieuwe Pagina',
    'page_successfully_created' => 'Pagina succesvol aangemaakt',
    'page_successfully_edited' => 'Pagina succesvol bewerkt',
    'unable_to_create_page' => 'Kan de pagina niet aanmaken.',
    'unable_to_edit_page' => 'Kan de pagina niet bewerken.',
    'create_page_error' => 'Zorg ervoor dat de URL tussen 1 en 20 tekens lang is, de pagina titel tussen 1 en 30 tekens lang is en de inhoud van de pagina tussen 5 en 20.480 tekens lang is.',
    'delete_page' => 'Verwijder Pagina',
    'confirm_delete_page' => 'Weet u zeker dat u deze pagina wilt verwijderen?',
    'page_deleted_successfully' => 'Pagina succesvol verwijderd',
    'page_link_location' => 'Toon pagina link in:',
    'page_link_navbar' => 'Navbar',
    'page_link_more' => 'Onder de navbar "Meer" knop',
    'page_link_footer' => 'Pagina Onderkant',
    'page_link_none' => 'Geen pagina link',
    'page_permissions' => 'Pagina Toestemmingen',
    'can_view_page' => 'Kan de pagina zien:',
    'redirect_page' => 'Stuur pagina door naar andere link?',
    'redirect_link' => 'Doorstuur link',
    'page_icon' => 'Pagina Icoon',

    // Admin forum page
    'labels' => 'Onderwerp Labels',
    'new_label' => 'Nieuwe Label',
    'no_labels_defined' => 'Geen labels aangemaakt',
    'label_name' => 'Label Naam',
    'label_type' => 'Label Type',
    'label_forums' => 'Label Forums',
    'label_creation_error' => 'Fout bij het maken van een label. Zorg ervoor dat de naam niet langer is dan 32 tekens en dat u een soort heeft opgegeven.',
    'confirm_label_deletion' => 'Weet je zeker dat je dit label verwijderen?',
    'editing_label' => 'Label bewerken',
    'label_creation_success' => 'Label succesvol gemaakt',
    'label_edit_success' => 'Label succesvol bewerkt',
    'label_default' => 'Standaard',
    'label_primary' => 'Primair',
    'label_success' => 'Succes',
    'label_info' => 'Info',
    'label_warning' => 'Waarschuwing',
    'label_danger' => 'Gevaar',
    'new_forum' => 'Nieuw Forum',
    'forum_layout' => 'Forum Layout',
    'table_view' => 'Forum overzicht',
    'latest_discussions_view' => 'Recente overzicht',
    'create_forum' => 'Maak Forum',
    'forum_name' => 'Forum Naam',
    'forum_description' => 'Forum Beschrijving',
    'delete_forum' => 'Verwijder Forum',
    'move_topics_and_posts_to' => 'Verplaats topics en posts naar',
    'delete_topics_and_posts' => 'Verwijder topics en posts',
    'parent_forum' => 'Subforum',
    'has_no_parent' => 'Heeft geen subforum',
    'forum_permissions' => 'Forum Rechten',
    'can_view_forum' => 'Kan forum bekijken',
    'can_create_topic' => 'Kan een onderwerp maken',
    'can_post_reply' => 'Kan een antwoord plaatsen',
    'display_threads_as_news' => 'Vertoon onderwerpen als nieuws op de voorpagina?',
    'input_forum_title' => 'Vul een forum titel in.',
    'input_forum_description' => 'Vul een forum beschrijving in (HTML codes mogen worden gebruikt).',
    'forum_name_minimum' => 'De forum naam moet minstens uit 2 tekens bestaan.',
    'forum_description_minimum' => 'De forum beschrijving moet minstens uit 2 tekens bestaan.',
    'forum_name_maximum' => 'De forum naam mag maximaal 150 tekens bevatten.',
    'forum_description_maximum' => 'De forum beschrijving mag maar uit 255 tekens bestaan.',
    'forum_type_forum' => 'Discussie Forum',
    'forum_type_category' => 'Categorie',

    // Admin Users and Groups page
    'users' => 'Gebruikers',
    'new_user' => 'Nieuwe Gebruiker',
    'created' => 'Aangemaakt',
    'user_deleted' => 'Gebruiker Verwijderd',
    'validate_user' => 'Bevestigde Gebruiker',
    'update_uuid' => 'UUID bijwerken',
    'unable_to_update_uuid' => 'De UUID kan niet worden bijgewerkt.',
    'update_mc_name' => 'Update Minecraft Naam',
    'reset_password' => 'Reset Wachtwoord',
    'punish_user' => 'Straf Gebruiker',
    'delete_user' => 'Verwijder Gebruiker',
    'minecraft_uuid' => 'Minecraft UUID',
    'ip_address' => 'IP Adres',
    'ip' => 'IP:',
    'other_actions' => 'Andere Actie\'s:',
    'disable_avatar' => 'Schakel avatar uit',
    'enable_avatar' => 'Schakel avatar in',
    'confirm_user_deletion' => 'Weet je zeker dat je de gebruiker <b>{x}</b> wilt verwijderen?', // Don't replace "{x}"
    'groups' => 'Groepen',
    'group' => 'Groep',
    'group2' => 'Tweede Groep',
    'new_group' => 'Nieuwe Groep',
    'id' => 'ID',
    'name' => 'Naam',
    'create_group' => 'Maak Groep',
    'group_name' => 'Groep Naam',
    'group_html' => 'Groep HTML',
    'group_html_lg' => 'Groep HTML Groote',
    'donor_group_id' => 'Donor Pakket ID',
    'donor_group_id_help' => '<p>Dit is het ID van het pakket van de groep van Buycraft, Minecraft Market of MCStock.</p><p>Dit kan leeg gelaten worden.</p>',
    'donor_group_instructions' =>   '<p>Donor groepen moeten worden gecreëerd in de volgorde van de <strong>laagste naar hoogste prijs</ strong>.</p>
                                    <p>Zo zal een &euro; 10 pakket worden gemaakt boven een &euro; 20 pakket.</p>',
    'delete_group' => 'Verwijder Groep',
    'confirm_group_deletion' => 'Weet je zeker dat je de groep <b>{x}<b> verwijderen?', // Don't replace "{x}"
    'group_staff' => 'Is de groep een staff groep?',
    'group_modcp' => 'Kan de groep de ModCP bekijken?',
    'group_admincp' => 'Kan de groep de AdminCP bekijken?',
    'group_name_required' => 'Groep naam invullen is verplicht.',
    'group_name_minimum' => 'De groep naam moet uit minimaal 2 tekens bestaan.',
    'group_name_maximum' => 'De groep naam mag maar uit 20 tekens bestaan.',
    'html_maximum' => 'De groep HTML mag maximaal uit 1024 tekens bestaan.',
    'select_user_group' => 'De gebruiker moet in een groep zijn.',
    'uuid_max_32' => 'De UUID kan uit niet meer dan 32 tekens bestaan.',
    'cant_delete_root_user' => 'De administrator kan niet verwijderd worden!',
    'cant_modify_root_user' => 'Je kan de groep van de administrator niet bewerken!',

    // Admin Minecraft page
    'minecraft_settings' => 'Minecraft Instellingen',
    'use_plugin' => 'Gebruik Nameless Minecraft plugin?',
    'force_avatars' => 'Forceer Minecraft avatars?',
    'uuid_linking' => 'Schakel UUID koppelen in?',
    'use_plugin_help' => 'Gebruik de plugin voor rank synchronisatie en ook in het spel registreren en ticket indienen.',
    'uuid_linking_help' => 'Indien uitgeschakeld, zullen gebruikersaccounts niet worden gekoppeld aan UUID. Je kan dit het beste aan laten.',
    'plugin_settings' => 'Plugin Instellingen',
    'confirm_api_regen' => 'Weet je zeker dat je een nieuwe API Key wilt genereren?',
    'servers' => 'Servers',
    'new_server' => 'Nieuwe Server',
    'confirm_server_deletion' => 'Weet je zeker dat je deze server wilt verwijderen?',
    'main_server' => 'Belangrijkste Server',
    'main_server_help' => 'Hierheen verbinden de spelers van de server. Normaal gesproken zal dit de BungeeCord zijn.',
    'choose_a_main_server' => 'Kies de belangrijkste server.',
    'external_query' => 'Gebruik externe query?',
    'external_query_help' => 'Gebruik een externe API om de Minecraft-server te Queryen? Gebruik dit alleen als de ingebouwde niet werkt.',
    'editing_server' => 'Server {x} aan het bewerken', // Don't replace "{x}"
    'server_ip_with_port' => 'Server IP (met port) (numerieke of domein)',
    'server_ip_with_port_help' => 'Dit is het IP dat zal worden getoond aan gebruikers. Het zal niet worden gequeryd.',
    'server_ip_numeric' => 'Server IP (met port) (alleen numerieke)',
    'server_ip_numeric_help' => 'Dit is het IP dat zal worden gequeryd, zorg ervoor dat het alleen numeriek is. Het zal niet worden getoond aan gebruikers.',
    'show_on_play_page' => 'Laat op de Play pagina zien?',
    'pre_17' => 'Lager dan Minecraft versie 1.7?',
    'server_name' => 'Servernaam',
    'invalid_server_id' => 'Ongeldige server ID',
    'show_players' => 'Laat de spelers op de Speel pagina zien.',
    'server_edited' => 'Server succesvol bewerkt',
    'server_created' => 'Server succesvol gemaakt',
    'query_errors' => 'Query fouten',
    'query_errors_info' => 'De volgende fouten kun je gebruiken om fouten met de interne server query op te lossen.',
    'no_query_errors' => 'Geen query fouten gevonden',
    'date' => 'Datum:',
    'port' => 'Poort:',
    'viewing_error' => 'Bekijk melding',
    'confirm_error_deletion' => 'Weet je het zeker dat je deze error wilt verwijderen?',
    'display_server_status' => 'Laat Server Status module zien',
    'server_name_required' => 'Je moet een server naam invullen.',
    'server_ip_required' => 'Je moet een server IP invullen.',
    'server_name_minimum' => 'De server naam moet minstens uit 2 tekens bestaan.',
    'server_ip_minimum' => 'De server IP moet minstens uit 2 tekens bestaan.',
    'server_name_maximum' => 'De server naam mag maar maximaal uit 20 tekens bestaan.',
    'server_ip_maximum' => 'De server IP mag maar uit 64 tekens bestaan.',
    'purge_errors' => 'Verwijder foutmeldingen',
    'confirm_purge_errors' => 'Weet je het zeker dat je de foutmeldingen wilt verwijderen>',
    'avatar_type' => 'Avatar type',
    'custom_usernames' => 'Forceer Minecraft gebruikersnaam?',
    'mcassoc' => 'mcassoc',
    'use_mcassoc' => 'Gebruik mcassoc?',
    'use_mcassoc_help' => 'mcassoc kijkt na of mensen echt eigenaar van het Minecraft account zijn.',
    'mcassoc_key' => 'mcassoc Gedeelde Sleutel',
    'invalid_mcassoc_key' => 'Ongeldige mcassoc sleutel.',
    'mcassoc_instance' => 'mcassoc Instance',
    'mcassoc_instance_help' => 'Genereer een instance code <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">hier</a>',
    'mcassoc_key_help' => 'Verkrijg je mcassoc sleutel <a href="https://mcassoc.lukegb.com/" target="_blank">hier</a>',
    'enable_name_history' => 'Schakel profiel gebruikersnaam geschiedenis is?',

    // Admin Themes, Templates and Addons
    'themes' => 'Thema\'s',
    'templates' => 'Sjablonen',
    'installed_themes' => 'Geinstaleerde thema\'s',
    'installed_templates' => 'Geinstaleerde sjablonen',
    'installed_addons' => 'Geinstaleerde addons',
    'install_theme' => 'Installeer thema',
    'install_template' => 'Installeer sjabloon',
    'install_addon' => 'Installeer addon',
    'install_a_theme' => 'Installeer een thema',
    'install_a_template' => 'Installeer een sjabloon',
    'install_an_addon' => 'Installeer een addon',
    'active' => 'Actief',
    'activate' => 'Activeer',
    'deactivate' => 'Deactiveer',
    'theme_install_instructions' => 'Upload thema\'s naar de <b>styles/themes</b> map, Klik daarna op de "Scan" knop.',
    'template_install_instructions' => 'Upload sjablonen naar de <b>styles/templates</b> map, Klik daarna op de "Scan" knop.',
    'addon_install_instructions' => 'Upload addons naar de <b>addons</b> map, Klik daarna op de "Scan" knop.',
    'addon_install_warning' => 'Addons worden geïnstalleerd op uw eigen risico. Maak een back-up van uw bestanden en de database voordat u verder gaat',
    'scan' => 'Zoek',
    'theme_not_exist' => 'Dat thema bestaat niet',
    'template_not_exist' => 'Dat sjabloon bestaat niet',
    'addon_not_exist' => 'Die addon bestaat niet',
    'style_scan_complete' => 'Klaar, alle nieuwe stijlen zijn geïnstalleerd.',
    'addon_scan_complete' => 'Klaar, alle nieuwe addons zijn geïnstalleerd.',
    'theme_enabled' => 'Thema ingeschakeld.',
    'template_enabled' => 'Sjabloon uitgeschakeld.',
    'addon_enabled' => 'Addon ingeschakeld.',
    'theme_deleted' => 'Thema verwijderd.',
    'template_deleted' => 'Sjabloon verwijderd.',
    'addon_disabled' => 'Addon uitgeschakeld.',
    'inverse_navbar' => 'Omgekeerde kleur Navbar',
    'confirm_theme_deletion' => 'Weet je het zeker dat je thema <b>{x}</b> wil verwijderen?<br /><br />Het thema wordt verwijderd uit de <b>styles/themes</b> map.', // Don't replace {x}
    'confirm_template_deletion' => 'Weet je zeker dat je het sjabloon <b>{x}</b> wilt verwijderen?<br /><br />Het sjabloon wordt verwijderd uit de <b>styles/templates</b> map.', // Don't replace {x}
    'unable_to_enable_addon' => 'Kan addon niet inschakelen. Zorg ervoor dat het een geldige NamelessMC addon is.',

    // Admin Misc page
    'other_settings' => 'Andere Instellingen',
    'enable_error_reporting' => 'Probleem rapportage inschakelen?',
    'error_reporting_description' => 'Dit mag alleen worden gebruikt voor het opsporen van fouten, het is aanbevolen dit als uitgeschakeld te laten staan.',
    'display_page_load_time' => 'Toon pagina laadtijd?',
    'page_load_time_description' => 'Nadat deze is ingeschakeld zal een snelheidsmeter weergegeven in de voettekst die de laadtijd van de pagina weergeeft. (Dit is aan de onderkant van de pagina bij de copyright.)',
    'reset_website' => 'Reset Website',
    'reset_website_info' => 'Deze knop zal je website resetten <b>Addons worden uitgeschakeld, maar niet verwijderd uit de database en addons map, en de instellingen worden niet veranderd.</b> Ingestelde Minecraft servers blijven behouden',
    'confirm_reset_website' => 'Weet je zeker dat je je website wilt resetten?',

    // Admin Update page
    'installation_up_to_date' => 'Je installatie is up to date',
    'update_check_error' => 'Helaas konden we niet controleren of er updates zijn. Probeer het later nog eens.',
    'new_update_available' => 'Er is een update beschikbaar.',
    'your_version' => 'Je huidige versie:',
    'new_version' => 'Recentste versie:',
    'download' => 'Download',
    'update_warning' => 'Waarschuwing: Kijk even dubbel of je de zip hebt gedownloadet en de bestanden daarvan hebt geupload, voordat je de update start!'
);

/*
 *  Navbar
 */
$navbar_language = array(
    // Text only
    'home' => 'Home',
    'play' => 'Speel',
    'forum' => 'Forum',
    'more' => 'Meer',
    'staff_apps' => 'Staff Sollicitaties',
    'view_messages' => 'Bekijk Berichten',
    'view_alerts' => 'Bekijk Meldingen',

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
    'username' => 'Gebruikersnaam',
    'minecraft_username' => 'Minecraft Gebruikersnaam',
    'email' => 'E-mail',
    'email_address' => 'Email Adres',
    'date_of_birth' => 'Geboortedatum',
    'location' => 'Locatie',
    'user_title' => 'Titel',
    'password' => 'Wachtwoord',
    'confirm_password' => 'Bevestig Wachtwoord',
    'i_agree' => 'Ik Accepteer',
    'agree_t_and_c' => 'Als u op <strong class="label label-primary">Registreer</strong> klikt, dan bent u akkoord om de <a href="#" data-toggle="modal" data-target="#t_and_c_m">Algemene Voorwaarden</a>.',
    'register' => 'Registreer',
    'sign_in' => 'Inloggen',
    'sign_out' => 'Uitloggen',
    'terms_and_conditions' => 'Algemene Voorwaarden',
    'successful_signin' => 'Je bent succesvol ingelogd',
    'incorrect_details' => 'Gegevens Onjuist',
    'remember_me' => 'Onthoud mij',
    'forgot_password' => 'Wachtwoord Vergeten',
    'must_input_username' => 'Je moet een gebruikersnaam invullen.',
    'must_input_password' => 'Je moet een wachtwoord invullen.',
    'inactive_account' => 'Je account is momenteel inactief. Bekijk uw mail om het te activeren.',
    'account_banned' => 'Je account is verbannen.',
    'successfully_logged_out' => 'Je bent succesvol uitgelogd.',
    'signature' => 'Ondertekening',
    'registration_check_email' => 'Controleer je e-mails voor een validatie link. Je zal niet in staat zijn om in te loggen, totdat er deze op de link wordt geklikt.',
    'unknown_login_error' => 'Sorry, er was een onbekende fout tijdens het inloggen. Probeer het later opnieuw.',
    'validation_complete' => 'Bedankt voor het registreren! Je kan nu inloggen.',
    'validation_error' => 'Fout bij het verwerken van je aanvraag. Probeer het opnieuw door op de link te klikken.',
    'registration_error' => 'Zorg ervoor dat je alle velden hebt ingevuld, dat je gebruikersnaam tussen de 3 en 20 tekens lang is en je wachtwoord tussen de 6 en 30 tekens lang is.',
    'username_required' => 'Vul een gebruikersnaam in.',
    'password_required' => 'Vul een wachtwoord in.',
    'email_required' => 'Vul een email adres in.',
    'mcname_required' => 'Vul een Minecraft gebruikersnaam in.',
    'accept_terms' => 'Je moet de Algemene Voorwaarden accepteren voordat je je kunt registreren.',
    'invalid_recaptcha' => 'Ongeldige reCAPTCHA.',
    'username_minimum_3' => 'Je gebruikersnaam moet minimaal 3 tekens lang zijn.',
    'username_maximum_20' => 'Je gebruikersnaam mag maximaal 20 tekens lang zijn.',
    'mcname_minimum_3' => 'Je Minecraft Gebruikersnaam moet minimaal 3 tekens lang zijn.',
    'mcname_maximum_20' => 'Je Minecraft Gebruikersnaam mag maximaal 20 tekens lang zijn.',
    'password_minimum_6' => 'Je wachtwoord moet minimaal 6 tekens lang zijn.',
    'password_maximum_30' => 'Je wachtwoord mag maximaal 30 tekens lang zijn.',
    'passwords_dont_match' => 'Je wachtwoorden komen niet overeen.',
    'username_mcname_email_exists' => 'Je gebruikersnaam, Minecraft gebruikersnaam of e-mailadres bestaat al. Heb je al een account aangemaakt?',
    'invalid_mcname' => 'Je Minecraft gebruikersnaam is geen geldig account. Er worden alleen gekochte Minecraft account geaccepteerd.',
    'mcname_lookup_error' => 'Er is een fout opgetreden bij het verbinden met de Mojang servers. Probeer het later opnieuw.',
    'signature_maximum_900' => 'Je handtekening mag maar uit 900 tekens bestaan.',
    'invalid_date_of_birth' => 'Ongeldige geboortedatum ingevuld. Vul deze in met behulp van de kalender.',
    'location_required' => 'Vul je plaats in.',
    'location_minimum_2' => 'Je locatie moet minstens uit 2 tekens bestaan.',
    'location_maximum_128' => 'Je locatie mag maar uit 128 tekens bestaan.',
    'verify_account' => 'Verifieer account',
    'verify_account_help' => 'Volg de stappen zodat wij kunnen zien of u eigenaar bent van het account',
    'verification_failed' => 'Verificatie mislukt. Probeer a.u.b. Opnieuw',
    'verification_success' => 'Verificatie gelukt! Je kan nu inloggen.',
    'complete_signup' => 'Voltooi de aanmelding',
    'registration_disabled' => 'Website aanmeldingen zijn uitgeschakeld.',

    // UserCP
    'user_cp' => 'UserCP',
    'no_file_chosen' => 'Er is geen bestand gekozen',
    'private_messages' => 'Prive berichten',
    'profile_settings' => 'Profiel Instellingen',
    'your_profile' => 'Mijn Profiel',
    'topics' => 'Onderwerpen',
    'posts' => 'Berichten',
    'reputation' => 'Reputatie',
    'friends' => 'Vrienden',
    'alerts' => 'Meldingen',

    // Messaging
    'new_message' => 'Nieuwe Berichten',
    'no_messages' => 'Geen Berichten',
    'and_x_more' => 'en {x} meer', // Don't replace "{x}"
    'system' => 'Systeem',
    'message_title' => 'Bericht Titel',
    'message' => 'Bericht',
    'to' => 'Aan:',
    'separate_users_with_comma' => 'Meerde gebruikers tegelijk een bericht sturen? Plaats er een komma ertussen (",")',
    'viewing_message' => 'Bekijk Bericht',
    'delete_message' => 'Verwijder Bericht',
    'confirm_message_deletion' => 'Weet je zeker dat je dit bericht wilt verwijderen?',

    // Profile settings
    'display_name' => 'Weergavenaam',
    'upload_an_avatar' => 'Upload een avatar (alleen .jpg, .png of .gif):',
    'use_gravatar' => 'Wil je gebruik maken van Gravatar? (Aanvinken is inschakelen)',
    'change_password' => 'Verander wachtwoord',
    'current_password' => 'Huidig wachtwoord',
    'new_password' => 'Nieuw wachtwoord',
    'repeat_new_password' => 'Herhaal nieuw wachtwoord',
    'password_changed_successfully' => 'Wachtwoord succesvol veranderd',
    'incorrect_password' => 'Uw huidige wachtwoord is onjuist',
    'update_minecraft_name_help' => 'Dit zal je website gebruikersnaam veranderen naar je Minecraft gebruikersnaam die je nu hebt. Je kan dit maar eens in de 30 dagen uitvoeren.',
    'unable_to_update_mcname' => 'De Minecraft gebruikersnaam kan niet worden bijgewerkt.',
    'display_age_on_profile' => 'Laat je leeftijd zien op je profiel?',
    'two_factor_authentication' => 'Authenticatie in 2 stappen',
    'enable_tfa' => 'Schakel tweefactorauthenticatie in.',
    'tfa_type' => 'Tweefactorauthenticatie type:',
    'authenticator_app' => 'Authenticator app',
    'tfa_scan_code' => 'Scan de volgende code in je authenticate app:',
    'tfa_code' => ' Als je apparaat geen camera heeft, of je bent niet in staat om de QR code uit te lezen, vul dan de volgende code in de app:',
    'tfa_enter_code' => 'Vul de code in die je ziet in de authenticatie app:',
    'invalid_tfa' => 'Ongeldige code, probeer het nog eens.',
    'tfa_successful' => 'Tweefactorauthenticatie is met success ingesteld. Iedere keer als je inlogd moet je jezelf verifieren met een code.',
    'confirm_tfa_disable' => 'Weet je het zeker dat je tweefactorauthenticatie wilt uitschakelen?',
    'tfa_disabled' => 'Tweefactorauthenticatie is uitgeschakeld.',
    'tfa_enter_email_code' => 'We hebben je een verificatie code gestuurd in een email. Vul de code in:',
    'tfa_email_contents' => 'Er is een login poging gemaakt tot je account. Als je dit bent, vul dan de volgende code in als dat word gevraagd. Als je dit niet bent dan kan je de email negeren, hoe dan ook een wachtwoord reset word aangeraden. De code is voor 10 minuten geldig.',

    // Alerts
    'viewing_unread_alerts' => 'Bekijk ongelezen meldingen. Verander naar <a href="/user/alerts/?view=read"><span class="label label-success">gelezen</span></a>.',
    'viewing_read_alerts' => 'Bekijk gelezen meldingen. Verander naar <a href="/user/alerts/"><span class="label label-warning">ongelezen</span></a>.',
    'no_unread_alerts' => 'Je hebt geen ongelezen meldingen.',
    'no_alerts' => 'Geen meldingen',
    'no_read_alerts' => 'Je heeft geen gelezen meldingen.',
    'view' => 'Bekijk',
    'alert' => 'Alert',
    'when' => 'Wanneer',
    'delete' => 'Verwijder',
    'tag' => 'Gebruiker Tag',
    'tagged_in_post' => 'Je bent getagd in een post',
    'report' => 'Rapporteer',
    'deleted_alert' => 'Melding is succesvol verwijderd',

    // Warnings
    'you_have_received_a_warning' => 'Je hebt een waarschuwing ontvangen van {x} op {y}.', // Don't replace "{x}" or "{y}"
    'acknowledge' => 'Dit Klopt',

    // Forgot password
    'password_reset' => 'Wachtwoord Reset',
    'email_body' => 'Je ontvangt deze e-mail omdat je om een wachtwoord reset hebt gevraagd. Om je wachtwoord te resetten, gebruik de volgende link:', // Body for the password reset email
    'email_body_2' => 'Als je de wachtwoord reset niet hebt aangevraagd, kan je deze e-mail negeren.',
    'password_email_set' => 'Controleer je e-mail voor verdere instructies.',
    'username_not_found' => 'Die gebruikersnaam bestaat niet.',
    'change_password' => 'Verander Wachtwoord',
    'your_password_has_been_changed' => 'Je wachtwoord is succesvol gewijzigd.',

    // Profile page
    'profile' => 'Profiel',
    'player' => 'Spelers',
    'offline' => 'Offline',
    'online' => 'Online',
    'pf_registered' => 'Geregistreerd:',
    'pf_posts' => 'Berichten:',
    'pf_reputation' => 'Reputatie:',
    'user_hasnt_registered' => 'Deze gebruiker heeft nog niet geregistreerd op onze website',
    'user_no_friends' => 'Deze gebruiker heeft geen vrienden toegevoegd',
    'send_message' => 'Verstuur Bericht',
    'remove_friend' => 'Verwijder Vriend',
    'add_friend' => 'Vriend Toevoegen',
    'last_online' => 'Laatst Online:',
    'find_a_user' => 'Zoek een gebruiker.',
    'user_not_following' => 'Deze gebruiker volgt niemand.',
    'user_no_followers' => 'Deze gebruiker heeft geen volgers.',
    'following' => 'Volgend',
    'followers' => 'Volgers',
    'display_location' => 'Komt uit {x}.', // Don't replace {x}, which will be the user's location
    'display_age_and_location' => '{x} jaar oud en komt uit {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
    'write_on_user_profile' => 'Plaats iets op {x}\'s profiel...', // Don't replace {x}
    'write_on_own_profile' => 'Plaats iets op je profiel...',
    'profile_posts' => 'Profiel Berichten.',
    'no_profile_posts' => 'Er zijn nog geen profiel berichten.',
    'invalid_wall_post' => 'Ongeldig profiel bericht. Zorg ervoor dat je bericht tussen de 2 en 2048 tekens is.',
    'about' => 'Over',
    'reply' => 'Antwoord',
    'x_likes' => '{x} vind-ik-leuks', // Don't replace {x}
    'likes' => 'vind-ik-leuks',
    'no_likes' => 'Geen vind-ik-leuks.',
    'post_liked' => 'Bericht vind ik leuk.',
    'post_unliked' => 'Bericht vind ik niet meer leuk.',
    'no_posts' => 'Geen berichten.',
    'last_5_posts' => 'Recentste 5 berichten',
    'follow' => 'Volg',
    'unfollow' => 'Ontvolgen',
    'name_history' => 'Naamgeschiedenis',
    'changed_name_to' => 'Veranderd naar: {x} op {y}', // Don't replace {x} or {y}
    'original_name' => 'Originele naam:',
    'name_history_error' => 'Het systeem kan geen naamverandering geschiedenis vinden.',

    // Staff applications
    'staff_application' => 'Solliciteren voor staff',
    'application_submitted' => 'Sollicitatie is verzonden.',
    'application_already_submitted' => 'Je hebt al een sollicitatie verzonden. Je moet wachten tot er antwoord is op je sollicitatie voordat je een nieuwe kunt aanmaken.',
    'not_logged_in' => 'Je moet inloggen om deze pagina te bekijken.',
    'application_accepted' => 'Je staff aanvraag is geaccepteerd, gefeliciteerd!',
    'application_rejected' => 'Je staff aanvraag is afgewezen.'
);

/*
 *  Moderation related
 */
$mod_language = array(
    'mod_cp' => 'ModCP',
    'overview' => 'Overzicht',
    'reports' => 'Rapporten',
    'punishments' => 'Straffen',
    'staff_applications' => 'Solliciteren voor staff',

    // Punishments
    'ban' => 'Ban',
    'unban' => 'Unban',
    'warn' => 'Waarschuwen',
    'search_for_a_user' => 'Zoek een speler',
    'user' => 'Gebruiker:',
    'ip_lookup' => 'IP Opzoeken:',
    'registered' => 'Geregistreerd',
    'reason' => 'Reden:',
    'cant_ban_root_user' => 'Je kan de administrator niet straffen!',
    'invalid_reason' => 'Vul een goede reden in tussen de 2 en 256 tekens lang.',
    'punished_successfully' => 'Straf succesvol toegevoegd.',

    // Reports
    'report_closed' => 'Rapport gesloten.',
    'new_comment' => 'Nieuw commentaar',
    'comments' => 'Commentaar',
    'only_viewed_by_staff' => 'Kan alleen worden bekeken door staff',
    'reported_by' => 'Gerapporteerd door',
    'close_issue' => 'Sluit rapport',
    'report' => 'Report:',
    'view_reported_content' => 'Bekijk gemelde inhoud',
    'no_open_reports' => 'Geen open rapporten',
    'user_reported' => 'Speler gerapporteerd',
    'type' => 'Type',
    'updated_by' => 'Bijgewerkt door',
    'forum_post' => 'Forum post',
    'user_profile' => 'Gebruikers profiel',
    'comment_added' => 'Commentaar toegevoegd.',
    'new_report_submitted_alert' => 'Nieuw rapport ingediend door {x} over {y}', // Don't replace "{x}" or "{y}"
    'ingame_report' => 'In-game rapport',

    // Staff applications
    'comment_error' => 'Zorg ervoor dat uw commentaar tussen de 2 en 2048 tekens lang is.',
    'viewing_open_applications' => 'Bekijk <span class="label label-info">open</span> applicaties. Verander naar <a href="/mod/applications/?view=accepted"><span class="label label-success">Geaccepteerde</span></a> of <a href="/mod/applications/?view=declined"><span class="label label-danger">Afgewezen</span></a>.',
    'viewing_accepted_applications' => 'Bekijk <span class="label label-success">geaccepteerde</span> applications. Verander naar <a href="/mod/applications/"><span class="label label-info">open</span></a> of <a href="/mod/applications/?view=declined"><span class="label label-danger">Afgewezen</span></a>.',
    'viewing_declined_applications' => 'Bekijk <span class="label label-danger">afgewezen</span> applications. Verander naar <a href="/mod/applications/"><span class="label label-info">open</span></a> of <a href="/mod/applications/?view=accepted"><span class="label label-success">Geaccepteerde</span></a>.',
    'time_applied' => 'Tijd Toegepast',
    'no_applications' => 'Geen applicaties in deze categorie',
    'viewing_app_from' => 'Bekijk applicatie van {x}', // Don't replace "{x}"
    'open' => 'Open',
    'accepted' => 'Geaccepteerd',
    'declined' => 'Afgewezen',
    'accept' => 'Accepteer',
    'decline' => 'Afwijzen',
    'new_app_submitted_alert' => 'Nieuwe applicatie ingediend door {x}' // Don't replace "{x}"
);

/*
 *  General
 */
$general_language = array(
    // Homepage
    'news' => 'Nieuws',
    'social' => 'Sociaal',
    'join' => 'Join',

    // General terms
    'submit' => 'Verstuur',
    'close' => 'Sluiten',
    'cookie_message' => '<strong>Deze website maakt gebruik van cookies om je ervaring te verbeteren.</strong><p>Door op deze website te blijven accepteer je deze.</p>',
    'theme_not_exist' => 'Het geselecteerde thema bestaat niet (meer).',
    'confirm' => 'Bevestigen',
    'cancel' => 'Annuleer',
    'guest' => 'Gast',
    'guests' => 'Gasten',
    'back' => 'Terug',
    'search' => 'Zoeken',
    'help' => 'Help',
    'success' => 'Succes',
    'error' => 'Fout',
    'view' => 'Bekijk',
    'info' => 'Info',
    'next' => 'Volgende',

    // Play page
    'connect_with' => 'Server IP: <b>{x}<b>', // Don't replace {x}
    'online' => 'Online',
    'offline' => 'Offline',
    'status' => 'Status:',
    'players_online' => 'Spelers Online:',
    'queried_in' => 'Opgevraagd In:',
    'server_status' => 'Server Status',
    'no_players_online' => 'Er zijn geen spelers online!',
	'1_player_online' => 'Er is 1 speler online.',
    'x_players_online' => 'Er zijn {x} spelers online.', // Don't replace {x}

    // Other
    'page_loaded_in' => 'Pagina geladen in {x} seconden', // Don't replace {x}; 's' stands for 'seconds'
    'none' => 'Geen',
    '404' => 'Sorry, we kunnen de pagina niet vinden die je probeert te bezoeken. Controleer de URL en probeer het opnieuw, als het nog niet lukt neem dan contact op met de Administrator'
);

/*
 *  Forum
 */
$forum_language = array(
    // Latest discussions view
    'forums' => 'Forums',
    'discussion' => 'Discussie',
    'stats' => 'Stats',
    'last_reply' => 'Recentste antwoord.',
    'ago' => 'geleden',
    'by' => 'door',
    'in' => 'in',
    'views' => 'bekeken',
    'posts' => 'berichten',
    'topics' => 'onderwerpen',
    'topic' => 'onderwerp',
    'statistics' => 'Statistieken',
    'overview' => 'Overzicht',
    'latest_discussions' => 'Nieuwste Discussies',
    'latest_posts' => 'Laatste berichten',
    'users_registered' => 'Gebruikers Geregistreerd:',
    'latest_member' => 'Nieuwste Gebruiker:',
    'forum' => 'Forum',
    'last_post' => 'Laatste bericht',
    'no_topics' => 'Nog geen onderwerpen hier',
    'new_topic' => 'Nieuw onderwerp',
    'subforums' => 'Subforums:',

    // View topic view
    'home' => 'Home',
    'topic_locked' => 'Onderwerp gesloten',
    'new_reply' => 'Nieuw antwoord',
    'mod_actions' => 'Mod acties',
    'lock_thread' => 'Sluit onderwerp',
    'unlock_thread' => 'Ontgrendel onderwerp',
    'merge_thread' => 'Voeg een onderwerp samen',
    'delete_thread' => 'Verwijder onderwerp',
    'confirm_thread_deletion' => 'Weet je zeker dat je dit onderwerp wilt verwijderen?',
    'move_thread' => 'Verplaats onderwerp',
    'sticky_thread' => 'Sticky onderwerp',
    'report_post' => 'Rapporteer bericht',
    'quote_post' => 'Citeer bericht',
    'delete_post' => 'Verwijder bericht',
    'edit_post' => 'Bewerk bericht',
    'reputation' => 'reputatie',
    'confirm_post_deletion' => 'Weet je zeker dat je dit bericht wilt verwijderen?',
    'give_reputation' => 'Geef Reputatie',
    'remove_reputation' => 'Verwijder Reputatie',
    'post_reputation' => 'Post Reputatie',
    'no_reputation' => 'Er zijn nog geen reputaties voor deze post',
    're' => 'RE:',

    // Create post view
    'create_post' => 'Maak bericht',
    'post_submitted' => 'Bericht  geplaatst',
    'creating_post_in' => 'Maak bericht in: ',
    'topic_locked_permission_post' => 'Dit onderwerp is gesloten, maar je kan nog een bericht plaatsen',

    // Edit post view
    'editing_post' => 'Bericht bewerken',

    // Sticky threads
    'thread_is_' => 'Onderwerp is ',
    'now_sticky' => 'Is nu een vastgepind onderwerp',
    'no_longer_sticky' => 'Is niet langer meer een vastgepind onderwerp',

    // Create topic
    'topic_created' => 'Onderwerp gemaakt.',
    'creating_topic_in_' => 'Maak onderwerp in forum ',
    'thread_title' => 'Onderwerp Titel',
    'confirm_cancellation' => 'Weet je het zeker?',
    'label' => 'Label',

    // Reports
    'report_submitted' => 'Rapport ingediend.',
    'view_post_content' => 'Bekijk bericht inhoud',
    'report_reason' => 'Rapport Reden',

    // Move thread
    'move_to' => 'Verplaats naar:',

    // Merge threads
    'merge_instructions' => 'Het onderwerp <strong>moet</strong> binnen hetzelfde forum zijn. Het verplaatsen van het onderwerp is nodig.',
    'merge_with' => 'Samenvoegen met:',

    // Other
    'forum_error' => 'Sorry, we konden dat forum of onderwerp niet vinden.',
    'are_you_logged_in' => 'Ben je ingelogd?',
    'online_users' => 'Gebruikers Online',
    'no_users_online' => 'Er zijn geen gebruikers online.',

    // Search
    'search_error' => 'Voer een zoekopdracht in die tussen 1 en 32 tekens lang is.',
    'no_search_results' => 'Je zoekopdracht heeft niks opgeleverd.',

    //Share on a social-media.
   'sm-share' => 'Delen',
   'sm-share-facebook' => 'Deel op Facebook',
   'sm-share-twitter' => 'Deel op Twitter',
);

/*
 *  Emails
 */
$email_language = array(
    // Registration email
    'greeting' => 'Beste Gebruiker',
    'message' => 'Bedankt voor het registreren! Om de registratie te voltooien, klik je op de volgende link:',
    'thanks' => 'Bedankt,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
    'seconds_short' => 's', // Shortened "seconds", eg "s"
    'less_than_a_minute' => 'Minder dan een minuut geleden',
    '1_minute' => '1 minuut geleden',
    '_minutes' => '{x} minuten geleden',
    'about_1_hour' => 'ongeveer 1 uur geleden',
    '_hours' => '{x} uren geleden',
    '1_day' => '1 dag geleden',
    '_days' => '{x} dagen geleden',
    'about_1_month' => 'ongeveer 1 maand geleden',
    '_months' => '{x} maanden geleden',
    'about_1_year' => 'ongeveer 1 jaar geleden',
    'over_x_years' => '{x} jaren geleden'
);

/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
    'display_records_per_page' => 'Geef _MENU_ rijen weer per pagina', // Don't replace "_MENU_"
    'nothing_found' => 'Geen resultaat gevonden',
    'page_x_of_y' => 'pagina _PAGE_ / _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
    'no_records' => 'Geen gegevens beschikbaar',
    'filtered' => '(gefilterd van de _MAX_ gegevens)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Registratie voltooien'
);

?>
