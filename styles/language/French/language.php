<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
/*
 *  French Language
 *  Translation made by Pandalyser
 *  pandalyser@gmail.com / Official Mascot of ONY
 *
 *  Thanks to CreaModZ (creamodz.fr) for the pre-translation
 *  https://www.spigotmc.org/members/creamodz.6937/
 *
 *  Last edition: 26/04/2017 22:39 (UTF+1) by Aviortheking
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP', 
	'infractions' => 'Infractions',
	'invalid_token' => 'Token invalide, merci de réessayer!',
	'invalid_action' => 'Action invalide',
	'successfully_updated' => 'Mis à jour avec succès!',
	'settings' => 'Réglages',
	'confirm_action' => 'Confirmer l\'action',
	'edit' => 'Editer',
	'actions' => 'Actions',
	'task_successful' => 'Tâche effectuée avec succès!',
	
	// Admin login
	're-authenticate' => 'Ré-identifiez-vous, merci!',
	
	// Admin sidebar
	'index' => 'Principal',
	'announcements' => 'Annonces',
	'core' => 'Core',
	'custom_pages' => 'Pages Persos',
	'general' => 'Général',
	'forums' => 'Forums',
	'users_and_groups' => 'Utilisateurs & Groupes',
	'minecraft' => 'Minecraft',
	'style' => 'Style',
	'addons' => 'Addons',
	'update' => 'Mise à jour',
	'misc' => 'Autre',
	'help' => 'Aide',
	
	// Admin index page
	'statistics' => 'Statistiques',
	'registrations_per_day' => 'Enregistrements par jour (7 derniers jours)',
	
	// Admin announcements page
	'current_announcements' => 'Annonces en cours',
	'create_announcement' => 'Créer une Annonce',
	'announcement_content' => 'Contenu de l\'Annonce',
	'announcement_location' => 'Emplacement de l\'Annonce',
	'announcement_can_close' => 'Possibilité de fermer l\'Annonce?',
	'announcement_permissions' => 'Permissions de l\'Annonce',
	'no_announcements' => 'Aucune Annonce disponible.',
	'confirm_cancel_announcement' => 'Désirez-vous réellement annuler cette Annonce?',
	'announcement_location_help' => 'Ctrl-clic pour sélectionner de multiples Annonces',
	'select_all' => 'Sélectionner tout',
	'deselect_all' => 'Désélectionner tout',
	'announcement_created' => 'Annonce créée avec succès',
	'please_input_announcement_content' => 'Veuillez saisir les informations de l\'Annonce et sélectionner le type.',
	'confirm_delete_announcement' => 'Désirez-vous réellement supprimer cette Annonce?',
	'announcement_actions' => 'Actions de l\'Annonce',
	'announcement_deleted' => 'Annonce supprimée avec succès',
	'announcement_type' => 'Type de l\'Annonce',
	'can_view_announcement' => 'Possibilité de voir l\'Annonce?',
	
	// Admin core page
	'general_settings' => 'Réglages généraux',
	'modules' => 'Modules',
	'module_not_exist' => 'Ce module n\'existe pas!',
	'module_enabled' => 'Module activé.',
	'module_disabled' => 'Module désactivé.',
	'site_name' => 'Nom du site',
	'language' => 'Langage',
	'voice_server_not_writable' => 'core/voice_server.php n\'est pas accessible à l\'écriture. Merci de vérifier vos permissions de fichier.',
	'email' => 'Email',
	'incoming_email' => 'Adresse Email entrante',
	'outgoing_email' => 'Adresse Email sortante',
	'outgoing_email_help' => 'Demander uniquement si la fonction PHP mail est activée',
	'use_php_mail' => 'Utiliser la fonction PHP mail()?',
	'use_php_mail_help' => 'Recommendé: activé. Si votre site web n\'envoie pas de mail, merci de désactiver cela et d\'éditer core/email.php avec vos réglages emails.',
	'use_gmail' => 'Utiliser GMail pour l\'envoi de mail?',
	'use_gmail_help' => 'Disponible uniquement si la fonction PHP mail est désactivée. Si vous ne désirez pas utiliser GMail, SMTP sera utilisé. Veuillez le configurer dans core/email.php.',
	'enable_mail_verification' => 'Activer la vérification des comptes par adresse email?',
	'enable_email_verification_help' => 'Activer cela demandera aux nouveaux utilisateurs de vérifier leur compte par mail avant de compléter leur enregistrement.',
	'explain_email_settings' => 'Cette option est requise si l\'option "Utiliser la fonction PHP mail()?" est <strong>désactivée</strong>. Vous trouverez la documentation pour ces paramètres <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">sur le wiki officiel de NamelessMC</a>.',
	'email_config_not_writable' => 'Le fichier <strong>core/email.php</strong> n\'est pas modifiable. Veuillez vérifier les permissions du fichier.',
	'pages' => 'Pages',
	'enable_or_disable_pages' => 'Activer ou désactiver des pages',
	'enable' => 'Activer',
	'disable' => 'Désactiver',
	'maintenance_mode' => 'Mode de Maintenance du Forum',
	'forum_in_maintenance' => 'Le Forum est actuellement en Mode de Maintenance.',
	'unable_to_update_settings' => 'Impossible de mettre les paramètres à jour. Assurez-vous que tous les champs sont remplis.',
	'editing_google_analytics_module' => 'Éditer le module Google Analytics',
	'tracking_code' => 'Code de suivi',
	'tracking_code_help' => 'Insérer le code de suivi pour Google Analytics, incluant les script tags (javascript).',
	'google_analytics_help' => 'Voir <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">ce guide</a> pour plus d\'informations.',
	'social_media_links' => 'Liens de média sociaux',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Utiliser le Thème "dark" pour Twitter?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Enregistrement',
	'registration_warning' => 'Avec ce module actif, les nouveaux membres ne pourront plus s\'inscrire sur votre site.',
	'google_recaptcha' => 'Activer Google reCAPTCHA',
	'recaptcha_site_key' => 'Clé de site reCAPTCHA',
	'recaptcha_secret_key' => 'Clé secrète reCAPTCHA',
	'registration_terms_and_conditions' => 'Conditions générales d\'enregistrement',
	'voice_server_module' => 'Module de "Chat Vocal / VOIP"',
	'only_works_with_teamspeak' => 'Ce module fonctionne avec TeamSpeak et Discord',
	'discord_id' => 'ID du serveur Discord',
	'voice_server_help' => 'Entrer les détails pour l\'utilisateur du ServerQuery (TeamSpeak)',
	'ip_without_port' => 'IP (sans le port)',
	'voice_server_port' => 'Port du serveur (s\'il y en a un)',
	'virtual_port' => 'Port virtuel (s\'il y en a un)',
	'permissions' => 'Permissions:',
	'view_applications' => 'Voir les Applications?',
	'accept_reject_applications' => 'Accepter/rejeter les Applications?',
	'questions' => 'Questions:',
	'question' => 'Question',
	'type' => 'Type',
	'options' => 'Options',
	'options_help' => 'Chaque option sur une nouvelle ligne; les lignes peuvent être vides',
	'no_questions' => 'Aucune question',
	'new_question' => 'Nouvelle question',
	'editing_question' => 'Éditer la question',
	'delete_question' => 'Supprimer la question',
	'dropdown' => 'Abaisser',
	'text' => 'Texte',
	'textarea' => 'Zone de texte',
	'question_deleted' => 'Question supprimée',
	'name_required' => 'Name is required.',
	'question_required' => 'Question is required.',
	'name_minimum' => 'Name must be a minimum of 2 characters.',
	'question_minimum' => 'Question must be a minimum of 2 characters.',
	'name_maximum' => 'Name must be a maximum of 16 characters.',
	'question_maximum' => 'Question must be a maximum of 16 characters.',
	'use_followers' => 'Utiliser la fonction "Fan"?',
	'use_followers_help' => 'Si la fonction "Fan" est désactivée, la fonction "Amis" sera utilisée.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Cliquez sur une page pour l\'éditer.',
	'page' => 'Page:',
	'url' => 'URL:',
	'page_url' => 'URL de la Page',
	'page_url_example' => '(Avec le "/" précédant, par exemple /boutique/)',
	'page_title' => 'Titre de la Page',
	'page_content' => 'Contenu de la Page',
	'new_page' => 'Nouvelle Page',
	'page_successfully_created' => 'Page créée avec succès!',
	'page_successfully_edited' => 'Page éditée avec succès!',
	'unable_to_create_page' => 'Impossible de créer une page.',
	'unable_to_edit_page' => 'Impossible d\'éditer cette page.',
	'create_page_error' => 'Assurez-vous d\'avoir entré une URL entre 1 et 20 caractères de longueur, un titre de page entre 1 et 30 caractères de longueur, et un contenu de page de 5 à 20480 caractères maximum.',
	'delete_page' => 'Supprimer cette Page',
	'confirm_delete_page' => 'Êtes-vous certain de vouloir supprimer cette Page?',
	'page_deleted_successfully' => 'Page supprimée avec succès',
	'page_link_location' => 'Afficher lien de Page',
	'page_link_navbar' => 'Barre de Navigation',
	'page_link_more' => 'Menu déroulant "Plus"',
	'page_link_footer' => 'Pied de Page',
	'page_link_none' => 'Aucun lien de Page',
	'page_permissions' => 'Permissions de la Page',
	'can_view_page' => 'Possibilité de voir la Page?',
	'redirect_page' => 'Rediriger la Page?',
	'redirect_link' => 'Rediriger le Lien',
	'page_icon' => 'Icône de la Page',
	
	// Admin forum page
	'labels' => 'Étiquettes',
	'new_label' => 'Nouvelle étiquette',
	'no_labels_defined' => 'Aucune étiquette définie',
	'label_name' => 'Nom de l\'étiquette',
	'label_type' => 'Type d\'étiquette',
	'label_forums' => 'Étiquette de forums',
	'label_creation_error' => 'Erreur lors de la création d\'une étiquette. Assurez-vous que le nom ne dépasse pas 32 caractères et que vous avez spécifié un type.',
	'confirm_label_deletion' => 'Êtes-vous certain de vouloir supprimer cette étiquette?',
	'editing_label' => 'Éditer l\'étiquette',
	'label_creation_success' => 'Étiquette créée avec succès',
	'label_edit_success' => 'Label édité avec succès',
	'label_default' => 'Étiquette par défaut',
	'label_primary' => 'Étiquette primaire',
	'label_success' => 'Succès',
	'label_info' => 'Infos de l\'étiquette',
	'label_warning' => 'Avertissement',
	'label_danger' => 'Danger',
	'new_forum' => 'Nouveau Forum',
	'forum_layout' => 'Forme du Forum',
	'table_view' => 'Vue du Tableau',
	'latest_discussions_view' => 'Vue des Dernières Discussions',
	'create_forum' => 'Créer un forum',
	'forum_name' => 'Nom du Forum',
	'forum_description' => 'Description du Forum',
	'delete_forum' => 'Supprimer ce Forum',
	'move_topics_and_posts_to' => 'Déplacer les sujets et les posts...',
	'delete_topics_and_posts' => 'Supprimer les sujets et les posts',
	'parent_forum' => 'Forum parent',
	'has_no_parent' => 'Ce Forum n\'a pas de parent',
	'forum_permissions' => 'Permissions du Forum',
	'can_view_forum' => 'Peut voir le Forum',
	'can_create_topic' => 'Peut créer des sujets',
	'can_post_reply' => 'Peut poster des réponses',
	'display_threads_as_news' => 'Afficher les sujets sur la page principale?',
	'input_forum_title' => 'Saisir un titre de Forum.',
	'input_forum_description' => 'Saisir la description du Forum.',
	'forum_name_minimum' => 'Le nom du Forum doit avoir un minimum de 2 caractères.',
	'forum_description_minimum' => 'La description du Forum doit avoir un minimum de 2 caractères.',
	'forum_name_maximum' => 'Le nom du Forum doit avoir un maximum de 150 caractères.',
	'forum_description_maximum' => 'La description du Forum doit avoir un maximum de 255 caractères.',
	'forum_type_forum' => 'Type de Forums',
	'forum_type_category' => 'Catégorie de Forums',
	
	// Admin Users and Groups page
	'users' => 'Utilisateurs',
	'new_user' => 'Nouvel Utilisateur',
	'created' => 'Utilisateur créé',
	'user_deleted' => 'Utilisateur supprimé',
	'validate_user' => 'Valider un utilisateur',
	'update_uuid' => 'Mettre à jour l\'UUID',
	'unable_to_update_uuid' => 'Impossible de mettre à jour l\'UUID.',
	'update_mc_name' => 'Mettre à jour le pseudo Minecraft',
	'reset_password' => 'Réinitialiser le Mot de Passe',
	'punish_user' => 'Punir l\'utilisateur',
	'delete_user' => 'Supprimer l\'utilisateur',
	'minecraft_uuid' => 'UUID Minecraft',
	'ip_address' => 'Adresse IP',
	'ip' => 'IP:',
	'other_actions' => 'Autres actions:',
	'disable_avatar' => 'Désactiver l\'avatar',
	'enable_avatar' => 'Activer l\'avatar',
	'confirm_user_deletion' => 'Êtes-vous certain de vouloir supprimer cet utilisateur: {x}?', // Don't replace "{x}"
	'groups' => 'Groupes',
	'group' => 'Groupe',
	'group' => 'Groupe 2',
	'new_group' => 'Nouveau Groupe',
	'id' => 'ID',
	'name' => 'Nom',
	'create_group' => 'Créer un Groupe',
	'group_name' => 'Nom du Groupe',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'ID du groupe de Donateur',
	'donor_group_id_help' => '<p>Ceci est ID du groupe de lot de Buycraft, MinecraftMarket ou MCStock.</p><p>Ceci doit être vide.</p>',
	'donor_group_instructions' => 	'<p>Les groupes de Donateurs doivent être classés de <strong>la plus petite valeur à la plus grande valeur</strong>.</p>
									<p>Par exemple, un lot de 10€ sera créé avant un lot de 20€.</p>',
	'delete_group' => 'Supprimer ce Groupe',
	'confirm_group_deletion' => 'Êtes-vous certain de vouloir supprimer ce groupe: {x}?', // Don't replace "{x}"
	'group_staff' => 'Est-ce un groupe de Staff?',
	'group_modcp' => 'Le groupe peut-il voir le ModCP?',
	'group_admincp' => 'Le groupe peut-il voir le AdminCP?',
	'group_name_required' => 'Veuillez insérer un nom de Groupe.',
	'group_name_minimum' => 'Le nom du groupe doit avoir un minimum de 2 caratères.',
	'group_name_maximum' => 'Le nom du groupe doit avoir un maximum de 20 caractères.',
	'html_maximum' => 'Le groupe HTML doit avoir un maximum de 1024 caractères.',
	'select_user_group' => 'Cet Utilisateur doit être dans un Groupe.',
	'uuid_max_32' => 'Le UUID doit avoir un maximum de 32 caractères.',
	'cant_delete_root_user' => 'Impossible de supprimer le Fondateur du Groupe!',
	'cant_modify_root_user' => 'Impossible de modifier le Fondateur du Groupe!.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Réglages Minecraft',
	'use_plugin' => 'Utiliser le plugin Nameless Minecraft?',
	'force_avatars' => 'Forcer les avatars Minecraft?',
	'uuid_linking' => 'Activer les liens d\'UUID?',
	'use_plugin_help' => 'L\'utilisation dece plugin permet la synchronisation des rangs, de l\'enregistrement In-Game et la submission de tickets.',
	'uuid_linking_help' => 'Si cette option est désactivée, les Utilisateurs ne seront pas lier avec un UUID. Il est recommandé de laisser cette option activée.',
	'plugin_settings' => 'Paramètres de plugin',
	'confirm_api_regen' => 'Désirez-vous générer une nouvelle clé API?',
	'servers' => 'Serveurs',
	'new_server' => 'Nouveau Serveur',
	'confirm_server_deletion' => 'Êtes-vous certain de vouloir supprimer ce Serveur?',
	'main_server' => 'Serveur principal',
	'main_server_help' => 'Le Serveur où les joueurs se connectent.',
	'choose_a_main_server' => 'Choisir un Serveur principal',
	'external_query' => 'Utiliser une requête externe?',
	'external_query_help' => 'Utiliser un API externe pour envoyer une requête au serveur Minecraft? Utiliser seulement si la requête ne fonctionne pas; il est recommandé de ne pas utiliser cette option.',
	'editing_server' => 'Éditer le Serveur {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'IP du Serveur (avec port) (numérique ou domaine)',
	'server_ip_with_port_help' => 'Ceci est l\'IP qui sera affichée aux joueurs.',
	'server_ip_numeric' => 'IP du Serveur (avec port) (numérique seulement)',
	'server_ip_numeric_help' => 'Ceci est l\'IP de la requête, assurez-vous qu\'elle soit <strong>numérique</strong>. Cette IP ne sera pas affichée aux Utilisateurs.',
	'show_on_play_page' => 'Montrer sur la page Jouer?',
	'pre_17' => 'Version Minecraft pré-1.7?',
	'server_name' => 'Nom du Serveur',
	'invalid_server_id' => 'ID du Serveur invalide',
	'show_players' => 'Montrer la liste des joueurs sur la page Jouer?',
	'server_edited' => 'Serveur édité avec succès',
	'server_created' => 'Serveur créé avec succès',
	'query_errors' => 'Erreur de requête',
	'query_errors_info' => 'Ces erreurs permettent de diagnostiquer les problèmes de requêtes de serveurs internes.',
	'no_query_errors' => 'Aucune erreur de requête n\'a été enregistrée',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Voir l\'erreur',
	'confirm_error_deletion' => 'Êtes-vous certain de voir supprimer cette erreur?',
	'display_server_status' => 'Montrer le statut du module de Serveur?',
	'server_name_required' => 'Vous devez insérer un nom de Serveur.',
	'server_ip_required' => 'Vous devez insérer l\'IP du Serveur.',
	'server_name_minimum' => 'Le nom du Serveur doit avoir un minimum de 2 caractères.',
	'server_ip_minimum' => 'L\'IP du Serveur doit avoir un minimum de 2 caractères.',
	'server_name_maximum' => 'Le nom du Serveur doit avoir un maximum de 20 caractères.',
	'server_ip_maximum' => 'L\'IP du Serveur doit avoir un maximum de 64 caractères.',
	'purge_errors' => 'Éliminer les erreurs',
	'confirm_purge_errors' => 'Êtes-vous certain de vouloir éliminer toutes les erreurs de requêtes?',
	'avatar_type' => 'Type d\'avatar',
	'custom_usernames' => 'Forcer les noms d\'Utilisateurs Minecraft?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Utiliser mcassoc?',
	'use_mcassoc_help' => 'mcassoc s\'assure que les Utilisateurs possèdent le compte Minecraft avec lequel ils sont enregistrés.',
	'mcassoc_key' => 'Clé de partage mcassoc',
	'invalid_mcassoc_key' => 'Clé de partage mcassoc invalide.',
	'mcassoc_instance' => 'Instance mcassoc',
	'mcassoc_instance_help' => 'Générer un code d\'instance mcassoc <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">ici</a>',
	'mcassoc_key_help' => 'Générer votre clé de partage mcassoc <a href="https://mcassoc.lukegb.com/" target="_blank">ici</a>',
	'enable_name_history' => 'Enable profile username history?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Thèmes',
	'templates' => 'Modèles',
	'installed_themes' => 'Thèmes installés',
	'installed_templates' => 'Modèles installés',
	'installed_addons' => 'Addons installés',
	'install_theme' => 'Installer le Thème',
	'install_template' => 'Installer le Modèle',
	'install_addon' => 'Installer l\'Addon',
	'install_a_theme' => 'Installer un Thème',
	'install_a_template' => 'Installer un Modèle',
	'install_an_addon' => 'Installer un Addon',
	'active' => 'Actif',
	'activate' => 'Activer',
	'deactivate' => 'Désactiver',
	'theme_install_instructions' => 'Installer le Thème dans la catégorie <strong>Thèmes</strong>. Ensuite, cliquez "Identifier".',
	'template_install_instructions' => 'Installer le Modèle dans la catégorie <strong>Modèles</strong>. Ensuite, cliquez "Identifier".',
	'addon_install_instructions' => 'Installer l\'Addon dans la catégorie <strong>Addons</strong>. Ensuite, cliquez "Identifier".',
	'addon_install_warning' => 'Les Addons sont installés à vos risques. Veuillez faire des back-ups de vos fichiers avant d\'en ajouter.',
	'scan' => 'Identifier',
	'theme_not_exist' => 'Ce Thème n\'existe pas!',
	'template_not_exist' => 'Ce Modèle n\'existe pas!',
	'addon_not_exist' => 'Ce Addon n\'existe pas!',
	'style_scan_complete' => 'Tous les nouveaux styles ont été enregistrés.',
	'addon_scan_complete' => 'Tous les nouveaux addons ont été enregistrés.',
	'theme_enabled' => 'Thème activé.',
	'template_enabled' => 'Modèle activé.',
	'addon_enabled' => 'Addon activé.',
	'theme_deleted' => 'Thème supprimé.',
	'template_deleted' => 'Modèle supprimé.',
	'addon_disabled' => 'Addon supprimé.',
	'inverse_navbar' => 'Inverser la barre de navigation',
	'confirm_theme_deletion' => 'Êtes-vous certain de vouloir supprimé le Thème <strong>{x}</strong>?<br /><br />Le Thème sera supprimé de la catégorie <strong>Thèmes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />Le Modèle sera supprimé de la catégorie <strong>Modèles</strong> directory.', // Don't replace {x}
	'unable_to_enable_addon' => 'Could not enable addon. Please ensure it is a valid NamelessMC addon.',
	
	// Admin Misc page
	'other_settings' => 'Autres paramètres',
	'enable_error_reporting' => 'Activer le rapport d\'erreurs?',
	'error_reporting_description' => 'Cette option devrait être utilité afin d\'aider le debug, il est recommendé de laisser cette option désactivée.',
	'display_page_load_time' => 'Afficher le temps de chargement de la page?',
	'page_load_time_description' => 'Cette option affiche une barre de chargement dans le bas de la page.',
	'reset_website' => 'Réinitialiser le site web.',
	'reset_website_info' => 'Cette option réinitialisera les paramètres du site web. <strong>Les Addons seront désactivés, et non supprimés, et leurs paramètres ne changeront pas.</strong> Vos serveurs Minecraft resteront intacts.',
	'confirm_reset_website' => 'Êtes-vous certain de vouloir réinitialiser les paramètres de votre site web?',
	
	// Admin Update page
	'installation_up_to_date' => 'Votre installation est à jour.',
	'update_check_error' => 'Impossible de vérifier pour une mise à jour. Veuillez essayer de nouveau.',
	'new_update_available' => 'Une nouvelle mise à jour est disponible.',
	'your_version' => 'Votre version:',
	'new_version' => 'Nouvelle version:',
	'download' => 'Télécharger',
	'update_warning' => 'Attention: Assurez-vous d\'avoir téléchargé le paquet et d\'y avoir uploadé les fichiers!'
);
/*
 *  Navbar
 */
