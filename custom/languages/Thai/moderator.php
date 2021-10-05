<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  ภาษาไทย Language - Moderator terms
 */

$language = array(
    'mod_cp' => 'ModCP',
    'staff_cp' => 'StaffCP',
    'overview' => 'ภาพรวม',

    // Spam
    'spam' => 'สแปม',
    'mark_as_spam' => 'ทำเครื่องหมายว่าเป็นสแปม',
    'confirm_spam' => '<p>คุณแน่ใจหรือไม่ว่าต้องการทำเครื่องหมายผู้ใช้รายนี้เป็นสแปม</p><p>ผู้ใช้จะถูกแบน IP และเนื้อหาทั้งหมดของพวกเขาจะถูกลบออก</p>',
    'user_marked_as_spam' => 'ผู้ใช้ทำเครื่องหมายว่าเป็นสแปมเรียบร้อยแล้ว.',
    'cant_ban_admin' => 'คุณไม่สามารถแบนผู้ดูแลระบบได้!',

    // IP Lookup
    'ip_lookup' => 'ค้นหา IP',
    'search_for_ip' => 'ค้นหาชื่อผู้ใช้หรือที่อยู่ IP',
    'no_ips_with_username' => 'ไม่พบที่อยู่ IP สำหรับผู้ใช้รายนั้น',
    'no_accounts_with_that_ip' => 'ไม่พบบัญชีสำหรับที่อยู่ IP นั้น',
    '1_account_with_ip' => 'พบ 1 บัญชีที่มี IP {y}', // Don't replace "{y}"
    'count_accounts_with_ip' => 'พบ {x} บัญชีที่มี IP {y}', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => 'พบ 1 IP address สำหรับผู้ใช้ {y}', // Don't replace "{y}"
    'count_ips_with_name' => 'พบ {x} ที่อยู่ IP สำหรับผู้ใช้ {y}', // Don't replace "{x}" or "{y}"
    'no_users_or_ips_found' => 'ไม่พบผู้ใช้หรือที่อยู่ IP',

    // Reports
    'reports' => 'รายงาน',
    'report_alert' => 'ส่งรายงานใหม่',
    'user_reported' => 'รายงานผู้ใช้',
    'comments' => 'ความคิดเห็น',
    'updated_by' => 'อัพเดทโดย',
    'actions' => 'การกระทำ',
    'view_closed' => 'ปิดดู',
    'view_open' => 'เปิดดู',
    'viewing_report' => 'กำลังดูรายงาน',
    'view_content' => 'ดูเนื้อหาที่รายงาน',
    'no_comments' => 'ไม่มีความคิดเห็น',
    'new_comment' => 'ความคิดเห็นใหม่',
    'report_comment_invalid' => 'เนื้อหาความคิดเห็นไม่ถูกต้อง โปรดตรวจสอบให้แน่ใจว่าคุณได้ป้อนความคิดเห็นระหว่าง 1 ถึง 10,000 อักขระ',
    'close_report' => 'ปิดรายงาน',
    'reopen_report' => 'เปิดรายงานอีกครั้ง',
    '1_open_report' => 'ขณะนี้มีรายงานที่เปิดอยู่ <strong>1</strong> รายการ',
    'open_reports' => 'ขณะนี้มีรายงานที่เปิดอยู่ <strong>{x}</strong> รายการ', // Don't replace {x}
    'no_open_reports' => 'ขณะนี้ยังไม่มีรายงานที่เปิดอยู่',
    'no_closed_reports' => 'ขณะนี้ยังไม่มีรายงานที่ปิด',
    'recent_reports' => 'รายงานล่าสุด',
    'reported_by' => 'รายงานโดย:',
    'website' => 'เว็บไซต์',
    'ingame' => 'ในเกมส์',
    'x_closed_report' => '{x} ปิดรายงานนี้.', // Don't replace {x} (username)
    'x_reopened_report' => '{x}ได้เปิดรายงานนี้อีกครั้ง', // Don't replace {x} (username)
    'report_reopened' => 'เปิดรายงานใหม่เรียบร้อยแล้ว.',
    'report_closed' => 'รายงานปิดสำเร็จ',
    'comment_created' => 'สร้างความคิดเห็นเรียบร้อยแล้ว',

    // Punishments
    'punishments' => 'Punishments',
    'view_punishments' => 'View Punishments',
    'banned' => 'Banned',
    'groups' => 'Groups',
    'punish' => 'Punish',
    'ban' => 'แบน',
    'warn' => 'เตือน',
    'ban_ip' => 'แบน IP',
    'viewing_user_x' => 'กำลังดูผู้ใช้ {x}', // Don't replace {x}
    'previous_punishments' => 'บทลงโทษก่อนหน้า',
    'no_previous_punishments' => 'ไม่มีการลงโทษก่อนหน้านี้',
    'warning' => 'คำเตือน',
    'ip_ban' => 'IP Ban',
    'reason' => 'เหตุผล',
    'warn_user' => 'เตือนผู้ใช้',
    'ban_user' => 'แบนผู้ใช้',
    'enter_valid_punishment_reason' => 'โปรดป้อนเหตุผลที่ถูกต้องสำหรับการลงโทษของคุณ ระหว่าง 5 ถึง 5000 อักขระ',
    'user_punished' => 'ผู้ใช้ถูกลงโทษ',
    'user_punished_alert' => '{x} ได้ลงโทษผู้ใช้ {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Revoke',
    'revoked' => 'ยกเลิก',
    'acknowledged' => 'รับทราบ',
    'confirm_revoke_warning' => 'คุณแน่ใจหรือไม่ว่าต้องการเพิกถอนคำเตือนนี้',
    'confirm_revoke_ban' => 'คุณแน่ใจหรือไม่ว่าต้องการเพิกถอนการแบนนี้ ผู้ใช้จะถูกแบน แม้ว่าจะมีการแบนครั้งล่าสุดก็ตาม!',
    'punishment_revoked' => 'เพิกถอนการลงโทษ',
    'punishment_revoked_alert' => '{x} ได้เพิกถอนการลงโทษผู้ใช้ {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'คุณไม่สามารถลงโทษผู้ดูแลระบบได้!',
    'viewing_all_punishments' => 'กำลังดูบทลงโทษทั้งหมด',
    'no_punishments_found' => 'ไม่พบการลงโทษ',
    'view_user' => 'ดูผู้ใช้',
    'when' => 'When',
    'staff' => 'ผู้ดูแล',
    'type' => 'Type',
    'recent_punishments' => 'การลงโทษล่าสุด',
    'created' => 'Created:',
    'staff:' => 'ผู้ดูแล:',
    'reason:' => 'เหตุผล:',

    // Users
    'recent_registrations' => 'การลงทะเบียนล่าสุด',
    'reset_profile_banner' => 'รีเซ็ต banner โปรไฟล์'

);