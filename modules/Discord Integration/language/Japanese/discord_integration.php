<?php

/*
 *  Translator
 *  - snake( @ViaSnake, https://github.com/ViaSnake )
 *
 *  Japanese Language
 */

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Discordの連携を有効にしますか？',
    'discord_role_id' => 'Discord ロールID',
    'discord_role_id_numeric' => 'Discord ロールIDは数値でなければなりません。',
    'discord_role_id_length' => 'Discord ロールIDは18桁でなければなりません。',
    'discord_guild_id' => 'DiscordサーバーID',
    'discord_widget_theme' => 'Discord ウィジェットテーマ',
    'discord_widget_disabled' => 'The widget is disabled for the specified Discord server. Please go to the \'Widget\' tab in your Discord server settings, and ensure the Discord widget is enabled and that the ID is correct.',
    'discord_id_length' => 'Discord IDは18文字以内にしてください。',
    'discord_id_numeric' => 'Discord IDが数字であることを確認してください(数字のみ)',
    'discord_invite_info' => 'Nameless LinkボットをDiscordサーバーに招待するには、<a target="_blank" href="https://namelessmc.com/discord-bot-invite">ここ</a>をクリックしてください。次に、<code>/apiurl</code>コマンドを実行して、ボットをあなたのウェブサイトにリンクさせます。また、<a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">自分でボットをホストすることもできます</a>。',
    'discord_bot_must_be_setup' => 'ボットを設定するまでは、Discord統合を有効にすることはできません。詳細については、<a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">ここをクリック</a>してください。',
    'discord_bot_setup' => 'ボットの設定',
    'discord_integration_not_setup' => 'Discord統合が設定されていません',
    'discord_username' => 'Discord Username',

    // Discord bot Errors
    'discord_bot_error_badparameter' => '無効なリクエストボディです。',
    'discord_bot_error_error' => 'ボットの内部エラーが発生しました。',
    'discord_bot_error_invguild' => '提供されたギルドIDが無効であるか、ボットが入っていません。',
    'discord_bot_error_invuser' => '提供されたユーザーIDが無効であるか、指定されたギルドに入っていません。',
    'discord_bot_error_notlinked' => '提供されたギルドIDに対して、ボットはウェブサイトにリンクされていません。',
    'discord_bot_error_unauthorized' => 'ウェブサイトのAPIキーが無効',
    'discord_bot_error_invrole' => '提供されたロールIDが無効です。',
    'discord_bot_error_partsuccess' => 'Discordのロール階層の誤設定により、ボットが1つ以上のロールを編集できませんでした。',

    // API Errors
    'discord_integration_disabled' => 'Discordの連携は無効になっています。',
    'unable_to_set_discord_id' => 'Discord IDを設定できません。',
    'unable_to_set_discord_bot_url' => 'DiscordボットのURLが設定できません。',
    'provide_one_discord_settings' => '次の項目のうち、少なくとも1つをご記入ください: "URL"、"guild_id"',
    'no_pending_verification_for_token' => '提供されたトークンの下で保留中の検証はありません。',
    'unable_to_update_discord_username' => 'Discordのユーザー名を更新できません。',
    'unable_to_update_discord_roles' => 'Discordのロールリストを更新できません。',
    'unable_to_update_discord_bot_username' => 'Discordボットのユーザー名を更新できません。',

    // API Success
    'discord_id_set' => 'Discord IDの設定に成功しました。',
    'discord_bot_url_updated' => 'DiscordボットのURLが更新されました。',
    'discord_usernames_updated' => 'Discordユーザー名の更新に成功',

    // User Settings
    'discord_link' => 'Discord リンク',
    'linked' => 'リンク済み',
    'not_linked' => 'リンクされていません',
    'discord_id' => 'DiscordユーザーID',
    'discord_id_unlinked' => 'Discord ユーザーIDのリンクを解除することに成功しました。',
    'discord_id_confirm' => 'Discordで「/verify {トークン}」というコマンドを実行して、Discordアカウントのリンクを完了させてください。',
    'pending_link' => '保留中',
    'discord_id_taken' => 'そのDiscord IDはすでに取られています。',
    'discord_invalid_id' => 'そのDiscord ユーザーIDは無効です。',
    'discord_already_pending' => 'すでに検証が保留されています。',
    'discord_database_error' => 'Nameless Link データベースが現在ダウンしています。後でもう一度お試しください。',
    'discord_communication_error' => 'Discordボットとの通信中にエラーが発生しました。ボットが動作しているかどうか、ボットのURLが正しいかどうかを確認してください。',
    'discord_unknown_error' => 'Discordロールの同期中に不明なエラーが発生しました。管理者に連絡してください。',
    'discord_id_help' => 'Discord IDがどこにあるかについては、<a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">こちら</a>をご確認ください。'
];
