<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Translated by IsS127, ItsLynix
 *  SwedishSE Language - Installer
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Installera',
    'pre-release' => 'Förhands-utgivning',
    'installer_welcome' => 'Välkommen till NamelessMC version 2.0 förhands-utgivning',
    'pre-release_warning' => 'Kom ihåg att detta släppet är inte avsett att användas på en allmän hemsida.',
    'installer_information' => 'Installatören kommer att guida dig igenom installation processen.',
    'terms_and_conditions' => 'By continuing you agree to the terms and conditions.',
    'new_installation_question' => 'För det första, är det här en ny installation?',
    'new_installation' => 'Ny installation &raquo;',
    'upgrading_from_v1' => 'Upgraderar från v1 &raquo;',
    'requirements' => 'Krav:',
    'config_writable' => 'core/config.php Skrivbar',
    'cache_writable' => 'Cache Skrivbar',
    'template_cache_writable' => 'Mall Cache Skrivbar',
    'exif_imagetype_banners_disabled' => 'Without the exif_imagetype function, server banners will be disabled.',
    'requirements_error' => 'Du måste ha alla nödvändiga tillägg installerade och ha rätt behörighet för att kunna fortsätta med installationen.',
    'proceed' => 'Fortsätt',
    'database_configuration' => 'Databas Konfiguration',
    'database_address' => 'Databas Adress',
    'database_port' => 'Databas Port',
    'database_Användarnamn' => 'Databas Användarnamn',
    'database_password' => 'Databas Lösenord',
    'database_name' => 'Databas Namn',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'This is the path Nameless is installed in, relative to your domain. For example, if Nameless is installed at example.com/forum, this needs to be <strong>forum</strong>. Leave empty if Nameless is not in a subfolder.',
    'friendly_urls' => 'Friendly URLs',
    'friendly_urls_info' => 'Las URL amigables mejorarán la legibilidad de las URL en su navegador. <br /><b>Por ejemplo:</b> <br /> <code>example.com/index.php?route=/forum</code><br/> se convertira en <br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>¡IMPORTANTE!</strong> Su servidor web debe estar configurado correctamente para que las url amigables funcione. Puede ver si puede habilitar esta opción haciendo clic <a href=\'./rewrite_test\' target=\'_blank\'style="color:#2185D0">aqui</a>.</div>',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => 'Teckenuppsättning',
    'database_engine' => 'Database Storage Engine',
    'host' => 'Hostname',
    'host_help' => 'The hostname is the <strong>base URL</strong> for your website. Do not include the subfolders from the Installation Path field, or http(s):// here!',
    'database_error' => 'Se till att alla fält har fyllts i.',
    'submit' => 'Enter',
    'installer_now_initialising_database' => 'Installatören initierar databasen nu. Det här kan ta ett tag...',
    'configuration' => 'Konfiguration',
    'configuration_info' => 'Vänligen ange grundläggande information om din webbplats. Dessa information kan ändras senare genom admin kontrolpanelen.',
    'configuration_error' => 'Ange ett giltigt webbplatsnamn mellan 1 och 32 tecken långt och giltiga e-postadresser mellan 4 och 64 tecken långa.',
    'site_name' => 'Sido Namn',
    'contact_email' => 'Kontakt Email',
    'language' => 'Language',
    'outgoing_email' => 'Utgående Email',
    'initialising_database_and_cache' => 'Initierar databasen och cachen, vänligen vänta...',
    'unable_to_login' => 'Det går inte att logga in.',
    'unable_to_create_account' => 'Det går inte att skapa ett konto.',
    'input_required' => 'Var god ange ett giltigt användarnamn, e-postadress och lösenord.',
    'input_minimum' => 'Vänligen se till att användarnamnet är minst 3 tecken, din e-postadress är minst 4 tecken och ditt lösenord är minst 6 tecken.',
    'input_maximum' => 'Se till att din användarnamn är högst 20 tecken och din e-postadress och ditt lösenord är högst 64 tecken.',
    'email_invalid' => 'Your email is not valid.',
    'passwords_must_match' => 'Dina lösenord måste matcha.',
    'creating_admin_account' => 'Skapar Admin Kontot.',
    'enter_admin_details' => 'Vänligen ange detaljerna för administratorkontot.',
    'Användarnamn' => 'Användarnamn',
    'email_address' => 'Email Adress',
    'password' => 'Lösenord',
    'confirm_password' => 'Bekräfta lösenordet',
    'upgrade' => 'Uppgradera',
    'input_v1_details' => 'Vänligen ange databas informationen för installationen av NamelessMC version 1.',
    'installer_upgrading_database' => 'Vänligen vänta medans installatören upgraderar din database...',
    'errors_logged' => 'Fel har loggats. Klicka Fortsätt för att fortsätta med uppgraderingen.',
    'continue' => 'Fortsätt',
    'convert' => 'Omvandla',
    'convert_message' => 'Slutligen, vill du konvertera från ett annat forumprogram?',
    'yes' => 'Ja',
    'no' => 'Nej',
    'converter' => 'Converter',
    'back' => 'Back',
    'unable_to_load_converter' => 'Unable to load converter!',
    'finish' => 'Slutför',
    'finish_message' => 'Tack för att du installerar NamelessMC! Nu kan du försätta till AdminKP, där du kan ytterligare konfigurera din hemsida.',
    'support_message' => 'Om du behöver hjälp, kolla in vår hemsida <a href="https://namelessmc.com" target="_blank">here</a>, eller kan du besöka vår <a href="https://discord.gg/J6QsVaP" target="_blank">Discord server</a> eller vår <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub förvaring</a>.',
    'credits' => 'Credits',
    'credits_message' => 'Ett stort tack till alla <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC bidragsgivare</a> sedan 2014',

    'step_home' => 'Hem',
    'step_requirements' => 'Krav',
    'step_general_config' => 'Allmän konfiguration',
    'step_database_config' => 'Databas konfiguration',
    'step_site_config' => 'Sid konfiguration',
    'step_admin_account' => 'Admin Konto',
    'step_conversion' => 'Konvertering',
    'step_finish' => 'Klart',

    'general_configuration' => 'Allmän konfiguration',
    'reload' => 'Ladda om',
    'reload_page' => 'Ladda om sidan',
    'no_converters_available' => 'Det finns inga konverterare tillgängliga.',
    'config_not_writable' => 'Konfigurationsfilen är inte skrivbar.',

    'session_doesnt_exist' => 'Det gick inte att upptäcka sessionen. Sessionsbesparingar är ett krav för att använda Nameless. Försök igen, och om problemet kvarstår, kontakta din webbhotell för support.'
);
