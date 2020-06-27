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
 *  Japanese Language - Admin
 */

$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'StaffCP ログイン',

	// Sidebar
	'dashboard' => 'ダッシュボード',
	'configuration' => 'コンフィグ',
	'layout' => 'レイアウト',
	'user_management' => 'ユーザー管理',
	'admin_cp' => 'StaffCP',
	'administration' => '管理',
	'overview' => '概要',
	'core' => 'コア',
	'integrations' => '連携',
	'minecraft' => 'Minecraft',
	'modules' => 'モジュール',
	'security' => 'セキュリティ',
	'sitemap' => 'サイトマップ',
	'styles' => 'スタイル',
	'users_and_groups' => 'ユーザー・グループ',

	// Overview
	'running_nameless_version' => '実行中の NamelessMC のバージョン: <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => '実行中の PHP のバージョン: <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => '統計情報',
	'registrations' => '登録',
	'topics' => 'トピック',
	'posts' => 'ポスト',
    'notices' => '通知',
    'no_notices' => '通知はありません。',
    'email_errors_logged' => '電子メールエラーが検出されました。',

	// Core
	'settings' => '設定',
	'general_settings' => '一般設定',
	'sitename' => 'サイト名',
	'default_language' => '初期言語',
	'default_language_help' => '初期言語に設定している言語が登録後最初の言語になりますが、各ユーザーは後でインストールされている言語から別の言語を選択できます。',
	'install_language' => '言語インストール',
	'update_user_languages' => 'アップデートユーザー言語',
	'update_user_languages_warning' => 'この設定を実行すると、すでに別言語を指定しているユーザーも現在設定している言語(初期言語)に強制的に変更されます。',
	'updated_user_languages' => '全ユーザーの言語を更新しました。',
	'installed_languages' => '言語が正常にインストールされました。',
	'default_timezone' => '初期タイムゾーン',
	'registration' => '登録',
	'enable_registration' => '新規登録を許可しますか？',
	'verify_with_mcassoc' => 'MCAssocで各ユーザーのMinecraftアカウントを検証しますか？',
	'email_verification' => 'メール認証を有効にしますか？',
	'registration_settings_updated' => '登録設定が正常に変更されました。',
	'homepage_type' => 'ホームページタイプ',
	'post_formatting_type' => 'ポストフォーマットタイプ',
	'portal' => 'ポータル',
	'private_profiles' => 'プライベートプロフィールをユーザーに許可しますか？',
	'missing_sitename' => '2~64 文字でサイト名を入力してください。',
	'missing_contact_address' => '3~255 文字のメールアドレスを入力してください。',
	'use_friendly_urls' => 'フレンドリーURL',
	'use_friendly_urls_help' => '<strong>重要</strong>: サーバー設定にて mod_rewrite と .htaccess を使用できるように設定する必要があります。IISの場合はルートディレクトリの web.config.example を web.config に変更してください。',
	'config_not_writable' => '<strong>core/config.php</strong> ファイルへの書き込み許可がありません。ファイルアクセス権限を確認して下さい。',
	'settings_updated_successfully' => '一般設定が正常に更新されました。',
	'social_media' => 'ソーシャルメディア',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Twitter ダークテーマを使用しますか？',
	'discord_id' => 'Discord サーバー ID',
	'discord_widget_theme' => 'Discord ウィジェットテーマ',
	'dark' => 'ダーク',
	'light' => 'ライト',
	'google_plus_url' => 'Google+ URL',
	'facebook_url' => 'Facebook URL',
	'social_media_settings_updated' => 'ソーシャルメディア設定が正常に更新されました。',
	'successfully_updated' => '正常に更新されました。',
    'debugging_and_maintenance' => 'メンテナンス',
    'maintenance' => 'メンテナンス',
    'enable_debug_mode' => 'デバッグモードを有効にしますか？',
	'debugging_settings_updated_successfully' => 'デバッグ・メンテナンス設定が正常に更新されました。',
    'force_https' => 'Https を強制させますか？',
    'force_https_help' => '有効にすると http は自動で https に置き換わります。有効にするには、必ず有効なサーバー証明書をサーバーに設定しておいてください。',
    'force_www' => 'www を強制させますか？',
    'contact_email_address' => '連絡先メールアドレス',
    'emails' => 'メール',
    'email_errors' => 'メールエラー',
    'registration_email' => '登録メール',
    'contact_email' => '連絡先メールアドレス',
	'forgot_password_email' => 'パスワードを忘れた場合',
	'forum_topic_reply_email' => 'Forum Topic Reply Email',
    'unknown' => '不明',
    'delete_email_error' => 'エラー削除',
    'confirm_email_error_deletion' => '選択中のエラーを削除してもよろしいですか？',
    'viewing_email_error' => 'エラー表示',
    'unable_to_write_email_config' => '<strong>core/email.php</core> ファイルに書き込みできません。ファイルアクセス権限を確認して下さい。',
    'enable_mailer' => 'PHPMailer を有効にしますか？',
    'enable_mailer_help' => 'php.ini で電子メール設定が完了していない場合こちらを使用できます。PHPMailer を使用するには、Gmail や SMTP プロバイダなどの電子メールを送信できるプロバイダーサービスが必要です。',
    'outgoing_email' => '送信メールアドレス',
    'outgoing_email_info' => 'NamelessMC が電子メールの送信に使用する電子メールアドレスです。',
    'mailer_settings_info' => 'PHPMailer を有効にした場合、以下のフィールドに必要事項を記入する必要があります。 以下のフィールドに記入する方法については <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-SMTP-with-Nameless-(e.g.-Gmail-or-Outlook)" target="_blank">Wiki</a> を確認して下さい。',
    'host' => 'ホスト',
    'email_port' => 'ポート',
    'email_password_hidden' => 'セキュリティ保護のためパスワードは非表示です。',
    'send_test_email' => 'テストメール 送信',
    'send_test_email_info' => '<strong>{x}</strong> にNamelessMCからテストメールを送信します。テストを実行することで、メール設定が正しくされているかを確認できます。 テスト終了後確認されたエラーは表示されます。送信するには「送信」を押してください。', // Don't replace {x}
    'send' => '送信',
    'test_email_error' => 'テストメールエラー:',
    'test_email_success' => 'テストメールが正常に送信されました。',
    'terms_error' => '利用規約が <strong>2048文字</strong> 以下であることをご確認ください。',
    'privacy_policy_error' => 'プライバシーポリシーが <strong>100000文字</strong> 以下であることをご確認ください。',
    'terms_updated' => '利用規約・プライバシーポリシーが正常に変更されました。',
    'avatars' => 'アバター',
    'allow_custom_avatars' => 'アバターをユーザーが変更できるようにしますか？',
    'default_avatar' => 'デフォルトアバター',
    'custom_avatar' => 'カスタムアバター',
    'minecraft_avatar' => 'Minecraft アバター',
    'minecraft_avatar_source' => 'Minecraft アバダーデータサーバー',
    'built_in_avatars' => 'ビルトインのアバターサービス',
    'minecraft_avatar_perspective' => 'Minecraft アバターの表示部位',
    'face' => '顔',
    'head' => '頭',
	'bust' => '胸',
    'select_default_avatar' => '新しいデフォルトアバターを選択:',
    'no_avatars_available' => 'デフォルトアバターがアップロードされていません。「画像アップロード」を選択後、デフォルトにするアバターを選択してください。',
    'avatar_settings_updated_successfully' => 'アバター設定が正常に更新されました。',
    'navigation' => 'ナビゲーション',
    'navbar_order' => 'ナビゲーションバー順番',
    'navbar_order_instructions' => 'ナビゲーションバー順番は、各メニューの順番を設定できます。一番最初は「1」、二番目以降はそれ以上の数字を設定することで順番を調節できます。',
    'navbar_icon' => 'ナビゲーションバーアイコン',
    'navbar_icon_instructions' => 'ナビゲーションバーアイコンは <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" rel="noopener nofollow">Font Awesome</a>, <a href="https://semantic-ui.com/elements/icon.html" target="_blank" rel="noopener nofollow">Semantic UI</a> を参照して使用したいアイコンのHTMLソースを貼り付けてください。',
    'navigation_settings_updated_successfully' => 'ナビゲーションバーの設定が正常に更新されました。',
    'dropdown_items' => 'ドロップダウンメニュー',
    'enable_page_load_timer' => 'ページロード時間を表示しますか？',
    'google_recaptcha' => 'Google reCAPTCHA を有効にしますか？',
    'google_recaptcha_login' => 'Enable Google reCAPTCHA on login?',
    'captcha_type' => 'Captcha Type',
    'recaptcha_site_key' => 'reCAPTCHA サイトキー',
    'recaptcha_secret_key' => 'reCAPTCHA シークレットキー',
    'registration_disabled_message' => '新規登録無効時メッセージ',
    'enable_nicknames_on_registration' => 'ユーザー登録時、Minecarft以外のユーザーネームの登録を許可しますか？',
    'validation_promote_group' => '検証後昇格先グループ',
    'validation_promote_group_info' => 'アカウントの検証が完了したユーザーが昇格されるグループです。',
    'login_method' => 'ログイン使用メゾット',
    'privacy_and_terms' => '規約設定',

	// Reactions
	'icon' => 'アイコン',
	'type' => 'タイプ',
	'positive' => 'ポジティブ',
	'neutral' => 'ナチュラル',
	'negative' => 'ネガティブ',
	'editing_reaction' => 'リアクション編集',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> 新しいリアクション',
	'creating_reaction' => 'リアクション作成',
	'no_reactions' => 'リアクションが設定されていません。',
	'reaction_created_successfully' => 'リアクションが正常に作成されました。',
	'reaction_edited_successfully' => 'リアクションが正常に編集されました。',
	'reaction_deleted_successfully' => 'リアクションが正常に削除されました。',
	'name_required' => '名前は必須です。',
	'html_required' => 'HTMLは必須です。',
	'type_required' => 'タイプは必須です。',
	'name_maximum_16' => '名前は　<strong>16文字</strong>　以内にする必要があります。',
	'html_maximum_255' => 'HTMLは <strong>255文字</strong> 以内で指定してください。',
	'confirm_delete_reaction' => '選択中のリアクションを削除してもよろしいですか？',

	// Custom profile fields
	'custom_fields' => 'カスタムプロフィール',
	'new_field' => '<i class="fa fa-plus-circle"></i> 新しいフィールド',
	'required' => '必須',
	'editable' => '編集許可',
	'public' => '全体公開',
	'text' => 'テキスト',
	'textarea' => 'テキスト エリア',
	'date' => '日付',
	'creating_profile_field' => 'プロフィールフィールド作成',
	'editing_profile_field' => 'プロフィールフィールド編集',
	'field_name' => 'フィールド名',
	'profile_field_required_help' => '必須に設定されたフィールドはユーザーが必ず入力する必要があるフィールドとなり、登録時に設定画面が表示されます。',
	'profile_field_public_help' => 'パブリックフィールドはすべてのユーザーに表示されますが、無効になっている場合は権限保持者のみ値を表示できます。',
	'profile_field_error' => '2~16文字のフィールド名を入力してください。',
	'description' => '説明',
	'display_field_on_forum' => 'フォーラムにフィールドを表示しますか？',
	'profile_field_forum_help' => '有効にすると、フォーラムの投稿のユーザー情報枠にフィールド内容を表示します。',
	'profile_field_editable_help' => '有効にすると、ユーザーはプロフィール設定でフィールドを編集することができるようになります。',
	'no_custom_fields' => 'カスタムフィールドが存在しません。',
	'profile_field_updated_successfully' => 'プロフィールフィールドが正常に更新されました。',
	'profile_field_created_successfully' => 'プロフィールフィールドが正常に作成されました。',
	'profile_field_deleted_successfully' => 'プロフィールフィールドが正常に削除されました。',

    // Minecraft
    'enable_minecraft_integration' => 'Minecraft 連携を有効にしますか？',
    'mc_service_status' => 'Minecraft サービスステータス',
    'service_query_error' => 'サービスのステータスを取得できません。',
    'authme_integration' => 'AuthMe 連携',
    'authme_integration_info' => 'AuthMe 連携が有効になっている場合、ユーザーはゲーム内でのみ登録できます。',
    'enable_authme' => 'AuthMe 連携を有効にしますか？',
    'authme_db_address' => 'AuthMe データベースアドレス',
    'authme_db_port' => 'AuthMe データベースポート',
    'authme_db_name' => 'AuthMe データベース名',
    'authme_db_user' => 'AuthMe データベースユーザー名',
    'authme_db_password' => 'AuthMe データベースパスワード',
    'authme_db_password_hidden' => 'The AuthMe database password is hidden for security reasons.',
    'authme_hash_algorithm' => 'AuthMe ハッシュアルゴリズム',
    'authme_db_table' => 'AuthMe ユーザーテーブル',
    'enter_authme_db_details' => '有効なデータベース情報を入力してください。',
    'authme_password_sync' => 'Authme パスワードを同期しますか？',
    'authme_password_sync_help' => '有効にするとユーザーのパスワードが更新されるたびに、Web サイトのパスワードも更新されます。',
    'minecraft_servers' => 'Minecraft サーバー',
    'account_verification' => 'Minecraft アカウントの確認',
    'server_banners' => 'サーバーバナー',
    'query_errors' => 'クエリエラー',
    'add_server' => '<i class="fa fa-plus-circle"></i> サーバー追加',
    'no_servers_defined' => '設定済みのサーバーは存在しません。',
    'query_settings' => 'クエリ設定',
    'default_server' => '初期サーバー',
    'no_default_server' => '初期サーバーが存在しません。',
    'external_query' => '外部クエリを使用しますか？',
    'external_query_help' => 'デフォルトのサーバークエリが機能しない場合は、このオプションを有効にします。',
    'adding_server' => 'サーバー追加',
    'server_name' => 'サーバー名',
    'server_address' => 'サーバーアドレス',
    'server_address_help' => 'ポート番号を除くアドレスを入力してください。',
    'server_port' => 'サーバーポート',
    'parent_server' => '親サーバー',
    'parent_server_help' => '親サーバーは、Bungeecordの場合主幹サーバーとなっているものを選択してください。',
    'no_parent_server' => '親サーバーなし',
    'bungee_instance' => 'BungeeCord 主幹サーバーですか？',
    'bungee_instance_help' => 'サーバーが BungeeCord 主幹サーバーである場合はこのオプションを選択してください。',
    'server_query_information' => 'Web サイトにオンラインプレーヤーのリストを表示するには、サーバーの <strong>server.properties</strong> ファイルで \'enable-query\' オプションを<strong>有効</strong>にする必要があります',
    'enable_status_query' => 'ステータスクエリを有効にしますか？',
    'status_query_help' => '有効にすると、ステータスページに接続上可能状況を表示できます。',
    'show_ip_on_status_page' => 'Show IP on status page?',
    'show_ip_on_status_page_info' => 'If this is enabled, users will be able to view and copy the IP address when viewing the Status page.',
    'enable_player_list' => 'プレイヤーリストを有効にしますか？',
    'pre_1.7' => '1.7 より古い Minecraft を使用していますか？',
    'player_list_help' => '有効にすると、ステータスページにログインしているユーザーのリストが表示されます。',
    'server_query_port' => 'サーバークエリポート',
    'server_query_port_help' => 'これはサーバーの server.properties ファイルの query.port オプションの値と同じです。ただし、 server.properties ファイル内の enable-query オプションが true に設定されている必要があります。',
    'server_name_required' => 'サーバー名を入力して下さい。',
    'server_name_minimum' => 'サーバー名が　<strong>1文字</strong>　以上であることを確認してください。',
    'server_name_maximum' => 'サーバー名が <strong>20文字</strong> 以下であることを確認してください。',
    'server_address_required' => 'サーバーのアドレスを入力してください',
    'server_address_minimum' => 'サーバーアドレスが <strong>1文字</strong> 以上であることを確認してください。',
    'server_address_maximum' => 'サーバーアドレスが <strong>64文字</strong> 以下であることを確認してください。',
    'server_port_required' => 'サーバーポートを入力してください',
    'server_port_minimum' => 'サーバーポートが <strong>2文字</strong> 以上であることを確認してください。',
    'server_port_maximum' => 'サーバーポートが <strong>5文字</strong> 以下であることを確認してください。',
    'server_parent_required' => '親サーバーを選択して下さい。',
    'query_port_maximum' => 'クエリポートが <strong>5文字</strong> 以下であることを確認してください。',
    'server_created' => 'サーバーが正常に作成されました。',
    'confirm_delete_server' => '選択中のサーバーを削除してもよろしいですか？',
    'server_updated' => 'サーバーが正常に更新されました。',
    'editing_server' => 'サーバー編集',
    'server_deleted' => 'サーバーが正常に削除されました。',
    'unable_to_delete_server' => 'サーバーを削除できません。',
    'leave_port_empty_for_srv' => 'ポートが25565である場合、またはドメインがSRVレコードを使用している場合は、ポートを空のままにすることができます。',
    'viewing_query_error' => 'クエリエラー表示',
    'confirm_query_error_deletion' => 'このクエリエラーを削除してもよろしいですか？',
    'no_query_errors' => 'クエリエラーは記録されません。',
    'new_banner' => '<i class="fa fa-plus-circle"></i> 新しいバナー',
    'purge_errors' => 'エラー消去',
    'confirm_purge_errors' => 'すべてのエラーを消去してもよろしいですか？',
	'email_errors_purged_successfully' => 'メールエラーが正常に消去されました。',
	'error_deleted_successfully' => 'エラーは正常に削除されました。',
	'no_email_errors' => 'ログに記録されたメールエラーはありません。',
	'email_settings_updated_successfully' => 'メール設定が正常に更新されました。',
	'content' => 'コンテンツ',
    'mcassoc_help' => 'mcassoc はユーザーが登録したMinecraftアカウントを所有していることを確認するために使用できる外部サービスです。 この機能を使用するには、共有キーが必要で <a href="https://mcassoc.lukegb.com/" target="_blank"> ここ </a> でサインアップする必要があります。',
    'mcassoc_key' => 'mcassoc 共有キー',
    'mcassoc_instance' => 'mcassoc インスタンスキー',
    'mcassoc_instance_help' => '<a href="#" onclick="generateInstance();">クリックしてインスタンスキーを生成する</a>',
    'mcassoc_error' => '共有キーが正しく入力され、インスタンスキーが正しく生成されていることを確認してください。',
    'updated_mcassoc_successfully' => 'mcassoc 設定が正常に更新されました。',
    'force_premium_accounts' => 'プレミアム Minecraft アカウントを強制しますか？',
    'banner_background' => 'バナー背景',
    'query_interval' => 'クエリー間隔 (分単位で、5~60でなければなりません)',
    'player_graphs' => 'プレイヤーグラフ',
    'player_count_cronjob_info' => '次のコマンドを使用して {x} 分ごとにサーバーに照会するようにジョブを設定できます。',
    'status_page' => 'ステータスページを表示しますか？',
    'minecraft_settings_updated_successfully' => '設定が正常に更新されました。',
	'server_id_x' => 'サーバーID：{x}',// {x}を置き換えない
    'server_information' => 'サーバー情報',
    'query_information' => 'クエリ情報',
    'query_errors_purged_successfully' => 'クエリエラーが正常にパージされました。',
    'query_error_deleted_successfully' => 'クエリエラーが正常に削除されました。',
    'banner_updated_successfully' => 'バナーが正常に更新されました。 変更が有効になるまでに時間がかかることがあります。',

	// Modules
	'modules_installed_successfully' => '新しいモジュールが正常にインストールされました。',
	'enabled' => '有効',
	'disabled' => '無効',
	'enable' => '有効',
	'disable' => '無効',
	'module_enabled' => 'モジュールを有効にしました。',
	'module_disabled' => 'モジュールを無効にしました。',
	'author' => '作成者:',
	'author_x' => '作成者: {x}', // Don't replace {x}
	'module_outdated' => '選択したモジュールは NamelessMC バージョン 【{x}】 を推奨しています。現在の NamelessMC バージョンは 【{y}】 です。', // Don't replace "{x}" or "{y}"
	'find_modules' => 'モジュール検索',
	'view_all_modules' => 'すべてのモジュールを見る',
	'unable_to_retrieve_modules' => 'モジュールを取得できませんでした。',
	'module' => 'モジュール',
	'unable_to_enable_module' => '互換性のないモジュールを有効にすることはできません。',

	// Styles
	'templates' => 'テンプレート',
	'panel_templates' => 'Panel Templates',
	'template_outdated' => '選択したテンプレートは NamelessMC バージョン {x} に対応していますが、現在 NamelessMC バージョン {y} を実行しています。', // Don't replace "{x}" or "{y}"
	'active' => 'アクティブ',
	'deactivate' => '非アクティブ',
	'activate' => 'アクティベート',
	'warning_editing_default_template' => '<strong>警告</strong>: デフォルトテンプレートの編集は推奨しません。',
	'images' => '背景画像',
	'upload_new_image' => '画像アップロード',
	'reset_background' => '背景画像リセット',
	'install' => '<i class="fa fa-plus-circle"></i> インストール',
	'template_updated' => 'テンプレートが正常に更新されました。',
	'default' => 'デフォルト',
	'make_default' => 'デフォルトに設定',
	'default_template_set' => ' {x} をデフォルトのテンプレートに正常に設定しました。', // Don't replace {x}
	'template_deactivated' => 'テンプレートが無効になりました。',
	'template_activated' => 'テンプレートが有効になりました。',
	'permissions' => '権限',
	'setting_perms_for_x' => 'テンプレート {x} のアクセス許可を設定する。', // Don't replace {x}
	'templates_installed_successfully' => '新しいテンプレートが正常にインストールされました。',
	'confirm_delete_template' => 'このテンプレートを削除してもよろしいですか？',
	'delete' => '削除',
	'template_deleted_successfully' => 'テンプレートが正常に削除されました。',
	'background_image_x' => '背景画像: <strong>{x}</strong>', // Don't replace {x}
	'banner_image_x' => 'Banner image: <strong>{x}</strong>', // Don't replace {x}
	'background_directory_not_writable' => 'The <strong>uploads/backgrounds</strong> directory is not writable!',
	'template_banners_directory_not_writable' => 'The <strong>uploads/template_banners</strong> directory is not writable!',
	'template_banner_reset_successfully' => 'Banner reset successfully.',
	'template_banner_updated_successfully' => 'Banner updated successfully.',
	'reset_banner' => 'Reset Banner',
	'find_templates' => 'テンプレートを探す',
	'view_all_templates' => 'すべてのテンプレートを見る',
	'unable_to_retrieve_templates' => 'テンプレートを取得できませんでした。',
	'template' => 'テンプレート',
	'stats' => '統計',
	'downloads_x' => 'ダウンロード: {x}',
	'views_x' => 'ビュー: {x}',
	'rating_x' => '評価: {x}',
	'editing_template_x' => 'テンプレート編集 {x}', // Don't replace {x}
	'editing_template_file_in_template' => 'Editing file {x} in template {y}', // Don't replace {x} or {y}
	'cant_write_to_template' => 'テンプレートファイルに書き込み出来ませんでした。ファイルのアクセス許可を確認してください。',
	'unable_to_delete_template' => 'テンプレートを完全に削除できませんでした。 ファイルのアクセス許可を確認してください。',
	'background_reset_successfully' => '背景が正常にリセットされました。',
	'background_updated_successfully' => '背景が正常に更新されました。',
	'unable_to_enable_template' => '互換性のないテンプレートを有効にすることはできません。',

	// Users & groups
	'users' => 'ユーザー',
	'groups' => 'グループ',
	'group' => 'グループ',
	'new_user' => '<i class="fa fa-plus-circle"></i> 新しいユーザー',
	'creating_new_user' => '新規ユーザー作成',
	'registered' => '登録',
	'user_created' => 'ユーザーが正常に作成されました。',
	'cant_delete_root_user' => 'Root ユーザーは削除できません。',
	'cant_modify_root_user' => 'Root ユーザーのグループは変更できません。',
	'user_deleted' => 'ユーザーは正常に削除されました。',
	'confirm_user_deletion' => '<strong>{x}</strong> を削除してもよろしいですか？', // Don't replace {x}
	'validate_user' => 'ユーザー検証',
	'update_uuid' => 'UUID更新',
	'update_mc_name' => 'Minecraftユーザー名更新',
	'reset_password' => 'パスワードリセット',
	'punish_user' => '処罰設定',
	'delete_user' => 'ユーザー削除',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'その他の操作',
	'disable_avatar' => 'アバター無効',
	'select_user_group' => 'ユーザーのグループを選択する必要があります。',
	'uuid_max_32' => 'UUID が <strong>32文字</strong> 以下であることをご確認ください。',
	'title_max_64' => 'ユーザーのタイトル が　<strong>64文字</strong> 以下であることをご確認ください。',
	'group_id' => 'グループ ID',
	'name' => '名前',
	'title' => 'ユーザータイトル',
	'new_group' => '<i class="fa fa-plus-circle"></i> 新しいグループ',
	'group_name_required' => 'グループ名を入力してください。',
	'group_name_minimum' => 'グループ名が <strong>2文字</strong> 以上であることをご確認ください。',
	'group_name_maximum' => 'グループ名が <strong>20文字</strong> 以下であることをご確認ください。',
	'creating_group' => '新規グループ作成',
	'group_html_maximum' => 'グループ HTML が <strong>1024文字</strong> 以下であることをご確認ください。',
	'group_html' => 'グループ HTML',
	'group_html_lg' => 'グループ HTML(大)',
	'group_username_colour' => 'グループテーマカラー',
	'group_staff' => 'スタッフグループに設定しますか？',
	'delete_group' => 'グループ削除',
	'confirm_group_deletion' => 'グループ {x} を削除してもよろしいですか？', // Don't replace {x}
	'group_not_exist' => '指定したグループは存在しません。',
	'secondary_groups' => 'セカンダリグループ',
	'secondary_groups_info' => 'セカンダリグループを設定したユーザーは、セカンダリグループからメイングループにプラスして権限を取得します。複数のグループを 選択/選択解除 するには 「Ctrl+クリック」 します。',
	'unable_to_update_uuid' => 'UUID を更新できませんでした。',
	'default_group' => 'デフォルトグループに設定しますか？ (新規ユーザー向け)',
	'user_id' => 'User ID',
	'uuid' => 'UUID',
	'group_order' => 'グループ順番',
	'group_created_successfully' => 'グループが正常に作成されました。',
	'group_updated_successfully' => 'グループが正常に更新されました。',
	'group_deleted_successfully' => 'グループが正常に削除されました。',
	'unable_to_delete_group' => 'デフォルトのグループ、またはStaffCPを表示できるグループを削除できません。 グループ設定を更新してください。',
	'can_view_staffcp' => 'StaffCPを表示させますか？',
	'user' => 'ユーザー',
	'user_validated_successfully' => 'ユーザーが正常に認証されました。',
	'user_updated_successfully' => 'ユーザーが正常に更新されました。',
	'editing_user_x' => 'ユーザー編集 {x}', // Don't replace {x}
	'details' => '詳細',

	// Permissions
	'select_all' => 'すべて選択',
	'deselect_all' => 'すべて選択を解除',
	'background_image' => '背景画像',
	'can_edit_own_group' => '自分のグループの権限を編集できます。',
	'permissions_updated_successfully' => '権限設定が正常に更新されました。',
	'cant_edit_this_group' => '指定されたグループの権限を編集することはできません。',

	// General Admin language
	'task_successful' => 'タスクは成功しました。',
	'invalid_action' => '無効な操作',
	'enable_night_mode' => 'ナイトモード有効化',
	'disable_night_mode' => 'ナイトモード無効化',
	'view_site' => 'ホームページ表示',
	'signed_in_as_x' => '{x} でログイン中', // Don't replace {x}
    'warning' => '警告',

    // Maintenance
    'maintenance_mode' => 'メンテナンスモード',
    'maintenance_enabled' => 'メンテナンスモードが有効になっています。',
    'enable_maintenance_mode' => 'メンテナンスモードを有効にしますか？',
    'maintenance_mode_message' => 'メンテナンスモードメッセージ',
    'maintenance_message_max_1024' => 'メンテナンスメッセージが <strong>1024文字</strong>　以下であることをご確認ください。',

	// Security
	'acp_logins' => 'StaffCP ログイン',
	'please_select_logs' => '表示するログを選択してください。',
	'ip_address' => 'IP アドレス',
	'template_changes' => 'テンプレート変更',
	'file_changed' => 'ファイルが変更されました。',
	'all_logs' => '全ログ',
	'action' => '操作',
	'action_info' => '操作情報',

	// Updates
	'update' => 'アップデート',
	'current_version_x' => '現在のバージョン: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => '新しいバージョン: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'NamelessMC のアップデートがあります。',
	'new_urgent_update_available' => '<strong>【緊急】</strong>: NamelessMC の緊急更新があります。今すぐに更新を実行してください。',
	'up_to_date' => '使用中の NamelessMC のバージョンは最新です。',
	'urgent' => '<strong>【緊急】</strong>: このアップデートは、緊急アップデートです。',
	'changelog' => '変更ログ',
	'update_check_error' => '更新エラー：',
	'instructions' => '指示',
	'download' => 'ダウンロード',
	'install_confirm' => 'パッケージをダウンロードし、含まれているファイルを最初にアップロードしたことを確認してください。',
	'check_again' => '確認する',

	// Widgets
	'widgets' => 'ウィジェット',
	'widget_enabled' => 'ウィジェットを有効化しました。',
	'widget_disabled' => 'ウィジェットを無効化しました。',
	'widget_updated' => 'ウィジェットを更新しました。',
	'editing_widget_x' => 'ウィジェット {x} の編集', // Don't replace {x}
	'module_x' => 'モジュール: {x}', // Don't replace {x}
	'widget_order' => 'ウィジェット順番',

    // Online users widget
    'include_staff_in_user_widget' => 'スタッフウィジェットをユーザーウィジェットに含めますか？',
    'show_nickname_instead_of_username' => 'Show user\'s nickname instead of username?',

    // Custom Pages
    'pages' => 'ページ',
    'custom_pages' => 'ページ',
    'new_page' => '<i class="fa fa-plus-circle"></i> 新しいページ',
    'no_custom_pages' => 'ページはまだ作成されていません。',
    'creating_new_page' => 'ページ作成',
    'page_title' => 'ページタイトル',
    'page_path' => 'ページパス (例: / , /example)',
    'page_icon' => 'ページ アイコン',
    'page_link_location' => 'ページリンクの場所',
    'page_link_navbar' => 'ナビゲーションバー',
    'page_link_footer' => 'フッター',
    'page_link_more' => '"さらに"ドロップダウン',
    'page_link_none' => 'リンクなし',
    'page_content' => 'ページ コンテンツ',
    'page_redirect' => 'ページをリダイレクトしますか？',
    'page_redirect_to' => 'リダイレクトリンク (http:// から入力してください。)',
    'unsafe_html' => '安全でない HTML を許可しますか？',
    'unsafe_html_warning' => 'このオプションを有効にすると、危険な JavaScript を含む HTML をページで使用することを許可します。 HTMLが安全であると確認できた場合のみ、有効にしてください。',
    'include_in_sitemap' => 'サイトマップを含めますか？',
    'sitemap_link' => 'Sitemap link:',
    'page_permissions' => 'ページのアクセス権利',
    'view_page' => 'ページを表示しますか？',
    'editing_page_x' => '編集ページ: {x}', // Don't replace {x}
    'unable_to_create_page' => 'ページを作成できません:',
    'page_title_required' => 'ページタイトルが必要です。',
    'page_url_required' => 'ページパスが必要です。',
    'link_location_required' => 'リンクの場所が必要です。',
    'page_title_minimum_2' => 'ページタイトルは　<strong>2文字</strong>　以上にする必要があります。',
    'page_url_minimum_2' => 'ページパスは <strong>2文字</strong> 以上にする必要があります。',
    'page_title_maximum_30' => 'ページタイトルは <strong>30文字</strong> 以下にする必要があります。',
    'page_icon_maximum_64' => 'ページアイコンは <strong>64文字</strong> 以下にする必要があります。',
    'page_url_maximum_20' => 'ページパスは <strong>20文字</strong> 以下にする必要があります。',
    'page_content_maximum_100000' => 'ページの内容は <strong>100000文字</strong> 以下にする必要があります。',
    'page_redirect_link_maximum_512' => 'ページリダイレクトリンクは <strong>512文字</strong> 以下にする必要があります。',
    'confirm_delete_page' => '選択中のページを削除してもよろしいですか？',
    'page_created_successfully' => 'ページが正常に作成されました。',
    'page_updated_successfully' => 'ページが正常に更新されました。',
    'page_deleted_successfully' => 'ページが正常に削除されました。',

    // API
    'api' => 'API',
    'enable_api' => 'API を有効にしますか？',
    'api_info' => 'API連携を有効にすると、Minecraftサーバーにインストールできる <a href="https://namelessmc.com/resources/resource/5-namelessplugin/" target="_blank" >Namelessプラグイン</a> とWebサイトが連携できるようになります。連携すると、Webサーバーのデータを読み込めたり、ゲーム内で登録できたり、アカウント検証ができるようになります。',
    'enable_legacy_api' => '従来のAPI(v1)を使用しますか？',
    'legacy_api_info' => '従来のAPIを使用すると、 Nameless v1 APIを使用するプラグインを、 v2 のWebサイトと連携できるようにできます。',
    'confirm_api_regen' => 'APIキーを再生成してもよろしいですか？',
	'api_key' => 'API キー',
	'api_url' => 'API URL',
	'copy' => 'コピー',
	'api_key_regenerated' => 'APIキーが正常に再生成されました。',
    'api_registration_email' => 'API登録メール',
    'show_registration_link' => '登録リンクを表示する',
    'registration_link' => '登録リンク',
    'link_to_complete_registration' => '登録を完了するためのリンク: {x}', // Don't replace {x}
    'api_verification' => 'APIの確認を有効にしますか？',
    'api_verification_info' => '有効にすると、登録時のアカウント検証に Namelessプラグイン を使用できるようになり、本当にMinecraftアカウントを所持しているのかがわかるようになります。</br><strong>このオプションは、メール認証が有効になっていても優先になります。検証が完了すると自動でアカウントが有効になります。</strong></br>初期グループを設定し、未検証アカウントのアクセス許可を確認してください。その後、「StaffCP → コンフィグ → 登録」の検証後昇格先グループを、通常のアクセス許可を持つグループに更新する必要があります。',
    'enable_username_sync' => 'ユーザーネームの同期を有効にしますか？',
    'enable_username_sync_info' => '有効にすると、WebサイトのユーザーネームをMinecraftユーザーネームと同期します。',
	'api_settings_updated_successfully' => 'API設定が正常に更新されました。',
	'group_sync' => 'グループ同期',
	'group_sync_info' => 'ゲーム(サーバー)内グループが変更されたときにユーザーのウェブサイトグループを自動的に更新するようにAPIを設定できます。 ゲーム内のグループ名と同期するウェブサイトグループを入力してください。',
	'ingame_group' => 'ゲーム内のグループ名',
	'website_group' => 'ウェブサイトグループ',
	'set_as_primary_group' => 'プライマリグループとして設定しますか？',
	'set_as_primary_group_info' => '有効にするとユーザーのプライマリWebサイトグループが自動で更新されます。無効にするとゲーム内のグループがユーザーのウェブサイトのセカンダリグループに追加されます。 ',
	'ingame_group_maximum' => 'グループ名は <strong>64文字</strong> 以下にする必要があります。',
	'select_website_group' => 'ウェブサイトグループを選択してください。',
	'ingame_group_already_exists' => '指定されたゲームグループのランク同期ルールはすでに作成されています。',
	'group_sync_rule_created_successfully' => 'グループ同期ルールが正常に作成されました。',
	'group_sync_rules_updated_successfully' => 'グループ同期ルールが正常に更新されました。',
	'group_sync_rule_deleted_successfully' => 'グループ同期ルールが正常に削除されました。',
	'existing_rules' => '既存のルール',
	'new_rule' => '新しいルール',

	// File uploads
	'drag_files_here' => 'アップロードするファイルをここにドラッグします。',
	'invalid_file_type' => '許可されていない拡張子または種類です。',
	'file_too_big' => 'ファイルサイズが大きすぎます。圧縮や画質を下げてもう一度お試しください。 送信されたファイルは {{filesize}} です。上限は {{maxFilesize}} です。', // Don't replace {{filesize}} or {{maxFilesize}}
	'allowed_proxies' => '許可されるプロキシ',
	'allowed_proxies_info' => '許可されたプロキシIPのリスト',

	// Error logs
	'error_logs' => 'エラーログ',
	'notice_log' => '通知ログ',
	'warning_log' => '警告ログ',
	'custom_log' => 'カスタムログ',
	'other_log' => 'その他ログ',
	'fatal_log' => '致命的ログ',
	'log_file_not_found' => 'ログファイルが見つかりませんでした。',
	'log_purged_successfully' => 'ログは正常に削除されました。',

	// Hooks
	'hooks' => 'Webhooks',
	'hooks_info' => 'Webhooks allow external services to be notified when certain events happen. When the specified events happen.',
	'new_hook' => 'New Hook',
	'creating_new_hook' => 'Creating New Webhook',
	'editing_hook' => 'Editing Webhook',
	'delete_hook' => 'Are you sure you want to delete this hook?',
	'hook_url' => 'Webhook URL',
	'hook_type' => 'Webhook Type',
	'hook_events' => 'Events to trigger this webhook',
	'invalid_hook_url' => 'Invalid webhook url',
	'invalid_hook_events' => 'You must select at least 1 event',
	'register_hook_info' => 'ユーザー登録',
	'validate_hook_info' => 'ユーザー検証',
	'delete_hook_info' => 'ユーザー削除',

	//サイトマップ
	'unable_to_load_sitemap_file_x' => 'サイトマップファイル {x} を読み込むことができませんでした。',// {x}を置き換えない
	'sitemap_generated' => 'サイトマップが正常に生成されました。',
	'sitemap_not_writable' => '<strong>cache/sitemaps</strong> ディレクトリに書き込みができません。アクセス権限を確認してください。',
	'cache_not_writable' => '<strong>cache</strong>　ディレクトリに書き込みができません。アクセス権限を確認してください。',
	'generate_sitemap' => 'サイトマップ生成',
	'download_sitemap' => 'ダウンロードサイトマップ',
	'sitemap_not_generated_yet' => '生成済みのサイトマップが存在しません。',
	'sitemap_last_generated_x' => '最終サイトマップ生成:{x}',// {x}を置き換えない

	//ページメタデータ
	'page_metadata' => 'ページメタデータ',
	'metadata_page_x' => '{x} のメタデータを表示',// {x}を置き換えない
	'keywords' => 'キーワード',
	'description_max_500' => '説明は　<strong>500文字</strong>　以下にする必要があります。',
	'page' => 'Page',
	'metadata_updated_successfully' => 'メタデータが正常に更新されました。',

	//ダッシュボード
	'total_users' => '合計ユーザー',
	'total_users_statistic_icon' => '<i class="fas fa-users"> </i>',
	'recent_users' => '新規ユーザー',
	'recent_users_statistic_icon' => '<i class="fas fa-users"> </i>',
	'average_players' => '平均ユーザー',
	'nameless_news' => 'NamelessMC ニュース',
	'unable_to_retrieve_nameless_news' => '最新のニュースを取得できませんでした。',
	'confirm_leave_site' => 'サイトを離れようとしています。 <strong id = "leaveSiteURL"> {x} </strong> にアクセスしてもよろしいですか？ ',// {x}を置き換えずに、そのIDがleaveSiteURLであることを確認してください
	'server_compatibility' => 'サーバーの互換性',
	'issues' => '問題',

	//その他
	'source' => 'ソース',
	'support' => 'サポート',
	'admin_dir_still_exists' => '【警告】 <strong> modules/Core/pages/admin </strong>ディレクトリがまだ存在しています。 ディレクトリを削除してください。 ',
	'mod_dir_still_exists' => '【警告】 <strong> modules/Core/pages/mod </strong>ディレクトリがまだ存在しています。 ディレクトリを削除してください。'
);
