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
		$pages->add('Core', '/user', 'pages/user/index.php');
		$pages->add('Core', '/user/settings', 'pages/user/settings.php');
		$pages->add('Core', '/user/messaging', 'pages/user/messaging.php');
		$pages->add('Core', '/user/alerts', 'pages/user/alerts.php');
		$pages->add('Core', '/user/acknowledge', 'pages/user/acknowledge.php');
		$pages->add('Core', '/mod', 'pages/mod/index.php');
		$pages->add('Core', '/mod/punishments', 'pages/mod/punishments.php');
		$pages->add('Core', '/mod/reports', 'pages/mod/reports.php');
		$pages->add('Core', '/mod/ip_lookup', 'pages/mod/ip_lookup.php');
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

												$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order, $custom_page->icon);
												break;
											case 2:
												// "More" dropdown
												$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon);
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

											$navigation->add($custom_page->id, Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order, $custom_page->icon);
											break;
										case 2:
											// "More" dropdown
											$more[] = array('id' => $custom_page->id, 'title' => Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect, 'icon' => $custom_page->icon);
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

				$navigation->addDropdown('more_dropdown', $language->get('general', 'more'), 'top', 2500, $icon);
				foreach($more as $item)
					$navigation->addItemToDropdown('more_dropdown', $item['id'], $item['title'], $item['url'], 'top', ($item['redirect']) ? '_blank' : null, $item['icon']);
			}
		}
		$custom_pages = null;
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

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets){
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
			'admincp.users' => $language->get('admin', 'users'),
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

		// Hooks
		HookHandler::registerEvent('registerUser', $language->get('admin', 'register_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid'), 'avatar_url' => $language->get('user', 'avatar'), 'content' => $language->get('general', 'content'), 'url' => $language->get('user', 'profile')));
		HookHandler::registerEvent('validateUser', $language->get('admin', 'validate_hook_info'), array('user_id' => $language->get('admin', 'user_id'), 'username' => $language->get('user', 'username'), 'uuid' => $language->get('admin', 'uuid')));

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
}
