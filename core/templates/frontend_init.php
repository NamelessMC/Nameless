<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Frontend template initialisation
 */

define('FRONT_END', true);

// Set current page URL in session, provided it's not the login page
if(defined('PAGE') && PAGE != 'login' && PAGE != 'register' && PAGE != 404 && PAGE != 'maintenance' && (!isset($_GET['route']) || strpos($_GET['route'], '/queries') === false)){
	if(FRIENDLY_URLS === true){
		$split = explode('?', $_SERVER['REQUEST_URI']);

		if(count($split) > 1)
			$_SESSION['last_page'] = URL::build($split[0], $split[1]);
		else
			$_SESSION['last_page'] = URL::build($split[0]);

		if(defined('CONFIG_PATH'))
			$_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));

	} else
		$_SESSION['last_page'] = URL::build($_GET['route']);

}

$template_path = ROOT_PATH . '/custom/templates/' . TEMPLATE;
$smarty->setTemplateDir($template_path);
$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

if(file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php'))
	require(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php');
else
	require(ROOT_PATH . '/custom/templates/Default/template.php');

// User related actions
if($user->isLoggedIn()){
	// Warnings
	$warnings = $queries->getWhere('infractions', array('punished', '=', $user->data()->id));
	if(count($warnings)){
		foreach($warnings as $warning){
			if($warning->revoked == 0 && $warning->acknowledged == 0){
				$smarty->assign(array(
					'GLOBAL_WARNING_TITLE' => $language->get('user', 'you_have_received_a_warning'),
					'GLOBAL_WARNING_REASON' => Output::getClean($warning->reason),
					'GLOBAL_WARNING_ACKNOWLEDGE' => $language->get('user', 'acknowledge'),
					'GLOBAL_WARNING_ACKNOWLEDGE_LINK' => URL::build('/user/acknowledge/' . $warning->id)
				));
				break;
			}
		}
	}

	// Does the account need verifying?
	// Get default group ID
	$cache->setCache('default_group');
	if($cache->isCached('default_group')) {
		$default_group = $cache->retrieve('default_group');
	} else {
		try {
			$default_group = $queries->getWhere('groups', array('default_group', '=', 1));
			$default_group = $default_group[0]->id;
		} catch(Exception $e){
			$default_group = 1;
		}

		$cache->store('default_group', $default_group);
	}
	if($user->data()->group_id == $default_group && ($user->data()->reset_code)){
		// User needs to validate account
		$smarty->assign('MUST_VALIDATE_ACCOUNT', str_replace('{x}', Output::getClean($user->data()->reset_code), $language->get('user', 'validate_account_command')));
	}
}

// Page metadata
if(isset($_GET['route']) && $_GET['route'] != '/'){
	$route = rtrim($_GET['route'], '/');
} else {
	$route = '/';
}

if(!defined('PAGE_DESCRIPTION')){
	$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $route));
	if(count($page_metadata)){
		$smarty->assign(array(
			'PAGE_DESCRIPTION' => str_replace('{site}', SITE_NAME, $page_metadata[0]->description),
			'PAGE_KEYWORDS' => $page_metadata[0]->tags
		));
	}
} else {
	$smarty->assign(array(
		'PAGE_DESCRIPTION' => str_replace('{site}', SITE_NAME, PAGE_DESCRIPTION),
		'PAGE_KEYWORDS' => (defined('PAGE_KEYWORDS') ? PAGE_KEYWORDS : '')
	));
}

$smarty->assign('TITLE', $page_title);

// Background?
$cache->setCache('backgroundcache');
$background_image = $cache->retrieve('background_image');

if(!empty($background_image)){
	$template->addCSSStyle('
			body {
				background-image: url(\'' . Output::getClean($background_image) . '\');
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-size: cover;
			}
			');
}

$banner_image = $cache->retrieve('banner_image');

if(!empty($banner_image))
	$smarty->assign('BANNER_IMAGE', Output::getClean($banner_image));