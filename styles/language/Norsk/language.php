<?php
/*
 *	Translation created by Partydragen and @tlystad24
 *  http://partydragen.com/
    http://tlystad24.github.io
 *
 *  License: MIT
 */

/*
 *  Norwegian Language
 */

/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'infractions' => 'brudd',
	'invalid_token' => 'Ugyldig token, prøv igjen.',
	'invalid_action' => 'Ugyldig handling',
	'successfully_updated' => 'Vellykket oppdatering',
	'settings' => 'Innstillinger',
	'confirm_action' => 'Bekreft handling',
	'edit' => 'Redigere',
	'actions' => 'Handlinger',
	'task_successful' => 'kjørte oppgaven med hell',

	// Admin login
	're-authenticate' => 'Vennligst re-godkjenne',

	// Admin sidebar
	'index' => 'Oversikt',
	'announcements' => 'Annonser',
	'core' => 'Kjerne',
	'custom_pages' => 'Custom Sider',
	'general' => 'General',
	'forums' => 'Forums',
	'users_and_groups' => 'Brukere og grupper',
	'minecraft' => 'Minecraft',
	'style' => 'Utseende',
	'addons' => 'Addons',
	'update' => 'Oppdater',
	'misc' => 'Diverse',
	'help' => 'Hjelp',

	// Admin index page
	'statistics' => 'Statistikk',
	'registrations_per_day' => 'Registreringer per dag (siste 7 dager)',

	// Admin announcements page
	'current_announcements' => 'Nåværende Annonser',
	'create_announcement' => 'opprett Annonse',
	'announcement_content' => 'Annonseinnhold',
	'announcement_location' => 'Annonseplassering',
	'announcement_can_close' => 'Kan lukke annonse?',
	'announcement_permissions' => 'Annonsetillatelser',
	'no_announcements' => 'Ingen annonser opprettet ennå.',
	'confirm_cancel_announcement' => 'Er du sikker på at du vil avbryte denne annonsen?',
	'announcement_location_help' => 'Ctrl-klikk for å velge flere sider',
	'select_all' => 'Velg alle',
	'deselect_all' => 'Opphev alle',
	'announcement_created' => 'Annonse opprettet',
	'please_input_announcement_content' => 'Vennligst skriv annonseinnhold og velge en type',
	'confirm_delete_announcement' => 'Er du sikker på at du vil slette denne annonsen?',
	'announcement_actions' => 'Annonseauksjoner',
	'announcement_deleted' => 'Annonse velykket slettet',
	'announcement_type' => 'Annonsetype',
	'can_view_announcement' => 'Kan vise Annonse?',

	// Admin core page
	'general_settings' => 'Generelle innstillinger',
	'modules' => 'Moduler',
	'module_not_exist' => 'Denne modulen eksisterer ikke!',
	'module_enabled' => 'Modul aktivert.',
	'module_disabled' => 'Modul deaktivert',
	'site_name' => 'Sidenavn',
	'language' => 'Språk',
	'voice_server_not_writable' => 'core/voice_server.php er ikke skrivbar. Vennligst sjekk filrettigheter',
	'email' => 'EPost',
	'incoming_email' => 'Innkommende e-postadresse',
	'outgoing_email' => 'Utgående e-postadresse',
	'outgoing_email_help' => 'Kun nødvendig dersom PHP-postfunksjonen er aktivert',
	'use_php_mail' => 'Bruk PHP mail () funksjonen?',
	'use_php_mail_help' => 'Anbefalt: aktivert. Hvis nettstedet ditt ikke er å sende e-post, må du deaktivere dette og redigere core/email.php med dine e-postinnstillingene.',
	'use_gmail' => 'Bruk Gmail for epost-sending?',
	'use_gmail_help' => 'Bare tilgjengelig hvis PHP-postfunksjonen er deaktivert. Hvis du velger å ikke bruke Gmail, vil SMTP brukes. Uansett må dette konfigurere i core/email.php.',
	'enable_mail_verification' => 'Aktiver e-postkonto verifisering?',
	'enable_email_verification_help' => 'Å ha denne aktivert vil be nyregistrerte brukere å verifisere sin konto via e-post før brukeren er fullført registreringen.',
	'explain_email_settings' => 'Følgende er nødvendig hvis "Bruk PHP mail () -funksjonen" alternativet er <strong>deaktivert</strong>. Du kan finne dokumentasjon på disse innstillingene <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">på vår wiki</a>.',
	'email_config_not_writable' => '<strong>core/email.php</strong> Filen er ikke skrivbar. Vennligst sjekk filrettigheter.',
	'pages' => 'Sider',
	'enable_or_disable_pages' => 'Aktivere eller deaktivere sider her.',
	'enable' => 'Aktiver',
	'disable' => 'Deaktiver',
	'maintenance_mode' => 'Forum vedlikeholdsmodus',
	'forum_in_maintenance' => 'Forumet er i vedlikeholdsmodus.',
	'unable_to_update_settings' => 'Kan ikke oppdatere innstillinger. Vennligst sikre at ingen felt er tomme.',
	'editing_google_analytics_module' => 'Redigerer Google Analytics-modul',
	'tracking_code' => 'Sporingskode',
	'tracking_code_help' => 'Sett inn sporingskoden for Google Analytics her, inkludert de omliggende skriptkodene .',
	'google_analytics_help' => 'Se <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank"> denne veiledningen </a> for mer informasjon, følge trinn 1 til 3.',
	'social_media_links' => 'Linker til sosiale medier',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Bruk mørkt Twitter tema?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registrering',
	'registration_warning' => 'Dersom denne modulen er deaktivert, deaktiveres registreringssiden seg på nettstedet.',
	'google_recaptcha' => 'Aktiver Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Side nøkkel',
	'recaptcha_secret_key' => 'reCAPTCHA Hemmelig nøkkel',
	'registration_terms_and_conditions' => 'Registreringsbetingelser',
	'voice_server_module' => 'Voice Server Modul',
	'only_works_with_teamspeak' => 'Denne modulen fungerer foreløpig bare med Teamspeak',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Fyll inn detaljene for ServerQuery-brukeren',
	'ip_without_port' => 'IP (uten port)',
	'voice_server_port' => 'Port (vanligvis 10 011)',
	'virtual_port' => 'Virtual Port (vanligvis 9987)',
	'permissions' => 'Tillatelser:',
	'view_applications' => 'Se søknader?',
	'accept_reject_applications' => 'Aksepter / avslå søknader?',
	'questions' => 'Spørsmåler:',
	'question' => 'Spørsmål',
	'type' => 'Type',
	'options' => 'Alternativer',
	'options_help' => 'Hvert alternativ på en ny linje; kan stå tomme (Fall ned bare)',
	'no_questions' => 'Ingen spørsmål er lagt til enda.',
	'new_question' => 'Nytt spørsmål',
	'editing_question' => 'Redigerer Spørsmål',
	'delete_question' => 'Slett Spørsmål',
	'dropdown' => 'Flervalgsmeny',
	'text' => 'Tekst',
	'textarea' => 'Tekstfelt',
	'question_deleted' => 'Spørsmål slettet',
	'name_required' => 'Name is required.',
	'question_required' => 'Question is required.',
	'name_minimum' => 'Name must be a minimum of 2 characters.',
	'question_minimum' => 'Question must be a minimum of 2 characters.',
	'name_maximum' => 'Name must be a maximum of 16 characters.',
	'question_maximum' => 'Question must be a maximum of 16 characters.',
	'use_followers' => 'Bruk følgere?',
	'use_followers_help' => 'Hvis deaktivert, vil venner systemet brukes.',

	// Admin custom pages page
	'click_on_page_to_edit' => 'Klikk på en side for å redigere den.',
	'page' => 'Side:',
	'url' => 'URL:',
	'page_url' => 'Side URL',
	'page_url_example' => '(Med foregående "/", for eksempel /hjelp/)',
	'page_title' => 'Sidetittel',
	'page_content' => 'Sideinnhold',
	'new_page' => 'Ny Side',
	'page_successfully_created' => 'Side opprettet',
	'page_successfully_edited' => 'Siden er redigert',
	'unable_to_create_page' => 'Kan ikke opprette siden.',
	'unable_to_edit_page' => 'Kan ikke redigere siden.',
	'create_page_error' => 'Kontroller at du har skrevet inn en nettadresse mellom 1 og 20 tegn langt, en sidetittel mellom 1 og 30 tegn langt, og sideinnhold mellom 5 og 20480 tegn.',
	'delete_page' => 'Slett Siden',
	'confirm_delete_page' => 'Er du sikker på at du vil slette denne siden?',
	'page_deleted_successfully' => 'Side slettet',
	'page_link_location' => 'Vis siden link i:',
	'page_link_navbar' => 'Navbar',
	'page_link_more' => 'Navbar "Mer" dropdown',
	'page_link_footer' => 'Side footer',
	'page_link_none' => 'Ingen lenke',
	'page_permissions' => 'Sidetilatelser',
	'can_view_page' => 'Kan vise side:',
	'redirect_page' => 'Omdirigeringsside?',
	'redirect_link' => 'Omdirigeringslenke',
	'page_icon' => 'Sideikon',

	// Admin forum page
	'labels' => 'Emneetiketter',
	'new_label' => 'Ny etikett',
	'no_labels_defined' => 'Ingen etiketter definert',
	'label_name' => 'Etikettnavn',
	'label_type' => 'Etikettype',
	'label_forums' => 'Etikett Forums',
	'label_creation_error' => 'Feil ved oppretting av etikett. Sørg for at navnet ikke er lengre enn 32 tegn, og at du har angitt en type.',
	'confirm_label_deletion' => 'Er du sikker på at du vil slette denne etiketten?',
	'editing_label' => 'Redigerer etikett',
	'label_creation_success' => 'Etikett opprettet',
	'label_edit_success' => 'Etikett er redigert',
	'label_default' => 'Default',
	'label_primary' => 'Primary',
	'label_success' => 'Suksess',
	'label_info' => 'Info',
	'label_warning' => 'Advarsel',
	'label_danger' => 'Fare',
	'new_forum' => 'Nytt forum',
	'forum_layout' => 'Forumoppsett',
	'table_view' => 'Tabellvisning',
	'latest_discussions_view' => 'Siste Diskusjoner Visning',
	'create_forum' => 'Opprett Forum',
	'forum_name' => 'Forumnavn',
	'forum_description' => 'Forumbeskrivelse',
	'delete_forum' => 'Slett Forum',
	'move_topics_and_posts_to' => 'Flytt emner og innlegg til',
	'delete_topics_and_posts' => 'Slett emner og innlegg',
	'parent_forum' => 'Overforum',
	'has_no_parent' => 'Har ingen kategori',
	'forum_permissions' => 'Forumtillatelser',
	'can_view_forum' => 'Kan vise forum',
	'can_create_topic' => 'Kan lage emne',
	'can_post_reply' => 'Kan legge inn svar',
	'display_threads_as_news' => 'Vis tråder som nyheter på forsiden?',
	'input_forum_title' => 'Skriv inn forumtittel',
	'input_forum_description' => 'Skriv inn forumbeskrivelse.',
	'forum_name_minimum' => 'Forumnavnet må være minst 2 tegn.',
	'forum_description_minimum' => 'Forumbeskrivelsen må være minst 2 tegn.',
	'forum_name_maximum' => 'Forumnavnet må være maksimalt 150 tegn.',
	'forum_description_maximum' => 'Forumbeskrivelsen må være maksimalt 255 tegn',
	'forum_type_forum' => 'Diskusjon Forum',
	'forum_type_category' => 'Kategori',

	// Admin Users and Groups page
	'users' => 'Brukere',
	'new_user' => 'Ny Bruker',
	'created' => 'Laget',
	'user_deleted' => 'Bruker slettet',
	'validate_user' => 'Valider Bruker',
	'update_uuid' => 'Oppdater UUID',
	'unable_to_update_uuid' => 'Kan ikke oppdatere UUID.',
	'update_mc_name' => 'Oppdater Minecraft navn',
	'reset_password' => 'Tilbakestill passord',
	'punish_user' => 'Straffe Bruker',
	'delete_user' => 'Slett bruker',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Adresse',
	'ip' => 'IP:',
	'other_actions' => 'Andre handlinger:',
	'disable_avatar' => 'Deaktiver avatar',
	'enable_avatar' => 'Aktiver avatar',
	'confirm_user_deletion' => 'Er du sikker på at du vil slette brukeren {x}?', // Don't replace "{x}"
	'groups' => 'Grupper',
	'group' => 'Gruppe',
	'group2' => 'Gruppe 2',
	'new_group' => 'Ny Gruppe',
	'id' => 'ID',
	'name' => 'Navn',
	'create_group' => 'Opprett Gruppe',
	'group_name' => 'Gruppenavn',
	'group_html' => 'Gruppe HTML',
	'group_html_lg' => 'Gruppe HTML Stor',
	'donor_group_id' => 'Donor pakke ID',
	'donor_group_id_help' => '<p>Dette er ID-gruppen\'s pakke fra Buycraft, Minecraft Market eller MCStock.</p><p>Dette kan stå tomt.</p>',
	'donor_group_instructions' => 	'<p>Donor grupper må opprettes i størrelsesorden <strong>laveste verdi til høyeste verdi</strong>.</p>
									<p>For eksempel vil en £10 pakke opprettes før en £20 pakke.</p>',
	'delete_group' => 'Slett gruppe',
	'confirm_group_deletion' => 'Er du sikker på at du vil slette gruppen {x}?', // Don't replace "{x}"
	'group_staff' => 'Er gruppen en stab gruppe?',
	'group_modcp' => 'Kan gruppen se ModCP?',
	'group_admincp' => 'Kan gruppen se AdminCP?',
	'group_name_required' => 'Du må sette inn et gruppenavn.',
	'group_name_minimum' => 'Gruppenavnet må være minst 2 tegn.',
	'group_name_maximum' => 'Gruppenavnet må være maksimalt 20 tegn.',
	'html_maximum' => 'Gruppen HTML skal være maksimalt 1024 tegn.',
	'select_user_group' => 'Brukeren må være i en gruppe.',
	'uuid_max_32' => 'UUID må være maksimalt 32 tegn.',
	'cant_delete_root_user' => 'Kan ikke slette root brukeren!',
	'cant_modify_root_user' => 'Kan ikke endre root brukerenen\'s gruppe.',

	// Admin Minecraft page
	'minecraft_settings' => 'Minecraftinnstillinger',
	'use_plugin' => 'Bruk Nameless Minecraft plugin?',
	'force_avatars' => 'Force Minecraft avatarer?',
	'uuid_linking' => 'Aktiver UUID linking?',
	'use_plugin_help' => 'Aktivering av API og server plugin, tillater synkronisering av rank, ingame registreting og rapporter.',
	'uuid_linking_help' => 'Dersom deaktivert, vil brukere ikke kobled med UUID. Det anbefales strekt at du holder dette aktivert.',
	'plugin_settings' => 'Plugininnstillinger',
	'confirm_api_regen' => 'Er du sikker på at du vil generere en ny API-nøkkel?',
	'servers' => 'Servere',
	'new_server' => 'Ny Server',
	'confirm_server_deletion' => 'Er du sikker på at du vil slette denne serveren?',
	'main_server' => 'Hovedserver',
	'main_server_help' => 'Serveren spillerene kobler igjennom. Normalt vil dette være for eksempel Bungee.',
	'choose_a_main_server' => 'Velg en hovedserver..',
	'external_query' => 'Bruk ekstern spørring?',
	'external_query_help' => 'Bruk en ekstern API for å søke i Minecraft server? Bruk dette kun hvis det innebygde søket ikke fungerer; Det er sterkt anbefalt at dette ikke er krysset.',
	'editing_server' => 'Redigerer server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (med port) (numerisk eller domene)',
	'server_ip_with_port_help' => 'Dette er IP-adressen som vil bli synlig for brukere. Denne vil ikke bli spurt.',
	'server_ip_numeric' => 'Server IP (med port) (kun numerisk)',
	'server_ip_numeric_help' => 'Dette er IP-adressen som skal spørres. Pass på at denne er kun numerisk. Det vil ikke bli vist til brukere.',
	'show_on_play_page' => 'Vis på Play side?',
	'pre_17' => 'Pre 1.7 Minecraft versjon?',
	'server_name' => 'Servernavn',
	'invalid_server_id' => 'Ugyldig server-ID',
	'show_players' => 'Vis spillerlisten på Play side?',
	'server_edited' => 'Server redigert',
	'server_created' => 'Server opprettet',
	'query_errors' => 'Query feil',
	'query_errors_info' => 'Følgende feil muliggjør at du kan diagnostisere problemet med den interne serveren spørringen .',
	'no_query_errors' => 'Ingen spørringsfeil logget',
	'date' => 'Dato:',
	'port' => 'Port:',
	'viewing_error' => 'Vis Feil',
	'confirm_error_deletion' => 'Er du sikker på at du vil slette denne feilen?',
	'display_server_status' => 'Vis serverstatusmodul?',
	'server_name_required' => 'Du må sette inn et servernavn .',
	'server_ip_required' => 'Du må sette inn serverens IP.',
	'server_name_minimum' => 'Servernavnet må være minst 2 tegn.',
	'server_ip_minimum' => 'Serveren IP må være minst 2 tegn.',
	'server_name_maximum' => 'Servernavnet må være maksimalt 20 tegn.',
	'server_ip_maximum' => 'Serveren IP må være maksimalt 64 tegn.',
	'purge_errors' => 'Purge feil',
	'confirm_purge_errors' => 'Er du sikker på at du vil slette alle feilmeldingene?',
	'avatar_type' => 'Avatartype',
	'custom_usernames' => 'Tillat kun Minecraftbrukernavn?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Bruk mcassoc?',
	'use_mcassoc_help' => 'mcassoc sikrer at brukerne eier sin Minecraftkonto som de registrerte seg med',
	'mcassoc_key' => 'mcassoc Delt nøkkel',
	'invalid_mcassoc_key' => 'Ugyldig mcassoc-nøkkel.',
	'mcassoc_instance' => 'mcassoc instance',
	'mcassoc_instance_help' => 'Generere en instance kode <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">her</a>',
	'mcassoc_key_help' => 'Få en mcassoc nøkkelen <a href="https://mcassoc.lukegb.com/" target="_blank">her</a>',
	'enable_name_history' => 'Enable profile username history?',

	// Admin Themes, Templates and Addons
	'themes' => 'Temaer',
	'templates' => 'Templates',
	'installed_themes' => 'Installerte temaer',
	'installed_templates' => 'Installerte templates',
	'installed_addons' => 'Installerte addons',
	'install_theme' => 'Installer Theme',
	'install_template' => 'Installer Template',
	'install_addon' => 'Installer Addon',
	'install_a_theme' => 'Installer en tema',
	'install_a_template' => 'Installer en template',
	'install_an_addon' => 'Installer en addon',
	'active' => 'Aktiv',
	'activate' => 'Aktiver',
	'deactivate' => 'Deaktiver',
	'theme_install_instructions' => 'Last opp temaer til <strong>styles/themes</strong> mappen. Deretter klikker du på "scan" knappen nedenfor.',
	'template_install_instructions' => 'Last opp templates til <strong>styles/templates</strong> mappen. Deretter klikker du på "scan" knappen nedenfor.',
	'addon_install_instructions' => 'Last opp addons til <strong>addons</strong> mappen. Deretter klikker du på "scan" knappen nedenfor..',
	'addon_install_warning' => 'Addons er installert på egen risiko. Ta sikkerhetskopi av filene og databasen før du fortsetter',
	'scan' => 'Skan',
	'theme_not_exist' => 'Dette temaet finnes ikke!',
	'template_not_exist' => 'Denne template finnes ikke!',
	'addon_not_exist' => 'Denne addon finnes ikke!',
	'style_scan_complete' => 'Fullført, nye teamer er installert.',
	'addon_scan_complete' => 'Fullført, nye addons er installert.',
	'theme_enabled' => 'Tema aktivert.',
	'template_enabled' => 'Template aktivert.',
	'addon_enabled' => 'Addon aktivert.',
	'theme_deleted' => 'tema slettet.',
	'template_deleted' => 'Template slettet.',
	'addon_disabled' => 'Addon deaktivert.',
	'inverse_navbar' => 'Omvendt Navigasjonsbar',
	'confirm_theme_deletion' => 'Er du sikker på at du vil slette temaet <strong>{x}</strong>?<br /><br />Temaet vil bli slettet fra <strong>styles/themes</strong> mappa.', // Don't replace {x}
	'confirm_template_deletion' => 'Er du sikker på at du vil slette template <strong>{x}</strong>?<br /><br />Template vil bli slettet fra <strong>styles/templates</strong> mappa.', // Don't replace {x}
	'unable_to_enable_addon' => 'Could not enable addon. Please ensure it is a valid NamelessMC addon.',

	// Admin Misc page
	'other_settings' => 'Andre Innstillinger',
	'enable_error_reporting' => 'Aktiver feilrapportering?',
	'error_reporting_description' => 'Dette bør bare brukes for debugging. Det er sterkt anbefalt at dette blir stående som deaktivert.',
	'display_page_load_time' => 'Vis siden\'s lastningstid?',
	'page_load_time_description' => 'Å ha denne aktivert vil vise tiden det tok å laste inn siden i bunnen av siden.',
	'reset_website' => 'Reset Hjemmeside',
	'reset_website_info' => 'Dette vil tilbakestille dine nettstedinnstillinger. <strong>Addons vil bli deaktivert, men ikke fjernet, og deres innstillinger endres ikke.</strong> De angitte Minecraft servere vil også forbli.',
	'confirm_reset_website' => 'Er du sikker på at du vil tilbakestille ditt nettstedinnstillinger?',

	// Admin Update page
	'installation_up_to_date' => 'Installasjonen kjører nyeste versjon.',
	'update_check_error' => 'Kan ikke se etter oppdateringer. Prøv igjen senere.',
	'new_update_available' => 'En ny oppdatering er tilgjengelig.',
	'your_version' => 'Din versjon:',
	'new_version' => 'Ny versjon::',
	'download' => 'Nedlasting',
	'update_warning' => 'Advarsel: Kontroller at du har lastet ned pakken og lastet de medfulgte filene først!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Hjem',
	'play' => 'Spill',
	'forum' => 'Forum',
	'more' => 'Mer',
	'staff_apps' => 'Staff-søknader',
	'view_messages' => 'Vis Meldinger',
	'view_alerts' => 'Vis Varsler',

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
	'create_an_account' => 'Opprett en bruker',
	'authme_password' => 'AuthMe Passord',
	'username' => 'Brukernavn',
	'minecraft_username' => 'Minecraft Brukernavn',
	'email' => 'Epost',
	'user_title' => 'Title',
	'email_address' => 'Epost Adresse',
	'date_of_birth' => 'Fødselsdato',
	'location' => 'Lokasjon',
	'password' => 'Passord',
	'confirm_password' => 'Bekreft Passord',
	'i_agree' => 'Jeg er enig',
	'agree_t_and_c' => 'Ved å klikke på <strong class="label label-primary">Registrer</strong>, samtykker du til våre <a href="#" data-toggle="modal" data-target="#t_and_c_m">vilkår</a>.',
	'register' => 'Registrer',
	'sign_in' => 'Logg inn',
	'sign_out' => 'Logg ut',
	'terms_and_conditions' => 'Vilkår og betingelser',
	'successful_signin' => 'Du har blitt logget inn',
	'incorrect_details' => 'uriktige opplysninger',
	'remember_me' => 'Husk meg',
	'forgot_password' => 'Glemt passord',
	'must_input_username' => 'Du må skrive inn et brukernavn.',
	'must_input_password' => 'Du må skrive inn et passord.',
	'inactive_account' => 'Kontoen din er for øyeblikket inaktiv. Har du bedt om tilbakestilling av passord?',
	'account_banned' => 'Din konto har blitt utestengt.',
	'successfully_logged_out' => 'Du har blitt logget ut.',
	'signature' => 'Signatur',
	'registration_check_email' => 'Vennligst sjekk e-posten din for en valideringskobling. Du vil ikke være i stand til å logge deg på før denne er klikket på.',
	'unknown_login_error' => 'Beklager, det oppstod en ukjent feil mens du prøvde logge deg inn. Vennligst prøv igjen senere.',
	'validation_complete' => 'Takk for at du registrerte deg! Du kan nå logge inn.',
	'validation_error' => 'Feil under behandling av forespørselen. Vennligst prøv å klikke på lenken igjen.',
	'registration_error' => 'Kontroller at du har fylt ut alle feltene, og at brukernavnet ditt er mellom 3 og 20 tegn og at passordet er mellom 6 og 30 tegn.',
	'username_required' => 'Skriv inn et brukernavn.',
	'password_required' => 'Skriv inn et passord.',
	'email_required' => 'Vennligst oppgi en e-postadresse.',
	'mcname_required' => 'Vennligst skriv inn en Minecraft brukernavn.',
	'accept_terms' => 'Du må godta vilkårene før du registrerer deg.',
	'invalid_recaptcha' => 'Ugyldig reCAPTCHA respons.',
	'username_minimum_3' => 'Brukernavnet må være minst 3 tegn.',
	'username_maximum_20' => 'Brukernavnet kan ikke være lengre enn 20 tegn.',
	'mcname_minimum_3' => 'Minecraft brukernavn må være minst 3 tegn.',
	'mcname_maximum_20' => 'Minecraft brukernavn kan ikke være lengre enn 20 tegn.',
	'password_minimum_6' => 'Ditt passord må være minst 6 tegn.',
	'password_maximum_30' => 'Passordet ditt kan ikke være lengre enn 30 tegn.',
	'passwords_dont_match' => 'Passordene er ikke like.',
	'username_mcname_email_exists' => 'Brukernavnet, Minecraft-brukernavnet eller e-postadressen finnes allerede. Har du allerede opprettet en bruker?',
	'invalid_mcname' => 'Minecraft-brukernavnet er ikke en gyldig bruker',
	'mcname_lookup_error' => 'Det oppstod en feil med å kontakte Mojang\'s servere. Prøv igjen senere.',
	'signature_maximum_900' => 'Din underskrift kan ikke være lengre enn 900 tegn.',
	'invalid_date_of_birth' => 'Ugyldig fødselsdato.',
	'location_required' => 'Vennligst skriv inn en lokasjon.',
	'location_minimum_2' => 'Din lokasjon må være minst 2 tegn.',
	'location_maximum_128' => 'Din lokasjon kan ikke være lengre enn 128 tegn.',
	'verify_account' => 'Bekreft kontoen',
	'verify_account_help' => 'Følg instruksjonene under slik at vi kan bekrefte at du eier den aktuelle Minecraftkontoen.',
	'verification_failed' => 'Verifisering mislyktes, vennligst prøv igjen.',
	'verification_success' => 'Vellykket verifisering! Du kan nå logge inn.',
	'complete_signup' => 'Complete Signup',
	'registration_disabled' => 'Registrering på nettstedet er for tiden deaktivert.',

	// UserCP
	'user_cp' => 'BrukerCP',
	'no_file_chosen' => 'Ingen fil valgt',
	'private_messages' => 'Private Meldinger',
	'profile_settings' => 'Profilinnstillinger',
	'your_profile' => 'Din Profil',
	'topics' => 'Emner',
	'posts' => 'Innlegg',
	'reputation' => 'Rykte',
	'friends' => 'Venner',
	'alerts' => 'Alerts',

	// Messaging
	'new_message' => 'Ny Melding',
	'no_messages' => 'Ingen Meldinger',
	'and_x_more' => 'Og {x} Mer', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Beskjed Tittel',
	'message' => 'Beskjed',
	'to' => 'Til:',
	'separate_users_with_comma' => 'Separer brukere med komma (",")',
	'viewing_message' => 'Vis Medling',
	'delete_message' => 'Slett Medling',
	'confirm_message_deletion' => 'Er du sikker på at du vil slette denne meldingen?',

	// Profile settings
	'display_name' => 'Vis Navn',
	'upload_an_avatar' => 'Last opp en avatar (.jpg, .png or .gif only):',
	'use_gravatar' => 'Bruk Gravatar?',
	'change_password' => 'Bytt passord',
	'current_password' => 'Nåværende passord',
	'new_password' => 'Nytt passord',
	'repeat_new_password' => 'Gjenta nytt passord',
	'password_changed_successfully' => 'Passordet er endret',
	'incorrect_password' => 'Din nåværende passord er feil',
	'update_minecraft_name_help' => 'Dette vil oppdatere ditt brukernavn på nettstedet til ditt nåværende Minecraftbrukernavn. Du kan bare utføre denne handlingen én gang hver 30. dag.',
	'unable_to_update_mcname' => 'Kan ikke oppdatere Minecraft brukernavn.',
	'display_age_on_profile' => 'Vis alder på profilen?',
	'two_factor_authentication' => 'To-trinns autentisering',
	'enable_tfa' => 'Aktiver to-trinns autentisering',
	'tfa_type' => 'to-trinns autentisering type:',
	'authenticator_app' => 'Autentiserings-App',
	'tfa_scan_code' => 'Vennligst skanne følgende kode i autentiseringsappen:',
	'tfa_code' => 'Hvis enheten ikke har et kamera, eller du ikke er i stand til å skanne QR-koden, legg inn følgende kode:',
	'tfa_enter_code' => 'Skriv inn koden som viser innenfor autentiseringsappen:',
	'invalid_tfa' => 'Ugyldig kode, prøv igjen.',
	'tfa_successful' => 'To faktor autentisering oppsett med hell. Du må godkjenne hver gang du logger deg på fra nå av.',
	'confirm_tfa_disable' => 'Er du sikker på at du ønsker å deaktivere to faktor autentisering?',
	'tfa_disabled' => 'To-trinns autentisering er deaktivert.',
	'tfa_enter_email_code' => 'Vi har sendt deg en kode i en e-post for bekreftelse. Vennligst skriv inn koden nå:',
	'tfa_email_contents' => 'Et påloggingsforsøk har blitt gjort til din konto. Hvis dette var deg, legg inn følgende to-trinn autentisering-kode når du blir bedt om å gjøre det. Hvis dette ikke var deg, kan du se bort ifra denne e-posten, men en tilbakestilling av passord er anbefalt. Koden er kun gyldig i 10 minutter.',

	// Alerts
	'viewing_unread_alerts' => 'Viser uleste varsler. Endre til <a href="/user/alerts/?view=read"><span class="label label-success">lest</span></a>.',
	'viewing_read_alerts' => 'Viser leste varslene. Endre til <a href="/user/alerts/"><span class="label label-warning">ulest</span></a>.',
	'no_unread_alerts' => 'Du har ingen uleste varsler.',
	'no_alerts' => 'Du har ingen varsler',
	'no_read_alerts' => 'Du har ingen leste varsler.',
	'view' => 'Vis',
	'alert' => 'Varsle',
	'when' => 'Når',
	'delete' => 'Slett',
	'tag' => 'Bruker-Tag',
	'tagged_in_post' => 'Du har blitt tagget i et innlegg',
	'report' => 'Rapporter',
	'deleted_alert' => 'varsel slettet',

	// Warnings
	'you_have_received_a_warning' => 'Du har mottat en advarsel fra {x} den {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Bekreft',

	// Forgot password
	'password_reset' => 'Reset passord',
	'email_body' => 'Du mottar denne e-posten fordi du har bedt om en tilbakestilling av passord. For å tilbakestille passordet ditt, kan du bruke følgende link:', // Body for the password reset email
	'email_body_2' => 'Hvis du ikke har bedt om tilbakestilling av passord, kan du se bort fra denne e-posten.',
	'password_email_set' => 'Suksess. Vennligst sjekk e-posten for videre instruksjoner.',
	'username_not_found' => 'Dette brukernavnet finnes ikke.',
	'change_password' => 'Bytt passord',
	'your_password_has_been_changed' => 'Ditt passord har blitt endret.',

	// Profile page
	'profile' => 'Profil',
	'player' => 'Spiller',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registrert:',
	'pf_posts' => 'Innlegg:',
	'pf_reputation' => 'Rykte:',
	'user_hasnt_registered' => 'Denne brukeren har ikke registrert seg på vår hjemmeside ennå',
	'user_no_friends' => 'Denne brukeren har ikke lagt til noen venner',
	'send_message' => 'Send Melding',
	'remove_friend' => 'Fjern Venn',
	'add_friend' => 'Legge til venn',
	'last_online' => 'Sist pålogget:',
	'find_a_user' => 'Finn en bruker',
	'user_not_following' => 'Denne brukeren følger ikke noen.',
	'user_no_followers' => 'Denne brukeren har ingen følgere.',
	'following' => 'Følger',
	'followers' => 'Følgere',
	'display_location' => 'Fra {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, fra {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Skriv noe på {x}\'s profil...', // Don't replace {x}
	'write_on_own_profile' => 'Skriv noe på profilen din...',
	'profile_posts' => 'Profil innlegg',
	'no_profile_posts' => 'Ingen profil innlegg ennå.',
	'invalid_wall_post' => 'Ugyldig vegginnlegg. Sørg for innlegget ditt er mellom 2 og 2048 tegn.',
	'about' => 'Om',
	'reply' => 'Svar',
	'x_likes' => '{x} Liker', // Don't replace {x}
	'likes' => 'Liker',
	'no_likes' => 'Ingen liker.',
	'post_liked' => 'Innlegg likt.',
	'post_unliked' => 'Innlegg unliked.',
	'no_posts' => 'Ingen innlegg.',
	'last_5_posts' => 'Siste 5 innlegg',
	'follow' => 'Følg',
	'unfollow' => 'Slutt å flkge',
	'name_history' => 'Navnehistorikk',
 	'changed_name_to' => 'Endret navn til: {x} på {y}', // Don't replace {x} or {y}
 	'original_name' => 'Opprinnelig navn:',
	'name_history_error' => 'Kan ikke hente brukernavnshistorikk.',

	// Staff applications
	'staff_application' => 'Staff Søknad',
	'application_submitted' => 'Søknad sendt.',
	'application_already_submitted' => 'Du har allerede sendt inn en søknad. Vent til den er ferdig før du sender en annen.',
	'not_logged_in' => 'Vennligst logg inn for å se denne siden.',
	'application_accepted' => 'Din søknad er godkjent.',
	'application_rejected' => 'Din søknad er avslått.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Oversikt',
	'reports' => 'Rapporter',
	'punishments' => 'Straff',
	'staff_applications' => 'Staff Søknader',

	// Punishments
	'ban' => 'Utesteng',
	'unban' => 'Opphev utestenging',
	'warn' => 'Varsle',
	'search_for_a_user' => 'Søk etter en bruker',
	'user' => 'Bruker:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registrert',
	'reason' => 'Grunn:',
	'cant_ban_root_user' => 'Kan ikke straffe root bruker!',
	'invalid_reason' => 'Vennligst skriv inn en gyldig grunn mellom 2 og 256 tegn.',
	'punished_successfully' => 'Straff lagt til.',

	// Reports
	'report_closed' => 'Rapporter lukket.',
	'new_comment' => 'Ny kommentar',
	'comments' => 'Kommentarer',
	'only_viewed_by_staff' => 'Kan bare bli sett av ansatte',
	'reported_by' => 'Rapportert av',
	'close_issue' => 'Lukk saken',
	'report' => 'Rapportere:',
	'view_reported_content' => 'Vis rapportert innhold',
	'no_open_reports' => 'Ingen åpne rapporter',
	'user_reported' => 'Bruker rapportert',
	'type' => 'Type',
	'updated_by' => 'Oppdatert av',
	'forum_post' => 'Foruminnlegg',
	'user_profile' => 'Brukerprofil',
	'comment_added' => 'Kommentar lagt til.',
	'new_report_submitted_alert' => 'Ny rapport fra {x} angående bruker {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Ingame Rapport',

	// Staff applications
	'comment_error' => 'Sørg for at din kommentar er mellom 2 og 2048 tegn.',
	'viewing_open_applications' => 'Viser <span class="label label-info">Åpne</span> søknader. Bytt til <a href="/mod/applications/?view=accepted"><span class="label label-success">Aksepterte</span></a> eller <a href="/mod/applications/?view=declined"><span class="label label-danger">Avslåtte</span></a>.',
	'viewing_accepted_applications' => 'Viser <span class="label label-success">Aksepterte</span> søknader. Bytt til <a href="/mod/applications/"><span class="label label-info">Åpne</span></a> eller <a href="/mod/applications/?view=declined"><span class="label label-danger">Avslåtte</span></a>.',
	'viewing_declined_applications' => 'Viser <span class="label label-danger">Avslåtte</span> søknader. Bytt til <a href="/mod/applications/"><span class="label label-info">Åpne</span></a> eller <a href="/mod/applications/?view=accepted"><span class="label label-success">Aksepterte</span></a>.',
	'time_applied' => 'tid ved innsending',
	'no_applications' => 'Ingen søknader i denne kategorien',
	'viewing_app_from' => 'Viser søknad fra {x}', // Don't replace "{x}"
	'open' => 'Åpne',
	'accepted' => 'Akseptert',
	'declined' => 'Avvist',
	'accept' => 'Aksepterer',
	'decline' => 'Avslå',
	'new_app_submitted_alert' => 'Ny søknad innsendt av {x}' // Don't replace "{x}"
);

