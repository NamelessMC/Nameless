<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Portuguese Language - Installation
 *  Translation By Douglas Teles, dasilvaj4 and Mansffer
 *  Last Update: 01/03/2021
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Instalar',
    'pre-release' => 'pre-release',
    'installer_welcome' => 'Bem-vindo ao NamelessMC versão 2.0.',
    'pre-release_warning' => 'Por favor, note que esta pre-release não se destina ao uso em um site público.',
    'installer_information' => 'O instalador irá guiá-lo através do processo de instalação.',
    'terms_and_conditions' => 'Ao continuar, você concorda com os termos e condições.',
    'new_installation_question' => 'Em primeiro lugar, esta é uma nova instalação?',
    'new_installation' => 'Nova instalação &raquo;',
    'upgrading_from_v1' => 'Atualizando da v1 &raquo;',
    'requirements' => 'Requisitos:',
    'config_writable' => 'core/config.php é Gravável',
    'cache_writable' => 'Cache é Gravável',
    'template_cache_writable' => 'Template Cache é Gravável',
    'exif_imagetype_banners_disabled' => 'Sem a função exif_imagetype ativa, os banners do servidor serão desativados.',
    'requirements_error' => 'Você deve ter todas as extensões necessárias instaladas e ter as permissões corretas definidas, a fim de prosseguir com a instalação.',
    'proceed' => 'Prosseguir',
    'database_configuration' => 'Configuração do banco de dados',
    'database_address' => 'Endereço do banco de dados',
    'database_port' => 'Porta do banco de dados',
    'database_username' => 'Usuário do banco de dados',
    'database_password' => 'Senha do banco de dados',
    'database_name' => 'Nome do banco de dados',
    'nameless_path' => 'Caminho da instalação',
    'nameless_path_info' => 'Este é o caminho relativo ao seu domínio, onde o Nameless será instalado. Por exemplo, se o Nameless for instalado em example.com/forum, então a configuração será <strong>forum</strong>. Deixe em branco caso não queira instalar em uma sub-pasta.',
    'friendly_urls' => 'URLs Amigáveis',
    'friendly_urls_info' => 'URLs amigáveis possibilitam que suas páginais sejam mais visíveis nos buscadores.<br />Por exemplo: <br /><code>example.com/index.php?route=/forum</code><br />se tornará<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Importante!</strong><br />Seu servidor deverá estar configurado para que funcione corretamente. Você pode ver se é possível ativar essa opção clicando <a href=\'./rewrite_test\' target=\'_blank\' style="color:#2185D0">aqui</a>.</div>',
    'enabled' => 'Ativado',
    'disabled' => 'Desativado',
    'character_set' => 'Conjunto de caracteres',
    'database_engine' => 'Mecanismo de armazenamento do banco de dados',
    'host' => 'Domínio',
    'host_help' => 'O domínio é a <strong>URL</strong> do seu site. Não inclua sub-pastas ou http(s):// aqui!',
    'database_error' => 'Certifique-se de que todos os campos foram preenchidos.',
    'submit' => 'Enviar',
    'installer_now_initialising_database' => 'O instalador agora está inicializando o banco de dados. Isto pode demorar um pouco...',
    'configuration' => 'Configuração',
    'configuration_info' => 'Por favor, insira informações básicas sobre seu site. Esses valores podem ser alterados posteriormente no painel de administração.',
    'configuration_error' => 'Digite um nome de site válido entre 1 e 32 caracteres e endereços de email válidos entre 4 e 64 caracteres.',
    'site_name' => 'Nome do Site',
    'contact_email' => 'Email de Contato',
    'outgoing_email' => 'Email de envio',
    'language' => 'Idioma',
    'initialising_database_and_cache' => 'Inicializando banco de dados e cache, por favor aguarde...',
    'unable_to_login' => 'Não é possível fazer login.',
    'unable_to_create_account' => 'Não é possível criar uma conta',
    'input_required' => 'Digite um nome de usuário, endereço de email e senha válidos.',
    'input_minimum' => 'Certifique-se de que seu nome de usuário tenha no mínimo 3 caracteres, o seu endereço de email deve ter no mínimo 4 caracteres e sua senha deve ter no mínimo 6 caracteres.',
    'input_maximum' => 'Certifique-se de que seu nome de usuário tenha no máximo 20 caracteres e seu endereço de email e senha tenham no máximo 64 caracteres.',
    'email_invalid' => 'O seu email não é válido.',
    'passwords_must_match' => 'Suas senhas devem corresponder.',
    'creating_admin_account' => 'Criando conta de administrador',
    'enter_admin_details' => 'Digite os detalhes da conta de administrador.',
    'username' => 'Usuário',
    'email_address' => 'Endereço de email',
    'password' => 'Senha',
    'confirm_password' => 'Confirmar Senha',
    'upgrade' => 'Atualizar',
    'input_v1_details' => 'Digite os detalhes do banco de dados para sua instalação Nameless v1.',
    'installer_upgrading_database' => 'Por favor, aguarde enquanto o instalador atualiza seu banco de dados...',
    'errors_logged' => 'Os erros foram registrados. Clique em continuar para continuar com a atualização.',
    'continue' => 'Continuar',
    'convert' => 'Converter',
    'convert_message' => 'Finalmente, você deseja converter de um software de fórum diferente?',
    'yes' => 'Sim',
    'no' => 'Não',
    'converter' => 'Converter',
    'back' => 'Voltar',
    'unable_to_load_converter' => 'Falha ao carregar o conversor!',
    'finish' => 'Terminar',
    'finish_message' => 'Obrigado por instalar o NamelessMC! Agora você pode prosseguir para o Painel de Controle, onde você pode configurar seu site.',
    'support_message' => 'Se você precisar de qualquer suporte, consulte o nosso site <a href="https://namelessmc.com" target="_blank">aqui</a>, ou você também pode visitar nosso <a href="https://discord.gg/nameless" target="_blank">servidor Discord</a> ou nosso <a href="https://github.com/NamelessMC/Nameless/" target="_blank">repositório GitHub</a>.',
    'credits' => 'Créditos',
    'credits_message' => 'Um grande agradecimento a todos os <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">colaboradores do NamelessMC</a> desde 2014',

    'step_home' => 'Início',
    'step_requirements' => 'Requisitos',
    'step_general_config' => 'Configuração Geral',
    'step_database_config' => 'Configuração do banco de dados',
    'step_site_config' => 'Configuração do Site',
    'step_admin_account' => 'Conta de administrador',
    'step_conversion' => 'Conversão',
    'step_finish' => 'Finalizar',

    'general_configuration' => 'Configuração Geral',
    'reload' => 'Recarregar',
    'reload_page' => 'Recarregar a página',
    'no_converters_available' => 'Não há conversores disponíveis.',
    'config_not_writable' => 'O arquivo de configuração não é gravável.',

    'session_doesnt_exist' => 'Não foi possível detectar a sessão. Salvar sessões é um requisito para usar o Nameless. Tente novamente e, se o problema persistir, entre em contato com sua hospedagem para obter suporte.'
);
