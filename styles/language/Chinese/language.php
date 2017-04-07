<?php 
/*
 *	Made by Birkhoff
 *  https://birkhoff.me
 *
 *  License: MIT
 */

/*
 *  Chinese Language
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => '管理者控制面板',
	'infractions' => 'Infractions',
	'invalid_token' => '不正確的 token，請重試。',
	'invalid_action' => '不正確的動作',
	'successfully_updated' => '更新完畢',
	'settings' => '設定',
	'confirm_action' => '確認操作',
	'edit' => '編輯',
	'actions' => '動作',
	'task_successful' => '任務執行完畢',
	
	// Admin login
	're-authenticate' => '請重新登入',
	
	// Admin sidebar
	'index' => '概覽',
	'announcements' => '公告',
	'core' => '核心',
	'custom_pages' => '自定義頁面',
	'general' => '一般',
	'forums' => '論壇',
	'users_and_groups' => '使用者與群組',
	'minecraft' => 'Minecraft',
	'style' => '樣式',
	'addons' => '插件',
	'update' => '更新',
	'misc' => '雜項',
	'help' => '幫助',
	
	// Admin index page
	'statistics' => '統計資料',
	'registrations_per_day' => '每天的註冊人數（最近七天）',
	
	// Admin announcements page
	'current_announcements' => '目前的公告',
	'create_announcement' => '建立一個公告',
	'announcement_content' => '公告內容',
	'announcement_location' => '公告位置',
	'announcement_can_close' => 'Can close announcement?',
	'announcement_permissions' => '公告權限',
	'no_announcements' => '目前還沒有建立任何公告。',
	'confirm_cancel_announcement' => '你確定要取消這個公告嗎？',
	'announcement_location_help' => '按住 Ctrl 並點選以選擇多個頁面',
	'select_all' => '全選',
	'deselect_all' => '取消全選',
	'announcement_created' => '公告建立完畢',
	'please_input_announcement_content' => '請輸入公告內容並且選擇一個類型',
	'confirm_delete_announcement' => '你確定要刪除這個公告嗎？',
	'announcement_actions' => 'Announcement Actions',
	'announcement_deleted' => '公告刪除完成',
	'announcement_type' => '公告類型',
	'can_view_announcement' => 'Can view announcement?',
	
	// Admin core page
	'general_settings' => '一般設定',
	'modules' => '模組',
	'module_not_exist' => '找不到該模組！',
	'module_enabled' => '模組啟用完畢。',
	'module_disabled' => '模組停用完畢。',
	'site_name' => '網站名稱',
	'language' => '語言',
	'voice_server_not_writable' => '無法寫入 core/voice_server.php，請檢查檔案權限。',
	'email' => '電子郵件位址',
	'incoming_email' => 'Incoming 電子郵件位址',
	'outgoing_email' => 'Outgoing 電子郵件位址',
	'outgoing_email_help' => '只有在已啟用 PHP mail() 函式的狀況之下才需要填寫',
	'use_php_mail' => '使用 PHP mail() 函式',
	'use_php_mail_help' => '建議啟用。如果無法發送電子郵件，請停用此項並且進入 core/email.php 進行電子郵件的設定。',
	'use_gmail' => '使用 Gmail 來發送電子郵件',
	'use_gmail_help' => '只有在 PHP mail() 函式停用的時候才可以使用。如果你選擇不使用 Gmail，系統將會使用 SMTP。兩種情況都需要你在 core/email.php 中進行設定。',
	'enable_mail_verification' => '啟用電子郵件驗證',
	'enable_email_verification_help' => '若你啟用此項，新使用者註冊時將會被要求驗證他們的電子郵件位址才能完成註冊。',
	'explain_email_settings' => '若你停用了「使用 PHP mail() 函式」 選項，則此項為必填。你可以在<a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">我們的維基頁面</a>上找到更多關於此項設定的資料。',
	'email_config_not_writable' => '無法寫入 <strong>core/email.php</strong>，請檢查檔案權限。',
	'pages' => '頁面',
	'enable_or_disable_pages' => '在這裡啟用或停用頁面。',
	'enable' => '啟用',
	'disable' => '停用',
	'maintenance_mode' => '論壇維護模式',
	'forum_in_maintenance' => '論壇目前處於維護模式中',
	'unable_to_update_settings' => '無法更新設定，請確認所有欄位都已填入資料。',
	'editing_google_analytics_module' => '設定 Google Analytics 模組',
	'tracking_code' => '追蹤代碼',
	'tracking_code_help' => '在這裡填入你的 Google Analytics 追蹤代碼，包括被 script 標籤包住的部分。',
	'google_analytics_help' => '查看<a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">這個教學</a>以了解更多，跟隨步驟一至步驟三。',
	'social_media_links' => '社交媒體連結',
	'youtube_url' => 'YouTube 連結',
	'twitter_url' => 'Twitter 連結（不要在結尾加上「/」）',
	'twitter_dark_theme' => '使用黑色的 Twitter 佈景主題',
	'twitter_widget_id' => 'Twitter 小工具 ID',
	'google_plus_url' => 'Google+ 連結',
	'facebook_url' => 'Facebook 連結',
	'registration' => '註冊',
	'registration_warning' => '若停用此模組，新使用者將無法在本站註冊。',
	'google_recaptcha' => '啟用 Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => '註冊條款',
	'voice_server_module' => '語音伺服器模組',
	'only_works_with_teamspeak' => '此模組目前僅支援 TeamSpeak 與 Discord',
	'discord_id' => 'Discord 伺服器 ID',
	'voice_server_help' => '請輸入 ServerQuery 使用者的資料',
	'ip_without_port' => 'IP 位址（不包含連線埠）',
	'voice_server_port' => '連線埠（一般而言為 10011）',
	'virtual_port' => '虛擬連線埠（一般而言為 9987）',
	'permissions' => '權限：',
	'view_applications' => '查看申請表單',
	'accept_reject_applications' => '接受或拒絕申請表單',
	'questions' => '問題：',
	'question' => '問題',
	'type' => '類別',
	'options' => '選項',
	'options_help' => '每個選項占一行，可以留空（僅下拉選單）',
	'no_questions' => '還沒有新增任何問題。',
	'new_question' => '新的問題',
	'editing_question' => '編輯問題',
	'delete_question' => '刪除問題',
	'dropdown' => '下拉選單',
	'text' => '文字',
	'textarea' => '文字區塊',
	'question_deleted' => '問題已刪除',
	'use_followers' => '使用追隨者',
	'use_followers_help' => '如果停用此選項，系統將會啟用好友系統。',
	
	// Admin custom pages page
	'click_on_page_to_edit' => '點選一個頁面來編輯',
	'page' => '頁面：',
	'url' => '網址：',
	'page_url' => '頁面網址',
	'page_url_example' => '（包含最前面的「/」，例如「/help/」）',
	'page_title' => '頁面標題',
	'page_content' => '頁面內容',
	'new_page' => '新的頁面',
	'page_successfully_created' => '頁面建立完畢',
	'page_successfully_edited' => '頁面編輯完畢',
	'unable_to_create_page' => '頁面建立失敗',
	'unable_to_edit_page' => '頁面編輯失敗',
	'create_page_error' => '請確認你輸入的網址的長度在 1 ~ 20 個字元之內，標題在 1 ~ 30 個字元之內，且內容在 5 ~ 20480 個字元之內。',
	'delete_page' => '刪除頁面',
	'confirm_delete_page' => '你確定要刪除這個頁面嗎？',
	'page_deleted_successfully' => '頁面刪除完畢',
	'page_link_location' => 'Display page link in:',
	'page_link_navbar' => '導航列',
	'page_link_more' => 'Navbar "More" dropdown',
	'page_link_footer' => '頁尾',
	'page_link_none' => 'No page link',
	'page_permissions' => '頁面權限',
	'can_view_page' => '可查看頁面：',
	'redirect_page' => 'Redirect page?',
	'redirect_link' => 'Redirect link',
	'page_icon' => '頁面圖示',
	
	// Admin forum page
	'labels' => '主題標籤',
	'new_label' => '新的標籤',
	'no_labels_defined' => '尚未定義任何標籤',
	'label_name' => '標籤名稱',
	'label_type' => '標籤類型',
	'label_forums' => 'Label Forums',
	'label_creation_error' => '標籤建立失敗。請確認該標籤的名字不超過 32 個字元，且選擇了一個類型。',
	'confirm_label_deletion' => '你確定要刪除這個標籤嗎？',
	'editing_label' => '編輯標籤',
	'label_creation_success' => '標籤建立完畢',
	'label_edit_success' => '標籤編輯完畢',
	'label_default' => '預設',
	'label_primary' => '主要',
	'label_success' => '成功',
	'label_info' => '資訊',
	'label_warning' => '警告',
	'label_danger' => '危險',
	'new_forum' => '新的論壇',
	'forum_layout' => '論壇樣式',
	'table_view' => 'Table view',
	'latest_discussions_view' => 'Latest Discussions view',
	'create_forum' => '建立論壇',
	'forum_name' => '論壇名稱',
	'forum_description' => '論壇描述',
	'delete_forum' => '刪除論壇',
	'move_topics_and_posts_to' => '將主題與文章移動至',
	'delete_topics_and_posts' => '刪除主題與文章',
	'parent_forum' => '父論壇',
	'has_no_parent' => '沒有父項',
	'forum_permissions' => '論壇權限',
	'can_view_forum' => '可以查看論壇',
	'can_create_topic' => '可以建立主題',
	'can_post_reply' => '可以發表回覆',
	'display_threads_as_news' => '將帖子顯示在首頁上的新聞欄',
	'input_forum_title' => '輸入一個論壇標籤。',
	'input_forum_description' => '輸入一個論壇描述（你可以使用 HTML 語法）。',
	'forum_name_minimum' => '論壇名稱的最小值為不小於 2 個字元。',
	'forum_description_minimum' => '論壇描述的最小值為不小於 2 個字元。',
	'forum_name_maximum' => '論壇名稱的最大值為不超過 150 個字元。',
	'forum_description_maximum' => '論壇描述的最大值為不超過 255 個字元。',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => '類別',
	
	// Admin Users and Groups page
	'users' => '使用者',
	'new_user' => 'New User',
	'created' => 'Created',
	'user_deleted' => 'User deleted',
	'validate_user' => 'Validate User',
	'update_uuid' => 'Update UUID',
	'unable_to_update_uuid' => 'Unable to update UUID.',
	'update_mc_name' => 'Update Minecraft Name',
	'reset_password' => 'Reset Password',
	'punish_user' => 'Punish User',
	'delete_user' => 'Delete User',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Address',
	'ip' => 'IP:',
	'other_actions' => 'Other actions:',
	'disable_avatar' => 'Disable avatar',
	'enable_avatar' => 'Enable avatar',
	'confirm_user_deletion' => 'Are you sure you want to delete the user {x}?', // Don't replace "{x}"
	'groups' => 'Groups',
	'group' => 'Group',
	'new_group' => 'New Group',
	'id' => 'ID',
	'name' => 'Name',
	'create_group' => 'Create Group',
	'group_name' => 'Group Name',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>This is the ID of the group\'s package from Buycraft, MinecraftMarket or MCStock.</p><p>This can be left empty.</p>',
	'donor_group_instructions' => 	'<p>Donor groups must be created in the order of <strong>lowest value to highest value</strong>.</p>
									<p>For example, a £10 package will be created before a £20 package.</p>',
	'delete_group' => 'Delete Group',
	'confirm_group_deletion' => 'Are you sure you want to delete the group {x}?', // Don't replace "{x}"
	'group_staff' => 'Is the group a staff group?',
	'group_modcp' => 'Can the group view the ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'The group name must be a minimum of 2 characters.',
	'group_name_maximum' => 'The group name must be a maximum of 20 characters.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'The user must be in a group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft Settings',
	'use_plugin' => 'Enable Nameless API?',
	'force_avatars' => 'Force Minecraft avatars?',
	'uuid_linking' => 'Enable UUID linking?',
	'use_plugin_help' => 'Enabling the API, along with the upcoming server plugin, allows for rank synchronisation and also ingame registration and report submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Plugin Settings',
	'confirm_api_regen' => 'Are you sure you want to generate a new API key?',
	'servers' => 'Servers',
	'new_server' => 'New Server',
	'confirm_server_deletion' => 'Are you sure you want to delete this server?',
	'main_server' => 'Main Server',
	'main_server_help' => 'The server players connect through. Normally this will be the Bungee instance.',
	'choose_a_main_server' => 'Choose a main server..',
	'external_query' => 'Use external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Editing server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (with port) (numeric or domain)',
	'server_ip_with_port_help' => 'This is the IP which will be displayed to users. It will not be queried.',
	'server_ip_numeric' => 'Server IP (with port) (numeric only)',
	'server_ip_numeric_help' => 'This is the IP which will be queried, please ensure it is numeric only. It will not be displayed to users.',
	'show_on_play_page' => 'Show on Play page?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Server Name',
	'invalid_server_id' => 'Invalid server ID',
	'show_players' => 'Show player list on Play page?',
	'server_edited' => 'Server edited successfully',
	'server_created' => 'Server created successfully',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'No query errors logged',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => 'Are you sure you want to delete this error?',
	'display_server_status' => 'Display server status module?',
	'server_name_required' => 'You must insert a server name.',
	'server_ip_required' => 'You must insert the server\'s IP.',
	'server_name_minimum' => 'The server name must be a minimum of 2 characters.',
	'server_ip_minimum' => 'The server IP must be a minimum of 2 characters.',
	'server_name_maximum' => 'The server name must be a maximum of 20 characters.',
	'server_ip_maximum' => 'The server IP must be a maximum of 64 characters.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	'avatar_type' => 'Avatar type',
	'custom_usernames' => 'Force Minecraft usernames?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Use mcassoc?',
	'use_mcassoc_help' => 'mcassoc ensures users own the Minecraft account they\'re registering with',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Invalid mcassoc key.',
	'mcassoc_instance' => 'mcassoc Instance',
	'mcassoc_instance_help' => 'Generate an instance code <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">here</a>',
	'mcassoc_key_help' => 'Get your mcassoc key <a href="https://mcassoc.lukegb.com/" target="_blank">here</a>',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Themes',
	'templates' => 'Templates',
	'installed_themes' => 'Installed themes',
	'installed_templates' => 'Installed templates',
	'installed_addons' => 'Installed addons',
	'install_theme' => 'Install Theme',
	'install_template' => 'Install Template',
	'install_addon' => 'Install Addon',
	'install_a_theme' => 'Install a theme',
	'install_a_template' => 'Install a template',
	'install_an_addon' => 'Install an addon',
	'active' => 'Active',
	'activate' => 'Activate',
	'deactivate' => 'Deactivate',
	'theme_install_instructions' => 'Please upload themes to the <strong>styles/themes</strong> directory. Then, click the "scan" button below.',
	'template_install_instructions' => 'Please upload templates to the <strong>styles/templates</strong> directory. Then, click the "scan" button below.',
	'addon_install_instructions' => 'Please upload addons to the <strong>addons</strong> directory. Then, click the "scan" button below.',
	'addon_install_warning' => 'Addons are installed at your own risk. Please back up your files and the database before proceeding',
	'scan' => 'Scan',
	'theme_not_exist' => 'That theme doesn\'t exist!',
	'template_not_exist' => 'That template doesn\'t exist!',
	'addon_not_exist' => 'That addon doesn\'t exist!',
	'style_scan_complete' => 'Completed, any new styles have been installed.',
	'addon_scan_complete' => 'Completed, any new addons have been installed.',
	'theme_enabled' => 'Theme enabled.',
	'template_enabled' => 'Template enabled.',
	'addon_enabled' => 'Addon enabled.',
	'theme_deleted' => 'Theme deleted.',
	'template_deleted' => 'Template deleted.',
	'addon_disabled' => 'Addon disabled.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Are you sure you wish to delete the theme <strong>{x}</strong>?<br /><br />The theme will be deleted from your <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />The template will be deleted from your <strong>styles/templates</strong> directory.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Other Settings',
	'enable_error_reporting' => 'Enable error reporting?',
	'error_reporting_description' => 'This should only be used for debugging purposes, it\'s highly recommended this is left as disabled.',
	'display_page_load_time' => 'Display page loading time?',
	'page_load_time_description' => 'Having this enabled will display a speedometer in the footer which will display the page loading time.',
	'reset_website' => 'Reset Website',
	'reset_website_info' => 'This will reset your website settings. <strong>Addons will be disabled but not removed, and their settings will not change.</strong> Your defined Minecraft servers will also remain.',
	'confirm_reset_website' => 'Are you sure you want to reset your website settings?',
	
	// Admin Update page
	'installation_up_to_date' => 'Your installation is up to date.',
	'update_check_error' => 'Unable to check for updates. Please try again later.',
	'new_update_available' => 'A new update is available.',
	'your_version' => 'Your version:',
	'new_version' => 'New version:',
	'download' => 'Download',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Home',
	'play' => 'Play',
	'forum' => 'Forum',
	'more' => 'More',
	'staff_apps' => 'Staff Applications',
	'view_messages' => 'View Messages',
	'view_alerts' => 'View Alerts',
	
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
	'create_an_account' => 'Create an Account',
	'authme_password' => 'AuthMe Password',
	'username' => 'Username',
	'minecraft_username' => 'Minecraft Username',
	'email' => 'Email',
	'user_title' => 'Title',
	'email_address' => 'Email Address',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Password',
	'confirm_password' => 'Confirm Password',
	'i_agree' => 'I Agree',
	'agree_t_and_c' => 'By clicking <strong class="label label-primary">Register</strong>, you agree to our <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a>.',
	'register' => 'Register',
	'sign_in' => 'Sign In',
	'sign_out' => 'Sign Out',
	'terms_and_conditions' => 'Terms and Conditions',
	'successful_signin' => 'You have been signed in successfully',
	'incorrect_details' => 'Incorrect details',
	'remember_me' => 'Remember me',
	'forgot_password' => 'Forgot Password',
	'must_input_username' => 'You must insert a username.',
	'must_input_password' => 'You must insert a password.',
	'inactive_account' => 'Your account is currently inactive. Did you request a password reset?',
	'account_banned' => 'Your account has been banned.',
	'successfully_logged_out' => 'You have been successfully logged out.',
	'signature' => 'Signature',
	'registration_check_email' => 'Please check your emails for a validation link. You won\'t be able to log in until this is clicked.',
	'unknown_login_error' => 'Sorry, there was an unknown error whilst logging you in. Please try again later.',
	'validation_complete' => 'Thanks for registering! You can now log in.',
	'validation_error' => 'Error processing your request. Please try clicking the link again.',
	'registration_error' => 'Please ensure you have filled out all fields, and that your username is between 3 and 20 characters long and your password is between 6 and 30 characters long.',
	'username_required' => 'Please enter a username.',
	'password_required' => 'Please enter a password.',
	'email_required' => 'Please enter an email address.',
	'mcname_required' => 'Please enter a Minecraft username.',
	'accept_terms' => 'You must accept the terms and conditions before registering.',
	'invalid_recaptcha' => 'Invalid reCAPTCHA response.',
	'username_minimum_3' => 'Your username must be a minimum of 3 characters long.',
	'username_maximum_20' => 'Your username must be a maximum of 20 characters long.',
	'mcname_minimum_3' => 'Your Minecraft username must be a minimum of 3 characters long.',
	'mcname_maximum_20' => 'Your Minecraft username must be a maximum of 20 characters long.',
	'password_minimum_6' => 'Your password must be at least 6 characters long.',
	'password_maximum_30' => 'Your password must be a maximum of 30 characters long.',
	'passwords_dont_match' => 'Your passwords do not match.',
	'username_mcname_email_exists' => 'Your username, Minecraft username or email address already exists. Have you already created an account?',
	'invalid_mcname' => 'Your Minecraft username is not a valid account',
	'mcname_lookup_error' => 'There was an error contacting Mojang\'s servers. Please try again later.',
	'signature_maximum_900' => 'Your signature must be a maximum of 900 characters.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
	'verify_account' => 'Verify account',
	'verify_account_help' => 'Please follow the instructions below so we can verify you own the Minecraft account in question.',
	'verification_failed' => 'Verification failed, please try again.',
	'verification_success' => 'Successfully validated! You can now log in.',
	'complete_signup' => 'Complete Signup',
	
	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => 'No file chosen',
	'private_messages' => 'Private Messages',
	'profile_settings' => 'Profile Settings',
	'your_profile' => 'Your Profile',
	'topics' => 'Topics',
	'posts' => 'Posts',
	'reputation' => 'Reputation',
	'friends' => 'Friends',
	'alerts' => 'Alerts',
	
	// Messaging
	'new_message' => 'New Message',
	'no_messages' => 'No messages',
	'and_x_more' => 'and {x} more', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Message Title',
	'message' => 'Message',
	'to' => 'To:',
	'separate_users_with_comma' => 'Separate users with a comma (",")',
	'viewing_message' => 'Viewing Message',
	'delete_message' => 'Delete Message',
	'confirm_message_deletion' => 'Are you sure you want to delete this message?',
	
	// Profile settings
	'display_name' => 'Display name',
	'upload_an_avatar' => 'Upload an avatar (.jpg, .png or .gif only):',
	'use_gravatar' => 'Use Gravatar?',
	'change_password' => 'Change password',
	'current_password' => 'Current password',
	'new_password' => 'New password',
	'repeat_new_password' => 'Repeat new password',
	'password_changed_successfully' => 'Password changed successfully',
	'incorrect_password' => 'Your current password is incorrect',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Display age on profile?',
	'two_factor_authentication' => 'Two Factor Authentication',
	'enable_tfa' => 'Enable Two Factor Authentication',
	'tfa_type' => 'Two Factor Authentication type:',
	'authenticator_app' => 'Authentication App',
	'tfa_scan_code' => 'Please scan the following code within your authentication app:',
	'tfa_code' => 'If your device does not have a camera, or you are unable to scan the QR code, please input the following code:',
	'tfa_enter_code' => 'Please enter the code displaying within your authentication app:',
	'invalid_tfa' => 'Invalid code, please try again.',
	'tfa_successful' => 'Two factor authentication set up successfully. You will need to authenticate every time you log in from now on.',
	'confirm_tfa_disable' => 'Are you sure you wish to disable two factor authentication?',
	'tfa_disabled' => 'Two factor authentication disabled.',
	'tfa_enter_email_code' => 'We have sent you a code within an email for verification. Please enter the code now:',
	'tfa_email_contents' => 'A login attempt has been made to your account. If this was you, please input the following two factor authentication code when asked to do so. If this was not you, you can ignore this email, however a password reset is advised. The code is only valid for 10 minutes.',
	
	// Alerts
	'viewing_unread_alerts' => 'Viewing unread alerts. Change to <a href="/user/alerts/?view=read"><span class="label label-success">read</span></a>.',
	'viewing_read_alerts' => 'Viewing read alerts. Change to <a href="/user/alerts/"><span class="label label-warning">unread</span></a>.',
	'no_unread_alerts' => 'You have no unread alerts.',
	'no_alerts' => 'No alerts',
	'no_read_alerts' => 'You have no read alerts.',
	'view' => 'View',
	'alert' => 'Alert',
	'when' => 'When',
	'delete' => 'Delete',
	'tag' => 'User Tag',
	'tagged_in_post' => 'You have been tagged in a post',
	'report' => 'Report',
	'deleted_alert' => 'Alert successfully deleted',
	
	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',
	
	// Forgot password
	'password_reset' => 'Password Reset',
	'email_body' => 'You are receiving this email because you requested a password reset. In order to reset your password, please use the following link:', // Body for the password reset email
	'email_body_2' => 'If you did not request the password reset, you can ignore this email.',
	'password_email_set' => 'Success. Please check your emails for further instructions.',
	'username_not_found' => 'That username does not exist.',
	'change_password' => 'Change Password',
	'your_password_has_been_changed' => 'Your password has been changed.',
	
	// Profile page
	'profile' => 'Profile',
	'player' => 'Player',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registered:',
	'pf_posts' => 'Posts:',
	'pf_reputation' => 'Reputation:',
	'user_hasnt_registered' => 'This user hasn\'t registered on our website yet',
	'user_no_friends' => 'This user has not added any friends',
	'send_message' => 'Send Message',
	'remove_friend' => 'Remove Friend',
	'add_friend' => 'Add Friend',
	'last_online' => 'Last Online:',
	'find_a_user' => 'Find a user',
	'user_not_following' => 'This user does not follow anyone.',
	'user_no_followers' => 'This user has no followers.',
	'following' => 'FOLLOWING',
	'followers' => 'FOLLOWERS',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Write something on your profile...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'About',
	'reply' => 'Reply',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'No likes.',
	'post_liked' => 'Post liked.',
	'post_unliked' => 'Post unliked.',
	'no_posts' => 'No posts.',
	'last_5_posts' => 'Last 5 posts',
	'follow' => 'Follow',
	'unfollow' => 'Unfollow',
	'name_history' => 'Name History',
	'changed_name_to' => 'Changed name to: {x} on {y}', // Don't replace {x} or {y}
	'original_name' => 'Original name:',
	'name_history_error' => 'Unable to retrieve username history.',
	
	// Staff applications
	'staff_application' => 'Staff Application',
	'application_submitted' => 'Application submitted successfully.',
	'application_already_submitted' => 'You\'ve already submitted an application. Please wait until it is complete before submitting another.',
	'not_logged_in' => 'Please log in to view that page.',
	'application_accepted' => 'Your staff application has been accepted.',
	'application_rejected' => 'Your staff application has been rejected.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Overview',
	'reports' => 'Reports',
	'punishments' => 'Punishments',
	'staff_applications' => 'Staff Applications',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warn',
	'search_for_a_user' => 'Search for a user',
	'user' => 'User:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registered',
	'reason' => 'Reason:',
	'cant_ban_root_user' => 'Can\'t punish the root user!',
	'invalid_reason' => 'Please enter a valid reason between 2 and 256 characters long.',
	'punished_successfully' => 'Punishment added successfully.',
	
	// Reports
	'report_closed' => 'Report closed.',
	'new_comment' => 'New comment',
	'comments' => 'Comments',
	'only_viewed_by_staff' => 'Can only be viewed by staff',
	'reported_by' => 'Reported by',
	'close_issue' => 'Close issue',
	'report' => 'Report:',
	'view_reported_content' => 'View reported content',
	'no_open_reports' => 'No open reports',
	'user_reported' => 'User Reported',
	'type' => 'Type',
	'updated_by' => 'Updated By',
	'forum_post' => 'Forum Post',
	'user_profile' => 'User Profile',
	'comment_added' => 'Comment added.',
	'new_report_submitted_alert' => 'New report submitted by {x} regarding user {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Ingame Report',
	
	// Staff applications
	'comment_error' => 'Please ensure your comment is between 2 and 2048 characters long.',
	'viewing_open_applications' => 'Viewing <span class="label label-info">open</span> applications. Change to <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_accepted_applications' => 'Viewing <span class="label label-success">accepted</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_declined_applications' => 'Viewing <span class="label label-danger">declined</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a>.',
	'time_applied' => 'Time Applied',
	'no_applications' => 'No applications in this category',
	'viewing_app_from' => 'Viewing application from {x}', // Don't replace "{x}"
	'open' => 'Open',
	'accepted' => 'Accepted',
	'declined' => 'Declined',
	'accept' => 'Accept',
	'decline' => 'Decline',
	'new_app_submitted_alert' => 'New application submitted by {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'News',
	'social' => 'Social',
	'join' => 'Join',
	
	// General terms
	'submit' => 'Submit',
	'close' => 'Close',
	'cookie_message' => '<strong>This site uses cookies to enhance your experience.</strong><p>By continuing to browse and interact with this website, you agree with their use.</p>',
	'theme_not_exist' => 'The selected theme does not exist.',
	'confirm' => 'Confirm',
	'cancel' => 'Cancel',
	'guest' => 'Guest',
	'guests' => 'Guests',
	'back' => 'Back',
	'search' => 'Search',
	'help' => 'Help',
	'success' => 'Success',
	'error' => 'Error',
	'view' => 'View',
	'info' => 'Info',
	'next' => 'Next',
	
	// Play page
	'connect_with' => 'Connect to the server with the IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Players Online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'There are no players online!',
	'x_players_online' => 'There are {x} players online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Page loaded in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'Sorry, we couldn\'t find that page.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => '論壇',
	'discussion' => '討論',
	'stats' => '統計資料',
	'last_reply' => '最新的回覆',
	'ago' => 'ago',
	'by' => 'by',
	'in' => 'in',
	'views' => 'views',
	'posts' => 'posts',
	'topics' => 'topics',
	'topic' => 'Topic',
	'statistics' => '統計資料',
	'overview' => '概覽',
	'latest_discussions' => '最新的討論串',
	'latest_posts' => '最新的文章',
	'users_registered' => '已註冊的使用者：',
	'latest_member' => '最新加入的使用者：',
	'forum' => '論壇',
	'last_post' => '最新的文章',
	'no_topics' => '這裡還沒有任何主題',
	'new_topic' => '新的主題',
	'subforums' => '子論壇：',
	
	// View topic view
	'home' => '主頁',
	'topic_locked' => '主題已上鎖',
	'new_reply' => '新的回覆',
	'mod_actions' => '管理動作',
	'lock_thread' => '鎖定帖子',
	'unlock_thread' => '解鎖帖子',
	'merge_thread' => '合併帖子',
	'delete_thread' => '刪除帖子',
	'confirm_thread_deletion' => '你確定要刪除這個帖子嗎？',
	'move_thread' => '移動帖子',
	'sticky_thread' => 'Sticky Thread',
	'report_post' => '回報文章',
	'quote_post' => '引用文章',
	'delete_post' => '刪除文章',
	'edit_post' => '編輯文章',
	'reputation' => 'reputation',
	'confirm_post_deletion' => '你確定要刪除此篇文章嗎？',
	'give_reputation' => 'Give reputation',
	'remove_reputation' => 'Remove reputation',
	'post_reputation' => 'Post Reputation',
	'no_reputation' => 'No reputation for this post yet',
	're' => 'RE:',
	
	// Create post view
	'create_post' => '建立文章',
	'post_submitted' => '文章已提交',
	'creating_post_in' => 'Creating post in: ',
	'topic_locked_permission_post' => '此主題已被上鎖，不過你還是有發文的權限。',
	
	// Edit post view
	'editing_post' => '正在編輯文章',
	
	// Sticky threads
	'thread_is_' => 'Thread is ',
	'now_sticky' => 'now a sticky thread',
	'no_longer_sticky' => 'no longer a sticky thread',
	
	// Create topic
	'topic_created' => '主題建立完畢。',
	'creating_topic_in_' => '正在建立主題至 ',
	'thread_title' => '帖子標題',
	'confirm_cancellation' => '你確定嗎？',
	'label' => '標籤',
	
	// Reports
	'report_submitted' => '回報完成。',
	'view_post_content' => '查看文章內容',
	'report_reason' => '舉報原因',
	
	// Move thread
	'move_to' => '移動至：',
	
	// Merge threads
	'merge_instructions' => '兩個要合併的帖子<strong>必須</strong>在同個論壇下。Move a thread if necessary.',
	'merge_with' => '與 ... 合併：',
	
	// Other
	'forum_error' => '對不起，我們找不到相關的論壇或主題。',
	'are_you_logged_in' => '你登入了嗎？',
	'online_users' => '線上的使用者',
	'no_users_online' => '沒有使用者在線上',
	
	// Search
	'search_error' => 'Please input a search query between 1 and 32 characters long.',
	'no_search_results' => 'No search results have been found.',
	
	//Share on a social-media.
	'sm-share' => '分享',
	'sm-share-facebook' => '分享到 Facebook',
	'sm-share-twitter' => '分享到 Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => '嗨',
	'message' => '感謝你的註冊！若要完成註冊，請點選這個連結：',
	'thanks' => '謝謝您'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => '秒', // Shortened "seconds", eg "s"
	'less_than_a_minute' => '剛剛',
	'1_minute' => '一分鐘之前',
	'_minutes' => '{x} 分鐘之前',
	'about_1_hour' => '約一個小時之前',
	'_hours' => '{x} 個小時之前',
	'1_day' => '昨天',
	'_days' => '{x} 天之前',
	'about_1_month' => '約一個月之前',
	'_months' => '{x} 個月之前',
	'about_1_year' => '約一年前',
	'over_x_years' => '超過 {x} 年之前'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => '每頁顯示 _MENU_ 項記錄', // Don't replace "_MENU_"
	'nothing_found' => '找不到結果',
	'page_x_of_y' => '第 _PAGE_ 頁（共 _PAGES_ 頁面）', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => '沒有相關記錄',
	'filtered' => '（自共 _MAX_ 項記錄中篩選而出）' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => '完成註冊'
);
 
?>
