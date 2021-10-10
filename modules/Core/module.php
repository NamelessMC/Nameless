<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Core module file
 */

class Core_Module extends Module {

    /** @var Language */
    private $_language;

    /** @var Configuration */
    private $_configuration;
    
    private static $_dashboard_graph = array(), $_notices = array(), $_user_actions = array();

    public function __construct($language, $pages, $user, $queries, $navigation, $cache, $endpoints){
        $this->_language = $language;
        $this->_configuration = new Configuration($cache);

        $name = 'Core';
        $author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
        $module_version = '2.0.0-pr12';
        $nameless_version = '2.0.0-pr12';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        // Define URLs which belong to this module
        $pages->add('Core', '/', 'pages/index.php');
        $pages->add('Core', '/api/v2', 'pages/api/v2/index.php');
        $pages->add('Core', '/contact', 'pages/contact.php');
        $pages->add('Core', '/home', 'pages/home.php', 'index', true);

        $pages->add('Core', '/login', 'pages/login.php');
        $pages->add('Core', '/logout', 'pages/logout.php');
        $pages->add('Core', '/profile', 'pages/profile.php', 'profile', true);
        $pages->add('Core', '/register', 'pages/register.php');
        $pages->add('Core', '/validate', 'pages/validate.php');
        $pages->add('Core', '/queries/admin_users', 'queries/admin_users.php');
        $pages->add('Core', '/queries/alerts', 'queries/alerts.php');
        $pages->add('Core', '/queries/dark_light_mode', 'queries/dark_light_mode.php');
        $pages->add('Core', '/queries/pms', 'queries/pms.php');
        $pages->add('Core', '/queries/servers', 'queries/servers.php');
        $pages->add('Core', '/queries/server', 'queries/server.php');
        $pages->add('Core', '/queries/user', 'queries/user.php');
        $pages->add('Core', '/queries/users', 'queries/users.php');
        $pages->add('Core', '/banner', 'pages/minecraft/banner.php');
        $pages->add('Core', '/terms', 'pages/terms.php');
        $pages->add('Core', '/privacy', 'pages/privacy.php');
        $pages->add('Core', '/forgot_password', 'pages/forgot_password.php');
        $pages->add('Core', '/complete_signup', 'pages/complete_signup.php');
        $pages->add('Core', '/status', 'pages/status.php', 'status');
        $pages->add('Core', '/leaderboards', 'pages/leaderboards.php', 'leaderboards');

        $pages->add('Core', '/user', 'pages/user/index.php');
        $pages->add('Core', '/user/settings', 'pages/user/settings.php');
        $pages->add('Core', '/user/messaging', 'pages/user/messaging.php');
        $pages->add('Core', '/user/alerts', 'pages/user/alerts.php');
        $pages->add('Core', '/user/placeholders', 'pages/user/placeholders.php');
        $pages->add('Core', '/user/acknowledge', 'pages/user/acknowledge.php');

        // Panel
        $pages->add('Core', '/panel', 'pages/panel/index.php');
        $pages->add('Core', '/panel/auth', 'pages/panel/auth.php');
        $pages->add('Core', '/panel/core/general_settings', 'pages/panel/general_settings.php');
        $pages->add('Core', '/panel/core/api', 'pages/panel/api.php');
        $pages->add('Core', '/panel/core/seo', 'pages/panel/seo.php');
        $pages->add('Core', '/panel/core/avatars', 'pages/panel/avatars.php');
        $pages->add('Core', '/panel/core/profile_fields', 'pages/panel/profile_fields.php');
        $pages->add('Core', '/panel/core/debugging_and_maintenance', 'pages/panel/debugging_and_maintenance.php');
        $pages->add('Core', '/panel/core/errors', 'pages/panel/errors.php');
        $pages->add('Core', '/panel/core/emails', 'pages/panel/emails.php');
        $pages->add('Core', '/panel/core/emails/errors', 'pages/panel/emails_errors.php');
        $pages->add('Core', '/panel/core/emails/mass_message', 'pages/panel/emails_mass_message.php');
        $pages->add('Core', '/panel/core/navigation', 'pages/panel/navigation.php');
        $pages->add('Core', '/panel/core/privacy_and_terms', 'pages/panel/privacy_and_terms.php');
        $pages->add('Core', '/panel/core/reactions', 'pages/panel/reactions.php');
        $pages->add('Core', '/panel/core/registration', 'pages/panel/registration.php');
        $pages->add('Core', '/panel/core/social_media', 'pages/panel/social_media.php');
        $pages->add('Core', '/panel/core/groups', 'pages/panel/groups.php');
        $pages->add('Core', '/panel/core/images', 'pages/panel/images.php');
        $pages->add('Core', '/panel/core/panel_templates', 'pages/panel/panel_templates.php');
        $pages->add('Core', '/panel/core/templates', 'pages/panel/templates.php');
        $pages->add('Core', '/panel/core/announcements', 'pages/panel/announcements.php');
        $pages->add('Core', '/panel/core/widgets', 'pages/panel/widgets.php');
        $pages->add('Core', '/panel/core/modules', 'pages/panel/modules.php');
        $pages->add('Core', '/panel/core/pages', 'pages/panel/pages.php');
        $pages->add('Core', '/panel/core/hooks', 'pages/panel/hooks.php');
        $pages->add('Core', '/panel/minecraft/placeholders', 'pages/panel/placeholders.php');
        $pages->add('Core', '/panel/minecraft', 'pages/panel/minecraft.php');
        $pages->add('Core', '/panel/minecraft/authme', 'pages/panel/minecraft_authme.php');
        $pages->add('Core', '/panel/minecraft/account_verification', 'pages/panel/minecraft_account_verification.php');
        $pages->add('Core', '/panel/minecraft/servers', 'pages/panel/minecraft_servers.php');
        $pages->add('Core', '/panel/minecraft/query_errors', 'pages/panel/minecraft_query_errors.php');
        $pages->add('Core', '/panel/minecraft/banners', 'pages/panel/minecraft_server_banners.php');
        $pages->add('Core', '/panel/discord', 'pages/panel/discord.php');
        $pages->add('Core', '/panel/security', 'pages/panel/security.php');
        $pages->add('Core', '/panel/update', 'pages/panel/update.php');
        $pages->add('Core', '/panel/upgrade', 'pages/panel/upgrade.php');
        $pages->add('Core', '/panel/users', 'pages/panel/users.php');
        $pages->add('Core', '/panel/users/edit', 'pages/panel/users_edit.php');
        $pages->add('Core', '/panel/users/ip_lookup', 'pages/panel/users_ip_lookup.php');
        $pages->add('Core', '/panel/users/punishments', 'pages/panel/users_punishments.php');
        $pages->add('Core', '/panel/users/reports', 'pages/panel/users_reports.php');
        $pages->add('Core', '/panel/user', 'pages/panel/user.php');

        $pages->add('Core', '/admin/update_execute', 'pages/admin/update_execute.php');

        // Ajax GET requests
        $pages->addAjaxScript(URL::build('/queries/servers'));

        // "More" dropdown
        $cache->setCache('navbar_icons');
        if($cache->isCached('more_dropdown_icon')){
            $icon = $cache->retrieve('more_dropdown_icon');
        } else
            $icon = '';

        $cache->setCache('navbar_order');
        if($cache->isCached('more_dropdown_order')){
            $order = $cache->retrieve('more_dropdown_order');
        } else
            $order = 2500;

        $navigation->addDropdown('more_dropdown', $language->get('general', 'more'), 'top', $order, $icon);

        // Custom pages
        $custom_pages = $queries->getWhere('custom_pages', array('id', '<>', 0));
        if(count($custom_pages)){
            $more = array();
            $cache->setCache('navbar_order');

            if($user->isLoggedIn()){
                // Check all groups
                $user_groups = $user->getAllGroupIds();

                foreach($custom_pages as $custom_page){
                    $redirect = null;

                    // Get redirect URL if enabled
                    if($custom_page->redirect == 1)
                        $redirect = Output::getClean($custom_page->link);

                    $pages->addCustom(Output::getClean($custom_page->url), Output::getClean($custom_page->title), !$custom_page->basic);

                    foreach($user_groups as $user_group){
                        $custom_page_permissions = $queries->getWhere('custom_pages_permissions', array('group_id', '=', $user_group));
                        if(count($custom_page_permissions)){
                            foreach($custom_page_permissions as $permission){
                                if($permission->page_id == $custom_page->id){
                                    if($permission->view == 1){
                                        // Check cache for order
                                        if(!$cache->isCached($custom_page->id . '_order')){
                                            // Create cache entry now
                                            $page_order = 200;
                                            $cache->store($custom_page->id . '_order', 200);
                                        } else {
                                            $page_order = $cache->retrieve($custom_page->id . '_order');
                                        }

                                        switch($custom_page->link_location){
                                            case 1:
                                                // Navbar
                                                $navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', $custom_page->target ? '_blank' : null, $page_order, $custom_page->icon);
                                                break;
                                            case 2:
                                                // "More" dropdown
                                                $more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'target' => $custom_page->target, 'icon' => $custom_page->icon, 'order' => $page_order);
                                                break;
                                            case 3:
                                                // Footer
                                                $navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', $custom_page->target ? '_blank' : null, 2000, $custom_page->icon);
                                                break;
                                        }
                                        break 2;
                                    } else
                                        break;
                                }
                            }
                        }
                    }
                }
            } else {
                $custom_page_permissions = $queries->getWhere('custom_pages_permissions', array('group_id', '=', 0));
                if(count($custom_page_permissions)){
                    foreach($custom_pages as $custom_page){
                        $redirect = null;

                        if($custom_page->redirect == 1)
                            $redirect = Output::getClean($custom_page->link);

                        $pages->addCustom(Output::getClean($custom_page->url), Output::getClean($custom_page->title), !$custom_page->basic);

                        foreach($custom_page_permissions as $permission){
                            if($permission->page_id == $custom_page->id){
                                if($permission->view == 1){
                                    // Check cache for order
                                    if(!$cache->isCached($custom_page->id . '_order')){
                                        // Create cache entry now
                                        $page_order = 200;
                                        $cache->store($custom_page->id . '_order', 200);
                                    } else {
                                        $page_order = $cache->retrieve($custom_page->id . '_order');
                                    }

                                    switch($custom_page->link_location){
                                        case 1:
                                            // Navbar
                                            $navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', $custom_page->target ? '_blank' : null, $page_order, $custom_page->icon);
                                            break;
                                        case 2:
                                            // "More" dropdown
                                            $more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'target' => $custom_page->target, 'icon' => $custom_page->icon, 'order' => $page_order);
                                            break;
                                        case 3:
                                            // Footer
                                            $navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', $custom_page->target ? '_blank' : null, 2000, $custom_page->icon);
                                            break;
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
            }
            $custom_page_permissions = null;

            if(count($more)){
                foreach($more as $item)
                    $navigation->addItemToDropdown('more_dropdown', $item['id'], $item['title'], $item['url'], 'top', ($item['target']) ? '_blank' : null, $item['icon'], $item['order']);
            }
        }
        $custom_pages = null;

        // Hooks
        HookHandler::registerEvent('registerUser', $language->get('admin', 'register_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid'), 'avatar_url' => $language->get('user', 'avatar'), 'content' => $language->get('general', 'content'), 'url' => $language->get('user', 'profile')));
        HookHandler::registerEvent('validateUser', $language->get('admin', 'validate_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid')));
        HookHandler::registerEvent('deleteUser', $language->get('admin', 'delete_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid'), 'email_address' => $language->get('user', 'email_address')));

        // Discord hook
        require_once(ROOT_PATH . '/modules/Core/hooks/DiscordHook.php');

        // Webhooks
        $cache->setCache('hooks');
        if($cache->isCached('hooks')){
            $hook_array = $cache->retrieve('hooks');
        } else {
            $hook_array = array();
            $hooks = $queries->tableExists('hooks');
            if (!empty($hooks)) {
                $hooks = $queries->getWhere('hooks', array('id', '<>', 0));
                if (count($hooks)) {
                    foreach ($hooks as $hook) {
                        if ($hook->action == 2) {
                            $action = 'DiscordHook::execute';
                        } else {
                            continue;
                        }

                        $hook_array[] = array(
                            'id' => $hook->id,
                            'url' => Output::getClean($hook->url),
                            'action' => $action,
                            'events' => json_decode($hook->events, true)
                        );
                    }
                    $cache->store('hooks', $hook_array);
                }
            }
        }
        HookHandler::registerHooks($hook_array);

        // Captcha
        $captchaPublicKey = $this->_configuration->get('Core', 'recaptcha_key');
        $captchaPrivateKey = $this->_configuration->get('Core', 'recaptcha_secret');
        $activeCaptcha = $this->_configuration->get('Core', 'recaptcha_type');

        CaptchaBase::addProvider(new hCaptcha($captchaPrivateKey, $captchaPublicKey));
        CaptchaBase::addProvider(new Recaptcha2($captchaPrivateKey, $captchaPublicKey));
        CaptchaBase::addProvider(new Recaptcha3($captchaPrivateKey, $captchaPublicKey));
        CaptchaBase::setActiveProvider($activeCaptcha);

        // Avatar Sources
        AvatarSource::registerSource(new CrafatarAvatarSource());
        AvatarSource::registerSource(new CraftheadAvatarSource());
        AvatarSource::registerSource(new CravatarAvatarSource());
        AvatarSource::registerSource(new MCHeadsAvatarSource());
        AvatarSource::registerSource(new MinotarAvatarSource());
        AvatarSource::registerSource(new NamelessMCAvatarSource($language));
        AvatarSource::registerSource(new VisageAvatarSource());
        AvatarSource::setActiveSource(DEFAULT_AVATAR_SOURCE);

        // Autoload API Endpoints
        Util::loadEndpoints(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Core', 'includes', 'endpoints')), $endpoints);
    }

    public function onInstall(){
        // Not necessary for Core
    }

    public function onUninstall(){
        // Not necessary for Core
    }

    public function onEnable(){
        // Not necessary for Core
    }

    public function onDisable(){
        // Not necessary for Core
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template){
        $language = $this->_language;

        // Permissions
        PermissionHandler::registerPermissions($language->get('admin', 'administrator'), array(
            'administrator' => $language->get('admin', 'administrator') . ' &raquo; ' . $language->get('admin', 'administrator_permission_info'),
        ));
        
        // AdminCP
        PermissionHandler::registerPermissions($language->get('moderator', 'staff_cp'), array(
            'admincp.core' => $language->get('admin', 'core'),
            'admincp.core.api' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'api'),
            'admincp.core.seo' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'seo'),
            'admincp.core.general' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'general_settings'),
            'admincp.core.avatars' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'avatars'),
            'admincp.core.fields' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'custom_fields'),
            'admincp.core.debugging' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'debugging_and_maintenance'),
            'admincp.errors' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'debugging_and_maintenance') . ' &raquo; ' . $language->get('admin', 'error_logs'),
            'admincp.core.emails' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'emails'),
            'admincp.core.emails_mass_message' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'emails_mass_message'),
            'admincp.core.navigation' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'navigation'),
            'admincp.core.reactions' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('user', 'reactions'),
            'admincp.core.registration' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'registration'),
            'admincp.core.social_media' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'social_media'),
            'admincp.core.terms' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'privacy_and_terms'),
            'admincp.core.hooks' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'hooks'),
            'admincp.core.announcements' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'announcements'),
            'admincp.core.placeholders' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'placeholders'),
            'admincp.integrations' => $language->get('admin', 'integrations'),
            'admincp.minecraft' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft'),
            'admincp.discord' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'discord'),
            'admincp.minecraft.authme' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'authme_integration'),
            'admincp.minecraft.verification' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'account_verification'),
            'admincp.minecraft.servers' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'minecraft_servers'),
            'admincp.minecraft.query_errors' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'query_errors'),
            'admincp.minecraft.banners' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'server_banners'),
            'admincp.modules' => $language->get('admin', 'modules'),
            'admincp.pages' => $language->get('admin', 'pages'),
            'admincp.security' => $language->get('admin', 'security'),
            'admincp.security.acp_logins' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'acp_logins'),
            'admincp.security.template' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'template_changes'),
            'admincp.security.emails' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'email_logs'),
            'admincp.security.group_sync' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'group_sync_logs'),
            'admincp.security.all' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'all_logs'),
            'admincp.styles' => $language->get('admin', 'styles'),
            'admincp.styles.panel_templates' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'panel_templates'),
            'admincp.styles.templates' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'templates'),
            'admincp.styles.templates.edit' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'templates') . ' &raquo; ' . $language->get('general', 'edit'),
            'admincp.styles.images' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'images'),
            'admincp.update' => $language->get('admin', 'update'),
            'admincp.users' => $language->get('admin', 'user_management'),
            'modcp.ip_lookup' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'ip_lookup'),
            'modcp.punishments' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments'),
            'modcp.punishments.reset_avatar' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'reset_avatar'),
            'modcp.punishments.warn' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'warn_user'),
            'modcp.punishments.ban' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'ban_user'),
            'modcp.punishments.banip' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'ban_ip'),
            'modcp.punishments.revoke' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'revoke'),
            'modcp.reports' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'reports'),
            'modcp.profile_banner_reset' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('moderator', 'reset_profile_banner'),
            'admincp.users.edit' => $language->get('admin', 'user_management') . ' &raquo; ' . $language->get('admin', 'users') . ' &raquo; ' . $language->get('general', 'edit'),
            'admincp.groups' => $language->get('admin', 'groups'),
            'admincp.groups.self' => $language->get('admin', 'groups') . ' &raquo; ' . $language->get('admin', 'can_edit_own_group'),
            'admincp.widgets' => $language->get('admin', 'widgets'),
        ));

        // UserCP
        PermissionHandler::registerPermissions('UserCP', array(
            'usercp.messaging' => $language->get('user', 'messaging'),
            'usercp.signature' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'signature'),
            'usercp.private_profile' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'private_profile'),
            'usercp.nickname' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'nickname'),
            'usercp.profile_banner' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'upload_profile_banner'),
            'usercp.gif_avatar' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'gif_avatar')
        ));

        // Profile Page
        PermissionHandler::registerPermissions('Profile', array(
            'profile.private.bypass' => $language->get('general', 'bypass') . ' &raquo; ' . $language->get('user', 'private_profile')
        ));

        // Sitemap
        $pages->registerSitemapMethod(ROOT_PATH . '/modules/Core/classes/Core_Sitemap.php', 'Core_Sitemap::generateSitemap');

        // Queries
        $queries = new Queries();

        // Widgets
        // Facebook
        require_once(ROOT_PATH . '/modules/Core/widgets/FacebookWidget.php');
        $cache->setCache('social_media');
        $fb_url = $cache->retrieve('facebook');
        if($fb_url){
            // Active pages
            $module_pages = $widgets->getPages('Facebook');

            $widgets->add(new FacebookWidget($module_pages, $fb_url));
        }

        // Twitter
        require_once(ROOT_PATH . '/modules/Core/widgets/TwitterWidget.php');
        $twitter = $cache->retrieve('twitter');

        if($twitter){
            $theme = $cache->retrieve('twitter_theme');
            $module_pages = $widgets->getPages('Twitter');

            $widgets->add(new TwitterWidget($module_pages, $twitter, $theme));
        }

        // Discord
        require_once(ROOT_PATH . '/modules/Core/widgets/DiscordWidget.php');
        $discord = $cache->retrieve('discord');
        $module_pages = $widgets->getPages('Discord');
        $widgets->add(new DiscordWidget($module_pages, $language, $cache, $discord));

        // Profile Posts
        require_once(ROOT_PATH . '/modules/Core/widgets/ProfilePostsWidget.php');
        $module_pages = $widgets->getPages('Latest Profile Posts');
        $widgets->add(new ProfilePostsWidget($module_pages, $smarty, $language, $cache, $user, new Timeago(TIMEZONE)));

        // Online staff
        require_once(ROOT_PATH . '/modules/Core/widgets/OnlineStaff.php');
        $module_pages = $widgets->getPages('Online Staff');
        $widgets->add(new OnlineStaffWidget($module_pages, $smarty, array('title' => $language->get('general', 'online_staff'), 'no_online_staff' => $language->get('general', 'no_online_staff'), 'total_online_staff' => $language->get('general', 'total_online_staff')), $cache));

        // Online users
        require_once(ROOT_PATH . '/modules/Core/widgets/OnlineUsers.php');
        $module_pages = $widgets->getPages('Online Users');
        $widgets->add(new OnlineUsersWidget($module_pages, $cache, $smarty, array('title' => $language->get('general', 'online_users'), 'no_online_users' => $language->get('general', 'no_online_users'), 'total_online_users' => $language->get('general', 'total_online_users'))));

        // Online users
        require_once(ROOT_PATH . '/modules/Core/widgets/ServerStatusWidget.php');
        $module_pages = $widgets->getPages('Server Status');
        $widgets->add(new ServerStatusWidget($module_pages, $smarty, $language, $cache));

        // Statistics
        require_once(ROOT_PATH . '/modules/Core/widgets/StatsWidget.php');
        $module_pages = $widgets->getPages('Statistics');
        $widgets->add(new StatsWidget($module_pages, $smarty, array(
            'statistics' => $language->get('general', 'statistics'),
            'users_registered' => $language->get('general', 'users_registered'),
            'latest_member' => $language->get('general', 'latest_member'),
            'forum_stats' => $language->get('general', 'forum_statistics'),
            'total_threads' => $language->get('general', 'total_threads'),
            'total_posts' => $language->get('general', 'total_posts'),
            'users_online' => $language->get('general', 'online_users'),
            'guests_online' => $language->get('general', 'online_guests'),
            'total_online' => $language->get('general', 'total_online'),
        ), $cache));

        // Validate user hook
        $cache->setCache('validate_action');
        if($cache->isCached('validate_action')){
            $validate_action = $cache->retrieve('validate_action');

        } else {
            $validate_action = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
            $validate_action = $validate_action[0]->value;
            $validate_action = json_decode($validate_action, true);

            $cache->store('validate_action', $validate_action);

        }

        if($validate_action['action'] == 'promote') {
            require_once(ROOT_PATH . '/modules/Core/hooks/ValidateHook.php');
            HookHandler::registerHook('validateUser', 'ValidateHook::validatePromote');
            define('VALIDATED_DEFAULT', $validate_action['group']);
        }

        // Define default group pre validation
        $cache->setCache('pre_validation_default');
        $group_id = null;

        if ($cache->isCached('pre_validation_default')) {
            $group_id = $cache->retrieve('pre_validation_default');

        } else {
            $group_id = $queries->getWhere('groups', array('default_group', '=', '1'));
            $group_id = $group_id[0]->id;
        }

        define('PRE_VALIDATED_DEFAULT', $group_id);

        // Check for updates
        if($user->isLoggedIn()){
            if($user->hasPermission('admincp.update')){
                $cache->setCache('update_check');
                if($cache->isCached('update_check')){
                    $update_check = $cache->retrieve('update_check');
                } else {
                    $update_check = Util::updateCheck();
                    $cache->store('update_check', $update_check, 3600);
                }

                $current_version = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
                $current_version = $current_version[0]->value;

                $update_check = json_decode($update_check);

                if(!isset($update_check->error) && !isset($update_check->no_update) && isset($update_check->new_version)){
                    $smarty->assign(array(
                        'NEW_UPDATE' => (isset($update_check->urgent) && $update_check->urgent == 'true') ? $language->get('admin', 'new_urgent_update_available') : $language->get('admin', 'new_update_available'),
                        'NEW_UPDATE_URGENT' => (isset($update_check->urgent) && $update_check->urgent == 'true'),
                        'CURRENT_VERSION' => str_replace('{x}', Output::getClean($current_version), $language->get('admin', 'current_version_x')),
                        'NEW_VERSION' => str_replace('{x}', Output::getClean($update_check->new_version), $language->get('admin', 'new_version_x')),
                        'UPDATE' => $language->get('admin', 'update'),
                        'UPDATE_LINK' => URL::build('/panel/update')
                    ));
                }
            }
        }

        if(defined('MINECRAFT') && MINECRAFT === true){
            // Status page?
            $cache->setCache('status_page');
            if($cache->isCached('enabled')){
                $status_enabled = $cache->retrieve('enabled');

            } else {
                $status_enabled = $queries->getWhere('settings', array('name', '=', 'status_page'));
                if($status_enabled[0]->value == 1)
                    $status_enabled = 1;
                else
                    $status_enabled = 0;

                $cache->store('enabled', $status_enabled);

            }

            if($status_enabled == 1){
                // Add status link to navbar
                $cache->setCache('navbar_order');
                if(!$cache->isCached('status_order')){
                    $status_order = 3;
                    $cache->store('status_order', 3);
                } else{
                    $status_order = $cache->retrieve('status_order');
                }

                $cache->setCache('navbar_icons');
                if(!$cache->isCached('status_icon'))
                    $icon = '';
                else
                    $icon = $cache->retrieve('status_icon');

                $navs[0]->add('status', $language->get('general', 'status'), URL::build('/status'), 'top', null, $status_order, $icon);
            }
        }

        $leaderboard_placeholders = Placeholders::getInstance()->getLeaderboardPlaceholders();

        // Only add leaderboard link if there is at least one enabled placeholder
        if (count($leaderboard_placeholders)) {

            $cache->setCache('navbar_order');
            if (!$cache->isCached('leaderboards_order')) {
                $leaderboards_order = 4;
                $cache->store('leaderboards_order', 4);
            } else {
                $leaderboards_order = $cache->retrieve('leaderboards_order');
            }

            $cache->setCache('navbar_icons');
            if (!$cache->isCached('leaderboards_icon'))
                $leaderboards_icon = '';
            else
                $leaderboards_icon = $cache->retrieve('leaderboards_icon');

            $navs[0]->add('leaderboards', $language->get('general', 'leaderboards'), URL::build('/leaderboards'), 'top', null, $leaderboards_order, $leaderboards_icon);
        }

        // Check page type (frontend or backend)
        if(defined('FRONT_END')){
            // Minecraft integration?
            if(defined('MINECRAFT') && MINECRAFT === true){
                // Query main server
                $cache->setCache('mc_default_server');

                // Already cached?
                if($cache->isCached('default_query')) {
                    $result = $cache->retrieve('default_query');
                    $default = $cache->retrieve('default');
                } else {
                    if($cache->isCached('default')){
                        $default = $cache->retrieve('default');
                        $sub_servers = $cache->retrieve('default_sub');
                    } else {
                        // Get default server from database
                        $default = $queries->getWhere('mc_servers', array('is_default', '=', 1));
                        if(count($default)){
                            // Get sub-servers of default server
                            $sub_servers = $queries->getWhere('mc_servers', array('parent_server', '=', $default[0]->id));
                            if(count($sub_servers))
                                $cache->store('default_sub', $sub_servers);
                            else
                                $cache->store('default_sub', array());

                            $default = $default[0];

                            $cache->store('default', $default, 60);
                        } else
                            $cache->store('default', null, 60);
                    }

                    if(!is_null($default) && isset($default->ip)){
                        $full_ip = array('ip' => $default->ip . (is_null($default->port) ? '' : ':' . $default->port), 'pre' => $default->pre, 'name' => $default->name);

                        // Get query type
                        $query_type = $queries->getWhere('settings', array('name', '=', 'external_query'));
                        if(count($query_type)){
                            if($query_type[0]->value == '1')
                                $query_type = 'external';
                            else
                                $query_type = 'internal';
                        } else
                            $query_type = 'internal';

                        if(isset($sub_servers) && count($sub_servers)){
                            $servers = array($full_ip);

                            foreach($sub_servers as $server)
                                $servers[] = array('ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port), 'pre' => $server->pre, 'name' => $server->name);

                            $result = MCQuery::multiQuery($servers, $query_type, $language, true, $queries);

                            if(isset($result['status_value']) && $result['status_value'] == 1){
                                $result['status'] = $language->get('general', 'online');

                                if($result['total_players'] == 1){
                                    $result['status_full'] = $language->get('general', 'currently_1_player_online');
                                    $result['x_players_online'] = $language->get('general', 'currently_1_player_online');
                                } else {
                                    $result['status_full'] = str_replace('{x}', $result['total_players'], $language->get('general', 'currently_x_players_online'));
                                    $result['x_players_online'] = str_replace('{x}', $result['total_players'], $language->get('general', 'currently_x_players_online'));
                                }

                            } else {
                                $result['status'] = $language->get('general', 'offline');
                                $result['status_full'] = $language->get('general', 'server_offline');
                                $result['server_offline'] = $language->get('general', 'server_offline');

                            }

                        } else {
                            $result = MCQuery::singleQuery($full_ip, $query_type, $language, $queries);

                            if(isset($result['status_value']) && $result['status_value'] == 1){
                                $result['status'] = $language->get('general', 'online');

                                if($result['player_count'] == 1){
                                    $result['status_full'] = $language->get('general', 'currently_1_player_online');
                                    $result['x_players_online'] = $language->get('general', 'currently_1_player_online');
                                } else {
                                    $result['status_full'] = str_replace('{x}', $result['player_count'], $language->get('general', 'currently_x_players_online'));
                                    $result['x_players_online'] = str_replace('{x}', $result['player_count'], $language->get('general', 'currently_x_players_online'));
                                }

                            } else {
                                $result['status'] = $language->get('general', 'offline');
                                $result['status_full'] = $language->get('general', 'server_offline');
                                $result['server_offline'] = $language->get('general', 'server_offline');

                            }

                        }

                        // Cache for 1 minute
                        $cache->store('default_query', $result, 60);
                    }
                }

                $smarty->assign('MINECRAFT', true);

                if(isset($result))
                    $smarty->assign('SERVER_QUERY', $result);

                if(!is_null($default) && isset($default->ip)){
                    $smarty->assign('CONNECT_WITH', str_replace('{x}', '<span id="ip">' . Output::getClean($default->ip . ($default->port && $default->port != 25565 ? ':' . $default->port : '')) . '</span>', $language->get('general', 'connect_with_ip_x')));
                    $smarty->assign('DEFAULT_IP', Output::getClean($default->ip . ($default->port != 25565 ? ':' . $default->port : '')));
                    $smarty->assign('CLICK_TO_COPY_TOOLTIP', $language->get('general', 'click_to_copy_tooltip'));
                    $smarty->assign('COPIED', $language->get('general', 'copied'));
                } else {
                    $smarty->assign('CONNECT_WITH', '');
                    $smarty->assign('DEFAULT_IP', '');
                }

                $smarty->assign('SERVER_OFFLINE', $language->get('general', 'server_offline'));

            }

            if(defined('PAGE') && PAGE == 'user_query'){
                // Collection
                $user_id = $smarty->getTemplateVars('USER_ID');

                $timeago = new Timeago(TIMEZONE);

                if($user_id){
                    $user_query = $queries->getWhere('users', array('id', '=', $user_id));
                    if(count($user_query)){
                        $user_query = $user_query[0];
                        $smarty->assign('REGISTERED', str_replace('{x}', $timeago->inWords(date('Y-m-d H:i:s', $user_query->joined), $language->getTimeLanguage()), $language->get('user', 'registered_x')));
                    }
                }
            }

        } else {
            // Navigation
            $cache->setCache('panel_sidebar');
            if(!$cache->isCached('dashboard_order')){
                $order = 1;
                $cache->store('dashboard_order', 1);
            } else {
                $order = $cache->retrieve('dashboard_order');
            }

            if(!$cache->isCached('dashboard_icon')){
                $icon = '<i class="nav-icon fas fa-home"></i>';
                $cache->store('dashboard_icon', $icon);
            } else
                $icon = $cache->retrieve('dashboard_icon');

            $navs[2]->add('core_divider', mb_strtoupper($language->get('admin', 'core')), 'divider', 'top', null, $order, '');
            $navs[2]->add('dashboard', $language->get('admin', 'dashboard'), URL::build('/panel'), 'top', null, $order, $icon);

            if($user->hasPermission('admincp.core')){
                if(!$cache->isCached('configuration_order')){
                    $order = 2;
                    $cache->store('configuration_order', 2);
                } else {
                    $order = $cache->retrieve('configuration_order');
                }

                if(!$cache->isCached('configuration_icon')){
                    $icon = '<i class="nav-icon fas fa-wrench"></i>';
                    $cache->store('configuration_icon', $icon);
                } else
                    $icon = $cache->retrieve('configuration_icon');

                $navs[2]->addDropdown('core_configuration', $language->get('admin', 'configuration'), 'top', $order, $icon);

                if($user->hasPermission('admincp.core.general')){
                    if(!$cache->isCached('general_settings_icon')){
                        $icon = '<i class="nav-icon fas fa-cogs"></i>';
                        $cache->store('general_settings_icon', $icon);
                    } else
                        $icon = $cache->retrieve('general_settings_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'general_settings', $language->get('admin', 'general_settings'), URL::build('/panel/core/general_settings'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.api')){
                    if(!$cache->isCached('api_icon')){
                        $icon = '<i class="nav-icon fas fa-code"></i>';
                        $cache->store('api_icon', $icon);
                    } else
                        $icon = $cache->retrieve('api_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'api', $language->get('admin', 'api'), URL::build('/panel/core/api'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.seo')){
                    if(!$cache->isCached('seo_icon')){
                        $icon = '<i class="nav-icon fas fa-globe"></i>';
                        $cache->store('seo_icon', $icon);
                    } else
                        $icon = $cache->retrieve('seo_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'seo', $language->get('admin', 'seo'), URL::build('/panel/core/seo'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.avatars')){
                    if(!$cache->isCached('avatars_icon')){
                        $icon = '<i class="nav-icon fas fa-image"></i>';
                        $cache->store('avatars_icon', $icon);
                    } else
                        $icon = $cache->retrieve('avatars_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'avatars', $language->get('admin', 'avatars'), URL::build('/panel/core/avatars'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.fields')){
                    if(!$cache->isCached('custom_profile_fields_icon')){
                        $icon = '<i class="nav-icon fas fa-id-card"></i>';
                        $cache->store('custom_profile_fields_icon', $icon);
                    } else
                        $icon = $cache->retrieve('custom_profile_fields_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'custom_profile_fields', $language->get('admin', 'custom_fields'), URL::build('/panel/core/profile_fields'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.debugging')){
                    if(!$cache->isCached('debugging_icon')){
                        $icon = '<i class="nav-icon fas fa-tachometer-alt"></i>';
                        $cache->store('debugging_icon', $icon);
                    } else
                        $icon = $cache->retrieve('debugging_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'debugging_and_maintenance', $language->get('admin', 'maintenance'), URL::build('/panel/core/debugging_and_maintenance'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.emails')){
                    if(!$cache->isCached('email_icon')){
                        $icon = '<i class="nav-icon fas fa-envelope"></i>';
                        $cache->store('email_icon', $icon);
                    } else
                        $icon = $cache->retrieve('email_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'emails', $language->get('admin', 'emails'), URL::build('/panel/core/emails'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.navigation')){
                    if(!$cache->isCached('navigation_icon')){
                        $icon = '<i class="nav-icon fas fa-bars"></i>';
                        $cache->store('navigation_icon', $icon);
                    } else
                        $icon = $cache->retrieve('navigation_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'navigation', $language->get('admin', 'navigation'), URL::build('/panel/core/navigation'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.terms')){
                    if(!$cache->isCached('privacy_and_terms_icon')){
                        $icon = '<i class="nav-icon fas fa-file-alt"></i>';
                        $cache->store('privacy_and_terms_icon', $icon);
                    } else
                        $icon = $cache->retrieve('privacy_and_terms_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'privacy_and_terms', $language->get('admin', 'privacy_and_terms'), URL::build('/panel/core/privacy_and_terms'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.reactions')){
                    if(!$cache->isCached('reactions_icon')){
                        $icon = '<i class="nav-icon fas fa-smile"></i>';
                        $cache->store('reactions_icon', $icon);
                    } else
                        $icon = $cache->retrieve('reactions_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'reactions', $language->get('user', 'reactions'), URL::build('/panel/core/reactions'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.registration')){
                    if(!$cache->isCached('registration_icon')){
                        $icon = '<i class="nav-icon fas fa-user-plus"></i>';
                        $cache->store('registration_icon', $icon);
                    } else
                        $icon = $cache->retrieve('registration_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'registration', $language->get('admin', 'registration'), URL::build('/panel/core/registration'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.social_media')){
                    if(!$cache->isCached('social_media_icon')){
                        $icon = '<i class="nav-icon fas fa-users"></i>';
                        $cache->store('social_media_icon', $icon);
                    } else
                        $icon = $cache->retrieve('social_media_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'social_media', $language->get('admin', 'social_media'), URL::build('/panel/core/social_media'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.core.hooks')){
                    if(!$cache->isCached('hooks_icon')){
                        $icon = '<i class="nav-icon fas fa-link"></i>';
                        $cache->store('hooks_icon', $icon);
                    } else
                        $icon = $cache->retrieve('hooks_icon');

                    $navs[2]->addItemToDropdown('core_configuration', 'hooks', $language->get('admin', 'hooks'), URL::build('/panel/core/hooks'), 'top', null, $icon, $order);
                }
            }

            if($user->hasPermission('admincp.groups')){
                if(!$cache->isCached('groups_order')){
                    $order = 3;
                    $cache->store('groups_order', 3);
                } else {
                    $order = $cache->retrieve('groups_order');
                }

                if(!$cache->isCached('groups_icon')){
                    $icon = '<i class="nav-icon fas fa-address-book"></i>';
                    $cache->store('group_icon', $icon);
                } else
                    $icon = $cache->retrieve('group_icon');

                $navs[2]->add('groups', $language->get('admin', 'groups'), URL::build('/panel/core/groups'), 'top', null, $order, $icon);
            }

            if ($user->hasPermission('admincp.core.announcements')) {
                if (!$cache->isCached('announcements_order')) {
                    $order = 4;
                    $cache->store('announcements_order', 4);
                } else {
                    $order = $cache->retrieve('announcements_order');
                }

                if (!$cache->isCached('announcements_icon')) {
                    $icon = '<i class="nav-icon fas fa-bullhorn"></i>';
                    $cache->store('announcements_icon', $icon);
                } else
                $icon = $cache->retrieve('announcements_icon');

                $navs[2]->add('announcements', $language->get('admin', 'announcements'), URL::build('/panel/core/announcements'), 'top', null, $order, $icon);
            }

            if($user->hasPermission('admincp.integrations')){
                if(!$cache->isCached('integrations_order')){
                    $order = 5;
                    $cache->store('integrations_order', 5);
                } else {
                    $order = $cache->retrieve('integrations_order');
                }

                if(!$cache->isCached('integrations_icon')){
                    $icon = '<i class="nav-icon fas fa-plug"></i>';
                    $cache->store('integrations_icon', $icon);
                } else
                    $icon = $cache->retrieve('integrations_icon');

                $navs[2]->addDropdown('integrations', $language->get('admin', 'integrations'), 'top', $order, $icon);
            }

            if($user->hasPermission('admincp.minecraft')){
                if(!$cache->isCached('minecraft_icon')){
                    $icon = '<i class="nav-icon fas fa-cubes"></i>';
                    $cache->store('minecraft_icon', $icon);
                } else
                    $icon = $cache->retrieve('minecraft_icon');

                $navs[2]->addItemToDropdown('integrations', 'minecraft', $language->get('admin', 'minecraft'), URL::build('/panel/minecraft'), 'top', null, $icon, $order);
            }

            if ($user->hasPermission('admincp.discord')) {
                if (!$cache->isCached('discord_icon')) {
                    $icon = '<i class="nav-icon fab fa-discord"></i>';
                    $cache->store('discord_icon', $icon);
                } else
                $icon = $cache->retrieve('discord_icon');

                $navs[2]->addItemToDropdown('integrations', 'discord', $language->get('admin', 'discord'), URL::build('/panel/discord'), 'top', null, $icon, $order);
            }

            if($user->hasPermission('admincp.styles') || $user->hasPermission('admincp.sitemap') || $user->hasPermission('admincp.widgets')){
                if(!$cache->isCached('layout_order')){
                    $order = 6;
                    $cache->store('layout_order', 6);
                } else {
                    $order = $cache->retrieve('layout_order');
                }

                if(!$cache->isCached('layout_icon')){
                    $icon = '<i class="nav-icon fas fa-object-group"></i>';
                    $cache->store('layout_icon', $icon);
                } else
                    $icon = $cache->retrieve('layout_icon');

                $navs[2]->addDropdown('layout', $language->get('admin', 'layout'), 'top', $order, $icon);

                if($user->hasPermission('admincp.styles.images')){
                    if(!$cache->isCached('images_icon')){
                        $icon = '<i class="nav-icon fas fa-images"></i>';
                        $cache->store('images_icon', $icon);
                    } else
                        $icon = $cache->retrieve('images_icon');

                    $navs[2]->addItemToDropdown('layout', 'images', $language->get('admin', 'images'), URL::build('/panel/core/images'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.styles.panel_templates')){
                    if(!$cache->isCached('panel_templates_icon')){
                        $icon = '<i class="nav-icon fas fa-tachometer-alt"></i>';
                        $cache->store('panel_templates_icon', $icon);
                    } else
                        $icon = $cache->retrieve('panel_templates_icon');

                    $navs[2]->addItemToDropdown('layout', 'panel_templates', $language->get('admin', 'panel_templates'), URL::build('/panel/core/panel_templates'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.styles')){
                    if(!$cache->isCached('templates_icon')){
                        $icon = '<i class="nav-icon fas fa-paint-brush"></i>';
                        $cache->store('templates_icon', $icon);
                    } else
                        $icon = $cache->retrieve('templates_icon');

                    $navs[2]->addItemToDropdown('layout', 'template', $language->get('admin', 'templates'), URL::build('/panel/core/templates'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('admincp.widgets')){
                    if(!$cache->isCached('widgets_icon')){
                        $icon = '<i class="nav-icon fas fa-th"></i>';
                        $cache->store('widgets_icon', $icon);
                    } else
                        $icon = $cache->retrieve('widgets_icon');

                    $navs[2]->addItemToDropdown('layout', 'widgets', $language->get('admin', 'widgets'), URL::build('/panel/core/widgets'), 'top', null, $icon, $order);
                }
            }

            if($user->hasPermission('admincp.modules')){
                if(!$cache->isCached('modules_order')){
                    $order = 7;
                    $cache->store('modules_order', 7);
                } else {
                    $order = $cache->retrieve('modules_order');
                }

                if(!$cache->isCached('modules_icon')){
                    $icon = '<i class="nav-icon fas fa-puzzle-piece"></i>';
                    $cache->store('modules_icon', $icon);
                } else
                    $icon = $cache->retrieve('modules_icon');

                $navs[2]->add('modules', $language->get('admin', 'modules'), URL::build('/panel/core/modules'), 'top', null, $order, $icon);
            }

            if($user->hasPermission('admincp.pages')){
                if(!$cache->isCached('pages_order')){
                    $order = 8;
                    $cache->store('pages_order', 8);
                } else {
                    $order = $cache->retrieve('pages_order');
                }
                
                if(!$cache->isCached('pages_icon')){
                    $icon = '<i class="nav-icon fas fa-file"></i>';
                    $cache->store('pages_icon', $icon);
                } else
                    $icon = $cache->retrieve('pages_icon');

                $navs[2]->add('custom_pages', $language->get('admin', 'custom_pages'), URL::build('/panel/core/pages'), 'top', null, $order, $icon);
            }

            if($user->hasPermission('admincp.security')){
                if(!$cache->isCached('security_order')){
                    $order = 9;
                    $cache->store('security_order', 9);
                } else {
                    $order = $cache->retrieve('security_order');
                }

                if(!$cache->isCached('security_icon')){
                    $icon = '<i class="nav-icon fas fa-lock"></i>';
                    $cache->store('security_icon', $icon);
                } else
                    $icon = $cache->retrieve('security_icon');

                $navs[2]->add('security', $language->get('admin', 'security'), URL::build('/panel/security'), 'top', null, $order, $icon);
            }

            if($user->hasPermission('admincp.update')){
                if(!$cache->isCached('update_order')){
                    $order = 10;
                    $cache->store('update_order', 10);
                } else {
                    $order = $cache->retrieve('update_order');
                }

                if(!$cache->isCached('update_icon')){
                    $icon = '<i class="nav-icon fas fa-download"></i>';
                    $cache->store('update_icon', $icon);
                } else
                    $icon = $cache->retrieve('update_icon');

                $navs[2]->add('update', $language->get('admin', 'update'), URL::build('/panel/update'), 'top', null, $order, $icon);
            }

            if($user->hasPermission('admincp.users')){
                if(!$cache->isCached('users_order')){
                    $order = 11;
                    $cache->store('users_order', 11);
                } else {
                    $order = $cache->retrieve('users_order');
                }

                if(!$cache->isCached('users_icon')){
                    $icon = '<i class="nav-icon fas fa-user-circle"></i>';
                    $cache->store('users_icon', $icon);
                } else
                    $icon = $cache->retrieve('users_icon');

                $navs[2]->addDropdown('users', $language->get('admin', 'user_management'), 'top', $order, $icon);

                if(!$cache->isCached('user_icon')){
                    $icon = '<i class="nav-icon fas fa-users"></i>';
                    $cache->store('user_icon', $icon);
                } else
                    $icon = $cache->retrieve('user_icon');

                $navs[2]->addItemToDropdown('users', 'users', $language->get('admin', 'users'), URL::build('/panel/users'), 'top', null, $icon, $order);

                if($user->hasPermission('modcp.ip_lookup')){
                    if(!$cache->isCached('ip_lookup_icon')){
                        $icon = '<i class="nav-icon fas fa-binoculars"></i>';
                        $cache->store('ip_lookup_icon', $icon);
                    } else
                        $icon = $cache->retrieve('ip_lookup_icon');

                    $navs[2]->addItemToDropdown('users', 'ip_lookup', $language->get('moderator', 'ip_lookup'), URL::build('/panel/users/ip_lookup'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('modcp.punishments')){
                    if(!$cache->isCached('punishments_icon')){
                        $icon = '<i class="nav-icon fas fa-gavel"></i>';
                        $cache->store('punishments_icon', $icon);
                    } else
                        $icon = $cache->retrieve('punishments_icon');

                    $navs[2]->addItemToDropdown('users', 'punishments', $language->get('moderator', 'punishments'), URL::build('/panel/users/punishments'), 'top', null, $icon, $order);
                }

                if($user->hasPermission('modcp.reports')){
                    if(!$cache->isCached('reports_icon')){
                        $icon = '<i class="nav-icon fas fa-exclamation-triangle"></i>';
                        $cache->store('reports_icon', $icon);
                    } else
                        $icon = $cache->retrieve('reports_icon');

                    $navs[2]->addItemToDropdown('users', 'reports', $language->get('moderator', 'reports'), URL::build('/panel/users/reports'), 'top', null, $icon, $order);
                }
            }

            // Notices
            $cache->setCache('notices_cache');

            // Email errors?
            if($user->hasPermission('admincp.core.emails')){
                if($cache->isCached('email_errors')){
                    $email_errors = $cache->retrieve('email_errors');
                } else {
                    $email_errors = $queries->getWhere('email_errors', array('id', '<>', 0));
                    $cache->store('email_errors', $email_errors, 120);
                }

                if(count($email_errors))
                    self::addNotice(URL::build('/panel/core/emails/errors'), $language->get('admin', 'email_errors_logged'));
            }

            if(defined('PANEL_PAGE') && PANEL_PAGE == 'dashboard'){
                // Dashboard graph
                $cache->setCache('dashboard_graph');
                if($cache->isCached('core_data')){
                    $data = $cache->retrieve('core_data');

                } else {
                    $users = $queries->orderWhere('users', 'joined > ' . strtotime("-1 week"), 'joined', 'ASC');

                    // Output array
                    $data = array();

                    $data['datasets']['users']['label'] = 'language/admin/registrations'; // for $language->get('admin', 'registrations');
                    $data['datasets']['users']['colour'] = '#0004FF';

                    foreach($users as $member){
                        // Turn into format for graph
                        // First, order them per day
                        $date = date('d M Y', $member->joined);
                        $date = '_' . strtotime($date);

                        if(isset($data[$date]['users'])){
                            $data[$date]['users'] = $data[$date]['users'] + 1;
                        } else {
                            $data[$date]['users'] = 1;
                        }
                    }

                    $users = null;

                    if(defined('MINECRAFT') && MINECRAFT){
                        $players = array();

                        $version = DB::getInstance()->query('select version()')->first()->{'version()'};

                        if(strpos(strtolower($version), 'mariadb') !== false){
                            $version = preg_replace('#[^0-9\.]#', '', $version);

                            if(version_compare($version, '10.1', '>=')){
                                try {
                                    $players = DB::getInstance()->query('SET STATEMENT MAX_STATEMENT_TIME = 1000 FOR SELECT ROUND(AVG(players_online)) AS players, DATE(FROM_UNIXTIME(queried_at)) AS `date` FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) IN (SELECT DATE(FROM_UNIXTIME(queried_at)) AS ForDate FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) > NOW() - INTERVAL 1 WEEK GROUP BY DATE(FROM_UNIXTIME(queried_at)) ORDER BY ForDate) GROUP BY DATE(FROM_UNIXTIME(queried_at))')->results();
                                } catch (Exception $e) {
                                    // Unable to obtain player count
                                    $player_count_error = true;
                                }
                            }
                        } else {
                            $version = preg_replace('#[^0-9\.]#', '', $version);

                            if(version_compare($version, '5.7.4', '>=') && version_compare($version, '5.7.8', '<')){
                                try {
                                    $players = DB::getInstance()->query('SELECT MAX_STATEMENT_TIME = 1000 ROUND(AVG(players_online)) AS players, DATE(FROM_UNIXTIME(queried_at)) AS `date` FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) IN (SELECT DATE(FROM_UNIXTIME(queried_at)) AS ForDate FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) > NOW() - INTERVAL 1 WEEK GROUP BY DATE(FROM_UNIXTIME(queried_at)) ORDER BY ForDate) GROUP BY DATE(FROM_UNIXTIME(queried_at))')->results();
                                } catch (Exception $e) {
                                    // Unable to obtain player count
                                    $player_count_error = true;
                                }
                            } else if(version_compare($version, '5.7.8', '>=')){
                                try {
                                    $players = DB::getInstance()->query('SELECT MAX_EXECUTION_TIME = 1000 ROUND(AVG(players_online)) AS players, DATE(FROM_UNIXTIME(queried_at)) AS `date` FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) IN (SELECT DATE(FROM_UNIXTIME(queried_at)) AS ForDate FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) > NOW() - INTERVAL 1 WEEK GROUP BY DATE(FROM_UNIXTIME(queried_at)) ORDER BY ForDate) GROUP BY DATE(FROM_UNIXTIME(queried_at))')->results();
                                } catch (Exception $e) {
                                    // Unable to obtain player count
                                    $player_count_error = true;
                                }
                            } else
                                $player_count_error = true;
                        }

                        if(!isset($player_count_error)){
                            $data['datasets']['players']['axis'] = 2; // second axis
                            $data['datasets']['players']['axis_side'] = 'right'; // right side
                            $data['datasets']['players']['label'] = 'language/admin/average_players';
                            $data['datasets']['players']['colour'] = '#ff0c00';

                            foreach($players as $player){
                                $date = '_' . strtotime($player->date);
                                $data[$date]['players'] = $player->players;
                            }

                            $players = null;
                        }
                    }

                    // Fill in missing dates, set registrations/players to 0
                    $start = strtotime("-1 week");
                    $start = date('d M Y', $start);
                    $start = strtotime($start);
                    $end = strtotime(date('d M Y'));
                    while($start <= $end){
                        if(!isset($data['_' . $start]['users']))
                            $data['_' . $start]['users'] = 0;

                        if(!isset($player_count_error) && defined('MINECRAFT') && MINECRAFT && !isset($data['_' . $start]['players']))
                            $data['_' . $start]['players'] = 0;

                        $start = strtotime('+1 day', $start);
                    }

                    // Sort by date
                    ksort($data);

                    $cache->store('core_data', $data, 120);
                }

                self::addDataToDashboardGraph($language->get('admin', 'overview'), $data);

                // Dashboard stats
                require_once(ROOT_PATH . '/modules/Core/collections/panel/TotalUsers.php');
                CollectionManager::addItemToCollection('dashboard_stats', new TotalUsersItem($smarty, $language, $cache));

                require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentUsers.php');
                CollectionManager::addItemToCollection('dashboard_stats', new RecentUsersItem($smarty, $language, $cache));

                // Dashboard items
                if($user->hasPermission('modcp.punishments')){
                    require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentPunishments.php');
                    CollectionManager::addItemToCollection('dashboard_main_items', new RecentPunishmentsItem($smarty, $language, $cache));
                }

                if($user->hasPermission('modcp.reports')){
                    require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentReports.php');
                    CollectionManager::addItemToCollection('dashboard_main_items', new RecentReportsItem($smarty, $language, $cache));
                }

                if($user->hasPermission('admincp.users')){
                    require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentRegistrations.php');
                    CollectionManager::addItemToCollection('dashboard_main_items', new RecentRegistrationsItem($smarty, $language, $cache));
                }
            }

            if($user->hasPermission('admincp.users.edit'))
                self::addUserAction($language->get('general', 'edit'), URL::build('/panel/users/edit/', 'id={id}'));

            if($user->hasPermission('modcp.ip_lookup'))
                self::addUserAction($language->get('moderator', 'ip_lookup'), URL::build('/panel/users/ip_lookup/', 'uid={id}'));

            if($user->hasPermission('modcp.punishments'))
                self::addUserAction($language->get('moderator', 'punish'), URL::build('/panel/users/punishments/', 'user={id}'));

            self::addUserAction($language->get('user', 'profile'), URL::build('/profile/{username}'));

            if($user->hasPermission('modcp.reports'))
                self::addUserAction($language->get('moderator', 'reports'), URL::build('/panel/users/reports/', 'uid={id}'));
        }

        require_once(ROOT_PATH . '/modules/Core/hooks/DeleteUserHook.php');
        HookHandler::registerHook('deleteUser', 'DeleteUserHook::deleteUser');
    }

    public static function addDataToDashboardGraph($title, $data){
        if(isset(self::$_dashboard_graph[$title]))
            self::$_dashboard_graph[$title] = array_merge_recursive(self::$_dashboard_graph[$title], $data);
        else
            self::$_dashboard_graph[$title] = $data;
    }

    public static function getDashboardGraphs(){
        return self::$_dashboard_graph;
    }

    public static function addNotice($url, $text){
        self::$_notices[$url] = $text;
    }

    public static function getNotices(){
        return self::$_notices;
    }

    public static function addUserAction($title, $link){
        self::$_user_actions[] = array('title' => $title, 'link' => $link);
    }

    public static function getUserActions(){
        $return = self::$_user_actions;

        uasort($return, function($a, $b){
            return $a['title'] > $b['title'];
        });

        return $return;
    }
}
