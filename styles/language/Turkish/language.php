<?php 
/*
 *	Made by KeremWho
 *  https://keremwho.xyz
 *
 *  License: No one. I do it for you use.
 */

/*
 *  Türkçe Language
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'infractions' => 'Cezanlandırmalar',
	'invalid_token' => 'Geçersiz token. Lütfen tekrar deneyin.',
	'invalid_action' => 'Geçersiz Eylem',
	'successfully_updated' => 'Başarıyla güncellendi.',
	'settings' => 'Ayarlar',
	'confirm_action' => 'Onayla',
	'edit' => 'Düzenle',
	'actions' => 'Eylemler',
	'task_successful' => 'İşlem başarıyla çalıştırıldı.',
	
	// Admin login
	're-authenticate' => 'Lütfen, tekrar giriş yapın.',
	
	// Admin sidebar
	'index' => 'Genel Bakış',
	'announcements' => 'Uyarılar',
	'core' => 'Genel Ayarlar',
	'custom_pages' => 'Sayfalar',
	'general' => 'Genel',
	'forums' => 'Forum Ayarları',
	'users_and_groups' => 'Kullanıcılar ve Gruplar',
	'minecraft' => 'Minecraft',
	'style' => 'Tasarım',
	'addons' => 'Eklentiler',
	'update' => 'Güncelleme',
	'misc' => 'Diğer',
	'help' => 'Yardım',
	
	// Admin index page
	'statistics' => 'İstatistikler',
	'registrations_per_day' => 'Kayıt/Gün (son 7 gün)',
	
	// Admin announcements page
	'current_announcements' => 'Aktif Uyarılar',
	'create_announcement' => 'Yeni Uyarı',
	'announcement_content' => 'İçerik',
	'announcement_location' => 'Konum',
	'announcement_can_close' => 'Uyarı kapatılabilsin mi?',
	'announcement_permissions' => 'Yetkiler',
	'no_announcements' => 'Hiç uyarı oluşturulmamış.',
	'confirm_cancel_announcement' => 'Uyarıyı iptal etmek istediğinize emin misiniz?',
	'announcement_location_help' => 'Birden fazla sayfa seçmek için CTRL ile tıklayın.',
	'select_all' => 'Tümünü Şeç',
	'deselect_all' => 'Şeçimi Temizle',
	'announcement_created' => 'Uyarı başarıyla oluşturuldu.',
	'please_input_announcement_content' => 'Lütfen, içeriği girin ve türü şeçin.',
	'confirm_delete_announcement' => 'Uyarıyı silmek istediğinize emin misiniz?',
	'announcement_actions' => 'Eylemler',
	'announcement_deleted' => 'Uyarı başarıyla silindi.',
	'announcement_type' => 'Uyarı Tipi',
	'can_view_announcement' => 'Uyarıyı Görebilsin mi?',
	
	// Admin core page
	'general_settings' => 'Genel Ayarlar',
	'modules' => 'Modüller',
	'module_not_exist' => 'Modül bulunamadı!',
	'module_enabled' => 'Modül aktif edildi.',
	'module_disabled' => 'Modül devre dışı bırakıldı.',
	'site_name' => 'Site Adı',
	'language' => 'Dil',
	'voice_server_not_writable' => 'core/voice_server.php dosyası yazılabilir değil. Lütfen dosya izinlerini kontrol edin.',
	'email' => 'E-Posta',
	'incoming_email' => 'Gelen E-Posta Adresi',
	'outgoing_email' => 'Giden E-Posta Adresi',
	'outgoing_email_help' => 'Sadece PHP Mail aktif iken gereklidir.',
	'use_php_mail' => 'PHP mail() kullanılsın mı?',
	'use_php_mail_help' => 'Önerilen: Aktif. Eğer siteniz e-posta gönderemiyor ise core/email.php dosyasını düzenleyin.',
	'use_gmail' => 'G-Mail kullanılsın mı?',
	'use_gmail_help' => 'Sadece PHP Mail devre dışı ise çalışıyor. Düzenlemelisin: core/email.php.',
	'enable_mail_verification' => 'E-Posta Doğrulamasının Aktif Olmasını ister misiniz?',
	'enable_email_verification_help' => 'Yeni kayıt olan kişiye doğrulama e-postası gidecektir.',
	'explain_email_settings' => 'Gerekli bilgiyi <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">buraya tıklayarak</a> bulabilirsin.',
	'email_config_not_writable' => '<strong>core/email.php</strong> dosyası yazılabilir değil. Lütfen, dosya izinlerini kontrol edin.',
	'pages' => 'Sayfalar',
	'enable_or_disable_pages' => 'Sayfaları buradan aktif edebilir ya da devre dışı bırakabilirsiniz.',
	'enable' => 'Aktif',
	'disable' => 'Devre Dışı',
	'maintenance_mode' => 'Forum Bakım Modu',
	'forum_in_maintenance' => 'Forum, bakımdadır.',
	'unable_to_update_settings' => 'Ayarlar güncellenemedi. Boş yer olmamalı.',
	'editing_google_analytics_module' => 'Google Analytics modülünü düzenliyorsunuz.',
	'tracking_code' => 'Takip Kodu',
	'tracking_code_help' => '',
	'google_analytics_help' => '<a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">Buraya</a> tıklayarak bilgi alabilirsiniz.',
	'social_media_links' => 'Sosyal Medya Adresleri',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Twitter için karanlık tema kullanılsın mı?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Kayıt',
	'registration_warning' => 'Bu modülü devre dışı bırakırsanız yeni kişiler kayıt olmayacaklardır.',
	'google_recaptcha' => 'Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Kayıt Olma Şartları',
	'voice_server_module' => 'Sesli Sunucu',
	'only_works_with_teamspeak' => 'Şu anda sadece TeamSpeak ve Discord ile çalışmaktadır.',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'ServerQuery kullanıcısının bilgilerini girin.',
	'ip_without_port' => 'IP (Port hariç)',
	'voice_server_port' => 'Port (genellikle 10011)',
	'virtual_port' => 'Virtual Port (genellikle 9987)',
	'permissions' => 'İzinler:',
	'view_applications' => 'Başvuruları Görebilir',
	'accept_reject_applications' => 'Başvuruları Kabul veya Reddedbilir.',
	'questions' => 'Sorular:',
	'question' => 'Soru',
	'type' => 'Tür',
	'options' => 'Ayarlar',
	'options_help' => '',
	'no_questions' => 'Hiç soru oluşturulmamış.',
	'new_question' => 'Yeni Soru',
	'editing_question' => 'Soru Düzenleniyor',
	'delete_question' => 'Sil',
	'dropdown' => 'Şeçilebilir Kutu',
	'text' => 'Yazı',
	'textarea' => 'Yazma Kutusu',
	'name_required' => 'İsim Gereklidir.',
	'question_required' => 'Soru Gereklidir.',
	'name_minimum' => 'İsim minumum 2 karakter olmalıdır.',
	'question_minimum' => 'Soru minumum 2 karakter olmalıdır.',
	'name_maximum' => 'İsim maksimum 16 karakter olmalıdır.',
	'question_maximum' => 'Soru maksimum 16 karakter olmalıdır.',
	'question_deleted' => 'Soru silindi.',
	'use_followers' => 'Takip Sistemi',
	'use_followers_help' => 'Eğer devre dışı bırakılırsa, arkadaşlık sistemi aktif olacaktır.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Tıkla ve Düzenle',
	'page' => 'Sayfa:',
	'url' => 'Adres:',
	'page_url' => 'Sayfa Adresi',
	'page_url_example' => '("/" ile başlayıp bitmeli, örneği /yardim/)',
	'page_title' => 'Sayfa Başlığı',
	'page_content' => 'Sayfa İçeriği',
	'new_page' => 'Yeni Sayfa',
	'page_successfully_created' => 'Sayfa başarıyla oluşturuldu.',
	'page_successfully_edited' => 'Sayfa düzenlendi.',
	'unable_to_create_page' => 'Sayfa oluşturulamıyor.',
	'unable_to_edit_page' => 'Sayfa düzenlenemiyor.',
	'create_page_error' => 'Girdiğiniz adres 1 ile 20 karakter arası olmalıdır, sayfa başlığı ise 1 ile 30 karakter arası olmalıdır, ve sayfa içeriği 5 ile 20480 karakter arası olmalıdır.',
	'delete_page' => 'Sil',
	'confirm_delete_page' => 'Sayfayı silmek istediğinize emin misiniz?',
	'page_deleted_successfully' => 'Sayfa başarıyla silindi.',
	'page_link_location' => 'Sayfanın Görüntüleneceği Yer:',
	'page_link_navbar' => 'Menü',
	'page_link_more' => 'Menü\'de bulunan "Diğer" Menüsü',
	'page_link_footer' => 'Sayfa Sonu',
	'page_link_none' => 'Bir yerde bulunmasın',
	'page_permissions' => 'Sayfa İzinleri',
	'can_view_page' => 'Sayfayı Görebilir:',
	'redirect_page' => 'Yönlendirlebilir Sayfa?',
	'redirect_link' => 'Yönlenecek Adres',
	'page_icon' => 'Sayfa Ikonu',
	
	// Admin forum page
	'labels' => 'Konu Etiketleri',
	'new_label' => 'Yeni',
	'no_labels_defined' => 'Etiket Bulunamadı.',
	'label_name' => 'Etiket Adı',
	'label_type' => 'Etiket Türü',
	'label_forums' => 'Geçerli Forumlar',
	'label_creation_error' => 'Etiket oluşturulamadı. Lütfen, 32\'den fazla olmadığına emin olun.',
	'confirm_label_deletion' => 'Silmek istediğinize emin misiniz?',
	'editing_label' => 'Etiket Düzenleniyor',
	'label_creation_success' => 'Etiket Başarıyla Oluşturuldu.',
	'label_edit_success' => 'Etiket Düzenlendi.',
	'label_default' => 'Varsayılan',
	'label_primary' => 'Birincil',
	'label_success' => 'Başarılı',
	'label_info' => 'Bilgi',
	'label_warning' => 'Uyarı',
	'label_danger' => 'Tehlike',
	'new_forum' => 'Yeni Forum',
	'forum_layout' => 'Forum Türü',
	'table_view' => 'Tablo Görünümü',
	'latest_discussions_view' => 'Üst Görünüm',
	'create_forum' => 'Yeni Forum',
	'forum_name' => 'Forum Adı',
	'forum_description' => 'Forum Açıklaması',
	'delete_forum' => 'Sil',
	'move_topics_and_posts_to' => 'Konuları ve mesajları şuraya taşı:',
	'delete_topics_and_posts' => 'Konuları ve mesajları sil.',
	'parent_forum' => 'Üst Kategori',
	'has_no_parent' => 'Üst yok.',
	'forum_permissions' => 'Yetkiler',
	'can_view_forum' => 'Forumu Görebilir',
	'can_create_topic' => 'Konu Açabilir',
	'can_post_reply' => 'Konuyu Yanıtlayabilir',
	'display_threads_as_news' => 'Konular anasayfada gösterilsin mi?',
	'input_forum_title' => 'Bir başlık girin.',
	'input_forum_description' => 'Bir açıklaması girin. (HTML kullanabilirsin.).',
	'forum_name_minimum' => 'İsim minimum 2 karakter olmalı.',
	'forum_description_minimum' => 'Açıklama minimum 2 karakter olmalı.',
	'forum_name_maximum' => 'İsim maksimum 150 karakter olmalı.',
	'forum_description_maximum' => 'Açıklama minimum 255 karakter olmalı.',
	'forum_type_forum' => 'Tartışma Forumu',
	'forum_type_category' => 'Kategori',
	
	// Admin Users and Groups page
	'users' => 'Kullanıcılar',
	'new_user' => 'Yeni Üye',
	'created' => 'Kayıt Tarihi',
	'user_deleted' => 'Kullanıcı silindi.',
	'validate_user' => 'Hesabı Doğrular',
	'update_uuid' => 'UUID güncelle',
	'unable_to_update_uuid' => 'UUID güncellenemedi.',
	'update_mc_name' => 'Minecraft Kullanıcı Adını Düzenle',
	'reset_password' => 'Şifre Sıfırla',
	'punish_user' => 'Yasakla',
	'delete_user' => 'Sil',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Adresi',
	'ip' => 'IP:',
	'other_actions' => 'Diğer Eylemler:',
	'disable_avatar' => 'Avatar\'ı kapat.',
	'enable_avatar' => 'Avatar\'ı açık.',
	'confirm_user_deletion' => 'Gerçekten {x} kullanıcısı silmek istiyor musunuz?', // Don't replace "{x}"
	'groups' => 'Gruplar',
	'group' => 'Grup',
	'group2' => 'Alt Grup',
	'new_group' => 'Yeni Grup',
	'id' => 'ID',
	'name' => 'İsim',
	'create_group' => 'Yeni Grup',
	'group_name' => 'Grup Adı',
	'group_html' => 'Grup HTML',
	'group_html_lg' => 'Grup HTML Uzun',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>Bu ID Buycraft, MinecraftMarket veya MCStockdan alınmalıdır.</p><p>İsteğe bağlıdır.</p>',
	'donor_group_instructions' => 	'<p>Paralı Üyelikler<strong>azdan çoğa doğru sıralanmalıdır.</strong>.</p>',
	'delete_group' => 'Sil',
	'confirm_group_deletion' => 'Gerçekten {x} grubunu silmek istediğinize emin misiniz?', // Don't replace "{x}"
	'group_staff' => 'Yetkili bir grup mu?',
	'group_modcp' => 'Moderatör panelini görebilsin mi?',
	'group_admincp' => 'Yönetici Panelini görebilsin mi?',
	'group_name_required' => 'Grup adı girmelisiniz.',
	'group_name_minimum' => 'İsim, Minimum 2 olmalıdır.',
	'group_name_maximum' => 'İsim, Maksimum 20 olmalıdır.',
	'html_maximum' => 'HTML, Maksimum 1024 olmalıdır.',
	'select_user_group' => 'Kullanıcı bir grubun içinde olmalıdır.',
	'uuid_max_32' => 'UUID maksimum 32 karakter olmalıdır.',
	'cant_delete_root_user' => 'Root yetkisini silemezsiniz.',
	'cant_modify_root_user' => 'Root kullanıcısının yetkisini alamazsınız.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft Ayarları',
	'use_plugin' => 'Sunucunuz için API',
	'force_avatars' => 'Zorunlu olarak Minecraft avatarı mı olmalı?',
	'uuid_linking' => 'UUID Eşleştirmesini Aktif Et',
	'use_plugin_help' => 'Enabling the API, along with the server plugin, allows for rank synchronisation and also ingame registration and report submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Eklenti Ayarları',
	'confirm_api_regen' => 'Yeni API anahtarı oluşturmak istediğinize emin misiniz?',
	'servers' => 'Sunucular',
	'new_server' => 'Yeni Sunucu',
	'confirm_server_deletion' => 'Sunucuyu silmek istediğinize istediğinze emin misiniz?',
	'main_server' => 'Ana Sunucu',
	'main_server_help' => 'Kişiler direkt olarak buraya bağlanacaktır. Genellikle Bungee olmaktadır.',
	'choose_a_main_server' => 'Ana sunucuyu şeçin..',
	'external_query' => 'Farklı bir query kullanılsın mı?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => '{x} adlı sunucu düzenleniyor.', // Don't replace "{x}"
	'server_ip_with_port' => 'Sunucu Adresi (ip:port) (sayılı veya domain)',
	'server_ip_with_port_help' => 'Bu IP adresi kullanıcılara gösterilecektir.',
	'server_ip_numeric' => 'Server Adresi (ip:port) (sadece sayılı)',
	'server_ip_numeric_help' => 'Bu adres sadece sistemler için kullanılacaktır. 3. kişilere gösterilmeyecektir.',
	'show_on_play_page' => '"Oyna" sayfasında gösterilsin mi?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Sunucu Adı',
	'invalid_server_id' => 'Geçersiz ID',
	'show_players' => 'Oyuncu Listesi "Oyna" sayfasında gösterilsin mi?',
	'server_edited' => 'Başarıyla düzenlendi.',
	'server_created' => 'Başarıyla oluşturuldu.',
	'query_errors' => 'Query Hataları',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'Hata yok.',
	'date' => 'Tarih:',
	'port' => 'Port:',
	'viewing_error' => 'Hata Gösteriliyor',
	'confirm_error_deletion' => 'Hatayı silmek istediğinize emin misiniz?',
	'display_server_status' => 'Sunucu durumu modülü gösterilsin mi?',
	'server_name_required' => 'Sunucu adını girmelisiniz.',
	'server_ip_required' => 'Sunucu adresini girmelisiniz.',
	'server_name_minimum' => 'Sunucu adı minimum 2 karakter olmalıdır.',
	'server_ip_minimum' => 'Sunucu adresi minimum 2 karakter olmalıdır.',
	'server_name_maximum' => 'Sunucu adı maksimum 20 karakter olmalıdır.',
	'server_ip_maximum' => 'Sunucu adresi maksimum 64 karakter olmalıdır.',
	'purge_errors' => 'Hataları Temizle',
	'confirm_purge_errors' => 'Emin misin?',
	'avatar_type' => 'Avatar Türü',
	'custom_usernames' => 'Minecraft kullanıcı adı zorunlu mu olsun?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'mcassoc kullanılsın mı?',
	'use_mcassoc_help' => 'Bu bir doğrulama sistemidir.',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Invalid mcassoc key.',
	'mcassoc_instance' => 'mcassoc Instance',
	'mcassoc_instance_help' => 'İnstance Keyi <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">buradan</a> oluştabilirsin.',
	'mcassoc_key_help' => 'mcassoc keyi <a href="https://mcassoc.lukegb.com/" target="_blank">buradan</a> alabilirsin.',
	'enable_name_history' => 'Kullanıcı Adı Geçmişini Aç?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Temalar',
	'templates' => 'Şablonlar',
	'installed_themes' => 'Kurulu Temalar',
	'installed_templates' => 'Kurulu Şablonlar',
	'installed_addons' => 'Kurulu Eklentiler',
	'install_theme' => 'Yeni Tema',
	'install_template' => 'Yeni Şablon',
	'install_addon' => 'Yeni Eklenti',
	'install_a_theme' => 'Bir tema kurun.',
	'install_a_template' => 'Bir şablon kurun.',
	'install_an_addon' => 'Bir eklenti kurun.',
	'active' => 'Aktif',
	'activate' => 'Aktif et',
	'deactivate' => 'Devre dışı bırak',
	'theme_install_instructions' => 'Lütfen, temalarınızı <strong>styles/themes</strong> klasörüne atın. Ve sonra, "tara" tuşuna tıklayın.',
	'template_install_instructions' => 'Lütfen, şablonlarınızı <strong>styles/templates</strong> klasörüne atın. Ve sonra, "tara" tuşuna tıklayın.',
	'addon_install_instructions' => 'Lütfen, eklentilerinizi <strong>addons</strong> klasörüne atın. Ve sonra, "tara" tuşuna tıklayın.',
	'addon_install_warning' => 'Yükleme işleminden önce yedek alınız.',
	'scan' => 'Tara',
	'theme_not_exist' => 'Tema bulunamadı.',
	'template_not_exist' => 'Şablon bulunamadı.',
	'addon_not_exist' => 'Eklenti bulunamadı.',
	'style_scan_complete' => 'Tamamlandı, yeni temalar kuruldu.',
	'addon_scan_complete' => 'Tamamlandı, yeni eklentiler kuruldu.',
	'theme_enabled' => 'Tema aktif edildi.',
	'template_enabled' => 'Şablon aktif edildi.',
	'addon_enabled' => 'Eklenti aktif edildi.',
	'theme_deleted' => 'Tema silindi.',
	'template_deleted' => 'Şablon silindi.',
	'addon_disabled' => 'Eklenti devre dışı bırakıldı.',
	'inverse_navbar' => 'Ters Menü',
	'confirm_theme_deletion' => '<strong>{x}</strong> adlı temayı silmek istediğinize emin misiniz?', // Don't replace {x}
	'confirm_template_deletion' => '<strong>{x}</strong> adlı şablonu silmek istediğinize emin misiniz?', // Don't replace {x}
	'unable_to_enable_addon' => 'Aktif edilemedi.',
	
	// Admin Misc page
	'other_settings' => 'Diğer Ayarlar',
	'enable_error_reporting' => 'Hatalar gösterilsin mi?',
	'error_reporting_description' => 'Sorunları çözmek için kullanılır, normal şartlarda kapalı kalmalıdır.',
	'display_page_load_time' => 'Sayfa yüklenme süresi gösterilsin mi?',
	'page_load_time_description' => 'Footerda belitilecektir.',
	'reset_website' => 'Siteyi Sıfırla',
	'reset_website_info' => 'Ayarlarınızı sıfırlar.',
	'confirm_reset_website' => 'Sıfırlamak istediğinize emin misiniz?',
	
	// Admin Update page
	'installation_up_to_date' => 'Siteniz güncel.',
	'update_check_error' => 'Güncellemeler kontrol edilemedi. Daha sonra tekrar deneyin.',
	'new_update_available' => 'Bir güncelleme mevcut.',
	'your_version' => 'Sürümünüz:',
	'new_version' => 'Yeni Sürüm:',
	'download' => 'İndir',
	'update_warning' => 'Güncellemeden önce indirdiğiniz dosyaları siteye yükleyin.'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Ana Sayfa',
	'play' => 'Oyna',
	'forum' => 'Forum',
	'more' => 'Diğer',
	'staff_apps' => 'Yetkili Alımı',
	'view_messages' => 'Mesajları Gör',
	'view_alerts' => 'Bildirimleri Gör',
	
	// Icons - will display before the text
	'home_icon' => '<i class="fa fa-home"></i> ',
	'play_icon' => '<i class="fa fa-gamepad"></i> ',
	'forum_icon' => '<i class="fa fa-bars"></i> ',
	'staff_apps_icon' => '<i class="fas fa-user-plus"></i> '
);

/*
 * User Related
 */
