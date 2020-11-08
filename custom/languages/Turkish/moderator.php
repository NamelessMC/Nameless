<?php
/*
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Turkish Language - Moderator terms
 *  Turkish translation by xOrcun
 */

$language = array(
    'mod_cp' => 'Moderatör Panel',
    'staff_cp' => 'Yönetim Panel',
    'overview' => 'Görüntüle',

    // Spam
    'spam' => 'Spam',
    'mark_as_spam' => 'Spam olarak işaretle',
    'confirm_spam' => '<p>Bu kullanıcıyı spam olarak işaretlemek istediğinizden emin misiniz?</p><p> Kullanıcı IP yasaklanacak ve tüm içeriği kaldırılacak.</p>',
    'user_marked_as_spam' => 'Kullanıcı başarıyla spam olarak işaretlendi.',
    'cant_ban_admin' => 'Bir yöneticiyi yasaklayamazsınız!',

    // IP Lookup
    'ip_lookup' => 'IP Arama',
    'search_for_ip' => 'Bir kullanıcı adı veya IP adresi arayın',
    'no_ips_with_username' => 'Bu kullanıcı için IP adresi bulunamadı.',
    'no_accounts_with_that_ip' => 'Bu IP adresi için hesap bulunamadı.',
    '1_account_with_ip' => 'IP {y} ile 1 hesap bulundu', // Don't replace "{y}"
    'count_accounts_with_ip' => 'IP ile {x} hesap bulundu {y}', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => '{y} kullanıcısı için 1 IP adresi bulundu', // Don't replace "{y}"
    'count_ips_with_name' => '{y} kullanıcısı için {x} IP adresi bulundu', // Don't replace "{x}" or "{y}"
    'no_users_or_ips_found' => 'Kullanıcı veya IP adresi bulunamadı.',

    // Reports
    'reports' => 'Raporlar',
    'report_alert' => 'Yeni rapor gönderildi!',
    'user_reported' => 'Kullanıcı raporlandı!',
    'comments' => 'Yorumlar',
    'updated_by' => 'Tarafından güncellendi',
    'actions' => 'Hareketler',
    'view_closed' => 'Görünüm Kapalı',
    'view_open' => 'Görünüm Açıkk',
    'viewing_report' => 'Raporu Görüntüleme',
    'view_content' => 'Bildirilen içeriği görüntüleme',
    'no_comments' => 'Yorum yok',
    'new_comment' => 'Yeni yorum',
    'report_comment_invalid' => 'Geçersiz yorum içeriği. Lütfen 1 ile 10000 karakter arasında bir yorum girdiğinizden emin olun.',
    'close_report' => 'Raporu kapat',
    'reopen_report' => 'Raporu yeniden aç',
    '1_open_report' => 'Şu anda <strong>1</strong> açık rapor var.',
    'open_reports' => 'Şu anda <strong>{x}</strong> açık rapor var.', // Don't replace {x}
    'no_open_reports' => 'Şu anda açık rapor yok.',
    'no_closed_reports' => 'Şu anda kapalı rapor yok.',
    'recent_reports' => 'Son Raporlar',
    'reported_by' => 'Raporlayan:',
    'website' => 'Site',
    'ingame' => 'Oyunda',
    'x_closed_report' => '{x} bu raporu kapattı.', // Don't replace {x} (username)
    'x_reopened_report' => '{x} bu raporu tekrar açtı', // Don't replace {x} (username)
    'report_reopened' => 'Rapor başarıyla yeniden açıldı.',
    'report_closed' => 'Rapor başarıyla kapatıldı.',
    'comment_created' => 'Yorum başarıyla oluşturuldu.',

    // Punishments
    'punishments' => 'Cezalar',
    'view_punishments' => 'Cezaları görüntüle',
    'banned' => 'Yasaklı',
    'groups' => 'Gruplar',
    'punish' => 'Cezalandır',
    'ban' => 'Yasakla',
    'warn' => 'Uyar',
    'ban_ip' => 'IP adresini yasakla',
    'viewing_user_x' => 'Kullanıcı {x} görüntüleniyor', // Don't replace {x}
    'previous_punishments' => 'Önceki cezalar',
    'no_previous_punishments' => 'Şu an hiç bir cezanız yok.',
    'warning' => 'Uyarı',
    'ip_ban' => 'IP adresi yasağı',
    'reason' => 'Sebei',
    'warn_user' => 'Kullanıcıyı uyar',
    'ban_user' => 'Kullanıcıyı yasakla',
    'enter_valid_punishment_reason' => 'Lütfen 5 ila 5000 karakter arasında geçerli bir ceza nedeni girin.',
    'user_punished' => 'Kullanıcı cezalandırdı.',
    'user_punished_alert' => '{x} kullanıcıyı cezalandırdı {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Iptal Et',
    'revoked' => 'Iptal Edilen',
    'acknowledged' => 'Tanınan',
    'confirm_revoke_warning' => 'Bu uyarıyı iptal etmek istediğinizden emin misiniz?',
    'confirm_revoke_ban' => 'Bu yasağı iptal etmek istediğinizden emin misiniz? Daha yakın bir yasağı olsa bile kullanıcının yasakları kaldırılacaktır!',
    'punishment_revoked' => 'Ceza iptal edildi.',
    'punishment_revoked_alert' => '{x}, {y} kullanıcısı için bir cezayı iptal etti', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'Bir yöneticiyi cezalandıramazsınız!',
    'viewing_all_punishments' => 'Tüm cezaları görüntüleme ',
    'no_punishments_found' => 'Ceza bulunmadı.',
    'view_user' => 'Kullanıcıyı Görüntüle',
    'when' => 'Tarih',
    'staff' => 'Yetkili',
    'type' => 'Tür',
    'recent_punishments' => 'Son Cezalar',
    'created' => 'Oluşturan:',
    'staff:' => 'Yetkili:',
    'reason:' => 'Sebep:',

    // Users
    'recent_registrations' => 'Son Kayıtlar',
    'reset_profile_banner' => 'Profil banner(afiş) sıfırla'

);
