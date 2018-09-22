<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Backend template initialisation
 */

define('BACK_END', true);

// Set current page URL in session, provided it's not the login page
if(defined('PAGE') && PAGE != 'login' && PAGE != 404){
	if(FRIENDLY_URLS === true){
		$split = explode('?', $_SERVER['REQUEST_URI']);

		if(count($split) > 1)
			$_SESSION['last_page'] = URL::build($split[0], $split[1]);
		else
			$_SESSION['last_page'] = URL::build($split[0]);
	} else
		$_SESSION['last_page'] = URL::build($_GET['route']);

	if(defined('CONFIG_PATH'))
		$_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));
}

$template_path = ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE;
$smarty->setTemplateDir($template_path);
$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

if(file_exists(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php'))
	require(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php');
else
	require(ROOT_PATH . '/custom/panel_templates/Default/template.php');

$smarty->assign('TITLE', $page_title);