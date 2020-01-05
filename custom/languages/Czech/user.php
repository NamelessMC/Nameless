<?php 
/*
  *	Made by Samerton, translated by Zemos, Renzotom, Ethxrnity and sad_mirai
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Czech Language - Users
 */

$language = array(
	/*
	 *  Change this for the account validation message
	 */
	'validate_account_command' => 'Chcete-li dokončit registraci, spusťte příkaz <strong> /validate {x} </strong> ve hře.', // Don't replace {x}

	/*
	 *  User Related
	 */
	'guest' => 'Host',
	'guests' => 'Hosté',
	
	// UserCP
	'user_cp' => 'Ovládací panel uživatele',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Přehled',
	'user_details' => 'Informace o uživateli',
	'profile_settings' => 'Nastavení profilu',
	'successfully_logged_out' => 'Byl jste úspěšně odhlášen.',
	'messaging' => 'Zprávy',
	'click_here_to_view' => 'Klikněte pro zobrazení.',
	'moderation' => 'Moderování',
	'administration' => 'Administrace',
	'alerts' => 'Upozornění',
	'delete_all' => 'Smazat vše',
	'private_profile' => 'Soukromý profil',
	
	// Profile settings
	'field_is_required' => '{x} je povinné.', // Don't replace {x}
	'settings_updated_successfully' => 'Nastavení bylo úspěšně aktualizováno.',
	'password_changed_successfully' => 'Změna hesla byla úspěšná.',
	'change_password' => 'Změnit heslo',
	'current_password' => 'Staré heslo',
	'new_password' => 'Nové heslo',
	'confirm_new_password' => 'Zopakujte nové heslo.',
	'incorrect_password' => 'Vaše heslo je nesprávné.',
	'two_factor_auth' => 'Dvoufázové ověření',
	'enabled' => 'Povoleno',
    'disabled' => 'Zakázáno',
	'enable' => 'Povolit',
	'disable' => 'Zakázat',
	'tfa_scan_code' => 'Prosím, naskenujte QR kód pomocí autentifikační aplikace:',
	'tfa_code' => 'Jestli-že nemáte kameru, nebo nemůžete naskenovat QR kód, prosím vložte kód níže:',
	'tfa_enter_code' => 'Prosím vložte kód pomocí autentifikační aplikace:',
	'invalid_tfa' => 'Špatný kód.',
	'tfa_successful' => 'Dvoufázové ověření bylo úspěšně nastaveno. Nyní budete pokaždé vyzván pro ověření přihlášení.',
	'active_language' => 'Aktivní jazyk',
	'active_template' => 'Active Template',
	'timezone' => 'Časová zóna',
	'upload_new_avatar' => 'Nahrát nového avatara.',
	'nickname_already_exists' => 'Zvolená přezdívka již existuje.',
	'change_email_address' => 'Změnit Email',
	'email_already_exists' => 'Email, který jste uvedl již existuje.',
	'email_changed_successfully' => 'Email úspěšně změněn.',
	'avatar' => 'Avatar',
	'profile_banner' => 'Banner',
	'upload_profile_banner' => 'Nahrát banner',
	'upload' => 'Nahrát',
	
	// Alerts
	'user_tag_info' => 'Byl jste označen v příspěvku {x}.', // Don't replace {x}
	'no_alerts' => 'Žádná nové upozornění.',
	'view_alerts' => 'Zobrazit upozornění',
	'1_new_alert' => 'Máte 1 nové upozornění',
	'x_new_alerts' => 'Nových upororněních: {x}', // Don't replace {x}
	'no_alerts_usercp' => 'Nemáte žádná upozornění.',
	
	// Registraton
	'registration_check_email' => 'Děkujeme za registraci! Prosím, zkontrolujte si email, pro ověření emailu. Pokud ho nemůžete najít, zkuste složku SPAM. Pokud není ani tam, kontaktujte administrátora.',
	'username' => 'Uživatelské jméno',
	'nickname' => 'Přezdívka',
	'minecraft_username' => 'Jméno v Minecraftu',
	'email_address' => 'E-mailová Adresa',
	'email' => 'E-mail',
	'password' => 'Heslo',
	'confirm_password' => 'Potvrďte heslo',
	'i_agree' => 'Souhlasím',
	'agree_t_and_c' => 'Kliknutím na <strong class="label label-primary">Registrovat</strong>, automaticky souhlasíte s našimi<a href="{x}" target="_blank"> pravidly a podmínkami</a>.',
	'create_an_account' => 'Vytvořit účet',
	'terms_and_conditions' => 'Pravidla a podmínky',
	'validation_complete' => 'Váš účet byl ověřen. Můžete se přihlásit :)',
	'validation_error' => 'Vznikl problém při ověřování účtu, prosím kontaktujte administrátora webu.',
	'signature' => 'Podpis',
	'signature_max_900' => 'Zajistěte, aby Váš popis byl maximálně 900 znaků dlouhý.',

	// Registration - Authme
	'connect_with_authme' => 'Propojte Váš účet s AuthMe',
	'authme_help' => 'Zadejte prosím Vaše herní AuthMe detaily účtu. Pokud ještě nemáte účet ve hře, připojte se na server nyní a postupujte podle uvedených pokynů.',
	'unable_to_connect_to_authme_db' => 'Nepodařilo se připojit do AuthMe databáze. Pokud tato chyba přetrvává, obraťte se prosím na správce.',
	'authme_account_linked' => 'Účet úspěšně propojen.',
	'authme_email_help_1' => 'Nakonec prosím zadejte svou e-mailovou adresu.',
	'authme_email_help_2' => 'Nakonec prosím zadejte svou e-mailovou adresu a také si vyberte zobrazované jméno pro Váš účet.',

	// Registration errors
	'username_required' => 'Je vyžadována přezdívka.',
	'email_required' => 'Je vyžadován e-mail.',
	'password_required' => 'Je vyžadováno heslo.',
	'mcname_required' => 'Je vyžadováno Minecraft jméno.',
	'accept_terms' => 'Musíte souhlasit s našimi pravidly a podmínkami.',
	'username_minimum_3' => 'Vaše přezdívka musí mít minimálně 3 znaky.',
	'mcname_minimum_3' => 'Vaše Minecraft jméno musí mít minimálně 3 znaky.',
	'password_minimum_6' => 'Vaše heslo musí obsahovat minimálně 6 znaků.',
	'username_maximum_20' => 'Vaše přezdívka musí mít maximálně 20 znaků.',
	'mcname_maximum_20' => 'Vaše Minecraft jméno musí mít maximálně 20 znaků.',
	'password_maximum_30' => 'Vaše heslo musí maximálně obsahovat 30 znaků.',
	'passwords_dont_match' => 'Vaše hesla se neshodují!',
	'username_mcname_email_exists' => 'Vaše přezdívka nebo e-mail jsou již zaregistrovány.',
	'invalid_mcname' => 'Vaše Minecraft jméno je špatně zadané.',
	'invalid_email' => 'Váš email je špatný.',
	'mcname_lookup_error' => 'Vznikl problém při ověřování jména přes Mojang servery. Zkuste to prosím později.',
	'invalid_recaptcha' => 'Špatná reCAPTCHA.',
	'verify_account' => 'Ověřit účet',
	'verify_account_help' => 'Postupujte podle pokynů níže, abychom mohli ověřit, zda je tento Minecraft účet opravdu Váš.',
	'validate_account' => 'Validate Account',
	'verification_failed' => 'Ověření selhalo, zkuste to prosím později.',
	'verification_success' => 'Ověření úspěšné! Nyní se můžete přihlásit.',
	'authme_username_exists' => 'Váš AuthMe účet byl právě připojen k webu!',
	'uuid_already_exists' => 'Vaše UUID již existuje. To znamená, že tento Minecraft účet je již registrován.',
	
	// Login
	'successful_login' => 'Úspěšně jste se přihlásil.',
	'incorrect_details' => 'Zadal jste špatné údaje.',
	'inactive_account' => 'Váš účet je deaktivován. Koukněte se na email pro získání ověřovacího odkazu.',
	'account_banned' => 'Tento účet je zabanován.',
	'forgot_password' => 'Zapomenuté heslo?',
	'remember_me' => 'Pamatovat si mě',
	'must_input_email' => 'Musíte vložit email.',
	'must_input_username' => 'Musíte vložit uživatelské jméno.',
	'must_input_password' => 'Musíte vložit heslo.',

	// Forgot password
	'forgot_password_instructions' => 'Zadejte prosím svou e-mailovou adresu, abychom Vám mohli zaslat další pokyny pro obnovení hesla.',
	'forgot_password_email_sent' => 'Pokud existuje účet s touto e-mailovou adresou, tak Vám právě byl odeslán e-mail s dalšími pokyny. Pokud jej nemůžete najít, zkuste zkontrolovat složku nevyžádané pošty (např. spam).',
	'unable_to_send_forgot_password_email' => 'Nepodařilo se odeslat e-mail se zapomenutým heslem. Kontaktujte prosím administrátora.',
	'enter_new_password' => 'Potvrďte prosím svou e-mailovou adresu a zadejte níže nové heslo.',
	'incorrect_email' => 'Vámi zadaná e-mailová adresa neodpovídá požadavku.',
	'forgot_password_change_successful' => 'Vaše zapomenuté heslo bylo úspěšně změněno. Nyní se můžete přihlásit.',
	
	// Profile pages
	'profile' => 'Profil',
	'follow' => 'Sledovat',
	'no_wall_posts' => 'Zde nejsou žádné příspevky.',
	'change_banner' => 'Změnit Banner',
	'post_on_wall' => 'Vložit komentář na zeď hráče {x}', // Don't replace {x}
	'invalid_wall_post' => 'Zajistěte, aby Váš příspěvek byl dlouhý mezi 1 a 10000 znaky.',
	'1_reaction' => '1 reakce',
	'x_reactions' => '{x} reakce', // Don't replace {x}
	'1_like' => '1 To se mi líbí',
	'x_likes' => '{x} To se mi líbí', // Don't replace {x}
	'1_reply' => '1 odpověď',
	'x_replies' => '{x} odpovědi', // Don't replace {x}
	'no_replies_yet' => 'Nemáte žádné odpovědi',
	'feed' => 'Komentáře',
	'about' => 'O mně',
	'reactions' => 'Reakce',
	'replies' => 'Odpovědi',
	'new_reply' => 'Nová odpověď',
	'registered' => 'Registrován:',
	'registered_x' => 'Registrován: {x}',
	'last_seen' => 'Poslední přihlášení:',
	'last_seen_x' => 'Poslední přihlášení: {x}', // Don't replace {x}
	'new_wall_post' => 'Hráč {x} napsal na Váš profil.',
	'couldnt_find_that_user' => 'Nepodařilo se najít tohoto uživatele.',
	'block_user' => 'Zablokovat uživatele',
	'unblock_user' => 'Odblokovat uživatele',
	'confirm_block_user' => 'Jste si jist, že chcete zablokovat tohoto uživatele? Blokovaní uživatelé Vám nebudou moci posílat soukromé zprávy nebo Vás označovat v příspěvcích.',
	'confirm_unblock_user' => 'Jste si jist, že chcete odblokovat tohoto uživatele? Odblkovaní uživatelé Vám budou moci posílat soukromé zprávy a označovat Vás v příspěvcích.',
	'user_blocked' => 'Uživatel zablokován.',
	'user_unblocked' => 'Uživatel odblokován.',
	'views' => 'Zobrazení profilu:',
	'private_profile_page' => 'Toto je soukromý profil!',
	'new_wall_post_reply' => '{x} odpověděl na váš příspěvek v profilu {y}.', // Don't replace {x} or {y}
	'new_wall_post_reply_your_profile' => '{x} odpověděl na váš příspěvek na vašem profilu.', // Don't replace {x}
	'no_about_fields' => 'Tento uživatel o sobě ještě nepřidal žádné údaje.',
	'reply' => 'Odpověď',
	
	// Reports
	'invalid_report_content' => 'Nelze vytvořit nahlášení. Musíte zadat důvod v rozmezí 2 až 1024 znaků.',
	'report_post_content' => 'Zadejte, prosím důvod nahlášení.',
	'report_created' => 'Nahlášení úspěšně vytvořeno.',
	
	// Messaging
	'no_messages' => 'Žádné nové zprávy.',
	'no_messages_full' => 'Nemáte žádné zprávy.',
	'view_messages' => 'Zobrazit zprávy',
	'1_new_message' => 'Máte 1 novou zprávu',
	'x_new_messages' => 'Nových zpráv: {x}', // Don't replace {x}
	'new_message' => 'Nová zpráva',
	'message_title' => 'Předmět',
	'to' => 'Pro',
	'separate_users_with_commas' => 'Oddělujte více uživatelů pomocí čárky.',
	'title_required' => 'Musíte zadat předmět',
	'content_required' => 'Musíte vložit nějaký obsah',
	'users_to_required' => 'Musíte zvolit aspoň jednoho příjemce',
	'cant_send_to_self' => 'Nemůžete poslat zprávu sám sobě!',
	'title_min_2' => 'Předmět musí mít mínimálně 2 znaky.',
	'content_min_2' => 'Zpráva musí obsahovat minimálně 5 znaků.',
	'title_max_64' => 'Předmět může mít maximálně 64 znaků.',
	'content_max_20480' => 'Zpráva může obsahovat maximálně 20480 znaků.',
	'max_pm_10_users' => 'Můžete poslat zprávu maximálně 20 uživatelům najednou.',
	'message_sent_successfully' => 'Zpráva úspěšně odeslána.',
	'participants' => 'Učastníci',
	'last_message' => 'Poslední zpráva',
	'by' => 'Od',
	'new_reply' => 'Nová odpověď',
	'leave_conversation' => 'Opustit konverzaci',
	'confirm_leave' => 'Opravdu chcete opustit konverzaci?',
	'one_or_more_users_blocked' => 'Alespoň jednomu členovi konverzace nemůžete odeslat soukromé zprávy.',
	'messages' => 'Zprávy',
	
	/*
	 *  Infractions area
	 */
	'you_have_been_banned' => 'Byl Vám zakázán přístup k této stránce!',
	'you_have_received_a_warning' => 'Dostal jste varování!',
	'acknowledge' => 'Uznat',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Zdravím,',
	'email_message' => 'Děkujeme za registraci na našem webu! Pro dokončení registrace klikněte na odkaz níže:',
	'forgot_password_email_message' => 'Pro obnovení Vašeho zapomenutého hesla klikněte prosím na následující odkaz. Pokud jste o obnovení zapomenutého hesla nepožádal sám, můžete e-mail bezpečně smazat.',
	'email_thanks' => 'Přejeme příjemné hraní,',

	/*
	 *  Hooks
	 */
	'user_x_has_registered' => '{x} se přidal na ' . SITE_NAME . '!'
);
