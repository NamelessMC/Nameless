<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Core module file
 */

class Core_Module extends Module {
	private $_language;
<<<<<<< HEAD
=======
	private static $_dashboard_graph = array(), $_notices = array();
>>>>>>> upstream/v2

	public function __construct($language, $pages, $user, $queries, $navigation, $cache){
		$this->_language = $language;

		$name = 'Core';
		$author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
		$module_version = '2.0.0-pr5';
		$nameless_version = '2.0.0-pr5';

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('Core', '/', 'pages/index.php');
		$pages->add('Core', '/api/v1', 'pages/api/v1/index.php');
		$pages->add('Core', '/api/v2', 'pages/api/v2/index.php');
		$pages->add('Core', '/contact', 'pages/contact.php');
		$pages->add('Core', '/home', 'pages/home.php', 'index', true);
<<<<<<< HEAD
=======

		$pages->add('Core', '/login', 'pages/login.php');
		$pages->add('Core', '/logout', 'pages/logout.php');
		$pages->add('Core', '/profile', 'pages/profile.php', 'profile', true);
		$pages->add('Core', '/register', 'pages/register.php');
		$pages->add('Core', '/validate', 'pages/validate.php');
		$pages->add('Core', '/queries/alerts', 'queries/alerts.php');
		$pages->add('Core', '/queries/pms', 'queries/pms.php');
		$pages->add('Core', '/queries/servers', 'queries/servers.php');
		$pages->add('Core', '/queries/server', 'queries/server.php');
		$pages->add('Core', '/queries/user', 'queries/user.php');
		$pages->add('Core', '/banner', 'pages/minecraft/banner.php');
		$pages->add('Core', '/terms', 'pages/terms.php');
		$pages->add('Core', '/privacy', 'pages/privacy.php');
		$pages->add('Core', '/forgot_password', 'pages/forgot_password.php');
		$pages->add('Core', '/complete_signup', 'pages/complete_signup.php');
		$pages->add('Core', '/status', 'pages/status.php');

		$pages->add('Core', '/user', 'pages/user/index.php');
		$pages->add('Core', '/user/settings', 'pages/user/settings.php');
		$pages->add('Core', '/user/messaging', 'pages/user/messaging.php');
		$pages->add('Core', '/user/alerts', 'pages/user/alerts.php');
		$pages->add('Core', '/user/acknowledge', 'pages/user/acknowledge.php');

		// Panel
		$pages->add('Core', '/panel', 'pages/panel/index.php');
		$pages->add('Core', '/panel/auth', 'pages/panel/auth.php');
		$pages->add('Core', '/panel/core/general_settings', 'pages/panel/general_settings.php');
		$pages->add('Core', '/panel/core/api', 'pages/panel/api.php');
		$pages->add('Core', '/panel/core/avatars', 'pages/panel/avatars.php');
		$pages->add('Core', '/panel/core/profile_fields', 'pages/panel/profile_fields.php');
		$pages->add('Core', '/panel/core/debugging_and_maintenance', 'pages/panel/debugging_and_maintenance.php');
		$pages->add('Core', '/panel/core/errors', 'pages/panel/errors.php');
		$pages->add('Core', '/panel/core/emails', 'pages/panel/emails.php');
		$pages->add('Core', '/panel/core/emails/errors', 'pages/panel/emails_errors.php');
		$pages->add('Core', '/panel/core/navigation', 'pages/panel/navigation.php');
		$pages->add('Core', '/panel/core/privacy_and_terms', 'pages/panel/privacy_and_terms.php');
		$pages->add('Core', '/panel/core/reactions', 'pages/panel/reactions.php');
		$pages->add('Core', '/panel/core/registration', 'pages/panel/registration.php');
		$pages->add('Core', '/panel/core/social_media', 'pages/panel/social_media.php');
		$pages->add('Core', '/panel/core/groups', 'pages/panel/groups.php');
		$pages->add('Core', '/panel/core/templates', 'pages/panel/templates.php');
		$pages->add('Core', '/panel/core/sitemap', 'pages/panel/sitemap.php');
		$pages->add('Core', '/panel/core/widgets', 'pages/panel/widgets.php');
		$pages->add('Core', '/panel/core/modules', 'pages/panel/modules.php');
		$pages->add('Core', '/panel/core/pages', 'pages/panel/pages.php');
		$pages->add('Core', '/panel/core/metadata', 'pages/panel/metadata.php');

>>>>>>> upstream/v2
		$pages->add('Core', '/admin', 'pages/admin/index.php');
		$pages->add('Core', '/admin/auth', 'pages/admin/auth.php');
		$pages->add('Core', '/admin/api', 'pages/admin/api.php');
		$pages->add('Core', '/admin/core', 'pages/admin/core.php');
		$pages->add('Core', '/admin/groups', 'pages/admin/groups.php');
		$pages->add('Core', '/admin/images', 'pages/admin/images.php');
		$pages->add('Core', '/admin/minecraft', 'pages/admin/minecraft.php');
		$pages->add('Core', '/admin/modules', 'pages/admin/modules.php');
		$pages->add('Core', '/admin/pages', 'pages/admin/pages.php');
		$pages->add('Core', '/admin/metadata', 'pages/admin/metadata.php');
		$pages->add('Core', '/admin/registration', 'pages/admin/registration.php');
		$pages->add('Core', '/admin/security', 'pages/admin/security.php');
		$pages->add('Core', '/admin/sitemap', 'pages/admin/sitemap.php');
		$pages->add('Core', '/admin/styles', 'pages/admin/styles.php');
		$pages->add('Core', '/admin/users', 'pages/admin/users.php');
		$pages->add('Core', '/admin/update', 'pages/admin/update.php');
		$pages->add('Core', '/admin/update_execute', 'pages/admin/update_execute.php');
		$pages->add('Core', '/admin/update_uuids', 'pages/admin/update_uuids.php');
		$pages->add('Core', '/admin/update_mcnames', 'pages/admin/update_mcnames.php');
		$pages->add('Core', '/admin/reset_password', 'pages/admin/reset_password.php');
		$pages->add('Core', '/admin/night_mode', 'pages/admin/night_mode.php');
		$pages->add('Core', '/admin/widgets', 'pages/admin/widgets.php');
<<<<<<< HEAD
		$pages->add('Core', '/user', 'pages/user/index.php');
		$pages->add('Core', '/user/settings', 'pages/user/settings.php');
		$pages->add('Core', '/user/messaging', 'pages/user/messaging.php');
		$pages->add('Core', '/user/alerts', 'pages/user/alerts.php');
		$pages->add('Core', '/user/acknowledge', 'pages/user/acknowledge.php');
=======

>>>>>>> upstream/v2
		$pages->add('Core', '/mod', 'pages/mod/index.php');
		$pages->add('Core', '/mod/punishments', 'pages/mod/punishments.php');
		$pages->add('Core', '/mod/reports', 'pages/mod/reports.php');
		$pages->add('Core', '/mod/ip_lookup', 'pages/mod/ip_lookup.php');
<<<<<<< HEAD
		$pages->add('Core', '/login', 'pages/login.php');
		$pages->add('Core', '/logout', 'pages/logout.php');
		$pages->add('Core', '/profile', 'pages/profile.php', 'profile', true);
		$pages->add('Core', '/register', 'pages/register.php');
		$pages->add('Core', '/validate', 'pages/validate.php');
		$pages->add('Core', '/queries/alerts', 'queries/alerts.php');
		$pages->add('Core', '/queries/pms', 'queries/pms.php');
		$pages->add('Core', '/queries/servers', 'queries/servers.php');
		$pages->add('Core', '/queries/server', 'queries/server.php');
		$pages->add('Core', '/banner', 'pages/minecraft/banner.php');
		$pages->add('Core', '/terms', 'pages/terms.php');
		$pages->add('Core', '/privacy', 'pages/privacy.php');
		$pages->add('Core', '/forgot_password', 'pages/forgot_password.php');
		$pages->add('Core', '/complete_signup', 'pages/complete_signup.php');
		$pages->add('Core', '/status', 'pages/status.php');
=======
>>>>>>> upstream/v2

		// Ajax GET requests
		$pages->addAjaxScript(URL::build('/queries/servers'));

		// Custom pages
		$custom_pages = $queries->getWhere('custom_pages', array('id', '<>', 0));
		if(count($custom_pages)){
			$more = array();
			$cache->setCache('navbar_order');

			if($user->isLoggedIn()){
				// Check all groups
				$user_groups = $user->getAllGroups($user->data()->id);

				foreach($custom_pages as $custom_page){
					$redirect = null;
					foreach($user_groups as $user_group){
						$custom_page_permissions = $queries->getWhere('custom_pages_permissions', array('group_id', '=', $user_group));
						if(count($custom_page_permissions)){
							foreach($custom_page_permissions as $permission){
								if($permission->page_id == $custom_page->id){
									if($permission->view == 1){
										// Get redirect URL if enabled
										if($custom_page->redirect == 1){
											$redirect = Output::getClean($custom_page->link);
										} else
											$pages->addCustom(Output::getClean($custom_page->url), Output::getClean($custom_page->title), false);

<<<<<<< HEAD
										switch($custom_page->link_location){
											case 1:
												// Navbar
												// Check cache first
												if(!$cache->isCached($custom_page->id . '_order')){
													// Create cache entry now
													$page_order = 200;
													$cache->store($custom_page->id . '_order', 200);
												} else {
													$page_order = $cache->retrieve($custom_page->id . '_order');
												}

=======
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
>>>>>>> upstream/v2
												$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order, $custom_page->icon);
												break;
											case 2:
												// "More" dropdown
<<<<<<< HEAD
												$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon);
=======
												$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon, 'order' => $page_order);
>>>>>>> upstream/v2
												break;
											case 3:
												// Footer
												$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', (is_null($redirect)) ? null : '_blank', 2000, $custom_page->icon);
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
						foreach($custom_page_permissions as $permission){
							if($permission->page_id == $custom_page->id){
								if($permission->view == 1){
									if($custom_page->redirect == 1){
										$redirect = Output::getClean($custom_page->link);
									} else
										$pages->addCustom(Output::getClean($custom_page->url), Output::getClean($custom_page->title), FALSE);

<<<<<<< HEAD
									switch($custom_page->link_location){
										case 1:
											// Navbar
											// Check cache first
											if(!$cache->isCached($custom_page->id . '_order')){
												// Create cache entry now
												$page_order = 200;
												$cache->store($custom_page->id . '_order', 200);
											} else {
												$page_order = $cache->retrieve($custom_page->id . '_order');
											}

=======
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
>>>>>>> upstream/v2
											$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order, $custom_page->icon);
											break;
										case 2:
											// "More" dropdown
<<<<<<< HEAD
											$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon);
=======
											$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon, 'order' => $page_order);
>>>>>>> upstream/v2
											break;
										case 3:
											// Footer
											$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', (is_null($redirect)) ? null : '_blank', 2000, $custom_page->icon);
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
				$cache->setCache('navbar_icons');
				if($cache->isCached('more_dropdown_icon')){
					$icon = $cache->retrieve('more_dropdown_icon');
				} else
					$icon = '';

<<<<<<< HEAD
				$navigation->addDropdown('more_dropdown', $language->get('general', 'more'), 'top', 2500, $icon);
				foreach($more as $item)
					$navigation->addItemToDropdown('more_dropdown', $item['id'], $item['title'], $item['url'], 'top', ($item['redirect']) ? '_blank' : null, $item['icon']);
			}
		}
		$custom_pages = null;
=======
				$cache->setCache('navbar_order');
				if($cache->isCached('more_dropdown_order')){
					$order = $cache->retrieve('more_dropdown_order');
				} else
					$order = 2500;

				$navigation->addDropdown('more_dropdown', $language->get('general', 'more'), 'top', $order, $icon);
				foreach($more as $item)
					$navigation->addItemToDropdown('more_dropdown', $item['id'], $item['title'], $item['url'], 'top', ($item['redirect']) ? '_blank' : null, $item['icon'], $item['order']);
			}
		}
		$custom_pages = null;

		// Hooks
		HookHandler::registerEvent('registerUser', $language->get('admin', 'register_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid'), 'avatar_url' => $language->get('user', 'avatar'), 'content' => $language->get('general', 'content'), 'url' => $language->get('user', 'profile')));
		HookHandler::registerEvent('validateUser', $language->get('admin', 'validate_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid')));

		// Discord hook
		require_once(ROOT_PATH . '/modules/Core/hooks/DiscordHook.php');
		$cache->setCache('discord_hook');
		if($cache->isCached('events')){
			$events = $cache->retrieve('events');
			if(is_array($events) && count($events)){
				foreach($events as $event){
					HookHandler::registerHook($event, 'DiscordHook::execute');
				}
			}
		}
		if($cache->isCached('url'))
			DiscordHook::setURL($cache->retrieve('url'));
>>>>>>> upstream/v2
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

<<<<<<< HEAD
	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets){
=======
	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template){
>>>>>>> upstream/v2
		$language = $this->_language;

		// Permissions
		// AdminCP
		PermissionHandler::registerPermissions('AdminCP', array(
			'admincp.core' => $language->get('admin', 'core'),
			'admincp.core.api' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'api'),
			'admincp.core.general' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'general_settings'),
			'admincp.core.avatars' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'avatars'),
			'admincp.core.fields' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'custom_fields'),
			'admincp.core.debugging' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'debugging_and_maintenance'),
			'admincp.errors' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'debugging_and_maintenance') . ' &raquo; ' . $language->get('admin', 'error_logs'),
			'admincp.core.emails' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'emails'),
			'admincp.core.navigation' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'navigation'),
			'admincp.core.reactions' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('user', 'reactions'),
			'admincp.core.registration' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'registration'),
			'admincp.core.social_media' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'social_media'),
			'admincp.core.terms' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'privacy_and_terms'),
			'admincp.minecraft' => $language->get('admin', 'minecraft'),
			'admincp.minecraft.authme' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'authme_integration'),
			'admincp.minecraft.verification' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'account_verification'),
			'admincp.minecraft.servers' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'minecraft_servers'),
			'admincp.minecraft.query_errors' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'query_errors'),
			'admincp.minecraft.banners' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'server_banners'),
			'admincp.modules' => $language->get('admin', 'modules'),
			'admincp.pages' => $language->get('admin', 'pages'),
			'admincp.pages.metadata' => $language->get('admin', 'pages') . ' &raquo; ' . $language->get('admin', 'page_metadata'),
			'admincp.security' => $language->get('admin', 'security'),
			'admincp.security.acp_logins' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'acp_logins'),
			'admincp.security.template' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'template_changes'),
			'admincp.security.all' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'all_logs'),
			'admincp.sitemap' => $language->get('admin', 'sitemap'),
			'admincp.styles' => $language->get('admin', 'styles'),
			'admincp.styles.templates' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'templates'),
			'admincp.styles.templates.edit' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'templates') . ' &raquo; ' . $language->get('general', 'edit'),
			'admincp.styles.images' => $language->get('admin', 'styles') . ' &raquo; ' . $language->get('admin', 'images'),
			'admincp.update' => $language->get('admin', 'update'),
