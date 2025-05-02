<?php
/**
 * NamelessMC homepage
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var array $front_page_modules TODO: replace with nicer system
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Always define page name
const PAGE = 'index';
$page_title = $language->get('general', 'home');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

$template->assets()->include([
    DARK_MODE
        ? AssetTree::PRISM_DARK
        : AssetTree::PRISM_LIGHT,
    AssetTree::TINYMCE_SPOILER,
]);

if (Session::exists('home')) {
    $template->getEngine()->addVariables([
        'HOME_SESSION_FLASH' => Session::flash('home'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (Session::exists('home_error')) {
    $template->getEngine()->addVariables([
        'HOME_SESSION_ERROR_FLASH' => Session::flash('home_error'),
        'ERROR_TITLE' => $language->get('general', 'error'),
    ]);
}

$home_type = Settings::get('home_type');
if ($home_type === '/') {
    $home_type = 'news';
}

if ($home_type === 'news') {
    foreach ($front_page_modules as $module) {
        require(ROOT_PATH . '/' . $module);
    }
} elseif ($home_type === 'custom') {
    $template->getEngine()->addVariable('CUSTOM_HOME_CONTENT', Settings::get('home_custom_content'));
}

// Assign to Smarty variables
$template->getEngine()->addVariables([
    'HOME_TYPE' => $home_type,
    'SOCIAL' => $language->get('general', 'social'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('index');
