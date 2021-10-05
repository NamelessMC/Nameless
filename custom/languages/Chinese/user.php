<?php
/*
 /*
 *  Made by Samerton
 *  Translation  by Hi_Michael
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Chinese Language - Users
 *  Translation progress : 97%
 *  翻譯有誤請使用GitHun回報issues
 *  https://github.com/haer0248/NamelessMC-v2-Traditional-Chinese/issues
 */

$language = array(
    /*
     *  Change this for the account validation message
     */
    'validate_account_command' => 'To complete registration, please execute the command <strong>/verify {x}</strong> ingame.', // Don't replace {x}

    /*
     *  User Related
     */
    'guest' => '遊客',
    'guests' => '遊客',

    // UserCP
    'user_cp' => '使用者後台',
    'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
    'overview' => '總覽',
    'user_details' => '使用者資訊',
    'profile_settings' => '個人檔設定',
    'successfully_logged_out' => '成功登出.',
    'messaging' => '訊息',
    'click_here_to_view' => '點擊查看.',
    'moderation' => 'Moderation',
    'administration' => '管理者',
    'alerts' => '提醒',
    'delete_all' => '移除全部',
    'private_profile' => 'Private profile',
    'gif_avatar' => 'Upload .gif as custom avatar',
    'placeholders' => 'Placeholders',
    'no_placeholders' => 'No Placeholders',

    // Profile settings
    'field_is_required' => '需要 {x}.', // Don't replace {x}
    'settings_updated_successfully' => '設定更新成功.',
    'password_changed_successfully' => '密碼更新成功.',
    'change_password' => '更換密碼',
    'current_password' => '目前密碼',
    'new_password' => '新密碼',
    'confirm_new_password' => '確認新密碼',
    'incorrect_password' => '密碼錯誤.',
    'two_factor_auth' => 'TFA 二次驗證',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'enable' => '啟用',
    'disable' => '禁用',
    'tfa_scan_code' => '請在APP中掃描以下QR Code:',
    'tfa_code' => '如果你的手機沒有相機鏡頭可以掃描QR Code，請輸入以下代碼:',
    'tfa_enter_code' => '請在APP中輸入顯示的代碼:',
    'invalid_tfa' => '代碼錯誤，請重試.',
    'tfa_successful' => 'TFA二次驗證設定成功. 每次登入時必須二次驗證才能登入成功.',
    'active_language' => '啟用語言',
    'active_template' => 'Active Template',
    'timezone' => '時區',
    'upload_new_avatar' => '上傳新的頭像',
    'nickname_already_exists' => 'Your chosen nickname already exists.',
    'change_email_address' => 'Change Email Address',
    'email_already_exists' => 'The email address you have entered already exists.',
    'email_changed_successfully' => 'Email address changed successfully.',
    'avatar' => 'Avatar',
    'profile_banner' => 'Profile Banner',
    'upload_profile_banner' => 'Upload Profile Banner',
    'upload' => 'Upload',
    'topic_updates' => 'Get emails for topics you follow',
    'gravatar' => 'Use Gravatar as avatar',

    // Alerts
    'user_tag_info' => '你被標註於 {x}.', // Don't replace {x}
    'no_alerts' => '沒有新提醒',
    'view_alerts' => '檢視提醒',
    '1_new_alert' => 'You have 1 new alert',
    'x_new_alerts' => '你有 {x} 個新提醒', // Don't replace {x}
    'no_alerts_usercp' => '你沒有任何提醒.',

    // Registraton
    'registration_check_email' => '感謝您的註冊! 請檢查你的電子郵件來完成註冊動作. 如果你沒有收到信請檢查垃圾信箱.',
    'username' => '帳號',
    'nickname' => '暱稱',
    'minecraft_username' => 'Minecraft Username (遊戲名稱)',
    'email_address' => '電子郵件位置',
    'email' => '電子郵件',
    'password' => '密碼',
    'confirm_password' => '確認密碼',
    'i_agree' => '我同意',
    'agree_t_and_c' => 'I have read and accept the <a href="{x}" target="_blank">Terms and Conditions</a>.',
    'create_an_account' => '建立帳號',
    'terms_and_conditions' => '使用條款',
    'validation_complete' => '你的帳戶已被驗證，你現在可以登入.',
    'validation_error' => '驗證失敗，請聯絡網站管理員.',
    'signature' => '簽名檔',
    'signature_max_900' => '你的簽名檔字元超過900字.',

    // Registration - Authme
    'connect_with_authme' => '使用AuthMe連接帳戶',
    'authme_help' => '請輸入在遊戲中的AuthMe帳戶資訊. 如果你沒有帳號，請依照伺服器給予的說明操作.',
    'unable_to_connect_to_authme_db' => '無法連線至AuthMe資料庫，如果錯誤仍然存在，請連接網站管理員.',
    'authme_account_linked' => '帳戶連接成功.',
    'authme_email_help_1' => '完成，請輸入電子郵件.',
    'authme_email_help_2' => '完成，請輸入電子郵件和選取帳戶名.',

    // Registration errors
    'username_required' => '帳號是必須的.',
    'email_required' => '電子郵件是必須的.',
    'password_required' => '密碼是必須的.',
    'mcname_required' => 'Minecraft username(遊戲名稱) 是必須的.',
    'accept_terms' => '你必須接受服務條款.',
    'username_minimum_3' => '帳號最底限制 3 字元.',
    'mcname_minimum_3' => 'Minecraft username (遊戲名稱) 最低限制 3 字元.',
    'password_minimum_6' => '密碼最低限制 6 字元.',
    'username_maximum_20' => '帳號限制最高 20 字元.',
    'mcname_maximum_20' => 'Minecraft username (遊戲名稱) 最高限制 30 字元.',
    'passwords_dont_match' => '密碼不相同.',
    'username_mcname_email_exists' => '帳號或電子郵件已存在.',
    'invalid_mcname' => 'Minecraft username 不相符 (非正版).',
    'invalid_email' => '電子郵件不正確.',
    'mcname_lookup_error' => '目前無法連接到 Moajng 伺服器，請稍等再試.',
    'invalid_recaptcha' => '無效的 reCAPTCHA.',
    'verify_account' => '驗證帳號',
    'verify_account_help' => '請依照下列的說明來驗證 Minecraft 帳戶為您所有.',
    'validate_account' => 'Validate Account',
    'verification_failed' => '驗證失敗，請重試.',
    'verification_success' => '成功驗證，已可以登入.',
    'authme_username_exists' => '你的 AuthMe 帳號已存在，請直接登入',
    'uuid_already_exists' => 'Your UUID already exists, meaning this Minecraft account has already registered.',

    // Login
    'successful_login' => '登入成功.',
    'incorrect_details' => '部分資料輸入錯誤.',
    'inactive_account' => ' 帳戶已啟動. 請點擊電子郵件驗證信，或許會在垃圾桶.',
    'account_banned' => '帳戶已被封禁.',
    'forgot_password' => '忘記密碼?',
    'remember_me' => '記住我',
    'must_input_email' => 'You must input an email address.',
    'must_input_username' => '你必須輸入帳號.',
    'must_input_password' => '你必須輸入密碼.',
    'must_input_email_or_username' => 'You must input an email or username.',
    'email_or_username' => 'Email or Username',

    // Forgot password
    'forgot_password_instructions' => '請輸入你的電子郵件讓我們可以在你忘記密碼時寄一封信給你重設密碼.',
    'forgot_password_email_sent' => '如果該帳號已有電子郵件，則已發送包含進一步的說明. 如果你沒看到，請檢查你的垃圾桶.',
    'unable_to_send_forgot_password_email' => '無法傳送忘記密碼電子郵件，請聯絡網站管理員.',
    'enter_new_password' => '請確認你的電子郵件並在下面輸入密碼.',
    'incorrect_email' => '電子郵件錯誤.',
    'forgot_password_change_successful' => '密碼變更成功，你可以登入了.',

    // Profile pages
    'profile' => '個人檔',
    'follow' => '追隨',
    'no_wall_posts' => '這個人的塗鴉牆沒有東西.',
    'change_banner' => '更換橫幅',
    'post_on_wall' => '塗鴉牆上有 {x} 篇文章', // Don't replace {x}
    'invalid_wall_post' => '請輸入 1 ~ 10000 個字元的文章內容.',
    '1_reaction' => '1 個回應',
    'x_reactions' => '{x} 個回應', // Don't replace {x}
    '1_like' => '1 個讚',
    'x_likes' => '{x} 個讚', // Don't replace {x}
    '1_reply' => '1 個回覆',
    'x_replies' => '{x} 個回覆', // Don't replace {x}
    'no_replies_yet' => '這邊沒有回覆',
    'feed' => '回饋',
    'about' => '關於',
    'reactions' => '回應',
    'replies' => '回覆',
    'new_reply' => '新回覆',
    'registered' => '已註冊:',
    'registered_x' => '已註冊: {x}',
    'last_seen' => '上次上線:',
    'last_seen_x' => '上次上線: {x}', // Don't replace {x}
    'new_wall_post' => '{x} 在你的塗鴉牆上發文.',
    'couldnt_find_that_user' => '找不到使用者.',
    'block_user' => '封鎖使用者',
    'unblock_user' => '解鎖使用者',
    'confirm_block_user' => '你要封鎖這位使用者嗎？如果封鎖了他將會無法傳送私人訊息與在文章標注你.',
    'confirm_unblock_user' => '你要解鎖這位使用者嗎？如果解鎖了他將可以傳送私人訊息與在文章標注你.',
    'user_blocked' => '使用者封鎖.',
    'user_unblocked' => '使用者解鎖.',
    'views' => 'Profile Views:',
    'private_profile_page' => 'This is a private profile!',
    'new_wall_post_reply' => '{x} has replied to your post on {y}\'s profile.', // Don't replace {x} or {y}
    'new_wall_post_reply_your_profile' => '{x} has replied to your post on your profile.', // Don't replace {x}
    'no_about_fields' => 'This user has not added any about fields yet.',
    'reply' => 'Reply',
    'discord_username' => 'Discord Username',

    // Reports
    'invalid_report_content' => '無法建立回報. 請確認你輸入的內容有在 2-1024 字元以內.',
    'report_post_content' => '請輸入內容',
    'report_created' => '回報建立成功',

    // Messaging
    'no_messages' => '沒有新訊息',
    'no_messages_full' => '你沒有新訊息.',
    'view_messages' => '查看訊息',
    '1_new_message' => 'You have 1 new message',
    'x_new_messages' => '你有 {x} 則新訊息', // Don't replace {x}
    'new_message' => '新訊息',
    'message_title' => '訊息標題',
    'to' => 'To',
    'separate_users_with_commas' => '使用「,」區分使用者',
    'title_required' => '請輸入標題',
    'content_required' => '請輸入內容',
    'users_to_required' => '請輸入使用者名稱',
    'cant_send_to_self' => '你不能傳訊息給自己!',
    'title_min_2' => '標題最低限制 2 字元',
    'content_min_2' => '內文最低限制 2 字元',
    'title_max_64' => '標題最高限制 64 字元',
    'content_max_20480' => '內文最高限制 20480 字元',
    'max_pm_10_users' => '最多只能傳給 10位 使用者',
    'message_sent_successfully' => '訊息傳送成功',
    'participants' => '參與者',
    'last_message' => '最後的訊息',
    'by' => 'by',
    'leave_conversation' => '離開對話',
    'confirm_leave' => '你要離開對話嗎?',
    'one_or_more_users_blocked' => '你無法傳送私人對話，因為有使用者封鎖你.',
    'messages' => 'Messages',
    'latest_profile_posts' => 'Latest Profile Posts',
    'no_profile_posts' => 'No profile posts.',

    /*
     *  Infractions area
     */
    'you_have_been_banned' => '你已被封禁!',
    'you_have_received_a_warning' => '你已收到警告!',
    'acknowledge' => '確認',

    /*
     *  Hooks
     */
    'user_x_has_registered' => '{x} has joined ' . SITE_NAME . '!',
    'user_x_has_validated' => '{x} has validated their account!',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Linked',
    'not_linked' => 'Not Linked',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Successfully unlinked your Discord User ID.',
    'discord_id_confirm' => 'Please run the command "/verify token:{token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pending',
    'discord_id_taken' => 'That Discord ID has already been taken.',
    'discord_invalid_id' => 'That Discord User ID is invalid.',
    'discord_already_pending' => 'You already have a pending verification.',
    'discord_database_error' => 'The Nameless Link database is currently down. Please try again later.',
    'discord_communication_error' => 'There was an error while communicating with the Discord Bot. Please ensure the bot is running and your Bot URL is correct.',
    'discord_unknown_error' => 'There was an unknown error while syncing Discord roles. Please contact an administrator.',
    'discord_id_help' => 'For information on where to find Discord ID\'s, please read <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>'
);
