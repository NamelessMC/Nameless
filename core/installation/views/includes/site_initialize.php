<?php

$queries = new Queries();
$cache = new Cache();

// Create first category + forum
$queries->create('forums', array(
	'forum_title' => 'Category',
	'forum_description' => 'The first forum category!',
	'forum_order' => 1,
	'forum_type' => 'category'
));

$queries->create('forums', array(
	'forum_title' => 'Forum',
	'forum_description' => 'The first discussion forum!',
	'forum_order' => 2,
	'parent' => 1,
	'forum_type' => 'forum'
));

// Permissions
for ($i = 0; $i < 4; $i++) {
	for ($n = 1; $n < 3; $n++) {
		$queries->create('forums_permissions', array(
			'group_id' => $i,
			'forum_id' => $n,
			'view' => 1,
			'create_topic' => (($i == 0 || $i == 4) ? 0 : 1),
			'edit_topic' => (($i == 0 || $i == 4) ? 0 : 1),
			'create_post' => (($i == 0 || $i == 4) ? 0 : 1),
			'view_other_topics' => 1,
			'moderate' => (($i == 2 || $i == 3) ? 1 : 0)
		));
	}
}

// Forum Labels
$queries->create('forums_labels', array(
	'name' => 'Default',
	'html' => '<span class="badge badge-default">{x}</span>'
));

$queries->create('forums_labels', array(
	'name' => 'Primary',
	'html' => '<span class="badge badge-primary">{x}</span>'
));

$queries->create('forums_labels', array(
	'name' => 'Success',
	'html' => '<span class="badge badge-success">{x}</span>'
));

$queries->create('forums_labels', array(
	'name' => 'Info',
	'html' => '<span class="badge badge-info">{x}</span>'
));

$queries->create('forums_labels', array(
	'name' => 'Warning',
	'html' => '<span class="badge badge-warning">{x}</span>'
));

$queries->create('forums_labels', array(
	'name' => 'Danger',
	'html' => '<span class="badge badge-danger">{x}</span>'
));

// Groups
$queries->create('groups', array(
	'name' => 'Member',
	'group_html' => '<span class="badge badge-success">Member</span>',
	'group_html_lg' => '<span class="badge badge-success">Member</span>',
	'permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}',
	'default_group' => 1,
	'order' => 3
));

$queries->create('groups', array(
	'name' => 'Admin',
	'group_html' => '<span class="badge badge-danger">Admin</span>',
	'group_html_lg' => '<span class="badge badge-danger">Admin</span>',
	'group_username_color' => '#ff0000',
	'group_username_css' => '',
	'mod_cp' => 1,
	'admin_cp' => 1,
	'permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.integrations":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.pages.metadata":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.sitemap":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.core.emails_mass_message":1}',
	'order' => 1,
	'staff' => 1
));

$queries->create('groups', array(
	'name' => 'Moderator',
	'group_html' => '<span class="badge badge-primary">Moderator</span>',
	'group_html_lg' => '<span class="badge badge-primary">Moderator</span>',
	'admin_cp' => 1,
	'permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}',
	'order' => 2,
	'staff' => 1
));

$queries->create('groups', array(
	'name' => 'Unconfirmed Member',
	'group_html' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
	'group_html_lg' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
	'group_username_color' => '#6c757d',
	'order' => 4
));

// Languages
$queries->create('languages', array(
	'name' => 'EnglishUK',
	'is_default' => 1
));

$queries->create('languages', array(
	'name' => 'Chinese',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Czech',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'EnglishUS',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Dutch',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'German',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Greek',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Japanese',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Lithuanian',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Norwegian',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Polish',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Portuguese',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Romanian',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Slovak',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Spanish',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'SwedishSE',
	'is_default' => 0
));

$queries->create('languages', array(
	'name' => 'Turkish',
	'is_default' => 0
));

$cache->setCache('languagecache');
$cache->store('language', 'EnglishUK');

// Modules
$queries->create('modules', array(
	'name' => 'Core',
	'enabled' => 1
));

$queries->create('modules', array(
	'name' => 'Forum',
	'enabled' => 1
));

$queries->create('modules', array(
	'name' => 'DefaultTheme',
	'enabled' => 0
));

$cache->setCache('modulescache');
$cache->store('enabled_modules', array(
	array(
		'name' => 'Core',
		'priority' => 1
	),
	array(
		'name' => 'Forum',
		'priority' => 4
	)
));
$cache->store('module_core', true);
$cache->store('module_forum', true);

// Reactions
$queries->create('reactions', array(
	'name' => 'Like',
	'html' => '<i class="fas fa-thumbs-up text-success"></i>',
	'enabled' => 1,
	'type' => 2
));

$queries->create('reactions', array(
	'name' => 'Dislike',
	'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
	'enabled' => 1,
	'type' => 0
));

$queries->create('reactions', array(
	'name' => 'Meh',
	'html' => '<i class="fas fa-meh text-warning"></i>',
	'enabled' => 1,
	'type' => 1
));

// Settings
$queries->create('settings', array(
	'name' => 'registration_enabled',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'displaynames',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'uuid_linking',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'recaptcha',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'recaptcha_type',
	'value' => 'reCaptcha'
));

