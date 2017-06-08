<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  EnglishUK Language - Admin
 */

$language = array(
	/*
	 *  Admin Control Panel
	 */
	// Login
	're-authenticate' => 'Please re-authenticate',

	// Sidebar
	'admin_cp' => 'AdminCP',
	'administration' => 'Administration',
	'overview' => 'Overview',
	'core' => 'Core',
	'minecraft' => 'Minecraft',
	'modules' => 'Modules',
	'security' => 'Security',
	'styles' => 'Styles',
	'users_and_groups' => 'Users and Groups',

	// Overview
	'running_nameless_version' => 'Running NamelessMC version <strong>{x}</strong>', // Don't replace "{x}"
	'running_php_version' => 'Running PHP version <strong>{x}</strong>', // Don't replace "{x}"
	'statistics' => 'Statistics',

	// Core
	'settings' => 'Settings',
	'general_settings' => 'General Settings',
	'sitename' => 'Site name',
	'default_language' => 'Default language',
	'default_language_help' => 'Users will be able to choose from any installed languages.',
	'installed_languages' => 'Any new languages have been installed successfully.',
	'default_timezone' => 'Default timezone',
	'registration' => 'Registration',
	'enable_registration' => 'Enable registration?',
	'verify_with_mcassoc' => 'Verify user accounts with MCAssoc?',
	'email_verification' => 'Enable email verification?',
	'homepage_type' => 'Homepage type',
	'post_formatting_type' => 'Post formatting type',
	'portal' => 'Portal',
	'missing_sitename' => 'Please insert a site name between 2 and 64 characters long.',
	'use_friendly_urls' => 'Friendly URLs',
	'use_friendly_urls_help' => 'IMPORTANT: Your server must be configured to allow the use of mod_rewrite and .htaccess files for this to work.',
	'config_not_writable' => 'Your <strong>core/config.php</strong> file is not writable. Please check file permissions.',
	'social_media' => 'Social Media',
	'youtube_url' => 'Youtube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_dark_theme' => 'Use Twitter dark theme?',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'successfully_updated' => 'Successfully updated',
    'debugging_and_maintenance' => 'Debugging and Maintenance',
    'enable_debug_mode' => 'Enable debug mode?',
	'force_https' => 'Force https?',
	'force_https_help' => 'If enabled, all requests to your website will be redirected to https. You must have a valid SSL certificate active for this to work correctly.',
	'contact_email' => 'Contact Email Address',

	// Reactions
	'icon' => 'Icon',
	'type' => 'Type',
	'positive' => 'Positive',
	'neutral' => 'Neutral',
	'negative' => 'Negative',
	'editing_reaction' => 'Editing Reaction',
	'html' => 'HTML',
	'new_reaction' => '<i class="fa fa-plus-circle"></i> New Reaction',
	'creating_reaction' => 'Creating Reaction',

	// Custom profile fields
	'custom_fields' => 'Custom Profile Fields',
	'new_field' => '<i class="fa fa-plus-circle"></i> New Field',
	'required' => 'Required',
	'public' => 'Public',
	'text' => 'Text',
	'textarea' => 'Text area',
	'date' => 'Date',
	'creating_profile_field' => 'Creating Profile Field',
	'editing_profile_field' => 'Editing Profile Field',
	'field_name' => 'Field Name',
	'profile_field_required_help' => 'Required fields must be filled out by the user, and they will appear during registration.',
	'profile_field_public_help' => 'Public fields will be displayed to all users, if this is disabled only moderators can view the values.',
	'profile_field_error' => 'Please input a field name between 2 and 16 characters long.',
	'description' => 'Description',
	'display_field_on_forum' => 'Display field on forum?',
	'profile_field_forum_help' => 'If enabled, the field will display by the user next to forum posts.',

	// Minecraft
	'enable_minecraft_integration' => 'Enable Minecraft integration?',
	'mc_service_status' => 'Minecraft Service Status',
	'service_query_error' => 'Unable to retrieve service status.',
	'authme_integration' => 'AuthMe Integration',
	'authme_integration_info' => 'When AuthMe integration is enabled, users can only register ingame.',
	'enable_authme' => 'Enable AuthMe integration?',
	'authme_db_address' => 'AuthMe Database Address',
	'authme_db_port' => 'AuthMe Database Port',
	'authme_db_name' => 'AuthMe Database Name',
	'authme_db_user' => 'AuthMe Database Username',
	'authme_db_password' => 'AuthMe Database Password',
	'authme_hash_algorithm' => 'AuthMe Hashing Algorithm',
	'authme_db_table' => 'AuthMe User Table',
	'enter_authme_db_details' => 'Please enter valid database details.',
	'authme_password_sync' => 'Synchronise AuthMe password?',
	'authme_password_sync_help' => 'If enabled, whenever a user\'s password is updated ingame, the password will also be updated on the website.',
	'minecraft_servers' => 'Minecraft Servers',
	'account_verification' => 'Minecraft Account Verification',
	'server_banners' => 'Server Banners',
	'query_errors' => 'Query Errors',
	'add_server' => '<i class="fa fa-plus-circle"></i> Add Server',
	'no_servers_defined' => 'No servers have been defined yet',
	'query_settings' => 'Query Settings',
	'default_server' => 'Default Server',
	'no_default_server' => 'No default server',
	'external_query' => 'Use external query?',
	'external_query_help' => 'If the default server query does not work, enable this option.',
	'adding_server' => 'Adding Server',
	'server_name' => 'Server Name',
	'server_address' => 'Server Address',
	'server_address_help' => 'This is the IP address or domain used to connect to your server, without the port.',
	'server_port' => 'Server Port',
    'parent_server' => 'Parent Server',
    'parent_server_help' => 'A parent server is typically the Bungee instance the server is connected to, if any.',
    'no_parent_server' => 'No parent server',
    'bungee_instance' => 'BungeeCord Instance?',
    'bungee_instance_help' => 'Select this option if the server is a BungeeCord instance.',
    'server_query_information' => 'In order to display a list of online players on your website, your server <strong>must</strong> have the \'enable-query\' option enabled in your server\'s <strong>server.properties</strong> file',
    'enable_status_query' => 'Enable status query?',
    'status_query_help' => 'If this is enabled, the status page will show this server as being online or offline.',
    'enable_player_list' => 'Enable player list?',
    'pre_1.7' => 'Minecraft version older than 1.7?',
    'player_list_help' => 'If this is enabled, the status page will display a list of online players.',
    'server_query_port' => 'Server Query Port',
    'server_query_port_help' => 'This is the query.port option in your server\'s server.properties file, provided the enable-query option in the same file is set to true.',
    'server_name_required' => 'Please enter the server name',
    'server_name_minimum' => 'Please ensure your server name is a minimum of 1 character',
    'server_name_maximum' => 'Please ensure your server name is a maximum of 20 characters',
    'server_address_required' => 'Please enter the server address',
    'server_address_minimum' => 'Please ensure your server address is a minimum of 1 character',
    'server_address_maximum' => 'Please ensure your server address is a maximum of 64 characters',
    'server_port_required' => 'Please enter the server port',
    'server_port_minimum' => 'Please ensure your server port is a minimum of 2 characters',
    'server_port_maximum' => 'Please ensure your server port is a maximum of 5 characters',
    'server_parent_required' => 'Please select a parent server',
    'query_port_maximum' => 'Please ensure your query port is a maximum of 5 characters',
    'server_created' => 'Server created successfully.',
    'confirm_delete_server' => 'Are you sure you want to delete this server?',
	'server_updated' => 'Server updated successfully.',
	'editing_server' => 'Editing Server',
	'server_deleted' => 'Server deleted successfully',
	'unable_to_delete_server' => 'Unable to delete server.',
	'leave_port_empty_for_srv' => 'You can the port empty if it is 25565, or if your domain uses an SRV record',

	// Modules
	'modules_installed_successfully' => 'Any new modules have been installed successfully.',
	'enabled' => 'Enabled',
	'disabled' => 'Disabled',
	'enable' => 'Enable',
	'disable' => 'Disable',
	'module_enabled' => 'Module enabled.',
	'module_disabled' => 'Module disabled.',

	// Styles
	'templates' => 'Templates',
	'template_outdated' => 'We have detected that your template is intended for Nameless version {x}, but you are running Nameless version {y}', // Don't replace "{x}" or "{y}"
	'active' => 'Active',
	'deactivate' => 'Deactivate',
	'activate' => 'Activate',
	'warning_editing_default_template' => 'Warning! It is recommended that you do not edit the default template.',
	'images' => 'Images',
	'upload_new_image' => 'Upload New Image',
	'reset_background' => 'Reset Background',
	'install' => '<i class="fa fa-plus-circle"></i> Install',
	'template_updated' => 'Template successfully updated.',
	'default' => 'Default',
	'make_default' => 'Make Default',
	'default_template_set' => 'Default template set to {x} successfully.', // Don't replace {x}
	'template_deactivated' => 'Template deactivated.',
	'template_activated' => 'Template activated.',
	'permissions' => 'Permissions',
	'setting_perms_for_x' => 'Setting permissions for template {x}', // Don't replace {x}

	// Users & groups
	'users' => 'Users',
	'groups' => 'Groups',
	'group' => 'Group',
	'new_user' => '<i class="fa fa-plus-circle"></i> New User',
	'creating_new_user' => 'Creating new user',
	'registered' => 'Registered',
	'user_created' => 'User created successfully.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group!',
	'user_deleted' => 'User deleted successfully.',
	'confirm_user_deletion' => 'Are you sure you want to delete the user <strong>{x}</strong>?', // Don't replace {x}
	'validate_user' => 'Validate User',
	'update_uuid' => 'Update UUID',
	'update_mc_name' => 'Update Minecraft Username',
	'reset_password' => 'Reset Password',
	'punish_user' => 'Punish User',
	'delete_user' => 'Delete User',
	'minecraft_uuid' => 'Minecraft UUID',
	'other_actions' => 'Other Actions',
	'disable_avatar' => 'Disable Avatar',
	'select_user_group' => 'You must select a user\'s group.',
	'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
	'title_max_64' => 'The user title must be a maximum of 64 characters.',
	'minecraft_uuid' => 'Minecraft UUID',
	'group_id' => 'Group ID',
	'name' => 'Name',
	'title' => 'User Title',
	'new_group' => '<i class="fa fa-plus-circle"></i> New Group',
	'group_name_required' => 'Please input a group name.',
	'group_name_minimum' => 'Please ensure your group name is a minimum of 2 characters long.',
	'group_name_maximum' => 'Please ensure your group name is a maximum of 20 characters long.',
	'creating_group' => 'Creating new group',
	'group_html_maximum' => 'Please ensure your group HTML is no longer than 1024 characters long.',
	'group_html' => 'Group HTML',
	'group_html_lg' => 'Group HTML Large',
	'group_username_colour' => 'Group Username Colour',
	'group_staff' => 'Is the group a staff group?',
	'group_modcp' => 'Can the group view the ModCP?',
	'group_admincp' => 'Can the group view the AdminCP?',
	'delete_group' => 'Delete Group',
	'confirm_group_deletion' => 'Are you sure you want to delete the group {x}?', // Don't replace {x}
	'group_not_exist' => 'That group doesn\'t exist.',

	// General Admin language
	'task_successful' => 'Task successful.',
	'invalid_action' => 'Invalid action.',
	'enable_night_mode' => 'Enable Night Mode',
	'disable_night_mode' => 'Disable Night Mode',
	'view_site' => 'View Site',
	'signed_in_as_x' => 'Signed in as {x}', // Don't replace {x}
    'warning' => 'Warning',

    // Maintenance
    'maintenance_mode' => 'Maintenance Mode',
    'maintenance_enabled' => 'Maintenance mode is currently enabled.',
    'enable_maintenance_mode' => 'Enable maintenance mode?',
    'maintenance_mode_message' => 'Maintenance mode message',
    'maintenance_message_max_1024' => 'Please ensure your maintenance message is a maximum of 1024 characters.',

	// Security
	'acp_logins' => 'AdminCP Logins',
	'please_select_logs' => 'Please select logs to view',
	'ip_address' => 'IP Address',
	'template_changes' => 'Template Changes',
	'file_changed' => 'File Changed',

	// Updates
	'update' => 'Update',
	'current_version_x' => 'Current version: <strong>{x}</strong>', // Don't replaec {x}
	'new_version_x' => 'New version: <strong>{x}</strong>', // Don't replace {x}
	'new_update_available' => 'There is a new update available',
	'up_to_date' => 'Your NamelessMC installation is up to date!',
	'urgent' => 'This update is an urgent update',
	'changelog' => 'Changelog',
	'update_check_error' => 'There was an error whilst checking for an update:',
	'instructions' => 'Instructions',
	'download' => 'Download',
	'install_confirm' => 'Please ensure you have downloaded the package and uploaded the contained files first!',

	// File uploads
	'drag_files_here' => 'Drag files here to upload.',
	'invalid_file_type' => 'Invalid file type!',
	'file_too_big' => 'File too big! Your file was {{filesize}} and the limit is {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
