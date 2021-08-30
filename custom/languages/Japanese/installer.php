<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr11
 *
 *  Translator
 *  - SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  - Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
 *  - snake( @ViaSnake, https://github.com/ViaSnake )
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
    'pre-release' => 'プレリリース',
    'installer_welcome' => 'NamelessMC v2プレリリースへようこそ。',
    'pre-release_warning' => 'このプレリリースは、公開サイトでの使用を意図したものではありませんのでご注意ください',
    'installer_information' => 'インストーラーがインストール手順を案内します。',
    'terms_and_conditions' => '継続することで、規約に同意することになります。',
    'new_installation_question' => 'これは新規インストールですか？',
    'new_installation' => '新規インストール &raquo;',
    'upgrading_from_v1' => 'v1からのアップグレード &raquo;',
    'requirements' => '要件:',
    'config_writable' => 'core/config.php 書き込み可能',
    'cache_writable' => 'cache 書き込み可能',
    'template_cache_writable' => 'Template Cache 書き込み可能',
    'exif_imagetype_banners_disabled' => 'exif_imagetype関数が無い場合、サーバーバナーが無効になります。',
    'requirements_error' => 'インストールを続行するには、必要な拡張機能がすべてインストールされていて、正しい権限が設定されている必要があります。',
    'proceed' => '続行',
    'database_configuration' => 'データベース設定',
    'database_address' => 'データベース アドレス',
    'database_port' => 'データベース ポート',
    'database_username' => 'データベース ユーザー名',
    'database_password' => 'データベース パスワード',
    'database_name' => 'データベース名',
    'nameless_path' => 'インストールパス',
    'nameless_path_info' => 'これはNamelessがインストールされているパスで、ドメインからの相対パスです。例えば、Namelessがexample.com/forumにインストールされている場合、これは<strong>forum</strong>である必要があります。Namelessがサブフォルダに入っていない場合は空にしてください。',
    'friendly_urls' => 'フレンドリーURL',
    'friendly_urls_info' => 'フレンドリーURLは、ブラウザでのURLの読みやすさを向上させます。<br />例:<br /><code>example.com/index.php?route=/forum</code><br />は次のようになります:<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>重要！</strong><br />この機能を有効にするには、サーバーが正しく設定されていなければなりません。このオプションを有効にできるかどうかは、<a href="./rewrite_test" target="_blank" style="color:#2185D0">ここ</a>で確認できます。</div>',
    'enabled' => '有効',
    'disabled' => '無効',
    'character_set' => 'キャラクターセット',
    'database_engine' => 'データベースストレージエンジン',
    'host' => 'ホスト名',
    'host_help' => 'ホスト名は、ウェブサイトの<strong>ベースURL</strong>です。インストールパス欄のサブフォルダや、http(s)://をここに入れないでください！',
    'database_error' => 'すべての項目が入力されていることを確認してください。',
    'submit' => '送信',
    'installer_now_initialising_database' => 'インストーラーがデータベースの初期化を行っています。これにはしばらく時間がかかるかもしれません...',
    'configuration' => '設定',
    'configuration_info' => 'サイトの基本情報を入力してください。これらの値は後から管理パネルで変更することができます。',
    'configuration_error' => '1~32文字の有効なサイト名と、4~64文字の有効なメールアドレスを入力してください。',
    'site_name' => 'サイト名',
    'contact_email' => '問い合わせ先メールアドレス',
    'outgoing_email' => '送信用メールアドレス',
    'language' => '言語',
    'initialising_database_and_cache' => 'データベースとキャッシュを初期化しています、しばらくお待ちください...',
    'unable_to_login' => 'ログインできません。',
    'unable_to_create_account' => 'アカウントを作成できません。',
    'input_required' => '有効なユーザー名、メールアドレス、パスワードを入力してください。',
    'input_minimum' => 'ユーザー名は3文字以上、メールアドレスは4文字以上、パスワードは6文字以上の必要があります。',
    'input_maximum' => 'ユーザー名は20文字以下、メールアドレスとパスワードは64文字以下の必要があります。',
    'email_invalid' => '入力されたメールアドレスは無効です。',
    'passwords_must_match' => 'パスワードが一致していません。',
    'creating_admin_account' => '管理者アカウントの作成',
    'enter_admin_details' => '管理者アカウントの詳細を入力してください。',
    'username' => 'ユーザー名',
    'email_address' => 'メールアドレス',
    'password' => 'パスワード',
    'confirm_password' => 'パスワードの確認',
    'upgrade' => 'アップグレード',
    'input_v1_details' => 'NamelessMC v1のデータベースに関する情報を入力してください。',
    'installer_upgrading_database' => 'インストーラーがデータベースをアップグレードするまでお待ちください...',
    'errors_logged' => 'エラーが記録されました。続行をクリックしてアップグレードを続行してください',
    'continue' => '続行',
    'convert' => '変換',
    'convert_message' => '他のフォーラムソフトウェアからデータを変換しますか？',
    'yes' => 'はい',
    'no' => 'いいえ',
    'converter' => '変換',
    'back' => '戻る',
    'unable_to_load_converter' => '変換を読み込めません！',
    'finish' => '完了',
    'finish_message' => 'NamelessMCをインストールしていただきありがとうございます！StaffCPに進み、ウェブサイトのさらなる設定を行うことができます。',
    'support_message' => 'サポートが必要な場合は、<a href="https://namelessmc.com" target="_blank">私達のウェブサイト</a>をご覧ください。また、<a href="https://discord.gg/nameless" target="_blank">Discordサーバー</a>や<a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHubリポジトリ</a>もご利用いただけます。',
    'credits' => 'クレジット',
    'credits_message' => '2014年以降のすべての<a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMCへの貢献者</a>に大きな感謝を捧げます',

    'step_home' => 'ホーム',
    'step_requirements' => '要件',
    'step_general_config' => '一般設定',
    'step_database_config' => 'データベース設定',
    'step_site_config' => 'サイト設定',
    'step_admin_account' => '管理者アカウント',
    'step_conversion' => '変換',
    'step_finish' => '終了',

    'general_configuration' => '一般設定',
    'reload' => '再読み込み',
    'reload_page' => 'ページを再読み込み',
    'no_converters_available' => '利用可能な変換がありません。',
    'config_not_writable' => '設定ファイルが書き込み可能ではありません。',

    'session_doesnt_exist' => 'セッションの検出ができません。セッションの保存は、Namelessを使用するための必須条件です。もう一度お試しください。問題が解決しない場合は、ウェブホストにお問い合わせください。'
);