$navbar_language = array(
	'home' => 'Accueil',
	'play' => 'Jouer',
	'forum' => 'Forum',
	'more' => 'Plus',
	'staff_apps' => 'Recrutement',
	'view_messages' => 'Voir les Messages',
	'view_alerts' => 'Voir les Alertes',
	
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
	'create_an_account' => 'Créer un compte',
	'authme_password' => 'Mot de passe AuthMe',
	'username' => 'Pseudonyme',
	'minecraft_username' => 'Pseudonyme Minecraft',
	'email' => 'Email',
	'user_title' => 'Titre',
	'email_address' => 'Adresse Email',
	'date_of_birth' => 'Date de naissance',
	'location' => 'Localisation',
	'password' => 'Mot de Passe',
	'confirm_password' => 'Confirmer votre Mot de Passe',
	'i_agree' => 'J\'accepte',
	'agree_t_and_c' => 'En cliquant <strong class="label label-primary">S\'enregistrer</strong>, vous acceptez nos <a href="#" data-toggle="modal" data-target="#t_and_c_m">Conditions Générales</a>.',
	'register' => 'S\'enregistrer',
	'sign_in' => 'Se connecter',
	'sign_out' => 'Se déconnecter',
	'terms_and_conditions' => 'Conditions Générales',
	'successful_signin' => 'Connexion réussie',
	'incorrect_details' => 'Identifiants de connexion incorrects',
	'remember_me' => 'Se souvenir de moi',
	'forgot_password' => 'Mot de Passe oublié',
	'must_input_username' => 'Vous devez insérer un Pseudonyme.',
	'must_input_password' => 'Vous devez insérer un Mot de Passe.',
	'inactive_account' => 'Votre compte est inactif. Souhaitez-vous réinitialiser votre mot de passe?',
	'account_banned' => 'Votre compte a été banni.',
	'successfully_logged_out' => 'Déconnexion réussie.',
	'signature' => 'Signature',
	'registration_check_email' => 'Veuillez regarder votre boîte de courriels pour le mail d\'activation. Vous ne pourrez pas vous connecter avant que votre compte soit activé.',
	'unknown_login_error' => 'Désolé, une erreur est survenue lors de votre connexion. Veuillez essayer de nouveau.',
	'validation_complete' => 'Merci d\'avoir validé votre compte! Vous pouvez désormais vous connecter.',
	'validation_error' => 'Erreur lors du traitement de la requête. Veuillez réessayer de cliquer sur le lien.',
	'registration_error' => 'Assurez-vous que tous les champs soient complets, et que votre Pseudonyme ait entre 3 et 20 caractères que votre Mot de Passe ait 6 à 30 caractères.',
	'username_required' => 'Veuillez entrer un Pseudonyme.',
	'password_required' => 'Veuillez entrer un Mot de Passe.',
	'email_required' => 'Veuillez entrer un Adresse email.',
	'mcname_required' => 'Veuillez entrer votre pseudonyme Minecraft.',
	'accept_terms' => 'Vous devez accepter les Conditions générales pour vous enregistrer.',
	'invalid_recaptcha' => 'Réponse reCAPTCHA invalide.',
	'username_minimum_3' => 'Votre Pseudonyme doit avoir un minimum de 3 caractères.',
	'username_maximum_20' => 'Votre Pseudonyme doit avoir un maximum de 20 caractères.',
	'mcname_minimum_3' => 'Votre pseudonyme Minecraft doit avoir un minimum de 3 caractères.',
	'mcname_maximum_20' => 'Votre pseudonyme Minecraft doit avoir un maximum de 20 caractères.',
	'password_minimum_6' => 'Votre Mot de Passe doit avoir un minimum de 6 caractères.',
	'password_maximum_30' => 'Votre Mot de Passe doit avoir un maximum de 30 caractères.',
	'passwords_dont_match' => 'Vos Mots de Passe ne correspondent pas.',
	'username_mcname_email_exists' => 'Votre Pseudonyme, votre pseudonyme Minecraft ou adresse email existe déjà. Avez-vous déjà créé un compte?',
	'invalid_mcname' => 'Votre pseudonyme Minecraft n\'est pas un compte valide.',
	'mcname_lookup_error' => 'Une erreur est survenue lors du contacte avec les serveurs de Mojang. Veuillez essayer de nouveau.',
	'signature_maximum_900' => 'Votre signature doit avoir un maximum de 900 caractères.',
	'invalid_date_of_birth' => 'Date de naissance invalide.',
	'location_required' => 'Veuillez indiquer votre location.',
	'location_minimum_2' => 'Votre location doit avoir un minimum de 2 caractères.',
	'location_maximum_128' => 'Votre location doit avoir un maximum de 128 caractères.',
	'verify_account' => 'Vérification du compte',
	'verify_account_help' => 'Veuillez suivre les instructions ci-dessous pour que l\'on passe à la vérification de votre compte Minecraft.',
	'verification_failed' => 'Vérification impossible, veuillez essayer de nouveau.',
	'verification_success' => 'Compte validé! Vous pouvez vous connecter.',
	'complete_signup' => 'Compléter l\'inscription.',
	'registration_disabled' => 'L\'inscription sur le site est actuellement désactivée.',
	
	// UserCP
	'user_cp' => 'Panneau d\'utilisateur',
	'no_file_chosen' => 'Pas de fichier choisi',
	'private_messages' => 'Messages Privés',
	'profile_settings' => 'Réglages de Profil',
	'your_profile' => 'Votre Profil',
	'topics' => 'Sujets',
	'posts' => 'Messages',
	'reputation' => 'Réputation',
	'friends' => 'Amis',
	'alerts' => 'Alertes',
	
	// Messaging
	'new_message' => 'Nouveau Message',
	'no_messages' => 'Pas de Message',
	'and_x_more' => 'et {x} de plus', // Don't replace "{x}"
	'system' => 'Système',
	'message_title' => 'Titre du message',
	'message' => 'Message',
	'to' => 'à:',
	'separate_users_with_comma' => 'Séparer les utilisateurs avec une virgule (",")',
	'viewing_message' => 'Voir le message',
	'delete_message' => 'Effacer le message',
	'confirm_message_deletion' => 'Désirez-vous réellement effacer ce message?',
	
	// Profile settings
	'display_name' => 'Pseudonyme',
	'upload_an_avatar' => 'Télécharger un avatar (.jpg, .png ou .gif):',
	'use_gravatar' => 'Utiliser Gravatar?',
	'change_password' => 'Modifier votre Mot de Passe',
	'current_password' => 'Mot de Passe actuel',
	'new_password' => 'Nouveau Mot de Passe',
	'repeat_new_password' => 'Écrire votre Mot de Passe de nouveau',
	'password_changed_successfully' => 'Mot de Passe changé avec succès',
	'incorrect_password' => 'Votre Mot de Passe actuel est incorrect.',
	'update_minecraft_name_help' => 'Cette option modifie votre Pseudonyme en votre pseudonyme Minecraft. Cette action peut seulement être faite une fois chaque 30 jours.',
	'unable_to_update_mcname' => 'Impossible de mettre à jour votre pseudonyme Minecraft.',
	'display_age_on_profile' => 'Montrer votre âge sur votre profil?',
	'two_factor_authentication' => 'Authentificateur Double',
	'enable_tfa' => 'Activer l\'Authentificateur Double',
	'tfa_type' => 'Type d\'Authentificateur Double:',
	'authenticator_app' => 'Application d\'Authentificateur',
	'tfa_scan_code' => 'Veuillez identifier ce code dans votre application d\'Authentificateur:',
	'tfa_code' => 'Si vous n\'avez pas de caméra sur votre Authentificateur ou qu\'il est impossible pour vous d\'identifier le code QR, veuillez inscrire le code ci-dessous:',
	'tfa_enter_code' => 'Veuillez inscrire le code qui apparait sur votre Authentificateur:',
	'invalid_tfa' => 'Code invalide, veuillez essayer de nouveau.',
	'tfa_successful' => 'Authentificateur Double installé avec succès. Vous devrez vous identifier à l\'aide de votre Authentificateur lors que chaque connexion.',
	'confirm_tfa_disable' => 'Désirez-vous réellement désactiver l\'Authentificateur Double?',
	'tfa_disabled' => 'Authentificateur Double désactivé.',
	'tfa_enter_email_code' => 'Vous avez reçu un courriel de vérification. Veuillez entrer le code ici:',
	'tfa_email_contents' => 'Une tentative de connexion a eu lieu sur votre compte. S\'il s\'agissait de vous, veuillez saisir le code de votre Authenticateur Double. S\'il ne s\'agissait pas de vous, ignore ce courriel et une réinitialisation de votre mot de passe est conseillée. Le code n\'est valide que pour les 10 prochaines minutes.',
	
	// Alerts
	'viewing_unread_alerts' => 'Voir les alertes non-lues. Changer à <a href="/user/alerts/?view=read"><span class="label label-success">lues</span></a>.',
	'viewing_read_alerts' => 'Voir les alertes lues. Change to <a href="/user/alerts/"><span class="label label-warning">non-lues</span></a>.',
	'no_unread_alerts' => 'Vous n\'avez aucune nouvelle alerte.',
	'no_alerts' => 'Aucune alerte.',
	'no_read_alerts' => 'Vous avez des alertes non-lues.',
	'view' => 'Voir',
	'alert' => 'Alerte',
	'when' => 'Quand',
	'delete' => 'Supprimer',
	'tag' => 'Identifier un Utilisateur',
	'tagged_in_post' => 'Vous avez été identifié dans un post!',
	'report' => 'Émettre un rapport',
	'deleted_alert' => 'Alerte effacée',
	
	// Warnings
	'you_have_received_a_warning' => 'Vous avez reçu un avertissement de {x}, daté du {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Soumettre son accord.',
	
	// Forgot password
	'password_reset' => 'Mot de Passe oublié',
	'email_body' => 'Vous recevez ce email puisque vous avez demandé une réinitialisation de mot de passe. Pour réinitialiser votre mot de passe, veuillez utliser ce lien:', // Body for the password reset email
	'email_body_2' => 'Si vous n\'avez pas demander une réinitialisation de mot de passe, vous pouvez ignorer ce message.',
	'password_email_set' => 'Succès! Veuillez vérifier vos emails pour plus d\'instructions.',
	'username_not_found' => 'Ce Pseudonyme n\'existe pas.',
	'change_password' => 'Changer de Mot de Passe',
	'your_password_has_been_changed' => 'Votre Mot de Passe a été changé.',
	
	// Profile page
	'profile' => 'Profile',
	'player' => 'Joueur',
	'offline' => 'Hors-Ligne',
	'online' => 'En Ligne',
	'pf_registered' => 'Enregistré:',
	'pf_posts' => 'Posts:',
	'pf_reputation' => 'Réputation:',
	'user_hasnt_registered' => 'Cet Utilisateur n\'est pas enregistré.',
	'user_no_friends' => 'Cet Utilisateur n\'a pas d\'amis',
	'send_message' => 'Envoyer un message',
	'remove_friend' => 'Retirer un ami',
	'add_friend' => 'Ajouter un ami',
	'last_online' => 'Dernière connexion:',
	'find_a_user' => 'Trouver un Utilisateur',
	'user_not_following' => 'Cet Utilisateur n\'est fan de personne.',
	'user_no_followers' => 'Cet Utilisateur n\'a pas de fan.',
	'following' => 'Personnes qu\'il suit',
	'followers' => 'Personnes qui le suivent',
	'display_location' => 'De {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, de {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Écrire quelque chose sur le profil de {x}', // Don't replace {x}
	'write_on_own_profile' => 'Écrire quelque chose sur votre profil',
	'profile_posts' => 'Posts sur le profile',
	'no_profile_posts' => 'Aucun posts sur le profil',
	'invalid_wall_post' => 'Post invalide. Veuillez vérifier que le post soit 2 à 2048 caractères.',
	'about' => 'À propos',
	'reply' => 'Répondre',
	'x_likes' => '{x} J\'aime', // Don't replace {x}
	'likes' => 'J\'aime',
	'no_likes' => 'Aucun J\'aime.',
	'post_liked' => 'Post aimé.',
	'post_unliked' => 'Post non-aimé.',
	'no_posts' => 'Aucun post.',
	'last_5_posts' => '5 derniers posts',
	'follow' => 'Je suis un Fan',
	'unfollow' => 'Je ne suis plus un Fan',
	'name_history' => 'Historique de noms',
	'changed_name_to' => 'Nom changé de: {x} à {y}', // Don't replace {x} or {y}
	'original_name' => 'Nom original:',
	'name_history_error' => 'Impossible de retrouver l\'historique des noms.',
	
	// Staff applications
	'staff_application' => 'Application de Staff',
	'application_submitted' => 'Application soumise avec succès.',
	'application_already_submitted' => 'Vous avez déjà soumis une application. Veuillez attendre qu\'elle soit complétée avant d\'appliquer de nouveau.',
	'not_logged_in' => 'Veuillez vous connecter pour voir cette page.',
	'application_accepted' => 'Votre application de Staff a été approuvée.',
	'application_rejected' => 'Votre application de Staff a été rejetée.'
);
/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Vue d\'ensemble',
	'reports' => 'Rapports',
	'punishments' => 'Punitions',
	'staff_applications' => 'Applications de Staff',
	
	// Punishments
	'ban' => 'Bannir',
	'unban' => 'Débannir',
	'warn' => 'Avertir',
	'search_for_a_user' => 'Chercher un Utilisateur',
	'user' => 'Utilisateur:',
	'ip_lookup' => 'IP de l\'Utilisateur:',
	'registered' => 'Enregistré',
	'reason' => 'Raison:',
	'cant_ban_root_user' => 'Impossible de punir un Fondateur!',
	'invalid_reason' => 'Veuillez entrer une raison valide entre 2 et 256 caractères.',
	'punished_successfully' => 'Punition ajoutée avec succès.',
	
	// Reports
	'report_closed' => 'Rapport fermé',
	'new_comment' => 'Nouveau commentaire',
	'comments' => 'Commentaires',
	'only_viewed_by_staff' => 'Peut seulement être vu par le Staff',
	'reported_by' => 'Rapport fait par',
	'close_issue' => 'Problème réglé',
	'report' => 'Rapport:',
	'view_reported_content' => 'Voir les informations du rapport',
	'no_open_reports' => 'Aucun rapport ouvert',
	'user_reported' => 'Utilisateur signalé.',
	'type' => 'Type',
	'updated_by' => 'Mis à jour par',
	'forum_post' => 'Post du Forum',
	'user_profile' => 'Profil de l\'Utilisateur',
	'comment_added' => 'Commentaire ajouté.',
	'new_report_submitted_alert' => 'Nouveau rapport soumis par {x} sur l\'Utilisateur {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Rapport In-Game',
	
	// Staff applications
	'comment_error' => 'Veuillez vous assurer que votre commentaire soit entre 2 et 2048 caractères.',
	'viewing_open_applications' => 'Voir les applications <span class="label label-info">ouvertes</span>. Changer pour <a href="/mod/applications/?view=accepted"><span class="label label-success">acceptée</span></a> ou <a href="/mod/applications/?view=declined"><span class="label label-danger">refusée</span></a>.',
	'viewing_accepted_applications' => 'Voir les applications <span class="label label-success">acceptées</span> applications. Changer pour <a href="/mod/applications/"><span class="label label-info">ouverte</span></a> ou <a href="/mod/applications/?view=declined"><span class="label label-danger">refusée</span></a>.',
	'viewing_declined_applications' => 'Voir les applications <span class="label label-danger">refusées</span> applications. Changer pour <a href="/mod/applications/"><span class="label label-info">ouverte</span></a> ou <a href="/mod/applications/?view=accepted"><span class="label label-success">acceptée</span></a>.',
	'time_applied' => 'Temps depuis l\'application',
	'no_applications' => 'Aucune applications dans cette catégorie',
	'viewing_app_from' => 'Voir les applications de {x}', // Don't replace "{x}"
	'open' => 'Ouvert',
	'accepted' => 'Accepté',
	'declined' => 'Refusé',
	'accept' => 'Accepter',
	'decline' => 'Refuser',
	'new_app_submitted_alert' => 'Nouvelle application soumise par {x}' // Don't replace "{x}"
);
/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Nouvelles',
	'social' => 'Social',
	'join' => 'Rejoindre',
	
	// General terms
	'submit' => 'Soumettre',
	'close' => 'Fermer',
	'cookie_message' => '<strong>Ce site web utilise des cookies pour améliorer votre expérience.</strong><p>En continuant de naviguer, vous acceptez ces cookies.</p>',
	'theme_not_exist' => 'Le Thème sélectionné n\'existe pas.',
	'confirm' => 'Confirmer',
	'cancel' => 'Annuler',
	'guest' => 'Invité',
	'guests' => 'Invités',
	'back' => 'Retour',
	'search' => 'Recherche',
	'help' => 'Help',
	'success' => 'Succès',
	'error' => 'Erreur',
	'view' => 'Voir',
	'info' => 'Infos',
	'next' => 'Prochain',
	
	// Play page
	'connect_with' => 'Se connecter au serveur avec l\'IP {x}', // Don't replace {x}
	'online' => 'En Ligne',
	'offline' => 'Hors-Ligne',
	'status' => 'Statut:',
	'players_online' => 'Joueurs en ligne:',
	'queried_in' => 'Requête:',
	'server_status' => 'Statut du Serveur',
	'no_players_online' => 'Il n\'y a pas de joueurs en ligne!',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'Il y a {x} joueurs en ligne .', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Page chargée dans {x} secondes', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Aucun',
	'404' => 'Désolé, nous ne pouvons trouver cette page. (404)'
);
/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forums',
	'discussion' => 'Discussion',
	'stats' => 'Statistiques',
	'last_reply' => 'Dernière réponse',
	'ago' => '.',
	'by' => 'par',
	'in' => 'dans',
	'views' => 'vues',
	'posts' => 'posts',
	'topics' => 'sujets',
	'topic' => 'Sujet',
	'statistics' => 'Statistiques',
	'overview' => 'Vue d\'ensemble',
	'latest_discussions' => 'Dernières discussions',
	'latest_posts' => 'Derniers posts',
	'users_registered' => 'Utilisateurs enregistrés:',
	'latest_member' => 'Dernier membre:',
	'forum' => 'Forum',
	'last_post' => 'Dernier post',
	'no_topics' => 'Aucun sujet ici',
	'new_topic' => 'Nouveau sujet',
	'subforums' => 'Sous-forums:',
	
	// View topic view
	'home' => 'Accueil',
	'topic_locked' => 'Sujet vérouillé',
	'new_reply' => 'Nouvelle réponse',
	'mod_actions' => 'Actions du Modérateur',
	'lock_thread' => 'Sujet vérouillé',
	'unlock_thread' => 'Sujet dévérouillé',
	'merge_thread' => 'Fusionner les sujets',
	'delete_thread' => 'Sujet supprimé',
	'confirm_thread_deletion' => 'Désirez-vous réellement supprimer ce sujet?',
	'move_thread' => 'Déplacer le sujet',
	'sticky_thread' => 'Sujet accroché',
	'report_post' => 'Signaler le post',
	'quote_post' => 'Citer le post',
	'delete_post' => 'Supprimer le post',
	'edit_post' => 'Éditer le post',
	'reputation' => 'réputation',
	'confirm_post_deletion' => 'Désirez-vous réellement supprimer votre post?',
	'give_reputation' => 'Donner de la réputation',
	'remove_reputation' => 'Retirer la réputation',
	'post_reputation' => 'Réputation du post',
	'no_reputation' => 'Aucune réputation pour ce post',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Créer un post',
	'post_submitted' => 'Post soumis',
	'creating_post_in' => 'Créer un post dans: ',
	'topic_locked_permission_post' => 'Ce sujet est vérouillé, cependant vos permissions vous permettent de poster',
	
	// Edit post view
	'editing_post' => 'Éditer le post',
	
	// Sticky threads
	'thread_is_' => 'Le sujet est ',
	'now_sticky' => 'maintenant accroché au Forum.',
	'no_longer_sticky' => 'n\'est plus accroché au Forum.',
	
	// Create topic
	'topic_created' => 'Sujet créé.',
	'creating_topic_in_' => 'Création du sujet dans le forum ',
	'thread_title' => 'Titre du sujet',
	'confirm_cancellation' => 'Êtes-vous certain?',
	'label' => 'Étiquette',
	
	// Reports
	'report_submitted' => 'Rapport envoyé.',
	'view_post_content' => 'Voir le contenu du message',
	'report_reason' => 'Raison du rapport',
	
	// Move thread
	'move_to' => 'Déplacer vers:',
	
	// Merge threads
	'merge_instructions' => 'Les sujets dont vous désirer fusionner <strong>doivent</strong> faire parti du même forum. Déplacer un sujet si nécessaire.',
	'merge_with' => 'Fusionné avec:',
	
	// Other
	'forum_error' => 'Désolé, impossible de trouver ce forum ou ce sujet.',
	'are_you_logged_in' => 'Êtes-vous connecté?',
	'online_users' => 'Utilisateurs connectés',
	'no_users_online' => 'Il n\'y a pas d\'utilisateurs connectés.',
	
	// Search
	'search_error' => 'Merci d\'entrer une recherche entre 1 et 32 caractères de longueur.',
	'no_search_results' => 'Aucun résultat trouvé lors de la recherche.',
	
	//Share on a social-media.
	'sm-share' => 'Partager',
	'sm-share-facebook' => 'Partager sur Facebook',
	'sm-share-twitter' => 'Partager sur Twitter',
);
/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Bonjour',
	'message' => 'Merci de vous être enregistré sur notre site web! Tout d\'abord, merci de cliquer sur le lien suivant pour valider votre compte:',
	'thanks' => 'Merci,'
);
/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'il y a moins d\'une minute',
	'1_minute' => 'il y a une minute',
	'_minutes' => 'il y a {x} minutes',
	'about_1_hour' => 'il y a environ 1 heure',
	'_hours' => 'il y a {x} heures',
	'1_day' => 'il y a 1 jour',
	'_days' => 'il y a {x} jours',
	'about_1_month' => 'il y a environ 1 mois',
	'_months' => 'il y a {x} mois',
	'about_1_year' => 'il y a environ 1 an',
	'over_x_years' => 'il y a plus de {x} ans'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Affichage de _MENU_ enregistrements par page', // Don't replace "_MENU_"
	'nothing_found' => 'Aucun résultat trouvé',
	'page_x_of_y' => 'Affichage de la page _PAGE_ sur _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Pas d\'enregistrement disponible',
	'filtered' => '(filtré _MAX_ d\'enregistrements totaux)' // Don't replace "_MAX_"
);
/*
 *  API language
 */
$api_language = array(
	'register' => 'Enregistrement complèté.'
);
 
?>
