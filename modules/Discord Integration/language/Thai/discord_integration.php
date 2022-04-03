<?php

// EnglishUK

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'เปิดใช้งานการรวม Discord หรือไม่',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'Discord Role ID ต้องเป็นตัวเลข',
    'discord_role_id_length' => 'Discord Role ID ต้องมีความยาว 18 หลัก',
    'discord_guild_id' => 'ID เซิฟเวอร์ Discord',
    'discord_widget_theme' => 'ธีมวิดเจ็ต Discord',
    'discord_widget_disabled' => 'วิดเจ็ตถูกปิดใช้งานสำหรับเซิร์ฟเวอร์ Discord ที่ระบุ โปรดไปที่แท็บ  ในการตั้งค่าเซิร์ฟเวอร์ Discord ของคุณ และตรวจสอบให้แน่ใจว่าวิดเจ็ต Discord เปิดใช้งานอยู่และ ID นั้นถูกต้อง',
    'discord_id_length' => 'โปรดตรวจสอบให้แน่ใจว่า ID Discord ของคุณมีความยาว 18 ตัวอักษร',
    'discord_id_numeric' => 'โปรดตรวจสอบให้แน่ใจว่า ID Discord ของคุณเป็นตัวเลข (เฉพาะตัวเลข)',
    'discord_invite_info' => 'เมื่อต้องการเชิญบอทลิงก์นิรนามไปยังเซิร์ฟเวอร์ Discord ของคุณ ให้คลิก {{inviteLinkStart}}here{{inviteLinkEnd}}. จากนั้นรันคำสั่ง <code>/apiurl</code> เพื่อเชื่อมโยงบอทกับเว็บไซต์ของคุณ หรือคุณสามารถใช้ {{selfHostLinkStart}}host the bot yourself{{selfHostLinkEnd}}.',
    'discord_bot_must_be_setup' => 'ไม่สามารถเปิดใช้งานการรวม Discord กันจนกว่าคุณจะตั้งค่าบอท สําหรับข้อมูลเพิ่มเติมโปรด {{linkStart}}click here{{linkEnd}}.',
    'discord_bot_setup' => 'เซ็ตค่าบอทหรือไม่',
    'discord_integration_not_setup' => 'ไม่ได้ตั้งค่าการรวม Discord',
    'discord_username' => 'ชื่อผู้ใช้ Discord',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'เนื้อหาคำขอไม่ถูกต้อง',
    'discord_bot_error_error' => 'เกิดข้อผิดพลาดของบอทภายใน',
    'discord_bot_error_invguild' => 'Guild ID ที่ระบุไม่ถูกต้อง หรือไม่มีบอทอยู่ในนั้น',
    'discord_bot_error_invuser' => 'ID ผู้ใช้ที่ระบุไม่ถูกต้อง หรือไม่ได้อยู่ในกิลด์ที่ระบุ',
    'discord_bot_error_notlinked' => 'บอทไม่ได้เชื่อมโยงกับเว็บไซต์นี้สำหรับ ID กิลด์ที่ให้ไว้',
    'discord_bot_error_unauthorized' => 'คีย์ API ของเว็บไซต์ไม่ถูกต้อง',
    'discord_bot_error_invrole' => 'รหัสบทบาทที่ระบุไม่ถูกต้อง',
    'discord_bot_check_logs' => 'You should check for a more specific error (if one exists) in StaffCP -> Security -> All Logs.',
    'discord_bot_error_partsuccess' => 'The bot could not edit one or more of the roles due to a Discord hierarchy misconfiguration.',

    // API Errors
    'discord_integration_disabled' => 'การรวม Discord ถูกปิดใช้งาน',
    'unable_to_set_discord_id' => 'ไม่สามารถตั้งค่า Discord ID',
    'unable_to_set_discord_bot_url' => 'ไม่สามารถตั้งค่า URL บอท Discord ได้',
    'provide_one_discord_settings' => 'โปรดระบุข้อมูลต่อไปนี้อย่างน้อยหนึ่งรายการ: "url", "guild_id"',
    'no_pending_verification_for_token' => 'ไม่มีการตรวจสอบที่รอดำเนินการภายใต้โทเค็นที่ให้มา',
    'unable_to_update_discord_username' => 'ไม่สามารถอัปเดตชื่อผู้ใช้ Discord',
    'unable_to_update_discord_roles' => 'ไม่สามารถอัปเดตรายการบทบาท Discord',
    'unable_to_update_discord_bot_username' => 'ไม่สามารถอัปเดตชื่อผู้ใช้บอท Discord',

    // API Success
    'discord_id_set' => 'ตั้ง Discord ID สำเร็จ',
    'discord_settings_updated' => 'อัปเดตการตั้งค่า Discord เรียบร้อยแล้ว',
    'discord_usernames_updated' => 'อัปเดตชื่อผู้ใช้ Discord สำเร็จแล้ว',

    // Discord
    'discord_link' => 'ลิงค์ Discord',
    'linked' => 'เชื่อมโยง',
    'not_linked' => 'ไม่เชื่อมโยง',
    'discord_id' => 'ID ผู้ใช้ Discord',
    'discord_id_unlinked' => 'ยกเลิกการเชื่อมโยง ID ผู้ใช้ Discord ของคุณสำเร็จแล้ว.',
    'discord_id_confirm' => 'โปรดเรียกใช้คำสั่ง "/verify {{token}}" ใน Discord เพื่อสิ้นสุดการเชื่อมโยงบัญชี Discord ของคุณ.',
    'pending_link' => 'รอดำเนินการ',
    'discord_id_taken' => 'Discord ID นั้นถูกใช้ไปแล้ว',
    'discord_invalid_id' => 'ID ผู้ใช้ Discord นั้นไม่ถูกต้อง',
    'discord_already_pending' => 'คุณมีการยืนยันที่รอดำเนินการอยู่แล้ว',
    'discord_database_error' => 'ขณะนี้ฐานข้อมูล Nameless Link หยุดทำงาน โปรดลองอีกครั้งในภายหลัง.',
    'discord_communication_error' => 'เกิดข้อผิดพลาดขณะสื่อสารกับ Discord Bot โปรดตรวจสอบว่าบอทกำลังทำงานและ URL บอทของคุณถูกต้อง',
    'discord_unknown_error' => 'เกิดข้อผิดพลาดที่ไม่รู้จักขณะซิงค์บทบาท Discord โปรดติดต่อผู้ดูแลระบบ',
    'discord_id_help' => 'สำหรับข้อมูลเกี่ยวกับตำแหน่งของ Discord ID โปรดอ่าน <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>',
];
