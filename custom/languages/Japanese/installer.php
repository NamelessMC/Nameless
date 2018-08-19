<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  Translation by SimplyRin(@SimplyRin_, https://www.simplyrin.net)
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
    'installer_welcome' => 'NamelessMCバージョン 2.0 プレリリースへようこそ。',
    'pre-release_warning' => 'このプレリリースは公開サイト用ではありませんのでご注意ください。',
    'installer_information' => 'インストーラーがインストールプロセスをガイドします。',
    'new_installation_question' => 'まず、これは新しいインストールですか？',
    'new_installation' => '新規インストール &raquo;',
    'upgrading_from_v1' => 'v1 からアップグレード &raquo;',
    'requirements' => '必要条件:',
    'config_writable' => 'core/config.php 書き込み可能',
    'cache_writable' => 'キャッシュ書き込み可能',
    'template_cache_writable' => '書き込み可能なテンプレートキャッシュ',
    'exif_imagetype_banners_disabled' => 'exif_imagetype 関数がなければ、サーバーバナーは無効になります。',
    'requirements_error' => 'インストールを続行するには、必要な拡張機能がすべてインストールされていて、適切な権限が設定されている必要があります。',
    'proceed' => '続行する',
    'database_configuration' => 'データベース 構成',
    'database_address' => 'データベース アドレス',
    'database_port' => 'データベース ポート',
    'database_username' => 'データベース ユーザー名',
    'database_password' => 'データベース パスワード',
    'database_name' => 'データベース名',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'This is the path Nameless is installed in, relative to your domain. For example, if Nameless is installed at example.com/forum, this needs to be <strong>forum</strong>. Leave empty if Nameless is not in a subfolder.',
    'friendly_urls' => 'Friendly URLs',
    'friendly_urls_info' => 'Friendly URLs will improve the readability of URLs in your browser.<br />For example: <br />example.com/index.php?route=/forum<br />would become<br />example.com/forum.<br /><strong>Important!</strong><br />Your server must be configured correctly for this to work. You can see whether you can enable this option by clicking <a href=\'./rewrite_test\' target=\'_blank\'>here</a>.',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => 'キャラクターセット',
    'database_engine' => 'データベースストレージエンジン',
    'host' => 'Hostname',
    'host_help' => 'The hostname is the <strong>base URL</strong> for your website. Do not include the subfolders from the Installation Path field, or http(s):// here!',
    'database_error' => 'すべてのフィールドに記入してください。',
    'submit' => '送信',
    'installer_now_initialising_database' => 'インストーラはデータベースを初期化しています。 これはしばらく時間がかかることがあります...',
    'configuration' => '構成',
    'configuration_info' => 'あなたのサイトに関する基本情報を入力してください。 これらの値は後で管理パネルから変更することができます。',
    'configuration_error' => '1 ~ 32 文字の有効なサイト名と 4 ~ 64 文字の有効な電子メールアドレスを入力してください。',
    'site_name' => 'サイト名',
    'contact_email' => '連絡先メールアドレス',
    'outgoing_email' => '送信メール',
    'initialising_database_and_cache' => 'データベースとキャッシュを初期化しています、しばらくお待ちください...',
    'unable_to_login' => 'ログインできません。',
    'unable_to_create_account' => 'アカウントを作成できません',
    'input_required' => '有効なユーザー名、電子メールアドレス、パスワードを入力してください。',
    'input_minimum' => 'ユーザー名は3文字以上、メールアドレスは4文字以上、パスワードは6文字以上であることを確認してください。',
    'input_maximum' => 'ユーザー名は最大20文字、メールアドレスとパスワードは最大64文字であることを確認してください。',
    'email_invalid' => 'Your email is not valid.',
    'passwords_must_match' => 'あなたのパスワードは一致する必要があります。',
    'creating_admin_account' => '管理者アカウントの作成',
    'enter_admin_details' => '管理者アカウントの詳細を入力してください。',
    'username' => 'ユーザー名',
    'email_address' => 'メール アドレス',
    'password' => 'パスワード',
    'confirm_password' => 'パスワードを確認',
    'upgrade' => 'アップグレード',
    'input_v1_details' => 'NamelessMC バージョン1インストールのデータベースの詳細を入力してください。',
    'installer_upgrading_database' => 'インストーラがあなたのデータベースをアップグレードしている間、お待ちください...',
    'errors_logged' => 'エラーが記録されました。 続行をクリックしてアップグレードを続行します。',
    'continue' => '続ける',
    'convert' => '変換',
    'convert_message' => '最後に別のフォーラムソフトウェアから変換したいですか？',
    'yes' => 'はい',
    'no' => 'いいえ',
    'finish' => 'フィニッシュ',
    'finish_message' => 'NamelessMCをインストールしていただきありがとうございます！ AdminCP に進み、Web サイトをさらに構成することができます。',
    'support_message' => 'サポートが必要な場合は、当社のウェブサイト <a href="https://namelessmc.com" target="_blank">こちら</a> をご覧いただくか、 <a href="https://discord.gg/9vk93VR" target="_blank">Discord サーバー</a> または <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHubリポジトリ</a> へ',
    'credits' => 'クレジット',
    'credits_message' => '2014年以降のすべての <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMCの貢献者</a>に感謝します'
);