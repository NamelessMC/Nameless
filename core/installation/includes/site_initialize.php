<?php

$queries = new Queries();
$cache = new Cache();

// Create first category + forum
$queries->create('forums', [
    'forum_title' => 'Category',
    'forum_description' => 'The first forum category!',
    'forum_order' => 1,
    'forum_type' => 'category'
]);

$queries->create('forums', [
    'forum_title' => 'Forum',
    'forum_description' => 'The first discussion forum!',
    'forum_order' => 2,
    'parent' => 1,
    'forum_type' => 'forum',
    'news' => 1
]);

$queries->create('topics', [
    'forum_id' => 2,
    'topic_title' => 'Welcome to NamelessMC!',
    'topic_creator' => 1,
    'topic_last_user' => 1,
    'topic_date' => date('U'),
    'topic_reply_date' => date('U'),
    'label' => null
]);

$queries->create('posts', [
    'forum_id' => 2,
    'topic_id' => 1,
    'post_creator' => 1,
    'post_content' => Output::getClean(
        '&lt;p&gt;Welcome!&lt;/p&gt;
		&lt;p&gt;To get started with NamelessMC, visit your StaffCP using the blue gear icon in the top right of your screen.&lt;/p&gt;
		&lt;p&gt;If you need support, visit our Discord server: &lt;a href=&quot;https://discord.gg/nameless&quot; target=&quot;_blank&quot; rel=&quot;noopener&quot;&gt;https://discord.gg/nameless&lt;/a&gt;&lt;/p&gt;
		&lt;p&gt;Thank you and enjoy,&lt;/p&gt;
		&lt;p&gt;The NamelessMC Development team.&lt;/p&gt;'
    ),
    'post_date' => date('Y-m-d H:i:s'),
    'created' => date('U')
]);

// Permissions
for ($i = 0; $i < 4; $i++) {
    for ($n = 1; $n < 3; $n++) {
        $queries->create('forums_permissions', [
            'group_id' => $i,
            'forum_id' => $n,
            'view' => 1,
            'create_topic' => ($i == 0 ? 0 : 1),
            'edit_topic' => ($i == 0 ? 0 : 1),
            'create_post' => ($i == 0 ? 0 : 1),
            'view_other_topics' => 1,
            'moderate' => (($i == 2 || $i == 3) ? 1 : 0)
        ]);
    }
}

// Forum Labels
$queries->create('forums_labels', [
    'name' => 'Default',
    'html' => '<span class="badge badge-default">{x}</span>'
]);

$queries->create('forums_labels', [
    'name' => 'Primary',
    'html' => '<span class="badge badge-primary">{x}</span>'
]);

$queries->create('forums_labels', [
    'name' => 'Success',
    'html' => '<span class="badge badge-success">{x}</span>'
]);

$queries->create('forums_labels', [
    'name' => 'Info',
    'html' => '<span class="badge badge-info">{x}</span>'
]);

$queries->create('forums_labels', [
    'name' => 'Warning',
    'html' => '<span class="badge badge-warning">{x}</span>'
]);

$queries->create('forums_labels', [
    'name' => 'Danger',
    'html' => '<span class="badge badge-danger">{x}</span>'
]);

// Groups
$queries->create('groups', [
    'name' => 'Member',
    'group_html' => '<span class="badge badge-success">Member</span>',
    'group_html_lg' => '<span class="badge badge-success">Member</span>',
    'permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}',
    'default_group' => 1,
    'order' => 3
]);

$queries->create('groups', [
    'name' => 'Admin',
    'group_html' => '<span class="badge badge-danger">Admin</span>',
    'group_html_lg' => '<span class="badge badge-danger">Admin</span>',
    'group_username_color' => '#ff0000',
    'group_username_css' => '',
    'admin_cp' => 1,
    'permissions' => '{"administrator":1,"admincp.core":1,"admincp.core.api":1,"admincp.core.seo":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.core.placeholders":1,"admincp.integrations":1,"admincp.integrations.edit":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.security.group_sync":1,"admincp.core.emails_mass_message":1,"modcp.punishments.reset_avatar":1,"usercp.gif_avatar":1}',
    'order' => 1,
    'staff' => 1
]);

$queries->create('groups', [
    'name' => 'Moderator',
    'group_html' => '<span class="badge badge-primary">Moderator</span>',
    'group_html_lg' => '<span class="badge badge-primary">Moderator</span>',
    'admin_cp' => 1,
    'permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}',
    'order' => 2,
    'staff' => 1
]);

