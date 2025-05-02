<?php
/**
 * Cookie notice page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $cookie_language
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
const PAGE = 'cookies';
$page_title = $cookie_language->get('cookie', 'cookie_notice');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Retrieve cookie notice from database
$cookie_notice = DB::getInstance()->query('SELECT value FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->first()->value;

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->getEngine()->addVariables([
    'COOKIE_NOTICE_HEADER' => $cookie_language->get('cookie', 'cookie_notice'),
    'COOKIE_NOTICE' => Output::getPurified($cookie_notice),
    'UPDATE_SETTINGS' => $cookie_language->get('cookie', 'update_settings'),
]);

$template->addJSScript(file_get_contents(ROOT_PATH . '/modules/Cookie Consent/assets/js/configure.js'));

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('cookies');
