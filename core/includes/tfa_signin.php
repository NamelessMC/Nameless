<?php
/**
 * Two-factor auth sign-in page.
 *
 * TODO: move this file
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
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
} else {
    if (isset($_POST['email'])) {
        $_SESSION['email'] = $_POST['email'];
    }
}

$_SESSION['password'] = $_POST['password'];
$_SESSION['remember'] = $_POST['remember'];
$_SESSION['tfa'] = true;

if (Session::exists('tfa_signin')) {
    $template->getEngine()->addVariables([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERROR' => Session::flash('tfa_signin'),
    ]);
}

// Template variables
$template->getEngine()->addVariables([
    'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
    'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('tfa');
