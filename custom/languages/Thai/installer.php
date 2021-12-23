<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  ภาษาไทย Language - Installation
 */

$language = [
    /*
     *  Installation
     */
    'install' => 'ติดตั้ง',
    'pre-release' => 'pre-release',
    'installer_welcome' => 'ยินดีต้อนรับสู่ NamelessMC version 2.0 pre-release.',
    'pre-release_warning' => 'โปรดทราบว่ารุ่นก่อนเผยแพร่นี้ไม่ได้มีไว้สำหรับใช้บนเว็บไซต์สาธารณะ',
    'installer_information' => 'โปรแกรมติดตั้งจะแนะนำคุณตลอดขั้นตอนการติดตั้ง',
    'terms_and_conditions' => 'การดำเนินการต่อแสดงว่าคุณยอมรับข้อกำหนดและเงื่อนไข',
    'new_installation_question' => 'ประการแรกนี่คือการติดตั้งใหม่หรือไม่?',
    'new_installation' => 'การติดตั้งใหม่ &raquo;',
    'upgrading_from_v1' => 'กำลังอัปเกรดจาก v1 &raquo;',
    'requirements' => 'Requirements:',
    'config_writable' => 'core/config.php เขียนได้',
    'cache_writable' => 'cache เขียนได้',
    'template_cache_writable' => 'Template Cache เขียนได้',
    'exif_imagetype_banners_disabled' => 'หากไม่มีฟังก์ชัน exif_imagetype banners เซิร์ฟเวอร์จะถูกปิดใช้งาน',
    'requirements_error' => 'คุณต้องติดตั้งส่วนขยายที่จำเป็นทั้งหมดและตั้งค่าการอนุญาตที่ถูกต้องเพื่อดำเนินการติดตั้งต่อ',
    'proceed' => 'ดำเนินการ',
    'database_configuration' => 'การกำหนดค่าฐานข้อมูล',
    'database_address' => 'ที่อยู่ฐานข้อมูล',
    'database_port' => 'พอร์ตฐานข้อมูล',
    'database_username' => 'ชื่อผู้ใช้ฐานข้อมูล',
    'database_password' => 'รหัสผ่านฐานข้อมูล',
    'database_name' => 'ชื่อฐานข้อมูล',
    'nameless_path' => 'เส้นทางการติดตั้ง',
    'nameless_path_info' => 'นี่คือเส้นทางที่มีการติดตั้ง Nameless ซึ่งสัมพันธ์กับโดเมนของคุณ ตัวอย่างเช่น หากติดตั้ง Nameless ไว้ที่ example.com/forum จะต้องเป็น <strong>forum</strong>. เว้นว่างไว้หาก Nameless ไม่ได้อยู่ในโฟลเดอร์ย่อย',
    'friendly_urls' => 'URL ที่เป็นมิตร',
    'friendly_urls_info' => 'URL ที่จำง่ายจะปรับปรุงความสามารถในการอ่าน URL ในเบราว์เซอร์ของคุณ.<br />For example: <br /><code>example.com/index.php?route=/forum</code><br />would become:<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Important!</strong><br />เซิร์ฟเวอร์ของคุณต้องได้รับการกำหนดค่าอย่างถูกต้องเพื่อให้ทำงานได้ คุณสามารถดูว่าคุณสามารถเปิดใช้งานตัวเลือกนี้ได้หรือไม่โดยคลิก <a href="./rewrite_test" target="_blank" style="color:#2185D0">here</a>.</div>',
    'enabled' => 'เปิดใช้งาน',
    'disabled' => 'ปิดการใช้งาน',
    'character_set' => 'ชุดตัวอักษร',
    'database_engine' => 'เครื่องมือจัดเก็บฐานข้อมูล',
    'host' => 'ชื่อโฮสต์',
    'host_help' => 'ชื่อโฮสต์คือ <strong>base URL</strong> for your website. อย่ารวมโฟลเดอร์ย่อยจากฟิลด์เส้นทางการติดตั้ง หรือ http(s):// ที่นี่!',
    'database_error' => 'โปรดตรวจสอบให้แน่ใจว่าได้กรอกข้อมูลครบทุกช่องแล้ว',
    'submit' => 'ส่ง',
    'installer_now_initialising_database' => 'โปรแกรมติดตั้งกำลังเริ่มต้นฐานข้อมูล อาจใช้เวลาสักครู่...',
    'configuration' => 'การกำหนดค่า',
    'configuration_info' => 'กรุณาป้อนข้อมูลพื้นฐานเกี่ยวกับเว็บไซต์ของคุณ ค่าเหล่านี้สามารถเปลี่ยนแปลงได้ในภายหลังผ่านแผงการดูแลระบบ',
    'configuration_error' => 'โปรดป้อนชื่อไซต์ที่ถูกต้องระหว่าง 1 ถึง 32 อักขระ และที่อยู่อีเมลที่ถูกต้องระหว่าง 4 ถึง 64 อักขระ',
    'site_name' => 'ชื่อเว็บไซต์',
    'contact_email' => 'อีเมลติดต่อ',
    'outgoing_email' => 'อีเมลส่งออก',
    'language' => 'ภาษา',
    'initialising_database_and_cache' => 'กำลังเริ่มต้นฐานข้อมูลและแคช โปรดรอสักครู่...',
    'unable_to_login' => 'ไม่สามารถเข้าสู่ระบบได้.',
    'unable_to_create_account' => 'สร้างบัญชีไม่ได้',
    'input_required' => 'กรุณาใส่ชื่อผู้ใช้ ที่อยู่อีเมล และรหัสผ่านที่ถูกต้อง',
    'input_minimum' => 'โปรดตรวจสอบให้แน่ใจว่าชื่อผู้ใช้ของคุณมีอย่างน้อย 3 ตัวอักษร ที่อยู่อีเมลของคุณมีอย่างน้อย 4 ตัวอักษร และรหัสผ่านของคุณมีอย่างน้อย 6 ตัวอักษร',
    'input_maximum' => 'โปรดตรวจสอบให้แน่ใจว่าชื่อผู้ใช้ของคุณมีความยาวสูงสุด 20 อักขระ และที่อยู่อีเมลและรหัสผ่านของคุณมีอักขระสูงสุด 64 ตัว',
    'email_invalid' => 'อีเมลของคุณไม่ถูกต้อง',
    'passwords_must_match' => 'รหัสผ่านของคุณต้องตรงกัน',
    'creating_admin_account' => 'การสร้างบัญชีผู้ดูแลระบบ',
    'enter_admin_details' => 'กรุณากรอกรายละเอียดสำหรับบัญชีผู้ดูแลระบบ',
    'username' => 'ชื่อผู้ใช้',
    'email_address' => 'ที่อยู่อีเมล',
    'password' => 'รหัสผ่าน',
    'confirm_password' => 'ยืนยันรหัสผ่าน',
    'upgrade' => 'อัพเกรด',
    'input_v1_details' => 'โปรดป้อนรายละเอียดฐานข้อมูลสำหรับการติดตั้ง Nameless เวอร์ชัน 1 ของคุณ',
    'installer_upgrading_database' => 'โปรดรอในขณะที่โปรแกรมติดตั้งอัปเกรดฐานข้อมูลของคุณ...',
    'errors_logged' => 'บันทึกข้อผิดพลาดแล้ว คลิกดำเนินการต่อเพื่อดำเนินการอัปเกรดต่อ',
    'continue' => 'ดำเนินการต่อ',
    'convert' => 'แปลง',
    'convert_message' => 'สุดท้าย คุณต้องการแปลงจากซอฟต์แวร์ฟอรัมอื่นหรือไม่?',
    'yes' => 'ใช่',
    'no' => 'ไม่',
    'converter' => 'ตัวแปลง',
    'back' => 'กลับ',
    'unable_to_load_converter' => 'โหลดตัวแปลงไม่ได้!',
    'finish' => 'เสร็จสิ้น',
    'finish_message' => 'ขอบคุณสำหรับการติดตั้ง NamelessMC! ตอนนี้คุณสามารถไปที่เจ้าหน้าที่ ซึ่งคุณสามารถกำหนดค่าเว็บไซต์ของคุณเพิ่มเติมได้',
    'support_message' => 'หากคุณต้องการความช่วยเหลือ ตรวจสอบเว็บไซต์ของเรา <a href="https://namelessmc.com" target="_blank">here</a>, หรือเยี่ยมชมของเราได้เช่นกัน <a href="https://discord.gg/nameless" target="_blank">Discord server</a> or our <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub repository</a>.',
    'credits' => 'เครดิต',
    'credits_message' => 'ขอบคุณมากสำหรับทุกคน <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC contributors</a> since 2014',

    'step_home' => 'Home',
    'step_requirements' => 'Requirements',
    'step_general_config' => 'การกำหนดค่าทั่วไป',
    'step_database_config' => 'การกำหนดค่าฐานข้อมูล',
    'step_site_config' => 'การกำหนดค่าไซต์',
    'step_admin_account' => 'บัญชีแอดมิน',
    'step_conversion' => 'การแปลง',
    'step_finish' => 'เสร็จสิ้น',

    'general_configuration' => 'การกำหนดค่าทั่วไป',
    'reload' => 'โหลดซ้ำ',
    'reload_page' => 'โหลดหน้าใหม่',
    'no_converters_available' => 'ไม่มีตัวแปลงที่ใช้ได้',
    'config_not_writable' => 'ไฟล์กำหนดค่าไม่สามารถเขียนได้',

    'session_doesnt_exist' => 'ตรวจไม่พบเซสชัน การบันทึกเซสชันเป็นข้อกำหนดในการใช้ Nameless โปรดลองอีกครั้ง และหากปัญหายังคงอยู่ โปรดติดต่อโฮสต์เว็บของคุณเพื่อขอรับการสนับสนุน'
];