/*
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Nyheter',
	'social' => 'Sosial',
	'join' => 'Bli med',

	// General terms
	'submit' => 'Send inn',
	'close' => 'Lukke',
	'cookie_message' => '<strong>Dette nettstedet bruker cookies for å forbedre din opplevelse.</strong><p>Ved å fortsette å bla gjennom og samhandle med dette nettstedet, samtykker du med deres bruk.</p>',
	'theme_not_exist' => 'Den valgte temaet finnes ikke.',
	'confirm' => 'Bekrefte',
	'cancel' => 'Kansellere',
	'guest' => 'Gjest',
	'guests' => 'Gjester',
	'back' => 'Tilbake',
	'search' => 'Søk',
	'help' => 'Hjelp',
	'success' => 'Suksess',
	'error' => 'Feil',
	'view' => 'Vis',
	'info' => 'Info',
	'next' => 'Neste',

	// Play page
	'connect_with' => 'Koble til serveren med IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Spillere Online:',
	'queried_in' => 'Spørres i:',
	'server_status' => 'Serverstatus',
	'no_players_online' => 'Det er ingen spillere online!',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'Det er {x} spillere online.', // Don't replace {x}

	// Other
	'page_loaded_in' => 'Siden lastet inn på {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Ingen',
	'404' => 'Beklager, men vi kunne ikke finne den siden.'
);

/*
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forums',
	'discussion' => 'Diskusjon',
	'stats' => 'Statistikk',
	'last_reply' => 'Siste svar',
	'ago' => 'siden',
	'by' => 'av',
	'in' => 'i',
	'views' => 'visninger',
	'posts' => 'innlegg',
	'topics' => 'emner',
	'topic' => 'Emne',
	'statistics' => 'Statistikk',
	'overview' => 'Oversikt',
	'latest_discussions' => 'Nyeste diskusjoner',
	'latest_posts' => 'Siste innlegg',
	'users_registered' => 'Brukere registrert:',
	'latest_member' => 'Siste medlem:',
	'forum' => 'Forum',
	'last_post' => 'Siste innlegg',
	'no_topics' => 'Ingen emner her ennå',
	'new_topic' => 'Nytt emne',
	'subforums' => 'Underforum:',

	// View topic view
	'home' => 'Hjem',
	'topic_locked' => 'Emne Låst',
	'new_reply' => 'Nytt Svar',
	'mod_actions' => 'Mod-handlinger',
	'lock_thread' => 'Lås Tråd',
	'unlock_thread' => 'Åpne Tråd',
	'merge_thread' => 'Flett Tråd',
	'delete_thread' => 'Slett Tråd',
	'confirm_thread_deletion' => 'Er du sikker på at du vil slette denne tråden?',
	'move_thread' => 'Flytt Tråd',
	'sticky_thread' => 'Klistret Tråd',
	'report_post' => 'Rapporter Innlegg',
	'quote_post' => 'Svar',
	'delete_post' => 'Slett Innlegg',
	'edit_post' => 'Rediger Innlegg',
	'reputation' => 'rykte',
	'confirm_post_deletion' => 'Er du sikker på at du vil slette dette innlegget?',
	'give_reputation' => 'Gi rykte',
	'remove_reputation' => 'Fjern rykte',
	'post_reputation' => 'Innlegg Rykte',
	'no_reputation' => 'Ingen rykte for dette innlegget ennå',
	're' => 'SV:',

	// Create post view
	'create_post' => 'Opprett Innlegg',
	'post_submitted' => 'Innlegg sendt',
	'creating_post_in' => 'Opprette innlegg i: ',
	'topic_locked_permission_post' => 'Dette temaet er stengt, men dine tillatelser tillater deg å poste',

	// Edit post view
	'editing_post' => 'Redigerer innlegg',

	// Sticky threads
	'thread_is_' => 'Tråd er ',
	'now_sticky' => 'nå en klisstret tråd',
	'no_longer_sticky' => 'ikke lenger en klistret tråd',

	// Create topic
	'topic_created' => 'Emne opprettet.',
	'creating_topic_in_' => 'Oppretter tema i forumet ',
	'thread_title' => ' Trådtittel',
	'confirm_cancellation' => 'Er du sikker?',
	'label' => 'Merkelapp',

	// Reports
	'report_submitted' => 'Rapport innsendt.',
	'view_post_content' => 'Vis innlegget\'s innhold',
	'report_reason' => 'Grunn for rapportering',

	// Move thread
	'move_to' => 'Flytt til:',

	// Merge threads
	'merge_instructions' => 'Tråden å flette med <strong>må</strong> være i samme forum. Flytt en tråd om nødvendig.',
	'merge_with' => 'Slå sammen med:',

	// Other
	'forum_error' => 'Beklager, men vi kunne ikke finne et forum eller et emne med dette emnet',
	'are_you_logged_in' => 'Er du logget inn?',
	'online_users' => 'Brukere Online',
	'no_users_online' => 'Det er ingen brukere online',

	// Search
	'search_error' => 'Vennligst skriv inn et søk på mellom 1 og 32 tegn.',
	'no_search_results' => 'Ingen søkeresultater har blitt funnet.',

	//Share on a social-media.
	'sm-share' => 'Del',
	'sm-share-facebook' => 'Del på Facebook',
	'sm-share-twitter' => 'Del på Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hei',
	'message' => 'Takk for at du registrerer deg! For å fullføre registreringen, vennligst klikk på følgende link:',
	'thanks' => 'Takk,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'mindre enn ett minutt siden',
	'1_minute' => '1 minutt siden',
	'_minutes' => '{x} minutter siden',
	'about_1_hour' => 'ca 1 time siden',
	'_hours' => '{x} timer siden',
	'1_day' => '1 dag siden',
	'_days' => '{x} dager siden',
	'about_1_month' => 'omtrent 1 måned siden',
	'_months' => '{x} måneder siden',
	'about_1_year' => 'ca 1 år siden',
	'over_x_years' => 'over {x} år siden'
);

/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Viser _MENU_ visninger per side', // Don't replace "_MENU_"
	'nothing_found' => 'Ingen resultater funnet',
	'page_x_of_y' => 'Viser side _PAGE_ av _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Ingen poster tilgjengelig',
	'filtered' => '(filtrert fra _MAX_ total rekord)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Registrering Fullført'
);

?>
