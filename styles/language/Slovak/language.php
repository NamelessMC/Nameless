<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  Slovak language by Marki35, edited by mrmiijo
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'Administrácia', 
	'infractions' => 'Priestupky',
	'invalid_token' => 'Zle dáta, skúste to prosím znovu.',
	'invalid_action' => 'Zlá akcia',
	'successfully_updated' => 'Úspešne aktualizované',
	'settings' => 'Nastavenia',
	'confirm_action' => 'Potvrdiť akciu',
	'edit' => 'Upraviť',
	'actions' => 'Akcia',
	'task_successful' => 'Úloha úspešne spustená',
	
	// Admin login
	're-authenticate' => 'Prosím over sa opätovným prihlásením...',
	
	// Admin sidebar
	'index' => 'Prehľad',
	'announcements' => 'Upozornenia',
	'core' => 'Základné',
	'custom_pages' => 'Stránky',
	'general' => 'Hlavné',
	'forums' => 'Fóra',
	'users_and_groups' => 'Uživatelia a skupiny',
	'minecraft' => 'Minecraft',
	'style' => 'Štýly',
	'addons' => 'Doplnky',
	'update' => 'Aktualizácie',
	'misc' => 'Ostatné',
	'help' => 'Pomoc',
	
	// Admin index page
	'statistics' => 'Štatistiky',
	'registrations_per_day' => 'Registracie za deň (posledných 7 dní)',
	
	// Admin announcements page
	'current_announcements' => 'Aktuálne upozornenia',
	'create_announcement' => 'Vytvoriť upozornenie',
	'announcement_content' => 'Obsah upozornenia',
	'announcement_location' => 'Umiestnenie',
	'announcement_can_close' => 'Môže byť upozornenie zatvorené?',
	'announcement_permissions' => 'Práva upozornení',
	'no_announcements' => 'Žiadne vytvorené upozornenia',
	'confirm_cancel_announcement' => 'Naozaj chceš zrušiť toto upozornenie?',
	'announcement_location_help' => 'Ctrl - klik pre označenie viacerých umiestnení',
	'select_all' => 'Označiť všetko',
	'deselect_all' => 'Odznačiť všetko',
	'announcement_created' => 'Upozornenie bolo úspešne vytvorené',
	'please_input_announcement_content' => 'Prosím vyplň obsah upozornenia a vyber typ',
	'confirm_delete_announcement' => 'Naozaj chceš odstrániť toto upozornenie?',
	'announcement_actions' => 'Možnosti',
	'announcement_deleted' => 'Upozornenie bolo úspešne odstranené',
	'announcement_type' => 'Typ upozornenia',
	'can_view_announcement' => 'Môže vidieť upozornenie?',
	
	// Admin core page
	'general_settings' => 'Hlavné nastavenie',
	'modules' => 'Moduly',
	'module_not_exist' => 'Tento modul neexistuje!',
	'module_enabled' => 'Modul bol zapnutý',
	'module_disabled' => 'Modul bolvypnutý',
	'site_name' => 'Názov stránky',
	'language' => 'Jazyk',
	'voice_server_not_writable' => 'core/voice_server.php Nieje zapisovateľný. Prosim skontroluj práva priečinka.',
	'email' => 'E-mail',
	'incoming_email' => 'Prichádzajúca e-mailová adresa',
	'outgoing_email' => 'Odchádzajúca e-mailová adresa',
	'outgoing_email_help' => 'Žiadá sa len vtedy, ak je povolená funkcia php mail',
	'use_php_mail' => 'Použiť funkciu php mail()?',
	'use_php_mail_help' => 'Odporúčané: Povolené Ak vaše webové stránky nemajú odosielanie e-mailov, zakážte to a upravte core/email.php s vašeho e-mailového nastavenia',
	'use_gmail' => 'Použivať Gmail pre odosielanie e-mailov',
	'use_gmail_help' => 'K dispozícii je iba ak je funkcia php mail vypnutá. Pokiaľ sa rozhodnete gmail nepoužívať, bude použitý SMTP. Tak či onak, to bude vyžadovať zodpovedajúce konfigúrácie: core/email.php.',
	'enable_mail_verification' => 'Povoliť overovanie účtu cez e-mail?',
	'enable_email_verification_help' => 'Pokiaľ bude toto povolené, bude požadované od novo registrovaných uživateľov overenie e-mailu pred dokončením registracie.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Stránky',
	'enable_or_disable_pages' => 'Aktivuj alebo zablokuj stránky tu.',
	'enable' => 'Aktivovať',
	'disable' => 'Zablokovať',
	'maintenance_mode' => 'Mód údržby fóra',
	'forum_in_maintenance' => 'Fórum je v móde údržby!',
	'unable_to_update_settings' => 'Chyba aktualizovania nastavení. Skontroluj, či je všetko vyplnené!',
	'editing_google_analytics_module' => 'Upravuješ Google Analytics modul',
	'tracking_code' => 'Tracking kód',
	'tracking_code_help' => 'Vlož tracking kód pre Google Analytics tu, obsahujúci značky skriptu.',
	'google_analytics_help' => 'Pozri <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">tento návod</a> pre viac informácií, kroky 1 až 3.',
	'social_media_links' => 'Odkazy sociálnych sietí',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Použiť tmavú tému Twitteru?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registrácia',
	'registration_warning' => 'Ak máš tento modul vypnutý, tiež je zakázaná registrácia nových uživateľov.',
	'google_recaptcha' => 'Povoliť Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site kľúč',
	'recaptcha_secret_key' => 'reCAPTCHA Secret kľúč',
	'registration_terms_and_conditions' => 'Registračné podmienky používania a ochrana osobných údajov',
	'voice_server_module' => 'Modul hlasových serverov',
	'only_works_with_teamspeak' => 'Tento modul funguje zatiaľ len so službou Discord a TeamSpeak!',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Prosím zadaj detaily server query uživateľa',
	'ip_without_port' => 'IP (bez portu)',
	'voice_server_port' => 'Port (zvyčajne 10011)',
	'virtual_port' => 'Virtual Port (zvyčajne 9987)',
	'permissions' => 'Práva:',
	'view_applications' => 'Povoliť zobrazenie žiadostí?',
	'accept_reject_applications' => 'Povoliť potvrdenie/zamietnutie žiadosti?',
	'questions' => 'Otázky:',
	'question' => 'Otázka',
	'type' => 'Typ',
	'options' => 'Možnosti',
	'options_help' => 'Každá možnosť na nový riadok; môžeš nechať prázne (len pre typ dropdown)',
	'no_questions' => 'Zatiaľ žiadne otázky.',
	'new_question' => 'Nová otázka',
	'editing_question' => 'Upravuješ otázku',
	'delete_question' => 'Odstrániť otázku',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'name_required' => 'Name is required.',
	'question_required' => 'Question is required.',
	'name_minimum' => 'Name must be a minimum of 2 characters.',
	'question_minimum' => 'Question must be a minimum of 2 characters.',
	'name_maximum' => 'Name must be a maximum of 16 characters.',
	'question_maximum' => 'Question must be a maximum of 16 characters.',
	'textarea' => 'Dlhý text',
	'question_deleted' => 'Otázka bola odstránená',
	'use_followers' => 'Použiť sledovateľov?',
	'use_followers_help' => 'Ak je vypnuté, bude sa použivať systém priateľov.',
	
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
	'page_permissions' => 'Práva stránky',
	'can_view_page' => 'Môže vidieť stránku:',
	'redirect_page' => 'Presmerovať stránku?',
	'redirect_link' => 'Presmerovací odkaz',
	'page_icon' => 'Ikona stránky',
	
	// Admin forum page
	'labels' => 'Štítky tém',
	'new_label' => 'Nový štítok',
	'no_labels_defined' => 'Žiadne štítky neboli vytvorené.',
	'label_name' => 'Názov štítku',
	'label_type' => 'Typ štítku',
	'label_forums' => 'Zaradenie štítku',
	'label_creation_error' => 'Chyba pri vytváraní štítku. Prosím uistite sa že názov nie je dlhší ako 32 znakov a že ste zadali špecifický typ.',
	'confirm_label_deletion' => 'Vážne chceš vymazať tento štítok?',
	'editing_label' => 'Upraviť štítok',
	'label_creation_success' => 'Štítok bol úspešne vytvorený',
	'label_edit_success' => 'Štítok bol úspešne upravený',
	'label_default' => 'Štandartné',
	'label_primary' => 'Primárny',
	'label_success' => 'Úspech',
	'label_info' => 'Info',
	'label_warning' => 'Varovanie',
	'label_danger' => 'Nebezpečné',
	'new_forum' => 'Nové fórum',
	'forum_layout' => 'Typ fóra',
	'table_view' => 'Výpis tabuliek',
	'latest_discussions_view' => 'Zobraziť posledné diskusie',
	'create_forum' => 'Vytvoriť fórum',
	'forum_name' => 'Názov fóra',
	'forum_description' => 'Popisok fóra',
	'delete_forum' => 'Vymazať fórum',
	'move_topics_and_posts_to' => 'Premiestniť témy a príspevky',
	'delete_topics_and_posts' => 'Vymazať témy a príspevky',
	'parent_forum' => 'Nadradené fórum',
	'has_no_parent' => 'Nemá žiadne nadradenie',
	'forum_permissions' => 'Práva fóra',
	'can_view_forum' => 'Môže vidieť fórum',
	'can_create_topic' => 'Môže vytvoriť príspevok',
	'can_post_reply' => 'Môže odpovedať v príspevku',
	'display_threads_as_news' => 'Zobraziť príspevok medzi novinkami?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description.',
	'forum_name_minimum' => 'Názov musí mať minimálne 2 znaky.',
	'forum_description_minimum' => 'Popis musí mať minimálne 2 znaky.',
	'forum_name_maximum' => 'Názov môže mať maximálne 255 znakov.',
	'forum_description_maximum' => 'Popis môže mať maximálne 255 znakov.',
	'forum_type_forum' => 'Diskuzné fórum',
	'forum_type_category' => 'Kategória',
	
	// Admin Users and Groups page
	'users' => 'Uživatelia',
	'new_user' => 'Nový uživaťeľ',
	'created' => 'Vytvoriť',
	'user_deleted' => 'Uživateľ bol vymazaný',
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
	'group2' => 'Skupina 2',
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
	'group_name_required' => 'Musíš napísať názov skupinu.',
	'group_name_minimum' => 'Názov musí mať najmenej 2 znaky.',
	'group_name_maximum' => 'Názov môže mať maximálne 20 znakov.',
	'html_maximum' => 'HTML môže mať maximálne 1024 znakov.',
	'select_user_group' => 'Uživateľ musí byť v skupine.',
	'uuid_max_32' => 'UUID môže mať maximálne 32 znakov.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft nastavenia',
	'use_plugin' => 'Použiť Nameless minecraft plugin?',
	'force_avatars' => 'Použiť Minecraft avatary?',
	'uuid_linking' => 'Aktivovať spájanie UUID?',
	'use_plugin_help' => 'Použitie pluginu umožňuje synchronizáciu hodnosti a tiež aj registráciu v hre.',
	'uuid_linking_help' => 'Pokiaľ je vypnuté, tvoje účty sa nebudú spájať s UUID. Je dôležité nechať toto zapnuté.',
	'plugin_settings' => 'Nastavenie pluginu',
	'confirm_api_regen' => 'Vážne chceš vygenerovať nový API kľúč?',
	'servers' => 'Servery',
	'new_server' => 'Nový server',
	'confirm_server_deletion' => 'Vážne chceš vymazať tento server?',
	'main_server' => 'Hlavný server',
	'main_server_help' => 'Tento server sa zobrazí navrchu. Väčšinou to býva bungee server.',
	'choose_a_main_server' => 'Vyber hlavný server..',
	'external_query' => 'Použiť external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Upraviť server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'IP serveru (s portom) (numerická alebo doménová)',
	'server_ip_with_port_help' => 'Táto IP sa bude zobrazovať hráčom.',
	'server_ip_numeric' => 'IP serveru (s portom) (iba numerická)',
	'server_ip_numeric_help' => 'Táto IP sa nebude zobrazovať hráčom. Je potrebné aby bola numerická s portom.',
	'show_on_play_page' => 'Zobrazovať na stránke "Hrať"?',
	'pre_17' => 'Staršie ako 1.7 minecraft?',
	'server_name' => 'Názov servera',
	'invalid_server_id' => 'Chybné ID servera',
	'show_players' => 'Zobraziť list hráčov na stránke "Hrať"?',
	'server_edited' => 'Server upravený úspešne',
	'server_created' => 'Server vytvorený úspešne',
	'query_errors' => 'Query errory',
	'query_errors_info' => 'Nasledujúce chyby vám umožnia diagnostikovať problémy s internal server errorom.',
	'no_query_errors' => 'Žiadne query chyby niesu nahlásené',
	'date' => 'Dátum:',
	'port' => 'Port:',
	'viewing_error' => 'Zobraziť error',
	'confirm_error_deletion' => 'Vážne chceš zmazať tento eror?',
	'display_server_status' => 'Zobraziť server status model?',
	'server_name_required' => 'Musíš zadať názov servera',
	'server_ip_required' => 'Musíš vložiť IP servera.',
	'server_name_minimum' => 'Názov musí mať najmenej 2 znaky.',
	'server_ip_minimum' => 'IP musí mať najmenej 2 znaky.',
	'server_name_maximum' => 'Názov môže mať najviac 20 znakov.',
	'server_ip_maximum' => 'IP môže mať najviac 64 znakov.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	'avatar_type' => 'Avatar type',
	'custom_usernames' => 'Force Minecraft usernames?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Use mcassoc?',
	'use_mcassoc_help' => 'mcassoc ensures users own the Minecraft account they\'re registering with',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Invalid mcassoc key.',
	'mcassoc_instance' => 'mcassoc Instance',
	'mcassoc_instance_help' => 'Generate an instance code <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">here</a>',
	'mcassoc_key_help' => 'Get your mcassoc key <a href="https://mcassoc.lukegb.com/" target="_blank">here</a>',
	'enable_name_history' => 'Enable profile username history?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Témy',
	'templates' => 'Šablóny',
	'installed_themes' => 'Inštálované témy',
	'installed_templates' => 'Inštálované šablóny',
	'installed_addons' => 'Inštálované pluginy',
	'install_theme' => 'Inštalovať tému',
	'install_template' => 'Inštalovať šablónu',
	'install_addon' => 'Inštalovať plugin',
	'install_a_theme' => 'Inštalovať tému',
	'install_a_template' => 'Inštalovať šablónu',
	'install_an_addon' => 'Inštalovať plugin',
	'active' => 'Aktívované',
	'activate' => 'Aktivovať',
	'deactivate' => 'Deaktivovať',
	'theme_install_instructions' => 'Prosím nahraj témy do <strong>styles/themes</strong> priečinku. Potom,klikni na tlačítko "Skenovať".',
	'template_install_instructions' => 'Prosím nahraj šablonu do <strong>styles/templates</strong> priečinku. Potom klikni na tlačítko "Skenovať".',
	'addon_install_instructions' => 'Prosím nahraj plugin do <strong>addons</strong> priečinku. Potom klikni na tlačítko "Skenovať".',
	'addon_install_warning' => 'Pluginy sú inštalované na vlastné nebezpečenstvo. Prosím zálohuj si súbory pred pokračovaním',
	'scan' => 'Skenovať',
	'theme_not_exist' => 'Táto téma neexistuje!',
	'template_not_exist' => 'Táto šablona neexistuje!',
	'addon_not_exist' => 'Tento plugin neexistuje!',
	'style_scan_complete' => 'Dokončené, boli nainštalované nové štýly.',
	'addon_scan_complete' => 'Dokončené, boli nainštalované nové štýly.',
	'theme_enabled' => 'Téma bola povolená.',
	'template_enabled' => 'Šablóna bola povolená.',
	'addon_enabled' => 'Plugin bol povolený.',
	'theme_deleted' => 'Téma bola zmazaná.',
	'template_deleted' => 'Šablóna bola zmazaná.',
	'addon_disabled' => 'Plugin bol vypnutý.',
	'inverse_navbar' => 'Zmeniť vrchný bar',
	'confirm_theme_deletion' => 'Vážne chceš zmazať túto tému <strong>{x}</strong>?<br /><br />Téma bude vymazaná z <strong>styles/themes</strong> priečinku.', // Don't replace {x}
	'confirm_template_deletion' => 'Vážne chceš zmazať túto šablónu <strong>{x}</strong>?<br /><br />Šablóna bude vymazaná z <strong>styles/templates</strong> priečinku.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Ďalšie nastavenia',
	'enable_error_reporting' => 'Povoliť nahlasovací eror?',
	'error_reporting_description' => 'To by sa malo použiť len na účely ladenia, je vysoko odporúčané nechať to ako zakázané.',
	'display_page_load_time' => 'Zobraziť čas načítania stránky?',
	'page_load_time_description' => 'Pokiaľ je to povolené je možné mať speedmeter v päte stránky, v ktorom sa zobrazí čas načítania stránky.',
	'reset_website' => 'Resetovanie webu',
	'reset_website_info' => 'To resetuje nastavenievašej webovej stránky. <strong>Pokiaľ boli pluginy vypnuté, ale neboli odstránené,ich nastavenie sa nezmení.</strong> Vaše definované minecraft serveri tiež zostanú.',
	'confirm_reset_website' => 'Vážne chceš resetovať nastavenie webu?',
	
	// Admin Update page
	'installation_up_to_date' => 'Verzia je aktuálna!',
	'update_check_error' => 'Chyba pri zistení aktualizácie. Skús to neskôr.',
	'new_update_available' => 'Dostupná nová verzia webu!',
	'your_version' => 'Tvoja verzia:',
	'new_version' => 'Nová verzia:',
	'download' => 'Stiahnuť',
	'update_warning' => 'Varovanie: Ensure you have downloaded the package and uploaded the contained files first!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Domov',
	'play' => 'Hrať',
	'forum' => 'Fórum',
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
	'username' => 'Nick',
	'minecraft_username' => 'Minecraft nick',
	'email' => 'E-mail',
	'user_title' => 'Title',
	'email_address' => 'Emailová adresa',
	'date_of_birth' => 'Dátum narodenia',
	'location' => 'Štát',
	'password' => 'Heslo',
	'confirm_password' => 'Potvrdiť heslo',
	'i_agree' => 'Suhlasím',
	'agree_t_and_c' => 'Kliknutím na <strong class="label label-primary">Registrácia</strong>, súhlasíte s <a href="#" data-toggle="modal" data-target="#t_and_c_m">podmienkami používania a ochrany osobných údajov</a>.',
	'register' => 'Registracia',
	'sign_in' => 'Prihlásenie',
	'sign_out' => 'Odhlásiť sa',
	'terms_and_conditions' => 'Podmienky používania a ochrana osobných údajov',
	'successful_signin' => 'Bol si prihlásený!',
	'incorrect_details' => 'Zlé detaily',
	'remember_me' => 'Zapamätať prihlásenie',
	'forgot_password' => 'Zabudnuté heslo',
	'must_input_username' => 'Musíte vložiť uživatelské meno.',
	'must_input_password' => 'Musíte vložiť uživatelské heslo.',
	'inactive_account' => 'Tvoj účet je deaktivovaný. Mrkni sa na email :).',
	'account_banned' => 'Tvoj účet bol zabanovaný.',
	'successfully_logged_out' => 'Bol si odhlásený.',
	'signature' => 'Podpis',
	'registration_check_email' => 'Registrácia: Pozri sa na mail a klikni na overovací odkaz.',
	'unknown_login_error' => 'Je nám ľúto, vyskystla sa neznáma chyba počas prihlasovania. Skús to prosim neskôr.',
	'validation_complete' => 'Ďakujeme za registráciu. Teraz sa môžeš prihlásiť',
	'validation_error' => 'Chyba pri spracovaní vašej požiadavky. Prosím skús to ešte raz.',
	'registration_error' => 'Prosím, ujistite sa, že ste vyplnili každé pole, a že vaše uživatelské méno je dlhé 3 až 20 znakov a heslo je dlhé 6 až 30 znakov.',
	'username_required' => 'Prosím zadaj prihlasovacie meno.',
	'password_required' => 'Prosím zadaj prihlasovacie heslo.',
	'email_required' => 'Prosím zadaj e-mailovú adresu.',
	'mcname_required' => 'Prosím zadaj tvoj minecraft nick.',
	'accept_terms' => 'Pred registráciou musíte prijať podmienky registrácie.',
	'invalid_recaptcha' => 'Zle zadaný kód reCAPTCHA.',
	'username_minimum_3' => 'Tvoje meno musí mať minimálne 3 znaky.',
	'username_maximum_20' => 'Tvoje meno môže mať dĺžku maximálne 20 znakov.',
	'mcname_minimum_3' => 'Tvoje minecraft meno musí mať minimálne 3 znaky.',
	'mcname_maximum_20' => 'Tvoj minecraft nick môže mať dĺžku maximálne 20 znakov.',
	'password_minimum_6' => 'Tvoje heslo musí mať dĺžku minimálne 6 znakov.',
	'password_maximum_30' => 'Tvoje heslo môže mať dĺžku maximálne 30 znakov.',
	'passwords_dont_match' => 'Hesla sa nezhodujú.',
	'username_mcname_email_exists' => 'Tvoje prihlasovacie meno, minecraft nick alebo emailová adresa už existuje. Máš už vytvorený účet?',
	'invalid_mcname' => 'Vaše zadané minecraft meno nieje platné',
	'mcname_lookup_error' => 'Došlo k chybe pri kontaktovaní mojang serveru. Skús to prosím neskôr.',
	'signature_maximum_900' => 'Podpis môže mať maximálne 900 znakov!',
	'invalid_date_of_birth' => 'Chybný dátum narodenia',
	'location_required' => 'Prosím napíš svoj štát',
	'location_minimum_2' => 'Štát musí mať najmenej 2 znaky.',
	'location_maximum_128' => 'Štát môže mať najviac 128 znakov.',
	'verify_account' => 'Overiť účet',
	'verify_account_help' => 'Please follow the instructions below so we can verify you own the Minecraft account in question.',
	'verification_failed' => 'Verification failed, please try again.',
	'verification_success' => 'Successfully validated! You can now log in.',
	'complete_signup' => 'Complete Signup',
	'registration_disabled' => 'Website registration is currently disabled.',
	
	// UserCP
	'user_cp' => 'Uživateľské menu',
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
	'no_messages' => '<small>Žiadne správy</small>',
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
	'upload_an_avatar' => 'Uploadni avatara (len .jpg, .png alebo .gif):',
	'use_gravatar' => 'Používať Gravatar?',
	'change_password' => 'Zmeniť heslo',
	'current_password' => 'Aktuálne heslo',
	'new_password' => 'Nové heslo',
	'repeat_new_password' => 'Zopakuj nové heslo',
	'password_changed_successfully' => 'Heslo úspešne zmenené.',
	'incorrect_password' => 'Vaše aktuálne heslo ste zadali nesprávne.',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Zobraziť vek na profile?',
	'two_factor_authentication' => 'Dvojité overovanie',
	'enable_tfa' => 'Povoliť dvojité overovanie',
	'tfa_type' => 'Typ dvojitého overovania:',
	'authenticator_app' => 'Overovacia aplikácia',
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
	'viewing_unread_alerts' => 'Zobrazujú sa neprečítané upozornenia. Zmeniť na <a href="/user/alerts/?view=read"><span class="label label-success">prečítané</span></a> upozornenia.',
	'viewing_read_alerts' => 'Zobrazujú sa prečítané upozornenia. Zmeniť na <a href="/user/alerts/"><span class="label label-warning">neprečítané</span></a> upozornenia.',
	'no_unread_alerts' => 'Nemáš žiadne neprečítané upozornenia.',
	'no_alerts' => '<small>Žiadne upozornenia</small>',
	'no_read_alerts' => 'Nemáš žiadne prečítané upozornenia.',
	'view' => 'Zobraziť',
	'alert' => 'Oznámenie',
	'when' => 'Kedy',
	'delete' => 'Vymazať',
	'tag' => 'Štítok uživateľa',
	'tagged_in_post' => 'Bol si označený v príspevku',
	'report' => 'Nahlásenie',
	'deleted_alert' => 'Upozornenie úspešne zmazané',
	
	// Warnings
	'you_have_received_a_warning' => 'Bol si varovaný adminom {x}, dátum {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Heslo bolo zresetované.',
	'email_body' => 'Tento e-mail vám bol odosladný z dôvodu že ste si požiadali o resetovanie hesla. Aby bolo možné obnoviť heslo, použite následujúci odkaz:', // Body for the password reset email
	'email_body_2' => 'Ak ste si resetovanie hesla nevyžiadali, môžete tento e-mail ignorovať.',
	'password_email_set' => 'Úspešne si zažiadal o zmenu hesla. Prosím pozri sa na e-mail pre dalšie inštrukcie.',
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
	'find_a_user' => 'Vyhľadaavť uživateľov',
	'user_not_following' => 'Tento uživateľ nikoho nesleduje.',
	'user_no_followers' => 'Tohto uživateľa nikto nesleduje.',
	'following' => 'Sleduje',
	'followers' => 'Sledujúci',
	'display_location' => 'Z {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, z {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Napíš niečo na profil hráča {x}...', // Don't replace {x}
	'write_on_own_profile' => 'Čo máte na mysli...',
	'profile_posts' => 'Profilové príspevky',
	'no_profile_posts' => 'Žiadne príspevky',
	'invalid_wall_post' => 'Text musí obsahovať medz 2 a 2048 znakov.',
	'about' => 'Informácie',
	'reply' => 'Komentovať',
	'x_likes' => '{x} Páči sa mi to', // Don't replace {x}
	'likes' => 'Páči sa mi to',
	'no_likes' => 'Nikto',
	'post_liked' => 'Príspevok bol označený, že sa ti páči.',
	'post_unliked' => 'Páči sa mi to bolo zrušené.',
	'no_posts' => 'Žiadne príspevky',
	'last_5_posts' => 'Posledných 5 príspevkov',
	'follow' => 'Sledovať',
	'unfollow' => 'Zrušiť sledovanie',
	'name_history' => 'História prezývok',
	'changed_name_to' => 'Zmenené meno z {x} na {y}', // Don't replace {x} or {y}
	'original_name' => 'Pôvodné meno:',
	'name_history_error' => 'Tento uživateľ si zatiaľ nezmenil prezývku.',
	
	// Staff applications
	'staff_application' => 'Formulár',
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
	'staff_applications' => 'Formuláre',
	
	// Punishments
	'ban' => 'Zabanovať',
	'unban' => 'Odbanovať',
	'warn' => 'Varovať',
	'search_for_a_user' => 'Hľadať uživateľov',
	'user' => 'Uživateľ:',
	'ip_lookup' => 'IP:',
	'registered' => 'Registrovaný',
	'reason' => 'Dôvod:',
	'cant_ban_root_user' => 'Nemôžeš nič spraviť tomuto uživateľovi!',
	'invalid_reason' => 'Napíš dôvod medzi 2 a 248 znakov.',
	'punished_successfully' => 'Trest bol pridaný úspešne.',
	
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
	'ingame_report' => 'Herné nahlásenie',
	
	// Staff applications
	'comment_error' => 'Uistite sa prosím, že váš komentár má dĺžku medzi 2 až 2048 znakov.',
	'viewing_open_applications' => 'Zobrazujú sa <span class="label label-info">otvorené</span> žiadosti.Zmeniť na <a href="/mod/applications/?view=accepted"><span class="label label-success">potvrdené</span></a> alebo <a href="/mod/applications/?view=declined"><span class="label label-danger">zamietnuté</span></a> žiadosti.',
	'viewing_accepted_applications' => 'Zobrazujú sa <span class="label label-success">potvrdené</span> žiadosti. Zmeniť na <a href="/mod/applications/"><span class="label label-info">otvorené</span></a> alebo <a href="/mod/applications/?view=declined"><span class="label label-danger">zamietnuté</span></a> žiadosti.',
	'viewing_declined_applications' => 'Zobrazujú sa <span class="label label-danger">zamietnuté</span> žiadosti. Zmeniť na <a href="/mod/applications/"><span class="label label-info">otvorené</span></a> alebo <a href="/mod/applications/?view=accepted"><span class="label label-success">potvrdené</span></a> žiadosti.',
	'time_applied' => 'Aplikované',
	'no_applications' => 'Žiadne ďalšie prihlášky v tejto kategórii',
	'viewing_app_from' => 'Zobrazená prihláška od {x}', // Don't replace "{x}"
	'open' => 'Otvorené',
	'accepted' => 'Potvrdené',
	'declined' => 'Zamietnuté',
	'accept' => 'Potvrdiť',
	'decline' => 'Zamietnuť',
	'new_app_submitted_alert' => 'Nová žiadosť predložená hráčom {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Novinky',
	'social' => 'Social',
	'join' => 'Hrať',
	
	// General terms
	'submit' => 'Odoslať',
	'close' => 'Zavrieť',
	'cookie_message' => '<strong>Tato stránka využíva cookies</strong><p>Pokračováním vyjadrujete súhlas s ich používaním.</p>',
	'theme_not_exist' => 'Vybraná šablona neexistuje.',
	'confirm' => 'Potvrdiť',
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
	'next' => 'Ďalej',
	
	// Play page
	'connect_with' => 'Pripoj sa cez IP {x}.', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Online hráči:',
	'queried_in' => 'Načítané za:',
	'server_status' => 'Status servera',
	'no_players_online' => 'Žiadni hráči nie sú online!',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'Na serveri je pripjených {x} hráčov.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Stránka bola načítaná za {x} sekúnd.', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'HEEEJ! Tu nemáš čo robiť, táto stránka neexistuje!'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Fórum',
	'discussion' => 'Diskusia',
	'stats' => 'Štatistiky',
	'last_reply' => 'Posledný príspevok',
	'ago' => 'pred',
	'by' => 'od',
	'in' => 'v',
	'views' => 'zobrazenie',
	'posts' => 'príspevkov',
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
	'new_topic' => 'Vytvoriť príspevok',
	'subforums' => 'Subfóra:',
	
	// View topic view
	'home' => 'Domov',
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
	'merge_instructions' => 'Téma sa <strong>musí</strong> zlučiť len s temou v rovnakom fóre. Podľa potreby si môžeš tému preniesť.',
	'merge_with' => 'Zlúčiť s:',
	
	// Other
	'forum_error' => 'Je nám ľúto, ale nenašli sa žiadne podobné fóra ani témy.',
	'are_you_logged_in' => 'Ste prihlásený?',
	'online_users' => 'Online uživatelia',
	'no_users_online' => 'Žiadny uživateľ nie je online.',
	
	// Search
	'search_error' => 'Zadajte prosím vyhľadávací dotaz medzi 1 az 32 znakov.',
	'no_search_results' => 'No search results have been found.',
	
	//Share on a social-media.
	'sm-share' => 'Zdieľať',
	'sm-share-facebook' => 'Zdielať na Facebooku',
	'sm-share-twitter' => 'Zdieľať na Twitteri',
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
	'seconds_short' => 's', // Shortened "seconds", eg "sec"
	'less_than_a_minute' => 'Pred minútou',
	'1_minute' => 'pred chvíľou',
	'_minutes' => 'pred {x} minutámi',
	'about_1_hour' => 'pred 1 hodinou',
	'_hours' => 'pred {x} hodinami',
	'1_day' => 'pred 1 dňom',
	'_days' => 'před {x} dňami',
	'about_1_month' => 'pred 1 mesiacom',
	'_months' => 'pred {x} mesiacmi',
	'about_1_year' => 'pred rokom',
	'over_x_years' => 'pred {x} rokmi'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Zobraziť _MENU_ výsledkov na str.ánku', // Don't replace "_MENU_"
	'nothing_found' => 'Neboli najdené žiadne vysledky',
	'page_x_of_y' => 'stránka _PAGE_ z _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'K dispozícii nie sú žiadné záznamy.',
	'filtered' => '(filtered from _MAX_ total records)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Dokončenie registrácie'
);
 
?>
