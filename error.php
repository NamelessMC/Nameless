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

// TODO: Test
// TODO: Translations in tpl

if (!defined('ERRORHANDLER')) {
    die();
}

if (!defined('LANGUAGE')) {
    define('LANGUAGE', 'EnglishUK');
}

$language = new Language('core', LANGUAGE);
$user = new User();

if (defined('CONFIG_PATH')) {
    $path = CONFIG_PATH . '/core/assets/'; 
} else {
    $path = '/core/assets/';
}

// TODO: remember to remove the time() (only here to stop browser from caching)
$boostrap = $path . 'css/bootstrap.min.css?' . time();
$custom = $path . 'css/custom.css?' . time();
$font_awesome = $path . 'css/font-awesome.min.css?' . time();
$jquery = $path . 'js/jquery.min.js?' . time();
$prism_css = $path . 'css/prism.css?' . time();
$prism_js = $path . 'js/prism.js?' . time();

$current_url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

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
    'ERROR_TYPE' => is_null($e) ? 'Error' : (new ReflectionClass($e))->getName(),
    'ERROR_STRING' => $error_string,
    'ERROR_FILE' => $error_file,
    'CURRENT_URL' => $current_url,
    'FRAMES' => $frames,
    'BACK' => $language->get('general', 'back'),
    'HOME' => $language->get('general', 'home'),
    'HOME_URL' => URL::build('/')
));

$smarty->display(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.tpl');
