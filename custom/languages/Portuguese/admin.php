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
	'security' => 'Segurança',
	'styles' => 'Estilos',
	'users_and_groups' => 'Usuários & Grupos',
	
	// Overview
	'running_nameless_version' => 'Versão do NamelessMC: <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Versão do PHP: <strong>{x}</strong>', // Don't replace "{x}"
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
	'profile_field_required_help' => 'Os campos obrigatórios devem ser preenchidos pelo usuário, e eles aparecerão durante o registro.',
	'profile_field_public_help' => 'Os campos públicos serão exibidos para todos os usuários, se ele estiver desabilitado, somente os moderadores poderão ver os valores.',
	'profile_field_error' => 'Introduza um nome de campo entre 2 e 16 caracteres.',
	'description' => 'Descrição',
	'display_field_on_forum' => 'Mostrar campo no fórum?',
	'profile_field_forum_help' => 'Se ativado, o campo será exibido pelo usuário próximo aos posts do fórum.',
	
	// Minecraft
	'enable_minecraft_integration' => 'Ativar a integração do Minecraft?',
	
	// Modules
	'modules_installed_successfully' => 'Todos os novos módulos foram instalados com êxito.',
	'enabled' => 'Ativado',
	'disabled' => 'Desativado',
	'module_enabled' => 'Módulo ativado.',
	'module_disabled' => 'Módulo desativado.',
	
	// Styles
	'templates' => 'Templates',
	'template_outdated' => 'Você está utilizando um template para a versão {x} do Nameless, porém sua versão do Nameless é {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Ativo',
	'deactivate' => 'Desativar',
	'activate' => 'Ativar',
	'warning_editing_default_template' => 'Atenção! É recomendável que você não edite o modelo padrão.',
	'images' => 'Imagens',
	'upload_new_image' => 'Carregar nova imagem',
	'reset_background' => 'Resetar Background',
	'install' => '<i class="fa fa-plus-circle"></i> Instalar',
	'template_updated' => 'Template atualizado com sucesso.',
	'default' => 'Padrão',
	'make_default' => 'Tornar Padrão',
	'default_template_set' => 'Template padrão definido para {x} com sucesso.', // Don't replace {x}
	'template_deactivated' => 'Template desativado.',
	'template_activated' => 'Template ativado.',
	
	// Users & groups
	'users' => 'Usuários',
	'groups' => 'Grupos',
	'group' => 'Grupo',
	'new_user' => '<i class="fa fa-plus-circle"></i> Novo Usuário',
	'creating_new_user' => 'Criando novo usuário',
	'registered' => 'Cadastrado',
	'user_created' => 'Usuário criado com sucesso.',
	'cant_delete_root_user' => 'Não é possível excluir o usuário root!',
	'cant_modify_root_user' => 'Não é possível modificar o grupo root!',
	'user_deleted' => 'Usuário excluído com sucesso.',
	'confirm_user_deletion' => 'Você tem certeza que deseja excluir o usuário <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Validar Usuário',
	'update_uuid' => 'Atualizar UUID',
	'update_mc_name' => 'Atualizar Usuário Minecraft',
	'reset_password' => 'Resetar Senha',
	'punish_user' => 'Punir Usuário',
	'delete_user' => 'Excluir Usuário',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Outras Ações',
	'disable_avatar' => 'Desativar Avatar',
	'select_user_group' => 'Você deve selecionar um grupo de usuários.',
	'uuid_max_32' => 'A UUID deve ter no máximo 32 caracteres.',
	'title_max_64' => 'O título do usuário deve ter no máximo 64 caracteres.',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'ID do Grupo',
	'name' => 'Nome',
	'title' => 'Título do Usuário',
	'new_group' => '<i class="fa fa-plus-circle"></i> Novo Grupo',
	'group_name_required' => 'Introduza um nome de grupo.',
	'group_name_minimum' => 'Certifique-se de que o nome do seu grupo tem um mínimo de 2 caracteres.',
	'group_name_maximum' => 'Certifique-se de que o nome do seu grupo tem um máximo de 20 caracteres.',
	'creating_group' => 'Criando novo grupo',
	'group_html_maximum' => 'Certifique-se de que o HTML do grupo não exceda 1024 caracteres.',
	'group_html' => 'HTML do Grupo',
	'group_html_lg' => 'HTML do Grupo Grande',
	'group_username_colour' => 'Cor do Grupo',
	'group_staff' => 'O grupo é um grupo da Staff?',
	'group_modcp' => 'O grupo possui acesso ao ModCP?',
	'group_admincp' => 'O grupo possui acesso ao AdminCP?',
	'delete_group' => 'Excluír Grupo',
	'confirm_group_deletion' => 'Você tem certeza de que deseja excluir o grupo {x}?', // Don't replace {x}
	'group_not_exist' => 'Esse grupo não existe.',
	
	// General Admin language
	'task_successful' => 'Tarefa bem-sucedida.',
	'invalid_action' => 'Ação inválida.',
	'enable_night_mode' => 'Ativar modo noturno',
	'disable_night_mode' => 'Desativar modo noturno',
	'view_site' => 'Ver Site',
	'signed_in_as_x' => 'Logado como {x}', // Don't replace {x}
	
	// Security
	'acp_logins' => 'AdminCP Logins',
	'please_select_logs' => 'Selecione logs para visualizar',
	'ip_address' => 'Endereço IP',
	'template_changes' => 'Alterações do Template',
	'file_changed' => 'Arquivo Modificado',
	
	// Updates
	'update' => 'Atualizar',
	'current_version_x' => 'Versão atual: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'Nova versão: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'Há uma nova atualização disponível',
	'up_to_date' => 'A sua instalação do NamelessMC está atualizada!',
	'urgent' => 'Esta atualização é uma atualização crítica',
	'changelog' => 'Changelog',
	'update_check_error' => 'Ocorreu um erro ao verificar se havia uma atualização:',
	'instructions' => 'Instruções',
	'download' => 'Download',
	'install' => 'Instalar',
	'install_confirm' => 'Certifique-se de que transferiu o pacote e carregou os ficheiros contidos em primeiro lugar!',
	
	// File uploads
	'drag_files_here' => 'Arraste arquivos aqui para fazer o upload.',
	'invalid_file_type' => 'Tipo de arquivo inválido!',
	'file_too_big' => 'Arquivo muito grande! Seu arquivo possui {{filesize}} e o limite é {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
