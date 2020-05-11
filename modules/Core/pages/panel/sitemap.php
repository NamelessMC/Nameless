<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel sitemap page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.sitemap')){
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'layout');
define('PANEL_PAGE', 'sitemap');
$page_title = $language->get('admin', 'sitemap');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$timeago = new Timeago(TIMEZONE);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

// Deal with input
if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		require_once(ROOT_PATH . '/core/includes/sitemapphp/Sitemap.php');
		$sitemap = new SitemapPHP\Sitemap(rtrim(Util::getSelfURL(), '/'));
		$sitemap->setPath(ROOT_PATH . '/cache/sitemaps/');

		$methods = $pages->getSitemapMethods();

		if(count($methods)){
			foreach($methods as $file => $method){
				if(file_exists($file)){
					require_once($file);

					call_user_func($method, $sitemap);

				} else
					$errors[] = str_replace('{x}', Output::getClean($file), $language->get('admin', 'unable_to_load_sitemap_file_x'));
			}
		}

		$sitemap->createSitemapIndex(rtrim(Util::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/');

		$cache->setCache('sitemap_cache');
		$cache->store('updated', date('d M Y, H:i'));

		$success = $language->get('admin', 'sitemap_generated');

	} else {
		$errors[] = $language->get('general', 'invalid_token');
	}
}

if(!is_dir(ROOT_PATH . '/cache/sitemaps')){
	if(!is_writable(ROOT_PATH . '/cache')){
		$errors = array($language->get('admin', 'cache_not_writable'));

	} else {
		mkdir(ROOT_PATH . '/cache/sitemaps');
		file_put_contents(ROOT_PATH . '/cache/sitemaps/.htaccess', 'Allow from all');
	}
}

if(!is_writable(ROOT_PATH . '/cache/sitemaps')){
	$errors = array($language->get('admin', 'sitemap_not_writable'));

} else {
	if(file_exists(ROOT_PATH . '/cache/sitemaps/sitemap-index.xml')){
		$cache->setCache('sitemap_cache');
		if($cache->isCached('updated')){
			$updated = $cache->retrieve('updated');
			$updated = $timeago->inWords($updated, $language->getTimeLanguage());
		} else
			$updated = $language->get('admin', 'unknown');

		$smarty->assign(array(
			'SITEMAP_LAST_GENERATED' => str_replace('{x}', $updated, $language->get('admin', 'sitemap_last_generated_x')),
			'SITEMAP_LINK' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/sitemap-index.xml',
			'SITEMAP_FULL_LINK' => rtrim(Util::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/sitemap-index.xml',
			'DOWNLOAD_SITEMAP' => $language->get('admin', 'download_sitemap'),
			'LINK' => $language->get('admin', 'sitemap_link')
		));

	} else {
		$smarty->assign('SITEMAP_NOT_GENERATED', $language->get('admin', 'sitemap_not_generated_yet'));
	}
}

if(isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'LAYOUT' => $language->get('admin', 'layout'),
	'SITEMAP' => $language->get('admin', 'sitemap'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'GENERATE' => $language->get('admin', 'generate_sitemap')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/sitemap.tpl', $smarty);