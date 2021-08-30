<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel forums page
 */

// Can the user view the panel?
if(!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'forum');
define('PANEL_PAGE', 'forum_settings');
$page_title = $forum_language->get('forum', 'forums');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
		// Update link location
        if(isset($_POST['link_location'])){
            switch($_POST['link_location']){
                case 1:
                case 2:
                case 3:
                case 4:
                    $location = $_POST['link_location'];
                break;
                default:
                    $location = 1;
            }
        } else
            $location = 1;
											
        // Update Link location cache
        $cache->setCache('nav_location');
        $cache->store('forum_location', $location);
        
        // Update reactions value
        if (isset($_POST['use_reactions']) && $_POST['use_reactions'] == 'on') $use_reactions = 1;
        else $use_reactions = 0;
        
        $configuration->set('Core', 'forum_reactions', $use_reactions);
        
        Session::flash('admin_forums_settings', $forum_language->get('forum', 'settings_updated_successfully'));
        Redirect::to(URL::build('/panel/forums/settings'));
        die();
    } else {
        // Invalid token
        Session::flash('admin_forums_settings', $language->get('general', 'invalid_token'));
        Redirect::to(URL::build('/panel/forums/settings'));
        die();
    }
}

// Retrieve Link Location from cache
$cache->setCache('nav_location');
$link_location = $cache->retrieve('forum_location');

// Retrieve reactions value
$use_reactions = $configuration->get('Core', 'forum_reactions');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('admin_forums_settings'))
    $success = Session::flash('admin_forums_settings');

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'FORUM' => $forum_language->get('forum', 'forum'),
    'SETTINGS' => $language->get('admin', 'settings'),
	'LINK_LOCATION' => $language->get('admin', 'page_link_location'),
	'LINK_LOCATION_VALUE' => $link_location,
	'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
	'LINK_MORE' => $language->get('admin', 'page_link_more'),
	'LINK_FOOTER' => $language->get('admin', 'page_link_footer'),
	'LINK_NONE' => $language->get('admin', 'page_link_none'),
    'USE_REACTIONS' => $forum_language->get('forum', 'use_reactions'),
    'USE_REACTIONS_VALUE' => ($use_reactions == 1),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('forum/forums_settings.tpl', $smarty);