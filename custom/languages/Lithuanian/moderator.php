<?php
/*
 *  Made by Samerton
 *  Translation by Locus
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Lithuanian Language - Moderator terms
 */

$language = array(
    // General
    'mod_cp' => 'ModCP',
    'staff_cp' => 'StaffCP',
    'overview' => 'Apžvalga',

    // Spam
    'spam' => 'Šlamštas',
    'mark_as_spam' => 'Pažymėti kaip šlamštą',
    'confirm_spam' => '<p>Ar tikrai norite pažymėti šį vartotoją kaip šlamštą?</p><p>Vartotojui bus užblokuotas IP, o visas jo turinys bus pašalintas.</p>',
    'user_marked_as_spam' => 'Vartotojas sėkmingai pažymėtas kaip šlamštas.',
    'cant_ban_admin' => 'Jūs negalite užblokuoti administratoriaus!',

    // IP Lookup
    'ip_lookup' => 'IP paieška',
    'search_for_ip' => 'Ieškoti vartotojo vardo arba IP adreso:',
    'no_ips_with_username' => 'Nerasta jokių to adreso IP adresų.',
    'no_accounts_with_that_ip' => 'Neatsirado šio IP adreso paskyrų.',
    '1_account_with_ip' => 'Rasta 1 paskyra su IP {y}', // Don't replace "{y}"
    'count_accounts_with_ip' => 'Rasta {x} paskyrų su IP {y}', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => 'Rastas 1 IP adresas vartotojui {y}', // Don't replace "{y}"
    'count_ips_with_name' => 'Rasti {x} IP adresas(-ai) vartotojui {y}', // Don't replace "{x}" or "{y}"
    'no_users_or_ips_found' => 'Nerasta jokių vartotojų ar IP adresų.',

    // Reports
    'reports' => 'Ataskaitos',
    'report_alert' => 'Nauja ataskaita pateikta',
    'user_reported' => 'Vartotojas Praneštas',
    'comments' => 'Komentarai',
    'updated_by' => 'Atnaujino',
    'actions' => 'Veiksmai',
    'view_closed' => 'Peržiūrėjimas Uždarytas',
    'view_open' => 'Peržiūrėjimas Atidarytas',
    'viewing_report' => 'Peržiūrima Ataskaita',
    'view_content' => 'Peržiūrėkite pateiktą turinį',
    'no_comments' => 'Nėra komentarų',
    'new_comment' => 'Naujas komentaras',
    'report_comment_invalid' => 'Netinkamas komentarų turinys. Įsitikinkite, kad įrašėte komentarą nuo 1 iki 10000 simbolių.',
    'close_report' => 'Uždaryti ataskaitą',
    'reopen_report' => 'Atnaujinti ataskaitą',
    '1_open_report' => 'Dabar yra <strong>1</strong> atidaryta ataskaita.',
    'open_reports' => 'Dabar yra <strong>{x}</strong> atidarytų ataskaitų.', // Don't replace {x}
    'no_open_reports' => 'Šiuo metu nėra atvirų ataskaitų.',
    'no_closed_reports' => 'Šiuo metu nėra uždarytų ataskaitų.',
    'recent_reports' => 'Naujausios ataskaitos',
    'reported_by' => 'Pranešė:',
    'website' => 'Tinklalapis',
    'ingame' => 'Žaidime',
    'x_closed_report' => '{x} uždarė šią ataskaitą.', // Don't replace {x} (username)
    'x_reopened_report' => '{x} atnaujino šią ataskaitą.', // Don't replace {x} (username)
    'report_reopened' => 'Pranešimas sėkmingai atnaujintas.',
    'report_closed' => 'Ataskaita uždaryta sėkmingai.',
    'comment_created' => 'Komentaras sėkmingai sukurtas.',

    // Punishments
    'punishments' => 'Bausmės',
    'view_punishments' => 'Peržiūrėti Bausmes',
    'banned' => 'Užblokuotas',
    'groups' => 'Grupės',
    'punish' => 'Bausti',
    'ban' => 'Užblokuoti',
    'warn' => 'Įspėti',
    'ban_ip' => 'Užblokuoti IP',
    'viewing_user_x' => 'Peržiūrimas Vartotojas {x}', // Don't replace {x}
    'previous_punishments' => 'Buvusios Bausmės',
    'no_previous_punishments' => 'Nėra Buvusių Bausmių',
    'warning' => 'Įspėjimas',
    'ip_ban' => 'IP Užblokavimas',
    'reason' => 'Priežastis',
    'warn_user' => 'Įspėti Vartotoją',
    'ban_user' => 'Užblokuoti Vartotoją',
    'enter_valid_punishment_reason' => 'Prašau įvesti pagrįstą bausmės priežastį nuo 5 iki 5000 simbolių.',
    'user_punished' => 'Naudotojas nubaustas.',
    'user_punished_alert' => '{x} nubaudė vartotoją {y}', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => 'Atšaukti',
    'revoked' => 'Atšauktas',
    'acknowledged' => 'Pripažintas',
    'confirm_revoke_warning' => 'Ar tikrai norite atšaukti šį įspėjimą?',
    'confirm_revoke_ban' => 'Ar tikrai norite atšaukti šį vartotojo užblokavimą, vartotojas bus užblokotas, net jei jis turi naujesnį užblokavimą.',
    'punishment_revoked' => 'Bausmė atšaukta.',
    'punishment_revoked_alert' => '{x} pašalino bausmę vartotojui {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => 'Jūs negalite nubausti administratoriaus!',
  'viewing_all_punishments' => 'Peržiūrimos visos bausmės',
  'no_punishments_found' => 'Nerasta jokių bausmių.',
  'view_user' => 'Peržiūrėti Vartotoją',
  'when' => 'Kada',
  'staff' => 'Darbuotojas',
  'type' => 'Tipas',
  'recent_punishments' => 'Nesenos Bausmės',
  'created' => 'Sukurta:',
  'staff:' => 'Darbuotojas:',
  'reason:' => 'Priežastis:',

  // Users
  'recent_registrations' => 'Naujausios Registracijos',
  'reset_profile_banner' => 'Reset Profile Banner'
);
