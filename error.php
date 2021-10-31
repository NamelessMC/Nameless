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

$current_url = 'http' . (($_SERVER['SERVER_PORT'] == 443) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$smarty = new Smarty();

$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

$smarty->assign([
    'LANG' => defined('HTML_LANG') ? HTML_LANG : 'en',
    'RTL' => defined('HTML_RTL') && HTML_RTL === true ? ' dir="rtl"' : '',
    'LANG_CHARSET' => defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8',
    'TITLE' => $language->get('errors', 'fatal_error') . ' - ' . SITE_NAME,
    'SITE_NAME' => SITE_NAME,
    'BOOTSTRAP' => $path . 'css/bootstrap.min.css',
    'BOOTSTRAP_JS' => $path . 'js/bootstrap.min.js',
    'CUSTOM' => $path . 'css/custom.css',
    'FONT_AWESOME' => $path . 'css/font-awesome.min.css',
    'JQUERY' => $path . 'js/jquery.min.js',
    'PRISM_CSS' => $path . 'plugins/prism/prism.css',
    'PRISM_JS' => $path . 'plugins/prism/prism.js',
    'DETAILED_ERROR' => defined('DEBUGGING') || ($user->isLoggedIn() && $user->hasPermission('admincp.errors')),
    'FATAL_ERROR_TITLE' => $language->get('errors', 'fatal_error_title'),
    'FATAL_ERROR_MESSAGE_ADMIN' => $language->get('errors', 'fatal_error_message_admin'),
    'FATAL_ERROR_MESSAGE_USER' => $language->get('errors', 'fatal_error_message_user'),
    'ERROR_TYPE' => is_null($exception) ? $language->get('general', 'error') : (new ReflectionClass($exception))->getName(),
    'ERROR_STRING' => $error_string,
    'ERROR_FILE' => $error_file,
    'DEBUG_LINK' => $language->get('admin', 'debug_link'),
    'DEBUG_LINK_URL' => URL::build('/queries/debug_link'),
    'ERROR_SQL_STACK' =>  QueryRecorder::getInstance()->getSqlStack(),
    'CURRENT_URL' => $current_url,
    'FRAMES' => $frames,
    'SKIP_FRAMES' => $skip_frames,
    'BACK' => $language->get('general', 'back'),
    'HOME' => $language->get('general', 'home'),
    'HOME_URL' => URL::build('/')
]);

$smarty->display(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.tpl');
