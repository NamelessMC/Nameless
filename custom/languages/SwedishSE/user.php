<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Translated by IsS127
 *  SwedishSE Language - User
 */

$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Gäst',
	'guests' => 'Gäster',
	
	// UserCP
	'user_cp' => 'UserKP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Översikt',
	'user_details' => 'Användaruppgifter',
	'profile_settings' => 'Profil Inställningar',
	'successfully_logged_out' => 'Du har blivit utloggad!',
	'messaging' => 'Meddelande',
	'click_here_to_view' => 'Klicka här för att visa.',
	'moderation' => 'Moderation',
	'administration' => 'Administration',
	'alerts' => 'Alerts',
	'delete_all' => 'Radera Alla',
	
	// Profile settings
	'field_is_required' => '{x} krävs', // Don't replace {x}
	'settings_updated_successfully' => 'Inställningarna uppdateras!',
	'password_changed_successfully' => 'Lösenordet har ändrats!',
	'change_password' => 'Byt Lösenord',
	'current_password' => 'Nurvarande Lösenord',
	'new_password' => 'Ny Lösenord',
	'confirm_new_password' => 'Bekräfta ditt nya lösenord',
	'incorrect_password' => 'Ditt lösenord är fel!',
	'two_factor_auth' => 'Two Factor Authentication',
	'enable' => 'Aktivera',
	'disable' => 'Inaktivera',
	'tfa_scan_code' => 'Vänligen skanna följande kod i din autentiserings app:',
	'tfa_code' => 'Om din enhet inte har en kamera eller om du inte kan skanna QR-koden, ange följande kod:',
	'tfa_enter_code' => 'Ange koden som visas i din autentiseringsapp:',
	'invalid_tfa' => 'Ogiltig kod, försök igen.',
	'tfa_successful' => 'Two factor authentication har upprättats. Du måste autentisera varje gång du loggar in från och med nu.',
	'active_language' => 'Aktiv Språk',
	'timezone' => 'Tidzon',
	'upload_new_avatar' => 'Ladda upp en ny avatar',
	
	// Alerts
	'user_tag_info' => 'Du har blivit taggat i ett inlägg av {x}.', // Don't replace {x}
	'no_alerts' => 'Inga nya alerter.',
	'view_alerts' => 'Visa alerter',
	'x_new_alerts' => 'Du har {x} nya alerter', // Don't replace {x}
	'no_alerts_usercp' => 'Du har inga nya alerter.',
	
	// Registraton
	'registration_check_email' => 'Tack för att du registrerade! Vänligen kolla i din e-post för en valideringslänk för att slutföra din registrering. Om du inte hittar e-postmeddelandet, kolla i din skräppostmapp.',
	'username' => 'Användarnamn',
	'nickname' => 'Smeknamn',
	'minecraft_username' => 'Minecraft Användarnamn',
	'email_address' => 'E-postadress',
	'email' => 'E-post',
	'password' => 'Lösenord',
	'confirm_password' => 'Bekräfta Lösenord',
	'i_agree' => 'Jag accepterar.',
	'agree_t_and_c' => 'Genom att klicka på <strong class="label label-primary">Registrera</strong> godkänner du våra <a href="{x}" target="_blank">användarvillkor</a>.',
	'create_an_account' => 'Skapa ett konto',
	'terms_and_conditions' => 'AnvändarVillkor',
	'validation_complete' => 'Ditt konto har validerats, du kan nu logga in.',
	'validation_error' => 'Det fanns ett okänt fel medans vi validerade ditt konto, var god kontakta en webbplats administratör.',
	'signature' => 'Signatur',

	// Registration - Authme
    'connect_with_authme' => 'Anslut ditt konto med AuthMe',
	'authme_help' => 'Please enter your ingame AuthMe account details. If you don\'t already have an account ingame, join the server now and follow the instructions provided.',
	'unable_to_connect_to_authme_db' => 'Unable to connect to the AuthMe database. If this error persists, please contact an administrator.',
	'authme_account_linked' => 'Account linked successfully.',
	'authme_email_help_1' => 'Slutligen, ange en e-postadress',
	'authme_email_help_2' => 'Slutligen, ange en e-postadress och ange ett smeknamn',
	
	// Registration errors
	'username_required' => 'Ett användarnamn krävs.',
	'email_required' => 'En e-postadress krävs.',
	'password_required' => 'Ett lösenord krävs.',
	'mcname_required' => 'A Minecraft username is required.',
	'accept_terms' => 'You must accept the terms and conditions before registering.',
	'username_minimum_3' => 'Your username must be a minimum of 3 characters.',
	'mcname_minimum_3' => 'Your Minecraft username must be a minimum of 3 characters.',
	'password_minimum_6' => 'Your password must be a minimum of 6 characters.',
	'username_maximum_20' => 'Your username must be a maximum of 20 characters.',
	'mcname_maximum_20' => 'Your Minecraft username must be a maximum of 20 characters.',
	'password_maximum_30' => 'Your password must be a maximum of 30 characters.',
	'passwords_dont_match' => 'Your passwords do not match.',
	'username_mcname_email_exists' => 'Your username or email address already exists.',
	'invalid_mcname' => 'Your Minecraft username is invalid.',
	'invalid_email' => 'Your email is invalid.',
	'mcname_lookup_error' => 'There has been an error communicating with Mojang\'s servers to verify your username. Please try again later.',
	'invalid_recaptcha' => 'Invalid reCAPTCHA response.',
	'verify_account' => 'Verify Account',
	'verify_account_help' => 'Please follow the instructions below so we can verify you own the Minecraft account in question.',
	'verification_failed' => 'Verification failed, please try again.',
	'verification_success' => 'Successfully validated! You can now log in.',
	'authme_username_exists' => 'Your Authme account has already been connected to the website!',
	
	// Login
	'successful_login' => 'You have successfully logged in.',
	'incorrect_details' => 'You have inputted incorrect details.',
	'inactive_account' => 'Your account is inactive. Please check your emails for a validation link, including within your junk folder.',
	'account_banned' => 'That account is banned.',
	'forgot_password' => 'Forgot password?',
	'remember_me' => 'Remember me',
	'must_input_username' => 'You must input a username.',
	'must_input_password' => 'You must input a password.',

	// Forgot password
    'forgot_password_instructions' => 'Please enter your email address so we can send you further instructions on resetting your password.',
	'forgot_password_email_sent' => 'If an account with the email address exists, an email has been sent containing further instructions. If you can\'t find it, try checking your junk folder.',
	'unable_to_send_forgot_password_email' => 'Unable to send forgot password email. Please contact an administrator.',
	'enter_new_password' => 'Please confirm your email address and enter a new password below.',
	'incorrect_email' => 'The email address you have entered does not match the request.',
	'forgot_password_change_successful' => 'Your password has been changed successfully. You can now log in.',
	
	// Profile pages
	'profile' => 'Profile',
	'follow' => 'Follow',
	'no_wall_posts' => 'There are no wall posts here yet.',
	'change_banner' => 'Change Banner',
	'post_on_wall' => 'Post on {x}\'s wall', // Don't replace {x}
	'invalid_wall_post' => 'Please ensure your post is between 1 and 10000 characters.',
	'1_reaction' => '1 reaction',
	'x_reactions' => '{x} reactions', // Don't replace {x}
	'1_like' => '1 like',
	'x_likes' => '{x} likes', // Don't replace {x}
	'1_reply' => '1 reply',
	'x_replies' => '{x} replies', // Don't replace {x}
	'no_replies_yet' => 'No replies yet',
	'feed' => 'Feed',
	'about' => 'About',
	'reactions' => 'Reactions',
	'replies' => 'Replies',
	'new_reply' => 'New Reply',
	'registered' => 'Registered:',
	'last_seen' => 'Last Seen:',
	'new_wall_post' => '{x} has posted on your profile.',
	'couldnt_find_that_user' => 'Couldn\'t find that user.',
	'block_user' => 'Block User',
	'unblock_user' => 'Unblock User',
	'confirm_block_user' => 'Are you sure you want to block this user? They will not be able to send you private messages or tag you in posts.',
	'confirm_unblock_user' => 'Are you sure you want to unblock this user? They will be able to send you private messages and tag you in posts.',
	'user_blocked' => 'User blocked.',
	'user_unblocked' => 'User unblocked.',
	
	// Reports
	'invalid_report_content' => 'Unable to create report. Please ensure your report reason is between 2 and 1024 characters.',
	'report_post_content' => 'Please enter a reason for your report',
	'report_created' => 'Report created successfully',
	
	// Messaging
	'no_messages' => 'No new messages',
	'no_messages_full' => 'You do not have any messages.',
	'view_messages' => 'View messages',
	'x_new_messages' => 'You have {x} new messages', // Don't replace {x}
	'new_message' => 'New Message',
	'message_title' => 'Message Title',
	'to' => 'To',
	'separate_users_with_commas' => 'Separate users with commas',
	'title_required' => 'Please input a title',
	'content_required' => 'Please input some content',
	'users_to_required' => 'Please input some message recipients',
	'cant_send_to_self' => 'You can\'t send a message to yourself!',
	'title_min_2' => 'The title must be a minimum of 2 characters',
	'content_min_2' => 'The content must be a minimum of 2 characters',
	'title_max_64' => 'The title must be a maximum of 64 characters',
	'content_max_20480' => 'The content must be a maximum of 20480 characters',
	'max_pm_10_users' => 'You can only send a message to a maximum of 10 users',
	'message_sent_successfully' => 'Message sent successfully',
	'participants' => 'Participants',
	'last_message' => 'Last Message',
	'by' => 'by',
	'leave_conversation' => 'Leave Conversation',
	'confirm_leave' => 'Are you sure you want to leave this conversation?',
	'one_or_more_users_blocked' => 'You cannot send private messages to at least one member of the conversation.',
	
	// Reactions
	'reactions' => 'Reaktioner',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Överträdelser',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Hej,',
	'email_message' => 'Tack för att du registrerade! För att slutföra din registrering klickar du på följande länk:',
	'forgot_password_email_message' => 'För att återställa ditt lösenord, klicka på följande länk. Om du inte begärt det själv kan du radera det här e-postmeddelandet.',
	'email_thanks' => 'Tack,'
);
