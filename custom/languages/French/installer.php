<?php
/*
 *  Made by White, Ikiae.
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  French Language - Users
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Installer',
    'pre-release' => 'pre-release',
    'installer_welcome' => 'Bienvenue sur NamelessMC sous la version 2.0 pre-release.',
    'pre-release_warning' => 'Veuillez noter que ce pre-release n\'est pas destinée à être utilisée sur un site public.',
    'installer_information' => 'L’installateur vous guidera dans le processus d’installation.',
    'new_installation_question' => 'Premièrement, s’agit-il d’une nouvelle installation?',
    'new_installation' => 'Nouvelle installation &raquo;',
    'upgrading_from_v1' => 'Mise à niveau à partir de la v1 &raquo;',
    'requirements' => 'Exigences :',
    'config_writable' => 'Permission d\'écrire dans le fichier core/config.php',
    'cache_writable' => 'Permission d\'écrire dans cache',
    'template_cache_writable' => 'Permission d\'écrire Template Cache',
    'exif_imagetype_banners_disabled' => 'Sans la fonction exif_imagetype, les bannières du serveurs seront indisponibles.',
    'requirements_error' => 'Vous devez avoir installé toutes les extensions requises, et avoir les permissions correctes définies, afin de procéder à l’installation.',
    'proceed' => 'Procéder',
    'database_configuration' => 'Configuration de la base de donnée',
    'database_address' => 'Adresse de la base de donnée',
    'database_port' => 'Port de la base de donnée',
    'database_username' => 'Nom d\'utilisateur de la base de donnée (ex: root)',
    'database_password' => 'Mot de passe de la base de donnée',
    'database_name' => 'Nom de la base de donnée',
    'nameless_path' => 'Chemin d\'installation',
    'nameless_path_info' => 'C\'est le chemin d\'installation où Nameless est installé, par rapport à votre domaine. Par exemple, si Nameless est installé à exemple.com/forum, cela doit être <strong>forum</strong>. Laisser vide si Nameless n’est pas dans un sous-dossier.',
    'friendly_urls' => 'Urls conviviales',
    'friendly_urls_info' => 'Les urls conviviales amélioreront la lisibilité des URLs dans votre navigateur. Par exemple: <br /> <code>exemple.com/index.php?route=/forum</code><br /> deviendra <br /> <code>exemple.com/forum</code> <div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Important!</strong><br /> Votre serveur doit être configuré correctement pour que cela fonctionne. Vous pouvez voir si vous pouvez activer cette option en cliquant <a href=\'./rewrite_test\'target=\'_blank\' style="color:#2185D0">ici</a>.</div>',
    'enabled' => 'Activé',
    'disabled' => 'Désactivé',
    'character_set' => 'Ensemble de caractères',
    'database_engine' => 'Moteur de stockage de base de données',
    'host' => 'Nom d\'hôte',
    'host_help' => 'Le nom d’hôte est <strong>l’URL de base</strong> de votre site web. N’incluez pas les sous-dossiers du champ du Chemin d’installation, ou http(s):// ici!',
    'database_error' => 'Assurez-vous que tous les champs ont été remplis.',
    'submit' => 'Soumettre',
    'installer_now_initialising_database' => 'L’installateur est en train d’initialiser la base de données. Celaa peut prendre du temps...',
    'configuration' => 'Configuration',
    'configuration_info' => 'Veuillez saisir des informations de base sur votre site. Ces valeurs peuvent être modifiées ultérieurement via le panneau d’administration.',
    'configuration_error' => 'Veuillez saisir un nom de site valide de 1 à 32 caractères, et des adresses email valides de 4 à 64 caractères.',
    'site_name' => 'Nom du site',
    'contact_email' => 'Email de contact',
    'outgoing_email' => 'Email de sortie (addresse mail d\'envoie)',
    'initialising_database_and_cache' => 'Initialisation de la base de données et du cache, veuillez patienter...',
    'unable_to_login' => 'Impossible de se connecter.',
    'unable_to_create_account' => 'Impossible de créer un compte',
    'input_required' => 'Veuillez saisir un nom d’utilisateur, une adresse e-mail et un mot de passe valides.',
    'input_minimum' => 'Assurez-vous que votre nom d’utilisateur est d’au moins 3 caractères, que votre adresse e-mail est d’au moins 4 caractères et que votre mot de passe est d’au moins 6 caractères.',
    'input_maximum' => 'Veuillez vous assurer que votre nom d\'utilisateur ne comporte pas plus de 20 caractères, et que votre adresse électronique et votre mot de passe ne comportent pas plus de 64 caractères.',
    'email_invalid' => 'Votre email n’est pas valide.',
    'passwords_must_match' => 'Vos mots de passe doivent correspondre.',
    'creating_admin_account' => 'Création d\'un compte administratif',
    'enter_admin_details' => 'S’il vous plaît entrer les détails pour le compte admin.',
    'username' => 'Nom d\'utilisateur',
    'email_address' => 'Adresse mail',
    'password' => 'Mot de passe',
    'confirm_password' => 'Confirmer le mot de passe',
    'upgrade' => 'Mise à niveau',
    'input_v1_details' => 'Veuillez saisir les détails de la base de données pour votre installation Nameless V1.',
    'installer_upgrading_database' => 'Veuillez patienter pendant que l’installateur met à jour votre base de données...',
    'errors_logged' => 'Des erreurs ont été enregistrées. Cliquez sur Continuer pour continuer la mise à jour.',
    'continue' => 'Continue',
    'convert' => 'Convertir',
    'convert_message' => 'Enfin, voulez-vous convertir à partir d’un logiciel de forum différent ?',
    'yes' => 'Oui',
    'no' => 'Non',
    'converter' => 'Convertisseur',
    'back' => 'Retour',
    'unable_to_load_converter' => 'Impossible de charger le convertisseur!',
    'finish' => 'Terminé',
    'finish_message' => 'Merci d’avoir installé NamelessMC! Vous pouvez maintenant passer au Panel d\'administration, où vous pouvez configurer votre site Web.',
    'support_message' => 'Si vous avez besoin d’aide, consultez notre site Web <a href="https://namelessmc.com" target="_blank">ici</a>, ou vous pouvez également consulter notre serveur <a href="https://discord.gg/nameless" target="_blank">Discord</a> ou notre dépôt <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub</a>.',
    'credits' => 'Crédits',
    'credits_message' => 'Un grand merci à tous les contributeurs <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC</a> depuis 2014'
);
