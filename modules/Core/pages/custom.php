<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Custom page
 */

// Get page info from URL
$custom_page = $queries->getWhere('custom_pages', array('url', '=', rtrim($route, '/')));
if(!count($custom_page)){
    require(ROOT_PATH . '/404.php');
    die();
} else
    $custom_page = $custom_page[0];

// Check permissions
$perms = $queries->getWhere('custom_pages_permissions', array('page_id', '=', $custom_page->id));
if($user->isLoggedIn()){
    $groups = $user->getAllGroups();
    foreach($groups as $group){
        foreach($perms as $perm){
            if($perm->group_id == $group){
                if($perm->view == 1){
                    $can_view = 1;
                    break 2;
                } else
                    break;
            }
        }
    }
} else {
    foreach($perms as $perm){
        if($perm->group_id == 0){
            if($perm->view == 1)
                $can_view = 1;

            break;
        }
    }
}

if(!isset($can_view)){
    require(ROOT_PATH . '/403.php');
    die();
}

if($custom_page->redirect) {
	header("X-Robots-Tag: noindex, nofollow", true);
	header('Location: ' . Output::getClean($custom_page->link));

	die(str_replace('{x}', Output::getClean($custom_page->link), $language->get('general', 'redirecting_message')));
}

// Always define page name
define('PAGE', $custom_page->id);
define('CUSTOM_PAGE', $custom_page->title);
$page_title = Output::getClean($custom_page->title);
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$smarty->assign(array(
    'WIDGETS_LEFT' => $widgets->getWidgets('left'),
    'WIDGETS_RIGHT' => $widgets->getWidgets('right'),
	'CONTENT' => (($custom_page->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($custom_page->content)) : htmlspecialchars_decode($custom_page->content))
));

$template->addCSSFiles(array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array()
));

$template->addJSFiles(array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array()
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

if ($custom_page->basic) {
	$template->displayTemplate('custom_basic.tpl', $smarty);
} else {
	$template->displayTemplate('custom.tpl', $smarty);
}
