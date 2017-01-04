<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Portuguese Language - Admin
 *  Translation By Douglas Teles
 *  Last Update: 04/01/2017
 */
$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'Por favor, logue-se novamente',
	
	// Sidebar
	'admin_cp' => 'AdminCP',
	'administration' => 'Administração',
	'overview' => 'Visão Geral',
	'core' => 'Core',
	'minecraft' => 'Minecraft',
	'modules' => 'Módulos',
	'security' => 'Seguraça',
	'styles' => 'Estilos',
	'users_and_groups' => 'Usuários & Grupos',
	
	// Overview
	'running_nameless_version' => 'Versão do NamelessMC: <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Versxão do PHP: <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => 'Estatísticas',
	
	// Core
	'settings' => 'Configurações',
	'general_settings' => 'Configurações Gerais',
	'sitename' => 'Nome do site',
	'default_language' => 'Linguagem padrão',
	'default_language_help' => 'Os usuários poderão escolher entre os idiomas instalados.',
	'installed_languages' => 'Todos os novos idiomas foram instalados com êxito.',
	'default_timezone' => 'Fuso horário padrão',
	'registration' => 'Cadastro',
	'enable_registration' => 'Ativar cadastro?',
	'verify_with_mcassoc' => 'Verificar contas dos usuários com MCAssoc?',
	'email_verification' => 'Ativar verificação de e-mail?',
	'homepage_type' => 'Tipo de Homepage',
	'post_formatting_type' => 'Tipo de formatação de postagem',
	'portal' => 'Portal',
	'missing_sitename' => 'Por favor, insira o nome do site contendo de 2 a 64 caracteres.',
	'use_friendly_urls' => 'URLs Amigáveis',
	'use_friendly_urls_help' => 'IMPORTANTE: Seu servidor deverá permitir o uso do mod_rewrite e do .htaccess para que isso funcione.',
	'config_not_writable' => 'Seu arquivo <strong>core/config.php</strong> não é gravável. Por favor, verifique as permissões do arquivo.',
	'social_media' => 'Social',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Utilizar tema escuro do Twitter?',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'successfully_updated' => 'Atualizado com sucesso!',
	
	// Reactions
	'icon' => 'Icone',
	'type' => 'Tipo',
	'positive' => 'Positivo',
	'neutral' => 'Neutro',
	'negative' => 'Negativo',
	'editing_reaction' => 'Editando Reação',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> Nova Reação',
	'creating_reaction' => 'Criando Reação',
	
	// Custom profile fields
	'custom_fields' => 'Campos Personalizados do Perfil',
	'new_field' => '<i class="fa fa-plus-circle"></i> Novo Campo',
	'required' => 'Requerido',
	'public' => 'Público',
	'text' => 'Texto',
	'textarea' => 'Área de texto',
	'date' => 'Data',
	'creating_profile_field' => 'Criando Campo de Perfil',
	'editing_profile_field' => 'Editando Campo de Perfil',
	'field_name' => 'Nome do Campo',
	'profile_field_required_help' => 'Required fields must be filled out by the user, and they will appear during registration.',
	'profile_field_public_help' => 'Public fields will be displayed to all users, if this is disabled only moderators can view the values.',
	'profile_field_error' => 'Please input a field name between 2 and 16 characters long.',
	'description' => 'Description',
	'display_field_on_forum' => 'Display field on forum?',
	'profile_field_forum_help' => 'If enabled, the field will display by the user next to forum posts.',
	
	// Minecraft
	'enable_minecraft_integration' => 'Enable Minecraft integration?',
	
	// Modules
	'modules_installed_successfully' => 'Any new modules have been installed successfully.',
	'enabled' => 'Enabled',
	'disabled' => 'Disabled',
	'module_enabled' => 'Module enabled.',
	'module_disabled' => 'Module disabled.',
	
	// Styles
	'templates' => 'Templates',
	'template_outdated' => 'We have detected that your template is intended for Nameless version {x}, but you are running Nameless version {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Active',
	'deactivate' => 'Deactivate',
	'activate' => 'Activate',
	'warning_editing_default_template' => 'Warning! It is recommended that you do not edit the default template.',
	'images' => 'Images',
	'upload_new_image' => 'Upload New Image',
	'reset_background' => 'Reset Background',
	'install' => '<i class="fa fa-plus-circle"></i> Install',
	'template_updated' => 'Template successfully updated.',
	'default' => 'Default',
	'make_default' => 'Make Default',
	'default_template_set' => 'Default template set to {x} successfully.', // Don't replace {x}
	'template_deactivated' => 'Template deactivated.',
	'template_activated' => 'Template activated.',
	
	// Users & groups
	'users' => 'Users',
	'groups' => 'Groups',
	'group' => 'Group',
	'new_user' => '<i class="fa fa-plus-circle"></i> New User',
	'creating_new_user' => 'Creating new user',
	'registered' => 'Registered',
	'user_created' => 'User created successfully.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group!',
	'user_deleted' => 'User deleted successfully.',
	'confirm_user_deletion' => 'Are you sure you want to delete the user <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Validate User',
	'update_uuid' => 'Update UUID',
	'update_mc_name' => 'Update Minecraft Username',
	'reset_password' => 'Reset Password',
	'punish_user' => 'Punish User',
	'delete_user' => 'Delete User',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Other Actions',
	'disable_avatar' => 'Disable Avatar',
	'select_user_group' => 'You must select a user\'s group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'title_max_64' => 'The user title must be a maximum of 64 characters.',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'Group ID',
	'name' => 'Name',
	'title' => 'User Title',
	'new_group' => '<i class="fa fa-plus-circle"></i> New Group',
	'group_name_required' => 'Please input a group name.',
	'group_name_minimum' => 'Please ensure your group name is a minimum of 2 characters long.',
	'group_name_maximum' => 'Please ensure your group name is a maximum of 20 characters long.',
	'creating_group' => 'Creating new group',
	'group_html_maximum' => 'Please ensure your group HTML is no longer than 1024 characters long.',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'group_username_colour' => 'Group Username Color',
	'group_staff' => 'Is the group a staff group?',
	'group_modcp' => 'Can the group view the ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'delete_group' => 'Delete Group',
	'confirm_group_deletion' => 'Are you sure you want to delete the group {x}?', // Don't replace {x}
	'group_not_exist' => 'That group doesn\'t exist.',
	
	// General Admin language
	'task_successful' => 'Task successful.',
	'invalid_action' => 'Invalid action.',
	'enable_night_mode' => 'Enable Night Mode',
	'disable_night_mode' => 'Disable Night Mode',
	'view_site' => 'View Site',
	'signed_in_as_x' => 'Signed in as {x}', // Don't replace {x}
	
	// Security
	'acp_logins' => 'AdminCP Logins',
	'please_select_logs' => 'Please select logs to view',
	'ip_address' => 'IP Address',
	'template_changes' => 'Template Changes',
	'file_changed' => 'File Changed',
	
	// Updates
	'update' => 'Update',
	'current_version_x' => 'Current version: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'New version: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'There is a new update available',
	'up_to_date' => 'Your NamelessMC installation is up to date!',
	'urgent' => 'This update is an urgent update',
	'changelog' => 'Changelog',
	'update_check_error' => 'There was an error whilst checking for an update:',
	'instructions' => 'Instructions',
	'download' => 'Download',
	'install' => 'Install',
	'install_confirm' => 'Please ensure you have downloaded the package and uploaded the contained files first!',
	
	// File uploads
	'drag_files_here' => 'Drag files here to upload.',
	'invalid_file_type' => 'Invalid file type!',
	'file_too_big' => 'File too big! Your file was {{filesize}} and the limit is {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
