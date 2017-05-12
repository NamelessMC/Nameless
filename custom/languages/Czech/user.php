<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  EnglishUK Language - Users
 */

$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Host',
	'guests' => 'Hosté',
	
	// UserCP
	'user_cp' => 'UserCP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Přehled',
	'user_details' => 'Informace o uživateli',
	'profile_settings' => 'Nastavení profilu',
	'successfully_logged_out' => 'Byl jsi úspěšně odhlášen.',
	'messaging' => 'Zprávy',
	'click_here_to_view' => 'Klikni pro zobrazení.',
	'moderation' => 'Moderation',
	'administration' => 'Administrace',
	'alerts' => 'Upozornění',
	'delete_all' => 'Smazat vše',
	
	// Profile settings
	'field_is_required' => '{x} je .', // Don't replace {x}
	'settings_updated_successfully' => 'Nastavení bylo úspěšné.',
	'password_changed_successfully' => 'Změna hesla byla úspěšná.',
	'change_password' => 'Změnit heslo',
	'current_password' => 'Staré heslo',
	'new_password' => 'Nové heslo',
	'confirm_new_password' => 'Zopakuj nové heslo',
	'incorrect_password' => 'Tvoje heslo je nesprávné.',
	'two_factor_auth' => 'Dvoufázové ověření',
	'enable' => 'Povlit',
	'disable' => 'Zakázat',
	'tfa_scan_code' => 'Prosím, naskenuj QR kéd pomocí autentifikační aplikace:',
	'tfa_code' => 'Jestli-že nemáš kameru, nebo nemůžeš naskenovat QR kód, prosím vlož kód níže:',
	'tfa_enter_code' => 'Prosím vlož kód pomocí autentifikační aplikace:',
	'invalid_tfa' => 'Špatný kód.',
	'tfa_successful' => 'Dvoufázové ověření bylo úspěšně nastaveno.Nyní budeš pokaždé vyzván pro ověření přihlášení.',
	'active_language' => 'Aktivní jazyk',
	
	// Alerts
	'user_tag_info' => 'Byl jsi označen v příspěvku {x}.', // Don't replace {x}
	'no_alerts' => 'Žádné nové upozornění',
	'view_alerts' => 'Zobrazit upozornění',
	'x_new_alerts' => 'Nových upororněních: {x}', // Don't replace {x}
	'no_alerts_usercp' => 'Nemáš žádné upozornění.',
	
	// Registraton
	'registration_check_email' => 'Děkujeme za registraci! Prosím, zkontrolujte si email, pro ověření emailu. Pokud ho nemůžete najít, zkuste složku SPAM. Pokud není ani tam, kontaktujte administrátora.',
	'username' => 'Přezdívka',
	'nickname' => 'Nick',
	'minecraft_username' => 'Minecraft Username',
	'email_address' => 'Email Adresa',
	'email' => 'Email',
	'password' => 'Heslo',
	'confirm_password' => 'Potvrď heslo',
	'i_agree' => 'Souhlasím',
	'agree_t_and_c' => 'Kliknutím na <strong class="label label-primary">Registrovat</strong>, automaticky souhlasíš s našimi<a href="{x}" target="_blank"> Podmínkami</a>.',
	'create_an_account' => 'Vytvořit účet',
	'terms_and_conditions' => 'Podmínky',
	'validation_complete' => 'Tvůj účet byl ověřen. Můžeš se přihlásit :)',
	'validation_error' => 'Vznikl problém při ověřování účtu, prosím kontaktuj administrátora webu.',
	'signature' => 'Registrovat',
	
	// Registration errors
	'username_required' => 'Je vyžadována přezdívka.',
	'email_required' => 'Je vyžadován email.',
	'password_required' => 'Je vyžadováno heslo.',
	'mcname_required' => 'Je vyžadováno Minecraft jméno.',
	'accept_terms' => 'Musíš souhlasit s našimi Podmínkami.',
	'username_minimum_3' => 'Tvoje přezdívka musí mít minimálně 3 znaky.',
	'mcname_minimum_3' => 'Tvoje Minecraft jméno musí mít minimálně 3 znaky.',
	'password_minimum_6' => 'Tvoje heslo musí obsahovat minimálně 6 znaků.',
	'username_maximum_20' => 'Tvoje přezdívka musí mít maximálně 20 znaků.',
	'mcname_maximum_20' => 'Tvoje Minecraft jméno musí mít maximálně 20 znaků.',
	'password_maximum_30' => 'Tvoje heslo musí maximálně obsahovat 30 znaků.',
	'passwords_dont_match' => 'Tvoje hesla se neshodují',
	'username_mcname_email_exists' => 'Tvoje přezdívka nebo email jsou již zaregistrovány.',
	'invalid_mcname' => 'Tvoje Minecraft jméno je špatně zadané.',
	'invalid_email' => 'Tvůj email je špatný.',
	'mcname_lookup_error' => 'Vznikl problém při ověřování jména přes Mojang servery. Zkus to prosím později.',
	'invalid_recaptcha' => 'Špatná reCAPTCHA.',
	'verify_account' => 'Ověřit účet',
	'verify_account_help' => 'Postupuj podle pokynů níže, abychom mohli ověřit, že Minecraft účet je opravdu tvůj.',
	'verification_failed' => 'Ověření selhalo, zkus to prosím později.',
	'verification_success' => 'Ověření úspěšné! Nyní se můžeš přihlásit.',
	
	// Login
	'successful_login' => 'Úspěšně si se přihlásil.',
	'incorrect_details' => 'Zadal jsi špatné údaje.',
	'inactive_account' => 'Tvůj účet je deaktivován. Koukni na email pro získání ověřovacího linku.',
	'account_banned' => 'Tento účet je zabanován.',
	'forgot_password' => 'Zapomenuté heslo?',
	'remember_me' => 'Zapamatovat si',
	'must_input_username' => 'Musíš vložit přezdívku.',
	'must_input_password' => 'Musíš vložit heslo.',
	
	// Profile pages
	'profile' => 'Profil',
	'follow' => 'Sledovat',
	'no_wall_posts' => 'Zde nejsou žádné příspevky.',
	'change_banner' => 'Změnit Banner',
	'post_on_wall' => 'Vložit komentář na {x}ovu zeď', // Don't replace {x}
	'invalid_wall_post' => 'Please ensure your post is between 1 and 10000 characters.',
	'1_reaction' => '1 reakce',
	'x_reactions' => '{x} reakce', // Don't replace {x}
	'1_like' => '1 like',
	'x_likes' => '{x} likes', // Don't replace {x}
	'1_reply' => '1 odpověď',
	'x_replies' => '{x} odpovědi', // Don't replace {x}
	'no_replies_yet' => 'Nemáš žádné odpovědi :(',
	'feed' => 'Komentáře',
	'about' => 'O mně',
	'reactions' => 'Reakce',
	'replies' => 'Odpovědi',
	'new_reply' => 'Nová odpověď',
	'registered' => 'Registrován:',
	'last_seen' => 'Poslední přihlášení:',
	
	// Reports
	'invalid_report_content' => 'Nelze vytvořit nahlášení. Musíš zadat důvod v rozmezí 2 až 1024 znaků.',
	'report_post_content' => 'Zadej, prosím, důvod nahlášení.',
	'report_created' => 'Nahlášení úspěšně vytvořeno.',
	
	// Messaging
	'no_messages' => 'Žádné nové zprávy',
	'no_messages_full' => 'Nemáš žádné zprávy.',
	'view_messages' => 'Zobrazit zprávy',
	'x_new_messages' => 'Nových zpráv: {x}', // Don't replace {x}
	'new_message' => 'Nová zpráva',
	'message_title' => 'Předmět',
	'to' => 'Pro',
	'separate_users_with_commas' => 'Odděluj více uživatelů pomocí čárky',
	'title_required' => 'Musíš zadat předmět',
	'content_required' => 'Musíš vložit nějaký obsah',
	'users_to_required' => 'Musíš zvolit aspoň jednoho příjemce',
	'cant_send_to_self' => 'Nemůžeš poslat zprávu sám sobě!',
	'title_min_2' => 'Předmět musí mít mínimálně 2 znaky',
	'content_min_2' => 'Zpráva musí obsahovat minimálně 5 znaků',
	'title_max_64' => 'Předmět může mít maximálně 64 znaků',
	'content_max_20480' => 'Zpráva může obsahovat maximálně 20480 znaků.',
	'max_pm_10_users' => 'Můžeš poslat zprávu maximálně 20 uživatelům najednou.',
	'message_sent_successfully' => 'Zpráva úspěšně odeslána.',
	'participants' => 'Učastníci',
	'last_message' => 'Poslední zpráva',
	'by' => 'pro',
	'new_reply' => 'Nová odpověď',
	'leave_conversation' => 'Opustit konverzaci',
	'confirm_leave' => 'Opravdu chceš opustit konverzaci?',
	
	// Reactions
	'reactions' => 'Reakce',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Porušení',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Zdravím,',
	'email_message' => 'Děkujeme za registraci na našem webu! Pro dokončení registrace klikni na odkaz níže:',
	'email_thanks' => 'Přejeme příjemné hraní,'
);