$queries->create('groups', [
    'name' => 'Unconfirmed Member',
    'group_html' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
    'group_html_lg' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
    'group_username_color' => '#6c757d',
    'order' => 4
]);

foreach (Language::LANGUAGES as $short_code => $meta) {
    $queries->create('languages', [
        'name' => $meta['name'],
        'short_code' => $short_code,
        'is_default' => (Session::get('default_language') == $short_code) ? 1 : 0
    ]);
}

$cache->setCache('languagecache');
$cache->store('language', Session::get('default_language'));

// Modules
$queries->create('modules', [
    'name' => 'Core',
    'enabled' => 1
]);

$queries->create('modules', [
    'name' => 'Forum',
    'enabled' => 1
]);

$queries->create('modules', [
    'name' => 'Discord Integration',
    'enabled' => 1
]);

$queries->create('modules', [
    'name' => 'Cookie Consent',
    'enabled' => 1
]);

$cache->setCache('modulescache');
$cache->store('enabled_modules', [
    [
        'name' => 'Core',
        'priority' => 1
    ],
    [
        'name' => 'Forum',
        'priority' => 4
    ],
    [
        'name' => 'Discord Integration',
        'priority' => 7
    ],
    [
        'name' => 'Cookie Consent',
        'priority' => 10
    ],
]);

$cache->store('module_core', true);
$cache->store('module_forum', true);

// Integrations
$queries->create('integrations', [
    'name' => 'Minecraft',
    'enabled' => 1,
    'can_unlink' => 0,
    'required' => 0
]);

$queries->create('integrations', [
    'name' => 'Discord',
    'enabled' => 1,
    'can_unlink' => 1,
    'required' => 0
]);

// Reactions
$queries->create('reactions', [
    'name' => 'Like',
    'html' => '<i class="fas fa-thumbs-up text-success"></i>',
    'enabled' => 1,
    'type' => 2
]);

$queries->create('reactions', [
    'name' => 'Dislike',
    'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
    'enabled' => 1,
    'type' => 0
]);

$queries->create('reactions', [
    'name' => 'Meh',
    'html' => '<i class="fas fa-meh text-warning"></i>',
    'enabled' => 1,
    'type' => 1
]);

// Settings
$queries->create('settings', [
    'name' => 'registration_enabled',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'displaynames',
    'value' => 'false'
]);

$queries->create('settings', [
    'name' => 'uuid_linking',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'recaptcha',
    'value' => 'false'
]);

$queries->create('settings', [
    'name' => 'recaptcha_type',
    'value' => 'reCaptcha'
]);

$queries->create('settings', [
    'name' => 'recaptcha_login',
    'value' => 'false'
]);

$queries->create('settings', [
    'name' => 'recaptcha_key',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'recaptcha_secret',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'email_verification',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 't_and_c',
    'value' => 'By registering on our website, you agree to the following:<p>' . $nameless_terms . '</p>'
]);

$queries->create('privacy_terms', [
    'name' => 'terms',
    'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
]);

$queries->create('privacy_terms', [
    'name' => 'cookies',
    'value' => '<span style="font-size:18px"><strong>What are cookies?</strong></span><br />Cookies are small files which are stored on your device by a website, unique to your web browser. The web browser will send these files to the website each time it communicates with the website.<br />Cookies are used by this website for a variety of reasons which are outlined below.<br /><br /><strong>Necessary cookies</strong><br />Necessary cookies are required for this website to function. These are used by the website to maintain your session, allowing for you to submit any forms, log into the website amongst other essential behaviour. It is not possible to disable these within the website, however you can disable cookies altogether via your browser.<br /><br /><strong>Functional cookies</strong><br />Functional cookies allow for the website to work as you choose. For example, enabling the &quot;Remember Me&quot; option as you log in will create a functional cookie to automatically log you in on future visits.<br /><br /><strong>Analytical cookies</strong><br />Analytical cookies allow both this website, and any third party services used by this website, to collect non-personally identifiable data about the user. This allows us (the website staff) to continue to improve the user experience and understand how the website is used.<br /><br />Further information about cookies can be found online, including the <a rel="nofollow noopener" target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICO&#39;s website</a> which contains useful links to further documentation about configuring your browser.<br /><br /><span style="font-size:18px"><strong>Configuring cookie use</strong></span><br />By default, only necessary cookies are used by this website. However, some website functionality may be unavailable until the use of cookies has been opted into.<br />You can opt into, or continue to disallow, the use of cookies using the cookie notice popup on this website. If you would like to update your preference, the cookie notice popup can be re-enabled by clicking the button below.'
]);

