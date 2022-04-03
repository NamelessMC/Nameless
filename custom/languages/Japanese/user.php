<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  Translator
 *  - SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  - Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
 *  - snake( @ViaSnake, https://github.com/ViaSnake )
 *
 *  License: MIT
 *
 *  Japanese Language - Users
 */

$language = [
    /*
     *  Change this for the account validation message
     */
    'validate_account_command' => '登録を完了するには、ゲーム内で /verify {{command}} コマンドを送信してください。',

    /*
     *  User Related
     */
    'guest' => 'ゲスト',
    'guests' => 'ゲスト',

    // UserCP
    'user_cp' => 'UserCP',
    'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i> <span class="mobile_only">アカウント</span>',
    'overview' => '概要',
    'user_details' => 'ユーザー詳細',
    'profile_settings' => 'プロフィール設定',
    'successfully_logged_out' => '正常にログアウトされました。',
    'messaging' => 'メッセージ',
    'click_here_to_view' => 'クリックして表示',
    'alerts' => '通知',
    'delete_all' => 'すべて削除',
    'private_profile' => 'プライベートプロフィール',
    'gif_avatar' => 'カスタムアバターとして.gifをアップロード',
    'placeholders' => 'プレースホルダー',
    'no_placeholders' => 'プレースホルダーなし',

    // Profile settings
    'field_is_required' => '{{field}} は必須です。',
    'settings_updated_successfully' => '設定が正常に更新されました。',
    'password_changed_successfully' => 'パスワードが正常に変更されました。',
    'change_password' => 'パスワード変更',
    'current_password' => '現在のパスワード',
    'new_password' => '新しいパスワード',
    'confirm_new_password' => '新しいパスワードを再入力',
    'incorrect_password' => 'パスワードが間違っています。',
    'two_factor_auth' => '二要素認証',
    'enabled' => '有効',
    'disabled' => '無効',
    'enable' => '有効',
    'disable' => '無効',
    'tfa_scan_code' => '認証アプリ内で次のコードをスキャンしてください:',
    'tfa_code' => 'お使いの端末にカメラがない場合や、QRコードをスキャンできない場合は、次のコードを入力してください:',
    'tfa_enter_code' => '認証アプリ内に表示されているコードを入力してください:',
    'invalid_tfa' => '無効なコードです、もう一度お試しください。',
    'tfa_successful' => '二要素認証が正常に設定されました。今後はログインするたびに認証が必要になります。',
    'active_language' => '有効な言語',
    'active_template' => '有効なテンプレート',
    'timezone' => 'タイムゾーン',
    'upload_new_avatar' => '新しいアバターをアップロード',
    'nickname_already_exists' => '指定されたニックネームは既に存在します。',
    'change_email_address' => 'メールアドレス変更',
    'email_already_exists' => '指定されたメールアドレスは既に存在しています。',
    'email_changed_successfully' => 'メールアドレスが正常に更新されました。',
    'avatar' => 'アバター',
    'profile_banner' => 'プロフィールバナー',
    'upload_profile_banner' => 'プロフィールバナーをアップロード',
    'upload' => 'アップロード',
    'topic_updates' => 'フォローしているトピックのメールを受け取る',
    'gravatar' => 'アバターとしてGravatarを使用',

    // Alerts
    'user_tag_info' => '{{author}} の投稿にタグがつけられました。',
    'no_alerts' => '新しい通知はありません',
    'view_alerts' => '通知を表示',
    '1_new_alert' => '1件の新しい通知があります。',
    'x_new_alerts' => '{{count}} 件の新しい通知があります。',
    'no_alerts_usercp' => '通知は受信していません。',

    // Registraton
    'registration_check_email' => 'ご登録ありがとうございます。登録を完了するために、検証リンクがないかメールを確認してください。メールが見当たらない場合は、迷惑メールフォルダをご確認ください。',
    'username' => 'ユーザー名',
    'nickname' => 'ニックネーム',
    'minecraft_username' => 'Minecraftユーザー名',
    'email_address' => 'メールアドレス',
    'email' => 'メール',
    'password' => 'パスワード',
    'confirm_password' => 'パスワードの確認',
    'i_agree' => '同意します',
    'agree_t_and_c' => '{{linkStart}}利用規約{{linkEnd}} を読み、同意しました。',
    'create_an_account' => 'アカウント作成',
    'terms_and_conditions' => '利用規約',
    'validation_complete' => 'アカウントが検証されました。ログインが可能です。',
    'validation_error' => 'アカウントの検証中に不明なエラーが発生しました。ウェブサイトの管理者に連絡してください。',
    'signature' => '署名',
    'signature_max_900' => '署名は900文字以下である必要があります。',

    // Registration - Authme
    'connect_with_authme' => 'アカウントをAuthMeと接続する',
    'authme_help' => 'ゲーム内の AuthMeアカウントの詳細を入力してください。 まだアカウントを作成していない場合はサーバーに参加し、表示される説明に従ってください。',
    'unable_to_connect_to_authme_db' => 'AuthMeデータベースへの接続ができません。このエラーが続く場合は、管理者に連絡してください。',
    'authme_account_linked' => 'アカウントのリンクに成功しました。',
    'authme_email_help_1' => '最後に、メールアドレスを入力してください。',
    'authme_email_help_2' => '最後に、メールアドレスを入力し、アカウントの表示名を選択してください。',

    // Registration errors
    'username_required' => 'ユーザー名は必須です。',
    'email_required' => 'メールアドレスは必須です。',
    'password_required' => 'パスワードは必須です。',
    'mcname_required' => 'Minecraftユーザー名は必須です。',
    'accept_terms' => '登録する前に利用規約に同意する必要があります。',
    'username_minimum_3' => 'ユーザー名が3文字以上であることをご確認ください。',
    'mcname_minimum_3' => 'Minecraftユーザー名が3文字以上であることをご確認ください。',
    'password_minimum_6' => 'パスワードが6文字以上であることをご確認ください。',
    'username_maximum_20' => 'ユーザー名が20文字以下であることをご確認ください。',
    'mcname_maximum_20' => 'Minecraftユーザー名が20文字以下であることをご確認ください。',
    'passwords_dont_match' => 'パスワードが一致しません。',
    'username_mcname_email_exists' => '指定されたのユーザー名または電子メールアドレスはすでに存在します。',
    'invalid_mcname' => '指定されたのMinecraftユーザー名は無効です。',
    'invalid_email' => '指定されたのメールアドレスは無効です。',
    'mcname_lookup_error' => 'Mojangのサーバーと通信してユーザー名を確認する際にエラーが発生しました。数分後にもう一度お試しください。',
    'invalid_recaptcha' => 'reCAPTCHAレスポンスが無効です。',
    'verify_account' => 'アカウント確認',
    'verify_account_help' => 'Minecraft アカウントを所有することを確認します。以下の手順に従ってください。',
    'validate_account' => 'アカウント検証',
    'verification_failed' => '検証に失敗しました、再試行してください。',
    'verification_success' => '正常に検証されました。ログインが可能です。',
    'authme_username_exists' => '指定されたAuthMeアカウントはすでにウェブサイトに接続されています。',
    'uuid_already_exists' => '指定されたのUUIDはすでに存在しています。このMinecraftアカウントはすでに登録されています。',

    // Login
    'successful_login' => '正常にログインしました。',
    'incorrect_details' => '間違った詳細情報を入力しました。',
    'inactive_account' => '指定されたアカウントは未アクティブです。迷惑メールフォルダ内を含め、検証メールが存在しないかを確認してください。',
    'account_banned' => 'アカウントはbanされています。',
    'forgot_password' => 'パスワードを忘れましたか？',
    'remember_me' => '自動ログイン',
    'must_input_email' => 'メールアドレスを入力する必要があります。',
    'must_input_username' => 'ユーザー名を入力する必要があります。',
    'must_input_password' => 'パスワードを入力する必要があります。',
    'must_input_email_or_username' => 'メールアドレスまたはユーザー名の入力が必要です。',
    'email_or_username' => 'メールアドレスまたはユーザー名',

    // Forgot password
    'forgot_password_instructions' => 'パスワードをリセットするための詳しい説明をお送りしますので、メールアドレスを入力してください。',
    'forgot_password_email_sent' => 'そのメールアドレスのアカウントが存在する場合、詳しい説明を含むメールが送信されました。見つからない場合は、迷惑メールフォルダを確認してみてください。',
    'unable_to_send_forgot_password_email' => 'パスワードを忘れた方へのメールが送信できません。管理者に連絡してください。',
    'enter_new_password' => 'メールアドレスを確認して、以下に新しいパスワードを入力してください',
    'incorrect_email' => '入力されたメールアドレスがリクエストと一致しません。',
    'forgot_password_change_successful' => 'パスワードの変更が成功しました。これでログインできます。',

    // Profile pages
    'profile' => 'プロフィール',
    'follow' => 'フォロー',
    'no_wall_posts' => 'ユーザーの投稿は存在しません。',
    'change_banner' => 'バナーを変更',
    'post_on_wall' => '{{user}} に投稿する',
    'invalid_wall_post' => '投稿内容が1~10000文字であることを確認してください。',
    '1_reaction' => '1件の反応',
    'x_reactions' => '{{count}} 件の反応',
    '1_like' => 'いいね',
    'x_likes' => '{{count}} 件のいいね',
    '1_reply' => '1件の返信',
    'x_replies' => '{{count}} 件の返信',
    'no_replies_yet' => '返信がありません。',
    'feed' => 'フィード',
    'about' => '情報',
    'reactions' => '反応',
    'replies' => '返信',
    'new_reply' => '新しい返信',
    'registered' => '登録:',
    'registered_x' => '登録: {{registeredAt}}',
    'last_seen' => '最終オンライン時:',
    'last_seen_x' => '最終オンライン時: {{lastSeenAt}}',
    'new_wall_post' => '{{author}} さんがあなたのプロフィールに投稿しました。',
    'couldnt_find_that_user' => '指定されたユーザーを見つけることができませんでした。',
    'block_user' => 'ユーザをブロック',
    'unblock_user' => 'ユーザのブロック解除',
    'confirm_block_user' => 'このユーザーをブロックしてもよろしいですか？このユーザーは、あなたにプライベートメッセージを送ったり、あなたをタグ付けして投稿することができなくなります',
    'confirm_unblock_user' => 'このユーザーのブロック解除してもよろしいですか？このユーザーは、あなたにプライベートメッセージを送ったり、投稿にタグ付けできるようになります。',
    'user_blocked' => 'ユーザーをブロックしました。',
    'user_unblocked' => 'ユーザーのブロックを解除しました。',
    'views' => 'プロフィールビュー:',
    'private_profile_page' => '表示しているユーザはプライベートプロファイルに設定されています。',
    'new_wall_post_reply' => '{{author}} さんが {{user}} のプロフィールの投稿に返信しました。',
    'new_wall_post_reply_your_profile' => '{{author}} さんがプロフィールの投稿に返信しました。',
    'no_about_fields' => 'フィールドの内容を追加していません。',
    'reply' => '返信',

    // Reports
    'invalid_report_content' => 'レポートを作成できません。レポートの理由が2~1024文字であることを確認してください。',
    'report_post_content' => 'レポートの理由を入力してください。',
    'report_created' => 'レポートが正常に作成されました。',

    // Messaging
    'no_messages' => '新しいメッセージはありません',
    'no_messages_full' => 'メッセージが存在しません。',
    'view_messages' => 'メッセージを表示',
    '1_new_message' => '1件の新しいメッセージがあります。',
    'x_new_messages' => '{{count}} 件の新しいメッセージがあります。',
    'new_message' => '新しいメッセージ',
    'message_title' => 'メッセージタイトル',
    'to' => 'To',
    'separate_users_with_commas' => 'ユーザーをカンマで区切る',
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
    'messages' => 'メッセージ',
    'latest_profile_posts' => '最新のプロフィール投稿',
    'no_profile_posts' => 'プロフィール投稿はありません。',

    /*
     *  Infractions area
     */
    'you_have_been_banned' => 'あなたはBANされています！',
    'you_have_received_a_warning' => 'あなたは警告を受けました！',
    'acknowledge' => '承認',

    /*
     *  Hooks
     */
    'user_x_has_registered' => '{{user}} が {{siteName}} に登録しました。',
    'user_x_has_validated' => '{{user}} がアカウントを認証しました！',

    // OAuth
    'oauth_unlinked' => 'プロバイダーからアカウントの連携解除に成功しました。',
    'oauth_already_linked' => '{{provider}} アカウントには、別のNamelessMCユーザーが既に連携されています。',
    'oauth_link_success' => 'あなたのアカウントと {{provider}} の連携に成功しました。',
    'no_user_found_with_provider' => '{{provider}} アカウントを持つユーザーは見つかりませんでした。',
    'oauth_login_success' => '{{provider}} のアカウントでログインしています。',
    'oauth_link_confirm' => 'アカウント連携のため、{{provider}} ウェブサイトに移動します。',
    'oauth_unlink_confirm' => '本当に {{provider}} からアカウントの連携を解除しますか？このアカウントでは今後ログインできなくなります。',
];
