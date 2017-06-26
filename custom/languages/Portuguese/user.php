<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Portuguese Language - Users
 *  Translation By Douglas Teles & dasilvaj4
 *  Last Update: 26/06/2017
 */
$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Visitante',
	'guests' => 'Visitantes',
	
	// UserCP
	'user_cp' => 'UserCP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Visão Geral',
	'user_details' => 'Detalhes do Usuário',
	'profile_settings' => 'Configurações de Perfil',
	'successfully_logged_out' => 'Você foi desconectado com sucesso.',
	'messaging' => 'Mensagens',
	'click_here_to_view' => 'Clique aqui para visualizar.',
	'moderation' => 'Moderação',
	'administration' => 'Administração',
	'alerts' => 'Alertas',
	'delete_all' => 'Excluir Todos',
	
	// Profile settings
	'field_is_required' => '{x} é requerido.', // Don't replace {x}
	'settings_updated_successfully' => 'Configurações atualizadas com sucesso.',
	'password_changed_successfully' => 'Senha alterada com sucesso.',
	'change_password' => 'Alterar Senha',
	'current_password' => 'Senha Atual',
	'new_password' => 'Nova Senha',
	'confirm_new_password' => 'Confirme a Nova Senha',
	'incorrect_password' => 'Sua senha está incorreta.',
	'two_factor_auth' => 'Autenticação em Dois Fatores',
	'enable' => 'Ativar',
	'disable' => 'Desativar',
	'tfa_scan_code' => 'Verifique o seguinte código no seu aplicativo de autenticação:',
	'tfa_code' => 'Se o dispositivo não tiver uma câmera ou não conseguir digitalizar o código QR, introduza o seguinte código:',
	'tfa_enter_code' => 'Insira o código exibido no seu aplicativo de autenticação:',
	'invalid_tfa' => 'Código inválido, por favor tente novamente.',
	'tfa_successful' => 'Autenticação em dois fatores configurado com sucesso. Você precisará se autenticar toda vez que você fizer login a partir de agora.',
	'active_language' => 'Linguagem Ativa',
    'timezone' => 'Fuso horário',
	
	// Alerts
	'user_tag_info' => 'Você foi marcado em um post por {x}.', // Don't replace {x}
	'no_alerts' => 'Sem novos alertas',
	'view_alerts' => 'Ver alertas',
	'x_new_alerts' => 'Você possui {x} novos alertas', // Don't replace {x}
	'no_alerts_usercp' => 'Você não possui nenhum alerta.',
	
	// Registraton
	'registration_check_email' => 'Obrigado por se registrar! Verifique os seus e-mails para obter um link de validação para concluir o seu registo. Se você não conseguir encontrar o e-mail, verifique sua pasta de lixo eletrônico.',
	'username' => 'Usuário',
	'nickname' => 'Nickname',
	'minecraft_username' => 'Usuário Minecraft',
	'email_address' => 'Endereço de E-mail',
	'email' => 'E-mail',
	'password' => 'Senha',
	'confirm_password' => 'Confirme a Senha',
	'i_agree' => 'Eu aceito',
	'agree_t_and_c' => 'Clicando em <strong class="label label-primary">Registrar</strong>, você concorda com nossos <a href="{x}" target="_blank">Termos & Condições</a>.',
	'create_an_account' => 'Criar uma Conta',
	'terms_and_conditions' => 'Termos & Condições',
	'validation_complete' => 'Sua conta foi validada, agora você pode fazer login.',
	'validation_error' => 'Ocorreu um erro desconhecido ao validar sua conta, entre em contato com um administrador.',
	'signature' => 'Assinatura',

    // Registration - Authme
    'connect_with_authme' => 'Conecte sua conta com AuthMe',
    'authme_help' => 'Digite os detalhes da sua conta AuthMe. Se você ainda não possui uma conta no servidor, entre no servidor agora e siga as instruções fornecidas.',
    'unable_to_connect_to_authme_db' => 'Não é possível conectar-se ao banco de dados AuthMe. Se esse erro persistir, entre em contato com um administrador.',
    'authme_account_linked' => 'Conta vinculada com sucesso.',
    'authme_email_help_1' => 'Finalmente, insira seu endereço de e-mail.',
    'authme_email_help_2' => 'Finalmente, digite seu endereço de e-mail e escolha um nome para sua conta.',

	// Registration errors
	'username_required' => 'É necessário um nome de usuário.',
	'email_required' => 'É necessário um endereço de e-mail.',
	'password_required' => 'Uma senha é necessária.',
	'mcname_required' => 'É necessário um nome de usuário do Minecraft.',
	'accept_terms' => 'Você deve aceitar os termos e condições antes de se registrar.',
	'username_minimum_3' => 'Seu nome de usuário deve ter no mínimo 3 caracteres.',
	'mcname_minimum_3' => 'Seu nome de usuário Minecraft deve ter no mínimo 3 caracteres.',
	'password_minimum_6' => 'Sua senha deve ter no mínimo 6 caracteres.',
	'username_maximum_20' => 'Seu nome de usuário deve ter no máximo 20 caracteres.',
	'mcname_maximum_20' => 'Seu nome de usuário do Minecraft deve ter no máximo 20 caracteres.',
	'password_maximum_30' => 'Sua senha deve ter no máximo 30 caracteres.',
	'passwords_dont_match' => 'Suas senhas não coincidem.',
	'username_mcname_email_exists' => 'Seu nome de usuário ou endereço de e-mail já existe.',
	'invalid_mcname' => 'Seu nome de usuário do Minecraft é inválido.',
	'invalid_email' => 'Seu e-mail é inválido.',
	'mcname_lookup_error' => 'Ocorreu um erro ao se comunicar com os servidores da Mojang para verificar seu nome de usuário. Por favor, tente novamente mais tarde.',
	'invalid_recaptcha' => 'Resposta reCAPTCHA inválida.',
	'verify_account' => 'Verificar Conta',
	'verify_account_help' => 'Siga as instruções abaixo para que possamos verificar se você possui a conta do Minecraft em questão.',
	'verification_failed' => 'Falha na verificação. Por favor tente novamente.',
	'verification_success' => 'Validado com sucesso! Agora você pode entrar.',
	
	// Login
	'successful_login' => 'Você fez login com sucesso.',
	'incorrect_details' => 'Você inseriu detalhes incorretos.',
	'inactive_account' => 'Sua conta está inativa. Verifique seus e-mails para obter um link de validação, inclusive dentro da pasta de lixo eletrônico.',
	'account_banned' => 'Essa conta está banida.',
	'forgot_password' => 'Esqueceu a senha?',
	'remember_me' => 'Lembrar-me',
	'must_input_username' => 'Você deve inserir um nome de usuário.',
	'must_input_password' => 'Você deve inserir uma senha.',
	
	// Profile pages
	'profile' => 'Perfil',
	'follow' => 'Seguir',
	'no_wall_posts' => 'Não há nenhuma publicação ainda.',
	'change_banner' => 'Alterar Banner',
	'post_on_wall' => 'Publicações no perfil de {x}', // Don't replace {x}
	'invalid_wall_post' => 'Certifique-se de que a sua mensagem tem entre 1 e 10000 caracteres.',
	'1_reaction' => '1 reação',
	'x_reactions' => '{x} reações', // Don't replace {x}
	'1_like' => '1 curtida',
	'x_likes' => '{x} curtidas', // Don't replace {x}
	'1_reply' => '1 resposta',
	'x_replies' => '{x} respostas', // Don't replace {x}
	'no_replies_yet' => 'Nenhuma resposta ainda',
	'feed' => 'Feed',
	'about' => 'Sobre',
	'reactions' => 'Reações',
	'replies' => 'Respostas',
	'new_reply' => 'Nova Resposta',
	'registered' => 'Cadastrado:',
	'last_seen' => 'Visto pela última vez:',
	
	// Reports
	'invalid_report_content' => 'Não foi possível criar o alerta. Certifique-se de que o motivo do alerta está entre 2 e 1024 caracteres.',
	'report_post_content' => 'Introduza uma razão para o seu alerta',
	'report_created' => 'Alerta criado com sucesso',
	
	// Messaging
	'no_messages' => 'Sem novas mensagens',
	'no_messages_full' => 'Você não possui nenhuma mensagem.',
	'view_messages' => 'Visualizar mensagens',
	'x_new_messages' => 'Você possui {x} novas mensagens', // Don't replace {x}
	'new_message' => 'Nova Mensagem',
	'message_title' => 'Título da Mensagem',
	'to' => 'Para',
	'separate_users_with_commas' => 'Separe os usuários com vírgula',
	'title_required' => 'Introduza um título',
	'content_required' => 'Introduza algum conteúdo',
	'users_to_required' => 'Introduza algum destinatário',
	'cant_send_to_self' => 'Você não pode enviar uma mensagem para si mesmo!',
	'title_min_2' => 'O título deve ter no mínimo 2 caracteres',
	'content_min_2' => 'O conteúdo deve ter no mínimo 2 caracteres',
	'title_max_64' => 'O título deve ter no máximo 64 caracteres',
	'content_max_20480' => 'O conteúdo deve ter no máximo 20480 caracteres',
	'max_pm_10_users' => 'Só pode enviar uma mensagem para no máximo de 10 usuários',
	'message_sent_successfully' => 'Mensagem enviada com sucesso',
	'participants' => 'Participantes',
	'last_message' => 'Última Mensagem',
	'by' => 'por',
	'leave_conversation' => 'Deixar Conversa',
	'confirm_leave' => 'Tem certeza de que deseja sair desta conversa?',
	
	// Reactions
	'reactions' => 'Reações',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Infrações',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Olá,',
	'email_message' => 'Obrigado por se registrar! Para completar o seu cadastro, clique no link a seguir:',
	'email_thanks' => 'Obrigado,'
);
