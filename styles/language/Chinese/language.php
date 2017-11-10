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
	'admin_cp' => '管理者後台',
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
	'forums' => '討論區',
	'users_and_groups' => '使用者與組別',
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
	'announcement_can_close' => '可以關閉公告嗎？',
	'announcement_permissions' => '公告權限',
	'no_announcements' => '目前還沒有建立任何公告。',
	'confirm_cancel_announcement' => '你確定要取消這個公告嗎？',
	'announcement_location_help' => '按住 Ctrl 並點選以選擇多個頁面',
	'select_all' => '全選',
	'deselect_all' => '取消全選',
	'announcement_created' => '公告建立完畢',
	'please_input_announcement_content' => '請輸入公告內容並且選擇一個類型',
	'confirm_delete_announcement' => '你確定要刪除這個公告嗎？',
	'announcement_actions' => '公告操作',
	'announcement_deleted' => '公告刪除完成',
	'announcement_type' => '公告類型',
	'can_view_announcement' => '可以查看公告嗎？',
	
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
	'explain_email_settings' => '若你停用了「使用 PHP mail() 函式」 選項，則以下項目為必填。你可以在<a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">我們的維基頁面</a>上找到更多關於此項設定的資料。',
	'email_config_not_writable' => '無法寫入 <strong>core/email.php</strong>，請檢查檔案權限。',
	'pages' => '頁面',
	'enable_or_disable_pages' => '在這裡啟用或停用頁面。',
	'enable' => '啟用',
	'disable' => '停用',
	'maintenance_mode' => '討論區維護模式',
	'forum_in_maintenance' => '討論區目前處於維護模式中',
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
	'accept_reject_applications' => '接受/拒絕申請',
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
	'name_required' => 'Name is required.',
	'question_required' => 'Question is required.',
	'name_minimum' => 'Name must be a minimum of 2 characters.',
	'question_minimum' => 'Question must be a minimum of 2 characters.',
	'name_maximum' => 'Name must be a maximum of 16 characters.',
	'question_maximum' => 'Question must be a maximum of 16 characters.',
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
	'page_link_location' => '頁面連結顯示位置：',
	'page_link_navbar' => '導航列',
	'page_link_more' => '導航列的「更多」下拉選單',
	'page_link_footer' => '頁尾',
	'page_link_none' => '不顯示連結',
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
	'new_forum' => '新的討論區',
	'forum_layout' => '討論區樣式',
	'table_view' => '顯示討論區列表',
	'latest_discussions_view' => '顯示最新文章',
	'create_forum' => '建立討論區',
	'forum_name' => '討論區名稱',
	'forum_description' => '討論區描述',
	'delete_forum' => '刪除討論區',
	'move_topics_and_posts_to' => '將主題與文章移動至',
	'delete_topics_and_posts' => '刪除主題與文章',
	'parent_forum' => '父討論區',
	'has_no_parent' => '沒有父項',
	'forum_permissions' => '討論區權限',
	'can_view_forum' => '可以查看討論區',
	'can_create_topic' => '可以建立主題',
	'can_post_reply' => '可以發表回覆',
	'display_threads_as_news' => '將討論串顯示在首頁上的新聞欄',
	'input_forum_title' => '輸入一個討論區標籤。',
	'input_forum_description' => '輸入一個討論區描述（你可以使用 HTML 語法）。',
	'forum_name_minimum' => '討論區名稱的最小值為不小於 2 個字元。',
	'forum_description_minimum' => '討論區描述的最小值為不小於 2 個字元。',
	'forum_name_maximum' => '討論區名稱的最大值為不超過 150 個字元。',
	'forum_description_maximum' => '討論區描述的最大值為不超過 255 個字元。',
	'forum_type_forum' => '討論區',
	'forum_type_category' => '作為類別使用',
	
	// Admin Users and Groups page
	'users' => '使用者',
	'new_user' => '新的使用者',
	'created' => 'Created',
	'user_deleted' => '使用者已刪除',
	'validate_user' => '驗證這個使用者',
	'update_uuid' => '更新 UUID',
	'unable_to_update_uuid' => 'UUID 更新失敗。',
	'update_mc_name' => '更新 Minecraft 名稱',
	'reset_password' => '重設密碼',
	'punish_user' => '懲罰使用者',
	'delete_user' => '刪除使用者',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP 位址',
	'ip' => 'IP 位址：',
	'other_actions' => '其他動作：',
	'disable_avatar' => '停用大頭照',
	'enable_avatar' => '啟用大頭照',
	'confirm_user_deletion' => '你確定要刪除使用者「{x}」嗎？', // Don't replace "{x}"
	'groups' => '組別',
	'group' => '組別',
	'group' => '組別 2',
	'new_group' => '新的組別',
	'id' => 'ID',
	'name' => '名稱',
	'create_group' => '建立組別',
	'group_name' => '組別名稱',
	'group_html' => '組別 HTML',
	'group_html_lg' => '組別 HTML Large',
	'donor_group_id' => '贊助者商品 ID',
	'donor_group_id_help' => '<p>該組別的 Buycraft、MinecraftMarket 或 MCStock 商品 ID。</p><p>此處可以留空。</p>',
	'donor_group_instructions' => 	'<p>贊助者的組別必須以價格<strong>從小到大</strong>的順序依次建立。</p>
									<p>例如，一個 $100 新台幣的項目必須在一個 $200 新台幣的項目之前建立好。</p>',
	'delete_group' => '刪除組別',
	'confirm_group_deletion' => '你確定要刪除組別 {x} 嗎？', // Don't replace "{x}"
	'group_staff' => '這是工作人員組別嗎？',
	'group_modcp' => '這個組別可以查看 ModCP 嗎？',
	'group_admincp' => '這個組別可以查看 AdminCP 嗎？',
	'group_name_required' => '你必須填寫一個組別名稱。',
	'group_name_minimum' => '組別名稱最少要有 2 個字元。',
	'group_name_maximum' => '組別名稱最多不能超過 20 個字元。',
	'html_maximum' => '組別 HTML 最多不能超過 1024 個字元。',
	'select_user_group' => '該使用者必須在一個組別內。',
	'uuid_max_32' => 'UUID 最多不能超過 32 個字元。',
	'cant_delete_root_user' => '無法刪除 root 使用者！',
	'cant_modify_root_user' => '無法變更 root 使用者的組別.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft 設定',
	'use_plugin' => '啟用 Nameless API？',
	'force_avatars' => '強制使用 Minecraft 大頭照',
	'uuid_linking' => '啟用 UUID 連結',
	'use_plugin_help' => '啟用 API 與即將到來的伺服器插件將讓你能夠同步 rank，且在遊戲內註冊本站的賬號，以及檢舉玩家。',
	'uuid_linking_help' => '如果停用這個選項，使用者賬號將不會與其 UUID 連結。強烈建議啟用此選項。',
	'plugin_settings' => '插件設定',
	'confirm_api_regen' => '你確定要產生一個新的 API 金鑰嗎？',
	'servers' => '伺服器',
	'new_server' => '新的伺服器',
	'confirm_server_deletion' => '你確定要移除這個伺服器嗎？',
	'main_server' => '主伺服器',
	'main_server_help' => '玩家連線的伺服器。一般而言這個伺服器會是個 BungeeCord 實例。',
	'choose_a_main_server' => '選擇一個主伺服器 ...',
	'external_query' => '使用外部查詢',
	'external_query_help' => '使用外部 API 來查詢 Minecraft 伺服器。如果內建的查詢功能無法使用再啟用此項，否則我們強烈建議停用此項。',
	'editing_server' => '正在編輯伺服器 {x}', // Don't replace "{x}"
	'server_ip_with_port' => '伺服器 IP 位址（含連線埠）（可為數字或網域名稱）',
	'server_ip_with_port_help' => '這是使用者會看到的 IP 位址，系統不會查詢它。',
	'server_ip_numeric' => '伺服器 IP 位址（含連線埠）（僅數字與小數點）',
	'server_ip_numeric_help' => '系統會查詢這個 IP 位址，請確認它僅含數字與小數點。使用者不會看到此項。',
	'show_on_play_page' => '要顯示在「遊玩」頁面上嗎？',
	'pre_17' => 'Pre 1.7 Minecraft 版本',
	'server_name' => '伺服器名稱',
	'invalid_server_id' => '伺服器 ID 錯誤',
	'show_players' => '在「遊玩」頁面上顯示玩家列表',
	'server_edited' => '伺服器編輯完畢',
	'server_created' => '伺服器建立完畢',
	'query_errors' => '查詢發生的錯誤',
	'query_errors_info' => '你可以用以下的錯誤資訊來診斷內部伺服器查詢的問題。',
	'no_query_errors' => '沒有任何查詢錯誤',
	'date' => '日期：',
	'port' => '連線埠：',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => '你確定要刪除這個錯誤嗎？',
	'display_server_status' => '顯示伺服器狀態模組',
	'server_name_required' => '你必須輸入一個伺服器名稱。',
	'server_ip_required' => '你必須輸入該伺服器的 IP 位址。',
	'server_name_minimum' => '伺服器名稱最少要有 2 個字元。',
	'server_ip_minimum' => '伺服器 IP 位址最少要有 2 個字元。',
	'server_name_maximum' => '伺服器名稱最最多可以有 20 個字元。',
	'server_ip_maximum' => '伺服器 IP 位址最多可以有 64 個字元。',
	'purge_errors' => '清除錯誤',
	'confirm_purge_errors' => '你確定要清除所有查詢錯誤嗎？',
	'avatar_type' => '大頭照類型',
	'custom_usernames' => '強制使用 Minecraft 名稱',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => '使用 mcassoc',
	'use_mcassoc_help' => 'mcassoc 可以確保使用者擁有他們正用來註冊本站賬號的 Minecraft 賬號',
	'mcassoc_key' => 'mcassoc 共享金鑰',
	'invalid_mcassoc_key' => 'mcassoc 金鑰不正確',
	'mcassoc_instance' => 'mcassoc Instance',
	'mcassoc_instance_help' => '在<a href="http://jsbin.com/jadofehoqu/1/" target="_blank">這裡</a>建立一個 Instance 代碼',
	'mcassoc_key_help' => '在<a href="https://mcassoc.lukegb.com/" target="_blank">這裡</a>取得你的 mcassoc 金鑰',
	'enable_name_history' => 'Enable profile username history?',
	
	// Admin Themes, Templates and Addons
	'themes' => '佈景主題',
	'templates' => '模板',
	'installed_themes' => '已安裝的佈景主題',
	'installed_templates' => '已安裝的模板',
	'installed_addons' => '已安裝的插件',
	'install_theme' => '安裝佈景主題',
	'install_template' => '安裝模板',
	'install_addon' => '安裝插件',
	'install_a_theme' => '安裝一個佈景主題',
	'install_a_template' => '安裝一個模板',
	'install_an_addon' => '安裝一個插件',
	'active' => '使用中',
	'activate' => '啟用',
	'deactivate' => '停用',
	'theme_install_instructions' => '請上傳佈景主題到 <strong>styles/themes</strong>，然後點選下方的「掃描」按鈕。',
	'template_install_instructions' => '請上傳模板到 <strong>styles/templates</strong>，然後點選下方的「掃描」按鈕。',
	'addon_install_instructions' => '請上傳插件到 <strong>addons</strong>，然後點選下方的「掃描」按鈕。',
	'addon_install_warning' => '安裝插件有一定的風險，安裝前請備份你的檔案與資料庫',
	'scan' => '掃描',
	'theme_not_exist' => '找不到該佈景主題。',
	'template_not_exist' => '找不到該模板。',
	'addon_not_exist' => '找不到該插件。',
	'style_scan_complete' => '完成，所有的新樣式都安裝好了。',
	'addon_scan_complete' => '完成，所有的新插件都安裝好了。',
	'theme_enabled' => '佈景主題啟用完畢。',
	'template_enabled' => '模板啟用完畢。',
	'addon_enabled' => '插件啟用完畢。',
	'theme_deleted' => '佈景主題刪除完畢。',
	'template_deleted' => '模板刪除完畢。',
	'addon_disabled' => '插件停用完畢。',
	'inverse_navbar' => '反轉導航列顏色',
	'confirm_theme_deletion' => '你確認要刪除佈景主題 <strong>{x}</strong>嗎？<br /><br />該佈景主題將會自 <strong>styles/themes</strong> 刪除。', // Don't replace {x}
	'confirm_template_deletion' => '你確認要刪除模板 <strong>{x}</strong>嗎？<br /><br />該模板將會自 <strong>styles/templates</strong> 刪除。', // Don't replace {x}
	'unable_to_enable_addon' => 'Could not enable addon. Please ensure it is a valid NamelessMC addon.',
	
	// Admin Misc page
	'other_settings' => '其他設定',
	'enable_error_reporting' => '顯示錯誤',
	'error_reporting_description' => '這應該只拿來作除錯用，非常建議停用此項目。',
	'display_page_load_time' => '顯示頁面載入時間',
	'page_load_time_description' => '若啟用此項，頁尾將會顯示頁面載入時間。',
	'reset_website' => '重設網站',
	'reset_website_info' => '這將會重設本站的設定。<strong>插件將會被停用，並不會被刪除，且他們的設定將不會被變更。</strong>你設定的 Minecraft 伺服器資料也會被保留。',
	'confirm_reset_website' => '你確定要重設本站的設定嗎？',
	
	// Admin Update page
	'installation_up_to_date' => '已經是最新版本。',
	'update_check_error' => '無法檢查更新，請稍後再重試。',
	'new_update_available' => '可以進行更新',
	'your_version' => '目前的版本：',
	'new_version' => '新的版本：',
	'download' => '下載',
	'update_warning' => '警告：請確認你已經下載了更新壓縮檔並已上傳了裡面的資料！'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => '主頁',
	'play' => '遊玩',
	'forum' => '討論區',
	'more' => 'More',
	'staff_apps' => '申請成為工作人員',
	'view_messages' => '查看訊息',
	'view_alerts' => '查看通知',
	
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
	'create_an_account' => '建立一個賬號',
	'authme_password' => 'AuthMe 密碼',
	'username' => '使用者名稱',
	'minecraft_username' => 'Minecraft 名稱',
	'email' => '電子郵件',
	'user_title' => 'Title',
	'email_address' => '電子郵件位址',
	'date_of_birth' => '生日',
	'location' => '所在地區',
	'password' => '密碼',
	'confirm_password' => '確認密碼',
	'i_agree' => '我同意',
	'agree_t_and_c' => '當你點下<strong class="label label-primary">註冊</strong>按鈕，即表示您同意我們的<a href="#" data-toggle="modal" data-target="#t_and_c_m">服務條款</a>。',
	'register' => '註冊',
	'sign_in' => '登入',
	'sign_out' => '登出',
	'terms_and_conditions' => '服務條款',
	'successful_signin' => '登入成功',
	'incorrect_details' => '錯誤的使用者名稱或密碼',
	'remember_me' => '記住我',
	'forgot_password' => '忘記密碼',
	'must_input_username' => '你必須輸入使用者名稱。',
	'must_input_password' => '你必須輸入密碼。',
	'inactive_account' => '你的賬號目前已被停用，你是不是有要求重設密碼？',
	'account_banned' => '你的賬號已被封鎖。',
	'successfully_logged_out' => '登出成功。',
	'signature' => '簽名檔',
	'registration_check_email' => '請到你的電子信箱內尋找賬號啟用連結。在你點選該連結之前，你將無法登入。',
	'unknown_login_error' => '抱歉，將您登入時發生未知錯誤。請稍後再重試。',
	'validation_complete' => '感謝您的註冊！您現在可以登入。',
	'validation_error' => '無法處理您的請求。請試著重新點選該連結。',
	'registration_error' => '請確認你填寫了所有的欄位，且你的使用者名稱的長度在 3 ~ 20 個字元之間，密碼長度在 6 ~ 30 個字元之間。',
	'username_required' => '請輸入使用者名稱。',
	'password_required' => '請輸入密碼。',
	'email_required' => '請輸入電子郵件位址。',
	'mcname_required' => '請輸入 Minecraft 名稱。',
	'accept_terms' => '你必須同意服務條款以繼續',
	'invalid_recaptcha' => 'reCAPTCHA 回應驗證失敗。',
	'username_minimum_3' => '你的使用者名稱最少要有 3 個字元。',
	'username_maximum_20' => '你的使用者名稱長度不能超過 20 個字元。',
	'mcname_minimum_3' => '你的 Minecraft 名稱最少要有 3 個字元。',
	'mcname_maximum_20' => '你的 Minecraft 名稱長度不能超過 20 個字元。',
	'password_minimum_6' => '你的密碼最少要有 6 個字元。',
	'password_maximum_30' => '你的密碼長度不能超過 20 個字元。',
	'passwords_dont_match' => '兩次輸入的密碼不一致。',
	'username_mcname_email_exists' => '你的使用者名稱、Minecraft 名稱或電子郵件位址已被使用。你是不是已經註冊過了？',
	'invalid_mcname' => '你輸入的 Minecraft 名稱並不是一個合法的賬戶',
	'mcname_lookup_error' => '無法與 Mojang 伺服器驗證您的賬戶，請稍後重試。',
	'signature_maximum_900' => '你的簽名檔長度不能超過 900 個字元。',
	'invalid_date_of_birth' => '請輸入一個正確的生日。',
	'location_required' => '請輸入所在地區。',
	'location_minimum_2' => '你的所在地區名稱長度至少要 2 個字元。',
	'location_maximum_128' => '你的所在地區名稱長度最多不可超過 128 個字元。',
	'verify_account' => '驗證賬號',
	'verify_account_help' => '請跟隨下方的指示以驗證我們認為您有問題的 Minecraft 賬號。',
	'verification_failed' => '驗證失敗，請稍後再試一次。',
	'verification_success' => '驗證完成！您現在可以登入。',
	'complete_signup' => '完成註冊',
	'registration_disabled' => '註冊功能目前已被停用。',
	
	// UserCP
	'user_cp' => '個人設定',
	'no_file_chosen' => '未選擇任何檔案',
	'private_messages' => '私人訊息',
	'profile_settings' => '個人資料設定',
	'your_profile' => '你的主頁',
	'topics' => '主題',
	'posts' => '文章',
	'reputation' => '聲望',
	'friends' => '好友',
	'alerts' => '通知',
	
	// Messaging
	'new_message' => '新的訊息',
	'no_messages' => '沒有訊息',
	'and_x_more' => '還有 {x} 個', // Don't replace "{x}"
	'system' => '系統',
	'message_title' => '訊息標題',
	'message' => '訊息',
	'to' => 'To:',
	'separate_users_with_comma' => '以半形逗點來區隔使用者（「,」）',
	'viewing_message' => '正在查看訊息',
	'delete_message' => '刪除訊息',
	'confirm_message_deletion' => '你確定要刪除這個訊息嗎？',
	
	// Profile settings
	'display_name' => '顯示名稱',
	'upload_an_avatar' => '上傳一張大頭照（你只能上傳 jpg、png 或 gif 格式的圖片檔案）：',
	'use_gravatar' => '使用 Gravatar',
	'change_password' => '變更密碼',
	'current_password' => '現在的密碼',
	'new_password' => '新的密碼',
	'repeat_new_password' => '重複新的密碼',
	'password_changed_successfully' => '密碼重設完畢',
	'incorrect_password' => '現在的密碼輸入錯誤',
	'update_minecraft_name_help' => '這將會把你在本站的使用者名稱變更為你現在的 Minecraft 使用者名稱。你每隔 30 天可以執行一次此動作。',
	'unable_to_update_mcname' => '無法更新 Minecraft 使用者名稱.',
	'display_age_on_profile' => '在個人資料上顯示年齡',
	'two_factor_authentication' => '雙因素驗證',
	'enable_tfa' => '啟用雙因素驗證',
	'tfa_type' => '雙因素驗證類型：',
	'authenticator_app' => '雙因素驗證 App',
	'tfa_scan_code' => '請在你的雙因素驗證應用程式內掃描這個 QR code：',
	'tfa_code' => '如果你的設備沒有相機，或是你無法掃描 QR code，請手動輸入這個代碼：',
	'tfa_enter_code' => '請將此代碼輸入到你的雙因素驗證應用程式內：',
	'invalid_tfa' => '代碼錯誤，請重試。',
	'tfa_successful' => '雙因素驗證設定完畢。從現在開始，你每次登入我們都會要求你進行雙因素驗證。',
	'confirm_tfa_disable' => '你確定要停用雙因素驗證嗎？',
	'tfa_disabled' => '雙因素驗證停用完畢。',
	'tfa_enter_email_code' => '我們已經傳送了一個驗證碼到你的電子郵件位址，請輸入該驗證碼：',
	'tfa_email_contents' => '有人嘗試登入你的賬號。如果那是你，當你被要求輸入雙因素驗證代碼時，請輸入以下的代碼。如果那不是你，你可以忽略這封電子郵件，不過我們建議你重設你的密碼。這個代碼在十分鐘內有效。',
	
	// Alerts
	'viewing_unread_alerts' => '正在查看未讀的通知。標記為<a href="/user/alerts/?view=read"><span class="label label-success">已讀</span></a>。',
	'viewing_read_alerts' => '正在查看已讀的通知。標記為<a href="/user/alerts/"><span class="label label-warning">未讀</span></a>。',
	'no_unread_alerts' => '你沒有未讀的通知。',
	'no_alerts' => '沒有通知',
	'no_read_alerts' => '你沒有已讀的通知。',
	'view' => 'View',
	'alert' => '通知',
	'when' => 'When',
	'delete' => 'Delete',
	'tag' => 'User Tag',
	'tagged_in_post' => '你在一則文章內被標記',
	'report' => 'Report',
	'deleted_alert' => '通知刪除完畢',
	
	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => '確認',
	
	// Forgot password
	'password_reset' => '密碼重設',
	'email_body' => '你因為請求重設密碼而受到了這封電子郵件。若你要重設你的密碼，請點選這個連結：', // Body for the password reset email
	'email_body_2' => '如果你沒有要重設你的密碼，直接忽略這封電子郵件即可。',
	'password_email_set' => '完成。請查看您的電子郵件以繼續。',
	'username_not_found' => '找不到這個使用者名稱。',
	'change_password' => '變更密碼',
	'your_password_has_been_changed' => '密碼變更完畢。',
	
	// Profile page
	'profile' => '主頁',
	'player' => '玩家',
	'offline' => '離線',
	'online' => '在線上',
	'pf_registered' => '註冊日期：',
	'pf_posts' => '文章數：',
	'pf_reputation' => '聲望：',
	'user_hasnt_registered' => '這個人尚未在本站註冊',
	'user_no_friends' => '這個人還沒有加任何好友',
	'send_message' => '傳送一個訊息',
	'remove_friend' => '刪除好友',
	'add_friend' => '添加好友',
	'last_online' => '最近一次上線：',
	'find_a_user' => '找一個人：',
	'user_not_following' => '這個人沒有追隨任何人。',
	'user_no_followers' => '沒有人追隨這個邊緣人。',
	'following' => 'FOLLOWING',
	'followers' => 'FOLLOWERS',
	'display_location' => '來自 {x}。', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x} 歲，來自 {y}。', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => '在 {x} 的主頁上寫些什麼吧 ...', // Don't replace {x}
	'write_on_own_profile' => '在你的主頁上寫些什麼吧 ...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => '關於',
	'reply' => '回覆',
	'x_likes' => '{x} 個讃', // Don't replace {x}
	'likes' => '讃數',
	'no_likes' => '沒有任何讃',
	'post_liked' => '給了該文章一個讃。',
	'post_unliked' => '收回了對該文章的讃。',
	'no_posts' => '沒有文章。',
	'last_5_posts' => '最新的五篇文章',
	'follow' => '追蹤',
	'unfollow' => '取消追蹤',
	'name_history' => 'Minecraft 名稱歷史',
	'changed_name_to' => '在 {y} 變更為 {x}', // Don't replace {x} or {y}
	'original_name' => '起初的名字：',
	'name_history_error' => '無法取得使用者名稱變更歷史。',
	
	// Staff applications
	'staff_application' => '工作人員申請',
	'application_submitted' => '表單提交完成。',
	'application_already_submitted' => '你已經提交了一個申請表單，前一個申請必須完成以提交另一個。',
	'not_logged_in' => '登入以繼續',
	'application_accepted' => '您的工作人員申請已被接受。',
	'application_rejected' => '您的工作人員申請已被回絕。'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => '工作人員後台',
	'overview' => '概覽',
	'reports' => '舉報',
	'punishments' => '懲處',
	'staff_applications' => '工作人員申請表單',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => '取消 Ban',
	'warn' => '警告',
	'search_for_a_user' => '搜尋使用者',
	'user' => '使用者：',
	'ip_lookup' => 'IP 位置查詢：',
	'registered' => '註冊時間',
	'reason' => '原因：',
	'cant_ban_root_user' => '無法處罰 root 使用者！',
	'invalid_reason' => '請輸入一個 2 ~ 256 字元長的理由。',
	'punished_successfully' => '懲處增加完畢。',
	
	// Reports
	'report_closed' => '舉報已關閉',
	'new_comment' => '新的回覆',
	'comments' => '回覆',
	'only_viewed_by_staff' => '只有工作人員可以查看',
	'reported_by' => '檢舉人：',
	'close_issue' => '關閉問題',
	'report' => '舉報：',
	'view_reported_content' => 'View reported content',
	'no_open_reports' => '沒有待受理的舉報',
	'user_reported' => 'User Reported',
	'type' => '類型',
	'updated_by' => 'Updated By',
	'forum_post' => 'Forum Post',
	'user_profile' => '使用者主頁',
	'comment_added' => '已新增回覆。',
	'new_report_submitted_alert' => '{x} 剛剛檢舉了 {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => '遊戲內的檢舉',
	
	// Staff applications
	'comment_error' => '請確認您的回覆在 2 ~ 2048 個字元之內。',
	'viewing_open_applications' => '正在查看<span class="label label-info">待受理的</span>申請表單。<a href="/mod/applications/?view=accepted"><span class="label label-success">變更狀態為接受</span></a>或<a href="/mod/applications/?view=declined"><span class="label label-danger">拒絕</span></a>.',
	'viewing_accepted_applications' => '正在查看<span class="label label-success">被接受的</span>申請表單。<a href="/mod/applications/"><span class="label label-info">變更狀態為待受理</span></a>或<a href="/mod/applications/?view=declined"><span class="label label-danger">變更為拒絕</span></a>.',
	'viewing_declined_applications' => '正在查看<span class="label label-danger">被拒絕的</span>申請表單。<a href="/mod/applications/"><span class="label label-info">變更狀態為待受理</span></a>或<a href="/mod/applications/?view=accepted"><span class="label label-success">變更為接受</span></a>.',
	'time_applied' => '申請時間',
	'no_applications' => '在此分類中沒有任何申請表單',
	'viewing_app_from' => 'Viewing application from {x}', // Don't replace "{x}"
	'open' => '待受理',
	'accepted' => '已接受',
	'declined' => '已拒絕',
	'accept' => '接受',
	'decline' => '拒絕',
	'new_app_submitted_alert' => '{x} 提交了新的申請表單' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => '新聞',
	'social' => '社群',
	'join' => '加入',
	
	// General terms
	'submit' => '提交',
	'close' => '關閉',
	'cookie_message' => '<strong>本站使用 Cookie 以提升您的體驗。</strong><p>若您繼續瀏覽或使用本站，我們將視您同意我們使用它們。</p>',
	'theme_not_exist' => '找不到你選擇的佈景主題。',
	'confirm' => '確認',
	'cancel' => '取消',
	'guest' => '訪客',
	'guests' => '訪客',
	'back' => '返回',
	'search' => '搜尋',
	'help' => '幫助',
	'success' => '成功',
	'error' => '錯誤',
	'view' => '查看',
	'info' => '資訊',
	'next' => 'Next',
	
	// Play page
	'connect_with' => '伺服器的 IP 位址：{x}', // Don't replace {x}
	'online' => '在線上',
	'offline' => '離線',
	'status' => '狀態：',
	'players_online' => '線上玩家數量：',
	'queried_in' => '查詢所用時間：',
	'server_status' => '伺服器狀態',
	'no_players_online' => '目前沒有玩家在線上。',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => '目前有 {x} 位玩家遊玩當中。', // Don't replace {x}
	
	// Other
	'page_loaded_in' => '頁面載入時間：{x} 秒', // Don't replace {x}; 's' stands for 'seconds'
	'none' => '無',
	'404' => '抱歉，我們找不到該頁面。'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => '討論區',
	'discussion' => '討論',
	'stats' => '統計資料',
	'last_reply' => '最新的回覆',
	'ago' => '之前',
	'by' => '作者：',
	'in' => '在',
	'views' => '次瀏覽',
	'posts' => '篇文章',
	'topics' => '個主題',
	'topic' => '主題',
	'statistics' => '統計',
	'overview' => '概覽',
	'latest_discussions' => '最新的討論串',
	'latest_posts' => '最新的文章',
	'users_registered' => '已註冊的使用者：',
	'latest_member' => '最新註冊的使用者：',
	'forum' => '討論區',
	'last_post' => '最新的文章',
	'no_topics' => '這裡還沒有任何主題',
	'new_topic' => '新的主題',
	'subforums' => '子討論區：',
	
	// View topic view
	'home' => '主頁',
	'topic_locked' => '主題已上鎖',
	'new_reply' => '新的回覆',
	'mod_actions' => '管理動作',
	'lock_thread' => '鎖定討論串',
	'unlock_thread' => '解鎖討論串',
	'merge_thread' => '合併討論串',
	'delete_thread' => '刪除討論串',
	'confirm_thread_deletion' => '你確定要刪除這個討論串嗎？',
	'move_thread' => '移動討論串',
	'sticky_thread' => '置頂討論串',
	'report_post' => '舉報文章',
	'quote_post' => '引用文章',
	'delete_post' => '刪除文章',
	'edit_post' => '編輯文章',
	'reputation' => '聲望',
	'confirm_post_deletion' => '你確定要刪除此篇文章嗎？',
	'give_reputation' => '給予聲望',
	'remove_reputation' => '移除聲望',
	'post_reputation' => 'Post Reputation',
	'no_reputation' => '這篇文章還沒獲得任何聲望',
	're' => 'RE:',
	
	// Create post view
	'create_post' => '建立文章',
	'post_submitted' => '文章已提交',
	'creating_post_in' => 'Creating post in: ',
	'topic_locked_permission_post' => '此主題已被上鎖，不過你還是有發文的權限。',
	
	// Edit post view
	'editing_post' => '正在編輯文章',
	
	// Sticky threads
	'thread_is_' => '討論串',
	'now_sticky' => '置頂完成',
	'no_longer_sticky' => '取消置頂完成',
	
	// Create topic
	'topic_created' => '主題建立完畢。',
	'creating_topic_in_' => '正在建立主題至 ',
	'thread_title' => '討論串標題',
	'confirm_cancellation' => '你確定嗎？',
	'label' => '標籤',
	
	// Reports
	'report_submitted' => '舉報完成。',
	'view_post_content' => '查看文章內容',
	'report_reason' => '舉報原因',
	
	// Move thread
	'move_to' => '移動至：',
	
	// Merge threads
	'merge_instructions' => '兩個要合併的討論串<strong>必須</strong>在同個討論區下。Move a thread if necessary.',
	'merge_with' => '與 ... 合併：',
	
	// Other
	'forum_error' => '對不起，我們找不到相關的討論區或主題。',
	'are_you_logged_in' => '你登入了嗎？',
	'online_users' => '本站線上使用者',
	'no_users_online' => '沒有使用者在線上',
	
	// Search
	'search_error' => '請輸入一個 1 ~ 32 個字元長的搜尋關鍵字',
	'no_search_results' => '我們搜尋不到任何資料。',
	
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
