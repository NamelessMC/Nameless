<?php

// Portuguese

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Ativar integração com o Discord?',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'O Role ID do discord deve ser numérico.',
    'discord_role_id_length' => 'O Role ID do Discord deve ter 18 dígitos.',
    'discord_guild_id' => 'ID do Servidor do Discord',
    'discord_widget_theme' => 'Tema do Widget do Discord',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Certifique-se de que seu ID do Discord tenha 18 caracteres.',
    'discord_id_numeric' => 'Certifique-se de que o seu ID Discord seja numérico (apenas números).',
    'discord_invite_info' => 'To invite the Nameless Link bot to your Discord server, click <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. Then, run the <code>/apiurl</code> command to link the bot with your website. Alternatively, you can <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'Não é possível ativar a integração do Discord até que você configure o bot. Para informações, por favor <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">clique aqui</a>.',
    'discord_bot_setup' => 'Configuração do bot',
    'discord_integration_not_setup' => 'A integração do Discord não está configurada',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Corpo de solicitação inválido.',
    'discord_bot_error_error' => 'Ocorreu um erro do bot interno.',
    'discord_bot_error_invguild' => 'O Guild ID fornecido é inválido ou o bot não está nele.',
    'discord_bot_error_invuser' => 'A ID de usuário fornecida é inválida ou não está na Guilda especificada.',
    'discord_bot_error_notlinked' => 'O bot não está vinculado a este site para fornecer a Guild ID.',
    'discord_bot_error_unauthorized' => 'A chave da API do site é inválida',
    'discord_bot_error_invrole' => 'A Role ID fornecida é inválida.',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'A integração do Discord está desativada.',
    'unable_to_set_discord_id' => 'Não foi possível definir o Discord ID.',
    'unable_to_set_discord_bot_url' => 'Não foi possível definir o URL do bot do Discord',
    'provide_one_discord_settings' => 'Forneça pelo menos um dos seguintes: "url", "guild_id"',
    'no_pending_verification_for_token' => 'Não há verificações pendentes no token fornecido.',
    'unable_to_update_discord_username' => 'Não foi possível atualizar o nome de usuário do Discord.',
    'unable_to_update_discord_roles' => 'Não foi possível atualizar a lista de funções do Discord.',
    'unable_to_update_discord_bot_username' => 'Não foi possível atualizar o nome de usuário do bot do Discord.',

    // API Success
    'discord_id_set' => 'Discord ID definido com sucesso',
    'discord_settings_updated' => 'Configurações do Discord atualizadas com sucesso',
    'discord_usernames_updated' => 'Nomes de usuário do Discord atualizados com sucesso',

    // User Settings
    'discord_link' => 'Discord Link',
    'linked' => 'Vinculado',
    'not_linked' => 'Ainda não foi vinculado',
    'discord_id' => 'ID do usuário do Discord',
    'discord_id_unlinked' => 'Desvinculou com sucesso seu ID de usuário do Discord.',
    'discord_id_confirm' => 'Please run the command "/verify {token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pendente',
    'discord_id_taken' => 'Esse ID do Discord já foi obtido.',
    'discord_invalid_id' => 'Esse ID de usuário do Discord é inválido.',
    'discord_already_pending' => 'Você já tem uma verificação pendente.',
    'discord_database_error' => 'O banco de dados Nameless Link está inativo no momento. Por favor, tente novamente mais tarde.',
    'discord_communication_error' => 'Ocorreu um erro ao se comunicar com o Bot do Discord. Certifique-se de que o bot está funcionando e que o URL do bot está correto.',
    'discord_unknown_error' => 'Ocorreu um erro desconhecido ao sincronizar as funções do Discord. Entre em contato com um administrador.',
    'discord_id_help' => 'Para obter informações sobre onde encontrar as IDs do Discord, leia <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">isso.</a>'
];
