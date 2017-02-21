<?php 
/*
 *	Gemaakt door Samerton
 *  en vertaald door Sander Lambrechts
 *  
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Nederlandse taal - Admin
 */
 
$language = array(
	/*
	 *  Admin controle paneel
	 */
	// Login
	're-authenticate' => 'Gelieve opnieuw te verifiëren',
	
	// Navigatiebalk
	'admin_cp' => 'Controle Paneel',
	'administration' => 'Administratie',
	'overview' => 'Overzicht',
	'core' => 'Kern',
	'minecraft' => 'Minecraft',
	'modules' => 'Modules',
	'security' => 'Beveiliging',
	'styles' => 'Stijlen',
	'users_and_groups' => 'Gebruikers en groepen',
	
	// Overzicht
	'running_nameless_version' => 'Website versie: <strong>{x}</strong>', // Verander "{x}" niet
	'running_php_version' => 'PHP versie: <strong>{x}</strong>', // Verander "{x}" niet
	'statistics' => 'Statistieken',
	
	// Kern
	'settings' => 'Instellingen',
	'general_settings' => 'Algemene Instellingen',
	'sitename' => 'Site naam',
	'default_language' => 'Standaard taal',
	'default_language_help' => 'Gebruikers kunnen kiezen uit alle geïnstalleerde talen.',
	'installed_languages' => 'Alle nieuwe talen zijn toegevoegd.',
	'default_timezone' => 'Standaard tijdzone',
	'registration' => 'Registratie',
	'enable_registration' => 'Registratie inschakelen?',
	'verify_with_mcassoc' => 'Verifieer gebruiker accounts met MCAssoc?',
	'email_verification' => 'Schakel email verificatie in?',
	'homepage_type' => 'Beginpagina type',
	'post_formatting_type' => 'Bericht opmaak soort',
	'portal' => 'Portaal',
	'missing_sitename' => 'Graag een site naam invullen van 2 tot 64 karakters lang.',
	'use_friendly_urls' => 'Vriendelijke URLs',
	'use_friendly_urls_help' => 'BELANGERIJK: Je host moet geconfigureerd zijn om het gebruik van mod_rewrite en .htaccess files toe te staan.',
	'config_not_writable' => 'Uw <strong>core/config.php</strong> bestand is niet herschrijfbaar.',
	'social_media' => 'Sociale Media',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Gebruik het donkere thema voor Twitter?',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'successfully_updated' => 'Succesvol bijgewerkt',
	
	// Reacties
	'icon' => 'Icoon',
	'type' => 'Type',
	'positive' => 'Positief',
	'neutral' => 'Neutraal',
	'negative' => 'Negatief',
	'editing_reaction' => 'Reactie veranderen',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> Nieuwe reactie',
	'creating_reaction' => 'Reactie maken',
	
	// Aangepaste profiel velden
	'custom_fields' => 'Aangepaste profiel velden',
	'new_field' => '<i class="fa fa-plus-circle"></i> Nieuw veld',
	'required' => 'Verplicht',
	'public' => 'Openbaar',
	'text' => 'Tekst',
	'textarea' => 'Tekst gebied',
	'date' => 'Datum',
	'creating_profile_field' => 'Maak een profiel veld',
	'editing_profile_field' => 'Profiel veld bewerken',
	'field_name' => 'Veld naam',
	'profile_field_required_help' => 'Verplichte velden moeten door de gebruiker worden ingevuld, en ze verschijnen tijden de registratie.',
	'profile_field_public_help' => 'Openbare velden kunnen alle gebruikers zien, als dit uit staat kunnen alleen beheerders het zien.',
	'profile_field_error' => 'Graag een veld naam invullen tussen de 2 en 16 karakters lang.',
	'description' => 'Beschrijving',
	'display_field_on_forum' => 'Veld laten zien in het forum?',
	'profile_field_forum_help' => 'Als aan staat, wordt het veld weergegeven bij de gebruiker naast de forum posts.',
	
	// Minecraft
	'enable_minecraft_integration' => 'Minecraft integratie aan?',
	
	// Modulen
	'modules_installed_successfully' => 'Elke nieuwe modulen zijn succesvol toegevoegd.',
	'enabled' => 'Inschakelen',
	'disabled' => 'Uitschakelen',
	'module_enabled' => 'Module ingeschakeld.',
	'module_disabled' => 'Module uitgeschakeld.',
	
	// Stijlen
	'templates' => 'Sjablonen',
	'template_outdated' => 'We hebben ontdekt dat deze sjabloon voor versie {x} is, maar je gebruikt versie {y}', // Verander "{x}" of "{y}" niet
	'active' => 'Actief',
	'deactivate' => 'Deactiveer',
	'activate' => 'Activeer',
	'warning_editing_default_template' => 'Waarschuwing! Het is aangeraden niet de standaard sjaboon te bewerken.',
	'images' => 'Afbeeldingen',
	'upload_new_image' => 'Upload nieuwe afbeelding',
	'reset_background' => 'Reset achtergrond',
	'install' => '<i class="fa fa-plus-circle"></i> Installeren',
	'template_updated' => 'Sjabloon succesvol bijgewerkt.',
	'default' => 'Standaard',
	'make_default' => 'Maak standaard',
	'default_template_set' => 'Standaard sjabloon verzet naar {x} succesvol.', // Verander "{x}" niet
	'template_deactivated' => 'Sjabloon gedeactiveerd.',
	'template_activated' => 'Sjabloon geactiveerd.',
	
	// Gebruikers & groepen
	'users' => 'Gebruikers',
	'groups' => 'Groepen',
	'group' => 'Groep',
	'new_user' => '<i class="fa fa-plus-circle"></i> Nieuwe gebruiker',
	'creating_new_user' => 'Maak een nieuwe gebruiker',
	'registered' => 'Aangemeld',
	'user_created' => 'Gebruiker succesvol aangemaakt.',
	'cant_delete_root_user' => 'Kan niet de hoofdgebruiker verwijderen!',
	'cant_modify_root_user' => 'Kan niet de groep van de hoofdgebruiker wijzigen!',
	'user_deleted' => 'Gebruiker succesvol verwijderd.',
	'confirm_user_deletion' => 'Weet je zeker dat je <strong>{x}</strong> wilt verwijderen?', // Verander "{x}" niet
	'validate_user' => 'Valideer gebruiker', 
	'update_uuid' => 'Werk UUID bij',
	'update_mc_name' => 'Werk Minecraft naam bij',
	'reset_password' => 'Reset wachtwoord',
	'punish_user' => 'Straf gebruiker',
	'delete_user' => 'Verwijder gebruiker',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Andere acties',
	'disable_avatar' => 'Avatar uitschakelen',
	'select_user_group' => 'Je moet een groep slecteren.',
	'uuid_max_32' => 'De UUID mag maar uit 32 karakters bestaan.',
	'title_max_64' => 'De gebruikers titel mag maar uit 64 karakters bestaan.',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'Groep ID',
	'name' => 'Naam groep',
	'title' => 'Gebruikers titel',
	'new_group' => '<i class="fa fa-plus-circle"></i> Nieuwe groep',
	'group_name_required' => 'Graag een groepsnaam invoeren.',
	'group_name_minimum' => 'Zorg ervoor dat de groepsnaam minimaal uit 2 karakters bestaat.',
	'group_name_maximum' => 'Zorg ervoor dat de groepsnaam maximaal uit 20 karakters bestaat.',
	'creating_group' => 'Nieuwe groep maken',
	'group_html_maximum' => 'Zorg ervoor dat je groeps HTML code niet langer is dan 1024 karakters.',
	'group_html' => 'Groeps HTML code',
	'group_html_lg' => 'Groeps langere HTML code',
	'group_username_colour' => 'Groep gebruikersnaam kleur',
	'group_staff' => 'Is de groep voor personeel?',
	'group_modcp' => 'Mag deze groep het beheer paneel zien?',
	'group_admincp' => 'Mag deze groep het controle paneel zien?',
	'delete_group' => 'Verwijder groep',
	'confirm_group_deletion' => 'Weet je zeker dat je de groep {x} wilt verwijderen?', // Verander "{x}" niet
	'group_not_exist' => 'Die groep bestaat niet.',
	
	// Algemene admin taal
	'task_successful' => 'Taak succesvol.',
	'invalid_action' => 'Ongeldige actie.',
	'enable_night_mode' => 'Nacht modus aanzetten',
	'disable_night_mode' => 'Nacht modus uitzetten',
	'view_site' => 'Terug naar de website',
	'signed_in_as_x' => 'Ingelogd als {x}', // Verander "{x}" niet
	
	// Beveiliging
	'acp_logins' => 'Controle paneel aanmeldingen',
	'please_select_logs' => 'Welk logboek wil je bekijken?',
	'ip_address' => 'IP Adres',
	'template_changes' => 'Sjabloon veranderingen',
	'file_changed' => 'Bestanden veranderd',
	
	// Updates
	'update' => 'Bijwerken',
	'current_version_x' => 'Huidige versie: <strong>{x}</strong>', // Verander "{x}" niet
	'new_version_x' => 'Nieuwe versie: <strong>{x}</strong>', // Verander "{x}" niet
	'new_update_available' => 'Er is een nieuwe versie beschikbaar',
	'up_to_date' => 'Uw website is al helemaal bijgewerkt!',
	'urgent' => 'U moet uw website dringend bijwerken',
	'changelog' => 'Lijst van veranderingen',
	'update_check_error' => 'Er was een probleem met het zoeken naar nieuwe versies:',
	'instructions' => 'Instructies',
	'download' => 'Download',
	'install_confirm' => 'Zorg ervoor dat je het bestand hebt gedownload en de bestanden hebt geupload voordat je verder gaat!',
	
	// Bestand uploads
	'drag_files_here' => 'Sleep bestanden hier naartoe om te uploaden.',
	'invalid_file_type' => 'Verkeerd bestands type',
	'file_too_big' => 'Bestand te groot! Uw bestand is {{filesize}} en het limiet is {{maxFilesize}}' // Verander {{filesize}} of {{maxFilesize}} niet
);