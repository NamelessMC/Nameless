<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  English UK Language
 */

/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'AdminCP',
	'infractions' => 'Infractions',
	'invalid_token' => 'Invalid token, please try again.',
	'invalid_action' => 'Invalid action',
	'successfully_updated' => 'Successfully updated',
	'settings' => 'Settings',
	'confirm_action' => 'Confirm action',
	'edit' => 'Edit',
	'actions' => 'Actions',
	'task_successful' => 'Task run successfully',

	// Admin login
	're-authenticate' => 'Please re-authenticate',

	// Admin sidebar
	'index' => 'Overview',
	'announcements' => 'Announcements',
	'core' => 'Core',
	'custom_pages' => 'Custom Pages',
	'general' => 'General',
	'forums' => 'Forums',
	'users_and_groups' => 'Users and Groups',
	'minecraft' => 'Minecraft',
	'style' => 'Style',
	'addons' => 'Addons',
	'update' => 'Update',
	'misc' => 'Misc',
	'help' => 'Help',

	// Admin index page
	'statistics' => 'Statistics',
	'registrations_per_day' => 'Registrations per day (last 7 days)',

	// Admin announcements page
	'current_announcements' => 'Current Announcements',
	'create_announcement' => 'Create Announcement',
	'announcement_content' => 'Announcement Content',
	'announcement_location' => 'Announcement Location',
	'announcement_can_close' => 'Can close announcement?',
	'announcement_permissions' => 'Announcement Permissions',
	'no_announcements' => 'No announcements created yet.',
	'confirm_cancel_announcement' => 'Are you sure you want to cancel this announcement?',
	'announcement_location_help' => 'Ctrl-click to select multiple pages',
	'select_all' => 'Select All',
	'deselect_all' => 'Deselect All',
	'announcement_created' => 'Announcement successfully created',
	'please_input_announcement_content' => 'Please input announcement content and select a type',
	'confirm_delete_announcement' => 'Are you sure you want to delete this announcement?',
	'announcement_actions' => 'Announcement Actions',
	'announcement_deleted' => 'Announcement successfully deleted',
	'announcement_type' => 'Announcement Type',
	'can_view_announcement' => 'Can view announcement?',

	// Admin core page
	'general_settings' => 'General Settings',
	'modules' => 'Modules',
	'module_not_exist' => 'That module doesn\'t exist!',
	'module_enabled' => 'Module enabled.',
	'module_disabled' => 'Module disabled.',
	'site_name' => 'Site Name',
	'language' => 'Language',
	'voice_server_not_writable' => 'core/voice_server.php is not writable. Please check file permissions',
	'email' => 'Email',
	'incoming_email' => 'Incoming email address',
	'outgoing_email' => 'Outgoing email address',
	'outgoing_email_help' => 'Only required if the PHP mail function is enabled',
	'use_php_mail' => 'Use PHP mail() function?',
	'use_php_mail_help' => 'Recommended: enabled. If your website is not sending emails, please disable this and edit core/email.php with your email settings.',
	'use_gmail' => 'Use Gmail for email sending?',
	'use_gmail_help' => 'Only available if the PHP mail function is disabled. If you choose not to use Gmail, SMTP will be used. Either way, this will need configuring in core/email.php.',
	'enable_mail_verification' => 'Enable email account verification?',
	'enable_email_verification_help' => 'Having this enabled will ask newly registered users to verify their account via email before completing registration.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Pages',
	'enable_or_disable_pages' => 'Enable or disable pages here.',
	'enable' => 'Enable',
	'disable' => 'Disable',
	'maintenance_mode' => 'Forum maintenance mode',
	'forum_in_maintenance' => 'Forum is in maintenance mode.',
	'unable_to_update_settings' => 'Unable to update settings. Please ensure no fields are left empty.',
	'editing_google_analytics_module' => 'Editing Google Analytics module',
	'tracking_code' => 'Tracking Code',
	'tracking_code_help' => 'Insert the tracking code for Google Analytics here, including the surrounding script tags.',
	'google_analytics_help' => 'See <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">this guide</a> for more information, following steps 1 to 3.',
	'social_media_links' => 'Social Media Links',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL (Don\'t end with \'/\')',
	'twitter_dark_theme' => 'Use dark Twitter theme?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Registration',
	'registration_warning' => 'Having this module disabled will also disable new members registering on your site.',
	'google_recaptcha' => 'Enable Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Registration Terms and Conditions',
	'voice_server_module' => 'Voice Server Module',
	'only_works_with_teamspeak' => 'This module currently only works with TeamSpeak and Discord',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Please enter the details for the ServerQuery user',
	'ip_without_port' => 'IP (without port)',
	'voice_server_port' => 'Port (usually 10011)',
	'virtual_port' => 'Virtual Port (usually 9987)',
	'permissions' => 'Permissions:',
	'view_applications' => 'View Applications?',
	'accept_reject_applications' => 'Accept/Reject Applications?',
	'questions' => 'Questions:',
	'question' => 'Question',
	'type' => 'Type',
	'options' => 'Options',
	'options_help' => 'Each option on a new line; can be left empty (dropdowns only)',
	'no_questions' => 'No questions added yet.',
	'new_question' => 'New Question',
	'editing_question' => 'Editing Question',
	'delete_question' => 'Delete Question',
	'dropdown' => 'Dropdown',
	'text' => 'Text',
	'textarea' => 'Text Area',
	'name_required' => 'Name is required.',
	'question_required' => 'Question is required.',
	'name_minimum' => 'Name must be a minimum of 2 characters.',
	'question_minimum' => 'Question must be a minimum of 2 characters.',
	'name_maximum' => 'Name must be a maximum of 16 characters.',
	'question_maximum' => 'Question must be a maximum of 16 characters.',
	'question_deleted' => 'Question Deleted',
	'use_followers' => 'Use followers?',
	'use_followers_help' => 'If disabled, the friends system will be used.',

	// Admin custom pages page
	'click_on_page_to_edit' => 'Click on a page to edit it.',
	'page' => 'Page:',
	'url' => 'URL:',
	'page_url' => 'Page URL',
	'page_url_example' => '(With preceding "/", for example /help/)',
	'page_title' => 'Page Title',
	'page_content' => 'Page Content',
	'new_page' => 'New Page',
	'page_successfully_created' => 'Page successfully created',
	'page_successfully_edited' => 'Page successfully edited',
	'unable_to_create_page' => 'Unable to create page.',
	'unable_to_edit_page' => 'Unable to edit page.',
	'create_page_error' => 'Please ensure you have entered an URL between 1 and 20 characters long, a page title between 1 and 30 characters long, and page content between 5 and 20480 characters long.',
	'delete_page' => 'Delete Page',
	'confirm_delete_page' => 'Are you sure you want to delete this page?',
	'page_deleted_successfully' => 'Page deleted successfully',
	'page_link_location' => 'Display page link in:',
	'page_link_navbar' => 'Navbar',
	'page_link_more' => 'Navbar "More" dropdown',
	'page_link_footer' => 'Page footer',
	'page_link_none' => 'No page link',
	'page_permissions' => 'Page Permissions',
	'can_view_page' => 'Can view page:',
	'redirect_page' => 'Redirect page?',
	'redirect_link' => 'Redirect link',
	'page_icon' => 'Page Icon',

	// Admin forum page
	'labels' => 'Topic Labels',
	'new_label' => 'New Label',
	'no_labels_defined' => 'No labels defined',
	'label_name' => 'Label Name',
	'label_type' => 'Label Type',
	'label_forums' => 'Label Forums',
	'label_creation_error' => 'Error creating a label. Please ensure the name is no longer than 32 characters and that you have specified a type.',
	'confirm_label_deletion' => 'Are you sure you want to delete this label?',
	'editing_label' => 'Editing label',
	'label_creation_success' => 'Label successfully created',
	'label_edit_success' => 'Label successfully edited',
	'label_default' => 'Default',
	'label_primary' => 'Primary',
	'label_success' => 'Success',
	'label_info' => 'Info',
	'label_warning' => 'Warning',
	'label_danger' => 'Danger',
	'new_forum' => 'New Forum',
	'forum_layout' => 'Forum Layout',
	'table_view' => 'Table view',
	'latest_discussions_view' => 'Latest Discussions view',
	'create_forum' => 'Create Forum',
	'forum_name' => 'Forum Name',
	'forum_description' => 'Forum Description',
	'delete_forum' => 'Delete Forum',
	'move_topics_and_posts_to' => 'Move topics and posts to',
	'delete_topics_and_posts' => 'Delete topics and posts',
	'parent_forum' => 'Parent Forum',
	'has_no_parent' => 'Has no parent',
	'forum_permissions' => 'Forum Permissions',
	'can_view_forum' => 'Can view forum',
	'can_create_topic' => 'Can create topic',
	'can_post_reply' => 'Can post reply',
	'display_threads_as_news' => 'Display threads as news on front page?',
	'input_forum_title' => 'Input a forum title.',
	'input_forum_description' => 'Input a forum description (You may use HTML).',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => 'Category',

	// Admin Users and Groups page
	'users' => 'Users',
	'new_user' => 'New User',
	'created' => 'Created',
	'user_deleted' => 'User deleted',
	'validate_user' => 'Validate User',
	'update_uuid' => 'Update UUID',
	'unable_to_update_uuid' => 'Unable to update UUID.',
	'update_mc_name' => 'Update Minecraft Name',
	'reset_password' => 'Reset Password',
	'punish_user' => 'Punish User',
	'delete_user' => 'Delete User',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP Address',
	'ip' => 'IP:',
	'other_actions' => 'Other actions:',
	'disable_avatar' => 'Disable avatar',
	'enable_avatar' => 'Enable avatar',
	'confirm_user_deletion' => 'Are you sure you want to delete the user {x}?', // Don't replace "{x}"
	'groups' => 'Groups',
	'group' => 'Group',
	'group2' => 'Second Group',
	'new_group' => 'New Group',
	'id' => 'ID',
	'name' => 'Name',
	'create_group' => 'Create Group',
	'group_name' => 'Group Name',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'donor_group_id' => 'Donor package ID',
	'donor_group_id_help' => '<p>This is the ID of the group\'s package from your Tebex or CraftingStore</p><p>This can be left empty.</p>',
	'donor_group_instructions' => 	'<p>Donor groups must be created in the order of <strong>lowest value to highest value</strong>.</p>
									<p>For example, a £10 package will be created before a £20 package.</p>',
	'delete_group' => 'Delete Group',
	'confirm_group_deletion' => 'Are you sure you want to delete the group {x}?', // Don't replace "{x}"
	'group_staff' => 'Is the group a staff group?',
	'group_modcp' => 'Can the group view the ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'group_name_required' => 'You must insert a group name.',
	'group_name_minimum' => 'The group name must be a minimum of 2 characters.',
	'group_name_maximum' => 'The group name must be a maximum of 20 characters.',
	'html_maximum' => 'The group HTML must be a maximum of 1024 characters.',
	'select_user_group' => 'The user must be in a group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',

	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft Settings',
	'use_plugin' => 'Enable Nameless API?',
	'force_avatars' => 'Force Minecraft avatars?',
	'uuid_linking' => 'Enable UUID linking?',
	'use_plugin_help' => 'Enabling the API, along with the server plugin, allows for rank synchronisation and also ingame registration and report submission.',
	'uuid_linking_help' => 'If disabled, user accounts won\'t be linked with UUIDs. It is highly recommended you keep this as enabled.',
	'plugin_settings' => 'Plugin Settings',
	'confirm_api_regen' => 'Are you sure you want to generate a new API key?',
	'servers' => 'Servers',
	'new_server' => 'New Server',
	'confirm_server_deletion' => 'Are you sure you want to delete this server?',
	'main_server' => 'Main Server',
	'main_server_help' => 'The server players connect through. Normally this will be the Bungee instance.',
	'choose_a_main_server' => 'Choose a main server..',
	'external_query' => 'Use external query?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Editing server {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Server IP (with port) (numeric or domain)',
	'server_ip_with_port_help' => 'This is the IP which will be displayed to users. It will not be queried.',
	'server_ip_numeric' => 'Server IP (with port) (numeric only)',
	'server_ip_numeric_help' => 'This is the IP which will be queried, please ensure it is numeric only. It will not be displayed to users.',
	'show_on_play_page' => 'Show on Play page?',
	'pre_17' => 'Pre 1.7 Minecraft version?',
	'server_name' => 'Server Name',
	'invalid_server_id' => 'Invalid server ID',
	'show_players' => 'Show player list on Play page?',
	'server_edited' => 'Server edited successfully',
	'server_created' => 'Server created successfully',
	'query_errors' => 'Query Errors',
	'query_errors_info' => 'The following errors allow you to diagnose issues with your internal server query.',
	'no_query_errors' => 'No query errors logged',
	'date' => 'Date:',
	'port' => 'Port:',
	'viewing_error' => 'Viewing Error',
	'confirm_error_deletion' => 'Are you sure you want to delete this error?',
	'display_server_status' => 'Display server status module?',
	'server_name_required' => 'You must insert a server name.',
	'server_ip_required' => 'You must insert the server\'s IP.',
	'server_name_minimum' => 'The server name must be a minimum of 2 characters.',
	'server_ip_minimum' => 'The server IP must be a minimum of 2 characters.',
	'server_name_maximum' => 'The server name must be a maximum of 20 characters.',
	'server_ip_maximum' => 'The server IP must be a maximum of 64 characters.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	'avatar_type' => 'Avatar type',
	'custom_usernames' => 'Force Minecraft usernames?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Use mcassoc?',
	'use_mcassoc_help' => 'mcassoc ensures users own the Minecraft account they\'re registering with',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Invalid mcassoc key.',
	'mcassoc_instance' => 'mcassoc Instance',
	'mcassoc_instance_help' => 'Generate an instance code <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">here</a>',
	'mcassoc_key_help' => 'Get your mcassoc key <a href="https://mcassoc.lukegb.com/" target="_blank">here</a>',
	'enable_name_history' => 'Enable profile username history?',

	// Admin Themes, Templates and Addons
	'themes' => 'Themes',
	'templates' => 'Templates',
	'installed_themes' => 'Installed themes',
	'installed_templates' => 'Installed templates',
	'installed_addons' => 'Installed addons',
	'install_theme' => 'Install Theme',
	'install_template' => 'Install Template',
	'install_addon' => 'Install Addon',
	'install_a_theme' => 'Install a theme',
	'install_a_template' => 'Install a template',
	'install_an_addon' => 'Install an addon',
	'active' => 'Active',
	'activate' => 'Activate',
	'deactivate' => 'Deactivate',
	'theme_install_instructions' => 'Please upload themes to the <strong>styles/themes</strong> directory. Then, click the "scan" button below.',
	'template_install_instructions' => 'Please upload templates to the <strong>styles/templates</strong> directory. Then, click the "scan" button below.',
	'addon_install_instructions' => 'Please upload addons to the <strong>addons</strong> directory. Then, click the "scan" button below.',
	'addon_install_warning' => 'Addons are installed at your own risk. Please back up your files and the database before proceeding',
	'scan' => 'Scan',
	'theme_not_exist' => 'That theme doesn\'t exist!',
	'template_not_exist' => 'That template doesn\'t exist!',
	'addon_not_exist' => 'That addon doesn\'t exist!',
	'style_scan_complete' => 'Completed, any new styles have been installed.',
	'addon_scan_complete' => 'Completed, any new addons have been installed.',
	'theme_enabled' => 'Theme enabled.',
	'template_enabled' => 'Template enabled.',
	'addon_enabled' => 'Addon enabled.',
	'theme_deleted' => 'Theme deleted.',
	'template_deleted' => 'Template deleted.',
	'addon_disabled' => 'Addon disabled.',
	'inverse_navbar' => 'Inverse Navbar',
	'confirm_theme_deletion' => 'Are you sure you wish to delete the theme <strong>{x}</strong>?<br /><br />The theme will be deleted from your <strong>styles/themes</strong> directory.', // Don't replace {x}
	'confirm_template_deletion' => 'Are you sure you wish to delete the template <strong>{x}</strong>?<br /><br />The template will be deleted from your <strong>styles/templates</strong> directory.', // Don't replace {x}
	'unable_to_enable_addon' => 'Could not enable addon. Please ensure it is a valid NamelessMC addon.',

	// Admin Misc page
	'other_settings' => 'Other Settings',
	'enable_error_reporting' => 'Enable error reporting?',
	'error_reporting_description' => 'This should only be used for debugging purposes, it\'s highly recommended this is left as disabled.',
	'display_page_load_time' => 'Display page loading time?',
	'page_load_time_description' => 'Having this enabled will display a speedometer in the footer which will display the page loading time.',
	'reset_website' => 'Reset Website',
	'reset_website_info' => 'This will reset your website settings. <strong>Addons will be disabled but not removed, and their settings will not change.</strong> Your defined Minecraft servers will also remain.',
	'confirm_reset_website' => 'Are you sure you want to reset your website settings?',

	// Admin Update page
	'installation_up_to_date' => 'Your installation is up to date.',
	'update_check_error' => 'Unable to check for updates. Please try again later.',
	'new_update_available' => 'A new update is available.',
	'your_version' => 'Your version:',
	'new_version' => 'New version:',
	'download' => 'Download',
	'update_warning' => 'Warning: Ensure you have downloaded the package and uploaded the contained files first!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Home',
	'play' => 'Play',
	'forum' => 'Forum',
	'more' => 'More',
	'staff_apps' => 'Staff Applications',
	'view_messages' => 'View Messages',
	'view_alerts' => 'View Alerts',

	// Icons - will display before the text
	'home_icon' => '',
	'play_icon' => '',
	'forum_icon' => '',
	'staff_apps_icon' => ''
);