$queries->create('settings', [
    'name' => 'nameless_version',
    'value' => '2.0.0-pr12'
]);

$queries->create('settings', [
    'name' => 'version_checked',
    'value' => date('U')
]);

$queries->create('settings', [
    'name' => 'version_update',
    'value' => 'false'
]);

$queries->create('settings', [
    'name' => 'phpmailer',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'phpmailer_type',
    'value' => 'smtp'
]);

$queries->create('settings', [
    'name' => 'verify_accounts',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'mcassoc_key',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'mcassoc_instance',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'user_avatars',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'forum_layout',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'maintenance',
    'value' => 'false'
]);

$queries->create('settings', [
    'name' => 'avatar_site',
    'value' => 'cravatar'
]);

$queries->create('settings', [
    'name' => 'mc_integration',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'discord_bot_url',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'discord_bot_username',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'discord_integration',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'avatar_type',
    'value' => 'helmavatar'
]);

$queries->create('settings', [
    'name' => 'home_type',
    'value' => 'news'
]);
$cache->setCache('home_type');
$cache->store('type', 'news');

$queries->create('settings', [
    'name' => 'home_custom_content',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'forum_reactions',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'youtube_url',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'twitter_url',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'twitter_style',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'fb_url',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'ga_script',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'error_reporting',
    'value' => 0
]);
$cache->setCache('error_cache');
$cache->store('error_reporting', 0);

$queries->create('settings', [
    'name' => 'page_loading',
    'value' => 0
]);
$cache->setCache('page_load_cache');
$cache->store('page_load', 0);

$queries->create('settings', [
    'name' => 'unique_id',
    'value' => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 62)
]);

$queries->create('settings', [
    'name' => 'use_api',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'mc_api_key',
    'value' => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 32)
]);

$queries->create('settings', [
    'name' => 'external_query',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'followers',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'discord',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'language',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'timezone',
    'value' => 'Europe/London'
]);
$cache->setCache('timezone_cache');
$cache->store('timezone', 'Europe/London');

$queries->create('settings', [
    'name' => 'maintenance_message',
    'value' => 'This website is currently in maintenance mode.'
]);
$cache->setCache('maintenance_cache');
$cache->store('maintenance', [
    'maintenance' => 'false',
    'message' => 'This website is currently in maintenance mode.'
]);

$queries->create('settings', [
    'name' => 'authme',
    'value' => 0
]);

$queries->create('settings', [
    'name' => 'authme_db',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'default_avatar_type',
    'value' => 'minecraft'
]);

$queries->create('settings', [
    'name' => 'custom_default_avatar',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'private_profile',
    'value' => 1
]);

$queries->create('settings', [
    'name' => 'registration_disabled_message',
    'value' => null
]);

$queries->create('settings', [
    'name' => 'validate_user_action',
    'value' => '{"action":"promote","group":1}'
]);

$queries->create('settings', [
    'name' => 'login_method',
    'value' => 'email'
]);

$queries->create('settings', [
    'name' => 'username_sync',
    'value' => '1'
]);

$queries->create('privacy_terms', [
    'name' => 'privacy',
    'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
]);

$queries->create('settings', [
    'name' => 'status_page',
    'value' => '1'
]);

$queries->create('settings', [
    'name' => 'placeholders',
    'value' => '0'
]);

// Templates
$queries->create('templates', [
    'name' => 'DefaultRevamp',
    'enabled' => 1,
    'is_default' => 1
]);

$cache->setCache('templatecache');
$cache->store('default', 'DefaultRevamp');

$queries->create('panel_templates', [
    'name' => 'Default',
    'enabled' => 1,
    'is_default' => 1
]);
$cache->store('panel_default', 'Default');

// Widgets - initialise just a few default ones for now
$queries->create('widgets', [
    'name' => 'Online Staff',
    'enabled' => 1,
    'pages' => '["index","forum"]'
]);

$queries->create('widgets', [
    'name' => 'Online Users',
    'enabled' => 1,
    'pages' => '["index","forum"]'
]);

$queries->create('widgets', [
    'name' => 'Statistics',
    'enabled' => 1,
    'pages' => '["index","forum"]'
]);

$cache->setCache('Core-widgets');
$cache->store('enabled', [
    'Online Staff' => 1,
    'Online Users' => 1,
    'Statistics' => 1
]);

$config_path = $conf['core']['path'];
$config_path = ($config_path ? '/' . trim($config_path, '/') : '');

$cache->setCache('backgroundcache');
$cache->store('banner_image', $config_path . '/uploads/template_banners/homepage_bg_trimmed.jpg');
