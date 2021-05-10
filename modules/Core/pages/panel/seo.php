<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel seo page
 */

if(!$user->handlePanelPageLoad('admincp.core.seo')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'seo');
$page_title = $language->get('admin', 'seo');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$timeago = new Timeago(TIMEZONE);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$errors = array();
if (!is_dir(ROOT_PATH . '/cache/sitemaps')) {
    if (!is_writable(ROOT_PATH . '/cache')) {
        $errors[] = $language->get('admin', 'cache_not_writable');
    } else {
        mkdir(ROOT_PATH . '/cache/sitemaps');
        file_put_contents(ROOT_PATH . '/cache/sitemaps/.htaccess', 'Allow from all');
    }
}

if(!isset($_GET['metadata'])){
    // Deal with input
    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            if(Input::get('type') == 'sitemap') {
                require_once(ROOT_PATH . '/core/includes/sitemapphp/Sitemap.php');
                $sitemap = new SitemapPHP\Sitemap(rtrim(Util::getSelfURL(), '/'));
                $sitemap->setPath(ROOT_PATH . '/cache/sitemaps/');

                $methods = $pages->getSitemapMethods();
                if(count($methods)){
                    foreach($methods as $file => $method){
                        if(file_exists($file)){
                            require_once($file);

                            call_user_func($method, $sitemap, $cache);

                        } else
                            $errors[] = str_replace('{x}', Output::getClean($file), $language->get('admin', 'unable_to_load_sitemap_file_x'));
                    }
                }

                $sitemap->createSitemapIndex(rtrim(Util::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/');

                $cache->setCache('sitemap_cache');
                $cache->store('updated', date('d M Y, H:i'));

                $success = $language->get('admin', 'sitemap_generated');
            } else if(Input::get('type') == 'google_analytics') {
                $configuration->set('Core', 'ga_script', Input::get('analyticsid'));

                $success = $language->get('admin', 'settings_updated_successfully');
            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    if (!is_writable(ROOT_PATH . '/cache/sitemaps')) {
        $errors[] = $language->get('admin', 'sitemap_not_writable');
    } else {
        if (file_exists(ROOT_PATH . '/cache/sitemaps/sitemap-index.xml')) {
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

    $template_file = 'core/seo.tpl';
} else {
    $page = $pages->getPageById($_GET['metadata']);
    if(is_null($page)){
        Redirect::to(URL::build('/panel/core/seo'));
        die();
    }

    $page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));
    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            if(isset($_POST['description'])){
                if(strlen($_POST['description']) > 500){
                    $errors[] = $language->get('admin', 'description_max_500');
                } else {
                    $description = $_POST['description'];
                }
            } else
                $description = null;

            if(isset($_POST['keywords']))
                $keywords = $_POST['keywords'];
            else
                $keywords = null;

            if(!count($errors)){
                if(count($page_metadata)){
                    $page_id = $page_metadata[0]->id;

                    $queries->update('page_descriptions', $page_id, array(
                        'description' => $description,
                        'tags' => $keywords
                    ));

                } else {
                    $queries->create('page_descriptions', array(
                        'page' => $page['key'],
                        'description' => $description,
                        'tags' => $keywords
                    ));
                }

                $page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));

                $success = $language->get('admin', 'metadata_updated_successfully');

            }
        } else
            $errors[] = $language->get('general', 'invalid_token');
    }

    if(count($page_metadata)){
        $description = Output::getClean($page_metadata[0]->description);
        $tags = Output::getClean($page_metadata[0]->tags);
    } else {
        $description = '';
        $tags = '';
    }

    $smarty->assign(array(
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/panel/core/seo'),
        'EDITING_PAGE' => str_replace('{x}', Output::getClean($page['key']), $language->get('admin', 'editing_page_x')),
        'DESCRIPTION' => $language->get('admin', 'description'),
        'DESCRIPTION_VALUE' => $description,
        'KEYWORDS' => $language->get('admin', 'keywords'),
        'KEYWORDS_VALUE' => $tags
    ));

    $template_file = 'core/seo_metadata_edit.tpl';
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
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'SEO' => $language->get('admin', 'seo'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'GENERATE' => $language->get('admin', 'generate_sitemap'),
    'SUBMIT' => $language->get('general', 'submit'),
    'GOOGLE_ANALYTICS_VALUE' => $configuration->get('Core', 'ga_script'),
    'PAGE_TITLE' => $language->get('admin', 'page'),
    'PAGE_LIST' => $pages->returnPages(),
    'EDIT_LINK' => URL::build('/panel/core/seo/', 'metadata={x}'),
    'GOOGLE_ANALYTICS' => $language->get('admin', 'google_analytics'),
    'GOOGLE_ANALYTICS_HELP' => $language->get('admin', 'google_analytics_help'),
    'SUBMIT' => $language->get('general', 'submit'),
    'SITEMAP' => $language->get('admin', 'sitemap'),
    'PAGE_METADATA' => $language->get('admin', 'page_metadata'),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
