<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  MCAssoc integration
 */

if(!defined('MCASSOC') || !(isset($_POST['username']) || isset($_SESSION['mcassoc'])) || !isset($mcassoc_site_id)) die();

// Assign post data to session variable
if(!isset($_SESSION['mcassoc'])) $_SESSION['mcassoc'] = $_POST;
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Verify Account">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
	  <meta name="robots" content="noindex">

    <?php
    $title = $language->get('user', 'verify_account');
    require('core/templates/header.php');
    ?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/client.js"></script>
	
  </head>
  
  <body>
  <?php
  // Generate navbar and footer
  require('core/templates/navbar.php');
  require('core/templates/footer.php');

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

    $return_link = "http://$_SERVER[HTTP_HOST]". strtok($_SERVER['REQUEST_URI'], '?') . '/?' . http_build_query(array(
            'step' => '2'
        ));
    $key = $mcassoc->generateKey($username);

    $smarty->assign('MCASSOC', '
	  <center>
	    <iframe id="mcassoc" width="100%" height="400" frameBorder="0" seamless scrolling="no"></iframe>
	  </center>
	  <script>
	  MCAssoc.init("' . $mcassoc_site_id . '", "' . $key . '", "' . $return_link . '");
	  </script>
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
                'group_id' => 1,
                'email' => htmlspecialchars($_SESSION['mcassoc']['email']),
                'active' => 1,
                'lastip' => htmlspecialchars($ip),
                'last_online' => date('U')
            ));

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

  // Display template
  $smarty->display('custom/templates/' . TEMPLATE . '/mcassoc.tpl');

  // Scripts
  require('core/templates/scripts.php');
	?>
  </body>
</html>