<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Two-Factor Auth signin page
 *
 * @var Language $language
 * @var Smarty $smarty
 * @var User $user
 * @var Pages $pages
 * @var Cache $cache
 * @var array $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

// Two-Factor Auth sign in
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
} else if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
}

$_SESSION['password'] = $_POST['password'];
$_SESSION['remember'] = $_POST['remember'];
$_SESSION['tfa'] = true;

if (Session::exists('tfa_signin')) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERROR' => Session::flash('tfa_signin')
    ]);
}

// Smarty variables
$smarty->assign([
    'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
    'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
try {
    $template->displayTemplate('tfa.tpl', $smarty);
} catch (SmartyException $ignored) {
}
