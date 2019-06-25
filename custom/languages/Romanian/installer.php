<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Romanian Language - Installation
 *  Translation By @BaxAndrei ( https://baxandrei.ro ) and ASMODΣUS
 *  Last Update: 17/11/2018
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Instalare',
    'pre-release' => 'pre-lansare',
    'installer_welcome' => 'Bine ați venit la versiunea 2.0-pre-release a programului NamelessMC.',
    'pre-release_warning' => 'Rețineți că această versiune prealabilă nu este destinată utilizării pe un site public.',
    'installer_information' => 'Programul de instalare vă va ghida în procesul de instalare.',
    'new_installation_question' => 'În primul rând, este o nouă instalare?',
    'new_installation' => 'Instalare nouă &raquo;',
    'upgrading_from_v1' => 'Actualizarea de la v1 &raquo;',
    'requirements' => 'Cerinţe:',
    'config_writable' => 'core/config.php are permisiune de scriere',
    'cache_writable' => 'Cache are permisiune de scriere',
    'template_cache_writable' => 'Șabloanele Cache au permisiune de scriere',
    'exif_imagetype_banners_disabled' => 'Fără funcția exif_imagetype, bannerele serverelor vor fi dezactivate.',
    'requirements_error' => 'Trebuie să aveți instalate toate extensiile necesare și setați permisiunile corecte (permisiuni de scriere) pentru a continua instalarea.',
    'proceed' => 'Continuă',
    'database_configuration' => 'Configurarea bazei de date',
    'database_address' => 'Adresa bazei de date',
    'database_port' => 'Portul bazei de date',
    'database_username' => 'Utilizatorul bazei de date',
    'database_password' => 'Parola utilizatorului bazei de date',
    'database_name' => 'Numele bazei de date',
    'nameless_path' => 'Calea de instalare',
    'nameless_path_info' => 'Aceasta este calea în care este instalat scriptul, în raport cu domeniul dvs. De exemplu, dacă Nameless este instalat la example.com/forum, acesta trebuie să fie <strong> forum </strong>. Lăsați goal dacă Nameless nu se află într-un subfolder.',
    'friendly_urls' => 'Adrese URL prietenoase',
    'friendly_urls_info' => 'Adresele URL prietenoase vor îmbunătăți lizibilitatea adreselor URL din browserul dvs.<br />De exemplu: <br />example.com/index.php?route=/forum<br />ar deveni<br />example.com/forum.<br /><strong>Important!</strong><br />Serverul dvs. trebuie configurat corect pentru ca această funcție să funcționeze. Puteți vedea dacă puteți activa această opțiune făcând clic <a href=\'./rewrite_test\' target=\'_blank\'>aici</a>.',
    'enabled' => 'Activat',
    'disabled' => 'Dezactivat',
    'character_set' => 'Set de caractere',
    'database_engine' => 'Motor de stocare pentru baza de date',
    'host' => 'Numele gazdei',
    'host_help' => 'Numele gazdei este <strong>adresa de bază</strong>  pentru site-ul dvs. web. Nu includeți subfolderele din câmpul Calea de instalare sau http(s):// aici!',
    'database_error' => 'Asigurați-vă că toate câmpurile au fost completate.',
    'submit' => 'Trimite',
    'installer_now_initialising_database' => 'Instalatorul inițiază acum baza de date. Acest lucru poate dura ceva timp...',
    'configuration' => 'Configurație',
    'configuration_info' => 'Introduceți informațiile de bază despre site-ul dvs. Aceste valori pot fi modificate ulterior prin panoul de administrare.',
    'configuration_error' => 'Introduceți un nume de site valid între 1 și 32 de caractere și adrese de e-mail valide între 4 și 64 de caractere.',
    'site_name' => 'Numele site-ului',
    'contact_email' => 'E-Mail contact',
    'outgoing_email' => 'E-Mail de iesire',
    'initialising_database_and_cache' => 'Inițializarea bazei de date și a memoriei cache, vă rugăm să așteptați ...',
    'unable_to_login' => 'Conectarea nu a putut fi efectuata.',
    'unable_to_create_account' => 'Nu am putut crea contul de utilizator',
    'input_required' => 'Introduceți un nume de utilizator, o adresă de e-mail și o parolă care să fie valide.',
    'input_minimum' => 'Asigurați-vă că numele dvs. de utilizator are cel puțin 3 caractere, adresa dvs. de e-mail este de cel puțin 4 caractere, iar parola dvs. are cel puțin 6 caractere.',
    'input_maximum' => 'Asigurați-vă că numele dvs. de utilizator este de maximum 20 de caractere, iar adresa dvs. de e-mail și parola sunt de maxim 64 de caractere.',
    'email_invalid' => 'E-mailul dvs. nu este valid.',
    'passwords_must_match' => 'Parolele dvs. trebuie să se potrivească.',
    'creating_admin_account' => 'Crearea contului de administrator',
    'enter_admin_details' => 'Introduceți detaliile contului de admin.',
    'username' => 'Nume de utilizator',
    'email_address' => 'E-mail',
    'password' => 'Parola',
    'confirm_password' => 'Confirmă parola',
    'upgrade' => 'Actualizare',
    'input_v1_details' => 'Introduceți detaliile bazei de date pentru instalarea dvs. NamelessMC v1.',
    'installer_upgrading_database' => 'Vă rugăm să așteptați până instalatorul actualizează baza de date ...',
    'errors_logged' => 'S-au înregistrat erori. Faceți clic pe Continuați pentru a continua actualizarea.',
    'continue' => 'Continuă',
    'convert' => 'Convertiţi',
    'convert_message' => 'În încheiere, doriți să convertiți datele de la un alt software de tip forum?',
    'yes' => 'Da',
    'no' => 'Nu',
    'converter' => 'Convertire',
    'back' => 'Înapoi',
    'unable_to_load_converter' => 'Imposibil de încărcat convertorul!',
    'finish' => 'Finalizare',
    'finish_message' => 'Vă mulțumim pentru instalarea NamelessMC! Acum puteți trece în Panoul de Control, unde puteți configura în continuare site-ul Web.',
    'support_message' => 'Dacă aveți nevoie de asistență, consultați site-ul nostru web <a href="https://namelessmc.com" target="_blank">aici</a>, sau puteți vizita și serverul nostru de <a href="https://discord.gg/9vk93VR" target="_blank">Discord</a> sau pagina noastra de <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub</a>.',
    'credits' => 'Credite',
    'credits_message' => 'O mulțime de mulțumiri tuturor <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">colaboratorilor NamelessMC</a> înca din 2014'
);