<<<<<<< HEAD
			'admincp.users' => $language->get('admin', 'users'),
=======
			'admincp.users' => $language->get('admin', 'user_management'),
>>>>>>> upstream/v2
			'admincp.groups' => $language->get('admin', 'groups'),
			'admincp.groups.self' => $language->get('admin', 'groups') . ' &raquo; ' . $language->get('admin', 'can_edit_own_group'),
			'admincp.widgets' => $language->get('admin', 'widgets')
		));

		// ModCP
		PermissionHandler::registerPermissions('ModCP', array(
			'modcp.ip_lookup' => $language->get('moderator', 'ip_lookup'),
			'modcp.punishments' => $language->get('moderator', 'punishments'),
			'modcp.punishments.warn' => $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'warn_user'),
			'modcp.punishments.ban' => $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'ban_user'),
			'modcp.punishments.banip' => $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'ban_ip'),
			'modcp.punishments.revoke' => $language->get('moderator', 'punishments') . ' &raquo; ' . $language->get('moderator', 'revoke'),
			'modcp.reports' => $language->get('moderator', 'reports')
		));

		// UserCP
		PermissionHandler::registerPermissions('UserCP', array(
			'usercp.messaging' => $language->get('user', 'messaging'),
			'usercp.signature' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'signature'),
			'usercp.private_profile' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'private_profile'),
			'usercp.nickname' => $language->get('user', 'profile_settings') . ' &raquo; ' . $language->get('user', 'nickname')
		));

		// Profile Page
		PermissionHandler::registerPermissions('Profile', array(
			'profile.private.bypass' => $language->get('general', 'bypass') . ' &raquo; ' . $language->get('user', 'private_profile')
		));

