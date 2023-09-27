<?php

class DatabaseInitialiser {

    private DB $_db;
    private Cache $_cache;

    private function __construct() {
        $this->_db = DB::getInstance();
        $this->_cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
    }

    public static function runPreUser() {
        $instance = new self();
        $instance->initialiseGroups();
        $instance->initialiseLanguages();
        $instance->initialiseModules();
        $instance->initialiseIntegrations();
        $instance->initialiseReactions();
        $instance->initialiseSettings();
        $instance->initialiseTasks();
        $instance->initialiseTemplates();
        $instance->initialiseWidgets();
    }

    public static function runPostUser() {
        $instance = new self();
        $instance->initialiseForum();
    }

    private function initialiseGroups(): void {
        $this->_db->insert('groups', [
            'name' => 'Member',
            'group_html' => '<span class="badge badge-success">Member</span>',
            'permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}',
            'order' => 3
        ]);

        $this->_db->insert('groups', [
            'name' => 'Admin',
            'group_html' => '<span class="badge badge-danger">Admin</span>',
            'group_username_color' => '#ff0000',
            'group_username_css' => '',
            'admin_cp' => true,
            'permissions' => '{"administrator":1,"admincp.core":1,"admincp.core.api":1,"admincp.core.seo":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.queue":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.core.placeholders":1,"admincp.members":1,"admincp.integrations":1,"admincp.integrations.edit":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.security.group_sync":1,"admincp.core.emails_mass_message":1,"modcp.punishments.reset_avatar":1,"usercp.gif_avatar":1}',
            'order' => 1,
            'staff' => true,
        ]);

        $this->_db->insert('groups', [
            'name' => 'Moderator',
            'group_html' => '<span class="badge badge-primary">Moderator</span>',
            'admin_cp' => true,
            'permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}',
            'order' => 2,
            'staff' => true,
        ]);

        $this->_db->insert('groups', [
            'name' => 'Unconfirmed Member',
            'group_html' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
            'group_username_color' => '#6c757d',
            'permissions' => '{}',
            'default_group' => true,
            'order' => 4
        ]);

        Util::setSetting('member_list_viewable_groups', json_encode([1, 2, 3, 4]), 'Members');
    }

    private function initialiseLanguages(): void {
        foreach (Language::LANGUAGES as $short_code => $meta) {
            $this->_db->insert('languages', [
                'name' => $meta['name'],
                'short_code' => $short_code,
                'is_default' => (Session::get('default_language') == $short_code) ? 1 : 0
            ]);
        }

        $this->_cache->setCache('languagecache');
        $this->_cache->store('language', Session::get('default_language'));
    }

    private function initialiseModules(): void {
        $this->_db->insert('modules', [
            'name' => 'Core',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Forum',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Discord Integration',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Cookie Consent',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Members',
            'enabled' => true,
        ]);

        $this->_cache->setCache('modulescache');
        $this->_cache->store('enabled_modules', [
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
            [
                'name' => 'Members',
                'priority' => 13
            ],
        ]);

        $this->_cache->store('module_core', true);
        $this->_cache->store('module_forum', true);
    }

    private function initialiseIntegrations(): void {
        $this->_db->insert('integrations', [
            'name' => 'Minecraft',
            'enabled' => true,
            'can_unlink' => false,
            'required' => false,
        ]);

        // TODO: should this be in the DiscordIntegration module...?
        $this->_db->insert('integrations', [
            'name' => 'Discord',
            'enabled' => true,
            'can_unlink' => true,
            'required' => false
        ]);
    }

    private function initialiseReactions(): void {
        $this->_db->insert('reactions', [
            'name' => 'Like',
            'html' => '<i class="fas fa-thumbs-up text-success"></i>',
            'enabled' => true,
            'type' => 2
        ]);

        $this->_db->insert('reactions', [
            'name' => 'Dislike',
            'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
            'enabled' => true,
            'type' => 0
        ]);

        $this->_db->insert('reactions', [
            'name' => 'Meh',
            'html' => '<i class="fas fa-meh text-warning"></i>',
            'enabled' => true,
            'type' => 1
        ]);
    }

