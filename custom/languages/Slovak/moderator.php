<?php
/*
 *  Made by RobiNN
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Slovak Language - Moderator terms
 */

$language = array(
    'staff_cp' => 'StaffCP',
    'overview' => 'Prehľad',

    // Spam
    'spam' => 'Spam',
    'mark_as_spam' => 'Označiť ako spam',
    'confirm_spam' => '<p>Naozaj chcete tohto užívateľa označiť ako spam?</p><p>Užívateľ bude mať IP ban a bude odstránený všetok jeho obsah.</p>',
    'user_marked_as_spam' => 'Používateľ bol úspešne označený ako spam.',
    'cant_ban_admin' => 'Nemôžete dať ban správcovi!',

    // IP Lookup
    'ip_lookup' => 'Vyhľadať IP',
    'search_for_ip' => 'Vyhľadajte užívateľské meno alebo IP adresu',
    'no_ips_with_username' => 'Nenašli sa žiadne adresy IP tohto užívateľa.',
    'no_accounts_with_that_ip' => 'Nenašli sa žiadne účty pre túto IP adresu.',
    '1_account_with_ip' => 'Bol nájdený 1 účet s IP {y}', // Don't replace "{y}"
    'count_accounts_with_ip' => 'Našlo sa {x} účtov s IP {y}', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => 'Nájdená 1 IP adresa pre užívateľa {y}', // Don't replace "{y}"
    'count_ips_with_name' => 'Nájdených {x} IP adries pre užívateľa {y}', // Don't replace "{x}" or "{y}"
    'no_users_or_ips_found' => 'Nenašli sa žiadni užívatelia ani IP adresy.',

    // Reports
    'reports' => 'Hlásenia',
    'report_alert' => 'Bola odoslané nové hlásenie',
    'user_reported' => 'Užívateľ nahlásený',
    'comments' => 'Komentáre',
    'updated_by' => 'Aktualizované užívateľom',
    'actions' => 'Akcie',
    'view_closed' => 'Zobraziť zatvorené',
    'view_open' => 'Zobraziť otvorené',
    'viewing_report' => 'Prezeranie hlásenia',
    'view_content' => 'Zobraziť nahlásený obsah',
    'no_comments' => 'Žiadne komentáre',
    'new_comment' => 'Novy komentár',
    'report_comment_invalid' => 'Neplatný obsah komentára. Uistite sa, že ste zadali komentár s dĺžkou od 1 do 10 000 znakov.',
    'close_report' => 'Zavrieť hlásenie',
    'reopen_report' => 'Znova otvoriť hlásenie',
    '1_open_report' => 'Momentálne existuje <strong>1</strong> otvorené hlásenie.',
    'open_reports' => 'Momentálne existuje <strong> {x} </strong> otvorených hlásení.', // Don't replace {x}
    'no_open_reports' => 'Momentálne nie sú k dispozícii žiadne otvorené hlásenia.',
    'no_closed_reports' => 'Momentálne neexistujú žiadne uzavreté hlásenia.',
    'recent_reports' => 'Posledné hlásenia',
    'reported_by' => 'Nahlásene od:',
    'website' => 'Webstránka',
    'ingame' => 'V hre',
    'x_closed_report' => '{x} uzavrel/a toto hlásenie.', // Don't replace {x} (username)
    'x_reopened_report' => '{x} znovu otvoril/a toto hlásenie.', // Don't replace {x} (username)
    'report_reopened' => 'Hlásenie sa znovu otvorilo úspešne.',
    'report_closed' => 'Hlásenie sa zatvorilo úspešne.',
    'comment_created' => 'Komentár bol úspešne vytvorený.',

    // Punishments
    'punishments' => 'Tresty',
    'view_punishments' => 'Zobraziť tresty',
    'banned' => 'Zabanovaný/a',
    'groups' => 'Skupiny',
    'punish' => 'Trestať',
    'ban' => 'Ban',
    'warn' => 'Varovať',
    'ban_ip' => 'Zabanovať IP',
    'viewing_user_x' => 'Prezeranie užívateľa {x}', // Don't replace {x}
    'previous_punishments' => 'Predchádzajúce tresty',
    'no_previous_punishments' => 'Žiadne predchádzajúce tresty',
    'reset_avatar' => 'Resetovať avatar',
    'warning' => 'Varovanie',
    'ip_ban' => 'IP Ban',
    'reason' => 'Dôvod',
    'warn_user' => 'Varovať užívateľa',
    'ban_user' => 'Zabanovať užívateľa',
    'enter_valid_punishment_reason' => 'Zadajte platný dôvod vášho trestu v rozmedzí od 5 do 5 000 znakov.',
    'user_punished' => 'Užívateľ bol potrestaný.',
    'user_punished_alert' => '{x} potrestal/a užívateľa {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Odvolať',
    'revoked' => 'Odvolané',
    'acknowledged' => 'Uznávaný',
    'confirm_revoke_warning' => 'Naozaj chcete odvolať toto varovanie?',
    'confirm_revoke_ban' => 'Naozaj chcete zrušiť tento ban? Užívateľovi bude zrušený ban, aj keď má novší ban!',
    'punishment_revoked' => 'Trest zrušený.',
    'punishment_revoked_alert' => '{x} zrušil/a trest pre užívateľa {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'Nemôžete potrestať správcu!',
    'viewing_all_punishments' => 'Prezeranie všetkých trestov',
    'no_punishments_found' => 'Neboli nájdené žiadne tresty.',
    'view_user' => 'Zobraziť užívateľa',
    'when' => 'Kedy',
    'staff' => 'Admini',
    'type' => 'Typ',
    'recent_punishments' => 'Nedávne tresty',
    'created' => 'Vytvorené:',
    'staff:' => 'Admini:',
    'reason:' => 'Dôvod:',

    // Users
    'recent_registrations' => 'Nedávne registrácie',
    'reset_profile_banner' => 'Resetovať banner profilu'

);