<<<<<<< HEAD
		// Hooks
		HookHandler::registerEvent('registerUser', $language->get('admin', 'register_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid'), 'avatar_url' => $language->get('user', 'avatar'), 'content' => $language->get('general', 'content'), 'url' => $language->get('user', 'profile')));
		HookHandler::registerEvent('validateUser', $language->get('admin', 'validate_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid')));

=======
>>>>>>> upstream/v2
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

		// Online staff
		require_once(ROOT_PATH . '/modules/Core/widgets/OnlineStaff.php');
		$module_pages = $widgets->getPages('Online Staff');
		$widgets->add(new OnlineStaffWidget($module_pages, $smarty, array('title' => $language->get('general', 'online_staff'), 'no_online_staff' => $language->get('general', 'no_online_staff')), $cache));

		// Online users
		require_once(ROOT_PATH . '/modules/Core/widgets/OnlineUsers.php');
		$module_pages = $widgets->getPages('Online Users');
		$widgets->add(new OnlineUsersWidget($module_pages, $cache, $smarty, array('title' => $language->get('general', 'online_users'), 'no_online_users' => $language->get('general', 'no_online_users'))));

<<<<<<< HEAD
		// Discord hook
		require_once(ROOT_PATH . '/modules/Core/hooks/DiscordHook.php');
		$cache->setCache('discord_hook');
		if($cache->isCached('events')){
			$events = $cache->retrieve('events');
			if(count($events)){
				foreach($events as $event){
					HookHandler::registerHook($event, 'DiscordHook::execute');
				}
			}
		}
		if($cache->isCached('url'))
			DiscordHook::setURL($cache->retrieve('url'));