    private function initialiseSettings(): void {
        Util::setSetting('registration_enabled', '1');
        Util::setSetting('displaynames', '0');
        Util::setSetting('uuid_linking', '1');
        Util::setSetting('recaptcha', '0');
        Util::setSetting('recaptcha_type', 'Recaptcha3');
        Util::setSetting('recaptcha_login', '0');
        Util::setSetting('email_verification', '1');
        Util::setSetting('nameless_version', '2.1.2');
        Util::setSetting('version_checked', date('U'));
        Util::setSetting('phpmailer', '0');
        Util::setSetting('user_avatars', '0');
        Util::setSetting('avatar_site', 'cravatar');
        Util::setSetting(Settings::MINECRAFT_INTEGRATION, '1');
        Util::setSetting('discord_integration', '0');
        Util::setSetting('avatar_type', 'helmavatar');
        Util::setSetting('home_type', 'news');
        Util::setSetting('forum_reactions', '1');
        Util::setSetting('error_reporting', '0');
        Util::setSetting('page_loading', '0');
        Util::setSetting('unique_id', substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 62));
        Util::setSetting('use_api', 0);
        Util::setSetting('mc_api_key', SecureRandom::alphanumeric());
        Util::setSetting('query_type', 'internal');
        Util::setSetting('player_list_limit', '20');
        Util::setSetting('timezone', $_SESSION['install_timezone']);
        Util::setSetting('maintenance', '0');
        Util::setSetting('maintenance_message', 'This website is currently in maintenance mode.');
        Util::setSetting('default_avatar_type', 'minecraft');
        Util::setSetting('private_profile', '1');
        Util::setSetting('validate_user_action', '{"action":"promote","group":1}');
        Util::setSetting('login_method', 'email');
        Util::setSetting('username_sync', '1');
        Util::setSetting('status_page', '0');
        Util::setSetting('placeholders', '0');

        $this->_db->insert('privacy_terms', [
            'name' => 'terms',
            'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'cookies',
            'value' => '<span style="font-size:18px"><strong>What are cookies?</strong></span><br />Cookies are small files which are stored on your device by a website, unique to your web browser. The web browser will send these files to the website each time it communicates with the website.<br />Cookies are used by this website for a variety of reasons which are outlined below.<br /><br /><strong>Necessary cookies</strong><br />Necessary cookies are required for this website to function. These are used by the website to maintain your session, allowing for you to submit any forms, log into the website amongst other essential behaviour. It is not possible to disable these within the website, however you can disable cookies altogether via your browser.<br /><br /><strong>Functional cookies</strong><br />Functional cookies allow for the website to work as you choose. For example, enabling the &quot;Remember Me&quot; option as you log in will create a functional cookie to automatically log you in on future visits.<br /><br /><strong>Analytical cookies</strong><br />Analytical cookies allow both this website, and any third party services used by this website, to collect non-personally identifiable data about the user. This allows us (the website staff) to continue to improve the user experience and understand how the website is used.<br /><br />Further information about cookies can be found online, including the <a rel="nofollow noopener" target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICO&#39;s website</a> which contains useful links to further documentation about configuring your browser.<br /><br /><span style="font-size:18px"><strong>Configuring cookie use</strong></span><br />By default, only necessary cookies are used by this website. However, some website functionality may be unavailable until the use of cookies has been opted into.<br />You can opt into, or continue to disallow, the use of cookies using the cookie notice popup on this website. If you would like to update your preference, the cookie notice popup can be re-enabled by clicking the button below.'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'privacy',
            'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
        ]);

