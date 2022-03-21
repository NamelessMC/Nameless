<?php

// EnglishUK

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Enable Discord integration?',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_numeric' => 'Discord Role ID must be numeric.',
    'discord_role_id_length' => 'Discord Role ID must be 18 digits long.',
    'discord_guild_id' => 'ID เซิฟเวอร์ Discord',
    'discord_widget_theme' => 'ธีมวิดเจ็ต Discord',
    'discord_widget_disabled' => 'วิดเจ็ตถูกปิดใช้งานสำหรับเซิร์ฟเวอร์ Discord ที่ระบุ โปรดไปที่แท็บ  ในการตั้งค่าเซิร์ฟเวอร์ Discord ของคุณ และตรวจสอบให้แน่ใจว่าวิดเจ็ต Discord เปิดใช้งานอยู่และ ID นั้นถูกต้อง',
    'discord_id_length' => 'โปรดตรวจสอบให้แน่ใจว่า ID Discord ของคุณมีความยาว 18 ตัวอักษร',
    'discord_id_numeric' => 'โปรดตรวจสอบให้แน่ใจว่า ID Discord ของคุณเป็นตัวเลข (เฉพาะตัวเลข)',
    'discord_invite_info' => 'เมื่อต้องการเชิญบอทลิงก์นิรนามไปยังเซิร์ฟเวอร์ Discord ของคุณ ให้คลิก <a target="_blank" href="https://namelessmc.com/discord-bot-invite">here</a>. จากนั้นรันคำสั่ง <code>/apiurl</code> เพื่อเชื่อมโยงบอทกับเว็บไซต์ของคุณ หรือคุณสามารถใช้ <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">host the bot yourself</a>.',
    'discord_bot_must_be_setup' => 'ไม่สามารถเปิดใช้งานการรวม Discord กันจนกว่าคุณจะตั้งค่าบอท สําหรับข้อมูลเพิ่มเติมโปรด <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">click here</a>.',
    'discord_bot_setup' => 'เซ็ตค่าบอทหรือไม่',
    'discord_integration_not_setup' => 'ไม่ได้ตั้งค่าการรวม Discord',
    'discord_username' => 'ชื่อผู้ใช้ Discord',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Invalid request body.',
    'discord_bot_error_error' => 'An internal bot error occured.',
    'discord_bot_error_invguild' => 'Provided Guild ID is invalid, or the bot is not in it.',
    'discord_bot_error_invuser' => 'Provided User ID is invalid, or is not in specified Guild.',
    'discord_bot_error_notlinked' => 'The bot is not linked to this website for provided Guild ID.',
    'discord_bot_error_unauthorized' => 'Website API key is invalid',
    'discord_bot_error_invrole' => 'Provided Role ID is invalid.',
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
    'discord_usernames_updated' => 'อัปเดตชื่อผู้ใช้ Discord สำเร็จแล้ว'

    // Discord
    'discord_link' => 'ลิงค์ Discord',
    'linked' => 'เชื่อมโยง',
    'not_linked' => 'ไม่เชื่อมโยง',
    'discord_id' => 'ID ผู้ใช้ Discord',
    'discord_id_unlinked' => 'ยกเลิกการเชื่อมโยง ID ผู้ใช้ Discord ของคุณสำเร็จแล้ว.',
    'discord_id_confirm' => 'โปรดเรียกใช้คำสั่ง "/verify token:{token}" ใน Discord เพื่อสิ้นสุดการเชื่อมโยงบัญชี Discord ของคุณ.',
    'pending_link' => 'รอดำเนินการ',
    'discord_id_taken' => 'Discord ID นั้นถูกใช้ไปแล้ว',
    'discord_invalid_id' => 'ID ผู้ใช้ Discord นั้นไม่ถูกต้อง',
    'discord_already_pending' => 'คุณมีการยืนยันที่รอดำเนินการอยู่แล้ว',
    'discord_database_error' => 'ขณะนี้ฐานข้อมูล Nameless Link หยุดทำงาน โปรดลองอีกครั้งในภายหลัง.',
    'discord_communication_error' => 'เกิดข้อผิดพลาดขณะสื่อสารกับ Discord Bot โปรดตรวจสอบว่าบอทกำลังทำงานและ URL บอทของคุณถูกต้อง',
    'discord_unknown_error' => 'เกิดข้อผิดพลาดที่ไม่รู้จักขณะซิงค์บทบาท Discord โปรดติดต่อผู้ดูแลระบบ',
    'discord_id_help' => 'สำหรับข้อมูลเกี่ยวกับตำแหน่งของ Discord ID โปรดอ่าน <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>',
];
