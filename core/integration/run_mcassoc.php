<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  MCAssoc integration
 */

if (!defined('MCASSOC') ||
    !(isset($_POST['username']) || isset($_SESSION['mcassoc'])) ||
    !isset($mcassoc_site_id)) {
    die();
}

$page_title = $language->get('general', 'verify_account');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->assets()->include([
    AssetTree::MCASSOC_CLIENT,
]);

// Assign post data to session variable
if (!isset($_SESSION['mcassoc'])) {
    $_SESSION['mcassoc'] = $_POST;
}

$smarty->assign([
    'VERIFY_ACCOUNT' => $language->get('user', 'verify_account'),
    'VERIFY_ACCOUNT_HELP' => $language->get('user', 'verify_account_help')
]);

if (!isset($_GET['step'])) {
    // Step 1 - MCAssoc
    if ($custom_usernames == 'true') {
        if (isset($_SESSION['mcassoc']['mcname'])) {
            $username = $_SESSION['mcassoc']['mcname'];
        }
    } else {
        if (isset($_SESSION['mcassoc']['username'])) {
            $username = $_SESSION['mcassoc']['username'];
        }
    }

    $return_link = rtrim(Util::getSelfURL(), '/') . URL::build('/register/', 'step=2');
    $key = $mcassoc->generateKey($username);

    $smarty->assign('MCASSOC', '
      <center>
        <iframe id="mcassoc" width="100%" height="400" frameBorder="0" seamless scrolling="no"></iframe>
      </center>
    ');

    $template->addJSScript('
      MCAssoc.init("' . $mcassoc_site_id . '", "' . $key . '", "' . $return_link . '");
    ');

} else {
    if ($_GET['step'] == 2) {
        // Final step - verify data matches form
        if ($custom_usernames == 'true') {
            if (isset($_SESSION['mcassoc']['mcname'])) {
                $username = $_SESSION['mcassoc']['mcname'];
            }
        } else {
            if (isset($_SESSION['mcassoc']['username'])) {
                $username = $_SESSION['mcassoc']['username'];
            }
        }

        if (!isset($username)) {
            die('Session expired, please try again.');
        }

        $smarty->assign('STEP', 2);

        try {
            $data = $mcassoc->unwrapData($_POST['data']);

            if (!$data || $username != $data->username) {
                // Does not match MCAssoc
                $smarty->assign('ERROR', $language->get('user', 'verification_failed'));
                $smarty->assign('RETRY_LINK', URL::build('/register'));
                $smarty->assign('RETRY_TEXT', $language->get('general', 'register'));

                unset($_SESSION['mcassoc']);
            } else {
                // Matches
                // Register the account
                // Password (already hashed)
                $password = $_SESSION['password'];

                // Get IP
                $ip = Util::getRemoteAddress();

                $user->create([
                    'username' => $username,
                    'nickname' => $_SESSION['mcassoc']['username'],
                    'password' => $password,
                    'pass_method' => 'default',
                    'joined' => date('U'),
                    'email' => $_SESSION['mcassoc']['email'],
                    'active' => true,
                    'lastip' => $ip,
                    'last_online' => date('U')
                ]);

                $new_user = new User(DB::getInstance()->lastId());
                // TODO: which group should they be set to?
                // VALIDATED_DEFAULT
                // PRE_VALIDATED_DEFAULT
                $new_user->setGroup(1);

                $integration = Integrations::getInstance()->getIntegration('Minecraft');
                $integrationUser = new IntegrationUser($integration);
                $integrationUser->linkIntegration($new_user, htmlspecialchars($data->uuid), htmlspecialchars($username), true);

                unset($_SESSION['mcassoc']);

                $smarty->assign('SUCCESS_TITLE', $language->get('general', 'success'));
                $smarty->assign('SUCCESS', $language->get('user', 'verification_success'));
                $smarty->assign('LOGIN_LINK', URL::build('/login'));
                $smarty->assign('LOGIN_TEXT', $language->get('general', 'sign_in'));

            }
        } catch (Exception $e) {
            $smarty->assign('ERROR_TITLE', $language->get('general', 'error'));
            $smarty->assign('ERROR', $language->get('user', 'verification_failed') . ' - ' . $e->getMessage());
            $smarty->assign('RETRY_LINK', URL::build('/register'));
            $smarty->assign('RETRY_TEXT', $language->get('general', 'register'));

            unset($_SESSION['mcassoc']);
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('mcassoc.tpl', $smarty);
