<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  SK language by Marki35
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'Administrácia', 
	'invalid_token' => 'Zle dáta, skúste to prosím znovu.',
	'invalid_action' => 'Zlá akcia',
	'successfully_updated' => 'Úspešne aktualizované',
	'settings' => 'Nastavenie',
	'confirm_action' => 'Potvrdiť akciu',
	'edit' => 'Upravit',
	'actions' => 'Akcia',
	'task_successful' => 'Úloha úspešne spustená',
	
	// Admin login
	're-authenticate' => 'Prosím over sa opätovným prihlásením...',
	
	// Admin sidebar
	'index' => 'Prehľad',
	'core' => 'Core',
	'custom_pages' => 'Stránky',
	'general' => 'Hlavné',
	'forums' => 'Fóra',
	'users_and_groups' => 'Uživatelia a skupiny',
	'minecraft' => 'Minecraft',
	'style' => 'Styly',
	'addons' => 'Doplňky',
	'update' => 'Aktualizácie',
	'misc' => 'Misc',
	
	// Admin index page
	'statistics' => 'Štatistiky',
	'registrations_per_day' => 'Registracie za deň (posledných 7 dní)',
	
	// Admin core page
	'general_settings' => 'Hlavné nastavenie',
	'modules' => 'Moduly',
	'module_not_exist' => 'Tento modul neexistuje!',
	'module_enabled' => 'Modul zapnutý.',
	'module_disabled' => 'Modul vypnutý.',
	'site_name' => 'Názov stránky',
	'language' => 'Jazyk',
	'voice_server_not_writable' => 'core/voice_server.php Nieje zapisovateľný. Prosim skontroluj permissions priečinok',
	'email' => 'Email',
	'incoming_email' => 'Prichádzajúca e-mailová adresa',
	'outgoing_email' => 'Odchádzajúca e-mailová adresa',
	'outgoing_email_help' => 'Žiadá sa len vtedy, ak je povolená funkcia php mail',
	'use_php_mail' => 'Použiť funkciu php mail()?',
	'use_php_mail_help' => 'Odporúčané:povolené.Ak vaše webové stránky nemajú odosielanie e-mailov,zakážteto a upravte core/email.php s vašeho e-mailového nastavenia',
	'use_gmail' => 'Použivať Gmail pre odosielanie e-mailov',
	'use_gmail_help' => 'K dispozícii je iba ak je funkcia php mail vypnutá. Pokiaľ sa rozhodnete gmail nepoužívať, bude použitý SMTP. Tak či onak, to bude vyžadovať zodpovedajúce konfigúrácie: core/email.php.',
	'enable_mail_verification' => 'Povoliť overovanie účtu cez e-mail?',
	'enable_email_verification_help' => 'Pokiaľ bude toto povolené, bude požadované od novo registrovaných uživateľov overenie e-mailu pred dokončením registracie.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Pages',
	'enable_or_disable_pages' => 'Enable or disable pages here.',
	'enable' => 'Enable',
	'disable' => 'Disable',
	'maintenance_mode' => 'Forum maintenance mode',
	'forum_in_maintenance' => 'Forum is in maintenance mode.',
	'unable_to_update_settings' => 'Unable to update settings. Please ensure no fields are left empty.',
	'editing_google_analytics_module' => 'Editing Google Analytics module',
	'tracking_code' => 'Tracking Code',
	'tracking_code_help' => 'Insert the tracking code for Google Analytics here, including the surrounding script tags.',
	'google_analytics_help' => 'See <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">this guide</a> for more information, following steps 1 to 3.',
	'social_media_links' => 'Social Media Links',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registration',
	'registration_warning' => 'Having this module disabled will also disable new members registering on your site.',
	'google_recaptcha' => 'Enable Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Registration Terms and Conditions',
	'voice_server_module' => 'Voice Server Module',
	'only_works_with_teamspeak' => 'This module currently only works with TeamSpeak and Discord',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Please enter the details for the ServerQuery user',
	'ip_without_port' => 'IP (without port)',
	'voice_server_port' => 'Port (usually 10011)',
	'virtual_port' => 'Virtual Port (usually 9987)',
	'permissions' => 'Permissions:',
	'view_applications' => 'View Applications?',
	'accept_reject_applications' => 'Accept/Reject Applications?',
	'questions' => 'Questions:',
	'question' => 'Question',
	'type' => 'Type',
	'options' => 'Options',
	'options_help' => 'Each option on a new line; can be left empty (dropdowns only)',
	'no_questions' => 'No questions added yet.',
	'new_question' => 'New Question',
	'editing_question' => 'Editing Question',
	'delete_question' => 'Delete Question',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'textarea' => 'Text Area',
	'question_deleted' => 'Question Deleted',
	'use_followers' => 'Use followers?',
	'use_followers_help' => 'If disabled, the friends system will be used.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Kliknutím na stránku ju upravíte.',
	'page' => 'Stránka:',
	'url' => 'URL:',
	'page_url' => 'URL stránky',
	'page_url_example' => '(Pred názov zadajte aj "/", príklad /priklad/)',
	'page_title' => 'Názov stránky',
	'page_content' => 'Miesto stránky',
	'new_page' => 'Nová stránka',
	'page_successfully_created' => 'Stránka úspešne vytvorená!',
	'page_successfully_edited' => 'Stránka úspešne upravená!',
	'unable_to_create_page' => 'Povolte vytvorenie stránky.',
	'unable_to_edit_page' => 'Nemožno upravovať túto stránku.',
	'create_page_error' => 'Prosím uistite sa, že ste zadali URL v rozmedzí 1 až 20 znakov dlhý, názov stránky medzi 1 až 30 znakov a obsah stránky dlhý 5 až 20480 znakov.',
	'delete_page' => 'Zmazať stránku.',
	'confirm_delete_page' => 'Vážne chceš vymazať túto stránku ??',
	'page_deleted_successfully' => 'Stránka úspešne vymazaná',
	'page_link_location' => 'Zobrazovať odkaz na stránku v:',
	'page_link_navbar' => 'Hlavné menu',
	'page_link_more' => '"Viac" rozbaľovacie sa hlavné menu',
	'page_link_footer' => 'Päta stránky',
	'page_link_none' => 'Žiadny odkaz na stránku na webe',
	'page_permissions' => 'Page Permissions',
	'can_view_page' => 'Can view page:',
	'redirect_page' => 'Redirect page?',
	'redirect_link' => 'Redirect link',
	
	// Admin forum page
	'labels' => 'Štítky témy',
	'new_label' => 'Nová štítka',
	'no_labels_defined' => 'Žiadne štítky niesu definované.',
	'label_name' => 'Názov štítku',
	'label_type' => 'Typ štítku',
	'label_forums' => 'Fórumové štítky',
	'label_creation_error' => 'Chyba pri vytváraní štítku. Prosím uistite sa že názov nieje dlhší ako 32 znakov a že ste zadali špecifický typ.',
	'confirm_label_deletion' => 'Vážne chceš vymazať tento štítok?',
	'editing_label' => 'Upraviť štítok',
	'label_creation_success' => 'Štítok úspešne vytvorený',
	'label_edit_success' => 'Štítok úspešne upravený',
	'label_default' => 'Štandartné',
	'label_primary' => 'Primárny',
	'label_success' => 'úspech',
	'label_info' => 'Info',
	'label_warning' => 'Varovanie',
	'label_danger' => 'nebezpečné',
	'new_forum' => 'Nové fórum',
	'forum_layout' => 'Forum Layout',
	'table_view' => 'Výpis tabuliek',
	'latest_discussions_view' => 'Zobraziť posledné diskusie',
	'create_forum' => 'Vytvoriť fórum',
	'forum_name' => 'Názov fóra',
	'forum_description' => 'Popisok fóra',
	'delete_forum' => 'Vymazať fórum',
	'move_topics_and_posts_to' => 'Premiestniť témy a príspevky',
	'delete_topics_and_posts' => 'Vymazať témy a príspevky',
	'parent_forum' => 'Parent Forum',
	'has_no_parent' => 'Has no parent',
	'forum_permissions' => 'Permissie fóra',
	'can_view_forum' => 'Môže vidieť fórum',
	'can_create_topic' => 'Môže vytvoriť príspevok',
	'can_post_reply' => 'Môže odpovedať v príspevku',
	'display_threads_as_news' => 'Zobraziť príspevok medzi novinkami?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description.',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => 'Category',
	
	// Admin Users and Groups page
	'users' => 'Uživatelia',
	'new_user' => 'Nový uživaťeľ',
	'created' => 'Vytvoriť',
	'user_deleted' => 'Uživateľ vymazaný',
	'validate_user' => 'Overený uživateľ',
	'update_uuid' => 'Aktualizovať UUID',
	'unable_to_update_uuid' => 'Nieje možné aktualizovať UUID.',
	'update_mc_name' => 'Aktualizovať Minecraft nick',
	'reset_password' => 'Reset hesla',
	'punish_user' => 'Trestajúci účet',
	'delete_user' => 'Zmazať účet',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Adresa',
	'ip' => 'IP:',
	'other_actions' => 'Ďalšie akcie:',
	'disable_avatar' => 'Vypnuť avatary',
	'enable_avatar' => 'Enable avatar',
	'confirm_user_deletion' => 'Vážne chceš vymazať účet {x}?', // Don't replace "{x}"
	'groups' => 'Skupiny',
	'group' => 'Skupina',
	'new_group' => 'Nová skupina',
	'id' => 'ID',
	'name' => 'Meno',
	'create_group' => 'Vytvoriť skupinu',
	'group_name' => 'Meno skupiny',
	'group_html' => 'Skupinové HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>Toto je id donatu z Buycraftu, MinecraftMarketu alebo MCStocku.</p><p>Nemusí to byť vyplnené.</p>',
	'donor_group_instructions' => 	'<p>Donatorske skupiny byť vytvorené v poradí <strong>od najnižšiej až po najvyššiu hodnotu</strong>.</p>
									<p>Napríklad donate 10€ musí byť pred donatom 20€.</p>',
	'delete_group' => 'Vymazať skupinu',
	'confirm_group_deletion' => 'Vážne chceš vymazať skupinu {x}?', // Don't replace "{x}"
	'group_staff' => 'Je to admin skupina?',
	'group_modcp' => 'Môže skupina používať ModCP?',
	'group_admincp' => 'Môže skupina používať AdminCP?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'The group name must be a minimum of 2 characters.',
	'group_name_maximum' => 'The group name must be a maximum of 20 characters.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'The user must be in a group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft nastavenia',
	'use_plugin' => 'Použiť nameless minecraft plugin?',
	'force_avatars' => 'Použiť Minecraft avatary?',
	'uuid_linking' => 'Aktivovať spájanie UUID?',
	'use_plugin_help' => 'Použitie pluginu umožňuje synchronizáciu hodnosti a tiež aj registráciu v hre.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Nastavenie pluginu',
	'confirm_api_regen' => 'Vážne chceš vygenerovať nový API kľúč?',
	'servers' => 'Servery',
	'new_server' => 'Nový server',
	'confirm_server_deletion' => 'Vážne chceš vymazať tento server ??',
	'main_server' => 'Hlavný server',
	'main_server_help' => 'The server players connect through. Normally this will be the Bungee instance.',
	'choose_a_main_server' => 'Vyber hlavný server..',
	'external_query' => 'Use external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Upraviť server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'IP serveru (s portom) (numerická alebo doménová)',
	'server_ip_with_port_help' => 'This is the IP which will be displayed to users. It will not be queried.',
	'server_ip_numeric' => 'IP serveru (s portom) (iba numerická)',
	'server_ip_numeric_help' => 'This is the IP which will be queried, please ensure it is numeric only. It will not be displayed to users.',
	'show_on_play_page' => 'Zobrazovať na stránke "Hrať"?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Meno servera',
	'invalid_server_id' => 'Invalid server ID',
	'show_players' => 'Zobraziť list hráčov na stránke "Hrať"?',
	'server_edited' => 'Server upravený úspešne',
	'server_created' => 'Server vytvorený úspešne',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'Nasledujúce chyby vám umožnia diagnostikovať problémy s internal server errorom.',
	'no_query_errors' => 'Žiadne query chyby niesu nahlásené',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Zobraziť error',
	'confirm_error_deletion' => 'Vážne chceš zmazať tento eror?',
	'display_server_status' => 'Zobraziť server status model?',
	'server_name_required' => 'You must insert a server name.',
	'server_ip_required' => 'You must insert the server\'s IP.',
	'server_name_minimum' => 'The server name must be a minimum of 2 characters.',
	'server_ip_minimum' => 'The server IP must be a minimum of 2 characters.',
	'server_name_maximum' => 'The server name must be a maximum of 20 characters.',
	'server_ip_maximum' => 'The server IP must be a maximum of 64 characters.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Témy',
	'templates' => 'Templates',
	'installed_themes' => 'Inštálované témy',
	'installed_templates' => 'Inštálované šablony',
	'installed_addons' => 'Inštálované pluginy',
	'install_theme' => 'Inštalovať tému',
	'install_template' => 'Inštalovať šablonu',
	'install_addon' => 'Inštalovať plugin',
	'install_a_theme' => 'Inštalovať tému',
	'install_a_template' => 'Inštalovať šablonu',
	'install_an_addon' => 'Inštalovať plugin',
	'active' => 'Aktívne',
	'activate' => 'Aktivovať',
	'deactivate' => 'Deaktivovať',
	'theme_install_instructions' => 'Prosím nahraj témy do <strong>styles/themes</strong> priečinku. Potom,klikni na tlačítko "scan".',
	'template_install_instructions' => 'Prosím nahraj šablonu do <strong>styles/templates</strong> priečinku. Potom klikni na tlačítko "scan".',
	'addon_install_instructions' => 'Prosím nahraj plugin do <strong>addons</strong> priečinku. Potom klikni na tlačítko "scan".',
	'addon_install_warning' => 'Pluginy sú inštalované na vlastné nebezpečenstvo. Prosím zálohuj si súbory pred pokračovaním',
	'scan' => 'Scan',
	'theme_not_exist' => 'Táto téma neexistuje!',
	'template_not_exist' => 'Táto šablona neexistuje!',
	'addon_not_exist' => 'Tento plugin neexistuje!',
	'style_scan_complete' => 'Dokončené,boli nainštalované nové styly.',
	'addon_scan_complete' => 'Dokončené,boli nainštalované nové styly.',
	'theme_enabled' => 'Téma povolená.',
	'template_enabled' => 'Šablona povolená.',
	'addon_enabled' => 'Plugin povolený.',
	'theme_deleted' => 'téma zmazaná.',
	'template_deleted' => 'Šablona zmazaná.',
	'addon_disabled' => 'plugin vypnutý.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Vážne chceš zmazať túto tému <strong>{x}</strong>?<br /><br />Téma bude vymazaná z <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Vážne chceš zmazať túto šablonu <strong>{x}</strong>?<br /><br />Šablona bude vymazaná z <strong>styles/templates</strong> directory.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Ďalšie nastavenia',
	'enable_error_reporting' => 'Povoliť nahlašovací eror?',
	'error_reporting_description' => 'To by sa malo použiť len na účely ladenia, je vysoko odporúčané nechať to ako zakázané.',
	'display_page_load_time' => 'Zobraziť čas načítania stránky?',
	'page_load_time_description' => 'Pokiaľ je to povolené je možné mať speedmeter v päte stránky, v ktorom sa zobrazí čas načítania stránky.',
	'reset_website' => 'Resetovanie webu',
	'reset_website_info' => 'To resetuje nastavenievašej webovej stránky. <strong>Pokiaľ boli pluginy vypnuté, ale neboli odstránené,ich nastavenie sa nezmení.</strong> Vaše definované minecraft serveri tiež zostanú.',
	'confirm_reset_website' => 'Vážne chceš resetovať nastavenie webu?',
	
	// Admin Update page
	'installation_up_to_date' => 'Your installation is up to date.',
	'update_check_error' => 'Unable to check for updates. Please try again later.',
	'new_update_available' => 'A new update is available.',
	'your_version' => 'Your version:',
	'new_version' => 'New version:',
	'download' => 'Download',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Domov',
	'play' => 'Hrať',
	'forum' => 'Forum',
	'more' => 'Dalšie',
	'staff_apps' => 'Staff App',
	'view_messages' => 'Zobraziť správy',
	'view_alerts' => 'Zobraziť upozornenia',
	
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
	'create_an_account' => 'Vytvorenie účtu',
	'username' => 'Meno',
	'minecraft_username' => 'Minecraft nick',
	'email' => 'Email',
	'email_address' => 'Emailová adresa',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Heslo',
	'confirm_password' => 'Potvrdiť heslo',
	'i_agree' => 'Suhlasím',
	'agree_t_and_c' => 'Kliknutím na <strong class="label label-primary">Registracia</strong>, Súhlasíte s <a href="#" data-toggle="modal" data-target="#t_and_c_m">Všeobecnými podmienkamy.</a>.',
	'register' => 'Registracia',
	'sign_in' => 'Prihlásenie',
	'sign_out' => 'Odhlásiť',
	'terms_and_conditions' => 'Všeobecné pravidla',
	'successful_signin' => 'Bol si prihlášený!',
	'incorrect_details' => 'Zlé detaili',
	'remember_me' => 'Pamätať',
	'forgot_password' => 'Zabudnuté heslo',
	'must_input_username' => 'Musíte vložiť uživatelské meno.',
	'must_input_password' => 'Musíte vložiť uživatelské heslo.',
	'inactive_account' => 'Tvoj účet je deaktivovaný. Mrkni sa na email :).',
	'account_banned' => 'Tvoj účet bol zabanovaný.',
	'successfully_logged_out' => 'Bol si odhlásený.',
	'signature' => 'Podpis',
	'registration_check_email' => 'Pozri sa na mail, a klikni na overovací odkaz.',
	'unknown_login_error' => 'Je nám ľúto, vyskystla sa neznáma chyba počas prihlasovania.Skús to prosim neskôr.',
	'validation_complete' => 'Ďakujeme za registráciu.Teraz sa môžeš prihlásiť',
	'validation_error' => 'Chyba pri spracovaní vašej požiadavky.Prosím skús to ešte raz.',
	'registration_error' => 'Prosím, ujistite sa, že ste vyplnili každé pole, a že vaše uživatelské méno je dlhé 3 až 20 znakov a heslo je dlhé 6 až 30 znakov.',
	'username_required' => 'Prosím zadaj prihlasovacie meno.',
	'password_required' => 'Prosím zadaj prihlasovacie heslo.',
	'email_required' => 'Prosím zadaj e-mailovú adresu.',
	'mcname_required' => 'Prosím zadaj tvoj minecraft nick.',
	'accept_terms' => 'Pred registráciou musíte prijať podmienky registrácie.',
	'invalid_recaptcha' => 'Zle zadaný kod reCAPTCHA.',
	'username_minimum_3' => 'Tvoje meno musí mať minimálne 3 znaky.',
	'username_maximum_20' => 'Tvoje meno môže mať dĺžku maximálne 20 znakov.',
	'mcname_minimum_3' => 'Tvoje minecraft meno musí mať minimálne 3 znaky.',
	'mcname_maximum_20' => 'Tvoj minecraft nick môže mať dĺžku maximálne 20 znakov.',
	'password_minimum_6' => 'Tvoje heslo musí mať dĺžku minimálne 6 znakov.',
	'password_maximum_30' => 'Tvoje heslo môže mať dĺžku maximálne 30 znakov.',
	'passwords_dont_match' => 'Hesla sa nezhodujú.',
	'username_mcname_email_exists' => 'Tvoje prihlasovacie meno, Minecraft nick alebo emailová adresa už existuje. Máš už vytvorený účet?',
	'invalid_mcname' => 'Vaše zadané minecraft meno nieje platné',
	'mcname_lookup_error' => 'Došlo k chybe pri kontaktovaní mojang serveru.Skús to prosím neskôr.',
	'signature_maximum_900' => 'Your signature must be a maximum of 900 characters.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
	
	// UserCP
	'user_cp' => 'Uživ. menu',
	'no_file_chosen' => 'Neboli vybrané súbory',
	'private_messages' => 'Súkromné správy',
	'profile_settings' => 'Nastavenie profilu',
	'your_profile' => 'Tvoj profil',
	'topics' => 'Témy',
	'posts' => 'Príspevky',
	'reputation' => 'Reputácia',
	'friends' => 'Priatelia',
	'alerts' => 'Upozornenia',
	
	// Messaging
	'new_message' => 'Nová správa',
	'no_messages' => 'Žiadne správy',
	'and_x_more' => 'a {x} dalšie', // Don't replace "{x}"
	'system' => 'Systém',
	'message_title' => 'Názov správy',
	'message' => 'Správa',
	'to' => 'Komu:',
	'separate_users_with_comma' => 'Mena oddeľuj čiarkov (",")',
	'viewing_message' => 'Zobraziť správu',
	'delete_message' => 'Vymazať správu',
	'confirm_message_deletion' => 'Vážne chceš vymazať túto správu?',
	
	// Profile settings
	'display_name' => 'Meno zobrazované ako',
	'upload_an_avatar' => 'Uploadni avatara (.jpg, .png or .gif only):',
	'use_gravatar' => 'Používať Gravatar?',
	'change_password' => 'Zmeniť heslo',
	'current_password' => 'Aktuálne heslo',
	'new_password' => 'Nové heslo',
	'repeat_new_password' => 'Zopakuj nové heslo',
	'password_changed_successfully' => 'Heslo úspešne zmenené.',
	'incorrect_password' => 'Vaše aktuálne heslo ste zadali nesprávne.',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Display age on profile?',
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
	'viewing_unread_alerts' => 'Zobraziť neprečítané upozornenia. Zmeniť na <a href="/user/alerts/?view=read"><span class="label label-success">čitať</span></a>.',
	'viewing_read_alerts' => 'Zobraziť prečítané upozornenia. Zmeniť na <a href="/user/alerts/"><span class="label label-warning">neprečítané</span></a>.',
	'no_unread_alerts' => 'Nemáš žiadne neprečítané upozornenia.',
	'no_alerts' => 'Žiadne upozornenia',
	'no_read_alerts' => 'Nemáš žiadne prečítané upozornenia.',
	'view' => 'Zobrazit',
	'alert' => 'Oznámenie',
	'when' => 'Kedy',
	'delete' => 'Vymazať',
	'tag' => 'Štítok uživateľa',
	'tagged_in_post' => 'Bol si označený v príspevku',
	'report' => 'Nahlásenie',
	'deleted_alert' => 'Upozornenie úspešne zmazané',
	
	// Warnings
	'you_have_received_a_warning' => 'Bol si varovaný adminom {x} datum {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Heslo resetované',
	'email_body' => 'Tento e-mail vám bol odosladný z dôvodu že ste si požiadali o resetovanie hesla. Aby bolo možné obnoviť heslo, použite následujúci odkaz:', // Body for the password reset email
	'email_body_2' => 'Ak ste si resetovanie hesla nevyžiadali, môžete tento e-mail ignorovať.',
	'password_email_set' => 'Úspešne. Prosím pozri sa na e-mail pre dalšie inštrukcie.',
	'username_not_found' => 'Toto meno už existuje.',
	'change_password' => 'Zmeniť heslo',
	'your_password_has_been_changed' => 'Tvoje heslo bolo zmenené.',
	
	// Profile page
	'profile' => 'Profil',
	'player' => 'Hráč',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registrovaný:',
	'pf_posts' => 'Príspevky:',
	'pf_reputation' => 'Hodnotenie:',
	'user_hasnt_registered' => 'Tento uživateľ není registrovaný na tejto stránke.',
	'user_no_friends' => 'Tento uživatel nemá žádné přátele.',
	'send_message' => 'Poslat správu',
	'remove_friend' => 'Vymazať priateľa',
	'add_friend' => 'Pridať priateľa',
	'last_online' => 'Naposledy Online:',
	'find_a_user' => 'Find a user',
	'user_not_following' => 'This user does not follow anyone.',
	'user_no_followers' => 'This user has no followers.',
	'following' => 'FOLLOWING',
	'followers' => 'FOLLOWERS',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Write something on your profile...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'About',
	'reply' => 'Reply',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'No likes.',
	'post_liked' => 'Post liked.',
	'post_unliked' => 'Post unliked.',
	'no_posts' => 'No posts.',
	'last_5_posts' => 'Last 5 posts',
	'follow' => 'Follow',
	'unfollow' => 'Unfollow',
	
	// Staff applications
	'staff_application' => 'StaffApp',
	'application_submitted' => 'Prihláška úspešne odoslaná.',
	'application_already_submitted' => 'Už ste podali jednu žiadosť. Prosím počkajte kým skontrolujeme ostatné prihlášky.',
	'not_logged_in' => 'Prosím prihláste sa pre zobrazenie tejto stránky.',
	'application_accepted' => 'Tvoja prihláška bola prijatá.',
	'application_rejected' => 'Tvojá prihláška bola neprijatá.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'Moderátor',
	'overview' => 'Prehľad',
	'reports' => 'Nahlásenia',
	'punishments' => 'Tresty',
	'staff_applications' => 'StaffApp',
	
	// Punishments
	'ban' => 'Zabanovať',
	'unban' => 'Odbanovať',
	'warn' => 'Varovať',
	'search_for_a_user' => 'Hľadať uživateľov',
	'user' => 'Uživateľ:',
	'ip_lookup' => 'IP:',
	'registered' => 'Registrovaný',
	'reason' => 'Dôvod:',
	
	// Reports
	'report_closed' => 'Nahlásenie uzavreté.',
	'new_comment' => 'Nový komentár',
	'comments' => 'Komentáre',
	'only_viewed_by_staff' => 'Toto môže vidieť iba adminitrátor',
	'reported_by' => 'Nahlásil',
	'close_issue' => 'Blízky problém',
	'report' => 'Nahlásenie:',
	'view_reported_content' => 'Zobrazit nahlásený príspevok',
	'no_open_reports' => 'Žiadné otvorené nahlásenia',
	'user_reported' => 'Uživateľové nahlásenie',
	'type' => 'Typ',
	'updated_by' => 'Updatoval',
	'forum_post' => 'Príspevok z fóra',
	'user_profile' => 'Profil uživateľa',
	'comment_added' => 'Komentár pridaný',
	'new_report_submitted_alert' => 'Nové nahlásenie predložené {x} týkajúce sa {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Uistite sa prosím, že váš komentár má dĺžku medzi 2 až 2048 znakov.',
	'viewing_open_applications' => 'Zobrazenie <span class="label label-info">otvorených</span> prihlášok. Zmeniť na <a href="/mod/applications/?view=accepted"><span class="label label-success">Prijaty</span></a> alebo <a href="/mod/applications/?view=declined"><span class="label label-danger">Neprijatý</span></a>.',
	'viewing_accepted_applications' => 'Zobrazenie <span class="label label-success">prijatých</span> prihlášok. Zmeniť na <a href="/mod/applications/"><span class="label label-info">Otvorene</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">Neprijatý</span></a>.',
	'viewing_declined_applications' => 'Zobrazenie <span class="label label-danger">Neprijatých</span> prihlášok. Zmeniť na <a href="/mod/applications/"><span class="label label-info">Otvorene</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">Prijatý</span></a>.',
	'time_applied' => 'Aplikované',
	'no_applications' => 'Žiadne ďalšie prihlášky v tejto kategórii',
	'viewing_app_from' => 'Zobrazená prihláška od {x}', // Don't replace "{x}"
	'open' => 'Otvorené',
	'accepted' => 'Prijaté',
	'declined' => 'Neprijatý',
	'accept' => 'Prijať',
	'decline' => 'Neprijať',
	'new_app_submitted_alert' => 'Nová žiadosť predložená hráčom {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Novinky',
	'social' => 'Social',
	'join' => 'Join',
	
	// General terms
	'submit' => 'Odoslať',
	'close' => 'Zavrieť',
	'cookie_message' => '<strong>Tato stránka využíva cookies</strong><p>Pokračováním vyjadrujete súhlas s jejím používaním.</p>',
	'theme_not_exist' => 'Vybraná šablona neexistuje.',
	'confirm' => 'Potvrdit',
	'cancel' => 'Ukončiť',
	'guest' => 'Hosť',
	'guests' => 'Hostia',
	'back' => 'Späť',
	'search' => 'Hľadať',
	'help' => 'Pomoc',
	'success' => 'Úspešne',
	'error' => 'Chyba',
	'view' => 'Zobraziť',
	'info' => 'Info',
	'next' => 'Next',
	
	// Play page
	'connect_with' => 'Pripoj sa cez IP {x}.', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Hráčov online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Žiadny hráči online!',
	'x_players_online' => 'Na serveri je {x} hráčov online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Stránka načítaná za {x} sekund', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'Sorry, we couldn\'t find that page.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forum',
	'discussion' => 'Diskusia',
	'stats' => 'Štatistiky',
	'last_reply' => 'Posledný príspevok',
	'ago' => 'pred',
	'by' => 'od',
	'in' => 'v',
	'views' => 'zobrazenie',
	'posts' => 'príspevku',
	'topics' => 'tém',
	'topic' => 'Téma',
	'statistics' => 'Štatistiky',
	'overview' => 'Prehľad',
	'latest_discussions' => 'Posledná diskusia',
	'latest_posts' => 'Posledný príspevok',
	'users_registered' => 'Registrovaných uživateľov:',
	'latest_member' => 'Posledný registrovaný:',
	'forum' => 'Fórum',
	'last_post' => 'Posledný príspevok',
	'no_topics' => 'Nenajdené žiadne príspevky',
	'new_topic' => 'Nová téma',
	'subforums' => 'Subfóra:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Téma je zamknutá',
	'new_reply' => 'Nová odpoveď',
	'mod_actions' => 'Akcie moderátora',
	'lock_thread' => 'Zamknuť tému',
	'unlock_thread' => 'Odomknuť tému',
	'merge_thread' => 'Zlúciť téma',
	'delete_thread' => 'Vymazať tému',
	'confirm_thread_deletion' => 'Vážne chcete vymazať túto tému?',
	'move_thread' => 'Premiestniť tému',
	'sticky_thread' => 'Pripnúť tému',
	'report_post' => 'Nahlásiť príspevok',
	'quote_post' => 'Citovať príspevok',
	'delete_post' => 'Vymazať príspevok',
	'edit_post' => 'Upraviť príspevok',
	'reputation' => 'Hodnotenie',
	'confirm_post_deletion' => 'Vážne chceš vymazať tento príspevok?',
	'give_reputation' => 'Ohodnotiť',
	'remove_reputation' => 'Vymazať hodnotenie',
	'post_reputation' => 'Hodnotenie príspevku',
	'no_reputation' => 'Žiadne hodnotenie pre tento príspevok.',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Vytvoriť príspevok',
	'post_submitted' => 'Príspevok odoslaný',
	'creating_post_in' => 'Vytvárate príspevok v: ',
	'topic_locked_permission_post' => 'Táto téma je zamknutá. Nemôžete odpovedať, ani posielať príspevky.',
	
	// Edit post view
	'editing_post' => 'Úprava príspevku',
	
	// Sticky threads
	'thread_is_' => 'Téma je ',
	'now_sticky' => 'Téma je teraz pripnutá',
	'no_longer_sticky' => 'Niesu žiadne témy na pripnutie',
	
	// Create topic
	'topic_created' => 'Téma vytvorená.',
	'creating_topic_in_' => 'Vytvárate téma v ',
	'thread_title' => 'Názov témy',
	'confirm_cancellation' => 'Vážne?',
	'label' => 'Štítok',
	
	// Reports
	'report_submitted' => 'Nahlasenie predložené.',
	'view_post_content' => 'Zobraziť obsah príspevku',
	'report_reason' => 'Ohlásit dôvod',
	
	// Move thread
	'move_to' => 'Premiestniť do:',
	
	// Merge threads
	'merge_instructions' => 'téma sa <strong>must</strong> zlučiť len s temou v rovnakom fóre. Podľa potreby si môžeš tému preniesť.',
	'merge_with' => 'Zlúčiť s:',
	
	// Other
	'forum_error' => 'Je nám ľúto, ale nenašli sa žiadne podobné fóra ani témy.',
	'are_you_logged_in' => 'Ste prihlásený?',
	'online_users' => 'Online uživatelia',
	'no_users_online' => 'Žiadny uživateľ není online.',
	
	// Search
	'search_error' => 'Zadajte prosím vyhľadávací dotaz medzi 1 az 32 znakov.',
	
	//Share on a social-media.
	'sm-share' => 'podiel',
	'sm-share-facebook' => 'Zdielať na Facebook-u',
	'sm-share-twitter' => 'Zdieľať na Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Dobrý deň',
	'message' => 'Ďakujeme že ste sa registrovali! Potvrďte svoju registráciu kliknutím na následujúci odkaz:',
	'thanks' => 'Dakujeme.'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 'sec', // Shortened "seconds", eg "sec"
	'less_than_a_minute' => 'Pred minútov',
	'1_minute' => 'pred chvíľov',
	'_minutes' => 'pred {x} minutami',
	'about_1_hour' => 'pred hodinou',
	'_hours' => 'pred {x} hodinami',
	'1_day' => 'pred 1 dňom',
	'_days' => 'před {x} dňami',
	'about_1_month' => 'pred mesiacom',
	'_months' => 'pred {x} mesiacmi',
	'about_1_year' => 'pred rokom',
	'over_x_years' => 'pred {x} rokmi'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Display _MENU_ records per page', // Don't replace "_MENU_"
	'nothing_found' => 'Neboli najdené žiadne vysledky',
	'page_x_of_y' => 'stránka _PAGE_ z _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'K dispozícii niesu žiadné záznami',
	'filtered' => '(filtered from _MAX_ total records)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Complete Registration'
);
 
?>
