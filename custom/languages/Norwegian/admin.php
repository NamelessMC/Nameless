<?php
/*
 *	Oversatt av Thesevs
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC versjon 2.0.0-dev
 *
 *  License: MIT
 *
 *  Norsk språk - Admin
 */

$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'Vennligst logg inn på nytt.',

	// Sidebar
	'admin_cp' => 'AdminCP',
	'administration' => 'Administrasjon',
	'overview' => 'Oppsummering',
	'core' => 'Kjerne',
	'minecraft' => 'Minecraft',
	'modules' => 'Moduler',
	'security' => 'Sikkerhet',
	'styles' => 'Stiler',
	'users_and_groups' => 'Brukere og grupper',

	// Overview
	'running_nameless_version' => 'Kjører NamelessMC versjon <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Kjører PHP versjon <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => 'Statistikk',

	// Core
	'settings' => 'Instillinger',
	'general_settings' => 'Generelle instillinger',
	'sitename' => 'Nettstedets navn',
	'default_language' => 'Standardspråk',
	'default_language_help' => 'Tilskuere på nettstedet kan benytte alle installerte språk',
	'installed_languages' => 'Installasjonen av språket var vellykket!',
	'default_timezone' => 'Standard tidssone',
	'registration' => 'Registrering',
	'enable_registration' => 'Vil du åpne for registrering?',
	'verify_with_mcassoc' => 'Verifiser bruker med MCAssoc-tillegget?',
	'email_verification' => 'Vil du kreve e-postverifikasjon av nye kontoer?',
	'homepage_type' => 'Forsidetype',
	'post_formatting_type' => 'Formatteringstype for poster',
	'portal' => 'Portal',
	'missing_sitename' => 'Navnet på nettstedet må være mellom 2 og 64 tegn.',
	'use_friendly_urls' => 'Søkemotorvennlige URL-adresser',
	'use_friendly_urls_help' => 'VIKTIG! For at denne funksjonen skal fungere må du ha mod_rewrite og .htaccess tilgjengelig for web-serveren din. (Kun Apache2 er støttet for øyeblikket)',
	'config_not_writable' => '<strong>core/config.php</strong> er ikke skrivbar. Sjekk filens rettigheter og web-serverens tilgang. (www-data og chmod g+rw)',
	'social_media' => 'Sosiale medier',
	'youtube_url' => 'YouTube-URL',
	'twitter_url' => 'Twitter-URL',
	'twitter_dark_theme' => 'Bruk mørkt tema for Twitter-modulen?',
	'google_plus_url' => 'Google+ URL',
	'facebook_url' => 'Facebook-URL',
	'successfully_updated' => 'Vellykket endring i innstillinger!',

	// Reactions
	'icon' => 'Ikon',
	'type' => 'Type',
	'positive' => 'Positiv',
	'neutral' => 'Nøytral',
	'negative' => 'Negativ',
	'editing_reaction' => 'Redigerer reaksjon',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> Ny reaksjon',
	'creating_reaction' => 'Oppretter ny reaksjon',

	// Custom profile fields
	'custom_fields' => 'Egendefinerbare profilfelt',
	'new_field' => '<i class="fa fa-plus-circle"></i> Nytt felt',
	'required' => 'Påkrevd',
	'public' => 'Synlig for offentligheten',
	'text' => 'Tekst',
	'textarea' => 'Tekstområde',
	'date' => 'Dato',
	'creating_profile_field' => 'Lager nytt profilfelt',
	'editing_profile_field' => 'Redigerer profilfelt',
	'field_name' => 'Feltnavn',
	'profile_field_required_help' => 'Påkrevde felt må fylles inn av en bruker.',
	'profile_field_public_help' => 'Offentlige bokser vil være synlig for alle tilskuere på nettstedet, med mindre nettstedsmoderatorene har skrudd av dette.',
	'profile_field_error' => 'Feltnavnet må være mellom 2 og 16 tegn langt.',
	'description' => 'Beskrivelse',
	'display_field_on_forum' => 'Synlig på forumet',
	'profile_field_forum_help' => 'Hvis "Synlig på forumet" er aktivert vil alle som bruker forumet kunne se feltet.',

	// Minecraft
	'enable_minecraft_integration' => 'Aktiver Minecraft-integrasjon',
    	'mc_service_status' => 'Minecraft-tjenestens status',
    	'service_query_error' => 'Kunne ikke hente tjenestestatus',
    	'authme_integration' => 'AuthMe-integrasjon',
    	'authme_integration_info' => 'Hvis AuthMe-integrasjonen er aktivert, vil brukere kun kunne registrere seg inne på serveren.',
   	'enable_authme' => 'Aktiver AuthMe-integrasjon',
    	'authme_db_address' => 'IP-adresse for AuthMe (MySQL)',
    	'authme_db_port' => 'Port for AuthMe (MySQL)',
    	'authme_db_name' => 'Databasenavn for AuthMe (MySQL)',
    	'authme_db_user' => 'Brukernavn for AuthMe (MySQL)',
    	'authme_db_password' => 'Passord for AuthMe (MySQL)',
    	'authme_hash_algorithm' => 'AuthMe hashing-algoritme',
    	'authme_db_table' => 'Tabellen for AuthMe (MySQL)',
    	'enter_authme_db_details' => 'Fyll inn databasekonfigurasjonsdataen for å koble sammen nettstedet og AuthMe.',
    	'authme_password_sync' => 'Synkroniser brukerpassordet',
    	'authme_password_sync_help' => 'Hvis denne funksjonen er aktivert vil en brukers passord automatisk oppdateres på nettstedet når brukeren endrer det inne i spillet.',
    	'minecraft_servers' => 'Minecraft-servere',
    	'account_verification' => 'Minecraft-kontoverifisering',
    	'server_banners' => 'Server-bannere',
    	'query_errors' => 'Spørrefeil',
    	'add_server' => '<i class="fa fa-plus-circle"></i> Legg til en server',
    	'adding_server' => 'Legger til en server',
    	'server_name' => 'Serverens navn',
    	'server_address' => 'Serverens adresse',
    	'server_address_help' => 'Adressen er det samme som feltet du fyller inn for å logge på serveren.',
	// Modules
	'modules_installed_successfully' => 'Modulene er nå oppdatert',
	'enabled' => 'Aktivert',
	'disabled' => 'Deaktivert',
	'enable' => 'Aktiver',
	'disable' => 'Deaktiver',
	'module_enabled' => 'Modul aktivert.',
	'module_disabled' => 'Modul deaktivert.',

	// Styles
	'templates' => 'Maler',
	'template_outdated' => 'Malen er kun kompatibel med NamelessMC versjon {x}, men du kjører versjon {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Aktiv',
	'deactivate' => 'Ikke aktiv',
	'activate' => 'Aktiver',
	'warning_editing_default_template' => 'Advarsel! Denne malen bør ikke redigeres.',
	'images' => 'Bilder',
	'upload_new_image' => 'Last opp nytt bilde',
	'reset_background' => 'Tilbakestill bakgrunnen',
	'install' => '<i class="fa fa-plus-circle"></i> Installer',
	'template_updated' => 'Malen er nå oppdatert!',
	'default' => 'Standard',
	'make_default' => 'Gjør til standardmal',
	'default_template_set' => 'Din standardmal er nå {x}.', // Don't replace {x}
	'template_deactivated' => 'Mal deaktivert.',
	'template_activated' => 'Mal aktivert.',
	'permissions' => 'Tilganger',

	// Users & groups
	'users' => 'Brukere',
	'groups' => 'Grupper',
	'group' => 'Gruppe',
	'new_user' => '<i class="fa fa-plus-circle"></i> Ny bruker',
	'creating_new_user' => 'Opprettet ny bruker.',
	'registered' => 'Registrert',
	'user_created' => 'Bruker opprettet.',
	'cant_delete_root_user' => 'Du kan ikke slette Admin-brukeren!',
	'cant_modify_root_user' => 'Du kan ikke redigere Admin-gruppen!',
	'user_deleted' => 'Bruker slettet.',
	'confirm_user_deletion' => 'Er du sikker på at du vil slette brukeren <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Gyldig bruker',
	'update_uuid' => 'Oppdater UUID',
	'update_mc_name' => 'Oppdater Minecraft-brukernavn',
	'reset_password' => 'Gjenoprett passord',
	'punish_user' => 'Gi brukeren en straff',
	'delete_user' => 'Slett brukeren',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Andre valg',
	'disable_avatar' => 'Deaktiver avatar',
	'select_user_group' => 'Du må velge brukerens gruppe.',
	'uuid_max_32' => 'UUID må være maksimum 32 tegn.',
	'title_max_64' => 'Brukernavnet kan maksimum være 64 tegn.',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'Gruppe ID',
	'name' => 'Navn',
	'title' => 'Bruker tittel',
	'new_group' => '<i class="fa fa-plus-circle"></i> Ny gruppe',
	'group_name_required' => 'Vennligst skriv inn et gruppenavn.',
	'group_name_minimum' => 'Pass på at gruppenavnet er minst 2 tegn.',
	'group_name_maximum' => 'Pass på at gruppenavnet er maks 20 tegn.',
	'creating_group' => 'Lager ny gruppe',
	'group_html_maximum' => 'Pass på at gruppens HTML ikke er lengre enn 1024 tegn.',
	'group_html' => 'Gruppe HTML',
	'group_html_lg' => 'Gruppe HTML Stor',
	'group_username_colour' => 'Gruppe brukernavnfarge',
	'group_staff' => 'Er denne gruppa en staff gruppe?',
	'group_modcp' => 'Har denne gruppa tilgang til ModCP?',
	'group_admincp' => 'Har denne gruppa tilgang til AdminCP?',
	'delete_group' => 'Slett gruppe',
	'confirm_group_deletion' => 'Er du sikker på at du vil slette gruppa {x}?', // Don't replace {x}
	'group_not_exist' => 'Den gruppa eksisterer ikke.',

	// General Admin language
	'task_successful' => 'Kapittel ferdig.',
	'invalid_action' => 'Ugyldig valg.',
	'enable_night_mode' => 'Aktiver nattmodus',
	'disable_night_mode' => 'Deaktiver nattmodus',
	'view_site' => 'Vis side',
	'signed_in_as_x' => 'Logget inn som {x}', // Don't replace {x}
    	'warning' => 'Advarsel',

    	// Maintenance
    	'maintenance_mode' => 'Vedlikeholdsmodus',
    	'maintenance_enabled' => 'Vedlikeholdsmodus er aktivert.',
    	'enable_maintenance_mode' => 'Aktiver vedlikeholdsmodus??',
    	'maintenance_mode_message' => 'Vedlikeholdsmelding',
    	'maintenance_message_max_1024' => 'Pass på at vedlikeholdsmeldingen er maks 1024 tegn',
	// Security
	'acp_logins' => 'AdminCP Logg inns',
	'please_select_logs' => 'Velg logs du vil vise.',
	'ip_address' => 'IP Addresser',
	'template_changes' => 'Template forandringer',
	'file_changed' => 'File endringer',

	// Updates
	'update' => 'Oppdater',
	'current_version_x' => 'Denne versjonen: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'Ny versjon:: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'Det er en ny oppdatering tilgjengelig',
	'up_to_date' => 'Din NamelessMC kjører den nyeste versjonen!',
	'urgent' => 'Denne oppdateringen er en midlertidig versjon.',
	'changelog' => 'Oppdateringshistorikk',
	'update_check_error' => 'Det oppsto en feil under søket:',
	'instructions' => 'Instruksjoner',
	'download' => 'Last ned',
	'install' => 'Installer',
	'install_confirm' => 'Pass på at du har lastet opp filene først!',

	// File uploads
	'drag_files_here' => 'Dra filene hit for å laste opp.',
	'invalid_file_type' => 'Ugyldig filtype.',
	'file_too_big' => 'Filen er for stor! Din fil var {{filesize}} og maksimum er {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
