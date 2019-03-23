<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr6
 *
 *  License: MIT
 *
 *  Russian Language - Users
 */

$language = array(
	/*
	 *  Change this for the account validation message
	 */
	'validate_account_command' => 'Для завершения регистрации, пожалуйста выполните в игре на сервере команду <strong>/validate {x}</strong>.', // Don't replace {x}

	/*
	 *  User Related
	 */
	'guest' => 'Гость',
	'guests' => 'Гости',
	
	// UserCP
	'user_cp' => 'Профиль',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i> <span class="mobile_only">Профиль</span>',
	'overview' => 'Обзор',
	'user_details' => 'Детали профиля',
	'profile_settings' => 'Настройки профиля',
	'successfully_logged_out' => 'Вы вышли.',
	'messaging' => 'Сообщения',
	'click_here_to_view' => 'Нажмите здесь для просмотра.',
	'moderation' => 'Модерация',
	'administration' => 'Администрация',
	'alerts' => 'Уведомления',
	'delete_all' => 'Удалить все',
	'private_profile' => 'Закрытый профиль',
	
	// Profile settings
	'field_is_required' => '{x} обязательно для заполнения.', // Don't replace {x}
	'settings_updated_successfully' => 'Настройки обновлены.',
	'password_changed_successfully' => 'Пароль обновлён.',
	'change_password' => 'Изменить пароль',
	'current_password' => 'Текущий пароль',
	'new_password' => 'Новый пароль',
	'confirm_new_password' => 'Подтверждение пароля',
	'incorrect_password' => 'Пароль не верен.',
	'two_factor_auth' => '2-факторная авторизация',
	'enabled' => 'Включено',
	'disabled' => 'Выключено',
	'enable' => 'Включить',
	'disable' => 'Выключить',
	'tfa_scan_code' => 'Пожалуйста отсканируйте код с помощью приложения:',
	'tfa_code' => 'Если вы не можете сосканировать QR код, используйте следующий код:',
	'tfa_enter_code' => 'Пожалуйста введите код из приложения:',
	'invalid_tfa' => 'Неверный код, попробуйте ещё раз.',
	'tfa_successful' => '2-факторная аутентификация успешно настроена. При каждом следующем входе будет запрошен код',
	'active_language' => 'Текущий Язык',
    'timezone' => 'Временная зона',
    'upload_new_avatar' => 'Загрузить новый аватар',
    'nickname_already_exists' => 'Выбранный вами логин уже занят.',
    'change_email_address' => 'Изменить Email',
    'email_already_exists' => 'Указанный email уже используется на портале.',
    'email_changed_successfully' => 'Email успешно изменён.',
    'avatar' => 'Аватар',
	
	// Alerts
	'user_tag_info' => 'Вас упомянул в сообщении пользователь {x}.', // Don't replace {x}
	'no_alerts' => 'Нет новых уведомлений',
	'view_alerts' => 'Просмотреть уведомления',
	'1_new_alert' => 'У вас 1 новое уведомление',
	'x_new_alerts' => 'Новых уведомлений для вас: {x}', // Don't replace {x}
	'no_alerts_usercp' => 'У вас не уведомлений.',
	
	// Registraton
	'registration_check_email' => 'Thanks for registering! Please check your emails for a validation link in order to complete your registration. If you are unable to find the email, check your junk folder.',
	'username' => 'Username',
	'nickname' => 'Nickname',
	'minecraft_username' => 'Minecraft Username',
	'email_address' => 'Email Address',
	'email' => 'Email',
	'password' => 'Password',
	'confirm_password' => 'Confirm Password',
	'i_agree' => 'I Agree',
	'agree_t_and_c' => 'By clicking <strong class="label label-primary">Register</strong>, you agree to our <a href="{x}" target="_blank">Terms and Conditions</a>.',
	'create_an_account' => 'Create an Account',
	'terms_and_conditions' => 'Terms and Conditions',
	'validation_complete' => 'Your account has been validated, you can now log in.',
	'validation_error' => 'There was an unknown error validating your account, please contact a website administrator.',
	'signature' => 'Signature',
	'signature_max_900' => 'Your signature must be a maximum of 900 characters.',

    // Registration - Authme
    'connect_with_authme' => 'Connect your account with AuthMe',
    'authme_help' => 'Please enter your ingame AuthMe account details. If you don\'t already have an account ingame, join the server now and follow the instructions provided.',
    'unable_to_connect_to_authme_db' => 'Unable to connect to the AuthMe database. If this error persists, please contact an administrator.',
    'authme_account_linked' => 'Account linked successfully.',
    'authme_email_help_1' => 'Finally, please enter your email address.',
    'authme_email_help_2' => 'Finally, please enter your email address, and also choose a display name for your account.',

	// Registration errors
	'username_required' => 'A username is required.',
	'email_required' => 'An email address is required.',
	'password_required' => 'A password is required.',
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
	'validate_account' => 'Validate Account',
	'verification_failed' => 'Verification failed, please try again.',
	'verification_success' => 'Successfully validated! You can now log in.',
	'authme_username_exists' => 'Your Authme account has already been connected to the website!',
	'uuid_already_exists' => 'Your UUID already exists, meaning this Minecraft account has already registered.',
	
	// Login
	'successful_login' => 'You have successfully logged in.',
	'incorrect_details' => 'You have entered incorrect details.',
	'inactive_account' => 'Your account is inactive. Please check your emails for a validation link, including within your junk folder.',
	'account_banned' => 'That account is banned.',
	'forgot_password' => 'Forgot password?',
	'remember_me' => 'Remember me',
	'must_input_email' => 'You must input an email address.',
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
	'registered_x' => 'Registered: {x}',
	'last_seen' => 'Last Seen:',
	'new_wall_post' => '{x} has posted on your profile.',
	'couldnt_find_that_user' => 'Couldn\'t find that user.',
	'block_user' => 'Block User',
	'unblock_user' => 'Unblock User',
	'confirm_block_user' => 'Are you sure you want to block this user? They will not be able to send you private messages or tag you in posts.',
	'confirm_unblock_user' => 'Are you sure you want to unblock this user? They will be able to send you private messages and tag you in posts.',
	'user_blocked' => 'User blocked.',
	'user_unblocked' => 'User unblocked.',
	'views' => 'Profile Views:',
	'private_profile_page' => 'This is a private profile!',
	'new_wall_post_reply' => '{x} has replied to your post on {y}\'s profile.', // Don't replace {x} or {y}
	'new_wall_post_reply_your_profile' => '{x} has replied to your post on your profile.', // Don't replace {x}
	'no_about_fields' => 'This user has not added any about fields yet.',
	
	// Reports
	'invalid_report_content' => 'Unable to create report. Please ensure your report reason is between 2 and 1024 characters.',
	'report_post_content' => 'Please enter a reason for your report',
	'report_created' => 'Report created successfully',
	
	// Messaging
	'no_messages' => 'Нет новых сообщений',
	'no_messages_full' => 'У вас нет сообшений.',
	'view_messages' => 'Просмотр сообщений',
	'1_new_message' => 'У вас 1 новое сообщение',
	'x_new_messages' => 'Новых сообщений для вас: {x}', // Don't replace {x}
	'new_message' => 'Новое сообщение',
	'message_title' => 'Тема сообщения',
	'to' => 'Кому',
	'separate_users_with_commas' => 'Перечислите пользователей через запятую',
	'title_required' => 'Пожалуйста укажите тему сообщения',
	'content_required' => 'Пожалуйста укажите текст сообщения',
	'users_to_required' => 'Пожалуйста укажите получателей',
	'cant_send_to_self' => 'Вы не можете отправлять сообщения самому себе!',
	'title_min_2' => 'Тема сообщения должна содержать минимум 2 символа',
	'content_min_2' => 'Текст сообщения должен содержать минимум 2 символа',
	'title_max_64' => 'Тема сообщения не может превышать 64 символов',
	'content_max_20480' => 'Текст сообщения не может превышать 20480 символов',
	'max_pm_10_users' => 'Вы не можете отправлять сообщение более чем 10 пользователям',
	'message_sent_successfully' => 'Сообщение отправлено',
	'participants' => 'Участникиs',
	'last_message' => 'Последнее сообщение',
	'by' => 'от',
	'leave_conversation' => 'Покинуть обсуждение',
	'confirm_leave' => 'Вы действительно хотите покинуть это обсуждение?',
	'one_or_more_users_blocked' => 'Вы не можете отправлять сообщения как минимум одному из участников беседы.',

	/*
	 *  Infractions area
	 */
	'you_have_been_banned' => 'Вы забанены!',
	'you_have_received_a_warning' => 'Вы получили предупреждение!',
	'acknowledge' => 'Подтвердить',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Привет,',
	'email_message' => 'Спасибо за регистрацию. Что бы завершить процесс, щёлкните по следующей ссылке:',
	'forgot_password_email_message' => 'Что-бы сбросить пароль, перейдите по следующей ссылке. Если вы не делали запроса на сброс пароля, просто проигнорируйте это письмо.',
	'email_thanks' => 'Спасибо,',

	/*
	 *  Hooks
	 */
	'user_x_has_registered' => '{x} присоединился к ' . SITE_NAME . '!'
);
