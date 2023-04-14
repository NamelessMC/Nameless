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

if (!$user->handlePanelPageLoad('admincp.core.seo')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'seo';
$page_title = $language->get('admin', 'seo');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$timeago = new TimeAgo(TIMEZONE);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$errors = [];
if (!is_dir(ROOT_PATH . '/cache/sitemaps')) {
    if (!is_writable(ROOT_PATH . '/cache')) {
        $errors[] = $language->get('admin', 'cache_not_writable');
    } else {
        mkdir(ROOT_PATH . '/cache/sitemaps');
        file_put_contents(ROOT_PATH . '/cache/sitemaps/.htaccess', 'Allow from all');
    }
}

if (!isset($_GET['metadata'])) {
    // Deal with input
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (Input::get('type') == 'sitemap') {

                $sitemap = new \SitemapPHP\Sitemap(rtrim(URL::getSelfURL(), '/'));
                $sitemap->setPath(ROOT_PATH . '/cache/sitemaps/');

                $methods = $pages->getSitemapMethods();
                foreach ($methods as $method) {
                    if (!class_exists($method[0])) {
                        $errors[] = $language->get('admin', 'unable_to_load_sitemap_file_x', ['file' => Output::getClean($method[0])]);
                        continue;
                    }

                    $method($sitemap, $cache);
                }

                $sitemap->createSitemapIndex(rtrim(URL::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/');

                $cache->setCache('sitemap_cache');
                $cache->store('updated', date(DATE_FORMAT));

                $success = $language->get('admin', 'sitemap_generated');
            } else {
                if (Input::get('type') == 'google_analytics') {
                    Util::setSetting('ga_script', Input::get('analyticsid'));
                    $success = $language->get('admin', 'seo_settings_updated_successfully');
                }
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
            if ($cache->isCached('updated')) {
                $updated = $cache->retrieve('updated');
                $updated = $timeago->inWords($updated, $language);
            } else {
                $updated = $language->get('admin', 'unknown');
            }

            $smarty->assign([
                'SITEMAP_LAST_GENERATED' => $language->get('admin', 'sitemap_last_generated_x', [
                    'generatedAt' => Text::bold($updated)
                ]),
                'SITEMAP_LINK' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/sitemap-index.xml',
                'SITEMAP_FULL_LINK' => rtrim(URL::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/sitemap-index.xml',
                'DOWNLOAD_SITEMAP' => $language->get('admin', 'download_sitemap'),
                'LINK' => $language->get('admin', 'sitemap_link')
            ]);

        } else {
            $smarty->assign('SITEMAP_NOT_GENERATED', $language->get('admin', 'sitemap_not_generated_yet'));
        }
    }

    $template_file = 'core/seo.tpl';
} else {
    $page = $pages->getPageById($_GET['metadata']);
    if (is_null($page)) {
        Redirect::to(URL::build('/panel/core/seo'));
    }

    $template->assets()->include(
        AssetTree::IMAGE_PICKER,
    );

    $page_metadata = DB::getInstance()->get('page_descriptions', ['page', $page['key']])->results();
    if (Input::exists()) {
        if (Token::check()) {
            if (isset($_POST['description'])) {
                if (strlen($_POST['description']) > 500) {
                    $errors[] = $language->get('admin', 'description_max_500');
                } else {
                    $description = $_POST['description'];
                }
            } else {
                $description = null;
            }

            $keywords = $_POST['keywords'] ?? null;

            if (Input::get('inputImage')) {
                $image_url = ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/og_images/' . Input::get('inputImage');
            } else {
                $image_url = '';
            }

            if (!count($errors)) {
                if (count($page_metadata)) {
                    $page_id = $page_metadata[0]->id;

                    DB::getInstance()->update('page_descriptions', $page_id, [
                        'description' => $description,
                        'tags' => $keywords,
                        'image' => $image_url,
                    ]);

                } else {
                    DB::getInstance()->insert('page_descriptions', [
                        'page' => $page['key'],
                        'description' => $description,
                        'tags' => $keywords,
                        'image' => $image_url,
                    ]);
                }

                $page_metadata = DB::getInstance()->get('page_descriptions', ['page', $page['key']])->results();

                $success = $language->get('admin', 'metadata_updated_successfully');

            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    if (count($page_metadata)) {
        $description = Output::getClean($page_metadata[0]->description);
        $tags = Output::getClean($page_metadata[0]->tags);
        $og_image = Output::getClean($page_metadata[0]->image);
    } else {
        $description = '';
        $tags = '';
        $og_image = '';
    }

    $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'og_images']);
    $images = scandir($image_path);
    $og_images = [];
    $n = 1;
    foreach ($images as $image) {
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            continue;
        }
        $og_images[] = [
            'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/og_images/' . $image,
            'value' => $image,
            'selected' => ($og_image === (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/og_images/' . $image),
            'n' => $n
        ];
        $n++;
    }

    $smarty->assign([
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/panel/core/seo'),
        'EDITING_PAGE' => $language->get('admin', 'editing_page_x', [
            'page' => Text::bold(Output::getClean($page['key']))
        ]),
        'DESCRIPTION' => $language->get('admin', 'description'),
        'DESCRIPTION_VALUE' => $description,
        'KEYWORDS' => $language->get('admin', 'keywords'),
        'KEYWORDS_VALUE' => $tags,
        'IMAGE' => $language->get('admin', 'image'),
        'OG_IMAGES_ARRAY' => $og_images,
    ]);

    $template_file = 'core/seo_metadata_edit.tpl';
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'SEO' => $language->get('admin', 'seo'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'GENERATE' => $language->get('admin', 'generate_sitemap'),
    'GOOGLE_ANALYTICS_VALUE' => Util::getSetting('ga_script'),
    'PAGE_TITLE' => $language->get('admin', 'page'),
    'PAGE_LIST' => $pages->returnPages(),
    'EDIT_LINK' => URL::build('/panel/core/seo/', 'metadata={x}'),
    'GOOGLE_ANALYTICS' => $language->get('admin', 'google_analytics'),
    'GOOGLE_ANALYTICS_HELP' => $language->get('admin', 'google_analytics_help'),
    'SUBMIT' => $language->get('general', 'submit'),
    'SITEMAP' => $language->get('admin', 'sitemap'),
    'PAGE_METADATA' => $language->get('admin', 'page_metadata'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
