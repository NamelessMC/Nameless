<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  403 Forbidden page
 */

header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');

const PAGE = 403;
$page_title = '403';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Assign Smarty variables
$smarty->assign(
    [
        '403_TITLE' => $language->get('errors', '403_title'),
        'CONTENT' => $language->get('errors', '403_content'),
        'CONTENT_LOGIN' => $language->get('errors', '403_login'),
        'BACK' => $language->get('errors', '403_back'),
        'HOME' => $language->get('errors', '403_home'),
        'LOGIN' => $language->get('general', 'sign_in'),
        'LOGIN_LINK' => URL::build('/login'),
        'PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : '')
    ]
);

// Display template
$template->displayTemplate('403.tpl', $smarty);
