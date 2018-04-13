<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Core initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_core');
if(!$module_installed){
	// Hasn't been installed
	// Need to run the installer
	
	die('Run the installer first!');
	
} else {
	// Installed
}

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
    'admincp.core.terms' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('user', 'terms_and_conditions'),
    'admincp.minecraft' => $language->get('admin', 'minecraft'),
    'admincp.minecraft.authme' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'authme_integration'),
    'admincp.minecraft.verification' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'account_verification'),
    'admincp.minecraft.servers' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'minecraft_servers'),
    'admincp.minecraft.query_errors' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'query_errors'),
    'admincp.minecraft.banners' => $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'server_banners'),
    'admincp.modules' => $language->get('admin', 'modules'),
    'admincp.pages' => $language->get('admin', 'pages'),
    'admincp.security' => $language->get('admin', 'security'),
    'admincp.security.acp_logins' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'acp_logins'),
    'admincp.security.template' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'template_changes'),
    'admincp.security.all' => $language->get('admin', 'security') . ' &raquo; ' . $language->get('admin', 'all_logs'),
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
$pages->add('Core', '/admin/registration', 'pages/admin/registration.php');
$pages->add('Core', '/admin/security', 'pages/admin/security.php');
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
$pages->add('Core', '/banner', 'pages/minecraft/banner.php');
$pages->add('Core', '/terms', 'pages/terms.php');
$pages->add('Core', '/forgot_password', 'pages/forgot_password.php');
$pages->add('Core', '/complete_signup', 'pages/complete_signup.php');

// Hooks
HookHandler::registerEvent('registerUser', $language->get('admin', 'register_hook_info'));
HookHandler::registerEvent('validateUser', $language->get('admin', 'validate_hook_info'));

if(!isset($_GET['route']) || (isset($_GET['route']) && rtrim($_GET['route'], '/') != '/admin/update_execute')){
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

											$navigation->add($custom_page->id, $custom_page->icon . ' ' . Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order);
											break;
										case 2:
											// "More" dropdown
											$more[] = array('title' => $custom_page->icon . ' ' . Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect);
											break;
										case 3:
											// Footer
											$navigation->add($custom_page->id, $custom_page->icon . ' ' . Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', (is_null($redirect)) ? null : '_blank', 2000);
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

										$navigation->add($custom_page->id, $custom_page->icon . ' ' . Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'top', (is_null($redirect)) ? null : '_blank', $page_order);
										break;
									case 2:
										// "More" dropdown
										$more[] = array('title' => $custom_page->icon . ' ' . Output::getClean($custom_page->title), 'url' => (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'redirect' => $redirect);
										break;
									case 3:
										// Footer
										$navigation->add($custom_page->id, $custom_page->icon . ' ' . Output::getClean($custom_page->title), (is_null($redirect)) ? URL::build(Output::getClean($custom_page->url)) : $redirect, 'footer', (is_null($redirect)) ? null : '_blank', 2000);
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
			$navigation->addDropdown('more_dropdown', $language->get('general', 'more'), 'top', 2500);
			foreach($more as $item)
				$navigation->addItemToDropdown('more_dropdown', $item['title'], $item['title'], $item['url'], 'top', ($item['redirect']) ? '_blank' : null);
		}
	}
	$custom_pages = null;

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

	if($discord){
		$module_pages = $widgets->getPages('Discord');

		$widgets->add(new DiscordWidget($module_pages, $discord));
	}

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
        HookHandler::registerHook('validateUser', 'User::validatePromote');
        define('VALIDATED_DEFAULT', $validate_action['group']);
    }
}