$user_language = array(
	// Registration
	'create_an_account' => 'Hesap Oluştur',
	'authme_password' => 'AuthMe Şifresi',
	'username' => 'Kullanıcı Adı',
	'minecraft_username' => 'Minecraft Kullanıcı Adı',
	'email' => 'E-Posta Adresi',
	'user_title' => 'Başlık',
	'email_address' => 'E-Posta Adresi',
	'date_of_birth' => 'Doğum Tarihi',
	'location' => 'Konum',
	'password' => 'Şifre',
	'confirm_password' => 'Şifreyi Tekrarla',
	'i_agree' => 'Kabul Ediyorum',
	'agree_t_and_c' => '<strong class="label label-primary">Kayıt Ol</strong>a basarak, <a href="#" data-toggle="modal" data-target="#t_and_c_m">Şartlar ve Koşulları</a> kabul ettiğinizi beyan edersiniz.',
	'register' => 'Kayıt Ol',
	'sign_in' => 'Giriş Yap',
	'sign_out' => 'Çıkış Yap',
	'terms_and_conditions' => 'Şartlar ve Koşullar',
	'successful_signin' => 'Başarıyla giriş yaptınız.',
	'incorrect_details' => 'Bilgiler yanlış.',
	'remember_me' => 'Beni Hatırla',
	'forgot_password' => 'Şifremi Unuttum',
	'must_input_username' => 'Kullanıcı adını girmelisiniz.',
	'must_input_password' => 'Şifre girmelisiniz.',
	'inactive_account' => 'Hesabınız şu anda deaktif. Şifrenizi sıfırlamak mı istediniz?',
	'account_banned' => 'Hesabınız yasaklı.',
	'successfully_logged_out' => 'Başarıyla çıkış yaptınız.',
	'signature' => 'İmza',
	'registration_check_email' => 'E-Posta adresinizi kontrol edin doğrulama e-posta gönderildi. Doğrulama yapmadan giriş yapamazsınız.',
	'unknown_login_error' => 'Giriş yaparken bir hata oluştu, lütfen daha sonra deneyin.',
	'validation_complete' => 'Kayıt olduğunuz için teşekkürler.',
	'validation_error' => 'Bir hata oluştu, Lütfen tekrar deneyin.',
	'registration_error' => 'Her yeri doldurduğunuza emin olun.',
	'username_required' => 'Lütfen Kullanıcı Adı girin.',
	'password_required' => 'Lütfen Şifrenizi Girin.',
	'email_required' => 'Lütfen E-Posta Adresinizi Girin',
	'mcname_required' => 'Lütfen Minecraft Kullanıcı Adı girin.',
	'accept_terms' => 'Şartları ve Koşulları kabul etmelisiniz.',
	'invalid_recaptcha' => 'Doğrulamayı yapın.',
	'username_minimum_3' => 'Kullanıcı adı minimum 3 karekter olmalıdır.',
	'username_maximum_20' => 'Kullanıcı adı maksimum 20 karekter olmalıdır.',
	'mcname_minimum_3' => 'MC Kullanıcı adı minimum 3 karekter olmalıdır.',
	'mcname_maximum_20' => 'MC Kullanıcı adı maksimum 20 karekter olmalıdır..',
	'password_minimum_6' => 'Şifre minimum 6 karekter olmalıdır.',
	'password_maximum_30' => 'Şifre minimum 6 karekter olmalıdır.',
	'passwords_dont_match' => 'Şifreleriniz eşleşmiyor.',
	'username_mcname_email_exists' => 'Kullanıcı adı veya E-Posta adresi zaten kayıtlı.',
	'invalid_mcname' => 'Minecraft kullanıcı adı geçerli değil.',
	'mcname_lookup_error' => 'Bir hata oluştu. Yöneticiye ulaşın.',
	'signature_maximum_900' => 'İmzanız maksimum 900 karekter olmalıdır.',
	'invalid_date_of_birth' => 'Geçersiz Doğum Tarihi.',
	'location_required' => 'Lütfen konumunuzu girin.',
	'location_minimum_2' => 'Konum minimum 2 karekter olmalıdır.',
	'location_maximum_128' => 'Konum maksimum 128 karekter olmalıdır.',
	'verify_account' => 'Hesabınızı Doğrulayın',
	'verify_account_help' => 'Hesabın size ait olduğunu doğrulamamız için bize yardımcı olmalısınız.',
	'verification_failed' => 'Doğrulama başarısız tekrar deneyin.',
	'verification_success' => 'Başarıyla doğrulandı. Giriş yapabilirsiniz.',
	'complete_signup' => 'Kayıt Ol',
	'registration_disabled' => 'Kayıtlar şu anda devre dışı.',
	
	// UserCP
	'user_cp' => 'Kullanıcı Ayarları',
	'no_file_chosen' => 'Dosya Şeçilmedi',
	'private_messages' => 'Özel Mesajlar',
	'profile_settings' => 'Profil Ayarları',
	'your_profile' => 'Profiliniz',
	'topics' => 'Konular',
	'posts' => 'Yorumlar',
	'reputation' => 'Puan',
	'friends' => 'Arkadaşlar',
	'alerts' => 'Bildirimler',
	
	// Messaging
	'new_message' => 'Yeni Mesaj',
	'no_messages' => 'Mesaj Bululanamadı.',
	'and_x_more' => 've {x} diğer kişi', // Don't replace "{x}"
	'system' => 'Sistem',
	'message_title' => 'Mesaj Başlığı',
	'message' => 'Mesaj',
	'to' => 'Gönderilecek Kişi:',
	'separate_users_with_comma' => 'Birden fazla kişi için virgül ile ayrın. (",")',
	'viewing_message' => 'Mesajı Gör',
	'delete_message' => 'Sil',
	'confirm_message_deletion' => 'Silmek istediğine emin misin?',
	
	// Profile settings
	'display_name' => 'Görüntülenecek İsim',
	'upload_an_avatar' => 'Bir avatar yükleyin (.jpg, .png veya .gif):',
	'use_gravatar' => 'Gravatar Kullan?',
	'change_password' => 'Şifreni Değiştir',
	'current_password' => 'Mevcut Şifre',
	'new_password' => 'Yeni Şifre',
	'repeat_new_password' => 'Şifreyi Tekrarla',
	'password_changed_successfully' => 'Şifre başarıyla değiştirildi.',
	'incorrect_password' => 'Mevcut şifre yanlış.',
	'update_minecraft_name_help' => 'Kulllanıcı adınızı 30 günde bir düzenleyebilirsin.',
	'unable_to_update_mcname' => 'Kullanıcı adı güncellenemedi.',
	'display_age_on_profile' => 'Profilde yaş gösterilsin mi?',
	'two_factor_authentication' => 'İki Aşamalı Doğrulama',
	'enable_tfa' => 'İki Aşamalı Doğrulamayı Aktif Et',
	'tfa_type' => 'Doğrulama Tipi:',
	'authenticator_app' => 'Doğrulama Uygulaması',
	'tfa_scan_code' => 'Aşağıdaki kodu programa okutun:',
	'tfa_code' => 'Eğer okumada sorun çıkıyor ise bu kodu kullanabilirsiniz:',
	'tfa_enter_code' => 'Görüntülenen kodu uygulamaya girin:',
	'invalid_tfa' => 'Geçersiz tekrar deneyin.',
	'tfa_successful' => 'Başarıyla atkif edildi.',
	'confirm_tfa_disable' => 'Devre dışı bırakmak istediğinize emin misiniz?',
	'tfa_disabled' => 'Başarıyla devre dışı bırakıldı.',
	'tfa_enter_email_code' => 'Kodu sana e-posta ile yolladık. Lütfen, kodu gir.',
	'tfa_email_contents' => 'A login attempt has been made to your account. If this was you, please input the following two factor authentication code when asked to do so. If this was not you, you can ignore this email, however a password reset is advised. The code is only valid for 10 minutes.',
	
	// Alerts
	'viewing_unread_alerts' => 'Okunmamış Bildirimleri Görüyorsunuz. <a href="/user/alerts/?view=read"><span class="label label-success">Okunmuşları Göster</span></a>.',
	'viewing_read_alerts' => 'Okunmuş Bildirimleri Görüyorsunuz. <a href="/user/alerts/"><span class="label label-warning">Okunmamışları Göster</span></a>.',
	'no_unread_alerts' => 'Hiç okunmamış bildiriminiz bulunmuyor.',
	'no_alerts' => 'Bildirim yok.',
	'no_read_alerts' => 'Hiç okunmuş bildiriminiz bulunmuyor.',
	'view' => 'Gör',
	'alert' => 'Bildirim',
	'when' => 'Ne Zaman',
	'delete' => 'Sil',
	'tag' => 'Etiket',
	'tagged_in_post' => 'Bir konuda etiketlendin.',
	'report' => 'Rapor Et',
	'deleted_alert' => 'Bildirim silindi.',
	
	// Warnings
	'you_have_received_a_warning' => '{x} konusuyla alakalı {y} tarihinde bir uyarı aldın.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Onayla',
	
	// Forgot password
	'password_reset' => 'Şifremi Unuttum',
	'email_body' => 'Bu e-posta\'yı şifre sıfırlama için alıyorsun. bu adresten şifreni sıfırlayabilirsin.:', // Body for the password reset email
	'email_body_2' => 'Eğer sen istemediysen, dikkate almana gerek yok.',
	'password_email_set' => 'Başarılı. Gerekli bilgi için e-posta adresinizi kontrol edin.',
	'username_not_found' => 'Kullanıcı bulunamadı.',
	'change_password' => 'Şifreni Değiştir',
	'your_password_has_been_changed' => 'Şifreniz başarıyla değiştirildi.',
	
	// Profile page
	'profile' => 'Profil',
	'player' => 'Oyuncu',
	'offline' => 'Çevrimdışı',
	'online' => 'Çevrimiçi',
	'pf_registered' => 'Kayıt Tarihi:',
	'pf_posts' => 'Mesajlar:',
	'pf_reputation' => 'Puan:',
	'user_hasnt_registered' => 'Bu kişi sitenize kayıt olmadı.',
	'user_no_friends' => 'Hiç arkadaşı yok.',
	'send_message' => 'Mesaj Gönder',
	'remove_friend' => 'Arkadaşlıktan Çıkart',
	'add_friend' => 'Arkadaş Ekle',
	'last_online' => 'Son Çevrimiçi:',
	'find_a_user' => 'Oyuncu Bul',
	'user_not_following' => 'Kimseyi takip etmiyor.',
	'user_no_followers' => 'Kimse takip etmiyor.',
	'following' => 'Takip Ettikleri',
	'followers' => 'Takipçileri',
	'display_location' => '{x} da yaşıyor.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x} yaşında ve {y} yılında doğmuş.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => '{x}\'nin profiline bir şey yaz...', // Don't replace {x}
	'write_on_own_profile' => 'Kendi profiline bir şey yaz...',
	'profile_posts' => 'Profil Gönderileri',
	'no_profile_posts' => 'Henüz hiç profil gönderisi yok.',
	'invalid_wall_post' => 'Hata oluştu. Lütfen mesajınızın 2 ile 2048 karakter arasında olduğuna dikkat edin.',
	'about' => 'Hakkında',
	'reply' => 'Yanıt',
	'x_likes' => '{x} beğeni', // Don't replace {x}
	'likes' => 'Beğeniler',
	'no_likes' => 'Hiç kimse beğenmemiş.',
	'post_liked' => 'Beğendin.',
	'post_unliked' => 'Beğenmedin.',
	'no_posts' => 'Mesajı yok.',
	'last_5_posts' => 'Son 5 mesajı',
	'follow' => 'Takip Et',
	'unfollow' => 'Takibi bırak',
	'name_history' => 'İsim Geçmişi',
	'changed_name_to' => 'Adını {y} tarihinde şuna değiştirdi: {x}', // Don't replace {x} or {y}
	'original_name' => 'Orjinal Adı:',
	'name_history_error' => 'İsim geçmişi alınamadı.',
	
	// Staff applications
	'staff_application' => 'Yetkili Başvurusu',
	'application_submitted' => 'Başvurunuz gönderildi.',
	'application_already_submitted' => 'Zaten bir başvuru yaptınız. Diğerinin tamamlanmasını bekleyin.',
	'not_logged_in' => 'Bu sayfayı görmek için giriş yapın.',
	'application_accepted' => 'Başvurunuz kabul edildi.',
	'application_rejected' => 'Başvurunuz reddedildi.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'Moderatör Paneli',
	'overview' => 'Genel Bakış',
	'reports' => 'Raporlar',
	'punishments' => 'Cezalandırmalar',
	'staff_applications' => 'Yetkili Başvuruları',
	
	// Punishments
	'ban' => 'Yasakla',
	'unban' => 'Yasaklamayı Kaldır',
	'warn' => 'Uyar',
	'search_for_a_user' => 'Oyuncu ara',
	'user' => 'Kullanıcı:',
	'ip_lookup' => 'IP Adresleri:',
	'registered' => 'Kayıt Tarihi',
	'reason' => 'Sebep:',
	'cant_ban_root_user' => 'Root kullancısını yasaklayamazsınız.',
	'invalid_reason' => 'Lütfen 2 ile 256 karakter arası sebep girin.',
	'punished_successfully' => 'Cezalandırma uygulandı.',
	
	// Reports
	'report_closed' => 'Rapor Kapatıldı.',
	'new_comment' => 'Yeni Yorum',
	'comments' => 'Yorumlar',
	'only_viewed_by_staff' => 'Sadece yetkililer tarafından görüntülenebilir.',
	'reported_by' => 'Rapor eden kişi:',
	'close_issue' => 'Kapat',
	'report' => 'Rapor:',
	'view_reported_content' => 'Rapor edilen içeriği gör.',
	'no_open_reports' => 'Rapor bulunamadı.',
	'user_reported' => 'Rapor edilen kişi:',
	'type' => 'Tür',
	'updated_by' => 'Güncelleyen Kişi:',
	'forum_post' => 'Forum Gönderisi',
	'user_profile' => 'Profil',
	'comment_added' => 'Yorum Eklendi',
	'new_report_submitted_alert' => '{x} tarafından, {y} ile ilgili bir rapor oluşturuldu.', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Oyun İçi',
	
	// Staff applications
	'comment_error' => 'Cevabınız 2 ile 2048 karakter arası olmalıdır.',
	'viewing_open_applications' => '<span class="label label-info">Açık</span> başvuruları görüntülüyorsunuz.',
	'viewing_accepted_applications' => '<span class="label label-success">Kabul Edilen</span> başvuruları görüntülüyorsunuz.',
	'viewing_declined_applications' => '<span class="label label-danger">Reddedilen</span> başvuruları görüntülüyorsunuz.',
	'time_applied' => 'Kabul Zamanı',
	'no_applications' => 'Bu kategoride başvuru bulunmuyor.',
	'viewing_app_from' => '{x} adlı kişinin başvurusu görüntüleniyor.', // Don't replace "{x}"
	'open' => 'Açık',
	'accepted' => 'Kabul Edildi',
	'declined' => 'Reddedildi.',
	'accept' => 'Kabul Et',
	'decline' => 'Reddedildi',
	'new_app_submitted_alert' => '{x} tarafından yeni bir başvuru oluşturuldu.' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Duyurular',
	'social' => 'Sosyal Medya',
	'join' => 'Katıl',
	
	// General terms
	'submit' => 'Gönder',
	'close' => 'Kapat',
	'cookie_message' => '<strong>Bu site daha iyi bir deneyim için çerezleri kullanmaktadır.</strong><p>Siteyi kullanmaya devam ettiğiniz sürece çerezleri kabul etmiş bulunuyorsunuz.</p>',
	'theme_not_exist' => 'Şeçilen tema bulunamadı.',
	'confirm' => 'Onayla',
	'cancel' => 'İptal',
	'guest' => 'Ziyaretçi',
	'guests' => 'Ziyaretçiler',
	'back' => 'Geri',
	'search' => 'Ara',
	'help' => 'Yardım',
	'success' => 'Başarılı',
	'error' => 'Hata',
	'view' => 'Göster',
	'info' => 'Bilgi',
	'next' => 'İleri',
	
	// Play page
	'connect_with' => '{x}', // Don't replace {x}
	'online' => 'Çevrimiçi',
	'offline' => 'Çevrimdışı',
	'status' => 'Durum:',
	'players_online' => 'Aktif Oyuncular:',
	'queried_in' => 'Sorgulanan:',
	'server_status' => 'Sunucu durumu',
	'no_players_online' => '0 kişinin arasında yerini al!',
	'1_player_online' => '1 kişinin arasında yerini al!',
	'x_players_online' => '{x} kişinin arasında yerini al!', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Sayfa {x} saniyede yüklendi.', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Hiç',
	'404' => 'Üzgünüz, her yeri aradık ama aradığınız sayfayı bulamadık.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forum',
	'discussion' => 'Tartışma',
	'stats' => 'İstastikler',
	'last_reply' => 'Son Yanıt',
	'ago' => 'önce',
	'by' => 'tarafından',
	'in' => 'içinde',
	'views' => 'Göster',
	'posts' => 'Mesajlar',
	'topics' => 'Konular',
	'topic' => 'Konu',
	'statistics' => '<i class="fas fa-chart-bar"></i>İstatistikler',
	'overview' => 'Genel Bakış',
	'latest_discussions' => 'Son Tartışma',
	'latest_posts' => 'Son Mesaj',
	'users_registered' => 'Kayıtlı Üye:',
	'latest_member' => 'Son Kayıt Olan Kişi:',
	'forum' => 'Forum',
	'last_post' => 'Son Konu',
	'no_topics' => 'Konu bulunamadı.',
	'new_topic' => 'Yeni Konu',
	'subforums' => 'Alt Forumlar:',
	
	// View topic view
	'home' => 'Ana Sayfa',
	'topic_locked' => 'Konu Kilitlendi',
	'new_reply' => 'Yeni Cevap',
	'mod_actions' => 'Yetkili Hareketleri',
	'lock_thread' => 'Kitle',
	'unlock_thread' => 'Kilidini Aç',
	'merge_thread' => 'Birleştir',
	'delete_thread' => 'Sil',
	'confirm_thread_deletion' => 'Silmek istediğinize emin misiniz?',
	'move_thread' => 'Taşı',
	'sticky_thread' => 'Sabitle',
	'report_post' => 'Rapor Et',
	'quote_post' => 'Alıntıla',
	'delete_post' => 'Sil',
	'edit_post' => 'Düzenle',
	'reputation' => 'Tepkiler',
	'confirm_post_deletion' => 'Silmek istediğinize emin misiniz?',
	'give_reputation' => 'Tepki Ekle',
	'remove_reputation' => 'Tepki Kaldır',
	'post_reputation' => 'Tepkiler',
	'no_reputation' => 'Hiç tepki verilmemiş.',
	're' => 'Cevap:',
	
	// Create post view
	'create_post' => 'Mesaj Gönder',
	'post_submitted' => 'Mesaj Gönderildi.',
	'creating_post_in' => 'Mesaj Gönderiliyor: ',
	'topic_locked_permission_post' => 'Konu normalde kilitli fakat yetkilileriniz yorum yapmanızı sağlıyor.',
	
	// Edit post view
	'editing_post' => 'Düzenleniyor...',
	
	// Sticky threads
	'thread_is_' => 'Konu ',
	'now_sticky' => 'sabitlendi.',
	'no_longer_sticky' => 'artık sabit değil.',
	
	// Create topic
	'topic_created' => 'Konu Oluşturuldu',
	'creating_topic_in_' => 'Forumda konu oluşturuluyor: ',
	'thread_title' => 'Konu Başlığı',
	'confirm_cancellation' => 'Emin misin?',
	'label' => 'Etkiket',
	
	// Reports
	'report_submitted' => 'Rapor Gönderildi.',
	'view_post_content' => 'İçerği Gör',
	'report_reason' => 'Rapor Sebebi',
	
	// Move thread
	'move_to' => 'Şuraya Taşı:',
	
	// Merge threads
	'merge_instructions' => 'Birleştirme <strong>sadece ve sadece</strong> aynı forum içinde yapılmaktadır. Taşımanız gereklidir.',
	'merge_with' => 'Şununla Birleştir:',
	
	// Other
    'forum_error' => 'Üzgünüz, konuyu veya forumu bulamadık.',
	'are_you_logged_in' => 'Giriş yaptığınıza emin misiniz?',
	'online_users' => '<i class="fas fa-users"></i>Aktif Kullanıcılar',
	'no_users_online' => 'Aktif hiç kimse yok.',
	
	// Search
	'search_error' => 'Aranacak şey 1 ile 32 karakter arası olmalıdır.',
	'no_search_results' => 'Hiç sonuç bulunamadı.',
	
	//Share on a social-media.
	'sm-share' => 'Paylaş',
	'sm-share-facebook' => 'Facebook\'da Paylaş',
	'sm-share-twitter' => 'Twitter\'da Paylaş',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Merhaba',
	'message' => 'Kayıt olduğunuz için teşekkürler. Bu link ile kaydınızı tamamlayabilirsiniz:',
	'thanks' => 'Teşekkürler,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 'sn', // Shortened "seconds", eg "s"
	'less_than_a_minute' => '1 dakikadan daha az',
	'1_minute' => '1 dakika önce',
	'_minutes' => '{x} dakika önce',
	'about_1_hour' => 'yaklaşık 1 saat önce',
	'_hours' => '{x} saat önce',
	'1_day' => '1 gün önce',
	'_days' => '{x} gün önce',
	'about_1_month' => 'yaklaşık 1 ay',
	'_months' => '{x} ay önce',
	'about_1_year' => 'yaklaşık 1 yıl',
	'over_x_years' => '{x} yıl önce'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Sayfa başına _MENU_ kayıt göster', // Don't replace "_MENU_"
	'nothing_found' => 'Sonuç bulunamadı.',
	'page_x_of_y' => '_PAGES_ sayfadan _PAGE_ gösteriliyor.', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Kayıt bulunamadı.',
	'filtered' => '(_MAX_ arasından filrelendi.)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Kayıt işlemini tamamla.'
);
 
?>
