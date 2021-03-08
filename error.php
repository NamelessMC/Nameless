<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *  Error Handler inspired by facade/ignition
 *
 *  Error page
 */

// TODO: Ignore empty files (valet server.php, etc) + make look nice
// TODO: MAke it more consistent - if an error happens in index.php it wont catch
// TODO: Catch parseErrors?

if(!defined('ERRORHANDLER'))
    die();

if (!defined('LANGUAGE'))
    define('LANGUAGE', 'EnglishUK');

$language = new Language('core', LANGUAGE);
$user = new User();

if (defined('CONFIG_PATH')) {
    $path = CONFIG_PATH . '/'; 
} else {
    $path = '/';
}

$boostrap = $path . 'core/assets/css/bootstrap.min.css?' . time();
$custom = $path . 'core/assets/css/custom.css?' . time();
$font_awesome = $path . 'core/assets/css/font-awesome.min.css?' . time();
$jquery = $path . 'core/assets/js/jquery.min.js?' . time();
$prism_css = $path . 'core/assets/css/prism.css?' . time();
$prism_js = $path . 'core/assets/js/prism.js?' . time();

$smarty = new Smarty();

$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

$smarty->assign(array(
    'LANG' => defined('HTML_LANG') ? HTML_LANG : 'en',
    'RTL' => defined('HTML_RTL') && HTML_RTL === true ? ' dir="rtl"' : '',
    'LANG_CHARSET' => defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8',
    'TITLE' => $language->get('errors', 'fatal_error') . ' - ' . SITE_NAME,
    'SITE_NAME' => SITE_NAME,
    'BOOTSTRAP' => $boostrap,
    'CUSTOM' => $custom,
    'FONT_AWESOME' => $font_awesome,
    'JQUERY' => $jquery,
    'PRISM_CSS' => $prism_css,
    'PRISM_JS' => $prism_js,
    'DETAILED_ERROR' => $user->isLoggedIn() && $user->hasPermission('admincp.errors'),
    'FATAL_ERROR_TITLE' => $language->get('errors', 'fatal_error_title'),
    'FATAL_ERROR_MESSAGE_ADMIN' => $language->get('errors', 'fatal_error_message_admin'),
    'FATAL_ERROR_MESSAGE_USER' => $language->get('errors', 'fatal_error_message_user'),
    'ERROR_STRING' => Output::getClean($errstr),
    'FRAMES' => $frames,
    'BACK' => $language->get('general', 'back'),
    'HOME' => $language->get('general', 'home'),
    'HOME_URL' => URL::build('/')
));

$smarty->display(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.tpl');
