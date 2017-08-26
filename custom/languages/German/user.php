<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  German Language - Users
 */

/*
 *	Translation by BukkitTNT
 *
 *  http://BukkitTNT.de
 *  http://twitter.com/BukkitTNT
 *
 */

$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Gast',
	'guests' => 'Gäste',
	
	// UserCP
	'user_cp' => 'UserCP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Übersicht',
	'user_details' => 'Benutzer Details',
	'profile_settings' => 'Profil Einstellungen',
	'successfully_logged_out' => 'Du hast dich erfolgreich abgemeldet.',
	'messaging' => 'Nachrichten',
	'click_here_to_view' => 'Klicke hier um es dir anzusehen.',
	'moderation' => 'Moderation',
	'administration' => 'Administration',
	'alerts' => 'Benachrichtigungen',
	'delete_all' => 'Alle Löschen',
	
	// Profile settings
	'field_is_required' => '{x} wird benötigt.', // Don't replace {x}
	'settings_updated_successfully' => 'Einstellungen wurden erfolgreich geändert.',
	'password_changed_successfully' => 'Passwort wurde erfolgreich geändert.',
	'change_password' => 'Passwort ändern',
	'current_password' => 'Aktuelles Passwort',
	'new_password' => 'Neues Passwort',
	'confirm_new_password' => 'Neues Passwort bestätigen',
	'incorrect_password' => 'Dein Passwort ist falsch.',
	'two_factor_auth' => '2 Schritt Authentifizierung',
	'enable' => 'Aktivieren',
	'disable' => 'Deaktivieren',
	'tfa_scan_code' => 'Scanne folgenden Code mit der APP:',
	'tfa_code' => 'Wenn die APP dies nicht unterstützt gib diesen Zahlen-Code ein:',
	'tfa_enter_code' => 'Gib den Code ein, welcher in der APP angezeigt wird:',
	'invalid_tfa' => 'Ungültiger Code, versuche es erneut.',
	'tfa_successful' => '2 Schritt Authentifizierung wurde aktiviert.',
	'active_language' => 'Ausgewählte Sprache:',
    'timezone' => 'Timezone',
    'upload_new_avatar' => 'Upload a new avatar',
	
	// Alerts
	'user_tag_info' => 'Du wurdest in einem Beitrag von {x} erwähnt.', // Don't replace {x}
	'no_alerts' => 'Keine Benachrichtigungen',
	'view_alerts' => 'Benachrichtigungen ansehen',
	'x_new_alerts' => 'Du hast {x} neue Benachrichtigungen', // Don't replace {x}
	'no_alerts_usercp' => 'Du hast keine Benachrichtigungen.',
	
	// Registraton
	'registration_check_email' => 'Danke für die Registrierung. Schalte nun bitte deine Email frei!',
	'username' => 'Benutzername',
	'nickname' => 'Nickname',
	'minecraft_username' => 'Minecraft Benutzername',
	'email_address' => 'Email Addresse',
	'email' => 'Email',
	'password' => 'Passwort',
	'confirm_password' => 'Passwort wiederholen',
	'i_agree' => 'I Agree',
	'agree_t_and_c' => 'Wenn du auf <strong class="label label-primary">Register</strong> drückst stimmst du unseren <a href="{x}" target="_blank">Geschäftsbedingungen</a>.',
	'create_an_account' => 'Erstelle einen Account',
	'terms_and_conditions' => 'Geschäftsbedingungen',
	'validation_complete' => 'Dein Account wurde verifiziert. Du kannst dich nun anmelden.',
	'validation_error' => 'Mit deinem Account stimmt etwas nicht. Benachrichtige einen Administrator.',
	'signature' => 'Signatur',

    // Registration - Authme
    'connect_with_authme' => 'Connect your account with AuthMe',
    'authme_help' => 'Please enter your ingame AuthMe account details. If you don\'t already have an account ingame, join the server now and follow the instructions provided.',
    'unable_to_connect_to_authme_db' => 'Unable to connect to the AuthMe database. If this error persists, please contact an administrator.',
    'authme_account_linked' => 'Account linked successfully.',
    'authme_email_help_1' => 'Finally, please enter your email address.',
    'authme_email_help_2' => 'Finally, please enter your email address, and also choose a display name for your account.',

	// Registration errors
    'username_required' => 'Bitte gebe einen Nutzernamen an.',
    'password_required' => 'Bitte gebe ein Passwort an.',
    'email_required' => 'Bitte gebe eine gültige E-Mail-Adresse an.',
    'mcname_required' => 'Bitte gebe einen Minecraft Namen an.',
    'accept_terms' => 'Du musst unsere AGBs akzeptieren, um dich zu registrieren.',
    'username_minimum_3' => 'Dein Nutzername muss mindestens 3 Zeichen lang sein.',
    'username_maximum_20' => 'Dein Nutzername darf nicht länger als 20 Zeichen sein.',
    'mcname_minimum_3' => 'Dein Minecraftname muss mindestens 3 Zeichen lang sein.',
    'mcname_maximum_20' => 'Dein Minecraftname darf nicht länger als 20 Zeichen sein..',
    'password_minimum_6' => 'Dein Passwort muss mindestens 6 Zeichen haben..',
    'password_maximum_30' => 'Dein Passwort darf nicht länger als 30 Zeichen sein.',
    'passwords_dont_match' => 'Deine Passwörter stimmen nicht überein.',
    'username_mcname_email_exists' => 'Dein Nutzername, Minecraftname oder deine E-Mail-Adresse sind bereits registriert. Hast du schon ein Konto eingerichtet?',
    'invalid_mcname' => 'Dein Minecraft Benutzername ist ungültig.',
	'invalid_email' => 'Deine EmailAdresse ist ungültig.',
	'mcname_lookup_error' => 'Fehler beim abrufen des MC-Namens.',
	'invalid_recaptcha' => 'Ungültige reCAPTCHA antwort.',
    'verify_account' => 'Account verifizieren',
    'verify_account_help' => 'Bitte führe die angegeben Schritte durch um deinen Account zu verifizieren.',
    'verification_failed' => 'Verifikation fehlgeschlagen.',
    'verification_success' => 'Erfolgreich freigeschaltet.',
    'authme_username_exists' => 'Your Authme account has already been connected to the website!',
	
	// Login
	'successful_login' => 'Du hast dich erfolgreich angemeldet.',
	'incorrect_details' => 'Du hast falsche Daten eingegeben.',
	'inactive_account' => 'Dein Account wurde noch nicht freigeschaltet.',
	'account_banned' => 'Dieser Account wurde gebannt.',
	'forgot_password' => 'Passwort vergessen?',
	'remember_me' => 'Angemeldet bleiben.',
	'must_input_username' => 'Du musst einen Benutzernamen eingeben.',
	'must_input_password' => 'Du musst ein Passwort eingeben.',

    // Forgot password
    'forgot_password_instructions' => 'Please enter your email address so we can send you further instructions on resetting your password.',
    'forgot_password_email_sent' => 'If an account with the email address exists, an email has been sent containing further instructions. If you can\'t find it, try checking your junk folder.',
    'unable_to_send_forgot_password_email' => 'Unable to send forgot password email. Please contact an administrator.',
    'enter_new_password' => 'Please confirm your email address and enter a new password below.',
    'incorrect_email' => 'The email address you have entered does not match the request.',
    'forgot_password_change_successful' => 'Your password has been changed successfully. You can now log in.',
	
	// Profile pages
	'profile' => 'Profil',
	'follow' => 'Folgen',
	'no_wall_posts' => 'Es existieren noch keine Profil-Nachrichten.',
	'change_banner' => 'Banner ändern',
	'post_on_wall' => 'Nachricht auf {x}\'s Profilseite posten.', // Don't replace {x}
	'invalid_wall_post' => 'Die Nachricht darf nicht länger als 10.000 Zeichen sein.',
	'1_reaction' => '1 Reaktion',
	'x_reactions' => '{x} Reaktionen', // Don't replace {x}
	'1_like' => '1 Like',
	'x_likes' => '{x} Likes', // Don't replace {x}
	'1_reply' => '1 Antwort',
	'x_replies' => '{x} Antworten', // Don't replace {x}
	'no_replies_yet' => 'Bisher keine Antworten',
	'feed' => 'Feed',
	'about' => 'Über',
	'reactions' => 'Reaktioenen',
	'replies' => 'Antwoeren',
	'new_reply' => 'Neue Antwort',
	'registered' => 'Registriert seit:',
	'last_seen' => 'Zuletzt gesehen:',
	'new_wall_post' => '{x} has posted on your profile.',
	'couldnt_find_that_user' => 'Couldn\'t find that user.',
	'block_user' => 'Block User',
	'unblock_user' => 'Unblock User',
	'confirm_block_user' => 'Are you sure you want to block this user? They will not be able to send you private messages or tag you in posts.',
	'confirm_unblock_user' => 'Are you sure you want to unblock this user? They will be able to send you private messages and tag you in posts.',
	'user_blocked' => 'User blocked.',
	'user_unblocked' => 'User unblocked.',
	
	// Reports
	'invalid_report_content' => 'Eine Meldung muss zwischen 2 & 1024 Zeichen lang sein.',
	'report_post_content' => 'Gib bitte einen Grund an.',
	'report_created' => 'Die Meldung wurde erfolgreich erstellt.',
	
	// Messaging
	'no_messages' => 'Keine neuen Nachrichten',
	'no_messages_full' => 'Du hast keine neuen Nachrichten.',
	'view_messages' => 'Nachrichten ansehen',
	'x_new_messages' => 'Du hast {x} neue Nachrichten', // Don't replace {x}
	'new_message' => 'Neue Nachricht',
	'message_title' => 'Nachrichten Titel',
	'to' => 'An',
	'separate_users_with_commas' => 'Gib mehrere Benutzer mit Kommata an.',
	'title_required' => 'Gib bitte einen Titel an',
	'content_required' => 'Beschreibe bitte den Inhalt',
	'users_to_required' => 'Bitte gib einige Empfänger ein',
	'cant_send_to_self' => 'Du kannst keine Nachricht an dich selbst senden!',
	'title_min_2' => 'Der Titel muss mindestens 2 Zeichen lang sein.',
	'content_min_2' => 'Der Inhalt muss mindestens 2 Zeichen lang sein.',
	'title_max_64' => 'The title must be a maximum of 64 characters',
	'content_max_20480' => 'Der Inhalt darf maximal 20480 Zeichen lang sein.',
	'max_pm_10_users' => 'Du kannst an max. 10 Nutzer eine Nachricht senden.',
	'message_sent_successfully' => 'Nachricht wurde erfolgreich gesendet.',
	'participants' => 'Teilnehmer',
	'last_message' => 'Letzte Nachricht',
	'by' => 'von',
	'leave_conversation' => 'Konversation verlassen.',
	'confirm_leave' => 'Möchtest du die Konversation wirklich verlassen?',
	'one_or_more_users_blocked' => 'You cannot send private messages to at least one member of the conversation.',
	
	// Reactions
	'reactions' => 'Reaktionen',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Straftaten',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Hey,',
	'email_message' => 'Danke für deine Registration! Um deine Registration abzuschließen klicke auf folgenden Link:',
    'forgot_password_email_message' => 'To reset your password, please click the following link. If you did not request this yourself, you can safely delete this email.',
	'email_thanks' => 'Liebe Grüße,'
);
