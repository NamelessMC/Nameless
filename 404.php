<?php
/**
 * 404 not found.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache        $cache
 * @var FakeSmarty   $smarty
 * @var Language     $language
 * @var Navigation   $cc_nav
 * @var Navigation   $navigation
 * @var Navigation   $staffcp_nav
 * @var Pages        $pages
 * @var TemplateBase $template
 * @var User         $user
 * @var Widgets      $widgets
 */
header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

const PAGE = 404;
$page_title = '404';
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Assign template variables
$template->getEngine()->addVariables(
    [
        '404_TITLE' => $language->get('errors', '404_title'),
        'CONTENT' => $language->get('errors', '404_content'),
        'BACK' => $language->get('errors', '404_back'),
        'HOME' => $language->get('errors', '404_home'),
        'ERROR' => $language->get('errors', '404_error'),
        'PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : ''),
    ]
);

// Display template
$template->displayTemplate('404.tpl');
