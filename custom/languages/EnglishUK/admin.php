<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
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
	'install' => 'Install',
	'install_confirm' => 'Please ensure you have downloaded the package and uploaded the contained files first!',

	// File uploads
	'drag_files_here' => 'Drag files here to upload.',
	'invalid_file_type' => 'Invalid file type!',
	'file_too_big' => 'File too big! Your file was {{filesize}} and the limit is {{maxFilesize}}' // Don't replace {{filesize}} or {{maxFilesize}}
);
