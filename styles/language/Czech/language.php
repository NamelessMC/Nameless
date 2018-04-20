<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  CZ language by SnooWiK
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'Administrace', 
	'infractions' => 'Přestupky',
	'invalid_token' => 'Špatná data, skuste to prosím znovu.',
	'invalid_action' => 'Špatná akce',
	'successfully_updated' => 'Successfully updated',
	'settings' => 'Nastavení',
	'confirm_action' => 'Potvrdit akci',
	'edit' => 'Upravit',
	'actions' => 'Akce',
	'task_successful' => 'Task run successfully',
	
	// Admin login
	're-authenticate' => 'Prosím ověřte se...',
	
	// Admin sidebar
	'index' => 'Přehled',
	'announcements' => 'Announcements',
	'core' => 'Core',
	'custom_pages' => 'Stránky',
	'general' => 'Hlavní',
	'forums' => 'Fóra',
	'users_and_groups' => 'Uživatelé a skupiny',
	'minecraft' => 'Minecraft',
	'style' => 'Styly',
	'addons' => 'Doplňky',
	'update' => 'Updaty',
	'misc' => 'Misc',
	'help' => 'Help',
	
	// Admin index page
	'statistics' => 'Statistiky',
	'registrations_per_day' => 'Registrace za posledních 7 dnů',
	
	// Admin announcements page
	'current_announcements' => 'Aktuální upozornění',
	'create_announcement' => 'Vytvořit upozornění',
	'announcement_content' => 'Obsad upozornění',
	'announcement_location' => 'Lokace upozornění',
	'announcement_can_close' => 'Lze zavřít upozornění?',
	'announcement_permissions' => 'Práva upozornění',
	'no_announcements' => 'Aktuálně nejsou aktivní žádná upozornění.',
	'confirm_cancel_announcement' => 'Opravdu chceš zrušít toto upozornění?',
	'announcement_location_help' => 'Ctrl-click pro vybrání více stránek.',
	'select_all' => 'Vybrat vše',
	'deselect_all' => 'Opustit vše',
	'announcement_created' => 'Upozornění uspěšně vytvořeno',
	'please_input_announcement_content' => 'Prosím vyber typ upozornění a vyplň jeho obsad.',
	'confirm_delete_announcement' => 'Opravdu chceš smazat toto upozornění?',
	'announcement_actions' => 'Akce upozornění',
	'announcement_deleted' => 'Upozornění uspěšně vytvořeno',
	'announcement_type' => 'Typ upozornění',
	'can_view_announcement' => 'Lze toto upozornění číst?',
	
	// Admin core page
	'general_settings' => 'Hlavní nastavení',
	'modules' => 'Moduly',
	'module_not_exist' => 'Tento modul neexistuje!',
	'module_enabled' => 'Modul zapnut.',
	'module_disabled' => 'Modul vypnut.',
	'site_name' => 'Jméno stránky',
	'language' => 'Jazyk',
	'voice_server_not_writable' => 'core/voice_server.php is not writable. Please check file permissions',
	'email' => 'Email',
	'incoming_email' => 'Příchozí e-mailová adresa',
	'outgoing_email' => 'Odchozí e-mailová adresa',
	'outgoing_email_help' => 'Only required if the PHP mail function is enabled',
	'use_php_mail' => 'Use PHP mail() function?',
	'use_php_mail_help' => 'Recommended: enabled. If your website is not sending emails, please disable this and edit core/email.php with your email settings.',
	'use_gmail' => 'Use Gmail for email sending?',
	'use_gmail_help' => 'Only available if the PHP mail function is disabled. If you choose not to use Gmail, SMTP will be used. Either way, this will need configuring in core/email.php.',
	'enable_mail_verification' => 'Enable email account verification?',
	'enable_email_verification_help' => 'Having this enabled will ask newly registered users to verify their account via email before completing registration.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Stránky',
	'enable_or_disable_pages' => 'Enable or disable pages here.',
	'enable' => 'Povolot',
	'disable' => 'Zakazat',
	'maintenance_mode' => 'Pro mod udržby',
	'forum_in_maintenance' => 'Forum je v modu udržby.',
	'unable_to_update_settings' => 'Unable to update settings. Please ensure no fields are left empty.',
	'editing_google_analytics_module' => 'Upravuji Google Analytics modul',
	'tracking_code' => 'Tracking Kod',
	'tracking_code_help' => 'Insert the tracking code for Google Analytics here, including the surrounding script tags.',
	'google_analytics_help' => 'See <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">this guide</a> for more information, following steps 1 to 3.',
	'social_media_links' => 'Social Media Links',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Použít temnou verzi Twitteru?',
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
	'questions' => 'Otázky:',
	'question' => 'Otázka',
	'type' => 'Type',
	'options' => 'Options',
	'options_help' => 'Each option on a new line; can be left empty (dropdowns only)',
	'no_questions' => 'No questions added yet.',
	'new_question' => 'Nová otázka',
	'editing_question' => 'Upravit otázku',
	'delete_question' => 'Smazat otázku',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'textarea' => 'Text Area',
	'question_deleted' => 'Otázka smazána',
	'name_required' => 'Jméno je povinné pole.',
	'question_required' => 'Otázka je povínné pole.',
	'name_minimum' => 'Jméno musí mít aspoň dvě čísla nebo písmena.',
	'question_minimum' => 'Otázka musí mít aspoň dvě čísla nebo písmena.',
	'name_maximum' => 'Jméno nesmí být delší než 16 znaků.',
	'question_maximum' => 'Otázka může mít maximálně 16 znaků.',
	'use_followers' => 'Použít sledovatele?',
	'use_followers_help' => 'Pokud vypnute, přátelé budou použiti.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Kliknutím na stránku ji upravíte.',
	'page' => 'Stránka:',
	'url' => 'URL:',
	'page_url' => 'URL stránky',
	'page_url_example' => '(With preceding "/", for example /help/)',
	'page_title' => 'Název stránky',
	'page_content' => 'Místo stránky',
	'new_page' => 'Nová stránka',
	'page_successfully_created' => 'Stránka úspěšně vytvořena!',
	'page_successfully_edited' => 'Stránka úspěšně upravena!',
	'unable_to_create_page' => 'Povolte vytvoření stránky.',
	'unable_to_edit_page' => 'Unable to edit page.',
	'create_page_error' => 'Please ensure you have entered an URL between 1 and 20 characters long, a page title between 1 and 30 characters long, and page content between 5 and 20480 characters long.',
	'delete_page' => 'Smazat stránku.',
	'confirm_delete_page' => 'Are you sure you want to delete this page?',
	'page_deleted_successfully' => 'Page deleted successfully',
	'page_link_location' => 'Display page link in:',
	'page_link_navbar' => 'Navbar',
	'page_link_more' => 'Navbar "More" dropdown',
	'page_link_footer' => 'Page footer',
	'page_link_none' => 'No page link',
	'page_permissions' => 'Oprávnění stránky',
	'can_view_page' => 'Zobrazit stránku:',
	'redirect_page' => 'Přesměrovat stránku?',
	'redirect_link' => 'Přesměrovat odkaz',
	
	// Admin forum page
	'labels' => 'Označení Témat',
	'new_label' => 'Nový Štítek',
	'no_labels_defined' => 'Žádne štítky nejsou definované',
	'label_name' => 'Název Štítku',
	'label_type' => 'Typ Štítku',
	'label_forums' => 'Label Forums',
	'label_creation_error' => 'Error creating a label. Please ensure the name is no longer than 32 characters and that you have specified a type.',
	'confirm_label_deletion' => 'Are you sure you want to delete this label?',
	'editing_label' => 'Editing label',
	'label_creation_success' => 'Label successfully created',
	'label_edit_success' => 'Label successfully edited',
	'label_default' => 'Defaultní',
	'label_primary' => 'Hlavní',
	'label_success' => 'Uspěšně',
	'label_info' => 'Info',
	'label_warning' => 'Upozornění',
	'label_danger' => 'Nebezpečí',
	'new_forum' => 'Nové fórum',
	'forum_layout' => 'Forum Layout',
	'table_view' => 'Table view',
	'latest_discussions_view' => 'Latest Discussions view',
	'create_forum' => 'Vytvořit fórum',
	'forum_name' => 'Název fóra',
	'forum_description' => 'Popis fóra',
	'delete_forum' => 'Smazat fórum',
	'move_topics_and_posts_to' => 'Přesunou téma a příspěveky do',
	'delete_topics_and_posts' => 'Odstranit téma a příspěvky',
	'parent_forum' => 'Parent Forum',
	'has_no_parent' => 'Has no parent',
	'forum_permissions' => 'Oprávnění fóra',
	'can_view_forum' => 'Může vidět fórum',
	'can_create_topic' => 'Může vytvářet témata',
	'can_post_reply' => 'Může vytvářet příspěvky',
	'display_threads_as_news' => 'Display threads as news on front page?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description.',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => 'Kategorie',
	
	// Admin Users and Groups page
	'users' => 'Uživatel',
	'new_user' => 'Nový uživatel',
	'created' => 'Vytvořil',
	'user_deleted' => 'Uživatel byl smazán',
	'validate_user' => 'Validate User',
	'update_uuid' => 'Update UUID',
	'unable_to_update_uuid' => 'Unable to update UUID.',
	'update_mc_name' => 'Update Minecraft Name',
	'reset_password' => 'Resetovat heslo',
	'punish_user' => 'Punish User',
	'delete_user' => 'Smazat uživatele',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Address',
	'ip' => 'IP:',
	'other_actions' => 'Ostatní akce:',
	'disable_avatar' => 'Zakázat avatara',
	'enable_avatar' => 'povolot avatara',
	'confirm_user_deletion' => 'Are you sure you want to delete the user {x}?', // Don't replace "{x}"
	'groups' => 'Skupiny',
	'group' => 'Skupina',
	'group2' => 'Skupina 2',
	'new_group' => 'Nová skupina',
	'id' => 'ID',
	'name' => 'Jméno',
	'create_group' => 'Vytvořit skupinu',
	'group_name' => 'Jméno skupiny',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>This is the ID of the group\'s package from Buycraft, MinecraftMarket or MCStock.</p><p>This can be left empty.</p>',
	'donor_group_instructions' => 	'<p>Donor groups must be created in the order of <strong>lowest value to highest value</strong>.</p>
									<p>For example, a £10 package will be created before a £20 package.</p>',
	'delete_group' => 'Smazat skupinu',
	'confirm_group_deletion' => 'Are you sure you want to delete the group {x}?', // Don't replace "{x}"
	'group_staff' => 'Je tato skupina nějaká hodnost?',
	'group_modcp' => 'Může skupinu vidět ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'Jméno skupiny musí být minimálně 2 znaky dlouhé.',
	'group_name_maximum' => 'Jméno skupiny může být maximalně 20 znaků dlouhé.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'Uživatel musí být ve skupině.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft nastavení',
	'use_plugin' => 'Use Nameless Minecraft plugin?',
	'force_avatars' => 'Force Minecraft avatars?',
	'uuid_linking' => 'Enable UUID linking?',
	'use_plugin_help' => 'Using the plugin allows for rank synchronisation and also ingame registration and ticket submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Plugin Settings',
	'confirm_api_regen' => 'Opravdu chceš vygenerovat nový API klíč?',
	'servers' => 'Servery',
	'new_server' => 'Nový server',
	'confirm_server_deletion' => 'Opravdu chceš odstranit tento server?',
	'main_server' => 'Hlavní server',
	'main_server_help' => 'The server players connect through. Normally this will be the Bungee instance.',
	'choose_a_main_server' => 'Choose a main server..',
	'external_query' => 'Use external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Editing server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (with port) (numeric or domain)',
	'server_ip_with_port_help' => 'This is the IP which will be displayed to users. It will not be queried.',
	'server_ip_numeric' => 'Server IP (with port) (numeric only)',
	'server_ip_numeric_help' => 'This is the IP which will be queried, please ensure it is numeric only. It will not be displayed to users.',
	'show_on_play_page' => 'Show on Play page?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Server Name',
	'invalid_server_id' => 'Invalid server ID',
	'show_players' => 'Show player list on Play page?',
	'server_edited' => 'Server byl úspěšně upraven',
	'server_created' => 'Server byl úspěšně vytvořen',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'No query errors logged',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => 'Opravdu chceš odstranit tento error?',
	'display_server_status' => 'Display server status module?',
	'server_name_required' => 'Musíš vložit název serveru.',
	'server_ip_required' => 'Musíš vložit IP adresu serveru.',
	'server_name_minimum' => 'Jméno serveru musí být minimálně 2 znaky dlouhé.',
	'server_ip_minimum' => 'IP adresa serveru musí být minimálně 2 znaky dlouhá.',
	'server_name_maximum' => 'Jméno serveru může být maximalne 20 znaků dlouhé.',
	'server_ip_maximum' => 'IP adresa serveru může být maximalne 64 znaků dlouhá.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	'avatar_type' => 'Typ avatara',
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
	'themes' => 'Vzhled',
	'templates' => 'Templates',
	'installed_themes' => 'Instalovat Vzhled',
	'installed_templates' => 'Instalovat Templates',
	'installed_addons' => 'Instalovat Dodatek',
	'install_theme' => 'Instalovat Vzhled',
	'install_template' => 'Instalovat Template',
	'install_addon' => 'Instalovat Dodtky',
	'install_a_theme' => 'Instalovat a Vzhled',
	'install_a_template' => 'Instalovat a Template',
	'install_an_addon' => 'Instalovat Dodatky',
	'active' => 'Aktivní',
	'activate' => 'Aktivavné',
	'deactivate' => 'Deaktivované',
	'theme_install_instructions' => 'Please upload themes to the <strong>styles/themes</strong> directory. Then, click the "scan" button below.',
	'template_install_instructions' => 'Please upload templates to the <strong>styles/templates</strong> directory. Then, click the "scan" button below.',
	'addon_install_instructions' => 'Please upload addons to the <strong>addons</strong> directory. Then, click the "scan" button below.',
	'addon_install_warning' => 'Addons are installed at your own risk. Please back up your files and the database before proceeding',
	'scan' => 'Scan',
	'theme_not_exist' => 'Tento vzhled neexistuje!',
	'template_not_exist' => 'Tento template neexistuje!',
	'addon_not_exist' => 'Tento dodatek neexistuje!',
	'style_scan_complete' => 'Další styly byly instalovány..',
	'addon_scan_complete' => 'Další dodatky byly instalovány.',
	'theme_enabled' => 'Vzhled byl povolen.',
	'template_enabled' => 'Template byl povoloen.',
	'addon_enabled' => 'Dodatek byl povoloen.',
	'theme_deleted' => 'Vzhled byl smazán.',
	'template_deleted' => 'Template deleted.',
	'addon_disabled' => 'Dodatek byl zakázan.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Are you sure you wish to delete the theme <strong>{x}</strong>?<br /><br />The theme will be deleted from your <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />The template will be deleted from your <strong>styles/templates</strong> directory.', // Don't replace {x}
	'unable_to_enable_addon' => 'Could not enable addon. Please ensure it is a valid NamelessMC addon.',
	
	// Admin Misc page
	'other_settings' => 'Ostatní nastavení',
	'enable_error_reporting' => 'Enable error reporting?',
	'error_reporting_description' => 'This should only be used for debugging purposes, it\'s highly recommended this is left as disabled.',
	'display_page_load_time' => 'Display page loading time?',
	'page_load_time_description' => 'Having this enabled will display a speedometer in the footer which will display the page loading time.',
	'reset_website' => 'Reset Website',
	'reset_website_info' => 'This will reset your website settings. <strong>Addons will be disabled but not removed, and their settings will not change.</strong> Your defined Minecraft servers will also remain.',
	'confirm_reset_website' => 'Are you sure you want to reset your website settings?',
	
	// Admin Update page
	'installation_up_to_date' => 'Your installation is up to date.',
	'update_check_error' => 'Unable to check for updates. Please try again later.',
	'new_update_available' => 'A new update is available.',
	'your_version' => 'Tvoje verze:',
	'new_version' => 'Nová verze:',
	'download' => 'Stáhnout',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Domů',
	'play' => 'Hrát',
	'forum' => 'Forum',
	'more' => 'Další',
	'staff_apps' => 'Staff Applications',
	'view_messages' => 'View Messages',
	'view_alerts' => 'View Alerts',
	
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
	'create_an_account' => 'Vytvoření ůčtu',
	'username' => 'Jméno',
	'minecraft_username' => 'Minecraft nick',
	'email' => 'Email',
	'user_title' => 'Titul',
	'email_address' => 'Emailová adresa',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Heslo',
	'confirm_password' => 'Potvrdit heslo',
	'i_agree' => 'Souhlasím',
	'agree_t_and_c' => 'Kliknutím na <strong class="label label-primary">Registrace</strong>, Souhlasíte s <a href="#" data-toggle="modal" data-target="#t_and_c_m">Všeobecnými podmínkamy.</a>.',
	'register' => 'Registrace',
	'sign_in' => 'Přihlášení',
	'sign_out' => 'Odhlásit',
	'terms_and_conditions' => 'Všeobecná pravidla',
	'successful_signin' => 'Byl si přihlášen!',
	'incorrect_details' => 'Špatné detaily',
	'remember_me' => 'Pamatovat',
	'forgot_password' => 'Zapomenuté heslo',
	'must_input_username' => 'Musíte vložit uživatelské jméno.',
	'must_input_password' => 'Musíte vložit uživatelské heslo.',
	'inactive_account' => 'Tvůj ůčet je deaktivován. Mrkni se na email :).',
	'account_banned' => 'Tvůj ůčet byl zabanován.',
	'successfully_logged_out' => 'Byl jsi odhlášen.',
	'signature' => 'Podpis',
	'registration_check_email' => 'Podívej se na mail, a klikni na ověřovací odkaz.',
	'unknown_login_error' => 'Sorry, there was an unknown error whilst logging you in. Please try again later.',
	'validation_complete' => 'Thanks for registering! You can now log in.',
	'validation_error' => 'Error processing your request. Please try clicking the link again.',
	'registration_error' => 'Prosím, ujistěte, že jste vyplnili všechna pole, a že vaše uživatelské jméno je dlouhá 3 až 20 znaků a heslo je dlouhé 6 až 30 znaků.',
	'username_required' => 'Please enter a username.',
	'password_required' => 'Please enter a password.',
	'email_required' => 'Please enter an email address.',
	'mcname_required' => 'Please enter a Minecraft username.',
	'accept_terms' => 'You must accept the terms and conditions before registering.',
	'invalid_recaptcha' => 'Invalid reCAPTCHA response.',
	'username_minimum_3' => 'Your username must be a minimum of 3 characters long.',
	'username_maximum_20' => 'Your username must be a maximum of 20 characters long.',
	'mcname_minimum_3' => 'Your Minecraft username must be a minimum of 3 characters long.',
	'mcname_maximum_20' => 'Your Minecraft username must be a maximum of 20 characters long.',
	'password_minimum_6' => 'Your password must be at least 6 characters long.',
	'password_maximum_30' => 'Your password must be a maximum of 30 characters long.',
	'passwords_dont_match' => 'Your passwords do not match.',
	'username_mcname_email_exists' => 'Your username, Minecraft username or email address already exists. Have you already created an account?',
	'invalid_mcname' => 'Your Minecraft username is not a valid account',
	'mcname_lookup_error' => 'There was an error contacting Mojang\'s servers. Please try again later.',
	'signature_maximum_900' => 'Your signature must be a maximum of 900 characters.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
	'verify_account' => 'Verify account',
	'verify_account_help' => 'Please follow the instructions below so we can verify you own the Minecraft account in question.',
	'verification_failed' => 'Špatně ses ověřis skus to znovu.',
	'verification_success' => 'Úspěžně jsi byl ověřen. Můžeš se přihlásit.',
	'complete_signup' => 'Dokončit přihlášení',
	'registration_disabled' => 'Website registration is currently disabled.',
	
	// UserCP
	'user_cp' => 'Uživ. menu',
	'no_file_chosen' => 'No file chosen',
	'private_messages' => 'Soukromé zprávy',
	'profile_settings' => 'Nastavení profilu',
	'your_profile' => 'Tvůj profil',
	'topics' => 'Témata',
	'posts' => 'Příspěvky',
	'reputation' => 'Reputation',
	'friends' => 'Přátelé',
	'alerts' => 'Alerts',
	
	// Messaging
	'new_message' => 'Nová zpráva',
	'no_messages' => 'Žádné zprávy',
	'and_x_more' => 'a {x} další', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Jméno zprávy',
	'message' => 'Zpráva',
	'to' => 'Komu:',
	'separate_users_with_comma' => 'Separate users with a comma (",")',
	'viewing_message' => 'Zobrazit zprávu',
	'delete_message' => 'Smazat zprávu',
	'confirm_message_deletion' => 'Are you sure you want to delete this message?',
	
	// Profile settings
	'display_name' => 'Jméno zobrazováno jako',
	'upload_an_avatar' => 'Uploadni avatara (.jpg, .png or .gif only):',
	'use_gravatar' => 'Use Gravatar?',
	'change_password' => 'Změnit heslo',
	'current_password' => 'Aktuální heslo',
	'new_password' => 'Nové heslo',
	'repeat_new_password' => 'Opravit nové heslo',
	'password_changed_successfully' => 'Heslo bylo úspěšně změněno',
	'incorrect_password' => 'Tvoje aktuální heslo je špatně',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Zobrazit tvůj věk na profilu?',
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
	'viewing_unread_alerts' => 'Viewing unread alerts. Change to <a href="/user/alerts/?view=read"><span class="label label-success">read</span></a>.',
	'viewing_read_alerts' => 'Viewing read alerts. Change to <a href="/user/alerts/"><span class="label label-warning">unread</span></a>.',
	'no_unread_alerts' => 'You have no unread alerts.',
	'no_alerts' => 'Nejsou 68dn0 upoyorn2n9.',
	'no_read_alerts' => 'You have no read alerts.',
	'view' => 'Zobrazit',
	'alert' => 'Upozornění',
	'when' => 'Kdy',
	'delete' => 'Smazat',
	'tag' => 'User Tag',
	'tagged_in_post' => 'You have been tagged in a post',
	'report' => 'Ohloásit',
	'deleted_alert' => 'Upozornění bylo úspěšně smazáno.',
	
	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Resetovat heslo',
	'email_body' => 'You are receiving this email because you requested a password reset. In order to reset your password, please use the following link:', // Body for the password reset email
	'email_body_2' => 'If you did not request the password reset, you can ignore this email.',
	'password_email_set' => 'Na tvůj email byly odeslány další instrukce..',
	'username_not_found' => 'Toto uživatelské jméno neexistuje.',
	'change_password' => 'Změnit heslo',
	'your_password_has_been_changed' => 'Tvoje heslo bylo změněno.',
	
	// Profile page
	'profile' => 'Profil',
	'player' => 'Hráč',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registrován:',
	'pf_posts' => 'Příspěvky:',
	'pf_reputation' => 'Reputace:',
	'user_hasnt_registered' => 'Tento uživatel není registrovaný na této stránce.',
	'user_no_friends' => 'Tento uživatel nemá žádné přátele.',
	'send_message' => 'Poslat zprávu',
	'remove_friend' => 'Vymazat přítele',
	'add_friend' => 'Přidat přítele',
	'last_online' => 'Naposledy online:',
	'find_a_user' => 'Najít uživatele',
	'user_not_following' => 'Tento uživatel zatím nikoho nesleduje.',
	'user_no_followers' => 'Tento uživatel nemá žádné sledující.',
	'following' => 'SLEDOVAT',
	'followers' => 'SLEDUJÍCÍ',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Napiš něco na svůj profil',
	'profile_posts' => 'Uživateleské příspěvky',
	'no_profile_posts' => '6ádné uživateleské příspěvky.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'O',
	'reply' => 'Odpověd',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'Žádné laiky.',
	'post_liked' => 'K příspěvku byl přidělen like.',
	'post_unliked' => 'Z příspěvku byl odebrán like.',
	'no_posts' => 'Nejsou žádné příspěvky..',
	'last_5_posts' => 'Nejnovějších 5 příspěvků.',
	'follow' => 'Sledovat',
	'unfollow' => 'Přestat sledovat',
	'name_history' => 'Historie jména',
	'changed_name_to' => 'Jméno bylo změněno na: {x} on {y}', // Don't replace {x} or {y}
	'original_name' => 'Originální jmeéno:',
	'name_history_error' => 'Unable to retrieve username history.',
	
	// Staff applications
	'staff_application' => 'Staff Application',
	'application_submitted' => 'Application submitted successfully.',
	'application_already_submitted' => 'You\'ve already submitted an application. Please wait until it is complete before submitting another.',
	'not_logged_in' => 'Please log in to view that page.',
	'application_accepted' => 'Your staff application has been accepted.',
	'application_rejected' => 'Your staff application has been rejected.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'Moderátor',
	'overview' => 'Přehled',
	'reports' => 'Reporty',
	'punishments' => 'tresty',
	'staff_applications' => 'Staff Applications',
	
	// Punishments
	'ban' => 'Zabanovat',
	'unban' => 'Odbanovat',
	'warn' => 'Varovat',
	'search_for_a_user' => 'Hledat uživatele',
	'user' => 'Uživatel:',
	'ip_lookup' => 'IP:',
	'registered' => 'Registrován',
	'reason' => 'Důvod:',
	'cant_ban_root_user' => 'Can\'t punish the root user!',
	'invalid_reason' => 'Prosím zadejte platný důvod.',
	'punished_successfully' => 'Punishment added successfully.',
	
	// Reports
	'report_closed' => 'Hlášení uzavřeno.',
	'new_comment' => 'Nový komentář',
	'comments' => 'Komentáře',
	'only_viewed_by_staff' => 'Toto může zobrazit pouze administrátor',
	'reported_by' => 'Oznámil',
	'close_issue' => 'Blízký problém',
	'report' => 'Oznámení:',
	'view_reported_content' => 'Zobrazit oznámený příspěvek',
	'no_open_reports' => 'Žádný oznámený příspěvek',
	'user_reported' => 'Uživatelův report',
	'type' => 'Typ',
	'updated_by' => 'Updatoval',
	'forum_post' => 'Příspěvek z fóra',
	'user_profile' => 'Uživatelův profil',
	'comment_added' => 'Komentář přidán',
	'new_report_submitted_alert' => 'Nové oznámení submitted {x} týkající se {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Ingame Report',
	
	// Staff applications
	'comment_error' => 'Please ensure your comment is between 2 and 2048 characters long.',
	'viewing_open_applications' => 'Viewing <span class="label label-info">open</span> applications. Change to <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_accepted_applications' => 'Viewing <span class="label label-success">accepted</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_declined_applications' => 'Viewing <span class="label label-danger">declined</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a>.',
	'time_applied' => 'Time Applied',
	'no_applications' => 'No applications in this category',
	'viewing_app_from' => 'Viewing application from {x}', // Don't replace "{x}"
	'open' => 'Open',
	'accepted' => 'Přijatý',
	'declined' => 'Declined',
	'accept' => 'Příjmout',
	'decline' => 'Decline',
	'new_app_submitted_alert' => 'New application submitted by {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Novinky',
	'social' => 'Sociální sítě',
	'join' => 'Připojit',
	
	// General terms
	'submit' => 'Odelsat',
	'close' => 'Zavřít',
	'cookie_message' => '<strong>Tato stránka pracuje s cookies</strong><p>Pokračováním vyjadřujete souhlas s jejím používáním.</p>',
	'theme_not_exist' => 'Označené téma neexistuje.',
	'confirm' => 'Potvrdit',
	'cancel' => 'Ukončit',
	'guest' => 'Host',
	'guests' => 'Hosté',
	'back' => 'Zpět',
	'search' => 'Hledat',
	'help' => 'Pomoc',
	'success' => 'Ůspěšně',
	'error' => 'Chyba',
	'view' => 'Zobrazit',
	'info' => 'Info',
	'next' => 'Další',
	
	// Play page
	'connect_with' => 'Připoj se přes IP {x}.', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Hráčů online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Aktuálně nejsou žádni hráči online!',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'Na serveru je {x} hráčů online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Stránka načtena za {x} sekund', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'Omlouváme se, ale nemůžeme najít tuto stránku'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forum',
	'discussion' => 'Diskuze',
	'stats' => 'Statistiky',
	'last_reply' => 'Poslední příspěvek',
	'ago' => 'před',
	'by' => 'od',
	'in' => 'v',
	'views' => 'zobrazení',
	'posts' => 'příspěvků',
	'topics' => 'témat',
	'topic' => 'Téma',
	'statistics' => 'Statistiky',
	'overview' => 'Přehled',
	'latest_discussions' => 'Poslední diskuze',
	'latest_posts' => 'Poslední příspěvky',
	'users_registered' => 'Registrovaných uživatelů:',
	'latest_member' => 'Poslední registrovaný:',
	'forum' => 'Fórum',
	'last_post' => 'Poslední příspěvek',
	'no_topics' => 'Nenalezeny žádné příspěvky',
	'new_topic' => 'Nová téma',
	'subforums' => 'Subfóra:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Téma je zamčená',
	'new_reply' => 'Nová odpověď',
	'mod_actions' => 'Akce moderátora',
	'lock_thread' => 'Zamknout téma',
	'unlock_thread' => 'Odemknout téma',
	'merge_thread' => 'Sloučit téma',
	'delete_thread' => 'Vymazat téma',
	'confirm_thread_deletion' => 'Opravdu chcete vymazat toto téma?',
	'move_thread' => 'Přemístit téma',
	'sticky_thread' => 'Přilepit téma',
	'report_post' => 'Ohlásit příspěvek',
	'quote_post' => 'Sitovat příspěvek',
	'delete_post' => 'Odstranit příspěvek',
	'edit_post' => 'Upravit příspěvek',
	'reputation' => 'reputace',
	'confirm_post_deletion' => 'Are you sure you want to delete this post?',
	'give_reputation' => 'Give reputation',
	'remove_reputation' => 'Odstranit reputaci',
	'post_reputation' => 'Poslat reputaci',
	'no_reputation' => 'Tento příspěvek nemá zatím žádnou reputaci',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Vytvořit příspěvek',
	'post_submitted' => 'Příspěvek byl odeslán',
	'creating_post_in' => 'Vytváříte příspěvek v: ',
	'topic_locked_permission_post' => 'Toto téma je zamčeno. Nemůžete odpovídat, ani posílat příspěvky.',
	
	// Edit post view
	'editing_post' => 'Úprava příspěvku',
	
	// Sticky threads
	'thread_is_' => 'Téma je ',
	'now_sticky' => 'nyní je přilepená téma',
	'no_longer_sticky' => 'no longer a sticky thread',
	
	// Create topic
	'topic_created' => 'Téma vytvořeno.',
	'creating_topic_in_' => 'Vytváříte téma v ',
	'thread_title' => 'Název témy',
	'confirm_cancellation' => 'Doopravdy?',
	'label' => 'Label',
	
	// Reports
	'report_submitted' => 'Ohlášená bylo odesláno',
	'view_post_content' => 'Zobrazit obsah',
	'report_reason' => 'Důvod',
	
	// Move thread
	'move_to' => 'Přemístit do:',
	
	// Merge threads
	'merge_instructions' => 'The thread to merge with <strong>must</strong> be within the same forum. Move a thread if necessary.',
	'merge_with' => 'Merge with:',
	
	// Other
	'forum_error' => 'Omlouváme se ale nemůžem najít toto fórum nebo téma',
	'are_you_logged_in' => 'Jste přihlášen?',
	'online_users' => 'Uživatelé online',
	'no_users_online' => 'Aktuálně není žádny uživatel online',
	
	// Search
	'search_error' => 'Please input a search query between 1 and 32 characters long.',
	'no_search_results' => 'Nebyly nalezeny žádně výsledky.',
	
	// Podíl na sociálním-médií.
	'sm-share' => 'Sdílet',
	'sm-share-facebook' => 'Sdílet na Facebooku',
	'sm-share-twitter'=> 'Sdílet se na Twitteru',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Dobrý den',
	'message' => 'Děkujeme že jste se registrovali! Potvrďte svoji registraci kliknutím na následující link:',
	'thanks' => 'Děkujeme.'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'less than a minute ago',
	'1_minute' => 'před chvílí',
	'_minutes' => 'před {x} minutami',
	'about_1_hour' => 'před hodinou',
	'_hours' => 'před {x} hodinami',
	'1_day' => 'před 1 dnem',
	'_days' => 'před {x} dny',
	'about_1_month' => 'před měsícem',
	'_months' => 'před {x} měsíci',
	'about_1_year' => 'před rokem',
	'over_x_years' => 'před {x} lety'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Display _MENU_ records per page', // Don't replace "_MENU_"
	'nothing_found' => 'No results found',
	'page_x_of_y' => 'stránka _PAGE_ z _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'No records available',
	'filtered' => '(filtered from _MAX_ total records)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Complete Registration'
);
 
?>
