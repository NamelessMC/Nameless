<?php
/**
 * 403 forbidden.
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
header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');

const PAGE = 403;
$page_title = '403';
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Assign template variables
$template->getEngine()->addVariables(
    [
        '403_TITLE' => $language->get('errors', '403_title'),
        'CONTENT' => $language->get('errors', '403_content'),
        'CONTENT_LOGIN' => $language->get('errors', '403_login'),
        'BACK' => $language->get('errors', '403_back'),
        'HOME' => $language->get('errors', '403_home'),
        'LOGIN' => $language->get('general', 'sign_in'),
        'LOGIN_LINK' => URL::build('/login'),
        'PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : ''),
    ]
);

// Display template
$template->displayTemplate('403.tpl');