        $nameless_terms = 'This website uses "Nameless" website software. The ' .
                        '"Nameless" software creators will not be held responsible for any content ' .
                        'that may be experienced whilst browsing this site, nor are they responsible ' .
                        'for any loss of data which may come about, for example a hacking attempt. ' .
                        'The website is run independently from the software creators, and any content' .
                        ' is the responsibility of the website administration.';
        Util::setSetting('t_and_c', 'By registering on our website, you agree to the following:<p>' . $nameless_terms . '</p>');
    }

    private function initialiseTasks(): void {
        GenerateSitemap::schedule(new Language('core', 'en_UK'));
    }

    private function initialiseTemplates(): void {
        $this->_db->insert('templates', [
            'name' => 'DefaultRevamp',
            'enabled' => true,
            'is_default' => true,
        ]);

        $this->_cache->setCache('templatecache');
        $this->_cache->store('default', 'DefaultRevamp');

        $this->_db->insert('panel_templates', [
            'name' => 'Default',
            'enabled' => true,
            'is_default' => true,
        ]);
        $this->_cache->store('panel_default', 'Default');

        $config_path = Config::get('core.path');
        if (!empty($config_path)) {
            $config_path = '/' . trim($config_path, '/');
        }

        $this->_cache->setCache('backgroundcache');
        $this->_cache->store('banner_image', $config_path . '/uploads/template_banners/homepage_bg_trimmed.jpg');
    }

    private function initialiseWidgets(): void {
        $this->_db->insert('widgets', [
            'name' => 'Online Staff',
            'enabled' => true,
            'pages' => '["index","forum"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Online Users',
            'enabled' => true,
            'pages' => '["index","forum"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Statistics',
            'enabled' => true,
            'pages' => '["index","forum"]'
        ]);

        $this->_cache->setCache('Core-widgets');
        $this->_cache->store('enabled', [
            'Online Staff' => 1,
            'Online Users' => 1,
            'Statistics' => 1
        ]);
    }

    private function initialiseForum() {
        $this->_db->insert('forums', [
            'forum_title' => 'Category',
            'forum_description' => 'The first forum category!',
            'forum_order' => 1,
            'forum_type' => 'category'
        ]);

        $this->_db->insert('forums', [
            'forum_title' => 'Forum',
            'forum_description' => 'The first discussion forum!',
            'forum_order' => 2,
            'parent' => 1,
            'forum_type' => 'forum',
            'news' => 1
        ]);

        $this->_db->insert('topics', [
            'forum_id' => 2,
            'topic_title' => 'Welcome to NamelessMC!',
            'topic_creator' => 1,
            'topic_last_user' => 1,
            'topic_date' => date('U'),
            'topic_reply_date' => date('U'),
            'label' => null
        ]);

        $this->_db->insert('posts', [
            'forum_id' => 2,
            'topic_id' => 1,
            'post_creator' => 1,
            'post_content' => <<<POST
                <p>Welcome!</p>
                <p>To get started with NamelessMC, visit your StaffCP using the blue gear icon in the top right of your screen.</p>
                <p>If you need support, visit our Discord server: <a href="https://discord.gg/nameless" target="_blank" rel="noopener">https://discord.gg/nameless</a></p>
                <p>Thank you and enjoy,</p>
                <p>The NamelessMC Development team.</p>
                POST,
            'post_date' => date('Y-m-d H:i:s'),
            'created' => date('U'),
            'last_edited' => date('U'),
        ]);

        // Must be updated afterwards due to foreign key
        $this->_db->update('forums', 2, [
            'last_post_date' => date('U'),
            'last_user_posted' => 1,
            'last_topic_posted' => 1,
        ]);

        // Permissions
        for ($i = 0; $i < 4; $i++) {
            for ($n = 1; $n < 3; $n++) {
                $this->_db->insert('forums_permissions', [
                    'group_id' => $i,
                    'forum_id' => $n,
                    'view' => true,
                    'create_topic' => ($i == 0 ? 0 : 1),
                    'edit_topic' => ($i == 0 ? 0 : 1),
                    'create_post' => ($i == 0 ? 0 : 1),
                    'view_other_topics' => true,
                    'moderate' => (($i == 2 || $i == 3) ? 1 : 0)
                ]);
            }
        }

        // Forum Labels
        $this->_db->insert('forums_labels', [
            'name' => 'Default',
            'html' => '<span class="badge badge-default">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Primary',
            'html' => '<span class="badge badge-primary">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Success',
            'html' => '<span class="badge badge-success">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Info',
            'html' => '<span class="badge badge-info">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Warning',
            'html' => '<span class="badge badge-warning">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Danger',
            'html' => '<span class="badge badge-danger">{x}</span>'
        ]);
    }
}
