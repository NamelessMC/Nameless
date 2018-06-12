<?php 
/*
 *	Made by Matthew18_
 *  http://warenode.com
 *
 *  License: MIT
 */

/*
 *  Italian Language
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'infractions' => 'Infrazioni',
	'invalid_token' => 'Token non valido, riprova per favore.',
	'invalid_action' => 'Azione non valida',
	'successfully_updated' => 'Aggiornato con successo',
	'settings' => 'Impostazioni',
	'confirm_action' => 'Conferma azione',
	'edit' => 'Modifica',
	'actions' => 'Azioni',
	'task_successful' => 'Compito eseguito con successo.',
	
	// Admin login
	're-authenticate' => 'Per favore ri-esegui il login.',
	
	// Admin sidebar
	'index' => 'Panoramica',
	'announcements' => 'Annunci',
	'core' => 'Core',
	'custom_pages' => 'Pagine personalizzate',
	'general' => 'Generale',
	'forums' => 'Forum',
	'users_and_groups' => 'Utenti e Gruppi',
	'minecraft' => 'Minecraft',
	'style' => 'Stile',
	'addons' => 'Addons',
	'update' => 'Aggiornamenti',
	'misc' => 'Varie',
	'help' => 'Aiuto',
	
	// Admin index page
	'statistics' => 'Statistiche',
	'registrations_per_day' => 'Registrazioni per giorno (ultimi 7 giorni)',
	
	// Admin announcements page
	'current_announcements' => 'Annunci attuali',
	'create_announcement' => 'Crea annuncio',
	'announcement_content' => 'Contenuto annuncio',
	'announcement_location' => 'Posizione dell\'annuncio',
	'announcement_can_close' => 'Pu&ograve; chiudere l\'annuncio?',
	'announcement_permissions' => 'Permessi annuncio',
	'no_announcements' => 'Non c\'&egrave; ancora nessun annuncio.',
	'confirm_cancel_announcement' => 'Sei sicuro di voler eliminare quest\'annuncio?',
	'announcement_location_help' => 'Ctrl-click per selezionare annunci multipli',
	'select_all' => 'Seleziona tutti',
	'deselect_all' => 'Deseleziona tutti',
	'announcement_created' => 'Annuncio creato con succesaso',
	'please_input_announcement_content' => 'Per favore inserisci il contenuto dell\'annuncio e seleziona un tipo',
	'confirm_delete_announcement' => 'Sei sicuro di voler cancellare quest\'annuncio?',
	'announcement_actions' => 'Azioni annunci',
	'announcement_deleted' => 'Annuncio cancellato con successo',
	'announcement_type' => 'Tipo di annuncio',
	'can_view_announcement' => 'Pu&ograve; vedere gli annunci?',
	
	// Admin core page
	'general_settings' => 'Impostazioni generali',
	'modules' => 'Moduli',
	'module_not_exist' => 'Quel modulo non esiste',
	'module_enabled' => 'Modulo abilitato.',
	'module_disabled' => 'Modulo disabilitato.',
	'site_name' => 'Nome sito',
	'language' => 'Lingua',
	'voice_server_not_writable' => 'core/voice_server.php non &egrave; scrivibile. Per favore controlla i permessi del file.',
	'email' => 'Email',
	'incoming_email' => 'Indirizzo email in entrata',
	'outgoing_email' => 'Indirizzo email in entrata',
	'outgoing_email_help' => 'Richiesto solo se la funzione PHP Mail &egrave; abilitata',
	'use_php_mail' => 'Usare la funzione PHP mail()?',
	'use_php_mail_help' => 'Raccomandato: abilitato. se il tuo sito non invia email, per favore disabilita quest\'impostazione e modifica core/email.php con le impostazioni della tua email.',
	'use_gmail' => 'Usare Gmail per l\'invio delle email?',
	'use_gmail_help' => 'Disponibile solo se la funzione PHP mail &egrave; disabilitata. Se scegli di non usare Gmail, SMTP sar&agrave; usato. In ogni caso, dovrai configurarlo su core/email.php.',
	'enable_mail_verification' => 'Abilitare la verifica account via email?',
	'enable_email_verification_help' => 'Abilitando questo i nuovi utenti registrati avranno bisogno di verificare il loro account via email prima di poter completare la registrazione.',
	'explain_email_settings' => 'La seguente configurazione &egrave; necessaria se l\'opzione "Utilizza la funzione PHP mail()" &egrave; <strong>disabilitata</strong>. Puoi trovare la documentazione per queste impostazioni <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">sulla nostra wiki</a>.',
	'email_config_not_writable' => 'Il tuo file <strong>core/email.php</strong> non &egrave; scrivibile. Per favore controlla i permessi.',
	'pages' => 'Pagine',
	'enable_or_disable_pages' => 'Abilita o disabilita le pagine qui.',
	'enable' => 'Abilita',
	'disable' => 'Disabilita',
	'maintenance_mode' => 'Forum in modalit&agrave; manutenzione',
	'forum_in_maintenance' => 'Il Forum &egrave; in manutenzione',
	'unable_to_update_settings' => 'Impossibile aggiornare le impostazioni. Per favore assicurati che non ci siano campi vuoti.',
	'editing_google_analytics_module' => 'Modificando il modulo di Google Analytics',
	'tracking_code' => 'Codice di Tracciamento',
	'tracking_code_help' => 'Insrisci il codice di tracciamento per Google Analytics qui, includendo i tag script che lo circondano.',
	'google_analytics_help' => 'Vedi <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">questa guida</a> per pi&ugrave; informazioni, seguendo gli step dal 1 al 3.',
	'social_media_links' => 'Link Social Media',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL (Non deve finire con \'/\')',
	'twitter_dark_theme' => 'Usa il tema dark di Twitter?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registrazione',
	'registration_warning' => 'Con questo modulo disabilitato i nuovi utenti non si potranno registrare sul sito.',
	'google_recaptcha' => 'Abilita Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Termini e Condizioni della registrazione',
	'voice_server_module' => 'Modulo server Voce',
	'only_works_with_teamspeak' => 'Questo modulo funziona attualmente solo con TeamSpeak e Discord',
	'discord_id' => 'ID server Discord',
	'voice_server_help' => 'Per favore inserisci le credenziali per l\'utente della query',
	'ip_without_port' => 'IP (senza porta)',
	'voice_server_port' => 'Port (default 10011)',
	'virtual_port' => 'Virtual Port (default 9987)',
	'permissions' => 'Permessi:',
	'view_applications' => 'Vedere le applicazioni?',
	'accept_reject_applications' => 'Accettare/Rifiutare applicazione?',
	'questions' => 'Domande:',
	'question' => 'Domanda',
	'type' => 'Tipo',
	'options' => 'Opzioni',
	'options_help' => 'Ogni opzione su una nuova linea; pu&ograve; rimanere vuota (solo men&ugrave; a tendina)',
	'no_questions' => 'Non c\'&egrave; nessuna domanda.',
	'new_question' => 'Nuova domanda',
	'editing_question' => 'Modificando la domanda',
	'delete_question' => 'Cancella domanda',
	'dropdown' => 'Men&ugrave; a tendina',
	'text' => 'Testo',
	'textarea' => 'Area di testo',
	'name_required' => 'Il nome &egrave; obbligatorio.',
	'question_required' => 'La domanda &egrave; obbligatoria.',
	'name_minimum' => 'Il nome deve avere un minimo di 2 caratteri.',
	'question_minimum' => 'La domanda deve avere un minimo di 2 caratteri.',
	'name_maximum' => 'Il nome pu&ograve; avere massimo 16 caratteri.',
	'question_maximum' => 'La domanda pu&ograve; avere massimo 16 caratteri.',
	'question_deleted' => 'Domanda cancellata',
	'use_followers' => 'Usare i seguaci?',
	'use_followers_help' => 'Se disabilitato, sar&agrave; usato il sistema di amici.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Clicca su una pagina per modificarla.',
	'page' => 'Pagina:',
	'url' => 'URL:',
	'page_url' => 'URL Pagina',
	'page_url_example' => '(Preceduta da "/", per esempio /aiuto/)',
	'page_title' => 'Titolo pagina',
	'page_content' => 'Contenuto pagina',
	'new_page' => 'Nuova pagina',
	'page_successfully_created' => 'Pagina creata con successo',
	'page_successfully_edited' => 'Pagina modificata con successo',
	'unable_to_create_page' => 'Impossibile creare la pagina.',
	'unable_to_edit_page' => 'Impossibile modificare la pagina.',
	'create_page_error' => 'Per favore assicurati che hai inserito un URL fra 1 e 20 caratteri di lunghezza, un titolo fra 1 e 30 caratteri di lunghezza e un contenuto pagina compreso fra 5 e 20480 caratteri di lunghezza.',
	'delete_page' => 'Cancella pagina',
	'confirm_delete_page' => 'Sei sicuro di voler cancellare questa pagina?',
	'page_deleted_successfully' => 'Pagina cancellata con successo',
	'page_link_location' => 'Mostra il link della pagina su:',
	'page_link_navbar' => 'Navbar',
	'page_link_more' => 'Dropdown "Altro" della navbar',
	'page_link_footer' => 'Pi&egrave; di pagina',
	'page_link_none' => 'Nessun link pagina',
	'page_permissions' => 'Permessi pagina',
	'can_view_page' => 'Possono vedere la pagina:',
	'redirect_page' => 'Pagina di redirect?',
	'redirect_link' => 'Link di redirect',
	'page_icon' => 'Icona pagina',
	
	// Admin forum page
	'labels' => 'Etichette topic',
	'new_label' => 'Nuova etichetta',
	'no_labels_defined' => 'Non ci sono etichette definite',
	'label_name' => 'Nome etichetta',
	'label_type' => 'Tipo etichetta',
	'label_forums' => 'Etichetta forum',
	'label_creation_error' => 'Impossibile creare un\'etichetta, per favore assicurati che il nome non &egrave; superiore a 32 caratteri di lunghezza e che hai specificato un tipo.',
	'confirm_label_deletion' => 'Sei sicuro di voler cancellare quest\'etichetta?',
	'editing_label' => 'Modificando etichetta',
	'label_creation_success' => 'Etichetta creata con successo',
	'label_edit_success' => 'Etichetta eliminata con successo',
	'label_default' => 'Default',
	'label_primary' => 'Primario',
	'label_success' => 'Successo',
	'label_info' => 'Info',
	'label_warning' => 'Avvertimento',
	'label_danger' => 'Pericolo',
	'new_forum' => 'Nuovo Forum',
	'forum_layout' => 'Layout Forum',
	'table_view' => 'Vista tavole',
	'latest_discussions_view' => 'Vista ultime discussioni',
	'create_forum' => 'Crea Forum',
	'forum_name' => 'Nome Forum',
	'forum_description' => 'Descrizione Forum',
	'delete_forum' => 'Cancella Forum',
	'move_topics_and_posts_to' => 'Sposta i topic e i post su',
	'delete_topics_and_posts' => 'Cancella topic e post',
	'parent_forum' => 'Forum genitore',
	'has_no_parent' => 'Non ha genitori',
	'forum_permissions' => 'Permessi del forum',
	'can_view_forum' => 'Pu&ograve; vedere il forum',
	'can_create_topic' => 'Pu&ograve; creare topic',
	'can_post_reply' => 'Pu&ograve; creare risposte',
	'display_threads_as_news' => 'Mostrare i thread come aggiornamenti su quella pagina??',
	'input_forum_title' => 'Inserisci un titolo del Forum.',
	'input_forum_description' => 'Inserisci una descrizione del Forum (Puoi usare HTML).',
	'forum_name_minimum' => 'Il nome del forum deve essere minimo 2 caratteri.',
	'forum_description_minimum' => 'La descrizione del forum deve essere minimo 2 caratteri.',
	'forum_name_maximum' => 'Il nome del forum pu&ograve; essere 150 caratteri massimo.',
	'forum_description_maximum' => 'La descrizione del forum pu&ograve; essere 255 caratteri massimo.',
	'forum_type_forum' => 'Forum di discussione',
	'forum_type_category' => 'Categoria',
	
	// Admin Users and Groups page
	'users' => 'Utenti',
	'new_user' => 'Nuovo utente',
	'created' => 'Creato',
	'user_deleted' => 'Utente cancellato',
	'validate_user' => 'Valida l\'utente',
	'update_uuid' => 'Aggiorna UUID',
	'unable_to_update_uuid' => 'Impossibile aggiornare l\'UUID.',
	'update_mc_name' => 'Aggiorna nome Minecraft',
	'reset_password' => 'Reset Password',
	'punish_user' => 'Punisci l\'utente',
	'delete_user' => 'Cancella utente',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'Indirizzo IP',
	'ip' => 'IP:',
	'other_actions' => 'Altre azioni:',
	'disable_avatar' => 'Disabilita avatar',
	'enable_avatar' => 'Abilita avatar',
	'confirm_user_deletion' => 'Sei sicuro di voler cancellare l\'utente {x}?', // Don't replace "{x}"
	'groups' => 'Gruppi',
	'group' => 'Gruppo',
	'group2' => 'Secondo Gruppo',
	'new_group' => 'Nuovo Gruppo',
	'id' => 'ID',
	'name' => 'Nome',
	'create_group' => 'Crea Gruppo',
	'group_name' => 'Nome Gruppo',
	'group_html' => 'HTML Gruppo',
	'group_html_lg' => 'HTML Largo del Gruppo',
	'donor_group_id' => 'ID Pacchetto Donor',
	'donor_group_id_help' => '<p>Questo &egrave; l\'ID del gruppo pacchetti su BuyCraft, MinecraftMarket oppure MCStock.</p><p>Questo pu&ograve; rimanere vuoto.</p>',
	'donor_group_instructions' => 	'<p>I gruppi Donor devono essere creati nell\'ordine del <strong>minor valore al maggior valore</strong>.</p>
									<p>Per esempio, un pacchetto da 10€ sar&agrave; creato prima di uno da 20€.</p>',
	'delete_group' => 'Cancella gruppo',
	'confirm_group_deletion' => 'Sei sicuro di voler cancellare il gruppo {x}?', // Don't replace "{x}"
	'group_staff' => 'Questo &egrave; un gruppo Staff?',
	'group_modcp' => 'Questo gruppo pu&ograve; vedere il ModCP?',
	'group_admincp' => 'Questo gruppo pu&ograve; vedere l\'AdminCP?',
	'group_name_required' => 'Devi inserire un nome gruppo.',
	'group_name_minimum' => 'Il nome del gruppo deve essere minimo 2 caratteri.',
	'group_name_maximum' => 'Il nome del gruppo pu&ograve; avere massimo 20 caratteri.',
	'html_maximum' => 'l\'HTML del gruppo pu&ograve; essere massimo 1024 caratteri.',
	'select_user_group' => 'L\'utente deve essere in un gruppo.',
	'uuid_max_32' => 'L\'UUID pu&ograve; essere massimo 32 caratteri.',
	'cant_delete_root_user' => 'Non puoi cancellare l\'utente root!',
	'cant_modify_root_user' => 'Non puoi modificare il gruppo dell\'utente root.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Impostazioni Minecraft',
	'use_plugin' => 'Abilitare le API Nameless?',
	'force_avatars' => 'Forzare gli avatar di Minecraft?',
	'uuid_linking' => 'Abilitare il linking UUID?',
	'use_plugin_help' => 'Abilitando le API, insieme al plugin per il server, mette a disposizione la sincronizzazione dei rank e la registrazione dal gioco e la sottomissione dei report.',
	'uuid_linking_help' => 'Se disabilitato, gli account degli utenti non saranno collegati con gli UUID. &Egrave; altamente raccomandato di lasciarlo su abilitato.',
	'plugin_settings' => 'Impostazioni plugin',
	'confirm_api_regen' => 'Sei sicuro di voler generato una nuova chiave API?',
	'servers' => 'Server',
	'new_server' => 'Nuovo Server',
	'confirm_server_deletion' => 'Sei sicuro di voler cancellare questo server?',
	'main_server' => 'Server principale',
	'main_server_help' => 'Il server attraverso il quale gli utenti si collegano. Di solito questa sar&agrave; un\'istanza BungeeCord.',
	'choose_a_main_server' => 'Scegli un server principale..',
	'external_query' => 'Usare la Query esterna?',
	'external_query_help' => 'Usare un\'API esterna per mandare una Query al server Minecraft? Usalo solo se quella default non funziona; &egrave; altamente raccomandato lasciare questo disabilitato.',
	'editing_server' => 'Modificando server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'IP del Server (con porta) (numerico o dominio)',
	'server_ip_with_port_help' => 'Questo &egrave; l\'indirizzo che verr&agrave; mostrato agli utenti. Non sar&agrave; usato per la query.',
	'server_ip_numeric' => 'IP del server (con porta) (solo numerico)',
	'server_ip_numeric_help' => 'Questo &egrave; l\'ip che verr&agrave; usato per la query, per favore assicurati sia solo numerico. Non sar&agrave; mostrato agli utenti.',
	'show_on_play_page' => 'Mostrare sulla pagina Play?',
	'pre_17' => 'Versione Minecraft Pre 1.7?',
	'server_name' => 'Nome server',
	'invalid_server_id' => 'Server ID non valido',
	'show_players' => 'Mostrare la lista utenti sulla pagina Play?',
	'server_edited' => 'Server modificato con successo',
	'server_created' => 'Server creato con successo',
	'query_errors' => 'Errori Query',
	'query_errors_info' => 'Gli errori qui sotto ti permettono di capire quali problemi ci sono con la tua query interna.',
	'no_query_errors' => 'Non ci sono errori salvati',
	'date' => 'Data:',
	'port' => 'Porta:',
	'viewing_error' => 'Vedendo errore',
	'confirm_error_deletion' => 'Sei sicuro di voler cancellare quest\'errore?',
	'display_server_status' => 'Mostrare il modulo dello stato del server?',
	'server_name_required' => 'Devi inserire un nome del server.',
	'server_ip_required' => 'Devi inserire un IP del server.',
	'server_name_minimum' => 'Il nome del server deve essere minimo 2 caratteri.',
	'server_ip_minimum' => 'L\'IP del server deve essere minimo 2 caratteri.',
	'server_name_maximum' => 'Il nome del server pu&ograve; essere massimo 20 caratteri.',
	'server_ip_maximum' => 'L\'IP del server pu&ograve; essere massimo 64 caratteri.',
	'purge_errors' => 'Elimina gli errori',
	'confirm_purge_errors' => 'Sei sicuro di voler cancellare tutti gli errori della Query?',
	'avatar_type' => 'Tipo di avatar',
	'custom_usernames' => 'Forzare gli username di Minecraft?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Usare mcassoc?',
	'use_mcassoc_help' => 'mcassoc si assicura che gli utenti siano proprietari dell\'account Minecraft con il quale si registrano',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Chiave mcassoc non valida.',
	'mcassoc_instance' => 'Istanza mcassoc',
	'mcassoc_instance_help' => 'Genera un codice istanza <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">qui</a>',
	'mcassoc_key_help' => 'Prendi la tua chiave mcassoc <a href="https://mcassoc.lukegb.com/" target="_blank">qui</a>',
	'enable_name_history' => 'Abilitare la cronologia username?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Temi',
	'templates' => 'Template',
	'installed_themes' => 'Temi installati',
	'installed_templates' => 'Template installati',
	'installed_addons' => 'Addon installati',
	'install_theme' => 'Installa un Tema',
	'install_template' => 'Installa un Template',
	'install_addon' => 'Installa un Addon',
	'install_a_theme' => 'Installa un Tema',
	'install_a_template' => 'Installa un Template',
	'install_an_addon' => 'Installa un Addon',
	'active' => 'Attivo',
	'activate' => 'Attiva',
	'deactivate' => 'Disattiva',
	'theme_install_instructions' => 'Per favore carica i temi alla cartella <strong>styles/themes</strong>. Dopodich&egrave; premi il tasto "Trova" qui sotto.',
	'template_install_instructions' => 'Per favore carica i template alla cartella <strong>styles/templates</strong>. Dopodich&egrave; premi il tasto "Trova" qui sotto.',
	'addon_install_instructions' => 'Per favore carica gli addon alla cartella <strong>addons</strong>. Dopodich&egrave; clicca il tasto "Trova" qui sotto.',
	'addon_install_warning' => 'Gli Addon sono installati a tuo rischio e pericolo, per favore esegui un backup dei file e del database prima di procedere.',
	'scan' => 'Trova',
	'theme_not_exist' => 'Quel Tema non esiste!',
	'template_not_exist' => 'Quel Template non esiste!',
	'addon_not_exist' => 'Quell\'Addon non esiste!',
	'style_scan_complete' => 'Completato, qualsiasi nuovo stile &egrave; stato installato.',
	'addon_scan_complete' => 'Completato, qualsiasi nuovo Addon &egrave; stato installato.',
	'theme_enabled' => 'Tema abilitato.',
	'template_enabled' => 'Template abilitato.',
	'addon_enabled' => 'Addon abilitato.',
	'theme_deleted' => 'Tema cancellato.',
	'template_deleted' => 'Template cancellato.',
	'addon_disabled' => 'Addon disabilitato.',
	'inverse_navbar' => 'Inverti la Navbar',
	'confirm_theme_deletion' => 'Sei sicuro di voler cancellare il tema <strong>{x}</strong>?<br /><br />Sar&agrave; cancellato dalla cartella <strong>styles/themes</strong>.', // Don't replace {x}
	'confirm_template_deletion' => 'Sei sicuro di voler cancellare il template <strong>{x}</strong>?<br /><br />Sar&agrave; cancellato dalla cartella <strong>styles/templates</strong>.', // Don't replace {x}
	'unable_to_enable_addon' => 'Impossibile abilitare l\'addon. Per favore assicurati che sia un addon valido di NamelessMC.',
	
	// Admin Misc page
	'other_settings' => 'Altre impostazioni',
	'enable_error_reporting' => 'Abilitare il report degli errori?',
	'error_reporting_description' => 'Questo dovrebbe essere usato solo per situazioni di debug, &egrave; altamente consigliato di lasciarlo disabilitato.',
	'display_page_load_time' => 'Mostrare il tempo di caricamento della pagina?',
	'page_load_time_description' => 'Questo mostrer&agrave; un contatore di velocit&agrave; nel footer che segner&agrave; il tempo di velocit&agrave; di caricamento della pagina.',
	'reset_website' => 'Resetta sito',
	'reset_website_info' => 'Questo resetter&agrave; le impostazioni del tuo sito. <strong>Gli addon saranno disabilitati ma non rimossi e le loro impostazioni non cambieranno.</strong>Anche i server Minecraft che hai definito rimarranno.',
	'confirm_reset_website' => 'Sei sicuro di voler resettare le impostazioni del tuo sito?',
	
	// Admin Update page
	'installation_up_to_date' => 'Hai gi&agrave; la versione corrente.',
	'update_check_error' => 'Impossibile controllare gli aggiornamenti. Per favore riprova pi&ugrave; tardi.',
	'new_update_available' => 'Un nuovo aggiornamento &egrave; disponibile.',
	'your_version' => 'La tua versione:',
	'new_version' => 'Nuova versione:',
	'download' => 'Scarica',
	'update_warning' => 'Attenzione: Assicurati che hai scaricato il pacchetto e caricato i file contenuti prima di proseguire!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Home',
	'play' => 'Play',
	'forum' => 'Forum',
	'more' => 'Altro',
	'staff_apps' => 'Richieste Staff',
	'view_messages' => 'Vedi messaggi',
	'view_alerts' => 'Vedi gli avvisi',
	
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
	'create_an_account' => 'Crea un\'account',
	'authme_password' => 'Password AuthMe',
	'username' => 'Username',
	'minecraft_username' => 'Username di Minecraft',
	'email' => 'Email',
	'user_title' => 'Titolo',
	'email_address' => 'Indirizzo Email',
	'date_of_birth' => 'Data di nascita',
	'location' => 'Posizione',
	'password' => 'Password',
	'confirm_password' => 'Conferma Password',
	'i_agree' => 'Sono d\'accordo',
	'agree_t_and_c' => 'Cliccando <strong class="label label-primary">Registrati</strong>, accetti i nostri <a href="#" data-toggle="modal" data-target="#t_and_c_m">Termini e Condizioni</a>.',
	'register' => 'Registrati',
	'sign_in' => 'Entra',
	'sign_out' => 'Esci',
	'terms_and_conditions' => 'Termini e Condizioni',
	'successful_signin' => 'Ti sei registrato con successo',
	'incorrect_details' => 'Credenziali errate',
	'remember_me' => 'Ricordami',
	'forgot_password' => 'Password dimenticata',
	'must_input_username' => 'Devi inserire un username.',
	'must_input_password' => 'Devi inserire una password.',
	'inactive_account' => 'Il tuo account &egrave; attualmente inattivo, hai richiesto un reset password?',
	'account_banned' => 'Sei stato bannato.',
	'successfully_logged_out' => 'Sei uscito con successo.',
	'signature' => 'Firma',
	'registration_check_email' => 'Per favore, controlla la tua email per il link di validazione. Non potrai entrare finch&egrave; non lo userai.',
	'unknown_login_error' => 'Scusami, c\'&egrave; stato un\'errore sconosciuto mentre tentavo di farti entrare, per favore riprova pi&ugrave; tardi.',
	'validation_complete' => 'Grazie per esserti registrato! Adesso puoi eseguire il login.',
	'validation_error' => 'Errore processando la tua richiesta. Per favore clicca sul link di nuovo.',
	'registration_error' => 'Per favore assicurati di aver riempito tutti i campi e che il tuo username sia fra 3 e 20 caratteri di lunghezza e la tua password fra 6 e 30 caratteri di lunghezza.',
	'username_required' => 'Per favore inserisci un username.',
	'password_required' => 'Per favore inserisci una password.',
	'email_required' => 'Per favore inserisci un indirizzo email.',
	'mcname_required' => 'Per favore inserisci un username Minecraft.',
	'accept_terms' => 'Devi accettare i Termini e Condizioni prima di poterti registrare.',
	'invalid_recaptcha' => 'risposta reCAPTCHA non valida.',
	'username_minimum_3' => 'Il tuo username deve essere minimo 3 caratteri.',
	'username_maximum_20' => 'Il tuo username pu&ograve; essere massimo 20 caratteri.',
	'mcname_minimum_3' => 'Il tuo username Minecraft deve essere minimo 3 caratteri',
	'mcname_maximum_20' => 'Il tuo username Minecraft pu&ograve; essere massimo 20 caratteri.',
	'password_minimum_6' => 'La tua password deve essere minimo 6 caratteri.',
	'password_maximum_30' => 'La tua password pu&ograve; essere massimo 30 caratteri.',
	'passwords_dont_match' => 'Le password inserite non sono uguali.',
	'username_mcname_email_exists' => 'Il tuo username, username di Minecraft o la tua email esistono gi&agrave;. Hai gi&agrave; creato un\'account?',
	'invalid_mcname' => 'Il tuo username Minecraft non &egrave; un account valido',
	'mcname_lookup_error' => 'C\'&egrave; stato un\'errore contattando i server della Mojany. Per favore riprova pi&ugrave; tardi.',
	'signature_maximum_900' => 'La tua firma deve essere massimo 900 caratteri.',
	'invalid_date_of_birth' => 'Data di nascita non valida.',
	'location_required' => 'Per favore inserisci una posizione.',
	'location_minimum_2' => 'La tua posizione deve essere minimo 2 caratteri.',
	'location_maximum_128' => 'La tua posizione pu&ograve; essere massimo 128 caratteri.',
	'verify_account' => 'Verifica account',
	'verify_account_help' => 'Per favore segui le istruzioni qui sotto cos&igrave; che possiamo verificare il tuo account Minecraft in questione.',
	'verification_failed' => 'Verifica fallita, per favore riprova.',
	'verification_success' => 'Validato con successo! Ora puoi eseguire l\'accesso.',
	'complete_signup' => 'Registrazione completata',
	'registration_disabled' => 'La registrazione sul sito &egrave; attualmente disabilitata.',
	
	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => 'Nessun file scelto',
	'private_messages' => 'Messaggi privati',
	'profile_settings' => 'Impostazioni profilo',
	'your_profile' => 'Il tuo profilo',
	'topics' => 'Topic',
	'posts' => 'Post',
	'reputation' => 'Reputazione',
	'friends' => 'Amici',
	'alerts' => 'Avvisi',
	
	// Messaging
	'new_message' => 'Nuovi messaggi',
	'no_messages' => 'Nessun messaggio',
	'and_x_more' => 'e altri {x}', // Don't replace "{x}"
	'system' => 'Sistema',
	'message_title' => 'Titolo del Messaggio',
	'message' => 'Messaggio',
	'to' => 'A:',
	'separate_users_with_comma' => 'Separa gli utenti con una virgola (",")',
	'viewing_message' => 'Vedendo il messaggio',
	'delete_message' => 'Cancella messaggio',
	'confirm_message_deletion' => 'Sei sicuro di voler cancellare questo messaggio?',
	
	// Profile settings
	'display_name' => 'Nome pubblico',
	'upload_an_avatar' => 'Carica un avatar (.jpg, .png or .gif only):',
	'use_gravatar' => 'Usare Gravatar?',
	'change_password' => 'Cambia password',
	'current_password' => 'Password attuale',
	'new_password' => 'Nuova password',
	'repeat_new_password' => 'Ripeti la nuova password',
	'password_changed_successfully' => 'Password cambiata con successo',
	'incorrect_password' => 'La tua password attuale non &egrave; corretta',
	'update_minecraft_name_help' => 'Questo aggiorner&agrave; il tuo nome sul sito con il tuo attuale username Minecraft. Puoi eseguire quest\'azione solo una volta ogni 30 giorni.',
	'unable_to_update_mcname' => 'Impossibile aggiornare l\'username Minecraft.',
	'display_age_on_profile' => 'Mostrare l\'et&agrave; sul profilo?',
	'two_factor_authentication' => 'Autenticazione a doppio fattore',
	'enable_tfa' => 'Abilita l\'autenticazione a doppio fattore',
	'tfa_type' => 'Tipologia di autenticazione a doppio fattore:',
	'authenticator_app' => 'Applicazione di Autenticazione',
	'tfa_scan_code' => 'Per favore inquadra con la fotocamera questo codice dalla tua applicazione di autenticazione:',
	'tfa_code' => 'Se il tuo dispositivo non ha una fotocamera, o non sei in grado di inquadrare il codice QR, per favore inserisci il seguente codice:',
	'tfa_enter_code' => 'Per favore inserisci il codice mostrato sulla tua applicazione di autenticazione:',
	'invalid_tfa' => 'Codice non valido, per favore riprova.',
	'tfa_successful' => 'Autenticazione a doppio fattore impostata con successo. Avrai bisogno di autenticarti con questa ogni volta che esegui il login d\'ora in avanti.',
	'confirm_tfa_disable' => 'Sei sicuro di voler disabilitare l\'autenticazione a doppio fattore?',
	'tfa_disabled' => 'Autenticazione a doppio fattore disabilitata.',
	'tfa_enter_email_code' => 'Ti abbiamo inviato un codice alla tua email per verifica. Inserisci il codice ora:',
	'tfa_email_contents' => '&Egrave; stato effettuato un tentativo di accesso al tuo account. Se sei stato tu, per favore inserisci il codice dell\'autenticazione a doppio fattore quando chiesto. Se non sei stato tu, puoi ignorare questa email, in ogni caso, un reset della password sarebbe opportuno. Il codice &egrave; valido solo per 10 minuti.',
	
	// Alerts
	'viewing_unread_alerts' => 'Osservando gli avvisi non letti. Cambia a <a href="/user/alerts/?view=read"><span class="label label-success">letti</span></a>.',
	'viewing_read_alerts' => 'Osservando gli avvisi letti. Cambia a <a href="/user/alerts/"><span class="label label-warning">non letti</span></a>.',
	'no_unread_alerts' => 'Non hai avvisi non letti.',
	'no_alerts' => 'Nessun avviso',
	'no_read_alerts' => 'Non hai avvisi letti.',
	'view' => 'Vedi',
	'alert' => 'Avviso',
	'when' => 'Quando',
	'delete' => 'Elimina',
	'tag' => 'Tag Utente',
	'tagged_in_post' => 'Sei stato taggato in un post',
	'report' => 'Segnala',
	'deleted_alert' => 'Avviso cancellato con successo',
	
	// Warnings
	'you_have_received_a_warning' => 'Hai ricevuto un avviso da {x} in data {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Riconoscere',
	
	// Forgot password
	'password_reset' => 'Reset Password',
	'email_body' => 'Stai ricevendo quest\'email perch&egrave; hai richiesto un reset della password. Per poterlo fare, per favore usa il link seguente:', // Body for the password reset email
	'email_body_2' => 'Se non hai richiesto un reset della password, puoi ignorare quest\'email.',
	'password_email_set' => 'Successo. Per favore controlla le tue email per maggiori informazioni.',
	'username_not_found' => 'Quell\'username non esiste.',
	'change_password' => 'Cambia password',
	'your_password_has_been_changed' => 'La tua password &egrave; stata cambiata.',
	
	// Profile page
	'profile' => 'Profilo',
	'player' => 'Utente',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registrato:',
	'pf_posts' => 'Post:',
	'pf_reputation' => 'Reputazione:',
	'user_hasnt_registered' => 'Quest\'utente non si &egrave; ancora registrato sul nostro sito',
	'user_no_friends' => 'Quest\'utente non ha aggiunto alcun amico',
	'send_message' => 'Invia Messaggio',
	'remove_friend' => 'Cancella amico',
	'add_friend' => 'Aggiungi amico',
	'last_online' => 'Ultimo accesso:',
	'find_a_user' => 'Trova un\'utente',
	'user_not_following' => 'Quest\'utente non segue nessuno.',
	'user_no_followers' => 'Quest\'utente non ha seguaci.',
	'following' => 'Segue',
	'followers' => 'Seguaci',
	'display_location' => 'Da {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, da {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Scrivi qualcosa sul profilo di {x} ...', // Don't replace {x}
	'write_on_own_profile' => 'Scrivi qualcosa sul tuo profilo...',
	'profile_posts' => 'Post del profilo',
	'no_profile_posts' => 'Non c\'&egrave; ancora nessun post.',
	'invalid_wall_post' => 'Muro dei post non valido. Per favore assicurati che il tuo post sia fra 2 e 2048 caratteri di lunghezza.',
	'about' => 'Di',
	'reply' => 'Rispondi',
	'x_likes' => '{x} mi piace', // Don't replace {x}
	'likes' => 'Mi piace',
	'no_likes' => 'Nessun mi piace.',
	'post_liked' => 'Post piaciuti.',
	'post_unliked' => 'Post non piaciuti.',
	'no_posts' => 'Nessun post.',
	'last_5_posts' => 'Ultimi 5 post',
	'follow' => 'Segui',
	'unfollow' => 'Smetti di seguire',
	'name_history' => 'Cambiamenti del nome',
	'changed_name_to' => 'Cambiato nome da: {x} il {y}', // Don't replace {x} or {y}
	'original_name' => 'Nome originale:',
	'name_history_error' => 'Impossibile mostrare la lista cambiamenti.',
	
	// Staff applications
	'staff_application' => 'Richieste staff',
	'application_submitted' => 'Richiesta staff inviata con successo.',
	'application_already_submitted' => 'Hai gi&agrave; inviato una richiesta staff. Per favore attendi che sia completata prima di invarne un\'altra.',
	'not_logged_in' => 'Per favore esegui l\'accesso per vedere quella pagina.',
	'application_accepted' => 'La tua richiesta staff &egrave; stata accettata.',
	'application_rejected' => 'La tua richiesta staff &egrave; stata respinta.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Panoramica',
	'reports' => 'Segnalazioni',
	'punishments' => 'Punizioni',
	'staff_applications' => 'Richieste staff',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warn',
	'search_for_a_user' => 'Cerca un\'utente',
	'user' => 'Utente:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registrato',
	'reason' => 'Motivo:',
	'cant_ban_root_user' => 'Non puoi punire l\'utente root!',
	'invalid_reason' => 'Per favore inserisci una motivazione valida fra 2 e 256 caratteri di lunghezza.',
	'punished_successfully' => 'Punizione aggiunta con success.',
	
	// Reports
	'report_closed' => 'Segnalazione chiusa.',
	'new_comment' => 'Nuovo commento',
	'comments' => 'Commenti',
	'only_viewed_by_staff' => 'Pu&ograve; esser visto solo dallo staff',
	'reported_by' => 'Segnalato da',
	'close_issue' => 'Chiudi problematica',
	'report' => 'Segnalazione:',
	'view_reported_content' => 'Vedi i contenuti segnalati',
	'no_open_reports' => 'Nessuna segnalazione aperta',
	'user_reported' => 'Utente segnalato',
	'type' => 'Tipo',
	'updated_by' => 'Aggiornato da',
	'forum_post' => 'Post sul Forum',
	'user_profile' => 'Profilo utente',
	'comment_added' => 'Commento aggiunto.',
	'new_report_submitted_alert' => 'Nuova segnalazione inviata da {x} riguardante l\'utente {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Segnalazione effettuata sul gioco',
	
	// Staff applications
	'comment_error' => 'Per favore assicurati che il tuo commento sia fra 2 e 2048 caratteri di lunghezza.',
	'viewing_open_applications' => 'Vedendo le richieste <span class="label label-info">aperte</span>. Cambia a <a href="/mod/applications/?view=accepted"><span class="label label-success">accettate</span></a> o <a href="/mod/applications/?view=declined"><span class="label label-danger">respinte</span></a>.',
	'viewing_accepted_applications' => 'Vedendo le richieste <span class="label label-success">accettate</span>. Cambia a <a href="/mod/applications/"><span class="label label-info">aperte</span></a> o <a href="/mod/applications/?view=declined"><span class="label label-danger">respinte</span></a>.',
	'viewing_declined_applications' => 'Vedendo le richieste <span class="label label-danger">respinte</span>. Cambia a <a href="/mod/applications/"><span class="label label-info">aperte</span></a> o <a href="/mod/applications/?view=accepted"><span class="label label-success">accettate</span></a>.',
	'time_applied' => 'Tempo impiegato',
	'no_applications' => 'Nessuna richiesta in questa categoria',
	'viewing_app_from' => 'Vedendo la richiesta da {x}', // Don't replace "{x}"
	'open' => 'Aperta',
	'accepted' => 'Accettata',
	'declined' => 'Respinta',
	'accept' => 'Accetta',
	'decline' => 'Respingi',
	'new_app_submitted_alert' => 'Nuova richiesta inviata da {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Novit&agrave;',
	'social' => 'Social',
	'join' => 'Entra',
	
	// General terms
	'submit' => 'Invia',
	'close' => 'Chiudi',
	'cookie_message' => '<strong>Questo sito utilizza i cookie per migliorare la tua esperienza di navigazione.</strong> <p>Continuando a navigare e interagire con il sito, accetti il loro utilizzo.</p>',
	'theme_not_exist' => 'Il tema selezionato non esiste.',
	'confirm' => 'Conferma',
	'cancel' => 'Cancella',
	'guest' => 'Ospite',
	'guests' => 'Ospiti',
	'back' => 'Indietro',
	'search' => 'Cerca',
	'help' => 'Aiuto',
	'success' => 'Successo',
	'error' => 'Errore',
	'view' => 'Vedi',
	'info' => 'Info',
	'next' => 'Prossimo',
	
	// Play page
	'connect_with' => 'Connettiti al server con l\'IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Stato:',
	'players_online' => 'Utenti Online:',
	'queried_in' => 'Richiesto in:',
	'server_status' => 'Stato del server',
	'no_players_online' => 'Non ci sono utenti online.',
	'1_player_online' => 'C\'&egrave; un utente online.',
	'x_players_online' => 'Ci sono {x} utenti online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Pagina caricata in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Nessuno',
	'404' => 'Oops, sembra che la pagina cercata non esiste.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forum',
	'discussion' => 'Discussione',
	'stats' => 'Statistiche',
	'last_reply' => 'Ultima risposta',
	'ago' => 'fa',
	'by' => 'da',
	'in' => 'in',
	'views' => 'visualizzazioni',
	'posts' => 'post',
	'topics' => 'topic',
	'topic' => 'Topic',
	'statistics' => 'Statistiche',
	'overview' => 'Panoramica',
	'latest_discussions' => 'Ultime discussioni',
	'latest_posts' => 'Ultimi post',
	'users_registered' => 'Utenti registrati:',
	'latest_member' => 'Ultimo membro:',
	'forum' => 'Forum',
	'last_post' => 'Ultimo post',
	'no_topics' => 'Non c\'&egrave; ancora nessun post',
	'new_topic' => 'Nuovo topic',
	'subforums' => 'Sotto-Forum:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Topic bloccato',
	'new_reply' => 'Nuova risposta',
	'mod_actions' => 'Azioni Mod',
	'lock_thread' => 'Blocca Thread',
	'unlock_thread' => 'Sblocca',
	'merge_thread' => 'Fondi Thread',
	'delete_thread' => 'Cancella Thread',
	'confirm_thread_deletion' => 'Sei sicuro di voler cancellare questo Thread?',
	'move_thread' => 'Muovi Thread',
	'sticky_thread' => 'Sticky Thread',
	'report_post' => 'Segnala post',
	'quote_post' => 'Quota post',
	'delete_post' => 'Cancella post',
	'edit_post' => 'Modifica post',
	'reputation' => 'reputazione',
	'confirm_post_deletion' => 'Sei sicuro di voler cancellare questo post?',
	'give_reputation' => 'Dai reputazione',
	'remove_reputation' => 'Rimuovi reputazione',
	'post_reputation' => 'Reputazione del post',
	'no_reputation' => 'Non c\'&egrave; ancora nessuna reputazione disponibile per questo post',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Crea post',
	'post_submitted' => 'Post inviato',
	'creating_post_in' => 'Creando un post in: ',
	'topic_locked_permission_post' => 'Questo topic &egrave; bloccato, in ogni caso, i tuoi permessi ti permettono di postare',
	
	// Edit post view
	'editing_post' => 'Modificando post',
	
	// Sticky threads
	'thread_is_' => 'il Thread &egrave; ',
	'now_sticky' => 'adesso sticky',
	'no_longer_sticky' => 'non pi&ugrave; sticky',
	
	// Create topic
	'topic_created' => 'Topic creato.',
	'creating_topic_in_' => 'Creando un topic in un forum ',
	'thread_title' => 'Titolo thread',
	'confirm_cancellation' => 'Sei sicuro?',
	'label' => 'Etichetta',
	
	// Reports
	'report_submitted' => 'Segnalazione inviata.',
	'view_post_content' => 'Vedi il contenuto del post',
	'report_reason' => 'Motivazione della segnalazione',
	
	// Move thread
	'move_to' => 'Muovi a:',
	
	// Merge threads
	'merge_instructions' => 'Il thread da fondere <strong>deve</strong> essere dello stesso forum. Muovi un thread se necessario.',
	'merge_with' => 'Fondi con:',
	
	// Other
	'forum_error' => 'Oops, non siamo stati in grado di trovare quel forum o topic.',
	'are_you_logged_in' => 'Hai effettuato l\'accesso?',
	'online_users' => 'Utenti online',
	'no_users_online' => 'Non ci sono utenti online.',
	
	// Search
	'search_error' => 'Per favore inserisci una contenuto di ricerca fra 1 e 32 caratteri.',
	'no_search_results' => 'La ricerca non ha prodotto nessun risultato.',
	
	//Share on a social-media.
	'sm-share' => 'Condividi',
	'sm-share-facebook' => 'Condividi su Facebook',
	'sm-share-twitter' => 'Condividi su Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Salve',
	'message' => 'Grazie per esserti registrato! Per poter completare la tua registrazione, clicca il seguente link:',
	'thanks' => 'Grazie,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'meno di un minuto fa',
	'1_minute' => '1 minuto fa',
	'_minutes' => '{x} minuti fa',
	'about_1_hour' => 'circa un\'ora fa',
	'_hours' => '{x} ore fa',
	'1_day' => 'un giorno fa',
	'_days' => '{x} giorni fa',
	'about_1_month' => 'circa 1 mese fa',
	'_months' => '{x} mesi fa',
	'about_1_year' => 'circa 1 anno fa',
	'over_x_years' => 'oltre {x} anni fa'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Mostra _MENU_ risultato per pagina', // Don't replace "_MENU_"
	'nothing_found' => 'Nessun risultato trovato',
	'page_x_of_y' => 'Mostrando pagina _PAGE_ di _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Nessun risultato disponibile',
	'filtered' => '(filtrato da _MAX_ risultati totali)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Completa la registrazione'
);
 
?>
