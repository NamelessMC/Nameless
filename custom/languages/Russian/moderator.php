<?php
/*
 *  Made by Samerton
 *  Translated by Я научу тебя шить XIMI
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Russian Language - Moderator terms
 */

$language = array(
    'mod_cp' => 'Панель модератора',
    'staff_cp' => 'Админ панель',
    'overview' => 'Обзор',

    // Spam
    'spam' => 'Спам',
    'mark_as_spam' => 'Пометить как спам',
    'confirm_spam' => '<p>Вы уверены, что хотите пометить это как спам?</p><p>Пользователь будет заблокирован по IP-адресу, и весь его контент будет удален.</p>',
    'user_marked_as_spam' => 'Пользователь успешно помечен как спам.',
    'cant_ban_admin' => 'Вы не можете заблокировать администратора!',

    // IP Lookup
    'ip_lookup' => 'Просмотр IP',
    'search_for_ip' => 'Поиск имени пользователя или IP-адреса',
    'no_ips_with_username' => 'IP-адреса для этого пользователя не найдены.',
    'no_accounts_with_that_ip' => 'Никаких учетных записей для этого IP-адреса не найдено.',
    '1_account_with_ip' => 'Найден 1 аккаунт с IP {y}', // Don't replace "{y}"
    'count_accounts_with_ip' => 'Найдено {x} аккаунтов с IP {y}', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => 'Найден 1 IP-адрес у пользователя {y}', // Don't replace "{y}"
    'count_ips_with_name' => 'Найдено {x} IP-адресов у пользователя {y}', // Don't replace "{x}" or "{y}"
    'no_users_or_ips_found' => 'Ни пользователей, ни IP-адресов не найдено.',

    // Reports
    'reports' => 'Жалобы',
    'report_alert' => 'Поступила новая жалоба',
    'user_reported' => 'Жалоба на пользователя',
    'comments' => 'Комментарии',
    'updated_by' => 'Обновлено пользователем ',
    'actions' => 'Действия',
    'view_closed' => 'Посмотреть закрытые',
    'view_open' => 'Посмотреть открытые',
    'viewing_report' => 'Просмотр жалобы',
    'view_content' => 'Посмотреть содержимое жалобы',
    'no_comments' => 'Нет комментариев',
    'new_comment' => 'Новый комментарий',
    'report_comment_invalid' => 'Недопустимое содержание комментария. Пожалуйста, убедитесь, что вы ввели комментарий длиной от 1 до 10000 символов.',
    'close_report' => 'Закрыть жалобу',
    'reopen_report' => 'Переоткрыть жалобу',
    '1_open_report' => 'Сейчас <strong>1</strong> открытая жалоба.',
    'open_reports' => 'Сейчас <strong>{x}</strong> открытых жалоб.', // Don't replace {x}
    'no_open_reports' => 'В настоящее время жалоб нет.',
    'no_closed_reports' => 'В настоящее время закрытых жалоб нет.',
    'recent_reports' => 'Недавние жалобы',
    'reported_by' => 'Пожаловался:',
    'website' => 'Вебсайт',
    'ingame' => 'Игра',
    'x_closed_report' => '{x} закрыл эту жалобу.', // Don't replace {x} (username)
    'x_reopened_report' => '{x} переоткрыл эту жалобу.', // Don't replace {x} (username)
    'report_reopened' => 'Жалоба успешно переоткрыта.',
    'report_closed' => 'Жалоба успешно закрыта.',
    'comment_created' => 'Комментарий успешно оставлен.',

    // Punishments
    'punishments' => 'Наказания',
    'view_punishments' => 'Просмотр наказаний',
    'banned' => 'Забанен',
    'groups' => 'Группы',
    'punish' => 'Наказать',
    'ban' => 'Бан',
    'warn' => 'Предупреждение',
    'ban_ip' => 'Бан по IP',
    'viewing_user_x' => 'Просмотр пользователя {x}', // Don't replace {x}
    'previous_punishments' => 'Предыдущие наказания',
    'no_previous_punishments' => 'Никаких наказаний не найдено',
    'warning' => 'Предупреждение',
    'ip_ban' => 'IP Бан',
    'reason' => 'Причина',
    'warn_user' => 'Выдать предупреждение',
    'ban_user' => 'Забанить',
    'enter_valid_punishment_reason' => 'Пожалуйста, введите действительную причину вашего наказания, от 5 до 5000 символов.',
    'user_punished' => 'Пользователь наказан.',
    'user_punished_alert' => '{x} наказал пользователя {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Отменить',
    'revoked' => 'Отменено',
    'acknowledged' => 'Acknowledged',
    'confirm_revoke_warning' => 'Вы уверены, что хотите отменить это предупреждение?',
    'confirm_revoke_ban' => 'Вы уверены, что хотите снять бан? У пользователя будет снята блокировка, даже если у него есть более поздний бан!',
    'punishment_revoked' => 'Наказание отменено.',
    'punishment_revoked_alert' => '{x} отменил наказание для пользователя {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'Вы не можете наказать администратора!',
    'viewing_all_punishments' => 'Просмотр всех наказаний',
    'no_punishments_found' => 'Никаких наказаний не найдено.',
    'view_user' => 'Просмотр пользователя',
    'when' => 'Когда',
    'staff' => 'Команда',
    'type' => 'Тип',
    'recent_punishments' => 'Последние наказания',
    'created' => 'Создано:',
    'staff:' => 'Выдал:',
    'reason:' => 'Причина:',

    // Users
    'recent_registrations' => 'Последние регистрации',
    'reset_profile_banner' => 'Сбросить баннер профиля'

);
