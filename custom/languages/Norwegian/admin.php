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
	'statistics' => 'Statistikker',

	// Core
	'settings' => 'Instillinger',
	'general_settings' => 'Generelle instillinger',
	'sitename' => 'Nettside navn',
	'default_language' => 'Standard språk',
	'default_language_help' => 'Brukere kan velge mellom alle installerte språk',
	'installed_languages' => 'Nye språk har suksessfullt blitt installert!',
	'default_timezone' => 'Standard tidssone',
	'registration' => 'Registrering',
	'enable_registration' => 'Tillatt registrering?',
	'verify_with_mcassoc' => 'Verifiser bruker med MCAssoc?',
	'email_verification' => 'Tillatt email verifisering?',
	'homepage_type' => 'Hjemmeside type',
	'post_formatting_type' => 'Post formatering',
	'portal' => 'Portal',
	'missing_sitename' => 'Vennligst velg et navn mellom 2 til 64 tegn.',
	'use_friendly_urls' => 'Trygge URLs',
	'use_friendly_urls_help' => 'VIKTIG: Din server må tillatte mod_rewrite og .htaccess for at NamelessMC skal fungere.',
	'config_not_writable' => 'Your <strong>core/config.php</strong> filen kan ikke skrives. Vennligst sjekk permisjonene.',
	'social_media' => 'Sosiale Medier',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Bruk Twitter Dark Theme?',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'successfully_updated' => 'Oppdatert suksessfullt!',

	// Reactions
	'icon' => 'Ikon',
	'type' => 'Type',
	'positive' => 'Positiv',
	'neutral' => 'Nøytral',
	'negative' => 'Negaativ',
	'editing_reaction' => 'Redigerings reaksjon',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> Ny reaksjon',
	'creating_reaction' => 'Oppretter ny reaksjon',

	// Custom profile fields
	'custom_fields' => 'Egenkonfigurerte profile bokser',
	'new_field' => '<i class="fa fa-plus-circle"></i> Ny boks',
	'required' => 'Kreves',
	'public' => 'Offentlig',
	'text' => 'Tekst',
	'textarea' => 'Tekst område',
	'date' => 'Dato',
	'creating_profile_field' => 'Lager ny profil boks.',
	'editing_profile_field' => 'Redigerer profil boks',
	'field_name' => 'Boks navn',
	'profile_field_required_help' => 'Påkrevde boksene må fylles inn av en bruker.',
	'profile_field_public_help' => 'Offentlige bokser vil bli sett av alle brukere, med mindre moderatorer har deaktivert denne funksjonen.',
	'profile_field_error' => 'Vennligst velg et navn innen 2 til 16 tegn.',
	'description' => 'Beskrivelse',
	'display_field_on_forum' => 'Vise boks på forumet?',
	'profile_field_forum_help' => 'Hvis boksen er aktivert, vil den bli sett på brukerforumet.',

	// Minecraft
	'enable_minecraft_integration' => 'Tillatt Minecraft integrering?',

	// Modules
	'modules_installed_successfully' => 'Nye moduler har suksessfullt blitt installert.',
	'enabled' => 'Aktivert',
	'disabled' => 'Deaktivert',
	'enable' => 'Aktivere',
	'disable' => 'Deaktivere',
	'module_enabled' => 'Modul aktivert.',
	'module_disabled' => 'Modul deaktivert.',

	// Styles
	'templates' => 'Templater',
	'template_outdated' => 'Vi har funnet ut av at din template er gyldig for NamelessMC versjon {x}, men du kjører versjon {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Aktiv',
	'deactivate' => 'Deaktiver',
	'activate' => 'Aktiver',
	'warning_editing_default_template' => 'Advarsel! Vi anbefaler at du ikke redigerer denne templaten',
	'images' => 'Bilder',
	'upload_new_image' => 'Last opp nytt bilde',
	'reset_background' => 'Resett bakgrunn',
	'install' => '<i class="fa fa-plus-circle"></i> Installer',
	'template_updated' => 'Template suksessfullt oppdatert!',
	'default' => 'Standard',
	'make_default' => 'Gjør til standard',
	'default_template_set' => 'Standard template satt til {x} suksessfullt.', // Don't replace {x}
	'template_deactivated' => 'Template deaktivert.',
	'template_activated' => 'Template aktivert.',
	'permissions' => 'Permissions',

	// Users & groups
	'users' => 'Brukere',
	'groups' => 'Grupper',
	'group' => 'Gruppe',
	'new_user' => '<i class="fa fa-plus-circle"></i> Ny bruker',
	'creating_new_user' => 'Lager ny bruker.',
	'registered' => 'Registrert',
	'user_created' => 'Bruker suksessfullt laget.',
	'cant_delete_root_user' => 'Kan ikke slette admin bruker!',
	'cant_modify_root_user' => 'Kan ikke redigere admin sin gruppe!',
	'user_deleted' => 'Bruker har suksessfullt blitt slettet.',
	'confirm_user_deletion' => 'Er du sikker på at du vil slette <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Gyldig bruker',
	'update_uuid' => 'Oppdater UUID',
	'update_mc_name' => 'Oppdater Minecraft brukernavn',
	'reset_password' => 'Reset Passord',
	'punish_user' => 'Advar bruker',
	'delete_user' => 'Slett bruker',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Andre valg',
	'disable_avatar' => 'Deaktiver Avatar',
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
    'warning' => 'Warning',

    // Maintenance
    'maintenance_mode' => 'Maintenance Mode',
    'maintenance_enabled' => 'Maintenance mode is currently enabled.',
    'enable_maintenance_mode' => 'Enable maintenance mode?',
    'maintenance_mode_message' => 'Maintenance mode message',
    'maintenance_message_max_1024' => 'Please ensure your maintenance message is a maximum of 1024 characters.',

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
