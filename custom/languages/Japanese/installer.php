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
 *  Japanese Language - Installation
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'インストール',
    'pre-release' => 'プレリリース版',
    'installer_welcome' => 'NamelessMC v2 プレリリース版インストーラへようこそ。',
    'pre-release_warning' => '<i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>プレリリース版は公開サイト用には推奨していません。',
    'installer_information' => 'インストーラがインストール手順を案内いたします。',
    'new_installation_question' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>以下よりインストールの種類を選択してください。',
    'new_installation' => '<i class="fa fa-plus-circle fa-fw" aria-hidden="true"></i>新規インストール',
    'upgrading_from_v1' => '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>v1 からアップグレード',
    'requirements' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i><strong>必要条件</strong>',
    'config_writable' => '<strong>core/config.php</strong>　書き込み許可',
    'cache_writable' => '<strong>core/cache</strong>　書き込み許可',
    'template_cache_writable' => '<strong>core/cache/templates_c</strong>　書き込み許可',
    'exif_imagetype_banners_disabled' => '<i class="fa fa-asterisk fa-fw" aria-hidden="true"></i>exif_imagetype 拡張機能が無効の場合サーバーバナーを使用することはできません。',
    'requirements_error' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>インストールを続行する場合は以上の拡張機能が正常にインストールされていて有効にされている必要があります。また、フォルダ・ファイルの適切な権限設定も行ってください。<br><i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>不適切な場合、サイトが攻撃によって改変される可能性があります。',
    'proceed' => '続行',
    'database_configuration' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>データベース 構成設定',
    'database_address' => 'データベース アドレス',
    'database_port' => 'データベース ポート番号',
    'database_username' => 'データベース ユーザー名',
    'database_password' => 'データベース パスワード',
    'database_name' => 'データベース名',
    'nameless_path' => 'インストール先パス',
    'nameless_path_info' => 'こちらの設定は、ドメイン(例:example.com)を基準にして、NamelessMCがインストールされているパスです。<br/>例:NamelessMCが<strong> 「example.com/forum」 (ルートディレクトリ/forum) </strong>にインストールされている場合設定する内容は<strong> 「forum」 </strong>になります。NamelessMCがサブディレクトリにない場合(ルートディレクトリにインストールしている場合やバーチャルホストを設定している場合)は空白のままで構いません。',
    'friendly_urls' => 'フレンドリーURL',
    'friendly_urls_info' => 'フレンドリーURLはブラウザに表示されるURLがわかりやすくなります。<br/>例:<strong>「example.com/index.php?route=/forum」</strong> → <strong>「example.com/forum」</strong><br/><strong>【重要】</strong> フレンドリーURLを有効にするには、サーバーが正しく設定される必要があります。有効にできるかどうかは、<a href=\'./rewrite_test\' target=\'_blank\'>ここ</a>をクリックして確認できます。',
    'enabled' => '有効',
    'disabled' => '無効',
    'character_set' => 'キャラクターセット',
    'database_engine' => 'データベースストレージエンジン',
    'host' => 'ホスト名',
    'host_help' => 'ホスト名は、ウェブサイトのドメイン<strong>(ベースURL)</strong>を指します。</br><strong>【注意】</strong>「インストールパス」フィールドのサブフォルダ、または「http(s)://」を含めないでください。このフィールドは通常自動で入力されるため変更の必要はありません。',
    'database_error' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>入力が完了していない項目があります。全項目を入力してください。',
    'submit' => '続行',
    'installer_now_initialising_database' => '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>現在データベースを初期化しています。数分かかる場合がございます。しばらくお待ちください...',
    'configuration' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>サイト構成',
    'configuration_info' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>サイトに関する基本情報を入力してください。 入力された内容は後から「StaffCP」にて変更することができます。',
    'configuration_error' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>1 ~ 32 文字の有効なサイト名と 4 ~ 64 文字の有効な電子メールアドレスを入力してください。',
    'site_name' => 'サイト名',
    'contact_email' => '連絡先メールアドレス',
    'outgoing_email' => '送信メールアドレス',
    'initialising_database_and_cache' => '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>現在データベースとキャッシュを初期化しています。数分かかる場合がございます。しばらくお待ちください...',
    'unable_to_login' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>ログインできませんでした。',
    'unable_to_create_account' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>アカウントを作成できませんでした。',
    'input_required' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>有効なユーザー名・電子メールアドレス・パスワードを入力してください。',
    'input_minimum' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>ユーザー名は3文字以上・メールアドレスは4文字以上・パスワードは6文字以上の必要があります。もう一度ご確認してください。',
    'input_maximum' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>ユーザー名は20文字以下、メールアドレス・パスワードは64文字以下の必要があります。もう一度ご確認してください。',
    'email_invalid' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>入力されたメールアドレスは無効です。',
    'passwords_must_match' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>パスワードが一致していません。もう一度ご確認ください。',
    'creating_admin_account' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>管理者アカウントの作成',
    'enter_admin_details' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>管理者アカウントに関する基本情報を入力してください。',
    'username' => 'ユーザー名',
    'email_address' => 'メールアドレス',
    'password' => 'パスワード',
    'confirm_password' => 'パスワードを再入力',
    'upgrade' => '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>アップグレード',
    'input_v1_details' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>NamelessMC v1 のデータベースに関する情報を入力してください。',
    'installer_upgrading_database' => '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>現在データベース情報の引き継ぎ処理をしています。数分かかる場合がございます。しばらくお待ちください...',
    'errors_logged' => '<i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i>引き継ぎ処理中にエラーが発生しました。処理を続行するには「続行」をクリックしてください。',
    'continue' => '<i class="fa fa-chevron-circle-right fa-fw" aria-hidden="true"></i>続行',
    'convert' => '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>変換',
    'convert_message' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>NamelessMC以外のフォーラムソフトウェアからデータを引き継ぎますか？(例:XenForoなど)',
    'yes' => 'はい',
    'no' => 'いいえ',
    'converter' => 'Converter',
    'back' => 'Back',
    'unable_to_load_converter' => 'Unable to load converter!',
    'finish' => '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>完了',
    'finish_message' => '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>NamelessMCのインストールが完了しました。StaffCPにログインするとさらに詳細な設定が可能です。',
    'support_message' => '不明な点がございましたら、当社のウェブサイト <a href="https://namelessmc.com" target="_blank">こちら</a> をご覧いただくか、 <a href="https://discord.gg/9vk93VR" target="_blank">Discord サーバー</a> または <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHubリポジトリ</a> をご確認ください。',
    'credits' => '<i class="fa fa-users fa-fw" aria-hidden="true"></i>クレジット',
    'credits_message' => '2014年以降にnamelessMCの製作に協力してくれたすべての <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMCの貢献者</a> に感謝します。'
);
