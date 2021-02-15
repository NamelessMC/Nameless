<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  MCAssoc integration
 */

if(!defined('MCASSOC') || !(isset($_POST['username']) || isset($_SESSION['mcassoc'])) || !isset($mcassoc_site_id)) die();

$page_title = $language->get('general', 'verify_account');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addJSFiles(array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/js/client.js' => array()
));

// Assign post data to session variable
if(!isset($_SESSION['mcassoc'])) $_SESSION['mcassoc'] = $_POST;

$smarty->assign(array(
	'VERIFY_ACCOUNT' => $language->get('user', 'verify_account'),
	'VERIFY_ACCOUNT_HELP' => $language->get('user', 'verify_account_help')
));

if(!isset($_GET['step'])){
	// Step 1 - MCAssoc
	if($custom_usernames == 'true'){
		if(isset($_SESSION['mcassoc']['mcname'])){
			$username = $_SESSION['mcassoc']['mcname'];
		}
	} else {
		if(isset($_SESSION['mcassoc']['username'])){
			$username = $_SESSION['mcassoc']['username'];
		}
	}

	$return_link = Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/register/', 'step=2');
	$key = $mcassoc->generateKey($username);

	$smarty->assign('MCASSOC', '
	  <center>
	    <iframe id="mcassoc" width="100%" height="400" frameBorder="0" seamless scrolling="no"></iframe>
	  </center>
    ');

	$template->addJSFiles(array((defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/js/client.js' => array()));
	$template->addJSScript('
	  MCAssoc.init("' . $mcassoc_site_id . '", "' . $key . '", "' . $return_link . '");
	');

} else if($_GET['step'] == 2){
	// Final step - verify data matches form
	if($custom_usernames == 'true'){
		if(isset($_SESSION['mcassoc']['mcname'])){
			$username = $_SESSION['mcassoc']['mcname'];
		}
	} else {
		if(isset($_SESSION['mcassoc']['username'])){
			$username = $_SESSION['mcassoc']['username'];
		}
	}

	if(!isset($username)) die('Session expired, please try again.');

	$smarty->assign('STEP', 2);

	try {
		$data = $mcassoc->unwrapData($_POST['data']);

		if(!$data || $username != $data->username){
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
			$ip = $user->getIP();

			$user->create(array(
				'username' => htmlspecialchars($username),
				'nickname' => htmlspecialchars($_SESSION['mcassoc']['username']),
				'uuid' => htmlspecialchars($data->uuid),
				'password' => $password,
				'pass_method' => 'default',
				'joined' => date('U'),
				'email' => htmlspecialchars($_SESSION['mcassoc']['email']),
				'active' => 1,
				'lastip' => htmlspecialchars($ip),
				'last_online' => date('U')
            ));
            
            $new_user = new User(DB::getInstance()->lastId());
            // TODO: which group should they be set to? 
            // VALIDATED_DEFAULT
            // PRE_VALIDATED_DEFAULT
            $new_user->setGroup(1);

			unset($_SESSION['mcassoc']);

			$smarty->assign('SUCCESS', $language->get('user', 'verification_success'));
			$smarty->assign('LOGIN_LINK', URL::build('/login'));
			$smarty->assign('LOGIN_TEXT', $language->get('general', 'sign_in'));

		}
	} catch (Exception $e) {
		$smarty->assign('ERROR', $language->get('user', 'verification_failed') . ' - ' . $e->getMessage());
		$smarty->assign('RETRY_LINK', URL::build('/register'));
		$smarty->assign('RETRY_TEXT', $language->get('general', 'register'));

		unset($_SESSION['mcassoc']);
	}
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('mcassoc.tpl', $smarty);
