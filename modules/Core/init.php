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

// Define URLs which belong to this module
$pages->add('Core', '/', 'pages/index.php');
$pages->add('Core', '/contact', 'pages/contact.php');
$pages->add('Core', '/home', 'pages/home.php', 'index', true);
$pages->add('Core', '/admin', 'pages/admin/index.php');
$pages->add('Core', '/admin/auth', 'pages/admin/auth.php');
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
}