<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Two Factor Auth signin page
 */

// Two Factor Auth signin
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
} else if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
}

$_SESSION['password'] = $_POST['password'];
$_SESSION['remember'] = $_POST['remember'];
$_SESSION['tfa'] = true;

if (Session::exists('tfa_signin')) {
    $smarty->assign('ERROR', Session::flash('tfa_signin'));
}

// Smarty variables
$smarty->assign(
    array(
        'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
        'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit')
    )
);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('tfa.tpl', $smarty);
