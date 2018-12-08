<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  Translation by SimplyRin(@SimplyRin_, https://www.simplyrin.net)
 *
 *  License: MIT
 */

/*
 *  Japanese Language
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'infractions' => '違反行為',
	'invalid_token' => 'トークンが無効です。やり直して下さい。',
	'invalid_action' => '無効なアクション',
	'successfully_updated' => '正常に更新されました',
	'settings' => '設定',
	'confirm_action' => 'アクションを確認する',
	'edit' => '編集',
	'actions' => 'アクション',
	'task_successful' => 'タスクが正常に実行されました。',
	
	// Admin login
	're-authenticate' => '再認証してください。',
	
	// Admin sidebar
	'index' => '概要',
	'announcements' => 'お知らせ',
	'core' => '設定',
	'custom_pages' => 'カスタムページ',
	'general' => '一般',
	'forums' => 'フォーラム',
	'users_and_groups' => 'ユーザーとグループ',
	'minecraft' => 'Minecraft',
	'style' => 'スタイル',
	'addons' => 'アドオン',
	'update' => 'アップデート',
	'misc' => 'その他',
	'help' => 'ヘルプ',
	
	// Admin index page
	'statistics' => '統計情報',
	'registrations_per_day' => '1日 (過去7日) 登録数',
	
	// Admin announcements page
	'current_announcements' => '現在のお知らせ',
	'create_announcement' => 'アナウンスメントを作成',
	'announcement_content' => 'アナウンスメントコンテンツ',
	'announcement_location' => 'アナウンスメントロケーション',
	'announcement_can_close' => 'アナウンスメントを閉じることができますか？',
	'announcement_permissions' => 'アナウンスメント アクセス権利',
	'no_announcements' => 'アナウンスメントはまだ作成されていません。',
	'confirm_cancel_announcement' => 'あなたはこのアナウンスメントをキャンセルしてもよろしいですか？',
	'announcement_location_help' => 'Ctrlキーを押しながらクリックして複数のページを選択',
	'select_all' => 'すべて選択',
	'deselect_all' => 'すべての選択を解除',
	'announcement_created' => 'アナウンスメントが正常に作成されました',
	'please_input_announcement_content' => 'アナウンスメントの内容と種類を入力し選択してください',
	'confirm_delete_announcement' => 'このアナウンスを削除してもよろしいですか？',
	'announcement_actions' => 'アナウンスメントアクション',
	'announcement_deleted' => 'アナウンスメントが正常に作成されました',
	'announcement_type' => 'アナウンスメントタイプ',
	'can_view_announcement' => 'アナウンスメントを表示することができますか？',
	
	// Admin core page
	'general_settings' => '全般設定',
	'modules' => 'モジュール',
	'module_not_exist' => 'そのモジュールが存在しません!',
	'module_enabled' => 'モジュールを有効にしました。',
	'module_disabled' => 'モジュールを無効にしました。',
	'site_name' => 'サイト名',
	'language' => '言語',
	'voice_server_not_writable' => 'core/voice_server.php への書き込み可能がありません。ファイルのアクセス権利を確認してください。',
	'email' => 'メールアドレス',
	'incoming_email' => '受信メールアドレス',
	'outgoing_email' => '送信メールアドレス',
	'outgoing_email_help' => 'PHP メール機能が有効な場合にのみ必要です。',
	'use_php_mail' => 'PHP の mail() 関数を使用しますか？',
	'use_php_mail_help' => 'おすすめ: 有効。 あなたのウェブサイトが電子メールを送信していない場合は、これを無効にしてあなたの電子メール設定で core/email.php を編集してください。',
	'use_gmail' => '電子メールの送信にGmailを使用しますか？',
	'use_gmail_help' => 'PHPのメール機能が無効になっている場合にのみ使用できます。 Gmailを使用しない場合は、SMTPが使用されます。 いずれにしても、
 core/email.php で設定する必要があります。',
	'enable_mail_verification' => 'メールアカウントの確認を有効にしますか？',
	'enable_email_verification_help' => 'これを有効にすると、登録を完了する前に、新規に登録したユーザーにEメールでアカウントの確認を求めます。',
	'explain_email_settings' => '「PHP mail() 関数を使用する」オプションが<strong>無効</strong>になっている場合は、以下が必要です。 これらの設定に関するドキュメントは、<a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">Wiki</a>にあります。',
	'email_config_not_writable' => 'あなたの <strong>core/email.php</strong> ファイルへの書き込み権利がありません。 ファイルのアクセス権利を確認してください。',
	'pages' => 'ページ',
	'enable_or_disable_pages' => 'ページを有効または無効にします。',
	'enable' => '有効',
	'disable' => '無効',
	'maintenance_mode' => 'フォーラムのメンテナンスモード',
	'forum_in_maintenance' => 'フォーラムは現在メンテナンス中です。',
	'unable_to_update_settings' => '設定を更新できません。 フィールドが空でないことを確認してください。',
	'editing_google_analytics_module' => 'Googleアナリティクスモジュールの編集',
	'tracking_code' => 'トラッキングコード',
	'tracking_code_help' => '周囲のスクリプトタグを含め、Googleアナリティクスのトラッキングコードをここに入力します。',
	'google_analytics_help' => '手順1〜3に従って、詳細については、この <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">ガイド</a> を参照してください。',
	'social_media_links' => 'ソーシャルメディアリンク',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL ( 最後に "/" を書かないで下さい )',
	'twitter_dark_theme' => 'ダークTwitterテーマを使用しますか？',
	'twitter_widget_id' => 'Twitter ウィジェット ID',
	'google_plus_url' => 'Google+ URL',
	'facebook_url' => 'Facebook URL',
	'registration' => '登録',
	'registration_warning' => 'このモジュールを無効にすると、サイトに登録している新しいメンバーも無効になります。',
	'google_recaptcha' => 'Google reCAPTCHA を有効にします。',
	'recaptcha_site_key' => 'reCAPTCHA サイトキー',
	'recaptcha_secret_key' => 'reCAPTCHA シークレットキー',
	'registration_terms_and_conditions' => '登録の利用規約',
	'voice_server_module' => '音声サーバーモジュール',
	'only_works_with_teamspeak' => 'このモジュールは現在、 TeamSpeak と Discord でのみ動作します',
	'discord_id' => 'Discord サーバー ID',
	'voice_server_help' => 'ServerQuery ユーザーの詳細を入力してください',
	'ip_without_port' => 'IP (ポートなし)',
	'voice_server_port' => 'ポート (通常 10011)',
	'virtual_port' => 'バーチャルポート (通常 9987)',
	'permissions' => 'アクセス許可:',
	'view_applications' => 'アプリケーションを表示しますか？',
	'accept_reject_applications' => 'アプリケーションの承認/拒否？',
	'questions' => '質問:',
	'question' => '質問',
	'type' => 'タイプ',
	'options' => 'オプション',
	'options_help' => '新しい行オプション。 空のままにすることができます (ドロップダウンのみ)',
	'no_questions' => '質問はまだ追加されていません。',
	'new_question' => '新しい質問　',
	'editing_question' => '質問の編集',
	'delete_question' => '質問を削除',
	'dropdown' => 'ドロップダウン',
	'text' => 'テキスト',
	'textarea' => 'テキスト エリア',
	'question_deleted' => '質問が削除されました',
	'use_followers' => 'フォロワーシステムを使いますか？',
	'use_followers_help' => '無効にした場合は、フレンド システムが使用されます。',
	
	// Admin custom pages page
	'click_on_page_to_edit' => '編集するページをクリックしてください。',
	'page' => 'ページ:',
	'url' => 'URL:',
	'page_url' => 'ページ URL',
	'page_url_example' => '(先行する "/", 例: /help/)',
	'page_title' => 'ページタイトル',
	'page_content' => 'ページ コンテンツ',
	'new_page' => '新しいページ',
	'page_successfully_created' => 'ページが正常に作成されました',
	'page_successfully_edited' => 'ページが正常に編集されました',
	'unable_to_create_page' => 'ページを作成することができません。',
	'unable_to_edit_page' => 'ページを編集することができません。',
	'create_page_error' => '1 ~ 20 文字の長さ、1 ~ 30 文字、5 文字 20480 ページ コンテンツ ページ タイトルと URL を入力したか確認してください。',
	'delete_page' => 'ページを削除',
	'confirm_delete_page' => 'このページを削除してもよろしいですか？',
	'page_deleted_successfully' => 'ページが正常に削除されました。',
	'page_link_location' => '表示ページのリンク:',
	'page_link_navbar' => 'ナビゲーションバー',
	'page_link_more' => 'ナビゲーションバー "もっと" ドロップダウン',
	'page_link_footer' => 'ページ フッター',
	'page_link_none' => 'ページリンクなし',
	'page_permissions' => 'ページのアクセス権利',
	'can_view_page' => '表示可能ページ:',
	'redirect_page' => 'ページをリダイレクトしますか？',
	'redirect_link' => 'リダイレクトリンク',
	'page_icon' => 'ページ アイコン',
	
	// Admin forum page
	'labels' => 'トピックラベル',
	'new_label' => '新しいラベル',
	'no_labels_defined' => '定義されているラベルはありません。',
	'label_name' => 'ラベル名',
	'label_type' => 'ラベルのタイプ',
	'label_forums' => 'ラベル フォーラム',
	'label_creation_error' => 'ラベルの作成中にエラーが発生しました。 名前が32文字以下で、タイプが指定されていることを確認してください。',
	'confirm_label_deletion' => 'このラベルを削除してもよろしいですか？',
	'editing_label' => 'ラベルの編集',
	'label_creation_success' => 'ラベルが正常に作成されました',
	'label_edit_success' => 'ラベルが正常に編集されました',
	'label_default' => 'デフォルト',
	'label_primary' => 'プライマリ',
	'label_success' => '成功',
	'label_info' => '情報',
	'label_warning' => '警告',
	'label_danger' => '危険',
	'new_forum' => '新しいフォーラム',
	'forum_layout' => 'フォーラムのレイアウト',
	'table_view' => 'テーブル ビュー',
	'latest_discussions_view' => '最新のディスカッションビュー',
	'create_forum' => 'フォーラムを作成します。',
	'forum_name' => 'フォーラム名',
	'forum_description' => 'フォーラムの説明',
	'delete_forum' => 'フォーラムを削除',
	'move_topics_and_posts_to' => 'トピックと投稿を',
	'delete_topics_and_posts' => 'トピックと投稿を削除する',
	'parent_forum' => '親フォーラム',
	'has_no_parent' => '親はいない',
	'forum_permissions' => 'フォーラムのアクセス許可',
	'can_view_forum' => 'フォーラムを表示できます',
	'can_create_topic' => 'トピックを作成できます',
	'can_post_reply' => '返信することができます',
	'display_threads_as_news' => 'フロントページのスレッドとしてスレッドを表示しますか？',
	'input_forum_title' => 'フォーラムのタイトルを入力してください。',
	'input_forum_description' => 'フォーラムの説明を入力します (HTMLを使用することができます)。',
	'forum_name_minimum' => 'フォーラム名は最低 2 文字をする必要があります。',
	'forum_description_minimum' => 'フォーラムの説明は、2 文字以上にする必要があります。',
	'forum_name_maximum' => 'フォーラム名は最大 150 文字をする必要があります。',
	'forum_description_maximum' => 'フォーラムの説明には、最大 255 文字をする必要があります。',
	'forum_type_forum' => 'ディスカッション フォーラム',
	'forum_type_category' => 'カテゴリ',
	
	// Admin Users and Groups page
	'users' => 'ユーザー',
	'new_user' => '新しいユーザー',
	'created' => '作成',
	'user_deleted' => 'ユーザーが削除されました',
	'validate_user' => 'ユーザーを検証します。',
	'update_uuid' => 'UUID を更新します。',
	'unable_to_update_uuid' => 'UUID を更新できません。',
	'update_mc_name' => 'Minecraft 名前を更新する',
	'reset_password' => 'パスワードをリセット',
	'punish_user' => '悪質なユーザー',
	'delete_user' => 'ユーザーを削除',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP アドレス',
	'ip' => 'IP:',
	'other_actions' => 'その他のアクション:',
	'disable_avatar' => 'アバターを無効にします。',
	'enable_avatar' => 'アバターを有効にします。',
	'confirm_user_deletion' => 'ユーザー {x} を削除してもよろしいですか？', // Don't replace "{x}"
	'groups' => 'グループ',
	'group' => 'グループ',
	'group2' => 'グループ 2',
	'new_group' => '新しいグループ',
	'id' => 'ID',
	'name' => '名前',
	'create_group' => 'グループを作成',
	'group_name' => 'グループ名',
	'group_html' => 'グループ HTML',
	'group_html_lg' => 'グループ HTML が大きい',
	'donor_group_id' => 'ドナー パッケージ ID',
	'donor_group_id_help' => '<p>これは、Buycraft、MinecraftMarketまたはMCStockからのグループパッケージのIDです。</p><p>これは空のままにできます。</p>',
	'donor_group_instructions' => 	'<p>ドナーグループは、<strong>最低値から最高値</strong>の順に作成する必要があります。</p>
<p>たとえば£10パッケージが20ポンドパッケージの前に作成されます。</p>',
	'delete_group' => 'グループの削除',
	'confirm_group_deletion' => 'グループ {x} を削除してもよろしいですか？', // Don't replace "{x}"
	'group_staff' => 'グループはスタッフグループですか？',
	'group_modcp' => 'グループは ModCP を表示できますか？',
	'group_admincp' => 'グループは AdminCP を表示できますか？',
	'group_name_required' => 'グループ名を入力する必要があります。',
	'group_name_minimum' => 'グループ名は 2 文字以上にする必要があります。',
	'group_name_maximum' => 'グループ名は最大 20 文字でなければなりません。',
	'html_maximum' => 'グループHTMLは、最大 1024 文字でなければなりません。',
	'select_user_group' => 'ユーザーはグループに属している必要があります。',
	'uuid_max_32' => 'UUID は、最大 32 文字にする必要があります。',
	'cant_delete_root_user' => 'Root ユーザーは削除できません!',
	'cant_modify_root_user' => '管理者のグループを変更することはできません。',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft の設定',
	'use_plugin' => 'Nameless API を有効にしますか？',
	'force_avatars' => 'Minecraft アバターを強制設定しますか？',
	'uuid_linking' => 'UUID のリンクを有効にしますか？',
	'use_plugin_help' => 'APIを有効にすると、今後のサーバープラグインと同様に、ランクの同期が可能になり、登録やレポートの送信が可能になります。',
	'uuid_linking_help' => '無効にすると、ユーザーアカウントはUUIDとリンクされません。 これを有効にしておくことを強くお勧めします。',
	'plugin_settings' => 'プラグインの設定',
	'confirm_api_regen' => '新しいAPIキーを生成してもよろしいですか？',
	'servers' => 'サーバー',
	'new_server' => '新しいサーバー',
	'confirm_server_deletion' => 'このサーバーを削除してもよろしいですか？',
	'main_server' => 'メイン サーバー',
	'main_server_help' => 'サーバープレーヤーが接続します。 通常これはBungeeのインスタンスになります。',
	'choose_a_main_server' => 'メイン サーバーを選択...',
	'external_query' => '外部クエリを使用しますか？',
	'external_query_help' => '外部APIを使用してMinecraftサーバーを照会しますか？ 組み込みのクエリが機能しない場合にのみこれを使用してください。 これはアンティッキであることを強くお勧めします。',
	'editing_server' => 'サーバー {x} の編集', // Don't replace "{x}"
	'server_ip_with_port' => 'サーバーIP (ポートあり) (ドメインまたはIP)',
	'server_ip_with_port_help' => 'これはユーザーに表示されるIPです。 それは照会されません。',
	'server_ip_numeric' => 'サーバーIP (ポートあり) (IPのみ)',
	'server_ip_numeric_help' => 'これは照会されるIPです。数値であることを確認してください。 ユーザーには表示されません。',
	'show_on_play_page' => 'プレイページに表示しますか？',
	'pre_17' => '~1.7 Minecraft のバージョンですか？',
	'server_name' => 'サーバー名',
	'invalid_server_id' => '無効なサーバー ID',
	'show_players' => 'プレイページにプレイヤーリストを表示しますか？',
	'server_edited' => 'サーバー正常に編集',
	'server_created' => 'サーバーが正常に作成されました',
	'query_errors' => 'クエリ エラー',
	'query_errors_info' => '次のエラーにより、内部サーバークエリの問題を診断できます。',
	'no_query_errors' => 'クエリエラーの記録なし',
	'date' => '日付:',
	'port' => 'ポート:',
	'viewing_error' => 'エラーを表示',
	'confirm_error_deletion' => 'このエラーを削除してもよろしいですか？',
	'display_server_status' => 'サーバー ステータスのモジュールを表示',
	'server_name_required' => 'サーバー名を入力する必要があります。',
	'server_ip_required' => 'サーバーの IP アドレスを入力する必要があります。',
	'server_name_minimum' => 'サーバー名は、2 文字以上にする必要があります。',
	'server_ip_minimum' => 'サーバーの IP アドレスは、2 文字以上でなければなりません。',
	'server_name_maximum' => 'サーバー名は最大 20 文字でなければなりません。',
	'server_ip_maximum' => 'サーバーIPは、最大 64 文字でなければなりません。',
	'purge_errors' => 'エラーを消去',
	'confirm_purge_errors' => 'すべてのクエリエラーを消去してもよろしいですか？',
	'avatar_type' => 'アバタータイプ',
	'custom_usernames' => 'Minecraft ユーザー名を強制しますか？',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Mcassoc を使用しますか？',
	'use_mcassoc_help' => 'mcassoc は、ユーザーが登録している Minecraft アカウントを所有していることを確認します。',
	'mcassoc_key' => 'mcassoc 共有キー',
	'invalid_mcassoc_key' => '無効な mcassoc キー。',
	'mcassoc_instance' => 'mcassoc インスタンス',
	'mcassoc_instance_help' => '<a href="http://jsbin.com/jadofehoqu/1/" target="_blank">ここ</a> でインスタンスキーを生成できます。',
	'mcassoc_key_help' => '<a href="https://mcassoc.lukegb.com/" target="_blank">ここ</a> であなたの mcassoc キーを取得',
	
	// Admin Themes, Templates and Addons
	'themes' => 'テーマ',
	'templates' => 'テンプレート',
	'installed_themes' => 'インストールされているテーマ',
	'installed_templates' => 'インストールされているテンプレート',
	'installed_addons' => 'インストールされているアドオン',
	'install_theme' => 'テーマをインストール',
	'install_template' => 'テンプレートをインストール',
	'install_addon' => 'アドオンをインストール',
	'install_a_theme' => 'テーマをインストール',
	'install_a_template' => 'テンプレートをインストール',
	'install_an_addon' => 'アドオンをインストール',
	'active' => '有効',
	'activate' => '有効化',
	'deactivate' => '無効化',
	'theme_install_instructions' => 'テーマを <strong>styles/themes</strong> ディレクトリにアップロードしてください。 次に、下の「スキャン」ボタンをクリックしてください。',
	'template_install_instructions' => 'テンプレートを <strong>styles/templates</strong> ディレクトリにアップロードしてください。 次に、下の「スキャン」ボタンをクリックしてください。',
	'addon_install_instructions' => 'アドオンを <strong>addons</strong> ディレクトリにアップロードしてください。 次に、下の「スキャン」ボタンをクリックしてください。',
	'addon_install_warning' => 'アドオンは、自己責任でインストールされます。 先に進む前にファイルとデータベースをバックアップしてください',
	'scan' => 'スキャン',
	'theme_not_exist' => 'そのテーマは存在しません！',
	'template_not_exist' => 'そのテンプレートは存在しません！',
	'addon_not_exist' => 'そのアドオンは存在しません！',
	'style_scan_complete' => '完了しました。新しいスタイルがインストールされました。',
	'addon_scan_complete' => '完了すると、新しいアドオンがインストールされました。',
	'theme_enabled' => 'テーマが有効になりました。',
	'template_enabled' => 'テンプレートが有効になりました。',
	'addon_enabled' => 'アドオンが有効になりました。',
	'theme_deleted' => 'テーマが削除されました。',
	'template_deleted' => 'テンプレートが削除されました。',
	'addon_disabled' => 'アドオンが削除されました。',
	'inverse_navbar' => '逆ナビゲーションバー',
	'confirm_theme_deletion' => 'テーマ<strong> {x} </strong>を削除してもよろしいですか？<br /><br />テーマは<strong>styles/themes</strong>ディレクトリから削除されます。', // Don't replace {x}
	'confirm_template_deletion' => 'テンプレート<strong> {x} </strong>を削除してもよろしいですか？<br /><br />テンプレートは<strong>styles/templates</strong>ディレクトリから削除されます。', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'その他の設定',
	'enable_error_reporting' => 'エラー報告を有効にしますか？',
	'error_reporting_description' => 'これはデバッグ目的でのみ使用してください。無効にしておくことを強くお勧めします。',
	'display_page_load_time' => 'ページの読み込み時間を表示しますか？',
	'page_load_time_description' => 'これを有効にすると、フッターにスピードメーターが表示され、ページの読み込み時間が表示されます。',
	'reset_website' => 'ウェブサイトをリセット',
	'reset_website_info' => 'これはにより Web サイトの設定がリセットされます。<strong>アドオンは無効になりますが削除されず、設定は変更されません。</strong> 設定された Minecraft サーバーも残ります。',
	'confirm_reset_website' => 'ウェブサイトの設定をリセットしてもよろしいですか？',
	
	// Admin Update page
	'installation_up_to_date' => '最新バージョンの NamelessMC を使用しています。',
	'update_check_error' => '更新を確認できません。 後でもう一度お試しください。',
	'new_update_available' => '新しいアップデートが利用可能です。',
	'your_version' => '現在のバージョン:',
	'new_version' => '新しいバージョン:',
	'download' => 'ダウンロード',
	'update_warning' => '警告：パッケージをダウンロードし、含まれているファイルを最初にアップロードしたことを確認してください！'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'ホーム',
	'play' => 'プレイ',
	'forum' => 'フォーラム',
	'more' => 'もっと',
	'staff_apps' => 'スタッフのアプリケーション',
	'view_messages' => 'メッセージを表示',
	'view_alerts' => 'アラートを表示',
	
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
	'create_an_account' => 'アカウントを作成',
	'authme_password' => 'AuthMe パスワード',
	'username' => 'ユーザー名',
	'minecraft_username' => 'Minecraft ユーザー名',
	'email' => 'メールアドレス',
	'user_title' => 'タイトル',
	'email_address' => 'メール アドレス',
	'date_of_birth' => '生年月日',
	'location' => '場所',
	'password' => 'パスワード',
	'confirm_password' => 'パスワードを確認',
	'i_agree' => '同意します',
	'agree_t_and_c' => '<strong class="label label-primary">登録</strong>をクリックすると、<a href="#" data-toggle="modal" data-target="#t_and_c_m">利用規約</a>に同意したことになります。',
	'register' => '登録',
	'sign_in' => 'ログイン',
	'sign_out' => 'ログアウト',
	'terms_and_conditions' => '利用規約',
	'successful_signin' => 'あなたは正常にサインインしました。',
	'incorrect_details' => '不正確な詳細',
	'remember_me' => 'ログインしたままにする',
	'forgot_password' => 'パスワードを忘れた',
	'must_input_username' => 'ユーザー名を入力する必要があります。',
	'must_input_password' => 'パスワードを入力する必要があります。',
	'inactive_account' => 'あなたのアカウントが現在アクティブではないです。パスワードの再設定を要求しましたか？',
	'account_banned' => 'あなたのアカウントは Ban されています。',
	'successfully_logged_out' => 'あなたは正常にログアウトされました。',
	'signature' => '署名',
	'registration_check_email' => '電子メールで確認リンクを確認してください。 これがクリックされるまでログインすることはできません。',
	'unknown_login_error' => '申し訳ありませんが、ログイン中に不明なエラーが発生しました。しばらくしてからもう一度お試しください。',
	'validation_complete' => '登録ありがとうございます！ これでログインできます。',
	'validation_error' => 'リクエストの処理中にエラーが発生しました。 リンクをもう一度クリックしてください。',
	'registration_error' => 'すべてのフィールドに記入してください。ユーザー名は3〜20文字、パスワードは6〜30文字です。',
	'username_required' => 'ユーザー名を入力してください。',
	'password_required' => 'パスワードを入力してください。',
	'email_required' => 'メール アドレスを入力してください。',
	'mcname_required' => 'Minecraft のユーザー名を入力してください。',
	'accept_terms' => '登録する前に利用規約に同意する必要があります。',
	'invalid_recaptcha' => 'reCAPTCHA 応答が無効です。',
	'username_minimum_3' => 'ユーザー名の長さは 3 文字以上でなければなりません。',
	'username_maximum_20' => 'ユーザー名の長さは最大 20 文字です。',
	'mcname_minimum_3' => 'あなたのMinecraftユーザー名は 3 文字以上でなければなりません。',
	'mcname_maximum_20' => 'あなたのMinecraftユーザー名は、最大 20 文字の長さでなければなりません。',
	'password_minimum_6' => 'パスワードは6文字以上でなければなりません。',
	'password_maximum_30' => 'パスワードは30文字以内で入力してください。',
	'passwords_dont_match' => 'パスワードが一致しません。',
	'username_mcname_email_exists' => 'あなたのユーザー名は、Minecraft のユーザー名または電子メールアドレスが既に存在します。アカウントは登録済みですか？',
	'invalid_mcname' => 'あなたの Minecraft のユーザー名は、有効なアカウントではありません。',
	'mcname_lookup_error' => 'Mojang のサーバーと通信エラーが発生しました。もう一度やり直してください。',
	'signature_maximum_900' => '署名は最大 900 文字でなければなりません。',
	'invalid_date_of_birth' => '生年月日が無効です。',
	'location_required' => '場所を入力してください。',
	'location_minimum_2' => 'あなたの所在地は 2 文字以上でなければなりません。',
	'location_maximum_128' => 'あなたの所在地は最大 128 文字でなければなりません。',
	'verify_account' => 'アカウントを確認します。',
	'verify_account_help' => 'Minecraft アカウントを所有することを確認以下の手順に従ってください。',
	'verification_failed' => '検証に失敗しました、再試行してください。',
	'verification_success' => '正常に検証されました！ これでログインできます。',
	'complete_signup' => 'サインアップを完了します。',
	'registration_disabled' => 'ウェブサイトの登録は現在無効です。',
	
	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => '選択されたファイルがありません。',
	'private_messages' => 'プライベート メッセージ',
	'profile_settings' => 'プロフィールの設定',
	'your_profile' => 'あなたのプロフィール',
	'topics' => 'トピック',
	'posts' => 'ポスト',
	'reputation' => '評判',
	'friends' => 'フレンド',
	'alerts' => 'アラート',
	
	// Messaging
	'new_message' => '新しいメッセージ',
	'no_messages' => 'メッセージはありません。',
	'and_x_more' => '{x} より', // Don't replace "{x}"
	'system' => 'システム',
	'message_title' => 'メッセージタイトル',
	'message' => 'メッセージ',
	'to' => '宛先:',
	'separate_users_with_comma' => 'ユーザーを "," (カンマ) で区切ります',
	'viewing_message' => 'メッセージを表示',
	'delete_message' => 'メッセージを削除します。',
	'confirm_message_deletion' => 'このメッセージを削除してもよろしいですか？',
	
	// Profile settings
	'display_name' => '表示名',
	'upload_an_avatar' => 'アバター画像をアップロード (.jpg .png .gif のみ)',
	'use_gravatar' => 'Gravatar を使用しますか？',
	'change_password' => 'パスワードを変更',
	'current_password' => '現在のパスワード',
	'new_password' => '新しいパスワード',
	'repeat_new_password' => '新しいパスワードを再入力してください',
	'password_changed_successfully' => 'パスワードが正常に変更されました。',
	'incorrect_password' => '現在のパスワードが正しくありません。',
	'update_minecraft_name_help' => 'Minecraft の現在のユーザー名をあなたのウェブサイトのユーザー名が更新されます。30日に一度この操作を実行できます。',
	'unable_to_update_mcname' => 'Minecraft のユーザー名を更新できません。',
	'display_age_on_profile' => 'プロフィールに年齢を表示しますか？',
	'two_factor_authentication' => '2段階認証',
	'enable_tfa' => '2段階認証を有効にします。',
	'tfa_type' => '二段階認証の種類:',
	'authenticator_app' => '認証アプリ',
	'tfa_scan_code' => '認証アプリ内で次のコードをスキャンしてください:',
	'tfa_code' => 'お使いのデバイスにカメラがない、または、QR コードをスキャンすることができる場合は、次のコードを入力してください。',
	'tfa_enter_code' => '認証アプリに表示されるコードを入力してください:',
	'invalid_tfa' => '無効なコード、もう一度やり直してください。',
	'tfa_successful' => '二段階認証が設定されました。今からログインするたびに二段階認証をする必要があります。',
	'confirm_tfa_disable' => '二段階認証を無効にしてもよろしいですか？',
	'tfa_disabled' => '二段階認証が無効になっています。',
	'tfa_enter_email_code' => '確認のため、メールにコードをお送りしています。 今すぐコードを入力してください:',
	'tfa_email_contents' => 'アカウントへのログインが試行されました。 これがあなたの場合は、次の二段階認証コードを入力するように求められます。 これがあなたでない場合は、このメールを無視することはできますが、パスワードの再設定をお勧めします。 コードは10分間有効です。',
	
	// Alerts
	'viewing_unread_alerts' => '未読アラートの表示を <a href="/user/alerts/?view=read"><span class="label label-success">既読</span></a> に変更します。',
	'viewing_read_alerts' => '未読アラートの表示を <a href="/user/alerts/"><span class="label label-warning">未読</span></a> に変更します。',
	'no_unread_alerts' => '未読のアラートはありません。',
	'no_alerts' => 'アラートなし',
	'no_read_alerts' => '読まれたアラートはありません。',
	'view' => 'ビュー',
	'alert' => 'アラート',
	'when' => 'いつ',
	'delete' => '削除',
	'tag' => 'ユーザータグ',
	'tagged_in_post' => 'あなたは投稿にタグがついています',
	'report' => 'レポート',
	'deleted_alert' => 'アラートが正常に削除されました',
	
	// Warnings
	'you_have_received_a_warning' => 'あなたは {y} の日付 {x} から警告を受け取りました。', // Don't replace "{x}" or "{y}"
	'acknowledge' => '認める',
	
	// Forgot password
	'password_reset' => 'パスワードリセット',
	'email_body' => 'パスワードの再設定をリクエストしたため、このメールをお送りしています。 パスワードをリセットするには、次のリンクを使用してください:', // Body for the password reset email
	'email_body_2' => 'パスワードのリセットを要求していない場合は、このメールを無視することができます。',
	'password_email_set' => '成功。 詳しい手順についてはメールをご確認ください。',
	'username_not_found' => 'そのユーザー名が存在しません。',
	'change_password' => 'パスワードの変更',
	'your_password_has_been_changed' => 'パスワードが変更されました。',
	
	// Profile page
	'profile' => 'プロフィール',
	'player' => 'プレーヤー',
	'offline' => 'オフライン',
	'online' => 'オンライン',
	'pf_registered' => '登録:',
	'pf_posts' => 'ポスト:',
	'pf_reputation' => '評判:',
	'user_hasnt_registered' => 'このユーザーはまだウェブサイトに登録していません',
	'user_no_friends' => 'このユーザーが友達を追加します。',
	'send_message' => 'メッセージを送信',
	'remove_friend' => 'フレンド削除',
	'add_friend' => 'フレンド追加',
	'last_online' => '最後のオンライン:',
	'find_a_user' => 'ユーザーを検索',
	'user_not_following' => 'このユーザーは誰にも従わない。',
	'user_no_followers' => 'このユーザーにはフォロワーはいません。',
	'following' => 'フォロー',
	'followers' => 'フォロワー',
	'display_location' => '{x} から', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}、{y} から', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => '{x} のプロフィールに何かを書く...。', // Don't replace {x}
	'write_on_own_profile' => 'あなたのプロフィールに何かを書く...。',
	'profile_posts' => 'プロフィール投稿',
	'no_profile_posts' => 'プロフィール投稿はまだありません。',
	'invalid_wall_post' => 'プロフィールの投稿はまだありません。',
	'about' => 'About',
	'reply' => '返信',
	'x_likes' => 'いいね {x}', // Don't replace {x}
	'likes' => 'いいね',
	'no_likes' => 'やだね！',
	'post_liked' => 'いいね！しています',
	'post_unliked' => 'やだね！しています',
	'no_posts' => '投稿はありません。',
	'last_5_posts' => '最後の5件',
	'follow' => 'フォロー',
	'unfollow' => 'フォロー解除',
	'name_history' => 'ページの履歴',
	'changed_name_to' => '名前を {y} に {x} へ変更しました。', // Don't replace {x} or {y}
	'original_name' => '元の名前:',
	'name_history_error' => 'ユーザー名の履歴を取得できません。',
	
	// Staff applications
	'staff_application' => 'スタッフアプリケーション',
	'application_submitted' => 'アプリケーションが正常に送信されました。',
	'application_already_submitted' => 'あなたは既に申請を提出しています。 完了するまでお待ちください。',
	'not_logged_in' => 'そのページを表示するにはログインしてください。',
	'application_accepted' => 'あなたのスタッフの応募が受け入れられました。',
	'application_rejected' => 'あなたのスタッフの申請は拒否されました。'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => '概要',
	'reports' => 'レポート',
	'punishments' => '処罰',
	'staff_applications' => 'スタッフアプリケーション',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => '警告',
	'search_for_a_user' => 'ユーザーを検索',
	'user' => 'ユーザー:',
	'ip_lookup' => 'IP 検索:',
	'registered' => '登録',
	'reason' => '理由:',
	'cant_ban_root_user' => '管理者を処罰することはできません!',
	'invalid_reason' => '2 ~ 256 文字の有効な理由を入力してください。',
	'punished_successfully' => '罰は正常に追加されました。',
	
	// Reports
	'report_closed' => 'レポートが閉じました。',
	'new_comment' => '新しいコメント',
	'comments' => 'コメント',
	'only_viewed_by_staff' => 'スタッフのみが閲覧することができます。',
	'reported_by' => '報告者',
	'close_issue' => '閉じる問題',
	'report' => 'レポート:',
	'view_reported_content' => '報告されたコンテンツを表示する',
	'no_open_reports' => '開いているレポートはありません',
	'user_reported' => 'ユーザー報告',
	'type' => 'タイプ',
	'updated_by' => '更新者',
	'forum_post' => 'フォーラムポスト',
	'user_profile' => 'ユーザープロフィール',
	'comment_added' => 'コメントが追加されました。',
	'new_report_submitted_alert' => 'ユーザー {y} について {x} が提出した新しいレポート', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'ゲーム内レポート',
	
	// Staff applications
	'comment_error' => 'コメントの長さは、2 ~ 2048文字の長さにしてください。',
	'viewing_open_applications' => 'アプリケーションの表示 <span class="label label-info">承認</span>された<a href="/mod/applications/?view=accepted"><span class="label label-success">承認</span></a>または<a href="/mod/applications/?view=declined"><span class="label label-danger">不承認</span></a>への変更',
	'viewing_accepted_applications' => '<span class="label label-success">承認</span>されたアプリケーションの表示。 <a href="/mod/applications/"><span class="label label-info">開いている</span></a>か<a href="/mod/applications/?view=declined"><span class="label label-danger">拒否</span></a>されているかに変更',
	'viewing_declined_applications' => '<span class="label label-danger">承認</span>されたアプリケーションの表示。 <a href="/mod/applications/"><span class="label label-info">開いている</span></a>か<a href="/mod/applications/?view=declined"><span class="label label-danger">拒否</span></a>されているかに変更',
	'time_applied' => '適用時間',
	'no_applications' => 'このカテゴリのアプリケーションはありません',
	'viewing_app_from' => '{x} からアプリケーションを表示します。', // Don't replace "{x}"
	'open' => 'オープン',
	'accepted' => '受け入れ',
	'declined' => '辞退',
	'accept' => '許可',
	'decline' => '非許可',
	'new_app_submitted_alert' => '{x} によって提出された新しい申請' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'ニュース',
	'social' => 'ソーシャル',
	'join' => '参加',
	
	// General terms
	'submit' => '送信',
	'close' => '閉じる',
	'cookie_message' => '<strong>このサイトは、あなたの体験を向上させるためにクッキーを使用します。</strong><p>このウェブサイトを閲覧することで、それに同意したことになります。</p>',
	'theme_not_exist' => '選択したテーマが存在しません。',
	'confirm' => '確認',
	'cancel' => 'キャンセル',
	'guest' => 'ゲスト',
	'guests' => 'ゲスト',
	'back' => '戻る',
	'search' => '検索　',
	'help' => 'ヘルプ',
	'success' => '成功',
	'error' => 'エラー',
	'view' => 'ビュー',
	'info' => '情報',
	'next' => '次へ',
	
	// Play page
	'connect_with' => 'サーバーアドレスは {x} です', // Don't replace {x}
	'online' => 'オンライン',
	'offline' => 'オフライン',
	'status' => 'ステータス:',
	'players_online' => 'オンラインプレイヤー:',
	'queried_in' => '応答速度:',
	'server_status' => 'サーバー状態',
	'no_players_online' => 'オンラインプレイヤーはいません！',
	'1_player_online' => 'オンラインの1人のプレーヤーがいます。',
	'x_players_online' => '{x} 人のプレーヤーがオンラインです。', // Don't replace {x}
	
	// Other
	'page_loaded_in' => '応答速度: {x}ms', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'なし',
	'404' => 'ページを見つけることができませんでした'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'フォーラム',
	'discussion' => 'ディスカッション',
	'stats' => '状態',
	'last_reply' => '最新の返信',
	'ago' => '前',
	'by' => '作成者',
	'in' => 'で',
	'views' => '表示',
	'posts' => 'ポスト',
	'topics' => 'トピック',
	'topic' => 'トピック',
	'statistics' => '統計情報',
	'overview' => '概要',
	'latest_discussions' => '最新のディスカッション',
	'latest_posts' => '最新の投稿',
	'users_registered' => '登録ユーザー:',
	'latest_member' => '最新のメンバー:',
	'forum' => 'フォーラム',
	'last_post' => '最新の投稿',
	'no_topics' => 'まだトピックはありません',
	'new_topic' => '新しいトピック',
	'subforums' => 'サブフォーラム:',
	
	// View topic view
	'home' => 'ホーム',
	'topic_locked' => 'トピックロック',
	'new_reply' => '新しい返信',
	'mod_actions' => 'その他の操作',
	'lock_thread' => 'ロック スレッド',
	'unlock_thread' => 'アンロック スレッド',
	'merge_thread' => 'スレッドをマージします。',
	'delete_thread' => 'スレッドを削除します。',
	'confirm_thread_deletion' => 'このスレッドを削除してもよろしいですか？',
	'move_thread' => 'スレッドを移動します。',
	'sticky_thread' => 'スティッキー スレッド',
	'report_post' => 'レポートの投稿',
	'quote_post' => '記事を引用',
	'delete_post' => '投稿を削除',
	'edit_post' => '投稿を編集',
	'reputation' => '評判',
	'confirm_post_deletion' => 'この投稿を削除してもよろしいですか？',
	'give_reputation' => '評判を与える',
	'remove_reputation' => '評判を削除',
	'post_reputation' => '評判を投稿します。',
	'no_reputation' => 'まだこの投稿には評判がありません',
	're' => 'RE:',
	
	// Create post view
	'create_post' => '投稿を作成',
	'post_submitted' => '記事を提出',
	'creating_post_in' => '投稿を作成する: ',
	'topic_locked_permission_post' => 'このトピックはロックされていますが、あなたの権限で投稿することができます',
	
	// Edit post view
	'editing_post' => '記事を編集',
	
	// Sticky threads
	'thread_is_' => 'このスレッドは ',
	'now_sticky' => 'トピックの一番に固定表示されます。',
	'no_longer_sticky' => 'トピックの一番上からの固定を解除しました。',
	
	// Create topic
	'topic_created' => 'トピックが作成されました。',
	'creating_topic_in_' => 'フォーラムでトピックを作成する ',
	'thread_title' => 'スレッドのタイトル',
	'confirm_cancellation' => '実行しますか？',
	'label' => 'ラベル',
	
	// Reports
	'report_submitted' => 'レポートが送信されました。',
	'view_post_content' => '投稿内容を表示',
	'report_reason' => 'レポートの理由',
	
	// Move thread
	'move_to' => '移動先:',
	
	// Merge threads
	'merge_instructions' => 'マージするスレッドは、同じフォーラム内になければなりません。 <strong>必要</strong>に応じてスレッドを移動します。',
	'merge_with' => 'マージ先:',
	
	// Other
	'forum_error' => '申し訳ありませんが、フォーラムやトピックを見つけることができませんでした。',
	'are_you_logged_in' => 'あなたはログインしていますか？',
	'online_users' => 'オンライン ユーザー',
	'no_users_online' => 'オンラインのユーザーはありません。',
	
	// Search
	'search_error' => '1~32文字の検索クエリを入力してください。',
	'no_search_results' => '検索結果は見つかりませんでした。',
	
	//Share on a social-media.
	'sm-share' => '共有',
	'sm-share-facebook' => 'Facebook で共有します。',
	'sm-share-twitter' => 'Twitter で共有します。',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'こんにちは！',
	'message' => '登録ありがとうございます！登録を完了するには、次のリンクをクリックしてください:',
	'thanks' => 'Thanks.'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => '数秒', // Shortened "seconds", eg "s"
	'less_than_a_minute' => '1 分前',
	'1_minute' => '1 分前',
	'_minutes' => '{x} 分前',
	'about_1_hour' => '約 1 時間前',
	'_hours' => '{x} 時間前',
	'1_day' => '1 日前',
	'_days' => '{x} 日前',
	'about_1_month' => '約 1 ヶ月前',
	'_months' => '{x} ヶ月前',
	'about_1_year' => '約 1 年前',
	'over_x_years' => '約 {x} 年前'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => '1 ページあたりの表示 _MENU_ レコード', // Don't replace "_MENU_"
	'nothing_found' => '結果は見つかりませんでした。',
	'page_x_of_y' => '表示ページの _PAGE_ または _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'レコードはありません。',
	'filtered' => '(_MAX_ レコードの合計から除外)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => '登録を完了'
);
 
?>
