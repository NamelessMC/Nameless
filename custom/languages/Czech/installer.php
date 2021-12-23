<?php
/*
 *  Made by Samerton
 *  Translated by Fjuro
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Czech Language - Installation
 */

$language = [
    /*
     *  Installation
     */
    'install' => 'Instalace',
    'pre-release' => 'předběžné vydání',
    'installer_welcome' => 'Vítejte v předběžném vydání NamelessMC verze 2.0.',
    'pre-release_warning' => 'Toto předběžné vydání není určen pro použití na veřejném webu.',
    'installer_information' => 'Instalátor vás provede procesem instalace.',
    'terms_and_conditions' => 'Pokračováním souhlasíte s pravidly a podmínkami.',
    'new_installation_question' => 'Je toto nová instalace?',
    'new_installation' => 'Nová instalace &raquo;',
    'upgrading_from_v1' => 'Aktualizace z v1 &raquo;',
    'requirements' => 'Požadavky:',
    'config_writable' => 'Soubor core/config.php zapisovatelný',
    'cache_writable' => 'Mezipaměť zapisovatelná',
    'template_cache_writable' => 'Mezipaměť šablon zapisovatelná',
    'exif_imagetype_banners_disabled' => 'Bez funkce exif_imagetype budou zakázány bannery serverů.',
    'requirements_error' => 'Pro pokračování v instalaci musíte mít nainstalována všechna vyžadovaná rozšíření a mít nastavena správná oprávnění.',
    'proceed' => 'Pokračovat',
    'database_configuration' => 'Konfigurace databáze',
    'database_address' => 'Adresa databáze',
    'database_port' => 'Port databáze',
    'database_username' => 'Uživatelské jméno databáze',
    'database_password' => 'Heslo databáze',
    'database_name' => 'Název databáze',
    'nameless_path' => 'Instalační cesta',
    'nameless_path_info' => 'Toto je cesta, ve které je nainstalováno Nameless, vůči vaší doméně. Pokud je například Nameless nainstalováno na priklad.cz/forum, do tohoto pole musíte zadat <strong>forum</strong>. Ponechte prázdné, pokud nemáte Nameless v podsložce.',
    'friendly_urls' => 'Přátelské adresy',
    'friendly_urls_info' => 'Přátelské adresy zlepšují čitelnost adres URL ve vašem prohlížeči.<br />Například z<br /><code>priklad.cz/index.php?route=/forum</code><br />by se stalo<br /><code>priklad.cz/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Důležité!</strong><br />Váš server musí být pro správnou funkčnost této možnosti správně nakonfigurován. Zda můžete tuto možnost povolit zjistíte kliknutím <a href="./rewrite_test" target="_blank" style="color:#2185D0">sem</a>.</div>',
    'enabled' => 'Povoleno',
    'disabled' => 'Zakázáno',
    'character_set' => 'Znaková sada',
    'database_engine' => 'Engine databázového úložiště',
    'host' => 'Adresa',
    'host_help' => 'Adresa je <strong>základní URL</strong> vašeho webu. Nepatří sem podsložky z pole Instalační cesta ani http(s)://!',
    'database_error' => 'Ujistěte se, že jste vyplnili všechna pole.',
    'submit' => 'Potvrdit',
    'installer_now_initialising_database' => 'Instalátor nyní připravuje databázi. Může to chvíli trvat...',
    'configuration' => 'Konfigurace',
    'configuration_info' => 'Zadejte základní informace o vašem webu. Tyto informace mohou být kdykoli později změněny v panelu.',
    'configuration_error' => 'Zadejte platný název webu o délce 1 až 32 znaků a platnou e-mailovou adresu o délce 4 až 64 znaků.',
    'site_name' => 'Název webu',
    'contact_email' => 'Kontaktní e-mail',
    'outgoing_email' => 'Odchozí e-mail',
    'language' => 'Jazyk',
    'initialising_database_and_cache' => 'Příprava databáze a mezipaměti, vydržte prosím...',
    'unable_to_login' => 'Nepodařilo se přihlásit.',
    'unable_to_create_account' => 'Nepodařilo se vytvořit účet',
    'input_required' => 'Zadejte platné uživatelské jméno, e-mailovou adresu a heslo.',
    'input_minimum' => 'Ujistěte se, že vaše uživatelské jméno má minimálně 3 znaky, e-mailová adresa minimálně 4 znaky a heslo minimálně 6 znaků.',
    'input_maximum' => 'Ujistěte se, že vaše uživatelské jméno má maximálně 20 znaků a e-mailová adresa a heslo maximálně 64 znaků.',
    'email_invalid' => 'Váš e-mail není platný.',
    'passwords_must_match' => 'Vaše hesla se musí shodovat.',
    'creating_admin_account' => 'Vytváření účtu správce',
    'enter_admin_details' => 'Zadejte podrobnosti účtu správce.',
    'username' => 'Uživatelské jméno',
    'email_address' => 'E-mailová adresa',
    'password' => 'Heslo',
    'confirm_password' => 'Potvrdit heslo',
    'upgrade' => 'Aktualizace',
    'input_v1_details' => 'Zadejte podrobnosti databáze instalace vašeho Nameless verze 1.',
    'installer_upgrading_database' => 'Vydržte, dokud instalátor nedokončí aktualizaci vaší databáze...',
    'errors_logged' => 'Byly zaznamenány chyby. Klikněte na Pokračovat pro pokračování v aktualizaci.',
    'continue' => 'Pokračovat',
    'convert' => 'Konvertovat',
    'convert_message' => 'Chcete konvertovat z jiného softwaru na fórum?',
    'yes' => 'Ano',
    'no' => 'Ne',
    'converter' => 'Konvertor',
    'back' => 'Zpět',
    'unable_to_load_converter' => 'Nepodařilo se načíst konvertor!',
    'finish' => 'Dokončit',
    'finish_message' => 'Děkujeme za instalaci NamelessMC! Nyní můžete pokračovat do panelu, kde můžete dále nastavit váš web.',
    'support_message' => 'Pokud potřebujete jakoukoli podporu, podívejte se na <a href="https://namelessmc.com" target="_blank">náš web</a> nebo navštivte náš <a href="https://discord.gg/nameless" target="_blank">Discord server</a> nebo náš <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub repozitář</a>.',
    'credits' => 'Poděkování',
    'credits_message' => 'Velké poděkování všem <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">přispěvatelům NamelessMC</a> od roku 2014',

    'step_home' => 'Domů',
    'step_requirements' => 'Požadavky',
    'step_general_config' => 'Základní konfigurace',
    'step_database_config' => 'Konfigurace databáze',
    'step_site_config' => 'Konfigurace webu',
    'step_admin_account' => 'Účet správce',
    'step_conversion' => 'Konvertování',
    'step_finish' => 'Dokončit',

    'general_configuration' => 'Základní konfigurace',
    'reload' => 'Znovu načíst',
    'reload_page' => 'Znovu načíst stránku',
    'no_converters_available' => 'Nejsou dostupné žádné konvertory.',
    'config_not_writable' => 'Konfigurační soubor není zapisovatelný.',

    'session_doesnt_exist' => 'Nepodařilo se detekovat relaci. Pro správnou funkci Nameless je potřeba funkce ukládání relací. Zkuste to prosím znovu. Pokud chyba přetrvává, kontaktujte podporu vašeho webhostingu.'
];