=======
>>>>>>> upstream/v2
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

<<<<<<< HEAD
=======
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

>>>>>>> upstream/v2
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

								if($result['total_count'] == 1){
									$result['status_full'] = $language->get('general', 'currently_1_player_online');
									$result['x_players_online'] = $language->get('general', 'currently_1_player_online');
								} else {
									$result['status_full'] = str_replace('{x}', $result['total_count'], $language->get('general', 'currently_x_players_online'));
									$result['x_players_online'] = str_replace('{x}', $result['total_count'], $language->get('general', 'currently_x_players_online'));
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
					$smarty->assign('CONNECT_WITH', str_replace('{x}', '<span id="ip">' . Output::getClean($default->ip . ($default->port != 25565 ? ':' . $default->port : '')) . '</span>', $language->get('general', 'connect_with_ip_x')));
					$smarty->assign('DEFAULT_IP', Output::getClean($default->ip . ($default->port != 25565 ? ':' . $default->port : '')));
					$smarty->assign('CLICK_TO_COPY_TOOLTIP', $language->get('general', 'click_to_copy_tooltip'));
					$smarty->assign('COPIED', $language->get('general', 'copied'));
				} else {
					$smarty->assign('CONNECT_WITH', '');
					$smarty->assign('DEFAULT_IP', '');
				}

				$smarty->assign('SERVER_OFFLINE', $language->get('general', 'server_offline'));

