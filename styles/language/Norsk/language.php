<?php 
/*
 *	Translation created by Partydragen
 *  http://partydragen.com/
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
	
	// Admin index page
	'statistics' => 'Statistikk',
	'registrations_per_day' => 'Registreringer per dag (siste 7 dager)',
	
	// Admin core page
	'general_settings' => 'Generelle innstillinger',
	'modules' => 'Moduler',
	'module_not_exist' => 'Denne modulen eksisterer ikke!',
	'module_enabled' => 'Modul aktivert.',
	'module_disabled' => 'Modul deaktivert',
	'site_name' => 'Side navn',
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
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Sider',
	'enable_or_disable_pages' => 'Aktivere eller deaktivere sider her.',
	'enable' => 'Aktiver',
	'disable' => 'Deaktiver',
	'maintenance_mode' => 'Forum vedlikeholdsmodus',
	'forum_in_maintenance' => 'Forumet er i vedlikeholdsmodus.',
	'unable_to_update_settings' => 'Kan ikke oppdatere innstillinger. Vennligst sikre at ingen felt er tomt.',
	'editing_google_analytics_module' => 'Redigerer Google Analytics-modul',
	'tracking_code' => 'Sporings Kode',
	'tracking_code_help' => 'Sett inn sporingskoden for Google Analytics her, inkludert de omliggende skriptkodene .',
	'google_analytics_help' => 'Se <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank"> denne veiledningen </a> for mer informasjon, følge trinn 1 til 3.',
	'social_media_links' => 'Sosial Media Linker',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registrering',
	'registration_warning' => 'Å ha denne modulen deaktivert, deaktiveres registrertings siden seg på nettstedet.',
	'google_recaptcha' => 'Aktiver Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Side nøkkel',
	'recaptcha_secret_key' => 'reCAPTCHA Hemmelig nøkkel',
	'registration_terms_and_conditions' => 'Registrering Betingelser',
	'voice_server_module' => 'Voice Server Modul',
	'only_works_with_teamspeak' => 'Denne modulen foreløpig bare fungerer med Teamspeak',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Fyll inn detaljene for ServerQuery brukeren',
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
	'no_questions' => 'Ingen spørsmål er lagt til ennå.',
	'new_question' => 'Nytt spørsmål',
	'editing_question' => 'Redigerer Spørsmål',
	'delete_question' => 'Slett Spørsmål',
	'dropdown' => 'Fall ned',
	'text' => 'Tekst',
	'textarea' => 'Tekst Area',
	'question_deleted' => 'Spørsmål slettet',
	'use_followers' => 'Bruk følgere?',
	'use_followers_help' => 'Hvis deaktivert, vil venner systemet brukes.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Klikk på en side for å redigere det.',
	'page' => 'Side:',
	'url' => 'URL:',
	'page_url' => 'Side URL',
	'page_url_example' => '(Med foregående "/", for eksempel /hjelp/)',
	'page_title' => 'Side Tittel',
	'page_content' => 'Side Innhold',
	'new_page' => 'Ny Side',
	'page_successfully_created' => 'Side opprettet',
	'page_successfully_edited' => 'Siden er redigert',
	'unable_to_create_page' => 'Kan ikke opprette siden.',
	'unable_to_edit_page' => 'Kan ikke redigere siden.',
	'create_page_error' => 'Kontroller at du har skrevet inn en nettadresse mellom 1 og 20 tegn langt, en sidetittel mellom 1 og 30 tegn langt, og sideinnhold mellom 5 og 20480 tegn.',
	'delete_page' => 'Slett Siden',
	'confirm_delete_page' => 'Er du sikker på at du vil slette denne siden?',
	'page_deleted_successfully' => 'Siden Slettet',
	'page_link_location' => 'Vis siden link i:',
	'page_link_navbar' => 'Navbar',
	'page_link_more' => 'Navbar "Mer" dropdown',
	'page_link_footer' => 'Side footer',
	'page_link_none' => 'Ingen lenke',
	'page_permissions' => 'Side Tillatelser',
	'can_view_page' => 'Kan vise side:',
	'redirect_page' => 'Omdirigerings side?',
	'redirect_link' => 'Omdirigere lenke',
	
	// Admin forum page
	'labels' => 'Emne etiketter',
	'new_label' => 'Ny etikett',
	'no_labels_defined' => 'Ingen etiketter definert',
	'label_name' => 'Etikett Navn',
	'label_type' => 'Etikett Type',
	'label_forums' => 'Etikett Forums',
	'label_creation_error' => 'Feil ved oppretting av en etikett. Sørg for at navnet ikke er lengre enn 32 tegn, og at du har angitt en type.',
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
	'new_forum' => 'Ny Forum',
	'forum_layout' => 'Forum Layout',
	'table_view' => 'Tabell Visning',
	'latest_discussions_view' => 'Siste Diskusjoner Visning',
	'create_forum' => 'Opprett Forum',
	'forum_name' => 'Forum Navn',
	'forum_description' => 'Forum Beskrivelse',
	'delete_forum' => 'Slett Forum',
	'move_topics_and_posts_to' => 'Flytt emner og innlegg til',
	'delete_topics_and_posts' => 'Slett emner og innlegg',
	'parent_forum' => 'Overforum',
	'has_no_parent' => 'Har ingen kategori',
	'forum_permissions' => 'Forum Tillatelser',
	'can_view_forum' => 'Kan vise forum:',
	'can_create_topic' => 'Kan lage emne:',
	'can_post_reply' => 'Kan legge inn svar:',
	'display_threads_as_news' => 'Vis tråder som nyheter på forsiden?',
	'input_forum_title' => 'Skriv inn forum tittel',
	'input_forum_description' => 'Skriv inn forum beskrivelse.',
	'forum_name_minimum' => 'Forumet Navnet må være minst 2 tegn.',
	'forum_description_minimum' => 'Forumet Beskrivelsen må være minst 2 tegn.',
	'forum_name_maximum' => 'Forumet Navnet må være maksimalt 150 tegn.',
	'forum_description_maximum' => 'Forumet Beskrivelsen må være maksimalt 255 tegn',
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
	'confirm_user_deletion' => 'Er du sikker på at du vil slette brukeren {x}?', // Don't replace "{x}"
	'groups' => 'Grupper',
	'group' => 'Gruppe',
	'new_group' => 'Ny Gruppe',
	'id' => 'ID',
	'name' => 'Navn',
	'create_group' => 'Opprett Gruppe',
	'group_name' => 'Gruppe Navn',
	'group_html' => 'Gruppe HTML',
	'group_html_lg' => 'Gruppe HTML Large',
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
	'minecraft_settings' => 'Minecraft Innstillinger',
	'use_plugin' => 'Bruk Nameless Minecraft plugin?',
	'force_avatars' => 'Force Minecraft avatars?',
	'uuid_linking' => 'Aktiver UUID linking?',
	'use_plugin_help' => 'Bruke plugin tillater rank synkronisering og også i spillet registrering og billett underkastelse .',
	'uuid_linking_help' => 'Hvis deaktivert, vil brukerkontoer ikke kobles med UUID. Det anbefales sterkt at du holder dette aktivert.',
	'plugin_settings' => 'Plugin Innstillinger',
	'confirm_api_regen' => 'Er du sikker på at du vil generere en ny API-nøkkel?',
	'servers' => 'Servere',
	'new_server' => 'Ny Server',
	'confirm_server_deletion' => 'Er du sikker på at du vil slette denne serveren?',
	'main_server' => 'Hoved Server',
	'main_server_help' => 'Spillerne server koble gjennom. Normalt vil dette være Bungee eksempel.',
	'choose_a_main_server' => 'Velg en hoved server..',
	'external_query' => 'Bruk ekstern spørringen?',
	'external_query_help' => 'Bruk en ekstern API for å søke i Minecraft server? Bare bruk dette hvis den innebygde søket ikke fungerer; Det er sterkt anbefalt at dette ikke er krysset.',
	'editing_server' => 'Redigerer server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (med port) (numerisk eller domene)',
	'server_ip_with_port_help' => 'Dette er IP som vil bli vist til brukere. Det vil ikke bli spurt.',
	'server_ip_numeric' => 'Server IP (med port) (kun numerisk)',
	'server_ip_numeric_help' => 'Dette er IP som skal spørres, må du sørge for at den er numerisk bare. Det vil ikke bli vist til brukere.',
	'show_on_play_page' => 'Vis på Play side?',
	'pre_17' => 'Pre 1.7 Minecraft versjon?',
	'server_name' => 'Server navn',
	'invalid_server_id' => 'Ugyldig server-ID',
	'show_players' => 'Vis spillerlisten på Play side?',
	'server_edited' => 'Server redigert',
	'server_created' => 'Server opprettet',
	'query_errors' => 'Query feil',
	'query_errors_info' => 'Følgende feil kan du diagnostisere problemer med den interne serveren spørringen .',
	'no_query_errors' => 'Ingen spørringsfeil logget',
	'date' => 'Dato:',
	'port' => 'Port:',
	'viewing_error' => 'Vis Feil',
	'confirm_error_deletion' => 'Er du sikker på at du vil slette denne feilen?',
	'display_server_status' => 'Vis server status modul?',
	'server_name_required' => 'Du må sette inn et servernavn .',
	'server_ip_required' => 'Du må sette inn serverens IP.',
	'server_name_minimum' => 'Servernavnet må være minst 2 tegn.',
	'server_ip_minimum' => 'Serveren IP må være minst 2 tegn.',
	'server_name_maximum' => 'Servernavnet må være maksimalt 20 tegn.',
	'server_ip_maximum' => 'Serveren IP må være maksimalt 64 tegn.',
	'purge_errors' => 'Sleep feil medlingene',
	'confirm_purge_errors' => 'Er du sikker på at du vil slette alle feil meldingene?',
	
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
	'theme_not_exist' => 'Denne temaet finnes ikke!',
	'template_not_exist' => 'Denne template finnes ikke!',
	'addon_not_exist' => 'Denne addon finnes ikke!',
	'style_scan_complete' => 'Fullført, har noen nye teamer er installert.',
	'addon_scan_complete' => 'Fullført, har noen nye addons er installert.',
	'theme_enabled' => 'Tema aktivert.',
	'template_enabled' => 'Template aktivert.',
	'addon_enabled' => 'Addon aktivert.',
	'theme_deleted' => 'tema slettet.',
	'template_deleted' => 'Template slettet.',
	'addon_disabled' => 'Addon deaktivert.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Er du sikker på at du vil slette temaet <strong>{x}</strong>?<br /><br />Temaet vil bli slettet fra <strong>styles/themes</strong> mappa.', // Don't replace {x}
	'confirm_template_deletion' => 'Er du sikker på at du vil slette template <strong>{x}</strong>?<br /><br />Template vil bli slettet fra <strong>styles/templates</strong> mappa.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Andre Innstillinger',
	'enable_error_reporting' => 'Aktiver feilrapportering?',
	'error_reporting_description' => 'Dette bør bare brukes for debugging formål, er det sterkt anbefalt dette blir stående som deaktivert.',
	'display_page_load_time' => 'Vis siden lasting tid?',
	'page_load_time_description' => 'Å ha denne aktivert vil vise et speedometer i bunnteksten som vil vise siden lasting tid.',
	'reset_website' => 'Reset Hjemmeside',
	'reset_website_info' => 'Dette vil tilbakestille ditt nettsted innstillinger. <strong>Addons vil bli deaktivert, men ikke fjernet, og deres innstillinger endres ikke.</strong> De angitte Minecraft servere vil også forbli.',
	'confirm_reset_website' => 'Er du sikker på at du vil tilbakestille ditt nettsted innstillinger?',
	
	// Admin Update page
	'installation_up_to_date' => 'Installasjonen kjører nyeste versjon.',
	'update_check_error' => 'Kan ikke se etter oppdateringer. Prøv igjen senere.',
	'new_update_available' => 'En ny oppdatering er tilgjengelig.',
	'your_version' => 'Din versjon:',
	'new_version' => 'Ny versjon::',
	'download' => 'Nedlasting',
	'update_warning' => 'Advarsel: Kontroller at du har lastet ned pakken og lastet de inneholdt filene først!'
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
	'staff_apps' => 'Staff Applications',
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
	'email_address' => 'Epost Address',
	'date_of_birth' => 'Fødselsdato',
	'location' => 'Lokasjon',
	'password' => 'Passord',
	'confirm_password' => 'Bekreft Passord',
	'i_agree' => 'Jeg er enig',
	'agree_t_and_c' => 'Ved å klikke på <strong class="label label-primary">Registrer</strong>, samtykker du til våre <a href="#" data-toggle="modal" data-target="#t_and_c_m">vilkårene</a>.',
	'register' => 'Registrere',
	'sign_in' => 'Logg inn',
	'sign_out' => 'Logg ut',
	'terms_and_conditions' => 'Vilkår og betingelser',
	'successful_signin' => 'Du har blitt logget inn',
	'incorrect_details' => 'uriktige opplysninger',
	'remember_me' => 'Husk meg',
	'forgot_password' => 'Glemt passord',
	'must_input_username' => 'Du må sette inn et brukernavn.',
	'must_input_password' => 'Du må sette inn et passord.',
	'inactive_account' => 'Kontoen din er for øyeblikket inaktiv. Har du be om en tilbakestilling av passord?',
	'account_banned' => 'Din konto har blitt utestengt.',
	'successfully_logged_out' => 'Du har blitt logget ut.',
	'signature' => 'Signatur',
	'registration_check_email' => 'Vennligst sjekk e-posten din for en validering kobling. Du vil ikke være i stand til å logge deg på før dette er klikket.',
	'unknown_login_error' => 'Beklager, det oppstod en ukjent feil mens du prøvde logge deg inn. Vennligst prøv igjen senere.',
	'validation_complete' => 'Takk for at du registrerer deg! Du kan nå logge inn.',
	'validation_error' => 'Feil behandling av forespørselen. Vennligst prøv å klikke på lenken igjen.',
	'registration_error' => 'Kontroller at du har fylt ut alle feltene, og at brukernavnet ditt er mellom 3 og 20 tegn og passord er mellom 6 og 30 tegn.',
	'username_required' => 'Skriv inn et brukernavn.',
	'password_required' => 'Skriv inn et passord.',
	'email_required' => 'Vennligst oppgi en e-postadresse.',
	'mcname_required' => 'Vennligst skriv inn en Minecraft brukernavn.',
	'accept_terms' => 'Du må godta vilkårene før du registrerer deg.',
	'invalid_recaptcha' => 'Ugyldig reCAPTCHA respons.',
	'username_minimum_3' => 'Brukernavnet må være minst 3 tegn.',
	'username_maximum_20' => 'Brukernavnet må være maksimalt 20 tegn.',
	'mcname_minimum_3' => 'Minecraft brukernavn må være minst 3 tegn.',
	'mcname_maximum_20' => 'Minecraft brukernavn må være maksimalt 20 tegn.',
	'password_minimum_6' => 'Ditt passord må være minst 6 tegn.',
	'password_maximum_30' => 'Passordet ditt må være maksimalt 30 tegn.',
	'passwords_dont_match' => 'Passordene er ikke like.',
	'username_mcname_email_exists' => 'Brukernavnet eksisterer allerede Minecraft brukernavn eller e-postadresse. Har du allerede opprettet en bruker?',
	'invalid_mcname' => 'Minecraft brukernavn er ikke en gyldig bruker',
	'mcname_lookup_error' => 'Det oppstod en feil med å kontakte Mojang servere. Prøv igjen senere.',
	'signature_maximum_900' => 'Din underskrift skal være maksimalt 900 tegn.',
	'invalid_date_of_birth' => 'Ugyldig fødselsdato.',
	'location_required' => 'Vennligst skriv inn et lokasjon.',
	'location_minimum_2' => 'Din lokasjon må være minst 2 tegn.',
	'location_maximum_128' => 'Din lokasjon må være maksimalt 128 tegn.',
	
	// UserCP
	'user_cp' => 'BrukerCP',
	'no_file_chosen' => 'Ingen fil valgt',
	'private_messages' => 'Private Meldinger',
	'profile_settings' => 'Profil innstillinger',
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
	'separate_users_with_comma' => 'Separate brukere med komma (",")',
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
	'password_changed_successfully' => 'Passord er endret',
	'incorrect_password' => 'Din nåværende passord er feil',
	'update_minecraft_name_help' => 'Dette vil oppdatere ditt nettsted brukernavn til din nåværende Minecraft brukernavn. Du kan bare utføre denne handlingen en gang hver 30. dag.',
	'unable_to_update_mcname' => 'Kan ikke oppdatere Minecraft brukernavn.',
	'display_age_on_profile' => 'Vis alder på profilen?',
	'two_factor_authentication' => 'Two Factor Authentication',
	'enable_tfa' => 'Enable Two Factor Authentication',
	'tfa_type' => 'Two Factor Authentication type:',
	'authenticator_app' => 'Authentication App',
	'tfa_scan_code' => 'Please scan the following code within your authentication app:',
	'tfa_code' => 'If your device does not have a camera, or you are unable to scan the QR code, please input the following code:',
	'tfa_enter_code' => 'Please enter the code displaying within your authentication app:',
	'invalid_tfa' => 'Invalid code, please try again.',
	'tfa_successful' => 'Two factor authentication set up successfully. You will need to authenticate every time you log in from now on.',
	'confirm_tfa_disable' => 'Are you sure you wish to disable two factor authentication?',
	'tfa_disabled' => 'Two factor authentication disabled.',
	'tfa_enter_email_code' => 'We have sent you a code within an email for verification. Please enter the code now:',
	'tfa_email_contents' => 'A login attempt has been made to your account. If this was you, please input the following two factor authentication code when asked to do so. If this was not you, you can ignore this email, however a password reset is advised. The code is only valid for 10 minutes.',
	
	// Alerts
	'viewing_unread_alerts' => 'Viser uleste varsler. Endre til <a href="/user/alerts/?view=read"><span class="label label-success">Lest</span></a>.',
	'viewing_read_alerts' => 'Viser leste varslene. Endre til <a href="/user/alerts/"><span class="label label-warning">ulest</span></a>.',
	'no_unread_alerts' => 'Du har ingen uleste varsler.',
	'no_alerts' => 'ingen advarsler',
	'no_read_alerts' => 'Du har ingen leste varsler.',
	'view' => 'Vis',
	'alert' => 'Varsle',
	'when' => 'Når',
	'delete' => 'Slett',
	'tag' => 'Bruker Tag',
	'tagged_in_post' => 'Du har blitt tagget i et innlegg',
	'report' => 'Rapporter',
	'deleted_alert' => 'varsel slettet',
	
	// Warnings
	'you_have_received_a_warning' => 'Du har mottat en advarsel fra {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Bekrefte',
	
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
	'invalid_wall_post' => 'Ugyldig vegg innlegg. Sørg for innlegget ditt er mellom 2 og 2048 tegn.',
	'about' => 'Handle om',
	'reply' => 'Svar',
	'x_likes' => '{x} Liker', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'Ingen likes.',
	'post_liked' => 'Innlegg likte.',
	'post_unliked' => 'Innlegg unliked.',
	'no_posts' => 'Ingen innlegg.',
	'last_5_posts' => 'Last 5 posts',
	
	// Staff applications
	'staff_application' => 'Staff Application',
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
	'reports' => 'Reports',
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
	'user_reported' => 'Bruker Rapportert',
	'type' => 'Type',
	'updated_by' => 'Oppdatert av',
	'forum_post' => 'Forum Innlegg',
	'user_profile' => 'Bruker Profil',
	'comment_added' => 'Kommentar lagt til.',
	'new_report_submitted_alert' => 'Ny rapport fra {x} angående bruker {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Sørg for din kommentar er mellom 2 og 2048 tegn.',
	'viewing_open_applications' => 'Viser <span class="label label-info">Åpne</span> søknader. Bytt til <a href="/mod/applications/?view=accepted"><span class="label label-success">aksepterte</span></a> eller <a href="/mod/applications/?view=declined"><span class="label label-danger">Avslått</span></a>.',
	'viewing_accepted_applications' => 'Viser <span class="label label-success">akseptert</span> applications. Bytt til <a href="/mod/applications/"><span class="label label-info">Åpne</span></a> eller <a href="/mod/applications/?view=declined"><span class="label label-danger">avslått</span></a>.',
	'viewing_declined_applications' => 'Viser <span class="label label-danger">Avslått</span> applications. Bytt til <a href="/mod/applications/"><span class="label label-info">Åpne</span></a> eller <a href="/mod/applications/?view=accepted"><span class="label label-success">akseptert</span></a>.',
	'time_applied' => 'tid Applied',
	'no_applications' => 'Ingen søknader i denne kategorien',
	'viewing_app_from' => 'Viser søknad fra {x}', // Don't replace "{x}"
	'open' => 'Åpne',
	'accepted' => 'Akseptert',
	'declined' => 'avvist',
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
	'join' => 'Join',
	
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
	'search' => 'Søke',
	'help' => 'Hjelp',
	'success' => 'Suksess',
	'error' => 'Feil',
	'view' => 'Vis',
	'info' => 'Info',
	'next' => 'Next',
	
	// Play page
	'connect_with' => 'Koble til serveren med IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Spillere Online:',
	'queried_in' => 'Spørres i:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Det er ingen spillere online!',
	'x_players_online' => 'Det er {x} spillere online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Siden lastet på {x}s', // Don't replace {x}; 's' stands for 'seconds'
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
	'subforums' => 'Subforums:',
	
	// View topic view
	'home' => 'Hjem',
	'topic_locked' => 'Emne Låst',
	'new_reply' => 'Ny Svar',
	'mod_actions' => 'Mod Handlinger',
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
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Opprett Innlegg',
	'post_submitted' => 'Innlegg sendes',
	'creating_post_in' => 'Opprette innkegg i: ',
	'topic_locked_permission_post' => 'Dette temaet er stengt, men dine tillatelsene tillater det så du legge inn',
	
	// Edit post view
	'editing_post' => 'Redigerer innlegg',
	
	// Sticky threads
	'thread_is_' => 'Tråd er ',
	'now_sticky' => 'Nå er en klisstrert tråd',
	'no_longer_sticky' => 'ikke lenger en klistret tråd',
	
	// Create topic
	'topic_created' => 'Emne opprettet.',
	'creating_topic_in_' => 'Opprette tema i forumet ',
	'thread_title' => ' Tråd Tittel',
	'confirm_cancellation' => 'Er du sikker?',
	'label' => 'Merkelapp',
	
	// Reports
	'report_submitted' => 'Rapporter innsendt.',
	'view_post_content' => 'Vis innlegget innhold',
	'report_reason' => 'Rapporter Grunn',
	
	// Move thread
	'move_to' => 'Flytt til:',
	
	// Merge threads
	'merge_instructions' => 'Tråden å fusjonere med <strong>må</strong> være i samme forum. Flytt en tråd om nødvendig.',
	'merge_with' => 'Slå sammen med:',
	
	// Other
	'forum_error' => 'Beklager, men vi kunne ikke finne at forum eller emne',
	'are_you_logged_in' => 'Er du logget inn?',
	'online_users' => 'Brukere Online',
	'no_users_online' => 'Det er ingen brukere i online',
	
	// Search
	'search_error' => 'Vennligst skriv inn et søk på mellom 1 og 32 tegn.',
	
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
	'display_records_per_page' => 'Viser _MENU_ Visninger per side', // Don't replace "_MENU_"
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
