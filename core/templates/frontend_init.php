<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
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

if (PAGE != 404) {
    // Auto unset signin tfa variables if set
    if (strpos($_GET['route'], '/queries/') === false && (isset($_SESSION['remember']) || isset($_SESSION['username']) || isset($_SESSION['email']) || isset($_SESSION['password'])) && (!isset($_POST['tfa_code']) && !isset($_SESSION['mcassoc']))) {
        unset($_SESSION['remember']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
    }
}

$template_path = ROOT_PATH . '/custom/templates/' . TEMPLATE;
$smarty->setTemplateDir($template_path);
$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

if(file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php'))
	require(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php');
else
	require(ROOT_PATH . '/custom/templates/DefaultRevamp/template.php');

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
		} catch (Exception $e) {
			$default_group = 1;
		}

		$cache->store('default_group', $default_group);
	}
    
    $api_verification = $configuration->get('Core', 'api_verification');
	if($api_verification == 1 && in_array($default_group, $user->getAllGroupIds()) && ($user->data()->reset_code)) {
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

$logo_image = $cache->retrieve('logo_image');

if(!empty($logo_image))
    $smarty->assign('LOGO_IMAGE', Output::getClean($logo_image));

$favicon_image = $cache->retrieve('favicon_image');

if(!empty($favicon_image))
    $smarty->assign('FAVICON', Output::getClean($favicon_image));

$analytics_id = $configuration->get('Core', 'ga_script');
if ($analytics_id)
    $smarty->assign('ANALYTICS_ID', Output::getClean($analytics_id));

$smarty->assign(array(
    'FOOTER_LINKS_TITLE' => $language->get('general', 'links'),
    'FOOTER_SOCIAL_TITLE' => $language->get('general', 'social'),
    'DARK_LIGHT_MODE' => $language->get('admin', 'mode_toggle'),
    'DARK_LIGHT_MODE_ACTION' => URL::build('/queries/dark_light_mode'),
    'DARK_LIGHT_MODE_TOKEN' => $user->isLoggedIn() ? Token::get() : null
));