/*
 * User Related
 */
$user_language = array(
	// Registration
	'create_an_account' => 'Create an Account',
	'authme_password' => 'AuthMe Password',
	'username' => 'Username',
	'minecraft_username' => 'Minecraft Username',
	'email' => 'Email',
	'user_title' => 'Title',
	'email_address' => 'Email Address',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Password',
	'confirm_password' => 'Confirm Password',
	'i_agree' => 'I Agree',
	'agree_t_and_c' => 'By clicking <strong class="label label-primary">Register</strong>, you agree to our <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a>.',
	'register' => 'Register',
	'sign_in' => 'Sign In',
	'sign_out' => 'Sign Out',
	'terms_and_conditions' => 'Terms and Conditions',
	'successful_signin' => 'You have been signed in successfully',
	'incorrect_details' => 'Incorrect details',
	'remember_me' => 'Remember me',
	'forgot_password' => 'Forgot Password',
	'must_input_username' => 'You must insert a username.',
	'must_input_password' => 'You must insert a password.',
	'inactive_account' => 'Your account is currently inactive. Did you request a password reset?',
	'account_banned' => 'Your account has been banned.',
	'successfully_logged_out' => 'You have been successfully logged out.',
	'signature' => 'Signature',
	'registration_check_email' => 'Please check your emails for a validation link. You won\'t be able to log in until this is clicked.',
	'unknown_login_error' => 'Sorry, there was an unknown error whilst logging you in. Please try again later.',
	'validation_complete' => 'Thanks for registering! You can now log in.',
	'validation_error' => 'Error processing your request. Please try clicking the link again.',
	'registration_error' => 'Please ensure you have filled out all fields, and that your username is between 3 and 20 characters long and your password is between 6 and 30 characters long.',
	'username_required' => 'Please enter a username.',
	'password_required' => 'Please enter a password.',
	'email_required' => 'Please enter an email address.',
	'mcname_required' => 'Please enter a Minecraft username.',
	'accept_terms' => 'You must accept the terms and conditions before registering.',
	'invalid_recaptcha' => 'Invalid reCAPTCHA response.',
	'username_minimum_3' => 'Your username must be a minimum of 3 characters long.',
	'username_maximum_20' => 'Your username must be a maximum of 20 characters long.',
	'mcname_minimum_3' => 'Your Minecraft username must be a minimum of 3 characters long.',
	'mcname_maximum_20' => 'Your Minecraft username must be a maximum of 20 characters long.',
	'password_minimum_6' => 'Your password must be at least 6 characters long.',
	'password_maximum_30' => 'Your password must be a maximum of 30 characters long.',
	'passwords_dont_match' => 'Your passwords do not match.',
	'username_mcname_email_exists' => 'Your username, Minecraft username or email address already exists. Have you already created an account?',
	'invalid_mcname' => 'Your Minecraft username is not a valid account',
	'mcname_lookup_error' => 'There was an error contacting Mojang\'s servers. Please try again later.',
	'signature_maximum_900' => 'Your signature must be a maximum of 900 characters.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
	'verify_account' => 'Verify account',
	'verify_account_help' => 'Please follow the instructions below so we can verify you own the Minecraft account in question.',
	'verification_failed' => 'Verification failed, please try again.',
	'verification_success' => 'Successfully validated! You can now log in.',
	'complete_signup' => 'Complete Signup',
	'registration_disabled' => 'Website registration is currently disabled.',

	// UserCP
	'user_cp' => 'UserCP',
	'no_file_chosen' => 'No file chosen',
	'private_messages' => 'Private Messages',
	'profile_settings' => 'Profile Settings',
	'your_profile' => 'Your Profile',
	'topics' => 'Topics',
	'posts' => 'Posts',
	'reputation' => 'Reputation',
	'friends' => 'Friends',
	'alerts' => 'Alerts',

	// Messaging
	'new_message' => 'New Message',
	'no_messages' => 'No messages',
	'and_x_more' => 'and {x} more', // Don't replace "{x}"
	'system' => 'System',
	'message_title' => 'Message Title',
	'message' => 'Message',
	'to' => 'To:',
	'separate_users_with_comma' => 'Separate users with a comma (",")',
	'viewing_message' => 'Viewing Message',
	'delete_message' => 'Delete Message',
	'confirm_message_deletion' => 'Are you sure you want to delete this message?',

	// Profile settings
	'display_name' => 'Display name',
	'upload_an_avatar' => 'Upload an avatar (.jpg, .png or .gif only):',
	'use_gravatar' => 'Use Gravatar?',
	'change_password' => 'Change password',
	'current_password' => 'Current password',
	'new_password' => 'New password',
	'repeat_new_password' => 'Repeat new password',
	'password_changed_successfully' => 'Password changed successfully',
	'incorrect_password' => 'Your current password is incorrect',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Display age on profile?',
	'two_factor_authentication' => 'Two Factor Authentication',
	'enable_tfa' => 'Enable Two Factor Authentication',
	'tfa_type' => 'Two Factor Authentication type:',
	'authenticator_app' => 'Authentication App',
	'tfa_scan_code' => 'Please scan the following code within your authentication app:',
	'tfa_code' => 'If your device does not have a camera, or you are unable to scan the QR code, please input the following code:',
	'tfa_enter_code' => 'Please enter the code displaying within your authentication app:',
	'invalid_tfa' => 'Invalid code, please try again.',
	'tfa_successful' => 'Two factor authentication set up successfully. You will need to authenticate every time you log in from now on.',
	'confirm_tfa_disable' => 'Are you sure you wish to disable two factor authentication?',
	'tfa_disabled' => 'Two factor authentication disabled.',
	'tfa_enter_email_code' => 'We have sent you a code within an email for verification. Please enter the code now:',
	'tfa_email_contents' => 'A login attempt has been made to your account. If this was you, please input the following two factor authentication code when asked to do so. If this was not you, you can ignore this email, however a password reset is advised. The code is only valid for 10 minutes.',

	// Alerts
	'viewing_unread_alerts' => 'Viewing unread alerts. Change to <a href="/user/alerts/?view=read"><span class="label label-success">read</span></a>.',
	'viewing_read_alerts' => 'Viewing read alerts. Change to <a href="/user/alerts/"><span class="label label-warning">unread</span></a>.',
	'no_unread_alerts' => 'You have no unread alerts.',
	'no_alerts' => 'No alerts',
	'no_read_alerts' => 'You have no read alerts.',
	'view' => 'View',
	'alert' => 'Alert',
	'when' => 'When',
	'delete' => 'Delete',
	'tag' => 'User Tag',
	'tagged_in_post' => 'You have been tagged in a post',
	'report' => 'Report',
	'deleted_alert' => 'Alert successfully deleted',

	// Warnings
	'you_have_received_a_warning' => 'You have received a warning from {x} dated {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Acknowledge',

	// Forgot password
	'password_reset' => 'Password Reset',
	'email_body' => 'You are receiving this email because you requested a password reset. In order to reset your password, please use the following link:', // Body for the password reset email
	'email_body_2' => 'If you did not request the password reset, you can ignore this email.',
	'password_email_set' => 'Success. Please check your emails for further instructions.',
	'username_not_found' => 'That username does not exist.',
	'change_password' => 'Change Password',
	'your_password_has_been_changed' => 'Your password has been changed.',

	// Profile page
	'profile' => 'Profile',
	'player' => 'Player',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Registered:',
	'pf_posts' => 'Posts:',
	'pf_reputation' => 'Reputation:',
	'user_hasnt_registered' => 'This user hasn\'t registered on our website yet',
	'user_no_friends' => 'This user has not added any friends',
	'send_message' => 'Send Message',
	'remove_friend' => 'Remove Friend',
	'add_friend' => 'Add Friend',
	'last_online' => 'Last Online:',
	'find_a_user' => 'Find a user',
	'user_not_following' => 'This user does not follow anyone.',
	'user_no_followers' => 'This user has no followers.',
	'following' => 'Following',
	'followers' => 'Followers',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Write something on your profile...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'About',
	'reply' => 'Reply',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'No likes.',
	'post_liked' => 'Post liked.',
	'post_unliked' => 'Post unliked.',
	'no_posts' => 'No posts.',
	'last_5_posts' => 'Last 5 posts',
	'follow' => 'Follow',
	'unfollow' => 'Unfollow',
	'name_history' => 'Name History',
	'changed_name_to' => 'Changed name to: {x} on {y}', // Don't replace {x} or {y}
	'original_name' => 'Original name:',
	'name_history_error' => 'Unable to retrieve username history.',

	// Staff applications
	'staff_application' => 'Staff Application',
	'application_submitted' => 'Application submitted successfully.',
	'application_already_submitted' => 'You\'ve already submitted an application. Please wait until it is complete before submitting another.',
	'not_logged_in' => 'Please log in to view that page.',
	'application_accepted' => 'Your staff application has been accepted.',
	'application_rejected' => 'Your staff application has been rejected.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Overview',
	'reports' => 'Reports',
	'punishments' => 'Punishments',
	'staff_applications' => 'Staff Applications',

	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Warn',
	'search_for_a_user' => 'Search for a user',
	'user' => 'User:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Registered',
	'reason' => 'Reason:',
	'cant_ban_root_user' => 'Can\'t punish the root user!',
	'invalid_reason' => 'Please enter a valid reason between 2 and 256 characters long.',
	'punished_successfully' => 'Punishment added successfully.',

	// Reports
	'report_closed' => 'Report closed.',
	'new_comment' => 'New comment',
	'comments' => 'Comments',
	'only_viewed_by_staff' => 'Can only be viewed by staff',
	'reported_by' => 'Reported by',
	'close_issue' => 'Close issue',
	'report' => 'Report:',
	'view_reported_content' => 'View reported content',
	'no_open_reports' => 'No open reports',
	'user_reported' => 'User Reported',
	'type' => 'Type',
	'updated_by' => 'Updated By',
	'forum_post' => 'Forum Post',
	'user_profile' => 'User Profile',
	'comment_added' => 'Comment added.',
	'new_report_submitted_alert' => 'New report submitted by {x} regarding user {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'Ingame Report',

	// Staff applications
	'comment_error' => 'Please ensure your comment is between 2 and 2048 characters long.',
	'viewing_open_applications' => 'Viewing <span class="label label-info">open</span> applications. Change to <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_accepted_applications' => 'Viewing <span class="label label-success">accepted</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=declined"><span class="label label-danger">declined</span></a>.',
	'viewing_declined_applications' => 'Viewing <span class="label label-danger">declined</span> applications. Change to <a href="/mod/applications/"><span class="label label-info">open</span></a> or <a href="/mod/applications/?view=accepted"><span class="label label-success">accepted</span></a>.',
	'time_applied' => 'Time Applied',
	'no_applications' => 'No applications in this category',
	'viewing_app_from' => 'Viewing application from {x}', // Don't replace "{x}"
	'open' => 'Open',
	'accepted' => 'Accepted',
	'declined' => 'Declined',
	'accept' => 'Accept',
	'decline' => 'Decline',
	'new_app_submitted_alert' => 'New application submitted by {x}' // Don't replace "{x}"
);

