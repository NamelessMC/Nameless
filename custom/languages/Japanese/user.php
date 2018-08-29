<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  Translation by SimplyRin(@SimplyRin_, https://www.simplyrin.net)
 *
 *  License: MIT
 *
 *  Japanese Language - Users
 */

$language = array(
	/*
	 *  Change this for the account validation message
	 */
	'validate_account_command' => '登録を完了するには、ゲーム内で <strong>/validate {x}</strong> コマンドを送信してください。', // Don't replace {x}

	/*
	 *  User Related
	 */
	'guest' => 'ゲスト',
	'guests' => 'ゲスト',
	
	// UserCP
	'user_cp' => 'UserCP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => '概要',
	'user_details' => 'ユーザーの詳細',
	'profile_settings' => 'プロファイルの設定',
	'successfully_logged_out' => 'あなたは正常にログアウトされました。',
	'messaging' => 'メッセージング',
	'click_here_to_view' => 'クリックして表示',
	'moderation' => 'モデレーション',
	'administration' => '管理',
	'alerts' => 'アラート',
	'delete_all' => 'すべて削除',
	'private_profile' => 'Private profile',
	
	// Profile settings
	'field_is_required' => '{x} は必須です。', // Don't replace {x}
	'settings_updated_successfully' => '設定が正常に更新されました。',
	'password_changed_successfully' => 'パスワードが正常に変更されました。',
	'change_password' => 'パスワードの変更',
	'current_password' => '現在のパスワード',
	'new_password' => '新しいパスワード',
	'confirm_new_password' => '新しいパスワードを再入力',
	'incorrect_password' => 'パスワードが間違っています。',
	'two_factor_auth' => '2段階認証',
	'enabled' => 'Enabled',
    'disabled' => 'Disabled',
	'enable' => '有効',
	'disable' => '無効',
	'tfa_scan_code' => '認証アプリ内で次のコードをスキャンしてください:',
	'tfa_code' => 'お使いのデバイスにカメラがない、または、QR コードをスキャンすることができる場合は、次のコードを入力してください。',
	'tfa_enter_code' => '認証アプリに表示されるコードを入力してください:',
	'invalid_tfa' => '無効なコード、もう一度やり直してください。',
	'tfa_successful' => '二段階認証が設定されました。今からログインするたびに二段階認証をする必要があります。',
	'active_language' => '有効な言語',
	'timezone' => 'タイムゾーン',
	'upload_new_avatar' => '新しいアバターをアップロードする',
	'nickname_already_exists' => 'Your chosen nickname already exists.',
	'change_email_address' => 'Change Email Address',
	'email_already_exists' => 'The email address you have entered already exists.',
	'email_changed_successfully' => 'Email address changed successfully.',
	'avatar' => 'Avatar',
	
	// Alerts
	'user_tag_info' => 'あなたは {x} の投稿にタグがついています。', // Don't replace {x}
	'no_alerts' => '新しいアラートはありません',
	'view_alerts' => 'アラートを表示',
	'1_new_alert' => 'You have 1 new alert',
	'x_new_alerts' => '{x} 個の新しいアラートがあります', // Don't replace {x}
	'no_alerts_usercp' => 'あなたはアラートを持っていません。',
	
	// Registraton
	'registration_check_email' => '登録ありがとうございます！登録を完了するには、電子メールで確認リンクを確認してください。 電子メールが見つからない場合は、迷惑メールフォルダを確認してください。',
	'username' => 'ユーザー名',
	'nickname' => 'ニックネーム',
	'minecraft_username' => 'Minecraft のユーザー名',
	'email_address' => 'メール アドレス',
	'email' => 'メールアドレス',
	'password' => 'パスワード',
	'confirm_password' => 'パスワードを確認',
	'i_agree' => '同意します',
	'agree_t_and_c' => '<strong class="label label-primary">登録</strong> をクリックすると、 <a href="{x}" target="_blank">利用規約</a> に同意したことになります。',
	'create_an_account' => 'アカウントを作成します。',
	'terms_and_conditions' => '利用規約',
	'validation_complete' => 'あなたのアカウントは有効になっているので、今すぐログインできます。',
	'validation_error' => 'アカウントの確認中に不明なエラーが発生しました。ウェブサイト管理者にお問い合わせください。',
	'signature' => '署名',
	'signature_max_900' => '署名は最大900文字でなければなりません。',

    // Registration - Authme
    'connect_with_authme' => 'あなたのアカウントを AuthMe に接続する',
    'authme_help' => 'あなたのアカウント AuthMe アカウントの詳細を入力してください。 まだアカウントを持っていない場合は、今すぐサーバーに参加し、説明に従ってください。',
    'unable_to_connect_to_authme_db' => 'AuthMe データベースに接続できません。 このエラーが解決しない場合は、管理者に連絡してください。',
    'authme_account_linked' => 'アカウントは正常にリンクされました。',
    'authme_email_help_1' => '最後にあなたのメールアドレスを入力してください。',
    'authme_email_help_2' => '最後にメールアドレスを入力し、アカウントの表示名を選択してください。',

	// Registration errors
	'username_required' => 'ユーザー名は必須です。',
	'email_required' => 'メールアドレスが必要です。',
	'password_required' => 'パスワードが必要です。',
	'mcname_required' => 'Minecraft ユーザー名が必要です。',
	'accept_terms' => '登録する前に利用規約に同意する必要があります。',
	'username_minimum_3' => 'ユーザー名は3文字以上でなければなりません。',
	'mcname_minimum_3' => 'Minecraft ユーザー名は3文字以上でなければなりません。',
	'password_minimum_6' => 'パスワードは6文字以上でなければなりません。',
	'username_maximum_20' => 'ユーザー名は最大20文字でなければなりません。',
	'mcname_maximum_20' => 'Minecraft ユーザー名は最大20文字でなければなりません。',
	'password_maximum_30' => 'パスワードは最大30文字です。',
	'passwords_dont_match' => 'パスワードが一致しません。',
	'username_mcname_email_exists' => 'あなたのユーザー名または電子メールアドレスはすでに存在します。',
	'invalid_mcname' => 'あなたの Minecraft ユーザー名は無効です。',
	'invalid_email' => 'あなたのメールアドレスは無効です。',
	'mcname_lookup_error' => 'Mojangの サーバーと通信してユーザー名を確認する際にエラーが発生しました。 後でもう一度お試しください。',
	'invalid_recaptcha' => 'reCAPTCHA 応答が無効です。',
	'verify_account' => 'アカウントを確認',
	'verify_account_help' => 'Minecraft アカウントを所有することを確認以下の手順に従ってください。',
	'validate_account' => 'Validate Account',
	'verification_failed' => '検証に失敗しました、再試行してください。',
	'verification_success' => '正常に検証されました！ これでログインできます。',
	'authme_username_exists' => 'AuthMe アカウントはすでにWebサイトに接続されています。',
	'uuid_already_exists' => 'Your UUID already exists, meaning this Minecraft account has already registered.',
	
	// Login
	'successful_login' => 'あなたは正常にログインしました。',
	'incorrect_details' => '間違った詳細を入力しました。',
	'inactive_account' => 'あなたのアカウントは非アクティブです。 あなたの迷惑メールフォルダ内を含む確認リンクがあるかどうかメールを確認してください。',
	'account_banned' => 'そのアカウントは禁止されています。',
	'forgot_password' => 'パスワードを忘れた？',
	'remember_me' => '私を覚えて',
	'must_input_email' => 'You must input an email address.',
	'must_input_username' => 'ユーザー名を入力する必要があります。',
	'must_input_password' => 'パスワードを入力する必要があります。',

    // Forgot password
    'forgot_password_instructions' => 'メールアドレスを入力して、パスワードを再設定するための詳しい指示をお送りください。',
    'forgot_password_email_sent' => '電子メールアドレスのアカウントが存在する場合、詳細な指示が記載された電子メールが送信されています。 見つからない場合は、迷惑メールフォルダを確認してください。',
    'unable_to_send_forgot_password_email' => 'パスワードを忘れたメールを送信できません。 管理者に連絡してください。',
    'enter_new_password' => 'あなたのメールアドレスを確認し、下に新しいパスワードを入力してください。',
    'incorrect_email' => '入力した電子メールアドレスが要求と一致しません。',
    'forgot_password_change_successful' => 'あなたのパスワードは正常に変更されました。 これでログインできます。',
	
	// Profile pages
	'profile' => 'プロファイル',
	'follow' => 'フォロー',
	'no_wall_posts' => 'まだ壁のポストはありません。',
	'change_banner' => 'バナーを変更',
	'post_on_wall' => '{x} の壁に投稿する', // Don't replace {x}
	'invalid_wall_post' => 'あなたの投稿が 1 ~ 10000 文字であることを確認してください。',
	'1_reaction' => '1 の反応',
	'x_reactions' => '{x} の反応', // Don't replace {x}
	'1_like' => 'いいね',
	'x_likes' => 'いいね {x}', // Don't replace {x}
	'1_reply' => '一件の返信',
	'x_replies' => '{x} の返信', // Don't replace {x}
	'no_replies_yet' => 'まだ回答がありません',
	'feed' => 'フィード',
	'about' => 'About',
	'reactions' => '反応',
	'replies' => '返信',
	'new_reply' => '新しい返信',
	'registered' => '登録:',
	'last_seen' => '最後に見たのは:',
	'new_wall_post' => '{x} さんがあなたのプロフィールに投稿しました。',
	'couldnt_find_that_user' => 'そのユーザーを見つけることができませんでした。',
	'block_user' => 'ユーザをブロックする',
	'unblock_user' => 'ユーザをブロック解除する',
	'confirm_block_user' => 'このユーザーをブロックしてもよろしいですか？ 彼らはあなたにプライベートなメッセージを送ったり、あなたの投稿にタグをつけることはできません。',
	'confirm_unblock_user' => 'このユーザーをブロック解除してもよろしいですか？ 彼らはあなたにプライベートメッセージを送り、あなたの投稿にタグを付けることができます。',
	'user_blocked' => 'ユーザーをブロックしました。',
	'user_unblocked' => 'ユーザーをアンブロックしました。',
	'views' => 'Profile Views:',
	'private_profile_page' => 'This is a private profile!',
	'new_wall_post_reply' => '{x} has replied to your post on {y}\'s profile.', // Don't replace {x} or {y}
	'new_wall_post_reply_your_profile' => '{x} has replied to your post on your profile.', // Don't replace {x}
	'no_about_fields' => 'This user has not added any about fields yet.',
	
	// Reports
	'invalid_report_content' => 'レポートを作成できません。 レポートの理由が 2~1024 文字であることを確認してください。',
	'report_post_content' => 'レポートの理由を入力してください',
	'report_created' => 'レポートが正常に作成されました',
	
	// Messaging
	'no_messages' => '新しいメッセージはありません',
	'no_messages_full' => 'あなたにはメッセージはありません。',
	'view_messages' => 'メッセージを表示する',
	'1_new_message' => 'You have 1 new message',
	'x_new_messages' => '{x} 個の新しいメッセージがあります', // Don't replace {x}
	'new_message' => '新しいメッセージ',
	'message_title' => 'メッセージタイトル',
	'to' => 'To',
	'separate_users_with_commas' => 'ユーザーをコンマで区切る',
	'title_required' => 'タイトルを入力してください',
	'content_required' => 'コンテンツを入力してください',
	'users_to_required' => 'いくつかのメッセージ受信者を入力してください',
	'cant_send_to_self' => 'あなた自身にメッセージを送ることはできません！',
	'title_min_2' => 'タイトルは2文字以上でなければなりません',
	'content_min_2' => 'コンテンツは2文字以上でなければなりません',
	'title_max_64' => 'タイトルは最大64文字でなければなりません',
	'content_max_20480' => 'コンテンツは最大20480文字でなければなりません',
	'max_pm_10_users' => '最大10人のユーザーにのみメッセージを送信できます',
	'message_sent_successfully' => '送信に成功しました',
	'participants' => '参加者',
	'last_message' => '最後のメッセージ',
	'by' => '作成者',
	'leave_conversation' => '会話から退出',
	'confirm_leave' => 'あなたはこの会話を退出したいですか？',
	'one_or_more_users_blocked' => '会話の少なくとも1人のメンバーにプライベートメッセージを送信することはできません。',

	/*
	 *  Infractions area
	 */
	'you_have_been_banned' => 'あなたはBanされています！',
	'you_have_received_a_warning' => 'あなたは警告を受けました！',
	'acknowledge' => '認める',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'こんにちは！',
	'email_message' => '登録ありがとうございます！ 登録を完了するには、次のリンクをクリックしてください:',
	'forgot_password_email_message' => 'パスワードをリセットするには、次のリンクをクリックしてください。 これを自分でリクエストしていない場合は、このメールを安全に削除できます。',
	'email_thanks' => 'Thanks!',

	/*
	 *  Hooks
	 */
	'user_x_has_registered' => '{x} が ' . SITE_NAME . ' に参加しました！'
);
