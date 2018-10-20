<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
<<<<<<< HEAD
 *  NamelessMC version 2.0.0-pr3
 *
 *  Translation by SimplyRin(@SimplyRin_, https://www.simplyrin.net)
=======
 *  NamelessMC version 2.0.0-pr5
 *
 *  Translation by SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  Additional translation by Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
>>>>>>> upstream/v2
 *
 *  License: MIT
 *
 *  Japanese Language - API
 */

$language = array(
    // Errors
    'unknown_error' => '不明なエラー',
    'invalid_api_key' => '無効な API キー',
<<<<<<< HEAD
    'invalid_language_file' => '言語ファイルが無効です',
    'invalid_api_method' => 'API メソッドが無効です',
    'no_unique_site_id' => 'サイト ID はありません',
    'unable_to_check_for_updates' => '更新を確認できません',
    'invalid_post_contents' => '無効な POST コンテンツ',
    'invalid_get_contents' => '無効な GE Tコンテンツ',
    'invalid_email_address' => '無効なメールアドレス',
    'invalid_username' => '無効なユーザー名',
    'invalid_uuid' => '無効な UUID',
    'email_already_exists' => 'メールは既に存在します',
    'username_already_exists' => 'ユーザー名は既に存在します',
    'uuid_already_exists' => 'UUID は既に存在します',
    'unable_to_create_account' => 'アカウントを作成できません',
    'unable_to_send_registration_email' => '登録メールを受け取れない場合は、管理者に連絡してアカウントを有効にしてください',
    'unable_to_find_user' => 'ユーザーを見つけることができません',
    'unable_to_find_group' => 'グループを見つけることができません',
    'unable_to_update_group' => 'ユーザーのグループを更新できません',
    'report_content_too_long' => 'レポートの内容は 255 文字以下でなければなりません',
    'you_must_register_to_report' => 'レポートを作成するには、ウェブサイトに登録する必要があります',
    'you_have_been_banned_from_website' => 'あなたはウェブサイトから禁止されています',
    'you_have_open_report_already' => 'あなたはすでにこのプレーヤーに関する公開レポートを持っています',
    'unable_to_create_report' => 'レポートを作成できません',
    'unable_to_update_username' => 'ユーザー名を更新できません',
    'unable_to_update_server_info' => 'サーバー情報を更新できません',
    'invalid_server_id' => '無効なサーバーID',
    'invalid_code' => '無効なコードが提供されました',

    // Success messages
    'finish_registration_link' => '登録を完了するには、次のリンクをクリックしてください:',
    'finish_registration_email' => '登録を完了するためにメールを確認してください。',
    'group_updated' => 'グループを正常に更新しました',
    'report_created' => 'レポートが正常に作成されました',
    'new_private_message_from' => '{x} からの新しいプライベートメッセージ', // Don't replace {x}
    'username_updated' => 'ユーザー名が正常に更新されました',
    'server_info_updated' => 'サーバー情報が正常に更新されました'
);
=======
    'invalid_language_file' => '言語ファイルが無効です。',
    'invalid_api_method' => 'API メソッドが無効です。',
    'no_unique_site_id' => 'サイト ID はありません。',
    'unable_to_check_for_updates' => '更新を確認できません。',
    'invalid_post_contents' => '無効な POST コンテンツ',
    'invalid_get_contents' => '無効な GET コンテンツ',
    'invalid_email_address' => '無効な メールアドレス',
    'invalid_username' => '無効な ユーザー名',
    'invalid_uuid' => '無効な UUID',
    'email_already_exists' => '指定されたメールアドレスは既に登録されています。',
    'username_already_exists' => '指定されたユーザー名は既に登録されています。',
    'uuid_already_exists' => '指定されたUUIDは既に登録されています。',
    'unable_to_create_account' => 'アカウントを作成できませんでした。',
    'unable_to_send_registration_email' => '登録確認メールを受け取ることができない場合、管理者に連絡してください。',
    'unable_to_find_user' => '指定されたユーザを見つけることができませんでした。',
    'unable_to_find_group' => '指定されたグループを見つけることができませんでした。',
    'unable_to_update_group' => 'ユーザーのグループを更新できませんでした。',
    'report_content_too_long' => 'レポートの内容は <strong>255文字</strong>　以内にする必要があります。',
    'you_must_register_to_report' => 'レポートを送信するには、サイトにてアカウントを作成する必要があります。',
    'you_have_been_banned_from_website' => 'あなたはウェブサイトにてアカウントがロックされています。',
    'you_have_open_report_already' => '既に指定されたプレイヤーのレポートが存在します。',
    'unable_to_create_report' => 'レポートを作成できませんでした。',
    'unable_to_update_username' => 'ユーザー名を更新できませんでした。',
    'unable_to_update_server_info' => 'サーバー情報を更新できませんでした。',
    'invalid_server_id' => '無効なサーバーID',
    'invalid_code' => '指定されたコードは無効です。',

    // Success messages
    'finish_registration_link' => '登録を完了するには次のリンクをクリックしてください:',
    'finish_registration_email' => '登録を完了するにはメールを確認して処理を完了してください。',
    'group_updated' => 'グループを正常に更新しました。',
    'report_created' => 'レポートが正常に送信されました。',
    'new_private_message_from' => '{x} から新しいプライベートメッセージが届いています。', // Don't replace {x}
    'username_updated' => 'ユーザー名が正常に更新されました。',
    'server_info_updated' => 'サーバー情報が正常に更新されました。',
    'account_validated' => 'アカウントの検証が完了しました。'
);
>>>>>>> upstream/v2
