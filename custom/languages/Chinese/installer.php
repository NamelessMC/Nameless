<?php
/*
 *  Made by Samerton
 *  Translation  by Hi_Michael
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Chinese Language - Installation
 *  Translation progress : 98%
 *  翻譯有誤請使用GitHun回報issues
 *  https://github.com/haer0248/NamelessMC-v2-Traditional-Chinese/issues
 */

$language = array(
    /*
     *  Installation
     */
    'install' => '安裝嚮導',
    'pre-release' => '先行測試版',
    'installer_welcome' => '歡迎使用 NamelessMC version 2.0 先行測試版.',
    'pre-release_warning' => '請注意! 此版本不適用於公共場所.',
    'installer_information' => '安裝程序將指導您安裝完成.',
    'terms_and_conditions' => 'By continuing you agree to the terms and conditions.',
    'new_installation_question' => '首先，這是新的安裝?',
    'new_installation' => '新的安裝 &raquo;',
    'upgrading_from_v1' => '從 v1 升級 &raquo;',
    'requirements' => '需要:',
    'config_writable' => 'core/config.php 可寫入',
    'cache_writable' => 'Cache 可寫入',
    'template_cache_writable' => 'Template Cache 可寫入',
    'exif_imagetype_banners_disabled' => 'Without the exif_imagetype function, server banners will be disabled.',
    'requirements_error' => '你必須安裝所有必需的，並設置正確才能繼續安裝.',
    'proceed' => '繼續',
    'database_configuration' => '資料庫設定',
    'database_address' => '資料庫位置',
    'database_port' => '資料庫端口',
    'database_username' => '資料庫使用者',
    'database_password' => '資料庫密碼',
    'database_name' => '資料庫名稱',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'This is the path Nameless is installed in, relative to your domain. For example, if Nameless is installed at example.com/forum, this needs to be <strong>forum</strong>. Leave empty if Nameless is not in a subfolder.',
    'friendly_urls' => 'Friendly URLs',
    'friendly_urls_info' => 'Friendly URLs will improve the readability of URLs in your browser.<br />For example: <br /><code>example.com/index.php?route=/forum</code><br />would become:<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Important!</strong><br />Your server must be configured correctly for this to work. You can see whether you can enable this option by clicking <a href="./rewrite_test" target="_blank" style="color:#2185D0">here</a>.</div>',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => '字符集',
    'database_engine' => 'Database Storage Engine',
    'host' => 'Hostname',
    'host_help' => 'The hostname is the <strong>base URL</strong> for your website. Do not include the subfolders from the Installation Path field, or http(s):// here!',
    'database_error' => '請確認所有資料都已填寫.',
    'submit' => '送出',
    'installer_now_initialising_database' => '安裝程式正在初始化...請喝杯茶稍等',
    'configuration' => '設定',
    'configuration_info' => '請輸入網站基本資料，這些設定在管理員後台可以看到.',
    'configuration_error' => '網站名稱字元限制 1~32，電子郵件字元限制 4~64.',
    'site_name' => '網站名稱',
    'contact_email' => '聯絡電子郵件',
    'outgoing_email' => '傳出電子郵件',
    'language' => 'Language',
    'initialising_database_and_cache' => '初始化資料庫與緩存...',
    'unable_to_login' => '無法登入.',
    'unable_to_create_account' => '無法建立帳號',
    'input_required' => '請輸入帳號、電子郵件和密碼.',
    'input_minimum' => '帳號最低限制 3 字元，電子郵件最低限制 4 字元，密碼最低限制 6 字元.',
    'input_maximum' => '帳號最高限制 20 字元，電子郵件最高限制 20 字元，密碼最高限制 64 字元.',
    'email_invalid' => 'Your email is not valid.',
    'passwords_must_match' => '密碼不相同.',
    'creating_admin_account' => '建立管理員帳號',
    'enter_admin_details' => '請輸入管理員的帳號資料.',
    'username' => '帳號 (Minecraft Username)',
    'email_address' => '電子郵件',
    'password' => '密碼',
    'confirm_password' => '確認密碼',
    'upgrade' => '升級',
    'input_v1_details' => '請輸入 Nameless version 1 的資料庫資料.',
    'installer_upgrading_database' => '請等待安裝程序升級資料庫...',
    'errors_logged' => '錯誤已記錄，點擊繼續繼續升級.',
    'continue' => '繼續',
    'convert' => '轉換',
    'convert_message' => '最後，您想要從不同的論壇升級?',
    'yes' => '好',
    'no' => '不了',
    'converter' => 'Converter',
    'back' => 'Back',
    'unable_to_load_converter' => 'Unable to load converter!',
    'finish' => '完成',
    'finish_message' => '感謝安裝 NamelessMC! 你現在可以造訪管理員後台，在後台進一步設定網站.',
    'support_message' => '如果需要支援，逛逛我們的 <a href="https://namelessmc.com" target="_blank">網站</a>, 或是加入我們的 <a href="https://discord.gg/nameless" target="_blank">Discord伺服器</a> 或是 <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub 知識庫</a>.',
    'credits' => '貢獻歸功於',
    'credits_message' => '從 2014年開始 非常感謝所有 <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC 貢獻者</a>',

    'step_home' => 'Home',
    'step_requirements' => 'Requirements',
    'step_general_config' => 'General Configuration',
    'step_database_config' => 'Database Configuration',
    'step_site_config' => 'Site Configuration',
    'step_admin_account' => 'Admin Account',
    'step_conversion' => 'Conversion',
    'step_finish' => 'Finish',

    'general_configuration' => 'General Configuration',
    'reload' => 'Reload',
    'reload_page' => 'Reload page',
    'no_converters_available' => 'There are no converters available.',
    'config_not_writable' => 'The config file is not writable.',

    'session_doesnt_exist' => 'Unable to detect session. Sessions saving are a requirement to use Nameless. Please try again, and if the issue persists, please contact your web host for support.'
);
