<?php 
/*
 *	Made by Samerton, translated by Zemos, Renzotom and Ethxrnity
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Czech Language - Moderator terms
 */

$language = array(
	'mod_cp' => 'Ovládací panel moderátora',
	'staff_cp' => 'AdminPanel',
	'overview' => 'Přehled',
	
	// Spam
	'spam' => 'Spam',
	'mark_as_spam' => 'Označit jako spam',
	'confirm_spam' => '<p>Opravdu chcete označit tohohle uživatele za spam?</p><p>Uživatel bude zabanován a veškerý jeho obsah odebrán..</p>',
	'user_marked_as_spam' => 'Uživatel byl úpěšně označen za spam.',
	'cant_ban_admin' => 'Nemůžete zabanovat administrátora!',
	
	// IP Lookup
	'ip_lookup' => 'Vyhledávání podle IP',
	'search_for_ip' => 'Vyhledejte uživatelské jméno nebo adresu IP:',
	'no_ips_with_username' => 'Pro daného uživatele nebyly nalezeny žádné IP adresy.',
	'no_accounts_with_that_ip' => 'Pro danou IP adresu nebyl nalezen žádný uživatel.',
	'1_account_with_ip' => 'Nalezen 1 účet s IP {y}', // Don't replace "{y}"
	'count_accounts_with_ip' => 'Nalezl jsem {x} účtů pro IP {y}', // Don't replace "{x}" or "{y}"
	'1_ip_with_name' => 'Nalezena 1 IP adresa pro uživatele {y}', // Don't replace "{y}"
	'count_ips_with_name' => 'Nalezl jsem {x} IP adres  pro uživatele {y}', // Don't replace "{x}" or "{y}"
	'no_users_or_ips_found' => 'Žádný uživatel nebo IP adresa nenalezena.',
	
	// Reports
	'reports' => 'Nahlášení',
	'report_alert' => 'Nahlášení odesláno',
	'user_reported' => 'Nahlášený uživatel',
	'comments' => 'Komentář',
	'updated_by' => 'Aktualizováno',
	'actions' => 'Akce',
	'view_closed' => 'Zobrazit zavřené',
	'view_open' => 'Zobrazit otevřené',
	'viewing_report' => 'Zobrazení nahlášení',
	'view_content' => 'Zobrazit nahlášený příspěvek',
	'no_comments' => 'Žádné komentáře',
	'new_comment' => 'Nový komentář',
	'report_comment_invalid' => 'Nezadal jste komentář. Zajistěte, aby jste zadal komentář s minimálně 5 znaky.',
	'close_report' => 'Zavřít nahlášení',
	'reopen_report' => 'Znovu otevřít náhlášení',
	'1_open_report' => 'V současné době je otevřeno <strong> 1 </strong> nahlášení.',
	'open_reports' => 'V současné době je <strong>{x}</strong> otevřených nahlášení.', // Don't replace {x}
	'no_open_reports' => 'V současné době nejsou otevřené žádné nahlášení.',
	'no_closed_reports' => 'V současné době není uzavřeno žádné nahlášení.',
	'recent_reports' => 'Nedávná Nahlášení',
	'reported_by' => 'Nahlášen od:',
	'website' => 'Webová stránka',
	'ingame' => 'Ve hře',
	'x_closed_report' => '{x} uzavřel toto nahlášení.', // Don't replace {x} (username)
	'x_reopened_report' => '{x} toto nahlášení znovu otevřel.', // Don't replace {x} (username)
	'report_reopened' => 'Nahlášení bylo úspěšně znovu otevřeno.',
	'report_closed' => 'Nahlášení bylo úspěšně uzavřeno.',
	'comment_created' => 'Komentář byl úspěšně vytvořen.',
	
	// Punishments
	'punishments' => 'Tresty',
	'view_punishments' => 'Zobrazit tresty',
	'banned' => 'Zabanovaní',
	'groups' => 'Skupiny',
	'punish' => 'Trest',
	'ban' => 'Zakázání přístupu ke stránce',
	'warn' => 'Varování',
	'ban_ip' => 'Zakázání přístupu ke stránce na IP adresu',
	'viewing_user_x' => 'Prohlížení uživatele {x}', // Don't replace {x}
	'previous_punishments' => 'Předchozí tresty',
	'no_previous_punishments' => 'Žádné předchozí tresty.',
	'warning' => 'Varování',
	'ip_ban' => 'Zakázat přístup ke stránce na IP adresu',
	'reason' => 'Důvod',
	'warn_user' => 'Varovat uživatele',
	'ban_user' => 'Zakázat přístup ke stránce',
	'enter_valid_punishment_reason' => 'Zadejte prosím platný důvod Vašeho trestu, musí být mezi 5 a 5000 znaky.',
	'user_punished' => 'Uživatel potrestán.',
	'user_punished_alert' => 'Člen AT {x} potrestal uživatele {y}', // Don't replace {x} (staff member) or {y} (user punished)
	'revoke' => 'Odvolat trest',
	'revoked' => 'Trest odvolán',
	'acknowledged' => 'Uznáno.',
	'confirm_revoke_warning' => 'Jste si jist, že chcete odvolat toto varování?',
	'confirm_revoke_ban' => 'Jste si jist, že chcete odvolat zákaz přístupu ke stránce? Přístup ke stránce bude uživateli povolen, i když mají jiný zákaz k přístupu!',
	'punishment_revoked' => 'Trest odvolán.',
	'punishment_revoked_alert' => 'Člen AT {x} odvolal trest uživateli {y}', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
	'cant_punish_admin' => 'Nemůžete potrestat administrátora!',
    'viewing_all_punishments' => 'Prohlížení všech trestů',
    'no_punishments_found' => 'Žádné tresty nenalezeny.',
    'view_user' => 'Prohlédnout hráče',
    'when' => 'Kdy',
    'staff' => 'Člen AT',
    'type' => 'Typ',
    'recent_punishments' => 'Nedávné tresty',
    'created' => 'Vytvořeno:',
    'staff:' => 'Admin:',
    'reason:' => 'Důvod:',
	
    // Users
    'recent_registrations' => 'Nedávné registrace',
	'reset_profile_banner' => 'Resetovat Banner profilu'

);
