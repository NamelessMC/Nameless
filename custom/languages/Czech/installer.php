<?php
/*
 *	Made by Samerton, translated by Renzotom
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Czech Language - Installation
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Instalace',
    'pre-release' => 'předběžné vydání',
    'installer_welcome' => 'Vítejte v předběžném vydání NamelessMC verze 2.0.',
    'pre-release_warning' => 'Upozorňujeme, že toto předběžné vydání není určeno k použití na veřejných stránkách.',
    'installer_information' => 'Instalátor Vás provede procesem instalace.',
    'new_installation_question' => 'Za prvé, jedná se o novou instalaci?',
    'new_installation' => 'Nová instalace &raquo;',
    'upgrading_from_v1' => 'Aktualizace z v1 &raquo;',
    'requirements' => 'Požadavky:',
    'config_writable' => 'core/config.php Writable',
    'cache_writable' => 'Mezipameť Writable',
    'template_cache_writable' => 'Mezipaměť šablony Writable',
    'exif_imagetype_banners_disabled' => 'Without the exif_imagetype function, server banners will be disabled.',
    'requirements_error' => 'Aby bylo možné pokračovat v instalaci, musíte mít nainstalována všechna potřebná rozlišení a nastavená správná oprávnění.',
    'proceed' => 'Pokračovat',
    'database_configuration' => 'Konfigurace databáze',
    'database_address' => 'Adresa databáze',
    'database_port' => 'Port na databázi',
    'database_username' => 'Uživatelské jméno k databázi',
    'database_password' => 'Heslo databáze',
    'database_name' => 'Název databáze',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'This is the path Nameless is installed in, relative to your domain. For example, if Nameless is installed at example.com/forum, this needs to be <strong>forum</strong>. Leave empty if Nameless is not in a subfolder.',
    'friendly_urls' => 'Friendly URLs',
    'friendly_urls_info' => 'Friendly URLs will improve the readability of URLs in your browser.<br />For example: <br />example.com/index.php?route=/forum<br />would become<br />example.com/forum.<br /><strong>Important!</strong><br />Your server must be configured correctly for this to work. You can see whether you can enable this option by clicking <a href=\'./rewrite_test\' target=\'_blank\'>here</a>.',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => 'Sada znaků',
    'database_engine' => 'Database Storage Engine',
    'host' => 'Hostname',
    'host_help' => 'The hostname is the <strong>base URL</strong> for your website. Do not include the subfolders from the Installation Path field, or http(s):// here!',
    'database_error' => 'Zajistěte, aby byla vyplněna všechna pole.',
    'submit' => 'Odeslat',
    'installer_now_initialising_database' => 'Instalátor nyní inicializuje databázi. To může chvíli trvat...',
    'configuration' => 'Konfigurace',
    'configuration_info' => 'Zadejte prosím základní informace o Vašem webu. Tyto hodnoty se dají změnit později, v ovládácím panelu administrátora.',
    'configuration_error' => 'Zadejte prosím platný název webu, dlouhý v rozmezí 1 až 32 znaků a platnou e-mailovou adresu dlouhou v rozmezí 4 až 64 znaků.',
    'site_name' => 'Název webu',
    'contact_email' => 'Kontaktní e-mail',
    'outgoing_email' => 'Odchozí e-mail',
    'initialising_database_and_cache' => 'Inicializace databáze a mezipaměti, počkejte prosím...',
    'unable_to_login' => 'Nepodařilo se přihlásit.',
    'unable_to_create_account' => 'Nepodařilo se vytvořit účet.',
    'input_required' => 'Zadejte prosím platné uživatelské jméno, e-mailovou adresu a heslo.',
    'input_minimum' => 'Zajistěte, aby Vaše uživatelské jméno mělo minimálně 3 znaky, Vaše e-mailová adresa 4 znaky a Vaše heslo 6 znaků.',
    'input_maximum' => 'Zajistěte, aby Vaše uživatelské jméno mělo maximálně 20 znaků, Vaše e-mailová adresa a heslo 64 znaků.',
    'email_invalid' => 'Your email is not valid.',
    'passwords_must_match' => 'Vaše hesla se musí shodovat.',
    'creating_admin_account' => 'Vytváření účtu administrátora',
    'enter_admin_details' => 'Zadejte prosím podrobnosti pro Váš účet administrátora.',
    'username' => 'Uživatelské jméno',
    'email_address' => 'E-mailová adresa',
    'password' => 'Heslo',
    'confirm_password' => 'Heslo znovu',
    'upgrade' => 'Aktualizovat',
    'input_v1_details' => 'Zadejte prosím podrobnosti o Vaší databázi, použité při instalaci Nameless verze 1.',
    'installer_upgrading_database' => 'Počkejte prosím, než instalátor aktualizuje Vaši databázi...',
    'errors_logged' => 'Byly zaznamenány chyby. Klikněte na "Pokračovat" pro pokračování v aktualizaci.',
    'continue' => 'Pokračovat',
    'convert' => 'Konvertovat',
    'convert_message' => 'Na závěr, chcete fórum převést z jiné služby pro fórum?',
    'yes' => 'Ano',
    'no' => 'Ne',
    'converter' => 'Converter',
    'back' => 'Back',
    'unable_to_load_converter' => 'Unable to load converter!',
    'finish' => 'Dokončit',
    'finish_message' => 'Děkujeme Vám za instalaci služby NamelessMC! Nyní můžete pokračovat do ovládacího panelu administrátora (StaffCP), kde můžete nakonfigurovat svůj web blíže.',
    'support_message' => 'Pokud potřebujete nějakou pomoc, podívejte se <a href="https://namelessmc.com" target="_blank">zde</a> na náš web, nebo můžete navštívit i  <a href="https://discord.gg/9vk93VR" target="_blank">Discord server</a> nebo naše <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub uložiště</a>.',
    'credits' => 'Zásluha',
    'credits_message' => 'Velké poděkování všem <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMC spolupracovníkům</a> od roku 2014'
);