/*
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'News',
	'social' => 'Social',
	'join' => 'Join',

	// General terms
	'submit' => 'Submit',
	'close' => 'Close',
	'cookie_message' => '<strong>This site uses cookies to enhance your experience.</strong><p>By continuing to browse and interact with this website, you agree with their use.</p>',
	'theme_not_exist' => 'The selected theme does not exist.',
	'confirm' => 'Confirm',
	'cancel' => 'Cancel',
	'guest' => 'Guest',
	'guests' => 'Guests',
	'back' => 'Back',
	'search' => 'Search',
	'help' => 'Help',
	'success' => 'Success',
	'error' => 'Error',
	'view' => 'View',
	'info' => 'Info',
	'next' => 'Next',

	// Play page
	'connect_with' => 'Connect to the server with the IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Players Online:',
	'queried_in' => 'Queried In:',
	'server_status' => 'Server Status',
	'no_players_online' => 'There are no players online.',
	'1_player_online' => 'There is 1 player online.',
	'x_players_online' => 'There are {x} players online.', // Don't replace {x}

	// Other
	'page_loaded_in' => 'Page loaded in {x}s', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'None',
	'404' => 'Sorry, we couldn\'t find that page.'
);

/*
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Forums',
	'discussion' => 'Discussion',
	'stats' => 'Stats',
	'last_reply' => 'Last Reply',
	'ago' => 'ago',
	'by' => 'by',
	'in' => 'in',
	'views' => 'views',
	'posts' => 'posts',
	'topics' => 'topics',
	'topic' => 'Topic',
	'statistics' => 'Statistics',
	'overview' => 'Overview',
	'latest_discussions' => 'Latest Discussions',
	'latest_posts' => 'Latest Posts',
	'users_registered' => 'Users registered:',
	'latest_member' => 'Latest member:',
	'forum' => 'Forum',
	'last_post' => 'Last Post',
	'no_topics' => 'No topics here yet',
	'new_topic' => 'New Topic',
	'subforums' => 'Subforums:',

	// View topic view
	'home' => 'Home',
	'topic_locked' => 'Topic Locked',
	'new_reply' => 'New Reply',
	'mod_actions' => 'Mod Actions',
	'lock_thread' => 'Lock Thread',
	'unlock_thread' => 'Unlock Thread',
	'merge_thread' => 'Merge Thread',
	'delete_thread' => 'Delete Thread',
	'confirm_thread_deletion' => 'Are you sure you want to delete this thread?',
	'move_thread' => 'Move Thread',
	'sticky_thread' => 'Sticky Thread',
	'report_post' => 'Report Post',
	'quote_post' => 'Quote Post',
	'delete_post' => 'Delete Post',
	'edit_post' => 'Edit Post',
	'reputation' => 'reputation',
	'confirm_post_deletion' => 'Are you sure you want to delete this post?',
	'give_reputation' => 'Give Reputation',
	'remove_reputation' => 'Remove Reputation',
	'post_reputation' => 'Post Reputation',
	'no_reputation' => 'No reputation for this post yet',
	're' => 'RE:',

	// Create post view
	'create_post' => 'Create post',
	'post_submitted' => 'Post submitted',
	'creating_post_in' => 'Creating post in: ',
	'topic_locked_permission_post' => 'This topic is locked, however your permissions allow you to post',

	// Edit post view
	'editing_post' => 'Editing post',

	// Sticky threads
	'thread_is_' => 'Thread is ',
	'now_sticky' => 'now a sticky thread',
	'no_longer_sticky' => 'no longer a sticky thread',

	// Create topic
	'topic_created' => 'Topic created.',
	'creating_topic_in_' => 'Creating topic in forum ',
	'thread_title' => 'Thread Title',
	'confirm_cancellation' => 'Are you sure?',
	'label' => 'Label',

	// Reports
	'report_submitted' => 'Report submitted.',
	'view_post_content' => 'View post content',
	'report_reason' => 'Report Reason',

	// Move thread
	'move_to' => 'Move to:',

	// Merge threads
	'merge_instructions' => 'The thread to merge with <strong>must</strong> be within the same forum. Move a thread if necessary.',
	'merge_with' => 'Merge with:',

	// Other
	'forum_error' => 'Sorry, we couldn\'t find that forum or topic.',
	'are_you_logged_in' => 'Are you logged in?',
	'online_users' => 'Online Users',
	'no_users_online' => 'There are no users online.',

	// Search
	'search_error' => 'Please input a search query between 1 and 32 characters long.',
	'no_search_results' => 'No search results have been found.',

	//Share on a social-media.
	'sm-share' => 'Share',
	'sm-share-facebook' => 'Share on Facebook',
	'sm-share-twitter' => 'Share on Twitter',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hi',
	'message' => 'Thanks for registering! In order to complete your registration, please click the following link:',
	'thanks' => 'Thanks,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'less than a minute ago',
	'1_minute' => '1 minute ago',
	'_minutes' => '{x} minutes ago',
	'about_1_hour' => 'about 1 hour ago',
	'_hours' => '{x} hours ago',
	'1_day' => '1 day ago',
	'_days' => '{x} days ago',
	'about_1_month' => 'about 1 month ago',
	'_months' => '{x} months ago',
	'about_1_year' => 'about 1 year ago',
	'over_x_years' => 'over {x} years ago'
);

/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Display _MENU_ records per page', // Don't replace "_MENU_"
	'nothing_found' => 'No results found',
	'page_x_of_y' => 'Showing page _PAGE_ of _PAGES_', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'No records available',
	'filtered' => '(filtered from _MAX_ total records)' // Don't replace "_MAX_"
);

/*
 *  API language
 */
$api_language = array(
	'register' => 'Complete Registration'
);

?>
