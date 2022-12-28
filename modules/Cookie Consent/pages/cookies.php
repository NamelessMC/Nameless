<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Site cookies page
 *
 * @var Language $cookie_language
 * @var User $user
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var array $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

// Always define page name
const PAGE = 'cookies';
$page_title = $cookie_language->get('cookie', 'cookie_notice');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Retrieve cookie notice from database
$cookie_notice = DB::getInstance()->query('SELECT value FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->first()->value;

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$smarty->assign([
    'COOKIE_NOTICE_HEADER' => $cookie_language->get('cookie', 'cookie_notice'),
    'COOKIE_NOTICE' => Output::getPurified($cookie_notice),
    'UPDATE_SETTINGS' => $cookie_language->get('cookie', 'update_settings'),
]);

$template->addJSScript(file_get_contents(ROOT_PATH . '/modules/Cookie Consent/assets/js/configure.js'));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
try {
    $template->displayTemplate('cookies.tpl', $smarty);
} catch (SmartyException $ignored) {
}