$queries->create('settings', array(
	'name' => 'recaptcha_login',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'recaptcha_key',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'recaptcha_secret',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'email_verification',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 't_and_c',
	'value' => 'By registering on our website, you agree to the following:<p>This website uses "Nameless" website software. The "Nameless" software creators will not be held responsible for any content that may be experienced whilst browsing this site, nor are they responsible for any loss of data which may come about, for example a hacking attempt. The website is run independently from the software creators, and any content is the responsibility of the website administration.</p>'
));

$queries->create('privacy_terms', array(
	'name' => 'terms',
	'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
));

$queries->create('settings', array(
	'name' => 'nameless_version',
	'value' => '2.0.0-pr8'
));

$queries->create('settings', array(
	'name' => 'version_checked',
	'value' => date('U')
));

$queries->create('settings', array(
	'name' => 'version_update',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'phpmailer',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'phpmailer_type',
	'value' => 'smtp'
));

$queries->create('settings', array(
	'name' => 'verify_accounts',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'mcassoc_key',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'mcassoc_instance',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'user_avatars',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'forum_layout',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'maintenance',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'avatar_site',
	'value' => 'cravatar'
));

$queries->create('settings', array(
	'name' => 'mc_integration',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'discord_bot_url',
	'value' => 'https://bot-1.namelessmc.com'
));

$queries->create('settings', array(
	'name' => 'discord_bot_url_backup',
	'value' => 'http://bot-2.namelessmc.com'
));

$queries->create('settings', array(
	'name' => 'discord_integration',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'avatar_type',
	'value' => 'helmavatar'
));

$queries->create('settings', array(
	'name' => 'portal',
	'value' => 0
));
$cache->setCache('portal_cache');
$cache->store('portal', 0);

$queries->create('settings', array(
	'name' => 'forum_reactions',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'formatting_type',
	'value' => 'html'
));
$cache->setCache('post_formatting');
$cache->store('formatting', 'html');

$queries->create('settings', array(
	'name' => 'youtube_url',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'twitter_url',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'twitter_style',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'fb_url',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'ga_script',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'error_reporting',
	'value' => 0
));
$cache->setCache('error_cache');
$cache->store('error_reporting', 0);

$queries->create('settings', array(
	'name' => 'page_loading',
	'value' => 0
));
$cache->setCache('page_load_cache');
$cache->store('page_load', 0);

$queries->create('settings', array(
	'name' => 'unique_id',
	'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 62)
));

$queries->create('settings', array(
	'name' => 'use_api',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'mc_api_key',
	'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32)
));

$queries->create('settings', array(
	'name' => 'external_query',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'followers',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'discord',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'language',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'timezone',
	'value' => 'Europe/London'
));
$cache->setCache('timezone_cache');
$cache->store('timezone', 'Europe/London');

$queries->create('settings', array(
	'name' => 'maintenance_message',
	'value' => 'This website is currently in maintenance mode.'
));
$cache->setCache('maintenance_cache');
$cache->store('maintenance', array(
	'maintenance' => 'false',
	'message' => 'This website is currently in maintenance mode.'
));

$queries->create('settings', array(
	'name' => 'authme',
	'value' => 0
));

$queries->create('settings', array(
	'name' => 'authme_db',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'force_https',
	'value' => 'false'
));

$queries->create('settings', array(
	'name' => 'default_avatar_type',
	'value' => 'minecraft'
));

$queries->create('settings', array(
	'name' => 'custom_default_avatar',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'private_profile',
	'value' => 1
));

$queries->create('settings', array(
	'name' => 'registration_disabled_message',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'discord_url',
	'value' => null
));

$queries->create('settings', array(
	'name' => 'discord_hooks',
	'value' => '{}'
));

$queries->create('settings', array(
	'name' => 'api_verification',
	'value' => '0'
));

$queries->create('settings', array(
	'name' => 'validate_user_action',
	'value' => '{"action":"promote","group":1}'
));

$queries->create('settings', array(
	'name' => 'login_method',
	'value' => 'email'
));

$queries->create('settings', array(
	'name' => 'username_sync',
	'value' => '1'
));

$queries->create('privacy_terms', array(
	'name' => 'privacy',
	'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
));

$queries->create('settings', array(
	'name' => 'status_page',
	'value' => '1'
));

// Templates
$queries->create('templates', array(
	'name' => 'Default',
	'enabled' => 1,
	'is_default' => 0
));

$queries->create('templates', array(
	'name' => 'DefaultRevamp',
	'enabled' => 1,
	'is_default' => 1
));

$cache->setCache('templatecache');
$cache->store('default', 'DefaultRevamp');

$queries->create('panel_templates', array(
	'name' => 'Default',
	'enabled' => 1,
	'is_default' => 1
));
$cache->store('panel_default', 'Default');

// Widgets - initialise just a few default ones for now
$queries->create('widgets', array(
	'name' => 'Online Staff',
	'enabled' => 1,
	'pages' => '["index","forum"]'
));

$queries->create('widgets', array(
	'name' => 'Online Users',
	'enabled' => 1,
	'pages' => '["index","forum"]'
));

$queries->create('widgets', array(
	'name' => 'Statistics',
	'enabled' => 1,
	'pages' => '["index","forum"]'
));

$cache->setCache('Core-widgets');
$cache->store('enabled', array(
	'Online Staff' => 1,
	'Online Users' => 1,
	'Statistics' => 1
));

$cache->setCache('backgroundcache');
$cache->store('banner_image', '/uploads/template_banners/homepage_bg_trimmed.jpg');