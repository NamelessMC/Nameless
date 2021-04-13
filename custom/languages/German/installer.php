<?php
/*
 *  Made by Samerton
 *  Translation by BukkitTNT, M_Viper
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  German Language - Installation
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Installieren',
    'pre-release' => 'Vorab Version (Pre-Release)',
    'installer_welcome' => 'Willkommen bei NamelessMC Version 2.0 Vorab Version (Pre-Release).',
    'pre-release_warning' => 'Bitte beachten Sie, dass diese Vorabversion nicht für den Einsatz auf einer öffentlichen Website bestimmt ist.',
    'installer_information' => 'Der Installationsassistent führt Sie durch den Installationsvorgang.',
    'terms_and_conditions' => 'Wenn Sie fortfahren, stimmen Sie den Allgemeinen Geschäftsbedingungen zu.',
    'new_installation_question' => 'Als erstens, ist das eine Neuinstallation?',
    'new_installation' => 'Neuinstallation &raquo;',
    'upgrading_from_v1' => 'Upgrade von v1 &raquo;',
    'requirements' => 'Erforderlichen Erweiterungen:',
    'config_writable' => 'core/config.php beschreibbar',
    'cache_writable' => 'Cache beschreibbar',
    'template_cache_writable' => 'Vorlagen Cache beschreibbar',
    'exif_imagetype_banners_disabled' => 'Ohne die Funktion exif_imagetype werden Server-Banner deaktiviert.',
    'requirements_error' => 'Sie müssen alle erforderlichen Erweiterungen installiert haben und korrekte Berechtigungen festgelegt haben, um mit der Installation fortzufahren.',
    'proceed' => 'Fortfahren',
    'database_configuration' => 'Datenbank Konfiguration',
    'database_address' => 'Datenbank Addresse',
    'database_port' => 'Datenbank Port',
    'database_username' => 'Datenbank Benutzername',
    'database_password' => 'Datenbank Passwort',
    'database_name' => 'Datenbank Name',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'Dies ist der Pfad, in dem Nameless relativ zu Ihrer Domain installiert ist. Wenn Nameless beispielsweise unter example.com/forum installiert ist, muss dies <strong>Forum</strong> sein. Lassen Sie das Feld leer, wenn sich Nameless nicht in einem Unterordner befindet.',
    'friendly_urls' => 'Freundliche URLs',
    'friendly_urls_info' => 'Freundliche URLs verbessern die Lesbarkeit von URLs in Ihrem Browser. <br />For example: <br /><code>example.com/index.php?route=/forum</code><br />would become:<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Wichtig!</strong><br />Ihr Server muss korrekt konfiguriert sein, damit dies funktioniert. Sie können sehen, ob Sie diese Option aktivieren können, indem Sie hier auf <a href="./rewrite_test" target="_blank" style="color:#2185D0">klicken</a>.</div>',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => 'Zeichensatz',
    'database_engine' => 'Database Storage Engine',
    'host' => 'Hostname',
    'host_help' => 'Der Hostname ist die <strong>base URL</strong> für Ihre Website. Fügen Sie hier keine Unterordner aus dem Feld Installationspfad oder http(s):// ein!!',
    'database_error' => 'Bitte stellen Sie sicher, dass alle Felder ausgefüllt sind.',
    'submit' => 'Absenden',
    'installer_now_initialising_database' => 'Der Installationsassistent initialisiert nun die Datenbank. Das kann eine kleine Weile dauern...',
    'configuration' => 'Konfiguration',
    'configuration_info' => 'Bitte geben Sie grundlegende Informationen über Ihre Website ein. Diese Werte können später über das Admin-Panel geändert werden.',
    'configuration_error' => 'Bitte geben Sie einen gültigen Website-Namen zwischen 1 und 32 Zeichen lang und gültige E-Mail-Adressen zwischen 4 und 64 Zeichen lang ein.',
    'site_name' => 'Webseiten Name',
    'contact_email' => 'Kontakt Emailadresse',
    'language' => 'Language',
    'outgoing_email' => 'Absender Emailadresse',
    'initialising_database_and_cache' => 'Initialisierung von Datenbank und Cache, bitte warten ...',
    'unable_to_login' => 'Anmeldung nicht möglich.',
    'unable_to_create_account' => 'Konto konnte nicht erstellt werden.',
    'input_required' => 'Bitte geben Sie einen gültigen Benutzernamen, eine E-Mail-Adresse und ein Passwort ein.',
    'input_minimum' => 'Bitte stellen Sie sicher, dass Ihr Benutzername mindestens 3 Zeichen beträgt, Ihre E-Mail-Adresse mindestens 4 Zeichen und Ihr Passwort mindestens 6 Zeichen.',
    'input_maximum' => 'Bitte stellen Sie sicher, dass Ihr Benutzername maximal 20 Zeichen beträgt und Ihre E-Mail-Adresse und Ihr Passwort maximal 64 Zeichen haben.',
    'email_invalid' => 'Ihre E-Mail ist ungültig.',
    'passwords_must_match' => 'Ihre Passwörter müssen übereinstimmen.',
    'creating_admin_account' => 'Admin-Konto erstellen',
    'enter_admin_details' => 'Bitte geben Sie die Details für das Admin-Konto ein.',
    'username' => 'Benutzername',
    'email_address' => 'Email Addresse',
    'password' => 'Passwort',
    'confirm_password' => 'Passwort bestätigen',
    'upgrade' => 'Upgrade',
    'input_v1_details' => 'Bitte geben Sie die Datenbankdetails für Ihre Nameless Version 1 Installation ein.',
    'installer_upgrading_database' => 'Bitte warten Sie, während die Installationsassistent Ihre Datenbank aktualisiert ...',
    'errors_logged' => 'Fehler wurden protokolliert. Klicken Sie auf Weiter, um mit dem Upgrade fortzufahren.',
    'continue' => 'Weiter',
    'convert' => 'Konvertieren',
    'convert_message' => 'Möchtest als letztes eine andere Forum Software konvertieren?',
    'yes' => 'Ja',
    'no' => 'Nein',
    'converter' => 'Konverter',
    'back' => 'Zurück',
    'unable_to_load_converter' => 'Konverter kann nicht geladen werden!',
    'finish' => 'Abschliessen',
    'finish_message' => 'Danke für die Installation von NamelessMC! Sie können nun zum StaffCP gelangen, wo Sie Ihre Website weiter konfigurieren können.',
    'support_message' => 'Wenn Sie irgendwelche Unterstützung benötigen, schauen Sie sich unsere Website <a href="https://namelessmc.com" target="_blank">NamelessMC</a> an, oder besuchen Sie doch unseren <a href="https://discord.gg/nameless" target="_blank">Discord Server</a> sowie unser <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub repository</a>.',
    'credits' => 'Credits',
    'credits_message' => 'Ein großer Dank an alle <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC Mitwirkenden</a> die seid 2014 mitgeholfen haben.',

    'step_home' => 'Home',
    'step_requirements' => 'Bedarf',
    'step_general_config' => 'Allgemeine Konfiguration',
    'step_database_config' => 'Datenbank Konfiguration',
    'step_site_config' => 'Standort Konfiguration',
    'step_admin_account' => 'Admin Account',
    'step_conversion' => 'Conversion',
    'step_finish' => 'Fertig',

    'general_configuration' => 'Allgemeine Konfiguration',
    'reload' => 'Neu laden',
    'reload_page' => 'Seite neuladen',
    'no_converters_available' => 'Es sind keine Konverter verfügbar.',
    'config_not_writable' => 'Die Konfigurationsdatei ist nicht beschreibbar.',

    'session_doesnt_exist' => 'Sitzung kann nicht erkannt werden. Das Speichern von Sitzungen ist eine Voraussetzung für die Verwendung von Nameless. Bitte versuchen Sie es erneut. Wenn das Problem weiterhin besteht, wenden Sie sich an Ihren Webhost, um Unterstützung zu erhalten.'
);
