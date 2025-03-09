<?php
/**
 * Staff panel initialisation.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache        $cache
 * @var Language     $language
 * @var Navigation   $cc_nav
 * @var string       $page_title
 * @var TemplateBase $template
 * @var User         $user
 */
const BACK_END = true;

if (file_exists(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php')) {
    /** @var TemplateBase $template */
    require(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php');
} else {
    /** @var TemplateBase $template */
    require(ROOT_PATH . '/custom/panel_templates/Default/template.php');
}

$cache->setCache('backgroundcache');
$logo_image = $cache->retrieve('logo_image');

if (!empty($logo_image)) {
    $template->getEngine()->addVariable('PANEL_LOGO_IMAGE', Output::getClean($logo_image));
}

$favicon_image = $cache->retrieve('favicon_image');

if (!empty($favicon_image)) {
    $template->getEngine()->addVariable('FAVICON', Output::getClean($favicon_image));
}

$template->getEngine()->addVariables([
    'DARK_MODE_ENABLED' => defined('DARK_MODE') && DARK_MODE ? DARK_MODE : '0',
    'DARK_LIGHT_MODE_ACTION' => URL::build('/queries/dark_light_mode'),
    'DARK_LIGHT_MODE_TOKEN' => $user->isLoggedIn() ? Token::get() : null,
    'TITLE' => $page_title,
]);

// Initialise widgets
$widgets = new Widgets($cache, $language, $template);

// TODO: remove in 2.3.0
$smarty = new FakeSmarty($template->getEngine());