<<<<<<< HEAD
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

		} else {

		}
	}
=======
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

					$navs[2]->addItemToDropdown('core_configuration', 'general_settings', $language->get('admin', 'general_settings'), URL::build('/panel/core/general_settings'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.api')){
					if(!$cache->isCached('api_icon')){
						$icon = '<i class="nav-icon fas fa-code"></i>';
						$cache->store('api_icon', $icon);
					} else
						$icon = $cache->retrieve('api_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'api', $language->get('admin', 'api'), URL::build('/panel/core/api'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.avatars')){
					if(!$cache->isCached('avatars_icon')){
						$icon = '<i class="nav-icon fas fa-image"></i>';
						$cache->store('avatars_icon', $icon);
					} else
						$icon = $cache->retrieve('avatars_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'avatars', $language->get('admin', 'avatars'), URL::build('/panel/core/avatars'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.fields')){
					if(!$cache->isCached('custom_profile_fields_icon')){
						$icon = '<i class="nav-icon fas fa-id-card"></i>';
						$cache->store('custom_profile_fields_icon', $icon);
					} else
						$icon = $cache->retrieve('custom_profile_fields_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'custom_profile_fields', $language->get('admin', 'custom_fields'), URL::build('/panel/core/profile_fields'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.debugging')){
					if(!$cache->isCached('debugging_icon')){
						$icon = '<i class="nav-icon fas fa-tachometer-alt"></i>';
						$cache->store('debugging_icon', $icon);
					} else
						$icon = $cache->retrieve('debugging_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'debugging_and_maintenance', $language->get('admin', 'debugging_and_maintenance'), URL::build('/panel/core/debugging_and_maintenance'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.emails')){
					if(!$cache->isCached('email_icon')){
						$icon = '<i class="nav-icon fas fa-envelope"></i>';
						$cache->store('email_icon', $icon);
					} else
						$icon = $cache->retrieve('email_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'emails', $language->get('admin', 'emails'), URL::build('/panel/core/emails'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.navigation')){
					if(!$cache->isCached('navigation_icon')){
						$icon = '<i class="nav-icon fas fa-bars"></i>';
						$cache->store('navigation_icon', $icon);
					} else
						$icon = $cache->retrieve('navigation_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'navigation', $language->get('admin', 'navigation'), URL::build('/panel/core/navigation'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.terms')){
					if(!$cache->isCached('privacy_and_terms_icon')){
						$icon = '<i class="nav-icon fas fa-file-alt"></i>';
						$cache->store('privacy_and_terms_icon', $icon);
					} else
						$icon = $cache->retrieve('privacy_and_terms_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'privacy_and_terms', $language->get('admin', 'privacy_and_terms'), URL::build('/panel/core/privacy_and_terms'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.reactions')){
					if(!$cache->isCached('reactions_icon')){
						$icon = '<i class="nav-icon fas fa-smile"></i>';
						$cache->store('reactions_icon', $icon);
					} else
						$icon = $cache->retrieve('reactions_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'reactions', $language->get('user', 'reactions'), URL::build('/panel/core/reactions'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.registration')){
					if(!$cache->isCached('registration_icon')){
						$icon = '<i class="nav-icon fas fa-user-plus"></i>';
						$cache->store('registration_icon', $icon);
					} else
						$icon = $cache->retrieve('registration_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'registration', $language->get('admin', 'registration'), URL::build('/panel/core/registration'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.core.social_media')){
					if(!$cache->isCached('social_media_icon')){
						$icon = '<i class="nav-icon fas fa-users"></i>';
						$cache->store('social_media_icon', $icon);
					} else
						$icon = $cache->retrieve('social_media_icon');

					$navs[2]->addItemToDropdown('core_configuration', 'social_media', $language->get('admin', 'social_media'), URL::build('/panel/core/social_media'), 'top', $order, $icon);
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

			if($user->hasPermission('admincp.styles')){
				if(!$cache->isCached('layout_order')){
					$order = 4;
					$cache->store('layout_order', 4);
				} else {
					$order = $cache->retrieve('layout_order');
				}

				if(!$cache->isCached('layout_icon')){
					$icon = '<i class="nav-icon fas fa-object-group"></i>';
					$cache->store('layout_icon', $icon);
				} else
					$icon = $cache->retrieve('layout_icon');

				$navs[2]->addDropdown('layout', $language->get('admin', 'layout'), 'top', $order, $icon);

				if(!$cache->isCached('templates_icon')){
					$icon = '<i class="nav-icon fas fa-paint-brush"></i>';
					$cache->store('templates_icon', $icon);
				} else
					$icon = $cache->retrieve('templates_icon');

				$navs[2]->addItemToDropdown('layout', 'template', $language->get('admin', 'templates'), URL::build('/panel/core/templates'), 'top', $order, $icon);

				if(!$cache->isCached('widgets_icon')){
					$icon = '<i class="nav-icon fas fa-th"></i>';
					$cache->store('widgets_icon', $icon);
				} else
					$icon = $cache->retrieve('widgets_icon');

				$navs[2]->addItemToDropdown('layout', 'widgets', $language->get('admin', 'widgets'), URL::build('/panel/core/widgets'), 'top', $order, $icon);
			}

			if($user->hasPermission('admincp.modules')){
				if(!$cache->isCached('modules_icon')){
					$icon = '<i class="nav-icon fas fa-puzzle-piece"></i>';
					$cache->store('modules_icon', $icon);
				} else
					$icon = $cache->retrieve('modules_icon');

				$navs[2]->add('modules', $language->get('admin', 'modules'), URL::build('/panel/core/modules'), 'top', null, $order, $icon);
			}

			if($user->hasPermission('admincp.pages') || $user->hasPermission('admincp.pages.metadata')){
				if(!$cache->isCached('pages_icon')){
					$icon = '<i class="nav-icon fas fa-file"></i>';
					$cache->store('pages_icon', $icon);
				} else
					$icon = $cache->retrieve('pages_icon');

				$navs[2]->addDropdown('pages', $language->get('admin', 'pages'), 'top', $order, $icon);

				if($user->hasPermission('admincp.pages')){
					if(!$cache->isCached('custom_pages_icon')){
						$icon = '<i class="nav-icon fas fa-file-alt"></i>';
						$cache->store('custom_pages_icon', $icon);
					} else
						$icon = $cache->retrieve('custom_pages_icon');

					$navs[2]->addItemToDropdown('pages', 'custom_pages', $language->get('admin', 'custom_pages'), URL::build('/panel/core/pages'), 'top', $order, $icon);
				}

				if($user->hasPermission('admincp.pages.metadata')){
					if(!$cache->isCached('page_metadata_icon')){
						$icon = '<i class="nav-icon fas fa-tag"></i>';
						$cache->store('page_metadata_icon', $icon);
					} else
						$icon = $cache->retrieve('page_metadata_icon');

					$navs[2]->addItemToDropdown('pages', 'page_metadata', $language->get('admin', 'page_metadata'), URL::build('/panel/core/metadata'), 'top', $order, $icon);
				}
			}

			if($user->hasPermission('admincp.users')){
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

				$navs[2]->addItemToDropdown('users', 'users', $language->get('admin', 'users'), URL::build('/panel/core/users'), 'top', $order, $icon);
			}

			if($user->hasPermission('admincp.sitemap')){
				if(!$cache->isCached('sitemap_icon')){
					$icon = '<i class="nav-icon fas fa-sitemap"></i>';
					$cache->store('sitemap_icon', $icon);
				} else
					$icon = $cache->retrieve('sitemap_icon');

				$navs[2]->add('sitemap', $language->get('admin', 'sitemap'), URL::build('/panel/core/sitemap'), 'top', null, $order, $icon);
			}

			// Notices
			$cache->setCache('notices_cache');

			// Email errors?
			if($cache->isCached('email_errors')){
				$email_errors = $cache->retrieve('email_errors');
			} else {
				$email_errors = $queries->getWhere('email_errors', array('id', '<>', 0));
				$cache->store('email_errors', $email_errors, 120);
			}

			if(count($email_errors))
				self::addNotice(URL::build('/panel/core/emails/errors'), $language->get('admin', 'email_errors_logged'));

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
						$players = DB::getInstance()->query('SELECT ROUND(AVG(players_online)) AS players, DATE(FROM_UNIXTIME(queried_at)) AS `date` FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) IN (SELECT DATE(FROM_UNIXTIME(queried_at)) AS ForDate FROM nl2_query_results WHERE DATE(FROM_UNIXTIME(queried_at)) > NOW() - INTERVAL 1 WEEK GROUP BY DATE(FROM_UNIXTIME(queried_at)) ORDER BY ForDate) GROUP BY DATE(FROM_UNIXTIME(queried_at))')->results();

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

					// Fill in missing dates, set registrations/players to 0
					$start = strtotime("-1 week");
					$start = date('d M Y', $start);
					$start = strtotime($start);
					$end = strtotime(date('d M Y'));
					while($start <= $end){
						if(!isset($data['_' . $start]['users']))
							$data['_' . $start]['users'] = 0;

						if(defined('MINECRAFT') && MINECRAFT && !isset($data['_' . $start]['players']))
							$data['_' . $start]['players'] = 0;

						$start = $start + 86400;
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
					CollectionManager::addItemToCollection('dashboard_main_items', new RecentPunishmentsItem($smarty, $language, $cache, $user));
				}

				if($user->hasPermission('modcp.reports')){
					require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentReports.php');
					CollectionManager::addItemToCollection('dashboard_main_items', new RecentReportsItem($smarty, $language, $cache, $user));
				}

				if($user->hasPermission('admincp.users')){
					require_once(ROOT_PATH . '/modules/Core/collections/panel/RecentRegistrations.php');
					CollectionManager::addItemToCollection('dashboard_main_items', new RecentRegistrationsItem($smarty, $language, $cache, $user));
				}
			}
		}
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
>>>>>>> upstream/v2
}
