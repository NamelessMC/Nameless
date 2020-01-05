<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  Translation by SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  Additional translation by Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
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
	'user_details' => 'ユーザー詳細',
	'profile_settings' => 'プロフィール設定',
	'successfully_logged_out' => '正常にログアウトされました。',
	'messaging' => 'メッセージ',
	'click_here_to_view' => 'クリックして表示',
	'moderation' => 'モデレーション',
	'administration' => '管理',
	'alerts' => 'アラート',
	'delete_all' => 'すべて削除',
	'private_profile' => 'プライベートプロフィール',

	// Profile settings
	'field_is_required' => '{x} は必須です。', // Don't replace {x}
	'settings_updated_successfully' => '設定が正常に更新されました。',
	'password_changed_successfully' => 'パスワードが正常に変更されました。',
	'change_password' => 'パスワード変更',
	'current_password' => '現在のパスワード',
	'new_password' => '新しいパスワード',
	'confirm_new_password' => '新しいパスワードを再入力',
	'incorrect_password' => 'パスワードが間違っています。',
	'two_factor_auth' => '2段階認証',
	'enabled' => '有効',
    'disabled' => '無効',
	'enable' => '有効',
	'disable' => '無効',
	'tfa_scan_code' => '①認証アプリをインストールしてください。<br>②認証アプリで以下のQRコードをスキャンしてください。',
	'tfa_code' => 'お使いのデバイスにカメラがない、または、QR コードをスキャンすることができる場合は、次のコードを入力してください。',
	'tfa_enter_code' => '認証アプリに表示されたコードを入力してください。',
	'invalid_tfa' => '無効なコードです。再試行してください。',
	'tfa_successful' => '二段階認証が設定されました。次回ログイン時から二段階認証が有効になります。',
	'active_language' => '有効な言語',
	'active_template' => 'Active Template',
	'timezone' => 'タイムゾーン',
	'upload_new_avatar' => '新しいアバターをアップロード',
	'nickname_already_exists' => '指定したニックネームは既に存在します。別のニックネームを指定してください。',
	'change_email_address' => 'メールアドレス変更',
	'email_already_exists' => '指定されたメールアドレスは既に登録されています。別のメールアドレスを指定してください。',
	'email_changed_successfully' => 'メールアドレスが正常に更新されました。',
	'avatar' => 'アバター',
	'profile_banner' => 'Profile Banner',
	'upload_profile_banner' => 'Upload Profile Banner',
	'upload' => 'Upload',

	// Alerts
	'user_tag_info' => '{x} の投稿にタグがつけられました。', // Don't replace {x}
	'no_alerts' => '新しいアラートはありません',
	'view_alerts' => 'アラートを表示',
	'1_new_alert' => '1 件の新しいアラートがあります。',
	'x_new_alerts' => '{x} 件の新しいアラートがあります。', // Don't replace {x}
	'no_alerts_usercp' => 'アラートは受信していません。',

	// Registraton
	'registration_check_email' => 'メール検証に関するメールを指定されたメールアドレスに送信しました。メールに記載されたリンクをクリックして検証を完了してください。メールが見つからない場合は迷惑メールフォルダを確認してみてください。',
	'username' => 'ユーザー名',
	'nickname' => 'ニックネーム',
	'minecraft_username' => 'Minecraft のユーザー名',
	'email_address' => 'メールアドレス',
	'email' => 'メールアドレス',
	'password' => 'パスワード',
	'confirm_password' => 'パスワード再入力',
	'i_agree' => '同意します',
	'agree_t_and_c' => '<strong class="label label-primary">登録</strong> をクリックすると、 <a href="{x}" target="_blank">利用規約</a> に同意したことになります。',
	'create_an_account' => 'アカウント作成',
	'terms_and_conditions' => '利用規約',
	'validation_complete' => 'アカウントが登録されました。ログインが可能です。',
	'validation_error' => 'アカウントの確認中にエラーが発生しました。管理者に連絡してください。',
	'signature' => '署名',
	'signature_max_900' => '署名が900文字以下であることをご確認ください。',

    // Registration - Authme
    'connect_with_authme' => 'あなたのアカウントを AuthMe に接続する',
    'authme_help' => 'AuthMe アカウントの詳細を入力してください。 まだアカウントを作成していない場合は、サーバーに参加し、説明に従ってください。',
    'unable_to_connect_to_authme_db' => 'AuthMe データベースに接続できませんでした。数分後に再度お試しください。修正されない場合は管理者に連絡してください。',
    'authme_account_linked' => 'アカウントは正常にリンクされました。',
    'authme_email_help_1' => 'メールアドレスを入力してください。',
    'authme_email_help_2' => 'メールアドレスを入力し、アカウントの表示名を選択してください。',

	// Registration errors
	'username_required' => 'ユーザー名は必須です。',
	'email_required' => 'メールアドレスは必須です。',
	'password_required' => 'パスワードは必須です。',
	'mcname_required' => 'Minecraft ユーザー名は必須です。',
	'accept_terms' => '登録する前に利用規約に同意する必要があります。',
	'username_minimum_3' => 'ユーザー名が3文字以上であることをご確認ください。',
	'mcname_minimum_3' => 'Minecraft ユーザー名が3文字以上であることをご確認ください。',
	'password_minimum_6' => 'パスワードが6文字以上であることをご確認ください。',
	'username_maximum_20' => 'ユーザー名が20文字以下であることをご確認ください。',
	'mcname_maximum_20' => 'Minecraft ユーザー名が20文字以下であることをご確認ください。',
	'password_maximum_30' => 'パスワードが30文字以下であることをご確認ください。',
	'passwords_dont_match' => 'パスワードが一致しません。',
	'username_mcname_email_exists' => '指定されたのユーザー名または電子メールアドレスはすでに存在します。',
	'invalid_mcname' => '指定されたの Minecraft ユーザー名は無効です。',
	'invalid_email' => '指定されたのメールアドレスは無効です。',
	'mcname_lookup_error' => 'Mojang のサーバーと通信してユーザー名を確認する際にエラーが発生しました。数分後にもう一度お試しください。',
	'invalid_recaptcha' => 'reCAPTCHA レスポンスが無効です。',
	'verify_account' => 'アカウント確認',
	'verify_account_help' => 'Minecraft アカウントを所有することを確認します。以下の手順に従ってください。',
	'validate_account' => 'アカウント検証',
	'verification_failed' => '検証に失敗しました、再試行してください。',
	'verification_success' => '正常に検証されました。ログインが可能です。',
	'authme_username_exists' => '指定された AuthMe アカウントはすでにWebサイトに接続されています。',
	'uuid_already_exists' => '指定されたのUUIDはすでに存在しています。このMinecraftアカウントはすでに登録されています。',

	// Login
	'successful_login' => '正常にログインしました。',
	'incorrect_details' => '間違った詳細を入力しました。',
	'inactive_account' => '指定されたアカウントは未検証です。迷惑メールフォルダ内を含め、検証メールが存在しないかを確認してください。',
	'account_banned' => '指定されたアカウントは 法令 又は 利用規約に違反したため使用が禁止されています。',
	'forgot_password' => 'パスワードを忘れましたか？',
	'remember_me' => '自動ログイン',
	'must_input_email' => 'メールアドレスを入力する必要があります。',
	'must_input_username' => 'ユーザー名を入力する必要があります。',
	'must_input_password' => 'パスワードを入力する必要があります。',

    // Forgot password
    'forgot_password_instructions' => '以下にメールアドレスを入力してください。入力されたメールアドレスが存在する場合、パスワード再設定用のメールを送信します。',
    'forgot_password_email_sent' => '入力されたメールアドレスが存在する場合、パスワード再設定用のメールを送信しました。 見つからない場合は、迷惑メールフォルダを確認し登録したメールアドレスと一致しているかを確認してください。',
    'unable_to_send_forgot_password_email' => 'パスワード再設定用のメールを送信できませんでした。管理者に連絡してください。',
    'enter_new_password' => 'もう一度メールアドレスを入力した後、新しいパスワードを入力してください。',
    'incorrect_email' => '入力した電子メールアドレスが要求元メールアドレスと一致しません。',
    'forgot_password_change_successful' => 'あなたのパスワードは正常に変更されました。新しいパスワードでログインしてください。',

	// Profile pages
	'profile' => 'プロフィール',
	'follow' => 'フォロー',
	'no_wall_posts' => 'ユーザーの投稿は存在しません。',
	'change_banner' => 'バナーを変更',
	'post_on_wall' => '{x} に投稿する', // Don't replace {x}
	'invalid_wall_post' => '投稿内容が 1 ~ 10000 文字であることを確認してください。',
	'1_reaction' => '1 の反応',
	'x_reactions' => '{x} の反応', // Don't replace {x}
	'1_like' => 'いいね',
	'x_likes' => 'いいね {x}', // Don't replace {x}
	'1_reply' => '1件の返信',
	'x_replies' => '{x}件の返信', // Don't replace {x}
	'no_replies_yet' => '返信がありません。',
	'feed' => 'フィード',
	'about' => '情報',
	'reactions' => '反応',
	'replies' => '返信',
	'new_reply' => '新しい返信',
	'registered' => '登録:',
	'registered_x' => '登録: {x}',
	'last_seen' => '最終オンライン時:',
	'last_seen_x' => '最終オンライン時: {x}', // Don't replace {x}
	'new_wall_post' => '{x} さんがあなたのプロフィールに投稿しました。',
	'couldnt_find_that_user' => '指定されたユーザーを見つけることができませんでした。',
	'block_user' => 'ユーザをブロック',
	'unblock_user' => 'ユーザのブロック解除',
	'confirm_block_user' => '指定したユーザーをブロックしてもよろしいですか？ ブロックされたユーザはプライベートなメッセージの送信や投稿にタグをつけることはできません。',
	'confirm_unblock_user' => '指定したユーザーのブロック解除してもよろしいですか？ ブロック解除されたユーザはプライベートメッセージの送信や投稿にタグを付けることが許可されます。',
	'user_blocked' => 'ユーザーをブロックしました。',
	'user_unblocked' => 'ユーザーのブロックを解除しました。',
	'views' => 'プロフィールビュー:',
	'private_profile_page' => '表示しているユーザはプライベートプロファイルに設定されています。',
	'new_wall_post_reply' => '{x}さんが{y}のプロフィールの投稿に返信しました。',// {x}か{y}
	'new_wall_post_reply_your_profile' => '{x}さんがプロフィールの投稿に返信しました。',// {x}を置き換えないでください
	'no_about_fields' => 'フィールドの内容を追加していません。',
	'reply' => 'Reply',

	// Reports
	'invalid_report_content' => 'レポートを作成できません。 レポートの理由が 2~1024 文字であることを確認してください。',
	'report_post_content' => 'レポートの理由を入力してください。',
	'report_created' => 'レポートが正常に作成されました。',

	// Messaging
	'no_messages' => '新しいメッセージはありません',
	'no_messages_full' => 'メッセージが存在しません。',
	'view_messages' => 'メッセージを表示',
	'1_new_message' => '1 件の新しいメッセージがあります。',
	'x_new_messages' => '{x} 件の新しいメッセージがあります。', // Don't replace {x}
	'new_message' => '新しいメッセージ',
	'message_title' => 'メッセージタイトル',
	'to' => 'To',
	'separate_users_with_commas' => 'ユーザーをコンマで区切る',
	'title_required' => 'タイトルを入力してください。',
	'content_required' => '内容を入力してください。',
	'users_to_required' => 'メッセージ受信者を入力してください。',
	'cant_send_to_self' => '自身にメッセージを送ることはできません。',
	'title_min_2' => 'タイトルが2文字以下であることをご確認ください。',
	'content_min_2' => '内容が2文字以上であることをご確認ください。',
	'title_max_64' => 'タイトルが64文字以下であることをご確認ください。',
	'content_max_20480' => '内容が20480文字以下であることをご確認ください。',
	'max_pm_10_users' => '最大10人のユーザーにのみメッセージを送信できます。',
	'message_sent_successfully' => '送信に成功しました。',
	'participants' => '参加者',
	'last_message' => '最後のメッセージ',
	'by' => '作成者',
	'leave_conversation' => '会話から退出',
	'confirm_leave' => '会話を退出しますか？',
	'one_or_more_users_blocked' => '会話の少なくとも1人のメンバーにプライベートメッセージを送信することはできません。',
	'messages' => 'Messages',

	/*
	 *  Infractions area
	 */
	'you_have_been_banned' => '指定されたアカウントは使用が禁止されています。',
	'you_have_received_a_warning' => '指定されたアカウントは警告を受信しています。',
	'acknowledge' => '承認する',


	/*
	 *  Emails
	 */
	'email_greeting' => 'こんにちは！',
	'email_message' => '登録ありがとうございます！ 登録を完了するには、次のリンクをクリックしてください:',
	'forgot_password_email_message' => 'パスワードをリセットするには、次のリンクをクリックしてください。 これを自分でリクエストしていない場合は、このメールを安全に削除できます。',
	'email_thanks' => 'ありがとうございます。',

	/*
	 *  Hooks
	 */
	'user_x_has_registered' => '{x} が ' . SITE_NAME . ' に登録しました。'
);
