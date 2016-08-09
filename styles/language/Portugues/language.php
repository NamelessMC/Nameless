<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  Linguagem: Português-BR
 *  Por: www.craftalizar.com
 *  Tradutor: Douglas Teles
 *  Versão: 0.2.0
 *  Última revisão: 08/07/2016
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP', 
	'invalid_token' => 'Token inválido, tente novamente.',
	'invalid_action' => 'Ação inválida',
	'successfully_updated' => 'Atualizado com sucesso',
	'settings' => 'Configurações',
	'confirm_action' => 'Confirmar ação',
	'edit' => 'Editar',
	'actions' => 'Ações',
	'task_successful' => 'Tarefa executada com êxito',
	
	// Admin login
	're-authenticate' => 'Por favor, logue-se novamente',
	
	// Admin sidebar
	'index' => 'Visão Geral',
	'core' => 'Core',
	'custom_pages' => 'Páginas Personalizadas',
	'general' => 'Geral',
	'forums' => 'Fóruns',
	'users_and_groups' => 'Usuários e Grupos',
	'minecraft' => 'Minecraft',
	'style' => 'Estilos',
	'addons' => 'Addons',
	'update' => 'Atualização',
	'misc' => 'Outras Configurações',
	
	// Admin index page
	'statistics' => 'Estatísticas',
	'registrations_per_day' => 'Cadastros por dia (últimos 7 dias)',
	
	// Admin core page
	'general_settings' => 'Configurações Gerais',
	'modules' => 'Módulos',
	'module_not_exist' => 'Esse módulo não existe!',
	'module_enabled' => 'Módulo ativado.',
	'module_disabled' => 'Módulo desativado.',
	'site_name' => 'Nome do Site',
	'language' => 'Linguagem',
	'voice_server_not_writable' => 'core/voice_server.php não é gravável. Por favor, verifique as permissões do arquivo.',
	'email' => 'Email',
	'incoming_email' => 'Email para recebimentos',
	'outgoing_email' => 'Email para envios',
	'outgoing_email_help' => 'Somente requerido se a função PHP Mail estiver ativa.',
	'use_php_mail' => 'Usar função PHP mail()?',
	'use_php_mail_help' => 'Recomendado: ativado. Se o seu site não estiver enviando emails, desative isso e edite o core/email.php com suas configurações de email.',
	'use_gmail' => 'Utilizar Gmail para envios?',
	'use_gmail_help' => 'Somente requerido se a função PHP Mail estiver desativa. Se você optar por não usar o Gmail, SMTP será usado. De qualquer maneira você precisará editar o core/email.php.',
	'enable_mail_verification' => 'Ativar a verificação da conta de e-mail?',
	'enable_email_verification_help' => 'Ativando esse recurso, novos usuários precisarão verificar via e-mail para completarem seus cadastrados.',
	'explain_email_settings' => 'O seguinte é necessário se a opção "Usar função PHP mail()" está <strong>desativada</strong>. Você poderá encontrar a documentação dessa configuração <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">na nossa Wiki</a>.',
	'email_config_not_writable' => 'Seu arquivo <strong>core/email.php</strong> não é gravável. Por favor, verifique as permissões.',
	'pages' => 'Páginas',
	'enable_or_disable_pages' => 'Ative ou desativas suas páginas aqui.',
	'enable' => 'Ativar',
	'disable' => 'Desativar',
	'maintenance_mode' => 'Modo de manutenção para o fórum',
	'forum_in_maintenance' => 'O fórum está em modo de manutenção.',
	'unable_to_update_settings' => 'Não foi possível atualizar as configurações. Por favor, verifique se não possui campos em branco.',
	'editing_google_analytics_module' => 'Editando módulo Google Analytics',
	'tracking_code' => 'API Google Analytics',
	'tracking_code_help' => 'Insira o código de acompanhamento do Google Analytics aqui, incluindo as tags de script circundantes.',
	'google_analytics_help' => 'Veja <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">este guia</a> para mais informações, siga os passos 1 ao 3.',
	'social_media_links' => 'Links Páginas Sociais',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Cadastro',
	'registration_warning' => 'Desativando este módulo, também desativará novos cadastros em seu site.',
	'google_recaptcha' => 'Ativar Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Termos e Condições de Cadastro',
	'voice_server_module' => 'Módulo de Servidores de Voz',
	'only_works_with_teamspeak' => 'Este módulo atualmente funciona com TeamSpeak e Discord',
	'discord_id' => 'ID do Servidor Discord',
	'voice_server_help' => 'Entre com os detalhes do usuário ServerQuery',
	'ip_without_port' => 'IP (não inclua a porta)',
	'voice_server_port' => 'Porta (normalmente 10011)',
	'virtual_port' => 'Porta Virtual (normalmente 9987)',
	'permissions' => 'Permissões:',
	'view_applications' => 'Ver Aplicações?',
	'accept_reject_applications' => 'Aceitar/Rejeitar aplicações?',
	'questions' => 'Perguntas:',
	'question' => 'Pergunta',
	'type' => 'Tipo',
	'options' => 'Opções',
	'options_help' => 'Cada opção em uma nova linha; podem ser deixados vazios (dropdowns apenas)',
	'no_questions' => 'Nenhuma pergunta adicionada ainda.',
	'new_question' => 'Nova Pergunta',
	'editing_question' => 'Editando Pergunta',
	'delete_question' => 'Apagar Pergunta',
	'dropdown' => 'Dropdown',
	'text' => 'Texto',
	'textarea' => 'Área de Texto',
	'question_deleted' => 'Pergunta Apagada',
	'use_followers' => 'Usar seguidores?',
	'use_followers_help' => 'Se desativado, será utilizado o sistema de amigos.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Clique na página para editá-la.',
	'page' => 'Página:',
	'url' => 'URL:',
	'page_url' => 'URL da Página',
	'page_url_example' => '(Utilize "/", por exemplo /ajuda/)',
	'page_title' => 'Título da Página',
	'page_content' => 'Conteúdo da Página',
	'new_page' => 'Nova Página',
	'page_successfully_created' => 'Página criada com sucesso',
	'page_successfully_edited' => 'Página editada com sucesso',
	'unable_to_create_page' => 'Não foi possível criar a página.',
	'unable_to_edit_page' => 'Não foi possível editar a página.',
	'create_page_error' => 'Certifique-se de que você digitou uma URL contendo entre 1 e 20 caracteres, um título de página contendo entre 1 e 30 caracteres e conteúdo da página contendo entre 5 e 20480 caracteres.',
	'delete_page' => 'Excluir Página',
	'confirm_delete_page' => 'Você tem certeza de que deseja excluir essa página?',
	'page_deleted_successfully' => 'Página excluída com sucesso',
	'page_link_location' => 'O link da página será exibido em:',
	'page_link_navbar' => 'Barra de Navegação',
	'page_link_more' => '"Mais" dropdown',
	'page_link_footer' => 'Rodapé da página',
	'page_link_none' => 'Não adicionar link',
	'page_permissions' => 'Permissões da Página',
	'can_view_page' => 'Pode visualizar a página:',
	'redirect_page' => 'Redirecionar página?',
	'redirect_link' => 'Redirecionar link',
	
	// Admin forum page
	'labels' => 'Rótulos de tópico',
	'new_label' => 'Novo rótulo',
	'no_labels_defined' => 'Nenhum rótulo definido',
	'label_name' => 'Nome do Rótulo',
	'label_type' => 'Tipo do Rótulo',
	'label_forums' => 'Utilizar Rótulo nos Fóruns:',
	'label_creation_error' => 'Erro ao criar um rótulo. Por favor, certifique-se do que nome não é mais do que 32 caracteres e que você especificou um tipo.',
	'confirm_label_deletion' => 'Tem certeza que deseja excluir este rótulo?',
	'editing_label' => 'Edição de rótulo',
	'label_creation_success' => 'Rótulo criado com sucesso',
	'label_edit_success' => 'Rótulo editado com sucesso',
	'label_default' => 'Padrão',
	'label_primary' => 'Primário',
	'label_success' => 'Sucesso',
	'label_info' => 'Info',
	'label_warning' => 'Aviso',
	'label_danger' => 'Perigo',
	'new_forum' => 'Novo Fórum',
	'forum_layout' => 'Layout do Fórum',
	'table_view' => 'Formato padrão (semelhante à IPB, MyBB e etc)',
	'latest_discussions_view' => 'Ver os tópicos mais recentes (semelhante ao Discourse)',
	'create_forum' => 'Criar Fórum',
	'forum_name' => 'Nome do Fórum',
	'forum_description' => 'Descrição do Fórum',
	'delete_forum' => 'Excluir Fórum',
	'move_topics_and_posts_to' => 'Mover tópicos e postagens para',
	'delete_topics_and_posts' => 'Excluir tópicos e postagens',
	'parent_forum' => 'Fórum Vinculado',
	'has_no_parent' => 'Não possui vínculo',
	'forum_permissions' => 'Permissões do Fórum',
	'can_view_forum' => 'Podem ver o fórum',
	'can_create_topic' => 'Podem criar o tópico',
	'can_post_reply' => 'Podem postar resposta',
	'display_threads_as_news' => 'Exibir tópicos como notícia na primeira página?',
	'input_forum_title' => 'Escreva o título do fórum.',
	'input_forum_description' => 'Escreva a descrição do fórum.',
	'forum_name_minimum' => 'O nome do fórum deve ser um mínimo de 2 caracteres.',
	'forum_description_minimum' => 'A descrição do fórum deve ser um mínimo de 2 caracteres.',
	'forum_name_maximum' => 'O nome do fórum deve ser um máximo de 150 caracteres.',
	'forum_description_maximum' => 'A descrição do fórum deve ser um máximo de 255 caracteres.',
	'forum_type_forum' => 'Fórum de Discussão',
	'forum_type_category' => 'Categoria',
	
	// Admin Users and Groups page
	'users' => 'Usuários',
	'new_user' => 'Novo Usuário',
	'created' => 'Criado',
	'user_deleted' => 'Usuário excluído',
	'validate_user' => 'Validar Usuário',
	'update_uuid' => 'Atualizar UUID',
	'unable_to_update_uuid' => 'Não foi possível atualizar o UUID.',
	'update_mc_name' => 'Atualizar Nome Minecraft',
	'reset_password' => 'Redefinir senha',
	'punish_user' => 'Punir Usuário',
	'delete_user' => 'Excluir Usuário',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'Endereço IP',
	'ip' => 'IP:',
	'other_actions' => 'Outras Ações:',
	'disable_avatar' => 'Desativar avatar',
	'enable_avatar' => 'Enable avatar',
	'confirm_user_deletion' => 'Você tem certeza de que deseja excluir o usuário {x}?', // Don't replace "{x}"
	'groups' => 'Grupos',
	'group' => 'Grupo',
	'new_group' => 'Novo Grupo',
	'id' => 'ID',
	'name' => 'Nome',
	'create_group' => 'Criar Grupo',
	'group_name' => 'Nome do Grupo',
	'group_html' => 'HTML do Grupo',
	'group_html_lg' => 'Grupo com HTML grande',
	'donor_group_id' => 'ID do pacote de doador',
	'donor_group_id_help' => '<p>Esse é o ID do grupo de pacotes do Buycraft, MinecraftMarket ou MCStock.</p><p>Pode ser deixado em branco.</p>',
	'donor_group_instructions' => 	'<p>Os grupos de doadores precisem ser criados na ordem de <strong>valor mais baixo para o mais alto valor</strong>.</p>
									<p>Por exemplo, um pacote de R$10 será criado antes de um pacote de R$20.</p>',
	'delete_group' => 'Excluir Grupo',
	'confirm_group_deletion' => 'Você tem certeza de que deseja excluir o grupo {x}?', // Don't replace "{x}"
	'group_staff' => 'Esse grupo é um grupo da Staff?',
	'group_modcp' => 'Esse grupo pode acessar o ModCP?',
	'group_admincp' => 'Esse grupo pode acessar o AdminCP?',
	'group_name_required' => 'Você precisa informar um nome para o grupo.',
	'group_name_minimum' => 'O nome do grupo deve ter um mínimo de 2 caracteres.',
	'group_name_maximum' => 'O nome do grupo deve ter um máximo de 2 caracteres.',
	'html_maximum' => 'O grupo HTML deve ter no máximo de 1024 caracteres.',
	'select_user_group' => 'O usuário deve estar em um grupo.',
	'uuid_max_32' => 'O UUID deve ter um máximo de 32 caracteres.',
	'cant_delete_root_user' => 'O usuário root não pode ser excluído!',
	'cant_modify_root_user' => 'O grupo root não pode ser modificado.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Configurações Minecraft',
	'use_plugin' => 'Usar o plugin Nameless Minecraft?',
	'force_avatars' => 'Forçar uso de avatares Minecraft?',
	'uuid_linking' => 'Ativar vinculação UUID?',
	'use_plugin_help' => 'Usando o plugin você terá a sincronização com o ranking, cadastro in-game e envios de tickets.',
	'uuid_linking_help' => 'Se desativado, os usuários cadastrados não terão vínculo com suas UUIDs. É altamente recomendado que você deixe essa opção ativada.',
	'plugin_settings' => 'Configurações do Plugin',
	'confirm_api_regen' => 'Você tem certeza de que deseja gerar uma nova API key?',
	'servers' => 'Servidores',
	'new_server' => 'Novo Servidor',
	'confirm_server_deletion' => 'Você tem certeza de que deseja excluir esse servidor?',
	'main_server' => 'Servidor Principal',
	'main_server_help' => 'O servidor em que os jogadores se conectam. Normalmente esta será a instância Bungee.',
	'choose_a_main_server' => 'Selecione o servidor principal..',
	'external_query' => 'Usar uma query externa?',
	'external_query_help' => 'Usar uma API externa para consultar o servidor Minecraft? Somente utilize essa opção caso a principal não funcione corretamente. É extremamente recomendável manter esta opção desmarcada.',
	'editing_server' => 'Editar servidor {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'IP do servidor (com a porta) (numérico ou domínio)',
	'server_ip_with_port_help' => 'Este é o IP que será exibido para os usuários. Não vai ser consultado.',
	'server_ip_numeric' => 'IP do servidor (com a porta) (somente numérico)',
	'server_ip_numeric_help' => 'Este é o IP que será consultado, certifique-se de que é apenas numérico. Ele não será exibido para os usuários.',
	'show_on_play_page' => 'Exibir na página Play?',
	'pre_17' => 'Versão do Minecraft pré 1.7?',
	'server_name' => 'Nome do Servidor',
	'invalid_server_id' => 'ID do servidor inválido',
	'show_players' => 'Exibir a lista de jogadores na página Play?',
	'server_edited' => 'Servidor editado com sucesso',
	'server_created' => 'Servidor criado com sucesso',
	'query_errors' => 'Erros Query',
	'query_errors_info' => 'Os erros a seguir permitem que você identifique problemas com sua consulta ao servidor interno.',
	'no_query_errors' => 'Sem erros de consulta registrados',
	'date' => 'Data:',
	'port' => 'Porta:',
	'viewing_error' => 'Visualizando Erro',
	'confirm_error_deletion' => 'Você tem certeza que deseja excluir esse erro?',
	'display_server_status' => 'Exibir módulo de status do servidor?',
	'server_name_required' => 'Você precisa inserir um nome para o servidor.',
	'server_ip_required' => 'Você precisa inserir o IP do servidor.',
	'server_name_minimum' => 'O nome do servidor precisa ter no mínimo 2 caracteres',
	'server_ip_minimum' => 'O IP do servidor precisa ter no mínimo 2 caracteres',
	'server_name_maximum' => 'O nome do servidor precisa ter no máximo 20 caracteres',
	'server_ip_maximum' => 'O IP do servidor precisa ter no máximo 64 caracteres',
	'purge_errors' => 'Eliminar Erros',
	'confirm_purge_errors' => 'Você tem certeza de que deseja eliminar todos os erros de query?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Temas',
	'templates' => 'Templates',
	'installed_themes' => 'Temas instalados',
	'installed_templates' => 'Templates instalados',
	'installed_addons' => 'Addons instalados',
	'install_theme' => 'Instalar Tema',
	'install_template' => 'Instalar Template',
	'install_addon' => 'Instalar Addon',
	'install_a_theme' => 'Instalar um tema',
	'install_a_template' => 'Instalar um template',
	'install_an_addon' => 'Instalar um addon',
	'active' => 'Ativo',
	'activate' => 'Ativar',
	'deactivate' => 'Desativar',
	'theme_install_instructions' => 'Por favor, faça upload de temas para o diretório <strong>styles/themes</strong>. Após, clique no botão "scan" abaixo.',
	'template_install_instructions' => 'Por favor, faça upload de templates para o diretório <strong>styles/templates</strong>. Após, clique no botão "scan" abaixo.',
	'addon_install_instructions' => 'Por favor, faça upload de addons para o diretório <strong>addons</strong>. Após, clique no botão "scan" abaixo.',
	'addon_install_warning' => 'Addons são instalados por sua conta e risco. Por favor, faça o backup de seus arquivos/database antes de continuar.',
	'scan' => 'Scan',
	'theme_not_exist' => 'Esse tema não existe!',
	'template_not_exist' => 'Esse template não existe!',
	'addon_not_exist' => 'Esse addon não existe!',
	'style_scan_complete' => 'Concluído, quaisquer novos estilos foram instalados.',
	'addon_scan_complete' => 'Concluído, quaisquer novos addons foram instalados.',
	'theme_enabled' => 'Tema ativado.',
	'template_enabled' => 'Template ativado.',
	'addon_enabled' => 'Addon ativado.',
	'theme_deleted' => 'Tema excluído.',
	'template_deleted' => 'Template excluído.',
	'addon_disabled' => 'Addon desativado.',
	'inverse_navbar' => 'Barra de navegação inversa',
	'confirm_theme_deletion' => 'Tem certeza de que deseja excluir o tema <strong>{x}</strong>?<br /><br />O tema será apagado do seu diretório <strong>styles/themes</strong>.', // Don't replace {x}
	'confirm_template_deletion' => 'Tem certeza de que deseja excluir o template <strong>{x}</strong>?<br /><br />O template será apagado do seu diretório <strong>styles/templates</strong>.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Outras Configurações',
	'enable_error_reporting' => 'Ativar relatório de erros?',
	'error_reporting_description' => 'Isso só deve ser usado para fins de depuração, é altamente recomendável deixar esta opção desativada.',
	'display_page_load_time' => 'Exibir carregamento da página?',
	'page_load_time_description' => 'Com este recurso ativado será exibido um velocímetro no rodapé que irá exibir o tempo de carregamento da página.',
	'reset_website' => 'Redefinir Website',
	'reset_website_info' => 'Isto irá repor as definições do site. <strong>Addons serão desativados mas não removidos, e suas configurações não mudarão.</strong> Seus servidores de Minecraft definidos também permanecerão.',
	'confirm_reset_website' => 'Você tem certeza de que deseja redefinir as configurações do website?',
	
	// Admin Update page
	'installation_up_to_date' => 'Sua instalação está atualizada.',
	'update_check_error' => 'Não foi possível verificar se há atualizações. Por favor, tente novamente mais tarde.',
	'new_update_available' => 'Uma nova atualização está disponível.',
	'your_version' => 'Sua versão:',
	'new_version' => 'Nova versão:',
	'download' => 'Download',
	'update_warning' => 'Aviso: Certifique-se de que você tenha baixado o pacote e enviado os arquivos contidos em primeiro lugar!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Início',
	'play' => 'Jogar',
	'forum' => 'Fórum',
	'more' => 'Mais',
	'staff_apps' => 'Recrutamentos',
	'view_messages' => 'Ver Mensagens',
	'view_alerts' => 'Ver Alertas',
	
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
	'create_an_account' => 'Criar uma Conta',
	'authme_password' => 'Senha AuthMe',
	'username' => 'Usuário',
	'minecraft_username' => 'Usuário Minecraft',
	'email' => 'Email',
	'email_address' => 'Endereço de email',
	'date_of_birth' => 'Data de Nascimento',
	'location' => 'Localização',
	'password' => 'Senha',
	'confirm_password' => 'Confirme a Senha',
	'i_agree' => 'Eu Aceito',
	'agree_t_and_c' => 'Clicando em <strong class="label label-primary">Cadastrar</strong>, você aceita nossos <a href="#" data-toggle="modal" data-target="#t_and_c_m">Termos e Condições</a>.',
	'register' => 'Cadastrar',
	'sign_in' => 'Entrar',
	'sign_out' => 'Sair',
	'terms_and_conditions' => 'Termos e Condições',
	'successful_signin' => 'Olá, bem-vindo!',
	'incorrect_details' => 'Informações incorretas',
	'remember_me' => 'Lembrar-me',
	'forgot_password' => 'Esqueci minha senha',
	'must_input_username' => 'Você precisa informar um usuário.',
	'must_input_password' => 'Você precisa informar uma senha.',
	'inactive_account' => 'Sua conta atualmente está inativa. Você solicitou um reset de senha?',
	'account_banned' => 'Sua conta está banida.',
	'successfully_logged_out' => 'Você se deslogou com sucesso. Até mais!',
	'signature' => 'Assinatura',
	'registration_check_email' => 'Por favor, verifique seu email para validar seu cadastro. Você está impossibilitado de entrar até que o mesmo seja validado.',
	'unknown_login_error' => 'Desculpe, ocorreu um erro desconhecido ao processar seu login. Tente novamente mais tarde.',
	'validation_complete' => 'Obrigado por se cadastrar! Você pode se logar agora.',
	'validation_error' => 'Erro ao processar seu pedido. Tente clicar no link novamente.',
	'registration_error' => 'Certifique-se de ter preenchido todos os campos, e que seu nome de usuário possua entre 3 e 20 caracteres e sua senha possua entre 6 e 30 caracteres.',
	'username_required' => 'Por favor, informe um usuário.',
	'password_required' => 'Por favor, informe uma senha.',
	'email_required' => 'Por favor, informe um endereço de email.',
	'mcname_required' => 'Por favor, informe um usuário Minecraft.',
	'accept_terms' => 'Você precisa aceitar os termos e condições antes de prosseguir.',
	'invalid_recaptcha' => 'Código reCAPTCHA inválido.',
	'username_minimum_3' => 'Seu nome de usuário deve ter um mínimo de 3 caracteres.',
	'username_maximum_20' => 'Seu nome de usuário deve ter um máximo de 20 caracteres.',
	'mcname_minimum_3' => 'Seu nome de usuário Minecraft deve ter um mínimo de 3 caracteres.',
	'mcname_maximum_20' => 'Seu nome de usuário Minecraft deve ter um máximo de 20 caracteres.',
	'password_minimum_6' => 'Sua senha deve ter pelo menos 6 caracteres.',
	'password_maximum_30' => 'Sua senha deve ter um máximo de 30 caracteres.',
	'passwords_dont_match' => 'Suas senhas não coincidem.',
	'username_mcname_email_exists' => 'Seu usuário, usuário Minecraft ou endereço de email já existe. Tem certeza de que já não criou uma conta?',
	'invalid_mcname' => 'Seu nome de usuário Minecraft não é válido.',
	'mcname_lookup_error' => 'Ouve um erro ao se comunicar com servidores da Mojang. Tente novamente mais tarde.',
	'signature_maximum_900' => 'Sua assinatura deve possuir no máximo de 900 caracteres.',
	'invalid_date_of_birth' => 'Data de nascimento inválida.',
	'location_required' => 'Por favor, informe uma localização.',
	'location_minimum_2' => 'Sua localização deve ter um mínimo de 2 caracteres.',
	'location_maximum_128' => 'Sua localização deve ter um máximo de 128 caracteres.',
	
	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => 'Nenhum arquivo selecionado',
	'private_messages' => 'Mensagens Privadas',
	'profile_settings' => 'Configurações de Perfil',
	'your_profile' => 'Seu Perfil',
	'topics' => 'Tópicos',
	'posts' => 'Postagens',
	'reputation' => 'Reputação',
	'friends' => 'Amigos',
	'alerts' => 'Alertas',
	
	// Messaging
	'new_message' => 'Nova Mensagem',
	'no_messages' => 'Nenhuma mensagem',
	'and_x_more' => 'e mais {x}', // Don't replace "{x}"
	'system' => 'Sistema',
	'message_title' => 'Título da Mensagem',
	'message' => 'Mensagem',
	'to' => 'Para:',
	'separate_users_with_comma' => 'Separe os usuários com a vírgula (",")',
	'viewing_message' => 'Visualizar Mensagem',
	'delete_message' => 'Excluir Mensagem',
	'confirm_message_deletion' => 'Você tem certeza de que deseja excluir essa mensagem?',
	
        // Profile settings
	'display_name' => 'Nome de exibição',
	'upload_an_avatar' => 'Enviar um avatar (somente .jpg, .png ou .gif):',
	'use_gravatar' => 'Usar Gravatar?',
	'change_password' => 'Alterar senha',
	'current_password' => 'Senha atual',
	'new_password' => 'Nova senha',
	'repeat_new_password' => 'Repita a nova senha',
	'password_changed_successfully' => 'Senha alterada com sucesso',
	'incorrect_password' => 'Sua senha atual está incorreta',
	'update_minecraft_name_help' => 'Isto irá atualizar o seu nome de usuário no site para o seu atual nome de usuário Minecraft. Você só pode executar esta ação uma vez a cada 30 dias.',
	'unable_to_update_mcname' => 'Não foi possível atualizar seu nome de usuário Minecraft',
	'display_age_on_profile' => 'Exibir idade no perfil?',
	'two_factor_authentication' => 'Dupla Autenticação',
	'enable_tfa' => 'Ativar Dupla Autenticação',
	'tfa_type' => 'Tipo da Autenticação Dupla:',
	'authenticator_app' => 'App de Autenticação',
	'tfa_scan_code' => 'Por favor verificar o seguinte código no seu aplicativo de autenticação:',
	'tfa_code' => 'Se o seu dispositivo não tiver uma câmera, ou você não consegue ler o código QR, por favor insira o seguinte código:',
	'tfa_enter_code' => 'Por favor entre com o código em exibição com o seu aplicativo de autenticação:',
	'invalid_tfa' => 'Código inválido, tente novamente.',
	'tfa_successful' => 'Dupla Autenticação configurada com êxito. Você vai precisar se autenticar toda vez que você entrar a partir de agora em diante.',
	'confirm_tfa_disable' => 'Você tem certeza que deseja desativar a dupla autenticação?',
	'tfa_disabled' => 'Dupla autenticação desativada.',
	'tfa_enter_email_code' => 'Nós enviamos um código com um email para verificação. Por favor digite o código agora:',
	'tfa_email_contents' => 'Uma tentativa de login foi feita em sua conta. Se foi você, por favor informe a dupla autenticação quando for solicitada. Se não foi você, você pode ignorar este e-mail, no entanto, uma redefinição de senha é aconselhada. O código é válido apenas por 10 minutos.',
	
	// Alerts
	'viewing_unread_alerts' => 'Visualizando alertas não lidos. Alterar para <a href="/user/alerts/?view=read"><span class="label label-success">lido</span></a>.',
	'viewing_read_alerts' => 'Visualizando alertas lidos. Alterar para <a href="/user/alerts/"><span class="label label-warning">não lido</span></a>.',
	'no_unread_alerts' => 'Você não tem nenhum alerta para ler.',
	'no_alerts' => 'Nenhum alerta',
	'no_read_alerts' => 'Você não tem nenhum alerta lido.',
	'view' => 'Ver',
	'alert' => 'Alerta',
	'when' => 'Quando',
	'delete' => 'Excluir',
	'tag' => 'Tag do usuário',
	'tagged_in_post' => 'Você foi marcado em um post',
	'report' => 'Reportar',
	'deleted_alert' => 'Alerta excluído com sucesso',
	
	// Warnings
	'you_have_received_a_warning' => 'Você recebeu um novo alerta de {x} dia {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Reconhecer', //Acknowledge
	
	// Forgot password
	'password_reset' => 'Resetar Senha',
	'email_body' => 'Você está recebendo este email porque solicitou um resete de senha. A fim de redefinir sua senha, utilize o seguinte link:', // Body for the password reset email
	'email_body_2' => 'Se você não solicitou uma redefinição de senha, ignore esse email.',
	'password_email_set' => 'Concluído. Verifique seu email para ações futuras.',
	'username_not_found' => 'Esse usuário não existe.',
	'change_password' => 'Alterar Senha',
	'your_password_has_been_changed' => 'Sua senha foi alterada.',
	
	// Profile page
	'profile' => 'Perfil',
	'player' => 'Jogador',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Cadastrado:',
	'pf_posts' => 'Postagens:',
	'pf_reputation' => 'Reputação:',
	'user_hasnt_registered' => 'Esse usuário não está cadastrado em seu site ainda',
	'user_no_friends' => 'Esse usuário não adicionou nenhum amigo',
	'send_message' => 'Enviar Mensagem',
	'remove_friend' => 'Remover Amigo',
	'add_friend' => 'Add Amigo',
	'last_online' => 'Última vez online:',
	'find_a_user' => 'Encontrar um usuário',
	'user_not_following' => 'Esse usuário não segue ninguém.',
	'user_no_followers' => 'Esse usuário não possui seguidores.',
	'following' => 'SEGUINDO',
	'followers' => 'SEGUIDORES',
	'display_location' => 'De {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x} anos, de {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Escreva algo no perfil de {x}...', // Don't replace {x}
	'write_on_own_profile' => 'Escreva alguma coisa no seu perfil...',
	'profile_posts' => 'Postagens no Perfil',
	'no_profile_posts' => 'Nenhuma postagem ainda.',
	'invalid_wall_post' => 'Postagem inválida. Verifique se sua postagem possui entre 2 e 2048 caracteres.',
	'about' => 'Sobre',
	'reply' => 'Responder',
	'x_likes' => '{x} curtidas', // Don't replace {x}
	'likes' => 'Curtidas',
	'no_likes' => 'Nenhuma curtida.',
	'post_liked' => 'Postagens curtidas.',
	'post_unliked' => 'Postagens reprovadas.',
	'no_posts' => 'Nenhuma postagem.',
	'last_5_posts' => 'Últimas 5 Postagens',
	'follow' => 'Follow',
	'unfollow' => 'Unfollow',
	
	// Staff applications
	'staff_application' => 'Entre na Staff',
	'application_submitted' => 'Formulário enviado com sucesso.',
	'application_already_submitted' => 'Você enviou um formulário. Por favor, aguarde até que seja concluído antes de enviar outro.',
	'not_logged_in' => 'Por favor, faça login para visualizar essa página.',
	'application_accepted' => 'Seu pedido para ingressar-se na Staff foi aceito.',
	'application_rejected' => 'Seu pedido para ingressar-se na Staff foi rejeitado.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Visão Geral',
	'reports' => 'Relatórios',
	'punishments' => 'Punições',
	'staff_applications' => 'Recrutamentos para Staff',
	
	// Punishments
	'ban' => 'Banir',
	'unban' => 'Desbanir',
	'warn' => 'Alertar',
	'search_for_a_user' => 'Procurar por usuário',
	'user' => 'Usuário:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registrado',
	'reason' => 'Razão:',
	
	// Reports
	'report_closed' => 'Parecer fechado.',
	'new_comment' => 'Novo comentário',
	'comments' => 'Comentários',
	'only_viewed_by_staff' => 'Apenas pode ser visto pela staff',
	'reported_by' => 'Reportado por',
	'close_issue' => 'Fechar problema',
	'report' => 'Parecer:',
	'view_reported_content' => 'Ver conteúdo reportado',
	'no_open_reports' => 'Nenhum parecer aberto',
	'user_reported' => 'Usuário Reportado',
	'type' => 'Tipo',
	'updated_by' => 'Atualizado Por',
	'forum_post' => 'Postagem do Fórum',
	'user_profile' => 'Perfil de Usuário',
	'comment_added' => 'Comentário Add.',
	'new_report_submitted_alert' => 'Novo parecer enviado por {x} em relação ao usuário {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Por favor, verifique se seu comentário possui de 2 à 2048 caracteres.',
	'viewing_open_applications' => 'Visualizando formulários <span class="label label-info">abertos</span>. Alterar para <a href="/mod/applications/?view=accepted"><span class="label label-success">aceito</span></a> ou <a href="/mod/applications/?view=declined"><span class="label label-danger">rejeitado</span></a>.',
	'viewing_accepted_applications' => 'Visualizando formulários <span class="label label-success">aceitos</span>. Alterar para <a href="/mod/applications/"><span class="label label-info">aberto</span></a> ou <a href="/mod/applications/?view=declined"><span class="label label-danger">rejeitado</span></a>.',
	'viewing_declined_applications' => 'Visualizando formulários <span class="label label-danger">rejeitados</span>. Alterar para <a href="/mod/applications/"><span class="label label-info">aberto</span></a> ou <a href="/mod/applications/?view=accepted"><span class="label label-success">aceito</span></a>.',
	'time_applied' => 'Tempo Aplicado',
	'no_applications' => 'Nenhum pedido nesta categoria',
	'viewing_app_from' => 'Visualizando pedido de {x}', // Don't replace "{x}"
	'open' => 'Abrir',
	'accepted' => 'Aceito',
	'declined' => 'Rejeitado',
	'accept' => 'Aceitar',
	'decline' => 'Rejeitar',
	'new_app_submitted_alert' => 'Novo pedido enviado por {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Notícias',
	'social' => 'Social',
	'join' => 'Entrar',
	
	// General terms
	'submit' => 'Enviar',
	'close' => 'Fechar',
	'cookie_message' => '<strong>Este site usa cookies para melhorar a sua experiência.</strong><p>Ao continuar a navegar e interagir com o site, você concorda com a sua utilização.</p>',
	'theme_not_exist' => 'O tema selecionado não existe.',
	'confirm' => 'Confirmar',
	'cancel' => 'Cancelar',
	'guest' => 'Visitante',
	'guests' => 'Visitantes',
	'back' => 'Voltar',
	'search' => 'Procurar',
	'help' => 'Ajuda',
	'success' => 'Sucesso',
	'error' => 'Error',
	'view' => 'Ver',
	'info' => 'Info',
	'next' => 'Próximo',
	
	// Play page
	'connect_with' => 'Junte-se aos nossos jogadores: {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Jogadores Online:',
	'queried_in' => 'Atualizado em:',
	'server_status' => 'Server Status',
	'no_players_online' => 'Não há jogadores online!',
	'x_players_online' => 'Há {x} jogadores online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Página carregada em {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Nenhum',
	'404' => 'Desculpe, nós não encontramos essa página.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Fóruns',
	'discussion' => 'Tópicos',
	'stats' => 'Estatísticas',
	'last_reply' => 'Última Resposta',
	'ago' => 'atrás',
	'by' => 'por',
	'in' => 'em',
	'views' => 'visualizações',
	'posts' => 'postagens',
	'topics' => 'tópicos',
	'topic' => 'Tópico',
	'statistics' => 'Estatísticas',
	'overview' => 'Visão Geral',
	'latest_discussions' => 'Últimos Tópicos',
	'latest_posts' => 'Últimos Posts',
	'users_registered' => 'Usuários cadastrados:',
	'latest_member' => 'Último membro:',
	'forum' => 'Fórum',
	'last_post' => 'Última Postagem',
	'no_topics' => 'Não há tópicos aqui ainda',
	'new_topic' => 'Novo Tópico',
	'subforums' => 'Subfóruns:',
	
	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Tópico Trancado',
	'new_reply' => 'Nova Resposta',
	'mod_actions' => 'Moderar Tópico',
	'lock_thread' => 'Trancar Tópico',
	'unlock_thread' => 'Destrancar Tópico',
	'merge_thread' => 'Mesclar Tópico',
	'delete_thread' => 'Deletar Tópico',
	'confirm_thread_deletion' => 'Você tem certeza de que deseja apagar este tópico?',
	'move_thread' => 'Mover Tópico',
	'sticky_thread' => 'Destacar Tópico',
	'report_post' => 'Reportar Postagem',
	'quote_post' => 'Citar Postagem',
	'delete_post' => 'Deletar Postagem',
	'edit_post' => 'Editar Postagem',
	'reputation' => 'reputação',
	'confirm_post_deletion' => 'Você tem certeza de que deseja apagar esta postagem?',
	'give_reputation' => 'Dar reputação',
	'remove_reputation' => 'Remover reputação',
	'post_reputation' => 'Reputação da Postagem',
	'no_reputation' => 'Não há reputação para esta postagem ainda',
	're' => 'RE:',
	
	// Create post view
	'create_post' => 'Criar postagem',
	'post_submitted' => 'Postagem enviada',
	'creating_post_in' => 'Criando postagem em: ',
	'topic_locked_permission_post' => 'Este tópico está trancado, no entanto suas permissões permitem que você poste nele',
	
	// Edit post view
	'editing_post' => 'Editando postagem',
	
	// Sticky threads
	'thread_is_' => 'Tópico é ',
	'now_sticky' => 'agora um tópico destacado',
	'no_longer_sticky' => 'não é mais um tópico destacado',
	
	// Create topic
	'topic_created' => 'Tópico criado.',
	'creating_topic_in_' => 'Criando tópico no fórum ',
	'thread_title' => 'Título do Tópico',
	'confirm_cancellation' => 'Você tem certeza?',
	'label' => 'Rótulo',
	
	// Reports
	'report_submitted' => 'Reporte enviado.',
	'view_post_content' => 'Ver conteúdo do post',
	'report_reason' => 'Motivo do Reporte',
	
	// Move thread
	'move_to' => 'Mover para:',
	
	// Merge threads
	'merge_instructions' => 'O tópico que será mesclado <strong>precisa</strong> estar no mesmo fórum. Mova o tópico se necessário.',
	'merge_with' => 'Mesclar com:',
	
	// Other
	'forum_error' => 'Desculpe, nós não conseguimos encontrar este tópico ou post.',
	'are_you_logged_in' => 'Você está logado?',
	'online_users' => 'Usuários Online',
	'no_users_online' => 'Não há usuários on-line.',
	
	// Search
	'search_error' => 'Por favor, informe de 1 à 32 caracteres para pesquisar.',
	
	//Share on a social-media.
	'sm-share' => 'Compartilhar',
	'sm-share-facebook' => 'Compartilhar no Facebook',
	'sm-share-twitter' => 'Compartilhar no Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Olá',
	'message' => 'Obrigado por se cadastrar! Para continuar com seu cadastro, clique no link a seguir para validar seus dados:',
	'thanks' => 'Obrigado,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'a menos de um minuto atrás',
	'1_minute' => '1 minuto atrás',
	'_minutes' => '{x} minutos atrás',
	'about_1_hour' => 'cerca de 1 hora atrás',
	'_hours' => '{x} horas atrás',
	'1_day' => '1 dia atrás',
	'_days' => '{x} dias atrás',
	'about_1_month' => 'cerca de 1 mês atrás',
	'_months' => '{x} meses atrás',
	'about_1_year' => 'cerca de 1 ano atrás',
	'over_x_years' => 'mais de {x} anos atrás'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Exibir registros _MENU_ por página ', // Don't replace "_MENU_"
	'nothing_found' => 'Nenhum resultado encontrado',
	'page_x_of_y' => 'Exibindo página _PAGE_ de _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Nenhum registro disponível',
	'filtered' => '(filtrado de _MAX_ registros totais)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Cadastro Completo'
);
 
?>
