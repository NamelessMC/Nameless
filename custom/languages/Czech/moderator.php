<?php
/*
 *  Made by Samerton
 *  Translated by Fjuro
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Czech Language - Moderator terms
 */

$language = [
    'staff_cp' => 'Panel',
    'overview' => 'Nástěnka',

    // Spam
    'spam' => 'Spam',
    'mark_as_spam' => 'Označit jako spam',
    'confirm_spam' => '<p>Opravdu chcete označit tohoto uživatele za spam?</p><p>Uživatelova IP bude zabanována a všechen jeho obsah odstraněn.</p>',
    'user_marked_as_spam' => 'Uživatel úspěšně označen za spam.',
    'cant_ban_admin' => 'Nemůžete zabanovat správce!',

    // IP Lookup
    'ip_lookup' => 'Vyhledávání IP',
    'search_for_ip' => 'Hledat uživatelské jméno nebo IP adresu',
    'no_ips_with_username' => 'U tohoto uživatele nebyly nalezeny žádné IP adresy.',
    'no_accounts_with_that_ip' => 'U této IP adresy nebyly nalezeny žádné účty.',
    '1_account_with_ip' => 'Byl nalezen 1 účet s IP adresou {y}', // Don't replace "{y}"
    'count_accounts_with_ip' => 'Bylo nalezeno {x} účtů s IP adresou {y}', // Don't replace "{x}" "{y}"
    '1_ip_with_name' => 'U uživatele {y} byla nalezena 1 IP adresa', // Don't replace "{y}"
    'count_ips_with_name' => 'U uživatele {y} bylo nalezeno {x} IP adres', // Don't replace "{x}" "{y}"
    'no_users_or_ips_found' => 'Nebyly nalezeny žádné IP adresy ani uživatelé.',

    // Reports
    'reports' => 'Nahlášení',
    'report_alert' => 'Bylo odesláno nové nahlášení',
    'user_reported' => 'Nahlášený uživatel',
    'comments' => 'Komentáře',
    'updated_by' => 'Aktualizoval',
    'actions' => 'Akce',
    'view_closed' => 'Zobrazit uzavřené',
    'view_open' => 'Zobrazit otevřené',
    'viewing_report' => 'Prohlížení nahlášení',
    'view_content' => 'Prohlížení nahlášeného obsahu',
    'no_comments' => 'Žádné komentáře',
    'new_comment' => 'Nový komentář',
    'report_comment_invalid' => 'Neplatný obsah komentáře. Ujistěte se, že jste zadali komentář o délce 1 až 10 000 znaků.',
    'close_report' => 'Uzavřít nahlášení',
    'reopen_report' => 'Znovu otevřít nahlášení',
    '1_open_report' => 'Momentálně je otevřeno <strong>1</strong> nahlášení.',
    'open_reports' => 'Momentálně je otevřeno <strong>{x}</strong> nahlášení.', // Don't replace {x}
    'no_open_reports' => 'Zatím nebyla otevřena žádná nahlášení.',
    'no_closed_reports' => 'Zatím nebyla uzavřena žádná nahlášení.',
    'recent_reports' => 'Poslední nahlášení',
    'reported_by' => 'Nahlásil:',
    'website' => 'Web',
    'ingame' => 'Ve hře',
    'x_closed_report' => '{x} uzavřel toto nahlášení.', // Don't replace {x} (username)
    'x_reopened_report' => '{x} znovu otevřel toto nahlášení.', // Don't replace {x} (username)
    'report_reopened' => 'Nahlášení úspěšně znovu otevřeno.',
    'report_closed' => 'Nahlášení úspěšně uzavřeno.',
    'comment_created' => 'Komentář úspěšně vytvořen.',

    // Punishments
    'punishments' => 'Tresty',
    'view_punishments' => 'Zobrazit tresty',
    'banned' => 'Zabanován',
    'groups' => 'Skupiny',
    'punish' => 'Potrestat',
    'ban' => 'Zabanovat',
    'warn' => 'Varovat',
    'ban_ip' => 'Zabanovat IP',
    'viewing_user_x' => 'Prohlížení uživatele {x}', // Don't replace {x}
    'previous_punishments' => 'Předchozí tresty',
    'no_previous_punishments' => 'Žádné předchozí tresty',
    'reset_avatar' => 'Obnovit avatar',
    'warning' => 'Varování',
    'ip_ban' => 'IP ban',
    'reason' => 'Důvod',
    'warn_user' => 'Varovat uživatele',
    'ban_user' => 'Zabanovat uživatele',
    'enter_valid_punishment_reason' => 'Zadejte platný důvod vašeho trestu, o délce 5 až 5000 znaků.',
    'user_punished' => 'Uživatel potrestán.',
    'user_punished_alert' => '{x} potrestal uživatele {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Odvolat',
    'revoked' => 'Odvolán',
    'acknowledged' => 'Uznáno',
    'confirm_revoke_warning' => 'Opravdu chcete odvolat toto varování?',
    'confirm_revoke_ban' => 'Opravdu chcete odvolat tento ban? Uživatel bude odbanován, i když má novější ban!',
    'punishment_revoked' => 'Trest odvolán.',
    'punishment_revoked_alert' => '{x} odvolal trest uživateli {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'Nemůžete potrestat správce!',
    'viewing_all_punishments' => 'Prohlížení všech trestů',
    'no_punishments_found' => 'Nebyly nalezeny žádné tresty.',
    'view_user' => 'Zobrazit uživatele',
    'when' => 'Kdy',
    'staff' => 'Člen týmu',
    'type' => 'Typ',
    'recent_punishments' => 'Poslední tresty',
    'created' => 'Vytvořeno:',
    'staff:' => 'Člen týmu:',
    'reason:' => 'Důvod:',

    // Users
    'recent_registrations' => 'Poslední registrace',
    'reset_profile_banner' => 'Obnovit profilový obrázek'

];