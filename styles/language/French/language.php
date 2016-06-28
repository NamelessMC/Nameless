<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
/*
 *  French Language
 *  Translation made by CreaModZ (creamodz.fr)
 *  https://www.spigotmc.org/members/creamodz.6937/
 *
 *  Last edition: 10/03/2016 1:30PM by CreaModZ
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP', 
	'invalid_token' => 'Token invalide, merci de rééssayer !',
	'invalid_action' => 'Action invalide',
	'successfully_updated' => 'Mis à jour avec succès !',
	'settings' => 'Réglages',
	'confirm_action' => 'Confirmer l\'action',
	'edit' => 'Editer',
	'actions' => 'Actions',
	'task_successful' => 'Tâche effectuée avec succès !',
	
	// Admin login
	're-authenticate' => 'Ré-identifiez-vous, merci !',
	
	// Admin sidebar
	'index' => 'Principal',
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
	
	// Admin index page
	'statistics' => 'Statistiques',
	'registrations_per_day' => 'Enregistrements par jour (7 derniers jours)',
	
	// Admin core page
	'general_settings' => 'Réglages généraux',
	'modules' => 'Modules',
	'module_not_exist' => 'Ce module n\'existe pas !',
	'module_enabled' => 'Module activé.',
	'module_disabled' => 'Module désactivé.',
	'site_name' => 'Nom du site',
	'language' => 'Langage',
	'voice_server_not_writable' => 'core/voice_server.php n\'est pas accessible en écriture. Merci de vérifier vos permissions de fichier.',
	'email' => 'Email',
	'incoming_email' => 'Adresse Email entrante',
	'outgoing_email' => 'Adresse Email sortante',
	'outgoing_email_help' => 'Uniquement demandé si la fonction PHP mail est activée',
	'use_php_mail' => 'Utiliser la fonction PHP mail() ?',
	'use_php_mail_help' => 'Recommendé: activé. Si votre site web n\'envoi pas de mails, merci de désactiver cela et d\'éditer core/email.php avec vos réglages emails.',
	'use_gmail' => 'Utiliser GMail pour l\'envoi de mail ?',
	'use_gmail_help' => 'Uniquement disponible si la fonction PHP mail est désactivée. If you choose not to use Gmail, SMTP will be used. Either way, this will need configuring in core/email.php.',
	'enable_mail_verification' => 'Activer la vérification des comptes par adresse mail ?',
	'enable_email_verification_help' => 'Activer cela demandera aux nouveaux utilisateurs de vérifier leur compte par mail avant de compléter leur enregistrement.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Pages',
	'enable_or_disable_pages' => 'Activer ou désactiver des pages ici.',
	'enable' => 'Activer',
	'disable' => 'Désactiver',
	'maintenance_mode' => 'Mode de Maintenance du Forum',
	'forum_in_maintenance' => 'Le Forum est actuellement en Mode de Maintenance.',
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
	'click_on_page_to_edit' => 'Cliquez sur une page pour l\'éditer.',
	'page' => 'Page:',
	'url' => 'URL:',
	'page_url' => 'URL de la Page',
	'page_url_example' => '(Avec le "/" précédant, par exemple /boutique/)',
	'page_title' => 'Titre de la Page',
	'page_content' => 'Contenu de la Page',
	'new_page' => 'Nouvelle Page',
	'page_successfully_created' => 'Page créée avec succès !',
	'page_successfully_edited' => 'Page éditée avec succès !',
	'unable_to_create_page' => 'Impossible de créer une page.',
	'unable_to_edit_page' => 'Impossible d\'éditer cette page.',
	'create_page_error' => 'Assurez-vous d\'avoir entré une URL entre 1 et 20 caractères de longueur, un titre de page entre 1 et 30 caractères de longueur, et un contenu de page de 5 à 20480 caractères maximum.',
	'delete_page' => 'Supprimer cette Page',
	'confirm_delete_page' => 'Êtes-vous sûr de vouloir supprimer cette Page ?',
	'page_deleted_successfully' => 'Page supprimée avec succès',
	'page_link_location' => 'Afficher lien de Page dans:',
	'page_link_navbar' => 'Barre de Navigation',
	'page_link_more' => 'Menu déroulant "Plus"',
	'page_link_footer' => 'Pied de Page',
	'page_link_none' => 'Aucun lien de Page',
	'page_permissions' => 'Page Permissions',
	'can_view_page' => 'Can view page:',
	'redirect_page' => 'Redirect page?',
	'redirect_link' => 'Redirect link',
	
	// Admin forum page
	'labels' => 'Labels de sujet',
	'new_label' => 'Nouveau label',
	'no_labels_defined' => 'Pas de label défini',
	'label_name' => 'Label',
	'label_type' => 'Type de label',
	'label_forums' => 'Label de forum',
	'label_creation_error' => 'Erreur de la création d\'un label. Assurez-vous que le nom ne dépasse pas 32 caractères et que vous avez spécifié un type.',
	'confirm_label_deletion' => 'Êtes-vous sûr de vouloir supprimer ce label ?',
	'editing_label' => 'Edition d\'un label',
	'label_creation_success' => 'Label créé avec succès',
	'label_edit_success' => 'Label édité avec succès',
	'label_default' => 'Défaut',
	'label_primary' => 'Primaire',
	'label_success' => 'Succès',
	'label_info' => 'Info',
	'label_warning' => 'Avertissement',
	'label_danger' => 'Danger',
	'new_forum' => 'Nouveau Forum',
	'forum_layout' => 'Forme du Forum',
	'table_view' => 'Vue de la Table',
	'latest_discussions_view' => 'Vue des Dernières Discussions',
	'create_forum' => 'Créer un forum',
	'forum_name' => 'Nom du Forum',
	'forum_description' => 'Description du Forum',
	'delete_forum' => 'Supprimer ce Forum',
	'move_topics_and_posts_to' => 'Move topics and posts to',
	'delete_topics_and_posts' => 'Delete topics and posts',
	'parent_forum' => 'Parent Forum',
	'has_no_parent' => 'Has no parent',
	'forum_permissions' => 'Forum Permissions',
	'can_view_forum' => 'Can view forum:',
	'can_create_topic' => 'Can create topic:',
	'can_post_reply' => 'Can post reply:',
	'display_threads_as_news' => 'Display threads as news on front page?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description.',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => 'Category',
	
	// Admin Users and Groups page
	'users' => 'Utilisateurs',
	'new_user' => 'Nouvel Utilisateur',
	'created' => 'Créé',
	'user_deleted' => 'Utilisateur supprimé',
	'validate_user' => 'Valider un utilisateur',
	'update_uuid' => 'Mettre à jour l\'UUID',
	'unable_to_update_uuid' => 'Impossible de mettre à jour un UUID.',
	'update_mc_name' => 'Mettre à jour le pseudo Minecraft',
	'reset_password' => 'Réinitialiser le Mot de Passe',
	'punish_user' => 'Punir l\'utilisateur',
	'delete_user' => 'Supprimer l\'utilisateur',
	'minecraft_uuid' => 'UUID Minecraft',
	'ip_address' => 'Adresse IP',
	'ip' => 'IP:',
	'other_actions' => 'Autres actions:',
	'disable_avatar' => 'Désactiver l\'avatar',
	'confirm_user_deletion' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur: {x} ?', // Don't replace "{x}"
	'groups' => 'Groupes',
	'group' => 'Groupe',
	'new_group' => 'Nouveau Groupe',
	'id' => 'ID',
	'name' => 'Nom',
	'create_group' => 'Créer un Groupe',
	'group_name' => 'Nom du Groupe',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>This is the ID of the group\'s package from Buycraft, MinecraftMarket or MCStock.</p><p>This can be left empty.</p>',
	'donor_group_instructions' => 	'<p>Donor groups must be created in the order of <strong>lowest value to highest value</strong>.</p>
									<p>For example, a £10 package will be created before a £20 package.</p>',
	'delete_group' => 'Supprimer ce groupe',
	'confirm_group_deletion' => 'Êtes-vous sûr de vouloir supprimer ce groupe: {x} ?', // Don't replace "{x}"
	'group_staff' => 'Is the group a staff group?',
	'group_modcp' => 'Can the group view the ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'The group name must be a minimum of 2 characters.',
	'group_name_maximum' => 'The group name must be a maximum of 20 characters.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'The user must be in a group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Réglages Minecraft',
	'use_plugin' => 'Use Nameless Minecraft plugin?',
	'force_avatars' => 'Force Minecraft avatars?',
	'uuid_linking' => 'Enable UUID linking?',
	'use_plugin_help' => 'Using the plugin allows for rank synchronisation and also ingame registration and ticket submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Plugin Settings',
	'confirm_api_regen' => 'Are you sure you want to generate a new API key?',
	'servers' => 'Servers',
	'new_server' => 'New Server',
	'confirm_server_deletion' => 'Are you sure you want to delete this server?',
	'main_server' => 'Main Server',
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
	'server_edited' => 'Server edited successfully',
	'server_created' => 'Server created successfully',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'No query errors logged',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => 'Are you sure you want to delete this error?',
	'display_server_status' => 'Display server status module?',
	'server_name_required' => 'You must insert a server name.',
	'server_ip_required' => 'You must insert the server\'s IP.',
	'server_name_minimum' => 'The server name must be a minimum of 2 characters.',
	'server_ip_minimum' => 'The server IP must be a minimum of 2 characters.',
	'server_name_maximum' => 'The server name must be a maximum of 20 characters.',
	'server_ip_maximum' => 'The server IP must be a maximum of 64 characters.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Themes',
	'templates' => 'Templates',
	'installed_themes' => 'Installed themes',
	'installed_templates' => 'Installed templates',
	'installed_addons' => 'Installed addons',
	'install_theme' => 'Install Theme',
	'install_template' => 'Install Template',
	'install_addon' => 'Install Addon',
	'install_a_theme' => 'Install a theme',
	'install_a_template' => 'Install a template',
	'install_an_addon' => 'Install an addon',
	'active' => 'Active',
	'activate' => 'Activate',
	'deactivate' => 'Deactivate',
	'theme_install_instructions' => 'Please upload themes to the <strong>styles/themes</strong> directory. Then, click the "scan" button below.',
	'template_install_instructions' => 'Please upload templates to the <strong>styles/templates</strong> directory. Then, click the "scan" button below.',
	'addon_install_instructions' => 'Please upload addons to the <strong>addons</strong> directory. Then, click the "scan" button below.',
	'addon_install_warning' => 'Addons are installed at your own risk. Please back up your files and the database before proceeding',
	'scan' => 'Scan',
	'theme_not_exist' => 'That theme doesn\'t exist!',
	'template_not_exist' => 'That template doesn\'t exist!',
	'addon_not_exist' => 'That addon doesn\'t exist!',
	'style_scan_complete' => 'Completed, any new styles have been installed.',
	'addon_scan_complete' => 'Completed, any new addons have been installed.',
	'theme_enabled' => 'Theme enabled.',
	'template_enabled' => 'Template enabled.',
	'addon_enabled' => 'Addon enabled.',
	'theme_deleted' => 'Theme deleted.',
	'template_deleted' => 'Template deleted.',
	'addon_disabled' => 'Addon disabled.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Are you sure you wish to delete the theme <strong>{x}</strong>?<br /><br />The theme will be deleted from your <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />The template will be deleted from your <strong>styles/templates</strong> directory.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Other Settings',
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
	'your_version' => 'Your version:',
	'new_version' => 'New version:',
	'download' => 'Download',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
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
	'email_address' => 'Adresse Email',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Mot de Passe',
	'confirm_password' => 'Confirmer Mot de Passe',
	'i_agree' => 'J\'accepte',
	'agree_t_and_c' => 'By clicking <strong class="label label-primary">Register</strong>, you agree to our <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a>.',
	'register' => 'S\'enregistrer',
	'sign_in' => 'Se connecter',
	'sign_out' => 'Se déconnecter',
	'terms_and_conditions' => 'Termes et Conditions',
	'successful_signin' => 'Vous vous êtes connecté avec succès',
	'incorrect_details' => 'Identifiants de connexion incorrects',
	'remember_me' => 'Se souvenir',
	'forgot_password' => 'Mot de Passe oublié',
	'must_input_username' => 'You must insert a username.',
	'must_input_password' => 'You must insert a password.',
	'inactive_account' => 'Your account is currently inactive. Did you request a password reset?',
	'account_banned' => 'Your account has been banned.',
	'successfully_logged_out' => 'You have been successfully logged out.',
	'signature' => 'Signature',
	'registration_check_email' => 'Please check your emails for a validation link. You won\'t be able to log in until this is clicked.',
	'unknown_login_error' => 'Sorry, there was an unknown error whilst logging you in. Please try again later.',
	'validation_complete' => 'Thanks for registering! You can now log in.',
	'validation_error' => 'Error processing your request. Please try clicking the link again.',
	'registration_error' => 'Please ensure you have filled out all fields, and that your username is between 3 and 20 characters long and your password is between 6 and 30 characters long.',
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
	
	// UserCP
	'user_cp' => 'Panel',
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
	'message_title' => 'Message Title',
	'message' => 'Message',
	'to' => 'à:',
	'separate_users_with_comma' => 'Separate users with a comma (",")',
	'viewing_message' => 'Viewing Message',
	'delete_message' => 'Delete Message',
	'confirm_message_deletion' => 'Are you sure you want to delete this message?',
	
	// Profile settings
	'display_name' => 'Display name',
	'upload_an_avatar' => 'Upload an avatar (.jpg, .png or .gif only):',
	'use_gravatar' => 'Use Gravatar?',
	'change_password' => 'Change password',
	'current_password' => 'Current password',
	'new_password' => 'New password',
	'repeat_new_password' => 'Repeat new password',
	'password_changed_successfully' => 'Password changed successfully',
	'incorrect_password' => 'Your current password is incorrect',
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
	'viewing_unread_alerts' => 'Viewing unread alerts. Change to <a href="/user/alerts/?view=read"><span class="label label-success">read</span></a>.',
	'viewing_read_alerts' => 'Viewing read alerts. Change to <a href="/user/alerts/"><span class="label label-warning">unread</span></a>.',
	'no_unread_alerts' => 'You have no unread alerts.',
	'no_alerts' => 'No alerts',
	'no_read_alerts' => 'You have no read alerts.',
	'view' => 'View',
	'alert' => 'Alert',
	'when' => 'When',
	'delete' => 'Delete',
	'tag' => 'User Tag',
	'tagged_in_post' => 'You have been tagged in a post',
	'report' => 'Report',
	'deleted_alert' => 'Alert successfully deleted',
	
	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Password Reset',
	'email_body' => 'You are receiving this email because you requested a password reset. In order to reset your password, please use the following link:', // Body for the password reset email
	'email_body_2' => 'If you did not request the password reset, you can ignore this email.',
	'password_email_set' => 'Success. Please check your emails for further instructions.',
	'username_not_found' => 'That username does not exist.',
	'change_password' => 'Change Password',
	'your_password_has_been_changed' => 'Your password has been changed.',
	
	// Profile page
	'profile' => 'Profile',
	'player' => 'Player',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registered:',
	'pf_posts' => 'Posts:',
	'pf_reputation' => 'Reputation:',
	'user_hasnt_registered' => 'This user hasn\'t registered on our website yet',
	'user_no_friends' => 'This user has not added any friends',
	'send_message' => 'Send Message',
	'remove_friend' => 'Remove Friend',
	'add_friend' => 'Add Friend',
	'last_online' => 'Last Online:',
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
	'mod_cp' => 'ModCP',
	'overview' => 'Overview',
	'reports' => 'Reports',
	'punishments' => 'Punishments',
	'staff_applications' => 'Staff Applications',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warn',
	'search_for_a_user' => 'Search for a user',
	'user' => 'User:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registered',
	'reason' => 'Reason:',
	
	// Reports
	'report_closed' => 'Report closed.',
	'new_comment' => 'New comment',
	'comments' => 'Comments',
	'only_viewed_by_staff' => 'Can only be viewed by staff',
	'reported_by' => 'Reported by',
	'close_issue' => 'Close issue',
	'report' => 'Report:',
	'view_reported_content' => 'View reported content',
	'no_open_reports' => 'No open reports',
	'user_reported' => 'User Reported',
	'type' => 'Type',
	'updated_by' => 'Updated By',
	'forum_post' => 'Forum Post',
	'user_profile' => 'User Profile',
	'comment_added' => 'Comment added.',
	'new_report_submitted_alert' => 'New report submitted by {x} regarding user {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Please ensure your comment is between 2 and 2048 characters long.',
	'viewing_open_applications' => 'Viewing <span class="label label-info">open</span> applications. Change to <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_accepted_applications' => 'Viewing <span class="label label-success">accepted</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_declined_applications' => 'Viewing <span class="label label-danger">declined</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a>.',
	'time_applied' => 'Time Applied',
	'no_applications' => 'No applications in this category',
	'viewing_app_from' => 'Viewing application from {x}', // Don't replace "{x}"
	'open' => 'Open',
	'accepted' => 'Accepted',
	'declined' => 'Declined',
	'accept' => 'Accept',
	'decline' => 'Decline',
	'new_app_submitted_alert' => 'New application submitted by {x}' // Don't replace "{x}"
);
/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'News',
	'social' => 'Social',
	'join' => 'Join',
	
	// General terms
	'submit' => 'Submit',
	'close' => 'Close',
	'cookie_message' => '<strong>This site uses cookies to enhance your experience.</strong><p>By continuing to browse and interact with this website, you agree with their use.</p>',
	'theme_not_exist' => 'The selected theme does not exist.',
	'confirm' => 'Confirm',
	'cancel' => 'Cancel',
	'guest' => 'Guest',
	'guests' => 'Guests',
	'back' => 'Back',
	'search' => 'Search',
	'help' => 'Help',
	'success' => 'Success',
	'error' => 'Error',
	'view' => 'View',
	'info' => 'Info',
	'next' => 'Next',
	
	// Play page
	'connect_with' => 'Connect to the server with the IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Players Online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'There are no players online!',
	'x_players_online' => 'There are {x} players online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Page loaded in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'Sorry, we couldn\'t find that page.'
);
/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forums',
	'discussion' => 'Discussion',
	'stats' => 'Stats',
	'last_reply' => 'Last Reply',
	'ago' => 'ago',
	'by' => 'by',
	'in' => 'in',
	'views' => 'views',
	'posts' => 'posts',
	'topics' => 'topics',
	'topic' => 'Topic',
	'statistics' => 'Statistics',
	'overview' => 'Overview',
	'latest_discussions' => 'Latest Discussions',
	'latest_posts' => 'Latest Posts',
	'users_registered' => 'Users registered:',
	'latest_member' => 'Latest member:',
	'forum' => 'Forum',
	'last_post' => 'Last Post',
	'no_topics' => 'No topics here yet',
	'new_topic' => 'New Topic',
	'subforums' => 'Subforums:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Topic Locked',
	'new_reply' => 'New Reply',
	'mod_actions' => 'Mod Actions',
	'lock_thread' => 'Lock Thread',
	'unlock_thread' => 'Unlock Thread',
	'merge_thread' => 'Merge Thread',
	'delete_thread' => 'Delete Thread',
	'confirm_thread_deletion' => 'Are you sure you want to delete this thread?',
	'move_thread' => 'Move Thread',
	'sticky_thread' => 'Sticky Thread',
	'report_post' => 'Report Post',
	'quote_post' => 'Quote Post',
	'delete_post' => 'Delete Post',
	'edit_post' => 'Edit Post',
	'reputation' => 'reputation',
	'confirm_post_deletion' => 'Are you sure you want to delete this post?',
	'give_reputation' => 'Give reputation',
	'remove_reputation' => 'Remove reputation',
	'post_reputation' => 'Post Reputation',
	'no_reputation' => 'No reputation for this post yet',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Create post',
	'post_submitted' => 'Post submitted',
	'creating_post_in' => 'Creating post in: ',
	'topic_locked_permission_post' => 'This topic is locked, however your permissions allow you to post',
	
	// Edit post view
	'editing_post' => 'Editing post',
	
	// Sticky threads
	'thread_is_' => 'Thread is ',
	'now_sticky' => 'now a sticky thread',
	'no_longer_sticky' => 'no longer a sticky thread',
	
	// Create topic
	'topic_created' => 'Sujet créé.',
	'creating_topic_in_' => 'Création du sujet dans le forum ',
	'thread_title' => 'Titre du sujet',
	'confirm_cancellation' => 'Êtes-vous sûr ?',
	'label' => 'Label',
	
	// Reports
	'report_submitted' => 'Rapport envoyé.',
	'view_post_content' => 'Voir le contenu du message',
	'report_reason' => 'Raison du rapport',
	
	// Move thread
	'move_to' => 'Déplacer vers:',
	
	// Merge threads
	'merge_instructions' => 'The thread to merge with <strong>must</strong> be within the same forum. Move a thread if necessary.',
	'merge_with' => 'Fusionné avec:',
	
	// Other
	'forum_error' => 'Désolé, impossible de trouver ce forum ou ce sujet.',
	'are_you_logged_in' => 'Êtes-vous connecté ?',
	'online_users' => 'Utilisateurs connectés',
	'no_users_online' => 'Il n\'y a pas d\'utilisateurs connectés.',
	
	// Search
	'search_error' => 'Merci d\'entrer une recherche comprise entre 1 et 32 caractères de longueur.',
	
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
	'message' => 'Merci de vous-être enregistré sur notre site web ! Tout d\'abord pour valider votre compte, merci de cliquer sur le lien suivant:',
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
	'display_records_per_page' => 'Afficha de _MENU_ enregistrements par page', // Don't replace "_MENU_"
	'nothing_found' => 'Aucun résultat trouvé',
	'page_x_of_y' => 'Affichage de la page _PAGE_ sur _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Pas d\'enregistrement disponible',
	'filtered' => '(filtré _MAX_ enregistrements totaux)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Complete Registration'
);
 
?>
