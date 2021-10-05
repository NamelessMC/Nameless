<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  EnglishUS Language - Users
 */

$language = array(
    /*
     *  Change this for the account validation message
     */
    'validate_account_command' => 'To complete registration, please execute the command <strong>/verify {x}</strong> ingame.', // Don't replace {x}

    /*
     *  User Related
     */
    'guest' => 'Guest',
    'guests' => 'Guests',

    // UserCP
    'user_cp' => 'Account',
    'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i> <span class="mobile_only">Account</span>',
    'overview' => 'Overview',
    'user_details' => 'User Details',
    'profile_settings' => 'Profile Settings',
    'successfully_logged_out' => 'You have been logged out successfully.',
    'messaging' => 'Messaging',
    'click_here_to_view' => 'Click here to view.',
    'moderation' => 'Moderation',
    'administration' => 'Administration',
    'alerts' => 'Alerts',
    'delete_all' => 'Delete All',
    'private_profile' => 'Private profile',
    'gif_avatar' => 'Upload .gif as custom avatar',
    'placeholders' => 'Placeholders',
    'no_placeholders' => 'No Placeholders',

    // Profile settings
    'field_is_required' => '{x} is required.', // Don't replace {x}
    'settings_updated_successfully' => 'Settings updated successfully.',
    'password_changed_successfully' => 'Password changed successfully.',
    'change_password' => 'Change Password',
    'current_password' => 'Current Password',
    'new_password' => 'New Password',
    'confirm_new_password' => 'Confirm New Password',
    'incorrect_password' => 'Your password is incorrect.',
    'two_factor_auth' => 'Two Factor Authentication',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'enable' => 'Enable',
    'disable' => 'Disable',
    'tfa_scan_code' => 'Please scan the following code within your authentication app:',
    'tfa_code' => 'If your device does not have a camera, or you are unable to scan the QR code, please input the following code:',
    'tfa_enter_code' => 'Please enter the code displaying within your authentication app:',
    'invalid_tfa' => 'Invalid code, please try again.',
    'tfa_successful' => 'Two factor authentication set up successfully. You will need to authenticate every time you log in from now on.',
    'active_language' => 'Active Language',
    'active_template' => 'Active Template',
    'timezone' => 'Timezone',
    'upload_new_avatar' => 'Upload a new avatar',
    'nickname_already_exists' => 'Your chosen nickname already exists.',
    'change_email_address' => 'Change Email Address',
    'email_already_exists' => 'The email address you have entered already exists.',
    'email_changed_successfully' => 'Email address changed successfully.',
    'avatar' => 'Avatar',
    'profile_banner' => 'Profile Banner',
    'upload_profile_banner' => 'Upload Profile Banner',
    'upload' => 'Upload',
    'topic_updates' => 'Get emails for topics you follow',
    'gravatar' => 'Use Gravatar as avatar',

    // Alerts
    'user_tag_info' => 'You have been tagged in a post by {x}.', // Don't replace {x}
    'no_alerts' => 'No new alerts',
    'view_alerts' => 'View alerts',
    '1_new_alert' => 'You have 1 new alert',
    'x_new_alerts' => 'You have {x} new alerts', // Don't replace {x}
    'no_alerts_usercp' => 'You do not have any alerts.',

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
    'agree_t_and_c' => 'I have read and accept the <a href="{x}" target="_blank">Terms and Conditions</a>.',
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
    'must_input_email_or_username' => 'You must input an email or username.',
    'email_or_username' => 'Email or Username',

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
    'last_seen_x' => 'Last Seen: {x}', // Don't replace {x}
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
    'reply' => 'Reply',
    'discord_username' => 'Discord Username',

    // Reports
    'invalid_report_content' => 'Unable to create report. Please ensure your report reason is between 2 and 1024 characters.',
    'report_post_content' => 'Please enter a reason for your report',
    'report_created' => 'Report created successfully',

    // Messaging
    'no_messages' => 'No new messages',
    'no_messages_full' => 'You do not have any messages.',
    'view_messages' => 'View messages',
    '1_new_message' => 'You have 1 new message',
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
    'messages' => 'Messages',
    'latest_profile_posts' => 'Latest Profile Posts',
    'no_profile_posts' => 'No profile posts.',

    /*
     *  Infractions area
     */
    'you_have_been_banned' => 'You have been banned!',
    'you_have_received_a_warning' => 'You have received a warning!',
    'acknowledge' => 'Acknowledge',

    /*
     *  Hooks
     */
    'user_x_has_registered' => '{x} has joined ' . SITE_NAME . '!',
    'user_x_has_validated' => '{x} has validated their account!',

    // Discord
    'discord_link' => 'Discord Link',
    'linked' => 'Linked',
    'not_linked' => 'Not Linked',
    'discord_id' => 'Discord User ID',
    'discord_id_unlinked' => 'Successfully unlinked your Discord User ID.',
    'discord_id_confirm' => 'Please run the command "/verify token:{token}" in Discord to finish linking your Discord account.',
    'pending_link' => 'Pending',
    'discord_id_taken' => 'That Discord ID has already been taken.',
    'discord_invalid_id' => 'That Discord User ID is invalid.',
    'discord_already_pending' => 'You already have a pending verification.',
    'discord_database_error' => 'The Nameless Link database is currently down. Please try again later.',
    'discord_communication_error' => 'There was an error while communicating with the Discord Bot. Please ensure the bot is running and your Bot URL is correct.',
    'discord_unknown_error' => 'There was an unknown error while syncing Discord roles. Please contact an administrator.',
    'discord_id_help' => 'For information on where to find Discord ID\'s, please read <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">this.</a>'
);
